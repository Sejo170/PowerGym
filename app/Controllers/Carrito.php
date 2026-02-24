<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProductosModel;
use App\Models\PedidosModel;
use App\Models\LineasPedidosModel;

class Carrito extends BaseController
{
    // Funcion para mostrar una tabla con lo que hay en el carrito
    public function index()
    {
        $session = session();
        $productosModel = new ProductosModel();

        $carrito = $session->get('carrito');
        $datosVista = [];

        if (empty($carrito)) {
            $datosVista['productos'] = []; 
        } else {
            $ids = array_keys($carrito);
            $datosVista['productos'] = $productosModel->find($ids);
        }

        echo view('plantilla/header');
        echo view('carrito/index', $datosVista);
        echo view('plantilla/footer');
    }

    // Funcion para confirmar el pedido (CON CONTROL DE STOCK)
    public function confirmar()
    {
        $session = session();
        $carrito = $session->get('carrito');

        // Si el carrito está vacío, los redirigimos a la tienda
        if (empty($carrito)) {
            return redirect()->to(base_url('tienda'));
        }

        // Si no está logueado, a iniciar sesión
        if (!$session->get('is_logged_in')) {
            return redirect()->to(base_url('login'))->with('mensaje_error', 'Debes iniciar sesión para finalizar tu compra.');
        }

        $productosModel = new ProductosModel();
        $ids = array_keys($carrito);
        $productos = $productosModel->find($ids);

        // VERIFICACIÓN DE STOCK FINAL
        foreach ($productos as $producto) {
            $cantidadPedida = $carrito[$producto['id']];
            if ($cantidadPedida > $producto['stock']) {
                // Si alguien se adelantó y no queda stock suficiente, cancelamos la compra y avisamos
                return redirect()->to(base_url('carrito'))->with('mensaje_error', 'Lo sentimos, solo nos quedan ' . $producto['stock'] . ' unidades de ' . $producto['nombre']);
            }
        }

        // CÁLCULO DEL TOTAL
        $total = 0;
        foreach ($productos as $producto) {
            $cantidad = $carrito[$producto['id']];
            $total += (float)$producto['precio'] * $cantidad;
        }

        // GUARDAMOS EL PEDIDO
        $pedidosModel = new PedidosModel();
        $id_usuario = $session->get('id'); 

        $datosPedido = [
            'id_usuario' => $id_usuario,
            'total'      => $total
        ];

        // insert() guarda el pedido y DEVUELVE el ID nuevo
        $id_pedido = $pedidosModel->insert($datosPedido);

        $lineasModel = new LineasPedidosModel();

        foreach ($productos as $producto) {
            $cantidadPedida = $carrito[$producto['id']];
            
            // Guardamos la línea
            $datosLinea = [
                'id_pedido'   => $id_pedido,
                'id_producto' => $producto['id'],
                'precio'      => $producto['precio'],
                'cantidad'    => $cantidadPedida
            ];
            $lineasModel->insert($datosLinea);

            // Actualizamos el Stock restando lo comprado
            $nuevoStock = $producto['stock'] - $cantidadPedida;
            $productosModel->update($producto['id'], ['stock' => $nuevoStock]);
        }

        // Vaciamos el carrito de la sesión
        $session->remove('carrito');

        // Lo mandamos a una vista de confirmación en lugar de devolverlo a la tienda de golpe
        return redirect()->to(base_url('carrito/exito/' . $id_pedido));
    }

    // Funcion para eliminar un producto del carrito
    public function eliminar($id = null)
    {
        $session = session();
        $carrito = $session->get('carrito');

        if (isset($carrito[$id])) {
            unset($carrito[$id]);
        }

        $session->set('carrito', $carrito);
        return redirect()->to(base_url('carrito'));
    }

    // Funcion para actualizar la cantidad (CON CONTROL DE STOCK)
    public function actualizar()
    {
        // Ahora recibimos un array asociativo: [ id_producto => nueva_cantidad ]
        $cantidades = $this->request->getPost('cantidad'); 
        $session = session();
        $carrito = $session->get('carrito');
        $productosModel = new \App\Models\ProductosModel();

        if ($cantidades) {
            // Recorremos exactamente lo que nos manda el formulario
            foreach ($cantidades as $id_producto => $nueva_cantidad) {
                
                // Solo actualizamos si el producto realmente está en el carrito
                if (isset($carrito[$id_producto])) {
                    $nueva_cantidad = (int) $nueva_cantidad;
                    $productoBD = $productosModel->find($id_producto);

                    // Validación: Stock máximo
                    if ($nueva_cantidad > $productoBD['stock']) {
                        $carrito[$id_producto] = $productoBD['stock'];
                        $session->set('carrito', $carrito);
                        return redirect()->to(base_url('carrito'))->with('mensaje_error', 'No puedes añadir ' . $nueva_cantidad . ' unidades. Solo nos quedan ' . $productoBD['stock'] . ' de ' . $productoBD['nombre']);
                    }

                    // Validación: Mínimo 1 unidad
                    if ($nueva_cantidad < 1) {
                        $nueva_cantidad = 1;
                    }

                    // Actualizamos la cantidad para ese ID exacto
                    $carrito[$id_producto] = $nueva_cantidad;
                }
            }
        }

        // Guardamos y volvemos
        $session->set('carrito', $carrito);
        return redirect()->to(base_url('carrito'))->with('mensaje_exito', 'Carrito actualizado correctamente.');
    }

    // Pantalla de confirmación de pedido
    public function exito($id_pedido)
    {
        // Solo dejamos entrar si el usuario está logueado
        if (!session()->get('is_logged_in')) {
            return redirect()->to(base_url('/'));
        }

        $pedidosModel = new PedidosModel();
        
        // Buscamos el pedido para enseñarle el resumen
        $pedido = $pedidosModel->find($id_pedido);

        // Seguridad: Si el pedido no existe o no es de este usuario, lo sacamos
        if (!$pedido || $pedido['id_usuario'] != session()->get('id')) {
            return redirect()->to(base_url('tienda'));
        }

        $data['pedido'] = $pedido;

        echo view('plantilla/header');
        echo view('carrito/exito', $data);
        echo view('plantilla/footer');
    }
}