<?php

namespace App\Controllers;

// Importamos los modelos
use App\Controllers\BaseController;
use App\Models\ProductosModel;

class Tienda extends BaseController
{
    // Funcion para mostrar los productos en el "Frontend"
    public function index()
    {
        $session = session();
        $productosModel = new ProductosModel();

        // CAPTURAMOS TODOS LOS DATOS (Filtros + Buscador + Orden)
        $categoria  = $this->request->getGet('categoria');
        $precio_max = $this->request->getGet('precio_max');
        $buscar     = $this->request->getGet('buscar');
        $orden      = $this->request->getGet('orden');

        // APLICAMOS LOS FILTROS
        if (!empty($categoria)) {
            $productosModel->where('id_categoria', $categoria);
        }

        if (!empty($precio_max)) {
            $productosModel->where('precio <=', $precio_max);
        }

        if (!empty($buscar)) {
            $productosModel->like('nombre', $buscar, 'both');
        }

        // Para ordenar
        switch ($orden) {
            case 'precio_bajo':
                // De barato a caro (Ascendente)
                $productosModel->orderBy('precio', 'ASC'); 
                break;
            case 'precio_alto':
                // De caro a barato (Descendente)
                $productosModel->orderBy('precio', 'DESC'); 
                break;
            case 'nombre_az':
                // Alfabético A-Z
                $productosModel->orderBy('nombre', 'ASC'); 
                break;
            case 'nombre_za':
                // Alfabético Z-A
                $productosModel->orderBy('nombre', 'DESC'); 
                break;
            default:
                // Si no eligen nada, ordenamos por ID (los más nuevos al final)
                $productosModel->orderBy('id', 'ASC');
                break;
        }

        // EJECUTAMOS LA CONSULTA
        $data['productos'] = $productosModel->paginate(6);
        $data['pager']     = $productosModel->pager;

        $data['is_logged_in'] = $session->get('is_logged_in');

        echo view('plantilla/header');
        echo view('tienda/index', $data);
        echo view('plantilla/footer');
    }

    // Funcion para guardar en el carrito
    public function agregar($id_producto)
    {
        $session = session();
        
        // Intentamos obtener el carrito actual.
        // Si no existe (es null), usamos una lista vacía [].
        $carrito = $session->get('carrito') ? $session->get('carrito') : [];

        // Comprobamos si el producto ya estaba en el carrito
        if (array_key_exists($id_producto, $carrito)) {
            // Si ya existe, le sumamos 1 a la cantidad
            $carrito[$id_producto] += 1;
        } else {
            // Si es nuevo, lo añadimos con cantidad 1
            $carrito[$id_producto] = 1;
        }

        // 3. Guardamos los cambios en la sesión
        $session->set('carrito', $carrito);

        // 4. Volvemos a la página anterior (para seguir comprando)
        return redirect()->back();
    }
}