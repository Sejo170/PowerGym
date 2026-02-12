<?php

namespace App\Models;

use CodeIgniter\Model;

class LineasPedidosModel extends Model
{
    protected $table = 'lineas_pedidos';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_pedido', 'id_producto', 'cantidad', 'precio'];
}