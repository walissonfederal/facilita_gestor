<?php
require_once('d4sign-php-master/sdk/vendor/autoload.php');
require_once '_class/Ferramenta.php';
use D4sign\Client;
$client = new Client();
$client->setAccessToken("live_f8dc1a9ce037ca098d8b328e5efe42fd39e611e02e21976834dc1a7c1bdaf7fe");

$docs = $client->documents->find();
foreach($docs as $docs_view){
    if($docs_view->nameDoc == 'Contrato Chip'){
        $read_contrato_chip = Read('contrato_chip', "WHERE contrato_chip_id_d4sign = '".$docs_view->uuidDoc."'");
        if(NumQuery($read_contrato_chip) > '0'){
            if($docs_view->statusId == '4'){
                $Update_Contrato_Chip['contrato_chip_cliente_assinou'] = '1';
                $Update_Contrato_Chip['contrato_chip_status'] = '0';
                Update('contrato_chip', $Update_Contrato_Chip, "WHERE contrato_chip_id_d4sign = '".$docs_view->uuidDoc."'");
            }else{
                $Update_Contrato_Chip['contrato_chip_status'] = $docs_view->statusId;
                Update('contrato_chip', $Update_Contrato_Chip, "WHERE contrato_chip_id_d4sign = '".$docs_view->uuidDoc."'");
            }
        }
    }elseif($docs_view->nameDoc == 'Contrato Rastreamento'){
        $read_contrato_rastreamento = Read('contrato_rastreamento', "WHERE contrato_rastreamento_id_d4sign = '".$docs_view->uuidDoc."'");
        if(NumQuery($read_contrato_rastreamento) > '0'){
            if($docs_view->statusId == '4'){
                $Update_Contrato_Rastreamento['contrato_rastreamento_cliente_assinou'] = '1';
                $Update_Contrato_Rastreamento['contrato_rastreamento_status'] = '0';
                Update('contrato_rastreamento', $Update_Contrato_Rastreamento, "WHERE contrato_rastreamento_id_d4sign = '".$docs_view->uuidDoc."'");
            }else{
                $Update_Contrato_Rastreamento['contrato_rastreamento_status'] = $docs_view->statusId;
                Update('contrato_rastreamento', $Update_Contrato_Rastreamento, "WHERE contrato_rastreamento_id_d4sign = '".$docs_view->uuidDoc."'");
            }
        }
    }
}


