<?php

namespace src\controllers;


use \core\Controller;
use \src\models\Classes;
use \src\models\Course;
use src\models\Teacher;
use src\models\Account;
use src\models\Student;
use src\models\CourseToStudent;

class CLassesController extends Controller
{
    private $lastWeek;

    private $today;

    public function __construct()
    {
        session_start();
        if (!$_SESSION['logou']) {
            $this->redirect('/');
            exit;
        }
        date_default_timezone_set('America/Sao_Paulo');
        ini_set('display_errors', 0);
        error_reporting(E_ALL);
        $this->lastWeek = date('Y-m-d 00:00:00', strtotime('-1 week'));
        $this->today = date('Y-m-d H:i:s');
    }

    public function index()
    { 
        $month = filter_input(INPUT_GET, 'month');
        $type = filter_input(INPUT_GET, 'type');

        if($_SESSION['permission'] == 'Professor') {
            $c = array_column(Course::select('id')->where('teacher_id', $_SESSION['id'])->get(), 'id');              
            $ids = array_column(CourseToStudent::select()->where('curso_id', 'in', $c)->get(), 'aluno_id');
            $cl = Classes::select()->where('professor_id', $_SESSION['id'])->where('aluno_id', 'in', $ids);
        } else if($_SESSION['permission'] == 'Aluno') {
            $cl = Classes::select()->where('aluno_id', $_SESSION['id']);
        } else if($_SESSION['permission'] == 'Empresa') {
            $cl = Classes::select()->where('conta_id', $_SESSION['id']);
        } else {
            $cl = Classes::select();
        }

        $params = [];

        if($month) {
            $cl->where('data_aula_inicio', 'LIKE', $month . '%');
            $params['month'] = $month;
        }
        if($type) {
            if($type == 'passadas') {
                $cl->where('data_aula_inicio', '<', $this->today);
                $params['type'] = 'passadas';
            }
            if ($type == 'concluded') {
                $cl->where('status', 'Concluida');
                $params['type'] = 'concluded';
            }
        } elseif (!$month) {
            if($_SESSION['permission'] == 'Aluno' || $_SESSION['permission'] == 'Empresa') {
                $cl->where('data_aula_inicio', '>', $this->today);
            } else {
                $cl->where('data_aula_inicio', '>=', $this->lastWeek);
            }
            $params['type'] = 'future';
        }

        $c = $cl->orderBy('data_aula_inicio', 'desc')
            ->get();

        $days = ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'];

        for($x = 0; $x < count($c); $x++) {
            $c[$x]['teacher'] = Teacher::select('nome_completo')->find($c[$x]['professor_id']);
            $c[$x]['course'] = Course::select(['course_name', 'lang', 'course_type'])->find($c[$x]['curso_id']);
            if($_SESSION['permission'] != 'Aluno') {
                $c[$x]['account'] = Account::select('nome_completo')->find($c[$x]['conta_id']);
                $c[$x]['student'] = Student::select('nome_completo')->find($c[$x]['aluno_id']);
            } else {
                $c[$x]['day'] = $days[date('w', strtotime($c[$x]['data_aula_inicio']))];
            }
        }
        $params['data'] = $c;
        $params['sidebar'] = 'sidebar-' . $_SESSION['permission'];

        $file = 'index-' . $_SESSION['permission'];  //$this->dd($params);

        $this->render('classes', $file, $params);
    }

    public function newClassess()
    {
        $c = Course::select()->where('teacher_id', $_SESSION['id'])->get();

        for($x = 0; $x < count($c); $x++) {
            $c[$x]['account'] = Account::select(['id', 'nome_completo'])->find($c[$x]['account_id']);
        }

        $this->render('classes','new', [
            'data' => $c,
            'sidebar' => 'sidebar-' . $_SESSION['permission']
        ]);
    }

