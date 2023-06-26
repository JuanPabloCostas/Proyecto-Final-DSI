<?php
function crearPDF($ultimo_id){
    include("conexion.php");
    $con = Conectar();
    $sql = "SELECT * FROM vistaPDF WHERE id_comprobante = $ultimo_id;";
    $result = Ejecutar($con, $sql);
    $registro =mysqli_fetch_array($result, MYSQLI_ASSOC);
    Desconectar($con);
    require('fpdf.php');//Invoca a la library

    $pdf = new FPDF('P','cm',array(21,29.7)); //Constructor
    $pdf->AddPage();
    $pdf->SetAutoPageBreak(false);
    $pdf->SetFillColor(255, 255, 255);
    $pdf->SetFont('Arial','',8);//SetFont(fuente,estilo,tamaño)
    $pdf->SetXY(1,1);
    $pdf->Cell(3,1,'RFC emisor:',0,0,'L');//Cell(ancho,alto,texto,borde,salto de linea,alineacion)
    $pdf->Cell(5.5,1,$registro["rfc_Emisor"],0,0,'L');
    $pdf->Cell(4,1,'Folio Fiscal:',0,0,'L');
    $pdf->Cell(4.5,1,$registro["folio"],0,1,'L');
    $pdf->Cell(3,1,'Nombre Emisor:',0,0,'L');
    $pdf->Cell(5.5,1,$registro["nombre_Emisor"],0,0,'L');
    $pdf->Cell(4,1,'No. Serie del CSD:',0,0,'L');
    $pdf->Cell(4.5,1,$registro["serie"],0,1,'L');
    $pdf->Cell(3,1,'RFC receptor:',0,0,'L');
    $pdf->Cell(5.5,1,$registro["rfc_Receptor"],0,0,'L');
    $pdf->MultiCell(4,.5,'Codigo postal, fecha y hora de emision:',0,0,'L');//MultiCell(ancho,alto,texto,borde,salto de linea,alineacion)
    $pdf->SetXY(13.5,3);
    $pdf->Cell(4.5,1,$registro["fecha"],0,1,'L');/////////////////////////////Revisar
    $pdf->Cell(3,1,'Nombre Receptor:',0,0,'L');
    $pdf->Cell(5.5,1,$registro["nombre_receptor"],0,0,'L');
    $pdf->Cell(4,1,'Efecto de Comprobante:',0,0,'L');
    $pdf->Cell(4.5,1,$registro["tipoComprobante"],0,1,'L');
    $pdf->MultiCell(3,.5,'Codigo postal del receptor:',0,0,'L');
    $pdf->SetXY(4,5);
    $pdf->Cell(5.5,1,"",0,0,'L');////////////////////////////////////////Revisar
    $pdf->Cell(4,1,'Regimen Fiscal:',0,0,'L');
    $pdf->Cell(4.5,1,$registro["regimenFiscal_Emisor"],0,1,'L');
    $pdf->MultiCell(3,.5,'Regimen Fiscal del receptor:',0,0,'L');
    $pdf->SetXY(4,6);
    $pdf->Cell(5.5,1,$registro["regimenFiscalReceptor"],0,0,'L');
    $pdf->Cell(4,1,'Exportacion:',0,0,'L');
    $pdf->Cell(4.5,1,$registro["exportacion"],0,1,'L');
    $pdf->Cell(3,1,'Uso CFDI:',0,0,'L');
    $pdf->Cell(5.5,1,$registro["usoCFDI_Receptor"],0,1,'L');
    //Tabla de conceptos
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell(3,1.5,'Conceptos:',0,1,'L');
    $pdf->SetX(.5);
    $pdf->SetFont('Arial','',8);
    $pdf->SetFillColor(192,192,192);
    $pdf->MultiCell(3,.5,'Clave del Producto y/o Servicio',1,0,'C');
    $pdf->SetXY(3.5,9.5);
    $pdf->Cell(2.5,1,'No. identificacion',1,0,'C',true);
    $pdf->Cell(1.5,1,'Cantidad',1,0,'C',true);
    $pdf->Cell(2.5,1,'Clave de Unidad',1,0,'C',true);
    $pdf->Cell(2,1,'Unidad',1,0,'C',true);
    $pdf->Cell(2,1,'Valor Unitario',1,0,'C',true);
    $pdf->Cell(2,1,'Importe',1,0,'C',true);
    $pdf->Cell(2,1,'Descuento',1,0,'C',true);
    $pdf->Cell(2.5,1,'Objeto Impuesto',1,1,'C',true);
    $pdf->SetX(.5);
    $pdf->Cell(3,.5,$registro["claveProdServ_Concepto"],1,0,'C',false);
    $pdf->Cell(2.5,.5,$registro["noIdentificacion_Concepto"],1,0,'C',false);
    $pdf->Cell(1.5,.5,$registro["cantidad_Concepto"],1,0,'C',false);
    $pdf->Cell(2.5,.5,$registro["claveUnidad_Concepto"],1,0,'C',false);
    $pdf->Cell(2,.5,$registro["unidad_Concepto"],1,0,'C',false);
    $pdf->Cell(2,.5,$registro["valorUnitario_Concepto"],1,0,'C',false);
    $pdf->Cell(2,.5,$registro["importe_Concepto"],1,0,'C',false);
    $pdf->Cell(2,.5,$registro["descuento_Concepto"],1,0,'C',false);
    $pdf->Cell(2.5,.5,$registro["objetoImp_Concepto"],1,1,'C',false);
    $pdf->SetX(.5);
    $pdf->Cell(3,.5,'Descripcion',1,0,'C',true);
    $pdf->Cell(8.5,.5,$registro["descripcion_Concepto"],1,1,'C',false);
    $pdf->SetX(.5);
    $pdf->Cell(5.5,.5,'Numero del pedimento',1,0,'C',true);
    $pdf->Cell(5.5,.5,'Numero de cuenta predial',1,1,'C',true);
    $pdf->SetX(.5);
    $pdf->Cell(5.5,.5,'',1,0,'C',false);//Estan vacios
    $pdf->Cell(5.5,.5,'',1,1,'C',false);
    //Fin de tabla de conceptos
    $pdf->SetY(13);
    $pdf->Cell(5,1,'Moneda:',0,0,'L');
    $pdf->Cell(4,1,$registro["moneda"],0,0,'L');
    $pdf->Cell(5,1,'Subtotal:',0,0,'L');
    $pdf->Cell(5.5,1,$registro["moneda"],0,1,'R');
    $pdf->Cell(5,1,'Forma de pago:',0,0,'L');
    $pdf->Cell(4,1,$registro["formaPago"],0,0,'L');
    $pdf->Cell(5,1,'Total:',0,0,'L');
    $pdf->Cell(5.5,1,$registro["total"],0,1,'R');
    $pdf->Cell(5,1,'Metodo de pago:',0,0,'L');
    $pdf->Cell(4,1,$registro["metodoPago"],0,1,'L');
    //Complementos
    $pdf->setY(17);
    $pdf->SetFont('Arial','',6);
    $pdf->Cell(5,1,'Sello digital del CFDI:',0,1,'L');
    $pdf->SetFillColor(255,255,255);
    $pdf->MultiCell(19.5,1.5,$registro["selloDigitalCFDI"],0,1,'L');
    $pdf->SetY(19.5);
    $pdf->Cell(5,1,'Sello digital del SAT:',0,1,'L');
    $pdf->MultiCell(19.5,1.5,''.$registro["selloDigitalSAT"],0,1,'L');
//$pdf->Image('QR.png',1,22.5,4,4,'PNG');//Image(ruta,coordenada x,coordenada y,ancho,alto,tipo de imagen)
    $pdf->SetXY(5,22);
    $pdf->Cell(8.5,1,'Cadena original del complemento de certificacion digital del SAT:',0,1,'L');
    $pdf->SetX(5);
    $pdf->MultiCell(15.5,1.8,$registro["cadenaOriginalComplemento"],0,1,'L');
    $pdf->SetXY(5,24.8);
    $pdf->Cell(4,1,'RFC del proveedor de certificacion:',0,0,'L');
    $pdf->Cell(4,1,''."",0,0,'L');
    $pdf->Cell(3,1,'Fecha y hora de certificacion:',0,0,'L');
    $pdf->Cell(4.5,1,''."",0,1,'L');
    $pdf->SetXY(5,25.8);
    $pdf->Cell(4,1,'No. de serie del certificado del SAT:',0,0,'L');
    $pdf->Cell(4,1,''."",0,0,'L');

    $pdf->SetXY(7,27.5);
    $pdf->SetFont('Arial','',7);
    $pdf->Cell(8,1,'Este documento es una representacion impresa de un CFDI',0,0,'C');
    $pdf->SetX(17.5);
    $pdf->Cell(3,1,'Pagina 1 de 1',0,0,'R');

    $nombreGuardado =  $ultimo_id . '.pdf';

    $pdf->Output($nombreGuardado,'F');
}

?>