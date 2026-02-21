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

    // Función para crear un nuevo usuario desde el panel de Admin
    public function crearUsuario()
    {
        // 1. Verificamos la seguridad: Si no es Admin, lo bloqueamos
        if (!session()->get('is_logged_in') || session()->get('id_rol') != 1) {
            return redirect()->to('/')->with('mensaje_error', 'Acceso denegado.');
        }

        // 2. Comprobamos si el administrador ha enviado el formulario (método POST)
        if ($this->request->getMethod() == 'POST') {
            
            $usuarioModel = new UsuarioModel();

            // 3. Recogemos los datos exactos que escribió el admin en el formulario
            $nombre    = $this->request->getPost('nombre');
            $apellidos = $this->request->getPost('apellidos');
            $email     = $this->request->getPost('email');
            $password  = $this->request->getPost('password');
            $id_rol    = $this->request->getPost('id_rol');

            // 4. ¡Súper importante! Encriptamos la contraseña por seguridad antes de guardarla
            $passwordEncriptada = password_hash($password, PASSWORD_DEFAULT);

            // 5. Preparamos el paquete de datos para enviarlo a la base de datos
            $datosParaGuardar = [
                'nombre'         => $nombre,
                'apellidos'      => $apellidos,
                'email'          => $email,
                'password'       => $passwordEncriptada,
                'fecha_registro' => date('Y-m-d H:i:s'), // Generamos la fecha y hora actual automáticamente
                'id_rol'         => $id_rol
            ];

            // 6. Insertamos el nuevo registro y redirigimos con éxito
            $usuarioModel->insert($datosParaGuardar);

            return redirect()->to('/admin/usuarios')->with('mensaje_exito', 'Usuario creado correctamente.');
        }

        // 7. Si no se ha enviado el formulario (es decir, el admin acaba de entrar a la página), le mostramos la vista
        echo view('plantilla/header');
        echo view('admin/crear_usuario'); 
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
        // Si no está logueado O no es rol 1 (Admin), lo echamos fuera.
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
        
        // 1. Buscamos y GUARDAMOS todos los datos del usuario que queremos borrar
        $usuarioABorrar = $usuarioModel->find($idUsuarioParaBorrar);

        // 2. Verificamos si el usuario existe
        if($usuarioABorrar) {
            
            // 3. Comprobamos si el usuario a borrar también es Admin (rol 1)
            if ($usuarioABorrar['id_rol'] == 1) {
                // Si es admin, bloqueamos la acción y regresamos con un error
                return redirect()->back()->with('mensaje_error', 'Por seguridad, no puedes eliminar a otro administrador.');
            }

            // Si no es admin, procedemos a borrarlo
            $usuarioModel->delete($idUsuarioParaBorrar);
            return redirect()->to('/admin/usuarios')->with('mensaje_exito', 'Usuario eliminado correctamente.');
            
        } else {
            // Si no existe, muestra un mensaje
            return redirect()->to('/admin/usuarios')->with('mensaje_error', 'El usuario no existe.');
        }
    }

    /**
     * Cambia el rol de un usuario (Hacer Socio / Quitar Socio).
     * URL: tudominio.com/admin/cambiarRol/5/4
     */

    // Ahora solo recibe el ID por la URL
    public function cambiarRol($idUsuario)
    {
        if (!session()->get('is_logged_in') || session()->get('id_rol') != 1) {
            return redirect()->to('/')->with('mensaje_error', 'Acceso denegado.');
        }

        // CAPTURAMOS el nuevo rol que viene del select del formulario
        $nuevoRol = $this->request->getPost('nuevo_rol'); 

        $rolesValidos = [1, 2, 3, 4]; 
        if (!in_array($nuevoRol, $rolesValidos)) {
            return redirect()->back()->with('mensaje_error', 'El rol seleccionado no es válido.');
        }

        $usuarioModel = new UsuarioModel();

        if (!$usuarioModel->find($idUsuario)) {
            return redirect()->back()->with('mensaje_error', 'Usuario no encontrado.');
        }

        $usuarioModel->save([
            'id'     => $idUsuario,
            'id_rol' => $nuevoRol
        ]);

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