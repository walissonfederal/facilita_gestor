<?php
$curl = curl_init();
 
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_URL, 'https://api-3t.paypal.com/nvp');
curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array(
    'USER' => 'diretor_api1.federalsistemas.com.brrr',
    'PWD' => 'MATU4BEUYXCRLBAC',
    'SIGNATURE' => 'AiPC9BjkCyDFQXbSkoZcgqH3hpacAus3X.FV1oP8753hIGt2hNCoEWnD',
 
    'METHOD' => 'UpdateRecurringPaymentsProfile',
    'VERSION' => '108',
    'PROFILEID' => 'I-CPB4EM9R665G',
 
    'NOTE' => 'Uma nota opcional, explicando o motivo da mudança',
    'AMT' => 6,
    'CURRENCYCODE' => 'BRL'
)));
 
$response =    curl_exec($curl);
 
curl_close($curl);
 
$nvp = array();
 
if (preg_match_all('/(?<name>[^\=]+)\=(?<value>[^&]+)&?/', $response, $matches)) {
    foreach ($matches['name'] as $offset => $name) {
        $nvp[$name] = urldecode($matches['value'][$offset]);
    }
}
 
print_r($nvp);