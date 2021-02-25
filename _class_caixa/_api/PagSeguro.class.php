<?php

    class PagSeguro{
        
        private $email_pagseguro = 'junioralphasistemas@gmail.com';
        private $token_producao = '';
        private $token_sandbox = '2D25C818B62B4DEF847C59F97C670E09';
        private $ambiente = '2';
        
        
        public function SessionId(){
            if($this->ambiente == '1'):
                $url = '';
            else:
                $url = 'https://ws.sandbox.pagseguro.uol.com.br/v2/sessions?email='.$this->email_pagseguro.'&token='.$this->token_sandbox.'';
            endif;
            
            $curl = curl_init($url);
            curl_setopt($curl,CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded; charset=UTF-8"));
            curl_setopt($curl,CURLOPT_POST,1);
            curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,false);
            curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
            $retorno = curl_exec($curl);
            curl_close($curl);
            
            $xml = simplexml_load_string($retorno);
            return $xml;
        }
    }
