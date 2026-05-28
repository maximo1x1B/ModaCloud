let vistaActual = 'grid';

function setVista(vista) {
    vistaActual = vista;

    document.getElementById('vista-grid').style.display      = vista === 'grid'      ? 'block' : 'none';
    document.getElementById('vista-categoria').style.display = vista === 'categoria' ? 'block' : 'none';

    const btnGrid = document.getElementById('btn-grid');
    const btnCat  = document.getElementById('btn-cat');

    btnGrid.style.background = vista === 'grid'      ? 'var(--color-primary)' : 'transparent';
    btnGrid.style.color      = vista === 'grid'      ? '#fff'                 : 'var(--color-text-muted)';
    btnCat.style.background  = vista === 'categoria' ? 'var(--color-primary)' : 'transparent';
    btnCat.style.color       = vista === 'categoria' ? '#fff'                 : 'var(--color-text-muted)';

    buscar(document.getElementById('buscador').value);
}

function buscar(query) {
    const q      = query.toLowerCase().trim();
    const cards  = document.querySelectorAll('.producto-card');
    let visibles = 0;

    cards.forEach(card => {
        const coincide = card.dataset.nombre.includes(q) ||
            card.dataset.categoria.includes(q);
        card.style.display = coincide ? 'flex' : 'none';
        if (coincide) visibles++;
    });

    document.querySelectorAll('.categoria-seccion').forEach(sec => {
        const tieneVisibles = [...sec.querySelectorAll('.producto-card')]
            .some(c => c.style.display !== 'none');
        sec.style.display = tieneVisibles ? 'block' : 'none';
    });

    document.getElementById('sin-resultados').style.display =
        visibles === 0 ? 'block' : 'none';
}