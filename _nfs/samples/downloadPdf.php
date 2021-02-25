<?php
	header('Content-Type: text/html; charset=utf-8');	
	
	require('../src/eNotasGW.php');
	
	use eNotasGW\Api\Exceptions as Exceptions;

	eNotasGW::configure(array(
		'apiKey' => 'YjI0ODA2YzQtOTE1NS00MjU2LTg4YmMtYzZhMWEyM2YwMjAw'
	));
	
	$empresaId = 'B64B24B7-E554-4D8F-9D97-61C784410200';
	
	try
	{
		$nfeId = '455ad392-845c-44f4-8334-3a31ce480200';
		$pdf = eNotasGW::$NFeApi->downloadPdf($empresaId, $nfeId);
		
		/*
		descomentar para efetuar o download pelo id externo
		
		$idExterno = '1';
		$pdf = eNotasGW::$NFeApi->downloadPdfPorIdExterno($empresaId, $idExterno);
		
		*/
		
		$folder = 'Downloads';
		
		if (!file_exists($folder)) {
			mkdir($folder, 0777, true);
		}
		
		$pdfFileName = "{$folder}/NF-{$nfeId}.pdf";
		file_put_contents($pdfFileName, $pdf);
		echo "Download do pdf, arquivo salvo em \"{$pdfFileName}\"";
	}
	catch(Exceptions\invalidApiKeyException $ex) {
		echo 'Erro de autenticação: </br></br>';
		echo $ex->getMessage();
	}
	catch(Exceptions\unauthorizedException $ex) {
		echo 'Acesso negado: </br></br>';
		echo $ex->getMessage();
	}
	catch(Exceptions\apiException $ex) {
		echo 'Erro de validação: </br></br>';
		echo $ex->getMessage();
	}
	catch(Exceptions\requestException $ex) {
		echo 'Erro na requisição web: </br></br>';
		
		echo 'Requested url: ' . $ex->requestedUrl;
		echo '</br>';
		echo 'Response Code: ' . $ex->getCode();
		echo '</br>';
		echo 'Message: ' . $ex->getMessage();
		echo '</br>';
		echo 'Response Body: ' . $ex->responseBody;
	}
?>