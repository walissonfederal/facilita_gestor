<?php
session_start();
require '../../php-boleto-pdf-master/vendor/autoload.php';

if(isset($_GET['04'])){
    $_SESSION['BASE_ENTIDADE'] = base64_decode($_GET['04']);
}
require_once('../../../_class/Boleto.php');
$type_boleto    = addslashes(base64_decode($_GET['00']));
$model_boleto   = addslashes(base64_decode($_GET['01']));
$conta_boleto   = addslashes(base64_decode($_GET['02']));
$url_boleto     = addslashes(base64_decode($_GET['03']));

$read_boleto = Read('boleto', "WHERE boleto_id = '".$conta_boleto."' AND boleto_status = '0'");
if(NumQuery($read_boleto) > '0'){
    foreach($read_boleto as $read_boleto_view);
}else{
    header("Location: ../../index.php");
}
$oCedente = new \Simonetti\Boleto\Cedente();
$oCedente->setNome(GetEmpresa('empresa_nome_razao'));
$oCedente->setAgencia($read_boleto_view['boleto_agencia']);
$oCedente->setDvAgencia("");
$oCedente->setConta($read_boleto_view['boleto_conta']);
$oCedente->setDvConta($read_boleto_view['boleto_conta_digito']);
$oCedente->setEndereco(GetEmpresa('empresa_endereco').", ". GetEmpresa('empresa_numero')." - ". GetEmpresa('empresa_numero')." - ". GetEmpresa('empresa_cidade')."/". GetEmpresa('empresa_estado')." - ". GetEmpresa('empresa_cep'));
$oCedente->setCidade(GetEmpresa('empresa_cidade'));
$oCedente->setUf(GetEmpresa('empresa_estado'));
$oCedente->setCpfCnpj(GetEmpresa('empresa_cnpj'));

$oSacado = new \Simonetti\Boleto\Sacado();
$oSacado->setNome("VINICIUS DE SÁ");
$oSacado->setCpfCnpj("144.840.167-45");
$oSacado->setTipoLogradouro("AVENIDA");
$oSacado->setEnderecoLogradouro("SETEMBIRNO PELISSARI");
$oSacado->setNumeroLogradouro("100");
$oSacado->setCidade("PINHEIROS");
$oSacado->setBairro("CENTRO");
$oSacado->setUf("ES");
$oSacado->setCep("29980-000");


$oAvalista = new \Simonetti\Boleto\Avalista('FRANK BRUNO', '133.567.677-55');

$banco = new \Simonetti\Boleto\Banco\Caixa();
$banco->setCarteiraModalidade('2');
$banco->setCarteira('SR');

$carne = new \Simonetti\Boleto\Carne(
    $banco,
    $oCedente,
    $oSacado,
    $oAvalista
);

$carne->setNumeroMoeda("9");
$carne->setDataDocumento(DateTime::createFromFormat('d/m/Y', "29/08/2015"));
$carne->setDataProcessamento(new DateTime('now'));
$carne->addDemonstrativo('Pagamento de Compra na Móveis Simonetti');
$carne->addInstrucao(" ");
$carne->addInstrucao("MULTA DE R$: 1,00 APÓS: 17/10/2015");
$carne->addInstrucao("JUROS DE R$: 0,09 AO DIA");
$carne->addInstrucao(" ");
$carne->addInstrucao("NÃO RECEBER APÓS 10 DIAS DO VENCIMENTO");

$dataInicial = new DateTime('2015-09-01');

$boletosValidacao = [];

$i = 1;

while(10 != count($boletosValidacao)) {

    $dataVenciemento = clone $dataInicial;

    $parcela = new \Simonetti\Boleto\Carne\Parcela();
    $parcela->setValorBoleto('10,00');
    $parcela->setDataVencimento($dataVenciemento);
    $parcela->setNossoNumero($i);
    $parcela->setNumeroDocumento(100+$i);

    $boletotmp = new \Simonetti\Boleto\Boleto();
    $boletotmp->setBanco($carne->getBanco());
    $boletotmp->setCedente($carne->getCedente());
    $boletotmp->setSacado($carne->getSacado());
    $boletotmp->setAvalista($carne->getAvalista());
    $boletotmp->setNumeroDocumento($parcela->getNumeroDocumento());
    $boletotmp->setNossoNumero($parcela->getNossoNumero());
    $boletotmp->setDataVencimento($parcela->getDataVencimento() );
    $boletotmp->setDataDocumento($carne->getDataDocumento());
    $boletotmp->setDataProcessamento($carne->getDataProcessamento());
    $boletotmp->setNumeroMoeda($carne->getNumeroMoeda());
    $boletotmp->setValorBoleto($parcela->getValorBoleto());

    $boletotmp->setDemonstrativos($carne->getDemonstrativos());
    $boletotmp->setInstrucoes($carne->getInstrucoes());

    $linha = substr($boletotmp->gerarLinhaDigitavel(), 35, 1);
    if(!in_array($linha, $boletosValidacao)) {
        $carne->addParcela($parcela);
        $dataInicial->modify('+1 days');
        $boletosValidacao[] = $linha;
    }

    $i++;
}

$loader = new Twig_Loader_Filesystem(\Simonetti\Boleto\Gerador::getDirImages() . '/../templates');
$twig = new Twig_Environment($loader);

$geradorCarne = new \Simonetti\Boleto\GeradorCarne($twig);
$boleto = $geradorCarne->gerar($carne);
$boleto->Output();
//$boleto->Output('boleto.pdf' , 'D');