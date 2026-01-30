<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ClasesModel;

class Clases extends BaseController
{
    public function index()
    {
        // 1. Instanciamos el Modelo
        $clasesModel = new ClasesModel();

        // 2. Obtenemos todas las clases de la base de datos
        $data['clases'] = $clasesModel->obtenerClasesConEntrenador();

        // 3. Cargamos la vista (y le pasamos los datos)
        echo view('plantilla/header');
        echo view('admin/clases/lista_clases', $data);
        echo view('plantilla/footer');
    }

    public function crear()
    {
        echo view('plantilla/header');
        echo view('admin/clases/crear_clase');
        echo view('plantilla/footer');
    }
}