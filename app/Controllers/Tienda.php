<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProductosModel;

class Tienda extends BaseController
{
    public function index()
    {
        // 1. Iniciamos sesión (aunque sea pública, la vista necesita saber si el usuario está logueado para mostrar el botón de compra)
        $session = session();

        // 2. Instanciamos el modelo
        $productosModel = new ProductosModel();

        // 3. Obtenemos TODOS los productos
        // findAll() hace un "SELECT * FROM productos"
        $data['productos'] = $productosModel->findAll();

        $data['is_logged_in'] = $session->get('is_logged_in');

        // 4. Cargamos las vistas
        echo view('plantilla/header');
        echo view('tienda/index', $data);
        echo view('plantilla/footer');
    }
}