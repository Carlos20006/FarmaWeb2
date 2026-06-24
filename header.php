<?php
ob_start();
session_start();

// Detect base URL dynamically from file system path
$project_root = str_replace('\\', '/', dirname(__FILE__));
$doc_root = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
$base_url = substr($project_root, strlen($doc_root));

if(!isset($_SESSION['user_id'])){
    header('Location: ' . $base_url . '/login.php');
    exit;
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function csrf_field() {
    return '<input type="hidden" name="csrf_token" value="' . $_SESSION['csrf_token'] . '">';
}

function validate_csrf($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

$page_main_class = $page_main_class ?? 'max-w-[1200px] mx-auto px-margin-mobile md:px-margin-desktop mt-24 mb-20';
?>
<!DOCTYPE html>
<html class="light" lang="es">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>FarmaVida - Sistema de Gesti&oacute;n de Farmacia</title>
<link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,0..1&display=swap" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script>
tailwind.config = {
  darkMode: "class",
  theme: {
    extend: {
      colors: {
        primary: "#28695c", "on-primary": "#ffffff", "primary-container": "#98d8c8", "on-primary-container": "#1d6053", "primary-fixed": "#afefdf", "primary-fixed-dim": "#93d3c3",
        secondary: "#416373", "on-secondary": "#ffffff", "secondary-container": "#c4e8fb", "on-secondary-container": "#476979", "secondary-fixed": "#c4e8fb", "secondary-fixed-dim": "#a9ccde",
        tertiary: "#874f4c", "on-tertiary": "#ffffff", "tertiary-container": "#ffbbb6", "on-tertiary-container": "#7d4744", "tertiary-fixed": "#ffdad7", "tertiary-fixed-dim": "#fcb4b0",
        error: "#ba1a1a", "on-error": "#ffffff", "error-container": "#ffdad6", "on-error-container": "#93000a",
        background: "#fbf9f8", "on-background": "#1b1c1c",
        surface: "#fbf9f8", "surface-dim": "#dbd9d9", "surface-bright": "#fbf9f8", "surface-container-lowest": "#ffffff", "surface-container-low": "#f5f3f3", "surface-container": "#efeded", "surface-container-high": "#eae8e7", "surface-container-highest": "#e4e2e2", "surface-variant": "#e4e2e2",
        "on-surface": "#1b1c1c", "on-surface-variant": "#3f4946",
        "inverse-surface": "#303030", "inverse-on-surface": "#f2f0f0", "inverse-primary": "#93d3c3",
        outline: "#6f7976", "outline-variant": "#bfc9c5", "surface-tint": "#28695c"
      },
      borderRadius: {
        DEFAULT: "0.25rem", lg: "0.5rem", xl: "0.75rem", "2xl": "1rem", "3xl": "1.5rem", full: "9999px"
      },
      spacing: {
        base: "8px", xs: "4px", sm: "12px", md: "24px", lg: "48px", xl: "80px", gutter: "24px", "margin-mobile": "16px", "margin-desktop": "40px"
      },
      fontFamily: {
        "headline": ["Quicksand"], "body": ["Quicksand"], "label": ["Quicksand"]
      },
      fontSize: {
        "headline-xl": ["40px", { lineHeight: "48px", letterSpacing: "-0.02em", fontWeight: "700" }],
        "headline-lg": ["32px", { lineHeight: "40px", letterSpacing: "-0.01em", fontWeight: "600" }],
        "headline-md": ["24px", { lineHeight: "32px", fontWeight: "600" }],
        "body-lg": ["18px", { lineHeight: "28px", fontWeight: "500" }],
        "body-md": ["16px", { lineHeight: "24px", fontWeight: "500" }],
        "label-md": ["14px", { lineHeight: "20px", letterSpacing: "0.02em", fontWeight: "600" }],
        "label-sm": ["12px", { lineHeight: "16px", letterSpacing: "0.04em", fontWeight: "700" }]
      }
    }
  }
}
</script>
<style>
body { font-family: 'Quicksand', sans-serif; -webkit-font-smoothing: antialiased; }
.material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; vertical-align: middle; }
.squishy-btn:active { transform: scale(0.95); }
.floating-card { transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.3s ease; }
.floating-card:hover { transform: translateY(-4px); box-shadow: 0 10px 25px rgba(0,0,0,0.08); }
.row-hover:hover { transform: translateY(-2px); background-color: #f5f5f5; }
::-webkit-scrollbar { width: 8px; }
::-webkit-scrollbar-track { background: #fbf9f8; }
::-webkit-scrollbar-thumb { background: #bfc9c5; border-radius: 10px; }
::-webkit-scrollbar-thumb:hover { background: #28695c; }
</style>
</head>
<body class="bg-background text-on-surface">
<header class="bg-surface shadow-[0_4px_15px_rgba(0,0,0,0.06)] fixed top-0 left-0 right-0 z-50">
<div class="relative flex justify-between items-center w-full px-margin-mobile md:px-margin-desktop py-4 max-w-[1200px] mx-auto">
<div class="flex items-center gap-6">
<a class="font-headline-md text-headline-md font-bold text-primary" href="<?= $base_url ?>/dashboard.php">FarmaVida</a>
<nav class="hidden md:flex items-center gap-5">
<a class="font-body-md text-body-md text-on-surface-variant hover:text-primary transition-colors duration-300" href="<?= $base_url ?>/dashboard.php">Dashboard</a>
<a class="font-body-md text-body-md text-on-surface-variant hover:text-primary transition-colors duration-300" href="<?= $base_url ?>/views/medicamentos/index.php">Medicamentos</a>
<a class="font-body-md text-body-md text-on-surface-variant hover:text-primary transition-colors duration-300" href="<?= $base_url ?>/views/lotes/index.php">Lotes</a>
<a class="font-body-md text-body-md text-on-surface-variant hover:text-primary transition-colors duration-300" href="<?= $base_url ?>/views/proveedores/index.php">Proveedores</a>
<a class="font-body-md text-body-md text-on-surface-variant hover:text-primary transition-colors duration-300" href="<?= $base_url ?>/views/clientes/index.php">Clientes</a>
<a class="font-body-md text-body-md text-on-surface-variant hover:text-primary transition-colors duration-300" href="<?= $base_url ?>/views/ventas/index.php">Ventas</a>
</nav>
</div>
<div class="flex items-center gap-2">
<button class="md:hidden p-2 rounded-lg hover:bg-surface-container transition-colors" onclick="document.getElementById('mobileMenu').classList.toggle('hidden')" aria-label="Men&uacute;">
<span class="material-symbols-outlined text-on-surface-variant">menu</span>
</button>
<span class="hidden sm:inline font-body-md text-body-md text-on-surface-variant"><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
<a class="font-label-sm text-label-sm text-primary hover:underline flex items-center gap-1" href="<?= $base_url ?>/logout.php">
<span class="material-symbols-outlined text-lg">logout</span> Salir
</a>
</div>
</div>
<div id="mobileMenu" class="hidden md:hidden bg-surface border-t border-surface-container-low shadow-lg">
<div class="flex flex-col px-margin-mobile py-3 gap-1">
<a class="font-body-md text-body-md text-on-surface-variant hover:text-primary hover:bg-surface-container-low px-4 py-3 rounded-xl transition-colors" href="<?= $base_url ?>/dashboard.php">Dashboard</a>
<a class="font-body-md text-body-md text-on-surface-variant hover:text-primary hover:bg-surface-container-low px-4 py-3 rounded-xl transition-colors" href="<?= $base_url ?>/views/medicamentos/index.php">Medicamentos</a>
<a class="font-body-md text-body-md text-on-surface-variant hover:text-primary hover:bg-surface-container-low px-4 py-3 rounded-xl transition-colors" href="<?= $base_url ?>/views/lotes/index.php">Lotes</a>
<a class="font-body-md text-body-md text-on-surface-variant hover:text-primary hover:bg-surface-container-low px-4 py-3 rounded-xl transition-colors" href="<?= $base_url ?>/views/proveedores/index.php">Proveedores</a>
<a class="font-body-md text-body-md text-on-surface-variant hover:text-primary hover:bg-surface-container-low px-4 py-3 rounded-xl transition-colors" href="<?= $base_url ?>/views/clientes/index.php">Clientes</a>
<a class="font-body-md text-body-md text-on-surface-variant hover:text-primary hover:bg-surface-container-low px-4 py-3 rounded-xl transition-colors" href="<?= $base_url ?>/views/ventas/index.php">Ventas</a>
</div>
</div>
</header>
<main class="<?= $page_main_class ?>">
