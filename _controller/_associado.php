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
        $order_by = "ORDER BY associado_id DESC";
    }else{
        
        $sort       = addslashes($_GET['sort']);
        $sort_dir   = addslashes($_GET['sort_dir']);
        if($sort == 'associado_status_view'){
            $order_by   = "ORDER BY associado_id ".$sort_dir."";
        }else{
            $order_by   = "ORDER BY ".$sort." ".$sort_dir."";
        }
    }
    
    //PESQUISA VIA AJAX
    if(isset($_GET['search']) && $_GET['search'] == 'true'){
        $get_id             = addslashes($_GET['id']);
        $get_nome_razao     = addslashes($_GET['nome_razao']);
        $get_status         = addslashes($_GET['status']);
        $get_nome_fantasia  = addslashes($_GET['nome_fantasia']);
        $get_cpf_cnpj       = addslashes($_GET['cpf_cnpj']);
        $get_email          = addslashes($_GET['email']);
        
        if($get_id != ''){
            $sql_id = "AND associado_id = '".$get_id."'";
        }else{
            $sql_id = "";
        }
        if($get_nome_razao != ''){
            $sql_nome_razao = "AND associado_nome_razao LIKE '%".$get_nome_razao."%'";
        }else{
            $sql_nome_razao = "";
        }
        if($get_email != ''){
            $sql_email = "AND associado_email LIKE '%".$get_email."%'";
        }else{
            $sql_email = "";
        }
        if($get_status != ''){
            $sql_status = "AND associado_status = '".$get_status."'";
        }else{
            $sql_status = "";
        }
        if($get_nome_fantasia != ''){
            $sql_nome_fantasia = "AND associado_nome_fantasia LIKE '%".$get_nome_fantasia."%'";
        }else{
            $sql_nome_fantasia = "";
        }
        if($get_cpf_cnpj != ''){
            $sql_cpf_cnpj = "AND associado_cpf_cnpj LIKE '%".$get_cpf_cnpj."%'";
        }else{
            $sql_cpf_cnpj = "";
        }
        
        $_SESSION['associado_load'] = "".$sql_id." ".$sql_nome_razao." ".$sql_status." ".$sql_nome_fantasia." ".$sql_cpf_cnpj." ".$sql_email." ";
    }
    
    $read_associado_paginator = ReadComposta("SELECT associado_id FROM associado WHERE associado_id != '' {$_SESSION['associado_load']}");
    $read_associado = Read('associado', "WHERE associado_id != '' {$_SESSION['associado_load']} ".$order_by." LIMIT $inicio,$maximo");
    if(NumQuery($read_associado) > '0'){
        //PEGA QUANTOS BOTÕES PARA PAGINAÇÃO DE RESULTADOS
        $paginas = ceil(NumQuery($read_associado_paginator) / $maximo);
        //TABULATOR EXIGE ESSA INFORMAÇÃO PARA QUE PRECISE DA PAGINAÇÃO DE RESULTADOS 
        $json_associado["last_page"] = $paginas;
        foreach($read_associado as $read_associado_view){
            if($read_associado_view['associado_status'] == '0'){
                $read_associado_view['associado_status_view'] = 'Ativo';
            }else{
                $read_associado_view['associado_status_view'] = 'Inativo';
            }
            $json_associado['data'][] = $read_associado_view;
        }
    }else{
        $json_associado['data'] = null;
    }
    echo json_encode($json_associado);
}elseif($acao == 'create'){
    //RECUPERA O FORMULARIO
    $associado_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($associado_form['acao']);
    
    if($associado_form['associado_nome_razao'] == ''){
        $json_associado = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        if(Create('associado', $associado_form)){
            $json_associado = array(
                'type' => 'success',
                'title' => 'Parabéns:',
                'msg' => 'Operação realizada com sucesso',
                'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal" onclick="limpar_campos();">Apenas Fechar</button><a href="javascript::" onclick="carrega_pagina(\'associado\', \'index.php\');" data-dismiss="modal" class="btn btn-primary">Sair</a>'
            );
        }else{
            $json_associado = array(
                'type' => 'error',
                'title' => 'Erro:',
                'msg' => 'Ops, operação não pode ser finalizada, existe CNPJ / CPF já cadastrado!',
                'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
            );
        }
    }
    echo json_encode($json_associado);
}elseif($acao == 'update'){
    //RECUPERA O FORMULARIO
    $associado_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($associado_form['acao']);
    
    if($associado_form['associado_nome_razao'] == ''){
        $json_associado = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        $uid = addslashes($_POST['id']);
        unset($associado_form['id']);
        if(Update('associado', $associado_form, "WHERE associado_id = '".$uid."'")){
            $json_associado = array(
                'type' => 'success',
                'title' => 'Parabéns:',
                'msg' => 'Operação realizada com sucesso',
                'buttons' => '<a href="javascript::" onclick="carrega_pagina(\'associado\', \'index.php\');" data-dismiss="modal" class="btn btn-primary">Sair</a>'
            );
        }else{
            $json_associado = array(
                'type' => 'error',
                'title' => 'Erro:',
                'msg' => 'Ops, operação não pode ser finalizada, existe CNPJ / CPF já cadastrado!',
                'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
            );
        }
    }
    echo json_encode($json_associado);
}elseif($acao == 'load_update'){
    $uid = addslashes($_POST['id']);
    
    $read_associado = Read('associado', "WHERE associado_id = '".$uid."'");
    if(NumQuery($read_associado) > '0'){
        foreach($read_associado as $read_associado_view);
        $json_associado[] = $read_associado_view;
    }else{
        $json_associado = null;
    }
    echo json_encode($json_associado);
}elseif($acao == 'load_associado'){
    $term = addslashes($_GET['term']);
    
    $read_associado_load = Read('associado', "WHERE (associado_nome_razao LIKE '%".$term."%') OR (associado_nome_fantasia LIKE '%".$term."%') OR (associado_cpf_cnpj LIKE '%".$term."%') ORDER BY associado_nome_razao ASC");
    if(NumQuery($read_associado_load) > '0'){
        $json_associado = '[';
        foreach($read_associado_load as $read_associado_load_view){
            $json_associado .= '{"label":"'.$read_associado_load_view['associado_nome_razao'].' | '.$read_associado_load_view['associado_nome_fantasia'].' | '.$read_associado_load_view['associado_cpf_cnpj'].'","value":"'.$read_associado_load_view['associado_id'].'"},';
        }
        $json_associado = substr($json_associado, 0,-1);
        $json_associado .= ']';
    }else{
        $json_associado = array(
                array( "label" => '', "value" => '' ),
        );
    }
    echo $json_associado;
}elseif($acao == 'load_associado_id'){
    $uid = addslashes($_POST['id']);
    
    $read_associado_load_id = Read('associado', "WHERE associado_id = '".$uid."' ORDER BY associado_nome_razao ASC");
    if(NumQuery($read_associado_load_id) > '0'){
        $json_associado = '[';
        foreach($read_associado_load_id as $read_associado_load_id_view){
            $json_associado .= '{"label":"'.$read_associado_load_id_view['associado_nome_razao'].' | '.$read_associado_load_id_view['associado_nome_fantasia'].' | '.$read_associado_load_id_view['associado_cpf_cnpj'].'","value":"'.$read_associado_load_id_view['associado_id'].'"},';
        }
        $json_associado = substr($json_associado, 0,-1);
        $json_associado .= ']';
    }else{
        $json_associado = '[';
            $json_associado .= '{"label":"","value":""}';
        $json_associado .= ']';
    }
    echo $json_associado;
}elseif($acao == 'load_associado_select'){
    $read_associado = Read('associado', "ORDER BY associado_nome_razao ASC");
    if(NumQuery($read_associado) > '0'){
        foreach($read_associado as $read_associado_view){
            $json_associado["data"][] = $read_associado_view;
        }
        echo json_encode($json_associado);
    }
}
?>