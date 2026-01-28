<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro - PowerGym</title>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6"> <div class="card shadow my-5">
                    <div class="card-body p-4">
                        
                        <h2 class="text-center mb-4">Crear Cuenta</h2>

                        <?php if (isset($validation)): ?>
                            <div class="alert alert-danger">
                                <?= $validation->listErrors() ?>
                            </div>
                        <?php endif; ?>

                        <form action="<?= base_url('register/save') ?>" method="post">
                            
                            <div class="mb-3">
                                <label class="form-label">Nombre:</label>
                                <input type="text" name="nombre" class="form-control" value="<?= set_value('nombre') ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Apellidos:</label>
                                <input type="text" name="apellidos" class="form-control" value="<?= set_value('apellidos') ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email:</label>
                                <input type="email" name="email" class="form-control" value="<?= set_value('email') ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Contraseña:</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Repetir Contraseña:</label>
                                <input type="password" name="pass_confirm" class="form-control" required>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Registrarse</button>
                            
                        </form>

                        <div class="text-center mt-3">
                            <a href="<?= base_url('login') ?>" class="text-decoration-none">¿Ya tienes cuenta? Entra aquí</a>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

</body>
</html>