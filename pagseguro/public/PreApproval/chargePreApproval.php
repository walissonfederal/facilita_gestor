<?php

require_once "../../vendor/autoload.php";

\PagSeguro\Library::initialize();
\PagSeguro\Library::cmsVersion()->setName("Nome")->setRelease("1.0.0");
\PagSeguro\Library::moduleVersion()->setName("Nome")->setRelease("1.0.0");

$preApproval = new \PagSeguro\Domains\Requests\PreApproval\Charge();
$preApproval->setReference("36FF35");
$preApproval->setCode("F93674228D8D013DD4C44F8B2BDB783C");
$preApproval->addItems()->withParameters(
    '0001',
    'Notebook prata',
    1,
    10.00
);

try {
    $response = $preApproval->register(\PagSeguro\Configuration\Configure::getAccountCredentials());
    echo "<pre>";
    print_r($response);
} catch (Exception $e) {
    die($e->getMessage());
}