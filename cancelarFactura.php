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

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include("conexion.php");
    $conexion = Conectar();
    $idComprobante = $_POST['idComprobante'];
    if ($tipo_usuario == 'A'){
        $consulta = "UPDATE comprobante SET estado = 0 WHERE id_comprobante = '$idComprobante';";
    } else {
        
        $consulta = "UPDATE comprobante SET estado = 0 WHERE id_comprobante = '$idComprobante' AND rfc_Emisor = '$user_rfc';";
    }
    $resultado = Ejecutar($conexion, $consulta);
    if ($resultado) {
        echo "Factura eliminada correctamente";
    } else {
        echo "Error al eliminar la factura";
    }
    Desconectar($conexion);
}

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
  <h2>Eliminar Factura</h2>
  <form action="" method="post">
    <label for="idComprobante">ID del comprobante:</label>
    <input type="number" name="idComprobante" id="idComprobante" placeholder="ID del comprobante" required>
    <br>
    <button type="submit">Eliminar</button>
  </form>

  <!-- Boton para ir a la pagina de generar factura -->
  <a href="generarFactura.php">Generar factura</a>

  <a href="logout.php">Cerrar sesión</a>
</body>
</html>
