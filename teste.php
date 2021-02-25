<?php
    session_start();
    ob_start();
    require_once '_class/Ferramenta.php';
	
	$read_user = Read('user', "ORDER BY user_id ASC");
	if(NumQuery($read_user) > '0'){
		foreach($read_user as $read_user_view){
			$user_form_dados['user_senha'] = md5($read_user_view['user_senha']);
			Update('user', $user_form_dados, "WHERE user_id = '".$read_user_view['user_id']."'");
		}
	}
	
    /*$string = 'ñÑáãâÁÃÂéêÉÊíîÍÎóõôÓÕÔúûÚÛ';
    function tirarAcentos($string){
        return strtoupper(preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/"),explode(" ","a A e E i I o O u U n N"),$string));
    }
	
	$link = "http://www.devmedia.com.br/xml/devmedia_full.xml"; //link do arquivo xml
    $xml = simplexml_load_file($link) -> channel; //carrega o arquivo XML e retornando um Array
     
    foreach($xml -> item as $item){ //faz o loop nas tag com o nome "item"
        //exibe o valor das tags que estão dentro da tag "item"
        //utilizamos a função "utf8_decode" para exibir os caracteres corretamente
        echo "<strong>Título:</strong> ".utf8_decode($item -> title)."<br />";
        echo "<strong>Link:</strong> ".utf8_decode($item -> link)."<br />";
        echo "<strong>Descrição:</strong> ".utf8_decode($item -> description)."<br />";
        echo "<strong>Autor:</strong> ".utf8_decode($item -> author)."<br />";
        echo "<strong>Data:</strong> ".utf8_decode($item -> pubDate)."<br />";
        echo "<br />";
    }
	
	
    //echo tirarAcentos($string);
    /*
    echo '<hr />';
	$inicio_flamengo = '1400';
	$read_faturamento = Read('faturamento', "WHERE faturamento_referencia = '07/2018' LIMIT ".$inicio_flamengo.",200");
	if(NumQuery($read_faturamento) > '0'){
		foreach($read_faturamento as $read_faturamento_view){
			$return_valor_faturamento = return_valor_total_faturamento($read_faturamento_view['faturamento_id']);
			if($return_valor_faturamento > '0'){
                $read_financeiro = ReadComposta("SELECT financeiro_id, financeiro_status FROM financeiro WHERE financeiro_referencia_faturamento = '".$read_faturamento_view['faturamento_referencia']."' AND financeiro_id_contato = '".$read_faturamento_view['faturamento_id_contato']."'");
                if(NumQuery($read_financeiro) > '0'){
                    foreach($read_financeiro as $read_financeiro_view);
                    if($read_financeiro_view['financeiro_status'] == '0'){
                        $financeiro_form['financeiro_valor'] = $return_valor_faturamento;
                        Update('financeiro', $financeiro_form, "WHERE financeiro_id = '".$read_financeiro_view['financeiro_id']."'");
                    }
                }else{
					$financeiro_form['financeiro_descricao'] = 'FATURAMENTO 07/2018';
					$financeiro_form['financeiro_id_plano_conta'] = '8';
					$financeiro_form['financeiro_id_tipo_documento'] = '5';
					$financeiro_form['financeiro_data_vencimento'] = '2018-08-10';
                    $financeiro_form['financeiro_codigo'] = GetReg('financeiro', 'financeiro_codigo', "WHERE financeiro_tipo = 'CR'") + 1;
                    $financeiro_form['financeiro_tipo'] = 'CR';
                    $financeiro_form['financeiro_data_lancamento'] = date('Y-m-d');
                    $financeiro_form['financeiro_valor'] = $return_valor_faturamento;
                    $financeiro_form['financeiro_obs'] = 'FATURAMENTO 07/2018';
                    $financeiro_form['financeiro_id_contato'] = $read_faturamento_view['faturamento_id_contato'];
                    $financeiro_form['financeiro_md5'] = md5(date('Y-m-dH:i:s').rand(9,9999999999999999999999999999));
                    $financeiro_form['financeiro_fixo'] = '0';
                    $financeiro_form['financeiro_app_financeira'] = '0';
                    $financeiro_form['financeiro_status'] = '0';
                    $financeiro_form['financeiro_numero_doc'] = rand(10000,99999);
                    $financeiro_form['financeiro_referencia_faturamento'] = $read_faturamento_view['faturamento_referencia'];
                    Create('financeiro', $financeiro_form);
                }
            }
			
			/*$assunto_mail = GetDados('msg_financeiro', '3', 'msg_financeiro_id', 'msg_financeiro_assunto');
			$msg_financeiro_texto = GetDados('msg_financeiro', '3', 'msg_financeiro_id', 'msg_financeiro_texto');
			$MSG_1 = str_replace('#NOMEEMPRESA#', GetEmpresa('empresa_nome_razao'), MSGEMAIL);
			$MSG_2 = str_replace('#IMGEMPRESA#', GetEmpresa('empresa_logo'), $MSG_1);
			$MSG_3 = str_replace('#TITULOMAIL#', $assunto_mail, $MSG_2);
			$MSG_4 = str_replace('#MSGMAIL#', $msg_financeiro_texto, $MSG_3);
			$MSG_5 = str_replace('#MAILEMPRESA#', GetEmpresa('empresa_email'), $MSG_4);
			$MSG_6 = str_replace('#FONEEMPRESA#', GetEmpresa('empresa_telefone'), $MSG_5);
			$MSG_7 = str_replace('#LINKBOLETO#', '<a href="http://federalsistemas.com.br/boleto_online/" target="_blank">Clique Aqui</a>', $MSG_6);
			$read_contato = Read('contato', "WHERE contato_id = '".$read_faturamento_view['faturamento_id_contato']."'");
			if(NumQuery($read_contato) > '0'){
				foreach($read_contato as $read_contato_view);
				$email_cliente = strtolower($read_contato_view['contato_email']);
				$nome_cliente = $read_contato_view['contato_nome_razao'];
			}else{
				$email_cliente = '';
				$nome_cliente = '';
			}
			if(valMail($email_cliente)){
				$read_financeiro = Read('financeiro', "WHERE financeiro_referencia_faturamento = '06/2018' AND financeiro_id_contato = '".$read_faturamento_view['faturamento_id_contato']."'");
				if(NumQuery($read_financeiro) > '0'){
					$retorno = sendMailCampanha($assunto_mail, $MSG_7, GetEmpresa('empresa_email'), GetEmpresa('empresa_nome_fantasia'), $email_cliente, $nome_cliente);
				}
			}*/
			/*
			
		}
	}
	echo $inicio_flamengo;
    /*function return_cobrar_no_cobrar_dois($referencia, $data_ativacao){
        //COBRAR A ATIVAÇÃO
        $explode_referencia = explode('/', $referencia);
        $referencia_ativacao = $explode_referencia['1'].'-'.$explode_referencia['0'];
        $explode_data_ativacao = explode('-', $data_ativacao);
        $data_ativacao_correta = date('Y-m', strtotime('-1 month', strtotime($explode_data_ativacao['0'].'-'.$explode_data_ativacao['1'].'-01')));
        if($referencia_ativacao == $data_ativacao_correta){
            return '0';
        }else{
            return '1';
        }
    }
    $retorno_ciclo = return_ciclo_chip('2017-12-27', '2017-12-31', '12/2017');
    $verificacao_cobrar_no_cobrar = return_cobrar_no_cobrar_dois('12/2017', '2017-12-27');
    echo $verificacao_cobrar_no_cobrar;
    
    
    $read_pedido_desinstalacao_teste = Read('pedido_desinstalacao', "ORDER BY pedido_desinstalacao_data ASC");
    if(NumQuery($read_pedido_desinstalacao_teste) > '0'){
        foreach($read_pedido_desinstalacao_teste as $read_pedido_desinstalacao_teste_view){
            $explode_data_correta = explode("-", $read_pedido_desinstalacao_teste_view['pedido_desinstalacao_data']);
            $EXPLODE_TESTE['pedido_desinstalacao_cobrar'] = $explode_data_correta['1'].'/'.$explode_data_correta['0'];
            Update('pedido_desinstalacao', $EXPLODE_TESTE, "WHERE pedido_desinstalacao_id = '".$read_pedido_desinstalacao_teste_view['pedido_desinstalacao_id']."'");
            echo '<pre>';
                print_r($EXPLODE_TESTE);
            echo '</pre>';
        }
    }
    /*
    $read_faturamento_cancelamento = ReadComposta("SELECT faturamento_referencia, faturamento_data FROM faturamento WHERE faturamento_id = '4408'");
    if(NumQuery($read_faturamento_cancelamento) > '0'){
        foreach ($read_faturamento_cancelamento as $read_faturamento_cancelamento_view);
    }
    $read_itens_cancelamentos = ReadComposta("SELECT chip.chip_plano, chip.chip_num, chip.chip_iccid, itens_faturamento.itens_faturamento_plano, itens_faturamento.itens_faturamento_valor_cobrado, itens_faturamento.itens_faturamento_descricao, pedido.pedido_data_ativacao, pedido.pedido_valor_ativacao, itens_faturamento.itens_faturamento_valor_ativacao FROM itens_faturamento INNER JOIN chip ON chip.chip_id = itens_faturamento.itens_faturamento_id_chip INNER JOIN pedido ON pedido.pedido_id = itens_faturamento.itens_faturamento_id_pedido WHERE itens_faturamento.itens_faturamento_tipo = '1' AND itens_faturamento.itens_faturamento_id_faturamento = '".$id_faturamento."'");
    if(NumQuery($read_itens_cancelamentos) > '0'){
        $read_itens_faturamento_cancelamento = Read('itens_faturamento', "WHERE itens_faturamento_id_faturamento = '".$id_faturamento."'");
        if(NumQuery($read_itens_faturamento_cancelamento) > '0'){
            foreach($read_itens_faturamento_cancelamento as $read_itens_faturamento_cancelamento_view){
                $data_ativacao_pedido = GetReg('pedido', 'pedido_data_ativacao', "WHERE pedido_id = '".$read_itens_faturamento_cancelamento_view['itens_faturamento_id_pedido']."'");
                //$read_pedido_desinstalacao = Read('pedido_desinstalacao', "WHERE pedido_desinstalacao_id_pedido = '".$read_itens_faturamento_cancelamento_view['itens_faturamento_id_pedido']."'");
                $read_pedido_desinstalacao = ReadComposta("SELECT pedido_desinstalacao.pedido_desinstalacao_id_chip, pedido_desinstalacao.pedido_desinstalacao_id_pedido, pedido_desinstalacao.pedido_desinstalacao_faturar, pedido_desinstalacao.pedido_desinstalacao_valor_fatura, pedido_desinstalacao.pedido_desinstalacao_valor_multa, pedido_desinstalacao.pedido_desinstalacao_data, pedido_desinstalacao.pedido_desinstalacao_valor_total, chip.chip_plano, chip.chip_num, chip.chip_iccid FROM pedido_desinstalacao INNER JOIN chip ON chip.chip_id = pedido_desinstalacao.pedido_desinstalacao_id_chip WHERE pedido_desinstalacao.pedido_desinstalacao_id_pedido = '".$read_itens_faturamento_cancelamento_view['itens_faturamento_id_pedido']."'");
                if(NumQuery($read_pedido_desinstalacao) > '0'){
                    foreach($read_pedido_desinstalacao as $read_pedido_desinstalacao_view){
                        //$verificacao_cobrar_no_cobrar = return_cobrar_no_cobrar($read_faturamento_cancelamento_view['faturamento_referencia'], $data_ativacao_pedido);
                        $verificacao_cobrar_no_cobrar = return_ciclo_chip($data_ativacao_pedido, $read_faturamento_cancelamento_view['faturamento_data'], $read_faturamento_cancelamento_view['faturamento_referencia']);
                        if($verificacao_cobrar_no_cobrar == '1'){
                            if($read_pedido_desinstalacao_view['pedido_desinstalacao_faturar'] == '0'){
                                $facturar_chip = 'Não';
                            }else{
                                $facturar_chip = 'Sim';
                            }
                            $valor_total_faturamento_multa += $read_pedido_desinstalacao_view['pedido_desinstalacao_valor_total'] + $read_itens_faturamento_cancelamento_view['itens_faturamento_valor_cobrado'];
                            echo 'ok<br />';
                        }
                    }
                }
            }
        }
    }
    
    /*$email_cliente_baixa = 'junioralphasistemas@gmail.com';
    $nome_cliente_baixa = 'Lourival';
    if(valMail($email_cliente_baixa)){
        $msg_mail = "Olá ".$nome_cliente_baixa." tudo bem?<br />Estou passando aqui para te agradecer pelo pagamento do boleto no valor de R$100,00, agradecemos pela parceria de sempre.";
        $MSG_1 = str_replace('#NOMEEMPRESA#', GetEmpresa('empresa_nome_razao'), MSGEMAIL);
        $MSG_2 = str_replace('#IMGEMPRESA#', GetEmpresa('empresa_logo'), $MSG_1);
        $MSG_3 = str_replace('#TITULOMAIL#', 'Pagamento confirmado', $MSG_2);
        $MSG_4 = str_replace('#MSGMAIL#', $msg_mail, $MSG_3);
        $MSG_5 = str_replace('#MAILEMPRESA#', GetEmpresa('empresa_email'), $MSG_4);
        $MSG_6 = str_replace('#FONEEMPRESA#', GetEmpresa('empresa_telefone'), $MSG_5);
        $MSG_7 = str_replace('#LINKBOLETO#', '', $MSG_6);
        sendMailCampanha('Pagamento confirmado', $MSG_7, GetEmpresa('empresa_email'), GetEmpresa('empresa_nome_fantasia'), $email_cliente_baixa, $nome_cliente_baixa);
    }
    
    */
	/*
    $link = "PagSeguro_2018-01-19_15-07-56.xml"; //link do arquivo xml
    $count_pagseguro = '0';
    $valor_liquido_pagseguro = '0';
    $valor_bruto_pagseguro = '0';
    $valor_taxa_pagseguro = '0';
    $xml = simplexml_load_file($link); //carrega o arquivo XML e retornando um Array
    foreach($xml as $fatura){
        $count_pagseguro++;
        $valor_liquido_pagseguro += $fatura->Valor_Liquido;
        $valor_bruto_pagseguro += $fatura->Valor_Bruto;
        $valor_taxa_pagseguro += $fatura->Valor_Taxa;
    }
    echo 'Liquido: R$'.FormatMoney($valor_liquido_pagseguro).'<br >';
    echo 'Bruto: R$'.FormatMoney($valor_bruto_pagseguro).'<br >';
    echo 'Taxa: R$'.FormatMoney($valor_taxa_pagseguro).'<br >';
    echo 'QTD: '.$count_pagseguro.'<br >';
    echo '<pre>';
    print_r($xml);
    echo '</pre>';
    
    
    
    //echo $_SESSION['teste'];
    /*if(empty($_SESSION[VSESSION])){
        header("Location: index.php");
    }
    //$retorno = sendMailCampanha('ok', 'ok tstes', GetEmpresa('empresa_email'), GetEmpresa('empresa_nome_fantasia'), 'junioralphasistemas@gmail.com', 'mj cado');
    if(valMail('junioralphasistemas@gmail.com')){
        echo 'ok';
    }else{
        echo 'no ok';
    }
    /*$read_clientes = ReadComposta("SELECT
                                    clientes.Id, clientes.NomeFantasia, clientes.NomeRazao, clientes.CpfCnpj,
                                    clientes.Cep, clientes.Endereco, clientes.Numero, clientes.Complemento,
                                    clientes.Bairro, clientes.Telefone, clientes.Email, clientes.Obs,
                                    cidades.nome, estados.sigla, cidades.codigo_ibge, clientes.`Status`,
                                    clientes.Celular
                                    FROM clientes
                                    LEFT JOIN cidades ON cidades.id = clientes.IdCidade
                                    LEFT JOIN estados ON estados.id = clientes.IdEstado");
    if(NumQuery($read_clientes) > '0'){
        foreach($read_clientes as $read_clientes_view){
            $CreateContato['contato_id_tipo_contato'] = '1';
            $CreateContato['contato_id_regiao']     = '1';
            $CreateContato['contato_id_rota']       = '1';
            $CreateContato['contato_id']            = addslashes(trim($read_clientes_view['Id']));
            $CreateContato['contato_nome_razao']    = addslashes(trim($read_clientes_view['NomeRazao']));
            $CreateContato['contato_nome_fantasia'] = addslashes(trim($read_clientes_view['NomeFantasia']));
            $CreateContato['contato_cpf_cnpj']      = addslashes(trim($read_clientes_view['CpfCnpj']));
            $CreateContato['contato_cep']           = addslashes(trim($read_clientes_view['Cep']));
            $CreateContato['contato_endereco']      = addslashes(trim($read_clientes_view['Endereco']));
            $CreateContato['contato_numero']        = addslashes(trim($read_clientes_view['Numero']));
            $CreateContato['contato_complemento']   = addslashes(trim($read_clientes_view['Complemento']));
            $CreateContato['contato_bairro']        = addslashes(trim($read_clientes_view['Bairro']));
            $CreateContato['contato_estado']        = addslashes(trim($read_clientes_view['sigla']));
            $CreateContato['contato_cidade']        = addslashes(trim($read_clientes_view['nome']));
            $CreateContato['contato_status']        = addslashes(trim($read_clientes_view['Status']));
            $CreateContato['contato_cliente']       = addslashes(trim('1'));
            $CreateContato['contato_fornecedor']    = addslashes(trim('0'));
            $CreateContato['contato_transportador'] = addslashes(trim('0'));
            $CreateContato['contato_telefone']      = addslashes(trim($read_clientes_view['Telefone']));
            $CreateContato['contato_celular']       = addslashes(trim($read_clientes_view['Celular']));
            $CreateContato['contato_email']         = addslashes(trim($read_clientes_view['Email']));
            $CreateContato['contato_cidade_ibge']   = addslashes(trim($read_clientes_view['codigo_ibge']));
            $CreateContato['contato_obs']           = addslashes(trim($read_clientes_view['Obs']));
            Create('contato', $CreateContato);
        }
    }*/
    /*$read_financeiro = ReadComposta("SELECT * FROM contas_receber");
    if(NumQuery($read_financeiro) > '0'){
        foreach($read_financeiro as $read_financeiro_view){
            $CreateFinanceiro['financeiro_codigo']                  = addslashes(trim($read_financeiro_view['Id']));
            $CreateFinanceiro['financeiro_tipo']                    = addslashes(trim('CR'));
            $CreateFinanceiro['financeiro_data_lancamento']         = addslashes(trim(date('Y-m-d')));
            $CreateFinanceiro['financeiro_data_vencimento']         = addslashes(trim($read_financeiro_view['DataVencimento']));
            $CreateFinanceiro['financeiro_valor']                   = addslashes(trim($read_financeiro_view['Valor']));
            $CreateFinanceiro['financeiro_id_plano_conta']          = addslashes(trim('0'));
            $CreateFinanceiro['financeiro_id_tipo_documento']       = addslashes(trim($read_financeiro_view['IdTipoDocumento']));
            $CreateFinanceiro['financeiro_descricao']               = addslashes(trim($read_financeiro_view['Descricao']));
            $CreateFinanceiro['financeiro_valor_pagamento']         = addslashes(trim($read_financeiro_view['ValorPagamento']));
            $CreateFinanceiro['financeiro_data_pagamento']          = addslashes(trim($read_financeiro_view['DataPagamento']));
            $CreateFinanceiro['financeiro_data_baixa']              = addslashes(trim($read_financeiro_view['DataBaixa']));
            $CreateFinanceiro['financeiro_obs']                     = addslashes(trim($read_financeiro_view['Obs']));
            $CreateFinanceiro['financeiro_id_contato']              = addslashes(trim($read_financeiro_view['IdCliente']));
            $CreateFinanceiro['financeiro_md5']                     = addslashes(trim(md5(date('Y-m-dH:i:s').rand(9,9999999999999999))));
            $CreateFinanceiro['financeiro_fixo']                    = addslashes(trim('0'));
            $CreateFinanceiro['financeiro_app_financeira']          = addslashes(trim('0'));
            $CreateFinanceiro['financeiro_status']                  = addslashes(trim($read_financeiro_view['Status']));
            $CreateFinanceiro['financeiro_numero_doc']              = addslashes(trim($read_financeiro_view['NumeroDocumento']));
            $CreateFinanceiro['financeiro_referencia_faturamento']  = addslashes(trim($read_financeiro_view['MesAnoFat']));
            $CreateFinanceiro['financeiro_nosso_numero_ult']        = addslashes(trim($read_financeiro_view['NossoNumero']));
            $CreateFinanceiro['financeiro_id_vendedor']             = addslashes(trim($read_financeiro_view['IdFranquiado']));
            Create('financeiro', $CreateFinanceiro);
        }
    }*/
?>