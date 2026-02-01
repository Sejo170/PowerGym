<?php

namespace App\Controllers;

use App\Models\ClasesModel;

class Horarios extends BaseController
{
    public function index()
    {
        $clasesModel = new ClasesModel();

        // Hacemos la MAGIA del cruce de datos:
        // 1. Seleccionamos todo de la clase y ADEMÁS el nombre del entrenador (apodado como 'nombre_entrenador')
        // 2. Unimos (join) con la tabla usuarios donde coincidan los IDs
        $datos['clases'] = $clasesModel->select('clases.*, usuarios.nombre as nombre_entrenador')
            ->join('usuarios', 'usuarios.id = clases.id_entrenador')
            ->findAll();

        return view('horarios', $datos);
    }
}