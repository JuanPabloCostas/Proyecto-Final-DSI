<?php
include("conexion.php");
session_start();

// Verificar si el usuario no ha iniciado sesión y redirigirlo a la página de inicio de sesión
if (!isset($_SESSION['user_id']) || $_SESSION['tipo_usuario'] == 'A') {
  header("Location: login.php");
  exit();
}

// Obtener el id del usuario almacenado en la sesión
$user_id = $_SESSION['user_id'];
$user_rfc = $_SESSION['rfc'];
$tipo_usuario = $_SESSION['tipo_usuario'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conexion = Conectar();
    
    // Obtener los valores ingresados por el usuario
    $rfcEmisor = $_POST['rfcEmisor']; //a
    // print_r($rfcEmisor);
    // print_r($_POST);
    $rfcReceptor = $_POST['rfcReceptor']; //a
    $folioFiscal = $_POST['folioFiscal']; //a
    $noSerie = $_POST['noSerie']; //a
    $fechaYHora = $_POST['fechaYHora']; //a
    $efectoComprobante = $_POST['efectoComprobante']; //a
    $exportacion = $_POST['exportacion']; //a
    $noCertificadoSAT = $_POST['noCertificadoSAT']; //a
    $moneda = $_POST['moneda'];
    $formaPago = $_POST['formaPago'];
    $metodoPago = $_POST['metodoPago'];
    $selloDigitalCFDI = $_POST['selloDigitalCFDI'];
    $selloDigitalSAT = $_POST['selloDigitalSAT'];
    $cadenaOriginalComplemento = $_POST['cadenaOriginalComplemento'];
    $subtotal = $_POST['subtotal'];

    $claveProdServ = $_POST['claveProdServ']; //c
    $noIdentificacion = $_POST['noIdentificacion']; //c
    $cantidad = $_POST['cantidad']; //c
    $claveUnidad = $_POST['claveUnidad']; //c
    $unidad = $_POST['unidad']; //c
    $valorUnitario = $_POST['valorUnitario']; //c
    $importe = $_POST['importe']; //c
    $descuento = $_POST['descuento']; //c
    $objetoImpuesto = $_POST['objetoImpuesto']; //c
    $rfcProveedorCertificacion = $_POST['rfcProveedorCertificacion']; //c
    $descripcion = $_POST['descripcion']; //c
    

    // Insertamos los datos en la base de datos
    // Insertamos los datos en la tabla comprobante (los que tienen //a)
    $consulta = "INSERT INTO comprobante (rfc_Emisor,rfc_Receptor,folio,serie,fecha,tipoComprobante,exportacion,noCertificado,moneda,formaPago,metodoPago,selloDigitalCFDI,selloDigitalSAT,cadenaOriginalComplemento,subtotal,estado) values ('$rfcEmisor','$rfcReceptor','$folioFiscal','$noSerie','$fechaYHora','$efectoComprobante','$exportacion','$noCertificadoSAT','$moneda','$formaPago','$metodoPago','$selloDigitalCFDI','$selloDigitalSAT','$cadenaOriginalComplemento','$subtotal','1');";  
    $resultado = Ejecutar($conexion, $consulta);
    if ($resultado) {
        $ultimoID = mysqli_insert_id($conexion);
        // Insertamos los datos en la tabla conceptos (los que tienen //c)
        $consulta = "INSERT INTO conceptos (claveProdServ,noIdentificacion,claveUnidad,unidad,valorUnitario,importe,descuento,objetoImp,rfcProveedorCertificacion,descripcion,cantidad) values ('$claveProdServ','$noIdentificacion','$claveUnidad','$unidad','$valorUnitario','$importe','$descuento','$objetoImpuesto','$rfcProveedorCertificacion','$descripcion','$cantidad');";
        $resultado = Ejecutar($conexion, $consulta);
        if ($resultado) {
            $consulta = "INSERT INTO comprobante_conceptos (id_comprobante,claveProdServ) values ('$ultimoID','$claveProdServ');";
            $resultado = Ejecutar($conexion, $consulta);

            $sql = "SELECT * FROM vistaXML WHERE id_comprobante = '$ultimoID'";
            $resultado = Ejecutar($conexion, $sql);
            $resultXML =mysqli_fetch_array($resultado, MYSQLI_ASSOC);

            $sql = "SELECT * FROM vistaPDF WHERE id_comprobante = '$ultimoID';";
            $result = Ejecutar($conexion, $sql);
            $resultPDF =mysqli_fetch_array($result, MYSQLI_ASSOC);
            include("generaXML.php");
            include("generaPDF.php");
            crearXML($resultXML);
            crearPDF($resultPDF);

        }
    }

    Desconectar($conexion);
    


}



// Aquí puedes mostrar el contenido de la página principal
?>

<!DOCTYPE html>
<html>
<head>
  <title>Generar Factura</title>
  
  <link rel="stylesheet" href="style.css">
</head>
<body>

<nav class="navbar">
    <?php if (isset($user_rfc)) { ?>
      <p class="rfc">Bienvenido, <?php echo $user_rfc; ?></p>
    <?php } ?>
  <ul>
    <li><a href="generarFactura.php">Generar factura</a></li>
    <li><a href="cancelarFactura.php">Cancelar factura</a></li>
    <li><a href="consultarFactura.php">Consultar factura</a></li>
    <li class="right"><a href="logout.php">Cerrar sesión</a></li>
  </ul>
  
