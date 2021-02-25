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
        $order_by = "ORDER BY categoria_id DESC";
    }else{
        
        $sort       = addslashes($_GET['sort']);
        $sort_dir   = addslashes($_GET['sort_dir']);
        if($sort == 'categoria_status_view'){
            $order_by   = "ORDER BY categoria_id ".$sort_dir."";
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
            $sql_id = "AND categoria_id = '".$get_id."'";
        }else{
            $sql_id = "";
        }
        if($get_descricao != ''){
            $sql_descricao = "AND categoria_descricao LIKE '%".$get_descricao."%'";
        }else{
            $sql_descricao = "";
        }
        if($get_status != ''){
            $sql_status = "AND categoria_status = '".$get_status."'";
        }else{
            $sql_status = "";
        }
        
        $_SESSION['categoria_load'] = "".$sql_id." ".$sql_descricao." ".$sql_status." ";
    }
    
    $read_categoria_paginator = ReadComposta("SELECT categoria_id FROM categoria WHERE categoria_id != '' {$_SESSION['categoria_load']}");
    $read_categoria = Read('categoria', "WHERE categoria_id != '' {$_SESSION['categoria_load']} ".$order_by." LIMIT $inicio,$maximo");
    if(NumQuery($read_categoria) > '0'){
        //PEGA QUANTOS BOTÕES PARA PAGINAÇÃO DE RESULTADOS
        $paginas = ceil(NumQuery($read_categoria_paginator) / $maximo);
        //TABULATOR EXIGE ESSA INFORMAÇÃO PARA QUE PRECISE DA PAGINAÇÃO DE RESULTADOS 
        $json_categoria["last_page"] = $paginas;
        foreach($read_categoria as $read_categoria_view){
            if($read_categoria_view['categoria_status'] == '0'){
                $read_categoria_view['categoria_status_view'] = 'Ativo';
            }else{
                $read_categoria_view['categoria_status_view'] = 'Inativo';
            }
            $json_categoria['data'][] = $read_categoria_view;
        }
    }else{
        //$json_categoria['data'][] = '';
    }
    echo json_encode($json_categoria);
}elseif($acao == 'create'){
    //RECUPERA O FORMULARIO
    $categoria_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($categoria_form['acao']);
    
    if(in_array('', $categoria_form)){
        $json_categoria = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        Create('categoria', $categoria_form);
        $json_categoria = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal" onclick="limpar_campos();">Apenas Fechar</button><a href="javascript::" onclick="carrega_pagina(\'categoria\', \'index.php\');" data-dismiss="modal" class="btn btn-primary">Sair</a>'
        );
    }
    echo json_encode($json_categoria);
}elseif($acao == 'update'){
    //RECUPERA O FORMULARIO
    $categoria_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($categoria_form['acao']);
    
    if(in_array('', $categoria_form)){
        $json_categoria = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        $uid = addslashes($_POST['id']);
        unset($categoria_form['id']);
        Update('categoria', $categoria_form, "WHERE categoria_id = '".$uid."'");
        $json_categoria = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<a href="javascript::" onclick="carrega_pagina(\'categoria\', \'index.php\');" data-dismiss="modal" class="btn btn-primary">Sair</a>'
        );
    }
    echo json_encode($json_categoria);
}elseif($acao == 'load_update'){
    $uid = addslashes($_POST['id']);
    
    $read_categoria = Read('categoria', "WHERE categoria_id = '".$uid."'");
    if(NumQuery($read_categoria) > '0'){
        foreach($read_categoria as $read_categoria_view);
        $json_categoria[] = $read_categoria_view;
    }else{
        $json_categoria = null;
    }
    echo json_encode($json_categoria);
}elseif($acao == 'load_categoria'){
    $term = addslashes($_GET['term']);
    
    $read_categoria_load = Read('categoria', "WHERE categoria_descricao LIKE '%".$term."%' ORDER BY categoria_descricao ASC");
    if(NumQuery($read_categoria_load) > '0'){
        $json_categoria = '[';
        foreach($read_categoria_load as $read_categoria_load_view){
            $json_categoria .= '{"label":"'.$read_categoria_load_view['categoria_descricao'].'","value":"'.$read_categoria_load_view['categoria_id'].'"},';
        }
        $json_categoria = substr($json_categoria, 0,-1);
        $json_categoria .= ']';
    }else{
        $json_categoria = array(
                array( "label" => '', "value" => '' ),
        );
    }
    echo $json_categoria;
}elseif($acao == 'load_categoria_id'){
    $uid = addslashes($_POST['id']);
    
    $read_categoria_load_id = Read('categoria', "WHERE categoria_id = '".$uid."' ORDER BY categoria_descricao ASC");
    if(NumQuery($read_categoria_load_id) > '0'){
        $json_categoria = '[';
        foreach($read_categoria_load_id as $read_categoria_load_id_view){
            $json_categoria .= '{"label":"'.$read_categoria_load_id_view['categoria_descricao'].'","value":"'.$read_categoria_load_id_view['categoria_id'].'"},';
        }
        $json_categoria = substr($json_categoria, 0,-1);
        $json_categoria .= ']';
    }else{
        $json_categoria = '[';
            $json_categoria .= '{"label":"","value":""}';
        $json_categoria .= ']';
    }
    echo $json_categoria;
}elseif($acao == 'gerar_excel'){
    $arquivo = 'categoria.xls';
    $tabela = '<table border="1" width="800px">';
        $tabela .= '<tr>';
            $tabela .= '<td colspan="3" align="center">Relação de categorias</tr>';
        $tabela .= '</tr>';
        $tabela .= '<tr>';
            $tabela .= '<td><b>Código</b></td>';
            $tabela .= '<td><b>Descrição</b></td>';
            $tabela .= '<td><b>Status</b></td>';
        $tabela .= '</tr>';
    
    $read_categoria = Read('categoria', "WHERE categoria_id != '' {$_SESSION['categoria_load']} ORDER BY categoria_descricao ASC");
    if(NumQuery($read_categoria) > '0'){
        foreach($read_categoria as $read_categoria_view){
            if($read_categoria_view['categoria_status'] == '0'){
                $status_categoria = 'ATIVO';
            }else{
                $status_categoria = 'INATIVO';
            }
            $tabela .= '<tr>';
                $tabela .= '<td>'.$read_categoria_view['categoria_id'].'</td>';
                $tabela .= '<td>'.$read_categoria_view['categoria_descricao'].'</td>';
                $tabela .= '<td>'.$status_categoria.'</td>';
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