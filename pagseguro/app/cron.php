<?php
require_once '../../class/Session.php';

require_once "../vendor/autoload.php";

\PagSeguro\Library::initialize();
\PagSeguro\Library::cmsVersion()->setName("Nome")->setRelease("1.0.0");
\PagSeguro\Library::moduleVersion()->setName("Nome")->setRelease("1.0.0");

$preApproval = new \PagSeguro\Domains\Requests\PreApproval\Charge();

$read_assinaturas = Read('assinatura', "WHERE Status = '1'");
if(NumQuery($read_assinaturas) > '0'){
    foreach($read_assinaturas as $read_assinaturas_view){
        if($read_assinaturas_view['DiaCobranca'] == date('d')){
            $read_financeiro = Read('contas_receber', "WHERE IdTipoDocumento = '5' AND IdCliente = '".$read_assinaturas_view['IdCliente']."' AND Status = '0' AND Valor <= '100' LIMIT 1");
            if(NumQuery($read_financeiro) > '0'){
                foreach($read_financeiro as $read_financeiro_view){
                    $preApproval->setReference($read_financeiro_view['Id']);
                    $preApproval->setCode($read_assinaturas_view['CodigoPagseguro']);
                    $preApproval->addItems()->withParameters(
                        '0001',
                        $read_financeiro_view['Descricao'],
                        1,
                        $read_financeiro_view['Valor']
                    );

                    try {
                        $response = $preApproval->register(\PagSeguro\Configuration\Configure::getAccountCredentials());
                        
                        $up_financeiro['ConsultaPagSeguro'] = '1';
                        $up_financeiro['TokenPagSeguro'] = $response->getCode();
                        Update('contas_receber', $up_financeiro, "WHERE Id = '".$read_financeiro_view['Id']."'");
                    } catch (Exception $e) {
                        die($e->getMessage());
                    }
                }
            }
        }
    }
}