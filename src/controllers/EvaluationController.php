<?php

namespace src\controllers;

use core\Controller;
use src\models\Evaluation;
use src\models\Account;
use src\models\Student;
use src\models\Course;
use src\models\CourseToStudent;

class EvaluationController extends Controller
{

    public function __construct()
    {
        session_start();
        if (!$_SESSION['logou']) {
            $this->redirect('/login/' . $_SESSION['permission']);
            exit;
        }
        date_default_timezone_set('America/Sao_Paulo');
        ini_set('display_errors', 0);
        error_reporting(E_ALL);
    }

    public function index()
    {
        if($_SESSION['permission'] == 'Aluno') {
            $c = Evaluation::select()->where('aluno_id', $_SESSION['id'])->get();
        } elseif($_SESSION['permission'] == 'Empresa') {
            $ids = array_column(Course::select('id')->where('account_id', $_SESSION['id'])->get(), 'id');
            $c = Evaluation::select()->where('curso_id', 'in', $ids)->get();
        } elseif($_SESSION['permission'] == 'Professor') {
            $co = array_column(Course::select('id')->where('teacher_id', $_SESSION['id'])->get(), 'id');
            $ids = array_column(CourseToStudent::select()->where('course_id', 'in', $co)->get(), 'student_id');
            $c = Evaluation::select()
                ->where('id', 'in', $ids)
                ->get();
        } else {
            $c = Evaluation::select()->get();
        }

        for($x = 0; $x < count($c); $x++) {
            $c[$x]['student'] = Student::select(['id', 'nome_completo', 'conta_id'])->where('id', $c[$x]['aluno_id'])->one();
            $c[$x]['course'] = Course::select('course_name')->find($c[$x]['curso_id']);
            $c[$x]['account'] = Account::select('nome_completo')->find($c[$x]['student']['conta_id']);
        }

        $this->render('evaluation','index', [
            'data' => $c,
            'sidebar' => 'sidebar-' . $_SESSION['permission']
        ]);
    }

    public function updateEvaluation()
    {
        $input1 = filter_input(INPUT_POST, 'date_evaluation') . '-01 00:00:00';
        $input2 = filter_input(INPUT_POST, 'course_id');
        $input3 = filter_input(INPUT_POST, 'name');
        $input4 = filter_input(INPUT_POST, 'note_evaluation_writing');
        $input5 = filter_input(INPUT_POST, 'oral_evaluation_note');
        $id = filter_input(INPUT_POST, 'id');
        $idStd = filter_input(INPUT_POST, 'id-std');

        Evaluation::update()
            ->set('data_avaliacao', $input1)
            ->set('curso_id', $input2)
            ->set('nome_avaliacao', $input3)
            ->set('nota_avaliacao_escrita', $input4)
            ->set('nota_avaliacao_oral', $input5)
            ->where('id', $id)
            ->execute();

        $_SESSION['edit'] = true;

        $this->redirect('/Perfil-Avaliacoes/' . $idStd);
    }

    public function addEvaluation()
    {
        $student_id= filter_input(INPUT_POST, 'student_id');
        $course_id= filter_input(INPUT_POST, 'course_id');
        $name= filter_input(INPUT_POST, 'name');
        $note_evaluation_writing= filter_input(INPUT_POST, 'note_evaluation_writing');
        $oral_evaluation_note= filter_input(INPUT_POST, 'oral_evaluation_note');
        $date_evaluation= filter_input(INPUT_POST, 'date_evaluation') . '-01 00:00:00';

        Evaluation::insert([
            'aluno_id' => $student_id,
            'curso_id' => $course_id,
            'nome_avaliacao' => $name,
            'nota_avaliacao_escrita' => $note_evaluation_writing,
            'nota_avaliacao_oral' => $oral_evaluation_note,
            'data_avaliacao' => $date_evaluation
        ])->execute();

        $_SESSION['new'] = true;

        $this->redirect('/Perfil-Avaliacoes/' . $student_id);
    }

    public function delEvaluation($args)
    {
        $ids = explode('-', $args['id']);

        Evaluation::delete()->where('id', $ids[0])->execute();
        
        $_SESSION['del'] = true;
        
        $this->redirect('/Perfil-Avaliacoes/' . $ids[1]);
    }
}
