<div class="container mt-4">
    <h1>Nuevo Producto</h1>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="<?= base_url('admin/productos/guardar') ?>" method="post" enctype="multipart/form-data">
                
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre del Producto</label>
                    <input type="text" class="form-control" name="nombre" id="nombre" required>
                </div>

                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea class="form-control" name="descripcion" id="descripcion" rows="3" required></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="precio" class="form-label">Precio (€)</label>
                        <input type="number" class="form-control" name="precio" id="precio" step="0.01" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="stock" class="form-label">Stock Inicial</label>
                        <input type="number" class="form-control" name="stock" id="stock" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="id_categoria" class="form-label">Categoría</label>
                    <select class="form-select" name="id_categoria" required>
                        <option value="">Selecciona una categoría...</option>
                        <?php foreach($categorias as $cat): ?>
                            <option value="<?= $cat['id'] ?>">
                                <?= esc($cat['nombre_categoria']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="imagen" class="form-label">Imagen del Producto</label>
                    <input type="file" class="form-control" name="imagen" id="imagen" accept="image/*" required>
                </div>

                <button type="submit" class="btn btn-primary">Guardar Producto</button>
                <a href="<?= base_url('admin/productos') ?>" class="btn btn-secondary">Cancelar</a>

            </form>
        </div>
    </div>
</div>