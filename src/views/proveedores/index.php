<div class="container">
    <div class="flex flex-between mb-lg">
        <h1>Proveedores</h1>
        <a href="/proveedores/crear" class="btn btn-primary">+ Nuevo Proveedor</a>
    </div>

    <?php if (empty($proveedores)): ?>
        <div class="card">
            <p class="text-center text-muted">No hay proveedores registrados</p>
        </div>
    <?php else: ?>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>RFC</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($proveedores as $prov): ?>
                        <tr>
                            <td><?= htmlspecialchars($prov['id']) ?></td>
                            <td><?= htmlspecialchars($prov['nombre']) ?></td>
                            <td><?= htmlspecialchars($prov['rfc'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($prov['telefono'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($prov['email']) ?></td>
                            <td class="flex gap-sm">
                                <a href="/proveedores/detalle?id=<?= $prov['id'] ?>" class="btn btn-sm btn-outline">Ver</a>
                                <a href="/proveedores/editar?id=<?= $prov['id'] ?>" class="btn btn-sm btn-primary">Editar</a>
                                <a href="/proveedores/eliminar?id=<?= $prov['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar?')">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
