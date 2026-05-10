<?php
// app/Core/Controller.php

class Controller {
    
    public function model($model) {
        // Caminho corrigido para sua estrutura
        $modelPath = APPROOT . '/app/Models/' . $model . '.php';
        
        if (file_exists($modelPath)) {
            require_once $modelPath;
            return new $model();
        } else {
            die("Modelo não encontrado: " . $modelPath);
        }
    }

    public function view($view, $data = []) {
        $viewPath = APPROOT . '/app/Views/' . $view . '.php';
        
        if (file_exists($viewPath)) {
            extract($data); // permite usar variáveis no view
            require_once $viewPath;
        } else {
            die('A view não existe: ' . $view);
        }
    }
}
