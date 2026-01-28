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
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">⚙️ Configuración de la Cuenta</h5>
                </div>
                <div class="card-body p-4">

                    <?php if (session()->getFlashdata('mensaje_exito')): ?>
                        <div class="alert alert-success">
                            <?= session()->getFlashdata('mensaje_exito') ?>
                        </div>
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
                        
                        <?= csrf_field() ?> <div class="row mb-3">
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
        </div>

    </div>
</div>