<?php

require_once '_class/Ferramenta.php';


/*$read_faturamento = ReadComposta("SELECT faturamento.faturamento_id_contato, contato.contato_email FROM faturamento INNER JOIN contato ON contato.contato_id = faturamento.faturamento_id_contato WHERE faturamento.faturamento_referencia = '09/2017'");
if (NumQuery($read_faturamento) > '0') {
    foreach ($read_faturamento as $read_faturamento_view) {
        
    }
}*/
/*$assunto_mail = GetDados('msg_financeiro', '3', 'msg_financeiro_id', 'msg_financeiro_assunto');
$msg_financeiro_texto = GetDados('msg_financeiro', '3', 'msg_financeiro_id', 'msg_financeiro_texto');
$MSG_1 = str_replace('#NOMEEMPRESA#', GetEmpresa('empresa_nome_razao'), MSGEMAIL);
$MSG_2 = str_replace('#IMGEMPRESA#', GetEmpresa('empresa_logo'), $MSG_1);
$MSG_3 = str_replace('#TITULOMAIL#', $assunto_mail, $MSG_2);
$MSG_4 = str_replace('#MSGMAIL#', $msg_financeiro_texto, $MSG_3);
$MSG_5 = str_replace('#MAILEMPRESA#', GetEmpresa('empresa_email'), $MSG_4);
$MSG_6 = str_replace('#FONEEMPRESA#', GetEmpresa('empresa_telefone'), $MSG_5);
$MSG_7 = str_replace('#LINKBOLETO#', '<a href="http://federalsistemas.com.br/boleto_online/" target="_blank">Clique Aqui</a>', $MSG_6);*/
$inicio_dados_correto = $_GET['inicio_dados'];
$dados_refresh = $inicio_dados_correto + 5;
echo '<meta http-equiv="refresh" content="2;url=envio-mail.php?inicio_dados='.$dados_refresh.'">';

$inicio_dados = $inicio_dados_correto;

$read_contato_m2m = ReadComposta("SELECT * FROM financeiro INNER JOIN contato ON contato_id = financeiro_id_contato WHERE financeiro_id_tipo_documento = '5' GROUP BY financeiro_id_contato LIMIT $inicio_dados,5");
if(NumQuery($read_contato_m2m) > '0'):
	foreach($read_contato_m2m as $read_pedido_envio_mail_view):
		$MailBody = "
			<p style='font-size: 1.4em;'>Prezado(a) " . $read_pedido_envio_mail_view['contato_nome_razao'] . ",</p>
			<p>Em nome do Grupo Federal, veio por meio deste, informar a nova mudança quanto à aquisição de Chips M2M (Machine to Machine). A partir do dia 01/10 fica acordado, que será faturado ao contratante, o valor de R$ 2,00 pelo SIMCARD. Valor este que será taxado somente 1 (uma) vez, na próxima fatura subsequente, juntamente ao faturamento sobre o serviço de transmissão de dados. </p>
			<p>Deste modo, ocorrerá uma mudança contratual, em relação ao CANCELAMENTO, fixado no item 7.3 do atual contrato. Anteriormente, na tratativa de cancelamento de Chips M2M, cobrava-se R$ 15,00 pelo SIMCARD, caso o contratante não fizesse a devolução do mesmo. A partir desta alteração, quanto a aquisição dos Chips, o valor de R$ 15,00 a ser faturado no ato do cancelamento será isento. </p>
			<p>O departamento comercial se coloca à disposição de todos para sanar quaisquer dúvidas! </p>
			
			<p>Agradecemos a compreensão de todos, </p>
			<p>...</p>
			<p><em>Grupo Federal!</em></p>
		";
		$MailContent = '
			<table width="550" style="font-family: "Trebuchet MS", sans-serif;">
			 <tr><td>
			  <font face="Trebuchet MS" size="3">
			   ' . $MailBody . '
			  </font>
			  </td></tr>
			</table>
			<style>body, img{max-width: 550px !important; height: auto !important;} p{margin-botton: 15px 0 !important;}</style>';
		if (valMail($read_pedido_envio_mail_view['contato_email'])) {

		}
		$retorno = sendMailCampanha("AVISO FEDERAL", $MailContent, GetEmpresa('empresa_email'), GetEmpresa('empresa_nome_fantasia'), $read_pedido_envio_mail_view['contato_email'], $read_pedido_envio_mail_view['contato_nome_razao'], 'financeiro2@federalsistemas.com.br', 'Sarah');
	endforeach;
endif;
/*
$MailBody = "
			<p style='font-size: 1.4em;'>Prezado(a) " . $read_pedido_envio_mail_view['contato_nome_razao'] . ",</p>
			<p>Em nome do Grupo Federal, veio por meio deste, informar a nova mudança quanto à aquisição de Chips M2M (Machine to Machine). A partir do dia 01/10 fica acordado, que será faturado ao contratante, o valor de R$ 2,00 pelo SIMCARD. Valor este que será taxado somente 1 (uma) vez, na próxima fatura subsequente, juntamente ao faturamento sobre o serviço de transmissão de dados. </p>
			<p>Deste modo, ocorrerá uma mudança contratual, em relação ao CANCELAMENTO, fixado no item 7.3 do atual contrato. Anteriormente, na tratativa de cancelamento de Chips M2M, cobrava-se R$ 15,00 pelo SIMCARD, caso o contratante não fizesse a devolução do mesmo. A partir desta alteração, quanto a aquisição dos Chips, o valor de R$ 15,00 a ser faturado no ato do cancelamento será isento. </p>
			<p>O departamento comercial se coloca à disposição de todos para sanar quaisquer dúvidas! </p>
			
			<p>Agradecemos a compreensão de todos, </p>
			<p>...</p>
			<p><em>Grupo Federal!</em></p>
		";
		$MailContent = '
			<table width="550" style="font-family: "Trebuchet MS", sans-serif;">
			 <tr><td>
			  <font face="Trebuchet MS" size="3">
			   ' . $MailBody . '
			  </font>
			  </td></tr>
			</table>
			<style>body, img{max-width: 550px !important; height: auto !important;} p{margin-botton: 15px 0 !important;}</style>';
		if (valMail($read_pedido_envio_mail_view['contato_email'])) {

		}
		$retorno = sendMailCampanha("AVISO FEDERAL", $MailContent, GetEmpresa('empresa_email'), GetEmpresa('empresa_nome_fantasia'), 'junioralphasistemas@gmail.com', 'Teste', 'financeiro2@federalsistemas.com.br', 'Sarah');
		*/