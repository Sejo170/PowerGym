<?php 
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthGuard implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // 1. ¿Está el usuario logueado?
        // Buscamos la marca 'is_logged_in' que creamos en el Login.php
        if (!session()->get('is_logged_in')) {
            // Si no está logueado, lo mandamos al login
            return redirect()->to('/login');
        }

        // 2. ¿Tiene permisos de Administrador o Entrenador?
        // Rol 1 = Admin, Rol 2 = Entrenador
        $rol = session()->get('id_rol');
        
        if ($rol != 1 && $rol != 2) {
            // Si es un cliente normal (Rol 3) o Socio (Rol 4), no puede entrar aquí.
            // Lo mandamos a la página de inicio.
            return redirect()->to('/');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}