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

$id = $_REQUEST['id'];
$modelo = $_REQUEST['modelo'];
$marca = $_REQUEST['marca'];
$color = $_REQUEST['color'];
$precio = $_REQUEST['precio'];
$foto = "";

if (isset($_FILES['foto']) && $_FILES['foto']['error'] == UPLOAD_ERR_OK) {
    $uploadDir = '../src/img/uploads/';
    $uploadFile = $uploadDir . basename($_FILES['foto']['name']);

    if (move_uploaded_file($_FILES['foto']['tmp_name'], $uploadFile)) {
        $foto = 'src/img/uploads/' . basename($_FILES['foto']['name']);
    } else {
        echo "Error al subir la imagen.";
        exit;
    }
} else {
    $query = "SELECT foto FROM coches WHERE id_coche = '$id'";
    $result = mysqli_query($conn, $query);
    if ($row = mysqli_fetch_assoc($result)) {
        $foto = $row['foto'];
    }
}

$sql = "UPDATE coches SET modelo='$modelo', marca='$marca', color='$color', precio='$precio', foto='$foto' WHERE id_coche ='$id'";

if (mysqli_query($conn, $sql)) {
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Platinum Auto | Modificación de coches</title>
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
            <a class="navbar-brand fw-bold" href="../index.php">Platinum Auto</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Coches</a>
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
                                <a class="nav-link" href="../alquileres/index.php">Alquileres</a>
                            </li>
                        <?php elseif ($_SESSION['tipo_usuario'] == 'admin'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="../usuarios/index.php">Usuarios</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../alquileres/index.php">Alquileres</a>
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
        <h3>¡El coche ha sido actualizado!</h3>
        <a href="index.php">Volver al menú de la categoría</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
} else {
    echo 'Error al actualizar: ' . mysqli_error($conn);
}

mysqli_close($conn);
?>