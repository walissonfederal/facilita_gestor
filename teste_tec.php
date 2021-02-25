<?php
    session_start();
    ob_start();
    require_once '_class/Ferramenta.php';
    function remover_acento_sistema($str){
        $map = array(
            'á' => 'a',
            'à' => 'a',
            'ã' => 'a',
            'â' => 'a',
            'é' => 'e',
            'ê' => 'e',
            'í' => 'i',
            'ó' => 'o',
            'ô' => 'o',
            'õ' => 'o',
            'ú' => 'u',
            'ü' => 'u',
            'ç' => 'c',
            'Á' => 'A',
            'À' => 'A',
            'Ã' => 'A',
            'Â' => 'A',
            'É' => 'E',
            'Ê' => 'E',
            'Í' => 'I',
            'Ó' => 'O',
            'Ô' => 'O',
            'Õ' => 'O',
            'Ú' => 'U',
            'Ü' => 'U',
            'Ç' => 'C'
        );
        return strtr($str, $map);
    }
	/*
	$inicio_dados_correto = $_GET['inicio_dados'];
	$dados_refresh = $inicio_dados_correto + 10;
	//echo '<meta http-equiv="refresh" content="10;url=teste_tec.php?inicio_dados='.$dados_refresh.'">';
	
	$inicio_dados = $inicio_dados_correto;
	
	$read_faturamento = Read('faturamento', "WHERE faturamento_referencia = '08/2018' AND faturamento_id = '14252'");
	if(NumQuery($read_faturamento) > '0'):
		foreach($read_faturamento as $read_ultimo_faturamento_view):
			if(return_valor_total_faturamento($read_ultimo_faturamento_view['faturamento_id']) > '0'){
                $read_financeiro = ReadComposta("SELECT financeiro_id, financeiro_status FROM financeiro WHERE financeiro_referencia_faturamento = '".$read_ultimo_faturamento_view['faturamento_referencia']."' AND financeiro_id_contato = '".$read_ultimo_faturamento_view['faturamento_id_contato']."'");
                if(NumQuery($read_financeiro) > '0'){
                    foreach($read_financeiro as $read_financeiro_view);
                    if($read_financeiro_view['financeiro_status'] == '0'){
                        $financeiro_form['financeiro_valor'] = return_valor_total_faturamento($read_ultimo_faturamento_view['faturamento_id']);
                        Update('financeiro', $financeiro_form, "WHERE financeiro_id = '".$read_financeiro_view['financeiro_id']."'");
                    }
                }else{
                    $financeiro_form['financeiro_codigo'] = GetReg('financeiro', 'financeiro_codigo', "WHERE financeiro_tipo = 'CR'") + 1;
                    $financeiro_form['financeiro_tipo'] = 'CR';
                    $financeiro_form['financeiro_data_lancamento'] = date('Y-m-d');
                    $financeiro_form['financeiro_valor'] = return_valor_total_faturamento($read_ultimo_faturamento_view['faturamento_id']);
                    $financeiro_form['financeiro_obs'] = 'FATURAMENTO 08/2018';
					$financeiro_form['financeiro_descricao'] = 'FATURAMENTO 08/2018';
					$financeiro_form['financeiro_id_plano_conta'] = '8';
					$financeiro_form['financeiro_id_tipo_documento'] = '5';
					$financeiro_form['financeiro_data_vencimento'] = '2018-09-10';
                    $financeiro_form['financeiro_id_contato'] = $read_ultimo_faturamento_view['faturamento_id_contato'];
                    $financeiro_form['financeiro_md5'] = md5(date('Y-m-dH:i:s').rand(9,9999999999999999999999999999));
                    $financeiro_form['financeiro_fixo'] = '0';
                    $financeiro_form['financeiro_app_financeira'] = '0';
                    $financeiro_form['financeiro_status'] = '0';
                    $financeiro_form['financeiro_numero_doc'] = rand(10000,99999);
                    $financeiro_form['financeiro_referencia_faturamento'] = '08/2018';
                    Create('financeiro', $financeiro_form);
                }
                $assunto_mail = GetDados('msg_financeiro', '3', 'msg_financeiro_id', 'msg_financeiro_assunto');
                $msg_financeiro_texto = GetDados('msg_financeiro', '3', 'msg_financeiro_id', 'msg_financeiro_texto');
                $MSG_1 = str_replace('#NOMEEMPRESA#', GetEmpresa('empresa_nome_razao'), MSGEMAIL);
                $MSG_2 = str_replace('#IMGEMPRESA#', GetEmpresa('empresa_logo'), $MSG_1);
                $MSG_3 = str_replace('#TITULOMAIL#', $assunto_mail, $MSG_2);
                $MSG_4 = str_replace('#MSGMAIL#', $msg_financeiro_texto, $MSG_3);
                $MSG_5 = str_replace('#MAILEMPRESA#', GetEmpresa('empresa_email'), $MSG_4);
                $MSG_6 = str_replace('#FONEEMPRESA#', GetEmpresa('empresa_telefone'), $MSG_5);
                $MSG_7 = str_replace('#LINKBOLETO#', '<a href="http://federalsistemas.com.br/boleto_online/" target="_blank">Clique Aqui</a>', $MSG_6);
                $read_contato = Read('contato', "WHERE contato_id = '".$read_ultimo_faturamento_view['faturamento_id_contato']."'");
                if(NumQuery($read_contato) > '0'){
                    foreach($read_contato as $read_contato_view);
                    $email_cliente = strtolower($read_contato_view['contato_email']);
                    $nome_cliente = $read_contato_view['contato_nome_razao'];
                }else{
                    $email_cliente = '';
                    $nome_cliente = '';
                }
                if(valMail($email_cliente)){
                    $retorno = sendMailCampanha($assunto_mail, $MSG_7, GetEmpresa('empresa_email'), GetEmpresa('empresa_nome_fantasia'), $email_cliente, $nome_cliente);
                }
            }
		endforeach;
	endif;
	*/
    
	
	
    $read_contato = Read('contato', "WHERE contato_status = '0' ORDER BY contato_id DESC");
    if(NumQuery($read_contato)):
        foreach($read_contato as $read_contato_view):
            $todos_contatos .= $read_contato_view['contato_id'].',';
        endforeach;
    endif;
    
    
    $contatos_id = substr($todos_contatos, 0,-1);
	
    $read_financeiro_valido = Read('financeiro', "WHERE financeiro_id_tipo_documento != '5' AND financeiro_id_contato IN(".$contatos_id.") AND financeiro_data_vencimento > '2020-07-30' AND financeiro_status IN(0,1) GROUP BY financeiro_id_contato ORDER BY financeiro_id_contato ASC");
    if(NumQuery($read_financeiro_valido) > '0'):
        foreach($read_financeiro_valido as $read_financeiro_valido_view):
            $in_contato_boleto .= $read_financeiro_valido_view['financeiro_id_contato'].',';
        endforeach;
    endif;
	
	$read_financeiro_valido = Read('financeiro', "WHERE financeiro_id_tipo_documento != '5' AND financeiro_id_contato IN(".$contatos_id.") GROUP BY financeiro_id_contato");
    if(NumQuery($read_financeiro_valido) > '0'):
        foreach($read_financeiro_valido as $read_financeiro_valido_view):
            $in_contato_boleto_ja_boleto .= $read_financeiro_valido_view['financeiro_id_contato'].',';
        endforeach;
    endif;
	
	$ja_boleto = substr($in_contato_boleto_ja_boleto, 0,-1);
	$read_contato_dados = Read('contato', "WHERE contato_id NOT IN(".$ja_boleto.")");
    if(NumQuery($read_contato_dados) > '0'):
        foreach($read_contato_dados as $read_contato_dados_view):
            $in_contato_boleto_ja_boleto_nao .= $read_contato_dados_view['contato_id'].',';
        endforeach;
    endif;
	$ja_boleto_nao = substr($in_contato_boleto_ja_boleto_nao, 0,-1);
	//echo $ja_boleto_nao; die();
    
    $validar_in_contato_boleto = substr($in_contato_boleto, 0,-1);
    
    $read_financeiro_boleto_chip = Read('financeiro', "WHERE financeiro_referencia_faturamento IN('01/2018', '02/2018', '03/2018', '04/2018', '05/2018', '06/2018', '07/2018', '08/2018', '09/2018', '10/2018', '11/2018', '12/2018', '01/2019', '02/2019', '03/2019', '04/2019', '05/2019', '06/2019', '07/2019', '08/2019', '09/2019', '10/2019', '11/2019', '12/2019', '01/2020', '02/2020', '03/2020', '04/2020, '05/2020', '06/2020, '07/2020', '08/2020') GROUP BY financeiro_id_contato");
    if(NumQuery($read_financeiro_boleto_chip) > '0'):
        foreach($read_financeiro_boleto_chip as $read_financeiro_boleto_chip_view):
            $in_contato_boleto_chip .= $read_financeiro_boleto_chip_view['financeiro_id_contato'].',';
        endforeach;
    endif;
    
    $validar_in_contato_boleto_chip = substr($in_contato_boleto_chip, 0,-1);
    
    
    $validar_in_contato = $validar_in_contato_boleto.','.$validar_in_contato_boleto_chip.','.$ja_boleto_nao;
    echo '<h2>Clientes sem boleto</h2>';
    $read_contato_boleto = ReadComposta("SELECT contato_id, contato_nome_razao, contato_nome_fantasia FROM contato WHERE contato_id IN(".$validar_in_contato.") AND contato_status = '0' ORDER BY contato_nome_razao ASC");
    if(NumQuery($read_contato_boleto) > '0'):
        echo '<p>Eu encontrei <strong>'.  NumQuery($read_contato_boleto) .'</strong> clientes sem boleto<hr>';
        foreach($read_contato_boleto as $read_contato_boleto_view):
            echo '<pre>';
                print_r($read_contato_boleto_view);
            echo '</pre>';
        endforeach;
    else:
        echo '<h3>Não encontrei nenhum cliente sem boleto! :)</h3>';
    endif;
    