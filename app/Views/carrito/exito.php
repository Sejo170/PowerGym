<div class="container my-5 text-center">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow border-0 p-5">
                <div class="mb-4">
                    <div style="font-size: 5rem; color: #198754;">
                        ✅
                    </div>
                </div>
                
                <h2 class="fw-bold mb-3">¡Pedido Confirmado!</h2>
                <p class="text-muted mb-4">
                    Gracias por tu compra. Hemos recibido tu pedido correctamente y ya estamos preparando tus productos.
                </p>

                <div class="bg-light p-4 rounded-3 mb-4">
                    <p class="mb-1 text-uppercase small fw-bold text-muted">Número de Pedido</p>
                    <h4 class="fw-bold">#<?= $pedido['id'] ?></h4>
                    <hr>
                    <p class="mb-1 text-uppercase small fw-bold text-muted">Total Pagado</p>
                    <h4 class="text-primary fw-bold"><?= number_format($pedido['total'], 2, ',', '.') ?> €</h4>
                </div>

                <div class="d-grid gap-2">
                    <a href="<?= base_url('perfil') ?>" class="btn btn-primary btn-lg fw-bold">Ver mis pedidos</a>
                    <a href="<?= base_url('tienda') ?>" class="btn btn-outline-secondary">Volver a la tienda</a>
                </div>
            </div>
            
            <p class="mt-4 text-muted small">
                Recibirás un correo con los detalles de tu compra y el seguimiento del envío.
            </p>
        </div>
    </div>
</div>