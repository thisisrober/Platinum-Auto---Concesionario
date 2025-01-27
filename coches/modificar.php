<?php
    $servername = 'localhost';
    $username = 'root';
    $password = 'rootroot';
    $dbname = 'concesionario';

    $conn = mysqli_connect($servername, $username, $password, $dbname);

    if (!$conn) {
        die("Error al conectar a la base de datos: " . mysqli_connect_error());
    }

    $sql = "SELECT * FROM coches";
    $result = mysqli_query($conn, $sql);
    $coches = [];
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $coches[] = $row;
        }
    }

    $selectedCar = null;
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        $sql = "SELECT * FROM coches WHERE id_coche = '$id'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) == 1) {
            $selectedCar = mysqli_fetch_assoc($result);
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
        <h3>Seleccione un coche:</h3>
        <form action="" method="POST">
            <select name="id" class="form-select mb-3">
                <option value="">Seleccione un coche</option>
                <?php foreach ($coches as $coche): ?>
                    <option value="<?php echo $coche['id_coche']; ?>" <?php echo isset($selectedCar) && $selectedCar['id_coche'] == $coche['id_coche'] ? 'selected' : ''; ?>>
                        <?php echo $coche['id_coche'] . ' | ' . $coche['marca'] . ', ' . $coche['modelo']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-primary">Seleccionar</button>
        </form>

        <?php if ($selectedCar): ?>
            <h3>Modificar coche:</h3>
            <form action="modificado.php" method="POST" enctype="multipart/form-data">
                <label>ID del coche:</label>
                <input type="text" name="id" readonly value="<?php echo $selectedCar['id_coche']; ?>" class="form-control mb-2">
                <label>Modelo:</label>
                <input type="text" name="modelo" value="<?php echo $selectedCar['modelo']; ?>" class="form-control mb-2">
                <label>Marca:</label>
                <input type="text" name="marca" value="<?php echo $selectedCar['marca']; ?>" class="form-control mb-2">
                <label>Color:</label>
                <input type="text" name="color" value="<?php echo $selectedCar['color']; ?>" class="form-control mb-2">
                <label>Precio:</label>
                <input type="text" name="precio" value="<?php echo $selectedCar['precio']; ?>" class="form-control mb-2">
                <label>¿Está alquilado?</label><br>
                <input type="radio" id="alquilado_si" name="alquilado" value="1" <?php echo $selectedCar['alquilado'] == 1 ? 'checked' : ''; ?>>
                <label for="alquilado_si">Sí</label>
                <input type="radio" id="alquilado_no" name="alquilado" value="0" <?php echo $selectedCar['alquilado'] == 0 ? 'checked' : ''; ?>>
                <label for="alquilado_no">No</label><br><br>
                <label>Foto:</label>
                <input type="file" name="foto" class="form-control mb-3">
                <button type="submit" class="btn btn-success">Confirmar cambios</button>
            </form>
        <?php endif; ?>

        <?php if (isset($_POST['id']) && !$selectedCar): ?>
            <p class="text-danger">No se encontró el coche con el ID proporcionado.</p>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php mysqli_close($conn); ?>
