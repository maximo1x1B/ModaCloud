<div class="flex-between mb-lg">
    <h1>🛒 Mi carrito</h1>
    <a href="/productos" class="btn btn-outline">← Seguir comprando</a>
</div>

<?php if (empty($productos)): ?>
    <div class="card text-center" style="padding:var(--space-2xl)">
        <p style="font-size:3rem;margin-bottom:var(--space-md)">🛒</p>
        <h2 class="text-muted">Tu carrito está vacío</h2>
        <p class="text-muted text-small">Agrega productos desde el catálogo</p>
        <a href="/productos" class="btn btn-primary mt-lg">Ver catálogo</a>
    </div>

<?php else: ?>
    <div class="flex gap-lg" style="align-items:flex-start;flex-wrap:wrap">

        <!-- Lista -->
        <div style="flex:2;min-width:280px">
            <?php foreach ($productos as $p): ?>
            <div class="card mb-md" style="padding:var(--space-md);<?= $p['agotado'] ? 'opacity:0.6' : '' ?>">
                <div class="flex gap-md" style="align-items:center">

                    <!-- Imagen -->
                    <div style="width:80px;height:80px;flex-shrink:0;border-radius:var(--radius-md);overflow:hidden;background:var(--color-bg)">
                        <?php if (!empty($p['imagen_url'])): ?>
                            <img src="<?= htmlspecialchars($p['imagen_url']) ?>"
                                 alt="<?= htmlspecialchars($p['nombre']) ?>"
                                 style="width:100%;height:100%;object-fit:cover">
                        <?php endif; ?>
                    </div>

                    <!-- Info -->
                    <div style="flex:1">
                        <h3 style="font-size:var(--font-size-md);margin-bottom:4px">
                            <?= htmlspecialchars($p['nombre']) ?>
                        </h3>
                        <span class="badge badge-primary text-small">
                            <?= htmlspecialchars($p['categoria'] ?? '') ?>
                        </span>
                        <!-- Stock disponible -->
                        <p class="text-small mt-sm" style="margin:4px 0 0">
                            <?php if ($p['agotado']): ?>
                                <span class="stock-danger">⚠️ Agotado</span>
                            <?php elseif ($p['stock_disponible'] <= 5): ?>
                                <span class="stock-warning">⚡ Solo <?= $p['stock_disponible'] ?> disponibles</span>
                            <?php else: ?>
                                <span class="stock-ok">✓ <?= $p['stock_disponible'] ?> en stock</span>
                            <?php endif; ?>
                        </p>
                    </div>

                    <!-- Contador +/- -->
                    <div class="flex" style="align-items:center;gap:8px;border:1.5px solid var(--color-border);border-radius:var(--radius-md);overflow:hidden">
                        <a href="/carrito/actualizar?id=<?= $p['product_id'] ?>&accion=restar"
                           class="btn btn-sm btn-outline"
                           style="border:none;border-radius:0;padding:6px 12px;font-size:1rem">−</a>
                        <span class="text-bold" style="min-width:24px;text-align:center">
                            <?= $p['cantidad'] ?>
                        </span>
                        <a href="/carrito/actualizar?id=<?= $p['product_id'] ?>&accion=sumar"
                           class="btn btn-sm btn-outline"
                           style="border:none;border-radius:0;padding:6px 12px;font-size:1rem;<?= $p['cantidad'] >= $p['stock_disponible'] ? 'opacity:0.4;pointer-events:none' : '' ?>">+</a>
                    </div>

                    <!-- Subtotal -->
                    <div class="text-center" style="min-width:100px">
                        <p class="text-muted text-small">Subtotal</p>
                        <p class="text-bold" style="color:var(--color-primary-dark);font-size:var(--font-size-lg)">
                            $<?= number_format($p['subtotal'], 2) ?>
                        </p>
                    </div>

                    <!-- Quitar -->
                    <a href="/carrito/quitar?id=<?= $p['product_id'] ?>"
                       class="btn btn-danger btn-sm"
                       onclick="return confirm('¿Quitar <?= htmlspecialchars($p['nombre']) ?>?')">✕</a>

                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Resumen -->
        <div style="flex:1;min-width:240px">
            <div class="card" style="position:sticky;top:80px">
                <h2 class="card-title mb-lg">Resumen</h2>

                <div class="flex-between mb-md">
                    <span class="text-muted">Productos</span>
                    <span><?= count($productos) ?></span>
                </div>
                <div class="flex-between mb-md">
                    <span class="text-muted">Unidades</span>
                    <span><?= array_sum(array_column($productos, 'cantidad')) ?></span>
                </div>

                <div class="flex-between mb-lg" style="border-top:1px solid var(--color-border);padding-top:var(--space-md)">
                    <span class="text-bold" style="font-size:var(--font-size-lg)">Total</span>
                    <span class="text-bold" style="font-size:var(--font-size-xl);color:var(--color-primary-dark)">
                        $<?= number_format($total, 2) ?>
                    </span>
                </div>

                <?php $hayAgotados = array_filter($productos, fn($p) => $p['agotado']); ?>
                <?php if ($hayAgotados): ?>
                    <div class="flash flash-error mb-md" style="font-size:var(--font-size-sm)">
                        Hay productos agotados en tu carrito. Quítalos para continuar.
                    </div>
                <?php endif; ?>

                <form action="/carrito/confirmar" method="POST">
                    <button type="submit" class="btn btn-primary w-full"
                            <?= $hayAgotados ? 'disabled style="opacity:0.5;cursor:not-allowed"' : '' ?>
                            onclick="return confirm('¿Confirmar tu pedido por $<?= number_format($total, 2) ?>?')">
                        ✅ Confirmar pedido
                    </button>
                </form>

                <a href="/carrito/vaciar" class="btn btn-outline w-full mt-md"
                   style="justify-content:center;text-align:center"
                   onclick="return confirm('¿Vaciar todo el carrito?')">
                    🗑️ Vaciar carrito
                </a>
            </div>
        </div>

    </div>
<?php endif; ?>