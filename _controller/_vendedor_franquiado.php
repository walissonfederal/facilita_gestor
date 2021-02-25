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
        $order_by = "ORDER BY vendedor_id DESC";
    }else{
        $sort       = addslashes($_GET['sort']);
        $sort_dir   = addslashes($_GET['sort_dir']);
        $order_by   = "ORDER BY ".$sort." ".$sort_dir."";
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
    
    $read_vendedor_paginator = ReadComposta("SELECT vendedor_id FROM vendedor WHERE vendedor_id != '' {$_SESSION['vendedor_load']}");
    $read_vendedor = Read('vendedor', "WHERE vendedor_id != '' {$_SESSION['vendedor_load']} ".$order_by." LIMIT $inicio,$maximo");
    if(NumQuery($read_vendedor) > '0'){
        //PEGA QUANTOS BOTÕES PARA PAGINAÇÃO DE RESULTADOS
        $paginas = ceil(NumQuery($read_vendedor_paginator) / $maximo);
        //TABULATOR EXIGE ESSA INFORMAÇÃO PARA QUE PRECISE DA PAGINAÇÃO DE RESULTADOS 
        $json_vendedor["last_page"] = $paginas;
        foreach($read_vendedor as $read_vendedor_view){
            $json_vendedor['data'][] = $read_vendedor_view;
        }
    }else{
        $json_vendedor['data'] = null;
    }
    echo json_encode($json_vendedor);
}elseif($acao == 'create'){
    //RECUPERA O FORMULARIO
    $vendedor_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($vendedor_form['acao']);
    
    if($vendedor_form['vendedor_nome'] == '' || $vendedor_form['vendedor_email'] == ''){
        $json_vendedor = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        $vendedor_form['vendedor_data'] = date('Y-m-d');
        if(Create('vendedor', $vendedor_form)){
            $json_vendedor = array(
                'type' => 'success',
                'title' => 'Parabéns:',
                'msg' => 'Operação realizada com sucesso',
                'buttons' => '<a href="javascript::" onclick="carrega_pagina(\'vendedor_franquiado\', \'index.php\');" data-dismiss="modal" class="btn btn-primary">Sair</a>'
            );
        }else{
            $json_vendedor = array(
                'type' => 'error',
                'title' => 'Erro:',
                'msg' => 'Ops, operação não pode ser finalizada, já deve existir um email cadastrado!',
                'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
            );
        }
    }
    echo json_encode($json_vendedor);
}elseif($acao == 'update'){
    //RECUPERA O FORMULARIO
    $vendedor_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($vendedor_form['acao']);
    
    if($vendedor_form['vendedor_nome'] == '' || $vendedor_form['vendedor_email'] == ''){
        $json_vendedor = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        $uid = addslashes($_POST['id']);
        unset($vendedor_form['id']);
        $senha = $vendedor_form['vendedor_senha'];
        if($senha == ''){
            unset($vendedor_form['vendedor_senha']);
        }
        if(Update('vendedor', $vendedor_form, "WHERE vendedor_id = '".$uid."'")){
            $json_vendedor = array(
                'type' => 'success',
                'title' => 'Parabéns:',
                'msg' => 'Operação realizada com sucesso',
                'buttons' => '<a href="javascript::" data-dismiss="modal" onclick="carrega_pagina(\'vendedor_franquiado\', \'index.php\');" class="btn btn-primary">Sair</a>'
            );
        }else{
            $json_vendedor = array(
                'type' => 'error',
                'title' => 'Erro:',
                'msg' => 'Ops, operação não pode ser finalizada, já deve existir um email cadastrado!',
                'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
            );
        }
    }
    echo json_encode($json_vendedor);
}elseif($acao == 'load_update'){
    $uid = addslashes($_POST['id']);
    
    $read_vendedor = Read('vendedor', "WHERE vendedor_id = '".$uid."'");
    if(NumQuery($read_vendedor) > '0'){
        foreach($read_vendedor as $read_vendedor_view);
        $json_vendedor[] = $read_vendedor_view;
    }else{
        $json_vendedor = null;
    }
    echo json_encode($json_vendedor);
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
}elseif($acao == 'load_vendedor'){
    $term = addslashes($_GET['term']);
    
    $read_vendedor_load = Read('vendedor', "WHERE (vendedor_nome LIKE '%".$term."%') OR (vendedor_email LIKE '%".$term."%') OR (vendedor_cpf LIKE '%".$term."%') ORDER BY vendedor_nome ASC");
    if(NumQuery($read_vendedor_load) > '0'){
        $json_vendedor = '[';
        foreach($read_vendedor_load as $read_vendedor_load_view){
            $json_vendedor .= '{"label":"'.$read_vendedor_load_view['vendedor_nome'].' | '.$read_vendedor_load_view['vendedor_cpf'].'","value":"'.$read_vendedor_load_view['vendedor_id'].'"},';
        }
        $json_vendedor = substr($json_vendedor, 0,-1);
        $json_vendedor .= ']';
    }else{
        $json_vendedor = array(
                array( "label" => '', "value" => '' ),
        );
    }
    echo $json_vendedor;
}elseif($acao == 'load_vendedor_id'){
    $uid = addslashes($_POST['id']);
    
    $read_vendedor_load_id = Read('vendedor', "WHERE vendedor_id = '".$uid."' ORDER BY vendedor_nome ASC");
    if(NumQuery($read_vendedor_load_id) > '0'){
        $json_vendedor = '[';
        foreach($read_vendedor_load_id as $read_vendedor_load_id_view){
            $json_vendedor .= '{"label":"'.$read_vendedor_load_id_view['vendedor_nome'].' | '.$read_vendedor_load_id_view['vendedor_cpf'].'","value":"'.$read_vendedor_load_id_view['vendedor_id'].'"},';
        }
        $json_vendedor = substr($json_vendedor, 0,-1);
        $json_vendedor .= ']';
    }else{
        $json_vendedor = '[';
            $json_vendedor .= '{"label":"","value":""}';
        $json_vendedor .= ']';
    }
    echo $json_vendedor;
}
?>