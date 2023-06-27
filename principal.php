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
$tipo_usuario = $_SESSION['tipo_usuario'];

// Aquí puedes mostrar el contenido de la página principal
?>

<!DOCTYPE html>
<html>
<head>
  <title>Página principal</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<nav class="navbar">
    <?php if (isset($user_rfc)) { ?>
      <p class="rfc">Bienvenido, <?php echo $user_rfc; ?></p>
    <?php } ?>
  <ul>
    <?php if ($tipo_usuario == "U") { ?>
      <li><a href="generarFactura.php">Generar factura</a></li>
    <?php } ?>
    <li><a href="cancelarFactura.php">Cancelar factura</a></li>
    <li><a href="consultarFactura.php">Consultar factura</a></li>
    <li class="right"><a href="logout.php">Cerrar sesión</a></li>
  </ul>
  
</nav>



  <h1>Bienvenido a la página principal</h1>
  <?php if ($tipo_usuario == "U") { ?>
          <p>Has accedido como Usuario</p>
        <?php } else { ?>
          <p>Has accedido como Administrador</p>
        <?php } ?>




</body>
</html>