    public function updateClasses()
    {
        $post = $_POST;
        
        if ($post['presenca'] == 2) {
            $post['reposicao'] = 1;
        }

        Classes::update()->set($post)->where('id', $post['id'])->execute();
    }

    public function updateClassesStatus($id)
    {
        $a = Classes::select('status')->find($id);

        if ($a['status'] != 'Concluida') {
            Classes::update()->set('status', 'Concluida')->where('id', $id)->execute();
        } else {
            Classes::update()->set('status', '')->where('id', $id)->execute();
        }
    }

    public function deleteClasses()
    {
        $start = filter_input(INPUT_POST, 'data-inicio');
        $end = filter_input(INPUT_POST, 'data-fim');

        if($start && $end) {
            Classes::delete()->where('data_aula_inicio', '>=', $start . ' 00:00:00')->where('data_aula_inicio', '<=', $end . ' 00:00:00')->execute();
            $_SESSION['msg'] = true;
        } else {
            $_SESSION['error'] = true;
        }

        $this->redirect('/Aulas');
    }

    public function delete()
    {
        
        $id = filter_input(INPUT_POST, 'id');
        
        Classes::delete()->where('id', $id)->execute();
    }

    public function newSchedule()
    {
        $classes_date = filter_input(INPUT_POST, 'classes_date');
        $classes_start = filter_input(INPUT_POST, 'classes_start');
        $classes_end = filter_input(INPUT_POST, 'classes_end');
        $curso_id = filter_input(INPUT_POST, 'curso_id');
        $conta_id = filter_input(INPUT_POST, 'conta_id');

        $start = $classes_date . ' ' . $classes_start . ':00';
        $end = $classes_date . ' ' . $classes_end . ':00';

        $aluno_ids = Student::select('id')->where('course_ids', 'like', '%' . $curso_id . '%')->get();

        foreach ($aluno_ids as $aluno_id) {
            if(
                !Classes::select()
                    ->where([
                        'curso_id' => $curso_id,
                        'professor_id' => $_SESSION['id'],
                        'conta_id' => $conta_id,
                        'aluno_id' => $aluno_id['id'],
                        'data_aula_inicio' => $start,
                        'data_aula_termino' => $end
                    ])
                    ->one()
            ) {
                Classes::insert([
                    'curso_id' => $curso_id,
                    'professor_id' => $_SESSION['id'],
                    'conta_id' => $conta_id,
                    'aluno_id' => $aluno_id['id'],
                    'data_aula_inicio' => $start,
                    'data_aula_termino' => $end
                ])->execute();
            }

        }

        $_SESSION['new'] = true;

        $this->redirect('/Nova-Aula');
    }

    public function update()
    {
        $day = date('w');
        $days = ['Domingo','Segunda-feira','Terça-feira','Quarta-feira','Quinta-feira','Sexta-feira','Sábado'];
        $course = Course::select()->where('schedule', 'LIKE', '%'.$days[$day].'%')->get();

        foreach ($course as $c) {

            $students = CourseToStudent::select('aluno_id')->where('curso_id', $c['id'])->get();

            $dates = json_decode($c['schedule'],true);

            for($x = 0; $x < count($dates); $x++) {
                if($dates[$x]['day'] == $days[$day]) {
                    $date = $dates[$x];
                }
            }

            foreach ($students as $s) {
                if(
                    !Classes::select('id')->where([
                        'curso_id' => $c['id'],
                        'aluno_id' => $s['aluno_id'],
                        'data_aula_inicio' => date('Y-m-d ') . $date['in']
                    ])->get()
                ) {                                                                          $this->dd($c);
                    Classes::insert([
                        'curso_id' => $c['id'],
                        'professor_id' => $c['teacher_id'],
                        'conta_id' => $c['account_id'],
                        'aluno_id' => $s['aluno_id'],
                        'data_aula_inicio' => date('Y-m-d ') . $date['in'],
                        'data_aula_termino' => date('Y-m-d ') . $date['out']
                    ])->execute();
                }
            }
        }
    }
}