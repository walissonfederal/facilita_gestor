<?php

require_once 'vendor/autoload.php';

BoletoSimples::configure(array(
    "environment" => 'sandbox', // default: 'sandbox'
    "access_token" => '65b88beb6fb96921d630eb8ce7691d05cc240bd8ed2a9c7a1a63123faf6ece8e'
));
$bank_billet = BoletoSimples\BankBillet::create(array(
    'amount' => 150,
    'bank_billet_account_id' => 1444,
    'our_number' => 26534,
    'description' => 'Despesas do contrato 0012',
    'expire_at' => '2018-02-28',
    'customer_address' => 'Rua quinhentos',
    'customer_address_complement' => 'Sala 4',
    'customer_address_number' => '111',
    'customer_city_name' => 'Rio de Janeiro',
    'customer_cnpj_cpf' => '04317159120',
    'customer_email' => 'cliente@example.com',
    'customer_neighborhood' => 'Sao Francisco',
    'customer_person_name' => 'Joao da Silva',
    'customer_person_type' => 'individual',
    'customer_phone_number' => '2112123434',
    'customer_state' => 'RJ',
    'customer_zipcode' => '12312-123'
));

if($bank_billet->isPersisted()) {
    echo "Sucesso :)\n";
    print_r($bank_billet->attributes());
} else {
    echo "Erro :(\n";
    print_r($bank_billet->response_errors);
}
