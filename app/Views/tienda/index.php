<div class="container my-5">
    <h2 class="text-center mb-5">Tienda PowerGym 🛒</h2>

    <div class="row">
        
        <div class="col-md-3">
            <div class="card shadow-sm border-0 p-3 mb-4">
                <h5 class="fw-bold mb-3">Filtrar por:</h5>
                
                <form action="<?= base_url('tienda') ?>" method="get">
                    <div class="mb-3">
                        <label class="form-label">Buscar producto</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white">🔍</span>
                            <input type="text" name="buscar" class="form-control" placeholder="Ej: Proteína..." value="<?= isset($_GET['buscar']) ? $_GET['buscar'] : '' ?>">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Categoría</label>
                        <select name="categoria" class="form-select">
                            <option value="">Todas</option>
                            <option value="1">Ropa</option>
                            <option value="2">Suplementos</option>
                            <option value="3">Accesorios</option>
                            <option value="4">Alimentacion</option>
                            <option value="5">Creatina</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Precio Máximo</label>
                        <input type="number" name="precio_max" class="form-control" placeholder="Ej: 50">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Ordenar por</label>
                        <select name="orden" class="form-select">
                            <option value="">Por defecto</option>
                            <option value="precio_bajo">Precio: Más barato primero</option>
                            <option value="precio_alto">Precio: Más caro primero</option>
                            <option value="nombre_az">Nombre: A - Z</option>
                            <option value="nombre_za">Nombre: Z - A</option>
                        </select>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-dark">Aplicar Filtros 🔎</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-md-9">
            
            <div class="row">
                <?php if(empty($productos)): ?>
                    <div class="col-12 text-center">
                        <div class="alert alert-info">No hay productos que coincidan con los filtros.</div>
                    </div>
                <?php else: ?>
                    <?php foreach($productos as $producto): ?>
                        <div class="col-md-4 mb-4"> <div class="card shadow-sm h-100 border-0">
                                
                                <img src="<?= base_url('uploads/productos/' . $producto['imagen']) ?>" 
                                        class="card-img-top" 
                                        alt="<?= esc($producto['nombre']) ?>"
                                        style="height: 250px; object-fit: cover;">

                                <div class="card-body text-center d-flex flex-column">
                                    <h5 class="card-title mt-2"><?= esc($producto['nombre']) ?></h5>
                                    <p class="text-muted"><?= esc($producto['descripcion']) ?></p>

                                    <div class="mt-auto">
                                        <h4 class="text-primary fw-bold mb-3"><?= esc($producto['precio']) ?> €</h4>
                                        <?php if($producto['stock'] > 0): ?>
                                            <p class="small text-muted mb-3">Quedan: <?= $producto['stock'] ?> unidades</p>
                                        <?php else: ?>
                                            <p class="small text-muted mb-3">Proximamente!!!!</p>
                                        <?php endif; ?>

                                        <?php if($is_logged_in): ?>
                                            
                                            <?php if($producto['stock'] > 0): ?>
                                                <a href="<?= base_url('tienda/agregar/' . $producto['id']) ?>" class="btn btn-primary w-100">
                                                    Comprar 🛒
                                                </a>
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

            <div class="mt-4">
                <?= $pager->links('default', 'bootstrap_powergym') ?>
            </div>
        </div> 
    </div> 
</div>