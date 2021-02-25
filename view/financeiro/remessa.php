
<?php
# ******************************************************************************************************************************************************************
# Arquivo.......: Gerar-remessa-caixa-arquivo.php
# Autor.........: Alexandre Guimaraes Sarmento 
# Contatos......: (98) 99212-5970 / E-mail: alexandre890@yahoo.com.br
# Co-Autor......: Marcelo Cajaíba
# Funcao........: Gerar o arquivo de remessas padrão CNAB-240 da Caixa Economica Federal
# Atualizado em.: 21-01-2017
# Licenca.......: GPL
# ******************************************************************************************************************************************************************
?>

<?php

$getIdRemessa = $_GET['id'];
require_once '../../_class/Ferramenta.php';
# ******************************************************************************************************************************************************************
# NAO ALTERAR ==> FUNCOES DO SISTEMA
# ******************************************************************************************************************************************************************

function remover_acentos($str) 
{ 
  $a = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'Ð', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', '?', '?', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', '?', '?', 'L', 'l', 'N', 'n', 'N', 'n', 'N', 'n', '?', 'O', 'o', 'O', 'o', 'O', 'o', 'Œ', 'œ', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'Š', 'š', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Ÿ', 'Z', 'z', 'Z', 'z', 'Ž', 'ž', '?', 'ƒ', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', '?', '?', '?', '?', '?', '?', 'ç', 'Ç', "'" ); 
  $b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o','c','C', ' '); 
  return str_replace($a, $b, $str); 
} 

function post_slug($str) 
{ 
  return strtoupper(preg_replace(array('/[^a-zA-Z0-9 -]/', '/[ -]+/', '/^-|-$/'), 
  array('', '-', ''), remover_acentos($str))); 
} 

# ******************************************************************************************************************************************************************
/*Campos Numéricos (“Picture 9”)
• Alinhamento: sempre à direita, preenchido com zeros à esquerda, sem máscara de edição;
• Não utilizados: preencher com zeros.
*/
# ******************************************************************************************************************************************************************

function picture_9($palavra,$limite){
        $palavra_completa = trim($palavra);
	$var=str_pad($palavra_completa, $limite, "0", STR_PAD_LEFT);
	return $var;
}

# ******************************************************************************************************************************************************************
/*
Campos Alfanuméricos (“Picture X”)
• Alinhamento: sempre à esquerda, preenchido com brancos à direita;
• Não utilizados: preencher com brancos;
• Caracteres: maiúsculos, sem acentuação, sem ‘ç’, sem caracteres especiais.
*/
# ******************************************************************************************************************************************************************
	 
function picture_x( $palavra, $limite ){
	$var = str_pad( $palavra, $limite, " ", STR_PAD_RIGHT );
	$var = remover_acentos( $var );
	if( strlen( $palavra ) >= $limite ){
		$var = substr( $palavra, 0, $limite );
	}
	$var = strtoupper( $var );// converte em letra maiuscula
	return $var;
}	 

# ******************************************************************************************************************************************************************	 

function sequencial($i)
{
if($i < 10)
{
return zeros(0,5).$i;
}
else if($i > 10 && $i < 100)
{
return zeros(0,4).$i;
}
else if($i > 100 && $i < 1000)
{
return zeros(0,3).$i;
}
else if($i > 1000 && $i < 10000)
{
return zeros(0,2).$i;
}
else if($i > 10000 && $i < 100000)
{
return zeros(0,1).$i;
}
}

# ******************************************************************************************************************************************************************

function zeros($min,$max)
{
$x = ($max - strlen($min));
for($i = 0; $i < $x; $i++)
{
$zeros .= '0';
}
return $zeros.$min;
}

function complementoRegistro($int,$tipo)
{
if($tipo == "zeros")
{
$space = '';
for($i = 1; $i <= $int; $i++)
{
$space .= '0';
}
}
else if($tipo == "brancos")
{
$space = '';
for($i = 1; $i <= $int; $i++)
{
$space .= ' ';
}
}

return $space;
}

# ******************************************************************************************************************************************************************
# FIM DAS FUNCOES
# ******************************************************************************************************************************************************************


# ******************************************************************************************************************************************************************
# NAO ALTERAR ==> DADOS FIXOS - NAO ALTERAR
# ******************************************************************************************************************************************************************
# NAO ALTERAR ==> DADOS PARA A CRIACAO DO ARQUIVO
# ******************************************************************************************************************************************************************

$fusohorario     = 3; // como o servidor de hospedagem é a dreamhost pego o fuso para o horario do brasil
$timestamp       = mktime(date("H") - $fusohorario, date("i"), date("s"), date("m"), date("d"), date("Y"));
$DATAHORA['PT']  = gmdate("d/m/Y H:i:s", $timestamp);
$DATAHORA['EN']  = gmdate("Y-m-d H:i:s", $timestamp);
$DATA['PT']      = gmdate("d/m/Y", $timestamp);
$DATA['EN']      = gmdate("Y-m-d", $timestamp);
$DATA['DIA']     = gmdate("d",$timestamp);
$DATA['MES']     = gmdate("m",$timestamp);
$DATA['ANO']     = gmdate("Y",$timestamp);
$HORA            = gmdate("H:i:s", $timestamp);
$HORA1           = gmdate("His", $timestamp);

