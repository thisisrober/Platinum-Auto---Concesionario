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
                        <?php if ($_SESSION['tipo_usuario'] == 'comprador'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="../perfil.php">Mi perfil</a>
                            </li>
                        <?php elseif ($_SESSION['tipo_usuario'] == 'vendedor'): ?>
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
            <div class="row">
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <div class="col-md-4">
                        <div class="card">
                            <img src="../<?php echo htmlspecialchars($row['foto']); ?>" alt="Foto del coche" class="img-fluid rounded mb-3">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($row['marca']); ?> <?php echo htmlspecialchars($row['modelo']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($row['color']); ?></p>
                                <p class="card-text" style="font-weight: bold; color: #E74C3C;"><?php echo number_format($row['precio'], 2); ?> €</p>
                                <?php if (!isset($_SESSION['usuario_id'])): ?>
                                    <button class="btn btn-primary" style="width: 100%; background-color:rgb(20, 160, 241); border: none;" onclick="window.location.href='../login.php'">
                                        Inicia sesión para alquilar
                                    </button>
                                <?php elseif ($row['alquilado'] == 0): ?>
                                    <button class="btn btn-primary" style="width: 100%; background-color:rgb(14, 146, 42); border: none;" onclick="window.location.href='alquilar.php?id=<?php echo $row['id_coche']; ?>'">Alquilar</button>
                                <?php else: ?>
                                    <button class="btn btn-secondary" style="width: 100%; background-color:rgb(78, 7, 7); border: none;" disabled>No disponible</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
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