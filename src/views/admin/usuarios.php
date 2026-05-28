<div class="flex-between mb-lg">
    <h1>Gestión de usuarios</h1>
    <span class="badge badge-primary" style="font-size:var(--font-size-sm);padding:6px 12px">
        <?= count($usuarios) ?> usuarios registrados
    </span>
</div>

<?php if (empty($usuarios)): ?>
    <div class="card text-center">
        <p class="text-muted">No hay usuarios registrados.</p>
    </div>
<?php else: ?>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Registro</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $u): ?>
                <tr>
                    <td class="text-muted text-small"><?= $u['id'] ?></td>
                    <td class="text-bold"><?= htmlspecialchars($u['nombre']) ?></td>
                    <td class="text-muted text-small"><?= htmlspecialchars($u['email']) ?></td>
                    <td>
                        <?php
                        $badgeRol = [
                            'admin'     => 'badge-danger',
                            'gerente'     => 'badge-warning',
                            'cliente'   => 'badge-primary',
                        ];
                        $clase = $badgeRol[$u['rol']] ?? 'badge-primary';
                        ?>
                        <span class="badge <?= $clase ?>">
                            <?= ucfirst($u['rol']) ?>
                        </span>
                    </td>
                    <td class="text-muted text-small">
                        <?= date('d/m/Y', strtotime($u['created_at'])) ?>
                    </td>
                    <td>
                        <div class="flex gap-sm">
                            <a href="/admin/usuarios/editar?id=<?= $u['id'] ?>"
                               class="btn btn-outline btn-sm">Editar</a>
                            <?php if ($u['id'] !== (int)$_SESSION['user_id']): ?>
                                <a href="/admin/usuarios/eliminar?id=<?= $u['id'] ?>"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('¿Eliminar a <?= htmlspecialchars($u['nombre']) ?>?')">
                                   Eliminar
                                </a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>