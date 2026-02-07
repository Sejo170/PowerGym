<?php

namespace App\Models;

use CodeIgniter\Model;

class LineasPedidosModel extends Model
{
    protected $table = 'lineas_pedidos'; // Ojo al nombre exacto de tu tabla
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_pedido', 'id_producto', 'cantidad', 'precio'];
}