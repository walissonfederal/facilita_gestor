<?php
session_start();
set_time_limit(0);
require_once 'Crud.php';
function diff_dias($date_inicial, $date_final) {
    // Define os valores a serem usados
    $data_inicial = $date_inicial;
    $data_final = $date_final;
    // Usa a função strtotime() e pega o timestamp das duas datas:
    $time_inicial = strtotime($data_inicial);
    $time_final = strtotime($data_final);
    // Calcula a diferença de segundos entre as duas datas:
    $diferenca = $time_final - $time_inicial; // 19522800 segundos
    // Calcula a diferença de dias
    $dias = (int) floor($diferenca / (60 * 60 * 24)); // 225 dias
    return $dias;
}
function return_valor_total_faturamento($post_id_faturamento){
    $read_faturamento = ReadComposta("SELECT faturamento_referencia FROM faturamento WHERE faturamento_id = '".$post_id_faturamento."'");
    if(NumQuery($read_faturamento) > '0'){
        foreach ($read_faturamento as $read_faturamento_view);
    }
    
    $read_itens_faturamento = Read('itens_faturamento', "WHERE itens_faturamento_id_faturamento = '".$post_id_faturamento."'");
    if(NumQuery($read_itens_faturamento) > '0'){
        foreach($read_itens_faturamento as $read_itens_faturamento_view){
            $data_ativacao_pedido = GetReg('pedido', 'pedido_data_ativacao', "WHERE pedido_id = '".$read_itens_faturamento_view['itens_faturamento_id_pedido']."'");
            $read_pedido_desinstalacao = Read('pedido_desinstalacao', "WHERE pedido_desinstalacao_id_chip = '".$read_itens_faturamento_view['itens_faturamento_id_chip']."'");
            if(NumQuery($read_pedido_desinstalacao) > '0'){
                foreach($read_pedido_desinstalacao as $read_pedido_desinstalacao_view){
                    $verificacao_cobrar_no_cobrar = return_cobrar_no_cobrar($read_faturamento_view['faturamento_referencia'], $data_ativacao_pedido);
                    if($read_pedido_desinstalacao_view['pedido_desinstalacao_cobrar'] == $read_faturamento_view['faturamento_referencia']){
                        $valor_multa_chip += $read_pedido_desinstalacao_view['pedido_desinstalacao_valor_total'];
                    }else{
                        $valor_multa_chip += '0';
                    }
                }
            }
            if($read_itens_faturamento_view['itens_faturamento_tipo'] == '0'){
                $valor_cobrado += $read_itens_faturamento_view['itens_faturamento_valor_cobrado'];
                $valor_ativacao += $read_itens_faturamento_view['itens_faturamento_valor_ativacao'];
            }elseif($read_itens_faturamento_view['itens_faturamento_tipo'] == '1'){
                $valor_cobrado_ciclo += $read_itens_faturamento_view['itens_faturamento_valor_cobrado'];
            }elseif($read_itens_faturamento_view['itens_faturamento_tipo'] == '2'){
                $valor_cobrado_sms += $read_itens_faturamento_view['itens_faturamento_valor_cobrado'];
            }elseif($read_itens_faturamento_view['itens_faturamento_tipo'] == '3'){
                $valor_cobrado_correios += $read_itens_faturamento_view['itens_faturamento_valor_cobrado'];
            }elseif($read_itens_faturamento_view['itens_faturamento_tipo'] == '5'){
                $valor_cobrado_sms_excedente += $read_itens_faturamento_view['itens_faturamento_valor_cobrado'];
            }
        }
    }
    $valor_total = $valor_cobrado + $valor_ativacao + $valor_cobrado_ciclo + $valor_cobrado_sms + $valor_cobrado_correios + $valor_multa_chip + $valor_cobrado_sms_excedente;
    return $valor_total;
}
function return_cobrar_no_cobrar($referencia, $data_ativacao){
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
function return_ciclo_chip($data_pedido, $data_encerramento, $mes_ano){
    $mes_ano_explode = explode("/", $mes_ano);
    $mes_ano_pedido_explode = explode("-", $data_pedido);
    $fat_cobranca_verificacao = $mes_ano_pedido_explode['0'].'-'.$mes_ano_pedido_explode['1'].'-01';
    $fat_mes_ano_verificacao = $mes_ano_explode['1'].'-'.$mes_ano_explode['0'].'-01';
    if($data_pedido > $data_encerramento){
        $verificao_correta = '0';
    }else{
        if($fat_cobranca_verificacao < $fat_mes_ano_verificacao){
            $verificao_correta = '0';
        }else{
            $verificao_correta = '1';
        }
    }
    return $verificao_correta;
}
function linha_tempo_chip($id_chip, $operacao, $texto, $id_contato){
    $linha_tempo_chip['linha_tempo_chip_id_chip'] = $id_chip;
    $linha_tempo_chip['linha_tempo_chip_data_hora'] = date('Y-m-d H:i:s');
    $linha_tempo_chip['linha_tempo_chip_operacao'] = $operacao;
    $linha_tempo_chip['linha_tempo_chip_texto'] = $texto;
    $linha_tempo_chip['linha_tempo_chip_id_user'] = $_SESSION[VSESSION]['user_id'];
    $linha_tempo_chip['linha_tempo_chip_id_contato'] = $id_contato;
    Create('linha_tempo_chip', $linha_tempo_chip);
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
/**
 * FAZ O INNER JOIN DAS TABELAS
 * */
function GetDados($Tabela, $GetId, $CampoPegar, $CampoBuscar) {
    $IdDados = addslashes($GetId);
    $readGetDados = Read($Tabela, "WHERE {$CampoPegar} = '" . $IdDados . "'");
    $CountGetDados = NumQuery($readGetDados);
    if ($CountGetDados > '0') {
        foreach($readGetDados as $readGetDadosView);
        return $readGetDadosView[$CampoBuscar];
    }else{
        echo '';
    }
}

function GetEmpresa($get_name){
    $read_empresa = ReadComposta("SELECT ".$get_name." FROM empresa WHERE empresa_id = '1'");
    if(NumQuery($read_empresa) > '0'){
        foreach($read_empresa as $read_empresa_view);
        return $read_empresa_view[$get_name];
    }
}

function GetReg($Tabela, $Coluna, $Where){
    $read_reg = ReadComposta("SELECT $Coluna FROM $Tabela {$Where} ORDER BY $Coluna DESC LIMIT 1");
    if(NumQuery($read_reg) > '0'){
        foreach($read_reg as $read_reg_view);
        return $read_reg_view[$Coluna];
    }
}

function GetPermMenu($id_nivel, $id_menu){
    $read_permissao_menu = ReadComposta("SELECT * FROM permissao_menu WHERE permissao_menu_id_menu = '".$id_menu."' AND permissao_menu_id_nivel = '".$id_nivel."'");
    if(NumQuery($read_permissao_menu) > '0'){
        return true;
    }else{
        return false;
    }
}


/**
 * faz o script
**/
function Script($Msg){
   echo "<script>alert('$Msg')</script>";
}
/**
 * FAZ O REDIMENCIONAMENTO
**/
function Redimencionamento($Url){
    echo "<script>window.location = '$Url'</script>";
}

/**
 * FAZ PAGINAÇÃO DOS RESULTADOS
**/
function PaginatorResult($Tabela, $Condicao, $Maximo, $Link, $Pag, $Width = NULL, $MaxLinks = 2){
    $readPaginacao = Read($Tabela,"{$Condicao}");
    $Total = NumQuery($readPaginacao);
    if($Total > $Maximo){
        $Paginas = ceil($Total/$Maximo);
        echo '<ul class="pagination">';
        echo '<li><a href="'.$Link.'1">Primeira Pagina</a></li>';
        for($i = $Pag - $MaxLinks; $i <= $Pag - 1; $i++){
            if($i >= 1){
                    echo '<li><a href="'.$Link.$i.'">'.$i.'</a></li>';
            }
        }
        echo '<li class="active"><a href="#">'.$Pag.'</a></li>';
        for($i = $Pag + 1; $i <= $Pag + $MaxLinks; $i++){
            if($i <= $Paginas){
                    echo '<li><a href="'.$Link.$i.'">'.$i.'</a></li>';
            }
        }
        echo '<li><a href="'.$Link.$Paginas.'">Ultima Pagina</a></li>';
        echo '</ul>';
    }
}

function FormDataBr($Data){
    $Date = strftime("%d/%m/%Y", strtotime($Data));
    return $Date;
}

function FormDataBrTudo($Data){
    $Date = strftime("%d/%m/%Y %H:%M:%S", strtotime($Data));
    return $Date;
}

function FormatMoney($money){
    $pegamoney = number_format($money,2,",",".");
    return $pegamoney;
}

function FormatEUA($Data){
    $Date = explode('/', $Data);
    $Dia = $Date['0'];
    $Mes = $Date['1'];
    $Ano = $Date['2'];
    
    $Resultado = $Ano.'-'.$Mes.'-'.$Dia;
    return $Resultado;
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

function Endereco($Latitude, $Longitude){
    $readEndereco = Read('endereco', "WHERE Lat = '".$Latitude."' AND '".$Longitude."'");
    if(NumQuery($readEndereco) > '0'){
        foreach($readEndereco as $readEnderecoView);
        echo $readEnderecoView['Endereco'];
    }
}
function EnderecoJson($Latitude, $Longitude){
    $readEndereco = Read('endereco', "WHERE Lat = '".$Latitude."' AND '".$Longitude."'");
    if(NumQuery($readEndereco) > '0'){
        foreach($readEndereco as $readEnderecoView);
        return $readEnderecoView['Endereco'];
    }
}

function CreateEndereco($Latitude, $Longitude){
    $readEndereco = Read('endereco', "WHERE Lat = '".$Latitude."' AND '".$Longitude."'");
    if(NumQuery($readEndereco) == '0'){
        $EnderecoAproximado     = file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$Latitude.','.$Longitude.'&sensor=false');
        $DecoJson               = json_decode($EnderecoAproximado);
        $Rua                    = $DecoJson->results[0]->formatted_address;
        $Endereco['Lat']        = $Latitude;
        $Endereco['Lng']        = $Longitude;
        $Endereco['Endereco']   = $Rua;
        Create('endereco', $Endereco);
    }
}

function StrMoney($Money){
    $MoneyReturn = str_replace(',', '', $Money);
    return $MoneyReturn;
}

function DistanciaPontos($p1LA, $p1LO, $p2LA, $p2LO) {
    $r = 6371.0;

    $p1LA = $p1LA * pi() / 180.0;
    $p1LO = $p1LO * pi() / 180.0;
    $p2LA = $p2LA * pi() / 180.0;
    $p2LO = $p2LO * pi() / 180.0;

    $dif_latitude = $p2LA - $p1LA;
    $dif_longitude = $p2LO - $p1LO;

    $a = sin($dif_latitude / 2) * sin($dif_latitude / 2) + cos($p1LA) * cos($p2LA) * sin($dif_longitude / 2) * sin($dif_longitude / 2);
    $aa=sqrt($a);
    $ab=sqrt(1 - $a);
    $c = atan2($aa, $ab) * 2;

    $metros = round($r * $c * 1000);
    //$KM = $metros / 1000;
    return $metros;
}

function numeroEscrito($n) {
 
    $numeros[1][0] = '';
    $numeros[1][1] = 'um';
    $numeros[1][2] = 'dois';
    $numeros[1][3] = 'três';
    $numeros[1][4] = 'quatro';
    $numeros[1][5] = 'cinco';
    $numeros[1][6] = 'seis';
    $numeros[1][7] = 'sete';
    $numeros[1][8] = 'oito';
    $numeros[1][9] = 'nove';
 
    $numeros[2][0] = '';
    $numeros[2][10] = 'dez';
    $numeros[2][11] = 'onze';
    $numeros[2][12] = 'doze';
    $numeros[2][13] = 'treze';
    $numeros[2][14] = 'quatorze';
    $numeros[2][15] = 'quinze';
    $numeros[2][16] = 'dezesseis';
    $numeros[2][17] = 'dezesete';
    $numeros[2][18] = 'dezoito';
    $numeros[2][19] = 'dezenove';
    $numeros[2][2] = 'vinte';
    $numeros[2][3] = 'trinta';
    $numeros[2][4] = 'quarenta';
    $numeros[2][5] = 'cinquenta';
    $numeros[2][6] = 'sessenta';
    $numeros[2][7] = 'setenta';
    $numeros[2][8] = 'oitenta';
    $numeros[2][9] = 'noventa';
 
    $numeros[3][0] = '';
    $numeros[3][1] = 'cem';
    $numeros[3][2] = 'duzentos';
    $numeros[3][3] = 'trezentos';
    $numeros[3][4] = 'quatrocentos';
    $numeros[3][5] = 'quinhentos';
    $numeros[3][6] = 'seiscentos';
    $numeros[3][7] = 'setecentos';
    $numeros[3][8] = 'oitocentos';
    $numeros[3][9] = 'novecentos';
 
    $qtd = strlen($n);
 
    $compl[0] = ' mil ';
    $compl[1] = ' milhão ';
    $compl[2] = ' milhões ';
    $numero = "";
    $casa = $qtd;
    $pulaum = false;
    $x = 0;
    for ($y = 0; $y < $qtd; $y++) {
 
        if ($casa == 5) {
 
            if ($n[$x] == '1') {
 
                $indice = '1' . $n[$x + 1];
                $pulaum = true;
            } else {
 
                $indice = $n[$x];
            }
 
            if ($n[$x] != '0') {
 
                if (isset($n[$x - 1])) {
 
                    $numero .= ' e ';
                }
 
                $numero .= $numeros[2][$indice];
 
                if ($pulaum) {
 
                    $numero .= ' ' . $compl[0];
                }
            }
        }
 
        if ($casa == 4) {
 
            if (!$pulaum) {
 
                if ($n[$x] != '0') {
 
                    if (isset($n[$x - 1])) {
 
                        $numero .= ' e ';
                    }
                }
            }
 
            $numero .= $numeros[1][$n[$x]] . ' ' . $compl[0];
        }
 
        if ($casa == 3) {
 
            if ($n[$x] == '1' && $n[$x + 1] != '0') {
 
                $numero .= 'cento ';
            } else {
 
                if ($n[$x] != '0') {
 
                    if (isset($n[$x - 1])) {
 
                        $numero .= ' e ';
                    }
 
                    $numero .= $numeros[3][$n[$x]];
                }
            }
        }
 
        if ($casa == 2) {
 
            if ($n[$x] == '1') {
 
                $indice = '1' . $n[$x + 1];
                $casa = 0;
            } else {
 
                $indice = $n[$x];
            }
 
            if ($n[$x] != '0') {
 
                if (isset($n[$x - 1])) {
 
                    $numero .= ' e ';
                }
 
                $numero .= $numeros[2][$indice];
            }
        }
 
        if ($casa == 1) {
 
            if ($n[$x] != '0') {
                if ($numeros[1][$n[$x]] <= 10)
                    $numero .= ' ' . $numeros[1][$n[$x]];
                else
                    $numero .= ' e ' . $numeros[1][$n[$x]];
            } else {
 
                $numero .= '';
            }
        }
 
        if ($pulaum) {
 
            $casa--;
            $x++;
            $pulaum = false;
        }
 
        $casa--;
        $x++;
    }
 
    return $numero;
}

function escreverValorMoeda($n){
    //Converte para o formato float 
    if(strpos($n, ',') !== FALSE){
        $n = str_replace('.','',$n); 
        $n = str_replace(',','.',$n);
    }
 
    //Separa o valor "reais" dos "centavos"; 
    $n = explode('.',$n);
 
    return ucfirst(numeroEscrito($n[0])). ' reais' . ((isset($n[1]) && $n[1] > 0)?' e '.numeroEscrito($n[1]).' centavos.':'');
 
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
function ValCnpj( $cnpj ) {
    $cnpj = preg_replace( '/[^0-9]/', '', $cnpj );
    
    $cnpj = (string)$cnpj;
    
    $cnpj_original = $cnpj;
    
    $primeiros_numeros_cnpj = substr( $cnpj, 0, 12 );
    
    if ( ! function_exists('multiplica_cnpj') ) {
        function multiplica_cnpj( $cnpj, $posicao = 5 ) {
            $calculo = 0;
            
            for ( $i = 0; $i < strlen( $cnpj ); $i++ ) {
                $calculo = $calculo + ( $cnpj[$i] * $posicao );
                
                $posicao--;
                
                if ( $posicao < 2 ) {
                    $posicao = 9;
                }
            }
            return $calculo;
        }
    }
    
    $primeiro_calculo = multiplica_cnpj( $primeiros_numeros_cnpj );
    
    $primeiro_digito = ( $primeiro_calculo % 11 ) < 2 ? 0 :  11 - ( $primeiro_calculo % 11 );
    
    $primeiros_numeros_cnpj .= $primeiro_digito;
 
    $segundo_calculo = multiplica_cnpj( $primeiros_numeros_cnpj, 6 );
    $segundo_digito = ( $segundo_calculo % 11 ) < 2 ? 0 :  11 - ( $segundo_calculo % 11 );
    
    $cnpj = $primeiros_numeros_cnpj . $segundo_digito;
    
    if ( $cnpj === $cnpj_original ) {
        return true;
    }
}

function geraTimestamp($data) {
    $partes = explode('/', $data);
    return mktime(0, 0, 0, $partes[1], $partes[0], $partes[2]);
}
function DiferencaDias($DataInicial, $DataFinal){
    // Define os valores a serem usados
    $data_inicial = $DataInicial;
    $data_final = $DataFinal;
    // Cria uma função que retorna o timestamp de uma data no formato DD/MM/AAAA
    
    // Usa a função criada e pega o timestamp das duas datas:
    $time_inicial = geraTimestamp($data_inicial);
    $time_final = geraTimestamp($data_final);
    // Calcula a diferença de segundos entre as duas datas:
    $diferenca = $time_final - $time_inicial; // 19522800 segundos
    // Calcula a diferença de dias
    $dias = (int)floor( $diferenca / (60 * 60 * 24)); // 225 dias
    
    return $dias;
}
function diasemana($data) {
    $ano =  substr($data, 6, 4);
    $mes =  substr($data, 3, 2);
    $dia =  substr($data, 0, 2);

    $diasemana = date("w", mktime(0,0,0,$mes,$dia,$ano) );

    /*switch($diasemana) {
            case"0": $diasemana = "Domingo";       break;
            case"1": $diasemana = "Segunda-Feira"; break;
            case"2": $diasemana = "Terça-Feira";   break;
            case"3": $diasemana = "Quarta-Feira";  break;
            case"4": $diasemana = "Quinta-Feira";  break;
            case"5": $diasemana = "Sexta-Feira";   break;
            case"6": $diasemana = "Sábado";        break;
    }*/

    return $diasemana;
}
function sendMail($assunto,$mensagem,$remetente,$nomeRemetente,$destino,$nomeDestino, $reply = NULL, $replyNome = NULL, $anexo = NULL, $nomeAnexo = NULL){
		
    require_once('mail/class.phpmailer.php'); //Include pasta/classe do PHPMailer

    $mail = new PHPMailer(); //INICIA A CLASSE
    $mail->IsSMTP(); //Habilita envio SMPT
    $mail->SMTPAuth = true; //Ativa email autenticado
    //$mail->SMTPSecure = 'ssl';	// SSL REQUERIDO pelo GMail
    $mail->IsHTML(true);
	
    $mail->SMTPDebug = 2;
    $mail->Host = 'smtplw.com.br'; //Servidor de envio
    $mail->Port = '587'; //Porta de envio
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
function sendMailCampanha($assunto,$mensagem,$remetente,$nomeRemetente,$destino,$nomeDestino, $reply = NULL, $replyNome = NULL, $anexo = NULL, $nomeAnexo = NULL){
		
    require_once('mail/class.phpmailer.php'); //Include pasta/classe do PHPMailer

    $mail = new PHPMailer(); //INICIA A CLASSE
    $mail->IsSMTP(); //Habilita envio SMPT
    $mail->SMTPAuth = true; //Ativa email autenticado
    //$mail->SMTPSecure = 'ssl';	// SSL REQUERIDO pelo GMail
    $mail->IsHTML(true);

    $mail->Host = 'smtplw.com.br'; //Servidor de envio
    $mail->Port = '587'; //Porta de envio
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
        //echo "<pre>";
        //die(print_r($mail));
    }
}
function valMail($email){
    if(preg_match('/[a-z0-9_\.\-]+@[a-z0-9_\.\-]*[a-z0-9_\.\-]+\.[a-z]{2,4}$/',$email)){
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



//funções de integração com a d4sign


function d4sign_gerar_contrato_chip($id_contrato){
    $read_contrato_chip = ReadComposta("SELECT contato.contato_nome_razao, contato.contato_nome_fantasia, contato.contato_cpf_cnpj, contato.contato_cep, contato.contato_endereco, contato.contato_numero, contato.contato_bairro, contato.contato_estado, contato.contato_cidade, contato.contato_telefone FROM contrato_chip INNER JOIN contato ON contato.contato_id = contrato_chip.contrato_chip_id_contato WHERE contrato_chip.contrato_chip_id = '".$id_contrato."'");
    if(NumQuery($read_contrato_chip) > '0'){
        foreach($read_contrato_chip as $read_contrato_chip_view);
    }
    $templates = array(
        ID_CONTRATO_CHIP_D4SIGN => array(
            'CLIENTE_NOME_RAZAO_SOCIAL' => $read_contrato_chip_view['contato_nome_razao'],
            'CLIENTE_NOME_FANTASIA' => $read_contrato_chip_view['contato_nome_fantasia'],
            'CLIENTE_ENDERECO' => $read_contrato_chip_view['contato_endereco'],
            'CLIENTE_NUMERO' => $read_contrato_chip_view['contato_numero'],
            'CLIENTE_BAIRRO' => $read_contrato_chip_view['contato_bairro'],
            'CLIENTE_CIDADE' => $read_contrato_chip_view['contato_cidade'],
            'CLIENTE_ESTADO' => $read_contrato_chip_view['contato_estado'],
            'CLIENTE_CEP' => $read_contrato_chip_view['contato_cep'],
            'CLIENTE_CNPJ_CPF' => $read_contrato_chip_view['contato_cpf_cnpj'],
            'CLIENTE_TELEFONE' => $read_contrato_chip_view['contato_telefone']
        )
    );

    $name_document = "Contrato Chip";
    $uuid_cofre = ID_COFRE_CONTRATO_CHIP_D4SIGN;

    $return = $client->documents->makedocumentbytemplate($uuid_cofre, $name_document, $templates);
    if($return->message == 'success'){
        $UPDATE_CONTRATO_CHIP['contrato_chip_id_d4sign'] = $return->uuid;
        Update('contrato_chip', $UPDATE_CONTRATO_CHIP, "WHERE contrato_chip_id = '".$id_contrato."'");
        return true;
    }else{
        return false;
    }
}
function valor_plano_compensacao($valor_total){
    $html = '<ul style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">
        <li>O valor de pagamento do ressarcimento é de até R$ '. FormatMoney($valor_total).' ('. escreverValorMoeda($valor_total).') e será concedido em forma de consertos, pela própria Federal Sistemas, de estruturas danificadas (portas, armários e afins), na forma de reposição de mercadorias e produtos eletrônicos furtadas (apresentando nota fiscal em nome da Contratante), serviços de alvenaria e serralheria;</li>
        <li>A Federal Sistemas realizará, junto ao Contratante, uma análise sobre os danos materiais e financeiros oriundos do furto para verificar o que poderá ser reembolsado pelo programa contratado;</li>
        <li>O programa não cobre furto de valores (dinheiro) em espécie, somente extravio de produtos e danificações físicas no local monitorado;</li>
        <li>Para ter direito ao reembolso, o furto no local monitorado deverá ter ocorrido em horário que o alarme do ambiente esteja acionado;</li>
        <li>Perderá o direito de receber o ressarcimento o cliente que estiver em débito, com pagamentos pendentes de mensalidade e outros, junto à Federal Sistemas;</li>
    </ul>';
    return $html;
}
function convert_number_to_words($number) {

    $hyphen      = '-';
    $conjunction = ' e ';
    $separator   = ', ';
    $negative    = 'menos ';
    $decimal     = ' ponto ';
    $dictionary  = array(
        0                   => 'zero',
        1                   => 'um',
        2                   => 'dois',
        3                   => 'três',
        4                   => 'quatro',
        5                   => 'cinco',
        6                   => 'seis',
        7                   => 'sete',
        8                   => 'oito',
        9                   => 'nove',
        10                  => 'dez',
        11                  => 'onze',
        12                  => 'doze',
        13                  => 'treze',
        14                  => 'quatorze',
        15                  => 'quinze',
        16                  => 'dezesseis',
        17                  => 'dezessete',
        18                  => 'dezoito',
        19                  => 'dezenove',
        20                  => 'vinte',
        30                  => 'trinta',
        40                  => 'quarenta',
        50                  => 'cinquenta',
        60                  => 'sessenta',
        70                  => 'setenta',
        80                  => 'oitenta',
        90                  => 'noventa',
        100                 => 'cento',
        200                 => 'duzentos',
        300                 => 'trezentos',
        400                 => 'quatrocentos',
        500                 => 'quinhentos',
        600                 => 'seiscentos',
        700                 => 'setecentos',
        800                 => 'oitocentos',
        900                 => 'novecentos',
        1000                => 'mil',
        1000000             => array('milhão', 'milhões'),
        1000000000          => array('bilhão', 'bilhões'),
        1000000000000       => array('trilhão', 'trilhões'),
        1000000000000000    => array('quatrilhão', 'quatrilhões'),
        1000000000000000000 => array('quinquilhão', 'quinquilhões')
    );

    if (!is_numeric($number)) {
        return false;
    }

    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
        // overflow
        trigger_error(
            'convert_number_to_words só aceita números entre ' . PHP_INT_MAX . ' à ' . PHP_INT_MAX,
            E_USER_WARNING
        );
        return false;
    }

    if ($number < 0) {
        return $negative . convert_number_to_words(abs($number));
    }

    $string = $fraction = null;

    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    }

    switch (true) {
        case $number < 21:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens   = ((int) ($number / 10)) * 10;
            $units  = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= $conjunction . $dictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds  = floor($number / 100)*100;
            $remainder = $number % 100;
            $string = $dictionary[$hundreds];
            if ($remainder) {
                $string .= $conjunction . convert_number_to_words($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int) ($number / $baseUnit);
            $remainder = $number % $baseUnit;
            if ($baseUnit == 1000) {
                $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[1000];
            } elseif ($numBaseUnits == 1) {
                $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit][0];
            } else {
                $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit][1];
            }
            if ($remainder) {
                $string .= $remainder < 100 ? $conjunction : $separator;
                $string .= convert_number_to_words($remainder);
            }
            break;
    }

    if (null !== $fraction && is_numeric($fraction)) {
        $string .= $decimal;
        $words = array();
        foreach (str_split((string) $fraction) as $number) {
            $words[] = $dictionary[$number];
        }
        $string .= implode(' ', $words);
    }

    return $string;
}
function email_convertido($assunto_email, $texto_email){
    $MSG_1 = str_replace('#NOMEEMPRESA#', GetEmpresa('empresa_nome_razao'), MSGEMAIL);
    $MSG_2 = str_replace('#IMGEMPRESA#', GetEmpresa('empresa_logo'), $MSG_1);
    $MSG_3 = str_replace('#TITULOMAIL#', $assunto_email, $MSG_2);
    $MSG_4 = str_replace('#MSGMAIL#', $texto_email, $MSG_3);
    $MSG_5 = str_replace('#MAILEMPRESA#', GetEmpresa('empresa_email'), $MSG_4);
    $MSG_6 = str_replace('#FONEEMPRESA#', GetEmpresa('empresa_telefone'), $MSG_5);
    $MSG_7 = str_replace('#LINKBOLETO#', '<a href="http://federalsistemas.com.br/facilita_gestor" target="_blank">Clique Aqui</a>', $MSG_6);
    return $MSG_7;
}
function ReturnCepCompleto($cep){
    $token = 'acb3020a0b603e028722896569a757e9';
    $url = 'http://www.cepaberto.com/api/v2/ceps.json?cep=' . $cep;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Token token="' . $token . '"'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    return $output;
}
?>