<div class="container-fluid mt-4">

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
    <div class="mb-3">
        <a href="<?= base_url('admin/crearUsuario') ?>" class="btn btn-success fw-bold">
            + Añadir Nuevo Usuario
        </a>
    </div>

    <div class="card mb-3">
        <div class="card-body py-3">
            <form action="<?= base_url('admin/usuarios') ?>" method="get">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <label class="col-form-label fw-bold">Filtrar por Rol:</label>
                    </div>
                    <div class="col-md-4">
                        <select name="rol" class="form-select">
                            <option value="">Ver todos</option>
                            <option value="1" <?= (isset($_GET['rol']) && $_GET['rol'] == '1') ? 'selected' : '' ?>>Admin</option>
                            <option value="2" <?= (isset($_GET['rol']) && $_GET['rol'] == '2') ? 'selected' : '' ?>>Entrenador</option>
                            <option value="3" <?= (isset($_GET['rol']) && $_GET['rol'] == '3') ? 'selected' : '' ?>>Cliente</option>
                            <option value="4" <?= (isset($_GET['rol']) && $_GET['rol'] == '4') ? 'selected' : '' ?>>Socio Gym</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">Filtrar</button>
                        <?php if(isset($_GET['rol'])): ?>
                            <a href="<?= base_url('admin/usuarios') ?>" class="btn btn-outline-secondary">Limpiar</a>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover table-bordered">
            <thead class="table-dark">
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
                                if($usuario['id_rol'] == 1) echo '<span class="badge bg-danger">Admin</span>';
                                elseif($usuario['id_rol'] == 2) echo '<span class="badge bg-warning text-dark">Entrenador</span>';
                                elseif($usuario['id_rol'] == 3) echo '<span class="badge bg-primary">Cliente</span>';
                                elseif($usuario['id_rol'] == 4) echo '<span class="badge bg-success">Socio Gym</span>';
                                else echo "Desconocido";
                            ?>
                        </td>

                        <td>
                            <div class="d-flex gap-2 align-items-center">
                                <?php if ($usuario['id'] == session()->get('id')): ?>
                                    <span class="text-muted fst-italic">(Tú)</span>

                                <?php elseif ($usuario['id_rol'] == 1): ?>
                                    <span class="text-muted fst-italic">
                                        (Admin)
                                    </span>

                                <?php else: ?>
                                    <form action="<?= base_url('admin/cambiarRol/' . $usuario['id']) ?>" method="post" class="d-flex gap-1">
                                        <?= csrf_field() ?>
                                        <select name="nuevo_rol" class="form-select form-select-sm" required>
                                            <option value="" disabled selected>Cambiar a...</option>
                                            <option value="1">Admin</option>
                                            <option value="2">Entrenador</option>
                                            <option value="3">Cliente</option>
                                            <option value="4">Socio Gym</option>
                                        </select>
                                        <button type="submit" class="btn btn-primary btn-sm">Guardar</button>
                                    </form>

                                    <form action="<?= base_url('admin/borrarUsuario/' . $usuario['id']) ?>" method="post">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro?');">Borrar</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>