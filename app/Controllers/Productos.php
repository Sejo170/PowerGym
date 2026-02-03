<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProductosModel;

class Productos extends BaseController
{
    // Muestra una lista con todos los productos
    public function index()
    {
        $productosModel = new ProductosModel();

        // --- CAMBIO PRO ---
        // En lugar de findAll() a secas, construimos la consulta:
        $data['productos'] = $productosModel->select('productos.*, categorias.nombre_categoria')
                                            ->join('categorias', 'categorias.id = productos.id_categoria')
                                            ->findAll();
        // ------------------

        echo view('plantilla/header');
        echo view('admin/lista_productos', $data);
        echo view('plantilla/footer');
    }
}