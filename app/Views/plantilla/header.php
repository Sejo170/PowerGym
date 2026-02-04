<?php 
    $rol = session()->get('id_rol'); 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PowerGym</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body { display: flex; flex-direction: column; min-height: 100vh; }
        main { flex: 1; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="<?= base_url('/') ?>">💪 PowerGym</a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if (session()->get('is_logged_in')): ?>
                        
                        <!--- Para que solo lo puedan ver los que son ADMINS, ENTRENADORES, SOCIOS --->
                        <?php if ($rol == 1 || $rol == 2 || $rol == 4): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= base_url('horarios') ?>">
                                    📅 <?= ($rol == 4) ? 'Reservar Clases' : 'Clases' ?>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if ($rol == 1 || $rol == 2): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= base_url('admin/productos') ?>">
                                    📦 Inventario
                                </a>
                            </li>
                        <?php endif; ?>

                        <!--- Para que solo lo puedan ver los que son ADMINS y ENTRENADORES --->
                        <?php if ($rol == 1 || $rol == 2): ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    ⚙️ Gestión
                                </a>
                                <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDropdown">
                                    
                                    <!--- Para que solo lo puedan ver los que son ADMINS --->
                                    <?php if ($rol == 1): ?>
                                        <li><a class="dropdown-item" href="<?= base_url('admin/usuarios') ?>">👥 Usuarios</a></li>
                                        <li><a class="dropdown-item" href="<?= base_url('admin/clases') ?>">📝 Editar Clases</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><span class="dropdown-header">Reportes</span></li>
                                        <li><a class="dropdown-item" href="<?= base_url('admin') ?>">📊 Estadísticas</a></li>
                                    <?php endif; ?>

                                    <!--- Para que solo lo puedan ver los que son ENTRENADORES --->
                                    <?php if ($rol == 2): ?>
                                        <li><a class="dropdown-item" href="<?= base_url('admin/clases') ?>">📝 Editar Clases</a></li>
                                    <?php endif; ?>

                                </ul>
                            </li>
                        <?php endif; ?>

                        <!--- Pueden verlo todo el mundo --->
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('perfil') ?>">Mi Perfil (<?= session()->get('nombre') ?>)</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-danger" href="<?= base_url('logout') ?>">Salir</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('login') ?>">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('register') ?>">Registro</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
    </nav>