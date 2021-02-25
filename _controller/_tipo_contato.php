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
        $order_by = "ORDER BY tipo_contato_id DESC";
    }else{
        
        $sort       = addslashes($_GET['sort']);
        $sort_dir   = addslashes($_GET['sort_dir']);
        if($sort == 'tipo_contato_status_view'){
            $order_by   = "ORDER BY tipo_contato_id ".$sort_dir."";
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
            $sql_id = "AND tipo_contato_id = '".$get_id."'";
        }else{
            $sql_id = "";
        }
        if($get_descricao != ''){
            $sql_descricao = "AND tipo_contato_descricao LIKE '%".$get_descricao."%'";
        }else{
            $sql_descricao = "";
        }
        if($get_status != ''){
            $sql_status = "AND tipo_contato_status = '".$get_status."'";
        }else{
            $sql_status = "";
        }
        
        $_SESSION['tipo_contato_load'] = "".$sql_id." ".$sql_descricao." ".$sql_status." ";
    }
    
    $read_tipo_contato_paginator = ReadComposta("SELECT tipo_contato_id FROM tipo_contato WHERE tipo_contato_id != '' {$_SESSION['tipo_contato_load']}");
    $read_tipo_contato = Read('tipo_contato', "WHERE tipo_contato_id != '' {$_SESSION['tipo_contato_load']} ".$order_by." LIMIT $inicio,$maximo");
    if(NumQuery($read_tipo_contato) > '0'){
        //PEGA QUANTOS BOTÕES PARA PAGINAÇÃO DE RESULTADOS
        $paginas = ceil(NumQuery($read_tipo_contato_paginator) / $maximo);
        //TABULATOR EXIGE ESSA INFORMAÇÃO PARA QUE PRECISE DA PAGINAÇÃO DE RESULTADOS 
        $json_tipo_contato["last_page"] = $paginas;
        foreach($read_tipo_contato as $read_tipo_contato_view){
            if($read_tipo_contato_view['tipo_contato_status'] == '0'){
                $read_tipo_contato_view['tipo_contato_status_view'] = 'Ativo';
            }else{
                $read_tipo_contato_view['tipo_contato_status_view'] = 'Inativo';
            }
            $json_tipo_contato['data'][] = $read_tipo_contato_view;
        }
    }else{
        $json_tipo_contato['data'] = null;
    }
    echo json_encode($json_tipo_contato);
}elseif($acao == 'create'){
    //RECUPERA O FORMULARIO
    $tipo_contato_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($tipo_contato_form['acao']);
    
    if(in_array('', $tipo_contato_form)){
        $json_tipo_contato = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        Create('tipo_contato', $tipo_contato_form);
        $json_tipo_contato = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal" onclick="limpar_campos();">Apenas Fechar</button><a href="javascript::" onclick="carrega_pagina(\'tipo-contato\', \'index.php\');" data-dismiss="modal" class="btn btn-primary">Sair</a>'
        );
    }
    echo json_encode($json_tipo_contato);
}elseif($acao == 'update'){
    //RECUPERA O FORMULARIO
    $tipo_contato_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($tipo_contato_form['acao']);
    
    if(in_array('', $tipo_contato_form)){
        $json_tipo_contato = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        $uid = addslashes($_POST['id']);
        unset($tipo_contato_form['id']);
        Update('tipo_contato', $tipo_contato_form, "WHERE tipo_contato_id = '".$uid."'");
        $json_tipo_contato = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<a href="javascript::" onclick="carrega_pagina(\'tipo-contato\', \'index.php\');" data-dismiss="modal" class="btn btn-primary">Sair</a>'
        );
    }
    echo json_encode($json_tipo_contato);
}elseif($acao == 'load_update'){
    $uid = addslashes($_POST['id']);
    
    $read_tipo_contato = Read('tipo_contato', "WHERE tipo_contato_id = '".$uid."'");
    if(NumQuery($read_tipo_contato) > '0'){
        foreach($read_tipo_contato as $read_tipo_contato_view);
        $json_tipo_contato[] = $read_tipo_contato_view;
    }else{
        $json_tipo_contato = null;
    }
    echo json_encode($json_tipo_contato);
}elseif($acao == 'load_tipo_contato'){
    $term = addslashes($_GET['term']);
    
    $read_tipo_contato_load = Read('tipo_contato', "WHERE tipo_contato_descricao LIKE '%".$term."%' ORDER BY tipo_contato_descricao ASC");
    if(NumQuery($read_tipo_contato_load) > '0'){
        $json_tipo_contato = '[';
        foreach($read_tipo_contato_load as $read_tipo_contato_load_view){
            $json_tipo_contato .= '{"label":"'.$read_tipo_contato_load_view['tipo_contato_descricao'].'","value":"'.$read_tipo_contato_load_view['tipo_contato_id'].'"},';
        }
        $json_tipo_contato = substr($json_tipo_contato, 0,-1);
        $json_tipo_contato .= ']';
    }else{
        $json_tipo_contato = array(
                array( "label" => '', "value" => '' ),
        );
    }
    echo $json_tipo_contato;
}elseif($acao == 'load_tipo_contato_id'){
    $uid = addslashes($_POST['id']);
    
    $read_tipo_contato_load_id = Read('tipo_contato', "WHERE tipo_contato_id = '".$uid."' ORDER BY tipo_contato_descricao ASC");
    if(NumQuery($read_tipo_contato_load_id) > '0'){
        $json_tipo_contato = '[';
        foreach($read_tipo_contato_load_id as $read_tipo_contato_load_id_view){
            $json_tipo_contato .= '{"label":"'.$read_tipo_contato_load_id_view['tipo_contato_descricao'].'","value":"'.$read_tipo_contato_load_id_view['tipo_contato_id'].'"},';
        }
        $json_tipo_contato = substr($json_tipo_contato, 0,-1);
        $json_tipo_contato .= ']';
    }else{
        $json_tipo_contato = '[';
            $json_tipo_contato .= '{"label":"","value":""}';
        $json_tipo_contato .= ']';
    }
    echo $json_tipo_contato;
}elseif($acao == 'load_tipo_contato_empresa'){
    $read_tipo_contato = Read('tipo_contato', "ORDER BY tipo_contato_descricao ASC");
    if(NumQuery($read_tipo_contato) > '0'){
        foreach($read_tipo_contato as $read_tipo_contato_view){
            $json_tipo_contato["data"][] = $read_tipo_contato_view;
        }
        echo json_encode($json_tipo_contato);
    }
}elseif($acao == 'gerar_excel'){
    $arquivo = 'tipo_contato.xls';
    $tabela = '<table border="1" width="800px">';
        $tabela .= '<tr>';
            $tabela .= '<td colspan="3" align="center">Relação de tipo contato</tr>';
        $tabela .= '</tr>';
        $tabela .= '<tr>';
            $tabela .= '<td><b>Código</b></td>';
            $tabela .= '<td><b>Descrição</b></td>';
            $tabela .= '<td><b>Status</b></td>';
        $tabela .= '</tr>';
    
    $read_tipo_contato = Read('tipo_contato', "WHERE tipo_contato_id != '' {$_SESSION['tipo_contato_load']} ORDER BY tipo_contato_descricao ASC");
    if(NumQuery($read_tipo_contato) > '0'){
        foreach($read_tipo_contato as $read_tipo_contato_view){
            if($read_tipo_contato_view['tipo_contato_status'] == '0'){
                $status_tipo_contato = 'ATIVO';
            }else{
                $status_tipo_contato = 'INATIVO';
            }
            $tabela .= '<tr>';
                $tabela .= '<td>'.$read_tipo_contato_view['tipo_contato_id'].'</td>';
                $tabela .= '<td>'.$read_tipo_contato_view['tipo_contato_descricao'].'</td>';
                $tabela .= '<td>'.$status_tipo_contato.'</td>';
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