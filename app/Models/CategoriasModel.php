<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoriasModel extends Model
{
    protected $table      = 'categorias';
    protected $primaryKey = 'id';

    // Lista blanca de campos que permitimos guardar
    protected $allowedFields = [
        'nombre_categoria'
    ];
}