# ******************************************************************************************************************************************************************
# NAO ALTERAR ==> DADOS PARA A CRIACAO DO CONTEUDO DO ARQUIVO
# ******************************************************************************************************************************************************************

$conteudo        = '';                                    // conteudo do arquivo de remessa
$lote_sequencial = 1;                                     // nº do lote. Sempre sera = 1
$lote_servico    = 1;                                     // lote de servico. sera = 1
$header          = '';                                    // 1ª linha do arquivo = header
$header_lote     = '';                                    // 2ª linha do arquivo = header de lote
$linha_p         = '';                                    // segmento 3 - linha 'P'
$linha_q         = '';                                    // segmento 3 - linha 'Q'
$linha_r         = '';                                    // segmento 3 - linha 'R'
$linha_5         = '';                                    // Linha segmento 5
$linha_9         = '';                                    // Linha segmento 9
$conteudo_meio   = '';                                    // meio ou miolo do arquivo (linhas P+Q+R) 
$qtd_titulos     = 0;                                     // qtd. de titulos
$total_valor     = 0;                                     // valor total dos titulos

define("REMESSA",$PATH."",true);

# ******************************************************************************************************************************************************************
# ALTERE AQUI ==> * * * COLOQUE AQUI OS DADOS DA SUA EMPRESA E DA CONTA CORRENTE E ALTERE COMO PREFERIR * * * 
# ******************************************************************************************************************************************************************

$valor_multa                = 200; // 200 => 2,00 %       // porcentagem de multa com 2 casas decimais     
$carteira                   = 14;                         // codigo da carteira de cobranca registrada
$cpf_cnpj                   = '11655954000159';           // cnpj da empresa
$agencia                    = '1298';                     // agencia
$dv_agencia                 = '';                        // digito verificador da agencia
$codigo_beneficiario        = '273007';                   // Codigo do cedente / beneficiario
$empresa_beneficario        = 'FEDERAL SISTEMAS';        // nome da empresa
$numero_sequencial_arquivo  = $getIdRemessa;                          // Nº remessa tem que ser sequencial e unico
$xid_remessa                = picture_9($numero_sequencial_arquivo,7); // Nº da remessa
$arquivo                    = "E".$xid_remessa.".REM";    // nome do arquivo de remessa a ser gerado
$filename                   = $arquivo;                   // nome do arquivo de remessa a ser gerado


# ******************************************************************************************************************************************************************
# NAO ALTERAR ==> CRIANDO O CONTEUDO DO ARQUIVO
# ******************************************************************************************************************************************************************
# REGISTRO HEADER - ( TIPO 0 )
# PARTE 1
# ******************************************************************************************************************************************************************

$header .= '104';                                       // 01.0 -> Cod. do banco no caso da caixa = 104 
$header .= complementoRegistro(4,"zeros");              // 02.0 -> Cod. do lote
$header .= complementoRegistro(1,"zeros");              // 03.0 -> Tipo de Registro
$header .= complementoRegistro(9,"brancos");            // 04.0 -> CNAB literal remessa escr. extenso 003 009 X(07)
$header .= '2';                                         // 05.0 -> Tipo de inscrição do beneficiario : um se pessoa fisico (1) ou juridica (2)
$header .= picture_9($cpf_cnpj,14);                     // 06.0 -> Nº de Inscrição do  Beneficiario cpf ou cnpj
$header .= complementoRegistro(20,"zeros");             // 07.0 -> Uso exclusivo da caixa, preencher com zeros
$header .= picture_9($agencia,5);                       // 08.0 -> Cod. da agencia mantenedora da conta
$header .= picture_9($dv_agencia,1);                    // 09.0 -> Digito verificador
$header .= picture_9($codigo_beneficiario,6);           // 10.0 -> Cod. do beneficiário fornecido pelo banco - Nº do cedente
$header .= complementoRegistro(7,"zeros");              // 11.0 -> Uso exclusivo da caixa, preencher com 7 zeros
$header .= complementoRegistro(1,"zeros");              // 12.0 -> Uso exclusivo da caixa, preencher com 1 zeros
$header .= picture_x($empresa_beneficario,30);          // 13.0 -> Nome da empresa
$header .= picture_x('CAIXA ECONOMICA FEDERAL',30);     // 14.0 -> Nome do banco, neste caso: CAIXA ECONOMICA FEDERAL ate completar 30 espacos
$header .= complementoRegistro(10,"brancos");           // 15.0 -> 10 espaços em banco
$header .='1';                                          // 16.0 -> Cod. (1) = Remessa ou (2) = Retorno.
$header .= $DATA['DIA'].$DATA['MES'].$DATA['ANO'];      // 17.0 -> Data da geracao arquivo 
$header .= $HORA1;                                      // 18.0 -> Hora da geracao arquivo 
$header .= picture_9($numero_sequencial_arquivo,6);     // 19.0 -> Sequencial do arquivo um numero novo para cada arquivo de remessa que for gerado
$header .='050';                                        // 20.0 -> Nova versao da leitura
$header .= complementoRegistro(5,"zeros");              // 21.0 -> Densidade de Gravacao do Arquivo
$header .= complementoRegistro(20,"brancos");           // 22.0 -> Filler
$header .= picture_x('REMESSA-PRODUCAO',20);               // 23.0 -> Preencher com ‘REMESSA-TESTE' na fase de testes(simulado) ou REMESSA-PRODUCAO quando OK
$header .= complementoRegistro(4,"brancos");            // 24.0 -> Preencher com espacos
$header .= complementoRegistro(25,"brancos");           // 25.0 -> Preencher com espacos
$header .= chr(13).chr(10);                             // QUEBRA DE LINHA

