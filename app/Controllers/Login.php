<?php

namespace App\Controllers;

use App\Models\UsuarioModel;

class Login extends BaseController
{
    // Muestra el formulario de login. URL: tudominio.com/login
    public function index()
    {
        // Simplemente cargamos la vista del formulario
        echo view('plantilla/header');
        echo view('auth/login');
        echo view('plantilla/footer');
    }

    /**
     * Recibe los datos del formulario y comprueba si son válidos.
     * Esta función se ejecuta cuando pulsas "Entrar".
     */
    public function auth()
    {
        // 1. Recogemos los datos que envió el usuario
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // 2. Buscamos al usuario en la base de datos por su email
        $usuarioModel = new UsuarioModel();
        
        // 'where' busca una fila donde el email coincida y 'first' nos da solo ese resultado
        $usuario = $usuarioModel->where('email', $email)->first();

        // 3. VERIFICACIÓN DE SEGURIDAD
        // A. Comprobamos si el usuario existe ($usuario)
        // B. Comprobamos si la contraseña coincide usando password_verify()
        //    * IMPORTANTE: Nunca comparamos texto plano (1234 == 1234).
        //    * password_verify compara la contraseña escrita con el HASH de la base de datos.
        if ($usuario && password_verify($password, $usuario['password'])) {
            
            // --- ¡ÉXITO! ---
            
            // 4. Creamos la SESIÓN (El "Carnet de Identidad")
            // Guardamos los datos importantes para que el sistema sepa quién es
            $datosSesion = [
                'id'       => $usuario['id'],       // CRÍTICO: Esto usa el Admin.php para protegerse
                'nombre'   => $usuario['nombre'],
                'id_rol'   => $usuario['id_rol'],   // Para saber si es admin, cliente, etc.
                'is_logged_in' => true              // Una marca para saber que está dentro
            ];

            session()->set($datosSesion);

            // 5. Redirigimos según el rol
            if ($usuario['id_rol'] == 1) {
                // Si es Admin, va al panel general
                return redirect()->to('/admin');
            } elseif ($usuario['id_rol'] == 2) {
                // Si es Entrenador, va DIRECTO a gestionar clases
                return redirect()->to('/admin/clases');
            } else {
                // Clientes y otros van a la portada
                return redirect()->to('/'); 
            }

        } else {
            // --- ERROR ---
            // Si el email no existe o la contraseña está mal
            return redirect()->back()->with('mensaje_error', 'Email o contraseña incorrectos');
        }
    }

    //Cierra la sesión (Logout)
    public function logout()
    {
        session()->destroy(); // Destruye el "carnet"
        return redirect()->to('/login');
    }
}