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
        // (La vista la crearemos en el siguiente paso)
        return view('admin/lista_usuarios', $data);
    }

    /**
     * Elimina un usuario, CON PROTECCIÓN DE SEGURIDAD.
     * URL: tudominio.com/admin/borrarUsuario/5
     * * @param int $idUsuarioParaBorrar El ID que viene en la URL
     */
    public function borrarUsuario($idUsuarioParaBorrar = null)
    {
        // --- 🛡️ INICIO DE SEGURIDAD CRÍTICA ---
        
        // 1. Obtenemos el ID del administrador que está conectado AHORA.
        // CodeIgniter busca en la sesión el valor guardado como 'id'.
        $idAdminLogueado = session()->get('id'); 

        // 2. Comparamos: ¿El ID que quieren borrar es IGUAL al mío?
        if ($idUsuarioParaBorrar == $idAdminLogueado) {
            
            // ¡ALERTA! El admin intenta borrarse a sí mismo.
            // redirect()->back() nos devuelve a la página anterior.
            // with() envía un mensaje temporal ("flash message") para mostrar el error.
            return redirect()->back()->with('mensaje_error', '¡No puedes borrar tu propia cuenta!');
        }

        // --- 🏁 FIN DE SEGURIDAD ---

        // Si llegamos aquí, es que los IDs son distintos. Podemos borrar.
        $usuarioModel = new UsuarioModel();
        
        // delete() es la función mágica de CI4 para borrar por ID
        $usuarioModel->delete($idUsuarioParaBorrar);

        // Volvemos a la lista con un mensaje de éxito
        return redirect()->to('/admin')->with('mensaje_exito', 'Usuario eliminado correctamente.');
    }

    /**
     * Cambia el rol de un usuario (Hacer Socio / Quitar Socio).
     * URL: tudominio.com/admin/cambiarRol/5/4
     */
    public function cambiarRol($idUsuario, $nuevoRol)
    {
        $usuarioModel = new UsuarioModel();

        // Preparamos los datos. Al tener 'id', save() sabe que es una ACTUALIZACIÓN (Update).
        $data = [
            'id'     => $idUsuario,
            'id_rol' => $nuevoRol
        ];

        // Guardamos el cambio
        $usuarioModel->save($data);

        return redirect()->back()->with('mensaje_exito', 'Rol actualizado correctamente.');
    }
}