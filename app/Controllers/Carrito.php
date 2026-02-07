<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProductosModel;
use App\Models\PedidosModel;
use App\Models\LineasPedidosModel;

class Carrito extends BaseController
{
    public function index()
    {
        $session = session();
        $productosModel = new ProductosModel();

        // 1. Recuperamos el carrito (Array: ID => Cantidad)
        $carrito = $session->get('carrito');
        
        $datosVista = [];

        if (empty($carrito)) {
            // Si está vacío, mandamos una lista vacía
            $datosVista['productos'] = []; 
        } else {
            // 2. Extraemos solo los IDs (las llaves del array)
            // Si $carrito es [1 => 2, 5 => 1], esto nos da [1, 5]
            $ids = array_keys($carrito);

            // 3. Buscamos todos esos productos de golpe en la BD
            $datosVista['productos'] = $productosModel->find($ids);
        }

        echo view('plantilla/header');
        echo view('carrito/index', $datosVista);
        echo view('plantilla/footer');
    }

    public function confirmar()
    {
        $session = session();
        $carrito = $session->get('carrito');

        // Si el carrito está vacío, los echamos fuera
        if (empty($carrito)) {
            return redirect()->to(base_url('tienda'));
        }

        // 1. Calculamos el total (igual que hicimos antes)
        $productosModel = new ProductosModel();
        $ids = array_keys($carrito);
        $productos = $productosModel->find($ids);

        $total = 0;
        foreach ($productos as $producto) {
            $cantidad = $carrito[$producto['id']];
            $total += $producto['precio'] * $cantidad;
        }

        // 2. AQUI GUARDAMOS EL PEDIDO EN LA BASE DE DATOS
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

            // 2. ACTUALIZAMOS EL STOCK 📉
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

    public function eliminar($id = null)
    {
        $session = session();
        $carrito = $session->get('carrito');

        // Si el producto existe en el carrito, lo borramos
        if (isset($carrito[$id])) {
            unset($carrito[$id]);
        }

        // Guardamos y redirigimos
        $session->set('carrito', $carrito);
        return redirect()->to(base_url('carrito'));
    }

    public function actualizar()
    {
        // 1. Recibimos las cantidades del formulario
        $cantidades = $this->request->getPost('cantidad'); 
        
        $session = session();
        $carrito = $session->get('carrito');
        
        // Obtenemos los IDs actuales para saber qué producto es cual
        $ids = array_keys($carrito);

        // 2. Recorremos la lista paso a paso
        for ($i = 0; $i < count($ids); $i++) {
            $id_producto = $ids[$i];
            $nueva_cantidad = $cantidades[$i];

            // --- AQUÍ ESTÁ LA MAGIA ✨ ---
            // Actualizamos la cantidad para ese ID específico
            $carrito[$id_producto] = $nueva_cantidad;
        }

        // 3. Guardamos y nos vamos
        $session->set('carrito', $carrito);
        return redirect()->to(base_url('carrito'));
    }
}