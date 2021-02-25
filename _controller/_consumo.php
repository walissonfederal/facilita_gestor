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
        $order_by = "ORDER BY pedido.pedido_id DESC";
    }else{
        
        $sort       = addslashes($_GET['sort']);
        $sort_dir   = addslashes($_GET['sort_dir']);
        $order_by   = "ORDER BY ".$sort." ".$sort_dir."";
    }
    
    //PESQUISA VIA AJAX
    if(isset($_GET['search']) && $_GET['search'] == 'true'){
        $get_id         = addslashes($_GET['id']);
        $get_referencia = addslashes($_GET['referencia']);
        $get_id_contato = addslashes($_GET['id_contato']);
        
        if($get_id != ''){
            $sql_id = "AND pedido.pedido_id = '".$get_id."'";
        }else{
            $sql_id = "";
        }
        if($get_referencia != ''){
            $sql_referencia = "AND consumo.consumo_referencia = '".$get_referencia."'";
        }else{
            $sql_referencia = "";
        }
        if($get_id_contato != ''){
            $sql_id_contato = "AND pedido.pedido_id_cliente = '".$get_id_contato."'";
        }else{
            $sql_id_contato = "";
        }
        
        $_SESSION['consumo_load'] = "".$sql_id." ".$sql_referencia." ".$sql_id_contato." ";
    }
    
    $read_consumo_paginator = ReadComposta("SELECT pedido.pedido_id FROM pedido INNER JOIN contato ON contato.contato_id = pedido.pedido_id_cliente LEFT JOIN consumo ON consumo.consumo_id_pedido = pedido.pedido_id WHERE pedido.pedido_id != '' AND pedido.pedido_status != '2' AND pedido.pedido_tipo = '2' {$_SESSION['consumo_load']}");
    //$read_consumo = Read('pedido', "WHERE pedido_id != '' AND pedido_tipo = '2' AND pedido_status != '2' {$_SESSION['consumo_load']} ".$order_by." LIMIT $inicio,$maximo");
    $read_consumo = ReadComposta("SELECT pedido.pedido_id, pedido.pedido_id_cliente, contato.contato_nome_razao, consumo.consumo_referencia, consumo.consumo_valor_excedente FROM pedido INNER JOIN contato ON contato.contato_id = pedido.pedido_id_cliente LEFT JOIN consumo ON consumo.consumo_id_pedido = pedido.pedido_id WHERE pedido.pedido_id != '' AND pedido.pedido_status != '2' AND pedido.pedido_tipo = '2' {$_SESSION['consumo_load']} ORDER BY consumo.consumo_id DESC LIMIT $inicio,$maximo");
    if(NumQuery($read_consumo) > '0'){
        //PEGA QUANTOS BOTÕES PARA PAGINAÇÃO DE RESULTADOS
        $paginas = ceil(NumQuery($read_consumo_paginator) / $maximo);
        //TABULATOR EXIGE ESSA INFORMAÇÃO PARA QUE PRECISE DA PAGINAÇÃO DE RESULTADOS 
        $json_consumo["last_page"] = $paginas;
        foreach($read_consumo as $read_consumo_view){
            $json_consumo['data'][] = $read_consumo_view;
        }
    }else{
        $json_consumo['data'] = null;
    }
    echo json_encode($json_consumo);
}elseif($acao == 'excedente'){
    $consumo['consumo_referencia'] = addslashes($_POST['referencia']);
    $consumo['consumo_id_contato'] = addslashes($_POST['id_contato']);
    $consumo['consumo_id_pedido']  = addslashes($_POST['id_pedido']);
    $consumo['consumo_qtd_sms']    = addslashes($_POST['quantidade']);
    
    if(in_array('', $consumo)){
        echo 'É preciso preencher todos os campos.';
    }else{
        $read_pedido = ReadComposta("SELECT plano.plano_quantidade FROM pedido INNER JOIN plano ON plano.plano_id = pedido.pedido_id_plano_sms WHERE pedido.pedido_id = '".$consumo['consumo_id_pedido']."'");
        if(NumQuery($read_pedido) > '0'){
            foreach($read_pedido as $read_pedido_view);
            if($read_pedido_view['plano_quantidade'] >= $consumo['consumo_qtd_sms']){
                echo 'Sem excedente';
            }else{
                $quantidade_excedente = $consumo['consumo_qtd_sms'] - $read_pedido_view['plano_quantidade'];
                $valor_excendente = $quantidade_excedente * GetEmpresa('empresa_valor_sms');
                echo 'Valor excedente: R$ '.FormatMoney($valor_excendente);
            }
        }
    }
}elseif($acao == 'informar'){
    $consumo['consumo_referencia'] = addslashes($_POST['referencia']);
    $consumo['consumo_id_contato'] = addslashes($_POST['id_contato']);
    $consumo['consumo_id_pedido']  = addslashes($_POST['id_pedido']);
    $consumo['consumo_qtd_sms']    = addslashes($_POST['quantidade']);
    
    if(in_array('', $consumo)){
        echo 'É preciso preencher todos os campos.';
    }else{
        $read_pedido = ReadComposta("SELECT plano.plano_quantidade FROM pedido INNER JOIN plano ON plano.plano_id = pedido.pedido_id_plano_sms WHERE pedido.pedido_id = '".$consumo['consumo_id_pedido']."'");
        if(NumQuery($read_pedido) > '0'){
            foreach($read_pedido as $read_pedido_view);
            if($read_pedido_view['plano_quantidade'] >= $consumo['consumo_qtd_sms']){
                $consumo['consumo_valor_excedente'] = '0';
            }else{
                $quantidade_excedente = $consumo['consumo_qtd_sms'] - $read_pedido_view['plano_quantidade'];
                $valor_excendente = $quantidade_excedente * GetEmpresa('empresa_valor_sms');
                $consumo['consumo_valor_excedente'] = $valor_excendente;
            }
        }
        $read_consumo = ReadComposta("SELECT consumo_id FROM consumo WHERE consumo_referencia = '".$consumo['consumo_referencia']."' AND consumo_id_pedido = '".$consumo['consumo_id_pedido']."' AND consumo_id_contato = '".$consumo['consumo_id_contato']."'");
        if(NumQuery($read_consumo) > '0'){
            Update('consumo', $consumo, "WHERE consumo_referencia = '".$consumo['consumo_referencia']."' AND consumo_id_pedido = '".$consumo['consumo_id_pedido']."' AND consumo_id_contato = '".$consumo['consumo_id_contato']."'");
        }else{
            Create('consumo', $consumo);
        }
        echo 'Operação realizada com sucesso.';
    }
}
?>