# ******************************************************************************************************************************************************************
# NAO ALTERAR ==> CRIANDO O CONTEUDO DO ARQUIVO
# ******************************************************************************************************************************************************************
// DESCRICAO DE REGISTRO - ( TIPO 1 )
// HEADER DE LOTE DE ARQUIVO REMESSA
// PARTE 2
# ******************************************************************************************************************************************************************

$header_lote .= '104';                                       // 01.1 -> Cod. do banco, neste caso = 104
$header_lote .= picture_9($lote_servico,4);                  // 02.1 -> Lote de servico = igual ao campo 02.1 do header acima
$header_lote .='1';                                          // 03.1 -> Preencher '1’ (equivale a Header de Lote)
$header_lote .='R';                                          // 04.1 -> Preencher ‘R’ (equivale a Arquivo Remessa)
$header_lote .='01';                                         // 05.1 -> Preencher com ‘01', se Cobrança Registrada; ou ‘02’, se Cobrança Sem Registro/Serviços
$header_lote .= complementoRegistro(2,"zeros");              // 06.1 -> Preencher com zeros
$header_lote .='030';                                        // 07.1 -> No. da versão do layout. Preencher com 030
$header_lote .= complementoRegistro(1,"brancos");            // 08.1 -> Preencher com espacos
$header_lote .= '2';                                         // 09.1 -> Tipo de inscrição do beneficiario : um se pessoa fisico (1) ou juridica (2)
$header_lote .= picture_9($cpf_cnpj,15);                     // 10.1 -> CNPJ = Número de Inscrição do  Beneficiário cpf ou cnpj
$header_lote .= picture_9($codigo_beneficiario,6);           // 11.1 -> COD. CEDENTE ou COD. DO CONVENIO NO BANCO = código do beneficiário fornecido pelo banco 
$header_lote .= complementoRegistro(14,"zeros");             // 11.1 -> Uso exclusivo da caixa, preencher com zeros
$header_lote .= picture_9($agencia,5);                       // 12.1 -> Agencia mantenedora da conta
$header_lote .= picture_9($dv_agencia,1);                    // 13.1 -> Digito verificador
$header_lote .= picture_9($codigo_beneficiario,6);           // 14.1 -> CEDENTE = Cod do beneficiário fornecido pelo banco 
$header_lote .= complementoRegistro(7,"zeros");              // 15.1 -> Cod fornecido pela CAIXA/Gráfica,utilizado se boleto personalizado; do contrário,preencher com zeros
$header_lote .= complementoRegistro(1,"zeros");              // 16.1 -> Uso exclusivo da caixa
$header_lote .= picture_x($empresa_beneficario,30);          // 17.1 -> Nome da empresa
$header_lote .= complementoRegistro(40,"brancos");           // 18.1 -> mensagem 1
$header_lote .= complementoRegistro(40,"brancos");           // 19.1 -> mensagem 2
$header_lote .= picture_9($numero_sequencial_arquivo,8);     // 20.1 -> Controle de cobranca - No. da remessa, mesmo que 19.0
$header_lote .= $DATA['DIA'].$DATA['MES'].$DATA['ANO'];      // 21.1 -> Controle de cobranca - Data de gravacao do arquivo de remessa
$header_lote .= complementoRegistro(8,"zeros");              // 22.1 -> Data do credito. Preencher com zeros
$header_lote .= complementoRegistro(33,"brancos");           // 23.1 -> CNAB. Preencher com espacos 
$header_lote .= chr(13).chr(10);                             // Quebra de linha

# ******************************************************************************************************************************************************************
# NAO ALTERAR ==> CRIANDO O CONTEUDO DO ARQUIVO
// *****************************************************************************************************************
// DADOS DOS CLIENTES PARA TESTE
// *****************************************************************************************************************

$num_seg_linha_p_q_r = 1;

$total_boletos = 1;  // quantidade de boletos a serem registrados no arquivo de remessa


