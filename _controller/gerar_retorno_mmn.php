<?php
session_start();
ob_start();
require_once '../_class_mmn/Ferramenta.php';

$limit_dados = $_GET['inicio'];
$retorno = $_GET['retorno'];

$dados_refresh = $limit_dados + 10;
echo '<meta http-equiv="refresh" content="2;url=gerar_retorno_mmn.php?inicio='.$dados_refresh.'&retorno='.$retorno.'">';

$readReport = Read('baixa', "WHERE baixa_id_baixa_retorno = '".$retorno."' AND baixa_valor_pago > '0' AND baixa_nosso_numero LIKE '%140000001%' LIMIT $limit_dados, 10");
if(NumQuery($readReport) > '0'){
	foreach($readReport as $readReportView){
		$NossoNumero = $readReportView['baixa_nosso_numero'];
		$readFinanceiro = Read('pedido', "WHERE pedido_status = '0' AND pedido_nosso_numero = '".$NossoNumero."'");
		if(NumQuery($readFinanceiro) > '0'){
			foreach($readFinanceiro as $readFinanceiroView);
			
			
			if($readFinanceiroView['pedido_valor'] > $readReportView['baixa_valor_pago']){
				$BaixaUpdate['baixa_obs'] = 'Baixa não autorizada: valor menor';
			}else{
				$FinanceiroBaixa['pedido_status']           = '1';
				$FinanceiroBaixa['pedido_data_pagamento']   = FormatEUA($readReportView['baixa_data_credito']);
				$FinanceiroBaixa['pedido_valor_pagamento']  = $readReportView['baixa_valor_pago'];
				if(Update('pedido', $FinanceiroBaixa, "WHERE pedido_nosso_numero = '".$NossoNumero."' LIMIT 1")):
					GerarComissao($readFinanceiroView['pedido_id_user'], $readFinanceiroView['pedido_valor']);
				endif;
				
				if($readFinanceiroView['pedido_tipologia'] == '1'){
					$user_update_pedido['user_status'] = '1';
					$user_update_pedido['user_data_ativacao'] = date('Y-m-d');
					Update('user', $user_update_pedido, "WHERE user_id = '".$readFinanceiroView['pedido_id_user']."' AND user_status = '0'");
				}
			}
		}
	}
}

echo 'OK';

?>