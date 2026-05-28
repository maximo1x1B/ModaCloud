<div class="flex-between mb-lg">
    <h1>Editar usuario</h1>
    <a href="/admin/usuarios" class="btn btn-outline">← Volver</a>
</div>

<div class="card" style="max-width:500px;margin:0 auto">
    <form action="/admin/usuarios/editar?id=<?= $usuario['id'] ?>" method="POST">

        <div class="form-group">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control"
                   value="<?= htmlspecialchars($usuario['nombre']) ?>" required>
        </div>

        <div class="form-group">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control"
                   value="<?= htmlspecialchars($usuario['email']) ?>" required>
        </div>

        <div class="form-group">
            <label class="form-label">Rol</label>
            <select name="rol" class="form-control">
                <?php foreach (['cliente','proveedor','gerente','admin'] as $r): ?>
                    <option value="<?= $r ?>" <?= $usuario['rol'] === $r ? 'selected' : '' ?>>
                        <?= ucfirst($r) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="flex gap-md mt-lg" style="justify-content:flex-end">
            <a href="/admin/usuarios" class="btn btn-outline">Cancelar</a>
            <button type="submit" class="btn btn-primary">Guardar cambios</button>
        </div>

    </form>
</div>