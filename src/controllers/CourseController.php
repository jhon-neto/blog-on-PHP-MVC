<?php

namespace src\controllers;

use \core\Controller;
use src\models\Account;
use src\models\Classes;
use src\models\Course;
use src\models\Evaluation;
use src\models\Student;
use src\models\Teacher;
use src\models\CourseToStudent;

class CourseController extends Controller
{
    private $today;

    public function __construct()
    {
        session_start();
        if (!$_SESSION['logou']) {
            $this->redirect('/login/' . $_SESSION['permission']);
            exit;
        }
        date_default_timezone_set('America/Sao_Paulo');
        $this->today = date('Y-m-d H:i:s');
        ini_set('display_errors', 0);
        error_reporting(E_ALL);
    }

    public function index()
    {
        switch ($_SESSION['permission']) {
            case 'Admin':
                $c = Course::select()->get();
                break;
            case 'Professor':
                $c = Course::select()->where('teacher_id', $_SESSION['id'])->get();
                break;
            case 'Aluno':
                $student = Student::select('course_ids')->find($_SESSION['id']);
                if(strpos($student['course_ids'], ',')) {
                    $courseIds = explode(',', json_decode($student['course_ids'], true));
                } else {
                    $courseIds = json_decode($student['course_ids'], true);
                }
                $c = Course::select()->where('id', 'in', $courseIds)->get();
                break;
            case 'Empresa':
                $c = Course::select()->where('account_id', $_SESSION['id'])->get();
                break;
        }
        for($x = 0; $x < count($c); $x++) {
            if ($_SESSION['permission'] == 'Empresa') {
                $c[$x]['account'] = Account::select('nome_completo')->find($c[$x]['account_id']);
            }
            if ($_SESSION['permission'] == 'Admin') {
                $c[$x]['account'] = Account::select('nome_completo')->find($c[$x]['account_id']);
                $c[$x]['teacher'] = Teacher::select('nome_completo')->find($c[$x]['teacher_id']);
            }
            if ($_SESSION['permission'] == 'Professor') {
                $c[$x]['account'] = Account::select('nome_completo')->find($c[$x]['account_id']);

            }
            $days = json_decode($c[$x]['schedule'], true);
            $day = array_column($days, 'day');
            $c[$x]['course_day'] = implode(' e ', $day);
            foreach ($days as $d) {
                $c[$x]['course_time'] .= $d['in'] . ' às ' . $d['out'] . '<br>';
            }

        } //$this->dd($c);

        $this->render('course','index', [
            'data' => $c,
            'sidebar' => 'sidebar-' . $_SESSION['permission']
        ]);
    }

    public function indexId($args)
    {
        if(empty($args)) {
            $this->redirect('/Cursos');
        }

        $c = Course::select()->where('teacher_id', $args['id'])->get();
        
        for($x = 0; $x < count($c); $x++) {
            $c[$x]['account'] = Account::select('nome_completo')->find($c[$x]['account_id']);
            $c[$x]['students'] = CourseToStudent::select()->where('course_id', $c[$x]['id'])->count();
        } //$this->dd($c);

        $this->render('course', 'index', [
            'data' => $c,
            'sidebar' => 'sidebar-' . $_SESSION['permission']
        ]);
    }

    public function newCourse()
    {
        $this->adminAuth();

        $acc = Account::select()->get();
        $t = Teacher::select()->get();

        $this->render('course', 'new', [
            'account' => $acc,
            'teacher' => $t
        ]);
    }

    public function addCourse()
    {
        $this->adminAuth();

        $days = [];

        $course_name = filter_input(INPUT_POST, 'course_name');
        $account_id = filter_input(INPUT_POST, 'account_id');
        $level = filter_input(INPUT_POST, 'level');
        $lang = filter_input(INPUT_POST, 'lang');
        $course_type = filter_input(INPUT_POST, 'course_type');
        $price = filter_input(INPUT_POST, 'price');
        $teacher_id = filter_input(INPUT_POST, 'teacher_id');
        $supplies = filter_input(INPUT_POST, 'supplies');
        $obs = filter_input(INPUT_POST, 'obs');

        $day = $_POST['days'];
        $in = $_POST['in'];
        $out = $_POST['out'];

        for($x = 0; $x < count($day); $x++) {
            $days[] = [
                'day' => $day[$x],
                'in' => $in[$x],
                'out' => $out[$x]
            ];
        }
        
        Course::insert([
            'course_name' => $course_name,
            'account_id' => $account_id,
            'level' => $level,
            'lang' => $lang,
            'course_type' => $course_type,
            'price' => $price,
            'teacher_id' => $teacher_id,
            'supplies' => $supplies,
            'obs' => $obs,
            'schedule' => json_encode($days)
        ])->execute();

        $_SESSION['new'] = true;

        $this->redirect('/Cursos');

    }

    public function editCourse($args)
    {
        $this->adminAuth();

        $c = Course::select()->find($args['id']);
        if (empty($c)){
            $this->redirect('/Cursos');
        }
        $c['std'] = Student::select(['id', 'nome_completo'])->where('course_ids', 'LIKE', '%"' . $c['id'] . '"%' )->get();
        $a = Account::select(['id', 'nome_completo'])->get();
        $t = Teacher::select(['id', 'nome_completo'])->get();
        $st = Student::select(['id', 'nome_completo', 'conta_id'])->where('status', 0)->where('course_ids', 'NOT LIKE', '%"' . $args['id'] . '"%')->orderBy('nome_completo')->get();

        foreach($a as $ac) {
            $arr[$ac['id']] = $ac['nome_completo'];
        }

        $this->render('course', 'edit', [
            'data' => $c,
            'account' => $a,
            'accArray' => $arr,
            'teacher' => $t,
            'student' => $st,
            'schedule' => json_decode($c['schedule'], true)
        ]);
    }

