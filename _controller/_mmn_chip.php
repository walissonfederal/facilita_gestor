<?php
session_start();
ob_start();
require_once '../_class_mmn/Ferramenta.php';

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
        $order_by = "ORDER BY chip_id DESC";
    }else{
        
        $sort       = addslashes($_GET['sort']);
        $sort_dir   = addslashes($_GET['sort_dir']);
        $order_by   = "ORDER BY ".$sort." ".$sort_dir."";
    }
    
    //PESQUISA VIA AJAX
    if(isset($_GET['search']) && $_GET['search'] == 'true'){
        $get_id         = addslashes($_GET['id']);
        $get_numero     = addslashes($_GET['numero']);
        $get_iccid      = addslashes($_GET['iccid']);
        $get_status     = addslashes($_GET['status']);
        
        if($get_id != ''){
            $sql_id = "AND chip_id = '".$get_id."'";
        }else{
            $sql_id = "";
        }
        if($get_numero != ''){
            $sql_numero = "AND chip_num LIKE '%".$get_numero."%'";
        }else{
            $sql_numero = "";
        }
        if($get_iccid != ''){
            $sql_iccid = "AND chip_iccid LIKE '%".$get_iccid."%'";
        }else{
            $sql_iccid = "";
        }
        if($get_status != ''){
            $sql_status = "AND chip_status = '".$get_status."'";
        }else{
            $sql_status = "";
        }
        
        $_SESSION['mmn_chip_load'] = "".$sql_id." ".$sql_numero." ".$sql_status." ".$sql_iccid." ";
    }
    
    $read_chip_paginator = ReadComposta("SELECT chip_id FROM chip WHERE chip_id != '' {$_SESSION['mmn_chip_load']}");
    $read_chip = Read('chip', "WHERE chip_id != '' {$_SESSION['mmn_chip_load']} ".$order_by." LIMIT $inicio,$maximo");
    if(NumQuery($read_chip) > '0'){
        //PEGA QUANTOS BOTÕES PARA PAGINAÇÃO DE RESULTADOS
        $paginas = ceil(NumQuery($read_chip_paginator) / $maximo);
        //TABULATOR EXIGE ESSA INFORMAÇÃO PARA QUE PRECISE DA PAGINAÇÃO DE RESULTADOS 
        $json_chip["last_page"] = $paginas;
        foreach($read_chip as $read_chip_view){
            if($read_chip_view['chip_status'] == '0'){
                $read_chip_view['chip_status'] = 'Disponível';
            }else{
                $read_chip_view['chip_status'] = 'Indisponível';
            }
            $json_chip['data'][] = $read_chip_view;
        }
    }else{
        $json_chip['data'] = null;
    }
    echo json_encode($json_chip);
}elseif($acao == 'create'){
    //RECUPERA O FORMULARIO
    $chip_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($chip_form['acao']);
    
    if(in_array('', $chip_form)){
        $json_chip = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        $chip_form['chip_plano'] = '4G';
        $chip_form['chip_status'] = '0';
        if(Create('chip', $chip_form)){
            $json_chip = array(
                'type' => 'success',
                'title' => 'Parabéns:',
                'msg' => 'Operação realizada com sucesso',
                'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal" onclick="limpar_campos();">Apenas Fechar</button><a href="javascript::" onclick="carrega_pagina(\'mmn_chip\', \'index.php\');" data-dismiss="modal" class="btn btn-primary">Sair</a>'
            );
        }else{
            $json_chip = array(
                'type' => 'error',
                'title' => 'Erro:',
                'msg' => 'Ops, operação não pode ser finalizada, provavelmente já existe um iccid cadastrado!',
                'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
            );
        }
    }
    echo json_encode($json_chip);
}elseif($acao == 'update'){
    //RECUPERA O FORMULARIO
    $chip_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($chip_form['acao']);
    
    if(in_array('', $chip_form)){
        $json_chip = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        $uid = addslashes($_POST['id']);
        unset($chip_form['id']);
        if(Update('chip', $chip_form, "WHERE chip_id = '".$uid."'")){
            $json_chip = array(
                'type' => 'success',
                'title' => 'Parabéns:',
                'msg' => 'Operação realizada com sucesso',
                'buttons' => '<a href="javascript::" data-dismiss="modal" onclick="carrega_pagina(\'mmn_chip\', \'index.php\');" class="btn btn-primary">Sair</a>'
            );
        }else{
            $json_chip = array(
                'type' => 'error',
                'title' => 'Erro:',
                'msg' => 'Ops, operação não pode ser finalizada, provavelmente já existe um iccid cadastrado!',
                'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
            );
        }
    }
    echo json_encode($json_chip);
}elseif($acao == 'load_update'){
    $uid = addslashes($_POST['id']);
    
    $read_chip = Read('chip', "WHERE chip_id = '".$uid."'");
    if(NumQuery($read_chip) > '0'){
        foreach($read_chip as $read_chip_view);
        $json_chip[] = $read_chip_view;
    }else{
        $json_chip = null;
    }
    echo json_encode($json_chip);
}elseif($acao == 'info_chip'){
    $get_id_chip = addslashes(trim(strip_tags($_GET['id'])));
    
    if($get_id_chip == ''){
        echo '<p>Não pode ser nulo</p>';
    }else{
        $read_chip = ReadComposta("SELECT * FROM itens_pedido_chip WHERE itens_pedido_chip.itens_pedido_chip_id_chip = '".$get_id_chip."'");
        if(NumQuery($read_chip) > '0'){
            foreach($read_chip as $read_chip_view);
            $read_pedido = Read('pedido', "WHERE pedido_id = '".$read_chip_view['itens_pedido_chip_id_pedido']."'");
            if(NumQuery($read_pedido) > '0'){
                foreach($read_pedido as $read_pedido_view);
                $read_user = Read('user', "WHERE user_id = '".$read_pedido_view['pedido_id_user']."'");
                if(NumQuery($read_user) > '0'){
                    foreach($read_user as $read_user_view);
                    echo '<p><strong>ID PEDIDO:</strong>'.$read_pedido_view['pedido_id'].'</p>';
                    echo '<p><strong>Nome Cliente:</strong>'.$read_user_view['user_nome'].'</p>';
                    echo '<p><strong>Email Cliente:</strong>'.$read_user_view['user_email'].'</p>';
                }
            }
        }else{
            echo '<p>Nenhuma informação foi encontrada</p>';
        }
    }
}
?>