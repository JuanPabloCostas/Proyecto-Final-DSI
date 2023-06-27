<?php
function crearXML($registro){

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
$concepto->setAttribute("ClaveProdServ", $registro['claveProdServ_Concepto']?? "");
$concepto->setAttribute("Cantidad", $registro['cantidad_Concepto']?? "");
$concepto->setAttribute("ClaveUnidad", $registro['claveUnidad_Concepto']?? "");
$concepto->setAttribute("Unidad", $registro['unidad_Concepto']?? "");
$concepto->setAttribute("Descripcion", $registro['descripcion_Concepto']?? "");
$concepto->setAttribute("ValorUnitario", $registro['valorUnitario_Concepto']?? "");
$concepto->setAttribute("Importe", $registro['importe_Concepto']?? "");
$concepto->setAttribute("ObjetoImp", $registro['objetoImp_Concepto']?? "");
$conceptos->appendChild($concepto);
$comprobante->appendChild($conceptos);

// Create and append the 'cfdi:Complemento' element
$complemento = $doc->createElement("cfdi:Complemento");
$timbre = $doc->createElement("tfd:TimbreFiscalDigital");
$timbre->setAttribute("xmlns:tfd", "http://www.sat.gob.mx/TimbreFiscalDigital");
$timbre->setAttribute("xsi:schemaLocation", "http://www.sat.gob.mx/TimbreFiscalDigital http://www.sat.gob.mx/sitio_internet/cfd/timbrefiscaldigital/TimbreFiscalDigitalv11.xsd");
$timbre->setAttribute("Version", $registro['version']?? "");
$timbre->setAttribute("UUID", "");
$timbre->setAttribute("FechaTimbrado", "");
$timbre->setAttribute("RfcProvCertif", $registro['rfcProveedorCertificacion ']?? "");
$timbre->setAttribute("SelloCFD", $registro['selloDigitalCFDI']?? "");
$timbre->setAttribute("NoCertificadoSAT", $registro['noCertificado']?? "");
$timbre->setAttribute("SelloSAT", $registro['selloDigitalSAT']?? "");
$complemento->appendChild($timbre);
$comprobante->appendChild($complemento);

// Append the 'cfdi:Comprobante' element to the document
$doc->appendChild($comprobante);

// Serialize the document to an XML string
$xmlString = $doc->saveXML();

// Format the XML string
$formattedXmlString = preg_replace('/></', ">\n<", $xmlString);

// Create a new XML file
$nombreArchivo = "files/".$registro['id_comprobante'] . ".xml";
file_put_contents($nombreArchivo, $formattedXmlString);
}
?>
