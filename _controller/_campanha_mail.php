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
        $order_by = "ORDER BY campanha_mail_id DESC";
    }else{
        
        $sort       = addslashes($_GET['sort']);
        $sort_dir   = addslashes($_GET['sort_dir']);
        if($sort == 'campanha_mail_status_view'){
            $order_by   = "ORDER BY campanha_mail_id ".$sort_dir."";
        }else{
            $order_by   = "ORDER BY ".$sort." ".$sort_dir."";
        }
    }
    
    $read_campanha_mail_paginator = ReadComposta("SELECT campanha_mail_id FROM campanha_mail WHERE campanha_mail_id != '' {$_SESSION['campanha_mail_load']}");
    $read_campanha_mail = Read('campanha_mail', "WHERE campanha_mail_id != '' {$_SESSION['campanha_mail_load']} ".$order_by." LIMIT $inicio,$maximo");
    if(NumQuery($read_campanha_mail) > '0'){
        //PEGA QUANTOS BOTÕES PARA PAGINAÇÃO DE RESULTADOS
        $paginas = ceil(NumQuery($read_campanha_mail_paginator) / $maximo);
        //TABULATOR EXIGE ESSA INFORMAÇÃO PARA QUE PRECISE DA PAGINAÇÃO DE RESULTADOS 
        $json_campanha_mail["last_page"] = $paginas;
        foreach($read_campanha_mail as $read_campanha_mail_view){
            if($read_campanha_mail_view['campanha_mail_status'] == '0'){
                $read_campanha_mail_view['campanha_mail_status_view'] = 'Aguardando';
                $read_campanha_mail_view['campanha_mail_data_hora_fim_format'] = '';
            }else{
                $read_campanha_mail_view['campanha_mail_status_view'] = 'Finalizada';
                $read_campanha_mail_view['campanha_mail_data_hora_fim_format'] = FormDataBrTudo($read_campanha_mail_view['campanha_mail_data_hora_fim']);
            }
            if($read_campanha_mail_view['campanha_mail_send_fatura'] == '0'){
                $read_campanha_mail_view['campanha_mail_send_fatura_format'] = 'Não';
            }else{
                $read_campanha_mail_view['campanha_mail_send_fatura_format'] = 'Sim';
            }
            $read_campanha_mail_view['campanha_mail_data_format'] = FormDataBr($read_campanha_mail_view['campanha_mail_data']);
            $read_campanha_mail_view['campanha_mail_data_hora_inicio_format'] = FormDataBrTudo($read_campanha_mail_view['campanha_mail_data_hora_inicio']);
            $json_campanha_mail['data'][] = $read_campanha_mail_view;
        }
    }else{
        $json_campanha_mail['data'] = null;
    }
    echo json_encode($json_campanha_mail);
}if($acao == 'load_item'){
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
        $order_by = "ORDER BY campanha_mail_itens_id DESC";
    }else{
        
        $sort       = addslashes($_GET['sort']);
        $sort_dir   = addslashes($_GET['sort_dir']);
        $order_by   = "ORDER BY ".$sort." ".$sort_dir."";
    }
    
    $read_campanha_mail_paginator = ReadComposta("SELECT campanha_mail_itens_id FROM campanha_mail_itens WHERE campanha_mail_itens_id_campanha_mail = '".$_GET['id_campanha']."' {$_SESSION['campanha_mail_load']}");
    $read_campanha_mail = Read('campanha_mail_itens', "WHERE campanha_mail_itens_id_campanha_mail = '".$_GET['id_campanha']."' {$_SESSION['campanha_mail_load']} ".$order_by." LIMIT $inicio,$maximo");
    if(NumQuery($read_campanha_mail) > '0'){
        //PEGA QUANTOS BOTÕES PARA PAGINAÇÃO DE RESULTADOS
        $paginas = ceil(NumQuery($read_campanha_mail_paginator) / $maximo);
        //TABULATOR EXIGE ESSA INFORMAÇÃO PARA QUE PRECISE DA PAGINAÇÃO DE RESULTADOS 
        $json_campanha_mail["last_page"] = $paginas;
        foreach($read_campanha_mail as $read_campanha_mail_view){
            if($read_campanha_mail_view['campanha_mail_itens_status'] == '0'){
                $read_campanha_mail_view['campanha_mail_itens_status'] = 'Aguardando envio';
            }elseif($read_campanha_mail_view['campanha_mail_itens_status'] == '1'){
                $read_campanha_mail_view['campanha_mail_itens_status'] = 'Enviado';
            }elseif($read_campanha_mail_view['campanha_mail_itens_status'] == '2'){
                $read_campanha_mail_view['campanha_mail_itens_status'] = 'Não enviado';
                if($read_campanha_mail_view['campanha_mail_itens_motivo'] == ''){
                    $read_campanha_mail_view['campanha_mail_itens_motivo'] = 'Motivo não identificado';
                }
            }
            $read_campanha_mail_view['campanha_mail_itens_id_contato_nome'] = GetDados('contato', $read_campanha_mail_view['campanha_mail_itens_id_contato'], 'contato_id', 'contato_nome_fantasia');
            
            $json_campanha_mail['data'][] = $read_campanha_mail_view;
        }
    }else{
        $json_campanha_mail['data'] = null;
    }
    echo json_encode($json_campanha_mail);
}
?>