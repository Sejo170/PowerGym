<div class="container py-5">
    <h1 class="mb-4">Tu Carrito de Compras 🛒</h1>

    <?php if (empty($productos)): ?>
        <div class="alert alert-warning">
            Tu carrito está vacío. <a href="<?= base_url('tienda') ?>">Volver a la tienda</a>
        </div>
    <?php else: ?>
        
        <?php 
            // Recuperamos el carrito de la sesión para saber las cantidades
            $carrito = session()->get('carrito'); 
            $gran_total = 0;
        ?>

        <form action="<?= base_url('carrito/actualizar') ?>" method="post">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Producto</th>
                        <th>Precio Unitario</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productos as $producto): ?>
                        <?php 
                            // Calculamos los datos de esta fila
                            $id = $producto['id'];
                            $cantidad = $carrito[$id];
                            $precio = $producto['precio'];
                            $subtotal = $cantidad * $precio;
                            
                            // Sumamos al total general de la compra
                            $gran_total += $subtotal;
                        ?>
                        <tr>
                            <td>
                                <strong><?= $producto['nombre'] ?></strong>
                            </td>
                            <td><?= $precio ?> €</td>
                            <td>
                                <input type="number" name="cantidad[]" value="<?= $cantidad ?>" min="1" class="form-control" style="width: 80px;">
                            </td>
                            <td>
                                <strong><?= $subtotal ?> €</strong>
                            </td>
                            <td>
                                <a href="<?= base_url('carrito/eliminar/' . $producto['id']) ?>" class="btn btn-danger btn-sm">
                                    🗑️
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
            </table>
            <div class="d-flex justify-content-end mt-2 mb-4">
                <button type="submit" class="btn btn-warning">🔄 Actualizar Carrito</button>
            </div>
        </form>

            <div class="d-flex justify-content-end mt-4">
                <h3>Total a pagar: <span class="text-primary"><?= $gran_total ?> €</span></h3>
            </div>
            
            <div class="mt-4 text-end">
                <a href="<?= base_url('tienda') ?>" class="btn btn-secondary me-2">Seguir comprando</a>
                <a href="<?= base_url('carrito/confirmar') ?>" class="btn btn-success btn-lg">
                    Finalizar Compra ✅
                </a>
            </div>
    <?php endif; ?>
</div>