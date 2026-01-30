<?php

namespace App\Models;

use CodeIgniter\Model;

class ClasesModel extends Model
{
    protected $table      = 'clases';
    protected $primaryKey = 'id';

    // Lista blanca de campos que permitimos guardar
    protected $allowedFields = [
        'nombre', 
        'descripcion', 
        'fecha_hora', 
        'plazas_totales', 
        'id_entrenador'
    ]; 

    // Función personalizada para traer las clases + nombre del entrenador
    public function obtenerClasesConEntrenador()
    {
        // 1. SELECT: Elegimos todo de clases (clases.*) y campos específicos del usuario
        // Usamos 'as' para darles un alias y no confundirlos con el nombre de la clase
        $this->select('clases.*, usuarios.nombre as nombre_entrenador, usuarios.apellidos as apellidos_entrenador');
        $this->join('usuarios', 'usuarios.id = clases.id_entrenador');
        return $this->findAll();
    }
}