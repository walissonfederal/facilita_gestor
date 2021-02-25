<?php

require_once 'Crud.php';

function FormatMoney($Valor) {
    return number_format($Valor, 2, ",", ".");
}

function FormatDataBr($Data) {
    $Date = strftime("%d/%m/%Y", strtotime($Data));
    return $Date;
}
function FormatData($Data) {
    $Date = explode("/", $Data);
    return $Date['2'].'-'.$Date['1'].'-'.$Date['0'];
}
function FormatEUA($Data){
    $Date = explode('/', $Data);
    $Dia = $Date['0'];
    $Mes = $Date['1'];
    $Ano = $Date['2'];
    
    $Resultado = $Ano.'-'.$Mes.'-'.$Dia;
    return $Resultado;
}

function GetDados($Tabela, $GetId, $CampoPegar, $CampoBuscar) {
    $IdDados = addslashes($GetId);
    $readGetDados = ReadComposta("SELECT {$CampoBuscar} FROM {$Tabela} WHERE {$CampoPegar} = '" . $IdDados . "'");
    $CountGetDados = NumQuery($readGetDados);
    if ($CountGetDados > '0') {
        foreach ($readGetDados as $readGetDadosView)
            ;
        return $readGetDadosView[$CampoBuscar];
    } else {
        echo '';
    }
}

if (!function_exists('calculaFrete')) {

    function calculaFrete(
    $cod_servico, /* codigo do servico desejado */ $cep_origem, /* cep de origem, apenas numeros */ $cep_destino, /* cep de destino, apenas numeros */ $peso, /* valor dado em Kg incluindo a embalagem. 0.1, 0.3, 1, 2 ,3 , 4 */ $altura, /* altura do produto em cm incluindo a embalagem */ $largura, /* altura do produto em cm incluindo a embalagem */ $comprimento, /* comprimento do produto incluindo embalagem em cm */ $valor_declarado = '0' /* indicar 0 caso nao queira o valor declarado */
    ) {

        $cod_servico = strtoupper($cod_servico);
        if ($cod_servico == 'SEDEX10')
            $cod_servico = 40215;
        if ($cod_servico == 'SEDEXACOBRAR')
            $cod_servico = 40045;
        if ($cod_servico == 'SEDEX')
            $cod_servico = 40010;
        if ($cod_servico == 'PAC')
            $cod_servico = 41106;

        # ###########################################
        # Código dos Principais Serviços dos Correios
        # 41106 PAC sem contrato
        # 40010 SEDEX sem contrato
        # 40045 SEDEX a Cobrar, sem contrato
        # 40215 SEDEX 10, sem contrato
        # ###########################################

        $correios = "http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx?nCdEmpresa=&sDsSenha=&sCepOrigem=" . $cep_origem . "&sCepDestino=" . $cep_destino . "&nVlPeso=" . $peso . "&nCdFormato=1&nVlComprimento=" . $comprimento . "&nVlAltura=" . $altura . "&nVlLargura=" . $largura . "&sCdMaoPropria=n&nVlValorDeclarado=" . $valor_declarado . "&sCdAvisoRecebimento=n&nCdServico=" . $cod_servico . "&nVlDiametro=0&StrRetorno=xml";

        $xml = simplexml_load_file($correios);

        $_arr_ = array();
        if ($xml->cServico->Erro == '0'):
            $_arr_['codigo'] = (string) $xml->cServico->Codigo;
            $_arr_['valor'] = $xml->cServico->Valor;
            $_arr_['prazo'] = (string) $xml->cServico->PrazoEntrega . ' Dias';
            // return $xml->cServico->Valor;
            return $_arr_;
        else:
            return false;
        endif;
    }

}

function busca_cep($cep) {
    $resultado = file_get_contents('http://republicavirtual.com.br/web_cep.php?cep=' . urlencode($cep) . '&formato=query_string');
    if (!$resultado) {
        $resultado = "&resultado=0&resultado_txt=erro+ao+buscar+cep";
    }
    parse_str($resultado, $retorno);
    return $retorno;
}

