<?php

namespace src\controllers;

use \core\Controller;
use src\models\Classes;
use src\models\CourseToStudent;
use src\models\Student;
use \src\models\Teacher;
use \src\models\Course;

class TeacherController extends Controller
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
        $this->adminAuth();

        $t = Teacher::select()->get();
        
        for($x = 0; $x < count($t); $x++) {
            $t[$x]['course'] = Course::select()->where('teacher_id', $t[$x]['id'])->count();
        }

        $this->render('teacher', 'index', [
            'data' => $t
        ]);
    }

    public function newTeacher()
    {
        $this->render('teacher', 'new');
    }

    public function addTeacher()
    {
        $this->adminAuth();

        $post = [];

        foreach($_POST as $path => $v) {
            $post[$path] = filter_input(INPUT_POST, $path);
        }

        if(!$this->hashCompare($post['senha'], $post['confirma_senha'])) {
            $_SESSION['error_pass'] = true;
            $this->redirect('/Alunos');
            exit;
        } else {
            unset($post['confirma_senha']);
        }

        Teacher::insert($post)->execute();

        $_SESSION['new'] = true;

        $this->redirect('/Professores');
    }

    public function editTeacher($args)
    {
        $st = Teacher::select()->find($args['id']);

        $this->render('teacher', 'edit', [
            'teacher' => $st,
            'sidebar' => 'sidebar-' . $_SESSION['permission']
        ]);
    }

    public function saveTeacher()
    {
        $post = [];

        foreach($_POST as $path => $v) {
            $post[$path] = filter_input(INPUT_POST, $path);
        }

        $std = Teacher::update();

        if($this->hashCompare($post['senha'], $post['confirma_senha'])) {
            $std->set('senha', password_hash($post['senha'], PASSWORD_DEFAULT));
        } else {
            unset($post['senha']);
            unset($post['confirma_senha']);
        }

        $std->set($post)
            ->where('id', $post['id'])
            ->execute();

        $_SESSION['edit'] = true;

        if ($_SESSION['permission'] == 'Professor') {
            $this->redirect('/Editar-professor/' . $_SESSION['id']);
            exit;
        }
        $this->redirect('/Professores');
    }

    public function deleteTeacher($args)
    {
        Teacher::delete()->where('id', $args['id'])->execute();

        $course = Course::select('id')->where('teacher_id', $args['id'])->execute();
        foreach ($course as $c) {
            CourseToStudent::delete()->where('curso_id', $c['id'])->execute();
            $aluno_ids = Student::select(['id', 'course_ids'])->where('course_ids', 'like', '%"' . $c['id'] . '"%')->get();
            foreach($aluno_ids as $ids) {
                $courseIds = str_replace('"'.$c['id'].'"', '', $ids['course_ids']);
                $courseIds = str_replace('[,', '[', str_replace(',]', ']', $courseIds));
                Student::update()->set('course_ids', $courseIds)->where('id', $ids['id'])->execute();
            }
        }

        Course::delete()->where('teacher_id', $args['id'])->execute();
        Classes::delete()->where('professor_id', $args['id'])->execute();
        $_SESSION['deleted'] = true;
        $this->redirect('/Professores');
    }

}
