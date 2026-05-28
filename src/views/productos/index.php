<?php
$porCategoria = [];
foreach ($productos as $p) {
    $cat = $porCategoria[$p['categoria'] ?? 'Sin categoría'][] = $p;
}
$porCategoria = [];
foreach ($productos as $p) {
    $cat = $p['categoria'] ?? 'Sin categoría';
    $porCategoria[$cat][] = $p;
}
$iconos = [
    'Ropa'         => '👕',
    'Accesorios'   => '👜',
    'Calzado'      => '👟',
    'Bolsas'       => '🛍️',
    'Sin categoría'=> '📦',
];
?>

<!-- Toolbar -->
<div class="flex-between mb-lg" style="flex-wrap:wrap;gap:var(--space-md)">
    <h1 style="margin:0">Productos</h1>
    <div class="flex gap-md" style="flex-wrap:wrap;align-items:center">

        <!-- Buscador -->
        <div style="position:relative">
            <span style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:var(--color-text-light)">🔍</span>
            <input
                type="text"
                id="buscador"
                placeholder="Buscar producto..."
                class="form-control"
                style="padding-left:2rem;width:220px"
                oninput="buscar(this.value)"
            >
        </div>

        <!-- Toggle vista -->
        <div class="flex" style="border:1.5px solid var(--color-border);border-radius:var(--radius-md);overflow:hidden">
            <button id="btn-grid" onclick="setVista('grid')"
                class="btn btn-sm"
                style="border-radius:0;border:none;background:var(--color-primary);color:#fff">
                ⊞ Grid
            </button>
            <button id="btn-cat" onclick="setVista('categoria')"
                class="btn btn-sm"
                style="border-radius:0;border:none;background:transparent;color:var(--color-text-muted)">
                ☰ Categoría
            </button>
        </div>

        <a href="/productos/crear" class="btn btn-primary">+ Nuevo producto</a>
    </div>
</div>

<!-- Sin resultados -->
<div id="sin-resultados" style="display:none" class="card text-center">
    <p class="text-muted">No se encontraron productos.</p>
</div>

<?php if (empty($productos)): ?>
    <div class="card text-center">
        <p class="text-muted">No hay productos registrados aún.</p>
        <a href="/productos/crear" class="btn btn-outline mt-md">Agregar el primero</a>
    </div>

<?php else: ?>

<!-- ============================================================
     Vista: Grid (todos juntos)
============================================================ -->
<div id="vista-grid">
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:var(--space-lg)">
        <?php foreach ($productos as $p): ?>
        <?= renderCard($p) ?>
        <?php endforeach; ?>
    </div>
</div>

<!-- ============================================================
     Vista: Por categoría
============================================================ -->
<div id="vista-categoria" style="display:none">
    <?php foreach ($porCategoria as $categoria => $items): ?>
    <div class="categoria-seccion">
        <div class="flex-between mt-lg mb-md" style="border-bottom:2px solid var(--color-border);padding-bottom:var(--space-sm)">
            <div class="flex gap-sm" style="align-items:center">
                <span style="font-size:var(--font-size-xl)"><?= $iconos[$categoria] ?? '🏷️' ?></span>
                <h2 style="margin:0;font-size:var(--font-size-lg)"><?= htmlspecialchars($categoria) ?></h2>
                <span class="badge badge-primary"><?= count($items) ?> productos</span>
            </div>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:var(--space-lg);margin-bottom:var(--space-xl)">
            <?php foreach ($items as $p): ?>
            <?= renderCard($p) ?>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php endif; ?>

<?php
function renderCard($p) {
    $nombre  = htmlspecialchars($p['nombre']);
    $cat     = htmlspecialchars($p['categoria'] ?? 'Sin categoría');
    $desc    = htmlspecialchars(substr($p['descripcion'] ?? '', 0, 65));
    $precio  = number_format($p['precio'], 2);
    $img     = htmlspecialchars($p['imagen_url'] ?? '');
    $id      = $p['id'];
    ob_start();
?>
<div class="producto-card card"
     data-nombre="<?= strtolower($nombre) ?>"
     data-categoria="<?= strtolower($cat) ?>"
     style="padding:0;overflow:hidden;display:flex;flex-direction:column">

    <div style="height:180px;overflow:hidden;background:var(--color-bg);flex-shrink:0">
        <?php if (!empty($img)): ?>
            <img src="<?= $img ?>" alt="<?= $nombre ?>"
                 style="width:100%;height:100%;object-fit:cover;transition:transform 0.3s ease"
                 onmouseover="this.style.transform='scale(1.05)'"
                 onmouseout="this.style.transform='scale(1)'"
                 onerror="this.src='https://via.placeholder.com/400x180?text=Sin+imagen'">
        <?php else: ?>
            <div class="flex-center" style="height:100%;color:var(--color-text-light);font-size:var(--font-size-sm)">Sin imagen</div>
        <?php endif; ?>
    </div>

    <div style="padding:var(--space-md);display:flex;flex-direction:column;flex:1">
        <div class="flex-between mb-sm">
            <span class="badge badge-primary"><?= $cat ?></span>
            <span class="text-bold" style="color:var(--color-primary-dark);font-size:var(--font-size-md)">
                $<?= $precio ?>
            </span>
        </div>
        <h3 style="font-size:var(--font-size-md);margin-bottom:var(--space-xs);line-height:1.3"><?= $nombre ?></h3>
        <?php if (!empty($desc)): ?>
            <p class="text-small text-muted" style="margin-bottom:var(--space-md);line-height:1.4;flex:1"><?= $desc ?>...</p>
        <?php endif; ?>
        <div class="flex gap-sm" style="margin-top:auto">
            <a href="/productos/editar?id=<?= $id ?>" class="btn btn-outline btn-sm w-full" style="justify-content:center">Editar</a>
            <a href="/productos/eliminar?id=<?= $id ?>" class="btn btn-danger btn-sm w-full" style="justify-content:center"
               onclick="return confirm('¿Eliminar <?= $nombre ?>?')">Eliminar</a>
        </div>
    </div>
</div>
<?php
    return ob_get_clean();
}
?>
<script src="/js/productos.js"></script>