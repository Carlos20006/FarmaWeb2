<?php
require_once dirname(__DIR__, 2) . '/header.php';
require_once dirname(__DIR__, 2) . '/controllers/ReporteController.php';

$controller = new ReporteController();
$masVendidos = $controller->getMasVendidos(10);
?>

<section class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
    <div>
        <h1 class="font-headline-xl text-headline-xl text-primary mb-2">M&aacute;s Vendidos</h1>
        <p class="font-body-md text-body-md text-on-surface-variant max-w-xl">Medicamentos con mayor volumen de ventas.</p>
    </div>
</section>

<div class="bg-surface-container-lowest rounded-[24px] overflow-hidden shadow-[0_4px_15px_rgba(0,0,0,0.06)]">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-surface-container-low">
                <th class="px-8 py-5 font-label-md text-label-md text-primary uppercase tracking-wider">#</th>
                <th class="px-8 py-5 font-label-md text-label-md text-primary uppercase tracking-wider">Medicamento</th>
                <th class="px-8 py-5 font-label-md text-label-md text-primary uppercase tracking-wider">Unidades Vendidas</th>
                <th class="px-8 py-5 font-label-md text-label-md text-primary uppercase tracking-wider">N&uacute;mero de Ventas</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-surface-container-low">
            <?php if(count($masVendidos) > 0): ?>
                <?php $pos = 1; foreach($masVendidos as $m): ?>
                <tr class="row-hover transition-all duration-300">
                    <td class="px-8 py-5">
                        <span class="font-headline-md text-headline-md <?php echo $pos <= 3 ? 'text-primary' : 'text-on-surface-variant'; ?>">
                            <?php if($pos == 1): ?>
                                <span class="material-symbols-outlined text-primary" style="font-variation-settings:'FILL'1">emoji_events</span>
                            <?php elseif($pos == 2): ?>
                                <span class="material-symbols-outlined text-secondary" style="font-variation-settings:'FILL'1">emoji_events</span>
                            <?php elseif($pos == 3): ?>
                                <span class="material-symbols-outlined text-tertiary" style="font-variation-settings:'FILL'1">emoji_events</span>
                            <?php else: ?>
                                <?php echo $pos; ?>
                            <?php endif; ?>
                        </span>
                    </td>
                    <td class="px-8 py-5 font-body-md text-body-md text-on-surface"><?php echo htmlspecialchars($m['nombre_generico']); ?></td>
                    <td class="px-8 py-5 font-headline-md text-headline-md text-on-surface"><?php echo $m['total_vendido']; ?></td>
                    <td class="px-8 py-5 font-body-md text-body-md text-on-surface"><?php echo $m['numero_ventas']; ?></td>
                </tr>
                <?php $pos++; endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="px-8 py-10 text-center font-body-md text-body-md text-on-surface-variant">No hay ventas registradas para mostrar estad&iacute;sticas.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="mt-6 flex gap-3">
    <a href="/FarmaWeb/dashboard.php" class="inline-flex items-center gap-2 px-6 py-3 font-label-md text-label-md text-primary bg-primary-fixed rounded-full hover:bg-primary-fixed-dim transition-all no-underline">
        <span class="material-symbols-outlined">arrow_back</span> Volver al inicio
    </a>
    <button onclick="window.print()" class="inline-flex items-center gap-2 px-6 py-3 font-label-md text-label-md text-on-surface bg-surface-container-high rounded-full hover:bg-surface-container-highest transition-all">
        <span class="material-symbols-outlined">print</span> Imprimir
    </button>
</div>

<?php require_once dirname(__DIR__, 2) . '/footer.php'; ?>
