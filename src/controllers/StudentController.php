<?php

namespace src\controllers;

use \core\Controller;
use \src\models\Student;
use \src\models\Account;
use \src\models\Evaluation;
use \src\models\Course;
use src\models\CourseToStudent;

class StudentController extends Controller
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
        $std = [];
        if($_SESSION['permission'] == 'Professor') {
            $c = array_column(Course::select('id')->where('teacher_id', $_SESSION['id'])->get(), 'id');              
            $ids = array_column(CourseToStudent::select()->where('curso_id', 'in', $c)->get(), 'aluno_id');
            $std = Student::select()
                ->where('status', 0)
                ->where('id', 'in', $ids)
                ->get();
        } elseif ($_SESSION['permission'] == 'Empresa') {
            $std = Student::select()->where(['conta_id' => $_SESSION['id'], 'status' => 0])->get();
        } else {
            $std = Student::select()->where('status', 0)->get();
        }

        $acc = Account::select()->get();

        $relatorio = [];
        $year = [];
        $bi = [];

        for ($xx = 0; $xx < count($std); $xx++) {

            foreach($acc as $ac) {
                if($std[$xx]['conta_id'] == $ac['id']) {
                    $std[$xx]['account'] = $ac['nome_completo'];
                }
            }

            if($std[$xx]['created'] != '') {
                $mesAno = explode('-', $std[$xx]['created']);
                $relatorio[$mesAno[0]][$mesAno[1]]++;
            }

            if(isset($std[$xx]['nascimento']) && date('m', strtotime($std[$xx]['nascimento'])) == date('m')) {
                $bi[$xx]['nome'] = $std[$xx]['nome_completo'];
                $bi[$xx]['day'] = date('d', strtotime($std[$xx]['nascimento']));
                $bi[$xx]['nascimento'] = $std[$xx]['nascimento'];
            }
        }

        foreach($relatorio as $key => $value) {
            for($x = 1; $x <= 12; $x++) {
                $id = $x < 10 ? '0'.$x : $x;
                $year[$key] .= $value[$id] ? $value[$id] : 0;
                $year[$key] .= ',';
            }
            $year[$key] = rtrim($year[$key], ',');
        }
        ksort($year);

        $this->render('student', 'index', [
            'data' => $std,
            'account' => $acc,
            'chart' => $year,
            'birthday' => $bi,
            'sidebar' => 'sidebar-' . $_SESSION['permission']
        ]);
    }

    public function ex()
    {

        $std = Student::select()->where('status', 1)->get();
        $acc = Account::select()->get(); 

        for ($x = 0; $x < count($std); $x++) {

            foreach($acc as $ac) {
                if($std[$x]['conta_id'] == $ac['id']) {
                    $std[$x]['account'] = $ac['nome_completo'];
                }
            }
        }

        $this->render('student', 'ex', [
            'data' => $std
        ]);
    }

    public function newStudent()
    {
        $a = Account::select()->get();

        $this->render('student', 'new', [
            'account' => $a
        ]);
    }

    public function addStudent()
    {
        $this->adminAuth();

        $post = [];

        foreach($_POST as $path => $v) {
            $post[$path] = filter_input(INPUT_POST, $path);
        }

        if($this->hashCompare($post['senha'], $post['confirma_senha'])) {
            unset($post['confirma_senha']);
        } else {
            $_SESSION['error_pass'] = true;
            $this->redirect('/Alunos');
            exit;
        }
        
        $post['course_ids'] = '[]';

        Student::insert($post)->execute();

        $_SESSION['new'] = true;

        $this->redirect('/Alunos');
    }

    public function editStudent($args)
    {
        if($_SESSION['permission'] == 'Admin' || $_SESSION['permission'] == 'Aluno') {
            $st = Student::select()->find($args['id']);
            $a = Account::select()->get();

            $this->render('student', 'edit', [
                'student' => $st,
                'account' => $a,
                'sidebar' => 'sidebar-' . $_SESSION['permission']
            ]);
        }else {
            $this->redirect('/Sistema');
        }
    }

    public function SaveStudent()
    {
        if($_SESSION['permission'] == 'Admin' || $_SESSION['permission'] == 'Aluno') {

            $post = [];

            foreach ($_POST as $path => $v) {
                $post[$path] = filter_input(INPUT_POST, $path);
            }

            $std = Student::update();

            if ($this->hashCompare($post['senha'], $post['confirma_senha'])) {
                $std->set('senha', password_hash($post['senha'], PASSWORD_DEFAULT));
            } else {
                unset($post['senha']);
                unset($post['confirma_senha']);
            }

            $std->set($post)
                ->where('id', $post['id'])
                ->execute();

            $_SESSION['edit'] = true;

            if($_SESSION['permission'] == 'Aluno') {
                $_SESSION['logou'] = $post['nome_completo'];
                $this->redirect('/Sistema');
                exit;
            }
            if ($post['status']) {
                $this->redirect('/Ex-alunos');
            } else {
                $this->redirect('/Alunos');
            }
        } else {
            $this->redirect('/Sistema');
        }
        
    }

    public function evaluation($args) 
    {
        $st = Student::select()->find($args['id']);
        $st['course'] = Course::select(['id','course_name'])->whereIn('id', json_decode($st['course_ids']))->get();
        $st['account'] = Account::select('nome_completo')->where('id', $st['conta_id'])->one(); 
        $st['evaluation'] = Evaluation::select()->where('aluno_id', $args['id'])->orderBy('data_avaliacao', 'desc')->get();

        $this->render('student', 'evaluation', [
            'data' => $st,
            'sidebar' => 'sidebar-' . $_SESSION['permission']
        ]);
    }

    public function changeStudentStatus()
    {
        $id = filter_input(INPUT_POST, 'id');

        $a = Student::select('status')->find($id);

        if ($a['status'] == 0) {
            Student::update()->set('status', '1')->where('id', $id)->execute();
        } else {
            Student::update()->set('status', '0')->where('id', $id)->execute();
        }
    }
}
