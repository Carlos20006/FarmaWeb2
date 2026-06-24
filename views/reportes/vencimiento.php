<?php
require_once dirname(__DIR__, 2) . '/header.php';
require_once dirname(__DIR__, 2) . '/controllers/ReporteController.php';

$controller = new ReporteController();
$vencimientos = $controller->getVencidosProximos();
?>

<section class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
    <div>
        <h1 class="font-headline-xl text-headline-xl text-primary mb-2">Pr&oacute;ximos a Vencer</h1>
        <p class="font-body-md text-body-md text-on-surface-variant max-w-xl">Lotes que vencen en los pr&oacute;ximos 30 d&iacute;as.</p>
    </div>
</section>

<div class="bg-surface-container-lowest rounded-[24px] overflow-hidden shadow-[0_4px_15px_rgba(0,0,0,0.06)]">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-surface-container-low">
                <th class="px-8 py-5 font-label-md text-label-md text-primary uppercase tracking-wider">Medicamento</th>
                <th class="px-8 py-5 font-label-md text-label-md text-primary uppercase tracking-wider">Lote</th>
                <th class="px-8 py-5 font-label-md text-label-md text-primary uppercase tracking-wider">Vencimiento</th>
                <th class="px-8 py-5 font-label-md text-label-md text-primary uppercase tracking-wider">Stock</th>
                <th class="px-8 py-5 font-label-md text-label-md text-primary uppercase tracking-wider">D&iacute;as Rest.</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-surface-container-low">
            <?php if(count($vencimientos) > 0): ?>
                <?php foreach($vencimientos as $v): ?>
                <tr class="row-hover transition-all duration-300">
                    <td class="px-8 py-5 font-body-md text-body-md text-on-surface"><?php echo htmlspecialchars($v['nombre_generico']); ?></td>
                    <td class="px-8 py-5 font-body-md text-body-md text-on-surface"><?php echo htmlspecialchars($v['numero_lote']); ?></td>
                    <td class="px-8 py-5 font-body-md text-body-md text-on-surface"><?php echo $v['fecha_vencimiento']; ?></td>
                    <td class="px-8 py-5 font-body-md text-body-md text-on-surface"><?php echo $v['cantidad_actual']; ?></td>
                    <td class="px-8 py-5">
                        <?php if($v['dias_restantes'] <= 7): ?>
                            <span class="px-3 py-1 bg-error-container text-on-error-container rounded-full text-label-sm font-label-sm"><?php echo $v['dias_restantes']; ?> d&iacute;as</span>
                        <?php else: ?>
                            <span class="px-3 py-1 bg-secondary-container text-on-secondary-container rounded-full text-label-sm font-label-sm"><?php echo $v['dias_restantes']; ?> d&iacute;as</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="px-8 py-10 text-center font-body-md text-body-md text-on-surface-variant">No hay lotes pr&oacute;ximos a vencer</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="mt-6">
    <a href="../../dashboard.php" class="inline-flex items-center gap-2 px-6 py-3 font-label-md text-label-md text-primary bg-primary-fixed rounded-full hover:bg-primary-fixed-dim transition-all no-underline">
        <span class="material-symbols-outlined">arrow_back</span> Volver al inicio
    </a>
</div>

<?php require_once dirname(__DIR__, 2) . '/footer.php'; ?>
