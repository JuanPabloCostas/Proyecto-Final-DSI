<?php
session_start();

// Verificar si el usuario no ha iniciado sesión y redirigirlo a la página de inicio de sesión
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

// Obtener el id del usuario almacenado en la sesión
$user_id = $_SESSION['user_id'];
$user_rfc = $_SESSION['rfc'];

// Aquí puedes mostrar el contenido de la página principal
?>

<!DOCTYPE html>
<html>
<head>
  <title>Página principal</title>
</head>
<body>

<?php if (isset($user_rfc)) { ?>
    <p>Bienvenido, <?php echo $user_rfc; ?></p>
  <?php } ?>
  <h1>Bienvenido a la página principal</h1>
  <p>Contenido restringido solo para usuarios autenticados.</p>



  <!-- Boton para ir a la pagina de generar factura -->
  <a href="generarFactura.php">Generar factura</a>
  <a href="cancelarFactura.php">Cancelar factura</a>
  <a href="consultarFactura.php">Consultar factura</a>

  <a href="logout.php">Cerrar sesión</a>
</body>
</html>
