<?php
session_start();
ob_start();
require_once '../_class/Ferramenta.php';

if(isset($_POST['acao'])){
    $acao = addslashes($_POST['acao']);
}else{
    $acao = addslashes($_GET['acao']);
}

if($acao == 'load'){
    //VERIFICAÇÃO PADRÃO PARA PAGINAÇÃO DOS RESULTADOS
    $pag = (empty($_GET['pageNo']) ? '1' : $_GET['pageNo']);
    if(empty($_GET['size'])){
        $maximo = '100';
    }else{
        $maximo = $_GET['size'];
    }
    $inicio = ($pag * $maximo) - $maximo;
    
    //ORDENAÇÃO DO TABULATOR
    if(empty($_GET['sort']) && empty($_GET['sort_dir'])){
        $order_by = "ORDER BY caixa_conta_data_lancamento ASC";
    }else{
        
        $sort       = addslashes($_GET['sort']);
        $sort_dir   = addslashes($_GET['sort_dir']);
        $order_by   = "ORDER BY ".$sort." ".$sort_dir."";
    }
    
    //PESQUISA VIA AJAX
    if(isset($_GET['search']) && $_GET['search'] == 'true'){
        $get_data_inicial   = addslashes($_GET['data_inicial']);
        $get_data_final     = addslashes($_GET['data_final']);
        $get_id_caixa       = addslashes($_GET['id_caixa']);
        
        if($get_id_caixa != ''){
            $sql_id_caixa = "AND caixa_conta_id_caixa = '".$get_id_caixa."'";
        }else{
            $sql_id_caixa = "";
        }
        if($get_data_inicial != '' && $get_data_final != ''){
            $sql_periodo = "AND caixa_conta_data_lancamento BETWEEN '".$get_data_inicial."' AND '".$get_data_final."'";
        }else{
            $sql_periodo = "";
        }
        
        $_SESSION['report_caixa_conta_id_caixa'] = $get_id_caixa; 
        $_SESSION['caixa_conta_load_report'] = "".$sql_periodo."";
        $_SESSION['caixa_conta_load_report_data_inicio'] = "".$get_data_inicial."";
        
        $_SESSION['caixa_conta_load'] = "".$sql_id_caixa." ".$sql_periodo."";
    }else{
        $get_data_inicial = date('Y-m-d');
        $get_id_caixa = '-0-';
    }
    $DataSaldoAnterior = date('Y-m-d', strtotime("-1day",strtotime($get_data_inicial)));
    $readCaixaSaldoAnterior = Read('caixa_conta', "WHERE caixa_conta_data_lancamento BETWEEN '2000-01-01' AND '".$DataSaldoAnterior."' AND caixa_conta_id_caixa = '".$get_id_caixa."'");
    if($readCaixaSaldoAnterior){
        foreach($readCaixaSaldoAnterior as $readCaixaSaldoAnteriorView){
            if($readCaixaSaldoAnteriorView['caixa_conta_tipo_lancamento'] == 'C'){
                $SaldoCreditoAnterior += $readCaixaSaldoAnteriorView['caixa_conta_valor_lancamento'];
            }elseif($readCaixaSaldoAnteriorView['caixa_conta_tipo_lancamento'] == 'D'){
                $SaldoDebitoAnterior += $readCaixaSaldoAnteriorView['caixa_conta_valor_lancamento'];
            }
        }
        $SaldoAnteriorTotal = $SaldoCreditoAnterior - $SaldoDebitoAnterior;
    }
    
    $json_caixa['saldo_anterior'] = FormatMoney($SaldoAnteriorTotal);
    $read_caixa_paginator = ReadComposta("SELECT caixa_conta_id FROM caixa_conta WHERE caixa_conta_id != '' {$_SESSION['caixa_conta_load']}");
    $read_caixa = Read('caixa_conta', "WHERE caixa_conta_id_caixa != '0' {$_SESSION['caixa_conta_load']} ".$order_by." LIMIT $inicio,$maximo");
    if(NumQuery($read_caixa) > '0'){
        //PEGA QUANTOS BOTÕES PARA PAGINAÇÃO DE RESULTADOS
        $paginas = ceil(NumQuery($read_caixa_paginator) / $maximo);
        //TABULATOR EXIGE ESSA INFORMAÇÃO PARA QUE PRECISE DA PAGINAÇÃO DE RESULTADOS 
        $json_caixa["last_page"] = $paginas;
        
        foreach($read_caixa as $read_caixa_view){
            $read_caixa_view['caixa_conta_data_lancamento'] = FormDataBr($read_caixa_view['caixa_conta_data_lancamento']);
            if($read_caixa_view['caixa_conta_tipo_lancamento'] == 'C'){
                $SaldoCreditoTotal  += $read_caixa_view['caixa_conta_valor_lancamento'];
            }elseif($read_caixa_view['caixa_conta_tipo_lancamento'] == 'D'){
                $SaldoDebitoTotal   += $read_caixa_view['caixa_conta_valor_lancamento'];
            }
            $json_caixa['data'][] = $read_caixa_view;
        }
        $SaldoFinal = ($SaldoCreditoTotal - $SaldoDebitoTotal) + $SaldoAnteriorTotal;
        $json_caixa['entradas_periodo'] = FormatMoney($SaldoCreditoTotal);
        $json_caixa['saidas_periodo'] = FormatMoney($SaldoDebitoTotal);
        $json_caixa['saldo_final'] = FormatMoney($SaldoFinal);
    }else{
        $json_caixa['data'] = null;
    }
    echo json_encode($json_caixa);
}elseif($acao == 'load_update'){
    $uid = addslashes($_POST['id']);
    
    $read_caixa_conta = Read('caixa_conta', "WHERE caixa_conta_id = '".$uid."'");
    if(NumQuery($read_caixa_conta) > '0'){
        foreach($read_caixa_conta as $read_caixa_conta_view);
        $json_caixa_conta[] = $read_caixa_conta_view;
    }else{
        $json_caixa_conta = null;
    }
    echo json_encode($json_caixa_conta);
}elseif($acao == 'update'){
    //RECUPERA O FORMULARIO
    $caixa_conta_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($caixa_conta_form['acao']);
    
    if(GetEmpresa('empresa_caixa_conta_config') == '0'){
        if(GetEmpresa('empresa_caixa_conta_pass') == $caixa_conta_form['pass']){
            $get_pass = '1';
        }else{
            $get_pass = '0';
        }
    }else{
        $get_pass = '1';
    }
    
    if(in_array('', $caixa_conta_form)){
        $json_caixa_conta = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        if($get_pass == '1'){
            $uid = addslashes($_POST['id']);
            unset($caixa_conta_form['id']);
            unset($caixa_conta_form['pass']);
            Update('caixa_conta', $caixa_conta_form, "WHERE caixa_conta_id = '".$uid."'");
            $read_caixa_conta = ReadComposta("SELECT caixa_conta_id_financeiro FROM caixa_conta WHERE caixa_conta_id = '".$uid."'");
            if(NumQuery($read_caixa_conta) > '0'){
                foreach($read_caixa_conta as $read_caixa_conta_view);
                $financeiro_form['financeiro_descricao']        = $caixa_conta_form['caixa_conta_descricao'];
                $financeiro_form['financeiro_valor_pagamento']  = $caixa_conta_form['caixa_conta_valor_lancamento'];
                $financeiro_form['financeiro_data_pagamento']   = $caixa_conta_form['caixa_conta_data_lancamento'];
                Update('financeiro', $financeiro_form, "WHERE financeiro_id = '".$read_caixa_conta_view['caixa_conta_id_financeiro']."'");
            }
            $json_caixa_conta = array(
                'type' => 'success',
                'title' => 'Parabéns:',
                'msg' => 'Operação realizada com sucesso',
                'buttons' => '<a href="javascript::" onclick="carrega_pagina(\'caixa-conta\', \'index.php\');" data-dismiss="modal" class="btn btn-primary">Sair</a>'
            );
        }else{
            $json_caixa_conta = array(
                'type' => 'success',
                'title' => 'Ops:',
                'msg' => 'Ops, senha incorreta',
                'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
            );
        }
    }
    echo json_encode($json_caixa_conta);
}
?>