    public function SaveCourse()
    {
        $this->adminAuth();

        $days = [];

        $course_name = filter_input(INPUT_POST, 'course_name');
        $account_id = filter_input(INPUT_POST, 'account_id');
        $level = filter_input(INPUT_POST, 'level');
        $lang = filter_input(INPUT_POST, 'lang');
        $course_type = filter_input(INPUT_POST, 'course_type');
        $price = filter_input(INPUT_POST, 'price');
        $teacher_id = filter_input(INPUT_POST, 'teacher_id');
        $supplies = filter_input(INPUT_POST, 'supplies');
        $obs = filter_input(INPUT_POST, 'obs');
        $id = filter_input(INPUT_POST, 'id');

        $day = $_POST['days'];
        $in = $_POST['in'];
        $out = $_POST['out'];

        for($x = 0; $x < count($day); $x++) {
            $days[] = [
                'day' => $day[$x],
                'in' => $in[$x],
                'out' => $out[$x]
            ];
        }

        Course::update()
            ->set('course_name', $course_name)
            ->set('account_id', $account_id)
            ->set('level', $level)
            ->set('lang', $lang)
            ->set('course_type', $course_type)
            ->set('price', $price)
            ->set('teacher_id', $teacher_id)
            ->set('supplies', $supplies)
            ->set('obs', $obs)
            ->set('schedule', json_encode($days))
            ->where('id', $id)
            ->execute();

        $_SESSION['edit'] = true;

        $this->redirect('/Cursos');
    }

    public function courseDetails($args)
    {
        $c = Course::select()->find($args['id']);
        if (empty($c)){
            $this->redirect('/Cursos');
        }
        $c['account'] = Account::select()->where('id', $c['account_id'])->one();
        $c['teacher'] = Teacher::select()->where('id', $c['teacher_id'])->one();
        $ids = array_column(CourseToStudent::select()->where('course_id', $args['id'])->get(), 'student_id');
        $c['student'] = Student::select(['id', 'nome_completo'])->where('id', 'in', $ids)->get();

        if($_SESSION['permission'] == 'Aluno') {
            $c['classes'] = Classes::select()
                ->where('aluno_id', $_SESSION['id'])
                ->where('data_aula_inicio', '>', $this->today)
                ->orderBy('data_aula_inicio')
                ->get();

            for ($x = 0; $x < count($c['classes']); $x++) {

                $c['classes'][$x]['teacher'] = Teacher::select('nome_completo')->where('id', $c['classes'][$x]['professor_id'])->one();

                $arr = explode(' ', $c['classes'][$x]['data_aula_inicio']);
                $dt = explode('-', $arr[0]);
                $c['classes'][$x]['date'] = $dt[2] . '/' . $dt[1] . '/' . $dt[0];
                $c['classes'][$x]['start'] = substr($arr[1], 0, 5);
                $c['classes'][$x]['end'] = substr($c['classes'][$x]['data_aula_termino'], 10, 6);

            }
        }

        $this->render('course', 'details', [
            'data' => $c,
            'schedule' => json_decode($c['schedule'], true),
            'sidebar' => 'sidebar-' . $_SESSION['permission']
        ]);
    }

    public function addStudentToCourse()
    {
        $this->adminAuth();

        $ids = $_POST['ids'];
        $id = filter_input(INPUT_POST, 'id');

        foreach($ids as $i) {
            $s = Student::select(['id', 'course_ids'])->find($i);
            $value = json_decode($s['course_ids'], true);  
            array_push($value, $id);

            Student::update()->set('course_ids', json_encode($value))->where('id', $s['id'])->execute();
            
            if (!CourseToStudent::select()->where(['curso_id' => $id, 'aluno_id' => $i])->one()) {
                CourseToStudent::insert(['curso_id' => $id, 'aluno_id' => $i])->execute();
            }
        }
        $_SESSION['added'] = true;

        $this->redirect('/Editar-curso/' . $id);
    }

    public function removeStudentToCourse()
    {
        $this->adminAuth();

        $ids = $_POST['ids'];
        $id = filter_input(INPUT_POST, 'id');

        foreach($ids as $i) {
            $s = Student::select(['id', 'course_ids'])->find($i);
            $value = json_decode($s['course_ids'], true);

            $result = json_encode(array_diff($value, [$id]));
            
            Student::update()->set('course_ids', $result)->where('id', $i)->execute();
            
            CourseToStudent::delete()->where(['curso_id' => $id, 'aluno_id' => $i])->execute();
        }
        $_SESSION['remove'] = true;
        
        $this->redirect('/Editar-curso/' . $id);
    }

    public function courseDelete($args)
    {
        Course::delete()->where( 'id', $args['id'])->execute();
        CourseToStudent::delete()->where( 'curso_id', $args['id'])->execute();
        Classes::delete()->where( 'curso_id', $args['id'])->execute();
        Evaluation::delete()->where( 'curso_id', $args['id'])->execute();

        $std = Student::select(['id', 'course_ids'])->where('course_ids', 'LIKE', '%"'.$args['id'].'"%')->get();

        foreach ($std as $st){
            $id = str_replace('"'.$args['id'].'"', '', $st['course_ids']);
            $id = str_replace('[,', '[', $id);
            $id = str_replace(',]', ']', $id);
            Student::update()->set(['course_ids' => $id])->where('id', $st['id'])->execute();
        }

        $_SESSION['delete'] = true;

        $this->redirect('/Cursos');
    }
}