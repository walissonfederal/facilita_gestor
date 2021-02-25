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
        $order_by = "ORDER BY diretoria_id DESC";
    }else{
        
        $sort       = addslashes($_GET['sort']);
        $sort_dir   = addslashes($_GET['sort_dir']);
        $order_by   = "ORDER BY ".$sort." ".$sort_dir."";
    }
    
    //PESQUISA VIA AJAX
    if(isset($_GET['search']) && $_GET['search'] == 'true'){
        $get_id         = addslashes($_GET['id']);
        $get_descricao  = addslashes($_GET['descricao']);
        $get_id_periodo = addslashes($_GET['id_periodo']);
        
        if($get_id != ''){
            $sql_id = "AND diretoria_id = '".$get_id."'";
        }else{
            $sql_id = "";
        }
        if($get_descricao != ''){
            $sql_descricao = "AND diretoria_descricao LIKE '%".$get_descricao."%'";
        }else{
            $sql_descricao = "";
        }
        if($get_id_periodo != ''){
            $sql_id_periodo = "AND diretoria_id_periodo = '".$get_id_periodo."'";
        }else{
            $sql_id_periodo = "";
        }
        
        $_SESSION['diretoria_load'] = "".$sql_id." ".$sql_descricao." ".$sql_id_periodo." ";
    }
    
    $read_diretoria_paginator = ReadComposta("SELECT diretoria_id FROM diretoria WHERE diretoria_id != '' {$_SESSION['diretoria_load']}");
    $read_diretoria = Read('diretoria', "WHERE diretoria_id != '' {$_SESSION['diretoria_load']} ".$order_by." LIMIT $inicio,$maximo");
    if(NumQuery($read_diretoria) > '0'){
        //PEGA QUANTOS BOTÕES PARA PAGINAÇÃO DE RESULTADOS
        $paginas = ceil(NumQuery($read_diretoria_paginator) / $maximo);
        //TABULATOR EXIGE ESSA INFORMAÇÃO PARA QUE PRECISE DA PAGINAÇÃO DE RESULTADOS 
        $json_diretoria["last_page"] = $paginas;
        foreach($read_diretoria as $read_diretoria_view){
            $read_diretoria_view['diretoria_id_periodo'] = GetDados('periodo', $read_diretoria_view['diretoria_id_periodo'], 'periodo_id', 'periodo_descricao');
            $json_diretoria['data'][] = $read_diretoria_view;
        }
    }else{
        $json_diretoria['data'] = null;
    }
    echo json_encode($json_diretoria);
}elseif($acao == 'create'){
    //RECUPERA O FORMULARIO
    $diretoria_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($diretoria_form['acao']);
    
    if(in_array('', $diretoria_form)){
        $json_diretoria = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        Create('diretoria', $diretoria_form);
        $json_diretoria = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal" onclick="limpar_campos();">Apenas Fechar</button><a href="javascript::" onclick="carrega_pagina(\'diretoria\', \'index.php\');" data-dismiss="modal" class="btn btn-primary">Sair</a>'
        );
    }
    echo json_encode($json_diretoria);
}elseif($acao == 'update'){
    //RECUPERA O FORMULARIO
    $diretoria_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($diretoria_form['acao']);
    
    if(in_array('', $diretoria_form)){
        $json_diretoria = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        $uid = addslashes($_POST['id']);
        unset($diretoria_form['id']);
        Update('diretoria', $diretoria_form, "WHERE diretoria_id = '".$uid."'");
        $json_diretoria = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<a href="javascript::" data-dismiss="modal" onclick="carrega_pagina(\'cargo\', \'index.php\');" class="btn btn-primary">Sair</a>'
        );
    }
    echo json_encode($json_diretoria);
}elseif($acao == 'load_update'){
    $uid = addslashes($_POST['id']);
    
    $read_diretoria = Read('diretoria', "WHERE diretoria_id = '".$uid."'");
    if(NumQuery($read_diretoria) > '0'){
        foreach($read_diretoria as $read_diretoria_view);
        $json_diretoria[] = $read_diretoria_view;
    }else{
        $json_diretoria = null;
    }
    echo json_encode($json_diretoria);
}
?>