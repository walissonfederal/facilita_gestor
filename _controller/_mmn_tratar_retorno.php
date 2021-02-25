<?php
session_start();
ob_start();
require_once '../_class_mmn/Ferramenta.php';

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

        $enviar = uploadFile($arquivo, '../_uploads/federal_sistemas/retorno/', $tipos);

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
                    if($f['baixa_valor_pago'] > '0'):
						Create('baixa', $f);
					endif;
                }
            }
        }
        Delete('baixa', "WHERE baixa_nosso_numero = '' AND baixa_id_baixa_retorno = '".$_SESSION['IdBaixas']."'");
        @mysqli_query(Conn(), "UPDATE baixa SET DataImport = NULL");
        $readReport = Read('baixa', "WHERE baixa_id_baixa_retorno = '".$_SESSION['IdBaixas']."' AND baixa_valor_pago > '0'");
        if(NumQuery($readReport) > '0'){
            foreach($readReport as $readReportView){
                $NossoNumero = $readReportView['baixa_nosso_numero'];
                $readFinanceiro = Read('pedido', "WHERE pedido_status = '0' AND pedido_nosso_numero = '".$NossoNumero."'");
                if(NumQuery($readFinanceiro) > '0'){
                    foreach($readFinanceiro as $readFinanceiroView);
                    
                    
                    if($readFinanceiroView['pedido_valor'] > $readReportView['baixa_valor_pago']){
                        $BaixaUpdate['baixa_obs'] = 'Baixa não autorizada: valor menor';
                    }else{
                        $teste_dados['teste'] = 'ok';
                        Create('teste', $teste_dados);
                        $FinanceiroBaixa['pedido_status']           = '1';
                        $FinanceiroBaixa['pedido_data_pagamento']   = FormatEUA($readReportView['baixa_data_credito']);
                        $FinanceiroBaixa['pedido_valor_pagamento']  = $readReportView['baixa_valor_pago'];
                        Update('pedido', $FinanceiroBaixa, "WHERE pedido_nosso_numero = '".$NossoNumero."' LIMIT 1");
                        GerarComissao($readFinanceiroView['pedido_id_user'], $readFinanceiroView['pedido_valor']);
                        
                        if($readFinanceiroView['pedido_tipologia'] == '1'){
                            $user_update_pedido['user_status'] = '1';
                            $user_update_pedido['user_data_ativacao'] = date('Y-m-d');
                            Update('user', $user_update_pedido, "WHERE user_id = '".$readFinanceiroView['pedido_id_user']."' AND user_status = '0'");
                        }
                    }
                }
            }
        }
        
        
        $data['sucesso'] = true;
        $data['msg'] = 'OK';
    }
    echo json_encode($data);
}
?>