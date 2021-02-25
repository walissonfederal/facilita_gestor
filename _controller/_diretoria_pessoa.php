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
        $order_by = "ORDER BY pessoa_diretoria_id DESC";
    }else{
        
        $sort       = addslashes($_GET['sort']);
        $sort_dir   = addslashes($_GET['sort_dir']);
        $order_by   = "ORDER BY ".$sort." ".$sort_dir."";
    }
    
    $read_diretoria_pessoa_paginator = ReadComposta("SELECT pessoa_diretoria_id FROM pessoa_diretoria WHERE pessoa_diretoria_id_diretoria = '".$diretoria_id."'");
    $read_diretoria_pessoa = Read('pessoa_diretoria', "WHERE pessoa_diretoria_id_diretoria = '".$diretoria_id."' ".$order_by." LIMIT $inicio,$maximo");
    if(NumQuery($read_diretoria_pessoa) > '0'){
        //PEGA QUANTOS BOTÕES PARA PAGINAÇÃO DE RESULTADOS
        $paginas = ceil(NumQuery($read_diretoria_pessoa_paginator) / $maximo);
        //TABULATOR EXIGE ESSA INFORMAÇÃO PARA QUE PRECISE DA PAGINAÇÃO DE RESULTADOS 
        $json_diretoria_pessoa["last_page"] = $paginas;
        foreach($read_diretoria_pessoa as $read_diretoria_pessoa_view){
            $read_diretoria_pessoa_view['id_diretoria'] = $read_diretoria_pessoa_view['pessoa_diretoria_id_diretoria'];
            $read_diretoria_pessoa_view['pessoa_diretoria_id_diretoria'] = GetDados('diretoria', $read_diretoria_pessoa_view['pessoa_diretoria_id_diretoria'], 'diretoria_id', 'diretoria_descricao');
            $read_diretoria_pessoa_view['pessoa_diretoria_id_pessoa'] = GetDados('pessoa', $read_diretoria_pessoa_view['pessoa_diretoria_id_pessoa'], 'pessoa_id', 'pessoa_nome');
            $read_diretoria_pessoa_view['pessoa_diretoria_id_cargo'] = GetDados('cargo', $read_diretoria_pessoa_view['pessoa_diretoria_id_cargo'], 'cargo_id', 'cargo_descricao');
            $json_diretoria_pessoa['data'][] = $read_diretoria_pessoa_view;
        }
    }else{
        $json_diretoria_pessoa['data'] = null;
    }
    echo json_encode($json_diretoria_pessoa);
}elseif($acao == 'create'){
    //RECUPERA O FORMULARIO
    $diretoria_pessoa_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($diretoria_pessoa_form['acao']);
    
    if(in_array('', $diretoria_pessoa_form)){
        $json_diretoria_pessoa = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        Create('pessoa_diretoria', $diretoria_pessoa_form);
        $json_diretoria_pessoa = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal" onclick="limpar_campos();">Apenas Fechar</button><a href="javascript::" onclick="carrega_pagina(\'diretoria\', \'index_pessoa.php?id_diretoria='.$diretoria_pessoa_form['pessoa_diretoria_id_diretoria'].'\');" data-dismiss="modal" class="btn btn-primary">Sair</a>'
        );
    }
    echo json_encode($json_diretoria_pessoa);
}elseif($acao == 'update'){
    //RECUPERA O FORMULARIO
    $diretoria_pessoa_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($diretoria_pessoa_form['acao']);
    
    if(in_array('', $diretoria_pessoa_form)){
        $json_diretoria_pessoa = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        $uid = addslashes($_POST['id']);
        $uid_diretoria = addslashes($_POST['pessoa_diretoria_id_diretoria']);
        unset($diretoria_pessoa_form['id']);
        unset($diretoria_pessoa_form['pessoa_diretoria_id_diretoria']);
        Update('pessoa_diretoria', $diretoria_pessoa_form, "WHERE pessoa_diretoria_id = '".$uid."'");
        $json_diretoria_pessoa = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<a href="javascript::" data-dismiss="modal" onclick="carrega_pagina(\'diretoria\', \'index_pessoa.php?id_diretoria='.$uid_diretoria.'\');" class="btn btn-primary">Sair</a>'
        );
    }
    echo json_encode($json_diretoria_pessoa);
}elseif($acao == 'load_update'){
    $uid = addslashes($_POST['id']);
    
    $read_diretoria_pessoa = Read('pessoa_diretoria', "WHERE pessoa_diretoria_id = '".$uid."'");
    if(NumQuery($read_diretoria_pessoa) > '0'){
        foreach($read_diretoria_pessoa as $read_diretoria_pessoa_view);
        $json_diretoria_pessoa[] = $read_diretoria_pessoa_view;
    }else{
        $json_diretoria_pessoa = null;
    }
    echo json_encode($json_diretoria_pessoa);
}
?>