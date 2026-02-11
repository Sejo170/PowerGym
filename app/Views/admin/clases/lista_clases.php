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

    <div class="card mb-4">
        <div class="card-body">
            <form action="<?= base_url('admin/clases') ?>" method="get">
                <div class="row align-items-end">
                    
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Filtrar por Entrenador:</label>
                        <select name="entrenador_id" class="form-select">
                            <option value="">Todos los entrenadores</option>
                            <?php if (!empty($entrenadores)): ?>
                                <?php foreach ($entrenadores as $entrenador): ?>
                                    <option value="<?= $entrenador['id'] ?>" <?= (isset($_GET['entrenador_id']) && $_GET['entrenador_id'] == $entrenador['id']) ? 'selected' : '' ?>>
                                        <?= esc($entrenador['nombre']) . ' ' . esc($entrenador['apellidos']) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            </select>
                    </div>

                    <div class="col-md-2">
                        <button type="submit" class="btn btn-dark w-100">
                            <i class="fas fa-search"></i> Filtrar
                        </button>
                    </div>

                    <?php if(isset($_GET['entrenador_id'])): ?>
                        <div class="col-md-2">
                            <a href="<?= base_url('admin/clases') ?>" class="btn btn-outline-secondary w-100">
                                Limpiar
                            </a>
                        </div>
                    <?php endif; ?>

                </div>
            </form>
        </div>
    </div>

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