<div class="container">
    <h2 class="text-center mb-5">Próximas Clases 🔥</h2>

    <div class="row">
        <?php if(empty($clases)): ?>
            <div class="col-12 text-center">
                <div class="alert alert-info">No hay clases programadas por el momento.</div>
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
                                <?= esc($clase['nombre_entrenador']) ?>
                            </h6>

                            <div class="d-flex justify-content-between align-items-center px-3">
                                <span id="plazas-<?= $clase['id'] ?>" class="badge bg-success rounded-pill px-3 py-2">
                                    <?= $clase['plazas_totales'] ?> Plazas
                                </span>

                                <?php if (in_array($clase['id'], $mis_reservas)): ?>

                                    <form action="<?= base_url('horarios/cancelar') ?>" method="post" class="form-reserva">
                                        <input type="hidden" name="id_clase" value="<?= $clase['id'] ?>">
                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                            ❌ Cancelar
                                        </button>
                                    </form>

                                <?php else: ?>

                                    <form action="<?= base_url('horarios/reservar') ?>" method="post" class="form-reserva">
                                        <input type="hidden" name="id_clase" value="<?= $clase['id'] ?>">
                                        <button type="submit" class="btn btn-outline-primary btn-sm">Reservar</button>
                                    </form>

                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    // 1. Seleccionamos todos los formularios de reserva/cancelación
        const formularios = document.querySelectorAll('.form-reserva');

        formularios.forEach(form => {
            form.addEventListener('submit', function(e) {
                // 2. IMPORTANTE: Evitamos que la página se recargue
                e.preventDefault();

                // 3. Preparamos los datos y la URL
                const url = this.action;
                const datos = new FormData(this);

                // 4. Enviamos la petición con Fetch
                fetch(url, {
                    method: 'POST',
                    body: datos,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest' // Para que CI4 sepa que es AJAX
                    }
                })
                .then(response => response.json()) // Convertimos la respuesta a JSON
                .then(data => {
                    // 5. Aquí recibimos lo que enviaste desde el controlador ($datos)
                    if(data.status === 'success') {
                        alert("✅ " + data.mensaje);
                        // Aquí luego haremos que cambie el botón sin recargar
                        location.reload(); // De momento recargamos para ver el cambio
                    } else {
                        alert("❌ " + data.mensaje);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert("Ocurrió un error inesperado.");
                });
            });
        });
    });
</script>