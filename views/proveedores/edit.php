<?php
$page_main_class = 'flex-grow pt-28 pb-xl px-margin-mobile md:px-margin-desktop flex items-center justify-center';
require_once dirname(__DIR__, 2) . '/header.php';
require_once dirname(__DIR__, 2) . '/controllers/ProveedorController.php';

$controller = new ProveedorController();
$id = $_GET['id'];
$proveedor = $controller->getById($id);

if(!$proveedor){
    header('Location: index.php');
    exit;
}

$error = '';
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if (!isset($_POST['csrf_token']) || !validate_csrf($_POST['csrf_token'])) {
        $error = 'Token de seguridad inv&aacute;lido';
    } elseif(!preg_match("/^[A-Za-záéíóúñÑ ]+$/", $_POST['nombre'])){
        $error = 'El nombre solo debe contener letras y espacios';
    } elseif(!empty($_POST['telefono']) && !preg_match("/^[0-9]{7,15}$/", $_POST['telefono'])){
        $error = 'El tel&eacute;fono debe tener entre 7 y 15 d&iacute;gitos num&eacute;ricos';
    } elseif(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
        $error = 'El email no es v&aacute;lido';
    } else {
        $data = [
            'nombre' => $_POST['nombre'],
            'contacto' => $_POST['contacto'],
            'telefono' => $_POST['telefono'],
            'email' => $_POST['email']
        ];

        if($controller->update($id, $data)){
            $_SESSION['mensaje'] = "Proveedor actualizado";
            header('Location: index.php');
            exit;
        }
    }
}
?>

<div class="w-full max-w-2xl bg-surface-container-lowest rounded-[32px] p-8 md:p-12 shadow-[0_4px_30px_rgba(0,0,0,0.04)]">
    <div class="flex flex-col items-center mb-8">
        <div class="w-16 h-16 bg-secondary-container/30 rounded-full flex items-center justify-center mb-4 text-secondary">
            <span class="material-symbols-outlined text-[32px]" style="font-variation-settings:'FILL'1">edit_note</span>
        </div>
        <h1 class="font-headline-lg text-headline-lg text-on-surface text-center">Editar Proveedor</h1>
        <p class="font-body-md text-body-md text-on-surface-variant mt-2 text-center">Modifique los datos del proveedor.</p>
    </div>

    <?php if($error): ?>
        <div class="flex items-center gap-2 px-4 py-3 mb-4 bg-error-container text-on-error-container rounded-2xl font-body-md text-body-md">
            <span class="material-symbols-outlined">error</span> <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="space-y-6">
        <?php echo csrf_field(); ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2 floating-label-group">
                <input class="w-full h-14 px-4 pt-2 bg-surface-container-low rounded-xl border-none focus:ring-2 focus:ring-primary-container focus:bg-white transition-all duration-300 font-body-md text-body-md outline-none" id="nombre" name="nombre" placeholder=" " type="text" value="<?php echo htmlspecialchars($proveedor['nombre']); ?>" required/>
                <label class="font-body-md text-body-md text-outline" for="nombre">Nombre del proveedor</label>
            </div>
            <div class="floating-label-group">
                <input class="w-full h-14 px-4 pt-2 bg-surface-container-low rounded-xl border-none focus:ring-2 focus:ring-primary-container focus:bg-white transition-all duration-300 font-body-md text-body-md outline-none" id="contacto" name="contacto" placeholder=" " type="text" value="<?php echo htmlspecialchars($proveedor['contacto']); ?>"/>
                <label class="font-body-md text-body-md text-outline" for="contacto">Persona de contacto</label>
            </div>
            <div class="floating-label-group">
                <input class="w-full h-14 px-4 pt-2 bg-surface-container-low rounded-xl border-none focus:ring-2 focus:ring-primary-container focus:bg-white transition-all duration-300 font-body-md text-body-md outline-none" id="telefono" name="telefono" placeholder=" " type="text" value="<?php echo htmlspecialchars($proveedor['telefono']); ?>"/>
                <label class="font-body-md text-body-md text-outline" for="telefono">Tel&eacute;fono</label>
            </div>
            <div class="md:col-span-2 floating-label-group">
                <input class="w-full h-14 px-4 pt-2 bg-surface-container-low rounded-xl border-none focus:ring-2 focus:ring-primary-container focus:bg-white transition-all duration-300 font-body-md text-body-md outline-none" id="email" name="email" placeholder=" " type="email" value="<?php echo htmlspecialchars($proveedor['email']); ?>" required/>
                <label class="font-body-md text-body-md text-outline" for="email">Email</label>
            </div>
        </div>
        <div class="flex flex-col-reverse md:flex-row items-center justify-center gap-4 mt-8 pt-4">
            <a href="index.php" class="w-full md:w-auto px-10 py-4 font-label-md text-label-md uppercase tracking-wider text-tertiary border-2 border-tertiary-container rounded-2xl hover:bg-tertiary-container/10 transition-all squishy-btn text-center no-underline">Cancelar</a>
            <button class="w-full md:w-auto px-12 py-4 font-label-md text-label-md uppercase tracking-wider text-white bg-primary rounded-2xl shadow-lg shadow-primary/20 hover:shadow-xl hover:shadow-primary/30 hover:-translate-y-0.5 transition-all squishy-btn" type="submit">Actualizar</button>
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
