<?php

namespace App\Controllers;

// Importo los modelos
use App\Controllers\BaseController;
use App\Models\ProductosModel;
use App\Models\PedidosModel;
use App\Models\LineasPedidosModel;

class Carrito extends BaseController
{
    // Funcion para mostrar una tabla con lo que hay en el carrito
    public function index()
    {
        // Creamos la sesion
        $session = session();
        $productosModel = new ProductosModel();

        // 1. Recupera lo que el usuario ha añadido al carrito
        $carrito = $session->get('carrito');
        
        $datosVista = [];

        // Verifica si hay algo
        if (empty($carrito)) {
            // Si está vacío, mandamos una lista vacía
            $datosVista['productos'] = []; 
        } else {
            // 2. Extraemos los IDs de los productos guardados
            $ids = array_keys($carrito);

            // 3. Busco todos esos productos en la BD
            $datosVista['productos'] = $productosModel->find($ids);
        }

        echo view('plantilla/header');
        echo view('carrito/index', $datosVista);
        echo view('plantilla/footer');
    }

    // Funcion para confirmar el pedido
    public function confirmar()
    {
        $session = session();
        $carrito = $session->get('carrito');

        // Si el carrito está vacío, los redirigimos a otro lado
        if (empty($carrito)) {
            return redirect()->to(base_url('tienda'));
        }

        // 1. Calculamos el total
        $productosModel = new ProductosModel();
        $ids = array_keys($carrito);
        $productos = $productosModel->find($ids);

        $total = 0;
        foreach ($productos as $producto) {
            $cantidad = $carrito[$producto['id']];
            $total += $producto['precio'] * $cantidad;
        }

        // Guardamos el pedido en la bd
        $pedidosModel = new PedidosModel();
        
        $id_usuario = $session->get('id'); 

        $datosPedido = [
            'id_usuario' => $id_usuario,
            'total'      => $total
        ];

        // insert() guarda el pedido y DEVUELVE el ID nuevo (ej: 54)
        $id_pedido = $pedidosModel->insert($datosPedido);

        $lineasModel = new LineasPedidosModel();

        foreach ($productos as $producto) {
            // 1. Guardamos la línea del pedido (Como antes)
            $datosLinea = [
                'id_pedido'   => $id_pedido,
                'id_producto' => $producto['id'],
                'precio'      => $producto['precio'],
                'cantidad'    => $carrito[$producto['id']]
            ];
            $lineasModel->insert($datosLinea);

            // Actualizamos el Stock
            // Calculamos lo que queda
            $nuevoStock = $producto['stock'] - $carrito[$producto['id']];

            // Lo guardamos en la base de datos
            $productosModel->update($producto['id'], ['stock' => $nuevoStock]);
        }

        // Vaciamos el carrito y redirigimos
        $session->remove('carrito');
        
        // Redirigimos a la tienda con un mensaje de éxito
        return redirect()->to(base_url('tienda'))->with('mensaje', '¡Pedido realizado con éxito!');
    }

    // Funcion para eliminar  un producto del carrito
    public function eliminar($id = null)
    {
        $session = session();
        // Recuperamos lo que hay en el carrito si hay algo
        $carrito = $session->get('carrito');

        // Si el producto esta seleccionado en el carrito, lo borramos
        if (isset($carrito[$id])) {
            unset($carrito[$id]);
        }

        // Guardamos y redirigimos
        $session->set('carrito', $carrito);
        return redirect()->to(base_url('carrito'));
    }

    // Funcion para actualizar la cantidad de un producto en el carrito
    public function actualizar()
    {
        // 1. Recibimos las cantidades del formulario
        $cantidades = $this->request->getPost('cantidad'); 
        
        $session = session();
        // Recuperamos lo que hay en el carrito si hay algo
        $carrito = $session->get('carrito');
        
        // Obtenemos los IDs actuales para saber qué producto es cual
        $ids = array_keys($carrito);

        // 2. Recorremos la lista paso a paso
        for ($i = 0; $i < count($ids); $i++) {
            $id_producto = $ids[$i];
            $nueva_cantidad = $cantidades[$i];

            // Actualizamos la cantidad para ese ID específico
            $carrito[$id_producto] = $nueva_cantidad;
        }

        // 3. Guardamos y nos saca a otra pagina
        $session->set('carrito', $carrito);
        return redirect()->to(base_url('carrito'));
    }
}