$read_financeiro = Read('financeiro', "WHERE financeiro_tipo = 'CR' AND financeiro_id_contato NOT IN(1009,600) AND financeiro_codigo NOT IN(31193,35664) {$_SESSION['financeiro_load']}");
if(NumQuery($read_financeiro) > '0'){
    foreach($read_financeiro as $read_financeiro_view){
        // *****************************************************************************************************************
	// DESCRICAO DE REGISTRO - ( TIPO 3 ) , Segmento "P":
	// DADOS DO TITULO
	// PARTE 3
	// TAMANHO DO REGISTRO = 240 CARACTERES
	// *****************************************************************************************************************

	// *****************************************************************************************************************
	// ALTERE DE ACORDO COM SEU BANCO DE DADOS - REGISTRO DETALHE (OBRIGATORIO)  - VARIAVEIS DO TITULO E DO PAGADOR
	// *****************************************************************************************************************

	// variaveis do titulo ou boleto -> pode alterar
        
        $valor_boleto_remessa = FormatMoney($read_financeiro_view['financeiro_valor']);
        $valor_boleto_remessa_1 = str_replace(',', '', $valor_boleto_remessa);
        $valor_boleto_remessa_2 = str_replace('.', '', $valor_boleto_remessa_1);
	
	$nosso_numero                    = $read_financeiro_view['financeiro_codigo'];         // nosso numero do seu boleto
	$numero_documento                = $nosso_numero;    // nosso numero do seu boleto
	$data_vencimento_boleto          = str_replace('/', '', FormDataBr($read_financeiro_view['financeiro_data_vencimento']));       // data de vencimento do boleto
	$data_multa                      = str_replace('/', '', FormDataBr($read_financeiro_view['financeiro_data_vencimento']));       // data da multa
	$data_emissao_boleto             = str_replace('/', '', FormDataBr($read_financeiro_view['financeiro_data_lancamento']));       // data da emissao do boleto
	$valor_boleto                    = $valor_boleto_remessa_2;          // valor nominal do titulo ==> 35000 ==> // 350,00
	$data_juros                      = str_replace('/', '', FormDataBr($read_financeiro_view['financeiro_data_vencimento']));       // data a partir daqual incidira juros
	$i = 2;
	$valor_juros                     = '0600';           // 0034 ou 350,00, depende se em valor ou em taxa
	$data_desconto                   = str_replace('/', '', FormDataBr($read_financeiro_view['financeiro_data_vencimento']));       // desconto ate o dia.......
	$valor_desconto                  = '000';            // valor expresso em porcentagem
	$valor_iof                       = '000';            // valor do iof
        $valor_abatimento                = '000';            // valor do abatimento que nao e a mesma coisa que desconto

	// variaveis ou dados do pagador do boleto (cliente) -> pode alterar
	$read_contato = Read('contato', "WHERE contato_id = '".$read_financeiro_view['financeiro_id_contato']."'");
        if(NumQuery($read_contato) > '0'){
            foreach($read_contato as $read_contato_view);
            $contato_cpf_cnpj_1 = str_replace('.', '', $read_contato_view['contato_cpf_cnpj']);
            $contato_cpf_cnpj_2 = str_replace('-', '', $contato_cpf_cnpj_1);
            $contato_cpf_cnpj_3 = str_replace('/', '', $contato_cpf_cnpj_2);
            
            if(strlen($contato_cpf_cnpj_3) == '11'){
                $verdadeiro_cpf_cnpj = '1';
            }else{
                $verdadeiro_cpf_cnpj = '2';
            }
            
            $cep_1 = str_replace('-', '', $read_contato_view['contato_cep']);
            $cep_2 = str_replace('.', '', $cep_1);
            
            $cep_00 = substr($cep_2, 0,5);
            $cep_01 = substr($cep_2, -3);
            //echo trim($verdadeiro_cpf_cnpj);
            //echo '<hr />';
        }
	$tipo_inscricao_pagador          = trim($verdadeiro_cpf_cnpj);              // tipo de inscrição do pagador 1 pessoa fisica 2 pessoa juridica
        $numero_inscricao_pagador        = trim($contato_cpf_cnpj_3);    // cpf
	$nome_pagador                    = $read_contato_view['contato_nome_razao']; // nome
	$endereco_pagador                = trim('AV. PRESIDENTE VARGAS'); // endereco
	$bairro_pagador                  = trim('CENTRO');         // bairro
	$cep_pagador                     = trim('76300');          // cep prefixo
	$cep_pagador_sufixo              = trim('000');            // cep sufixo
	$cidade_pagador                  = trim('CERES');       // cidade
	$estado_pagador                  = trim('GO');             // estado
	$email_pagador                   = trim($read_contato_view['contato_email']);  // email
        
        $financeiro_update_remessa['financeiro_remessa'] = '1';
        Update('financeiro', $financeiro_update_remessa, "WHERE financeiro_id = '".$read_financeiro_view['financeiro_id']."'");
	
	
	// NAO ALTERAR ==> Montando a linha P do boleto do loop
		
	$linha_p .= '104';                                   // 01.3P -> CCONTROLE. COD. DO BANCO, Neste caso = 104
	$linha_p .= picture_9($lote_servico,4);              // 02.3P -> CONTROLE. LOTE DE SERVICO. TEM QUE SER IGUAL AO HEADER DE LOTE DO CAMPO 02.1 
	$linha_p .= '3';                                     // 03.3P -> CONTROLE. TIPO DE REGISTRO. Preencher com 3 (EQUIVALE A DETALHE DO LOTE)
	$linha_p .= picture_9($num_seg_linha_p_q_r,5);       // 04.3P -> SERVICO. Nº Sequencial do Registro no Lote. (G038). EVOLUIR DE 1 EM 1 PARA CADA SEGMENTO DO LOTE
	$linha_p .= 'P';                                     // 05.3P -> SERVICO. Cód. Segmento do Registro Detalhe, PREENCHER P
	$linha_p .= complementoRegistro(1,"brancos");        // 06.3P -> SERVICO. Preencher com espaco
	$linha_p .= picture_9('01',2);                       // 07.3P -> SERVICO. Cod. de movimento remessa. 1=entrada/2=baixa/6=alterar vencimento (C004)
	$linha_p .= picture_9($agencia,5);                   // 08.3P -> COD. ID. BENEFICIARIO. Agencia mantenedora da conta
	$linha_p .= picture_9($dv_agencia,1);                // 09.3P -> COD. ID. BENEFICIARIO. Digito verificador
	$linha_p .= picture_9($codigo_beneficiario,6);       // 10.3P -> COD. ID. BENEFICIARIO. Cod. do convenio no banco ou Cód. CEDENTE. 
	$linha_p .= complementoRegistro(8,"zeros");          // 11.3P -> COD. ID. BENEFICIARIO. Uso Exclusivo CAIXA - Filler
	$linha_p .= complementoRegistro(3,"zeros");          // 12.3P -> USO EXCLUSIVO DA CAIXA. Filler
	$linha_p .= picture_9($carteira,2);                  // 13.3P -> CARTEIRA/NOSSO NUMERO. Modalidade de Carteira = tipo de carteira do boleto (14 OU 24) com ou sem registro
	$linha_p .= picture_9($nosso_numero,15);             // 13.3P -> CARTEIRA/NOSSO NUMERO. Identificacao do titulo no banco = Nosso numero 
	$linha_p .='1';                                      // 14.3P -> codigo da carteira (1) equivale a cobrança simples (C006)
	$linha_p .='1';                                      // 15.3P -> Forma de Cadastramento do Título no Banco. 1=cobranca registrada / 2=cobranca sem registro
	$linha_p .='2';                                      // 16.3P -> Tipo de Documento - Preencher '2’ (equivale a Escritural)
	$linha_p .='2';                                      // 17.3P -> Identificação da Emissao do boleto. 1 = Banco emite/ 2 = Cliente emite (C009)
	$linha_p .='0';                                      // 18.3P -> Identificacao da Entrega do boleto. (C010)
	$linha_p .= picture_x($numero_documento,11);         // 19.3P -> Numero do documento de cobranca. (C011) = meu numero de boleto
	$linha_p .= complementoRegistro(4,"brancos");        // 19.3P -> espacos
	$linha_p .= picture_9($data_vencimento_boleto,8);    // 20.3P -> Data de vencimento do título, no formato DDMMAAAA (Dia, Mêse Ano);
	$linha_p .= picture_9($valor_boleto,15);             // 21.3p -> Valor nominal do título,utilizando 2 casas decimais (exemplo:título de valor 530,44 - preencher 0000000053044)
	$linha_p .= complementoRegistro(5,"zeros");          // 22.3P -> Agência Encarregada da Cobrança (Preencher com zeros)
	$linha_p .= complementoRegistro(1,"zeros");          // 23.3P -> DV (Preencher com zeros)
	$linha_p .= picture_x('99',2);                       // 24.3P -> Espécie do Título (NF: NOTA FISCAL, DD:DOCUMENTO DE DIVIDA, CPR: CÉDULA DE PRODUTO RURAL, OU:OUTROS = 99
	$linha_p .= picture_x('N',1);                        // 25.3P -> Aceite. preencher com ‘A’ (Aceite) ou‘N’ (Não Aceite)
	$linha_p .= picture_9($data_emissao_boleto,8);       // 26.3P -> Data de emissjão do título, no formato DDMMAAAA (Dia, Mêse Ano);
	$linha_p .= picture_9('2',1);                        // 27.3P -> Juros de mora;preencher com o tipo de preferência:‘1’ (Valor por Dia); ou ‘2’ (Taxa Mensal); ou ‘3’(Isento)
	$linha_p .= picture_9($data_juros,8);                // 28.3P -> Data para início da cobrança de Juros de Mora, no formato DDMMAAAA (Dia, Mês e Ano). 0 = dia posterior venc. 
	                                                     //          devendo ser maior que a Data de Vencimento; ATENÇÃO, caso a informação seja inválida ou nãoinformada, 
										                 //          o sistema assumirá data igual à Datade Vencimento + 1
	$linha_p .= picture_9($valor_juros,15);              // 29.3P -> Juros de Mora por Dia/Taxa

	// Se houver taxa de desconto nesse boleto
	
	if( $valor_desconto >0 ){
		$linha_p .= picture_9('2',1);                    // 30.3P -> DESCONTO 1. Cod. do desconto. tipo desconto Pagador / 0=Sem Desconto / 1=Valor Fixo / 2 = Percentual
		$linha_p .= picture_9($data_desconto,8);         // 31.3P -> DESCONTO 1. Data do desconto
		$linha_p .= picture_9($valor_desconto,15);       // 32.3P -> DESCONTO 1. Valor/percentual do desconto a ser concedido
	}else{
		$linha_p .= picture_9('0',1);                    // 30.3P -> DESCONTO 1. Cod. do desconto. tipo desconto Pagador / 0=Sem Desconto / 1=Valor Fixo / 2 = Percentual
		$linha_p .= picture_9('0',8);                    // 31.3P -> DESCONTO 1. Data do desconto
		$linha_p .= picture_9('0',15);                   // 32.3P -> DESCONTO 1. Valor/percentual do desconto a ser concedido
	}

	$linha_p .= picture_9($valor_iof,15);                // 33.3P -> VLR. IOF. Valor do IOF a ser recolhido
	$linha_p .= picture_9($valor_abatimento,15);         // 34.3P -> Valor do abatimento
	$linha_p .= picture_x($numero_documento,25);         // 35.3P -> Uso empresa cedente. Identificacao do titulo na empresa. Identico ao campo 19.3P
	$linha_p .= '3';                                     // 36.3P -> Código para Protesto. 1 = protestar / 3 = nao protestar
	$linha_p .= '00';                                    // 37.3P -> Prazo para protesto. Numero de dias para  Protesto
	$linha_p .= '1';                                     // 38.3P -> Código p/ Baixa/Devolução: Preencher - vencido: '1’ (Baixar/ Devolver) ou ‘2’ (Não Baixar / Não Devolver
	$linha_p .= picture_9('029',3);                      // 39.3P -> Prazo p/ baixa/devolucao. Numero de dias para baixa/devolucao
   	$linha_p .= picture_9('9',2);                        // 40.3P -> Codigo da moeda. 09 = REAL
	$linha_p .= complementoRegistro(10,"zeros");         // 41.3P -> Preencher com zeros
	$linha_p .= complementoRegistro(1,"brancos");        // 42.3P -> Preencher com espacos
	$linha_p .= chr(13).chr(10);                         // essa é a quebra de linha
	
	$num_seg_linha_p_q_r++;
	
	$qtd_titulos++;
	
	$total_valor+=$valor_boleto;
	
	

	// NAO ALTERAR ==> Montando a linha Q do boleto do loop
	
	// *****************************************************************************************************************
	// DESCRICAO DE REGISTRO - ( TIPO 3 ) , Segmento "Q":
	// DADOS DO PAGADOR E SACADOR/AVALISTA
	// PARTE 4
	// TAMANHO DO REGISTRO = 240 CARACTERES
	// *****************************************************************************************************************
	
	$linha_q .= '104';                                   // 01.3Q -> Cod. Banco. Caixa = 104 
	$linha_q .= picture_9($lote_servico,4);              // 02.3Q -> Lote de serviço
	$linha_q .= '3';                                     // 03.3Q -> tipo de registro. Equivalente a detalhe de lote. preencher '3'
	$linha_q .= picture_9($num_seg_linha_p_q_r,5);       // 04.3Q -> Nº Sequencial do Registro no Lote
	$linha_q .= 'Q';                                     // 05.3Q -> Cód. Segmento do Registro Detalhe
	$linha_q .= complementoRegistro(1,"brancos");        // 06.3Q -> Espaco
	$linha_q .= picture_9('01',2);                       // 07.3Q -> Cod de Movimento Remessa
	$linha_q .= $tipo_inscricao_pagador;                 // 08.3Q -> Tipo de Inscricao do Pagador (1) CPF (pessoa física) (2) CNPJ Pessoa jurídica
	$linha_q .= picture_9($numero_inscricao_pagador,15); // 09.3Q -> cpf ou cnpj
	$linha_q .= picture_x($nome_pagador,40);             // 10.3Q -> Nome do pagador
	$linha_q .= picture_x($endereco_pagador,40);         // 11.3Q -> Endereco do pagador
	$linha_q .= picture_x($bairro_pagador,15);           // 12.3Q -> Bairro
	$linha_q .= picture_9($cep_pagador,5);               // 13.3Q -> Cep
	$linha_q .= picture_9($cep_pagador_sufixo,3);        // 14.3Q -> Cep (sufixo)
	$linha_q .= picture_x($cidade_pagador,15);           // 15.3Q -> Cidade
	$linha_q .= picture_x($estado_pagador,2);            // 16.3Q -> UF
	$linha_q .= '0';                                     // 17.3Q -> Tipo de Inscrição do sacador AVALISTA (0) nenhum (1) CPF (pessoa física) (2) CNPJ Pessoa jurídica
	$linha_q .= picture_9('0',15);                       // 18.3Q -> CPF ou CNPJ do Sacador avalista
	$linha_q .= complementoRegistro(40,"brancos");       // 19.3Q -> nome do sacador avalista
	$linha_q .= complementoRegistro(3,"brancos");        // 20.3Q -> Zeros
	$linha_q .= complementoRegistro(20,"brancos");       // 21.3Q -> Espaco
	$linha_q .= complementoRegistro(8,"brancos");        // 22.3Q -> Espaco

	$tam_linha_q  = strlen($linha_q);

	$zeros_rest_2 = 240 - $tam_linha_q;

	$linha_q      = $linha_q.complementoRegistro($zeros_rest_2,"zeros");
	
	$linha_q .= chr(13).chr(10);                         // essa é a quebra de linha

 	$num_seg_linha_p_q_r++;


	// *****************************************************************************************************************
	// DESCRICAO DE REGISTRO - ( TIPO 3 ) , Segmento "R":
	// DADOS DO PAGADOR E SACADOR/AVALISTA
	// PARTE 4
	// TAMANHO DO REGISTRO = 240 CARACTERES
	// *****************************************************************************************************************
	
	// NAO ALTERAR ==> Montando a linha R do boleto do loop
	
	$linha_r .= '104';                                   // 01.3R -> Cod. Banco. Caixa = 104 
	$linha_r .= picture_9($lote_servico,4);              // 02.3R -> Lote de serviço
	$linha_r .= '3';                                     // 03.3R -> tipo de registro. Equivalente a detalhe de lote. preencher '3'
	$linha_r .= picture_9($num_seg_linha_p_q_r,5);       // 04.3R -> Nº Sequencial do Registro no Lote
	$linha_r .= 'R';                                     // 05.3R -> Cód. Segmento do Registro Detalhe
	$linha_r .= complementoRegistro(1,"brancos");        // 06.3R -> Espaco
	$linha_r .= picture_9('01',2);                       // 07.3R -> Cod. Movimento Rem = 01 => Entrada de titulo Nota Explicativa: (C004)
	$linha_r .= '0';                                     // 08.3R -> DESCONTO-2. COD. DESCONTO / 0=sem / 1=valor fixo / 2=valor percentual
        $linha_r .= picture_9('0',8);                        // 09.3R -> DESCONTO-2. DATA DESCONTO 
        $linha_r .=	picture_9('0',15);                       // 10.3R -> DESCONTO-2. VALOR DO DESCONTO
	$linha_r .= '0';                                     // 11.3R -> DESCONTO-3. COD. DESCONTO / 0=sem / 1=valor fixo / 2=valor percentual
        $linha_r .= picture_9('0',8);                        // 12.3R -> DESCONTO-3. DATA DESCONTO 
        $linha_r .=	picture_9('0',15);                       // 13.3R -> DESCONTO-3. VALOR DO DESCONTO
	$linha_r .= '2';                                     // 14.3R -> MULTA. COD. DESCONTO / 0=sem / 1=valor fixo / 2=valor percentual
        $linha_r .= picture_9($data_juros,8);                // 15.3R -> MULTA. DATA DA MULTA 
        $linha_r .=	picture_9('200',15);                     // 16.3R -> MULTA. VALOR DO DESCONTO
	$linha_r .= complementoRegistro(10,"brancos");       // 17.3R -> INFORMACAO AO PAGADOR - preencher com espacos
	$linha_r .= complementoRegistro(40,"brancos");       // 18.3R -> INFORMACAO 3 - mensagem 3
	$linha_r .= complementoRegistro(40,"brancos");       // 19.3R -> INFORMACAO 4 - Mensagem 4
	$linha_r .= complementoRegistro(50,"brancos");       // 20.3R -> e-mail pagador - e-mail pagador para envio de informacoes
	$linha_r .= complementoRegistro(11,"brancos");       // 21.3R -> Zeros
	$linha_r .= chr(13).chr(10);                         // essa é a quebra de linha
		
	$lote_sequencial++;
	
 	$num_seg_linha_p_q_r++;
	
	$conteudo_meio .= $linha_p.$linha_q.$linha_r;

	$linha_p = "";
	$linha_q = "";
	$linha_r = "";
    }
}
for( $j=0; $j<$total_boletos; $j++ ){   // loop para obter os dados dos boletos da sua base de dados e montar as linhas P, Q e R do arquivo de remessa

	


} // final do LOOP para obter os dados dos boletos e dos clientes e montar o conteudo do meio do arquivo (linhas P, Q e R)