function GetRegUlt($Tabela, $CampoBuscar) {
    $read_ultimo_dado = ReadComposta("SELECT $CampoBuscar FROM {$Tabela} ORDER BY {$CampoBuscar} DESC LIMIT 1");
    if (NumQuery($read_ultimo_dado) > '0') {
        foreach ($read_ultimo_dado as $read_ultimo_dado_view)
            ;
        return $read_ultimo_dado_view[$CampoBuscar];
    }
}
function GetReg($Tabela, $Coluna, $Where){
    $read_reg = ReadComposta("SELECT $Coluna FROM $Tabela {$Where} ORDER BY $Coluna DESC LIMIT 1");
    if(NumQuery($read_reg) > '0'){
        foreach($read_reg as $read_reg_view);
        return $read_reg_view[$Coluna];
    }
}
function formataData($data) {
    if($data == "00000000" or $data == "000000")
            return "";

    if(trim($data)=="")
            return "";
    //formata a data para o padr�o americano MM/DD/AAAA ou MM/DD/AA (dependendo do tamanho da string $data)
    $iano = 4; //posicao onde inicia o ano
    $data =  substr($data, 2, 2) . "/". substr($data, 0, 2) . "/" . substr($data, $iano, strlen($data)-$iano);

    //formata a data, a partir do padr�o americano, para o padr�o DD/MM/AAAA
    return date("d/m/Y", strtotime($data));
}

function StrValor($Valor) {
    $retorno = str_replace('.', '', $Valor);
    $retorno_dois = str_replace(',', '.', $retorno);
    return $retorno_dois;
}

function PaginatorResult($Tabela, $Condicao, $Maximo, $Link, $Pag, $Width = NULL, $MaxLinks = 2) {
    $readPaginacao = Read($Tabela, "{$Condicao}");
    $Total = NumQuery($readPaginacao);
    if ($Total > $Maximo) {
        $Paginas = ceil($Total / $Maximo);
        echo '<ul class="pagination">';
        echo '<li><a href="' . $Link . '1">Primeira Pagina</a></li>';
        for ($i = $Pag - $MaxLinks; $i <= $Pag - 1; $i++) {
            if ($i >= 1) {
                echo '<li><a href="' . $Link . $i . '">' . $i . '</a></li>';
            }
        }
        echo '<li class="active"><a href="#">' . $Pag . '</a></li>';
        for ($i = $Pag + 1; $i <= $Pag + $MaxLinks; $i++) {
            if ($i <= $Paginas) {
                echo '<li><a href="' . $Link . $i . '">' . $i . '</a></li>';
            }
        }
        echo '<li><a href="' . $Link . $Paginas . '">Ultima Pagina</a></li>';
        echo '</ul>';
    }
}

function CarregaSaldo($IdUser) {
    $valor_credito = '0';
    $valor_debito = '0';
    $read_saldo = ReadComposta("SELECT extrato_valor, extrato_tipo FROM extrato WHERE extrato_id_user = '" . $IdUser . "'");
    if (NumQuery($read_saldo) > '0') {
        foreach ($read_saldo as $read_saldo_view) {
            if ($read_saldo_view['extrato_tipo'] == 'C') {
                $valor_credito += $read_saldo_view['extrato_valor'];
            } else {
                $valor_debito += $read_saldo_view['extrato_valor'];
            }
        }
        $saldo_total = $valor_credito - $valor_debito;
    } else {
        $saldo_total = '0';
    }
    return $saldo_total;
}

