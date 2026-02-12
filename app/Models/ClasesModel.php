<?php

namespace App\Models;

use CodeIgniter\Model;

class ClasesModel extends Model
{
    protected $table      = 'clases';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'nombre', 
        'descripcion', 
        'fecha_hora', 
        'plazas_totales', 
        'id_entrenador'
    ]; 

    // Función para traer las clases y nombre del entrenador
    public function obtenerClasesConEntrenador()
    {
        $this->select('clases.*, usuarios.nombre as nombre_entrenador, usuarios.apellidos as apellidos_entrenador');
        $this->join('usuarios', 'usuarios.id = clases.id_entrenador');
        return $this->findAll();
    }
}