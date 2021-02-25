<?php
/**
 * User: Marques Junior
 * Date: 08/2018
 * Version: 1.0
 *
 * Arquivo inicial que tem a função de importar as classes usadas e realizar uma chamada
 *
 */
 session_start();
 
$inicio_dados_correto = $_GET['id'];
$dados_refresh = $inicio_dados_correto + 1;
$data_inicial = $_GET['inicio'];
$data_fim = $_GET['fim'];

echo '<meta http-equiv="refresh" content="2;url=reg_massa.php?id='.$dados_refresh.'&inicio='.$data_inicial.'&fim='.$data_fim.'">';
function cnpjRandom($mascara = "1") {
    $n1 = rand(0, 9);
    $n2 = rand(0, 9);
    $n3 = rand(0, 9);
    $n4 = rand(0, 9);
    $n5 = rand(0, 9);
    $n6 = rand(0, 9);
    $n7 = rand(0, 9);
    $n8 = rand(0, 9);
    $n9 = 0;
    $n10 = 0;
    $n11 = 0;
    $n12 = 1;
    $d1 = $n12 * 2 + $n11 * 3 + $n10 * 4 + $n9 * 5 + $n8 * 6 + $n7 * 7 + $n6 * 8 + $n5 * 9 + $n4 * 2 + $n3 * 3 + $n2 * 4 + $n1 * 5;
    $d1 = 11 - (mod($d1, 11) );
    if ($d1 >= 10) {
        $d1 = 0;
    }
    $d2 = $d1 * 2 + $n12 * 3 + $n11 * 4 + $n10 * 5 + $n9 * 6 + $n8 * 7 + $n7 * 8 + $n6 * 9 + $n5 * 2 + $n4 * 3 + $n3 * 4 + $n2 * 5 + $n1 * 6;
    $d2 = 11 - (mod($d2, 11) );
    if ($d2 >= 10) {
        $d2 = 0;
    }
    $retorno = '';
    if ($mascara == 1) {
        $retorno = '' . $n1 . $n2 . "." . $n3 . $n4 . $n5 . "." . $n6 . $n7 . $n8 . "/" . $n9 . $n10 . $n11 . $n12 . "-" . $d1 . $d2;
    } else {
        $retorno = '' . $n1 . $n2 . $n3 . $n4 . $n5 . $n6 . $n7 . $n8 . $n9 . $n10 . $n11 . $n12 . $d1 . $d2;
    }
    return $retorno;
}

function cpfRandom($mascara = "1") {
    $n1 = rand(0, 9);
    $n2 = rand(0, 9);
    $n3 = rand(0, 9);
    $n4 = rand(0, 9);
    $n5 = rand(0, 9);
    $n6 = rand(0, 9);
    $n7 = rand(0, 9);
    $n8 = rand(0, 9);
    $n9 = rand(0, 9);
    $d1 = $n9 * 2 + $n8 * 3 + $n7 * 4 + $n6 * 5 + $n5 * 6 + $n4 * 7 + $n3 * 8 + $n2 * 9 + $n1 * 10;
    $d1 = 11 - (mod($d1, 11) );
    if ($d1 >= 10) {
        $d1 = 0;
    }
    $d2 = $d1 * 2 + $n9 * 3 + $n8 * 4 + $n7 * 5 + $n6 * 6 + $n5 * 7 + $n4 * 8 + $n3 * 9 + $n2 * 10 + $n1 * 11;
    $d2 = 11 - (mod($d2, 11) );
    if ($d2 >= 10) {
        $d2 = 0;
    }
    $retorno = '';
    if ($mascara == 1) {
        $retorno = '' . $n1 . $n2 . $n3 . "." . $n4 . $n5 . $n6 . "." . $n7 . $n8 . $n9 . "-" . $d1 . $d2;
    } else {
        $retorno = '' . $n1 . $n2 . $n3 . $n4 . $n5 . $n6 . $n7 . $n8 . $n9 . $d1 . $d2;
    }
    return $retorno;
}
function mod($dividendo, $divisor) {
    return round($dividendo - (floor($dividendo / $divisor) * $divisor));
}
function CPF($Cpf) {
	$CPFDIG = preg_replace('/[^0-9]/', '', $Cpf);

	if (strlen($CPFDIG) != 11):
		return false;
	endif;

	$digitoA = 0;
	$digitoB = 0;

	for ($i = 0, $x = 10; $i <= 8; $i++, $x--) {
		$digitoA += $CPFDIG[$i] * $x;
	}

	for ($i = 0, $x = 11; $i <= 9; $i++, $x--) {
		if (str_repeat($i, 11) == $CPFDIG) {
			return false;
		}
		$digitoB += $CPFDIG[$i] * $x;
	}

	$somaA = (($digitoA % 11) < 2 ) ? 0 : 11 - ($digitoA % 11);
	$somaB = (($digitoB % 11) < 2 ) ? 0 : 11 - ($digitoB % 11);

	if ($somaA != $CPFDIG[9] || $somaB != $CPFDIG[10]) {
		return false;
	} else {
		return true;
	}
}

