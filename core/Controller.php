<?php
namespace core;

use \src\Config;

class Controller {

    protected function redirect($url) {
        header("Location: ".$this->getBaseUrl().$url);
        exit;
    }

    private function getBaseUrl() {
        $base = (isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on') ? 'https://' : 'http://';
        $base .= $_SERVER['SERVER_NAME'];
        if($_SERVER['SERVER_PORT'] != '80') {
            $base .= ':'.$_SERVER['SERVER_PORT'];
        }
        $base .= Config::BASE_DIR;
        
        return $base;
    }

    private function _render($folder, $viewName, $viewData = []) {
        if(file_exists('../src/views/'.$folder.'/'.$viewName.'.php')) {
            extract($viewData);
            $render = function($vN, $vD = [])
            {
                return $this->renderPartial($vN, $vD);
            };
            $base = $this->getBaseUrl();
            $chart_color = [
                '2030' => '#F0F8FF',
                '2029' => '#A52A2A',
                '2028' => '#0000FF',
                '2027' => '#00FFFF',
                '2026' => '#A9A9A9',
                '2025' => '#9932CC',
                '2024' => '#1400D3'
            ];
            require '../src/views/'.$folder.'/'.$viewName.'.php';
        }
    }

    private function renderPartial($viewName, $viewData = []) {
        $this->_render('partials', $viewName, $viewData);
    }

    public function render($viewName, $viewData = []) {
        $this->_render('pages', $viewName, $viewData);
    }

    public function adminAuth()
    {
        if($_SESSION['permission'] != 'Admin') {
            $this->redirect('/Sistema');
            exit;
        }
    }

    public function hashCompare($pass1, $pass2)
    {
        if($pass1 && $pass2 && $pass1 === $pass2) {
            return true;
        }
        return false;
    }

    function dd($args, $type = null)
    {
        echo '<pre>';
        print_r($args);

        if($type) {
            echo '<hr>';
            var_dump($args);
        }
        exit;
    }

}