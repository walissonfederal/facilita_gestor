<?php
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
	
	
	/*mexer apenas aqui
	LEMBRANDO QUE A REFERENCIA TEM QUE SER DO MES REAL DO CICLO 
	EXEMPLO FATURAMENTO DO MES 01/05/2020 AO 30/05/2020 SERIA 05/2020.
	*/
	$referencia_faturamento = '06/2020';
	$data_vencimento_boleto = '2020-07-15';
	/*fim mexer*/
	
	/*
		ESSE ARQUIVO AQUI GERA APENAS O FINANCEIRO, NÃO EXECUTE ELE SEM TER GERADO O FATURAMENTO QUE ESTÁ NO ARQUIVO cadfaturamentoteste.php, SE EXECUTA-LO 
		SEM TER O FATURAMENTO GERADO O MESMO NÃO VAI GERAR O FINANCEIROOK?
		
		http://federals.gagarin1965.hospedagemdesites.ws/sis/.com.br/facilita_gestor/gera_fat.php?inicio_dados=0
	*/
	
	
	
	$inicio_dados_correto = $_GET['inicio_dados'];
	$dados_refresh = $inicio_dados_correto + 1;
	echo '<meta http-equiv="refresh" content="2;url=gera_fat.php?inicio_dados='.$dados_refresh.'">';
	
	$inicio_dados = $inicio_dados_correto;
	
	$read_faturamento = Read('faturamento', "WHERE faturamento_referencia = '".$referencia_faturamento."' AND faturamento_id_contato IN (
3274,
3405,
4245,
4795,
4942
) LIMIT $inicio_dados,1");
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
                    $financeiro_form['financeiro_obs'] = 'FATURAMENTO '.$referencia_faturamento;
					$financeiro_form['financeiro_descricao'] = 'FATURAMENTO '.$referencia_faturamento;
					$financeiro_form['financeiro_id_plano_conta'] = '8';
					$financeiro_form['financeiro_id_tipo_documento'] = '5';
					$financeiro_form['financeiro_data_vencimento'] = $data_vencimento_boleto;
                    $financeiro_form['financeiro_id_contato'] = $read_ultimo_faturamento_view['faturamento_id_contato'];
                    $financeiro_form['financeiro_md5'] = md5(date('Y-m-dH:i:s').rand(100000,999999));
                    $financeiro_form['financeiro_fixo'] = '0';
                    $financeiro_form['financeiro_app_financeira'] = '0';
                    $financeiro_form['financeiro_status'] = '0';
                    $financeiro_form['financeiro_numero_doc'] = rand(10000,99999);
                    $financeiro_form['financeiro_referencia_faturamento'] = $referencia_faturamento;
                    Create('financeiro', $financeiro_form);
					
					$assunto_mail = GetDados('msg_financeiro', '3', 'msg_financeiro_id', 'msg_financeiro_assunto');
					$msg_financeiro_texto = GetDados('msg_financeiro', '3', 'msg_financeiro_id', 'msg_financeiro_texto');
					$MSG_1 = str_replace('#NOMEEMPRESA#', GetEmpresa('empresa_nome_razao'), MSGEMAIL);
					$MSG_2 = str_replace('#IMGEMPRESA#', GetEmpresa('empresa_logo'), $MSG_1);
					$MSG_3 = str_replace('#TITULOMAIL#', $assunto_mail, $MSG_2);
					$MSG_4 = str_replace('#MSGMAIL#', $msg_financeiro_texto, $MSG_3);
					$MSG_5 = str_replace('#MAILEMPRESA#', GetEmpresa('empresa_email'), $MSG_4);
					$MSG_6 = str_replace('#FONEEMPRESA#', GetEmpresa('empresa_telefone'), $MSG_5);
					$MSG_7 = str_replace('#LINKBOLETO#', '<a href="https://boletos.federalsistemas.com.br/" target="_blank">Clique Aqui</a><hr /><strong>Aguarde até 1hora depois de receber esse email para pagar o boleto</strong>', $MSG_6);
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
						//$retorno = sendMailCampanha('Seu faturamento está disponível', $MSG_7, GetEmpresa('empresa_email'), GetEmpresa('empresa_nome_fantasia'), $email_cliente, $nome_cliente);
					}
                }
				echo '<pre>';
				print_r($financeiro_form);
                
            }
		endforeach;
		echo 'aqui';
	endif;