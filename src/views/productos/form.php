<?php
$editando = isset($producto);
$titulo   = $editando ? 'Editar producto' : 'Nuevo producto';
$accion   = $editando ? '/productos/editar?id=' . $producto['id'] : '/productos/crear';
?>

<div class="flex-between mb-lg">
    <h1><?= $titulo ?></h1>
    <a href="/productos" class="btn btn-outline">← Volver</a>
</div>

<div class="card">
    <form action="<?= $accion ?>" method="POST">

        <div class="flex gap-lg">

            <!-- Columna izquierda -->
            <div style="flex:2">

                <div class="form-group">
                    <label class="form-label" for="nombre">
                        Nombre <span style="color:var(--color-danger-dark)">*</span>
                    </label>
                    <input
                        type="text"
                        id="nombre"
                        name="nombre"
                        class="form-control"
                        placeholder="Ej. Playera básica"
                        value="<?= htmlspecialchars($producto['nombre'] ?? '') ?>"
                        required
                        autofocus
                    >
                </div>

                <div class="form-group">
                    <label class="form-label" for="descripcion">Descripción</label>
                    <textarea
                        id="descripcion"
                        name="descripcion"
                        class="form-control"
                        placeholder="Describe el producto..."
                    ><?= htmlspecialchars($producto['descripcion'] ?? '') ?></textarea>
                </div>

                <div class="flex gap-md">

                    <div class="form-group w-full">
                        <label class="form-label" for="precio">
                            Precio <span style="color:var(--color-danger-dark)">*</span>
                        </label>
                        <input
                            type="number"
                            id="precio"
                            name="precio"
                            class="form-control"
                            placeholder="0.00"
                            step="0.01"
                            min="0"
                            value="<?= htmlspecialchars($producto['precio'] ?? '') ?>"
                            required
                        >
                    </div>

                    <div class="form-group w-full">
                        <label class="form-label" for="categoria">Categoría</label>
                        <input
                            type="text"
                            id="categoria"
                            name="categoria"
                            class="form-control"
                            placeholder="Ej. Ropa, Accesorios"
                            value="<?= htmlspecialchars($producto['categoria'] ?? '') ?>"
                            list="categorias-list"
                        >
                        <datalist id="categorias-list">
                            <option value="Ropa">
                            <option value="Accesorios">
                            <option value="Calzado">
                            <option value="Bolsas">
                        </datalist>
                    </div>

                </div>

            </div>

            <!-- Columna derecha -->
            <div style="flex:1">

                <div class="form-group">
                    <label class="form-label" for="imagen_url">URL de imagen</label>
                    <input
                        type="text"
                        id="imagen_url"
                        name="imagen_url"
                        class="form-control"
                        placeholder="https://..."
                        value="<?= htmlspecialchars($producto['imagen_url'] ?? '') ?>"
                        oninput="previewImagen(this.value)"
                    >
                </div>

                <!-- Preview de imagen -->
                <div style="
                    border: 1.5px dashed var(--color-border);
                    border-radius: var(--radius-md);
                    height: 180px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    overflow: hidden;
                    background: var(--color-bg);
                ">
                    <?php if (!empty($producto['imagen_url'])): ?>
                        <img
                            id="img-preview"
                            src="<?= htmlspecialchars($producto['imagen_url']) ?>"
                            alt="Preview"
                            style="width:100%;height:180px;object-fit:cover;border-radius:var(--radius-md)"
                            onerror="this.style.display='none';document.getElementById('img-placeholder').style.display='block'"
                        >
                        <span id="img-placeholder" class="text-muted text-small" style="display:none">
                            Sin imagen
                        </span>
                    <?php else: ?>
                        <img id="img-preview" src="" alt="Preview"
                             style="width:100%;height:180px;object-fit:cover;border-radius:var(--radius-md);display:none">
                        <span id="img-placeholder" class="text-muted text-small">
                            Vista previa
                        </span>
                    <?php endif; ?>
                </div>

            </div>

        </div>

        <!-- Botones -->
        <div class="flex gap-md mt-lg" style="justify-content:flex-end">
            <a href="/productos" class="btn btn-outline">Cancelar</a>
            <button type="submit" class="btn btn-primary">
                <?= $editando ? 'Guardar cambios' : 'Crear producto' ?>
            </button>
        </div>

    </form>
</div>

<script>
function previewImagen(url) {
    const img         = document.getElementById('img-preview');
    const placeholder = document.getElementById('img-placeholder');

    if (url.trim() === '') {
        img.style.display         = 'none';
        placeholder.style.display = 'block';
        return;
    }

    img.src           = url;
    img.style.display = 'block';
    placeholder.style.display = 'none';

    img.onerror = () => {
        img.style.display         = 'none';
        placeholder.style.display = 'block';
    };
}
</script>