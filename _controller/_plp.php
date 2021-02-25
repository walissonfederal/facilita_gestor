<?php
session_start();
ob_start();
require_once '../_class/Ferramenta.php';

if(isset($_POST['acao'])){
    $acao = addslashes($_POST['acao']);
}else{
    $acao = addslashes($_GET['acao']);
}

function sanitizeString($string) {

		// matriz de entrada
		$what = array( 'Ã', 'Õ', 'ä','ã','à','á','â','ê','ë','è','é','ï','ì','í','ö','õ','ò','ó','ô','ü','ù','ú','û','À','Á','É','Í','Ó','Ú','ñ','Ñ','ç','Ç',' ','-','(',')',',',';',':','|','!','"','#','$','%','&','/','=','?','~','^','>','<','ª','º' );

		// matriz de saída
		$by   = array( 'A', 'O', 'a','a','a','a','a','e','e','e','e','i','i','i','o','o','o','o','o','u','u','u','u','A','A','E','I','O','U','n','n','c','C',' ','','','',',','','','','','','','','','','','','','','','','','','' );

		// devolver a string
		$retorno = str_replace($what, $by, $string);
		
		return strtoupper($retorno);
	}    

if($acao == 'load'){
    //VERIFICAÇÃO PADRÃO PARA PAGINAÇÃO DOS RESULTADOS
    $pag = (empty($_GET['pageNo']) ? '1' : $_GET['pageNo']);
    if(empty($_GET['size'])){
        $maximo = '100';
    }else{
        $maximo = $_GET['size'];
    }
    $inicio = ($pag * $maximo) - $maximo;
    
    //ORDENAÇÃO DO TABULATOR
    if(empty($_GET['sort']) && empty($_GET['sort_dir'])){
        $order_by = "ORDER BY plp_id DESC";
    }else{
        
        $sort       = addslashes($_GET['sort']);
        $sort_dir   = addslashes($_GET['sort_dir']);
        $order_by   = "ORDER BY plp_id DESC";
    }
    
    //PESQUISA VIA AJAX
    if(isset($_GET['search']) && $_GET['search'] == 'true'){
        $get_id         = addslashes($_GET['id']);
        
        if($get_id != ''){
            $sql_id = "AND plp_id = '".$get_id."'";
        }else{
            $sql_id = "";
        }
        
        $_SESSION['plp_load'] = "".$sql_id." ";
    }
    
    $read_plp_paginator = ReadComposta("SELECT plp_id FROM plp WHERE plp_id != '' {$_SESSION['plp_load']}");
    $read_plp = Read('plp', "WHERE plp_id != '' {$_SESSION['plp_load']} ".$order_by." LIMIT $inicio,$maximo");
    if(NumQuery($read_plp) > '0'){
        //PEGA QUANTOS BOTÕES PARA PAGINAÇÃO DE RESULTADOS
        $paginas = ceil(NumQuery($read_plp_paginator) / $maximo);
        //TABULATOR EXIGE ESSA INFORMAÇÃO PARA QUE PRECISE DA PAGINAÇÃO DE RESULTADOS 
        $json_plp["last_page"] = $paginas;
        foreach($read_plp as $read_plp_view){
            if($read_plp_view['plp_status'] == '0'){
                $read_plp_view['plp_status'] = 'ABERTO';
            }else{
                $read_plp_view['plp_status'] = 'FECHADO';
            }
			if($read_plp_view['plp_tipo'] == '0'){
                $read_plp_view['plp_tipo'] = 'CARTA REGISTRADA';
            }elseif($read_plp_view['plp_tipo'] == '1'){
                $read_plp_view['plp_tipo'] = 'SEDEX';
            }elseif($read_plp_view['plp_tipo'] == '2'){
                $read_plp_view['plp_tipo'] = 'PAC';
            }
            $json_plp['data'][] = $read_plp_view;
        }
    }else{
        $json_plp['data'] = null;
    }
    echo json_encode($json_plp);
}elseif($acao == 'create'){
    //RECUPERA O FORMULARIO
    $plp_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($plp_form['acao']);
	
	$read_plp = Read('plp', "WHERE plp_status = '0'");
    
    if(in_array('', $plp_form)){
        $json_plp = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }elseif(NumQuery($read_plp) > '0'){
		$json_plp = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada já existe uma plp aberta!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
	}else{
		$plp_form['plp_status'] = '0';
		$plp_form['plp_data_hora'] = date('Y-m-d H:i:s');
        Create('plp', $plp_form);
        $json_plp = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal" onclick="limpar_campos();">Apenas Fechar</button><a href="javascript::" onclick="carrega_pagina(\'plp\', \'index.php\');" data-dismiss="modal" class="btn btn-primary">Sair</a>'
        );
    }
    echo json_encode($json_plp);
}elseif($acao == 'update'){
    //RECUPERA O FORMULARIO
    $caixa_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($caixa_form['acao']);
    
    if(in_array('', $caixa_form)){
        $json_caixa = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        $uid = addslashes($_POST['id']);
        unset($caixa_form['id']);
        Update('caixa', $caixa_form, "WHERE caixa_id = '".$uid."'");
        $json_caixa = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<a href="javascript::" data-dismiss="modal" onclick="carrega_pagina(\'caixa\', \'index.php\');" class="btn btn-primary">Sair</a>'
        );
    }
    echo json_encode($json_caixa);
}elseif($acao == 'load_plp_grid'){
    $uid = addslashes($_POST['id_pedido']);
    
	$count_itens_pedidos = '0';
    $read_itens_plp = Read('itens_plp', "WHERE itens_plp_id_plp = '".$uid."' ORDER BY itens_plp_id DESC");
    if(NumQuery($read_itens_plp) > '0'){
		echo '<table class="table">';
            echo '<thead>';
                echo '<tr>';
                    echo '<th>#</th>';
					echo '<th>Peso</th>';
                    echo '<th>Etiqueta</th>';
                    echo '<th>Nome</th>';
					echo '<th>Endereço</th>';
					echo '<th>Número</th>';
					echo '<th>Complemento</th>';
					echo '<th>Bairro</th>';
					echo '<th>CEP</th>';
					echo '<th>Cidade</th>';
					echo '<th>UF</th>';
                    echo '<th colspan="2">ações</th>';
                echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
        foreach($read_itens_plp as $read_itens_plp_view){
			$count_itens_pedidos++;
            echo '<tr>';
                echo '<td>'.$count_itens_pedidos.'</td>';
				echo '<td>'.$read_itens_plp_view['itens_plp_peso'].'</td>';
                echo '<td>'.$read_itens_plp_view['itens_plp_etiqueta'].'</td>';
                echo '<td>'.$read_itens_plp_view['itens_plp_nome'].'</td>';
				echo '<td>'.$read_itens_plp_view['itens_plp_endereco'].'</td>';
				echo '<td>'.$read_itens_plp_view['itens_plp_numero'].'</td>';
				echo '<td>'.$read_itens_plp_view['itens_plp_complemento'].'</td>';
				echo '<td>'.$read_itens_plp_view['itens_plp_bairro'].'</td>';
				echo '<td>'.$read_itens_plp_view['itens_plp_cep'].'</td>';
				echo '<td>'.$read_itens_plp_view['itens_plp_cidade'].'</td>';
				echo '<td>'.$read_itens_plp_view['itens_plp_uf'].'</td>';
                echo '<td><a href="#" onclick="delete_iten_etiqueta_sistema('.$read_itens_plp_view['itens_plp_id'].');">DELETAR</a> | <a href="view/plp/etiqueta.php?id_item='.$read_itens_plp_view['itens_plp_id'].'" target="_blank">ETIQUETA</a></td>';
            echo '</tr>';
		}
		echo '</tbody>';
        echo '</table>';
    }else{
       
    }
}elseif($acao == 'delete_iten_etiqueta_sistema'){
    $uid = addslashes($_POST['id_pedido']);
    
	$read_itens_plp = ReadComposta("SELECT * FROM itens_plp INNER JOIN plp ON plp_id = itens_plp_id_plp WHERE itens_plp_id = '".$uid."' AND plp_status = '0'");
	if(NumQuery($read_itens_plp) > '0'){
		$update_plp['itens_plp_id_plp'] = '0';
		Update('itens_plp', $update_plp, "WHERE itens_plp_id = '".$uid."'");
	}else{
		echo "<script>alert('Etiqueta não pode ser deletada!');</script>";
	}
    
}elseif($acao == 'load_contato_insert'){
    $term = addslashes($_GET['term']);
    
    $read_contato_load = Read('contato', "WHERE ((contato_nome_razao LIKE '%".$term."%') OR (contato_nome_fantasia LIKE '%".$term."%') OR (contato_cpf_cnpj LIKE '%".$term."%')) ORDER BY contato_id ASC LIMIT 10");
    if(NumQuery($read_contato_load) > '0'){
        $json_contato = '[';
        foreach($read_contato_load as $read_contato_load_view){
            $json_contato .= '{"label":"'.$read_contato_load_view['contato_nome_razao'].' | '.$read_contato_load_view['contato_nome_fantasia'].' | '.$read_contato_load_view['contato_cpf_cnpj'].'","value":"'.$read_contato_load_view['contato_id'].'"},';
        }
        $json_contato = substr($json_contato, 0,-1);
        $json_contato .= ']';
    }else{
        $json_contato = 'Sem info';
    }
    echo $json_contato;
}elseif($acao == 'load_update_campos'){
    $uid = addslashes($_POST['id_contato']);
    
    $read_contato = Read('contato', "WHERE contato_id = '".$uid."'");
    if(NumQuery($read_contato) > '0'){
        foreach($read_contato as $read_contato_view);
        $json_pedido[] = $read_contato_view;
    }else{
        $json_pedido = null;
    }
    echo json_encode($json_pedido);
}elseif($acao == 'create_insert'){
	$plp_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($plp_form['acao']);
	
	$form_validacao = $plp_form;
	unset($form_validacao['itens_plp_complemento']);
	
	if(in_array('', $form_validacao)){
        $json_plp = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        Create('itens_plp', $plp_form);
        $json_plp = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<a href="javascript::" data-dismiss="modal" onclick="carrega_pagina(\'plp\', \'index.php\');" class="btn btn-primary">Sair</a><a href="javascript::" data-dismiss="modal" onclick="carrega_pagina(\'plp\', \'insert.php?id='.$form_validacao['itens_plp_id_plp'].'\');" class="btn btn-primary">Mais Itens?</a>'
        );
    }
    echo json_encode($json_plp);
}elseif($acao == 'close_plp'){
	require_once '../_sigep/vendor/autoload.php';
	require_once '../_sigep/src/PhpSigep/Bootstrap.php';

	$accessDataParaAmbienteDeHomologacao = new \PhpSigep\Model\AccessDataHomologacao();

	$config = new \PhpSigep\Config();
	$config->setAccessData($accessDataParaAmbienteDeHomologacao);
	$config->setEnv(\PhpSigep\Config::ENV_PRODUCTION);
	$config->setCacheOptions(
		array(
			'storageOptions' => array(
				'enabled' => false,
				'ttl' => 10,
				'cacheDir' => sys_get_temp_dir(),
			),
		)
	);

	\PhpSigep\Bootstrap::start($config);
	
	$read_plp = ReadComposta("SELECT * FROM plp WHERE plp_status = '0' LIMIT 1");
	if(NumQuery($read_plp) > '0'):
		foreach($read_plp as $read_plp_view):
			$encomendas = array();
			$read_itens_plp = ReadComposta("SELECT * FROM itens_plp WHERE itens_plp_id_plp = '".$read_plp_view['plp_id']."'");
			if(NumQuery($read_itens_plp) > '0'):
				foreach($read_itens_plp as $read_itens_plp_view):
					
					$dimensao = new \PhpSigep\Model\Dimensao();
					$dimensao->setAltura(0);
					$dimensao->setLargura(0);
					$dimensao->setComprimento(0);
					$dimensao->setDiametro(0);
					if ($read_plp_view['plp_tipo'] == '0'):
						$dimensao->setTipo(\PhpSigep\Model\Dimensao::TIPO_ENVELOPE);
					else:
						$dimensao->setTipo(\PhpSigep\Model\Dimensao::TIPO_PACOTE_CAIXA);
					endif;
					
					
					$destinatario = new \PhpSigep\Model\Destinatario();
					$destinatario->setNome(sanitizeString(substr($read_itens_plp_view['itens_plp_nome'],0,50)));
					$destinatario->setLogradouro(sanitizeString(substr($read_itens_plp_view['itens_plp_endereco'],0,50)));
					$destinatario->setNumero(sanitizeString(substr($read_itens_plp_view['itens_plp_numero'],0,5)));
					$destinatario->setComplemento(sanitizeString(substr($read_itens_plp_view['itens_plp_complemento'],0,30)));
					
					$destino = new \PhpSigep\Model\DestinoNacional();
					$destino->setBairro(sanitizeString(substr($read_itens_plp_view['itens_plp_bairro'],0,30)));
					$destino->setCep(sanitizeString($read_itens_plp_view['itens_plp_cep']));
					$destino->setCidade(sanitizeString(substr($read_itens_plp_view['itens_plp_cidade'],0,30)));
					$destino->setUf(sanitizeString(substr($read_itens_plp_view['itens_plp_uf'],0,2)));

					$criar_etiqueta_sem_dv = substr($read_itens_plp_view['itens_plp_etiqueta'], -2);
					$criar_etiqueta_com_dv = substr($read_itens_plp_view['itens_plp_etiqueta'], 0,10);

					$etique_certa = $criar_etiqueta_com_dv.$criar_etiqueta_sem_dv;

					$etiqueta = new \PhpSigep\Model\Etiqueta();
					$etiqueta->setEtiquetaSemDv($etique_certa);

					$encomenda = new \PhpSigep\Model\ObjetoPostal();
					$encomenda->setDestinatario($destinatario);
					$encomenda->setDestino($destino);
					$encomenda->setDimensao($dimensao);
					$encomenda->setEtiqueta($etiqueta);
					$encomenda->setPeso($read_itens_plp_view['itens_plp_peso']);
					$encomenda->setObservacao('');
					if ($read_plp_view['plp_tipo'] == '1'):
						$encomenda->setServicoDePostagem(new \PhpSigep\Model\ServicoDePostagem(\PhpSigep\Model\ServicoDePostagem::SERVICE_SEDEX_CONTRATO_AGENCIA_TA));
					elseif ($read_plp_view['plp_tipo'] == '2'):
						$encomenda->setServicoDePostagem(new \PhpSigep\Model\ServicoDePostagem(\PhpSigep\Model\ServicoDePostagem::SERVICE_PAC_CONTRATO_AGENCIA_TA));
					elseif ($read_plp_view['plp_tipo'] == '0'):
						$encomenda->setServicoDePostagem(new \PhpSigep\Model\ServicoDePostagem(\PhpSigep\Model\ServicoDePostagem::SERVICE_CARTA_COMERCIAL_A_FATURAR));
					else:
						$encomenda->setServicoDePostagem(new \PhpSigep\Model\ServicoDePostagem(\PhpSigep\Model\ServicoDePostagem::SERVICE_CARTA_COMERCIAL_A_FATURAR));
					endif;
					$encomendas[] = $encomenda;

					$remetente = new \PhpSigep\Model\Remetente();
					$remetente->setNome('Federal Sistemas');
					$remetente->setLogradouro('AV PRESIDENTE VARGAS');
					$remetente->setNumero('254');
					$remetente->setComplemento('AO LADO DOS CORREIOS');
					$remetente->setBairro('CENTRO');
					$remetente->setCep('76300000');
					$remetente->setUf('GO');
					$remetente->setCidade('CERES');
					
				endforeach;
			endif;
			
			$count_registros = NumQuery($read_itens_plp);
			$count_plp = count($encomendas);
			
			$plp = new \PhpSigep\Model\PreListaDePostagem();
			$plp->setAccessData(new \PhpSigep\Model\AccessDataHomologacao());
			$plp->setEncomendas($encomendas);
			$plp->setRemetente($remetente);
			
			//print_r($encomendas);
			
			$phpSigep = new PhpSigep\Services\SoapClient\Real();
			$result = $phpSigep->fechaPlpVariosServicos($plp);
			
			//print_r($result);
			
			if($result->getResult() == ''):
				$jSON['msg'] = $result->getErrorMsg();
				$jSON['type'] = 'error';
				$jSON['title'] = 'Erro';
				$jSON['buttons'] = '';
			else:
				$resultC = $result->getResult();
				$idPLP = $resultC->getIdPlp();
				
				$plp_update['plp_id_correios'] = $idPLP;
				$plp_update['plp_status'] = '1';
				$plp_update['plp_data_hora_fim'] = date('Y-m-d H:i:s');
				Update('plp', $plp_update, "WHERE plp_status = '0' LIMIT 1");
				
				$jSON['msg'] = "Operação realizada com sucesso";
				$jSON['type'] = 'ok';
				$jSON['title'] = 'Parabéns';
				$jSON['buttons'] = '';
			endif;      
				
		endforeach;
	else:
		$jSON['msg'] = 'Não existe plp aberta';
		$jSON['type'] = 'error';
		$jSON['title'] = 'Erro';
		$jSON['buttons'] = '';
	endif;
	
	echo json_encode($jSON);
}
?>