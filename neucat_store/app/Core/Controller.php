<?php
class Controller {
    public function model($model) {
        require_once APPROOT . '/Models/' . $model . '.php';
        return new $model();
    }

    public function view($view, $data = []) {
        $viewPath = APPROOT . '/Views/' . $view . '.php';
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            die('A view não existe: ' . $view);
        }
    }
}
