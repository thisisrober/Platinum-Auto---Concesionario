<?php
session_start();
require '../src/php/db.php';

$modelo = isset($_GET['modelo']) ? trim($_GET['modelo']) : '';
$marca = isset($_GET['marca']) ? trim($_GET['marca']) : '';
$color = isset($_GET['color']) ? trim($_GET['color']) : '';
$precio_min = isset($_GET['precio_min']) ? (float)$_GET['precio_min'] : 0;
$precio_max = isset($_GET['precio_max']) ? (float)$_GET['precio_max'] : 0;

$query = "SELECT * FROM coches WHERE 1=1";

if (!empty($modelo)) {
    $query .= " AND modelo LIKE '%" . mysqli_real_escape_string($conn, $modelo) . "%'";
}
if (!empty($marca)) {
    $query .= " AND marca LIKE '%" . mysqli_real_escape_string($conn, $marca) . "%'";
}
if (!empty($color)) {
    $query .= " AND color LIKE '%" . mysqli_real_escape_string($conn, $color) . "%'";
}
if ($precio_min > 0) {
    $query .= " AND precio >= " . $precio_min;
}
if ($precio_max > 0) {
    $query .= " AND precio <= " . $precio_max;
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
            <a class="navbar-brand fw-bold" href="../index.php">Platinum Auto</a>
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
        <h3>Resultados de búsqueda:</h3>
        <?php if (mysqli_num_rows($result) > 0): ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Modelo</th>
                        <th>Marca</th>
                        <th>Color</th>
                        <th>Precio</th>
                        <th>Foto</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['modelo']); ?></td>
                            <td><?php echo htmlspecialchars($row['marca']); ?></td>
                            <td><?php echo htmlspecialchars($row['color']); ?></td>
                            <td><?php echo htmlspecialchars($row['precio']); ?> €</td>
                            <td><img src="../<?php echo htmlspecialchars($row['foto']); ?>" alt="Foto" style="width: 100px;"></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No se encontraron resultados para los criterios de búsqueda.</p>
        <?php endif; ?>
        <form action="buscar.php" method="GET">
            <button type="submit" class="btn btn-secondary">Nueva búsqueda</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
mysqli_close($conn);
?>
