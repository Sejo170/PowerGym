<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        echo view('plantilla/header');
        echo view('welcome_message');
        echo view('plantilla/footer');
    }

    // Función para obtener la frase vía AJAX
    public function obtenerFrase()
    {
        // 1. Pedimos la frase a la API externa desde el servidor
        $json = @file_get_contents('https://zenquotes.io/api/random');
        
        // 2. Devolvemos el JSON directamente al navegador
        return $this->response->setJSON(json_decode($json));
    }
}
