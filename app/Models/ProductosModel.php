<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductosModel extends Model
{
    protected $table      = 'productos';
    protected $primaryKey = 'id';

    // Lista blanca de campos que permitimos guardar
    protected $allowedFields = [
        'nombre', 
        'descripcion', 
        'precio', 
        'stock',
        'imagen',
        'id_categoria'
    ];
}