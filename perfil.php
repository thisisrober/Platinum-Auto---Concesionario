<?php
session_start();
require 'src/php/db.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$id_usuario = $_SESSION['usuario_id'];

$sql_usuario = "SELECT nombre, apellidos, dni, saldo FROM usuarios WHERE id_usuario = '$id_usuario'";
$result_usuario = mysqli_query($conn, $sql_usuario);
$usuario = mysqli_fetch_assoc($result_usuario);

$sql_alquileres = "SELECT alquileres.id_alquiler, coches.id_coche, coches.modelo, coches.marca, coches.foto, alquileres.prestado, alquileres.devuelto 
                   FROM alquileres 
                   JOIN coches ON alquileres.id_coche = coches.id_coche
                   WHERE alquileres.id_usuario = '$id_usuario' AND alquileres.devuelto IS NULL";
$result_alquileres = mysqli_query($conn, $sql_alquileres);

$alquileres = [];
if (mysqli_num_rows($result_alquileres) > 0) {
    while ($row = mysqli_fetch_assoc($result_alquileres)) {
        $alquileres[] = $row;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['nombre']) && isset($_POST['apellidos'])) {
        $nombre = $_POST['nombre'];
        $apellidos = $_POST['apellidos'];
        $sql_update = "UPDATE usuarios SET nombre = '$nombre', apellidos = '$apellidos' WHERE id_usuario = '$id_usuario'";
        mysqli_query($conn, $sql_update);
        header("Location: perfil.php");
    }

    if (isset($_POST['devolver_coche'])) {
        $id_alquiler = $_POST['id_alquiler'];
        $id_coche = $_POST['id_coche'];
        $fecha_devolucion = date('Y-m-d H:i:s');

        $sql_devolucion = "UPDATE alquileres SET devuelto = '$fecha_devolucion' WHERE id_alquiler = '$id_alquiler'";
        mysqli_query($conn, $sql_devolucion);

        $sql_actualizar_coche = "UPDATE coches SET alquilado = 0 WHERE id_coche = '$id_coche'";
        mysqli_query($conn, $sql_actualizar_coche);

        header("Location: perfil.php");
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="src/css/general.css">
</head>
<body>
    <div class="banner">
        <img src="src/img/banner.jpg">
    </div>

    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">Platinum Auto</a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="coches/index.php">Coches</a>
                    </li>
                    <?php if (!isset($_SESSION['usuario_id'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="registro.php">Registro</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Iniciar Sesión</a>
                        </li>
                    <?php else: ?>
                        <?php if ($_SESSION['tipo_usuario'] == 'comprador'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="perfil.php">Mi perfil</a>
                            </li>
                        <?php elseif ($_SESSION['tipo_usuario'] == 'vendedor'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="alquileres/index.php">Alquileres</a>
                            </li>
                        <?php elseif ($_SESSION['tipo_usuario'] == 'admin'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="usuarios/index.php">Usuarios</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="alquileres/index.php">Alquileres</a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link" href="src/php/cerrar_sesion.php">Cerrar Sesión</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-5" id="center">
        <h3>Mi perfil</h3>
        <form action="perfil.php" method="POST">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre:</label>
                <input type="text" name="nombre" class="form-control" value="<?php echo $usuario['nombre']; ?>" required style="text-align: center;">
            </div>
            <div class="mb-3">
                <label for="apellidos" class="form-label">Apellidos:</label>
                <input type="text" name="apellidos" class="form-control" value="<?php echo $usuario['apellidos']; ?>" required style="text-align: center;">
            </div>
            <div class="mb-3">
                <label for="dni" class="form-label">DNI:</label>
                <input type="text" name="dni" class="form-control" value="<?php echo $usuario['dni']; ?>" readonly style="text-align: center; color: grey;">
            </div>
            <div class="mb-3">
                <label for="saldo" class="form-label">Saldo actual:</label>
                <input type="text" name="dni" class="form-control" value="<?php echo number_format($usuario['saldo'], 2); ?> €" readonly style="text-align: center; color: grey;">
                <label for="saldo1" class="form-label" style="color: red;">Para incrementar el saldo, deberá contactar al administrador del concesionario.</label>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar datos</button>
        </form>

        <h3 class="mt-5">Coches alquilados</h3>
        <?php if (count($alquileres) > 0): ?>
            <div class="row">
                <?php foreach ($alquileres as $alquiler): ?>
                    <div class="col-md-4">
                        <div class="card">
                            <img src="<?php echo $alquiler['foto']; ?>" alt="Foto" class="img-fluid rounded mb-3">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($alquiler['marca']); ?> <?php echo htmlspecialchars($alquiler['modelo']); ?></h5>
                                <p class="card-text">Fecha de alquiler: <?php echo $alquiler['prestado']; ?></p>
                                <form action="perfil.php" method="POST">
                                    <input type="hidden" name="id_alquiler" value="<?php echo $alquiler['id_alquiler']; ?>">
                                    <input type="hidden" name="id_coche" value="<?php echo $alquiler['id_coche']; ?>">
                                    <button type="submit" name="devolver_coche" class="btn btn-danger" style="background-color:rgb(177, 24, 24);">Devolver coche</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No tienes coches alquilados en este momento.</p>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>