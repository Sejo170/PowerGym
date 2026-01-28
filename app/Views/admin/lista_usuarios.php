<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración - PowerGym</title>
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .btn { padding: 5px 10px; text-decoration: none; color: white; margin-right: 5px; border-radius: 4px; }
        .btn-green { background-color: green; } /* Para hacer socio */
        .btn-orange { background-color: orange; } /* Para quitar socio */
        .btn-red { background-color: red; }       /* Para borrar */
        .alert { padding: 10px; margin-bottom: 15px; border: 1px solid transparent; border-radius: 4px; }
        .alert-success { color: #155724; background-color: #d4edda; border-color: #c3e6cb; }
        .alert-danger { color: #721c24; background-color: #f8d7da; border-color: #f5c6cb; }
    </style>
</head>
<body>

    <h1>Gestión de Usuarios - PowerGym</h1>

    <?php if (session()->getFlashdata('mensaje_exito')): ?>
        <div class="alert alert-success">
            <?= session()->getFlashdata('mensaje_exito') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('mensaje_error')): ?>
        <div class="alert alert-danger">
            <?= session()->getFlashdata('mensaje_error') ?>
        </div>
    <?php endif; ?>

    <!--- Tabla para listar los usuarios en el admin --->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre Completo</th>
                <th>Email</th>
                <th>Rol Actual</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <!--- Bucle para mostrar los usuarios --->
            <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td><?= $usuario['id']; ?></td>
                    <td><?= $usuario['nombre'] . ' ' . $usuario['apellidos']; ?></td>
                    <td><?= $usuario['email']; ?></td>
                    
                    <td>
                        <?php 
                            if($usuario['id_rol'] == 1) echo "Admin";
                            elseif($usuario['id_rol'] == 2) echo "Entrenador";
                            elseif($usuario['id_rol'] == 3) echo "Cliente";
                            elseif($usuario['id_rol'] == 4) echo "Socio Gym";
                        ?>
                    </td>

                    <td>
                        <?php if ($usuario['id_rol'] == 3): ?>
                            <a href="<?= base_url('admin/cambiarRol/' . $usuario['id'] . '/4') ?>" class="btn btn-green">Hacer Socio</a>
                        <?php endif; ?>

                        <?php if ($usuario['id_rol'] == 4): ?>
                            <a href="<?= base_url('admin/cambiarRol/' . $usuario['id'] . '/3') ?>" class="btn btn-orange">Quitar Socio</a>
                        <?php endif; ?>

                        <!--- Si el ID de esta fila ES DIFERENTE (!=) a MI ID... entonces dibuja el botón de borrar. Si es igual, no dibujes nada. --->
                        <?php if ($usuario['id'] != session()->get('id')): ?>
                            
                            <a href="<?= base_url('admin/borrarUsuario/' . $usuario['id']) ?>" 
                                class="btn btn-red"
                                onclick="return confirm('¿Seguro que quieres borrar a este usuario?');">
                                Borrar
                            </a>

                        <?php else: ?>
                            <span style="color: gray; font-style: italic;">(Tú)</span>
                        <?php endif; ?>

                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <br>
    <a href="<?= base_url('logout') ?>">Cerrar Sesión</a>

</body>
</html>