function GerarComissao($IdUser, $Valor) {

    $read_user_pagou = ReadComposta("SELECT user_id, user_id_pai FROM user WHERE user_id = '" . $IdUser . "'");
    if (NumQuery($read_user_pagou) > '0') {
        foreach ($read_user_pagou as $read_user_pagou_view)
            ;
    }

    //GERAR COMISSAO NÍVEL 1
    $ValorComissao1 = (COMISSAO1 / 100) * $Valor;
    $ValorComissaoPadrao = (2 / 100) * $Valor;
    $read_comissao_1 = ReadComposta("SELECT user_id, user_id_pai FROM user WHERE user_id = '" . $read_user_pagou_view['user_id_pai'] . "'");
    if (NumQuery($read_comissao_1) > '0') {
        foreach ($read_comissao_1 as $read_comissao_1_view)
            ;
        
        $extrato_nivel1['extrato_data_hora'] = date('Y-m-d H:i:s');
        $extrato_nivel1['extrato_data'] = date('Y-m-d');
        $extrato_nivel1['extrato_id_user'] = $read_comissao_1_view['user_id'];
        $extrato_nivel1['extrato_tipo'] = 'C';
        $extrato_nivel1['extrato_valor'] = $ValorComissao1;
        $extrato_nivel1['extrato_descricao'] = 'Comissão aplicada 1º nível';
        $extrato_nivel1['extrato_tipologia'] = '0';
        Create('extrato', $extrato_nivel1);

        //GERAR COMISSAO NIVEL 2
        $ValorComissao2 = (COMISSAO2 / 100) * $Valor;
        $read_comissao_2 = ReadComposta("SELECT user_id, user_id_pai FROM user WHERE user_id = '" . $read_comissao_1_view['user_id_pai'] . "'");
        if (NumQuery($read_comissao_2) > '0') {
            foreach ($read_comissao_2 as $read_comissao_2_view)
                ;
            $extrato_nivel2['extrato_data_hora'] = date('Y-m-d H:i:s');
            $extrato_nivel2['extrato_data'] = date('Y-m-d');
            $extrato_nivel2['extrato_id_user'] = $read_comissao_2_view['user_id'];
            $extrato_nivel2['extrato_tipo'] = 'C';
            $extrato_nivel2['extrato_valor'] = $ValorComissao2;
            $extrato_nivel2['extrato_descricao'] = 'Comissão aplicada 2º nível';
            $extrato_nivel2['extrato_tipologia'] = '0';
            Create('extrato', $extrato_nivel2);

            //GERAR COMISSAO NIVEL 3
            $ValorComissao3 = (COMISSAO3 / 100) * $Valor;
            $read_comissao_3 = ReadComposta("SELECT user_id, user_id_pai FROM user WHERE user_id = '" . $read_comissao_2_view['user_id_pai'] . "'");
            if (NumQuery($read_comissao_3) > '0') {
                foreach ($read_comissao_3 as $read_comissao_3_view)
                    ;
                $extrato_nivel3['extrato_data_hora'] = date('Y-m-d H:i:s');
                $extrato_nivel3['extrato_data'] = date('Y-m-d');
                $extrato_nivel3['extrato_id_user'] = $read_comissao_3_view['user_id'];
                $extrato_nivel3['extrato_tipo'] = 'C';
                $extrato_nivel3['extrato_valor'] = $ValorComissao3;
                $extrato_nivel3['extrato_descricao'] = 'Comissão aplicada 3º nível';
                $extrato_nivel3['extrato_tipologia'] = '0';
                Create('extrato', $extrato_nivel3);

                //GERAR COMISSAO NIVEL 4
                $ValorComissao4 = (COMISSAO4 / 100) * $Valor;
                $read_comissao_4 = ReadComposta("SELECT user_id, user_id_pai FROM user WHERE user_id = '" . $read_comissao_3_view['user_id_pai'] . "'");
                if (NumQuery($read_comissao_4) > '0') {
                    foreach ($read_comissao_4 as $read_comissao_4_view)
                        ;

                    $extrato_nivel4['extrato_data_hora'] = date('Y-m-d H:i:s');
                    $extrato_nivel4['extrato_data'] = date('Y-m-d');
                    $extrato_nivel4['extrato_id_user'] = $read_comissao_4_view['user_id'];
                    $extrato_nivel4['extrato_tipo'] = 'C';
                    $extrato_nivel4['extrato_valor'] = $ValorComissao4;
                    $extrato_nivel4['extrato_descricao'] = 'Comissão aplicada 4º nível';
                    $extrato_nivel4['extrato_tipologia'] = '0';
                    Create('extrato', $extrato_nivel4);
                }
            }
        }
    }
}

