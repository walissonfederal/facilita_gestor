<?php

session_start();
ob_start();

require_once '_class_caixa/AutoLoad.php';


define('SITE_ADDR_EMAIL', 'cobranca@federalsistemas.com.br');
define('SITE_NAME', 'FEDERALNETMÓVEL');
define('SITE_ADDR_PHONE_A', '(62) 3353-4350');
define('SITE_ADDR_NAME', 'Rua 6, número 220, Bairro Bouganville - Goianésia (GO)');



$DB = new Database();
$FM = new Format();
$FE = new Ferraments();

$INT = new Integra();


$inicio_dados_correto = $_GET['inicio_dados'];
$dados_refresh = $inicio_dados_correto + 5;
if ($dados_refresh < '10500'):
    echo '<meta http-equiv="refresh" content="2;url=dda_fed.php?inicio_dados=0">';
endif;


    
$read_adesao = $DB->ReadComposta("SELECT * FROM financeiro INNER JOIN contato ON contato_id = financeiro_id_contato WHERE financeiro_status IN(1,2) AND financeiro_dda_fed IS NULL AND financeiro_tipo = 'CR' ORDER BY financeiro_id DESC LIMIT $inicio_dados_correto,5");
if($DB->NumQuery($read_adesao) > '0'):
    foreach($read_adesao as $read_adesao_view):
		$pedido_id = addslashes(trim(strip_tags($read_adesao_view['financeiro_id'])));
        if($read_adesao_view['financeiro_status'] == '0'):
            $status_adesao = 'ABERTO';
            $status_adesao_style = '<span class="badge badge-warning">ABERTO</span>';
        elseif($read_adesao_view['financeiro_status'] == '1'):
            $status_adesao = 'PAGO';
            $status_adesao_style = '<span class="badge badge-success">PAGO</span>';
        else:
            $status_adesao = 'CANCELADO';
            $status_adesao_style = '<span class="badge badge-danger">CANCELADO</span>';
        endif;

        $NossoNumeroPedido = $FE->GeraNossoNumero($read_adesao_view['financeiro_codigo']);
        $NossoNumeroCompleto = $FE->GeraNossoNumeroCompleto($read_adesao_view['financeiro_codigo']);
		$ConsultarBoleto = $INT->ConsultaBoletoCaixa($NossoNumeroPedido);
		echo '<pre>';
		print_r($ConsultarBoleto);

		if ($ConsultarBoleto['CONTROLE_NEGOCIAL']['COD_RETORNO'] == '0' && $ConsultarBoleto['CONTROLE_NEGOCIAL']['MENSAGENS']['RETORNO'] == '(0) OPERACAO EFETUADA - SITUACAO DO TITULO = EM ABERTO'):
			$BaixaBoleto = $INT->BaixaBoletoCaixa($FE->GeraNossoNumero($read_adesao_view['financeiro_codigo']));
			if ($BaixaBoleto['CONTROLE_NEGOCIAL']['COD_RETORNO'] == '0' && $BaixaBoleto['CONTROLE_NEGOCIAL']['MENSAGENS']['RETORNO'] == '(0) OPERACAO EFETUADA'):
				$update_pedido_nosso_form['financeiro_dda_fed'] = '1';
				$DB->Update('financeiro', $update_pedido_nosso_form, "WHERE financeiro_id_contato = '".$read_adesao_view['financeiro_id_contato']."' AND financeiro_id = '".$pedido_id."'");
				$jSON['msg'] = "Operação realizada com sucesso!";
				$jSON['type'] = 'ok';
				$jSON['title'] = 'Parabéns';
			else:
				$jSON['msg'] = 'Não consegui baixar o boleto primeiro no banco, não posso continuar a operação!';
				$jSON['type'] = 'error';
				$jSON['title'] = 'Erro';
			endif;
			echo '<pre>';
			print_r($jSON);
		elseif($ConsultarBoleto['CONTROLE_NEGOCIAL']['COD_RETORNO'] == '0' && $ConsultarBoleto['CONTROLE_NEGOCIAL']['MENSAGENS']['RETORNO'] == '(0) OPERACAO EFETUADA - SITUACAO DO TITULO = LIQUIDADO'):
			$update_pedido_nosso_form['financeiro_dda_fed'] = '1';
			$DB->Update('financeiro', $update_pedido_nosso_form, "WHERE financeiro_id_contato = '".$read_adesao_view['financeiro_id_contato']."' AND financeiro_id = '".$pedido_id."'");
		elseif($ConsultarBoleto['CONTROLE_NEGOCIAL']['COD_RETORNO'] == '1' && $ConsultarBoleto['CONTROLE_NEGOCIAL']['MENSAGENS']['RETORNO'] == '(47) NOSSO NUMERO NAO CADASTRADO PARA O BENEFICIARIO'):
			$update_pedido_nosso_form['financeiro_dda_fed'] = '1';
			$DB->Update('financeiro', $update_pedido_nosso_form, "WHERE financeiro_id_contato = '".$read_adesao_view['financeiro_id_contato']."' AND financeiro_id = '".$pedido_id."'");
		elseif($ConsultarBoleto['CONTROLE_NEGOCIAL']['COD_RETORNO'] == '0' && $ConsultarBoleto['CONTROLE_NEGOCIAL']['MENSAGENS']['RETORNO'] == '(0) OPERACAO EFETUADA - SITUACAO DO TITULO = BAIXA POR DEVOLUCAO'):
			$update_pedido_nosso_form['financeiro_dda_fed'] = '1';
			$DB->Update('financeiro', $update_pedido_nosso_form, "WHERE financeiro_id_contato = '".$read_adesao_view['financeiro_id_contato']."' AND financeiro_id = '".$pedido_id."'");		
		else:
			$update_pedido_nosso_form['pedido_dda_fed'] = '2';
			$DB->Update('financeiro', $update_pedido_nosso_form, "WHERE financeiro_id_contato = '".$read_adesao_view['financeiro_id_contato']."' AND financeiro_id = '".$pedido_id."'");
		endif;
    endforeach;
else:
    echo 'okok';
endif;