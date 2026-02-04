<div class="container">
    <h2 class="text-center mb-5">Tienda PowerGym 🛒</h2>

    <div class="row">
        <?php if(empty($productos)): ?>
            <div class="col-12 text-center">
                <div class="alert alert-info">No hay productos disponibles.</div>
            </div>
        <?php else: ?>
            <?php foreach($productos as $producto): ?>
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm h-100 border-0">
                        
                        <img src="<?= base_url('uploads/productos/' . $producto['imagen']) ?>" 
                                class="card-img-top" 
                                alt="<?= esc($producto['nombre']) ?>"
                                style="height: 250px; object-fit: cover;">

                        <div class="card-body text-center d-flex flex-column">
                            <h5 class="card-title mt-2"><?= esc($producto['nombre']) ?></h5>
                            <p class="text-muted"><?= esc($producto['descripcion']) ?></p>

                            <div class="mt-auto">
                                <h4 class="text-primary fw-bold mb-3"><?= esc($producto['precio']) ?> €</h4>

                                <?php if($is_logged_in): ?>
                                    
                                    <?php if($producto['stock'] > 0): ?>
                                        <button class="btn btn-primary w-100">Comprar 🛒</button>
                                    <?php else: ?>
                                        <button class="btn btn-secondary w-100" disabled>Agotado</button>
                                    <?php endif; ?>

                                <?php else: ?>
                                    <a href="<?= base_url('/login') ?>" class="btn btn-outline-dark w-100">
                                        Inicia sesión para comprar
                                    </a>
                                <?php endif; ?>

                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>