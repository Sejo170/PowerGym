<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ClasesModel;

class Clases extends BaseController
{
    public function index()
    {
        // 1. Instanciamos el Modelo
        $clasesModel = new ClasesModel();

        // 2. Obtenemos todas las clases de la base de datos
        $data['clases'] = $clasesModel->obtenerClasesConEntrenador();

        // 3. Cargamos la vista (y le pasamos los datos)
        echo view('plantilla/header');
        echo view('admin/clases/lista_clases', $data);
        echo view('plantilla/footer');
    }

    public function crear()
    {
        // 1. Llamamos al modelo de Usuarios
        $usuarioModel = new \App\Models\UsuarioModel(); 
        
        // 2. Buscamos SOLO a los entrenadores
        // Asumimos que el 'id_rol' 2 es el de los entrenadores (ajusta el número si es otro en tu BD)
        $data['entrenadores'] = $usuarioModel->where('id_rol', 2)->findAll();

        // 3. Cargamos la vista pasando la lista de entrenadores ($data)
        echo view('plantilla/header');
        echo view('admin/clases/crear_clase', $data);
        echo view('plantilla/footer');
    }

    public function guardar()
    {
        // 1. Instanciamos el modelo
        $clasesModel = new ClasesModel();

        // 2. Creamos el paquete de datos (Array)
        // OJO: Las palabras a la izquierda deben coincidir EXACTAMENTE con tu base de datos
        $datos = [
            'nombre'         => $this->request->getPost('nombre'),
            'descripcion'    => $this->request->getPost('descripcion'),
            'fecha_hora'     => $this->request->getPost('fecha_hora'),
            'plazas_totales' => $this->request->getPost('plazas_totales'),
            'id_entrenador'  => $this->request->getPost('id_entrenador'),
        ];

        // 3. Guardamos
        $clasesModel->save($datos);

        // 4. Volvemos a la lista y mostramos mensaje de éxito (Opcional pero recomendado)
        return redirect()->to('admin/clases')->with('mensaje_exito', '¡Clase creada correctamente!');
    }

    public function borrar($id)
    {
        $clasesModel = new ClasesModel();
        $clasesModel->delete($id);
        return redirect()->to('admin/clases')->with('mensaje_exito', 'Clase eliminada');
    }

    public function editar($id)
    {
        $clasesModel = new ClasesModel();
        $usuariosModel = new \App\Models\UsuarioModel();

        // 1. Recuperamos la clase existente usando su ID
        $data['clase'] = $clasesModel->find($id);
        
        // 2. Recuperamos los entrenadores para llenar el select
        $data['entrenadores'] = $usuariosModel->where('id_rol', 2)->findAll();

        // 3. Cargamos la vista específica de edición
        echo view('plantilla/header');
        echo view('admin/clases/editar_clase', $data);
        echo view('plantilla/footer');
    }

    public function actualizar()
    {
        // 0. VALIDACIÓN (El Portero) 🛡️
        // Si los datos no cumplen las reglas, les impedimos pasar
        if (! $this->validate([
            'nombre'          => 'required|min_length[3]',
            'plazas_totales'  => 'required|integer|greater_than[0]',
            'id_entrenador'   => 'required'
        ])) {
            // Si falla, los mandamos de vuelta al formulario con los errores
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $clasesModel = new ClasesModel();

        // 1. Recogemos el ID
        $id = $this->request->getPost('id');

        // 2. Recogemos los datos
        $datos = [
            'nombre'         => $this->request->getPost('nombre'),
            'descripcion'    => $this->request->getPost('descripcion'),
            'fecha_hora'     => $this->request->getPost('fecha_hora'),
            'plazas_totales' => $this->request->getPost('plazas_totales'),
            'id_entrenador'  => $this->request->getPost('id_entrenador'),
        ];

        // 3. Actualizamos
        $clasesModel->update($id, $datos);

        // 4. Redirigimos
        return redirect()->to('admin/clases')->with('mensaje_exito', '¡Clase actualizada correctamente!');
    }
}