<?php

namespace App\Controllers;

// Importo el modelo
use App\Models\ClasesModel;

class Horarios extends BaseController
{
    // Funcion mostrar las clases en "Frontend"
    public function index()
    {
        $session = session();

        if (!$session->get('is_logged_in')) {
            return redirect()->to('/login');
        }

        if ($session->get('id_rol') == 3) {
            return redirect()->to('/')->with('mensaje_error', 'Debes ser Socio para reservar clases.');
        }

        $clasesModel = new \App\Models\ClasesModel();
        $reservasModel = new \App\Models\ReservasModel();
        
        $usuarioModel = new \App\Models\UsuarioModel();
        $data['entrenadores'] = $usuarioModel->where('id_rol', 2)->findAll();

        $busqueda = $this->request->getGet('buscar');
        $filtroEntrenador = $this->request->getGet('entrenador');
        $filtroPlazas = $this->request->getGet('plazas');

        // Capturamos la hora exacta
        $fechaHoraActual = date('Y-m-d H:i:s');

        // Preparamos la consulta base con JOIN y el filtro de FECHA OBLIGATORIO
        $clasesModel->select('clases.*, usuarios.nombre as nombre_entrenador, usuarios.apellidos as apellidos_entrenador')
            ->join('usuarios', 'usuarios.id = clases.id_entrenador')
            ->where('clases.fecha_hora >', $fechaHoraActual);

        // Filtro 1: Texto
        if (!empty($busqueda)) {
            $clasesModel->like('clases.nombre', $busqueda);
        }

        // Filtro 2: Entrenador
        if (!empty($filtroEntrenador)) {
            $clasesModel->where('clases.id_entrenador', $filtroEntrenador);
        }

        // Filtro 3: Plazas Libres
        if ($filtroPlazas == 'libres') {
            $clasesModel->where('(plazas_totales - (SELECT COUNT(id) FROM reservas WHERE reservas.id_clase = clases.id)) >', 0);
        }

        // Ordenamos y Paginamos de 6 en 6
        $clasesModel->orderBy('clases.fecha_hora', 'ASC');
        $clases = $clasesModel->paginate(6);

        // Calculamos las plazas libres (para mostrarlas en la tarjeta)
        foreach ($clases as &$clase) {
            $numReservas = $reservasModel->where('id_clase', $clase['id'])->countAllResults();
            $clase['plazas_libres'] = $clase['plazas_totales'] - $numReservas;
        }

        $data['clases'] = $clases;
        $data['pager'] = $clasesModel->pager;

        // Obtener reservas del usuario actual
        $idUsuario = $session->get('id');
        $misReservas = $reservasModel->where('id_usuario', $idUsuario)->findColumn('id_clase');
        $data['mis_reservas'] = $misReservas ?? [];

        echo view('plantilla/header');
        echo view('horarios', $data);
        echo view('plantilla/footer');
    }

    // Funcion para poder apuntarse a una clase
    public function reservar()
    {
        $idClase = $this->request->getPost('id_clase');
        $idUsuario = session()->get('id');

        if (!$idUsuario) {
            return redirect()->to('/login')->with('mensaje_error', 'Debes iniciar sesión para reservar.');
        }

        $clasesModel = new \App\Models\ClasesModel();
        $reservasModel = new \App\Models\ReservasModel();

        // 🌟 TRAMPA EVITADA: Comprobamos si la clase ya ha pasado
        $clase = $clasesModel->find($idClase);
        
        if (!$clase) {
            return redirect()->to('/horarios')->with('mensaje_error', 'La clase no existe.');
        }

        if (strtotime($clase['fecha_hora']) <= strtotime(date('Y-m-d H:i:s'))) {
            return redirect()->to('/horarios')->with('mensaje_error', 'Esta clase ya ha comenzado, no puedes apuntarte.');
        }

        // Comprobamos si el usuario ya está apuntado (Para evitar doble click)
        $reservaExistente = $reservasModel->where('id_clase', $idClase)->where('id_usuario', $idUsuario)->first();
        if ($reservaExistente) {
            return redirect()->to('/horarios')->with('mensaje_error', 'Ya estás apuntado a esta clase.');
        }

        // Contamos cuantos van
        $numReservas = $reservasModel->where('id_clase', $idClase)->countAllResults();

        // Comprobamos si hay plazas
        if ($numReservas < $clase['plazas_totales']) {
            $datosReserva = [
                'id_usuario' => $idUsuario,
                'id_clase'   => $idClase
            ];

            $reservasModel->insert($datosReserva);
            return redirect()->to('/horarios')->with('mensaje', '¡Reserva confirmada con éxito!');
        } else {
            return redirect()->back()->with('mensaje_error', '¡Lo sentimos! La clase se acaba de llenar.');
        }
    }

    // Funcion para borrarse de una clase
    public function cancelar()
    {
        // Recogemos datos
        $idClase = $this->request->getPost('id_clase');
        $session = session();
        $idUsuario = $session->get('id');

        // Si no esta logueado lo sacamos fuera
        if (!$session->get('is_logged_in')) {
            return redirect()->to('/login');
        }

        // Cargamos el modelo
        $reservasModel = new \App\Models\ReservasModel();

        // Lo borramos de la bd
        $reservasModel->where('id_clase', $idClase)
                    ->where('id_usuario', $idUsuario)
                    ->delete();

        // Lo redireccionamos con mensaje de éxito
        return redirect()->to('/horarios')->with('mensaje', 'Reserva cancelada correctamente.');
    }
}