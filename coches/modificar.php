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

$id_usuario = $_SESSION['usuario_id'];
$usuario_tipo = $_SESSION['tipo_usuario'];

$sql = "SELECT * FROM coches WHERE id_vendedor = '$id_usuario' OR '$usuario_tipo' = 'admin'";
$result = mysqli_query($conn, $sql);
$coches = [];
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $coches[] = $row;
    }
}

$cocheSelecc = null;
if (isset($_POST['id'])) {
    $id = $_POST['id'];
    
    if ($usuario_tipo == 'vendedor') {
        $sql = "SELECT * FROM coches WHERE id_coche = '$id' AND id_vendedor = '$id_usuario'";
    } else {
        $sql = "SELECT * FROM coches WHERE id_coche = '$id'";
    }

    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) == 1) {
        $cocheSelecc = mysqli_fetch_assoc($result);
    }
}
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
        <h3>Seleccione un coche:</h3>
        <form action="" method="POST">
            <select name="id" class="form-select mb-3">
                <option value="">Seleccione un coche</option>
                <?php foreach ($coches as $coche): ?>
                    <option value="<?php echo $coche['id_coche']; ?>" <?php echo isset($cocheSelecc) && $cocheSelecc['id_coche'] == $coche['id_coche'] ? 'selected' : ''; ?>>
                        <?php echo $coche['id_coche'] . ' | ' . $coche['marca'] . ', ' . $coche['modelo']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-primary">Seleccionar</button>
        </form>

        <?php if ($cocheSelecc): ?>
            <h3>Modificar coche</h3>
            <form id="modificar-form" method="POST" enctype="multipart/form-data" action="modificado.php">
                <img id="preview" src="../<?php echo $cocheSelecc['foto']; ?>" alt="Foto actual del coche" class="img-fluid mb-3" style="width: 600px; border-radius: 5px;"><br>
                <span style="display: flex; justify-content: center; align-items: center;">
                    <input type="file" name="foto" class="form-control mb-3" onchange="document.getElementById('preview').src = window.URL.createObjectURL(this.files[0])" style="width: 400px;">
                </span>
                <span style="display: flex; text-align: center; justify-content: center; align-items: center; flex-direction: column;">
                    <label>ID del coche:</label>
                    <input type="text" name="id" readonly value="<?php echo $cocheSelecc['id_coche']; ?>" class="form-control mb-2" style="text-align: center;">
                    <label>Marca:</label>
                    <input type="text" name="marca" value="<?php echo $cocheSelecc['marca']; ?>" class="form-control mb-2" style="text-align: center;">
                    <label>Modelo:</label>
                    <input type="text" name="modelo" value="<?php echo $cocheSelecc['modelo']; ?>" class="form-control mb-2" style="text-align: center;">
                    <label>Color:</label>
                    <input type="text" name="color" value="<?php echo $cocheSelecc['color']; ?>" class="form-control mb-2" style="text-align: center;">
                    <label>Precio:</label>
                    <input type="text" name="precio" value="<?php echo $cocheSelecc['precio']; ?>" class="form-control mb-2" style="text-align: center;">
                </span>
                <button type="submit" class="btn btn-success">Confirmar cambios</button>
            </form>
        <?php endif; ?>

        <?php if (isset($_POST['id']) && !$cocheSelecc): ?>
            <p class="text-danger">No se encontró el coche con el ID proporcionado o no tienes permisos para modificar este coche.</p>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php mysqli_close($conn); ?>
