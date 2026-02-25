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
        // Instancio los modelos
        $usuarioModel   = new UsuarioModel();
        $productosModel = new ProductosModel();
        $clasesModel    = new ClasesModel();

        // Pido el total de Usuarios, Productos y Clases
        $data['total_usuarios']  = $usuarioModel->countAll();
        $data['total_productos'] = $productosModel->countAll();
        $data['total_clases']    = $clasesModel->countAll();

        // Cargamos la vista con la Dashboard
        echo view('plantilla/header');
        echo view('admin/dashboard', $data); 
        echo view('plantilla/footer');
    }

    // Funcion para mostrar una tabla con todos los USUARIOS
    public function usuarios()
    {
        // Instancio el Modelo
        $usuarioModel = new UsuarioModel();

        // Capturo el dato 'rol' de la URL
        $filtroRol = $this->request->getGet('rol');

        // Si tengo un rol seleccionado, aplico el filtro
        if ($filtroRol) {
            $usuarioModel->where('id_rol', $filtroRol);
        }

        // Obtengo los usuarios (filtrados o todos)
        $data['usuarios'] = $usuarioModel->findAll();

        echo view('plantilla/header');
        echo view('admin/lista_usuarios', $data);
        echo view('plantilla/footer');
    }

    // Función para crear un nuevo usuario desde el panel de Admin
    public function crearUsuario()
    {
        // Verificamos la seguridad: Si no es Admin, lo bloqueamos
        if (!session()->get('is_logged_in') || session()->get('id_rol') != 1) {
            return redirect()->to('/')->with('mensaje_error', 'Acceso denegado.');
        }

        // Comprobamos si el administrador ha enviado el formulario
        if ($this->request->getMethod() == 'POST') {
            
            $usuarioModel = new UsuarioModel();

            // Recogemos los datos exactos que escribió el admin en el formulario
            $nombre    = $this->request->getPost('nombre');
            $apellidos = $this->request->getPost('apellidos');
            $email     = $this->request->getPost('email');
            $password  = $this->request->getPost('password');
            $id_rol    = $this->request->getPost('id_rol');

            // Encriptamos la contraseña por seguridad antes de guardarla
            $passwordEncriptada = password_hash($password, PASSWORD_DEFAULT);

            // Preparamos el paquete de datos para enviarlo a la base de datos
            $datosParaGuardar = [
                'nombre'         => $nombre,
                'apellidos'      => $apellidos,
                'email'          => $email,
                'password'       => $passwordEncriptada,
                'fecha_registro' => date('Y-m-d H:i:s'),
                'id_rol'         => $id_rol
            ];

            // Insertamos el nuevo registro y redirigimos con éxito
            $usuarioModel->insert($datosParaGuardar);

            return redirect()->to('/admin/usuarios')->with('mensaje_exito', 'Usuario creado correctamente.');
        }

        echo view('plantilla/header');
        echo view('admin/crear_usuario'); 
        echo view('plantilla/footer');
    }

    // Funcion para Borrar Usuarios
    public function borrarUsuario($idUsuarioParaBorrar = null)
    {
        if (!session()->get('is_logged_in') || session()->get('id_rol') != 1) {
            return redirect()->to('/')->with('mensaje_error', 'Acceso denegado. No eres administrador.');
        }

        $idAdminLogueado = session()->get('id'); 
        if ($idUsuarioParaBorrar == $idAdminLogueado) {
            return redirect()->back()->with('mensaje_error', '¡No puedes borrar tu propia cuenta!');
        }

        $usuarioModel = new UsuarioModel();
        $usuarioABorrar = $usuarioModel->find($idUsuarioParaBorrar);

        if($usuarioABorrar) {
            
            // No se puede borrar a otro admin
            if ($usuarioABorrar['id_rol'] == 1) {
                return redirect()->back()->with('mensaje_error', 'Por seguridad, no puedes eliminar a otro administrador.');
            }

            try {
                // Borramos sus reservas (si es cliente/socio) para evitar el error de clave foránea
                $reservasModel = new \App\Models\ReservasModel();
                $reservasModel->where('id_usuario', $idUsuarioParaBorrar)->delete();

                // Si es Entrenador, comprobamos si tiene clases asignadas
                if ($usuarioABorrar['id_rol'] == 2) {
                    $clasesModel = new \App\Models\ClasesModel();
                    $tieneClases = $clasesModel->where('id_entrenador', $idUsuarioParaBorrar)->countAllResults();
                    
                    if ($tieneClases > 0) {
                        return redirect()->back()->with('mensaje_error', 'No se puede borrar este entrenador porque tiene clases asignadas. Reasigna o borra sus clases primero.');
                    }
                }

                // Si todo está limpio, borramos al usuario
                $usuarioModel->delete($idUsuarioParaBorrar);
                return redirect()->to('/admin/usuarios')->with('mensaje_exito', 'Usuario y sus reservas eliminados correctamente.');

            } catch (\Exception $e) {
                // Si la base de datos se queja (por ejemplo, porque tiene Pedidos en la tienda)
                return redirect()->back()->with('mensaje_error', 'No se puede borrar este usuario porque tiene compras o historial en la tienda asociado a su cuenta.');
            }
            
        } else {
            return redirect()->to('/admin/usuarios')->with('mensaje_error', 'El usuario no existe.');
        }
    }

    // Funcion para Cambiar Rol
    public function cambiarRol($idUsuario)
    {
        if (!session()->get('is_logged_in') || session()->get('id_rol') != 1) {
            return redirect()->to('/')->with('mensaje_error', 'Acceso denegado.');
        }

        $usuarioModel = new UsuarioModel();
        $usuarioAEditar = $usuarioModel->find($idUsuario);

        if (!$usuarioAEditar) {
            return redirect()->back()->with('mensaje_error', 'Usuario no encontrado.');
        }

        // No puedes cambiarle el rol a otro Admin
        if ($usuarioAEditar['id_rol'] == 1 && $usuarioAEditar['id'] != session()->get('id')) {
            return redirect()->back()->with('mensaje_error', 'Por seguridad, no puedes cambiar el rol de otro Administrador.');
        }

        $nuevoRol = $this->request->getPost('nuevo_rol'); 
        $rolesValidos = [1, 2, 3, 4]; 
        
        if (!in_array($nuevoRol, $rolesValidos)) {
            return redirect()->back()->with('mensaje_error', 'El rol seleccionado no es válido.');
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