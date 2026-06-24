<?php
$page_main_class = 'flex-grow pt-28 pb-xl px-margin-mobile md:px-margin-desktop flex items-center justify-center';
require_once dirname(__DIR__, 2) . '/header.php';
require_once dirname(__DIR__, 2) . '/controllers/MedicamentoController.php';

$controller = new MedicamentoController();
$mensaje = '';

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    if (!isset($_POST['csrf_token']) || !validate_csrf($_POST['csrf_token'])) {
        $mensaje = '<div class="flex items-center gap-2 px-4 py-3 bg-error-container text-on-error-container rounded-2xl font-body-md text-body-md"><span class="material-symbols-outlined">error</span> Token de seguridad inv&aacute;lido</div>';
    } elseif($_POST['precio'] <= 0){
        $mensaje = '<div class="flex items-center gap-2 px-4 py-3 bg-error-container text-on-error-container rounded-2xl font-body-md text-body-md"><span class="material-symbols-outlined">error</span> El precio debe ser mayor a 0</div>';
    } elseif($_POST['stock_minimo'] < 0){
        $mensaje = '<div class="flex items-center gap-2 px-4 py-3 bg-error-container text-on-error-container rounded-2xl font-body-md text-body-md"><span class="material-symbols-outlined">error</span> El stock m&iacute;nimo no puede ser negativo</div>';
    } elseif(!preg_match("/^[A-Za-záéíóúñÑ ]+$/", $_POST['nombre'])){
        $mensaje = '<div class="flex items-center gap-2 px-4 py-3 bg-error-container text-on-error-container rounded-2xl font-body-md text-body-md"><span class="material-symbols-outlined">error</span> El nombre solo debe contener letras y espacios</div>';
    } elseif(empty($_POST['presentacion'])){
        $mensaje = '<div class="flex items-center gap-2 px-4 py-3 bg-error-container text-on-error-container rounded-2xl font-body-md text-body-md"><span class="material-symbols-outlined">error</span> Debe seleccionar una presentaci&oacute;n</div>';
    } else {
        $data = [
            'nombre' => $_POST['nombre'],
            'presentacion' => $_POST['presentacion'],
            'categoria' => $_POST['categoria'],
            'precio' => $_POST['precio'],
            'stock_minimo' => $_POST['stock_minimo']
        ];

        if($controller->create($data)){
            $_SESSION['mensaje'] = "Medicamento creado exitosamente";
            header('Location: index.php');
            exit;
        } else {
            $mensaje = '<div class="flex items-center gap-2 px-4 py-3 bg-error-container text-on-error-container rounded-2xl font-body-md text-body-md"><span class="material-symbols-outlined">error</span> Error al crear medicamento</div>';
        }
    }
}
?>

