<?php
$servername = 'localhost';
$username = 'root';
$password = 'rootroot';
$dbname = 'concesionario';
$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die('Error al conectar con la base de datos: ' . mysqli_connect_error());
}

$sql = "SELECT a.id_alquiler, u.nombre, u.apellidos, c.modelo, c.marca, a.prestado, a.devuelto 
        FROM Alquileres a 
        JOIN Usuarios u ON a.id_usuario = u.id_usuario 
        JOIN Coches c ON a.id_coche = c.id_coche";
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
                            <td><?php echo htmlspecialchars($row['modelo'] . " " . $row['marca']); ?></td>
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
