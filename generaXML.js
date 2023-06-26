var mysql = require('mysql2');

var con = mysql.createConnection({
  host: "localhost",
  user: "root",
  password: "",
  database: "facturas"
});

con.connect(function(err) {
    if (err) throw err;
    const input = document.getElementById("emisor");
    const emisor = input.value;
    input = document.getElementById("receptor");
    const receptor = input.value;
    con.query(`SELECT * FROM vistaXML where rfc_Emisor = ${emisor} && rfc_Receptor = ${receptor}`, function (err, result, fields) {
        if (err) throw err;
        generateXML(result)
      
    });
  });

function generateXML(result) {
    var doc = document.implementation.createDocument("", "", null);
    var comprobante = doc.createElement("cfdi:Comprobante");
    comprobante.setAttribute("xsi:schemaLocation", "http://www.sat.gob.mx/cfd/4 http://www.sat.gob.mx/sitio_internet/cfd/4/cfdv40.xsd");
    comprobante.setAttribute("Version", result[0].version);
    comprobante.setAttribute("Fecha", result[0].fecha);
    comprobante.setAttribute("Sello", result[0].sello);
    comprobante.setAttribute("FormaPago", result[0].formaPago);
    comprobante.setAttribute("NoCertificado", result[0].noCertificado);
    comprobante.setAttribute("Certificado", result[0].certificado);
    comprobante.setAttribute("SubTotal", result[0].subTotal);
    comprobante.setAttribute("Moneda", result[0].moneda);
    comprobante.setAttribute("Total", result[0].total);
    comprobante.setAttribute("TipoDeComprobante", result[0].tipoDeComprobante);
    comprobante.setAttribute("MetodoPago", result[0].metodoPago);
    comprobante.setAttribute("LugarExpedicion", result[0].lugarExpedicion);
    comprobante.setAttribute("xmlns:cfdi", "http://www.sat.gob.mx/cfd/4");
    comprobante.setAttribute("xmlns:xsi", "http://www.w3.org/2001/XMLSchema-instance");
    
    var xmlDeclaration = doc.createProcessingInstruction('xml', 'version="1.0" encoding="utf-8"');
    doc.insertBefore(xmlDeclaration, doc.firstChild);

    var emisor = doc.createElement("cfdi:Emisor");
    emisor.setAttribute("Rfc", result[0].rfc_Emisor);
    emisor.setAttribute("Nombre", result[0].nombre_Emisor);
    emisor.setAttribute("RegimenFiscal", result[0].regimenFiscal_Emisor);
    comprobante.appendChild(emisor);

    var receptor = doc.createElement("cfdi:Receptor");
    receptor.setAttribute("Rfc", result[0].rfc_Receptor);
    receptor.setAttribute("Nombre", result[0].nombre_Receptor);
    receptor.setAttribute("DomicilioFiscalReceptor", result[0].domicilioFiscalReceptor);
    receptor.setAttribute("RegimenFiscalReceptor", result[0].regimenFiscalReceptor);
    receptor.setAttribute("UsoCFDI", result[0].usoCFDI_Receptor);
    comprobante.appendChild(receptor);
    
    var conceptos = doc.createElement("cfdi:Conceptos");
        var concepto = doc.createElement("cfdi:Concepto");
        concepto.setAttribute("ClaveProdServ", result[0].claveProdServ_Concepto);
        concepto.setAttribute("Cantidad", result[0].cantidad_Concepto);
        concepto.setAttribute("ClaveUnidad", result[0].claveUnidad_Concepto);
        concepto.setAttribute("Unidad", result[0].unidad_Concepto);
        concepto.setAttribute("Descripcion", result[0].descripcion_Concepto);
        concepto.setAttribute("ValorUnitario", result[0].valorUnitario_Concepto);
        concepto.setAttribute("Importe", result[0].importe_Concepto);
        concepto.setAttribute("ObjetoImp", result[0].objetoImp_Concepto);
        conceptos.appendChild(concepto);
    comprobante.appendChild(conceptos);
    
    var complemento = doc.createElement("cfdi:Complemento");    
        var timbre = doc.createElement("tfd:TimbreFiscalDigital");
        timbre.setAttribute("xmlns:tfd", "http://www.sat.gob.mx/TimbreFiscalDigital");
        timbre.setAttribute("xsi:schemaLocation", "http://www.sat.gob.mx/TimbreFiscalDigital http://www.sat.gob.mx/sitio_internet/cfd/timbrefiscaldigital/TimbreFiscalDigitalv11.xsd");
        timbre.setAttribute("Version", "");
        timbre.setAttribute("UUID", "");
        timbre.setAttribute("FechaTimbrado", "");
        timbre.setAttribute("RfcProvCertif", "");
        timbre.setAttribute("SelloCFD", "");
        timbre.setAttribute("NoCertificadoSAT", "");
        timbre.setAttribute("SelloSAT", "");       
        complemento.appendChild(timbre);
    comprobante.appendChild(complemento);
    
    doc.appendChild(comprobante);

    var serializer = new XMLSerializer();
    var xmlString = serializer.serializeToString(doc);

    var formattedXmlString = xmlString.replace(/></g, '>\n<');

    var blob = new Blob([formattedXmlString], { type: 'text/xml' });
    nombreArchivo = "C:/xampp/htdocs/DSI30/" + result[0].id_Comprobante + ".xml";
    saveAs(blob, nombreArchivo);
}