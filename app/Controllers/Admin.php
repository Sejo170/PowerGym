<?php

namespace App\Controllers;

// Importo Modelos
use App\Models\UsuarioModel;
use App\Models\ProductosModel;
use App\Models\ClasesModel;

class Admin extends BaseController
{
    // Funcion para mostrar DASHBOARD con datos
    public function index()
    {
        // 1. Instancio los modelos
        $usuarioModel   = new UsuarioModel();
        $productosModel = new ProductosModel();
        $clasesModel    = new ClasesModel();

        // 2. Pido el total de Usuarios, Productos y Clases
        $data['total_usuarios']  = $usuarioModel->countAll();
        $data['total_productos'] = $productosModel->countAll();
        $data['total_clases']    = $clasesModel->countAll();

        // 3. Cargamos la vista con la Dashboard
        echo view('plantilla/header');
        echo view('admin/dashboard', $data); 
        echo view('plantilla/footer');
    }

    // Funcion para mostrar una tabla con todos los USUARIOS
    public function usuarios()
    {
        // Instancio el Modelo
        $usuarioModel = new UsuarioModel();

        // 1. Capturo el dato 'rol' de la URL
        $filtroRol = $this->request->getGet('rol');

        // 2. Si tengo un rol seleccionado, aplico el filtro
        if ($filtroRol) {
            $usuarioModel->where('id_rol', $filtroRol);
        }

        // 3. Obtengo los usuarios (filtrados o todos)
        $data['usuarios'] = $usuarioModel->findAll();

        echo view('plantilla/header');
        echo view('admin/lista_usuarios', $data);
        echo view('plantilla/footer');
    }

    /**
     * Elimina un usuario, CON PROTECCIÓN DE SEGURIDAD.
     * URL: tudominio.com/admin/borrarUsuario/5
     * * @param int $idUsuarioParaBorrar El ID que viene en la URL
     */

    // Funcion para Borrar Usuarios
    public function borrarUsuario($idUsuarioParaBorrar = null)
    {
        // Si no está logueado O no es rol 1, lo echamos fuera.
        if (!session()->get('is_logged_in') || session()->get('id_rol') != 1) {
            return redirect()->to('/')->with('mensaje_error', 'Acceso denegado. No eres administrador.');
        }

        // Para evitar que el Admin se borre a si mismo
        $idAdminLogueado = session()->get('id'); 

        if ($idUsuarioParaBorrar == $idAdminLogueado) {
            return redirect()->back()->with('mensaje_error', '¡No puedes borrar tu propia cuenta!');
        }

        // Instancio el Modelo de Usuarios
        $usuarioModel = new UsuarioModel();
        
        // Verifico si el usuario existe antes de intentar borrarlo
        if($usuarioModel->find($idUsuarioParaBorrar)) {
            // Si existe lo borro
            $usuarioModel->delete($idUsuarioParaBorrar);
            return redirect()->to('/admin/usuarios')->with('mensaje_exito', 'Usuario eliminado correctamente.');
        } else {
            // Sino muestra un mensaje
            return redirect()->to('/admin/usuarios')->with('mensaje_error', 'El usuario no existe.');
        }
    }

    /**
     * Cambia el rol de un usuario (Hacer Socio / Quitar Socio).
     * URL: tudominio.com/admin/cambiarRol/5/4
     */

    // Funcion para cambiar el ROL 
    public function cambiarRol($idUsuario, $nuevoRol)
    {
        // Verifico si esta logeado y si es un admin, sino muestro un mensaje
        if (!session()->get('is_logged_in') || session()->get('id_rol') != 1) {
            return redirect()->to('/')->with('mensaje_error', 'Acceso denegado.');
        }

        // Ahora Validamos
        // Pasamos un lista de los roles que hay para que nadie pueda ponerse otro rol etc.. (1=Admin, 2=Entrenador, 3=Cliente, 4=Socio)
        $rolesValidos = [1, 2, 3, 4]; 
        
        if (!in_array($nuevoRol, $rolesValidos)) {
            return redirect()->back()->with('mensaje_error', 'El rol seleccionado no es válido.');
        }

        // PROCESAMOS EL CAMBIO
        $usuarioModel = new UsuarioModel();

        // Verifico que el usuario exista
        if (!$usuarioModel->find($idUsuario)) {
            return redirect()->back()->with('mensaje_error', 'Usuario no encontrado.');
        }

        // Guardamos el cambio (save detecta el ID y hace un UPDATE), le damos el usuario y cual es su nuevo rol
        $usuarioModel->save([
            'id'     => $idUsuario,
            'id_rol' => $nuevoRol
        ]);

        // Mensaje de EXITO
        return redirect()->back()->with('mensaje_exito', 'Rol actualizado correctamente.');
    }

    // Funcion para la API
    public function datosGrafica()
    {
        // Se conecta a la bd y accede a la tabla de las clases
        $db = \Config\Database::connect();
        $builder = $db->table('clases');

        // Seleccionamos el nombre de la clase y contamos el total de personas que se han apuntado
        $builder->select('clases.nombre, COUNT(reservas.id) as total'); 

        // Hacemos un JOIN para juntar las 2 tablas, ponemos el LEFT para que aunque hayan 0 reservas se muestren igualmente
        $builder->join('reservas', 'reservas.id_clase = clases.id', 'left');
        $builder->groupBy('clases.id');
        
        // Obtenemos los datos y los enviamos como JSON
        $data = $builder->get()->getResultArray();

        return $this->response->setJSON($data);
    }
}