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
        $order_by = "ORDER BY regiao_id DESC";
    }else{
        
        $sort       = addslashes($_GET['sort']);
        $sort_dir   = addslashes($_GET['sort_dir']);
        if($sort == 'regiao_status_view'){
            $order_by   = "ORDER BY regiao_id ".$sort_dir."";
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
            $sql_id = "AND regiao_id = '".$get_id."'";
        }else{
            $sql_id = "";
        }
        if($get_descricao != ''){
            $sql_descricao = "AND regiao_descricao LIKE '%".$get_descricao."%'";
        }else{
            $sql_descricao = "";
        }
        if($get_status != ''){
            $sql_status = "AND regiao_status = '".$get_status."'";
        }else{
            $sql_status = "";
        }
        
        $_SESSION['regiao_load'] = "".$sql_id." ".$sql_descricao." ".$sql_status." ";
    }
    
    $read_regiao_paginator = ReadComposta("SELECT regiao_id FROM regiao");
    $read_regiao = Read('regiao', "WHERE regiao_id != '' {$_SESSION['regiao_load']} ".$order_by." LIMIT $inicio,$maximo");
    if(NumQuery($read_regiao) > '0'){
        //PEGA QUANTOS BOTÕES PARA PAGINAÇÃO DE RESULTADOS
        $paginas = ceil(NumQuery($read_regiao_paginator) / $maximo);
        //TABULATOR EXIGE ESSA INFORMAÇÃO PARA QUE PRECISE DA PAGINAÇÃO DE RESULTADOS 
        $json_regiao["last_page"] = $paginas;
        foreach($read_regiao as $read_regiao_view){
            if($read_regiao_view['regiao_status'] == '0'){
                $read_regiao_view['regiao_status_view'] = 'Ativo';
            }else{
                $read_regiao_view['regiao_status_view'] = 'Inativo';
            }
            $json_regiao['data'][] = $read_regiao_view;
        }
    }else{
        $json_regiao['data'] = null;
    }
    echo json_encode($json_regiao);
}elseif($acao == 'create'){
    //RECUPERA O FORMULARIO
    $regiao_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($regiao_form['acao']);
    
    if(in_array('', $regiao_form)){
        $json_regiao = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        Create('regiao', $regiao_form);
        $json_regiao = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal" onclick="limpar_campos();">Apenas Fechar</button><a href="javascript::" data-dismiss="modal" onclick="carrega_pagina(\'regiao\', \'index.php\');" class="btn btn-primary">Sair</a>'
        );
    }
    echo json_encode($json_regiao);
}elseif($acao == 'update'){
    //RECUPERA O FORMULARIO
    $regiao_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($regiao_form['acao']);
    
    if(in_array('', $regiao_form)){
        $json_regiao = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        $uid = addslashes($_POST['id']);
        unset($regiao_form['id']);
        Update('regiao', $regiao_form, "WHERE regiao_id = '".$uid."'");
        $json_regiao = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<a href="javascript::" data-dismiss="modal" onclick="carrega_pagina(\'regiao\', \'index.php\');" class="btn btn-primary">Sair</a>'
        );
    }
    echo json_encode($json_regiao);
}elseif($acao == 'load_update'){
    $uid = addslashes($_POST['id']);
    
    $read_regiao = Read('regiao', "WHERE regiao_id = '".$uid."'");
    if(NumQuery($read_regiao) > '0'){
        foreach($read_regiao as $read_regiao_view);
        $json_regiao[] = $read_regiao_view;
    }else{
        $json_regiao = null;
    }
    echo json_encode($json_regiao);
}elseif($acao == 'load_regiao'){
    $term = addslashes($_GET['term']);
    
    $read_regiao_load = Read('regiao', "WHERE regiao_descricao LIKE '%".$term."%' ORDER BY regiao_descricao ASC");
    if(NumQuery($read_regiao_load) > '0'){
        $json_regiao = '[';
        foreach($read_regiao_load as $read_regiao_load_view){
            $json_regiao .= '{"label":"'.$read_regiao_load_view['regiao_descricao'].'","value":"'.$read_regiao_load_view['regiao_id'].'"},';
        }
        $json_regiao = substr($json_regiao, 0,-1);
        $json_regiao .= ']';
    }else{
        $json_regiao = array(
                array( "label" => '', "value" => '' ),
        );
    }
    echo $json_regiao;
}elseif($acao == 'load_regiao_id'){
    $uid = addslashes($_POST['id']);
    
    $read_regiao_load_id = Read('regiao', "WHERE regiao_id = '".$uid."' ORDER BY regiao_descricao ASC");
    if(NumQuery($read_regiao_load_id) > '0'){
        $json_regiao = '[';
        foreach($read_regiao_load_id as $read_regiao_load_id_view){
            $json_regiao .= '{"label":"'.$read_regiao_load_id_view['regiao_descricao'].'","value":"'.$read_regiao_load_id_view['regiao_id'].'"},';
        }
        $json_regiao = substr($json_regiao, 0,-1);
        $json_regiao .= ']';
    }else{
        $json_regiao = '[';
            $json_regiao .= '{"label":"","value":""}';
        $json_regiao .= ']';
    }
    echo $json_regiao;
}elseif($acao == 'load_regiao_empresa'){
    $read_regiao = Read('regiao', "ORDER BY regiao_descricao ASC");
    if(NumQuery($read_regiao) > '0'){
        foreach($read_regiao as $read_regiao_view){
            $json_regiao["data"][] = $read_regiao_view;
        }
        echo json_encode($json_regiao);
    }
}elseif($acao == 'gerar_excel'){
    $arquivo = 'regiao.xls';
    $tabela = '<table border="1" width="800px">';
        $tabela .= '<tr>';
            $tabela .= '<td colspan="3" align="center">Relação de regiões</tr>';
        $tabela .= '</tr>';
        $tabela .= '<tr>';
            $tabela .= '<td><b>Código</b></td>';
            $tabela .= '<td><b>Descrição</b></td>';
            $tabela .= '<td><b>Status</b></td>';
        $tabela .= '</tr>';
    
    $read_regiao = Read('regiao', "WHERE regiao_id != '' {$_SESSION['regiao_load']} ORDER BY regiao_descricao ASC");
    if(NumQuery($read_regiao) > '0'){
        foreach($read_regiao as $read_regiao_view){
            if($read_regiao_view['regiao_status'] == '0'){
                $status_regiao = 'ATIVO';
            }else{
                $status_regiao = 'INATIVO';
            }
            $tabela .= '<tr>';
                $tabela .= '<td>'.$read_regiao_view['regiao_id'].'</td>';
                $tabela .= '<td>'.$read_regiao_view['regiao_descricao'].'</td>';
                $tabela .= '<td>'.$status_regiao.'</td>';
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