// *****************************************************************************************************************
// NAO ALTERAR ==> Montando o segmento 5 do arquivo de remessa => rodape ou trailer de lote => final do lote
// *****************************************************************************************************************
// DESCRICAO DE REGISTRO TIPO "5"
// TRAILER DE LOTE DE ARQUIVO REMESSA
// PARTE 5 - PNULTIMA LINHA DO ARQUIVO
// TAMANHO DO REGISTRO = 240 CARACTERES
// *****************************************************************************************************************
	
$linha_5 .= '104';                                   // 01.5 -> COD. DO BANCO. CAIXA = 104
$linha_5 .= picture_9($lote_servico,4);              // 02.5 -> CONTROLE -> Lote de servico equivalente a 02.1
$linha_5 .= '5';                                     // 03.5 -> CONTROLE -> Tipo de registro, preencher com '5'
													 //         equivalente a (Trailer de Lote).
$linha_5 .= complementoRegistro(9,"brancos");        // 04.9 -> CNAB. FIller, preencher com espacos

$qtd_registros = ($lote_sequencial*3)+2-1-1;
$linha_5 .= picture_9(($qtd_registros-1),6);         // 05.5 -> Qtd. de registros no lote. Somatoria dos registros
													 //         de tipo 1, 3 e 5 ( obs alex = total de linhas -2 )

