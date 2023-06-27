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
    $filtro = $_POST['filtro'];
    $valor = $_POST['valor'];
    $fecha = $_POST['fecha'];
    if ($tipo_usuario == 'A') {
        $consulta = "SELECT id_comprobante, rfc_Emisor, rfc_Receptor, fecha, subtotal FROM comprobante WHERE ($filtro = '$valor' or DATE(fecha) = DATE('$fecha')) and estado = 1; ";
        // $consulta = "SELECT rfc_ FROM comprobante WHERE $filtro = '$valor' or fecha = '$fecha' and estado = 1;";
    } else {
        $consulta = "SELECT id_comprobante, rfc_Emisor, rfc_Receptor, fecha, subtotal FROM comprobante WHERE ($filtro = '$valor' or DATE(fecha) = DATE('$fecha')) AND rfc_Emisor = '$user_rfc' and estado = 1;";
    }

    $resultado = Ejecutar($conexion, $consulta);

    if ($resultado) {
        $numFilas = mysqli_num_rows($resultado);
        if ($numFilas > 0) {
            echo "<table border='1'>";
            echo "<tr>";
            echo "<th>RFC Emisor</th>";
            echo "<th>RFC Receptor</th>";
            echo "<th>Fecha</th>";
            echo "<th>Subtotal</th>";
            echo "<th>Descargar PDF</th>";
            echo "<th>Descargar XML</th>";
            echo "</tr>";
            while ($fila = mysqli_fetch_array($resultado)) {
                echo "<tr>";
                echo "<td>" . $fila['rfc_Emisor'] . "</td>";
                echo "<td>" . $fila['rfc_Receptor'] . "</td>";
                echo "<td>" . $fila['fecha'] . "</td>";
                echo "<td>" . $fila['subtotal'] . "</td>";
                echo "<td><a href='files/{$fila['id_comprobante']}.pdf' download>Descargar PDF</a></td>";
                echo "<td><a href='files/{$fila['id_comprobante']}.xml' download>Descargar XML</a></td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "No se encontraron resultados";
        }
    } else {
        echo "Error al consultar la factura";
    }
}
    

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
  <h1>Consultar Factura</h1>
  <form action="" method="post">
    <label for="filtro">Filtro:</label>
    <select name="filtro" id="filtro">
        <option value="id_comprobante">ID del comprobante</option>
        <option value="rfc_Emisor">RFC del emisor</option>
        <option value="rfc_Receptor">RFC del receptor</option>
        </select>
    <input type="text" name="valor" id="valor" placeholder="Filtro de busqueda" >
    <br>
    <label for="fecha">Fecha:</label>
    <input type="date" name="fecha" id="fecha" placeholder="Fecha" >
    <br>
    <br>
    <button type="submit">Consultar</button>


  </form>


  <!-- Boton para ir a la pagina de generar factura -->

</body>
</html>
