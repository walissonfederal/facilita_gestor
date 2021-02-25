<?php

include_once 'XmlDomConstruct.php';

class Baixa {

    /**
     * @var string
     */
    private $urlIntegracao = '';

    /**
     * @var XmlDomConstruct
     */
    private $dadosXml;

    public function __construct($informacoes, $arrDescontos = null) {
        $this->_setConfigs($informacoes, $arrDescontos);
    }

    /**
     * Realiza o registro no webservice da Caixa Econômica Federal
     *
     * @throws Exception
     */
    public function baixaRegistro() {

        try {
            $connCURL = curl_init($this->urlIntegracao);
            curl_setopt($connCURL, CURLOPT_POSTFIELDS, $this->dadosXml);
            curl_setopt($connCURL, CURLOPT_POST, true);
            curl_setopt($connCURL, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($connCURL, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($connCURL, CURLOPT_SSL_VERIFYHOST, false);

            curl_setopt($connCURL, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/xml',
                'SOAPAction: "BAIXA_BOLETO"'
            ));
            
            $responseCURL = curl_exec($connCURL);
            $err = curl_error($connCURL);
            curl_close($connCURL);

            if ($err) {
                return $infoArray;
            }

            $response = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $responseCURL);
            $xml = new SimpleXMLElement($response);
            $xmlArray = json_decode(json_encode((array) $xml), TRUE);
            $infoArray = $xmlArray['soapenvBody']['manutencaocobrancabancariaSERVICO_SAIDA']['DADOS'];
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }

        if (isset($infoArray['EXCECAO'])) {
            return $infoArray;
        }

        if ($infoArray['CONTROLE_NEGOCIAL']['COD_RETORNO'] == '00') {
            return $infoArray;
        } else {
            return $infoArray;
        }
    }

    /**
     * Aqui é realizada a configuração dos dados que serão encaminhados para o webservice
     * 
     * @param $informacoes
     * @param null $arrDescontos
     */
    private function _setConfigs($informacoes) {
        $this->urlIntegracao = $informacoes['urlIntegracao'];

        $arrayDadosHash = array(
            'codigoCedente' => $informacoes['codigoCedente'],
            'nossoNumero' => $informacoes['nossoNumero'],
            'dataVencimento' => 0,
            'valorNominal' => 0,
            'cnpj' => $informacoes['cnpj']
        );

        $autenticacao = $this->_geraHashAutenticacao($arrayDadosHash);

        $arrayDados = array(
            'soapenv:Body' => array(
                'manutencaocobrancabancaria:SERVICO_ENTRADA' => array(
                    'sibar_base:HEADER' => array(
                        'VERSAO' => '1.0',
                        'AUTENTICACAO' => $autenticacao,
                        'USUARIO_SERVICO' => 'SGCBS02P', //SGCBS02P - Produção | SGCBS01D - Desenvolvimento
                        'OPERACAO' => 'BAIXA_BOLETO', //Implementado apenas para inclusão
                        'SISTEMA_ORIGEM' => 'SIGCB',
                        'UNIDADE' => $informacoes['numeroAgencia'],
                        'DATA_HORA' => date('YmdHis')
                    ),
                    'DADOS' => array(
                        'BAIXA_BOLETO' => array(
                            'CODIGO_BENEFICIARIO' => $informacoes['codigoCedente'],
                            'NOSSO_NUMERO' => $informacoes['nossoNumero']
                        )
                    )
                )
            )
        );
        echo $this->_geraEstruturaXml($arrayDados);
    }

    /**
     * Realiza a geração do hash codificado para integração com a CAIXA - CEF
     * 
     * Autenticação validada pelo hash que a CAIXA retornou, abaixo segue o retorno
     * para fazer a validação caso necessário.
     * 
     * Código do Beneficiário		0443451
     * Nosso Número					00000000000000000
     * Data do Vencimento			30092017
     * Valor						000000024999998
     * CPF ou CNPJ do Beneficiário	05461103000172
     * Valor base para HASH			0443451000000000000000003009201700000002499999805461103000172
     * 
     * Codigo HASH esperado para os dados acima
     * MYS8MvnenMdBCqZOIqw357uYIi/WoDmr8fx+c5YREXs=
     * 
     * @param array $arrayDadosHash
     * @return string
     */
    private function _geraHashAutenticacao(array $arrayDadosHash) {
        $numeroParaHash = preg_replace('/[^A-Za-z0-9]/', '', str_pad($arrayDadosHash['codigoCedente'], 7, '0', STR_PAD_LEFT) .
                        $arrayDadosHash['nossoNumero'] .
                        strftime('%d%m%Y', strtotime($arrayDadosHash['dataVencimento']))) .
                str_pad(preg_replace('/[^0-9]/', '', $arrayDadosHash['valorNominal']), 15, '0', STR_PAD_LEFT) .
                str_pad($arrayDadosHash['cnpj'], 14, '0', STR_PAD_LEFT);

        $autenticacao = base64_encode(hash('sha256', $numeroParaHash, true));
        return $autenticacao;
    }

    /**
     * Recebe o array de dados e faz a geração do XML conforme layout da CAIXA
     * será armazenado em $this->dadosXml para envio posterior
     * 
     * @param array $arrayDados
     */
    private function _geraEstruturaXml(array $arrayDados) {
        $xml_root = 'soapenv:Envelope';
        $xml = new XmlDomConstruct('1.0', 'utf-8');
        $xml->preserveWhiteSpace = false;
        $xml->formatOutput = true;
        $xml->convertArrayToXml(array($xml_root => $arrayDados));
        $xml_root_item = $xml->getElementsByTagName($xml_root)->item(0);
        $xml_root_item->setAttribute(
                'xmlns:soapenv', 'http://schemas.xmlsoap.org/soap/envelope/'
        );
        $xml_root_item->setAttribute(
                'xmlns:manutencaocobrancabancaria', 'http://caixa.gov.br/sibar/manutencao_cobranca_bancaria/boleto/externo'
        );
        $xml_root_item->setAttribute(
                'xmlns:sibar_base', 'http://caixa.gov.br/sibar'
        );

        $this->dadosXml = $xml->saveXML();
    }

}