$linha_5 .= picture_9($qtd_titulos,6);               // 06.5 -> TOTALIZACAO COBRANCA SIMPLES - Preencher com a qtd.
													 //         de titulos informados no lote
$linha_5 .=	picture_9($total_valor,17);              // 07.5 -> TOTALIZACAO COBRANCA SIMPLES - Preencher com o valor
													 //         total de titulos informados no lote   
$linha_5 .= complementoRegistro(6,"zeros");          // 08.5 -> Preencher com zeros     
$linha_5 .= complementoRegistro(17,"zeros");         // 09.5 -> Preencher com zeros     
$linha_5 .= complementoRegistro(6,"zeros");          // 10.5 -> Preencher com zeros     
$linha_5 .= complementoRegistro(17,"zeros");         // 11.5 -> Preencher com zeros     
$linha_5 .= complementoRegistro(31,"brancos");       // 12.5 -> CNAB -> Filler -> Preencher com espacos
$linha_5 .= complementoRegistro(117,"brancos");      // 13.5 -> CNAB -> Filler -> Preencher com espacos

$linha_5 .= chr(13).chr(10);                         // essa é a quebra de linha

// *****************************************************************************************************************
// NAO ALTERAR ==> Montando o segmento 9 do arquivo de remessa => rodape final do arquivo
// *****************************************************************************************************************
// DESCRICAO DE REGISTRO TIPO "9"
// TRAILER DE ARQUIVO REMESSA
// PARTE 5 - FINAL OU RODAPE DO ARQUIVO
// TAMANHO DO REGISTRO = 240 CARACTERES
// *****************************************************************************************************************
	
