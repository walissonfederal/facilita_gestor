<?php
session_start();
ob_start();
if(isset($_GET['04'])){
    $_SESSION['BASE_ENTIDADE'] = base64_decode($_GET['04']);
}
require_once('../../../_class/Boleto.php');
$PDF = new FPDF("P", 'mm', 'A4');
function fbarcode($valor, FPDF $PDF){
    $fino = px2milimetros(1); // valores em px
    $largo = px2milimetros(2.3); // valor em px
    $altura = px2milimetros(40); // valor em px

    $barcodes[0] = "00110";
    $barcodes[1] = "10001";
    $barcodes[2] = "01001";
    $barcodes[3] = "11000";
    $barcodes[4] = "00101";
    $barcodes[5] = "10100";
    $barcodes[6] = "01100";
    $barcodes[7] = "00011";
    $barcodes[8] = "10010";
    $barcodes[9] = "01010";
    for ($f1 = 9; $f1 >= 0; $f1--) {
        for ($f2 = 9; $f2 >= 0; $f2--) {
            $f = ($f1 * 10) + $f2;
            $texto = "";
            for ($i = 1; $i < 6; $i++) {
                $texto .= substr($barcodes[$f1], ($i - 1), 1) . substr($barcodes[$f2], ($i - 1), 1);
            }
            $barcodes[$f] = $texto;
        }
    }

    // Guarda inicial
    $PDF->Image('imgs/p.png', $PDF->GetX(), $PDF->GetY(), $fino, $altura);
    $PDF->SetX($PDF->GetX() + $fino);
    $PDF->Image('imgs/b.png', $PDF->GetX(), $PDF->GetY(), $fino, $altura);
    $PDF->SetX($PDF->GetX() + $fino);
    $PDF->Image('imgs/p.png', $PDF->GetX(), $PDF->GetY(), $fino, $altura);
    $PDF->SetX($PDF->GetX() + $fino);
    $PDF->Image('imgs/b.png', $PDF->GetX(), $PDF->GetY(), $fino, $altura);
    $PDF->SetX($PDF->GetX() + $fino);

    $texto = $valor;
    if ((strlen($texto) % 2) <> 0) {
        $texto = "0" . $texto;
    }

    // Draw dos dados
    while (strlen($texto) > 0) {
        $i = round(esquerda($texto, 2));
        $texto = direita($texto, strlen($texto) - 2);
        $f = $barcodes[$i];
        for ($i = 1; $i < 11; $i += 2) {
            if (substr($f, ($i - 1), 1) == "0") {
                $f1 = $fino;
            } else {
                $f1 = $largo;
            }

            $PDF->Image('imgs/p.png', $PDF->GetX(), $PDF->GetY(), $f1, $altura);
            $PDF->SetX($PDF->GetX() + $f1);

            if (substr($f, $i, 1) == "0") {
                $f2 = $fino;
            } else {
                $f2 = $largo;
            }

            $PDF->Image('imgs/b.png', $PDF->GetX(), $PDF->GetY(), $f2, $altura);
            $PDF->SetX($PDF->GetX() + $f2);
        }
    }

    // Draw guarda final
    $PDF->Image('imgs/p.png', $PDF->GetX(), $PDF->GetY(), $largo, $altura);
    $PDF->SetX($PDF->GetX() + $largo);
    $PDF->Image('imgs/b.png', $PDF->GetX(), $PDF->GetY(), $fino, $altura);
    $PDF->SetX($PDF->GetX() + $fino);
    $PDF->Image('imgs/p.png', $PDF->GetX(), $PDF->GetY(), $fino, $altura);
    $PDF->SetX($PDF->GetX() + $fino);
    $PDF->Image(
        'imgs/b.png',
        $PDF->GetX(),
        $PDF->GetY(),
        px2milimetros(1),
        $altura
    );
    $PDF->SetX($PDF->GetX() + px2milimetros(1));

}

$type_boleto    = addslashes(base64_decode($_GET['00']));
$model_boleto   = addslashes(base64_decode($_GET['01']));
$conta_boleto   = addslashes(base64_decode($_GET['02']));
$url_boleto     = addslashes(base64_decode($_GET['03']));
$tipo_calculo   = addslashes($_GET['05']);
$data_vencimento_web = addslashes(base64_decode($_GET['06']));
$juros_web = addslashes(base64_decode($_GET['07']));

$read_boleto = Read('boleto', "WHERE boleto_id = '".$conta_boleto."' AND boleto_status = '0'");
if(NumQuery($read_boleto) > '0'){
    foreach($read_boleto as $read_boleto_view);
}else{
    header("Location: ../../index.php");
}



if($type_boleto == 'boleto_cef'){
    if($model_boleto == 'sicob'){
        include_once '../boletophp/include/funcoes_cef.php';
    }elseif($model_boleto == 'sinco'){
        include_once '../boletophp/include/funcoes_cef_sinco.php';
    }elseif($model_boleto == 'sigcb'){
        include_once '../boletophp/include/funcoes_cef_sigcb.php';
    }
}

