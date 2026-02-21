<div class="container mt-5">
    <h2>Añadir Nuevo Usuario</h2>
    
    <?php if(session()->getFlashdata('mensaje_error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('mensaje_error') ?></div>
    <?php endif; ?>

    <form action="<?= base_url('admin/crearUsuario') ?>" method="POST">
        
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" name="nombre" id="nombre" required>
        </div>
        
        <div class="mb-3">
            <label for="apellidos" class="form-label">Apellidos</label>
            <input type="text" class="form-control" name="apellidos" id="apellidos" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Correo Electrónico</label>
            <input type="email" class="form-control" name="email" id="email" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" class="form-control" name="password" id="password" required>
        </div>

        <div class="mb-3">
            <label for="id_rol" class="form-label">Rol del Usuario</label>
            <select class="form-select" name="id_rol" id="id_rol" required>
                <option value="">Selecciona un rol...</option>
                <option value="1">Admin</option>
                <option value="2">Entrenador</option>
                <option value="3">Cliente</option>
                <option value="4">Socio Gym</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Crear Usuario</button>
        <a href="<?= base_url('admin/usuarios') ?>" class="btn btn-secondary">Cancelar</a>
    </form>
</div>