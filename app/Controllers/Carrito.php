<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProductosModel;

class Carrito extends BaseController
{
    public function index()
    {
        $session = session();
        $productosModel = new ProductosModel();

        // 1. Recuperamos el carrito (Array: ID => Cantidad)
        $carrito = $session->get('carrito');
        
        $datosVista = [];

        if (empty($carrito)) {
            // Si está vacío, mandamos una lista vacía
            $datosVista['productos'] = []; 
        } else {
            // 2. Extraemos solo los IDs (las llaves del array)
            // Si $carrito es [1 => 2, 5 => 1], esto nos da [1, 5]
            $ids = array_keys($carrito);

            // 3. Buscamos todos esos productos de golpe en la BD
            $datosVista['productos'] = $productosModel->find($ids);
        }

        echo view('plantilla/header');
        echo view('carrito/index', $datosVista);
        echo view('plantilla/footer');
    }
}