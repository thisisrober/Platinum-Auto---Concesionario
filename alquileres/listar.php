<?php
session_start();
require '../src/php/db.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit();
}

// Seguridad de acceso: si el usuario no es tipo vendedor o administrador, le redirigirá a la página principal.
if ($_SESSION['tipo_usuario'] !== 'vendedor' && $_SESSION['tipo_usuario'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

$id_usuario = $_SESSION['usuario_id'];
$tipo_usuario = $_SESSION['tipo_usuario'];

if ($tipo_usuario === 'admin') {
    $sql = "SELECT a.id_alquiler, u.nombre, u.apellidos, c.modelo, c.marca, a.prestado, a.devuelto 
            FROM alquileres a 
            JOIN usuarios u ON a.id_usuario = u.id_usuario 
            JOIN coches c ON a.id_coche = c.id_coche";
} else {
    $sql = "SELECT a.id_alquiler, u.nombre, u.apellidos, c.modelo, c.marca, a.prestado, a.devuelto 
            FROM alquileres a 
            JOIN usuarios u ON a.id_usuario = u.id_usuario 
            JOIN coches c ON a.id_coche = c.id_coche
            WHERE c.id_vendedor = $id_usuario";
}

$consulta = mysqli_query($conn, $sql);
$nfilas = mysqli_num_rows($consulta);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Platinum Auto | Listado de Alquileres</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../src/css/tablas.css">
</head>
<body>
    <div class="banner">
        <img src="../src/img/banner.jpg">
    </div>

    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">Platinum Auto</a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../coches/index.php">Coches</a>
                    </li>
                    <?php if (!isset($_SESSION['usuario_id'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="../registro.php">Registro</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../login.php">Iniciar Sesión</a>
                        </li>
                    <?php else: ?>
                        <?php if ($_SESSION['tipo_usuario'] == 'vendedor'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="index.php">Alquileres</a>
                            </li>
                        <?php elseif ($_SESSION['tipo_usuario'] == 'admin'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="../usuarios/index.php">Usuarios</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="index.php">Alquileres</a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link" href="../src/php/cerrar_sesion.php">Cerrar Sesión</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-5" id="center">
        <h3>Listado de Alquileres:</h3>
        <?php if ($nfilas > 0): ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Coche</th>
                        <th>Fecha Prestado</th>
                        <th>Fecha Devuelto</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_array($consulta)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['nombre'] . " " . $row['apellidos']); ?></td>
                            <td><?php echo htmlspecialchars($row['marca'] . " " . $row['modelo']); ?></td>
                            <td><?php echo htmlspecialchars($row['prestado']); ?></td>
                            <td><?php echo htmlspecialchars($row['devuelto']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No se han encontrado alquileres en el sistema.</p>
        <?php endif; ?>
    </div>
</body>
</html>
<?php mysqli_close($conn); ?>