function CNPJ($Cnpj) {
	$CNPJDIG = (string) $Cnpj;
	$CNPJDIG = preg_replace('/[^0-9]/', '', $CNPJDIG);

	if (strlen($CNPJDIG) != 14):
		return false;
	endif;

	$A = 0;
	$B = 0;

	for ($i = 0, $c = 5; $i <= 11; $i++, $c--):
		$c = ($c == 1 ? 9 : $c);
		$A += $CNPJDIG[$i] * $c;
	endfor;

	for ($i = 0, $c = 6; $i <= 12; $i++, $c--):
		if (str_repeat($i, 14) == $CNPJDIG):
			return false;
		endif;
		$c = ($c == 1 ? 9 : $c);
		$B += $CNPJDIG[$i] * $c;
	endfor;

	$somaA = (($A % 11) < 2) ? 0 : 11 - ($A % 11);
	$somaB = (($B % 11) < 2) ? 0 : 11 - ($B % 11);

	if (strlen($CNPJDIG) != 14):
		return false;
	elseif ($somaA != $CNPJDIG[12] || $somaB != $CNPJDIG[13]):
		return false;
	else:
		return true;
	endif;
}

$inicio_dados = $inicio_dados_correto;


$getIdRemessa = $_GET['id'];
require_once '../_class/Ferramenta.php';
$read_financeiro = Read('financeiro', "WHERE financeiro_data_vencimento BETWEEN '".$data_inicial."' AND '".$data_fim."' AND financeiro_tipo = 'CR' AND financeiro_status = '0' LIMIT $getIdRemessa,1");
if(NumQuery($read_financeiro) > '0'):
foreach($read_financeiro as $read_financeiro_view);
	echo $read_financeiro_view['financeiro_codigo'];
	$read_contato = Read('contato', "WHERE contato_id = '".$read_financeiro_view['financeiro_id_contato']."'");
	if(NumQuery($read_contato) > '0'):
		foreach($read_contato as $read_contato_view);
		$financeiro_nosso_numero = substr($read_financeiro_view['financeiro_nosso_numero'], 0, -1);
		$financeiro_data_vencimento = $read_financeiro_view['financeiro_data_vencimento'];
		$financeiro_valor = $read_financeiro_view['financeiro_valor'];
		$financeiro_codigo = $read_financeiro_view['financeiro_codigo'];
		$financeiro_data_lancamento = $read_financeiro_view['financeiro_data_lancamento'];
		
		$contato_cpf_cnpj = $read_contato_view['contato_cpf_cnpj'];
		$contato_nome_razao = $read_contato_view['contato_nome_razao'];
	endif;
else:
echo 'PROCESSO FINALIZADO<HR />';	
endif;

$remover_cara = str_replace('&', '', $contato_nome_razao);

$remover_isso = array(".", "-", "/");
$colocar_isso   = array("", "", "");

