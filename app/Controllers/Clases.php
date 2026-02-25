<?php

namespace App\Controllers;

// Importo los modelos
use App\Controllers\BaseController;
use App\Models\ClasesModel;

class Clases extends BaseController
{
    // Funcion para mostrar una tabla con las clases
    // Funcion para mostrar una tabla con las clases (CON FILTROS Y PAGINACIÓN)
    public function index()
    {
        $clasesModel = new ClasesModel();
        $usuarioModel = new \App\Models\UsuarioModel();

        // Cargamos los entrenadores para el select
        $data['entrenadores'] = $usuarioModel->where('id_rol', 2)->findAll();

        // Capturamos los filtros de la URL
        $filtroEntrenador = $this->request->getGet('entrenador_id');
        $busqueda = $this->request->getGet('buscar');

        $clasesModel->select('clases.*, usuarios.nombre as nombre_entrenador, usuarios.apellidos as apellidos_entrenador')
                    ->join('usuarios', 'usuarios.id = clases.id_entrenador');

        // Ocultamos las clases pasadas
        date_default_timezone_set('Europe/Madrid');
        $fechaHoraActual = date('Y-m-d H:i:s');
        $clasesModel->where('clases.fecha_hora >', $fechaHoraActual);

        // Aplicamos el filtro por Entrenador
        if ($filtroEntrenador) {
            $clasesModel->where('clases.id_entrenador', $filtroEntrenador);
        }

        // Aplicamos el filtro por Nombre de la clase (NUEVO)
        if ($busqueda) {
            $clasesModel->like('clases.nombre', $busqueda);
        }

        // Ordenamos y PAGINAMOS (Ej: 10 clases por página)
        $clasesModel->orderBy('clases.fecha_hora', 'ASC');
        
        // Pasamos los datos y los enlaces de paginación a la vista
        $data['clases'] = $clasesModel->paginate(10);
        $data['pager'] = $clasesModel->pager;

        echo view('plantilla/header');
        echo view('admin/clases/lista_clases', $data);
        echo view('plantilla/footer');
    }

    // Funcion para crear una clase
    public function crear()
    {
        // Llamamos al modelo de Usuarios
        $usuarioModel = new \App\Models\UsuarioModel(); 
        
        // Buscamos SOLO a los entrenadores
        $data['entrenadores'] = $usuarioModel->where('id_rol', 2)->findAll();

        // Cargamos la vista pasando la lista de entrenadores ($data)
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
        
        // Verificación de existencia
        if (!$clase) {
            return redirect()->to('admin/clases')->with('mensaje_error', 'La clase no existe.');
        }

        // Si es Entrenador (rol 2) y la clase no le pertenece, denegar acceso
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