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
        $order_by = "ORDER BY departamento_id DESC";
    }else{
        
        $sort       = addslashes($_GET['sort']);
        $sort_dir   = addslashes($_GET['sort_dir']);
        if($sort == 'departamento_status_view'){
            $order_by   = "ORDER BY departamento_id ".$sort_dir."";
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
            $sql_id = "AND departamento_id = '".$get_id."'";
        }else{
            $sql_id = "";
        }
        if($get_descricao != ''){
            $sql_descricao = "AND departamento_descricao LIKE '%".$get_descricao."%'";
        }else{
            $sql_descricao = "";
        }
        if($get_status != ''){
            $sql_status = "AND departamento_status = '".$get_status."'";
        }else{
            $sql_status = "";
        }
        
        $_SESSION['departamento_load'] = "".$sql_id." ".$sql_descricao." ".$sql_status." ";
    }
    
    $read_departamento_paginator = ReadComposta("SELECT departamento_id FROM departamento WHERE departamento_id != '' {$_SESSION['departamento_load']}");
    $read_departamento = Read('departamento', "WHERE departamento_id != '' {$_SESSION['departamento_load']} ".$order_by." LIMIT $inicio,$maximo");
    if(NumQuery($read_departamento) > '0'){
        //PEGA QUANTOS BOTÕES PARA PAGINAÇÃO DE RESULTADOS
        $paginas = ceil(NumQuery($read_departamento_paginator) / $maximo);
        //TABULATOR EXIGE ESSA INFORMAÇÃO PARA QUE PRECISE DA PAGINAÇÃO DE RESULTADOS 
        $json_departamento["last_page"] = $paginas;
        foreach($read_departamento as $read_departamento_view){
            if($read_departamento_view['departamento_status'] == '0'){
                $read_departamento_view['departamento_status_view'] = 'Ativo';
            }else{
                $read_departamento_view['departamento_status_view'] = 'Inativo';
            }
            $json_departamento['data'][] = $read_departamento_view;
        }
    }else{
        $json_departamento['data'] = null;
    }
    echo json_encode($json_departamento);
}elseif($acao == 'create'){
    //RECUPERA O FORMULARIO
    $departamento_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($departamento_form['acao']);
    
    if(in_array('', $departamento_form)){
        $json_departamento = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        Create('departamento', $departamento_form);
        $json_departamento = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal" onclick="limpar_campos();">Apenas Fechar</button><a href="javascript::" onclick="carrega_pagina(\'departamento\', \'index.php\');" data-dismiss="modal" class="btn btn-primary">Sair</a>'
        );
    }
    echo json_encode($json_departamento);
}elseif($acao == 'update'){
    //RECUPERA O FORMULARIO
    $departamento_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($departamento_form['acao']);
    
    if(in_array('', $departamento_form)){
        $json_departamento = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        $uid = addslashes($_POST['id']);
        unset($departamento_form['id']);
        Update('departamento', $departamento_form, "WHERE departamento_id = '".$uid."'");
        $json_departamento = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<a href="javascript::" data-dismiss="modal" onclick="carrega_pagina(\'departamento\', \'index.php\');" class="btn btn-primary">Sair</a>'
        );
    }
    echo json_encode($json_departamento);
}elseif($acao == 'load_update'){
    $uid = addslashes($_POST['id']);
    
    $read_departamento = Read('departamento', "WHERE departamento_id = '".$uid."'");
    if(NumQuery($read_departamento) > '0'){
        foreach($read_departamento as $read_departamento_view);
        $json_departamento[] = $read_departamento_view;
    }else{
        $json_departamento = null;
    }
    echo json_encode($json_departamento);
}elseif($acao == 'load_departamento'){
    $read_departamento = Read('departamento', "ORDER BY departamento_descricao ASC");
    if(NumQuery($read_departamento) > '0'){
        foreach($read_departamento as $read_departamento_view){
            $json_departamento["data"][] = $read_departamento_view;
        }
        echo json_encode($json_departamento);
    }
}elseif($acao == 'gerar_excel'){
    $arquivo = 'departamento.xls';
    $tabela = '<table border="1" width="800px">';
        $tabela .= '<tr>';
            $tabela .= '<td colspan="3" align="center">Relação de departamentos</tr>';
        $tabela .= '</tr>';
        $tabela .= '<tr>';
            $tabela .= '<td><b>Código</b></td>';
            $tabela .= '<td><b>Descrição</b></td>';
            $tabela .= '<td><b>Status</b></td>';
        $tabela .= '</tr>';
    
    $read_departamento = Read('departamento', "WHERE departamento_id != '' {$_SESSION['departamento_load']} ORDER BY departamento_descricao ASC");
    if(NumQuery($read_departamento) > '0'){
        foreach($read_departamento as $read_departamento_view){
            if($read_departamento_view['departamento_status'] == '0'){
                $status_departamento = 'ATIVO';
            }else{
                $status_departamento = 'INATIVO';
            }
            $tabela .= '<tr>';
                $tabela .= '<td>'.$read_departamento_view['departamento_id'].'</td>';
                $tabela .= '<td>'.$read_departamento_view['departamento_descricao'].'</td>';
                $tabela .= '<td>'.$status_departamento.'</td>';
            $tabela .= '</tr>';
        }
    }
    
    header("Content-type: application/vnd.ms-excel");  
    header("Content-type: application/force-download"); 
    header("Content-Disposition: attachment; filename=file.xls");
    header("Pragma: no-cache");
    echo $tabela;
}elseif($acao == 'load_departamento'){
    $read_departamento = Read('departamento', "ORDER BY departamento_descricao ASC");
    if(NumQuery($read_departamento) > '0'){
        foreach($read_departamento as $read_departamento_view){
            $json_departamento["data"][] = $read_departamento_view;
        }
        echo json_encode($json_departamento);
    }
}
?>