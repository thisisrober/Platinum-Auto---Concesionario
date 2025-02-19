# 🚗 Platinum Auto - Concesionario Online

[Pantalla principal](https://prnt.sc/4iber8x4VeD7)

**Platinum Auto** es una aplicación web de concesionario de coches desarrollada en **PHP** y **MySQL**, con una interfaz moderna utilizando **Bootstrap**. Permite gestionar vehículos, alquileres y usuarios con diferentes roles.

## 📌 Características principales
- 🔹 **Usuarios con diferentes roles** (Administrador, Vendedor, Comprador)
- 🔹 **Gestión de coches** (Añadir, modificar, eliminar y ver coches)
- 🔹 **Sistema de alquileres** (Registro y administración de alquileres de coches)
- 🔹 **Panel de administración** (Gestión de usuarios y concesionario)
- 🔹 **Autenticación segura** (Inicio de sesión y permisos por rol)

---

## 🛠️ Instalación y configuración

### 🔹 1. Requisitos
- PHP 8+
- MySQL o MariaDB
- Servidor local como **XAMPP** o **WAMP**

### 🔹 2. Clonar el repositorio
```sh
git clone https://github.com/thisisrober/Pr-ctica-PHP-MYSQL.git
cd Pr-ctica-PHP-MYSQL
```

### 🔹 3. Importar la base de datos
1. Abre phpMyAdmin o tu herramienta SQL favorita.
2. Crea una base de datos con el nombre concesionario.
3. Importa el archivo database/concesionario.sql.

[Base de datos SQL](https://prnt.sc/tuLTKUkyVdut)

### 🔹 4. Configurar la conexión a la base de datos
Edita el archivo src/php/db.php y asegúrate de que los datos de conexión coinciden con tu entorno:
```sh
$host = "localhost";
$user = "root"; // Cambiar si es necesario
$password = ""; // Cambiar si tienes contraseña
$database = "concesionario";
$conn = mysqli_connect($host, $user, $password, $database);
```

---

## 👤 Usuarios de prueba
Para probar la plataforma, puedes usar los siguientes usuarios:

00000000C	- Usuario Comprador - Contraseña: Ab123456
00000000V	- Usuario Vendedor - Contraseña: Ab123456
00000000A	- Usuario Administrador - Contraseña: Ab123456

Las contraseñas constan como encriptadas en la base de datos.

[Usuarios base de datos](https://prnt.sc/aSM-fzd9RQ3l)

# 🚀 Funcionalidades
### 🔹 Usuarios
- Comprador: Puede ver los coches disponibles en el concesionario.
- Vendedor: Puede gestionar los coches que ha publicado, además de ver los alquileres de sus coches.
- Administrador: Tiene control total sobre los usuarios, los coches y los alquileres.

### 🔹 Coches
- Listado de coches disponibles.
- Posibilidad de agregar, modificar y eliminar coches (según rol).
- Sistema de búsqueda y filtrado.

[Listado de coches](https://prnt.sc/jOZZBXNbuYkH)

### 🔹 Alquileres
- Los vendedores solo pueden ver los alquileres de sus propios coches.
- Los administradores pueden ver todos los alquileres.
- Opción de finalizar un alquiler y marcar el coche como disponible.

### 🖥️ Uso del sistema
- Inicia sesión con uno de los usuarios de prueba.
- Explora el sistema y accede a las funciones según tu rol.
- Prueba las funciones de añadir/modificar coches, gestionar alquileres y cambiar entre diferentes usuarios.

# 📞 Contacto
Si tienes alguna pregunta o sugerencia, puedes contactarme en: 🔗 https://thisisrober.es/
