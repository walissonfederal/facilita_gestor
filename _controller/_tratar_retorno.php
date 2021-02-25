<?php
session_start();
ob_start();
require_once '../_class/Ferramenta.php';

require_once("../_remessa/retorno_bancario/RetornoBanco.php");
require_once("../_remessa/retorno_bancario/RetornoFactory.php");

if(isset($_POST['acao'])){
    $acao = addslashes($_POST['acao']);
}else{
    $acao = addslashes($_GET['acao']);
}

if($acao == 'enviar'){
    
    $get_tipo_arquivo   = addslashes($_GET['tipo_arquivo']);
    $get_id_caixa       = addslashes($_GET['id_caixa']);
    $get_data_pagamento = addslashes($_GET['data_pagamento']);
    
    $_SESSION['retorno_tipo_arquivo']   = $get_tipo_arquivo;
    $_SESSION['retorno_id_caixa']       = $get_id_caixa;
    $_SESSION['retorno_data_pagamento'] = $get_data_pagamento;
    
    if($get_id_caixa == ''){
        $data['sucesso'] = false;

        $data['msg'] = 'Todos os campos devem ser preenchidos!';
    }else{
        
        $arquivo = $_FILES['arquivo'];

        $tipos = array('txt', 'ret');

        $enviar = uploadFile($arquivo, '../_uploads/'.GetEmpresa('empresa_nome_pasta').'/retorno/', $tipos);

        $data['sucesso'] = false;

        if($enviar['erro']){    
            $data['msg'] = $enviar['erro'];
        }
        else{
            $data['sucesso'] = true;

            $data['msg'] = $enviar['caminho'];
            
            $retorno_form['baixa_retorno_arquivo']      = $enviar['caminho'];
            $retorno_form['baixa_retorno_data_hora']    = date('Y-m-d H:i:s');
            $retorno_form['baixa_retorno_banco']        = $get_tipo_arquivo;
            Create('baixa_retorno', $retorno_form);
        }
    }
    echo json_encode($data);
}elseif($acao == 'tratar_retorno'){
    require_once("../_remessa/retorno_bancario/RetornoBanco.php");
    require_once("../_remessa/retorno_bancario/RetornoFactory.php");
    if($_SESSION['retorno_tipo_arquivo'] == '2'){
        $_SESSION['IdBaixas'] = GetReg('baixa_retorno', 'baixa_retorno_id', "WHERE baixa_retorno_id != ''");
        
        $ArquivoRetornoUltimo = GetDados('baixa_retorno', $_SESSION['IdBaixas'], 'baixa_retorno_id', 'baixa_retorno_arquivo');
        
        $ArquivoRetorno = $ArquivoRetornoUltimo;
        $ArquivoArray 	= array();
        $ArqAberto 	= fopen($ArquivoRetorno, 'r');
        $TotalLinhas	= 0;

        while(!feof($ArqAberto)){
            $ConteudoArquivo = fgets($ArqAberto);
            $LinhaArquivo    = explode(';', $ConteudoArquivo);

            $ArquivoArray[$TotalLinhas] = $LinhaArquivo;
            $TotalLinhas++;
        }
        foreach($ArquivoArray as $LinhaArquivo){
            foreach($LinhaArquivo as $Campo){
                if(substr($Campo, 13,1) == 'T'){
                    $f['baixa_id_baixa_retorno']= $_SESSION['IdBaixas'];
                    $f['baixa_identificador']   = md5(date('Y-m-d H:i:s').rand(9,999999999999999999));
                    $f['baixa_nosso_numero'] 	= trim(substr($Campo, 39,18));
                    $f['baixa_valor']   	= addslashes(trim(substr($Campo, 81,15) / 100));
                    $f['baixa_data_import'] 	= date('d/m/Y');
                }elseif(substr($Campo, 13,1) == 'U'){
                    $f['baixa_valor_pago'] 	= addslashes(trim(substr($Campo, 77,15) / 100));
                    $f['baixa_data_credito'] 	= formataData(substr($Campo, 137,8));
                    if($f['baixa_valor_pago'] > '1'){
                        Create('baixa', $f);
                    }
                }
            }
        }
        Delete('baixa', "WHERE baixa_nosso_numero = '' AND baixa_id_baixa_retorno = '".$_SESSION['IdBaixas']."'");
        @mysqli_query(Conn(), "UPDATE baixa SET DataImport = NULL");
        $readReport = Read('baixa', "WHERE baixa_id_baixa_retorno = '".$_SESSION['IdBaixas']."'");
        if(NumQuery($readReport) > '0'){
            foreach($readReport as $readReportView){
                $NossoNumero = $readReportView['baixa_nosso_numero'];
                $readFinanceiro = Read('financeiro', "WHERE financeiro_status = '0' AND financeiro_nosso_numero = '".$NossoNumero."' OR financeiro_nosso_numero_ult = '".$NossoNumero."'");
                if($readFinanceiro){
                    foreach($readFinanceiro as $readFinanceiroView);
                    
                    $email_cliente_baixa = GetDados('contato', $readFinanceiroView['financeiro_id_contato'], 'contato_id', 'contato_email');
                    $nome_cliente_baixa = GetDados('contato', $readFinanceiroView['financeiro_id_contato'], 'contato_id', 'contato_nome_razao');
                    if(valMail($email_cliente_baixa)){
                        $msg_mail = "Olá ".$nome_cliente_baixa." tudo bem?<br />Estou passando aqui para te agradecer pelo pagamento do boleto no valor de R$".$readReportView['baixa_valor_pago'].", agradecemos pela parceria de sempre.";
                        $MSG_1 = str_replace('#NOMEEMPRESA#', GetEmpresa('empresa_nome_razao'), MSGEMAIL);
                        $MSG_2 = str_replace('#IMGEMPRESA#', GetEmpresa('empresa_logo'), $MSG_1);
                        $MSG_3 = str_replace('#TITULOMAIL#', 'Confirmação de pagamento', $MSG_2);
                        $MSG_4 = str_replace('#MSGMAIL#', $msg_mail, $MSG_3);
                        $MSG_5 = str_replace('#MAILEMPRESA#', GetEmpresa('empresa_email'), $MSG_4);
                        $MSG_6 = str_replace('#FONEEMPRESA#', GetEmpresa('empresa_telefone'), $MSG_5);
                        $MSG_7 = str_replace('#LINKBOLETO#', '', $MSG_6);
                        //sendMailCampanha('Pagamento confirmado', $MSG_7, GetEmpresa('empresa_email'), GetEmpresa('empresa_nome_fantasia'), $email_cliente_baixa, $nome_cliente_baixa);
                    }
                    
                    $FinanceiroBaixa['financeiro_status']           = '1';
                    $FinanceiroBaixa['financeiro_data_pagamento']   = FormatEUA($readReportView['baixa_data_credito']);
                    $FinanceiroBaixa['financeiro_data_baixa']       = date('Y-m-d');
                    $FinanceiroBaixa['financeiro_valor_pagamento']  = $readReportView['baixa_valor_pago'];
                    Update('financeiro', $FinanceiroBaixa, "WHERE financeiro_nosso_numero = '".$NossoNumero."' OR financeiro_nosso_numero_ult = '".$NossoNumero."' LIMIT 1");

                    $CaixaContaCreate['caixa_conta_id_financeiro']	= $readFinanceiroView['financeiro_id'];
                    $CaixaContaCreate['caixa_conta_data_lancamento']	= FormatEUA($readReportView['baixa_data_credito']);
                    $CaixaContaCreate['caixa_conta_valor_lancamento']   = $readReportView['baixa_valor_pago'];
                    $CaixaContaCreate['caixa_conta_numero_doc']		= $readFinanceiroView['financeiro_numero_doc'];
                    $CaixaContaCreate['caixa_conta_tipo_lancamento']	= 'C';
                    $CaixaContaCreate['caixa_conta_descricao']		= $readFinanceiroView['financeiro_descricao'];
                    $CaixaContaCreate['caixa_conta_id_caixa']		= $_SESSION['retorno_id_caixa'];
                    Create('caixa_conta', $CaixaContaCreate);
                }
            }
        }
        
        
        $data['sucesso'] = true;
        $data['msg'] = 'OK';
    }
    echo json_encode($data);
}
?>