$linha_9 .= '104';                                   // 01.9 -> COD. DO BANCO. CAIXA = 104
$linha_9 .= '9999';                                  // 02.9 -> lote de serviço. Preencher '9999'
$linha_9 .= '9';                                     // 03.9 -> Tipo de registro. Preencher '9'
$linha_9 .= complementoRegistro(9,"brancos");        // 04.9 -> CNAB. FIller
$qtd_lotes_arquivo = $lote_servico;
$linha_9 .= picture_9($qtd_lotes_arquivo,6);         // 05.9 -> Quantidade de lotes do arquivo

$qtd_reg_arq = ($lote_sequencial*3)+2-1+1-1;                 
$linha_9 .= picture_9($qtd_reg_arq,6);               // 06.9 -> Quantidade de registros no arquivo

$linha_9 .= complementoRegistro(6,"brancos");        // 07.9 -> Espacos
$linha_9 .= complementoRegistro(105,"brancos");      // 08.9 -> Espacos
$linha_9 .= complementoRegistro(100,"brancos");      // ajuste alex para completar as 240 posicoes dessa linha de rodape

$conteudo = $header.$header_lote.$conteudo_meio.$linha_5.$linha_9;

// *****************************************************************************************************************



// *****************************************************************************************************************
# CRIAR O ARQUIVO FISICO DA REMESSA
// *****************************************************************************************************************

if (!$handle = fopen($filename, 'w+')){
	erro("<br>&nbsp;Não foi possível abrir o arquivo ($filename)");
}

if (fwrite($handle, "$conteudo") === FALSE){
	echo "<br>&nbsp;Não foi possível escrever no arquivo ($filename)";
}

fclose($handle);

echo "<br>&nbsp;Arquivo de remessa gerado com sucesso!";

// TRANSFERIR O ARQUIVO PARA O SERVIDOR

$xdestino = "remessa/".$filename;
$xorigem = $filename;

@copy($xorigem,$xdestino);

$arquivo = $filename;
//echo "<br>passei aqui na hora de copiar....";

?>
<br />
<br />
Arquivo de Remessa: <a href="<?php echo $xdestino;?>" target="_blank"><?php echo $xdestino;?></a>