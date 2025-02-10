<?php
session_start();
require '../src/php/db.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$id_coche = isset($_POST['id_coche']) ? (int)$_POST['id_coche'] : 0;
$id_usuario = $_SESSION['usuario_id'];

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

$sql_usuario = "SELECT saldo, id_usuario FROM usuarios WHERE id_usuario = ?";
$stmt_usuario = $conn->prepare($sql_usuario);
$stmt_usuario->bind_param("i", $id_usuario);
$stmt_usuario->execute();
$resultado_usuario = $stmt_usuario->get_result()->fetch_assoc();
$saldo = $resultado_usuario['saldo'];
$precio = $resultado['precio'];
$id_vendedor = $resultado['id_usuario']; // Obtenemos el ID del vendedor

$new_balance = $saldo - $precio;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['confirmar_pago'])) {
    if ($saldo >= $precio) {
        $sql_update = "UPDATE usuarios SET saldo = ? WHERE id_usuario = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("di", $new_balance, $id_usuario);
        $stmt_update->execute();

        $prestado = date("Y-m-d H:i:s");

        // Insertamos el alquiler en la tabla 'alquileres'
        $sql_alquiler = "INSERT INTO alquileres (id_usuario, id_coche, prestado, id_vendedor) VALUES (?, ?, ?, ?)";
        $stmt_alquiler = $conn->prepare($sql_alquiler);
        $stmt_alquiler->bind_param("iisi", $id_usuario, $id_coche, $prestado, $id_vendedor);
        $stmt_alquiler->execute();

        // Actualizamos el coche a 'alquilado' = 1
        $sql_coche = "UPDATE coches SET alquilado = 1 WHERE id_coche = ?";
        $stmt_coche = $conn->prepare($sql_coche);
        $stmt_coche->bind_param("i", $id_coche);
        $stmt_coche->execute();

        $alquiler_id = $stmt_alquiler->insert_id;

        echo "<p>Pago realizado con éxito. El coche ha sido alquilado. Referencia de alquiler: #$alquiler_id</p>";
    } else {
        echo "<p>No tienes saldo suficiente para alquilar este coche.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pasarela de pago</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f7f8fa;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .container {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
            padding: 30px;
            max-width: 600px;
            width: 100%;
            text-align: center;
        }
        h3, h4 {
            color: #3a3a3a;
        }
        .row {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .btn-success {
            background-color: #6772e5;
            border-color: #6772e5;
            padding: 12px 30px;
            font-size: 16px;
            border-radius: 8px;
        }
        .btn-success:hover {
            background-color: #5469d4;
            border-color: #5469d4;
        }
        .stripe-logo {
            width: 200px;
            margin-top: 30px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 14px;
            color: #777;
        }
        .footer img {
            width: 120px;
            margin-top: 10px;
        }
        .header-link {
            position: absolute;
            top: 10px;
            left: 10px;
        }
        #payment-info {
            display: block;
        }
        #payment-confirmation {
            display: none;
            color: #28a745;
        }
        .payment-icon {
            font-size: 50px;
        }
        .payment-confirmation-text {
            margin-top: 20px;
            font-size: 20px;
        }
    </style>
</head>
<body>

    <a href="index.php" class="btn btn-link header-link" style="text-decoration: none; color: black;">Cancelar pago</a>

    <div class="container" id="payment-info">
        <div class="text-center">
            <img src="https://upload.wikimedia.org/wikipedia/commons/b/ba/Stripe_Logo%2C_revised_2016.svg" alt="Stripe Logo" class="stripe-logo">
        </div>

        <h3>Saldo actual: <?php echo number_format($saldo, 2); ?> €</h3>
        <h4>Precio del coche: <?php echo number_format($precio, 2); ?> €</h4>
        <h4>Saldo restante: <?php echo number_format($new_balance, 2); ?> €</h4>

        <form action="confirmar_pago.php" method="POST">
            <input type="hidden" name="id_coche" value="<?php echo $id_coche; ?>">
            <button type="submit" name="confirmar_pago" class="btn btn-success">Confirmar compra</button>
        </form>
    </div>

    <div class="container" id="payment-confirmation">
        <div class="payment-icon">✔️</div>
        <div class="payment-confirmation-text">Pago realizado, ¡gracias!</div>
        <a href="index.php" class="btn btn-success">Volver al concesionario</a>
    </div>

    <script>
        const paymentInfo = document.getElementById('payment-info');
        const paymentConfirmation = document.getElementById('payment-confirmation');

        if (<?php echo ($saldo >= $precio ? 'true' : 'false'); ?>) {
            const confirmButton = document.querySelector('button[name="confirmar_pago"]');
            confirmButton.addEventListener('click', function(e) {
                e.preventDefault();
                paymentInfo.style.display = 'none';
                paymentConfirmation.style.display = 'block';
            });
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$stmt->close();
$stmt_usuario->close();
$stmt_update->close();
$stmt_alquiler->close();
$stmt_coche->close();
mysqli_close($conn);
?>