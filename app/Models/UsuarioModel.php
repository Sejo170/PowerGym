<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table            = 'usuarios';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array'; // Queremos los resultados como array (más fácil de usar)

    protected $allowedFields    = [
        'nombre',
        'apellidos',
        'email',
        'password',
        'id_rol'
    ];

    protected $useTimestamps = true;

    // Le decimos a CI4 que use tu columna 'fecha_registro' para guardar
    // el momento de creación automáticamente.
    protected $createdField  = 'fecha_registro'; 

    // Como en tu BD no tienes una columna para "fecha de modificación" (updated_at),
    // dejamos esto vacío para que no intente buscarla y dar error.
    protected $updatedField  = ''; 
}