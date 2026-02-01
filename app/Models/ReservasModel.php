<?php

namespace App\Models;

use CodeIgniter\Model;

class ReservasModel extends Model
{
    protected $table            = 'reservas';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['id_usuario', 'id_clase'];
}
?>