<div class="container">
    <div class="flex flex-between mb-lg">
        <h1>Control de Inventario</h1>
        <div class="flex gap-sm">
            <a href="/inventario/alertas" class="btn btn-outline">Ver Alertas</a>
            <a href="/inventario/registrar" class="btn btn-primary">+ Registrar Movimiento</a>
        </div>
    </div>

    <?php if (empty($inventarios)): ?>
        <div class="card">
            <p class="text-center text-muted">No hay movimientos de inventario registrados</p>
        </div>
    <?php else: ?>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Producto</th>
                        <th>Cantidad Actual</th>
                        <th>Stock Mínimo</th>
                        <th>Tipo de Movimiento</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($inventarios as $inv): ?>
                        <tr>
                            <td><?= htmlspecialchars($inv['id'] ?? '') ?></td>
                            <td>
                                <?php 
                                    // Intentamos obtener el nombre del producto de varias formas posibles
                                    echo htmlspecialchars($inv['producto_nombre'] ?? $inv['nombre'] ?? 'Producto #' . ($inv['product_id'] ?? 'N/A')); 
                                ?>
                            </td>
                            <td><?= htmlspecialchars($inv['cantidad'] ?? 0) ?></td>
                            <td><?= htmlspecialchars($inv['stock_minimo'] ?? 0) ?></td>
                            <td>
                                <?php $tipo = $inv['tipo_movimiento'] ?? 'entrada'; ?>
                                <span class="badge <?= $tipo === 'entrada' ? 'badge-success' : ($tipo === 'salida' ? 'badge-danger' : 'badge-primary') ?>">
                                    <?= htmlspecialchars(ucfirst($tipo)) ?>
                                </span>
                            </td>
                            <td>
                                <?php 
                                    $cant = $inv['cantidad'] ?? 0;
                                    $min = $inv['stock_minimo'] ?? 0;
                                    if ($cant <= $min): 
                                ?>
                                    <span class="badge badge-danger stock-danger">Stock Bajo</span>
                                <?php else: ?>
                                    <span class="badge badge-success stock-ok">Suficiente</span>
                                <?php endif; ?>
                            </td>
                            <td class="flex gap-sm">
                                <a href="/inventario/editar?id=<?= $inv['id'] ?>" class="btn btn-sm btn-primary">Editar</a>
                                <a href="/inventario/eliminar?id=<?= $inv['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar registro?')">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
