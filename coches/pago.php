<?php
session_start();
include('../src/php/db.php');

if (!isset($_SESSION['usuario_id'])) {
    echo '<p>Debe iniciar sesión para continuar.</p>';
    exit();
}

$id_usuario = $_SESSION['usuario_id'];

if (!isset($_POST['id_coche']) || empty($_POST['id_coche'])) {
    echo '<p>Error: No se ha proporcionado el id del coche.</p>';
    exit();
}

$id_coche = $_POST['id_coche'];

$query_saldo = "SELECT saldo FROM usuarios WHERE id_usuario = $id_usuario";
$result_saldo = mysqli_query($conn, $query_saldo);
$row_saldo = mysqli_fetch_assoc($result_saldo);
$saldo = $row_saldo['saldo'];

$query_precio = "SELECT precio FROM coches WHERE id_coche = $id_coche";
$result_precio = mysqli_query($conn, $query_precio);
$row_precio = mysqli_fetch_assoc($result_precio);
$precio = $row_precio['precio'];

if ($saldo < $precio) {
    echo '<p>No tiene suficiente saldo para realizar esta compra.</p>';
    echo '<a href="index.php" class="btn btn-link">Volver al concesionario</a>';
    exit();
}

$new_balance = $saldo - $precio;

if (isset($_POST['confirmar_pago'])) {
    $query_update_saldo = "UPDATE usuarios SET saldo = $new_balance WHERE id_usuario = $id_usuario";
    mysqli_query($conn, $query_update_saldo);

    $query_insert_alquiler = "INSERT INTO alquileres (id_usuario, id_coche, prestado) VALUES ($id_usuario, $id_coche, NOW())";
    mysqli_query($conn, $query_insert_alquiler);

    $query_update_coche = "UPDATE coches SET alquilado = 1 WHERE id_coche = $id_coche";
    mysqli_query($conn, $query_update_coche);

    $payment_confirmed = true;
} else {
    $payment_confirmed = false;
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
    #payment-info, #payment-confirmation {
        display: none;
    }
    </style>
    <link rel="stylesheet" href="../src/css/pago.css">
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

        <form action="pago.php" method="POST">
            <input type="hidden" name="id_coche" value="<?php echo $id_coche; ?>">
            <button type="submit" name="confirmar_pago" class="btn btn-success">Confirmar compra</button>
        </form>
    </div>

    <div class="container" id="payment-confirmation">
        <div class="payment-icon">✔️</div>
        <div class="payment-confirmation-text">Pago realizado, ¡gracias!</div>
        <a href="index.php" class="btn btn-success">Volver al concesionario</a>
        <p>Si no haces clic en "Volver al concesionario", serás redirigido automáticamente en 5 segundos.</p>
    </div>

    <script>
        const paymentInfo = document.getElementById('payment-info');
        const paymentConfirmation = document.getElementById('payment-confirmation');
        
        <?php if ($payment_confirmed): ?>
            paymentInfo.style.display = 'none';
            paymentConfirmation.style.display = 'block';
        <?php else: ?>
            paymentInfo.style.display = 'block';
        <?php endif; ?>

        setTimeout(function() {
            if (!document.querySelector('#payment-confirmation .btn-success').clicked) {
                window.location.href = "index.php";
            }
        }, 5000);
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>