<div class="w-full max-w-2xl bg-surface-container-lowest rounded-[32px] p-8 md:p-12 shadow-[0_4px_30px_rgba(0,0,0,0.04)]">
    <div class="flex flex-col items-center mb-8">
        <div class="w-16 h-16 bg-primary-container/30 rounded-full flex items-center justify-center mb-4 text-primary">
            <span class="material-symbols-outlined text-[32px]" style="font-variation-settings:'FILL'1">spa</span>
        </div>
        <h1 class="font-headline-lg text-headline-lg text-on-surface text-center">Registrar Medicamento</h1>
        <p class="font-body-md text-body-md text-on-surface-variant mt-2 text-center">Complete la informaci&oacute;n para actualizar el inventario.</p>
    </div>

    <?php if($mensaje): ?>
        <?php echo $mensaje; ?>
    <?php endif; ?>

    <form method="POST" class="space-y-6">
        <?php echo csrf_field(); ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2 floating-label-group">
                <input class="w-full h-14 px-4 pt-2 bg-surface-container-low rounded-xl border-none focus:ring-2 focus:ring-primary-container focus:bg-white transition-all duration-300 font-body-md text-body-md outline-none" id="nombre" name="nombre" placeholder=" " type="text" pattern="[A-Za-záéíóúñÑ ]+" required/>
                <label class="font-body-md text-body-md text-outline" for="nombre">Nombre del Medicamento</label>
            </div>
            <div class="flex flex-col">
                <label class="ml-2 mb-2 font-label-md text-label-md text-outline" for="presentacion">Presentaci&oacute;n</label>
                <div class="relative">
                    <select class="w-full h-14 pl-4 pr-10 bg-surface-container-low rounded-xl border-none focus:ring-2 focus:ring-primary-container focus:bg-white appearance-none transition-all duration-300 font-body-md text-body-md outline-none" id="presentacion" name="presentacion" required>
                        <option disabled selected value="">Seleccione...</option>
                        <option value="Tableta 500mg">Tableta 500mg</option>
                        <option value="Tableta 750mg">Tableta 750mg</option>
                        <option value="Tableta 1g">Tableta 1g</option>
                        <option value="Cápsula 250mg">C&aacute;psula 250mg</option>
                        <option value="Cápsula 500mg">C&aacute;psula 500mg</option>
                        <option value="Jarabe 120mg/5mL">Jarabe 120mg/5mL</option>
                        <option value="Suspensión 250mg/5mL">Suspensi&oacute;n 250mg/5mL</option>
                        <option value="Crema 30g">Crema 30g</option>
                        <option value="Gotas 20mL">Gotas 20mL</option>
                        <option value="Inyectable 2mL">Inyectable 2mL</option>
                        <option value="Jarabe 250mg/5mL">Jarabe 250mg/5mL</option>
                        <option value="Suspensión 500mg/5mL">Suspensi&oacute;n 500mg/5mL</option>
                        <option value="Crema 50g">Crema 50g</option>
                        <option value="Gotas 10mL">Gotas 10mL</option>
                        <option value="Inyectable 5mL">Inyectable 5mL</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-4 top-4 text-outline pointer-events-none">expand_more</span>
                </div>
            </div>
            <div class="flex flex-col">
                <label class="ml-2 mb-2 font-label-md text-label-md text-outline" for="categoria">Categor&iacute;a</label>
                <div class="relative">
                    <select class="w-full h-14 pl-4 pr-10 bg-surface-container-low rounded-xl border-none focus:ring-2 focus:ring-primary-container focus:bg-white appearance-none transition-all duration-300 font-body-md text-body-md outline-none" id="categoria" name="categoria">
                        <option value="">Seleccione...</option>
                        <option>Analg&eacute;sico</option>
                        <option>Antiinflamatorio</option>
                        <option>Antibi&oacute;tico</option>
                        <option>Antihistam&iacute;nico</option>
                        <option>Antihipertensivo</option>
                        <option>Antidiab&eacute;tico</option>
                        <option>Anti&aacute;cido</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-4 top-4 text-outline pointer-events-none">expand_more</span>
                </div>
            </div>
            <div class="floating-label-group">
                <input class="w-full h-14 px-4 pt-2 bg-surface-container-low rounded-xl border-none focus:ring-2 focus:ring-primary-container focus:bg-white transition-all duration-300 font-body-md text-body-md outline-none" id="precio" name="precio" placeholder=" " type="number" step="0.01" min="0" required/>
                <label class="font-body-md text-body-md text-outline" for="precio">Precio Unitario ($)</label>
            </div>
            <div class="floating-label-group">
                <input class="w-full h-14 px-4 pt-2 bg-surface-container-low rounded-xl border-none focus:ring-2 focus:ring-primary-container focus:bg-white transition-all duration-300 font-body-md text-body-md outline-none" id="stock_minimo" name="stock_minimo" placeholder=" " type="number" min="0" value="10"/>
                <label class="font-body-md text-body-md text-outline" for="stock_minimo">Stock M&iacute;nimo</label>
            </div>
        </div>
        <div class="flex flex-col-reverse md:flex-row items-center justify-center gap-4 mt-8 pt-4">
            <a href="index.php" class="w-full md:w-auto px-10 py-4 font-label-md text-label-md uppercase tracking-wider text-tertiary border-2 border-tertiary-container rounded-2xl hover:bg-tertiary-container/10 transition-all squishy-btn text-center no-underline">Cancelar</a>
            <button class="w-full md:w-auto px-12 py-4 font-label-md text-label-md uppercase tracking-wider text-white bg-primary rounded-2xl shadow-lg shadow-primary/20 hover:shadow-xl hover:shadow-primary/30 hover:-translate-y-0.5 transition-all squishy-btn" type="submit">Guardar</button>
        </div>
    </form>
</div>

<style>
.floating-label-group { position: relative; }
.floating-label-group input:focus ~ label,
.floating-label-group input:not(:placeholder-shown) ~ label {
    top: -10px; left: 12px; font-size: 12px; color: #28695c;
    background-color: white; padding: 0 4px;
}
.floating-label-group label {
    position: absolute; top: 16px; left: 16px;
    transition: all 0.2s ease-out; pointer-events: none;
}
</style>

<?php require_once dirname(__DIR__, 2) . '/footer.php'; ?>
