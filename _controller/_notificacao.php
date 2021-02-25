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
        $order_by = "ORDER BY notificacao_id DESC";
    }else{
        $sort       = addslashes($_GET['sort']);
        $sort_dir   = addslashes($_GET['sort_dir']);
        $order_by   = "ORDER BY ".$sort." ".$sort_dir."";
    }
    
    $read_notificacao_paginator = ReadComposta("SELECT notificacao_id FROM notificacao WHERE notificacao_id_user = '".$_SESSION[VSESSION]['user_id']."'");
    $read_notificacao = Read('notificacao', "WHERE notificacao_id_user = '".$_SESSION[VSESSION]['user_id']."' ".$order_by." LIMIT $inicio,$maximo");
    if(NumQuery($read_notificacao) > '0'){
        //PEGA QUANTOS BOTÕES PARA PAGINAÇÃO DE RESULTADOS
        $paginas = ceil(NumQuery($read_notificacao_paginator) / $maximo);
        //TABULATOR EXIGE ESSA INFORMAÇÃO PARA QUE PRECISE DA PAGINAÇÃO DE RESULTADOS 
        $json_notificacao["last_page"] = $paginas;
        foreach($read_notificacao as $read_notificacao_view){
            if($read_notificacao_view['notificacao_status'] == '0'){
                $read_notificacao_view['notificacao_status'] = 'Não visto';
            }else{
                $read_notificacao_view['notificacao_status'] = 'Visto';
            }
            $json_notificacao['data'][] = $read_notificacao_view;
        }
    }else{
        $json_notificacao['data'] = null;
    }
    echo json_encode($json_notificacao);
}elseif($acao == 'load_notificacao'){
    $uid = addslashes($_POST['id']); 
    
    $read_notificacao = Read('notificacao', "WHERE notificacao_id = '".$uid."'");
    if(NumQuery($read_notificacao) > '0'){
        foreach($read_notificacao as $read_notificacao_view);
        echo '<p><strong>Titulo: </strong>'.$read_notificacao_view['notificacao_titulo'].'</p>';
        echo '<hr />';
        echo '<p><strong>Descrição: </strong>'.$read_notificacao_view['notificacao_descricao'].'</p>';
        echo '<a href="#" onclick="carrega_pagina(\'ticket\', \'update.php?id='.$read_notificacao_view['notificacao_id_ticket'].'\');">Abrir Ticket</a>';
        $up_notificacao['notificacao_status'] = '1';
        $up_notificacao['notificacao_data_hora_ver'] = date('Y-m-d H:i:s');
        Update('notificacao', $up_notificacao, "WHERE notificacao_id = '".$uid."'");
    }
}
?>