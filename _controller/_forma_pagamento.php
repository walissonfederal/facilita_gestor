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
        $order_by = "ORDER BY forma_pagamento_id DESC";
    }else{
        
        $sort       = addslashes($_GET['sort']);
        $sort_dir   = addslashes($_GET['sort_dir']);
        if($sort == 'forma_pagamento_status_view'){
            $order_by   = "ORDER BY forma_pagamento_id ".$sort_dir."";
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
            $sql_id = "AND forma_pagamento_id = '".$get_id."'";
        }else{
            $sql_id = "";
        }
        if($get_descricao != ''){
            $sql_descricao = "AND forma_pagamento_descricao LIKE '%".$get_descricao."%'";
        }else{
            $sql_descricao = "";
        }
        if($get_status != ''){
            $sql_status = "AND forma_pagamento_status = '".$get_status."'";
        }else{
            $sql_status = "";
        }
        
        $_SESSION['forma_pagamento_load'] = "".$sql_id." ".$sql_descricao." ".$sql_status." ";
    }
    
    $read_forma_pagamento_paginator = ReadComposta("SELECT forma_pagamento_id FROM forma_pagamento WHERE forma_pagamento_id != '' {$_SESSION['forma_pagamento_load']}");
    $read_forma_pagamento = Read('forma_pagamento', "WHERE forma_pagamento_id != '' {$_SESSION['forma_pagamento_load']} ".$order_by." LIMIT $inicio,$maximo");
    if(NumQuery($read_forma_pagamento) > '0'){
        //PEGA QUANTOS BOTÕES PARA PAGINAÇÃO DE RESULTADOS
        $paginas = ceil(NumQuery($read_forma_pagamento_paginator) / $maximo);
        //TABULATOR EXIGE ESSA INFORMAÇÃO PARA QUE PRECISE DA PAGINAÇÃO DE RESULTADOS 
        $json_forma_pagamento["last_page"] = $paginas;
        foreach($read_forma_pagamento as $read_forma_pagamento_view){
            if($read_forma_pagamento_view['forma_pagamento_status'] == '0'){
                $read_forma_pagamento_view['forma_pagamento_status_view'] = 'Ativo';
            }else{
                $read_forma_pagamento_view['forma_pagamento_status_view'] = 'Inativo';
            }
            $json_forma_pagamento['data'][] = $read_forma_pagamento_view;
        }
    }else{
        $json_forma_pagamento['data'] = null;
    }
    echo json_encode($json_forma_pagamento);
}elseif($acao == 'create'){
    //RECUPERA O FORMULARIO
    $forma_pagamento_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($forma_pagamento_form['acao']);
    
    if(in_array('', $forma_pagamento_form)){
        $json_forma_pagamento = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        Create('forma_pagamento', $forma_pagamento_form);
        $json_forma_pagamento = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal" onclick="limpar_campos();">Apenas Fechar</button><a href="javascript::" onclick="carrega_pagina(\'forma-pagamento\', \'index.php\');" data-dismiss="modal" class="btn btn-primary">Sair</a>'
        );
    }
    echo json_encode($json_forma_pagamento);
}elseif($acao == 'update'){
    //RECUPERA O FORMULARIO
    $forma_pagamento_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($forma_pagamento_form['acao']);
    
    if(in_array('', $forma_pagamento_form)){
        $json_forma_pagamento = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        $uid = addslashes($_POST['id']);
        unset($forma_pagamento_form['id']);
        Update('forma_pagamento', $forma_pagamento_form, "WHERE forma_pagamento_id = '".$uid."'");
        $json_forma_pagamento = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<a href="javascript::" onclick="carrega_pagina(\'forma-pagamento\', \'index.php\');" data-dismiss="modal" class="btn btn-primary">Sair</a>'
        );
    }
    echo json_encode($json_forma_pagamento);
}elseif($acao == 'load_update'){
    $uid = addslashes($_POST['id']);
    
    $read_forma_pagamento = Read('forma_pagamento', "WHERE forma_pagamento_id = '".$uid."'");
    if(NumQuery($read_forma_pagamento) > '0'){
        foreach($read_forma_pagamento as $read_forma_pagamento_view);
        $json_forma_pagamento[] = $read_forma_pagamento_view;
    }else{
        $json_forma_pagamento = null;
    }
    echo json_encode($json_forma_pagamento);
}elseif($acao == 'load_forma_pagamento'){
    $read_forma_pagamento = Read('forma_pagamento', "ORDER BY forma_pagamento_descricao ASC");
    if(NumQuery($read_forma_pagamento) > '0'){
        foreach($read_forma_pagamento as $read_forma_pagamento_view){
            $json_forma_pagamento["data"][] = $read_forma_pagamento_view;
        }
        echo json_encode($json_forma_pagamento);
    }
}elseif($acao == 'gerar_excel'){
    $arquivo = 'forma_pagamento.xls';
    $tabela = '<table border="1" width="800px">';
        $tabela .= '<tr>';
            $tabela .= '<td colspan="3" align="center">Relação de regiões</tr>';
        $tabela .= '</tr>';
        $tabela .= '<tr>';
            $tabela .= '<td><b>Código</b></td>';
            $tabela .= '<td><b>Descrição</b></td>';
            $tabela .= '<td><b>Status</b></td>';
        $tabela .= '</tr>';
    
    $read_forma_pagamento = Read('forma_pagamento', "WHERE forma_pagamento_id != '' {$_SESSION['forma_pagamento_load']} ORDER BY forma_pagamento_descricao ASC");
    if(NumQuery($read_forma_pagamento) > '0'){
        foreach($read_forma_pagamento as $read_forma_pagamento_view){
            if($read_forma_pagamento_view['forma_pagamento_status'] == '0'){
                $status_forma_pagamento = 'ATIVO';
            }else{
                $status_forma_pagamento = 'INATIVO';
            }
            $tabela .= '<tr>';
                $tabela .= '<td>'.$read_forma_pagamento_view['forma_pagamento_id'].'</td>';
                $tabela .= '<td>'.$read_forma_pagamento_view['forma_pagamento_descricao'].'</td>';
                $tabela .= '<td>'.$status_forma_pagamento.'</td>';
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