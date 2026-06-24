<?php
require_once dirname(__DIR__, 2) . '/header.php';
require_once dirname(__DIR__, 2) . '/controllers/VentaController.php';

$controller = new VentaController();
$ventas = $controller->index();
?>

<section class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
    <div>
        <h1 class="font-headline-xl text-headline-xl text-primary mb-2">Historial de Ventas</h1>
        <p class="font-body-md text-body-md text-on-surface-variant max-w-xl">Consulte el registro completo de ventas realizadas.</p>
    </div>
    <a href="create.php" class="squishy-btn flex items-center gap-2 bg-primary text-white px-8 py-3 rounded-full font-label-md text-label-md shadow-lg hover:bg-primary-container hover:text-on-primary-container transition-all duration-300 no-underline">
        <span class="material-symbols-outlined">add</span>
        Nueva Venta
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
                <th class="px-8 py-5 font-label-md text-label-md text-primary uppercase tracking-wider">#</th>
                <th class="px-8 py-5 font-label-md text-label-md text-primary uppercase tracking-wider">Fecha</th>
                <th class="px-8 py-5 font-label-md text-label-md text-primary uppercase tracking-wider">Cliente</th>
                <th class="px-8 py-5 font-label-md text-label-md text-primary uppercase tracking-wider">Vendedor</th>
                <th class="px-8 py-5 font-label-md text-label-md text-primary uppercase tracking-wider">Total</th>
                <th class="px-8 py-5 font-label-md text-label-md text-primary uppercase tracking-wider text-right">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-surface-container-low">
            <?php foreach($ventas as $v): ?>
            <tr class="row-hover transition-all duration-300 group">
                <td class="px-8 py-5 font-body-md text-body-md text-on-surface"><?php echo $v['id_venta']; ?></td>
                <td class="px-8 py-5 font-body-md text-body-md text-on-surface"><?php echo $v['fecha']; ?></td>
                <td class="px-8 py-5 font-body-md text-body-md text-on-surface"><?php echo htmlspecialchars($v['cliente_nombre']); ?></td>
                <td class="px-8 py-5 font-body-md text-body-md text-on-surface"><?php echo htmlspecialchars($v['vendedor']); ?></td>
                <td class="px-8 py-5 font-body-md text-body-md font-semibold text-on-surface">$<?php echo number_format($v['total'], 2); ?></td>
                <td class="px-8 py-5 text-right">
                    <button type="button" class="p-2 rounded-full bg-secondary-fixed hover:bg-secondary transition-colors text-on-secondary-fixed squishy-btn no-underline inline-flex" data-bs-toggle="modal" data-bs-target="#modal<?php echo $v['id_venta']; ?>">
                        <span class="material-symbols-outlined text-[20px]">visibility</span>
                    </button>

                    <div class="modal fade" id="modal<?php echo $v['id_venta']; ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content" style="border-radius: 24px; border: none; box-shadow: 0 4px 30px rgba(0,0,0,0.08);">
                                <div class="modal-header" style="border: none; padding: 24px 24px 0;">
                                    <h5 class="font-headline-md text-headline-md text-on-surface">Detalles Venta #<?php echo $v['id_venta']; ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body" style="padding: 24px;">
                                    <?php $detalles = $controller->getDetalles($v['id_venta']); ?>
                                    <table class="w-full text-left">
                                        <thead>
                                            <tr class="bg-surface-container-low">
                                                <th class="px-4 py-3 font-label-sm text-label-sm text-primary">Medicamento</th>
                                                <th class="px-4 py-3 font-label-sm text-label-sm text-primary">Lote</th>
                                                <th class="px-4 py-3 font-label-sm text-label-sm text-primary">Cant.</th>
                                                <th class="px-4 py-3 font-label-sm text-label-sm text-primary">Precio</th>
                                                <th class="px-4 py-3 font-label-sm text-label-sm text-primary text-right">Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($detalles as $d): ?>
                                            <tr class="border-b border-surface-container-low">
                                                <td class="px-4 py-3 font-body-md text-body-md"><?php echo htmlspecialchars($d['nombre_generico']); ?></td>
                                                <td class="px-4 py-3 font-body-md text-body-md"><?php echo htmlspecialchars($d['numero_lote']); ?></td>
                                                <td class="px-4 py-3 font-body-md text-body-md"><?php echo $d['cantidad']; ?></td>
                                                <td class="px-4 py-3 font-body-md text-body-md">$<?php echo number_format($d['precio_unitario'], 2); ?></td>
                                                <td class="px-4 py-3 font-body-md text-body-md text-right">$<?php echo number_format($d['cantidad'] * $d['precio_unitario'], 2); ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="4" class="px-4 py-3 font-label-md text-label-md text-right">TOTAL</td>
                                                <td class="px-4 py-3 font-headline-md text-headline-md text-primary text-right">$<?php echo number_format($v['total'], 2); ?></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once dirname(__DIR__, 2) . '/footer.php'; ?>
