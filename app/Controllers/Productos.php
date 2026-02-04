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

    // GUARDAR EL PRODUCTO
    public function guardar()
    {
        // 1. Validamos
        $validacion = $this->validate([
            'nombre'       => 'required|min_length[3]',
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

    // Recibimos el $id como parámetro (ej: borrar/5)
    public function borrar($id)
    {
        $productosModel = new ProductosModel();

        // 1. Buscamos los datos del producto antes de borrarlo
        $datosProducto = $productosModel->find($id);

        // 2. Si el producto existe y tiene una imagen guardada...
        if (isset($datosProducto['imagen']) && file_exists(ROOTPATH . 'public/uploads/productos/' . $datosProducto['imagen'])) {
            // ...la borramos de la carpeta del servidor
            unlink(ROOTPATH . 'public/uploads/productos/' . $datosProducto['imagen']);
        }

        // 3. Ahora sí, borramos el registro de la base de datos
        $productosModel->delete($id);

        // 4. Redirigimos a la lista con un mensaje
        return redirect()->to(base_url('admin/productos'))->with('mensaje_exito', 'Producto y su imagen eliminados correctamente.');
    }

    // EDITAR EL PRODUCTO
    public function editar($id)
    {
        $productosModel = new ProductosModel();
        $categoriasModel = new CategoriasModel();

        // Buscamos el producto que queremos editar
        $data['producto'] = $productosModel->find($id);
        
        // También necesitamos las categorías para el desplegable
        $data['categorias'] = $categoriasModel->findAll();

        echo view('plantilla/header');
        echo view('admin/productos/editar_producto', $data);
        echo view('plantilla/footer');
    }

    // ACTUALIZAR PRODUCTO
    public function actualizar()
    {
        $productosModel = new ProductosModel();
        
        // Recogemos el ID oculto del formulario
        $id = $this->request->getPost('id');

        // Recogemos los datos básicos
        $datos = [
            'nombre'       => $this->request->getPost('nombre'),
            'descripcion'  => $this->request->getPost('descripcion'),
            'precio'       => $this->request->getPost('precio'),
            'stock'        => $this->request->getPost('stock'),
            'id_categoria' => $this->request->getPost('id_categoria'),
        ];

        // --- Lógica de la Imagen ---
        $img = $this->request->getFile('imagen');

        // Solo si se ha subido una imagen nueva y es válida...
        if ($img && $img->isValid() && !$img->hasMoved()) {
            
            // 1. Validamos que sea imagen (opcional pero recomendable)
            $validacion = $this->validate([
                'imagen' => 'uploaded[imagen]|max_size[imagen,2048]|is_image[imagen]'
            ]);

            if ($validacion) {
                // 2. Subimos la nueva
                $nuevoNombre = $img->getRandomName();
                $img->move(ROOTPATH . 'public/uploads/productos', $nuevoNombre);

                // 3. Añadimos el nombre al array de datos para que se actualice
                $datos['imagen'] = $nuevoNombre;

                // (Opcional) Aquí podrías borrar la imagen vieja si quisieras limpiar el servidor
            }
        }

        // Actualizamos en la base de datos
        $productosModel->update($id, $datos);

        return redirect()->to(base_url('admin/productos'))->with('mensaje_exito', 'Producto actualizado correctamente.');
    }
}