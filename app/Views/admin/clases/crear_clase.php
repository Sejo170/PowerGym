<div class="container mt-4" style="max-width: 800px;">
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            <h3 class="mb-0">Nueva Clase</h3>
        </div>
        <?php if (session()->getFlashdata('mensaje_error')): ?>
            <div class="alert alert-danger fw-bold" role="alert">
                <?= session()->getFlashdata('mensaje_error') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('mensaje_exito')): ?>
            <div class="alert alert-success fw-bold" role="alert">
                <?= session()->getFlashdata('mensaje_exito') ?>
            </div>
        <?php endif; ?>
        <div class="card-body">
            
            <form action="<?= base_url('admin/clases/guardar') ?>" method="post">
                
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre de la Clase</label>
                    <input type="text" class="form-control" name="nombre" id="nombre" required placeholder="Ej: Yoga Matutino">
                </div>

                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea class="form-control" name="descripcion" id="descripcion" rows="3" placeholder="Detalles de la sesión..."></textarea>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="fecha_hora" class="form-label">Fecha y Hora</label>
                        <input type="datetime-local" class="form-control" name="fecha_hora" id="fecha_hora" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="plazas_totales" class="form-label">Plazas Disponibles</label>
                        <input type="number" class="form-control" name="plazas_totales" id="plazas_totales" value="20" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="id_entrenador" class="form-label">Entrenador</label>
                        <select class="form-select" name="id_entrenador" id="id_entrenador" required>
                            <option value="" selected disabled>Selecciona un entrenador...</option>
                            
                            <?php foreach ($entrenadores as $entrenador): ?>
                                <option value="<?= $entrenador['id'] ?>">
                                    <?= esc($entrenador['nombre']) . ' ' . esc($entrenador['apellidos']) ?>
                                </option>
                            <?php endforeach; ?>
                            
                        </select>
                    </div>
                </div>

                <hr>

                <div class="d-flex justify-content-between">
                    <a href="<?= base_url('admin/clases') ?>" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Guardar Clase</button>
                </div>

            </form>
        </div>
    </div>
</div>