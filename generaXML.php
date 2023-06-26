<?php
function crearXML($ultimo_id){
include("conexion.php");
$con = Conectar();
$sql = "SELECT * FROM vistaXML WHERE id_comprobante = ultimo_id";
$resultado = Ejecutar($con, $sql);
$result =mysqli_fetch_array($resultado, MYSQLI_ASSOC);
Desconectar($con);

// Create a new DOMDocument object
$doc = new DOMDocument();

$comprobante = $doc->createElement("cfdi:Comprobante");
$comprobante->setAttribute("xsi:schemaLocation", "http://www.sat.gob.mx/cfd/4 http://www.sat.gob.mx/sitio_internet/cfd/4/cfdv40.xsd");
$comprobante->setAttribute("Version", $registro['version'] ?? "");
$comprobante->setAttribute("Fecha", $registro['fecha'] ?? "");
$comprobante->setAttribute("Sello", $registro['sello'] ?? "");
$comprobante->setAttribute("FormaPago", $registro['formaPago'] ?? "");
$comprobante->setAttribute("NoCertificado", $registro['noCertificado'] ?? "");
$comprobante->setAttribute("Certificado", $registro['certificado'] ?? "");
$comprobante->setAttribute("SubTotal", $registro['subTotal'] ?? "");
$comprobante->setAttribute("Moneda", $registro['moneda'] ?? "");
$comprobante->setAttribute("Total", $registro['total'] ?? "");
$comprobante->setAttribute("TipoDeComprobante", $registro['tipoDeComprobante'] ?? "");
$comprobante->setAttribute("MetodoPago", $registro['metodoPago'] ?? "");
$comprobante->setAttribute("LugarExpedicion", $registro['lugarExpedicion'] ?? "");
$comprobante->setAttribute("xmlns:cfdi", "http://www.sat.gob.mx/cfd/4");
$comprobante->setAttribute("xmlns:xsi", "http://www.w3.org/2001/XMLSchema-instance");


// Create and append the 'cfdi:Emisor' element
$emisor = $doc->createElement("cfdi:Emisor");
$emisor->setAttribute("Rfc", $registro['rfc_Emisor'] ?? "");
$emisor->setAttribute("Nombre", $registro['nombre_Emisor'] ?? "");
$emisor->setAttribute("RegimenFiscal", $registro['regimenFiscal_Emisor'] ?? "");
$comprobante->appendChild($emisor);

// Create and append the 'cfdi:Receptor' element
$receptor = $doc->createElement("cfdi:Receptor");
$receptor->setAttribute("Rfc", $registro['rfc_Receptor'] ?? "");
$receptor->setAttribute("Nombre", $registro['nombre_Receptor'] ?? "");
$receptor->setAttribute("DomicilioFiscalReceptor", $registro['domicilioFiscalReceptor'] ?? "");
$receptor->setAttribute("RegimenFiscalReceptor", $registro['regimenFiscalReceptor'] ?? "");
$receptor->setAttribute("UsoCFDI", $registro['usoCFDI_Receptor'] ?? "");
$comprobante->appendChild($receptor);

// Create and append the 'cfdi:Conceptos' element
$conceptos = $doc->createElement("cfdi:Conceptos");
$concepto = $doc->createElement("cfdi:Concepto");
$concepto->setAttribute("ClaveProdServ", $result['claveProdServ_Concepto']?? "");
$concepto->setAttribute("Cantidad", $result['cantidad_Concepto']?? "");
$concepto->setAttribute("ClaveUnidad", $result['claveUnidad_Concepto']?? "");
$concepto->setAttribute("Unidad", $result['unidad_Concepto']?? "");
$concepto->setAttribute("Descripcion", $result['descripcion_Concepto']?? "");
$concepto->setAttribute("ValorUnitario", $result['valorUnitario_Concepto']?? "");
$concepto->setAttribute("Importe", $result['importe_Concepto']?? "");
$concepto->setAttribute("ObjetoImp", $result['objetoImp_Concepto']?? "");
$conceptos->appendChild($concepto);
$comprobante->appendChild($conceptos);

// Create and append the 'cfdi:Complemento' element
$complemento = $doc->createElement("cfdi:Complemento");
$timbre = $doc->createElement("tfd:TimbreFiscalDigital");
$timbre->setAttribute("xmlns:tfd", "http://www.sat.gob.mx/TimbreFiscalDigital");
$timbre->setAttribute("xsi:schemaLocation", "http://www.sat.gob.mx/TimbreFiscalDigital http://www.sat.gob.mx/sitio_internet/cfd/timbrefiscaldigital/TimbreFiscalDigitalv11.xsd");
$timbre->setAttribute("Version", "");
$timbre->setAttribute("UUID", "");
$timbre->setAttribute("FechaTimbrado", "");
$timbre->setAttribute("RfcProvCertif", "");
$timbre->setAttribute("SelloCFD", "");
$timbre->setAttribute("NoCertificadoSAT", "");
$timbre->setAttribute("SelloSAT", "");
$complemento->appendChild($timbre);
$comprobante->appendChild($complemento);

// Append the 'cfdi:Comprobante' element to the document
$doc->appendChild($comprobante);

// Serialize the document to an XML string
$xmlString = $doc->saveXML();

// Format the XML string
$formattedXmlString = preg_replace('/></', ">\n<", $xmlString);

// Create a new XML file
$nombreArchivo = $result['id_comprobante'] . ".xml";
file_put_contents($nombreArchivo, $formattedXmlString);
}
?>
