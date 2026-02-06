<?php

namespace App\Controllers;
use App\Models\ClasesModel;

class Horarios extends BaseController
{

    public function index()
    {
        $session = session();

        // 1. Seguridad
        if (!$session->get('is_logged_in')) {
            return redirect()->to('/login');
        }
        if ($session->get('id_rol') == 3) {
            return redirect()->to('/')->with('mensaje_error', 'Debes ser Socio para reservar clases.');
        }

        // 2. Cargar modelos
        $clasesModel = new \App\Models\ClasesModel();
        $reservasModel = new \App\Models\ReservasModel();

        // 3. Obtener clases
        $clases = $clasesModel->select('clases.*, usuarios.nombre as nombre_entrenador')
            ->join('usuarios', 'usuarios.id = clases.id_entrenador')
            ->orderBy('fecha_hora', 'ASC')
            ->findAll();

        // --- CÁLCULO DE PLAZAS LIBRES (NUEVO) ---
        // Recorremos cada clase para calcular cuántas plazas quedan
        foreach ($clases as &$clase) {
            // Contamos cuántas reservas tiene esta clase específica
            $numReservas = $reservasModel->where('id_clase', $clase['id'])->countAllResults();
            
            // Calculamos: Totales - Ocupadas = Libres
            $clase['plazas_libres'] = $clase['plazas_totales'] - $numReservas;
        }
        // ----------------------------------------

        $data['clases'] = $clases;

        // 4. Obtener reservas del usuario actual
        $idUsuario = $session->get('id');
        $misReservas = $reservasModel->where('id_usuario', $idUsuario)->findColumn('id_clase');
        $data['mis_reservas'] = $misReservas ?? [];

        // 5. Cargar vistas
        echo view('plantilla/header');
        echo view('horarios', $data);
        echo view('plantilla/footer');
    }

    // Para apuntarse a una clase
    public function reservar()
    {
        // 1. Recogemos datos
        $idClase = $this->request->getPost('id_clase');
        $idUsuario = session()->get('id');

        // 2. Seguridad: Si no hay usuario que mos saque a fuera
        if (!$idUsuario) {
            return redirect()->to('/login')->with('mensaje_error', 'Debes iniciar sesión para reservar.');
        }

        // 3. Cargamos modelos
        $clasesModel = new \App\Models\ClasesModel();
        $reservasModel = new \App\Models\ReservasModel();

        // 4. Datos de la clase y contar cuantos van
        $clase = $clasesModel->find($idClase);
        $numReservas = $reservasModel->where('id_clase', $idClase)->countAllResults();

        // 5. Comprobamos aforo
        if ($numReservas < $clase['plazas_totales']) {
            // A) Hay sitio: Guardamos
            $datosReserva = [
                'id_usuario' => $idUsuario,
                'id_clase'   => $idClase
            ];

            $reservasModel->insert($datosReserva);

            // B) ¡ÉXITO! Redirigimos con mensaje positivo
            return redirect()->to('/horarios')->with('mensaje', '¡Reserva confirmada con éxito!');

        } else {
            // C) No hay sitio: Error
            return redirect()->back()->with('mensaje_error', '¡Lo sentimos! La clase está llena.');
        }
    }

    // Para borrarse de una clase
    public function cancelar()
    {
        // 1. Recogemos datos
        $idClase = $this->request->getPost('id_clase');
        $session = session();
        $idUsuario = $session->get('id');

        // 2. Seguridad: Si no hay usuario logueado, fuera
        if (!$session->get('is_logged_in')) {
            return redirect()->to('/login');
        }

        // 3. Cargamos el modelo
        $reservasModel = new \App\Models\ReservasModel();

        // 4. BORRADO SEGURO (El "truco" del encadenamiento) ⛓️
        // Esto se traduce en SQL como: DELETE FROM reservas WHERE id_clase = X AND id_usuario = Y
        $reservasModel->where('id_clase', $idClase)
                    ->where('id_usuario', $idUsuario)
                    ->delete();

        // 5. Redirección con mensaje de éxito
        return redirect()->to('/horarios')->with('mensaje', 'Reserva cancelada correctamente.');
    }
}