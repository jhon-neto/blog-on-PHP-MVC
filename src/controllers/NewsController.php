<?php

namespace src\controllers;

use core\Controller;
use src\models\Editorial;
use src\models\News;
use src\models\User;
use src\models\UsersInNews;

class NewsController extends Controller
{
    public function index()
    {
        $a = News::select()->get();
        $e = Editorial::select()->get();
        $u = User::select()->get();

        if (is_array($a) && !empty($a)) {
            for ($x = 0; $x < count($a); $x++) {
                $txt = explode(' ', $a[$x]['text']);
                $a[$x]['intro'] = $txt[0] . ' ' .
                    $txt[1] . ' ' .
                    $txt[2] . ' ' .
                    $txt[3] . ' ' .
                    $txt[4] . '...';
                $a[$x]['modal'] = json_encode([
                    'title' => $a[$x]['title'],
                    'text' => $a[$x]['text']
                ]);
            }
        }

        $this->render('news', [
            'data' => $a,
            'editorial' => $e,
            'user' => $u
        ]);
    }

    public function update($args)
    {
        $a = News::select()->find($args['id']);
        $e = Editorial::select()->get();
        $u = User::select()->get();
        $ui = UsersInNews::select()->where('news_id', $a['id'])->get();
        $ids = array_column($ui, 'user_id');

        $this->render('news-update', [
            'data' => $a,
            'editorial' => $e,
            'user' => $u,
            'ids' => $ids
        ]);
    }

    public function create()
    {
        $data['title'] = filter_input(INPUT_POST, 'title');
        $data['author'] = filter_input(INPUT_POST, 'author');
        $data['editorial'] = filter_input(INPUT_POST, 'editorial');
        $data['text'] = filter_input(INPUT_POST, 'text');
        $ids = filter_input(INPUT_POST, 'user', FILTER_DEFAULT , FILTER_REQUIRE_ARRAY);

        $news = News::insert($data)->execute();

        foreach ($ids as $id) {
            UsersInNews::insert([
                'user_id' => $id,
                'news_id' => $news
            ])->execute();
        }

        $_SESSION['result']['class'] = 'btn btn-success btn-lg';
        $_SESSION['result']['msg'] = 'Notícia foi ADICIONADA com sucesso!';
        $this->redirect('/adicionar-noticia');
    }

    public function updateNews()
    {
        $data['username'] = str_replace('@', '', filter_input(INPUT_POST, 'username'));
        $id = filter_input(INPUT_POST, 'id');

        News::update()
            ->set($data)
            ->where('id', $id)
            ->execute();

        $_SESSION['result']['class'] = 'btn btn-primary btn-lg';
        $_SESSION['result']['msg'] = 'Notícia foi EDITADA com sucesso!';
        $this->redirect('/adicionar-noticia');
    }

    public function delete($args)
    {
        News::delete()->where('id', $args['id'])->execute();

        $_SESSION['result']['class'] = 'btn btn-danger btn-lg';
        $_SESSION['result']['msg'] = 'Notícia foi EXCLUÍDA com sucesso!';
        $this->redirect('/adicionar-noticia');
    }

    public function getById($args)
    {
        $a = News::select(['title', 'text'])->find($args['id']);
        echo json_encode($a);
    }
}