$read_financeiro = Read('financeiro', "WHERE financeiro_tipo = 'CR' AND financeiro_status = '0' AND financeiro_id IN($url_boleto) ORDER BY financeiro_codigo ASC");
if(NumQuery($read_financeiro) > '0'){
    foreach($read_financeiro as $read_financeiro_view){
        
        //SEMPRE OS MESMOS DADOS
        $dias_de_prazo_para_pagamento = 0;
        $taxa_boleto = 0;
        $data_venc = FormDataBr($read_financeiro_view['financeiro_data_vencimento']);
        $valor_cobrado = $read_financeiro_view['financeiro_valor'];
        $valor_cobrado = str_replace(",", ".",$valor_cobrado);
        $valor_boleto=number_format($valor_cobrado+$taxa_boleto, 2, ',', '');
        
        if($tipo_calculo == 'web'){
            $ValorMulta = '0';
            $ValorJuros = '0';
            $ValorAtualizado = '0';
            if($data_vencimento_web == ''){
                $NovaDtVenc = date('Y-m-d');
                if(strtotime($read_financeiro_view['financeiro_data_vencimento']) < strtotime(date('Y-m-d'))){
                    $IntervadoVencido = strtotime($NovaDtVenc) - strtotime($read_financeiro_view['financeiro_data_vencimento']);
                    //$DiasIntervalo = DiferencaDias(FormDataBr($read_financeiro_paginator_view['financeiro_data_vencimento']), FormDataBr($NovaDtVenc));
                    $DiasIntervalo = floor($IntervadoVencido / (60 * 60 * 24));
                    //echo $DiasIntervalo.'<br />';
                    $ValorContaOriginal = $read_financeiro_view['financeiro_valor'];
                    $ValorMulta = (MULTA / 100) * $ValorContaOriginal;
                    $ValorJuros = ((JUROS * $ValorContaOriginal) / 100) * $DiasIntervalo;
                    $ValorJurosDia = ((JUROS * $ValorContaOriginal) / 100);
                    $ValorMultaDia = (MULTA / 100) * $ValorContaOriginal;
                    if($ValorJuros > '0'){
                        if($juros_web == '0'){
                            $ValorAtualizado = $ValorContaOriginal + $ValorMulta + $ValorJuros;
                            $DataVenc = FormDataBr($NovaDtVenc);
                        }else{
                            $ValorAtualizado = $ValorContaOriginal;
                            $ValorMulta = '0';
                            $ValorJuros = '0';
                            $DataVenc = FormDataBr($NovaDtVenc);
                        }
                    }else{
                        $ValorAtualizado = $ValorContaOriginal;
                        $DataVenc = FormDataBr($NovaDtVenc);
                        $ValorJurosDia = '0';
                        $ValorMultaDia = '0';
                        $ValorJuros = '0';
                        $ValorMulta = '0';
                    }
                }else{
                    $ValorAtualizado = $read_financeiro_view['financeiro_valor'];
                    $DataVenc = FormDataBr($read_financeiro_view['financeiro_data_vencimento']);
                    $ValorJuros = '0';
                    $ValorMulta = '0';
                }
            }else{
                $NovaDtVenc = $data_vencimento_web;
                if(strtotime($read_financeiro_view['financeiro_data_vencimento']) < strtotime(date('Y-m-d'))){
                    $IntervadoVencido = strtotime($NovaDtVenc) - strtotime($read_financeiro_view['financeiro_data_vencimento']);
                    //$DiasIntervalo = DiferencaDias(FormDataBr($read_financeiro_paginator_view['financeiro_data_vencimento']), FormDataBr($NovaDtVenc));
                    $DiasIntervalo = floor($IntervadoVencido / (60 * 60 * 24));
                    //echo $DiasIntervalo.'<br />';
                    $ValorContaOriginal = $read_financeiro_view['financeiro_valor'];
                    $ValorMulta = (MULTA / 100) * $ValorContaOriginal;
                    $ValorJuros = ((JUROS * $ValorContaOriginal) / 100) * $DiasIntervalo;
                    $ValorJurosDia = ((JUROS * $ValorContaOriginal) / 100);
                    $ValorMultaDia = (MULTA / 100) * $ValorContaOriginal;
                    if($ValorJuros > '0'){
                        if($juros_web == '0'){
                            $ValorAtualizado = $ValorContaOriginal + $ValorMulta + $ValorJuros;
                            $DataVenc = FormDataBr($NovaDtVenc);
                        }else{
                            $ValorAtualizado = $ValorContaOriginal;
                            $ValorMulta = '0';
                            $ValorJuros = '0';
                            $DataVenc = FormDataBr($NovaDtVenc);
                        }
                    }else{
                        $ValorAtualizado = $ValorContaOriginal;
                        $DataVenc = FormDataBr($NovaDtVenc);
                        $ValorJurosDia = '0';
                        $ValorMultaDia = '0';
                        $ValorJuros = '0';
                        $ValorMulta = '0';
                    }
                }else{
                    $ValorAtualizado = $read_financeiro_view['financeiro_valor'];
                    $DataVenc = FormDataBr($read_financeiro_view['financeiro_data_vencimento']);
                    $ValorJuros = '0';
                    $ValorMulta = '0';
                }
            }
            $data_venc = FormDataBr($read_financeiro_view['financeiro_data_vencimento']);
            $valor_cobrado = number_format($read_financeiro_view['financeiro_valor'],2,".","");
            $valor_cobrado = str_replace(",", ".",$valor_cobrado);
            $valor_boleto=number_format($valor_cobrado+$taxa_boleto, 2, ',', '');
            //$valor_boleto = number_format($ValorAtualizado,2,".","");
        }
        
        if($type_boleto == 'boleto_cef'){
            $logo_empresa_banco = 'imgs/logocaixa.jpg';
            if($model_boleto == 'sicob'){
                
                $dadosboleto["agencia"] = $read_boleto_view['boleto_agencia']; // Num da agencia, sem digito
                $dadosboleto["conta"] = $read_boleto_view['boleto_conta'];// Num da conta, sem digito
                $dadosboleto["conta_dv"] = $read_boleto_view['boleto_conta_digito']; // Digito do Num da conta

                // DADOS PERSONALIZADOS - CEF
                $dadosboleto["conta_cedente"] = $read_boleto_view['boleto_conta_cedente']; // ContaCedente do Cliente, sem digito (Somente N�meros)
                $dadosboleto["conta_cedente_dv"] = $read_boleto_view['boleto_conta_cedente_digito']; // Digito da ContaCedente do Cliente
                $dadosboleto["carteira"] = "CR";  // C�digo da Carteira: pode ser SR (Sem Registro) ou CR (Com Registro) - (Confirmar com gerente qual usar)
                
                $dadosboleto["inicio_nosso_numero"] = $read_boleto_view['boleto_carteira']; // Carteira SR: 80, 81 ou 82 - Carteira CR: 90 (Confirmar com gerente qual usar)
                $dadosboleto["nosso_numero"] = $read_financeiro_view['financeiro_codigo'];  // Nosso numero sem o DV - REGRA: Máximo de 8 caracteres!
                $dadosboleto["numero_documento"] = $dadosboleto["nosso_numero"];	// Num do pedido ou do documento
                $dadosboleto["data_vencimento"] = $data_venc; // Data de Vencimento do Boleto - REGRA: Formato DD/MM/AAAA
                $dadosboleto["data_documento"] = date("d/m/Y"); // Data de emissão do Boleto
                $dadosboleto["data_processamento"] = date("d/m/Y"); // Data de processamento do boleto (opcional)
                $dadosboleto["valor_boleto"] = $valor_boleto; 	// Valor do Boleto - REGRA: Com vírgula e sempre com duas casas depois da virgula

                $codigobanco = "104";
                $codigo_banco_com_dv = geraCodigoBanco($codigobanco);
                $nummoeda = "9";
                $fator_vencimento = fator_vencimento($dadosboleto["data_vencimento"]);

                $valor = formata_numero($dadosboleto["valor_boleto"],10,0,"valor");
                $agencia = formata_numero($dadosboleto["agencia"],4,0);
                $conta = formata_numero($dadosboleto["conta"],5,0);
                $conta_dv = formata_numero($dadosboleto["conta_dv"],1,0);
                $carteira = $dadosboleto["carteira"];

                $conta_cedente = formata_numero($dadosboleto["conta_cedente"],11,0);
                $conta_cedente_dv = formata_numero($dadosboleto["conta_cedente_dv"],1,0);

                $nnum = $dadosboleto["inicio_nosso_numero"] . formata_numero($dadosboleto["nosso_numero"],8,0);
                $nossonumero = $nnum .'-'. digitoVerificador_nossonumero($nnum);

                $dv = digitoVerificador_barra("$codigobanco$nummoeda$fator_vencimento$valor$nnum$agencia$conta_cedente", 9, 0);
                $linha = "$codigobanco$nummoeda$dv$fator_vencimento$valor$nnum$agencia$conta_cedente";

                $agencia_codigo = $agencia." / ". $conta_cedente ."-". $conta_cedente_dv;

                $dadosboleto["codigo_barras"] = $linha;
                $dadosboleto["linha_digitavel"] = monta_linha_digitavel($linha);
                $dadosboleto["agencia_codigo"] = $agencia_codigo;
                $dadosboleto["nosso_numero"] = $nossonumero;
                $dadosboleto["codigo_banco_com_dv"] = $codigo_banco_com_dv;
                
                $up_nosso_numero['financeiro_nosso_numero'] = str_replace('-', '', $nossonumero);
                Update('financeiro', $up_nosso_numero, "WHERE financeiro_id = '".$read_financeiro_view['financeiro_id']."'");

            }elseif($model_boleto == 'sinco'){
                // DADOS DA SUA CONTA - CEF
                $dadosboleto["agencia"] = $read_boleto_view['boleto_agencia']; // Num da agencia, sem digito
                $dadosboleto["conta"] = $read_boleto_view['boleto_conta']; 	// Num da conta, sem digito
                $dadosboleto["conta_dv"] = $read_boleto_view['boleto_conta_digito']; 	// Digito do Num da conta

                // DADOS PERSONALIZADOS - CEF
                $dadosboleto["conta_cedente"] = $read_boleto_view['boleto_conta_cedente']; // ContaCedente do Cliente, sem digito (Somente N�meros)
                $dadosboleto["conta_cedente_dv"] = $read_boleto_view['boleto_conta_digito']; // Digito da ContaCedente do Cliente
                $dadosboleto["carteira"] = "CR";  // C�digo da Carteira: pode ser SR (Sem Registro) ou CR (Com Registro) - (Confirmar com gerente qual usar)
                
                $dadosboleto["campo_fixo_obrigatorio"] = "1";       // campo fixo obrigatorio - valor = 1 
                $dadosboleto["inicio_nosso_numero"] = "9";          // Inicio do Nosso numero - obrigatoriamente deve come�ar com 9;
                $dadosboleto["nosso_numero"] = $read_financeiro_view['financeiro_codigo'];  // Nosso numero sem o DV - REGRA: M�ximo de 16 caracteres! (Pode ser um n�mero sequencial do sistema, o cpf ou o cnpj)
                $dadosboleto["numero_documento"] = $read_financeiro_view['financeiro_codigo'];	// Num do pedido ou do documento
                $dadosboleto["data_vencimento"] = $data_venc; // Data de Vencimento do Boleto - REGRA: Formato DD/MM/AAAA
                $dadosboleto["data_documento"] = date("d/m/Y"); // Data de emiss�o do Boleto
                $dadosboleto["data_processamento"] = date("d/m/Y"); // Data de processamento do boleto (opcional)
                $dadosboleto["valor_boleto"] = $valor_boleto; 	// Valor do Boleto - REGRA: Com v�rgula e sempre com duas casas depois da virgula
                
                $codigobanco = "104";
                $codigo_banco_com_dv = geraCodigoBanco($codigobanco);
                $nummoeda = "9";
                $fator_vencimento = fator_vencimento($dadosboleto["data_vencimento"]);

                $valor = formata_numero($dadosboleto["valor_boleto"],10,0,"valor");
                $agencia = formata_numero($dadosboleto["agencia"],4,0);
                $conta = formata_numero($dadosboleto["conta"],5,0);
                $conta_dv = formata_numero($dadosboleto["conta_dv"],1,0);
                $carteira = $dadosboleto["carteira"];

                $nnum = $dadosboleto["inicio_nosso_numero"] . formata_numero($dadosboleto["nosso_numero"],17,0);
                $dv_nosso_numero = digitoVerificador_nossonumero($nnum);
                $nossonumero_dv ="$nnum$dv_nosso_numero";

                $conta_cedente = formata_numero($dadosboleto["conta_cedente"],6,0);
                $conta_cedente_dv = formata_numero($dadosboleto["conta_cedente_dv"],1,0);

                $ag_contacedente  = $agencia . $conta_cedente;
                $fixo             = $dadosboleto["campo_fixo_obrigatorio"];
                $campo_livre      = "$fixo$conta_cedente$nnum";

                $dv = digitoVerificador_barra("$codigobanco$nummoeda$fator_vencimento$valor$campo_livre", 9, 0);
                $linha = "$codigobanco$nummoeda$dv$fator_vencimento$valor$campo_livre";

                $nossonumero = substr($nossonumero_dv,0,18).'-'.substr($nossonumero_dv,18,1);
                $agencia_codigo = $agencia." / ". $conta_cedente ."-". $conta_cedente_dv;


                $dadosboleto["codigo_barras"] = $linha;
                $dadosboleto["linha_digitavel"] = monta_linha_digitavel($linha);
                $dadosboleto["agencia_codigo"] = $agencia_codigo;
                $dadosboleto["nosso_numero"] = $nossonumero;
                $dadosboleto["codigo_banco_com_dv"] = $codigo_banco_com_dv;
                
                $up_nosso_numero['financeiro_nosso_numero'] = str_replace('-', '', $nossonumero);
                Update('financeiro', $up_nosso_numero, "WHERE financeiro_id = '".$read_financeiro_view['financeiro_id']."'");
                
                
            }elseif($model_boleto == 'sigcb'){
                
                // DADOS DA SUA CONTA - CEF
                $dadosboleto["agencia"] = $read_boleto_view['boleto_agencia']; // Num da agencia, sem digito
                $dadosboleto["conta"] = $read_boleto_view['boleto_conta']; 	// Num da conta, sem digito
                $dadosboleto["conta_dv"] = $read_boleto_view['boleto_conta_digito']; 	// Digito do Num da conta

                // DADOS PERSONALIZADOS - CEF
                $dadosboleto["conta_cedente"] = $read_boleto_view['boleto_conta_cedente']; // C�digo Cedente do Cliente, com 6 digitos (Somente N�meros)
                $dadosboleto["carteira"] = "CR";  // C�digo da Carteira: pode ser SR (Sem Registro) ou CR (Com Registro) - (Confirmar com gerente qual usar)
                
                $dadosboleto["nosso_numero1"] = "000"; // tamanho 3
                $dadosboleto["nosso_numero_const1"] = "1"; //constanto 1 , 1=registrada , 2=sem registro
                $dadosboleto["nosso_numero2"] = "000"; // tamanho 3
                $dadosboleto["nosso_numero_const2"] = "4"; //constanto 2 , 4=emitido pelo proprio cliente
                $dadosboleto["nosso_numero3"] = $read_financeiro_view['financeiro_codigo']; // tamanho 9


                $dadosboleto["numero_documento"] = $read_financeiro_view['financeiro_codigo'];	// Num do pedido ou do documento
                $dadosboleto["data_vencimento"] = $data_venc; // Data de Vencimento do Boleto - REGRA: Formato DD/MM/AAAA
                $dadosboleto["data_documento"] = date("d/m/Y"); // Data de emiss�o do Boleto
                $dadosboleto["data_processamento"] = date("d/m/Y"); // Data de processamento do boleto (opcional)
                $dadosboleto["valor_boleto"] = $valor_boleto; 	// Valor do Boleto - REGRA: Com v�rgula e sempre com duas casas depois da virgula
                
                $codigobanco = "104";
                $codigo_banco_com_dv = geraCodigoBanco($codigobanco);
                $nummoeda = "9";
                $fator_vencimento = fator_vencimento($dadosboleto["data_vencimento"]);

                //valor tem 10 digitos, sem virgula
                $valor = formata_numero($dadosboleto["valor_boleto"],10,0,"valor");
                //agencia � 4 digitos
                $agencia = formata_numero($dadosboleto["agencia"],4,0);
                //conta � 5 digitos
                $conta = formata_numero($dadosboleto["conta"],5,0);
                //dv da conta
                $conta_dv = formata_numero($dadosboleto["conta_dv"],1,0);
                //carteira � 2 caracteres
                $carteira = $dadosboleto["carteira"];

                //conta cedente (sem dv) com 6 digitos
                $conta_cedente = formata_numero($dadosboleto["conta_cedente"],6,0);
                //dv da conta cedente
                $conta_cedente_dv = digitoVerificador_cedente($conta_cedente);

                //campo livre (sem dv) � 24 digitos
                $campo_livre = $conta_cedente . $conta_cedente_dv . formata_numero($dadosboleto["nosso_numero1"],3,0) . formata_numero($dadosboleto["nosso_numero_const1"],1,0) . formata_numero($dadosboleto["nosso_numero2"],3,0) . formata_numero($dadosboleto["nosso_numero_const2"],1,0) . formata_numero($dadosboleto["nosso_numero3"],9,0);
                //dv do campo livre
                $dv_campo_livre = digitoVerificador_nossonumero($campo_livre);
                $campo_livre_com_dv ="$campo_livre$dv_campo_livre";

                //nosso n�mero (sem dv) � 17 digitos
                $nnum = formata_numero($dadosboleto["nosso_numero_const1"],1,0).formata_numero($dadosboleto["nosso_numero_const2"],1,0).formata_numero($dadosboleto["nosso_numero1"],3,0).formata_numero($dadosboleto["nosso_numero2"],3,0).formata_numero($dadosboleto["nosso_numero3"],9,0);
                //nosso n�mero completo (com dv) com 18 digitos
                $nossonumero = $nnum .'-' .digitoVerificador_nossonumero($nnum);

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
                
                $up_nosso_numero['financeiro_nosso_numero'] = str_replace('-', '', $nossonumero);
                Update('financeiro', $up_nosso_numero, "WHERE financeiro_id = '".$read_financeiro_view['financeiro_id']."'");
            }
        }
        
        $dadosboleto["quantidade"] = "";
        $dadosboleto["valor_unitario"] = "";
        $dadosboleto["aceite"] = "";		
        $dadosboleto["especie"] = "R$";
        $dadosboleto["especie_doc"] = "DM";

        //DADOS DO SACADO
        
        $financeiro_nome_sacado                 = GetDados('contato', $read_financeiro_view['financeiro_id_contato'], 'contato_id', 'contato_nome_fantasia');
        $financeiro_cpf_cnpj_sacado             = GetDados('contato', $read_financeiro_view['financeiro_id_contato'], 'contato_id', 'contato_cpf_cnpj');
        $financeiro_endereco_sacado             = GetDados('contato', $read_financeiro_view['financeiro_id_contato'], 'contato_id', 'contato_endereco');
        $financeiro_numero_sacado               = GetDados('contato', $read_financeiro_view['financeiro_id_contato'], 'contato_id', 'contato_numero');
        $financeiro_bairro_sacado               = GetDados('contato', $read_financeiro_view['financeiro_id_contato'], 'contato_id', 'contato_bairro');
        $financeiro_cidade_sacado               = GetDados('contato', $read_financeiro_view['financeiro_id_contato'], 'contato_id', 'contato_cidade');
        $financeiro_estado_sacado               = GetDados('contato', $read_financeiro_view['financeiro_id_contato'], 'contato_id', 'contato_estado');
        $financeiro_cep_sacado                  = GetDados('contato', $read_financeiro_view['financeiro_id_contato'], 'contato_id', 'contato_cep');

        //DADOS DO CEDENTE
        $financeiro_cedente                     = GetEmpresa('empresa_nome_fantasia');
        $financeiro_cpf_cnpj_cedente            = GetEmpresa('empresa_cnpj');
        $financeiro_logo_cedente                = GetEmpresa('empresa_logo');

        //DADOS DO BOLETO
        $financeiro_data_vencimento             = $data_venc;
        $financeiro_valor                       = $valor_cobrado;
        $financeiro_nosso_numero                = $read_financeiro_view['financeiro_codigo'];
        $financeiro_data_documento              = $dadosboleto["data_documento"];
        $financeiro_numero_documento            = $financeiro_nosso_numero;
        $financeiro_data_processamento          = $dadosboleto["data_processamento"];

        //DADOS DE CONTA
        $financeiro_agencia                     = $dadosboleto["agencia_codigo"];
        $financeiro_conta_cedente               = '';
        $financeiro_carteira                    = $dadosboleto["carteira"];

        //DADOS ADICIONAIS PRIORITARIOS
        $financeiro_logo_banco                  = $logo_empresa_banco;
        $financeiro_codigo_banco                = $codigo_banco_com_dv;
        $financeiro_linha_digitavel             = $dadosboleto["linha_digitavel"];
        $financeiro_especie                     = $dadosboleto["especie"];
        $financeiro_especie_doc                 = $dadosboleto["especie_doc"];
        $financeiro_nosso_numero_completo       = $dadosboleto["nosso_numero"];
        $financeiro_local_pagamento             = 'Preferencialmente nas casas lotéricas até o valor limite';
        $financeiro_codigo_barras               = $dadosboleto["codigo_barras"];

        //DADOS DE INSTRUCOES
        $financeiro_instrucao[0]                 = $read_boleto_view['boleto_demonstrativo_1'];
        $financeiro_instrucao[1]                 = $read_boleto_view['boleto_demonstrativo_2'];
        $financeiro_instrucao[2]                 = $read_boleto_view['boleto_demonstrativo_3'];
        $financeiro_instrucao[3]                 = $read_boleto_view['boleto_demonstrativo_4'];
        $financeiro_instrucao[4]                 = 'Valor original R$'.FormatMoney($read_financeiro_view['financeiro_valor']).' | Vencimento original '. FormDataBr($read_financeiro_view['financeiro_data_vencimento']).' | Juros R$'. FormatMoney($ValorJuros).' | Multa R$'. FormatMoney($ValorMulta).' - Espere 3 horas para efetuar o pagamento';
        $financeiro_instrucao[5]                 = $read_financeiro_view['financeiro_obs'];

        //add a pagina
        $PDF->AddPage();

        //Select Arial bold 8
        $PDF->SetFont('Arial', 'B', 6);
        //titulo da impressão
        //$PDF->Image($financeiro_logo_cedente, 10, 5, 60, 20);
        $PDF->Ln('15');
        $PDF->SetFont('Arial', '', 8);
        $PDF->Ln();
        //titulo da impressão
        //$PDF->Cell(190, 5, utf8_decode("Detalhamento de cobrança:"), '', 1, 'C');

        //tabela de detalhamento de cobrança
        /*$PDF->SetFont('Arial', 'B', 7);
        $PDF->Cell(20, 4, 'Item', 1, 0, 'L');
        $PDF->Cell(80, 4, utf8_decode('Produto/Serviço'), 1, 0, 'L');
        $PDF->Cell(20, 4, 'Quantidade', 1, 0, 'L');
        $PDF->Cell(35, 4, utf8_decode('Valor Unitário'), 1, 0, 'C');
        $PDF->Cell(35, 4, utf8_decode('Valor Total'), 1, 1, 'C');*/

        //itens do detalhamento de cobrança
        /*$count_dados = '0';
        $read_itens_vendas = Read('itens_orcamento_venda',"WHERE itens_orcamento_venda_id_orcamento_venda = '".$read_financeiro_view['financeiro_id_venda']."' LIMIT 10");
        if(NumQuery($read_itens_vendas) > '0'){
            foreach($read_itens_vendas as $read_itens_vendas_view){
                $count_dados++;
                $PDF->SetFont('Arial', '', 6);
                $PDF->Cell(20, 4, utf8_decode($count_dados), 1, 0, 'L');
                $PDF->Cell(80, 4, utf8_decode(GetDados('produto', $read_itens_vendas_view['itens_orcamento_venda_id_produto'], 'produto_id', 'produto_descricao')), 1, 0, 'L');
                $PDF->Cell(20, 4, utf8_decode($read_itens_vendas_view['itens_orcamento_venda_qtd']), 1, 0, 'L');
                $PDF->Cell(35, 4, utf8_decode(FormatMoney($read_itens_vendas_view['itens_orcamento_venda_valor_unitario'])), 1, 0, 'C');
                $PDF->Cell(35, 4, utf8_decode(FormatMoney($read_itens_vendas_view['itens_orcamento_venda_valor_total'])), 1, 1, 'C');
            }
        }
        for($x=0;$x<10-NumQuery($read_itens_vendas);$x++){
            $count_dados++;
            $PDF->SetFont('Arial', '', 6);
            $PDF->Cell(20, 4, utf8_decode($count_dados), 1, 0, 'L');
            $PDF->Cell(80, 4, utf8_decode('.'), 1, 0, 'L');
            $PDF->Cell(20, 4, utf8_decode('.'), 1, 0, 'L');
            $PDF->Cell(35, 4, utf8_decode('.'), 1, 0, 'C');
            $PDF->Cell(35, 4, utf8_decode('.'), 1, 1, 'C');
        }
        */

        //informações e linha do recibo
        /*$PDF->Ln();
        $PDF->SetFont('Arial', 'B', 6);
        $PDF->Cell(190, 2, 'Recibo do Pagador', '', 1, 'R');
        $PDF->SetFont('Arial', '', 12);
        $PDF->Cell(
            190,
            2,
            '--------------------------------------------------------------------------------------------------------------------------------------',
            '',
            0,
            'L'
        );*/

        $PDF->Ln();
        $PDF->Ln(15);

        $PDF->SetFont('Arial', '', 9);

        $PDF->Cell(50, 10, '', 'B', 0, 'L');

        //logo marca banco
        $PDF->Image($financeiro_logo_banco, 10, 38, 40, 10);

        //Select Arial italic 8
        $PDF->SetFont('Arial', 'B', 14);
        $PDF->Cell(20, 10, $financeiro_codigo_banco, 'LBR', 0, 'C');

        $PDF->SetFont('Arial', 'B', 9);
        $PDF->Cell(120, 10, $financeiro_linha_digitavel, 'B', 1, 'R');

        $PDF->SetFont('Arial', '', 6);
        $PDF->Cell(85, 3, utf8_decode('Beneficiário'), 'LR', 0, 'L');
        $PDF->Cell(30, 3, utf8_decode('Agência/Código do Beneficiário'), 'R', 0, 'L');
        $PDF->Cell(15, 3, utf8_decode('Espécie Moeda'), 'R', 0, 'L');
        $PDF->Cell(20, 3, 'Quantidade Moeda', 'R', 0, 'L');
        $PDF->Cell(40, 3, utf8_decode('Carteira/Nosso número'), '', 1, 'L');

        $PDF->SetFont('Arial', '', 7);
        $PDF->Cell(85, 5, utf8_decode($financeiro_cedente), 'BLR', 0, 'L');
        $PDF->Cell(
            30,
            5,
            $financeiro_agencia . " / " . $financeiro_conta_cedente,
            'BR',
            0,
            'L'
        );

        $PDF->Cell(15, 5, $financeiro_especie, 'BR', 0, 'L');
        $PDF->Cell(20, 5, "", 'BR', 0, 'L');
        $PDF->Cell(40, 5, $financeiro_nosso_numero_completo, 'B', 1, 'R');

        $PDF->SetFont('Arial', '', 6);
        $PDF->Cell(60, 3, utf8_decode('Número do Documento'), 'LR', 0, 'L');
        $PDF->Cell(35, 3, 'CPF/CNPJ', 'R', 0, 'L');
        $PDF->Cell(35, 3, 'Vencimento', 'R', 0, 'L');
        $PDF->Cell(60, 3, 'Valor Documento', 'L', 1, 'L');

        $PDF->SetFont('Arial', '', 7);
        $PDF->Cell(60, 5, $financeiro_nosso_numero, 'BLR', 0, 'L');
        $PDF->Cell(35, 5, $financeiro_cpf_cnpj_cedente, 'BR', 0, 'L');
        $PDF->Cell(35, 5, $financeiro_data_vencimento, 'BR', 0, 'L');
        $PDF->Cell(60, 5, $financeiro_valor, 'B', 1, 'R');

        $PDF->SetFont('Arial', '', 6);
        $PDF->Cell(33, 3, '(-)Desconto/Abatimentos', 'LR', 0, 'L');
        $PDF->Cell(32, 3, utf8_decode('(-)Outras deduções'), 'R', 0, 'L');
        $PDF->Cell(32, 3, '(+)Mora/Multa', 'R', 0, 'L');
        $PDF->Cell(33, 3, utf8_decode('(+)Outros acréscimos'), '', 0, 'L');
        $PDF->Cell(60, 3, '(*)Valor Cobrado', 'L', 1, 'L');

        $PDF->SetFont('Arial', '', 7);
        $PDF->Cell(33, 5, '', 'BLR', 0, 'L');
        $PDF->Cell(32, 5, '', 'BR', 0, 'L');
        $PDF->Cell(32, 5, '', 'BR', 0, 'L');
        $PDF->Cell(33, 5, '', 'BR', 0, 'L');
        $PDF->Cell(60, 5, '', 'B', 1, 'R');

        $PDF->SetFont('Arial', '', 6);
        $PDF->Cell(190, 3, 'Pagador', 'L', 1, 'L');

        $PDF->SetFont('Arial', '', 7);
        $PDF->Cell(190, 5, $financeiro_cpf_cnpj_sacado .' - '.utf8_decode($financeiro_nome_sacado), 'L', 1, 'L');
        $PDF->Cell(
            190,
            5,
            utf8_decode(
                $financeiro_endereco_sacado. "," . $financeiro_numero_sacado . ", ". $financeiro_bairro_sacado
            ),
            'L',
            1,
            'L'
        );

        $PDF->Cell(
            190,
            5,
            utf8_decode(
                $financeiro_cidade_sacado . " - " . $financeiro_estado_sacado . " - CEP: " . $financeiro_cep_sacado
            ),
            'BL',
            1,
            'L'
        );

        //$PDF->SetFont('Arial', '', 6);
        //$PDF->Cell(170, 3, utf8_decode('Instruções'), '', 0, 'L');
        //$PDF->Cell(20, 3, utf8_decode('Autênticação Mecânica'), '', 1, 'R');

        //$PDF->SetFont('Arial', '', 7);

        //instruções    
        //$PDF->Cell(190, 5, utf8_decode($financeiro_instrucao_1), '', 1, 'L');
        //$PDF->Cell(190, 5, utf8_decode($financeiro_instrucao_2), '', 1, 'L');
        //$PDF->Cell(190, 5, utf8_decode($financeiro_instrucao_3), '', 1, 'L');
        //$PDF->Cell(190, 5, utf8_decode($financeiro_instrucao_4), '', 1, 'L');
        $PDF->SetFont('Arial', 'B', 6);
        $PDF->Cell(190, 2, utf8_decode('SAC CAIXA: 0800 726 0101 (informações, reclamações, sugestões e elogios)'), '', 1, 'L');
        $PDF->Cell(190, 2, utf8_decode('Para pessoas com deficiência auditiva ou de fala: 0800 726 2492'), '', 1, 'L');
        $PDF->Cell(190, 2, utf8_decode('Ouvidoria: 0800 725 7474'), '', 1, 'L');
        $PDF->Cell(190, 2, utf8_decode('caixa.gov.br'), '', 1, 'L');
        $PDF->Ln();
        $PDF->SetFont('Arial', 'B', 6);
        $PDF->Cell(190, 2, 'Corte na linha pontilhada', '', 1, 'R');
        $PDF->SetFont('Arial', '', 12);
        $PDF->Cell(
            190,
            2,
            '--------------------------------------------------------------------------------------------------------------------------------------',
            '',
            0,
            'L'
        );

        $PDF->Ln(10);

        $PDF->Cell(50, 10, '', 'B', 0, 'L');
        $PDF->Image($financeiro_logo_banco, 10, 112, 40, 10);
        //Select Arial italic 8
        $PDF->SetFont('Arial', 'B', 14);
        $PDF->Cell(20, 10, $financeiro_codigo_banco, 'LBR', 0, 'C');

        $PDF->SetFont('Arial', 'B', 9);
        $PDF->Cell(120, 10, $financeiro_linha_digitavel, 'B', 1, 'R');

        $PDF->SetFont('Arial', '', 6);
        $PDF->Cell(130, 3, 'Local Pagamento', 'LR', 0, 'L');
        $PDF->Cell(60, 3, 'Vencimento', '', 1, 'L');

        $PDF->SetFont('Arial', '', 7);
        $PDF->Cell(130, 5, utf8_decode($financeiro_local_pagamento), 'BLR', 0, 'L');
        $PDF->Cell(60, 5, $financeiro_data_vencimento, 'B', 1, 'R');

        $PDF->SetFont('Arial', '', 6);
        $PDF->Cell(130, 3, utf8_decode('Beneficiário'), 'LR', 0, 'L');
        $PDF->Cell(60, 3, utf8_decode('Agência/Código Beneficiário'), '', 1, 'L');

        $PDF->SetFont('Arial', '', 7);
        $PDF->Cell(130, 5, $financeiro_cpf_cnpj_cedente.' - '.utf8_decode($financeiro_cedente), 'BLR', 0, 'L');
        $PDF->Cell(
            60,
            5,
            $financeiro_agencia . " / " . $financeiro_conta_cedente,
            'B',
            1,
            'R'
        );

        $PDF->SetFont('Arial', '', 6);
        $PDF->Cell(28, 3, 'Data Documento', 'LR', 0, 'L');
        $PDF->Cell(40, 3, utf8_decode('Número do Documento'), 'R', 0, 'L');
        $PDF->Cell(20, 3, utf8_decode('Espécie doc.'), 'R', 0, 'L');
        $PDF->Cell(20, 3, 'Aceite', 'R', 0, 'L');
        $PDF->Cell(22, 3, 'Data processamento', '', 0, 'L');
        $PDF->Cell(60, 3, utf8_decode('Carteira / Nosso número'), 'L', 1, 'L');

        $PDF->SetFont('Arial', '', 7);
        $PDF->Cell(28, 5, $financeiro_data_documento, 'BLR', 0, 'L');
        $PDF->Cell(40, 5, $financeiro_numero_documento, 'BR', 0, 'L');
        $PDF->Cell(20, 5, $financeiro_especie_doc, 'BR', 0, 'L');
        $PDF->Cell(20, 5, "N", 'BR', 0, 'L');
        $PDF->Cell(22, 5, $financeiro_data_processamento, 'BR', 0, 'L');
        $PDF->Cell(60, 5, $financeiro_nosso_numero_completo, 'B', 1, 'R');

        $PDF->SetFont('Arial', '', 6);
        $PDF->Cell(28, 3, 'Uso do Banco', 'LR', 0, 'L');
        $PDF->Cell(25, 3, 'Carteira', 'R', 0, 'L');
        $PDF->Cell(15, 3, utf8_decode('Espécie Moeda'), 'R', 0, 'L');
        $PDF->Cell(40, 3, 'Quantidade Moeda', 'R', 0, 'L');
        $PDF->Cell(22, 3, '(x)Valor', '', 0, 'L');
        $PDF->Cell(60, 3, '(=)Valor Documento', 'L', 1, 'L');

        $PDF->SetFont('Arial', '', 7);
        $PDF->Cell(28, 5, '', 'BLR', 0, 'L');
        $PDF->Cell(25, 5, $financeiro_carteira, 'BR', 0, 'L');
        $PDF->Cell(15, 5, $financeiro_especie, 'BR', 0, 'L');
        $PDF->Cell(40, 5, "", 'BR', 0, 'L');
        $PDF->Cell(22, 5, '', 'BR', 0, 'L');
        $PDF->Cell(60, 5, $financeiro_valor, 'B', 1, 'R');

        $PDF->SetFont('Arial', '', 6);
        $PDF->Cell(130, 3, utf8_decode('Instruções (Texto de Responsabilidade do Beneficiário)'), 'L', 0, 'L');
        $PDF->Cell(60, 3, '(-)Desconto', 'L', 1, 'L');
        
        
        $l = 0;
        for ($i = 0; $i < 5; $i++) {
            $l++;
            $PDF->Cell(130, 5, utf8_decode($financeiro_instrucao[$i]), 'L', 0, 'L');

            if (1 == $l) {
                $PDF->Cell(60, 5, '', 'LB', 1, 'R');
            } else if (2 == $l) {
                $PDF->SetFont('Arial', '', 6);
                $PDF->Cell(60, 3, utf8_decode('(-)Outras deduções/Abatimentos'), 'L', 1, 'L');
            } else if (3 == $l) {
                $PDF->Cell(60, 5, '', 'LB', 1, 'R');
            } else {
                if (4 == $l) {
                    $PDF->SetFont('Arial', '', 6);
                    $PDF->Cell(60, 3, '(+)Mora/Multa/Juros', 'L', 1, 'L');
                }
            }
        }

        $PDF->SetFont('Arial', '', 7);
        $PDF->Cell(130, 5, '', 'L', 0, 'L');
        $PDF->Cell(60, 5, '', 'LB', 1, 'R');

        $PDF->Cell(130, 3, '', 'L', 0, 'L');
        $PDF->SetFont('Arial', '', 6);
        $PDF->Cell(60, 3, utf8_decode('(+)Outros acréscimos'), 'L', 1, 'L');

        $PDF->SetFont('Arial', '', 7);
        $PDF->Cell(130, 5, '', 'L', 0, 'L');
        $PDF->Cell(60, 5, '', 'LB', 1, 'R');

        $PDF->Cell(130, 3, '', 'L', 0, 'L');
        $PDF->SetFont('Arial', '', 6);
        $PDF->Cell(60, 3, '(=)Valor cobrado', 'L', 1, 'L');
        $PDF->SetFont('Arial', '', 7);
        $PDF->Cell(130, 5, '', 'LB', 0, 'L');
        $PDF->Cell(60, 5, '', 'LB', 1, 'R');

        $PDF->SetFont('Arial', '', 6);
        $PDF->Cell(190, 3, 'Pagador', 'L', 1, 'L');

        $PDF->SetFont('Arial', '', 7);
        $PDF->Cell(190, 5, $financeiro_cpf_cnpj_sacado.' - '.utf8_decode($financeiro_nome_sacado), 'L', 1, 'L');
        $PDF->Cell(
            190,
            5,
            utf8_decode(
                $financeiro_endereco_sacado. "," . $financeiro_numero_sacado . ", ". $financeiro_bairro_sacado
            ),
            'L',
            1,
            'L'
        );
        $PDF->Cell(
            190,
            5,
            utf8_decode(
                $financeiro_cidade_sacado . " - " . $financeiro_estado_sacado . " - CEP: " . $financeiro_cep_sacado
            ),
            'BL',
            1,
            'L'
        );

        $PDF->SetFont('Arial', '', 6);
        $PDF->Cell(170, 3, 'Sacado/Avalista', '', 0, 'L');
        $PDF->Cell(20, 3, utf8_decode('Autênticação Mecânica - Ficha de Compensação'), '', 1, 'R');

        fbarcode($financeiro_codigo_barras, $PDF);

        $PDF->Ln(10);
        $PDF->SetY(260);
    }
}
$PDF->Output();
ob_end_flush(); 
