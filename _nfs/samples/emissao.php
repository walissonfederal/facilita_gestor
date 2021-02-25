<?php
	header('Content-Type: text/html; charset=utf-8');
	
	require('../src/eNotasGW.php');
	
	use eNotasGW\Api\Exceptions as Exceptions;

	eNotasGW::configure(array(
		'apiKey' => 'YjI0ODA2YzQtOTE1NS00MjU2LTg4YmMtYzZhMWEyM2YwMjAw'
	));
	
	$empresaId = 'B64B24B7-E554-4D8F-9D97-61C784410200';
	$idExterno = '';
	
	try
	{
		$nfeId = eNotasGW::$NFeApi->emitir($empresaId, array(
			'tipo' => 'NFS-e',
			'idExterno' => $idExterno,
			'ambienteEmissao' => 'Producao',
			'cliente' => array(
				'nome' => 'Fulano de Tal',
				'email' => 'junior178_junior@hotmail.com',
				'cpfCnpj' => '23857396237',
				'endereco' => array(
					'uf' => 'MG', 
					'cidade' => 'Belo Horizonte',
					'logradouro' => 'Rua 01',
					'numero' => '112',
					'complemento' => 'AP 402',
					'bairro' => 'Savassi',
					'cep' => '32323111'
				)
			),
			'servico' => array(
                            'descricao' => 'Discriminação do Serviço prestado',
                            'cnae' => '452006'
			),
			'valorTotal' => 10.05
		));
		
		echo 'Sucesso! </br>';
		echo "ID da NFe: {$nfeId}";
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
