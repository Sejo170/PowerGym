<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold">Gestión de Clases</h1>
        <a href="<?= base_url('admin/clases/crear') ?>" class="btn btn-primary">
            + Nueva Clase
        </a>
    </div>

    <?php if (session()->getFlashdata('mensaje_error')): ?>
        <div class="alert alert-danger">
            <?= session()->getFlashdata('mensaje_error') ?>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <?php if (session()->getFlashdata('mensaje_exito')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('mensaje_exito') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0 align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Fecha</th>
                            <th>Plazas</th>
                            <th>Entrenador</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($clases)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    No hay clases creadas todavía.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($clases as $clase): ?>
                                <tr>
                                    <td>#<?= $clase['id'] ?></td>
                                    <td class="fw-bold"><?= esc($clase['nombre']) ?></td>
                                    <td><?= esc($clase['descripcion']) ?></td>
                                    <td><?= $clase['fecha_hora'] ?></td>
                                    <td><?= $clase['plazas_totales'] ?></td>
                                    
                                    <td>
                                        <span class="badge bg-secondary">
                                            Entrenador: <?= esc($clase['nombre_entrenador']) . ' ' . esc($clase['apellidos_entrenador']) ?>
                                        </span>
                                    </td>

                                    <td class="text-end">
                                        <a href="<?= base_url('admin/clases/editar/' . $clase['id']) ?>" class="btn btn-sm btn-outline-primary me-1">
                                            Editar
                                        </a>

                                        <a href="<?= base_url('admin/clases/borrar/' . $clase['id']) ?>" 
                                            class="btn btn-danger btn-sm" 
                                            onclick="return confirm('¿Estás seguro de que deseas eliminar esta clase? Esta acción no se puede deshacer.');">
                                            Borrar
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>