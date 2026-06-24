# FarmaVida - Sistema de Gestión de Farmacia

Sistema web para la gestión integral de farmacias desarrollado en PHP con MySQL.

## 🚀 Características

- **Inventario de medicamentos** con control de lotes y fechas de vencimiento
- **Gestión de clientes** con historial de compras
- **Proveedores** y vinculación con medicamentos
- **Ventas** con sistema FIFO y descuento automático de inventario
- **Reportes**: stock crítico, próximos a vencer, más vendidos
- **Autenticación** de usuarios con roles (admin/vendedor)

## 🛠️ Tecnologías

- **Backend:** PHP 7+ nativo con PDO
- **Frontend:** Tailwind CSS, Quicksand, Material Symbols
- **Base de datos:** MySQL
- **Diseño:** Healing Nature Design System

## 📋 Requisitos

- PHP 7.4 o superior
- MySQL 5.7+ o MariaDB
- Servidor web (Apache, Nginx, etc.)

## 🔧 Instalación

1. Clonar el repositorio en la carpeta del servidor web:
```bash
git clone https://github.com/Carlos20006/FarmaWeb.git
```

2. Importar la base de datos desde `sql/farmacia.sql`

3. Configurar la conexión en `config/db.php`:
```php
$host = 'localhost:3307';
$dbname = 'farmacia_db';
$user = 'root';
$pass = '';
```

4. Acceder desde el navegador:
```
http://localhost/FarmaWeb
```

## 👤 Usuarios por defecto

| Usuario | Contraseña | Rol |
|---------|-----------|-----|
| admin | admin123 | Administrador |

## 🎨 Diseño

Este proyecto utiliza el **Healing Nature Design System**, un sistema de diseño de minimalismo orgánico con paleta de colores botánicos (verde menta, azul cielo, coral) y tipografía Quicksand.
