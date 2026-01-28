<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    // 1. Configuración Básica de la Tabla
    protected $table            = 'usuarios'; // Nombre exacto de tu tabla en la BD
    protected $primaryKey       = 'id';       // La columna que es llave primaria
    protected $useAutoIncrement = true;       // Le decimos que el ID se genera solo
    protected $returnType       = 'array';    // Queremos los resultados como array (más fácil de usar)

    /**
     * 2. SEGURIDAD: $allowedFields (Protección contra Asignación Masiva)
     * ------------------------------------------------------------------
     * Esta es tu "lista de invitados". CodeIgniter SOLO permitirá guardar
     * o actualizar los datos de estas columnas. Si alguien intenta colar
     * un campo extra, será ignorado.
     * * Nota: Incluimos 'id_rol' para que el ADMIN pueda cambiar roles,
     * pero debemos protegerlo en el Controlador para que un usuario
     * normal no se cambie el rol al registrarse.
     */
    protected $allowedFields    = [
        'nombre',
        'apellidos',
        'email',
        'password',
        'id_rol'
    ];

    /**
     * 3. AUTOMATIZACIÓN: Timestamps (Fechas automáticas)
     * ------------------------------------------------------------------
     * Activamos esto para no tener que escribir manualmente la fecha
     * cada vez que registramos a alguien.
     */
    protected $useTimestamps = true;

    // Le decimos a CI4 que use tu columna 'fecha_registro' para guardar
    // el momento de creación automáticamente.
    protected $createdField  = 'fecha_registro'; 

    // Como en tu BD no tienes una columna para "fecha de modificación" (updated_at),
    // dejamos esto vacío para que no intente buscarla y dar error.
    protected $updatedField  = ''; 
}