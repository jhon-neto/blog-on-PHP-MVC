<?php

namespace src\controllers;

use core\Controller;
use src\models\Course;
use src\models\Classes;
use src\models\CourseToStudent;
use src\Config;
use src\models\Log;

class CronController extends Controller
{
    private $today;

    public function __construct()
    {
        date_default_timezone_set('America/Sao_Paulo');
        $this->today = date('Y-m-d ');
        ini_set('display_errors', 0);
        error_reporting(E_ALL);
    }

    /**
     * @return void
     */
    public function addClasses($args)
    {
        if ($args['token'] !== Config::TOKEN) {
            echo 'acesso negado!';
            exit;
        }
        if ($args['day']) {
            $this->today = date('Y-m-d ', strtotime($args['day']));
        }

        $days = [
            'Sun' => 'Domingo',
            'Mon' => 'Segunda-feira', 
            'Tue' => 'Terça-feira', 
            'Wed' => 'Quarta-feira', 
            'Thu' => 'Quinta-feira', 
            'Fri' => 'Sexta-feira', 
            'Sat' => 'Sábado'
        ];
        $today = $days[date('D', strtotime($this->today))];
        $daySql = '%' . $today . '%';

        $course = Course::select()->where('finished', 0)->where('schedule', 'LIKE', $daySql)->get();

        foreach ($course as $c) {
            $sch = json_decode($c['schedule'], true);

            foreach ($sch as $schedule) {
                if ($schedule['day'] == $today) {
                    $this->findStudent($c, $schedule);
                }
            }
        }

        echo 'Concluído!';
        Log::insert(['log' => 'Aulas salvas em: ' . $this->today])->execute();
    }

    /**
     * @param $course
     * @param $schedule
     * @return void
     */
    protected function findStudent($course, $schedule)
    {   //$x = Classes::select()->where('curso_id', $course['id'])->where('data_aula_inicio', 'LIKE', $this->today)->get(); $this->dd($x);

        $std = CourseToStudent::select('aluno_id')->where('curso_id', $course['id'])->get();
        if (!std) {
            die('Nenhum aluno encontrado');
        }
        foreach ($std as $st) {
            if (!Classes::select()->where([
                'curso_id' => $course['id'],
                'aluno_id' => $st['aluno_id']
            ])
                ->where('data_aula_inicio', 'LIKE', $this->today . '%')
                ->one()) {
                $data = [
                    'curso_id' => $course['id'],
                    'professor_id' => $course['teacher_id'],
                    'conta_id' => $course['account_id'],
                    'aluno_id' => $st['aluno_id'],
                    'data_aula_inicio' => $this->today . $schedule['in'] . ':00',
                    'data_aula_termino' => $this->today . $schedule['out'] . ':00'
                ];

                $this->addNewClasses($st, $data, $schedule);
            }
        }
    }

    protected function addNewClasses($std, $data, $schedule)
    {
        echo "Salvou: Aluno = " . $std['aluno_id'] . 
            ' curso: ' . $data['curso_id'] . 
            ' dia: ' . $schedule['day'] . 
            ' de: ' . $schedule['in'] . 
            ' ate: ' . $schedule['out'] . '<br>';

        $data['aluno_id'] = $std['aluno_id'];
        Classes::insert($data)->execute();
        // $date = date('Y-m-d ', strtotime('+1 week'));
        // $data['data_aula_inicio'] = $date . $schedule['in'] . ':00';
        // $data['data_aula_termino'] = $date . $schedule['out'] . ':00';
        // Classes::insert($data)->execute();
        
    }
}
