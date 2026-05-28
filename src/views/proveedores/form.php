<div class="container">
    <div class="card" style="max-width: 500px; margin: 2rem auto;">
        <div class="card-header">
            <h2 class="card-title"><?= isset($proveedor) ? 'Editar Proveedor' : 'Nuevo Proveedor' ?></h2>
        </div>

        <form method="POST">
            <?php if (isset($proveedor)): ?>
                <input type="hidden" name="id" value="<?= $proveedor['id'] ?>">
            <?php endif; ?>

            <div class="form-group">
                <label class="form-label">Nombre</label>
                <input 
                    type="text" 
                    name="nombre" 
                    class="form-control" 
                    value="<?= isset($proveedor['nombre']) ? htmlspecialchars($proveedor['nombre']) : '' ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label class="form-label">RFC</label>
                <input 
                    type="text" 
                    name="rfc" 
                    class="form-control" 
                    value="<?= isset($proveedor['rfc']) ? htmlspecialchars($proveedor['rfc']) : '' ?>"
                >
            </div>

            <div class="form-group">
                <label class="form-label">Teléfono</label>
                <input 
                    type="tel" 
                    name="telefono" 
                    class="form-control" 
                    value="<?= isset($proveedor['telefono']) ? htmlspecialchars($proveedor['telefono']) : '' ?>"
                >
            </div>

            <div class="form-group">
                <label class="form-label">Email</label>
                <input 
                    type="email" 
                    name="email" 
                    class="form-control" 
                    value="<?= isset($proveedor['email']) ? htmlspecialchars($proveedor['email']) : '' ?>"
                    required
                >
            </div>

            <div class="flex gap-md mt-lg">
                <button type="submit" class="btn btn-primary">
                    <?= isset($proveedor) ? 'Actualizar' : 'Crear' ?>
                </button>
                <a href="/proveedores" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
