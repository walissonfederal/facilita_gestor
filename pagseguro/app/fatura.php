<?php
    session_start();
    if(isset($_GET['04'])){
        $_SESSION['BASE_ENTIDADE'] = base64_decode($_GET['04']);
    }
    require_once '../../_class/Ferramenta.php';
    $type_boleto    = addslashes(base64_decode($_GET['00']));
    $model_boleto   = addslashes(base64_decode($_GET['01']));
    $conta_boleto   = addslashes(base64_decode($_GET['02']));
    $url_boleto     = addslashes(base64_decode($_GET['03']));
    $tipo_calculo   = addslashes($_GET['05']);
    $data_vencimento_web = addslashes(base64_decode($_GET['06']));
    $juros_web = addslashes(base64_decode($_GET['07']));
    
    $read_financeiro = Read('financeiro', "WHERE financeiro_tipo = 'CR' AND financeiro_status = '0' AND financeiro_id IN($url_boleto) ORDER BY financeiro_codigo ASC");
    if(NumQuery($read_financeiro) > '0'){
        foreach($read_financeiro as $read_financeiro_view);
        //SEMPRE OS MESMOS DADOS
        $dias_de_prazo_para_pagamento = 0;
        $taxa_boleto = 0;
        $data_venc = FormDataBr($read_financeiro_view['financeiro_data_vencimento']);
        $valor_cobrado = $read_financeiro_view['financeiro_valor'];
        $valor_cobrado = str_replace(",", ".",$valor_cobrado);
        $valor_boleto=number_format($valor_cobrado+$taxa_boleto, 2, ',', '');
        if($tipo_calculo == 'web'){
            $ValorMulta = '0';
            $ValorJuros = '0';
            $ValorAtualizado = '0';
            if($data_vencimento_web == ''){
                $NovaDtVenc = date('Y-m-d');
                if(strtotime($read_financeiro_view['financeiro_data_vencimento']) < strtotime(date('Y-m-d'))){
                    $IntervadoVencido = strtotime($NovaDtVenc) - strtotime($read_financeiro_view['financeiro_data_vencimento']);
                    //$DiasIntervalo = DiferencaDias(FormDataBr($read_financeiro_paginator_view['financeiro_data_vencimento']), FormDataBr($NovaDtVenc));
                    $DiasIntervalo = floor($IntervadoVencido / (60 * 60 * 24));
                    //echo $DiasIntervalo.'<br />';
                    $ValorContaOriginal = $read_financeiro_view['financeiro_valor'];
                    $ValorMulta = (MULTA / 100) * $ValorContaOriginal;
                    $ValorJuros = ((JUROS * $ValorContaOriginal) / 100) * $DiasIntervalo;
                    $ValorJurosDia = ((JUROS * $ValorContaOriginal) / 100);
                    $ValorMultaDia = (MULTA / 100) * $ValorContaOriginal;
                    if($ValorJuros > '0'){
                        if($juros_web == '0'){
                            $ValorAtualizado = $ValorContaOriginal + $ValorMulta + $ValorJuros;
                            $DataVenc = FormDataBr($NovaDtVenc);
                        }else{
                            $ValorAtualizado = $ValorContaOriginal;
                            $ValorMulta = '0';
                            $ValorJuros = '0';
                            $DataVenc = FormDataBr($NovaDtVenc);
                        }
                    }else{
                        $ValorAtualizado = $ValorContaOriginal;
                        $DataVenc = FormDataBr($NovaDtVenc);
                        $ValorJurosDia = '0';
                        $ValorMultaDia = '0';
                        $ValorJuros = '0';
                        $ValorMulta = '0';
                    }
                }else{
                    $ValorAtualizado = $read_financeiro_view['financeiro_valor'];
                    $DataVenc = FormDataBr($read_financeiro_view['financeiro_data_vencimento']);
                    $ValorJuros = '0';
                    $ValorMulta = '0';
                }
            }else{
                $NovaDtVenc = $data_vencimento_web;
                if(strtotime($read_financeiro_view['financeiro_data_vencimento']) < strtotime(date('Y-m-d'))){
                    $IntervadoVencido = strtotime($NovaDtVenc) - strtotime($read_financeiro_view['financeiro_data_vencimento']);
                    //$DiasIntervalo = DiferencaDias(FormDataBr($read_financeiro_paginator_view['financeiro_data_vencimento']), FormDataBr($NovaDtVenc));
                    $DiasIntervalo = floor($IntervadoVencido / (60 * 60 * 24));
                    //echo $DiasIntervalo.'<br />';
                    $ValorContaOriginal = $read_financeiro_view['financeiro_valor'];
                    $ValorMulta = (MULTA / 100) * $ValorContaOriginal;
                    $ValorJuros = ((JUROS * $ValorContaOriginal) / 100) * $DiasIntervalo;
                    $ValorJurosDia = ((JUROS * $ValorContaOriginal) / 100);
                    $ValorMultaDia = (MULTA / 100) * $ValorContaOriginal;
                    if($ValorJuros > '0'){
                        if($juros_web == '0'){
                            $ValorAtualizado = $ValorContaOriginal + $ValorMulta + $ValorJuros;
                            $DataVenc = FormDataBr($NovaDtVenc);
                        }else{
                            $ValorAtualizado = $ValorContaOriginal;
                            $ValorMulta = '0';
                            $ValorJuros = '0';
                            $DataVenc = FormDataBr($NovaDtVenc);
                        }
                    }else{
                        $ValorAtualizado = $ValorContaOriginal;
                        $DataVenc = FormDataBr($NovaDtVenc);
                        $ValorJurosDia = '0';
                        $ValorMultaDia = '0';
                        $ValorJuros = '0';
                        $ValorMulta = '0';
                    }
                }else{
                    $ValorAtualizado = $read_financeiro_view['financeiro_valor'];
                    $DataVenc = FormDataBr($read_financeiro_view['financeiro_data_vencimento']);
                    $ValorJuros = '0';
                    $ValorMulta = '0';
                }
            }
            $data_venc = $DataVenc;
            $valor_cobrado = number_format($ValorAtualizado,2,".","");
            $valor_cobrado = str_replace(",", ".",$valor_cobrado);
            $valor_boleto=number_format($valor_cobrado+$taxa_boleto, 2, ',', '');
            //$valor_boleto = number_format($ValorAtualizado,2,".","");
        }
    }else{
        Redimencionamento('http://www.federalsistemas.com.br');
    }
    require_once "../vendor/autoload.php";
    
    \PagSeguro\Library::initialize();
    \PagSeguro\Library::cmsVersion()->setName("Nome")->setRelease("1.0.0");
    \PagSeguro\Library::moduleVersion()->setName("Nome")->setRelease("1.0.0");

    try {
        $sessionCode = \PagSeguro\Services\Session::create(
            \PagSeguro\Configuration\Configure::getAccountCredentials()
        );

        //echo "<strong>ID de sess&atilde;o criado: </strong>{$sessionCode->getResult()}";
    } catch (Exception $e) {
        //die($e->getMessage());
    }
    

    \PagSeguro\Library::initialize();
    \PagSeguro\Library::cmsVersion()->setName("Nome")->setRelease("1.0.0");
    \PagSeguro\Library::moduleVersion()->setName("Nome")->setRelease("1.0.0");

    $payment = new \PagSeguro\Domains\Requests\Payment();

    $payment->addItems()->withParameters(
        '0001',
        $read_financeiro_view['financeiro_descricao'],
        1,
        $read_financeiro_view['financeiro_valor']
    );
    $valorJurosMulta = $ValorJuros + $ValorMulta;
    if($valorJurosMulta != ''){
        $valor_total_juros_multa = $valorJurosMulta;
    }else{
        $valor_total_juros_multa = '0';
    }
    $payment->setCurrency("BRL");

    $payment->setExtraAmount($valor_total_juros_multa);

    $payment->setReference($read_financeiro_view['financeiro_codigo']);

    $payment->setRedirectUrl("http://www.federalsistemas.com.br");
    $readCliente = Read('contato', "WHERE contato_id = '".$read_financeiro_view['financeiro_id_contato']."'");
    if(NumQuery($readCliente) > '0'){
        foreach($readCliente as $readClienteView);
        $TelefoneSemMascara = str_replace('(', '', $readClienteView['contato_telefone']);
        $TelefoneSemMascara_1 = str_replace(')', '', $TelefoneSemMascara);
        $TelefoneSemMascara_2 = str_replace(' ', '', $TelefoneSemMascara_1);
        $TelefoneSemMascara_3 = str_replace('-', '', $TelefoneSemMascara_2);
        $DDDTelefone = substr($TelefoneSemMascara_3, 0,2);
        $Telefone = substr($TelefoneSemMascara_3, 2,15);
        
        if(strlen($readClienteView['contato_cpf_cnpj']) == '14'){
            $TypeDoc = 'CNPJ';
        }else{
            $TypeDoc = 'CPF';
        }
    }
    // Set your customer information.
    //$payment->setSender()->setName(substr($readClienteView['NomeRazao'],0,50));
    //$payment->setSender()->setEmail($readClienteView['Email']);
    /*$payment->setSender()->setPhone()->withParameters(
        $DDDTelefone,
        $Telefone
    );
    /*$payment->setSender()->setDocument()->withParameters(
        $TypeDoc,
        $readClienteView['CpfCnpj']
    );

    /*$payment->setShipping()->setAddress()->withParameters(
        $readClienteView['Endereco'],
        $readClienteView['Numero'],
        $readClienteView['Bairro'],
        str_replace('-', '', $readClienteView['Cep']),
        $readCidadeView['nome'],
        $readEstadosView['sigla'],
        'BRA',
        $readClienteView['Complemento']
    );*/
    //$payment->setShipping()->
    $payment->setShipping()->setCost()->withParameters(0.00);
    $payment->setShipping()->setType()->withParameters(\PagSeguro\Enum\Shipping\Type::SEDEX);

    //Add metadata items
    //$payment->addMetadata()->withParameters('PASSENGER_CPF', '35824468168');
    //$payment->addMetadata()->withParameters('GAME_NAME', 'DOTA');
    //$payment->addMetadata()->withParameters('PASSENGER_PASSPORT', '23456', 1);

    //Add items by parameter
    //On index, you have to pass in parameter: total items plus one.
    //$payment->addParameter()->withParameters('itemId', '0003')->index(3);
    //$payment->addParameter()->withParameters('itemDescription', 'Notebook Amarelo')->index(3);
    //$payment->addParameter()->withParameters('itemQuantity', '1')->index(3);
    //$payment->addParameter()->withParameters('itemAmount', '200.00')->index(3);

    //Add items by parameter using an array
    $payment->addParameter()->withArray(['notificationURL', 'http://www.federalsistemas.com.br/erp/notification.php']);

    $payment->setRedirectUrl("http://www.federalsistemas.com.br/erp/tela-confirmacao.php");
    $payment->setNotificationUrl("http://www.federalsistemas.com.br/erp/notification.php");

    //Add discount
    /*$payment->addPaymentMethod()->withParameters(
        PagSeguro\Enum\PaymentMethod\Group::CREDIT_CARD,
        PagSeguro\Enum\PaymentMethod\Config\Keys::DISCOUNT_PERCENT,
        0.00 // (float) Percent
    );*/

    //Add installments with no interest
    /*$payment->addPaymentMethod()->withParameters(
        PagSeguro\Enum\PaymentMethod\Group::CREDIT_CARD,
        PagSeguro\Enum\PaymentMethod\Config\Keys::MAX_INSTALLMENTS_NO_INTEREST,
        2 // (int) qty of installment
    );*/

    //Add a limit for installment
    /*$payment->addPaymentMethod()->withParameters(
        PagSeguro\Enum\PaymentMethod\Group::CREDIT_CARD,
        PagSeguro\Enum\PaymentMethod\Config\Keys::MAX_INSTALLMENTS_LIMIT,
        6 // (int) qty of installment
    );*/

    // Add a group and/or payment methods name
    $payment->acceptPaymentMethod()->groups(
        \PagSeguro\Enum\PaymentMethod\Group::CREDIT_CARD,
        \PagSeguro\Enum\PaymentMethod\Group::BALANCE,
        \PagSeguro\Enum\PaymentMethod\Group::BOLETO,
        \PagSeguro\Enum\PaymentMethod\Group::EFT,
        \PagSeguro\Enum\PaymentMethod\Group::DEPOSIT
    );
    //$payment->acceptPaymentMethod()->name(\PagSeguro\Enum\PaymentMethod\Name::DEBITO_ITAU);
    // Remove a group and/or payment methods name
    //$payment->excludePaymentMethod()->group(\PagSeguro\Enum\PaymentMethod\Group::BOLETO);


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
            . "<p><a title=\"URL do pagamento\" href=\"$result\" target=\_blank\">Fatura gerada. Clique aqui e faça o pagamento</a></p>";
        header("Location: $result");
    } catch (Exception $e) {
        die($e->getMessage());
    }
?>