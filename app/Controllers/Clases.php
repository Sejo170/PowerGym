<?php

namespace App\Controllers;

// Importo los modelos
use App\Controllers\BaseController;
use App\Models\ClasesModel;

class Clases extends BaseController
{
    // Funcion para mostrar una tabla con las clases
    public function index()
    {
        // Instanciamos el modelo
        $clasesModel = new ClasesModel();
        
        // 1. Cargamos el modelo de Usuarios para llenar el desplegable
        $usuarioModel = new \App\Models\UsuarioModel();
        $data['entrenadores'] = $usuarioModel->where('id_rol', 2)->findAll();

        // 2. Capturamos el filtro de la URL usando el GET
        $filtroEntrenador = $this->request->getGet('entrenador_id');

        // 3. Construimos la consulta para poder filtrar
        // Seleccionamos los datos de la clase y los nombres del entrenador
        $clasesModel->select('clases.*, usuarios.nombre as nombre_entrenador, usuarios.apellidos as apellidos_entrenador')
                    ->join('usuarios', 'usuarios.id = clases.id_entrenador');

        // 4. Si hay filtro seleccionado, lo aplicamos
        if ($filtroEntrenador) {
            $clasesModel->where('clases.id_entrenador', $filtroEntrenador);
        }

        // 5. Ejecutar la consulta
        $data['clases'] = $clasesModel->findAll();

        // 6. Cargar vistas
        echo view('plantilla/header');
        echo view('admin/clases/lista_clases', $data);
        echo view('plantilla/footer');
    }

    // Funcion para crear una clase
    public function crear()
    {
        // 1. Llamamos al modelo de Usuarios
        $usuarioModel = new \App\Models\UsuarioModel(); 
        
        // 2. Buscamos SOLO a los entrenadores
        $data['entrenadores'] = $usuarioModel->where('id_rol', 2)->findAll();

        // 3. Cargamos la vista pasando la lista de entrenadores ($data)
        echo view('plantilla/header');
        echo view('admin/clases/crear_clase', $data);
        echo view('plantilla/footer');
    }

    // Funcion para guardar la clase
    public function guardar()
    {
        // Instanciamos el modelo
        $clasesModel = new ClasesModel();

        // Creamos el paquete de datos (Array)
        $datos = [
            'nombre'         => $this->request->getPost('nombre'),
            'descripcion'    => $this->request->getPost('descripcion'),
            'fecha_hora'     => $this->request->getPost('fecha_hora'),
            'plazas_totales' => $this->request->getPost('plazas_totales'),
            'id_entrenador'  => $this->request->getPost('id_entrenador'),
        ];

        // Guardamos los datos
        $clasesModel->save($datos);

        // Volvemos a la tabla y mostramos mensaje de éxito
        return redirect()->to('admin/clases')->with('mensaje_exito', '¡Clase creada correctamente!');
    }

    // Funcion para borrar los datos CON SEGURIDAD
    public function borrar($id)
    {
        // Verificamos si hay alguien logueado por seguridad básica
        if (!session()->get('is_logged_in')) {
            return redirect()->to('/')->with('mensaje_error', 'Acceso denegado.');
        }

        $clasesModel = new ClasesModel();
        
        // 1. Buscamos la clase en la base de datos ANTES de intentar borrarla
        $claseABorrar = $clasesModel->find($id);

        // 2. Comprobamos si la clase realmente existe
        if ($claseABorrar) {
            
            // Guardamos los datos del usuario actual en variables para que sea más fácil de leer
            $rolUsuario = session()->get('id_rol');
            $idUsuario = session()->get('id');

            // 3. LA REGLA DE SEGURIDAD CLAVE:
            // Si el usuario es un Entrenador (rol 2) Y (&&) el ID del entrenador de la clase NO coincide con el suyo (!=)
            if ($rolUsuario == 2 && $claseABorrar['id_entrenador'] != $idUsuario) {
                // Lo bloqueamos y le enviamos un mensaje de error
                return redirect()->back()->with('mensaje_error', 'No tienes permiso para borrar la clase de otro entrenador.');
            }

            // Si es un Admin (rol 1) pasará de largo el 'if' anterior sin problemas.
            // Si es el entrenador dueño de la clase, también pasará de largo.
            
            // 4. Borramos la clase
            $clasesModel->delete($id);
            return redirect()->to('admin/clases')->with('mensaje_exito', 'Clase eliminada correctamente.');
            
        } else {
            return redirect()->to('admin/clases')->with('mensaje_error', 'La clase no existe.');
        }
    }

    // Funcion para Editar una clase
    public function editar($id)
    {
        // Instanciamos los modelos
        $clasesModel = new ClasesModel();
        $usuariosModel = new \App\Models\UsuarioModel();

        // Recuperamos la clase existente usando su ID
        $data['clase'] = $clasesModel->find($id);
        
        // Recuperamos los entrenadores para llenar el select
        $data['entrenadores'] = $usuariosModel->where('id_rol', 2)->findAll();

        // Cargamos la vista específica de edición
        echo view('plantilla/header');
        echo view('admin/clases/editar_clase', $data);
        echo view('plantilla/footer');
    }

    // Funcion para actualizar el producto
    public function actualizar()
    {
        // Si los datos no cumplen las reglas, no les dejamos pasar
        if (! $this->validate([
            'nombre'          => 'required|min_length[3]',
            'plazas_totales'  => 'required|integer|greater_than[0]',
            'id_entrenador'   => 'required'
        ])) {
            // Si falla, los mandamos de vuelta al formulario con los errores
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Instanciamos el modelo
        $clasesModel = new ClasesModel();

        // Recogemos el ID
        $id = $this->request->getPost('id');

        // Recogemos los datos
        $datos = [
            'nombre'         => $this->request->getPost('nombre'),
            'descripcion'    => $this->request->getPost('descripcion'),
            'fecha_hora'     => $this->request->getPost('fecha_hora'),
            'plazas_totales' => $this->request->getPost('plazas_totales'),
            'id_entrenador'  => $this->request->getPost('id_entrenador'),
        ];

        // Actualizamos en la bd
        $clasesModel->update($id, $datos);

        // Redirigimos y mostramos un mensaje
        return redirect()->to('admin/clases')->with('mensaje_exito', '¡Clase actualizada correctamente!');
    }
}