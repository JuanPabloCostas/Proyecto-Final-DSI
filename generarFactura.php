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

    

    if (isset($_POST['idComprobante'])) {
        $idComprobante = $_POST['idComprobante'];
        $claveProdServ = $_POST['claveProdServ'];
        $consulta = "SELECT rfc_Emisor FROM comprobante WHERE id_comprobante = '$idComprobante';";
        $resultado = Ejecutar($conexion, $consulta);
        $fila = mysqli_fetch_array($resultado, MYSQLI_ASSOC);
        $rfcEmisor = $fila['rfc_Emisor'];
        if ($rfcEmisor != $user_rfc) {
            echo "No tienes permiso para modificar este comprobante";
            exit();
        }
        $consulta = "INSERT INTO comprobante_conceptos (id_comprobante,claveProdServ) values ('$idComprobante','$claveProdServ');";
        $resultado = Ejecutar($conexion, $consulta);
        if ($resultado) {
            $sql = "SELECT * FROM vistaXML WHERE id_comprobante = '$idComprobante' and claveProdServ_Concepto = '$claveProdServ';";
            $resultado = Ejecutar($conexion, $sql);
            $resultXML =mysqli_fetch_array($resultado, MYSQLI_ASSOC);

            $sql = "SELECT * FROM vistaPDF WHERE id_comprobante = '$idComprobante' and claveProdServ_Concepto = '$claveProdServ';";
            $result = Ejecutar($conexion, $sql);
            $resultPDF =mysqli_fetch_array($result, MYSQLI_ASSOC);
            include("generaXML.php");
            include("generaPDF.php");
            crearXML($resultXML);
            crearPDF($resultPDF);
        }
        
    } else {

    
    
    // Obtener los valores ingresados por el usuario
    $rfcEmisor = $_POST['rfcEmisor']; //a
    $rfcReceptor = $_POST['rfcReceptor']; //a
    $folioFiscal = $_POST['folioFiscal']; //a
    $noSerie = $_POST['noSerie']; //a
    $fechaYHora = $_POST['fechaYHora']; //a
    $efectoComprobante = $_POST['efectoComprobante']; //a
    $exportacion = $_POST['exportacion']; //a
    $noCertificadoSAT = $_POST['noCertificadoSAT']; //a
    $moneda = $_POST['moneda']; //a
    $formaPago = $_POST['formaPago']; //a
    $metodoPago = $_POST['metodoPago']; //a
    $selloDigitalCFDI = $_POST['selloDigitalCFDI']; //a
    $selloDigitalSAT = $_POST['selloDigitalSAT']; //a
    $cadenaOriginalComplemento = $_POST['cadenaOriginalComplemento']; //a
    $subtotal = $_POST['subtotal']; //a

    $claveProdServ = $_POST['claveProdServ']; //c

    $consulta = "INSERT INTO comprobante (rfc_Emisor,rfc_Receptor,folio,serie,fecha,tipoComprobante,exportacion,noCertificado,moneda,formaPago,metodoPago,selloDigitalCFDI,selloDigitalSAT,cadenaOriginalComplemento,subtotal,estado) values ('$rfcEmisor','$rfcReceptor','$folioFiscal','$noSerie','$fechaYHora','$efectoComprobante','$exportacion','$noCertificadoSAT','$moneda','$formaPago','$metodoPago','$selloDigitalCFDI','$selloDigitalSAT','$cadenaOriginalComplemento','$subtotal','1');";  
    $resultado = Ejecutar($conexion, $consulta);
    if ($resultado) {
        $ultimoID = mysqli_insert_id($conexion);
        if ($resultado) {
            $consulta = "INSERT INTO comprobante_conceptos (id_comprobante,claveProdServ) values ('$ultimoID','$claveProdServ');";
            $resultado = Ejecutar($conexion, $consulta);

            $sql = "SELECT * FROM vistaXML WHERE id_comprobante = '$ultimoID' and claveProdServ_Concepto = '$claveProdServ';";
            $resultado = Ejecutar($conexion, $sql);
            $resultXML =mysqli_fetch_array($resultado, MYSQLI_ASSOC);

            $sql = "SELECT * FROM vistaPDF WHERE id_comprobante = '$ultimoID' and claveProdServ_Concepto = '$claveProdServ';";
            $result = Ejecutar($conexion, $sql);
            $resultPDF =mysqli_fetch_array($result, MYSQLI_ASSOC);
            include("generaXML.php");
            include("generaPDF.php");
            crearXML($resultXML);
            crearPDF($resultPDF);

        }
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

  <label>
    <input type="checkbox" id="miCheckbox" onclick="mostrarOcultarDivs()">
    Crear comprobante
</label>
    

    <!-- <div id="div1" style="display:none;">
        Este div se muestra cuando el checkbox está marcado.
    </div> -->

    <!-- <div id="div2" style="display:block;">
        Este div se muestra cuando el checkbox no está marcado.
    </div> -->

    



    <form action="" method="post">
        <h2>Datos de comprobante</h2>
        <div id="div1" style="display:none;">
        <label for="rfcEmisor">RFC del emisor:</label>

        

        <?php if ($tipo_usuario == "U") { ?>
          <input type="text" name="rfcEmisor" id="rfcEmisor" placeholder="RFC del emisor" value="<?php echo $user_rfc; ?>" readonly>
        <?php } else { ?>
        <input type="text" name="rfcEmisor" id="rfcEmisor" placeholder="RFC del emisor" required>
        <?php } ?>
        <br>
        <label for="rfcReceptor">RFC del receptor:</label>
        <input type="text" name="rfcReceptor" id="rfcReceptor" placeholder="RFC del receptor" required>
        <br>
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
        <label for="noCertificadoSAT">No. de certificado del SAT:</label>
        <input type="number" name="noCertificadoSAT" id="noCertificadoSAT" placeholder="No. de certificado del SAT" required>
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
        </div>
        <div id="div2" style="display:block;">
        <label for="idComprobante">ID del comprobante:</label>
        <input type="number" name="idComprobante" id="idComprobante" placeholder="ID del comprobante" required>
        <br>
        </div>
        <h2>Datos de concepto</h2>
        <label for="claveProdServ">Clave de producto o servicio:</label>
        <input type="text" name="claveProdServ" id="claveProdServ" placeholder="Clave de producto o servicio" required>
        <br>
        <!-- <label for="noIdentificacion">No. de identificacion (opcional):</label>
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
        <label for="descripcion">Descripcion:</label>
        <input type="text" name="descripcion" id="descripcion" placeholder="Descripcion" required>
        <br> -->
        <br>
        <input class="button-submit" type="submit" value="Generar">
    </form>

    <script type="text/javascript">
    function mostrarOcultarDivs() {
    var checkbox = document.getElementById('miCheckbox');
    var div1 = document.getElementById('div1');
    var div2 = document.getElementById('div2');

    // Obtener todas las entradas en cada div
    var inputsDiv1 = div1.getElementsByTagName('input');
    var inputsDiv2 = div2.getElementsByTagName('input');

    if (checkbox.checked) {
        div1.style.display = 'block';
        div2.style.display = 'none';

        // Habilitar y establecer como requeridos todos los campos de entrada en div1
        for (var i = 0; i < inputsDiv1.length; i++) {
            inputsDiv1[i].disabled = false;
            inputsDiv1[i].required = true;
        }

        // Deshabilitar y establecer como no requeridos todos los campos de entrada en div2
        for (var i = 0; i < inputsDiv2.length; i++) {
            inputsDiv2[i].disabled = true;
            inputsDiv2[i].required = false;
        }
    } else {
        div1.style.display = 'none';
        div2.style.display = 'block';

        // Deshabilitar y establecer como no requeridos todos los campos de entrada en div1
        for (var i = 0; i < inputsDiv1.length; i++) {
            inputsDiv1[i].disabled = true;
            inputsDiv1[i].required = false;
        }

        // Habilitar y establecer como requeridos todos los campos de entrada en div2
        for (var i = 0; i < inputsDiv2.length; i++) {
            inputsDiv2[i].disabled = false;
            inputsDiv2[i].required = true;
        }
    }
}

    document.addEventListener('DOMContentLoaded', (event) => {
        mostrarOcultarDivs();
    });

    </script>
</body>
</html>