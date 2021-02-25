<?php
require_once('../../d4sign-php-master/sdk/vendor/autoload.php');

use D4sign\Client;
$client = new Client();
$client->setAccessToken("live_f8dc1a9ce037ca098d8b328e5efe42fd39e611e02e21976834dc1a7c1bdaf7fe");

//Você poderá fazer download do ZIP ou apenas do PDF setando o último parametro.
$url_doc = $client->documents->getfileurl(trim(addslashes($_GET['arquivo'])),'zip');
//print_r($url_doc);

$arquivo = file_get_contents($url_doc->url);

//CASO VOCÊ ESTEJA FAZENDO O DOWNLOAD APENAS DO PDF, NÃO ESQUEÇA DE ALTERAR O CONTENT-TYPE PARA application/pdf E O NOME DO ARQUIVO PARA .PDF
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"".$url_doc->name.".zip"."\"");

//Para PDF
//header("Content-type: application/pdf");
//header("Content-Disposition: attachment; filename=\"".$url_doc->name.".pdf"."\"");

echo $arquivo;

//Você poderá, também, simplesmente redirecionar o seu usuário para a URL final de download.