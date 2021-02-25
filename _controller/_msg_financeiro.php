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
        $order_by = "ORDER BY msg_financeiro_id DESC";
    }else{
        $sort       = addslashes($_GET['sort']);
        $sort_dir   = addslashes($_GET['sort_dir']);
        if($sort == 'msg_financeiro_status_view'){
            $order_by   = "ORDER BY msg_financeiro_id ".$sort_dir."";
        }else{
            $order_by   = "ORDER BY ".$sort." ".$sort_dir."";
        }
    }
    
    $read_msg_financeiro_paginator = ReadComposta("SELECT msg_financeiro_id FROM msg_financeiro");
    $read_msg_financeiro = Read('msg_financeiro', "WHERE msg_financeiro_id != '' ".$order_by." LIMIT $inicio,$maximo");
    if(NumQuery($read_msg_financeiro) > '0'){
        //PEGA QUANTOS BOTÕES PARA PAGINAÇÃO DE RESULTADOS
        $paginas = ceil(NumQuery($read_msg_financeiro_paginator) / $maximo);
        //TABULATOR EXIGE ESSA INFORMAÇÃO PARA QUE PRECISE DA PAGINAÇÃO DE RESULTADOS 
        $json_msg_financeiro["last_page"] = $paginas;
        foreach($read_msg_financeiro as $read_msg_financeiro_view){
            if($read_msg_financeiro_view['msg_financeiro_status'] == '0'){
                $read_msg_financeiro_view['msg_financeiro_status_view'] = 'Ativo';
            }else{
                $read_msg_financeiro_view['msg_financeiro_status_view'] = 'Inativo';
            }
            $json_msg_financeiro['data'][] = $read_msg_financeiro_view;
        }
    }else{
        $json_msg_financeiro['data'] = null;
    }
    echo json_encode($json_msg_financeiro);
}elseif($acao == 'create'){
    //RECUPERA O FORMULARIO
    $msg_financeiro_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($msg_financeiro_form['acao']);
    
    if(in_array('', $msg_financeiro_form)){
        $json_msg_financeiro = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        Create('msg_financeiro', $msg_financeiro_form);
        $json_msg_financeiro = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal" onclick="limpar_campos();">Apenas Fechar</button><a href="javascript::" data-dismiss="modal" onclick="carrega_pagina(\'msg-financeiro\', \'index.php\');" class="btn btn-primary">Sair</a>'
        );
    }
    echo json_encode($json_msg_financeiro);
}elseif($acao == 'update'){
    //RECUPERA O FORMULARIO
    $msg_financeiro_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($msg_financeiro_form['acao']);
    
    if(in_array('', $msg_financeiro_form)){
        $json_msg_financeiro = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        $uid = addslashes($_POST['id']);
        unset($msg_financeiro_form['id']);
        Update('msg_financeiro', $msg_financeiro_form, "WHERE msg_financeiro_id = '".$uid."'");
        $json_msg_financeiro = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<a href="javascript::" data-dismiss="modal" onclick="carrega_pagina(\'msg-financeiro\', \'index.php\');" class="btn btn-primary">Sair</a>'
        );
    }
    echo json_encode($json_msg_financeiro);
}elseif($acao == 'load_update'){
    $uid = addslashes($_POST['id']);
    
    $read_msg_financeiro = Read('msg_financeiro', "WHERE msg_financeiro_id = '".$uid."'");
    if(NumQuery($read_msg_financeiro) > '0'){
        foreach($read_msg_financeiro as $read_msg_financeiro_view);
        $json_msg_financeiro[] = $read_msg_financeiro_view;
    }else{
        $json_msg_financeiro = null;
    }
    echo json_encode($json_msg_financeiro);
}
?>