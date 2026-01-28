<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuarioModel;

class Perfil extends BaseController
{
    // Muestra el formulario con TUS datos actuales
    public function index()
    {
        $usuarioModel = new UsuarioModel();
        
        // Obtenemos el ID de la sesión (¿Quién soy?)
        $idUsuario = session()->get('id');
        
        // Buscamos los datos en la BD
        $data['usuario'] = $usuarioModel->find($idUsuario);

        // En lugar de: return view('perfil/editar', $data);
    
        echo view('plantilla/header');
        echo view('perfil/editar', $data);
        echo view('plantilla/footer');
    }

    // Para EDITAR los datos del usuario
    public function actualizar()
    {
        $usuarioModel = new UsuarioModel();
        $idUsuario = session()->get('id');

        // 1. Reglas fijas (siempre se validan)
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

        // 3. Validamos todo junto
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // 4. Preparamos los datos
        $data = [
            'nombre'    => $this->request->getPost('nombre'),
            'apellidos' => $this->request->getPost('apellidos'),
            'email'     => $this->request->getPost('email'),
        ];

        // Si hay contraseña nueva, la encriptamos y la guardamos
        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        // 5. Actualizamos en BD y Sesión
        $usuarioModel->update($idUsuario, $data);
        
        // Actualizamos el nombre en la sesión por si lo cambió
        session()->set('nombre', $data['nombre']);

        return redirect()->to('/perfil')->with('mensaje_exito', '¡Tus datos se han actualizado!');
    }
}