<?php
$servername = 'localhost';
$username = 'root';
$password = 'rootroot';
$dbname = 'concesionario';
$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die('Error al conectar con la base de datos: ' . mysqli_connect_error());
}

$sql = "SELECT * FROM coches";
$consulta = mysqli_query($conn, $sql) or die("Fallo en la consulta");

$nfilas = mysqli_num_rows($consulta);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Platinum Auto | Listado de coches</title>
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
                        <a class="nav-link" href="index.html">Coches</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../usuarios/index.html">Usuarios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../alquileres/index.html">Alquileres</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-5" id="center">
        <h3>Listado de coches:</h3>
        <?php if ($nfilas > 0): ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Modelo</th>
                        <th>Marca</th>
                        <th>Color</th>
                        <th>Precio</th>
                        <th>¿Alquilado?</th>
                        <th>Foto</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($resultado = mysqli_fetch_array($consulta)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($resultado['modelo']); ?></td>
                            <td><?php echo htmlspecialchars($resultado['marca']); ?></td>
                            <td><?php echo htmlspecialchars($resultado['color']); ?></td>
                            <td><?php echo htmlspecialchars($resultado['precio']); ?> €</td>
                            <td><?php echo $resultado['alquilado'] == 0 ? 'No' : 'Sí'; ?></td>
                            <td><img src="../<?php echo htmlspecialchars($resultado['foto']); ?>" alt="Foto" style="width: 100px;"></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No se han encontrado coches en el sistema.</p>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php mysqli_close($conn); ?>
