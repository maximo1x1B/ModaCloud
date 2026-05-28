<div class="container">
    <div class="card" style="max-width: 500px; margin: 2rem auto;">
        <div class="card-header">
            <h2 class="card-title"><?= isset($movimiento) ? 'Editar Registro de Inventario' : 'Registrar Movimiento de Inventario' ?></h2>
        </div>

        <form method="POST">
            <?php if (isset($movimiento)): ?>
                <input type="hidden" name="id" value="<?= $movimiento['id'] ?>">
            <?php endif; ?>

            <div class="form-group">
                <label class="form-label">Producto</label>
                <select name="product_id" class="form-control" required>
                    <option value="">-- Seleccione un Producto --</option>
                    <?php foreach ($productos as $prod): ?>
                        <option value="<?= $prod['id'] ?>" <?= (isset($movimiento) && $movimiento['product_id'] == $prod['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($prod['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Cantidad</label>
                <input 
                    type="number" 
                    name="cantidad" 
                    class="form-control" 
                    value="<?= isset($movimiento['cantidad']) ? htmlspecialchars($movimiento['cantidad']) : '' ?>"
                    min="0"
                    required
                >
            </div>

            <div class="form-group">
                <label class="form-label">Stock Mínimo Requerido</label>
                <input 
                    type="number" 
                    name="stock_minimo" 
                    class="form-control" 
                    value="<?= isset($movimiento['stock_minimo']) ? htmlspecialchars($movimiento['stock_minimo']) : '' ?>"
                    min="0"
                    required
                >
            </div>

            <div class="form-group">
                <label class="form-label">Tipo de Movimiento</label>
                <select name="tipo_movimiento" class="form-control" required>
                    <option value="entrada" <?= (isset($movimiento) && $movimiento['tipo_movimiento'] === 'entrada') ? 'selected' : '' ?>>Entrada</option>
                    <option value="salida" <?= (isset($movimiento) && $movimiento['tipo_movimiento'] === 'salida') ? 'selected' : '' ?>>Salida</option>
                    <option value="ajuste" <?= (isset($movimiento) && $movimiento['tipo_movimiento'] === 'ajuste') ? 'selected' : '' ?>>Ajuste</option>
                </select>
            </div>

            <div class="flex gap-md mt-lg">
                <button type="submit" class="btn btn-primary">
                    <?= isset($movimiento) ? 'Actualizar' : 'Guardar Registro' ?>
                </button>
                <a href="/inventario" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

