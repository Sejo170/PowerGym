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
        // 1. Instanciamos los modelos necesarios
        $clasesModel = new ClasesModel();
        $usuarioModel = new \App\Models\UsuarioModel();

        // 2. Cargamos la lista de entrenadores para el desplegable del filtro
        $data['entrenadores'] = $usuarioModel->where('id_rol', 2)->findAll();

        // 3. Capturamos el ID del entrenador desde la URL
        $filtroEntrenador = $this->request->getGet('entrenador_id');

        // 4. Preparamos la consulta con JOIN para traer los nombres de los entrenadores
        $clasesModel->select('clases.*, usuarios.nombre as nombre_entrenador, usuarios.apellidos as apellidos_entrenador')
                    ->join('usuarios', 'usuarios.id = clases.id_entrenador');

        // 5. APLICAMOS EL FILTRO
        if ($filtroEntrenador) {
            $clasesModel->where('clases.id_entrenador', $filtroEntrenador);
        }

        // 6. Ejecutamos la consulta y guardamos los resultados
        $data['clases'] = $clasesModel->orderBy('clases.fecha_hora', 'ASC')->findAll();

        // 7. Cargamos las vistas
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

        $fechaPost = $this->request->getPost('fecha_hora');

        $fechaLimpia = date('Y-m-d H:i:s', strtotime($fechaPost));

        $tiempoInicio = date('Y-m-d H:i:s', strtotime($fechaLimpia . ' -30 minutes'));
        $tiempoFin    = date('Y-m-d H:i:s', strtotime($fechaLimpia . ' +30 minutes'));

        $clasesConflicto = $clasesModel->where("fecha_hora BETWEEN '$tiempoInicio' AND '$tiempoFin'")
            ->countAllResults();

        if ($clasesConflicto > 0) {
            return redirect()->back()->withInput()->with('mensaje_error', 'Ya existe una clase programada en ese horario. Deja al menos 30 minutos de margen entre clases.');
        }

        $datos = [
            'nombre'         => $this->request->getPost('nombre'),
            'descripcion'    => $this->request->getPost('descripcion'),
            'fecha_hora'     => $fechaLimpia,
            'plazas_totales' => $this->request->getPost('plazas_totales'),
            'id_entrenador'  => $this->request->getPost('id_entrenador'),
        ];

        // Guardamos los datos
        $clasesModel->save($datos);

        // Volvemos a la tabla y mostramos mensaje de éxito
        return redirect()->to('admin/clases')->with('mensaje_exito', '¡Clase creada correctamente sin solapamientos!');
    }

    // Funcion para borrar los datos CON SEGURIDAD Y LIMPIEZA DE DEPENDENCIAS
    public function borrar($id)
    {
        if (!session()->get('is_logged_in')) {
            return redirect()->to('/')->with('mensaje_error', 'Acceso denegado.');
        }

        $clasesModel = new ClasesModel();
        $reservasModel = new \App\Models\ReservasModel(); 
        
        $claseABorrar = $clasesModel->find($id);

        if ($claseABorrar) {
            // SEGURIDAD: Solo Admin o el dueño borran
            if (session()->get('id_rol') == 2 && $claseABorrar['id_entrenador'] != session()->get('id')) {
                return redirect()->to('admin/clases')->with('mensaje_error', 'No tienes permiso para borrar esta clase.');
            }

            // Primero eliminamos dependencias (reservas)
            $reservasModel->where('id_clase', $id)->delete();
            
            $clasesModel->delete($id);
            return redirect()->to('admin/clases')->with('mensaje_exito', 'Clase y sus reservas eliminadas correctamente.');
        } 
        
        return redirect()->to('admin/clases')->with('mensaje_error', 'La clase no existe.');
    }

    // Funcion para Editar una clase
    public function editar($id)
    {
        $clasesModel = new ClasesModel();
        $usuariosModel = new \App\Models\UsuarioModel();

        // Recuperamos la clase
        $clase = $clasesModel->find($id);
        
        // 1. Verificación de existencia
        if (!$clase) {
            return redirect()->to('admin/clases')->with('mensaje_error', 'La clase no existe.');
        }

        // 2. Si es Entrenador (rol 2) y la clase no le pertenece, denegar acceso
        if (session()->get('id_rol') == 2 && $clase['id_entrenador'] != session()->get('id')) {
            return redirect()->to('admin/clases')->with('mensaje_error', 'No tienes permiso para editar clases de otros entrenadores.');
        }

        $data['clase'] = $clase;
        $data['entrenadores'] = $usuariosModel->where('id_rol', 2)->findAll();

        echo view('plantilla/header');
        echo view('admin/clases/editar_clase', $data);
        echo view('plantilla/footer');
    }

    // Funcion para actualizar el producto
    public function actualizar()
    {
        $clasesModel = new ClasesModel();
        $id = $this->request->getPost('id');
        $claseOriginal = $clasesModel->find($id);

        if (session()->get('id_rol') == 2 && $claseOriginal['id_entrenador'] != session()->get('id')) {
            return redirect()->to('admin/clases')->with('mensaje_error', 'Acción denegada: No puedes modificar esta clase.');
        }

        // Si los datos no cumplen las reglas, no les dejamos pasar
        if (! $this->validate([
            'nombre'          => 'required|min_length[3]',
            'plazas_totales'  => 'required|integer|greater_than[0]',
            'id_entrenador'   => 'required'
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $fechaPost = $this->request->getPost('fecha_hora');
        $fechaLimpia = date('Y-m-d H:i:s', strtotime($fechaPost));
        $tiempoInicio = date('Y-m-d H:i:s', strtotime($fechaLimpia . ' -30 minutes'));
        $tiempoFin    = date('Y-m-d H:i:s', strtotime($fechaLimpia . ' +30 minutes'));

        $clasesConflicto = $clasesModel->where("fecha_hora BETWEEN '$tiempoInicio' AND '$tiempoFin'")
            ->where('id !=', $id) 
            ->countAllResults();

        if ($clasesConflicto > 0) {
            return redirect()->back()->withInput()->with('mensaje_error', 'Conflicto de horario: Ya existe otra clase programada.');
        }

        $datos = [
            'nombre'         => $this->request->getPost('nombre'),
            'descripcion'    => $this->request->getPost('descripcion'),
            'fecha_hora'     => $fechaLimpia,
            'plazas_totales' => $this->request->getPost('plazas_totales'),
            'id_entrenador'  => $this->request->getPost('id_entrenador'),
        ];

        $clasesModel->update($id, $datos);
        return redirect()->to('admin/clases')->with('mensaje_exito', '¡Clase actualizada correctamente!');
    }
}