$remover_cpf_cnpj = str_replace($remover_isso, $colocar_isso, $contato_cpf_cnpj);
$quantidade_caracter = strlen($remover_cpf_cnpj);
if($quantidade_caracter == '11'):
	if(!CPF($remover_cpf_cnpj)):
		$remover_cpf_cnpj = cpfRandom('0');
	endif;
else:
	if(!CNPJ($remover_cpf_cnpj)):
		$remover_cpf_cnpj = cnpjRandom('0');
	endif;
endif;

$_SESSION['NOSSO_NUMERO'] = $financeiro_nosso_numero;
$_SESSION['DATA_VENCIMENTO'] = $financeiro_data_vencimento;
$_SESSION['VALOR'] = $financeiro_valor;
$_SESSION['CODIGO'] = $financeiro_codigo;
$_SESSION['DATA_LANCAMENTO'] = $financeiro_data_lancamento;
$_SESSION['QUANTIDADE'] = $quantidade_caracter;
$_SESSION['CPF_CNPJ'] = $remover_cpf_cnpj;
$_SESSION['NOME_RAZAO'] = substr($remover_cara,0,40);

if($_SESSION['QUANTIDADE'] == '11'):
	$quantidade = 'CPF';
	$quantidade_nome = 'NOME';
else:
	$quantidade = 'CNPJ';
	$quantidade_nome = 'RAZAO_SOCIAL';
endif;

$dados_retorno = array(
	'urlIntegracao' => 'https://barramento.caixa.gov.br/sibar/ManutencaoCobrancaBancaria/Boleto/Externo',
	'codigoCedente' => '273007',
	'nossoNumero' => $_SESSION['NOSSO_NUMERO'],
	'dataVencimento' => $_SESSION['DATA_VENCIMENTO'],
	'valorNominal' => $_SESSION['VALOR'],
	'cnpj' => '11655954000159',
	'codigoTitulo' => $_SESSION['CODIGO'],
	'dataEmissao' => $_SESSION['DATA_LANCAMENTO'],
	'dataJuros' => date('Y-m-d', strtotime('+1 days', strtotime($_SESSION['DATA_VENCIMENTO']))),
	'juros' => '2.00',
	'dataMulta' => date('Y-m-d', strtotime('+1 days', strtotime($_SESSION['DATA_VENCIMENTO']))),
	'multa' => '2.00',
	'numeroAgencia' => '1298',
	'infoPagador' => array(
		$quantidade => $_SESSION['CPF_CNPJ'],
		$quantidade_nome => $_SESSION['NOME_RAZAO'],
		'ENDERECO' => array(
			'LOGRADOURO' => 'AV. PRESIDENTE VARGAS',
			'BAIRRO' => 'CENTRO',
			'CIDADE' => 'CERES',
			'UF' => 'GO',
			'CEP' => '76300000'
		)
	)
);

//include_once 'src/Dados.php';
include_once 'lib11/Caixa.php';

/*echo '<pre>';
	print_r($dados_retorno);
echo '</pre>';*/

$integracao = new Caixa($dados_retorno);
$finalizacao = $integracao->realizarRegistro();

if($finalizacao['CONTROLE_NEGOCIAL']['COD_RETORNO'] == '0'):
	$retorno_form['retorno_id_financeiro'] = $getIdRemessa;
	$retorno_form['retorno_status'] = '0';
	$retorno_form['retorno_mensagem'] = $finalizacao['CONTROLE_NEGOCIAL']['MENSAGENS']['RETORNO'];
	Create('retorno', $retorno_form);
	echo '<pre>';
	print_r($finalizacao);
else:
	$retorno_form['retorno_id_financeiro'] = $getIdRemessa;
	$retorno_form['retorno_status'] = '1';
	$retorno_form['retorno_mensagem'] = $finalizacao['CONTROLE_NEGOCIAL']['MENSAGENS']['RETORNO'];
	Create('retorno', $retorno_form);
	echo '<pre>';
	print_r($finalizacao);
endif;

///print_r($dados_retorno);