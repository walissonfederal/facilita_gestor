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
        $order_by = "ORDER BY caixa_id DESC";
    }else{
        
        $sort       = addslashes($_GET['sort']);
        $sort_dir   = addslashes($_GET['sort_dir']);
        if($sort == 'caixa_status_view'){
            $order_by   = "ORDER BY caixa_id ".$sort_dir."";
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
            $sql_id = "AND caixa_id = '".$get_id."'";
        }else{
            $sql_id = "";
        }
        if($get_descricao != ''){
            $sql_descricao = "AND caixa_descricao LIKE '%".$get_descricao."%'";
        }else{
            $sql_descricao = "";
        }
        if($get_status != ''){
            $sql_status = "AND caixa_status = '".$get_status."'";
        }else{
            $sql_status = "";
        }
        
        $_SESSION['caixa_load'] = "".$sql_id." ".$sql_descricao." ".$sql_status." ";
    }
    
    $read_caixa_paginator = ReadComposta("SELECT caixa_id FROM caixa WHERE caixa_id != '' {$_SESSION['caixa_load']}");
    $read_caixa = Read('caixa', "WHERE caixa_id != '' {$_SESSION['caixa_load']} ".$order_by." LIMIT $inicio,$maximo");
    if(NumQuery($read_caixa) > '0'){
        //PEGA QUANTOS BOTÕES PARA PAGINAÇÃO DE RESULTADOS
        $paginas = ceil(NumQuery($read_caixa_paginator) / $maximo);
        //TABULATOR EXIGE ESSA INFORMAÇÃO PARA QUE PRECISE DA PAGINAÇÃO DE RESULTADOS 
        $json_caixa["last_page"] = $paginas;
        foreach($read_caixa as $read_caixa_view){
            if($read_caixa_view['caixa_status'] == '0'){
                $read_caixa_view['caixa_status_view'] = 'Ativo';
            }else{
                $read_caixa_view['caixa_status_view'] = 'Inativo';
            }
            $json_caixa['data'][] = $read_caixa_view;
        }
    }else{
        $json_caixa['data'] = null;
    }
    echo json_encode($json_caixa);
}elseif($acao == 'create'){
    //RECUPERA O FORMULARIO
    $caixa_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($caixa_form['acao']);
    
    if(in_array('', $caixa_form)){
        $json_caixa = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        Create('caixa', $caixa_form);
        $json_caixa = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal" onclick="limpar_campos();">Apenas Fechar</button><a href="javascript::" onclick="carrega_pagina(\'caixa\', \'index.php\');" data-dismiss="modal" class="btn btn-primary">Sair</a>'
        );
    }
    echo json_encode($json_caixa);
}elseif($acao == 'update'){
    //RECUPERA O FORMULARIO
    $caixa_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($caixa_form['acao']);
    
    if(in_array('', $caixa_form)){
        $json_caixa = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        $uid = addslashes($_POST['id']);
        unset($caixa_form['id']);
        Update('caixa', $caixa_form, "WHERE caixa_id = '".$uid."'");
        $json_caixa = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<a href="javascript::" data-dismiss="modal" onclick="carrega_pagina(\'caixa\', \'index.php\');" class="btn btn-primary">Sair</a>'
        );
    }
    echo json_encode($json_caixa);
}elseif($acao == 'load_update'){
    $uid = addslashes($_POST['id']);
    
    $read_caixa = Read('caixa', "WHERE caixa_id = '".$uid."'");
    if(NumQuery($read_caixa) > '0'){
        foreach($read_caixa as $read_caixa_view);
        $json_caixa[] = $read_caixa_view;
    }else{
        $json_caixa = null;
    }
    echo json_encode($json_caixa);
}elseif($acao == 'load_caixa'){
    $read_caixa = Read('caixa', "ORDER BY caixa_descricao ASC");
    if(NumQuery($read_caixa) > '0'){
        foreach($read_caixa as $read_caixa_view){
            $json_caixa["data"][] = $read_caixa_view;
        }
        echo json_encode($json_caixa);
    }
}elseif($acao == 'gerar_excel'){
    $arquivo = 'caixa.xls';
    $tabela = '<table border="1" width="800px">';
        $tabela .= '<tr>';
            $tabela .= '<td colspan="3" align="center">Relação de caixas</tr>';
        $tabela .= '</tr>';
        $tabela .= '<tr>';
            $tabela .= '<td><b>Código</b></td>';
            $tabela .= '<td><b>Descrição</b></td>';
            $tabela .= '<td><b>Status</b></td>';
        $tabela .= '</tr>';
    
    $read_caixa = Read('caixa', "WHERE caixa_id != '' {$_SESSION['caixa_load']} ORDER BY caixa_descricao ASC");
    if(NumQuery($read_caixa) > '0'){
        foreach($read_caixa as $read_caixa_view){
            if($read_caixa_view['caixa_status'] == '0'){
                $status_caixa = 'ATIVO';
            }else{
                $status_caixa = 'INATIVO';
            }
            $tabela .= '<tr>';
                $tabela .= '<td>'.$read_caixa_view['caixa_id'].'</td>';
                $tabela .= '<td>'.$read_caixa_view['caixa_descricao'].'</td>';
                $tabela .= '<td>'.$status_caixa.'</td>';
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