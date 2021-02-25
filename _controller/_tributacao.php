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
        $order_by = "ORDER BY tributacao_id DESC";
    }else{
        
        $sort       = addslashes($_GET['sort']);
        $sort_dir   = addslashes($_GET['sort_dir']);
        $order_by   = "ORDER BY ".$sort." ".$sort_dir."";
    }
    
    //PESQUISA VIA AJAX
    if(isset($_GET['search']) && $_GET['search'] == 'true'){
        $get_id         = addslashes($_GET['id']);
        $get_descricao  = addslashes($_GET['descricao']);
        $get_cfop       = addslashes($_GET['cfop']);
        
        if($get_id != ''){
            $sql_id = "AND tributacao_id = '".$get_id."'";
        }else{
            $sql_id = "";
        }
        if($get_descricao != ''){
            $sql_descricao = "AND tributacao_descricao LIKE '%".$get_descricao."%'";
        }else{
            $sql_descricao = "";
        }
        if($get_cfop != ''){
            $sql_cfop = "AND tributacao_cfop = '".$get_cfop."'";
        }else{
            $sql_cfop = "";
        }
        
        $_SESSION['tributacao_load'] = "".$sql_id." ".$sql_descricao." ".$sql_cfop." ";
    }
    
    $read_tributacao_paginator = ReadComposta("SELECT tributacao_id FROM tributacao WHERE tributacao_id != '' {$_SESSION['tributacao_load']}");
    $read_tributacao = Read('tributacao', "WHERE tributacao_id != '' {$_SESSION['tributacao_load']} ".$order_by." LIMIT $inicio,$maximo");
    if(NumQuery($read_tributacao) > '0'){
        //PEGA QUANTOS BOTÕES PARA PAGINAÇÃO DE RESULTADOS
        $paginas = ceil(NumQuery($read_tributacao_paginator) / $maximo);
        //TABULATOR EXIGE ESSA INFORMAÇÃO PARA QUE PRECISE DA PAGINAÇÃO DE RESULTADOS 
        $json_tributacao["last_page"] = $paginas;
        foreach($read_tributacao as $read_tributacao_view){
            $json_tributacao['data'][] = $read_tributacao_view;
        }
    }else{
        $json_tributacao['data'] = '';
    }
    echo json_encode($json_tributacao);
}elseif($acao == 'create'){
    //RECUPERA O FORMULARIO
    $tributacao_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($tributacao_form['acao']);
    
    if($tributacao_form['tributacao_descricao'] == ''){
        $json_tributacao = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        Create('tributacao', $tributacao_form);
        $json_tributacao = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal" onclick="limpar_campos();">Apenas Fechar</button><a href="javascript::" onclick="carrega_pagina(\'tributacao\', \'index.php\');" data-dismiss="modal" class="btn btn-primary">Sair</a>'
        );
    }
    echo json_encode($json_tributacao);
}elseif($acao == 'update'){
    //RECUPERA O FORMULARIO
    $tributacao_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($tributacao_form['acao']);
    
    if($tributacao_form['tributacao_descricao'] == ''){
        $json_tributacao = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        $uid = addslashes($_POST['id']);
        unset($tributacao_form['id']);
        Update('tributacao', $tributacao_form, "WHERE tributacao_id = '".$uid."'");
        $json_tributacao = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<a href="javascript::" onclick="carrega_pagina(\'tributacao\', \'index.php\');" data-dismiss="modal" class="btn btn-primary">Sair</a>'
        );
    }
    echo json_encode($json_tributacao);
}elseif($acao == 'load_update'){
    $uid = addslashes($_POST['id']);
    
    $read_tributacao = Read('tributacao', "WHERE tributacao_id = '".$uid."'");
    if(NumQuery($read_tributacao) > '0'){
        foreach($read_tributacao as $read_tributacao_view);
        $json_tributacao[] = $read_tributacao_view;
    }else{
        $json_tributacao = null;
    }
    echo json_encode($json_tributacao);
}elseif($acao == 'load_icms_st'){
    $read_icms_st = Read('icms', "");
    if(NumQuery($read_icms_st) > '0'){
        foreach($read_icms_st as $read_icms_st_view){
            $read_icms_st_view['desc_icms'] = utf8_encode($read_icms_st_view['desc_icms']);
            $json_icms_st["data"][] = $read_icms_st_view;
        }
        echo json_encode($json_icms_st);
    }
}elseif($acao == 'load_icms_modalidade_bc'){
    $read_icms_modalidade_bc = Read('icms_modalidade_bc', "");
    if(NumQuery($read_icms_modalidade_bc) > '0'){
        foreach($read_icms_modalidade_bc as $read_icms_modalidade_bc_view){
            $read_icms_modalidade_bc_view['desc_icms_modalidade_bc'] = utf8_encode($read_icms_modalidade_bc_view['desc_icms_modalidade_bc']);
            $json_icms_modalidade_bc["data"][] = $read_icms_modalidade_bc_view;
        }
        echo json_encode($json_icms_modalidade_bc);
    }
}elseif($acao == 'load_icms_modalidade_bc_st'){
    $read_icms_modalidade_bc_st = Read('icms_modalidade_st', "");
    if(NumQuery($read_icms_modalidade_bc_st) > '0'){
        foreach($read_icms_modalidade_bc_st as $read_icms_modalidade_bc_st_view){
            $read_icms_modalidade_bc_st_view['desc_icms_modalidade_st'] = utf8_encode($read_icms_modalidade_bc_st_view['desc_icms_modalidade_st']);
            $json_icms_modalidade_bc_st["data"][] = $read_icms_modalidade_bc_st_view;
        }
        echo json_encode($json_icms_modalidade_bc_st);
    }
}elseif($acao == 'load_icms_motivo_desoneracao'){
    $read_icms_motivo_desoneracao = Read('icms_desoneracao', "");
    if(NumQuery($read_icms_motivo_desoneracao) > '0'){
        foreach($read_icms_motivo_desoneracao as $read_icms_motivo_desoneracao_view){
            $read_icms_motivo_desoneracao_view['desc_icms_desoneracao'] = utf8_encode($read_icms_motivo_desoneracao_view['desc_icms_desoneracao']);
            $json_icms_motivo_desoneracao["data"][] = $read_icms_motivo_desoneracao_view;
        }
        echo json_encode($json_icms_motivo_desoneracao);
    }
}elseif($acao == 'load_icms_uf'){
    $read_icms_uf = Read('estado', "");
    if(NumQuery($read_icms_uf) > '0'){
        foreach($read_icms_uf as $read_icms_uf_view){
            $read_icms_uf_view['nome_estado'] = utf8_encode($read_icms_uf_view['nome_estado']);
            $json_icms_uf["data"][] = $read_icms_uf_view;
        }
        echo json_encode($json_icms_uf);
    }
}elseif($acao == 'load_ipi_st'){
    $read_ipi_st = Read('ipi', "");
    if(NumQuery($read_ipi_st) > '0'){
        foreach($read_ipi_st as $read_ipi_st_view){
            $read_ipi_st_view['desc_ipi'] = utf8_encode($read_ipi_st_view['desc_ipi']);
            $json_ipi_st["data"][] = $read_ipi_st_view;
        }
        echo json_encode($json_ipi_st);
    }
}elseif($acao == 'load_pis_st'){
    $read_pis_st = Read('pis', "");
    if(NumQuery($read_pis_st) > '0'){
        foreach($read_pis_st as $read_pis_st_view){
            $read_pis_st_view['desc_pis'] = utf8_encode($read_pis_st_view['desc_pis']);
            $json_pis_st["data"][] = $read_pis_st_view;
        }
        echo json_encode($json_pis_st);
    }
}elseif($acao == 'load_cofins_st'){
    $read_cofins_st = Read('cofins', "");
    if(NumQuery($read_cofins_st) > '0'){
        foreach($read_cofins_st as $read_cofins_st_view){
            $read_cofins_st_view['desc_cofins'] = utf8_encode($read_cofins_st_view['desc_cofins']);
            $json_cofins_st["data"][] = $read_cofins_st_view;
        }
        echo json_encode($json_cofins_st);
    }
}
?>