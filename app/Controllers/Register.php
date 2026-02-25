<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuarioModel;

class Register extends BaseController
{
    // Muestra el formulario de registro
    public function index()
    {
        helper(['form']);
        
        echo view('plantilla/header');
        echo view('auth/register');
        echo view('plantilla/footer');
    }

    // Procesa el formulario y crea el usuario
    public function save()
    {
        // Validamos los datos
        $rules = [
            'nombre'      => 'required|min_length[3]',
            'apellidos'   => 'required|min_length[3]',
            'email'       => 'required|valid_email|is_unique[usuarios.email]',
            'password'    => 'required|min_length[4]',
            'pass_confirm'=> 'matches[password]' // Debe coincidir con el campo password
        ];

        if (!$this->validate($rules)) {
            // Si falla algo, volvemos al formulario y mostramos los errores
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Preparamos los datos para guardar
        $usuarioModel = new UsuarioModel();

        $data = [
            'nombre'    => $this->request->getPost('nombre'),
            'apellidos' => $this->request->getPost('apellidos'),
            'email'     => $this->request->getPost('email'),
            'password'  => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT), // Encriptamos
            'id_rol'    => 3
        ];

        // Guardamos en la base de datos
        $usuarioModel->save($data);

        // Lo mandamos al login para que entre
        return redirect()->to('/login')->with('mensaje_exito', '¡Registro completado! Ahora puedes iniciar sesión.');
    }
}