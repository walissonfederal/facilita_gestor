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
        $order_by = "ORDER BY sub_categoria.sub_categoria_id DESC";
    }else{
        
        $sort       = addslashes($_GET['sort']);
        $sort_dir   = addslashes($_GET['sort_dir']);
        if($sort == 'sub_categoria_status_view'){
            $order_by   = "ORDER BY sub_categoria.sub_categoria_id ".$sort_dir."";
        }else{
            $order_by   = "ORDER BY ".$sort." ".$sort_dir."";
        }
    }
    
    //PESQUISA VIA AJAX
    if(isset($_GET['search']) && $_GET['search'] == 'true'){
        $get_id         = addslashes($_GET['id']);
        $get_descricao  = addslashes($_GET['descricao']);
        $get_status     = addslashes($_GET['status']);
        $get_id_categoria = addslashes($_GET['id_categoria']);
        
        if($get_id != ''){
            $sql_id = "AND sub_categoria.sub_categoria_id = '".$get_id."'";
        }else{
            $sql_id = "";
        }
        if($get_id_categoria != ''){
            $sql_id_categoria = "AND sub_categoria.sub_categoria_id_categoria = '".$get_id_categoria."'";
        }else{
            $sql_id_categoria = "";
        }
        if($get_descricao != ''){
            $sql_descricao = "AND sub_categoria.sub_categoria_descricao LIKE '%".$get_descricao."%'";
        }else{
            $sql_descricao = "";
        }
        if($get_status != ''){
            $sql_status = "AND sub_categoria.sub_categoria_status = '".$get_status."'";
        }else{
            $sql_status = "";
        }
        
        $_SESSION['sub_categoria_load'] = "".$sql_id." ".$sql_descricao." ".$sql_status." ".$sql_id_categoria."";
    }
    
    $read_sub_categoria_paginator = ReadComposta("SELECT sub_categoria_id FROM sub_categoria WHERE sub_categoria.sub_categoria_id != '' {$_SESSION['sub_categoria_load']}");
    //$read_sub_categoria = Read('sub_categoria', "WHERE sub_categoria_id != '' {$_SESSION['sub_categoria_load']} ".$order_by." LIMIT $inicio,$maximo");
    $read_sub_categoria = ReadComposta("SELECT sub_categoria.sub_categoria_id, sub_categoria.sub_categoria_descricao, sub_categoria.sub_categoria_status, sub_categoria.sub_categoria_id_categoria, categoria.categoria_id, categoria.categoria_descricao AS sub_categoria_descricao_categoria FROM sub_categoria INNER JOIN categoria ON sub_categoria.sub_categoria_id_categoria = categoria.categoria_id WHERE sub_categoria.sub_categoria_id != '' {$_SESSION['sub_categoria_load']} ".$order_by." LIMIT $inicio,$maximo");
    if(NumQuery($read_sub_categoria) > '0'){
        //PEGA QUANTOS BOTÕES PARA PAGINAÇÃO DE RESULTADOS
        $paginas = ceil(NumQuery($read_sub_categoria_paginator) / $maximo);
        //TABULATOR EXIGE ESSA INFORMAÇÃO PARA QUE PRECISE DA PAGINAÇÃO DE RESULTADOS 
        $json_sub_categoria["last_page"] = $paginas;
        foreach($read_sub_categoria as $read_sub_categoria_view){
            if($read_sub_categoria_view['sub_categoria_status'] == '0'){
                $read_sub_categoria_view['sub_categoria_status_view'] = 'Ativo';
            }else{
                $read_sub_categoria_view['sub_categoria_status_view'] = 'Inativo';
            }
            $json_sub_categoria['data'][] = $read_sub_categoria_view;
        }
    }else{
        $json_sub_categoria['data'] = null;
    }
    echo json_encode($json_sub_categoria);
}elseif($acao == 'create'){
    //RECUPERA O FORMULARIO
    $sub_categoria_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($sub_categoria_form['acao']);
    
    if(in_array('', $sub_categoria_form)){
        $json_sub_categoria = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        Create('sub_categoria', $sub_categoria_form);
        $json_sub_categoria = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal" onclick="limpar_campos();">Apenas Fechar</button><a href="javascript::" onclick="carrega_pagina(\'sub-categoria\', \'index.php\');" data-dismiss="modal" class="btn btn-primary">Sair</a>'
        );
    }
    echo json_encode($json_sub_categoria);
}elseif($acao == 'update'){
    //RECUPERA O FORMULARIO
    $sub_categoria_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($sub_categoria_form['acao']);
    
    if(in_array('', $sub_categoria_form)){
        $json_sub_categoria = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        $uid = addslashes($_POST['id']);
        unset($sub_categoria_form['id']);
        Update('sub_categoria', $sub_categoria_form, "WHERE sub_categoria_id = '".$uid."'");
        $json_sub_categoria = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<a href="javascript::" onclick="carrega_pagina(\'sub-categoria\', \'index.php\');" data-dismiss="modal" class="btn btn-primary">Sair</a>'
        );
    }
    echo json_encode($json_sub_categoria);
}elseif($acao == 'load_update'){
    $uid = addslashes($_POST['id']);
    
    $read_sub_categoria = Read('sub_categoria', "WHERE sub_categoria_id = '".$uid."'");
    if(NumQuery($read_sub_categoria) > '0'){
        foreach($read_sub_categoria as $read_sub_categoria_view);
        $json_sub_categoria[] = $read_sub_categoria_view;
    }else{
        $json_sub_categoria = null;
    }
    echo json_encode($json_sub_categoria);
}elseif($acao == 'load_categoria'){
    $read_categoria = Read('categoria', "ORDER BY categoria_descricao ASC");
    if(NumQuery($read_categoria) > '0'){
        foreach($read_categoria as $read_categoria_view){
            $json_categoria["data"][] = $read_categoria_view;
        }
        echo json_encode($json_categoria);
    }
}elseif($acao == 'gerar_excel'){
    $arquivo = 'subcategoria.xls';
    $tabela = '<table border="1" width="800px">';
        $tabela .= '<tr>';
            $tabela .= '<td colspan="4" align="center">Relação de subcategorias</tr>';
        $tabela .= '</tr>';
        $tabela .= '<tr>';
            $tabela .= '<td><b>Código</b></td>';
            $tabela .= '<td><b>SubCategoria</b></td>';
            $tabela .= '<td><b>Categoria</b></td>';
            $tabela .= '<td><b>Status</b></td>';
        $tabela .= '</tr>';
    
    $read_sub_categoria = ReadComposta("SELECT sub_categoria.sub_categoria_id, sub_categoria.sub_categoria_descricao, sub_categoria.sub_categoria_status, sub_categoria.sub_categoria_id_categoria, categoria.categoria_id, categoria.categoria_descricao AS sub_categoria_descricao_categoria FROM sub_categoria INNER JOIN categoria ON sub_categoria.sub_categoria_id_categoria = categoria.categoria_id WHERE sub_categoria.sub_categoria_id != '' {$_SESSION['sub_categoria_load']} ORDER BY sub_categoria.sub_categoria_descricao ASC");
    if(NumQuery($read_sub_categoria) > '0'){
        foreach($read_sub_categoria as $read_sub_categoria_view){
            if($read_sub_categoria_view['sub_categoria_status'] == '0'){
                $status_sub_categoria = 'ATIVO';
            }else{
                $status_sub_categoria = 'INATIVO';
            }
            $tabela .= '<tr>';
                $tabela .= '<td>'.$read_sub_categoria_view['sub_categoria_id'].'</td>';
                $tabela .= '<td>'.$read_sub_categoria_view['sub_categoria_descricao'].'</td>';
                $tabela .= '<td>'.$read_sub_categoria_view['sub_categoria_descricao_categoria'].'</td>';
                $tabela .= '<td>'.$status_sub_categoria.'</td>';
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