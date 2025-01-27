<?php
$servername = 'localhost';
$username = 'root';
$password = 'rootroot';
$dbname = 'concesionario';

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die('Error al conectar a la base de datos: ' . mysqli_connect_error());
}

if (isset($_POST['id_alquiler']) && !empty($_POST['id_alquiler'])) {
    $id_alquiler = intval($_POST['id_alquiler']);

    $sql = "DELETE FROM Alquileres WHERE id_alquiler = '$id_alquiler'";
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
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../index.html">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../coches/index.html">Coches</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../usuarios/index.html">Usuarios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.html">Alquileres</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-5" id="center">
        <h3>¡El alquiler ha sido eliminado!</h3>
        <a href="index.html">Volver al menú de la categoría</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
    } else {
        echo "<h3>Error al eliminar el alquiler: " . mysqli_error($conn) . "</h3>";
    }
} else {
    echo "<h3>No se seleccionó ningún alquiler para eliminar.</h3>";
}

mysqli_close($conn);
?>
