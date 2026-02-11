<?php

namespace App\Controllers;

class Home extends BaseController
{
    // Funcion para mostrar la web principal
    public function index()
    {
        echo view('plantilla/header');
        echo view('welcome_message');
        echo view('plantilla/footer');
    }

    // Función para obtener la frase por AJAX
    public function obtenerFrase()
    {
        // Iniciamos CURL
        $ch = curl_init();
        
        // Configuramos la dirección de la API
        curl_setopt($ch, CURLOPT_URL, "https://zenquotes.io/api/random/");
        // Queremos que nos devuelva el resultado, no que lo imprima directo
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // Ignoramos la verificación de certificados SSL (solo para localhost)
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
        
        // Ejecutamos y cerramos
        $json = curl_exec($ch);
        curl_close($ch);

        // Si falla y no muestra nada, mostramos un error vacío
        if (!$json) {
            return $this->response->setJSON([]); 
        }

        // Devolvemos los datos al navegador
        return $this->response->setJSON(json_decode($json));
    }
}
