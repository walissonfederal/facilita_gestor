<?php
session_start();
ob_start();
require_once '../_class/Ferramenta.php';
if(!isset($_SESSION[VSESSION])){
    header("Location: ../index.php");
}

$ide = addslashes($_GET['ide']);
if($ide == '00'){
    $get_url = addslashes(base64_encode($_GET['03']));
    $get_juros = addslashes($_GET['04']);
    $get_data_vencimento = addslashes($_GET['01']);
    $url = 'boleto_cef.php?00='.base64_encode('boleto_cef').'&01='.base64_encode('sigcb').'&02='.base64_encode('1').'&03='.$get_url.'&juros='.$get_juros.'&data_vencimento='.$get_data_vencimento.'&segunda_via=true';
    header("Location: $url");
}

$get_boleto = addslashes(base64_decode($_GET['00']));
$get_tipo_boleto  = addslashes(base64_decode($_GET['01']));
$get_conta_boleto  = addslashes(base64_decode($_GET['02']));
$get_id_contas  = addslashes(base64_decode($_GET['03']));
//echo $get_id_contas;

//echo $get_id_contas;

$readContaBoleto = Read('boleto', "WHERE boleto_id = '".$get_conta_boleto."'");
if(NumQuery($readContaBoleto) > '0'){
    foreach($readContaBoleto as $readContaBoletoView);
}
$readBoleto = Read('financeiro', "WHERE financeiro_id IN($get_id_contas) AND financeiro_status = '0' AND financeiro_tipo = 'CR' ORDER BY financeiro_data_vencimento ASC");
include("funcoes_cef.php"); 
if(NumQuery($readBoleto) == '0'){
    header("Location: ../Home.php?model=home&pg=home");
}else{
    foreach($readBoleto as $readBoletoView){

// ------------------------- DADOS DINÂMICOS DO SEU CLIENTE PARA A GERAÇÃO DO BOLETO (FIXO OU VIA GET) -------------------- //
// Os valores abaixo podem ser colocados manualmente ou ajustados p/ formulário c/ POST, GET ou de BD (MySql,Postgre,etc)	//

// DADOS DO BOLETO PARA O SEU CLIENTE
$dias_de_prazo_para_pagamento = 0;
$taxa_boleto = 0;


//CALCULO DOS JUROS
if($_GET['segunda_via'] == 'true'){
    $ValorMulta = '0';
    $ValorJuros = '0';
    $ValorAtualizado = '0';
    if(isset($_GET['juros']) && $_GET['data_vencimento'] == ''){
        $NovaDtVenc = date('Y-m-d');
        if(strtotime($readBoletoView['financeiro_data_vencimento']) < strtotime(date('Y-m-d'))){
            $IntervadoVencido = strtotime($NovaDtVenc) - strtotime($readBoletoView['financeiro_data_vencimento']);
            //$DiasIntervalo = DiferencaDias(FormDataBr($read_financeiro_paginator_view['financeiro_data_vencimento']), FormDataBr($NovaDtVenc));
            $DiasIntervalo = floor($IntervadoVencido / (60 * 60 * 24));
            //echo $DiasIntervalo.'<br />';
            $ValorContaOriginal = $readBoletoView['financeiro_valor'];
            $ValorMulta = (MULTA / 100) * $ValorContaOriginal;
            $ValorJuros = ((JUROS * $ValorContaOriginal) / 100) * $DiasIntervalo;
            $ValorJurosDia = ((JUROS * $ValorContaOriginal) / 100);
            $ValorMultaDia = (MULTA / 100) * $ValorContaOriginal;
            $ValorAtualizado = $ValorContaOriginal + $ValorMulta + $ValorJuros;
            $DataVenc = FormDataBr($NovaDtVenc);
            if($_GET['juros'] == '1'){
                $ValorMulta = '0';
                $ValorJuros = '0';
                $ValorAtualizado = $readBoletoView['financeiro_valor'];
            }
        }else{
            $ValorAtualizado = $readBoletoView['financeiro_valor'];
            $DataVenc = FormDataBr($readBoletoView['financeiro_data_vencimento']);
            $ValorJuros = '0';
            $ValorMulta = '0';
        }
    }else{
        $NovaDtVenc = $_GET['data_vencimento'];
        if(strtotime($readBoletoView['financeiro_data_vencimento']) < strtotime(date('Y-m-d'))){
            $IntervadoVencido = strtotime($NovaDtVenc) - strtotime($readBoletoView['financeiro_data_vencimento']);
            //$DiasIntervalo = DiferencaDias(FormDataBr($read_financeiro_paginator_view['financeiro_data_vencimento']), FormDataBr($NovaDtVenc));
            $DiasIntervalo = floor($IntervadoVencido / (60 * 60 * 24));
            //echo $DiasIntervalo.'<br />';
            $ValorContaOriginal = $readBoletoView['financeiro_valor'];
            $ValorMulta = (MULTA / 100) * $ValorContaOriginal;
            $ValorJuros = ((JUROS * $ValorContaOriginal) / 100) * $DiasIntervalo;
            $ValorJurosDia = ((JUROS * $ValorContaOriginal) / 100);
            $ValorMultaDia = (MULTA / 100) * $ValorContaOriginal;
            $ValorAtualizado = $ValorContaOriginal + $ValorMulta + $ValorJuros;
            $DataVenc = FormDataBr($NovaDtVenc);
            if($_GET['juros'] == '1'){
                $ValorMulta = '0';
                $ValorJuros = '0';
                $ValorAtualizado = $readBoletoView['financeiro_valor'];
            }
        }else{
            $ValorAtualizado = $readBoletoView['financeiro_valor'];
            $DataVenc = FormDataBr($readBoletoView['financeiro_data_vencimento']);
            $ValorJuros = '0';
            $ValorMulta = '0';
        }
    }
    $instrucao_dados = 'Valor Original: '.  FormatMoney($readBoletoView['financeiro_valor']).' | Valor Multa: '.  FormatMoney($ValorMulta).' | Valor Juros: '.  FormatMoney($ValorJuros).' | Vencimento Original: '.  FormDataBr($readBoletoView['financeiro_data_vencimento']).'';
}else{
    $ValorAtualizado = $readBoletoView['financeiro_valor'];
    $DataVenc = FormDataBr($readBoletoView['financeiro_data_vencimento']);
    $instrucao_dados = '';
}
$data_venc = $DataVenc; //date("d/m/Y", time() + ($dias_de_prazo_para_pagamento * 86400));  // Prazo de X dias OU informe data: "13/04/2006";
$valor_cobrado = $ValorAtualizado; // Valor - REGRA: Sem pontos na milhar e tanto faz com "." ou "," ou com 1 ou 2 ou sem casa decimal
$valor_cobrado = str_replace(",", ".",$valor_cobrado);
$valor_boleto=number_format($valor_cobrado+$taxa_boleto, 2, ',', '');

// Composição Nosso Numero - CEF SIGCB
$dadosboleto["nosso_numero1"] = "000"; // tamanho 3
$dadosboleto["nosso_numero_const1"] = "1"; //constanto 1 , 1=registrada , 2=sem registro
$dadosboleto["nosso_numero2"] = "000"; // tamanho 3
$dadosboleto["nosso_numero_const2"] = "4"; //constanto 2 , 4=emitido pelo proprio cliente
$dadosboleto["nosso_numero3"] = $readBoletoView['financeiro_codigo']; // tamanho 9

$dadosboleto["numero_documento"] = $readBoletoView['financeiro_codigo'];	// Num do pedido ou do documento
$dadosboleto["data_vencimento"] = $data_venc; // Data de Vencimento do Boleto - REGRA: Formato DD/MM/AAAA
$dadosboleto["data_documento"] = date("d/m/Y"); // Data de emissão do Boleto
$dadosboleto["data_processamento"] = date("d/m/Y"); // Data de processamento do boleto (opcional)
$dadosboleto["valor_boleto"] = $valor_boleto; 	// Valor do Boleto - REGRA: Com vírgula e sempre com duas casas depois da virgula

// DADOS DA SUA CONTA - CEF
$dadosboleto["agencia"] = "1298"; // Num da agencia, sem digito
$dadosboleto["conta"] = "484"; 	// Num da conta, sem digito
$dadosboleto["conta_dv"] = "6"; 	// Digito do Num da conta

// DADOS PERSONALIZADOS - CEF
$dadosboleto["conta_cedente"] = "273007"; // Código Cedente do Cliente, com 6 digitos (Somente Números)
$dadosboleto["carteira"] = "CR";  // Código da Carteira: pode ser SR (Sem Registro) ou CR (Com Registro) - (Confirmar com gerente qual usar)

$codigobanco = "104";
$codigo_banco_com_dv = geraCodigoBanco($codigobanco);
$nummoeda = "9";
$fator_vencimento = fator_vencimento($dadosboleto["data_vencimento"]);

//valor tem 10 digitos, sem virgula
$valor = formata_numero($dadosboleto["valor_boleto"],10,0,"valor");
//agencia é 4 digitos
$agencia = formata_numero($dadosboleto["agencia"],4,0);
//conta é 5 digitos
$conta = formata_numero($dadosboleto["conta"],5,0);
//dv da conta
$conta_dv = formata_numero($dadosboleto["conta_dv"],1,0);
//carteira é 2 caracteres
$carteira = $dadosboleto["carteira"];

//conta cedente (sem dv) com 6 digitos
$conta_cedente = formata_numero($dadosboleto["conta_cedente"],6,0);
//dv da conta cedente
$conta_cedente_dv = digitoVerificador_cedente($conta_cedente);

//campo livre (sem dv) é 24 digitos
$campo_livre = $conta_cedente . $conta_cedente_dv . formata_numero($dadosboleto["nosso_numero1"],3,0) . formata_numero($dadosboleto["nosso_numero_const1"],1,0) . formata_numero($dadosboleto["nosso_numero2"],3,0) . formata_numero($dadosboleto["nosso_numero_const2"],1,0) . formata_numero($dadosboleto["nosso_numero3"],9,0);
//dv do campo livre
$dv_campo_livre = digitoVerificador_nossonumero($campo_livre);
$campo_livre_com_dv ="$campo_livre$dv_campo_livre";

//nosso número (sem dv) é 17 digitos
$nnum = formata_numero($dadosboleto["nosso_numero_const1"],1,0).formata_numero($dadosboleto["nosso_numero_const2"],1,0).formata_numero($dadosboleto["nosso_numero1"],3,0).formata_numero($dadosboleto["nosso_numero2"],3,0).formata_numero($dadosboleto["nosso_numero3"],9,0);
//nosso número completo (com dv) com 18 digitos
$nossonumero = $nnum . digitoVerificador_nossonumero($nnum);

// 43 numeros para o calculo do digito verificador do codigo de barras
$dv = digitoVerificador_barra("$codigobanco$nummoeda$fator_vencimento$valor$campo_livre_com_dv", 9, 0);
// Numero para o codigo de barras com 44 digitos
$linha = "$codigobanco$nummoeda$dv$fator_vencimento$valor$campo_livre_com_dv";

$agencia_codigo = $agencia." / ". $conta_cedente ."-". $conta_cedente_dv;

$dadosboleto["codigo_barras"] = $linha;
$dadosboleto["linha_digitavel"] = monta_linha_digitavel($linha);
$dadosboleto["agencia_codigo"] = $agencia_codigo;
$dadosboleto["nosso_numero"] = $nossonumero;
$dadosboleto["codigo_banco_com_dv"] = $codigo_banco_com_dv;

$UpFinanceiro['financeiro_nosso_numero'] = $dadosboleto["nosso_numero"];
Update('financeiro', $UpFinanceiro, "WHERE financeiro_id = '".$readBoletoView['financeiro_id']."'");




// DADOS DO SEU CLIENTE
$readCliente = Read('contato', "WHERE contato_id = '".$readBoletoView['financeiro_id_contato']."'");
if(NumQuery($readCliente) > '0'){
    foreach($readCliente as $readClienteView);
}
$dadosboleto["sacado"] = $readClienteView['contato_nome_razao'];
$dadosboleto["endereco1"] = $readClienteView['contato_endereco']." - ".$readClienteView['contato_numero']." - ".$readClienteView['contato_bairro'];
$dadosboleto["endereco2"] = $readClienteView['contato_cidade']." - ".$readClienteView['contato_estado']." -  CEP: ".$readClienteView['contato_cep'];

// INFORMACOES PARA O CLIENTE
$dadosboleto["demonstrativo1"] = "Pagamento de Compra na Loja Nonononono";
$dadosboleto["demonstrativo2"] = "Mensalidade referente a nonon nonooon nononon<br>Taxa bancária - R$ ".number_format($taxa_boleto, 2, ',', '');
$dadosboleto["demonstrativo3"] = "BoletoPhp - http://www.boletophp.com.br";

// INSTRUÇÕES PARA O CAIXA
$dadosboleto["instrucoes1"] = "- Sr. Caixa, cobrar multa de ".$readContaBoletoView['boleto_multa']."% após o vencimento<br />- Sr. Caixa, cobrar JUROS de ".$readContaBoletoView['boleto_juros']."% ao dia após o vencimento";

$dadosboleto["instrucoes2"] = "- Em caso de dúvidas entre em contato conosco: financeiro@federalsistemas.com.br<br />".$instrucao_dados;
$dadosboleto["instrucoes4"] = 'Não receber após 29 dias do vencimento'.'<hr />'.$readBoletoView['financeiro_obs'];

// DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
$dadosboleto["quantidade"] = "";
$dadosboleto["valor_unitario"] = "";
$dadosboleto["aceite"] = "";		
$dadosboleto["especie"] = "R$";
$dadosboleto["especie_doc"] = "";


// ---------------------- DADOS FIXOS DE CONFIGURAÇÃO DO SEU BOLETO --------------- //




// SEUS DADOS
$dadosboleto["identificacao"] = "FEDERAL SISTEMAS DE S E M LTDA";
$dadosboleto["cpf_cnpj"] = "11.655.954/0001-59";
$dadosboleto["endereco"] = "PRESIDENTE VARGAS, CENTRO, 76300-000";
$dadosboleto["cidade_uf"] = "CERES / GO";
$dadosboleto["cedente"] = "FEDERAL SISTEMAS DE S E M LTDA";

// NÃO ALTERAR!

include("layout_cef.php");
    }
}
?>
