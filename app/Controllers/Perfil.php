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
        // Instanciamos el modelo
        $usuarioModel = new UsuarioModel();
        
        // Obtenemos el ID de la sesión
        $idUsuario = session()->get('id');
        
        // Buscamos los datos en la BD
        $data['usuario'] = $usuarioModel->find($idUsuario);

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