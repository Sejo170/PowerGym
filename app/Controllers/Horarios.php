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
        
        // Cargamos el modelo de usuarios para traer a los entrenadores al filtro
        $usuarioModel = new \App\Models\UsuarioModel();
        $data['entrenadores'] = $usuarioModel->where('id_rol', 2)->findAll();

        // Capturamos los 3 posibles filtros de la URL
        $busqueda = $this->request->getGet('buscar');
        $filtroEntrenador = $this->request->getGet('entrenador');
        $filtroPlazas = $this->request->getGet('plazas');

        // Preparamos la consulta base
        $clasesModel->select('clases.*, usuarios.nombre as nombre_entrenador, usuarios.apellidos as apellidos_entrenador')
            ->join('usuarios', 'usuarios.id = clases.id_entrenador')
            ->where('fecha_hora >=', date('Y-m-d H:i:s'))
            ->orderBy('fecha_hora', 'ASC');

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
            // Usamos una subconsulta SQL nativa para restar el total - las reservas
            $clasesModel->where('(plazas_totales - (SELECT COUNT(id) FROM reservas WHERE reservas.id_clase = clases.id)) >', 0);
        }

        // Paginamos de 6 en 6
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

        // Cargamos las vistas
        echo view('plantilla/header');
        echo view('horarios', $data);
        echo view('plantilla/footer');
    }

    // Funcion para poder apuntarse a una clase
    public function reservar()
    {
        // Recogemos los datos (Clase y usuario)
        $idClase = $this->request->getPost('id_clase');
        $idUsuario = session()->get('id');

        // Si no ha iniciado sesion que nos saque fuera
        if (!$idUsuario) {
            return redirect()->to('/login')->with('mensaje_error', 'Debes iniciar sesión para reservar.');
        }

        // Cargamos modelos
        $clasesModel = new \App\Models\ClasesModel();
        $reservasModel = new \App\Models\ReservasModel();

        // Datos de la clase y contar cuantos van
        $clase = $clasesModel->find($idClase);
        $numReservas = $reservasModel->where('id_clase', $idClase)->countAllResults();

        // Comprobamos si hay plazas
        if ($numReservas < $clase['plazas_totales']) {
            // Si hay hueco lo apuntamos
            $datosReserva = [
                'id_usuario' => $idUsuario,
                'id_clase'   => $idClase
            ];

            // Lo apuntamos
            $reservasModel->insert($datosReserva);

            // Redirigimos con mensaje de exito
            return redirect()->to('/horarios')->with('mensaje', '¡Reserva confirmada con éxito!');

        } else {
            // Si no hay sitio mostramos un mensahe de error
            return redirect()->back()->with('mensaje_error', '¡Lo sentimos! La clase está llena.');
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