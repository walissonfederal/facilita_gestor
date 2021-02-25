<?php
    
    include_once 'RegistraBoleto.class.php';
    include_once 'ConsultaBoleto.class.php';
    include_once 'AlteraBoleto.class.php';
    include_once 'BaixaBoleto.class.php';
    
    class Integra extends Validator {
        
        private $url_consulta           = 'https://barramento.caixa.gov.br/sibar/ConsultaCobrancaBancaria/Boleto';
        private $url_operacao           = 'https://barramento.caixa.gov.br/sibar/ManutencaoCobrancaBancaria/Boleto/Externo';
        private $codigo_beneficiario    = '273007';
        private $cnpj_beneficiario      = '11655954000159';
        private $juros_beneficiario     = '2.00';
        private $multa_beneficiario     = '2.00';
        
        private function RemoveString($string) {
            $nao_quero = array( 'ä','ã','à','á','â','ê','ë','è','é','ï','ì','í','ö','õ','ò','ó','ô','ü','ù','ú','û','À','Á','É','Í','Ó','Ú','ñ','Ñ','ç','Ç',' ','-','(',')',',',';',':','|','!','"','#','$','%','&','/','=','?','~','^','>','<','ª','º', '¹', '²', '³', '@', '£', '¢', '¬', '¨', '_', '+', '§', '^', '[', '{', ']', 'º', '.', "\\", '*');
            $quero  = array( 'a','a','a','a','a','e','e','e','e','i','i','i','o','o','o','o','o','u','u','u','u','A','A','E','I','O','U','n','n','c','C',' ','','','','','','','','','','','','','','','','','','','','','','','', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');
            return str_replace($nao_quero, $quero, $string);
        }
        
        
        public function RegistraBoletoCaixa($nosso_numero, $data_vencimento, $valor, $codigo, $data_lancamento, $nome_contato, $cpf_cnpj_contato){
            
            $RemoverCPFCNPJ = array(".", "-", "/", " ");
            $InserirCPFCNPJ = array("", "", "", "");
            
            $NewCPFCNPJ = str_replace($RemoverCPFCNPJ, $InserirCPFCNPJ, $cpf_cnpj_contato);
            
            $QuantidadeCPFCPNJ = strlen($NewCPFCNPJ);
            
            if($QuantidadeCPFCPNJ == '11'):
                if(!$this->CPF($NewCPFCNPJ)):
                    $NewCPFCNPJ = $this->GeraCPF('0');
                endif;
                $QuantidadeType = 'CPF';
                $QuantidadeNome = 'NOME';
            else:
                if(!$this->CNPJ($NewCPFCNPJ)):
                    $NewCPFCNPJ = $this->GeraCNPJ('0');
                endif;
                $QuantidadeType = 'CNPJ';
                $QuantidadeNome = 'RAZAO_SOCIAL';
            endif;
            
            $NameContato = substr($this->RemoveString($nome_contato),0,40);
			
			$array_search_name = array("’", "'");
			$array_troca_name = array("", "");
            
            $ArrRegistra = array(
                'urlIntegracao' => $this->url_operacao,
                'codigoCedente' => $this->codigo_beneficiario,
                'nossoNumero' => $nosso_numero,
                'dataVencimento' => $data_vencimento,
                'valorNominal' => $valor,
                'cnpj' => $this->cnpj_beneficiario,
                'codigoTitulo' => $codigo,
                'dataEmissao' => $data_lancamento,
                'dataJuros' => date('Y-m-d', strtotime('+1 days', strtotime($data_vencimento))),
                'juros' => $this->juros_beneficiario,
                'dataMulta' => date('Y-m-d', strtotime('+1 days', strtotime($data_vencimento))),
                'multa' => $this->multa_beneficiario,
                'infoPagador' => array(
                    $QuantidadeType => $NewCPFCNPJ,
                    $QuantidadeNome => str_replace($array_search_name, $array_troca_name, $NameContato),
                    'ENDERECO' => array(
                        'LOGRADOURO' => 'AV CONTORNO',
                        'BAIRRO' => 'R SANTA CLARA',
                        'CIDADE' => 'GOIANESIA',
                        'UF' => 'GO',
                        'CEP' => '76380260'
                    )
                )
            );
            
            $CaixaRegistra = new RegistraBoleto($ArrRegistra);
            $Return = $CaixaRegistra->realizarRegistro();
            return $Return;
        }
        
        public function ConsultaBoletoCaixa($nosso_numero){
            $ArrConsulta = array(
                'urlIntegracao' => $this->url_consulta,
                'codigoCedente' => $this->codigo_beneficiario,
                'nossoNumero' => $nosso_numero,
                'cnpj' => $this->cnpj_beneficiario
            );
            
            $CaixaConsulta = new ConsultaBoleto($ArrConsulta);
            $Return = $CaixaConsulta->consultaRegistro();
            return $Return;
        }
        
        public function AlteraBoletoCaixa($nosso_numero, $data_vencimento, $valor, $codigo){
            $ArrAltera = array(
                'urlIntegracao' => $this->url_operacao,
                'codigoCedente' => $this->codigo_beneficiario,
                'nossoNumero' => $nosso_numero,
                'dataVencimento' => $data_vencimento,
                'valorNominal' => $valor,
                'cnpj' => $this->cnpj_beneficiario,
                'codigoTitulo' => $codigo,
                'dataJuros' => date('Y-m-d', strtotime('+1 days', strtotime($data_vencimento))),
                'juros' => '2.00',
                'dataMulta' => date('Y-m-d', strtotime('+1 days', strtotime($data_vencimento))),
                'multa' => '2.00',
            );
            
            $CaixaAltera = new AlteraBoleto($ArrAltera);
            $Return = $CaixaAltera->alteraRegistro();
            return $Return;
        }
        
        public function BaixaBoletoCaixa($nosso_numero){
            $ArrConsulta = array(
                'urlIntegracao' => $this->url_operacao,
                'codigoCedente' => $this->codigo_beneficiario,
                'nossoNumero' => $nosso_numero,
                'cnpj' => $this->cnpj_beneficiario
            );
            
            $CaixaBaixa = new BaixaBoleto($ArrConsulta);
            $Return = $CaixaBaixa->baixaRegistro();
            return $Return;
        }
        
    }
