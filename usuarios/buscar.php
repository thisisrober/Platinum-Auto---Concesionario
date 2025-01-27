<?php
$servername = 'localhost';
$username = 'root';
$password = 'rootroot';
$dbname = 'concesionario';

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Error al conectar a la base de datos: " . mysqli_connect_error());
}

$dni = isset($_GET['dni']) ? trim($_GET['dni']) : '';
$nombre = isset($_GET['nombre']) ? trim($_GET['nombre']) : '';
$apellidos = isset($_GET['apellidos']) ? trim($_GET['apellidos']) : '';
$saldo = isset($_GET['saldo']) ? (float)$_GET['saldo'] : 0;

$query = "SELECT * FROM usuarios WHERE 1=1";

if (!empty($dni)) {
    $query .= " AND dni LIKE '%" . mysqli_real_escape_string($conn, $dni) . "%'";
}
if (!empty($nombre)) {
    $query .= " AND nombre LIKE '%" . mysqli_real_escape_string($conn, $nombre) . "%'";
}
if (!empty($apellidos)) {
    $query .= " AND apellidos LIKE '%" . mysqli_real_escape_string($conn, $apellidos) . "%'";
}
if (!empty($saldo)) {
    $query .= " AND saldo LIKE '%" . mysqli_real_escape_string($conn, $saldo) . "%'";
}

$result = mysqli_query($conn, $query);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Platinum Auto | Resultados de búsqueda</title>
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
                        <a class="nav-link" href="index.html">Usuarios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../alquileres/index.html">Alquileres</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-5" id="center">
        <h3>Resultados de búsqueda:</h3>
        <?php if (mysqli_num_rows($result) > 0): ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>DNI</th>
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>Saldo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['dni']); ?></td>
                            <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($row['apellidos']); ?></td>
                            <td><?php echo htmlspecialchars($row['saldo']); ?> €</td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No se encontraron resultados para los criterios de búsqueda.</p>
        <?php endif; ?>
        <form action="buscar.html" method="GET">
            <button type="submit" class="btn btn-secondary">Nueva búsqueda</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
mysqli_close($conn);
?>
