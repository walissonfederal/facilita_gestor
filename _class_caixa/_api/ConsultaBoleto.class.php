<?php

    include_once 'lib/Consulta.php';
    
    class ConsultaBoleto{
        
        
        private $urlIntegracao = '';
        
        private $dadosXml;
        
        public function __construct($informacoes) {
            $this->_setConfigs($informacoes);
        }
        
        public function consultaRegistro() {
            try {
                $connCURL = curl_init($this->urlIntegracao);
                curl_setopt($connCURL, CURLOPT_POSTFIELDS, $this->dadosXml);
                curl_setopt($connCURL, CURLOPT_POST, true);
                curl_setopt($connCURL, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($connCURL, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($connCURL, CURLOPT_SSL_VERIFYHOST, false);

                curl_setopt($connCURL, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/xml',
                    'SOAPAction: "CONSULTA_BOLETO"'
                ));

                $responseCURL = curl_exec($connCURL);
                $err = curl_error($connCURL);
                curl_close($connCURL);

                if ($err) {
                    echo '<pre>';
                    print_r($err);
                    die;
                }


                $response = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $responseCURL);
                $xml = new SimpleXMLElement($response);
                $xmlArray = json_decode(json_encode((array) $xml), TRUE);
                $infoArray = $xmlArray['soapenvBody']['consultacobrancabancariaSERVICO_SAIDA']['DADOS'];
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
                    'consultacobrancabancaria:SERVICO_ENTRADA' => array(
                        'sibar_base:HEADER' => array(
                            'VERSAO' => '1.0',
                            'AUTENTICACAO' => $autenticacao,
                            'USUARIO_SERVICO' => 'SGCBS02P', //SGCBS02P - Produção | SGCBS01D - Desenvolvimento
                            'OPERACAO' => 'CONSULTA_BOLETO', //Implementado apenas para inclusão
                            'SISTEMA_ORIGEM' => 'SIGCB',
                            'UNIDADE' => $informacoes['numeroAgencia'],
                            'DATA_HORA' => date('YmdHis')
                        ),
                        'DADOS' => array(
                            'CONSULTA_BOLETO' => array(
                                'CODIGO_BENEFICIARIO' => $informacoes['codigoCedente'],
                                'NOSSO_NUMERO' => $informacoes['nossoNumero']
                            )
                        )
                    )
                )
            );
            echo $this->_geraEstruturaXml($arrayDados);
        }
        
        private function _geraHashAutenticacao(array $arrayDadosHash) {
            $numeroParaHash = preg_replace('/[^A-Za-z0-9]/', '', str_pad($arrayDadosHash['codigoCedente'], 7, '0', STR_PAD_LEFT) .
                            $arrayDadosHash['nossoNumero'] .
                            ((!$arrayDadosHash['dataVencimento']) ?
                                    sprintf('%08d', 0) :
                                    strftime('%d%m%Y', strtotime($arrayDadosHash['dataVencimento'])))) .
                    str_pad(preg_replace('/[^0-9]/', '', $arrayDadosHash['valorNominal']), 15, '0', STR_PAD_LEFT) .
                    str_pad($arrayDadosHash['cnpj'], 14, '0', STR_PAD_LEFT);

            $autenticacao = base64_encode(hash('sha256', $numeroParaHash, true));
            return $autenticacao;
        }
        
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
                    'xmlns:consultacobrancabancaria', 'http://caixa.gov.br/sibar/consulta_cobranca_bancaria/boleto'
            );
            $xml_root_item->setAttribute(
                    'xmlns:sibar_base', 'http://caixa.gov.br/sibar'
            );

            $this->dadosXml = $xml->saveXML();
        }
        
        
    }