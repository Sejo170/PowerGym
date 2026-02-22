<style>
    /* Magia CSS para hacer la paginación de CodeIgniter bonita estilo Bootstrap 5 */
    .pagination { display: flex; padding-left: 0; list-style: none; justify-content: center; gap: 5px; }
    .pagination li a, .pagination li span { position: relative; display: block; padding: 0.5rem 1rem; color: #0d6efd; text-decoration: none; background-color: #fff; border: 1px solid #dee2e6; border-radius: 0.375rem; font-weight: 500; transition: all 0.2s;}
    .pagination li.active a, .pagination li.active span { z-index: 3; color: #fff; background-color: #0d6efd; border-color: #0d6efd; box-shadow: 0 4px 6px rgba(13, 110, 253, 0.2); }
    .pagination li a:hover { z-index: 2; color: #0a58ca; background-color: #e9ecef; border-color: #dee2e6; }
</style>

<div class="container">
    <h2 class="text-center mb-4">Próximas Clases 🔥</h2>
    
    <div class="card shadow-sm mb-5 border-0 bg-light">
        <div class="card-body">
            <form action="<?= base_url('horarios') ?>" method="get">
                <div class="row g-3 align-items-end">
                    
                    <div class="col-md-4">
                        <label class="form-label fw-bold text-muted small">Buscar actividad</label>
                        <input type="text" name="buscar" class="form-control" placeholder="Ej. Yoga, HIIT..." value="<?= isset($_GET['buscar']) ? esc($_GET['buscar']) : '' ?>">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold text-muted small">Entrenador</label>
                        <select name="entrenador" class="form-select">
                            <option value="">Cualquiera</option>
                            <?php foreach ($entrenadores as $entrenador): ?>
                                <option value="<?= $entrenador['id'] ?>" <?= (isset($_GET['entrenador']) && $_GET['entrenador'] == $entrenador['id']) ? 'selected' : '' ?>>
                                    <?= esc($entrenador['nombre']) . ' ' . esc($entrenador['apellidos']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold text-muted small">Disponibilidad</label>
                        <select name="plazas" class="form-select">
                            <option value="">Ver todas</option>
                            <option value="libres" <?= (isset($_GET['plazas']) && $_GET['plazas'] == 'libres') ? 'selected' : '' ?>>Solo con plazas libres</option>
                        </select>
                    </div>

                    <div class="col-md-2 d-grid gap-2">
                        <button type="submit" class="btn btn-primary fw-bold">Filtrar</button>
                        <?php if(isset($_GET['buscar']) || isset($_GET['entrenador']) || isset($_GET['plazas'])): ?>
                            <a href="<?= base_url('horarios') ?>" class="btn btn-outline-secondary btn-sm">Limpiar</a>
                        <?php endif; ?>
                    </div>

                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <?php if(empty($clases)): ?>
            <div class="col-12 text-center">
                <div class="alert alert-info shadow-sm">No se encontraron clases con esos filtros.</div>
            </div>
        <?php else: ?>
            <?php foreach($clases as $clase): ?>
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm h-100 border-0">
                        <div class="card-header bg-primary text-white text-center">
                            <h5 class="card-title m-0"><?= esc($clase['nombre']) ?></h5>
                        </div>

                        <div class="card-body text-center">
                            <p class="text-muted mb-3">
                                📅 <?= date('d/m/Y H:i', strtotime($clase['fecha_hora'])) ?>
                            </p>
                            <p class="mb-1">Entrenador:</p>
                            <h6 class="text-primary fw-bold mb-4">
                                <?= esc($clase['nombre_entrenador']) . ' ' . esc($clase['apellidos_entrenador']) ?>
                            </h6>
                            <div class="d-flex justify-content-between align-items-center px-3">
                                <span class="badge <?= $clase['plazas_libres'] > 0 ? 'bg-success' : 'bg-danger' ?> rounded-pill px-3 py-2">
                                    <?= $clase['plazas_libres'] ?> Plazas
                                </span>

                                <?php if (in_array($clase['id'], $mis_reservas)): ?>
                                    <form action="<?= base_url('horarios/cancelar') ?>" method="post">
                                        <input type="hidden" name="id_clase" value="<?= $clase['id'] ?>">
                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                            ❌ Cancelar
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <?php if ($clase['plazas_libres'] > 0): ?>
                                        <form action="<?= base_url('horarios/reservar') ?>" method="post">
                                            <input type="hidden" name="id_clase" value="<?= $clase['id'] ?>">
                                            <button type="submit" class="btn btn-outline-primary btn-sm">Reservar</button>
                                        </form>
                                    <?php else: ?>
                                        <button class="btn btn-secondary btn-sm" disabled>LLENA</button>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <?php if(!empty($clases)): ?>
        <div class="mt-4 mb-5">
            <?= $pager->links() ?>
        </div>
    <?php endif; ?>

</div>