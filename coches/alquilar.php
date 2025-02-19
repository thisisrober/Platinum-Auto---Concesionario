<?php
session_start();
require '../src/php/db.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit();
}

$id_coche = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id_coche <= 0) {
    die("Coche no válido.");
}

$sql = "SELECT * FROM coches WHERE id_coche = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_coche);
$stmt->execute();
$resultado = $stmt->get_result()->fetch_assoc();

if (!$resultado) {
    die("Coche no encontrado.");
}

$id_usuario = $_SESSION['usuario_id'];
$sql_usuario = "SELECT saldo FROM usuarios WHERE id_usuario = ?";
$stmt_usuario = $conn->prepare($sql_usuario);
$stmt_usuario->bind_param("i", $id_usuario);
$stmt_usuario->execute();
$resultado_usuario = $stmt_usuario->get_result()->fetch_assoc();

$saldo = $resultado_usuario['saldo'];
$precio = $resultado['precio'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alquilar <?php echo htmlspecialchars($resultado['marca']) . " " . htmlspecialchars($resultado['modelo']); ?></title>
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

    <div class="container my-5" id="center" style="align-items: center; justify-content: center; text-align: center;">
        <h3>Alquilar: <?php echo htmlspecialchars($resultado['marca']) . " " . htmlspecialchars($resultado['modelo']); ?></h3><br>
        <div class="row d-flex align-items-center">
            <div class="col-md-6">
                <img src="../<?php echo htmlspecialchars($resultado['foto']); ?>" alt="Imagen del coche" class="img-fluid">
            </div>
            
            <div class="col-md-6" style="text-align: center; justify-content: center;">
                <h4>Precio: <?php echo number_format($precio, 2); ?> €</h4>
                <p><strong>Color:</strong> <?php echo htmlspecialchars($resultado['color']); ?></p>
                
                <?php if ($saldo >= $precio): ?>
                    <form action="pago.php" method="POST">
                        <input type="hidden" name="id_coche" value="<?php echo $id_coche; ?>">
                        <button type="submit" class="btn btn-success">Realizar el pago</button>
                    </form>
                <?php else: ?>
                    <p class="text-danger">No tienes saldo suficiente.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$stmt->close();
$stmt_usuario->close();
mysqli_close($conn);
?>