<?php
$page_main_class = 'flex-grow pt-28 pb-xl px-margin-mobile md:px-margin-desktop flex items-center justify-center';
require_once dirname(__DIR__, 2) . '/header.php';
require_once dirname(__DIR__, 2) . '/config/db.php';
require_once dirname(__DIR__, 2) . '/controllers/VentaController.php';

$clientes = $pdo->query("SELECT * FROM clientes ORDER BY nombre")->fetchAll();
$medicamentos = $pdo->query("SELECT * FROM medicamentos ORDER BY nombre_generico")->fetchAll();
$controller = new VentaController();

$mensaje = '';
if($_SERVER['REQUEST_METHOD'] == 'POST'){

    if (!isset($_POST['csrf_token']) || !validate_csrf($_POST['csrf_token'])) {
        $mensaje = '<div class="flex items-center gap-2 px-4 py-3 bg-error-container text-on-error-container rounded-2xl font-body-md text-body-md"><span class="material-symbols-outlined">error</span> Token de seguridad inv&aacute;lido</div>';
    } else {
        $id_cliente = $_POST['id_cliente'];
        $id_usuario = $_SESSION['user_id'];
        $productos = json_decode($_POST['productos'], true);

        $error_producto = false;
        foreach($productos as $item){
            if($item['cantidad'] <= 0){
                $error_producto = true;
                break;
            }
        }

        if(empty($productos)){
            $mensaje = '<div class="flex items-center gap-2 px-4 py-3 bg-error-container text-on-error-container rounded-2xl font-body-md text-body-md"><span class="material-symbols-outlined">error</span> Debe agregar al menos un producto</div>';
        } elseif($error_producto){
            $mensaje = '<div class="flex items-center gap-2 px-4 py-3 bg-error-container text-on-error-container rounded-2xl font-body-md text-body-md"><span class="material-symbols-outlined">error</span> Las cantidades deben ser mayores a 0</div>';
        } else {
            $resultado = $controller->create($id_cliente, $id_usuario, $productos);

            if($resultado['success']){
                $_SESSION['mensaje'] = "Venta registrada exitosamente. Total: $" . number_format($resultado['total'], 2);
                header('Location: index.php');
                exit;
            } else {
                $mensaje = '<div class="flex items-center gap-2 px-4 py-3 bg-error-container text-on-error-container rounded-2xl font-body-md text-body-md"><span class="material-symbols-outlined">error</span> ' . $resultado['error'] . '</div>';
            }
        }
    }
}
?>

<div class="w-full max-w-3xl bg-surface-container-lowest rounded-[32px] p-8 md:p-12 shadow-[0_4px_30px_rgba(0,0,0,0.04)]">
    <div class="flex flex-col items-center mb-8">
        <div class="w-16 h-16 bg-primary-container/30 rounded-full flex items-center justify-center mb-4 text-primary">
            <span class="material-symbols-outlined text-[32px]" style="font-variation-settings:'FILL'1">point_of_sale</span>
        </div>
        <h1 class="font-headline-lg text-headline-lg text-on-surface text-center">Nueva Venta</h1>
        <p class="font-body-md text-body-md text-on-surface-variant mt-2 text-center">Registre una nueva venta en el sistema.</p>
    </div>

    <?php echo $mensaje; ?>

    <form method="POST" onsubmit="return validarVenta()" class="space-y-6">
        <?php echo csrf_field(); ?>
        <div class="flex flex-col">
            <label class="ml-2 mb-2 font-label-md text-label-md text-outline" for="id_cliente">Cliente</label>
            <div class="relative">
                <select name="id_cliente" class="w-full h-14 pl-4 pr-10 bg-surface-container-low rounded-xl border-none focus:ring-2 focus:ring-primary-container focus:bg-white appearance-none transition-all duration-300 font-body-md text-body-md outline-none" required>
                    <option value="">Seleccione cliente</option>
                    <?php foreach($clientes as $c): ?>
                    <option value="<?php echo $c['id_cliente']; ?>"><?php echo htmlspecialchars($c['nombre']); ?> - <?php echo htmlspecialchars($c['documento']); ?></option>
                    <?php endforeach; ?>
                </select>
                <span class="material-symbols-outlined absolute right-4 top-4 text-outline pointer-events-none">expand_more</span>
            </div>
        </div>

        <div>
            <h5 class="font-headline-md text-headline-md text-on-surface mb-4">Productos</h5>
            <div id="productos-container" class="space-y-3">
                <div class="producto-item bg-surface-container-low rounded-2xl p-4">
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-3 items-end">
                        <div class="md:col-span-2 flex flex-col">
                            <label class="font-label-sm text-label-sm text-outline mb-1">Medicamento</label>
                            <select name="medicamento_temp[]" class="medicamento-select w-full h-12 pl-3 pr-8 bg-white rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary-container transition-all font-body-md text-body-md outline-none appearance-none" required>
                                <option value="">Seleccione</option>
                                <?php foreach($medicamentos as $m):
                                    $stmt = $pdo->prepare("SELECT SUM(cantidad_actual) as total FROM lotes WHERE id_medicamento = ? AND fecha_vencimiento >= CURDATE() AND cantidad_actual > 0");
                                    $stmt->execute([$m['id_medicamento']]);
                                    $stock = $stmt->fetch()['total'] ?? 0;
                                ?>
                                <option value="<?php echo $m['id_medicamento']; ?>" data-precio="<?php echo $m['precio']; ?>" data-stock="<?php echo $stock; ?>">
                                    <?php echo htmlspecialchars($m['nombre_generico']); ?> - $<?php echo number_format($m['precio'], 2); ?> (Stock: <?php echo $stock; ?>)
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="flex flex-col">
                            <label class="font-label-sm text-label-sm text-outline mb-1">Cantidad</label>
                            <input type="number" min="1" name="cantidad_temp[]" class="cantidad-input w-full h-12 px-3 bg-white rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary-container transition-all font-body-md text-body-md outline-none" placeholder="Cant." required/>
                        </div>
                        <div class="flex items-center h-12">
                            <span class="precio-item font-body-md text-body-md text-on-surface-variant"></span>
                        </div>
                        <div class="flex justify-end">
                            <button type="button" class="eliminar-producto p-2 rounded-full bg-tertiary-container hover:bg-tertiary transition-colors text-on-tertiary-container squishy-btn">
                                <span class="material-symbols-outlined text-[20px]">close</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <button type="button" id="agregar-producto" class="mt-3 px-6 py-3 font-label-md text-label-md text-primary bg-primary-fixed rounded-2xl hover:bg-primary-fixed-dim transition-all squishy-btn">
                <span class="material-symbols-outlined">add</span> Agregar producto
            </button>
        </div>

        <input type="hidden" name="productos" id="productos">

        <div class="flex flex-col-reverse md:flex-row items-center justify-center gap-4 pt-4">
            <a href="index.php" class="w-full md:w-auto px-10 py-4 font-label-md text-label-md uppercase tracking-wider text-tertiary border-2 border-tertiary-container rounded-2xl hover:bg-tertiary-container/10 transition-all squishy-btn text-center no-underline">Cancelar</a>
            <button class="w-full md:w-auto px-12 py-4 font-label-md text-label-md uppercase tracking-wider text-white bg-primary rounded-2xl shadow-lg shadow-primary/20 hover:shadow-xl hover:shadow-primary/30 hover:-translate-y-0.5 transition-all squishy-btn" type="submit">
                <span class="material-symbols-outlined">payments</span> Registrar Venta
            </button>
        </div>
    </form>
