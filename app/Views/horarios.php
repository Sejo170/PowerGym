<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Horarios - PowerGym</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    
    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="#">💪 PowerGym</a>
            <a href="<?= base_url('login') ?>" class="btn btn-outline-light btn-sm">Admin Access</a>
        </div>
    </nav>

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
                                    <span class="badge bg-success rounded-pill px-3 py-2">
                                        <?= $clase['plazas_totales'] ?> Plazas
                                    </span>
                                    <button class="btn btn-outline-primary btn-sm">Reservar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>