function CarregaValorBloqueado($IdUser) {
    $read_saque = ReadComposta("SELECT SUM(saque_valor) AS Valor FROM saque WHERE saque_id_user = '" . $IdUser . "' AND saque_status = '0'");
    if (NumQuery($read_saque) > '0') {
        foreach ($read_saque as $read_saque_view)
            ;
        $ValorRetorno = $read_saque_view['Valor'];
    } else {
        $ValorRetorno = '0';
    }
    return $ValorRetorno;
}

function CarregaValorSacado($IdUser) {
    $read_saque = ReadComposta("SELECT SUM(saque_valor) AS Valor FROM saque WHERE saque_id_user = '" . $IdUser . "' AND saque_status = '1'");
    if (NumQuery($read_saque) > '0') {
        foreach ($read_saque as $read_saque_view)
            ;
        $ValorRetorno = $read_saque_view['Valor'];
    } else {
        $ValorRetorno = '0';
    }
    return $ValorRetorno;
}

function GanhoHoje($IdUser) {
    $read_extrato = ReadComposta("SELECT SUM(extrato_valor) AS Valor FROM extrato WHERE extrato_id_user = '" . $IdUser . "' AND extrato_data = '" . date('Y-m-d') . "' AND extrato_tipo = 'C'");
    if(NumQuery($read_extrato) > '0'){
        foreach($read_extrato as $read_extrato_view);
        $ValorRetorno = $read_extrato_view['Valor'];
    }else{
        $ValorRetorno = '0';
    }
    return $ValorRetorno;
}
function ContagemQuery($Tabela, $GetId, $CampoPegar, $CampoBuscar) {
    $IdDados = addslashes($GetId);
    $readGetDados = ReadComposta("SELECT {$CampoBuscar} FROM {$Tabela} WHERE {$CampoPegar} = '" . $IdDados . "' AND user_data = '".date('Y-m-d')."'");
    $CountGetDados = NumQuery($readGetDados);
    return $CountGetDados;
}
function CarregaRede($IdUser){
    $read_user_1 = ReadComposta("SELECT user_id FROM user WHERE user_id_pai = '".$IdUser."' AND user_data = '".date('Y-m-d')."'");
    if(NumQuery($read_user_1) > '0'){
        $count_nivel_1 = NumQuery($read_user_1);
        foreach($read_user_1 as $read_user_1_view){
            $read_user_2 = ReadComposta("SELECT user_id FROM user WHERE user_id_pai = '".$read_user_1_view['user_id']."' AND user_data = '".date('Y-m-d')."'");
            $count_nivel_2 = NumQuery($read_user_2);
            if(NumQuery($read_user_2) > '0'){
                foreach($read_user_2 as $read_user_2_view){
                    $read_user_3 = ReadComposta("SELECT user_id FROM user WHERE user_id_pai = '".$read_user_2_view['user_id']."' AND user_data = '".date('Y-m-d')."'");
                    $count_nivel_3 = NumQuery($read_user_3);
                    if(NumQuery($read_user_3) > '0'){
                        foreach($read_user_3 as $read_user_3_view){
                            $read_user_4 = ReadComposta("SELECT user_id FROM user WHERE user_id_pai = '".$read_user_3_view['user_id']."' AND user_data = '".date('Y-m-d')."'");
                            $count_nivel_4 = NumQuery($read_user_4);
                        }
                    }
                }
            }
        }
    }
    $count_dados = $count_nivel_1 + $count_nivel_2 + $count_nivel_3 + $count_nivel_4;
    if($count_dados){
        return $count_dados;
    }else{
        return 0;
    }
}
function CarregaRedeTotal($IdUser){
    $count_nivel_1_total = '0';
    $count_nivel_1_ativo = '0';
    $count_nivel_1_inativo = '0';
    $count_nivel_2_total = '0';
    $count_nivel_2_ativo = '0';
    $count_nivel_2_inativo = '0';
    $count_nivel_3_total = '0';
    $count_nivel_3_ativo = '0';
    $count_nivel_3_inativo = '0';
    $count_nivel_4_total = '0';
    $count_nivel_4_ativo = '0';
    $count_nivel_4_inativo = '0';
    $read_user_1 = ReadComposta("SELECT user_id, user_status FROM user WHERE user_id_pai = '".$IdUser."'");
    $count_nivel_1_total = NumQuery($read_user_1);
    if(NumQuery($read_user_1) > '0'){
        foreach($read_user_1 as $read_user_1_view){
            if($read_user_1_view['user_status'] == '0'){
                $count_nivel_1_inativo += 1;
            }elseif($read_user_1_view['user_status'] == '1'){
                $count_nivel_1_ativo += 1;
            }
            $read_user_2 = ReadComposta("SELECT user_id, user_status FROM user WHERE user_id_pai = '".$read_user_1_view['user_id']."' ");
            $count_nivel_2_total += NumQuery($read_user_2);
            if(NumQuery($read_user_2) > '0'){
                foreach($read_user_2 as $read_user_2_view){
                    if($read_user_2_view['user_status'] == '0'){
                        $count_nivel_2_inativo += 1;
                    }elseif($read_user_2_view['user_status'] == '1'){
                        $count_nivel_2_ativo += 1;
                    }
                    $read_user_3 = ReadComposta("SELECT user_id, user_status FROM user WHERE user_id_pai = '".$read_user_2_view['user_id']."'");
                    $count_nivel_3_total += NumQuery($read_user_3);
                    if(NumQuery($read_user_3) > '0'){
                        foreach($read_user_3 as $read_user_3_view){
                            if($read_user_3_view['user_status'] == '0'){
                                $count_nivel_3_inativo += 1;
                            }elseif($read_user_3_view['user_status'] == '1'){
                                $count_nivel_3_ativo += 1;
                            }
                            $read_user_4 = ReadComposta("SELECT user_id, user_status FROM user WHERE user_id_pai = '".$read_user_3_view['user_id']."'");
                            $count_nivel_4_total += NumQuery($read_user_4);
                            if(NumQuery($read_user_4) > '0'){
                                foreach($read_user_4 as $read_user_4_view){
                                    if($read_user_4_view['user_status'] == '0'){
                                        $count_nivel_4_inativo += 1;
                                    }elseif($read_user_4_view['user_status'] == '1'){
                                        $count_nivel_4_ativo += 1;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    $json_retorno = array(
        "comissao_1" => $count_nivel_1_total,
        "comissao_2" => $count_nivel_2_total,
        "comissao_3" => $count_nivel_3_total,
        "comissao_4" => $count_nivel_4_total,
        "comissao_ativo_1" => $count_nivel_1_ativo,
        "comissao_inativo_1" => $count_nivel_1_inativo,
        "comissao_ativo_2" => $count_nivel_2_ativo,
        "comissao_inativo_2" => $count_nivel_2_inativo,
        "comissao_ativo_3" => $count_nivel_3_ativo,
        "comissao_inativo_3" => $count_nivel_3_inativo,
        "comissao_ativo_4" => $count_nivel_4_ativo,
        "comissao_inativo_4" => $count_nivel_4_inativo
    );
    echo json_encode($json_retorno);
}
function GetAvatar($EmailBusca) {
    $email   = $EmailBusca; // e-mail de cadastro para pegar as imagens
    $default = 'https://www.samservicos.com.br/wp-content/uploads/2015/11/sem-imagem-avatar-300x300.png'; // imagem alternativa para se não existir
    $size    = 50; // tamanho da imagem
    $grav_url = "http://www.gravatar.com/avatar/" . md5( strtolower( trim( $email ) ) ) .
    "?d=" . urlencode( $default ) . "&s=" . $size;
    return $grav_url;
}
function ValCpf($cpf){
    $d1 = 0;
    $d2 = 0;
    
    $cpf = preg_replace("/[^0-9]/", "", $cpf);
    $ignore_list = array(
        '00000000000',
        '01234567890',
        '11111111111',
        '22222222222',
        '33333333333',
        '44444444444',
        '55555555555',
        '66666666666',
        '77777777777',
        '88888888888',
        '99999999999'
    );
    if(strlen($cpf) != 11 || in_array($cpf, $ignore_list)){
        return false;
    }else{
        for($i = 0; $i < 9; $i++){
            $d1 += $cpf[$i] * (10 - $i);
        }
        $r1 = $d1 % 11;
        $d1 = ($r1 > 1) ? (11 - $r1) : 0;
        for($i = 0; $i < 9; $i++) {
            $d2 += $cpf[$i] * (11 - $i);
        }
        $r2 = ($d2 + ($d1 * 2)) % 11;
        $d2 = ($r2 > 1) ? (11 - $r2) : 0;
        return (substr($cpf, -2) == $d1 . $d2) ? true : false;
    }
}
function sendMail($assunto,$mensagem,$remetente,$nomeRemetente,$destino,$nomeDestino, $reply = NULL, $replyNome = NULL, $anexo = NULL, $nomeAnexo = NULL){
		
    require_once('mail/class.phpmailer.php'); //Include pasta/classe do PHPMailer

    $mail = new PHPMailer(); //INICIA A CLASSE
    $mail->IsSMTP(); //Habilita envio SMPT
    $mail->SMTPAuth = true; //Ativa email autenticado
    $mail->SMTPSecure = 'ssl';	// SSL REQUERIDO pelo GMail
    $mail->IsHTML(true);

    $mail->Host = 'smtplw.com.br'; //Servidor de envio
    $mail->Port = '465'; //Porta de envio
    $mail->Username = 'federalsistemas'; //email para smtp autenticado
    $mail->Password = 'FeD468579!?'; //seleciona a porta de envio

    $mail->From = utf8_decode($remetente); //remtente
    $mail->FromName = utf8_decode($nomeRemetente); //remtetene nome

    if($reply != NULL){
        $mail->AddReplyTo(utf8_decode($reply),utf8_decode($replyNome));	
    }
    if($anexo != NULL){
        $mail->AddAttachment($anexo, $nomeAnexo);
    }

    $mail->Subject = utf8_decode($assunto); //assunto
    $mail->Body = utf8_decode($mensagem); //mensagem
    $mail->AddAddress(utf8_decode($destino),utf8_decode($nomeDestino)); //email e nome do destino

    if($mail->Send()){
        return true;
    }else{
        return false;
    }
}
function uploadFile($arquivo, $pasta, $tipos, $nome = null){
    if(isset($arquivo)){
        $infos = explode(".", $arquivo["name"]);
 
        if(!$nome){
            for($i = 0; $i < count($infos) - 1; $i++){
                $nomeOriginal = $nomeOriginal . $infos[$i] . ".";
            }
        }
        else{
            $nomeOriginal = $nome . ".";
        }
 
        $tipoArquivo = $infos[count($infos) - 1];
 
        $tipoPermitido = false;
        foreach($tipos as $tipo){
            if(strtolower($tipoArquivo) == strtolower($tipo)){
                $tipoPermitido = true;
            }
        }
        if(!$tipoPermitido){
            $retorno["erro"] = "Tipo não permitido";
        }else{
            $nome_arquivo = md5($nomeOriginal.rand(9,9999999));
            if(move_uploaded_file($arquivo['tmp_name'], $pasta . $nome_arquivo . '.' .$tipoArquivo)){
                $retorno["caminho"] = $pasta . $nome_arquivo. '.' . $tipoArquivo;
            }
            else{
                $retorno["erro"] = "Erro ao fazer upload";
            }
        }
    }
    else{
        $retorno["erro"] = "Arquivo nao setado";
    }
    return $retorno;
}
function ValMail($Email){
    if(preg_match('/[a-z0-9_\.\-]+@[a-z0-9_\.\-]*[a-z0-9_\.\-]+\.[a-z]{2,4}$/',$Email)){
        return true;
    }else{
        return false;
    }
}
function notification_ticket($titulo, $descricao, $id_user, $id_ticket){
    $notificacao_form['notificacao_titulo'] = $titulo;
    $notificacao_form['notificacao_descricao'] = $descricao;
    $notificacao_form['notificacao_status'] = '0';
    $notificacao_form['notificacao_id_user'] = $id_user;
    $notificacao_form['notificacao_data_hora'] = date('Y-m-d H:i:s');
    $notificacao_form['notificacao_id_ticket'] = $id_ticket;
    Create('notificacao', $notificacao_form);
}