</div>

<script>
let items = [];

function actualizarItems() {
    let selects = document.querySelectorAll('.medicamento-select');
    let cantidades = document.querySelectorAll('.cantidad-input');
    items = [];

    for(let i = 0; i < selects.length; i++) {
        let select = selects[i];
        let cantidad = cantidades[i];
        if(select.value && cantidad.value > 0) {
            let option = select.options[select.selectedIndex];
            items.push({
                id_medicamento: select.value,
                nombre: option.text.split(' - ')[0],
                cantidad: parseInt(cantidad.value),
                precio: parseFloat(option.dataset.precio)
            });
        }
    }
    document.getElementById('productos').value = JSON.stringify(items);
}

function validarVenta() {
    actualizarItems();
    if(items.length === 0) {
        alert('Debe agregar al menos un producto');
        return false;
    }

    for(let i = 0; i < items.length; i++) {
        if(items[i].cantidad <= 0) {
            alert('Las cantidades deben ser mayores a 0');
            return false;
        }
    }

    let selects = document.querySelectorAll('.medicamento-select');
    let cantidades = document.querySelectorAll('.cantidad-input');

    for(let i = 0; i < selects.length; i++) {
        let select = selects[i];
        let cantidad = cantidades[i];
        if(select.value && cantidad.value > 0) {
            let option = select.options[select.selectedIndex];
            let stock = parseInt(option.dataset.stock);
            let cantidadReq = parseInt(cantidad.value);
            if(cantidadReq > stock) {
                alert('Stock insuficiente para ' + option.text.split(' - ')[0]);
                return false;
            }
        }
    }
    return confirm('&iquest;Confirmar esta venta?');
}

function actualizarPrecios() {
    document.querySelectorAll('.producto-item').forEach((item, i) => {
        let select = item.querySelector('.medicamento-select');
        let precioSpan = item.querySelector('.precio-item');
        if (select.value) {
            let precio = parseFloat(select.options[select.selectedIndex].dataset.precio);
            precioSpan.textContent = '$' + precio.toFixed(2);
        } else {
            precioSpan.textContent = '';
        }
    });
}

document.getElementById('agregar-producto').onclick = function() {
    let container = document.getElementById('productos-container');
    let original = container.querySelector('.producto-item');
    let nuevo = original.cloneNode(true);
    nuevo.querySelectorAll('select, input').forEach(el => el.value = '');
    nuevo.querySelector('.precio-item').innerHTML = '';
    container.appendChild(nuevo);
    actualizarPrecios();
};

document.addEventListener('click', function(e) {
    if(e.target.closest('.eliminar-producto')) {
        let items = document.querySelectorAll('.producto-item');
        if(items.length > 1) {
            e.target.closest('.producto-item').remove();
            actualizarPrecios();
        } else {
            alert('Debe haber al menos un producto');
        }
    }
});

document.addEventListener('change', function(e) {
    if(e.target.classList.contains('medicamento-select') || e.target.classList.contains('cantidad-input')) {
        actualizarItems();
        actualizarPrecios();
    }
});

actualizarPrecios();
</script>

<?php require_once dirname(__DIR__, 2) . '/footer.php'; ?>
