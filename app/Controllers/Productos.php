<?php

namespace App\Controllers;

use App\Models\ProductosModel;
use App\Models\CategoriasModel;

class Productos extends BaseController
{
    public function index()
    {
        $productosModel = new ProductosModel();

        // Traemos productos + nombre de categoría
        $data['productos'] = $productosModel->select('productos.*, categorias.nombre_categoria')
                                            ->join('categorias', 'categorias.id = productos.id_categoria')
                                            ->findAll();

        echo view('plantilla/header');
        // OJO AQUÍ: La ruta ahora incluye la carpeta 'productos'
        echo view('admin/productos/lista_productos', $data);
        echo view('plantilla/footer');
    }

    public function crear()
    {
        $categoriasModel = new CategoriasModel();
        
        $data['categorias'] = $categoriasModel->findAll();

        echo view('plantilla/header');
        // OJO AQUÍ TAMBIÉN
        echo view('admin/productos/crear_producto', $data);
        echo view('plantilla/footer');
    }

    public function guardar()
    {
        // 1. Validamos
        $validacion = $this->validate([
            'nombre'       => 'required|min_length(3)',
            'descripcion'  => 'required',
            'precio'       => 'required|numeric',
            'stock'        => 'required|integer',
            'id_categoria' => 'required|integer',
            'imagen'       => [
                'uploaded[imagen]',
                'mime_in[imagen,image/jpg,image/jpeg,image/png,image/gif]',
                'max_size[imagen,2048]',
            ]
        ]);

        if (!$validacion) {
            return redirect()->back()->withInput()->with('mensaje_error', 'Revisa los datos del formulario.');
        }

        // 2. Procesamos la imagen
        $img = $this->request->getFile('imagen');
        $nombreImagen = $img->getRandomName();
        $img->move(ROOTPATH . 'public/uploads/productos', $nombreImagen);

        // 3. Guardamos
        $datos = [
            'nombre'       => $this->request->getPost('nombre'),
            'descripcion'  => $this->request->getPost('descripcion'),
            'precio'       => $this->request->getPost('precio'),
            'stock'        => $this->request->getPost('stock'),
            'id_categoria' => $this->request->getPost('id_categoria'),
            'imagen'       => $nombreImagen 
        ];

        $productosModel = new ProductosModel();
        $productosModel->save($datos);

        // Redirigimos a la lista (que ahora está en admin/productos)
        return redirect()->to(base_url('admin/productos'))->with('mensaje_exito', '¡Producto creado correctamente!');
    }
}