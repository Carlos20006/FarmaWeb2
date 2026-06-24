<?php
require_once dirname(__DIR__, 2) . '/header.php';
require_once dirname(__DIR__, 2) . '/controllers/ProveedorController.php';

$controller = new ProveedorController();
$proveedores = $controller->index();
?>

<section class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
    <div>
        <h1 class="font-headline-xl text-headline-xl text-primary mb-2">Proveedores</h1>
        <p class="font-body-md text-body-md text-on-surface-variant max-w-xl">Administre sus proveedores y datos de contacto.</p>
    </div>
    <a href="create.php" class="squishy-btn flex items-center gap-2 bg-primary text-white px-8 py-3 rounded-full font-label-md text-label-md shadow-lg hover:bg-primary-container hover:text-on-primary-container transition-all duration-300 no-underline">
        <span class="material-symbols-outlined">add</span>
        Nuevo Proveedor
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
                <th class="px-8 py-5 font-label-md text-label-md text-primary uppercase tracking-wider">Nombre</th>
                <th class="px-8 py-5 font-label-md text-label-md text-primary uppercase tracking-wider">Contacto</th>
                <th class="px-8 py-5 font-label-md text-label-md text-primary uppercase tracking-wider">Tel&eacute;fono</th>
                <th class="px-8 py-5 font-label-md text-label-md text-primary uppercase tracking-wider">Email</th>
                <th class="px-8 py-5 font-label-md text-label-md text-primary uppercase tracking-wider text-right">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-surface-container-low">
            <?php foreach($proveedores as $p): ?>
            <tr class="row-hover transition-all duration-300 group">
                <td class="px-8 py-5">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary-container" style="font-variation-settings:'FILL'1">local_shipping</span>
                        <span class="font-body-md text-body-md text-on-surface"><?php echo htmlspecialchars($p['nombre']); ?></span>
                    </div>
                </td>
                <td class="px-8 py-5 font-body-md text-body-md text-on-surface"><?php echo htmlspecialchars($p['contacto']); ?></td>
                <td class="px-8 py-5 font-body-md text-body-md text-on-surface"><?php echo htmlspecialchars($p['telefono']); ?></td>
                <td class="px-8 py-5 font-body-md text-body-md text-on-surface"><?php echo htmlspecialchars($p['email']); ?></td>
                <td class="px-8 py-5 text-right">
                    <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <a href="edit.php?id=<?php echo $p['id_proveedor']; ?>" class="p-2 rounded-full bg-secondary-fixed hover:bg-secondary transition-colors text-on-secondary-fixed squishy-btn no-underline inline-flex">
                            <span class="material-symbols-outlined text-[20px]">edit</span>
                        </a>
                        <a href="delete.php?id=<?php echo $p['id_proveedor']; ?>&csrf_token=<?php echo $_SESSION['csrf_token']; ?>" class="p-2 rounded-full bg-tertiary-container hover:bg-tertiary transition-colors text-on-tertiary-container squishy-btn no-underline inline-flex" onclick="return confirm('&iquest;Eliminar este proveedor?')">
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
