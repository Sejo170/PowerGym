<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión - PowerGym</title>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5"> <div class="card shadow my-5">
                    <div class="card-body p-4">
                        
                        <h2 class="text-center mb-4">Iniciar Sesión</h2>

                        <?php if (session()->getFlashdata('msg')): ?>
                            <div class="alert alert-danger">
                                <?= session()->getFlashdata('msg') ?>
                            </div>
                        <?php endif; ?>

                        <form action="<?= base_url('auth') ?>" method="post">
                            
                            <div class="mb-3">
                                <label class="form-label">Email:</label>
                                <input type="email" name="email" class="form-control" value="<?= old('email') ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Contraseña:</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Entrar</button>
                            
                        </form>

                        <div class="text-center mt-3">
                            <a href="<?= base_url('/') ?>" class="text-decoration-none">Volver al Inicio</a>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

</body>
</html>