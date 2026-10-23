# TiendApp 🛒

Tienda online hecha en **PHP y MySQL** para la materia Programación Web II en la Escuela Da Vinci.

Los usuarios se registran, publican sus productos y compran productos de otros usuarios. Hay además un panel de administrador para gestionar las categorías.

## Funcionalidades

**Usuarios**
- Registro e inicio de sesión (con validación en el navegador y en el servidor)
- Página de bienvenida solo para usuarios logueados, con resumen de productos y compras
- Perfil editable (nombre, mail, ubicación y contraseña)
- Publicar, editar y eliminar productos propios, con varias imágenes o videos por producto
- Buscador y filtro por categorías, con paginación
- Compra de productos con método de pago (tarjeta o CBU) y cuotas, con control de stock
- Seguimiento del envío y confirmación de recepción
- Historial de compras
- Formulario de sugerencias con imagen adjunta

**Administrador**
- ABM de todos los productos de la tienda (de cualquier usuario), paginado y con imágenes
- Gestión de usuarios: darle permiso de administrador a quien no lo tiene
- ABM de categorías
- Revisión de las sugerencias de los usuarios (pendiente, aprobada o rechazada)
- Gestión del perfil

**General**
- Página de error propia cuando no se puede conectar a la base de datos

## Tecnologías

- PHP 8 (mysqli)
- MySQL / MariaDB
- HTML, CSS y Bootstrap 5
- JavaScript (vista previa de imágenes al publicar y validación del registro)
- XAMPP como entorno local

## Base de datos

La base `tiendapp` tiene 17 tablas: usuarios, roles, productos, categorías, archivos, transacciones, envíos, métodos de pago, sugerencias y comentarios, y las tablas de estados de cada uno.

El script completo está en [`database/tiendapp.sql`](database/tiendapp.sql).

## Seguridad y validaciones

- **Consultas preparadas** (`mysqli_prepare`) en todo lo que recibe texto del usuario, y los números se convierten con `intval`, para evitar inyección SQL.
- **Prevención de XSS:** los datos se guardan tal cual y se escapan con `htmlspecialchars` al mostrarlos.
- **Formularios:** se validan con `trim`, `filter_var` (mail) y expresiones regulares (tarjeta, CBU, nombres de archivo).
- **Sesiones y roles:** solo el admin entra al panel, y cada usuario solo puede ver, editar o cancelar sus propios productos, compras y envíos. Los permisos se chequean antes de imprimir la página, así las redirecciones funcionan en cualquier servidor.
- **Cambios por POST:** las acciones que borran, cancelan o cambian estados se hacen desde formularios y no desde links.
- **Compras:**
  - la cantidad tiene que ser al menos 1;
  - no se puede comprar un producto propio;
  - el stock se descuenta una sola vez y solo si alcanza, así no se vende de más;
  - al cancelar un envío, el stock se devuelve una sola vez.
- **Tarjetas:** se guardan solo los últimos 4 números, y nunca el código de seguridad.

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
| Usuario | martin@gmail.com | 1234 |

> Los datos de tarjeta son de prueba. Es un proyecto académico y no procesa pagos reales.

## Estructura

```
tiendApp/
├── admin/        panel del administrador (productos, usuarios, categorías, sugerencias y perfil)
├── components/   header, footer, conexión, permisos y paginación
├── database/     script SQL de la base
├── editor/       publicar y editar productos
├── log/          login, registro y cierre de sesión
├── pages/        páginas de la tienda (inicio, búsqueda, compra, envío, perfil...)
├── css/  js/  imgs/
└── prod_img/     imágenes subidas de los productos
```

---

Hecho por **Valentina Soster** · Analista de Sistemas, Escuela Da Vinci
