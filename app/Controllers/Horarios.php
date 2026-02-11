<?php

namespace App\Controllers;

// Importo el modelo
use App\Models\ClasesModel;

class Horarios extends BaseController
{
    // Funcion mostrar las clases en "Frontend"
    public function index()
    {
        // Iniciamos la sesion
        $session = session();

        // Seguridad
        // Si no estamos logeado nos manda al login
        if (!$session->get('is_logged_in')) {
            return redirect()->to('/login');
        }

        // Si intenta apuntarse una persona que no es socio muestra un mensaje de error
        if ($session->get('id_rol') == 3) {
            return redirect()->to('/')->with('mensaje_error', 'Debes ser Socio para reservar clases.');
        }

        // Cargar modelos
        $clasesModel = new \App\Models\ClasesModel();
        $reservasModel = new \App\Models\ReservasModel();

        // Obtenemos las clases
        $clases = $clasesModel->select('clases.*, usuarios.nombre as nombre_entrenador')
            ->join('usuarios', 'usuarios.id = clases.id_entrenador')
            ->orderBy('fecha_hora', 'ASC')
            ->findAll();

        // Calculamos las plaza libres
        // Recorremos cada clase para calcular cuántas plazas quedan
        foreach ($clases as &$clase) {
            // Contamos cuántas reservas tiene esta clase específica
            $numReservas = $reservasModel->where('id_clase', $clase['id'])->countAllResults();
            
            // Calculamos: Totales - Ocupadas = Libres
            $clase['plazas_libres'] = $clase['plazas_totales'] - $numReservas;
        }

        $data['clases'] = $clases;

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