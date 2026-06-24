<?php
$page_main_class = 'flex-grow pt-28 pb-xl px-margin-mobile md:px-margin-desktop flex items-center justify-center';
require_once dirname(__DIR__, 2) . '/header.php';
require_once dirname(__DIR__, 2) . '/config/db.php';
require_once dirname(__DIR__, 2) . '/controllers/LoteController.php';

$medicamentos = $pdo->query("SELECT * FROM medicamentos")->fetchAll();
$controller = new LoteController();
$error = '';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if (!isset($_POST['csrf_token']) || !validate_csrf($_POST['csrf_token'])) {
        $error = 'Token de seguridad inv&aacute;lido';
    } elseif($_POST['cantidad'] <= 0){
        $error = 'La cantidad debe ser mayor a 0';
    } elseif(!preg_match("/^[A-Za-z0-9\-]+$/", $_POST['numero_lote'])){
        $error = 'El n&uacute;mero de lote solo puede tener letras, n&uacute;meros y guiones';
    } elseif(strtotime($_POST['fecha_vencimiento']) < strtotime(date('Y-m-d'))){
        $error = 'La fecha de vencimiento no puede ser anterior a hoy';
    } else {
        $data = [
            'numero_lote' => $_POST['numero_lote'],
            'id_medicamento' => $_POST['id_medicamento'],
            'cantidad' => $_POST['cantidad'],
            'fecha_vencimiento' => $_POST['fecha_vencimiento']
        ];

        if($controller->create($data)){
            $_SESSION['mensaje'] = "Lote creado exitosamente";
            header('Location: index.php');
            exit;
        } else {
            $error = 'Error al crear lote';
        }
    }
}
?>

<div class="w-full max-w-2xl bg-surface-container-lowest rounded-[32px] p-8 md:p-12 shadow-[0_4px_30px_rgba(0,0,0,0.04)]">
    <div class="flex flex-col items-center mb-8">
        <div class="w-16 h-16 bg-primary-container/30 rounded-full flex items-center justify-center mb-4 text-primary">
            <span class="material-symbols-outlined text-[32px]" style="font-variation-settings:'FILL'1">inventory</span>
        </div>
        <h1 class="font-headline-lg text-headline-lg text-on-surface text-center">Nuevo Lote</h1>
        <p class="font-body-md text-body-md text-on-surface-variant mt-2 text-center">Registre un nuevo lote de medicamento.</p>
    </div>

    <?php if($error): ?>
        <div class="flex items-center gap-2 px-4 py-3 mb-4 bg-error-container text-on-error-container rounded-2xl font-body-md text-body-md">
            <span class="material-symbols-outlined">error</span> <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="space-y-6">
        <?php echo csrf_field(); ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="floating-label-group">
                <input class="w-full h-14 px-4 pt-2 bg-surface-container-low rounded-xl border-none focus:ring-2 focus:ring-primary-container focus:bg-white transition-all duration-300 font-body-md text-body-md outline-none" id="numero_lote" name="numero_lote" placeholder=" " type="text" required/>
                <label class="font-body-md text-body-md text-outline" for="numero_lote">N&uacute;mero de Lote</label>
            </div>
            <div class="flex flex-col">
                <label class="ml-2 mb-2 font-label-md text-label-md text-outline" for="id_medicamento">Medicamento</label>
                <div class="relative">
                    <select class="w-full h-14 pl-4 pr-10 bg-surface-container-low rounded-xl border-none focus:ring-2 focus:ring-primary-container focus:bg-white appearance-none transition-all duration-300 font-body-md text-body-md outline-none" id="id_medicamento" name="id_medicamento" required>
                        <option value="">Seleccione</option>
                        <?php foreach($medicamentos as $m): ?>
                        <option value="<?php echo $m['id_medicamento']; ?>"><?php echo htmlspecialchars($m['nombre_generico']); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <span class="material-symbols-outlined absolute right-4 top-4 text-outline pointer-events-none">expand_more</span>
                </div>
            </div>
            <div class="floating-label-group">
                <input class="w-full h-14 px-4 pt-2 bg-surface-container-low rounded-xl border-none focus:ring-2 focus:ring-primary-container focus:bg-white transition-all duration-300 font-body-md text-body-md outline-none" id="cantidad" name="cantidad" placeholder=" " type="number" min="1" required/>
                <label class="font-body-md text-body-md text-outline" for="cantidad">Cantidad</label>
            </div>
            <div class="floating-label-group">
                <input class="w-full h-14 px-4 pt-2 bg-surface-container-low rounded-xl border-none focus:ring-2 focus:ring-primary-container focus:bg-white transition-all duration-300 font-body-md text-body-md outline-none" id="fecha_vencimiento" name="fecha_vencimiento" placeholder=" " type="date" required/>
                <label class="font-body-md text-body-md text-outline" for="fecha_vencimiento">Fecha Vencimiento</label>
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
