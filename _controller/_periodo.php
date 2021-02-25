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
        $order_by = "ORDER BY periodo_id DESC";
    }else{
        
        $sort       = addslashes($_GET['sort']);
        $sort_dir   = addslashes($_GET['sort_dir']);
        $order_by   = "ORDER BY ".$sort." ".$sort_dir."";
    }
    
    //PESQUISA VIA AJAX
    if(isset($_GET['search']) && $_GET['search'] == 'true'){
        $get_id         = addslashes($_GET['id']);
        $get_descricao  = addslashes($_GET['descricao']);
        
        if($get_id != ''){
            $sql_id = "AND cargo_id = '".$get_id."'";
        }else{
            $sql_id = "";
        }
        if($get_descricao != ''){
            $sql_descricao = "AND cargo_descricao LIKE '%".$get_descricao."%'";
        }else{
            $sql_descricao = "";
        }
        
        $_SESSION['periodo_load'] = " ".$sql_id." ".$sql_descricao." ";
    }
    
    $read_periodo_paginator = ReadComposta("SELECT periodo_id FROM periodo WHERE periodo_id != '' {$_SESSION['periodo_load']}");
    $read_periodo = Read('periodo', "WHERE periodo_id != '' {$_SESSION['periodo_load']} ".$order_by." LIMIT $inicio,$maximo");
    if(NumQuery($read_periodo) > '0'){
        //PEGA QUANTOS BOTÕES PARA PAGINAÇÃO DE RESULTADOS
        $paginas = ceil(NumQuery($read_periodo_paginator) / $maximo);
        //TABULATOR EXIGE ESSA INFORMAÇÃO PARA QUE PRECISE DA PAGINAÇÃO DE RESULTADOS 
        $json_periodo["last_page"] = $paginas;
        foreach($read_periodo as $read_periodo_view){
            $json_periodo['data'][] = $read_periodo_view;
        }
    }else{
        $json_periodo['data'] = null;
    }
    echo json_encode($json_periodo);
}elseif($acao == 'create'){
    //RECUPERA O FORMULARIO
    $periodo_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($periodo_form['acao']);
    
    if(in_array('', $periodo_form)){
        $json_periodo = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        Create('periodo', $periodo_form);
        $json_periodo = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal" onclick="limpar_campos();">Apenas Fechar</button><a href="javascript::" onclick="carrega_pagina(\'periodo\', \'index.php\');" data-dismiss="modal" class="btn btn-primary">Sair</a>'
        );
    }
    echo json_encode($json_periodo);
}elseif($acao == 'update'){
    //RECUPERA O FORMULARIO
    $periodo_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($periodo_form['acao']);
    
    if(in_array('', $periodo_form)){
        $json_periodo = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        $uid = addslashes($_POST['id']);
        unset($periodo_form['id']);
        Update('periodo', $periodo_form, "WHERE periodo_id = '".$uid."'");
        $json_periodo = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<a href="javascript::" data-dismiss="modal" onclick="carrega_pagina(\'periodo\', \'index.php\');" class="btn btn-primary">Sair</a>'
        );
    }
    echo json_encode($json_periodo);
}elseif($acao == 'load_update'){
    $uid = addslashes($_POST['id']);
    
    $read_periodo = Read('periodo', "WHERE periodo_id = '".$uid."'");
    if(NumQuery($read_periodo) > '0'){
        foreach($read_periodo as $read_periodo_view);
        $json_periodo[] = $read_periodo_view;
    }else{
        $json_periodo = null;
    }
    echo json_encode($json_periodo);
}elseif($acao == 'load_periodo'){
    $read_periodo = Read('periodo', "ORDER BY periodo_descricao ASC");
    if(NumQuery($read_periodo) > '0'){
        foreach($read_periodo as $read_periodo_view){
            $json_periodo["data"][] = $read_periodo_view;
        }
        echo json_encode($json_periodo);
    }
}elseif($acao == 'gerar_excel'){
    $arquivo = 'cargo.xls';
    $tabela = '<table border="1" width="800px">';
        $tabela .= '<tr>';
            $tabela .= '<td colspan="3" align="center">Relação de cargos</tr>';
        $tabela .= '</tr>';
        $tabela .= '<tr>';
            $tabela .= '<td><b>Código</b></td>';
            $tabela .= '<td><b>Descrição</b></td>';
            $tabela .= '<td><b>Data Inicial</b></td>';
            $tabela .= '<td><b>Data Final</b></td>';
        $tabela .= '</tr>';
    
    $read_periodo = Read('periodo', "WHERE periodo_id != '' {$_SESSION['periodo_load']} ORDER BY periodo_descricao ASC");
    if(NumQuery($read_periodo) > '0'){
        foreach($read_periodo as $read_periodo_view){
            $tabela .= '<tr>';
                $tabela .= '<td>'.$read_periodo_view['periodo_id'].'</td>';
                $tabela .= '<td>'.$read_periodo_view['periodo_descricao'].'</td>';
                $tabela .= '<td>'.$read_periodo_view['periodo_data_inicial'].'</td>';
                $tabela .= '<td>'.$read_periodo_view['periodo_data_final'].'</td>';
            $tabela .= '</tr>';
        }
    }
    
    header("Content-type: application/vnd.ms-excel");  
    header("Content-type: application/force-download"); 
    header("Content-Disposition: attachment; filename=file.xls");
    header("Pragma: no-cache");
    echo $tabela;
}
?>