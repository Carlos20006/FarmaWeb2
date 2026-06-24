<?php
session_start();
require_once 'config/db.php';

if(isset($_SESSION['user_id'])){
    header('Location: dashboard.php');
    exit;
}

$error = '';
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE nombre_usuario = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if($user && password_verify($password, $user['password_hash'])){
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id_usuario'];
        $_SESSION['user_name'] = $user['nombre_usuario'];
        $_SESSION['rol'] = $user['rol'];
        header('Location: dashboard.php');
        exit;
    } else {
        $error = "Usuario o contrase&ntilde;a incorrectos";
    }
}
?>
<!DOCTYPE html>
<html class="light" lang="es">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>FarmaVida - Iniciar Sesi&oacute;n</title>
<link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,0..1&display=swap" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script>
tailwind.config = {
  darkMode: "class",
  theme: {
    extend: {
      colors: { primary: "#28695c", "on-primary": "#ffffff", "primary-container": "#98d8c8", "on-primary-container": "#1d6053", "primary-fixed": "#afefdf", "primary-fixed-dim": "#93d3c3", secondary: "#416373", "secondary-container": "#c4e8fb", "on-secondary-container": "#476979", tertiary: "#874f4c", "tertiary-container": "#ffbbb6", error: "#ba1a1a", "error-container": "#ffdad6", "on-error-container": "#93000a", background: "#fbf9f8", "on-background": "#1b1c1c", surface: "#fbf9f8", "surface-container-lowest": "#ffffff", "surface-container-low": "#f5f3f3", "surface-container": "#efeded", "surface-container-high": "#eae8e7", "surface-variant": "#e4e2e2", "on-surface": "#1b1c1c", "on-surface-variant": "#3f4946", outline: "#6f7976", "outline-variant": "#bfc9c5" },
      borderRadius: { DEFAULT: "0.25rem", "2xl": "1rem", "3xl": "1.5rem", full: "9999px" },
      fontFamily: { sans: ["Quicksand"] }
    }
  }
}
</script>
<style>
body { font-family: 'Quicksand', sans-serif; background: linear-gradient(135deg, #fbf9f8 0%, #e8f3f0 50%, #d4e8e3 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; overflow-x: hidden; -webkit-font-smoothing: antialiased; }
.login-card { box-shadow: 0 4px 15px rgba(0,0,0,0.06); transition: transform 0.3s ease, box-shadow 0.3s ease; }
.login-card:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(0,0,0,0.08); }
.input-field:focus { box-shadow: 0 0 0 4px rgba(152, 216, 200, 0.4); background-color: #ffffff; border-color: #28695c; }
.btn-press:active { transform: scale(0.95); }
.material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; vertical-align: middle; }
.float-anim { animation: float 6s ease-in-out infinite; }
@keyframes float { 0%, 100% { transform: translateY(0) rotate(0deg); } 50% { transform: translateY(-10px) rotate(5deg); } }
.pulse { animation: pulse 3s ease-in-out infinite; }
@keyframes pulse { 0%, 100% { opacity: 0.15; } 50% { opacity: 0.25; } }
</style>
</head>
<body>
<div class="absolute top-10 left-10 opacity-20 pointer-events-none float-anim">
<span class="material-symbols-outlined text-[120px] text-primary" style="font-variation-settings:'FILL'1">medication</span>
</div>
<div class="absolute bottom-10 right-10 opacity-10 pointer-events-none float-anim" style="animation-delay: -2s;">
<span class="material-symbols-outlined text-[180px] text-primary-container" style="font-variation-settings:'FILL'1">spa</span>
</div>
<div class="absolute top-1/2 right-5 opacity-5 pointer-events-none pulse">
<span class="material-symbols-outlined text-[100px] text-primary" style="font-variation-settings:'FILL'1">local_pharmacy</span>
</div>

<main class="w-full max-w-[420px] px-margin-mobile md:px-0">
<div class="login-card bg-surface-container-lowest rounded-[24px] p-8 md:p-10 relative overflow-hidden">
<header class="text-center mb-8">
<div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-primary to-primary-container rounded-2xl mb-5 shadow-lg shadow-primary/20">
<span class="material-symbols-outlined text-on-primary text-3xl" style="font-variation-settings:'FILL'1">health_and_safety</span>
</div>
<h1 class="text-headline-md text-headline-md text-primary mb-1">FarmaVida</h1>
<p class="font-body-md text-body-md text-on-surface-variant">Cuidamos de tu salud</p>
</header>

<form method="POST" class="space-y-5">
<div class="space-y-2">
<label class="block font-label-md text-label-md text-on-surface-variant ml-1" for="username">Usuario</label>
<div class="relative">
<span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline">person</span>
<input class="input-field w-full h-14 pl-12 pr-4 bg-surface-container rounded-[16px] border border-transparent outline-none font-body-md text-body-md text-on-surface transition-all duration-300" id="username" name="username" placeholder="Ingrese su usuario" type="text" required autofocus/>
</div>
</div>

<div class="space-y-2">
<label class="block font-label-md text-label-md text-on-surface-variant ml-1" for="password">Contrase&ntilde;a</label>
<div class="relative">
<span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline">lock</span>
<input class="input-field w-full h-14 pl-12 pr-12 bg-surface-container rounded-[16px] border border-transparent outline-none font-body-md text-body-md text-on-surface transition-all duration-300" id="password" name="password" placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;" type="password" required/>
<button class="absolute right-4 top-1/2 -translate-y-1/2 text-outline hover:text-primary transition-colors" type="button" onclick="togglePassword()">
<span class="material-symbols-outlined" id="passIcon">visibility</span>
</button>
</div>
</div>

<?php if($error): ?>
<div class="flex items-center gap-2 px-4 py-3 bg-error-container text-on-error-container rounded-2xl font-body-md text-body-md">
<span class="material-symbols-outlined">error</span>
<span><?php echo $error; ?></span>
</div>
<?php endif; ?>

<button class="btn-press w-full h-14 bg-primary text-on-primary font-headline-md text-headline-md rounded-[16px] shadow-lg shadow-primary/20 hover:shadow-xl hover:shadow-primary/30 transition-all duration-300 flex items-center justify-center gap-2" type="submit">
<span>Entrar</span>
<span class="material-symbols-outlined">arrow_forward</span>
</button>
</form>

<footer class="mt-8 pt-6 border-t border-surface-variant text-center">
<p class="font-label-sm text-label-sm text-outline">&copy; 2024 FarmaVida Pharmacy Management.</p>
</footer>
</div>
</main>

<script>
function togglePassword() {
    const input = document.getElementById('password');
    const icon = document.getElementById('passIcon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.textContent = 'visibility_off';
    } else {
        input.type = 'password';
        icon.textContent = 'visibility';
    }
}

document.querySelector('button[type="submit"]')?.addEventListener('click', function() {
    const original = this.innerHTML;
    this.innerHTML = '<svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> <span class="ml-2">Ingresando...</span>';
    setTimeout(() => { this.innerHTML = original; }, 3000);
});
</script>
</body>
</html>
