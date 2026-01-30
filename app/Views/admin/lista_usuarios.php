<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración - PowerGym</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        h1 { color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        
        /* Estilos de los botones */
        .btn { padding: 6px 12px; text-decoration: none; color: white; margin-right: 5px; border-radius: 4px; border: none; cursor: pointer; font-size: 14px; }
        .btn-green { background-color: #28a745; } /* Verde para Hacer Socio */
        .btn-orange { background-color: #fd7e14; } /* Naranja para Quitar Socio */
        .btn-red { background-color: #dc3545; }    /* Rojo para Borrar */
        
        /* Estilos de las alertas */
        .alert { padding: 15px; margin-bottom: 20px; border: 1px solid transparent; border-radius: 4px; }
        .alert-success { color: #155724; background-color: #d4edda; border-color: #c3e6cb; }
        .alert-danger { color: #721c24; background-color: #f8d7da; border-color: #f5c6cb; }
        
        /* Para alinear los formularios en la misma línea */
        form { display: inline-block; }
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
            <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td><?= $usuario['id']; ?></td>
                    <td><?= esc($usuario['nombre']) . ' ' . esc($usuario['apellidos']); ?></td>
                    <td><?= esc($usuario['email']); ?></td>
                    
                    <td>
                        <?php 
                            if($usuario['id_rol'] == 1) echo "Admin";
                            elseif($usuario['id_rol'] == 2) echo "Entrenador";
                            elseif($usuario['id_rol'] == 3) echo "Cliente";
                            elseif($usuario['id_rol'] == 4) echo "Socio Gym";
                            else echo "Desconocido";
                        ?>
                    </td>

                    <td>
                        <?php if ($usuario['id_rol'] == 3): ?>
                            <form action="<?= base_url('admin/cambiarRol/' . $usuario['id'] . '/4') ?>" method="post">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-green">Hacer Socio</button>
                            </form>
                        <?php endif; ?>

                        <?php if ($usuario['id_rol'] == 4): ?>
                            <form action="<?= base_url('admin/cambiarRol/' . $usuario['id'] . '/3') ?>" method="post">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-orange">Quitar Socio</button>
                            </form>
                        <?php endif; ?>

                        <?php if ($usuario['id'] != session()->get('id')): ?>
                            <form action="<?= base_url('admin/borrarUsuario/' . $usuario['id']) ?>" method="post">
                                <?= csrf_field() ?>
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-red" onclick="return confirm('¿Seguro que quieres borrar a este usuario?');">
                                    Borrar
                                </button>
                            </form>
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