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
        $order_by = "ORDER BY assinatura_id DESC";
    }else{
        
        $sort       = addslashes($_GET['sort']);
        $sort_dir   = addslashes($_GET['sort_dir']);
        $order_by   = "ORDER BY ".$sort." ".$sort_dir."";
    }
    
    //PESQUISA VIA AJAX
    if(isset($_GET['search']) && $_GET['search'] == 'true'){
        $get_id         = addslashes($_GET['id']);
        $get_id_contato = addslashes($_GET['id_contato']);
        $get_status     = addslashes($_GET['status']);
        
        if($get_id != ''){
            $sql_id = "AND assinatura.assinatura_id = '".$get_id."'";
        }else{
            $sql_id = "";
        }
        if($get_id_contato != ''){
            $sql_id_contato = "AND assinatura.assinatura_id_contato LIKE '%".$get_id_contato."%'";
        }else{
            $sql_id_contato = "";
        }
        if($get_status != ''){
            $sql_status = "AND assinatura.assinatura_status = '".$get_status."'";
        }else{
            $sql_status = "";
        }
        
        $_SESSION['assinatura_load'] = "".$sql_id." ".$sql_id_contato." ".$sql_status." ";
    }
    
    $read_assinatura_paginator = ReadComposta("SELECT assinatura_id FROM assinatura WHERE assinatura_id != '' {$_SESSION['assinatura_load']}");
    $read_assinatura = ReadComposta("SELECT contato.contato_nome_razao, assinatura.assinatura_id, assinatura.assinatura_data_criacao, assinatura.assinatura_data_final, assinatura.assinatura_status, assinatura.assinatura_lancamento_auto, plano_assinatura.plano_assinatura_descricao FROM assinatura LEFT JOIN contato ON contato.contato_id = assinatura.assinatura_id_contato LEFT JOIN plano_assinatura ON plano_assinatura.plano_assinatura_id = assinatura.assinatura_id_plano WHERE assinatura.assinatura_id != '' {$_SESSION['assinatura_load']} ".$order_by." LIMIT $inicio,$maximo");
    if(NumQuery($read_assinatura) > '0'){
        //PEGA QUANTOS BOTÕES PARA PAGINAÇÃO DE RESULTADOS
        $paginas = ceil(NumQuery($read_assinatura_paginator) / $maximo);
        //TABULATOR EXIGE ESSA INFORMAÇÃO PARA QUE PRECISE DA PAGINAÇÃO DE RESULTADOS 
        $json_assinatura["last_page"] = $paginas;
        foreach($read_assinatura as $read_assinatura_view){
            if($read_assinatura_view['assinatura_status'] == '0'){
                $read_assinatura_view['assinatura_status'] = 'Pendente';
            }elseif($read_assinatura_view['assinatura_status'] == '1'){
                $read_assinatura_view['assinatura_status'] = 'Ativa';
            }elseif($read_assinatura_view['assinatura_status'] == '2'){
                $read_assinatura_view['assinatura_status'] = 'Cancelada';
            }
            if($read_assinatura_view['assinatura_lancamento_auto'] == '0'){
                $read_assinatura_view['assinatura_lancamento_auto'] = 'Não';
            }elseif($read_assinatura_view['assinatura_lancamento_auto'] == '1'){
                $read_assinatura_view['assinatura_lancamento_auto'] = 'Sim';
            }
            $json_assinatura['data'][] = $read_assinatura_view;
        }
    }else{
        $json_assinatura['data'] = null;
    }
    echo json_encode($json_assinatura);
}elseif($acao == 'create'){
    //RECUPERA O FORMULARIO
    $assinatura_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($assinatura_form['acao']);
    
    if(in_array('', $assinatura_form)){
        $json_assinatura = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        Create('assinatura', $assinatura_form);
        $json_assinatura = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal" onclick="limpar_campos();">Apenas Fechar</button><a href="javascript::" onclick="carrega_pagina(\'assinatura\', \'index.php\');" data-dismiss="modal" class="btn btn-primary">Sair</a>'
        );
    }
    echo json_encode($json_assinatura);
}elseif($acao == 'update'){
    //RECUPERA O FORMULARIO
    $assinatura_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($assinatura_form['acao']);
    
    if(in_array('', $assinatura_form)){
        $json_assinatura = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        $uid = addslashes($_POST['id']);
        unset($assinatura_form['id']);
        Update('assinatura', $assinatura_form, "WHERE assinatura_id = '".$uid."'");
        $json_assinatura = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<a href="javascript::" data-dismiss="modal" onclick="carrega_pagina(\'assinatura\', \'index.php\');" class="btn btn-primary">Sair</a>'
        );
    }
    echo json_encode($json_assinatura);
}elseif($acao == 'load_update'){
    $uid = addslashes($_POST['id']);
    
    $read_assinatura = Read('assinatura', "WHERE assinatura_id = '".$uid."'");
    if(NumQuery($read_assinatura) > '0'){
        foreach($read_assinatura as $read_assinatura_view);
        $json_assinatura[] = $read_assinatura_view;
    }else{
        $json_assinatura = null;
    }
    echo json_encode($json_assinatura);
}elseif($acao == 'load_msg_assinatura'){
    $url = addslashes($_POST['url']);
    
    $read_msg_financeiro = Read('msg_financeiro', "WHERE msg_financeiro_status = '0'");
    if(NumQuery($read_msg_financeiro) > '0'){
        echo '<select class="form-control txt_mail_msg" onchange="carrega_msg_assinatura();">';
        echo '<option value=""></option>';
        foreach($read_msg_financeiro as $read_msg_financeiro_view){
            echo '<option value="'.$read_msg_financeiro_view['msg_financeiro_id'].'">'.$read_msg_financeiro_view['msg_financeiro_assunto'].'</option>';
        }
        echo '</select>';
        echo '<input type="hidden" class="msg_financeiro_id" value="'.$url.'"/>';
    }
}elseif($acao == 'load_msgs_assinatura'){
    $url = addslashes($_POST['id_msg_assinatura']);
    
    $read_msg_financeiro = Read('msg_financeiro', "WHERE msg_financeiro_status = '0' AND msg_financeiro_id = '".$url."'");
    if(NumQuery($read_msg_financeiro) > '0'){
        foreach($read_msg_financeiro as $read_msg_financeiro_view);
        echo '<hr />';
        echo '<textarea class="form-control msg_financeiro_texto" cols="" rows="10">'.$read_msg_financeiro_view['msg_financeiro_texto'].'</textarea>';
        echo '<hr />';
    }
}elseif($acao == 'mail_assinatura'){
    $msg_financeiro_texto   = addslashes($_POST['msg_financeiro_texto']);
    $msg_financeiro_id      = addslashes($_POST['msg_financeiro_id']);
    $msg_financeiro_md5     = addslashes($_POST['msg_financeiro_md5']);
    $id_assinatura          = addslashes($_POST['id_assinatura']);
    
    $assunto_mail = GetDados('msg_financeiro', $msg_financeiro_md5, 'msg_financeiro_id', 'msg_financeiro_assunto');
    $MSG_1 = str_replace('#NOMEEMPRESA#', GetEmpresa('empresa_nome_razao'), MSGEMAIL);
    $MSG_2 = str_replace('#IMGEMPRESA#', GetEmpresa('empresa_logo'), $MSG_1);
    $MSG_3 = str_replace('#TITULOMAIL#', $assunto_mail, $MSG_2);
    $MSG_4 = str_replace('#MSGMAIL#', $msg_financeiro_texto, $MSG_3);
    $MSG_5 = str_replace('#MAILEMPRESA#', GetEmpresa('empresa_email'), $MSG_4);
    $MSG_6 = str_replace('#FONEEMPRESA#', GetEmpresa('empresa_telefone'), $MSG_5);
    $MSG_7 = str_replace('#LINKBOLETO#', '<a href="'.URL.'/_paypal/assinar.php?identificacao='. base64_encode($id_assinatura).'" target="_blank">Clique Aqui</a>', $MSG_6);
    
    $count_mails = '0';
    $count_mails_send = '0';
    $count_mails_no_send = '0';
    
    $campanha_mail['campanha_mail_id_msg_financeiro']   = $msg_financeiro_md5;
    $campanha_mail['campanha_mail_data']                = date('Y-m-d');
    $campanha_mail['campanha_mail_data_hora_inicio']    = date('Y-m-d H:i:s');
    $campanha_mail['campanha_mail_send_fatura']         = $msg_financeiro_boleto;
    $campanha_mail['campanha_mail_status']              = '0';
    Create('campanha_mail', $campanha_mail);
    
    $id_campanha_mail = GetReg('campanha_mail', "campanha_mail_id", "");
    
    $read_financeiro = Read('assinatura', "WHERE assinatura_id IN($msg_financeiro_id) AND assinatura_status = '0'");
    if(NumQuery($read_financeiro) > '10'){
        foreach($read_financeiro as $read_financeiro_view){
            $count_mails++;
            $read_contato = Read('contato', "WHERE contato_id = '".$read_financeiro_view['assinatura_id_contato']."'");
            if(NumQuery($read_contato) > '0'){
                foreach($read_contato as $read_contato_view);
                $email_cliente = $read_contato_view['contato_email'];
                $nome_cliente = $read_contato_view['contato_nome_razao'];
            }else{
                $email_cliente = '';
                $nome_cliente = '';
            }
            if(valMail($email_cliente)){
                $campanha_mail_itens['campanha_mail_itens_id_campanha_mail'] = $id_campanha_mail;
                $campanha_mail_itens['campanha_mail_itens_id_contato']       = $read_financeiro_view['assinatura_id_contato'];
                $campanha_mail_itens['campanha_mail_itens_email']            = $email_cliente;
                $campanha_mail_itens['campanha_mail_itens_status'] = '0';
                $campanha_mail_itens['campanha_mail_itens_motivo'] = '';
                Create('campanha_mail_itens', $campanha_mail_itens);
                $count_mails_send++;
            }else{
                $campanha_mail_itens['campanha_mail_itens_id_campanha_mail'] = $id_campanha_mail;
                $campanha_mail_itens['campanha_mail_itens_id_contato']       = $read_financeiro_view['assinatura_id_contato'];
                $campanha_mail_itens['campanha_mail_itens_email']            = $email_cliente;
                $campanha_mail_itens['campanha_mail_itens_status'] = '2';
                $campanha_mail_itens['campanha_mail_itens_motivo'] = 'Não enviado devido a não ter email cadastrado';
                Create('campanha_mail_itens', $campanha_mail_itens);
                $count_mails_no_send++;
            }
        }
        echo 'De '.$count_mails.' email(s) a serem enviado(s), '.$count_mails_send.' foram solicitado(s) para envio com sucesso e '.$count_mails_no_send.' não foram se quer solicitado(s). Isso acontece pois foram enviados mais de 10 emails, dessa forma o sistema vai enviar de forma mais lenta para que não tenha nenhum email perdido.';
    }elseif(NumQuery($read_financeiro) > '0'){
        foreach($read_financeiro as $read_financeiro_view){
            $count_mails++;
            $read_contato = Read('contato', "WHERE contato_id = '".$read_financeiro_view['assinatura_id_contato']."'");
            if(NumQuery($read_contato) > '0'){
                foreach($read_contato as $read_contato_view);
                $email_cliente = $read_contato_view['contato_email'];
                $nome_cliente = $read_contato_view['contato_nome_razao'];
            }else{
                $email_cliente = '';
                $nome_cliente = '';
            }
            if(valMail($email_cliente)){
                $campanha_mail_itens['campanha_mail_itens_id_campanha_mail'] = $id_campanha_mail;
                $campanha_mail_itens['campanha_mail_itens_id_contato']       = $read_financeiro_view['assinatura_id_contato'];
                $campanha_mail_itens['campanha_mail_itens_email']            = $email_cliente;
                $retorno = sendMailCampanha($assunto_mail, $MSG_7, GetEmpresa('empresa_email'), GetEmpresa('empresa_nome_fantasia'), $email_cliente, $nome_cliente);
                if($retorno){
                    $campanha_mail_itens['campanha_mail_itens_status'] = '1';
                    $campanha_mail_itens['campanha_mail_itens_motivo'] = '';
                    $count_mails_send++;
                }else{
                    $campanha_mail_itens['campanha_mail_itens_status'] = '2';
                    $campanha_mail_itens['campanha_mail_itens_motivo'] = $retorno;
                    $count_mails_no_send++;
                }
                Create('campanha_mail_itens', $campanha_mail_itens);
            }else{
                $campanha_mail_itens['campanha_mail_itens_id_campanha_mail'] = $id_campanha_mail;
                $campanha_mail_itens['campanha_mail_itens_id_contato']       = $read_financeiro_view['assinatura_id_contato'];
                $campanha_mail_itens['campanha_mail_itens_email']            = $email_cliente;
                $campanha_mail_itens['campanha_mail_itens_status'] = '2';
                $campanha_mail_itens['campanha_mail_itens_motivo'] = 'Não enviado devido a não ter email cadastrado';
                Create('campanha_mail_itens', $campanha_mail_itens);
            }
        }
        $update_campanha_mail['campanha_mail_data_hora_fim'] = date('Y-m-d H:i:s');
        $update_campanha_mail['campanha_mail_status'] = '1';
        Update('campanha_mail', $update_campanha_mail, "WHERE campanha_mail_id = '".$id_campanha_mail."'");
        echo 'De '.$count_mails.' email(s) a serem enviado(s), '.$count_mails_send.' foram enviado(s) com sucesso e '.$count_mails_no_send.' não foram enviado(s)';
    }
}
?>