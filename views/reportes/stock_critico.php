<?php
require_once dirname(__DIR__, 2) . '/header.php';
require_once dirname(__DIR__, 2) . '/controllers/ReporteController.php';

$controller = new ReporteController();
$stockCritico = $controller->getStockCritico();
?>

<section class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
    <div>
        <h1 class="font-headline-xl text-headline-xl text-primary mb-2">Stock Cr&iacute;tico</h1>
        <p class="font-body-md text-body-md text-on-surface-variant max-w-xl">Medicamentos con stock por debajo del m&iacute;nimo establecido.</p>
    </div>
</section>

<div class="bg-surface-container-lowest rounded-[24px] overflow-hidden shadow-[0_4px_15px_rgba(0,0,0,0.06)]">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-surface-container-low">
                <th class="px-8 py-5 font-label-md text-label-md text-primary uppercase tracking-wider">Medicamento</th>
                <th class="px-8 py-5 font-label-md text-label-md text-primary uppercase tracking-wider">Presentaci&oacute;n</th>
                <th class="px-8 py-5 font-label-md text-label-md text-primary uppercase tracking-wider">Stock Total</th>
                <th class="px-8 py-5 font-label-md text-label-md text-primary uppercase tracking-wider">Stock M&iacute;nimo</th>
                <th class="px-8 py-5 font-label-md text-label-md text-primary uppercase tracking-wider">Estado</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-surface-container-low">
            <?php if(count($stockCritico) > 0): ?>
                <?php foreach($stockCritico as $m): ?>
                <tr class="row-hover transition-all duration-300">
                    <td class="px-8 py-5">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-tertiary" style="font-variation-settings:'FILL'1">medication</span>
                            <span class="font-body-md text-body-md text-on-surface"><?php echo htmlspecialchars($m['nombre_generico']); ?></span>
                        </div>
                    </td>
                    <td class="px-8 py-5 font-body-md text-body-md text-on-surface"><?php echo htmlspecialchars($m['presentacion']); ?></td>
                    <td class="px-8 py-5 font-body-md text-body-md text-on-surface"><?php echo $m['stock_total']; ?></td>
                    <td class="px-8 py-5 font-body-md text-body-md text-on-surface"><?php echo $m['stock_minimo']; ?></td>
                    <td class="px-8 py-5">
                        <span class="px-3 py-1 bg-error-container text-on-error-container rounded-full text-label-sm font-label-sm flex items-center gap-1 w-fit">
                            <span class="material-symbols-outlined text-sm">priority_high</span> Cr&iacute;tico
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="px-8 py-10 text-center font-body-md text-body-md text-on-surface-variant">No hay medicamentos con stock cr&iacute;tico</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="mt-6">
    <a href="/FarmaWeb/dashboard.php" class="inline-flex items-center gap-2 px-6 py-3 font-label-md text-label-md text-primary bg-primary-fixed rounded-full hover:bg-primary-fixed-dim transition-all no-underline">
        <span class="material-symbols-outlined">arrow_back</span> Volver al inicio
    </a>
</div>

<?php require_once dirname(__DIR__, 2) . '/footer.php'; ?>
