<div class="container-fluid mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Inventario de Productos - PowerGym</h1>
        <a href="<?= base_url('admin/productos/crear') ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Producto
        </a>
    </div>

    <?php if (session()->getFlashdata('mensaje_exito')): ?>
        <div class="alert alert-success">
            <?= session()->getFlashdata('mensaje_exito') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('mensaje_error')): ?>
        <div class="alert alert-danger">
            <?= session()->getFlashdata('mensaje_error') ?>
        </div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-striped table-hover table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Imagen</th>
                    <th>Nombre y Descripción</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Categoría (ID)</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($productos)): ?>
                    <?php foreach ($productos as $producto): ?>
                        <tr>
                            <td><?= $producto['id']; ?></td>
                            
                            <td class="text-center" style="width: 80px;">
                                <?php if(!empty($producto['imagen'])): ?>
                                    <img src="<?= base_url('uploads/productos/' . $producto['imagen']) ?>" alt="Img" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                <?php else: ?>
                                    <span class="text-muted small">Sin img</span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <strong><?= esc($producto['nombre']); ?></strong>
                                <br>
                                <small class="text-muted"><?= esc($producto['descripcion']); ?></small>
                            </td>

                            <td><?= number_format($producto['precio'], 2); ?> €</td>
                            
                            <td>
                                <?php if($producto['stock'] < 5): ?>
                                    <span class="badge bg-danger"><?= $producto['stock']; ?></span>
                                <?php else: ?>
                                    <span class="badge bg-success"><?= $producto['stock']; ?></span>
                                <?php endif; ?>
                            </td>

                            <td><?= $producto['nombre_categoria']; ?></td>

                            <td style="width: 150px;">
                                <div class="d-flex gap-2">
                                    <a href="<?= base_url('admin/productos/editar/' . $producto['id']) ?>" class="btn btn-warning btn-sm">
                                        Editar
                                    </a>

                                    <form action="<?= base_url('admin/productos/borrar/' . $producto['id']) ?>" method="post">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que quieres eliminar este producto del inventario?');">
                                            Borrar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">No hay productos en el inventario.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>