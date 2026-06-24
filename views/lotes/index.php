<?php
require_once dirname(__DIR__, 2) . '/header.php';
require_once dirname(__DIR__, 2) . '/controllers/LoteController.php';

$controller = new LoteController();
$lotes = $controller->index();
?>

<section class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
    <div>
        <h1 class="font-headline-xl text-headline-xl text-primary mb-2">Lotes</h1>
        <p class="font-body-md text-body-md text-on-surface-variant max-w-xl">Gestione los lotes de medicamentos y sus fechas de vencimiento.</p>
    </div>
    <a href="create.php" class="squishy-btn flex items-center gap-2 bg-primary text-white px-8 py-3 rounded-full font-label-md text-label-md shadow-lg hover:bg-primary-container hover:text-on-primary-container transition-all duration-300 no-underline">
        <span class="material-symbols-outlined">add</span>
        Nuevo Lote
    </a>
</section>

<?php if(isset($_SESSION['mensaje'])): ?>
    <div class="flex items-center gap-3 px-6 py-4 mb-6 bg-primary-fixed text-on-primary-fixed rounded-[20px] font-body-md text-body-md">
        <span class="material-symbols-outlined">check_circle</span>
        <?php echo $_SESSION['mensaje']; unset($_SESSION['mensaje']); ?>
    </div>
<?php endif; ?>

<div class="bg-surface-container-lowest rounded-[24px] overflow-hidden shadow-[0_4px_15px_rgba(0,0,0,0.06)]">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-surface-container-low">
                <th class="px-8 py-5 font-label-md text-label-md text-primary uppercase tracking-wider">Lote</th>
                <th class="px-8 py-5 font-label-md text-label-md text-primary uppercase tracking-wider">Medicamento</th>
                <th class="px-8 py-5 font-label-md text-label-md text-primary uppercase tracking-wider">Cantidad</th>
                <th class="px-8 py-5 font-label-md text-label-md text-primary uppercase tracking-wider">Vencimiento</th>
                <th class="px-8 py-5 font-label-md text-label-md text-primary uppercase tracking-wider text-right">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-surface-container-low">
            <?php foreach($lotes as $l): ?>
            <tr class="row-hover transition-all duration-300 group">
                <td class="px-8 py-5">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary-container" style="font-variation-settings:'FILL'1">tag</span>
                        <span class="font-body-md text-body-md text-on-surface"><?php echo htmlspecialchars($l['numero_lote']); ?></span>
                    </div>
                </td>
                <td class="px-8 py-5 font-body-md text-body-md text-on-surface"><?php echo htmlspecialchars($l['nombre_generico']); ?></td>
                <td class="px-8 py-5 font-body-md text-body-md text-on-surface"><?php echo $l['cantidad_actual']; ?></td>
                <td class="px-8 py-5">
                    <?php
                    $vencido = strtotime($l['fecha_vencimiento']) < strtotime(date('Y-m-d'));
                    ?>
                    <span class="font-body-md text-body-md <?php echo $vencido ? 'text-error' : 'text-on-surface'; ?>">
                        <?php echo $l['fecha_vencimiento']; ?>
                        <?php if($vencido): ?>
                            <span class="material-symbols-outlined text-error text-sm" style="font-variation-settings:'FILL'1">warning</span>
                        <?php endif; ?>
                    </span>
                </td>
                <td class="px-8 py-5 text-right">
                    <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <a href="delete.php?id=<?php echo $l['id_lote']; ?>&csrf_token=<?php echo $_SESSION['csrf_token']; ?>" class="p-2 rounded-full bg-tertiary-container hover:bg-tertiary transition-colors text-on-tertiary-container squishy-btn no-underline inline-flex" onclick="return confirm('&iquest;Eliminar este lote?')">
                            <span class="material-symbols-outlined text-[20px]">delete</span>
                        </a>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once dirname(__DIR__, 2) . '/footer.php'; ?>
