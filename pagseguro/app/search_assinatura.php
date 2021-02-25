<?php
session_start();
ob_start();
require_once '../../class/Session.php';
require_once "../vendor/autoload.php";

    
\PagSeguro\Library::initialize();

$options = [
    'initial_date' => date('Y-m-d').'T00:00',
    'final_date' => '', //Optional
    'page' => 1, //Optional
    'max_per_page' => 20, //Optional
];

try {
    $response = \PagSeguro\Services\PreApproval\Search\Date::search(
        \PagSeguro\Configuration\Configure::getAccountCredentials(),
        $options
    );
    if(count($response->getPreApprovals()) > '0'){
        foreach ($response->getPreApprovals() as $ass){
            if($ass->getStatus() == 'ACTIVE'){
                $token_assinatura = $ass->getCode();
                $tracker_assinatura = $ass->getTracker();
                $read_assinatura = Read('assinatura', "WHERE CodigoPagseguro = '".$token_assinatura."'");
                if(NumQuery($read_assinatura) == '0'){
                    $code = $token_assinatura;
                    $response_code = \PagSeguro\Services\PreApproval\Search\Code::search(
                        \PagSeguro\Configuration\Configure::getAccountCredentials(),
                        $code
                    );
                    $mail_cliente = $response_code->getSender()->getEmail();

                    $up_ass['CodigoPagseguro'] = $token_assinatura;
                    $up_ass['CodigoPagseguro_1'] = $tracker_assinatura;
                    $up_ass['Status'] = '1';
                    Update('assinatura', $up_ass, "WHERE EmailPagSeguro = '".$mail_cliente."'");
                }
            }
        }
    }
} catch (Exception $e) {
    die($e->getMessage());
}
