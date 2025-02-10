<?php
session_start();
require '../src/php/db.php';

if (isset($_POST['id_alquiler']) && !empty($_POST['id_alquiler'])) {
    $id_alquiler = $_POST['id_alquiler'];
    $sql_delete = "DELETE FROM alquileres WHERE id_alquiler = $id_alquiler";
    mysqli_query($conn, $sql_delete);
    header("Location: borrar.php");
    exit();
}

$sql = "SELECT a.id_alquiler, u.nombre, u.apellidos, c.modelo, c.marca 
        FROM alquileres a 
        JOIN usuarios u ON a.id_usuario = u.id_usuario 
        JOIN coches c ON a.id_coche = c.id_coche";
$result = mysqli_query($conn, $sql);
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
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../coches/index.php">Coches</a>
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
                                <a class="nav-link" href="index.php">Alquileres</a>
                            </li>
                        <?php elseif ($_SESSION['tipo_usuario'] == 'admin'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="../usuarios/index.php">Usuarios</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="index.php">Alquileres</a>
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
        <h3>Eliminar alquiler:</h3>
        <form action="eliminar.php" method="POST">
            <label for="id_alquiler">Seleccione un alquiler:</label>
            <select name="id_alquiler" class="form-select mb-3">
                <option value="">-- Seleccione --</option>
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<option value="' . $row['id_alquiler'] . '">' . $row['id_alquiler'] . ' | ' . $row['nombre'] . ' ' . $row['apellidos'] . ' - ' . $row['marca'] . ' ' . $row['modelo'] . '</option>';
                    }
                }
                ?>
            </select>
            <button type="submit" class="btn btn-danger">Eliminar</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php mysqli_close($conn); ?>
