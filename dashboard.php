<?php require_once 'header.php'; ?>
<?php require_once 'config/db.php'; ?>

<?php
$total_medicamentos = $pdo->query("SELECT COUNT(*) FROM medicamentos")->fetchColumn();
$total_clientes = $pdo->query("SELECT COUNT(*) FROM clientes")->fetchColumn();
$total_ventas_hoy = $pdo->query("SELECT COUNT(*) FROM ventas WHERE DATE(fecha) = CURDATE()")->fetchColumn();
$ingresos_hoy = $pdo->query("SELECT COALESCE(SUM(total), 0) FROM ventas WHERE DATE(fecha) = CURDATE()")->fetchColumn();

$stock_critico = $pdo->query("
    SELECT COUNT(*)
    FROM medicamentos m
    WHERE m.stock_minimo > (
        SELECT COALESCE(SUM(l.cantidad_actual), 0)
        FROM lotes l
        WHERE l.id_medicamento = m.id_medicamento
        AND l.fecha_vencimiento >= CURDATE()
    )
")->fetchColumn();

$por_vencer = $pdo->query("
    SELECT COUNT(*) FROM lotes
    WHERE fecha_vencimiento BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY)
    AND cantidad_actual > 0
")->fetchColumn();
?>

<section class="space-y-2 mb-8">
    <h1 class="font-headline-xl text-headline-xl text-on-surface">&iexcl;Buen d&iacute;a!</h1>
    <p class="font-body-lg text-body-lg text-on-surface-variant">Aqu&iacute; tienes el resumen de hoy en FarmaVida.</p>
</section>

<section class="grid grid-cols-1 md:grid-cols-3 gap-gutter mb-8">
    <div class="floating-card bg-surface-container-lowest p-md rounded-[24px] shadow-[0_4px_15px_rgba(0,0,0,0.06)] flex items-center gap-md">
        <div class="w-14 h-14 rounded-full bg-primary-container flex items-center justify-center text-on-primary-container shadow-sm">
            <span class="material-symbols-outlined text-3xl" style="font-variation-settings:'FILL'1">payments</span>
        </div>
        <div>
            <p class="font-label-sm text-label-sm text-on-surface-variant">Ventas Hoy</p>
            <p class="font-headline-md text-headline-md text-on-surface">$<?php echo number_format($ingresos_hoy, 0); ?></p>
            <span class="text-primary font-label-md text-xs"><?php echo $total_ventas_hoy; ?> transacciones</span>
        </div>
    </div>

    <div class="floating-card bg-surface-container-lowest p-md rounded-[24px] shadow-[0_4px_15px_rgba(0,0,0,0.06)] flex items-center gap-md">
        <div class="w-14 h-14 rounded-full bg-secondary-container flex items-center justify-center text-on-secondary-container shadow-sm">
            <span class="material-symbols-outlined text-3xl" style="font-variation-settings:'FILL'1">inventory_2</span>
        </div>
        <div>
            <p class="font-label-sm text-label-sm text-on-surface-variant">Medicamentos</p>
            <p class="font-headline-md text-headline-md text-on-surface"><?php echo $total_medicamentos; ?></p>
            <span class="text-on-surface-variant font-label-md text-xs"><?php echo $total_clientes; ?> clientes</span>
        </div>
    </div>

    <div class="floating-card bg-surface-container-lowest p-md rounded-[24px] shadow-[0_4px_15px_rgba(0,0,0,0.06)] flex items-center gap-md">
        <div class="w-14 h-14 rounded-full bg-tertiary-container flex items-center justify-center text-on-tertiary-container shadow-sm">
            <span class="material-symbols-outlined text-3xl" style="font-variation-settings:'FILL'1">calendar_month</span>
        </div>
        <div>
            <p class="font-label-sm text-label-sm text-on-surface-variant">Por Vencer</p>
            <p class="font-headline-md text-headline-md text-on-surface"><?php echo $por_vencer; ?> Lotes</p>
            <span class="text-error font-label-md text-xs flex items-center gap-1">
                <span class="material-symbols-outlined text-sm">warning</span> Pr&oacute;ximos 30 d&iacute;as
            </span>
        </div>
    </div>
</section>

<section class="grid grid-cols-1 lg:grid-cols-12 gap-gutter items-start mb-8">
    <div class="lg:col-span-8 bg-surface-container-lowest p-lg rounded-[24px] shadow-[0_4px_15px_rgba(0,0,0,0.06)]">
        <div class="flex items-center gap-3 mb-6">
            <span class="material-symbols-outlined text-primary" style="font-variation-settings:'FILL'1">monitoring</span>
            <h3 class="font-headline-md text-headline-md text-on-surface">Alertas del Sistema</h3>
        </div>
        <div class="space-y-3">
            <?php if($stock_critico > 0): ?>
            <div class="flex items-start gap-3 p-4 bg-tertiary-fixed rounded-[20px]">
                <span class="material-symbols-outlined text-tertiary" style="font-variation-settings:'FILL'1">inventory</span>
                <div>
                    <p class="font-label-md text-label-md text-on-surface">Stock Cr&iacute;tico</p>
                    <p class="font-body-md text-sm text-on-surface-variant"><strong><?php echo $stock_critico; ?></strong> medicamento(s) con stock por debajo del m&iacute;nimo.</p>
                </div>
            </div>
            <?php endif; ?>
            <?php if($por_vencer > 0): ?>
            <div class="flex items-start gap-3 p-4 bg-secondary-fixed rounded-[20px]">
                <span class="material-symbols-outlined text-secondary" style="font-variation-settings:'FILL'1">history</span>
                <div>
                    <p class="font-label-md text-label-md text-on-surface">Lotes por Vencer</p>
                    <p class="font-body-md text-sm text-on-surface-variant"><strong><?php echo $por_vencer; ?></strong> lote(s) pr&oacute;ximos a vencer en los pr&oacute;ximos 30 d&iacute;as.</p>
                </div>
            </div>
            <?php endif; ?>
            <?php if($stock_critico == 0 && $por_vencer == 0): ?>
            <div class="flex items-start gap-3 p-4 bg-primary-fixed rounded-[20px]">
                <span class="material-symbols-outlined text-primary" style="font-variation-settings:'FILL'1">check_circle</span>
                <div>
                    <p class="font-label-md text-label-md text-on-surface">Todo en orden</p>
                    <p class="font-body-md text-sm text-on-surface-variant">No hay alertas pendientes. &iexcl;Sistema funcionando correctamente!</p>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="lg:col-span-4 space-y-4">
        <div class="flex items-center justify-between px-2">
            <h3 class="font-headline-md text-headline-md text-on-surface">Acceso R&aacute;pido</h3>
        </div>
        <div class="grid grid-cols-2 gap-3">
            <a href="views/ventas/create.php" class="floating-card bg-surface-container-low p-4 rounded-2xl flex flex-col items-center gap-2 hover:bg-primary-fixed group text-center no-underline">
                <span class="material-symbols-outlined text-primary group-hover:scale-110 transition-transform" style="font-variation-settings:'FILL'1">add_circle</span>
                <span class="font-label-sm text-label-sm text-on-surface-variant">Nueva Venta</span>
            </a>
            <a href="views/medicamentos/create.php" class="floating-card bg-surface-container-low p-4 rounded-2xl flex flex-col items-center gap-2 hover:bg-primary-fixed group text-center no-underline">
                <span class="material-symbols-outlined text-primary group-hover:scale-110 transition-transform" style="font-variation-settings:'FILL'1">vaccines</span>
                <span class="font-label-sm text-label-sm text-on-surface-variant">Medicamento</span>
            </a>
            <a href="views/lotes/create.php" class="floating-card bg-surface-container-low p-4 rounded-2xl flex flex-col items-center gap-2 hover:bg-primary-fixed group text-center no-underline">
                <span class="material-symbols-outlined text-primary group-hover:scale-110 transition-transform" style="font-variation-settings:'FILL'1">inventory</span>
                <span class="font-label-sm text-label-sm text-on-surface-variant">Nuevo Lote</span>
            </a>
            <a href="views/clientes/create.php" class="floating-card bg-surface-container-low p-4 rounded-2xl flex flex-col items-center gap-2 hover:bg-primary-fixed group text-center no-underline">
                <span class="material-symbols-outlined text-primary group-hover:scale-110 transition-transform" style="font-variation-settings:'FILL'1">group</span>
                <span class="font-label-sm text-label-sm text-on-surface-variant">Cliente</span>
            </a>
        </div>
    </div>
</section>

<?php require_once 'footer.php'; ?>
