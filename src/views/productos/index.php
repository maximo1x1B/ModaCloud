<div class="flex-between mb-lg">
    <h1>Productos</h1>
    <a href="/productos/crear" class="btn btn-primary">+ Nuevo producto</a>
</div>

<?php if (empty($productos)): ?>
    <div class="card text-center">
        <p class="text-muted">No hay productos registrados aún.</p>
        <a href="/productos/crear" class="btn btn-outline mt-md">Agregar el primero</a>
    </div>

<?php else: ?>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Categoría</th>
                    <th>Precio</th>
                    <th>Imagen</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos as $p): ?>
                <tr>
                    <td class="text-muted text-small"><?= $p['id'] ?></td>
                    <td>
                        <span class="text-bold"><?= htmlspecialchars($p['nombre']) ?></span>
                        <?php if (!empty($p['descripcion'])): ?>
                            <br>
                            <span class="text-muted text-small">
                                <?= htmlspecialchars(substr($p['descripcion'], 0, 60)) ?>...
                            </span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if (!empty($p['categoria'])): ?>
                            <span class="badge badge-primary">
                                <?= htmlspecialchars($p['categoria']) ?>
                            </span>
                        <?php else: ?>
                            <span class="text-muted text-small">—</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <span class="text-bold">
                            $<?= number_format($p['precio'], 2) ?>
                        </span>
                    </td>
                    <td>
                        <?php if (!empty($p['imagen_url'])): ?>
                            <img
                                src="<?= htmlspecialchars($p['imagen_url']) ?>"
                                alt="<?= htmlspecialchars($p['nombre']) ?>"
                                style="width:48px;height:48px;object-fit:cover;border-radius:8px;"
                                onerror="this.src='https://via.placeholder.com/48x48?text=?'"
                            >
                        <?php else: ?>
                            <span class="text-muted text-small">Sin imagen</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="flex gap-sm">
                            <a href="/productos/editar?id=<?= $p['id'] ?>"
                               class="btn btn-outline btn-sm">
                                Editar
                            </a>
                            <a href="/productos/eliminar?id=<?= $p['id'] ?>"
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('¿Eliminar <?= htmlspecialchars($p['nombre']) ?>?')">
                                Eliminar
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>