<style>
    /* Diseño Pro para la Paginación */
    .pagination { 
        display: flex; 
        padding-left: 0; 
        list-style: none; 
        justify-content: center; 
        gap: 8px; 
        margin-top: 20px;
    }
    .pagination li a, .pagination li span { 
        position: relative; 
        display: block; 
        padding: 0.6rem 1rem; 
        color: #212529; 
        text-decoration: none; 
        background-color: #f8f9fa; 
        border: 1px solid #dee2e6; 
        border-radius: 8px !important; 
        font-weight: 600; 
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .pagination li.active a, .pagination li.active span { 
        z-index: 3; 
        color: #fff; 
        background-color: #0d6efd; 
        border-color: #0d6efd; 
        box-shadow: 0 4px 8px rgba(13, 110, 253, 0.3); 
    }
    .pagination li a:hover:not(.active) { 
        background-color: #e9ecef; 
        border-color: #ced4da; 
        transform: translateY(-2px);
    }
</style>

<div class="container my-5">
    <div class="row">
        
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0 text-center p-4">
                <div class="mb-3">
                    <div style="width: 100px; height: 100px; background-color: #e9ecef; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 3rem;">
                        👤
                    </div>
                </div>
                <h4 class="fw-bold"><?= esc($usuario['nombre']) ?> <?= esc($usuario['apellidos']) ?></h4>
                <p class="text-muted"><?= esc($usuario['email']) ?></p>
                <hr>
                <div class="d-grid">
                    <a href="<?= base_url('logout') ?>" class="btn btn-outline-danger">Cerrar Sesión</a>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">⚙️ Configuración de la Cuenta</h5>
                </div>
                <div class="card-body p-4">
                    <?php if (session()->getFlashdata('mensaje_exito')): ?>
                        <div class="alert alert-success"><?= session()->getFlashdata('mensaje_exito') ?></div>
                    <?php endif; ?>

                    <?php if (session()->has('errors')): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach (session('errors') as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('perfil/actualizar') ?>" method="post">
                        <?= csrf_field() ?> 
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Nombre</label>
                                <input type="text" name="nombre" class="form-control" value="<?= esc($usuario['nombre']) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Apellidos</label>
                                <input type="text" name="apellidos" class="form-control" value="<?= esc($usuario['apellidos']) ?>" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Correo Electrónico</label>
                            <input type="email" name="email" class="form-control" value="<?= esc($usuario['email']) ?>" required>
                        </div>
                        <hr class="my-4">
                        <h6 class="text-muted mb-3">Cambiar Contraseña (Opcional)</h6>
                        <div class="mb-3">
                            <label class="form-label">Nueva Contraseña</label>
                            <input type="password" name="password" class="form-control" placeholder="Déjalo vacío si no quieres cambiarla">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Confirmar Contraseña</label>
                            <input type="password" name="pass_confirm" class="form-control">
                        </div>
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <button type="submit" class="btn btn-primary px-4">Guardar Cambios</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0 fw-bold">📅 Mis Próximas Clases</h5>
                </div>
                <div class="card-body p-0">
                    <?php if(empty($reservas_pendientes)): ?>
                        <div class="p-4 text-center text-muted">No tienes clases próximas reservadas.</div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Clase</th>
                                        <th>Fecha y Hora</th>
                                        <th>Entrenador</th>
                                        <th class="text-end pe-4">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($reservas_pendientes as $reserva): ?>
                                        <tr>
                                            <td class="align-middle fw-bold ps-4"><?= esc($reserva['nombre']) ?></td>
                                            <td class="align-middle text-primary fw-medium"><?= date('d/m/Y H:i', strtotime($reserva['fecha_hora'])) ?></td>
                                            <td class="align-middle"><?= esc($reserva['nombre_entrenador']) ?></td>
                                            <td class="align-middle text-end pe-4">
                                                <form action="<?= base_url('horarios/cancelar') ?>" method="post">
                                                    <input type="hidden" name="id_clase" value="<?= $reserva['id'] ?>">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Seguro que quieres cancelar?')">Cancelar</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-secondary text-white py-3">
                    <h5 class="mb-0 fw-bold">🕒 Historial de Clases</h5>
                </div>
                <div class="card-body p-0">
                    <?php if(empty($historial_clases)): ?>
                        <div class="p-4 text-center text-muted">Aún no hay historial.</div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Clase</th>
                                        <th>Fecha</th>
                                        <th>Entrenador</th>
                                        <th class="text-end pe-4">Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($historial_clases as $clase): ?>
                                        <tr>
                                            <td class="align-middle fw-bold ps-4 text-muted"><?= esc($clase['nombre']) ?></td>
                                            <td class="align-middle text-muted"><?= date('d/m/Y', strtotime($clase['fecha_hora'])) ?></td>
                                            <td class="align-middle text-muted"><?= esc($clase['nombre_entrenador']) ?></td>
                                            <td class="align-middle text-end pe-4"><span class="badge bg-success">Completada</span></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card shadow-sm border-0 mt-4 mb-5">
                <div class="card-header bg-dark text-white py-3">
                    <h5 class="mb-0 fw-bold">🛒 Mis Compras</h5>
                </div>
                <div class="card-body p-0">
                    <?php if(empty($mis_pedidos)): ?>
                        <div class="p-4 text-center text-muted">Aún no has realizado ninguna compra.</div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Pedido #</th>
                                        <th>Productos</th> 
                                        <th>Fecha</th>
                                        <th>Total</th>
                                        <th class="text-end pe-4">Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($mis_pedidos as $pedido): ?>
                                        <tr>
                                            <td class="align-middle fw-bold ps-4">#<?= $pedido['id'] ?></td>
                                            <td class="align-middle">
                                                <small class="text-muted d-block" style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?= esc($pedido['nombres_productos']) ?>">
                                                    <?= esc($pedido['nombres_productos']) ?>
                                                </small>
                                            </td>
                                            <td class="align-middle"><?= date('d/m/Y', strtotime($pedido['fecha'])) ?></td>
                                            <td class="align-middle fw-medium text-success"><?= number_format($pedido['total'], 2, ',', '.') ?> €</td>
                                            <td class="align-middle text-end pe-4"><span class="badge rounded-pill bg-info text-dark">Pagado</span></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="card-footer bg-white border-0 py-3">
                            <nav aria-label="Navegación de pedidos">
                                <?= $pager->links('pedidos') ?>
                            </nav>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</div>