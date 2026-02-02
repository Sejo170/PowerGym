<?php

namespace App\Controllers;

// 1. Importamos el Modelo
// Esto es vital: sin esta línea, el controlador no sabe que existe "UsuarioModel"
use App\Models\UsuarioModel;

class Admin extends BaseController
{
    // Muestra la lista de todos los usuarios.
    public function index()
    {
        // Instanciamos el modelo ("llamamos al portero")
        $usuarioModel = new UsuarioModel();

        // Le pedimos TODOS los usuarios con findAll()
        // Esto equivale a un "SELECT * FROM usuarios"
        $data['usuarios'] = $usuarioModel->findAll();

        // Cargamos la vista y le enviamos los datos
        echo view('plantilla/header');
        echo view('admin/lista_usuarios', $data);
        echo view('plantilla/footer');
    }

    /**
     * Elimina un usuario, CON PROTECCIÓN DE SEGURIDAD.
     * URL: tudominio.com/admin/borrarUsuario/5
     * * @param int $idUsuarioParaBorrar El ID que viene en la URL
     */
    public function borrarUsuario($idUsuarioParaBorrar = null)
    {
        // --- 🛡️ 1. SEGURIDAD: ¿Eres Admin? ---
        // Si no está logueado O no es rol 1, lo echamos fuera.
        if (!session()->get('is_logged_in') || session()->get('id_rol') != 1) {
            return redirect()->to('/')->with('mensaje_error', 'Acceso denegado. No eres administrador.');
        }

        // --- 🛡️ 2. SEGURIDAD: ¿Te estás borrando a ti mismo? ---
        $idAdminLogueado = session()->get('id'); 

        if ($idUsuarioParaBorrar == $idAdminLogueado) {
            return redirect()->back()->with('mensaje_error', '¡No puedes borrar tu propia cuenta!');
        }

        // --- 💀 3. BORRADO ---
        $usuarioModel = new UsuarioModel();
        
        // Verificamos si el usuario existe antes de intentar borrar
        if($usuarioModel->find($idUsuarioParaBorrar)) {
            $usuarioModel->delete($idUsuarioParaBorrar);
            return redirect()->to('/admin')->with('mensaje_exito', 'Usuario eliminado correctamente.');
        } else {
            return redirect()->to('/admin')->with('mensaje_error', 'El usuario no existe.');
        }
    }

    /**
     * Cambia el rol de un usuario (Hacer Socio / Quitar Socio).
     * URL: tudominio.com/admin/cambiarRol/5/4
     */
    public function cambiarRol($idUsuario, $nuevoRol)
    {
        // --- 🛡️ 1. SEGURIDAD: Verificación de Admin ---
        if (!session()->get('is_logged_in') || session()->get('id_rol') != 1) {
            return redirect()->to('/')->with('mensaje_error', 'Acceso denegado.');
        }

        // --- 🛡️ 2. VALIDACIÓN: ¿El rol es válido? ---
        // Definimos los roles permitidos según tu base de datos (1=Admin, 2=Entrenador, 3=Cliente, 4=Socio)
        $rolesValidos = [1, 2, 3, 4]; 
        
        if (!in_array($nuevoRol, $rolesValidos)) {
            return redirect()->back()->with('mensaje_error', 'El rol seleccionado no es válido.');
        }

        // --- 3. PROCESAR EL CAMBIO ---
        $usuarioModel = new UsuarioModel();

        // Verificamos que el usuario exista
        if (!$usuarioModel->find($idUsuario)) {
            return redirect()->back()->with('mensaje_error', 'Usuario no encontrado.');
        }

        // Guardamos el cambio (save detecta el ID y hace un UPDATE)
        $usuarioModel->save([
            'id'     => $idUsuario,
            'id_rol' => $nuevoRol
        ]);

        return redirect()->back()->with('mensaje_exito', 'Rol actualizado correctamente.');
    }
}