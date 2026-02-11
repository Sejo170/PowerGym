<?php

namespace App\Controllers;

// Importamos los modelos
use App\Models\ProductosModel;
use App\Models\CategoriasModel;

class Productos extends BaseController
{
    // Funcion para mostrar una tabla con los productos y filtros
    public function index()
    {
        // Instanciamos el modelp
        $productosModel = new ProductosModel();

        // Capturamos los datos de la URL con el GET
        $nombre     = $this->request->getGet('nombre');
        $categoria  = $this->request->getGet('categoria');
        $precio_max = $this->request->getGet('precio_max');

        // Construimos la consulta (sin traer los datos todavía)
        $productosModel->select('productos.*, categorias.nombre_categoria')
                    ->join('categorias', 'categorias.id = productos.id_categoria');

        // Aplicamos filtros

        // Si hemos elegido alguna categoria
        if ($categoria) {
            $productosModel->where('productos.id_categoria', $categoria);
        }

        // Si el usuario escribió un precio máximo...
        if ($precio_max) {
            $productosModel->where('productos.precio <=', $precio_max);
        }

        // --- AQUÍ FALTA EL FILTRO DEL NOMBRE ---
        // Si el usuario escribió algo en el nombre...
        // Si el usuario escribió algo en el nombre...
        if ($nombre) {
            $productosModel->like('productos.nombre', $nombre);
        }

        // 4. Finalmente, ejecutamos la consulta
        $data['productos'] = $productosModel->findAll();

        echo view('plantilla/header');
        echo view('admin/productos/lista_productos', $data);
        echo view('plantilla/footer');
    }

    // Funcion para crear productos
    public function crear()
    {
        $categoriasModel = new CategoriasModel();
        
        $data['categorias'] = $categoriasModel->findAll();

        echo view('plantilla/header');
        echo view('admin/productos/crear_producto', $data);
        echo view('plantilla/footer');
    }

    // Funcion para guardar el producto
    public function guardar()
    {
        // Validamos
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

        // Procesamos la imagen
        $img = $this->request->getFile('imagen');
        $nombreImagen = $img->getRandomName();
        $img->move(ROOTPATH . 'public/uploads/productos', $nombreImagen);

        // Guardamos los datos
        $datos = [
            'nombre'       => $this->request->getPost('nombre'),
            'descripcion'  => $this->request->getPost('descripcion'),
            'precio'       => $this->request->getPost('precio'),
            'stock'        => $this->request->getPost('stock'),
            'id_categoria' => $this->request->getPost('id_categoria'),
            'imagen'       => $nombreImagen 
        ];

        // Instanciamos y guardamos
        $productosModel = new ProductosModel();
        $productosModel->save($datos);

        // Redirigimos a la lista mostrando un mensaje de exito
        return redirect()->to(base_url('admin/productos'))->with('mensaje_exito', '¡Producto creado correctamente!');
    }

    // Funcion para borrar el producto
    public function borrar($id)
    {
        // Instanciamos el modelo
        $productosModel = new ProductosModel();

        // Buscamos los datos del producto antes de borrarlo
        $datosProducto = $productosModel->find($id);

        // Si el producto existe y tiene una imagen guardada la borramos de la carpeta del servidor
        if (isset($datosProducto['imagen']) && file_exists(ROOTPATH . 'public/uploads/productos/' . $datosProducto['imagen'])) {
            unlink(ROOTPATH . 'public/uploads/productos/' . $datosProducto['imagen']);
        }

        // Borramos el registro de la base de datos
        $productosModel->delete($id);

        // Redirigimos a la tabla mostrando un mensaje de exito
        return redirect()->to(base_url('admin/productos'))->with('mensaje_exito', 'Producto y su imagen eliminados correctamente.');
    }

    // Funcion para editar el producto
    public function editar($id)
    {
        // Instanciamos los modelos
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

    // Funcion para ACTUALIZAR EL PRODUCTO
    public function actualizar()
    {
        // Instanciamos el modelo
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

        // Lógica de la Imagen
        $img = $this->request->getFile('imagen');

        // Solo si se ha subido una imagen nueva y es válida
        if ($img && $img->isValid() && !$img->hasMoved()) {
            
            // Validamos que sea imagen
            $validacion = $this->validate([
                'imagen' => 'uploaded[imagen]|max_size[imagen,2048]|is_image[imagen]'
            ]);

            if ($validacion) {
                // Subimos la nueva
                $nuevoNombre = $img->getRandomName();
                $img->move(ROOTPATH . 'public/uploads/productos', $nuevoNombre);

                // Añadimos el nombre al array de datos para que se actualice
                $datos['imagen'] = $nuevoNombre;
            }
        }

        // Actualizamos en la base de datos
        $productosModel->update($id, $datos);

        // Redirigimos con un mostrando un mensaje de exito
        return redirect()->to(base_url('admin/productos'))->with('mensaje_exito', 'Producto actualizado correctamente.');
    }
}