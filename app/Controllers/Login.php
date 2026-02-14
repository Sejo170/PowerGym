<?php

namespace App\Controllers;

// Importo el modelo
use App\Models\UsuarioModel;

class Login extends BaseController
{
    // Funcion que muestra el formulario de login
    public function index()
    {
        // Cargamos la vista del formulario
        echo view('plantilla/header');
        echo view('auth/login');
        echo view('plantilla/footer');
    }

    
    // Recibe los datos del formulario y comprueba si son válidos.
    // Esta función se ejecuta cuando pulsas "Entrar".
    public function auth()
    {
        // 1. Recogemos los datos que envió el usuario
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // 2. Buscamos el usuario en la base de datos por su email
        $usuarioModel = new UsuarioModel();
        $usuario = $usuarioModel->where('email', $email)->first();

        // 3. VERIFICACIÓN DE SEGURIDAD
        // Comprobamos si el usuario existe ($usuario)
        // Comprobamos si la contraseña coincide usando password_verify()
        // Nunca comparamos texto plano (1234 == 1234).
        // password_verify compara la contraseña escrita con el HASH de la base de datos.
        if ($usuario && password_verify($password, $usuario['password'])) {
            
            // 4. Creamos la SESIÓN (El "Carnet de Identidad")
            // Guardamos los datos importantes para que el sistema sepa quién es
            $datosSesion = [
                'id'       => $usuario['id'],
                'nombre'   => $usuario['nombre'],
                'id_rol'   => $usuario['id_rol'],
                'is_logged_in' => true              // Una marca para saber que está dentro
            ];

            session()->set($datosSesion);

            // Redirigimos según el rol que tengan
            if ($usuario['id_rol'] == 1) {
                // Si es Admin, va al panel general
                return redirect()->to('/admin');
            } elseif ($usuario['id_rol'] == 2) {
                // Si es Entrenador, va directo a gestionar clases
                return redirect()->to('/admin/clases');
            } else {
                // Clientes y otros van a la portada
                return redirect()->to('/'); 
            }

        } else {
            // Si el email no existe o la contraseña está mal
            return redirect()->back()->with('mensaje_error', 'Email o contraseña incorrectos');
        }
    }

    // Funcion para cerrar la sesión (Logout)
    public function logout()
    {
        // Destruye la sesion y nos redirige al login
        session()->destroy();
        return redirect()->to('/login');
    }
}