<?php
session_start();
/**
 * User: Marques Junior
 * Date: 08/2018
 * Version: 1.0
 *
 * Classe simplificada para preenchimento dos dados para testes do webservice
 *
 * Durante a implementação do envio dos dados, fique atento à quantidade de caracteres que são encaminhados e
 * ao envio de caracteres especiais que podem ultrapassar o limite conforme encoding.
 * Cuide também a formatação dos campos conforme o manual
 *
 */
 
 
class Dados
{
	
	//public $urlIntegracao = 'https://des.barramento.caixa.gov.br/sibar/ManutencaoCobrancaBancaria/Boleto/Externo';
	
	
    public $urlIntegracao = 'https://barramento.caixa.gov.br/sibar/ManutencaoCobrancaBancaria/Boleto/Externo';
    public $codigoCedente = '273007'; //cedente ou beneficiário
    public $nossoNumero = '14000000000037084';
    public $dataVencimento = '2018-09-24';
    public $valorNominal = '800.40';
    public $cnpj = '11655954000159';
    public $codigoTitulo = '37084';
    public $dataEmissao = '2018-09-24';

    public $dataJuros = '2018-09-25';
    public $juros = '2.00';

    public $dataMulta = '2018-09-25';
    public $multa = '2.00';

    public $numeroAgencia = '1298';
	
	/*if($_SESSION['QUANTIDADE'] == '11'):
		public $infoPagador = array(
			'CPF' => $_SESSION['CPF_CNPJ'],
			'NOME' => $_SESSION['NOME_RAZAO'],
			'ENDERECO' => array(
				'LOGRADOURO' => 'AV. PRESIDENTE VARGAS',
				'BAIRRO' => 'CENTRO',
				'CIDADE' => 'CERES',
				'UF' => 'GO',
				'CEP' => '76300000'
			)
		);
	else:
		public $infoPagador = array(
			'CNPJ' => '73981362268',
			'RAZAO_SOCIAL' => 'CHARLES LOPES',
			'ENDERECO' => array(
				'LOGRADOURO' => 'AV. PRESIDENTE VARGAS',
				'BAIRRO' => 'CENTRO',
				'CIDADE' => 'CERES',
				'UF' => 'GO',
				'CEP' => '76300000'
			)
		);
	endif;*/
	public $infoPagador = array(
			'CPF' => '73981362268',
			'RAZAO_SOCIAL' => 'CHARLES LOPES',
			'ENDERECO' => array(
				'LOGRADOURO' => 'AV. PRESIDENTE VARGAS',
				'BAIRRO' => 'CENTRO',
				'CIDADE' => 'CERES',
				'UF' => 'GO',
				'CEP' => '76300000'
			)
		);

    /*Caso o pagador seja uma empresa*/
    
}