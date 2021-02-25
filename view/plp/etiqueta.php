<?php
	session_start();
	ob_start();
	require_once '../../_class/Ferramenta.php';
    
    $get_id = addslashes(trim(strip_tags($_GET['id_item'])));
	
	function sanitizeString($string) {

		// matriz de entrada
		$what = array( 'Ã', 'Õ', 'ä','ã','à','á','â','ê','ë','è','é','ï','ì','í','ö','õ','ò','ó','ô','ü','ù','ú','û','À','Á','É','Í','Ó','Ú','ñ','Ñ','ç','Ç',' ','-','(',')',',',';',':','|','!','"','#','$','%','&','/','=','?','~','^','>','<','ª','º' );

		// matriz de saída
		$by   = array( 'A', 'O', 'a','a','a','a','a','e','e','e','e','i','i','i','o','o','o','o','o','u','u','u','u','A','A','E','I','O','U','n','n','c','C',' ','','','',',','','','','','','','','','','','','','','','','','','' );

		// devolver a string
		$retorno = str_replace($what, $by, $string);
		
		return strtoupper($retorno);
	}    
	
	
    $read_pedido = Read('itens_plp', "WHERE itens_plp_id = '".$get_id."' LIMIT 1");
    if(NumQuery($read_pedido) > '0'):
        foreach($read_pedido as $read_pedido_view):
        endforeach;
    endif;
	
	
	
    $url_DADOS = "https://ws.hubdodesenvolvedor.com.br/v2/cep/?cep=".str_replace('-', '', $read_pedido_view['itens_plp_cep'])."&token=86665050ZsnlEgyDeF156471120";
    $ch_DADOS = curl_init(); 
    curl_setopt($ch_DADOS, CURLOPT_URL, $url_DADOS); 
    curl_setopt($ch_DADOS, CURLOPT_HEADER, false); 
    curl_setopt($ch_DADOS, CURLOPT_RETURNTRANSFER, true); 
    curl_setopt($ch_DADOS, CURLOPT_FOLLOWLOCATION, true); 
    curl_setopt($ch_DADOS,CURLOPT_TIMEOUT,450); 
    curl_setopt($ch_DADOS, CURLOPT_CONNECTTIMEOUT ,10); 
    $result_DADOS = curl_exec($ch_DADOS); 
    curl_close($ch_DADOS); 


    $result_DADOS = json_decode($result_DADOS, true);
    if($result_DADOS['return'] == 'NOK'):
        echo 'CEP INVÁLIDO';
        exit;
    endif;
	
	
    
    $read_plp_aberta = ReadComposta("SELECT * FROM plp WHERE plp_status = '0' LIMIT 1");
    
    require_once '../../_sigep/vendor/autoload.php';
    require_once '../../_sigep/src/PhpSigep/Bootstrap.php';
	
    
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
	
	
    
    
    if(NumQuery($read_plp_aberta) > '0'):
		
        if($read_pedido_view['itens_plp_etiqueta'] == ''):
        
            foreach($read_plp_aberta as $read_plp_aberta_view);
        
            $accessDataDeHomologacao = new \PhpSigep\Model\AccessDataHomologacao();
            $usuario = trim((isset($_GET['usuario']) ? $_GET['usuario'] : $accessDataDeHomologacao->getUsuario()));
            $senha = trim((isset($_GET['senha']) ? $_GET['senha'] : $accessDataDeHomologacao->getSenha()));
            $cnpjEmpresa = $accessDataDeHomologacao->getCnpjEmpresa();
			
			
            $accessData = new \PhpSigep\Model\AccessDataHomologacao();
            $accessData->setUsuario($usuario);
            $accessData->setSenha($senha);
            $accessData->setCnpjEmpresa($cnpjEmpresa);
			
			
            $params = new \PhpSigep\Model\SolicitaEtiquetas();
            $params->setQtdEtiquetas(1);
			
            if ($read_plp_aberta_view['plp_tipo'] == '1'):
                $params->setServicoDePostagem(\PhpSigep\Model\ServicoDePostagem::SERVICE_SEDEX_CONTRATO_AGENCIA);
            elseif ($read_plp_aberta_view['plp_tipo'] == '2'):
                $params->setServicoDePostagem(\PhpSigep\Model\ServicoDePostagem::SERVICE_PAC_CONTRATO_AGENCIA);
            elseif ($read_plp_aberta_view['plp_tipo'] == '0'):
                $params->setServicoDePostagem(\PhpSigep\Model\ServicoDePostagem::SERVICE_CARTA_COMERCIAL_A_FATURAR);
            else:
                $params->setServicoDePostagem(\PhpSigep\Model\ServicoDePostagem::SERVICE_CARTA_COMERCIAL_A_FATURAR);
            endif;

            $params->setAccessData($accessData);
			
            $phpSigep = new PhpSigep\Services\SoapClient\Real();
			$array_sigep = $phpSigep->solicitaEtiquetas($params);
			$etiquetasComDv = $array_sigep->getResult();

            foreach ($etiquetasComDv as $etiqueta) {
				$pedido_update['itens_plp_etiqueta'] = $etiqueta->getEtiquetaComDv();
                Update('itens_plp', $pedido_update, "WHERE itens_plp_id = '".$read_pedido_view['itens_plp_id']."'");
            }
            
            header("Location: etiqueta.php?id_item=".$get_id."");
			//echo "<script>window.location = 'etiqueta.php?id_item=".$get_id."'</script>";
		else:
			foreach($read_plp_aberta as $read_plp_aberta_view):
            
			endforeach;


			$dimensao = new \PhpSigep\Model\Dimensao();
			$dimensao->setAltura(0);
			$dimensao->setLargura(0);
			$dimensao->setComprimento(0);
			$dimensao->setDiametro(0);
			if ($read_plp_aberta_view['plp_tipo'] == '0'):
				$dimensao->setTipo(\PhpSigep\Model\Dimensao::TIPO_ENVELOPE);
			else:
				$dimensao->setTipo(\PhpSigep\Model\Dimensao::TIPO_PACOTE_CAIXA);
			endif;

			$destinatario = new \PhpSigep\Model\Destinatario();
			$destinatario->setNome(sanitizeString(substr($read_pedido_view['itens_plp_nome'],0,50)));
			$destinatario->setLogradouro(sanitizeString(substr($read_pedido_view['itens_plp_endereco'],0,50)));
			$destinatario->setNumero(sanitizeString(substr($read_pedido_view['itens_plp_numero'],0,5)));
			$destinatario->setComplemento(sanitizeString(substr($read_pedido_view['itens_plp_complemento'],0,30)));

			$destino = new \PhpSigep\Model\DestinoNacional();
			$destino->setBairro(sanitizeString(substr($read_pedido_view['itens_plp_bairro'],0,30)));
			$destino->setCep(sanitizeString($read_pedido_view['itens_plp_cep']));
			$destino->setCidade(sanitizeString(substr($read_pedido_view['itens_plp_cidade'],0,30)));
			$destino->setUf(sanitizeString(substr($read_pedido_view['itens_plp_uf'],0,2)));
			$destino->setNumeroPedido($get_id);

			$criar_etiqueta_sem_dv = substr($read_pedido_view['itens_plp_etiqueta'], -2);
			$criar_etiqueta_com_dv = substr($read_pedido_view['itens_plp_etiqueta'], 0,10);

			$etique_certa = $criar_etiqueta_com_dv.$criar_etiqueta_sem_dv;

			$etiqueta = new \PhpSigep\Model\Etiqueta();
			$etiqueta->setEtiquetaSemDv($etique_certa);

			$encomenda = new \PhpSigep\Model\ObjetoPostal();
			$encomenda->setDestinatario($destinatario);
			$encomenda->setDestino($destino);
			$encomenda->setDimensao($dimensao);
			$encomenda->setEtiqueta($etiqueta);
			$encomenda->setPeso($read_pedido_view['itens_plp_peso']);// 500 gramas
			$encomenda->setObservacao('');
			if ($read_plp_aberta_view['plp_tipo'] == '1'):
				$encomenda->setServicoDePostagem(new \PhpSigep\Model\ServicoDePostagem(\PhpSigep\Model\ServicoDePostagem::SERVICE_SEDEX_CONTRATO_AGENCIA));
			elseif ($read_plp_aberta_view['plp_tipo'] == '2'):
				$encomenda->setServicoDePostagem(new \PhpSigep\Model\ServicoDePostagem(\PhpSigep\Model\ServicoDePostagem::SERVICE_PAC_CONTRATO_AGENCIA));
			elseif ($read_plp_aberta_view['plp_tipo'] == '0'):
				$encomenda->setServicoDePostagem(new \PhpSigep\Model\ServicoDePostagem(\PhpSigep\Model\ServicoDePostagem::SERVICE_CARTA_COMERCIAL_A_FATURAR));
			else:
				$encomenda->setServicoDePostagem(new \PhpSigep\Model\ServicoDePostagem(\PhpSigep\Model\ServicoDePostagem::SERVICE_CARTA_COMERCIAL_A_FATURAR));
			endif;

			$remetente = new \PhpSigep\Model\Remetente();
			$remetente->setNome('Federal Sistemas');
			$remetente->setLogradouro('AV CONTORNO');
			$remetente->setNumero('3790');
			$remetente->setComplemento('LT 01 QD 20');
			$remetente->setBairro('RESIDENCIAL SANTA CLARA');
			$remetente->setCep('76382-256');
			$remetente->setUf('GO');
			$remetente->setCidade('GOIANESIA');

			$plp = new \PhpSigep\Model\PreListaDePostagem();
			$plp->setAccessData(new \PhpSigep\Model\AccessDataHomologacao());
			$plp->setEncomendas([$encomenda]);
			$plp->setRemetente($remetente);

			$dados = $plp;

			$logoFile = '../../_img/federal.png';

			$layoutChancela = array();
			
			$pdf = new \PhpSigep\Pdf\CartaoDePostagem2016($dados, '0'.$read_plp_aberta_view['plp_id'], $logoFile, $layoutChancela);
			
			$pdf->render();
        endif;
		
    else:
        echo 'PLP não está aberta ou pedido está aberto';
    endif;    
    
?>