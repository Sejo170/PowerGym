<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');

// --------------
// RUTAS PUBLICAS
// --------------

// Rutas de Registro
$routes->get('register', 'Register::index');   // Muestra el formulario
$routes->post('register/save', 'Register::save'); // Procesa el formulario

// RUTAS DE LOGIN (Públicas - Van fuera del grupo)
// Rutas de Autenticación
$routes->get('login', 'Login::index');   // Muestra el formulario
$routes->post('auth', 'Login::auth');    // Procesa el formulario (Tu base_url('auth'))
$routes->get('logout', 'Login::logout'); // Cierra la sesión

// --------------
// RUTA PRIVADA
// --------------

// Grupo de rutas para el Administrador
// Las direcciones que empiecen por /admin son controladas por Admin
$routes->group('admin', ['filter' => 'authGuard'], function($routes) {
    
    // 1. La página principal del panel
    // URL: tudominio.com/admin
    // Llama a: Controlador Admin, función index
    $routes->get('/', 'Admin::index');

    // 2. Ruta para borrar usuario
    // URL: tudominio.com/admin/borrarUsuario/5
    // (:num) es un comodín que acepta solo números
    $routes->get('borrarUsuario/(:num)', 'Admin::borrarUsuario/$1');

    // 3. Ruta para cambiar el rol
    // URL: tudominio.com/admin/cambiarRol/5/4
    // Aquí esperamos dos números: ID del usuario y ID del nuevo rol
    $routes->get('cambiarRol/(:num)/(:num)', 'Admin::cambiarRol/$1/$2');
});

// Grupo para usuarios logueados (Cualquier rol)
$routes->group('perfil', ['filter' => 'authBasic'], function($routes) {
    $routes->get('/', 'Perfil::index');
    $routes->post('actualizar', 'Perfil::actualizar');
});

?>
