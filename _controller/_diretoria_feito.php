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
    
    $diretoria_id = addslashes($_GET['id_diretoria']);
    
    //ORDENAÇÃO DO TABULATOR
    if(empty($_GET['sort']) && empty($_GET['sort_dir'])){
        $order_by = "ORDER BY feito_diretoria_id DESC";
    }else{
        
        $sort       = addslashes($_GET['sort']);
        $sort_dir   = addslashes($_GET['sort_dir']);
        $order_by   = "ORDER BY ".$sort." ".$sort_dir."";
    }
    
    $read_diretoria_feito_paginator = ReadComposta("SELECT feito_diretoria_id FROM feito_diretoria WHERE feito_diretoria_id_diretoria = '".$diretoria_id."'");
    $read_diretoria_feito = Read('feito_diretoria', "WHERE feito_diretoria_id_diretoria = '".$diretoria_id."' ".$order_by." LIMIT $inicio,$maximo");
    if(NumQuery($read_diretoria_feito) > '0'){
        //PEGA QUANTOS BOTÕES PARA PAGINAÇÃO DE RESULTADOS
        $paginas = ceil(NumQuery($read_diretoria_feito_paginator) / $maximo);
        //TABULATOR EXIGE ESSA INFORMAÇÃO PARA QUE PRECISE DA PAGINAÇÃO DE RESULTADOS 
        $json_diretoria_feito["last_page"] = $paginas;
        foreach($read_diretoria_feito as $read_diretoria_feito_view){
            $read_diretoria_feito_view['id_diretoria'] = $read_diretoria_feito_view['feito_diretoria_id_diretoria'];
            $read_diretoria_feito_view['feito_diretoria_data'] = FormDataBr($read_diretoria_feito_view['feito_diretoria_data']);
            $read_diretoria_feito_view['feito_diretoria_id_diretoria'] = GetDados('diretoria', $read_diretoria_feito_view['feito_diretoria_id_diretoria'], 'diretoria_id', 'diretoria_descricao');
            $json_diretoria_feito['data'][] = $read_diretoria_feito_view;
        }
    }else{
        $json_diretoria_feito['data'] = null;
    }
    echo json_encode($json_diretoria_feito);
}elseif($acao == 'create'){
    //RECUPERA O FORMULARIO
    $diretoria_feito_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($diretoria_feito_form['acao']);
    
    if(in_array('', $diretoria_feito_form)){
        $json_diretoria_feito = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        Create('feito_diretoria', $diretoria_feito_form);
        $json_diretoria_feito = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal" onclick="limpar_campos();">Apenas Fechar</button><a href="javascript::" onclick="carrega_pagina(\'diretoria\', \'index_feito.php?id_diretoria='.$diretoria_feito_form['feito_diretoria_id_diretoria'].'\');" data-dismiss="modal" class="btn btn-primary">Sair</a>'
        );
    }
    echo json_encode($json_diretoria_feito);
}elseif($acao == 'update'){
    //RECUPERA O FORMULARIO
    $diretoria_feito_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($diretoria_feito_form['acao']);
    
    if(in_array('', $diretoria_feito_form)){
        $json_diretoria_feito = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        $uid = addslashes($_POST['id']);
        $uid_diretoria = addslashes($_POST['feito_diretoria_id_diretoria']);
        unset($diretoria_feito_form['id']);
        unset($diretoria_feito_form['feito_diretoria_id_diretoria']);
        Update('feito_diretoria', $diretoria_feito_form, "WHERE feito_diretoria_id = '".$uid."'");
        $json_diretoria_feito = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<a href="javascript::" data-dismiss="modal" onclick="carrega_pagina(\'diretoria\', \'index_feito.php?id_diretoria='.$uid_diretoria.'\');" class="btn btn-primary">Sair</a>'
        );
    }
    echo json_encode($json_diretoria_feito);
}elseif($acao == 'load_update'){
    $uid = addslashes($_POST['id']);
    
    $read_diretoria_feito = Read('feito_diretoria', "WHERE feito_diretoria_id = '".$uid."'");
    if(NumQuery($read_diretoria_feito) > '0'){
        foreach($read_diretoria_feito as $read_diretoria_feito_view);
        $json_diretoria_feito[] = $read_diretoria_feito_view;
    }else{
        $json_diretoria_feito = null;
    }
    echo json_encode($json_diretoria_feito);
}
?>