<?php
    require_once 'Ferramenta.php';
    require('../../../_boleto_pdf/_mj_boleto_geracao/_fpdf/fpdf.php');
    include('../../../_boleto_pdf/_mj_boleto_geracao/_php-barcode-generator/src/BarcodeGenerator.php');
    include('../../../_boleto_pdf/_mj_boleto_geracao/_php-barcode-generator/src/BarcodeGeneratorPNG.php');
    include('../../../_boleto_pdf/_mj_boleto_geracao/_php-barcode-generator/src/BarcodeGeneratorSVG.php');
    include('../../../_boleto_pdf/_mj_boleto_geracao/_php-barcode-generator/src/BarcodeGeneratorHTML.php');
    
    function px2milimetros($valor){
        return ((25.4 * $valor) / 96);
    }
    
    function esquerda($entra, $comp){
        return substr($entra, 0, $comp);
    }

    function direita($entra, $comp){
        return substr($entra, strlen($entra) - $comp, $comp);
    }
?>