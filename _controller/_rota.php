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
        $order_by = "ORDER BY rota_id DESC";
    }else{
        
        $sort       = addslashes($_GET['sort']);
        $sort_dir   = addslashes($_GET['sort_dir']);
        if($sort == 'rota_status_view'){
            $order_by   = "ORDER BY rota_id ".$sort_dir."";
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
            $sql_id = "AND rota_id = '".$get_id."'";
        }else{
            $sql_id = "";
        }
        if($get_descricao != ''){
            $sql_descricao = "AND rota_descricao LIKE '%".$get_descricao."%'";
        }else{
            $sql_descricao = "";
        }
        if($get_status != ''){
            $sql_status = "AND rota_status = '".$get_status."'";
        }else{
            $sql_status = "";
        }
        
        $_SESSION['rota_load'] = "".$sql_id." ".$sql_descricao." ".$sql_status." ";
    }
    
    $read_rota_paginator = ReadComposta("SELECT rota_id FROM rota WHERE rota_id != '' {$_SESSION['rota_load']}");
    $read_rota = Read('rota', "WHERE rota_id != '' {$_SESSION['rota_load']} ".$order_by." LIMIT $inicio,$maximo");
    if(NumQuery($read_rota) > '0'){
        //PEGA QUANTOS BOTÕES PARA PAGINAÇÃO DE RESULTADOS
        $paginas = ceil(NumQuery($read_rota_paginator) / $maximo);
        //TABULATOR EXIGE ESSA INFORMAÇÃO PARA QUE PRECISE DA PAGINAÇÃO DE RESULTADOS 
        $json_rota["last_page"] = $paginas;
        foreach($read_rota as $read_rota_view){
            if($read_rota_view['rota_status'] == '0'){
                $read_rota_view['rota_status_view'] = 'Ativo';
            }else{
                $read_rota_view['rota_status_view'] = 'Inativo';
            }
            $json_rota['data'][] = $read_rota_view;
        }
    }else{
        $json_rota['data'] = null;
    }
    echo json_encode($json_rota);
}elseif($acao == 'create'){
    //RECUPERA O FORMULARIO
    $rota_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($rota_form['acao']);
    
    if(in_array('', $rota_form)){
        $json_rota = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        Create('rota', $rota_form);
        $json_rota = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal" onclick="limpar_campos();">Apenas Fechar</button><a href="javascript::" data-dismiss="modal" onclick="carrega_pagina(\'rota\', \'index.php\');" class="btn btn-primary">Sair</a>'
        );
    }
    echo json_encode($json_rota);
}elseif($acao == 'update'){
    //RECUPERA O FORMULARIO
    $rota_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($rota_form['acao']);
    
    if(in_array('', $rota_form)){
        $json_rota = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        $uid = addslashes($_POST['id']);
        unset($rota_form['id']);
        Update('rota', $rota_form, "WHERE rota_id = '".$uid."'");
        $json_rota = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<a href="javascript::" data-dismiss="modal" onclick="carrega_pagina(\'rota\', \'index.php\');" class="btn btn-primary">Sair</a>'
        );
    }
    echo json_encode($json_rota);
}elseif($acao == 'load_update'){
    $uid = addslashes($_POST['id']);
    
    $read_rota = Read('rota', "WHERE rota_id = '".$uid."'");
    if(NumQuery($read_rota) > '0'){
        foreach($read_rota as $read_rota_view);
        $json_rota[] = $read_rota_view;
    }else{
        $json_rota = null;
    }
    echo json_encode($json_rota);
}elseif($acao == 'load_rota'){
    $term = addslashes($_GET['term']);
    
    $read_rota_load = Read('rota', "WHERE rota_descricao LIKE '%".$term."%' ORDER BY rota_descricao ASC");
    if(NumQuery($read_rota_load) > '0'){
        $json_rota = '[';
        foreach($read_rota_load as $read_rota_load_view){
            $json_rota .= '{"label":"'.$read_rota_load_view['rota_descricao'].'","value":"'.$read_rota_load_view['rota_id'].'"},';
        }
        $json_rota = substr($json_rota, 0,-1);
        $json_rota .= ']';
    }else{
        $json_rota = array(
                array( "label" => '', "value" => '' ),
        );
    }
    echo $json_rota;
}elseif($acao == 'load_rota_id'){
    $uid = addslashes($_POST['id']);
    
    $read_rota_load_id = Read('rota', "WHERE rota_id = '".$uid."' ORDER BY rota_descricao ASC");
    if(NumQuery($read_rota_load_id) > '0'){
        $json_rota = '[';
        foreach($read_rota_load_id as $read_rota_load_id_view){
            $json_rota .= '{"label":"'.$read_rota_load_id_view['rota_descricao'].'","value":"'.$read_rota_load_id_view['rota_id'].'"},';
        }
        $json_rota = substr($json_rota, 0,-1);
        $json_rota .= ']';
    }else{
        $json_rota = '[';
            $json_rota .= '{"label":"","value":""}';
        $json_rota .= ']';
    }
    echo $json_rota;
}elseif($acao == 'load_rota_empresa'){
    $read_rota = Read('rota', "ORDER BY rota_descricao ASC");
    if(NumQuery($read_rota) > '0'){
        foreach($read_rota as $read_rota_view){
            $json_rota["data"][] = $read_rota_view;
        }
        echo json_encode($json_rota);
    }
}elseif($acao == 'gerar_excel'){
    $arquivo = 'rota.xls';
    $tabela = '<table border="1" width="800px">';
        $tabela .= '<tr>';
            $tabela .= '<td colspan="3" align="center">Relação de regiões</tr>';
        $tabela .= '</tr>';
        $tabela .= '<tr>';
            $tabela .= '<td><b>Código</b></td>';
            $tabela .= '<td><b>Descrição</b></td>';
            $tabela .= '<td><b>Status</b></td>';
        $tabela .= '</tr>';
    
    $read_rota = Read('rota', "WHERE rota_id != '' {$_SESSION['rota_load']} ORDER BY rota_descricao ASC");
    if(NumQuery($read_rota) > '0'){
        foreach($read_rota as $read_rota_view){
            if($read_rota_view['rota_status'] == '0'){
                $status_rota = 'ATIVO';
            }else{
                $status_rota = 'INATIVO';
            }
            $tabela .= '<tr>';
                $tabela .= '<td>'.$read_rota_view['rota_id'].'</td>';
                $tabela .= '<td>'.$read_rota_view['rota_descricao'].'</td>';
                $tabela .= '<td>'.$status_rota.'</td>';
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