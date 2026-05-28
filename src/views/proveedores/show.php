<div class="container">
    <div class="card" style="max-width: 500px; margin: 2rem auto;">
        <div class="card-header">
            <h2 class="card-title"><?= htmlspecialchars($proveedor['nombre']) ?></h2>
        </div>

        <div style="padding: 1.5rem;">
            <div class="form-group">
                <label class="form-label">ID</label>
                <p><?= htmlspecialchars($proveedor['id']) ?></p>
            </div>

            <div class="form-group">
                <label class="form-label">RFC</label>
                <p><?= htmlspecialchars($proveedor['rfc'] ?? 'N/A') ?></p>
            </div>

            <div class="form-group">
                <label class="form-label">Teléfono</label>
                <p><?= htmlspecialchars($proveedor['telefono'] ?? 'N/A') ?></p>
            </div>

            <div class="form-group">
                <label class="form-label">Email</label>
                <p><?= htmlspecialchars($proveedor['email']) ?></p>
            </div>

            <div class="form-group">
                <label class="form-label">Creado</label>
                <p><?= htmlspecialchars($proveedor['created_at']) ?></p>
            </div>

            <div class="flex gap-md mt-lg">
                <a href="/proveedores/editar?id=<?= $proveedor['id'] ?>" class="btn btn-primary">Editar</a>
                <a href="/proveedores" class="btn btn-secondary">Volver</a>
            </div>
        </div>
    </div>
</div>
