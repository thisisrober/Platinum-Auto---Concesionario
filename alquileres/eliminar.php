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

if (isset($_POST['id_alquiler']) && !empty($_POST['id_alquiler'])) {
    $id_alquiler = intval($_POST['id_alquiler']);

    $sql_get_coche = "SELECT id_coche FROM alquileres WHERE id_alquiler = '$id_alquiler'";
    $result = mysqli_query($conn, $sql_get_coche);

    if ($row = mysqli_fetch_assoc($result)) {
        $id_coche = $row['id_coche'];

        $sql2 = "UPDATE coches SET alquilado = 0 WHERE id_coche = '$id_coche'";
        mysqli_query($conn, $sql2);

        $sql = "DELETE FROM alquileres WHERE id_alquiler = '$id_alquiler'";
        if (mysqli_query($conn, $sql)) {
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Platinum Auto | Eliminar Alquiler</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../src/css/general.css">
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
        <h3>¡El alquiler ha sido eliminado correctamente!</h3>
        <a href="index.php">Volver al listado de alquileres</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
        } else {
            echo "<h3>Error al eliminar el alquiler: " . mysqli_error($conn) . "</h3>";
        }
    } else {
        echo "<h3>No se encontró el alquiler especificado.</h3>";
    }
} else {
    echo "<h3>No se seleccionó ningún alquiler para eliminar.</h3>";
}

mysqli_close($conn);
?>
