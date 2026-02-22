<?php

namespace App\Controllers;

// Importo los modelos
use App\Controllers\BaseController;
use App\Models\UsuarioModel;

class Perfil extends BaseController
{
    // Funcion para mostrar el formulario con tus datos actuales
    public function index()
    {
        $idUsuario = session()->get('id');
        
        // Cargamos modelos con la ruta completa para evitar fallos de "use"
        $usuarioModel  = new \App\Models\UsuarioModel();
        $reservasModel = new \App\Models\ReservasModel();
        $pedidosModel  = new \App\Models\PedidosModel(); 
        
        $data['usuario'] = $usuarioModel->find($idUsuario);

        // Reservas Pendientes
        $data['reservas_pendientes'] = $reservasModel->select('clases.*, usuarios.nombre as nombre_entrenador')
            ->join('clases', 'clases.id = reservas.id_clase')
            ->join('usuarios', 'usuarios.id = clases.id_entrenador')
            ->where('reservas.id_usuario', $idUsuario)
            ->where('clases.fecha_hora >=', date('Y-m-d H:i:s'))
            ->orderBy('clases.fecha_hora', 'ASC')
            ->findAll();

        // Historial Clases
        $data['historial_clases'] = $reservasModel->select('clases.*, usuarios.nombre as nombre_entrenador')
            ->join('clases', 'clases.id = reservas.id_clase')
            ->join('usuarios', 'usuarios.id = clases.id_entrenador')
            ->where('reservas.id_usuario', $idUsuario)
            ->where('clases.fecha_hora <', date('Y-m-d H:i:s'))
            ->orderBy('clases.fecha_hora', 'DESC')
            ->findAll();

        // Pedidos
        $data['mis_pedidos'] = $pedidosModel->select('pedidos.*, GROUP_CONCAT(productos.nombre SEPARATOR ", ") as nombres_productos')
            ->join('lineas_pedidos', 'lineas_pedidos.id_pedido = pedidos.id')
            ->join('productos', 'productos.id = lineas_pedidos.id_producto')
            ->where('pedidos.id_usuario', $idUsuario)
            ->groupBy('pedidos.id')
            ->orderBy('pedidos.fecha', 'DESC')
            ->paginate(5, 'pedidos');

        // Pasamos el paginador a la vista
        $data['pager'] = $pedidosModel->pager;

        echo view('plantilla/header');
        echo view('perfil/editar', $data);
        echo view('plantilla/footer');
    }

    // Funcion para editar los datos del usuario
    public function actualizar()
    {
        // Instanciamos el model
        $usuarioModel = new UsuarioModel();
        $idUsuario = session()->get('id');

        // Reglas fijas siempre se validan
        $rules = [
            'nombre'    => 'required|min_length[3]',
            'apellidos' => 'required|min_length[3]',
            'email'     => "required|valid_email|is_unique[usuarios.email,id,$idUsuario]" 
        ];

        // 2. Lógica condicional para la contraseña
        $password = $this->request->getPost('password');

        if (!empty($password)) {
            // Si el usuario escribió algo, añadimos estas reglas a la lista
            $rules['password']     = 'required|min_length[4]';
            $rules['pass_confirm'] = 'required|matches[password]';
        }

        // Validamos todo
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Preparamos los datos
        $data = [
            'nombre'    => $this->request->getPost('nombre'),
            'apellidos' => $this->request->getPost('apellidos'),
            'email'     => $this->request->getPost('email'),
        ];

        // Si hay contraseña nueva, la encriptamos y la guardamos
        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        // Actualizamos la BD y Sesión
        $usuarioModel->update($idUsuario, $data);
        
        // Actualizamos el nombre en la sesión por si lo a cambiado
        session()->set('nombre', $data['nombre']);

        return redirect()->to('/perfil')->with('mensaje_exito', '¡Tus datos se han actualizado!');
    }
}