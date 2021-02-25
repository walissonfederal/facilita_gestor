<?php
require_once '../../class/Session.php';
IniSession();
$id_assinatura = addslashes($_GET['Id']);
$read_assinatura = Read('assinatura', "WHERE Id = '".$id_assinatura."' AND Status = '0'");
if(NumQuery($read_assinatura) == '0'){
    header("http://federalsistemas.com.br");
}else{
    foreach($read_assinatura as $read_assinatura_view);
    $email_cliente = GetDados('clientes', $read_assinatura_view['IdCliente'], 'Id', 'Email');
}

require_once "../vendor/autoload.php";

\PagSeguro\Library::initialize();
\PagSeguro\Library::cmsVersion()->setName("Nome")->setRelease("1.0.0");
\PagSeguro\Library::moduleVersion()->setName("Nome")->setRelease("1.0.0");

$payment = new \PagSeguro\Domains\Requests\PreApproval();


$payment->setCurrency("BRL");

/***
 * Pre Approval information
 */
$payment->setPreApproval()->setCharge('manual');
$payment->setPreApproval()->setName($read_assinatura_view['Descricao']);
$payment->setPreApproval()->setDetails($read_assinatura_view['Detalhes']);
//$payment->setPreApproval()->setAmountPerPayment('100.00');
$payment->setPreApproval()->setMaxAmountPerPeriod($read_assinatura_view['ValorMaximo']);
$payment->setPreApproval()->setPeriod('Monthly');
$payment->setPreApproval()->setInitialDate(date('Y-m-d').'T00:00:00');
$payment->setPreApproval()->setFinalDate($read_assinatura_view['DataFinal'].'T00:00:00');

$payment->setRedirectUrl("http://www.federalsistemas.com.br/erp/tela-confirmacao.php");

try {

    /**
     * @todo For checkout with application use:
     * \PagSeguro\Configuration\Configure::getApplicationCredentials()
     *  ->setAuthorizationCode("FD3AF1B214EC40F0B0A6745D041BF50D")
     */
    $result = $payment->register(
        \PagSeguro\Configuration\Configure::getAccountCredentials()
    );

    echo "<h2>Criando requisi&ccedil;&atilde;o de pagamento</h2>"
        . "<p>URL do pagamento: <strong>$result</strong></p>"
        . "<p><a title=\"URL do pagamento\" href=\"$result\" target=\_blank\">Ir para URL do pagamento.</a></p>";
    header("Location: $result");
} catch (Exception $e) {
    die($e->getMessage());
}
