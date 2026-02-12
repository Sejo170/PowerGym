<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');
$routes->get('home/obtenerFrase', 'Home::obtenerFrase'); // API Frase Motivadora

// --------------
// RUTAS PUBLICAS
// --------------

// Rutas de Registro
$routes->get('register', 'Register::index');   // Muestra el formulario de registro
$routes->post('register/save', 'Register::save'); // Procesa el formulario de registro

// Rutas de Login
// Rutas de Autenticación
$routes->get('login', 'Login::index');   // Muestra el formulario del login
$routes->post('auth', 'Login::auth');    // Procesa el formulario (Tu base_url('auth'))
$routes->get('logout', 'Login::logout'); // Cierra la sesión

// Rutas de Horarios
$routes->get('horarios', 'Horarios::index');
$routes->post('horarios/reservar', 'Horarios::reservar'); // Cuando pulsemos al boton de reserva que funcione
$routes->post('horarios/cancelar', 'Horarios::cancelar'); // Cuando pulsemos el boton se cancelara la reserva

// Rutas de Tienda
$routes->get('tienda', 'Tienda::index');
$routes->get('tienda/agregar/(:num)', 'Tienda::agregar/$1');

// Carrito Compra
$routes->get('carrito', 'Carrito::index');
$routes->get('carrito/confirmar', 'Carrito::confirmar');
$routes->get('carrito/eliminar/(:num)', 'Carrito::eliminar/$1');
$routes->post('carrito/actualizar', 'Carrito::actualizar');


// --------------
// RUTA PRIVADA
// --------------

// Grupo de rutas para el Administrador
$routes->group('admin', ['filter' => 'authGuard'], function($routes) {
    
    // La página principal del panel
    $routes->get('/', 'Admin::index');

    // Para mostrar la lista de usuarios
    $routes->get('usuarios', 'Admin::usuarios');

    // Para borrar usuario
    // (:num) es un comodín que acepta solo números
    $routes->post('borrarUsuario/(:num)', 'Admin::borrarUsuario/$1');

    // Para cambiar el rol
    // Aquí esperamos dos números: ID del usuario y ID del nuevo rol
    $routes->post('cambiarRol/(:num)/(:num)', 'Admin::cambiarRol/$1/$2');

    // API para las gráficas
    $routes->get('datosGrafica', 'Admin::datosGrafica');

    // --- GESTIÓN DE PRODUCTOS ---
    $routes->get('productos', 'Productos::index');
    $routes->get('productos/crear', 'Productos::crear');
    $routes->post('productos/guardar', 'Productos::guardar');
    $routes->get('productos/borrar/(:num)', 'Productos::borrar/$1');
    $routes->get('productos/editar/(:num)', 'Productos::editar/$1');
    $routes->post('productos/actualizar', 'Productos::actualizar');
    
    // (Opcionales para cuando programes borrar/editar)
    $routes->delete('productos/borrar/(:num)', 'Productos::borrar/$1');
    $routes->get('productos/editar/(:num)', 'Productos::editar/$1');

    // --- GESTIÓN DE CLASES ---
    $routes->get('clases', 'Clases::index'); // Ver la lista
    $routes->get('clases/crear', 'Clases::crear'); // Ver el formulario
    $routes->post('clases/guardar', 'Clases::guardar'); // Recibir los datos (POST)
    $routes->get('clases/borrar/(:num)', 'Clases::borrar/$1'); // Borrar clase
    $routes->post('clases/actualizar', 'Clases::actualizar'); // Actualizar las clases
    $routes->get('clases/editar/(:num)', 'Clases::editar/$1');
});

// Grupo para usuarios logueados (Cualquier rol)
$routes->group('perfil', ['filter' => 'authBasic'], function($routes) {
    $routes->get('/', 'Perfil::index');
    $routes->post('actualizar', 'Perfil::actualizar');
});

?>
