<?php 
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthBasic implements FilterInterface // <--- Nombre actualizado
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Solo comprobamos si ha iniciado sesión
        if (!session()->get('is_logged_in')) {
            return redirect()->to('/login');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nada aquí
    }
}