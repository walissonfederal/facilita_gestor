<?php
session_start();
ob_start();
require_once '../_class/Ferramenta.php';

$limit_dados = $_GET['inicio'];
$retorno = $_GET['retorno'];

$dados_refresh = $limit_dados + 10;
echo '<meta http-equiv="refresh" content="2;url=gerar_retorno_normal.php?inicio='.$dados_refresh.'&retorno='.$retorno.'">';

$readReport = Read('baixa', "WHERE baixa_id_baixa_retorno = '".$retorno."' AND baixa_valor_pago > '0' AND baixa_nosso_numero LIKE '%1400000000%' LIMIT $limit_dados, 10");
if(NumQuery($readReport) > '0'){
	foreach($readReport as $readReportView){
		$NossoNumero = $readReportView['baixa_nosso_numero'];
		$readFinanceiro = Read('financeiro', "WHERE financeiro_status = '0' AND financeiro_nosso_numero = '".$NossoNumero."' OR financeiro_nosso_numero_ult = '".$NossoNumero."'");
		if($readFinanceiro){
			foreach($readFinanceiro as $readFinanceiroView);
			
			$email_cliente_baixa = GetDados('contato', $readFinanceiroView['financeiro_id_contato'], 'contato_id', 'contato_email');
			$nome_cliente_baixa = GetDados('contato', $readFinanceiroView['financeiro_id_contato'], 'contato_id', 'contato_nome_razao');
			if(valMail($email_cliente_baixa)){
				$msg_mail = "Olá ".$nome_cliente_baixa." tudo bem?<br />Estou passando aqui para te agradecer pelo pagamento do boleto no valor de R$".$readReportView['baixa_valor_pago'].", agradecemos pela parceria de sempre.";
				$MSG_1 = str_replace('#NOMEEMPRESA#', GetEmpresa('empresa_nome_razao'), MSGEMAIL);
				$MSG_2 = str_replace('#IMGEMPRESA#', GetEmpresa('empresa_logo'), $MSG_1);
				$MSG_3 = str_replace('#TITULOMAIL#', 'Confirmação de pagamento', $MSG_2);
				$MSG_4 = str_replace('#MSGMAIL#', $msg_mail, $MSG_3);
				$MSG_5 = str_replace('#MAILEMPRESA#', GetEmpresa('empresa_email'), $MSG_4);
				$MSG_6 = str_replace('#FONEEMPRESA#', GetEmpresa('empresa_telefone'), $MSG_5);
				$MSG_7 = str_replace('#LINKBOLETO#', '', $MSG_6);
				//sendMailCampanha('Pagamento confirmado', $MSG_7, GetEmpresa('empresa_email'), GetEmpresa('empresa_nome_fantasia'), $email_cliente_baixa, $nome_cliente_baixa);
			}
			
			$FinanceiroBaixa['financeiro_status']           = '1';
			$FinanceiroBaixa['financeiro_data_pagamento']   = FormatEUA($readReportView['baixa_data_credito']);
			$FinanceiroBaixa['financeiro_data_baixa']       = date('Y-m-d');
			$FinanceiroBaixa['financeiro_valor_pagamento']  = $readReportView['baixa_valor_pago'];
			Update('financeiro', $FinanceiroBaixa, "WHERE financeiro_nosso_numero = '".$NossoNumero."' OR financeiro_nosso_numero_ult = '".$NossoNumero."' LIMIT 1");

			$CaixaContaCreate['caixa_conta_id_financeiro']	= $readFinanceiroView['financeiro_id'];
			$CaixaContaCreate['caixa_conta_data_lancamento']	= FormatEUA($readReportView['baixa_data_credito']);
			$CaixaContaCreate['caixa_conta_valor_lancamento']   = $readReportView['baixa_valor_pago'];
			$CaixaContaCreate['caixa_conta_numero_doc']		= $readFinanceiroView['financeiro_numero_doc'];
			$CaixaContaCreate['caixa_conta_tipo_lancamento']	= 'C';
			$CaixaContaCreate['caixa_conta_descricao']		= $readFinanceiroView['financeiro_descricao'];
			$CaixaContaCreate['caixa_conta_id_caixa']		= $_SESSION['retorno_id_caixa'];
			Create('caixa_conta', $CaixaContaCreate);
		}
	}
}
echo 'OK';