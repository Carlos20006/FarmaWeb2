<?php
require_once dirname(__DIR__, 2) . '/header.php';
require_once dirname(__DIR__, 2) . '/controllers/MedicamentoController.php';

$controller = new MedicamentoController();
$medicamentos = $controller->index();
?>

<section class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
    <div>
        <h1 class="font-headline-xl text-headline-xl text-primary mb-2">Inventario de Medicamentos</h1>
        <p class="font-body-md text-body-md text-on-surface-variant max-w-xl">Gestione existencias, categor&iacute;as y precios de su farmacia.</p>
    </div>
    <a href="create.php" class="squishy-btn flex items-center gap-2 bg-primary text-white px-8 py-3 rounded-full font-label-md text-label-md shadow-lg hover:bg-primary-container hover:text-on-primary-container transition-all duration-300 no-underline">
        <span class="material-symbols-outlined">add</span>
        Nuevo Medicamento
    </a>
</section>

<?php if(isset($_SESSION['mensaje'])): ?>
    <div class="flex items-center gap-3 px-6 py-4 mb-6 bg-primary-fixed text-on-primary-fixed rounded-[20px] font-body-md text-body-md">
        <span class="material-symbols-outlined">check_circle</span>
        <?php echo $_SESSION['mensaje']; unset($_SESSION['mensaje']); ?>
    </div>
<?php endif; ?>

<div class="glass-card rounded-[24px] p-6 mb-8 shadow-[0_4px_15px_rgba(0,0,0,0.06)]" style="background: rgba(255,255,255,0.8); backdrop-filter: blur(12px);">
    <div class="relative w-full max-w-md">
        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline">search</span>
        <input class="w-full pl-12 pr-6 py-3 bg-[#f0f0f0] border-none rounded-full focus:ring-2 focus:ring-secondary-container focus:bg-white transition-all font-body-md text-body-md outline-none" placeholder="Buscar por nombre o categor&iacute;a..." type="text" id="searchInput"/>
    </div>
</div>

<div class="bg-surface-container-lowest rounded-[24px] overflow-hidden shadow-[0_4px_15px_rgba(0,0,0,0.06)] relative">
    <div class="absolute -right-8 -bottom-8 opacity-5 pointer-events-none transform -rotate-12">
        <span class="material-symbols-outlined text-[200px] text-primary" style="font-variation-settings:'FILL'1">eco</span>
    </div>
    <table class="w-full text-left border-collapse" id="medicamentosTable">
        <thead>
            <tr class="bg-surface-container-low">
                <th class="px-8 py-5 font-label-md text-label-md text-primary uppercase tracking-wider">Nombre</th>
                <th class="px-8 py-5 font-label-md text-label-md text-primary uppercase tracking-wider">Presentaci&oacute;n</th>
                <th class="px-8 py-5 font-label-md text-label-md text-primary uppercase tracking-wider">Categor&iacute;a</th>
                <th class="px-8 py-5 font-label-md text-label-md text-primary uppercase tracking-wider">Precio</th>
                <th class="px-8 py-5 font-label-md text-label-md text-primary uppercase tracking-wider">Stock M&iacute;n</th>
                <th class="px-8 py-5 font-label-md text-label-md text-primary uppercase tracking-wider text-right">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-surface-container-low">
            <?php if(count($medicamentos) > 0): ?>
                <?php foreach($medicamentos as $m): ?>
                <tr class="row-hover transition-all duration-300 group">
                    <td class="px-8 py-5">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary-container" style="font-variation-settings:'FILL'1">pill</span>
                            <span class="font-body-md text-body-md text-on-surface"><?php echo htmlspecialchars($m['nombre_generico']); ?></span>
                        </div>
                    </td>
                    <td class="px-8 py-5 font-body-md text-body-md text-on-surface"><?php echo htmlspecialchars($m['presentacion']); ?></td>
                    <td class="px-8 py-5">
                        <span class="px-3 py-1 bg-secondary-container text-on-secondary-container rounded-full text-label-sm font-label-sm"><?php echo htmlspecialchars($m['categoria']); ?></span>
                    </td>
                    <td class="px-8 py-5 font-body-md text-body-md font-semibold text-on-surface">$<?php echo number_format($m['precio'], 2); ?></td>
                    <td class="px-8 py-5 font-body-md text-body-md text-on-surface"><?php echo $m['stock_minimo']; ?></td>
                    <td class="px-8 py-5 text-right">
                        <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <a href="edit.php?id=<?php echo $m['id_medicamento']; ?>" class="p-2 rounded-full bg-secondary-fixed hover:bg-secondary transition-colors text-on-secondary-fixed squishy-btn no-underline inline-flex">
                                <span class="material-symbols-outlined text-[20px]">edit</span>
                            </a>
                            <a href="delete.php?id=<?php echo $m['id_medicamento']; ?>&csrf_token=<?php echo $_SESSION['csrf_token']; ?>" class="p-2 rounded-full bg-tertiary-container hover:bg-tertiary transition-colors text-on-tertiary-container squishy-btn no-underline inline-flex" onclick="return confirm('&iquest;Eliminar este medicamento?')">
                                <span class="material-symbols-outlined text-[20px]">delete</span>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="px-8 py-10 text-center font-body-md text-body-md text-on-surface-variant">No hay medicamentos registrados</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="mt-12 flex flex-col items-center justify-center opacity-40">
    <span class="material-symbols-outlined text-primary text-6xl mb-4" style="font-variation-settings:'FILL'1">opacity</span>
    <p class="font-body-md text-body-md italic">Manteniendo la salud con cada registro</p>
</div>

<script>
document.getElementById('searchInput')?.addEventListener('input', function() {
    const term = this.value.toLowerCase();
    document.querySelectorAll('#medicamentosTable tbody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(term) ? '' : 'none';
    });
});
</script>

<?php require_once dirname(__DIR__, 2) . '/footer.php'; ?>
