# TiendApp 🛒

Tienda online hecha en **PHP y MySQL** para la materia Programación Web en la Escuela Da Vinci.

Los usuarios se registran, publican sus productos y compran productos de otros usuarios. Hay además un panel de administrador para gestionar las categorías.

## Funcionalidades

**Usuarios**
- Registro e inicio de sesión
- Perfil editable (nombre, mail, ubicación y contraseña)
- Publicar, editar y eliminar productos propios, con varias imágenes o videos por producto
- Buscador y filtro por categorías
- Compra de productos con método de pago (tarjeta o CBU) y cuotas
- Seguimiento del envío y confirmación de recepción
- Historial de compras
- Formulario de sugerencias con imagen adjunta

**Administrador**
- ABM de categorías
- Gestión del perfil

## Tecnologías

- PHP 8 (mysqli)
- MySQL / MariaDB
- HTML, CSS y Bootstrap 5
- JavaScript (vista previa de imágenes al publicar)
- XAMPP como entorno local

## Base de datos

La base `tiendapp` tiene 17 tablas relacionadas: usuarios, roles, productos, categorías, archivos, transacciones, envíos, métodos de pago, sugerencias y comentarios, y las tablas de estados de cada uno.

El script completo está en [`database/tiendapp.sql`](database/tiendapp.sql).

## Seguridad

- Consultas preparadas (`mysqli_prepare`) en login, registro, perfil, categorías y productos, para evitar inyección SQL
- Validación de datos de formularios con `trim`, `htmlspecialchars` y `filter_var` (prevención de XSS)
- Control de sesión y de roles: solo el admin entra al panel, y cada usuario solo puede editar o borrar sus propios productos
- Validación del tipo de archivo y limpieza del nombre con expresiones regulares en las subidas

## Cómo correrlo

1. Instalar [XAMPP](https://www.apachefriends.org/) y prender **Apache** y **MySQL**.
2. Copiar la carpeta del proyecto en `C:\xampp\htdocs\tiendApp` (tiene que llamarse así, las rutas usan `/tiendApp/`).
3. Entrar a `http://localhost/phpmyadmin`, crear una base llamada `tiendapp` e importar `database/tiendapp.sql`.
4. Si tu MySQL tiene usuario o clave distintos a los de XAMPP, cambiarlos en `components/config/config.php`.
5. Abrir `http://localhost/tiendApp`.

### Usuarios de prueba

| Rol | Mail | Contraseña |
| --- | --- | --- |
| Administrador | admin@gmail.com | 1234 |
| Usuario | laura@gmail.com | 1234 |

> Los datos de tarjeta son de prueba. Es un proyecto académico y no procesa pagos reales.

## Estructura

```
tiendApp/
├── admin/        panel del administrador (categorías y perfil)
├── components/   header, footer, conexión y permisos
├── database/     script SQL de la base
├── editor/       publicar y editar productos
├── log/          login, registro y cierre de sesión
├── pages/        páginas de la tienda (inicio, búsqueda, compra, envío, perfil...)
├── css/  js/  imgs/
└── prod_img/     imágenes subidas de los productos
```

---

Hecho por **Valentina Soster** · Analista de Sistemas, Escuela Da Vinci
