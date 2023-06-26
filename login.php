<?php
session_start();

// Verificar si el usuario ya ha iniciado sesión y redirigirlo a la página principal
if (isset($_SESSION['user_id'])) {
  header("Location: principal.php");
  exit();
}

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Conectar a la base de datos (reemplaza los valores con los de tu configuración)
  
  include("conexion.php");
  
  // $db_host = 'localhost';
  // $db_nombre = 'facturas';
  // $db_usuario = 'root';
  // $db_contrasena = '';



  // $conexion = new mysqli($db_host, $db_usuario, $db_contrasena, $db_nombre);
  $conexion = Conectar();
  
  // Obtener los valores ingresados por el usuario
  
  $rfc = $_POST['rfc'];
  $contrasena = $_POST['contrasena'];
  // $efirma = $_POST['efirma'];
  $archivoKey = $_FILES['archivoKey'];



  // Buscar en la base de datos un usuario que coincida con el nombre de usuario ingresado
  $consulta = "SELECT id, contrasena, TipoUsuario, archivo_key  FROM usuarios WHERE rfc = '$rfc' LIMIT 1";
  // print_r($consulta);
  // $resultado = $conexion->query($consulta);
  $resultado = Ejecutar($conexion, $consulta);

  Desconectar($conexion);
  

  if ($resultado->num_rows === 1) {
    $fila = $resultado->fetch_assoc();
    $contrasenaAlmacenada = $fila['contrasena'];

    // Verificar si la contraseña ingresada coincide con la contraseña almacenada
    print_r($contrasenaAlmacenada);
    print_r($contrasena);
    if ($contrasena === $contrasenaAlmacenada) {
      // Iniciar sesión y almacenar el id del usuario en la sesión
      // print_r("entro");
      $keyAlmacenado = $fila['archivo_key'];
      // print_r($keyAlmacenado);

      $key = file_get_contents($archivoKey['tmp_name']);
      // $keyAlmacenado = file_get_contents($keyAlmacenado);

      $hashKey = hash('sha256', $key);
      $hashKeyAlmacenado = hash('sha256', $keyAlmacenado);

      if ($hashKey === $hashKeyAlmacenado) {
        // print_r("entro");
        $_SESSION['user_id'] = $fila['id'];
        $_SESSION['tipo_usuario'] = $fila['TipoUsuario'];
        $_SESSION['rfc'] = $rfc;

        // Redirigir al usuario a la página principal
        header("Location: principal.php");
        exit();
      }
      // $_SESSION['user_id'] = $fila['id'];
      // $_SESSION['tipo_usuario'] = $fila['TipoUsuario'];
      // $_SESSION['rfc'] = $rfc;

      // // Redirigir al usuario a la página principal
      // header("Location: principal.php");
      // exit();
    }
  }

  

  $mensajeError = "Credenciales inválidas";
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Iniciar sesión</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h1>Iniciar sesión</h1>

  <?php if (isset($mensajeError)) { ?>
    <p><?php echo $mensajeError; ?></p>
  <?php } ?>

  <form method="POST" action="" enctype="multipart/form-data">
    <label for="rfc">RFC:</label>
    <input type="text" id="rfc" name="rfc" required><br>

    <label for="contrasena">Contraseña:</label>
    <input type="password" id="contrasena" name="contrasena" required><br>

    <label for="archivoKey">e.firma (.key):</label>
    <input type="file" id="archivoKey" name="archivoKey" required><br>


    <button type="submit">Iniciar sesión</button>
  </form>
</body>
</html>
