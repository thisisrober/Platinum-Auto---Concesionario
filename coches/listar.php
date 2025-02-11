<?php
session_start();
require '../src/php/db.php';

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
    <link rel="stylesheet" href="../src/css/general.css">
    <link rel="stylesheet" href="../src/css/coches.css">
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

    <div class="container my-5" id="center" style="align-items: center; justify-content: center; display: flex; text-align: center;">
    <h3>Listado de coches:</h3>
        <div class="row">
            <?php if ($nfilas > 0): ?>
                <?php while ($resultado = mysqli_fetch_array($consulta)): ?>
                    <div class="col-md-4">
                        <div class="card">
                            <img src="../<?php echo htmlspecialchars($resultado['foto']); ?>" alt="Foto" class="img-fluid rounded mb-3">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($resultado['marca']); ?> <?php echo htmlspecialchars($resultado['modelo']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($resultado['color']); ?></p>
                                <p class="card-text" style="font-weight: bold; color: #E74C3C;"><?php echo number_format($resultado['precio'], 2); ?> €</p>
                                <?php if (!isset($_SESSION['usuario_id'])): ?>
                                        <button class="btn btn-primary" style="width: 100%; background-color:rgb(20, 160, 241); border: none;" onclick="window.location.href='../login.php'">
                                            Inicia sesión para alquilar
                                        </button>
                                    <?php elseif ($resultado['alquilado'] == 0): ?>
                                        <button class="btn btn-primary" style="width: 100%; background-color:rgb(14, 146, 42); border: none;" onclick="window.location.href='alquilar.php?id=<?php echo $resultado['id_coche']; ?>'">Alquilar</button>
                                    <?php else: ?>
                                        <button class="btn btn-secondary" style="width: 100%; background-color:rgb(78, 7, 7); border: none;" disabled>No disponible</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No se han encontrado coches en el sistema.</p>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php mysqli_close($conn); ?>
