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
        $order_by = "ORDER BY cargo_id DESC";
    }else{
        
        $sort       = addslashes($_GET['sort']);
        $sort_dir   = addslashes($_GET['sort_dir']);
        if($sort == 'cargo_status_view'){
            $order_by   = "ORDER BY cargo_id ".$sort_dir."";
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
            $sql_id = "AND cargo_id = '".$get_id."'";
        }else{
            $sql_id = "";
        }
        if($get_descricao != ''){
            $sql_descricao = "AND cargo_descricao LIKE '%".$get_descricao."%'";
        }else{
            $sql_descricao = "";
        }
        if($get_status != ''){
            $sql_status = "AND cargo_status = '".$get_status."'";
        }else{
            $sql_status = "";
        }
        
        $_SESSION['cargo_load'] = "".$sql_id." ".$sql_descricao." ".$sql_status." ";
    }
    
    $read_cargo_paginator = ReadComposta("SELECT cargo_id FROM cargo WHERE cargo_id != '' {$_SESSION['cargo_load']}");
    $read_cargo = Read('cargo', "WHERE cargo_id != '' {$_SESSION['cargo_load']} ".$order_by." LIMIT $inicio,$maximo");
    if(NumQuery($read_cargo) > '0'){
        //PEGA QUANTOS BOTÕES PARA PAGINAÇÃO DE RESULTADOS
        $paginas = ceil(NumQuery($read_cargo_paginator) / $maximo);
        //TABULATOR EXIGE ESSA INFORMAÇÃO PARA QUE PRECISE DA PAGINAÇÃO DE RESULTADOS 
        $json_cargo["last_page"] = $paginas;
        foreach($read_cargo as $read_cargo_view){
            if($read_cargo_view['cargo_status'] == '0'){
                $read_cargo_view['cargo_status_view'] = 'Ativo';
            }else{
                $read_cargo_view['cargo_status_view'] = 'Inativo';
            }
            $json_cargo['data'][] = $read_cargo_view;
        }
    }else{
        $json_cargo['data'] = null;
    }
    echo json_encode($json_cargo);
}elseif($acao == 'create'){
    //RECUPERA O FORMULARIO
    $cargo_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($cargo_form['acao']);
    
    if(in_array('', $cargo_form)){
        $json_cargo = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        Create('cargo', $cargo_form);
        $json_cargo = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal" onclick="limpar_campos();">Apenas Fechar</button><a href="javascript::" onclick="carrega_pagina(\'cargo\', \'index.php\');" data-dismiss="modal" class="btn btn-primary">Sair</a>'
        );
    }
    echo json_encode($json_cargo);
}elseif($acao == 'update'){
    //RECUPERA O FORMULARIO
    $cargo_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($cargo_form['acao']);
    
    if(in_array('', $cargo_form)){
        $json_cargo = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        $uid = addslashes($_POST['id']);
        unset($cargo_form['id']);
        Update('cargo', $cargo_form, "WHERE cargo_id = '".$uid."'");
        $json_cargo = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<a href="javascript::" data-dismiss="modal" onclick="carrega_pagina(\'cargo\', \'index.php\');" class="btn btn-primary">Sair</a>'
        );
    }
    echo json_encode($json_cargo);
}elseif($acao == 'load_update'){
    $uid = addslashes($_POST['id']);
    
    $read_cargo = Read('cargo', "WHERE cargo_id = '".$uid."'");
    if(NumQuery($read_cargo) > '0'){
        foreach($read_cargo as $read_cargo_view);
        $json_cargo[] = $read_cargo_view;
    }else{
        $json_cargo = null;
    }
    echo json_encode($json_cargo);
}elseif($acao == 'load_cargo'){
    $read_cargo = Read('cargo', "ORDER BY cargo_descricao ASC");
    if(NumQuery($read_cargo) > '0'){
        foreach($read_cargo as $read_cargo_view){
            $json_cargo["data"][] = $read_cargo_view;
        }
        echo json_encode($json_cargo);
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
            $tabela .= '<td><b>Status</b></td>';
        $tabela .= '</tr>';
    
    $read_cargo = Read('cargo', "WHERE cargo_id != '' {$_SESSION['cargo_load']} ORDER BY cargo_descricao ASC");
    if(NumQuery($read_cargo) > '0'){
        foreach($read_cargo as $read_cargo_view){
            if($read_cargo_view['cargo_status'] == '0'){
                $status_cargo = 'ATIVO';
            }else{
                $status_cargo = 'INATIVO';
            }
            $tabela .= '<tr>';
                $tabela .= '<td>'.$read_cargo_view['cargo_id'].'</td>';
                $tabela .= '<td>'.$read_cargo_view['cargo_descricao'].'</td>';
                $tabela .= '<td>'.$status_cargo.'</td>';
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