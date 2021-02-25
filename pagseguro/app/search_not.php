<?php
require_once '../../class/Session.php';
require_once "../../../vendor/autoload.php";

\PagSeguro\Library::initialize();

$read_financeiro = Read('contas_receber', "WHERE ConsultaPagSeguro = '1' AND Status = '0'");
if(NumQuery($read_financeiro) > '0'){
    foreach($read_financeiro as $read_financeiro_view){
        $code = $read_financeiro_view['TokenPagSeguro'];

        try {
            $response = \PagSeguro\Services\Transactions\Search\Code::search(
                \PagSeguro\Configuration\Configure::getAccountCredentials(),
                $code
            );

            //data
            $data_resposta_retorno = $response->getDate();
            $data_resposta = explode('T', $data_resposta_retorno);
            //id da conta
            $id_conta = $response->getReference();
            //status da conta
            $status_conta = $response->getStatus();
            //tipo_pagamento
            $tipo_pagamento = $response->getPaymentMethod()->getType();
            //detalhe tipo pagamento
            $detalhe_tipo_pagamento = $response->getPaymentMethod()->getCode();
            //valor_pago
            $valor_pago = $response->getGrossAmount();

            if($status_conta == '3'){
                $BaixarFinanceiro['Status']                      = '1';
                $BaixarFinanceiro['ValorPagamento']              = $valor_pago;
                $BaixarFinanceiro['DataPagamento']               = $data_resposta['0'];
                $BaixarFinanceiro['TipoPagamento']               = '1';
                $BaixarFinanceiro['DetalheTipoPagamento']        = $tipo_pagamento;
                $BaixarFinanceiro['DetalheTipoPagamentoDetalhe'] = $detalhe_tipo_pagamento;
                $BaixarFinanceiro['DataBaixa']                   = date('Y-m-d');
                $BaixarFinanceiro['ConsultaPagSeguro'] = '0';
                Update('contas_receber', $BaixarFinanceiro, "WHERE Id = '".$id_conta."'");
            }
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}
