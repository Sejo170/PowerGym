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

        /* 🔵 MEJORA: Animación suave en los enlaces (Subrayado) */
        .nav-link {
            position: relative;
            margin-right: 10px;
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 0;
            background-color: #0d6efd; /* Azul brillante */
            transition: width 0.3s ease-in-out;
        }

        .nav-link:hover::after {
            width: 100%;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow">
        <div class="container"> 
            
            <a class="navbar-brand fw-bold" href="<?= base_url('/') ?>">💪 PowerGym</a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('/#contacto') ?>">Contacto</a></li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('tienda') ?>">
                            🛍️ Tienda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="<?= base_url('carrito') ?>">
                            🛒 Mi Carrito
                            
                            <?php 
                                $carrito = session()->get('carrito'); 
                                $cantidad = ($carrito) ? count($carrito) : 0; 
                            ?>
                            
                            <?php if($cantidad > 0): ?>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    <?= $cantidad ?>
                                </span>
                            <?php endif; ?>
                        </a>
                    </li>
                    <?php if (session()->get('is_logged_in')): ?>
                        <?php if (in_array($rol, [1, 2, 4])): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= base_url('horarios') ?>">
                                    📅 <?= ($rol == 4) ? 'Reservar' : 'Clases' ?>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if (in_array($rol, [1, 2])): ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">⚙️ Gestión</a>
                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark">
                                    <?php if ($rol == 1): ?>
                                        <li><a class="dropdown-item" href="<?= base_url('admin/usuarios') ?>">👥 Usuarios</a></li>
                                        <li><a class="dropdown-item" href="<?= base_url('admin') ?>">📊 Estadísticas</a></li>
                                    <?php endif; ?>
                                    <li><a class="dropdown-item" href="<?= base_url('admin/clases') ?>">📝 Clases</a></li>
                                    <li><a class="dropdown-item" href="<?= base_url('admin/productos') ?>">📦 Inventario</a></li>
                                </ul>
                            </li>
                        <?php endif; ?>

                        <li class="nav-item dropdown ms-lg-3">
                            <a class="nav-link dropdown-toggle btn btn-outline-light rounded-pill px-3" href="#" role="button" data-bs-toggle="dropdown">
                                👤 <?= session()->get('nombre') ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark">
                                <li><a class="dropdown-item" href="<?= base_url('perfil') ?>">Mi Perfil</a></li>
                                <li><a class="dropdown-item" href="<?= base_url('mis-reservas') ?>">Mis Reservas</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="<?= base_url('logout') ?>">Cerrar Sesión</a></li>
                            </ul>
                        </li>

                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="<?= base_url('login') ?>">Login</a></li>
                        <li class="nav-item ms-2">
                            <a class="btn btn-primary rounded-pill px-4" href="<?= base_url('register') ?>">Registro</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>