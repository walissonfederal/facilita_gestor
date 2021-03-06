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
		eNotasGW::$NFeApi->cancelar($empresaId, $nfeId);
		
		/**
		descomentar caso não possua o id único e queira efetuar o cancelamento pelo id externo
		
		$idExterno = '1';
		eNotasGW::$NFeApi->cancelarPorIdExterno($empresaId, $idExterno);
		
		*/
		
		echo 'Cancelamento solicitado com sucesso!';
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