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
        $order_by = "ORDER BY plano_assinatura_id DESC";
    }else{
        
        $sort       = addslashes($_GET['sort']);
        $sort_dir   = addslashes($_GET['sort_dir']);
        if($sort == 'plano_assinatura_status_view'){
            $order_by   = "ORDER BY plano_assinatura_id ".$sort_dir."";
        }else{
            $order_by   = "ORDER BY ".$sort." ".$sort_dir."";
        }
    }
    
    //PESQUISA VIA AJAX
    if(isset($_GET['search']) && $_GET['search'] == 'true'){
        $get_id         = addslashes($_GET['id']);
        $get_descricao  = addslashes($_GET['descricao']);
        $get_status     = addslashes($_GET['status']);
        
        if($get_id != ''){
            $sql_id = "AND plano_assinatura_id = '".$get_id."'";
        }else{
            $sql_id = "";
        }
        if($get_descricao != ''){
            $sql_descricao = "AND plano_assinatura_descricao LIKE '%".$get_descricao."%'";
        }else{
            $sql_descricao = "";
        }
        if($get_status != ''){
            $sql_status = "AND plano_assinatura_status = '".$get_status."'";
        }else{
            $sql_status = "";
        }
        
        $_SESSION['plano_assinatura_load'] = "".$sql_id." ".$sql_descricao." ".$sql_status." ";
    }
    
    $read_plano_assinatura_paginator = ReadComposta("SELECT plano_assinatura_id FROM plano_assinatura WHERE plano_assinatura_id != '' {$_SESSION['plano_assinatura_load']}");
    $read_plano_assinatura = Read('plano_assinatura', "WHERE plano_assinatura_id != '' {$_SESSION['plano_assinatura_load']} ".$order_by." LIMIT $inicio,$maximo");
    if(NumQuery($read_plano_assinatura) > '0'){
        //PEGA QUANTOS BOTÕES PARA PAGINAÇÃO DE RESULTADOS
        $paginas = ceil(NumQuery($read_plano_assinatura_paginator) / $maximo);
        //TABULATOR EXIGE ESSA INFORMAÇÃO PARA QUE PRECISE DA PAGINAÇÃO DE RESULTADOS 
        $json_plano_assinatura["last_page"] = $paginas;
        foreach($read_plano_assinatura as $read_plano_assinatura_view){
            if($read_plano_assinatura_view['plano_assinatura_status'] == '0'){
                $read_plano_assinatura_view['plano_assinatura_status_view'] = 'Ativo';
            }else{
                $read_plano_assinatura_view['plano_assinatura_status_view'] = 'Inativo';
            }
            $json_plano_assinatura['data'][] = $read_plano_assinatura_view;
        }
    }else{
        $json_plano_assinatura['data'] = null;
    }
    echo json_encode($json_plano_assinatura);
}elseif($acao == 'create'){
    //RECUPERA O FORMULARIO
    $plano_assinatura_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($plano_assinatura_form['acao']);
    
    if(in_array('', $plano_assinatura_form)){
        $json_plano_assinatura = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        Create('plano_assinatura', $plano_assinatura_form);
        $json_plano_assinatura = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal" onclick="limpar_campos();">Apenas Fechar</button><a href="javascript::" onclick="carrega_pagina(\'plano-assinatura\', \'index.php\');" data-dismiss="modal" class="btn btn-primary">Sair</a>'
        );
    }
    echo json_encode($json_plano_assinatura);
}elseif($acao == 'update'){
    //RECUPERA O FORMULARIO
    $plano_assinatura_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($plano_assinatura_form['acao']);
    
    if(in_array('', $plano_assinatura_form)){
        $json_plano_assinatura = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        $uid = addslashes($_POST['id']);
        unset($plano_assinatura_form['id']);
        Update('plano_assinatura', $plano_assinatura_form, "WHERE plano_assinatura_id = '".$uid."'");
        $json_plano_assinatura = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<a href="javascript::" data-dismiss="modal" onclick="carrega_pagina(\'plano-assinatura\', \'index.php\');" class="btn btn-primary">Sair</a>'
        );
    }
    echo json_encode($json_plano_assinatura);
}elseif($acao == 'load_update'){
    $uid = addslashes($_POST['id']);
    
    $read_plano_assinatura = Read('plano_assinatura', "WHERE plano_assinatura_id = '".$uid."'");
    if(NumQuery($read_plano_assinatura) > '0'){
        foreach($read_plano_assinatura as $read_plano_assinatura_view);
        $json_plano_assinatura[] = $read_plano_assinatura_view;
    }else{
        $json_plano_assinatura = null;
    }
    echo json_encode($json_plano_assinatura);
}elseif($acao == 'load_plano_assinatura'){
    $read_plano_assinatura = Read('plano_assinatura', "ORDER BY plano_assinatura_descricao ASC");
    if(NumQuery($read_plano_assinatura) > '0'){
        foreach($read_plano_assinatura as $read_plano_assinatura_view){
            $json_plano_assinatura["data"][] = $read_plano_assinatura_view;
        }
        echo json_encode($json_plano_assinatura);
    }
}
?>