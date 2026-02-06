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
        // Iniciamos cURL (una herramienta más potente para hacer peticiones)
        $ch = curl_init();
        
        // Configuramos la dirección de la API
        curl_setopt($ch, CURLOPT_URL, "https://zenquotes.io/api/random/");
        // Queremos que nos devuelva el resultado, no que lo imprima directo
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // 🚑 TRUCO: Ignoramos la verificación de certificados SSL (solo para localhost)
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
        
        // Ejecutamos y cerramos
        $json = curl_exec($ch);
        curl_close($ch);

        // Si falló y no trajo nada, devolvemos un error vacío
        if (!$json) {
            return $this->response->setJSON([]); 
        }

        // Devolvemos los datos al navegador
        return $this->response->setJSON(json_decode($json));
    }
}