</nav>


  <h1>Generar Factura</h1>
    <form action="" method="post">
        <h2>Datos de emisor</h2>
        <label for="rfcEmisor">RFC del emisor:</label>

        <?php if ($tipo_usuario == "U") { ?>
          <input type="text" name="rfcEmisor" id="rfcEmisor" placeholder="RFC del emisor" value="<?php echo $user_rfc; ?>" readonly>
        <?php } else { ?>
        <input type="text" name="rfcEmisor" id="rfcEmisor" placeholder="RFC del emisor" required>
        <?php } ?>
        <br>
        <h2>Datos de receptor</h2>
        <label for="rfcReceptor">RFC del receptor:</label>
        <input type="text" name="rfcReceptor" id="rfcReceptor" placeholder="RFC del receptor" required>
        <br>
        <h2>Datos de emision</h2>
        <label for="folioFiscal">Folio fiscal:</label>
        <input type="text" name="folioFiscal" id="folioFiscal" placeholder="Folio fiscal" required>
        <br>
        <label for="noSerie">No. de Serie del CSD:</label>
        <input type="number" name="noSerie" id="noSerie" placeholder="No. de Serie del CSD" required>
        <br>
        <label for="fechaYHora">Fecha y hora de emision:</label>
        <input type="datetime-local" name="fechaYHora" id="fechaYHora" placeholder="Fecha y hora de emision" required>
        <br>
        <label for="efectoComprobante">Efecto de comprobante:</label>
        <input type="text" name="efectoComprobante" id="efectoComprobante" placeholder="Efecto de comprobante" required>
        <br>
        <label for="exportacion">Exportacion:</label>
        <input type="text" name="exportacion" id="exportacion" placeholder="Exportacion" required>
        <br>
        <h2>Conceptos</h2>
        <label for="claveProdServ">Clave de producto o servicio:</label>
        <input type="text" name="claveProdServ" id="claveProdServ" placeholder="Clave de producto o servicio" required>
        <br>
        <label for="noIdentificacion">No. de identificacion (opcional):</label>
        <input type="text" name="noIdentificacion" id="noIdentificacion" placeholder="No. de identificacion (opcional)">
        <br>
        <label for="cantidad">Cantidad:</label>
        <input type="number" name="cantidad" id="cantidad" placeholder="Cantidad" required>
        <br>
        <label for="claveUnidad">Clave de unidad:</label>
        <input type="text" name="claveUnidad" id="claveUnidad" placeholder="Clave de unidad" required>
        <br>
        <label for="unidad">Unidad:</label>
        <input type="text" name="unidad" id="unidad" placeholder="Unidad" required>
        <br>
        <label for="valorUnitario">Valor unitario:</label>
        <input type="decimal" name="valorUnitario" id="valorUnitario" placeholder="Valor unitario" required>
        <br>
        <label for="importe">Importe:</label>
        <input type="number" name="importe" id="importe" placeholder="Importe" required>
        <br>
        <label for="desuento">Descuento:</label>
        <input type="number" name="descuento" id="descuento" placeholder="Descuento">
        <br>
        <label for="objetoImpuesto">Objeto de impuesto:</label>
        <input type="text" name="objetoImpuesto" id="objetoImpuesto" placeholder="Objeto de impuesto" required>
        <br>
        <label for="rfcProveedorCertificacion">RFC del proveedor de certificacion:</label>
        <input type="text" name="rfcProveedorCertificacion" id="rfcProveedorCertificacion" placeholder="RFC del proveedor de certificacion" required>
        <br>
        <label for="noCertificadoSAT">No. de certificado del SAT:</label>
        <input type="number" name="noCertificadoSAT" id="noCertificadoSAT" placeholder="No. de certificado del SAT" required>
        <br>
        <label for="descripcion">Descripcion:</label>
        <input type="text" name="descripcion" id="descripcion" placeholder="Descripcion" required>
        <br>
        <label for="moneda">Moneda:</label>
        <input type="text" name="moneda" id="moneda" placeholder="Moneda" required>
        <br>
        <label for="formaPago">Forma de pago:</label>
        <input type="text" name="formaPago" id="formaPago" placeholder="Forma de pago" required>
        <br>
        <label for="metodoPago">Metodo de pago:</label>
        <input type="text" name="metodoPago" id="metodoPago" placeholder="Metodo de pago" required>
        <br>
        <label for="selloDigitalCFDI">Sello digital del CFDI:</label>
        <input type="text" name="selloDigitalCFDI" id="selloDigitalCFDI" placeholder="Sello digital del CFDI" required>
        <br>
        <label for="selloDigitalSAT">Sello digital del SAT:</label>
        <input type="text" name="selloDigitalSAT" id="selloDigitalSAT" placeholder="Sello digital del SAT" required>
        <br>
        <label for="cadenaOriginalComplemento">Cadena original del complemento de certificacion digital del SAT:</label>
        <input type="text" name="cadenaOriginalComplemento" id="cadenaOriginalComplemento" placeholder="Cadena original del complemento de certificacion digital del SAT" required>
        <br>
        <label for="subtotal">Subtotal:</label>
        <input type="number" name="subtotal" id="subtotal" placeholder="Subtotal" required>
        <br>
        <br>
        <input class="button-submit" type="submit" value="Generar">
    </form>
</body>
</html>