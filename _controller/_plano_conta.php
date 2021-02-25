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
        $order_by = "ORDER BY plano_conta_id DESC";
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
            $sql_id = "AND plano_conta_id = '".$get_id."'";
        }else{
            $sql_id = "";
        }
        if($get_descricao != ''){
            $sql_descricao = "AND plano_conta_descricao LIKE '%".$get_descricao."%'";
        }else{
            $sql_descricao = "";
        }
        
        $_SESSION['plano_conta_load'] = "".$sql_id." ".$sql_descricao." ";
    }
    
    $read_plano_conta_paginator = ReadComposta("SELECT plano_conta_id FROM plano_conta WHERE plano_conta_id != '' {$_SESSION['plano_conta_load']}");
    $read_plano_conta = Read('plano_conta', "WHERE plano_conta_id != '' {$_SESSION['plano_conta_load']} ".$order_by." LIMIT $inicio,$maximo");
    if(NumQuery($read_plano_conta) > '0'){
        //PEGA QUANTOS BOTÕES PARA PAGINAÇÃO DE RESULTADOS
        $paginas = ceil(NumQuery($read_plano_conta_paginator) / $maximo);
        //TABULATOR EXIGE ESSA INFORMAÇÃO PARA QUE PRECISE DA PAGINAÇÃO DE RESULTADOS 
        $json_plano_conta["last_page"] = $paginas;
        foreach($read_plano_conta as $read_plano_conta_view){
            $json_plano_conta['data'][] = $read_plano_conta_view;
        }
    }else{
        $json_plano_conta['data'] = null;
    }
    echo json_encode($json_plano_conta);
}elseif($acao == 'create'){
    //RECUPERA O FORMULARIO
    $plano_conta_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($plano_conta_form['acao']);
    
    if(in_array('', $plano_conta_form)){
        $json_plano_conta = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        Create('plano_conta', $plano_conta_form);
        $json_plano_conta = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal" onclick="limpar_campos();">Apenas Fechar</button><a href="javascript::" onclick="carrega_pagina(\'plano-conta\', \'index.php\');" data-dismiss="modal" class="btn btn-primary">Sair</a>'
        );
    }
    echo json_encode($json_plano_conta);
}elseif($acao == 'update'){
    //RECUPERA O FORMULARIO
    $plano_conta_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($plano_conta_form['acao']);
    
    if(in_array('', $plano_conta_form)){
        $json_plano_conta = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        $uid = addslashes($_POST['id']);
        unset($plano_conta_form['id']);
        Update('plano_conta', $plano_conta_form, "WHERE plano_conta_id = '".$uid."'");
        $json_plano_conta = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<a href="javascript::" onclick="carrega_pagina(\'plano-conta\', \'index.php\');" data-dismiss="modal" class="btn btn-primary">Sair</a>'
        );
    }
    echo json_encode($json_plano_conta);
}elseif($acao == 'load_update'){
    $uid = addslashes($_POST['id']);
    
    $read_plano_conta = Read('plano_conta', "WHERE plano_conta_id = '".$uid."'");
    if(NumQuery($read_plano_conta) > '0'){
        foreach($read_plano_conta as $read_plano_conta_view);
        $json_plano_conta[] = $read_plano_conta_view;
    }else{
        $json_plano_conta = null;
    }
    echo json_encode($json_plano_conta);
}elseif($acao == 'load_pai_plano_conta'){
    $read_pai_plano_conta = Read('plano_conta', "ORDER BY plano_conta_classificacao ASC");
    if(NumQuery($read_pai_plano_conta) > '0'){
        foreach($read_pai_plano_conta as $read_pai_plano_conta_view){
            $json_plano_conta["data"][] = $read_pai_plano_conta_view;
        }
        echo json_encode($json_plano_conta);
    }
}elseif($acao == 'load_plano_conta'){
    $read_plano_conta = Read('plano_conta', "ORDER BY plano_conta_classificacao ASC");
    if(NumQuery($read_plano_conta) > '0'){
        foreach($read_plano_conta as $read_plano_conta_view){
            $json_plano_conta["data"][] = $read_plano_conta_view;
        }
        echo json_encode($json_plano_conta);
    }
}elseif($acao == 'gerar_excel'){
    $arquivo = 'plano_conta.xls';
    $tabela = '<table border="1" width="800px">';
        $tabela .= '<tr>';
            $tabela .= '<td colspan="3" align="center">Relação de planos de contas</tr>';
        $tabela .= '</tr>';
        $tabela .= '<tr>';
            $tabela .= '<td><b>Código</b></td>';
            $tabela .= '<td><b>Classificação</b></td>';
            $tabela .= '<td><b>Descrição</b></td>';
        $tabela .= '</tr>';
    
    $read_plano_conta = Read('plano_conta', "WHERE plano_conta_id != '' {$_SESSION['plano_conta_load']} ORDER BY plano_conta_classificacao ASC");
    if(NumQuery($read_plano_conta) > '0'){
        foreach($read_plano_conta as $read_plano_conta_view){
            $tabela .= '<tr>';
                $tabela .= '<td>'.$read_plano_conta_view['plano_conta_id'].'</td>';
		$tabela .= '<td>-'.$read_plano_conta_view['plano_conta_classificacao'].'-</td>';
                $tabela .= '<td>'.$read_plano_conta_view['plano_conta_descricao'].'</td>';
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