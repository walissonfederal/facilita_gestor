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
        $order_by = "ORDER BY ticket_id DESC";
    }else{
        
        $sort       = addslashes($_GET['sort']);
        $sort_dir   = addslashes($_GET['sort_dir']);
        $order_by   = "ORDER BY ".$sort." ".$sort_dir."";
    }
    
    //PESQUISA VIA AJAX
    if(isset($_GET['search']) && $_GET['search'] == 'true'){
        $get_id         = addslashes($_GET['id']);
        $get_assunto    = addslashes($_GET['assunto']);
        $get_status     = addslashes($_GET['status']);
        $get_id_contato = addslashes($_GET['id_contato']);
        $get_id_departamento    = addslashes($_GET['id_departamento']);
        $get_id_user_final      = addslashes($_GET['id_user_final']);
        $get_id_user_inicial      = addslashes($_GET['id_user_inicial']);
        $get_data_inicial       = addslashes($_GET['data_inicial']);
        $get_data_final         = addslashes($_GET['data_final']);
        $get_tipo_pesquisa      = addslashes($_GET['tipo_pesquisa']);
        
        
        $_SESSION['ticket_departamento'] = $get_id_departamento;
        $_SESSION['ticket_id'] = $get_id;
        $_SESSION['ticket_assunto'] = $get_assunto;
        $_SESSION['ticket_status'] = $get_status;
        $_SESSION['ticket_tipo_pesquisa'] = $get_tipo_pesquisa;
        $_SESSION['ticket_data_inicial'] = $get_data_inicial;
        $_SESSION['ticket_data_final'] = $get_data_final;
        
        if($get_data_inicial != '' && $get_data_final != ''){
            $sql_periodo = "AND $get_tipo_pesquisa BETWEEN '".$get_data_inicial."' AND '".$get_data_final."'";
        }else{
            $sql_periodo = "";
        }
        if($get_id_user_final != ''){
            $sql_id_user_final = "AND ticket_id_user_final = '".$get_id_user_final."'";
        }else{
            $sql_id_user_final = "";
        }
        if($get_id_user_inicial != ''){
            $sql_id_user_inicial = "AND ticket_id_user_inicio = '".$get_id_user_inicial."'";
        }else{
            $sql_id_user_inicial = "";
        }
        if($get_id_departamento != ''){
            $sql_id_departamento = "AND ticket_id_departamento = '".$get_id_departamento."'";
        }else{
            $sql_id_departamento = "";
        }
        if($get_id_contato != ''){
            $sql_id_contato = "AND ticket_id_contato = '".$get_id_contato."'";
        }else{
            $sql_id_contato = "";
        }
        if($get_id != ''){
            $sql_id = "AND ticket_id = '".$get_id."'";
        }else{
            $sql_id = "";
        }
        if($get_assunto != ''){
            $sql_descricao = "AND ticket_assunto LIKE '%".$get_assunto."%'";
        }else{
            $sql_descricao = "";
        }
        if($get_status != ''){
            $sql_status = "AND ticket_status = '".$get_status."'";
        }else{
            $sql_status = "";
        }
        if($_SESSION[VSESSION]['user_tipo_ticket'] == '1'){
            $sql_tipo_ticket = "AND ticket_diff = '1'";
            $sql_id_contato  = "AND ticket_id_contato = '".$_SESSION[VSESSION]['user_id_contato']."'";
        }else{
            $sql_tipo_ticket = "";
        }
        
        $_SESSION['ticket_load'] = "".$sql_id." ".$sql_descricao." ".$sql_status." ".$sql_id_contato." ".$sql_id_departamento." ".$sql_id_user_final." ".$sql_id_user_inicial." ".$sql_periodo." ".$sql_tipo_ticket." ";
        
    }else{
        if($_SESSION[VSESSION]['user_tipo_ticket'] == '1'){
            $sql_tipo_ticket = "AND ticket_diff = '1'";
            $sql_contato = "AND ticket_id_contato = '".$_SESSION[VSESSION]['user_id_contato']."'";
            $_SESSION['ticket_load'] = " ".$sql_contato." ".$sql_tipo_ticket." ";
        }
    }
    
    $read_ticket_paginator = ReadComposta("SELECT ticket_id FROM ticket WHERE ticket_id != '' {$_SESSION['ticket_load']}");
    $read_ticket = Read('ticket', "WHERE ticket_id != '' {$_SESSION['ticket_load']} ".$order_by." LIMIT $inicio,$maximo");
    if(NumQuery($read_ticket) > '0'){
        //PEGA QUANTOS BOTÕES PARA PAGINAÇÃO DE RESULTADOS
        $paginas = ceil(NumQuery($read_ticket_paginator) / $maximo);
        //TABULATOR EXIGE ESSA INFORMAÇÃO PARA QUE PRECISE DA PAGINAÇÃO DE RESULTADOS 
        $json_ticket["last_page"] = $paginas;
        foreach($read_ticket as $read_ticket_view){
            if($read_ticket_view['ticket_status'] == '0'){
                $read_ticket_view['ticket_status'] = 'Pendente';
            }elseif($read_ticket_view['ticket_status'] == '1'){
                $read_ticket_view['ticket_status'] = 'Fechado';
            }elseif($read_ticket_view['ticket_status'] == '2'){
                $read_ticket_view['ticket_status'] = 'Cancelado';
            }
            if($read_ticket_view['ticket_prioridade'] == '0'){
                $read_ticket_view['ticket_prioridade'] = 'Sem prioridade';
            }elseif($read_ticket_view['ticket_prioridade'] == '1'){
                $read_ticket_view['ticket_prioridade'] = 'Baixa';
            }elseif($read_ticket_view['ticket_prioridade'] == '2'){
                $read_ticket_view['ticket_prioridade'] = 'Regular';
            }elseif($read_ticket_view['ticket_prioridade'] == '3'){
                $read_ticket_view['ticket_prioridade'] = 'Alta';
            }
            $read_contato = ReadComposta("SELECT contato_nome_razao, contato_nome_fantasia FROM contato WHERE contato_id = '".$read_ticket_view['ticket_id_contato']."'");
            if(NumQuery($read_contato) > '0'){
                foreach($read_contato as $read_contato_view);
            }
            $read_ticket_view['ticket_id_user_final'] = GetDados('user', $read_ticket_view['ticket_id_user_final'], 'user_id', 'user_nome');
            $read_ticket_view['ticket_id_contato'] = $read_contato_view['contato_nome_fantasia']. ' - ' .$read_contato_view['contato_nome_razao'];
            $json_ticket['data'][] = $read_ticket_view;
        }
    }else{
        $json_ticket['data'] = null;
    }
    echo json_encode($json_ticket);
}elseif($acao == 'create'){
    //RECUPERA O FORMULARIO
    $ticket_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($ticket_form['acao']);
    
    if(in_array('', $ticket_form)){
        $json_ticket = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        $ticket_form['ticket_id_user_inicio'] = $_SESSION[VSESSION]['user_id'];
        $ticket_form['ticket_data_criacao'] = date('Y-m-d');
        $ticket_form['ticket_data_cadastro'] = date('Y-m-d H:i:s');
        $ticket_form['ticket_status'] = '0';
        $itens_ticket_form['itens_ticket_descricao'] = $ticket_form['itens_ticket_descricao'];
        unset($ticket_form['itens_ticket_descricao']);
        if($_SESSION[VSESSION]['user_tipo_ticket'] == '1'){
            $ticket_form['ticket_diff'] = '1';
            if($ticket_form['ticket_id_departamento'] == '1'){
                $ticket_form['ticket_id_user_final'] = '6';
            }elseif($ticket_form['ticket_id_departamento'] == '2'){
                $ticket_form['ticket_id_user_final'] = '8';
            }elseif($ticket_form['ticket_id_departamento'] == '3'){
                $ticket_form['ticket_id_user_final'] = '1';
            }elseif($ticket_form['ticket_id_departamento'] == '4'){
                $ticket_form['ticket_id_user_final'] = '14';
            }else{
                $ticket_form['ticket_id_user_final'] = '18';
            }
            $ticket_form['ticket_id_contato'] = $_SESSION[VSESSION]['user_id_contato'];
        }
        Create('ticket', $ticket_form);
        $itens_ticket_form['itens_ticket_id_ticket'] = GetReg('ticket', "ticket_id", "");
        $itens_ticket_form['itens_ticket_data_cadastro'] = date('Y-m-d H:i:s');
        $itens_ticket_form['itens_ticket_data'] = date('Y-m-d');
        $itens_ticket_form['itens_ticket_id_user'] = $_SESSION[VSESSION]['user_id'];
        Create('itens_ticket', $itens_ticket_form);
        $id_ticket = GetReg('ticket', 'ticket_id', "");
        notification_ticket('Criado um ticket', 'Ticket criado e você ficou como responsável', $ticket_form['ticket_id_user_final'], $id_ticket);
        $json_ticket = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso. Lembrando que o id do protocolo é: #'.$id_ticket,
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal" onclick="limpar_campos();">Apenas Fechar</button><a href="javascript::" onclick="carrega_pagina(\'ticket\', \'index.php\');" data-dismiss="modal" class="btn btn-primary">Sair</a>'
        );
    }
    echo json_encode($json_ticket);
}elseif($acao == 'update'){
    //RECUPERA O FORMULARIO
    $ticket_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($ticket_form['acao']);
    
    if(in_array('', $ticket_form)){
        $json_ticket = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        $uid = addslashes($_POST['id']);
        unset($ticket_form['id']);
        if($ticket_form['ticket_status'] == '1'){
            $read_ticket = ReadComposta("SELECT ticket_status, ticket_diff, ticket_id_user_inicio FROM ticket WHERE ticket_id = '".$uid."'");
            if(NumQuery($read_ticket) > '0'){
                foreach($read_ticket as $read_ticket_view);
                if($read_ticket_view['ticket_status'] == '0'){
                    $ticket_form['ticket_data_fim'] = date('Y-m-d');
                    if($read_ticket_view['ticket_diff'] == '1'){
                        $assunto_email = 'Ticket finalizado';
                        $texto_email = 'O ticket #'.$uid.' foi finalizado, caso ainda queira interagir com no ticket ainda pode inserir alguma observação.';
                        $msg_envio = email_convertido($assunto_email, $texto_email);
                        $email_usuario = GetDados('user', $read_ticket_view['ticket_id_user_inicio'], 'user_id', 'user_email');
                        $nome_usuario = GetDados('user', $read_ticket_view['ticket_id_user_inicio'], 'user_id', 'user_nome');
                        sendMail($assunto_email, $msg_envio, 'financeiro@federalsistemas.com.br', 'Federal Sistemas', $email_usuario, $nome_usuario);
                    }
                }
            }
        }
        Update('ticket', $ticket_form, "WHERE ticket_id = '".$uid."'");
        $json_ticket = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<a href="javascript::" data-dismiss="modal" onclick="carrega_pagina(\'ticket\', \'index.php\');" class="btn btn-primary">Sair</a><a href="javascript::" data-dismiss="modal" onclick="refresh();" class="btn btn-success">Continuar nesse atendimento</a>'
        );
    }
    echo json_encode($json_ticket);
}elseif($acao == 'load_update'){
    $uid = addslashes($_POST['id']);
    
    $read_ticket = Read('ticket', "WHERE ticket_id = '".$uid."'");
    if(NumQuery($read_ticket) > '0'){
        foreach($read_ticket as $read_ticket_view);
        $json_ticket[] = $read_ticket_view;
    }else{
        $json_ticket = null;
    }
    echo json_encode($json_ticket);
}elseif($acao == 'load_msg'){
    $uid = addslashes($_POST['id']);
    
    $read_ticket = Read('ticket', "WHERE ticket_id = '".$uid."'");
    if(NumQuery($read_ticket) > '0'){
        $read_itens_ticket = Read('itens_ticket', "WHERE itens_ticket_id_ticket = '".$uid."' ORDER BY itens_ticket_data_cadastro ASC");
        if(NumQuery($read_itens_ticket) > '0'){
            echo '<div class="box-content nopadding">';
                echo '<ul class="messages">';
                    foreach($read_itens_ticket as $read_itens_ticket_view){
                        if($read_itens_ticket_view['itens_ticket_id_user'] == $_SESSION[VSESSION]['user_id']){
                            $foto_user_inicio = GetDados('user', $read_itens_ticket_view['itens_ticket_id_user'], 'user_id', 'user_foto');
                            if($foto_user_inicio != ''){
                                $mostra_foto_user_inicio = '<img src="'.substr($foto_user_inicio,3,300).'" alt="" width="85" height="">';
                            }else{
                                $mostra_foto_user_inicio = '<img src="_boot/img/demo/sem-foto.png" alt="" width="85">';
                            }
                            if($read_itens_ticket_view['itens_ticket_anexo'] != ''){
                                $arquivo_existente = substr(GetDados('anexo_ticket', $read_itens_ticket_view['itens_ticket_anexo'], 'anexo_ticket_id', 'anexo_ticket_arquivo'),3,500);
                                $anexo_existente = ' - <a href="'.$arquivo_existente.'" target="_blank" class="btn btn-danger">Baixar Anexo</a>';
                            }else{
                                $anexo_existente = '';
                            }
                            echo '<li class="right">
                                    <div class="image">
                                        '.$mostra_foto_user_inicio.'
                                    </div>
                                    <div class="message">
                                        <span class="caret"></span>
                                        <span class="name">'.GetDados('user', $read_itens_ticket_view['itens_ticket_id_user'], 'user_id', 'user_nome').'</span>
                                        <p>'.$read_itens_ticket_view['itens_ticket_descricao'].'</p>
                                        
                                        <span class="time">
                                            '.FormDataBrTudo($read_itens_ticket_view['itens_ticket_data_cadastro']).' '.$anexo_existente.'
                                        </span>
                                    </div>
                                </li>';
                        }else{
                            $foto_user_fim = GetDados('user', $read_itens_ticket_view['itens_ticket_id_user'], 'user_id', 'user_foto');
                            if($foto_user_fim != ''){
                                $mostra_foto_user_fim = '<img src="'.substr($foto_user_fim,3,300).'" alt="" width="85">';
                            }else{
                                $mostra_foto_user_fim = '<img src="_boot/img/demo/sem-foto.png" alt="" width="85">';
                            }
                            if($read_itens_ticket_view['itens_ticket_ativacao'] != '0'){
                                //$ticket_dados = '<p><a href="Home.php?model=ticket&pg=pedido&id_ativacao='.$read_itens_ticket_view['itens_ticket_ativacao'].'" target="_blank">Abrir Pedido</a></p>';
                            }
                            if($read_itens_ticket_view['itens_ticket_desativacao'] != '0'){
                                //$ticket_dados = '<p><a href="Home.php?model=ticket&pg=pedido&id_desativacao='.$read_itens_ticket_view['itens_ticket_desativacao'].'" target="_blank">Abrir Pedido</a></p>';
                            }
                            if($read_itens_ticket_view['itens_ticket_anexo'] != ''){
                                $arquivo_existente = substr(GetDados('anexo_ticket', $read_itens_ticket_view['itens_ticket_anexo'], 'anexo_ticket_id', 'anexo_ticket_arquivo'),3,500);
                                $anexo_existente = ' - <a href="'.$arquivo_existente.'" target="_blank" class="btn btn-danger">Baixar Anexo(Sis Novo)</a> <a href="http://federalmultinivel.com.br/facilita_gestor/'.$arquivo_existente.'" target="_blank" class="btn btn-danger">Baixar Anexo(Sis Antigo)</a>';
                            }else{
                                $anexo_existente = '';
                            }
                            echo '<li class="left">
                                    <div class="image">
                                        '.$mostra_foto_user_fim.'
                                    </div>
                                    <div class="message">
                                        <span class="caret"></span>
                                        <span class="name">'.GetDados('user', $read_itens_ticket_view['itens_ticket_id_user'], 'user_id', 'user_nome').'</span>
                                        <p>'.$read_itens_ticket_view['itens_ticket_descricao'].'</p>
                                        '.$ticket_dados.'
                                        <span class="time">
                                            '.FormDataBrTudo($read_itens_ticket_view['itens_ticket_data_cadastro']).' '.$anexo_existente.'
                                        </span>
                                    </div>
                                </li>';
                        }
                    }
                    echo '<li class="insert">
                            <form id="message-form" action="">
                                <div class="text">
                                    <input type="hidden" class="id_anexo"/>
                                    <input type="text" name="text" placeholder="Registrar atendimento" autocomplete="off" class="form-control msg_itens_atendimento">
                                </div>
                                <div class="submit">
                                    <button type="button" class="btn btn-primary" onclick="insert_ticket();">
                                        <i class="fa fa-share"></i>
                                    </button>
                                    <button type="button" class="btn btn-primary" onclick="open_anexo();">
                                        <i class="fa fa-upload"></i>
                                    </button>
                                    <button type="button" class="btn btn-primary" onclick="delete_arquivo_anexo()">
                                        <div id="arquivo_anexado"></div>
                                    </button>
                                </div>
                            </form>
                        </li>';
                echo '</ul>';
            echo '</div>';
        }else{
            echo '<div class="alert alert-danger">Ticket não pode carregar conversa</div>';
        }
    }else{
        echo '<div class="alert alert-danger">Ticket não foi identificado</div>';
    }
    
}elseif($acao == 'insert_ticket'){
    $uid = addslashes($_POST['id']);
    $msg_atendimento = addslashes(trim($_POST['msg_atendimento']));
    $id_anexo = addslashes(trim($_POST['id_anexo']));
    
    if($uid == '' || $msg_atendimento == ''){
        $json_itens_ticket = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        $itens_ticket_form['itens_ticket_id_ticket'] = $uid;
        $itens_ticket_form['itens_ticket_descricao'] = $msg_atendimento;
        $itens_ticket_form['itens_ticket_data_cadastro'] = date('Y-m-d H:i:s');
        $itens_ticket_form['itens_ticket_data'] = date('Y-m-d');
        $itens_ticket_form['itens_ticket_id_user'] = $_SESSION[VSESSION]['user_id'];
        if($id_anexo != ''){
            $itens_ticket_form['itens_ticket_anexo'] = $id_anexo;
        }
        Create('itens_ticket', $itens_ticket_form);
        $user_inicio_ticket = GetDados('ticket', $uid, 'ticket_id', 'ticket_id_user_inicio');
        $user_final_ticket = GetDados('ticket', $uid, 'ticket_id', 'ticket_id_user_final');
        if($user_inicio_ticket == $_SESSION[VSESSION]['user_id']){
            notification_ticket('Nova interação no ticket #'.$uid, 'Verifique o que aconteceu...', $user_final_ticket, $uid);
        }elseif($user_final_ticket == $_SESSION[VSESSION]['user_id']){
            $ticket_diff = GetDados('ticket', $uid, 'ticket_id', 'ticket_diff');
            if($ticket_diff == '1'){
                notification_ticket('Nova interação no ticket #'.$uid, 'Verifique o que aconteceu...', $user_inicio_ticket, $uid);
                $assunto_email = 'Nova interação';
                $texto_email = 'Houve uma interação no ticket, clique no link abaixo e acesse as notificações geradas pelo sistema.';
                $msg_envio = email_convertido($assunto_email, $texto_email);
                $email_usuario = GetDados('user', $user_inicio_ticket, 'user_id', 'user_email');
                $nome_usuario = GetDados('user', $user_inicio_ticket, 'user_id', 'user_nome');
                sendMail($assunto_email, $msg_envio, 'financeiro@federalsistemas.com.br', 'Federal Sistemas', $email_usuario, $nome_usuario);
            }else{
                notification_ticket('Nova interação no ticket #'.$uid, 'Verifique o que aconteceu...', $user_inicio_ticket, $uid);
            }
        }else{
            if($ticket_diff == '1'){
                notification_ticket('Nova interação no ticket #'.$uid, 'Verifique o que aconteceu...', $user_inicio_ticket, $uid);
                $assunto_email = 'Nova interação';
                $texto_email = 'Houve uma interação no ticket, clique no link abaixo e acesse as notificações geradas pelo sistema.';
                $msg_envio = email_convertido($assunto_email, $texto_email);
                $email_usuario = GetDados('user', $user_inicio_ticket, 'user_id', 'user_email');
                $nome_usuario = GetDados('user', $user_inicio_ticket, 'user_id', 'user_nome');
                sendMail($assunto_email, $msg_envio, 'financeiro@federalsistemas.com.br', 'Federal Sistemas', $email_usuario, $nome_usuario);
                notification_ticket('Nova interação no ticket #'.$uid, 'Verifique o que aconteceu...', $user_final_ticket, $uid);
            }else{
                notification_ticket('Nova interação no ticket #'.$uid, 'Verifique o que aconteceu...', $user_inicio_ticket, $uid);
                notification_ticket('Nova interação no ticket #'.$uid, 'Verifique o que aconteceu...', $user_final_ticket, $uid);
            }
        }
        $json_itens_ticket = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<a href="javascript::" data-dismiss="modal" onclick="carrega_pagina(\'ticket\', \'index.php\');" class="btn btn-primary">Sair</a><a href="javascript::" data-dismiss="modal" onclick="refresh();" class="btn btn-success">Continuar nesse atendimento</a>'
        );
    }
    echo json_encode($json_itens_ticket);
}elseif($acao == 'gerar_excel'){
    $arquivo = 'caixa.xls';
    $tabela = '<table border="1" width="800px">';
        $tabela .= '<tr>';
            $tabela .= '<td colspan="3" align="center">Relação de caixas</tr>';
        $tabela .= '</tr>';
        $tabela .= '<tr>';
            $tabela .= '<td><b>Código</b></td>';
            $tabela .= '<td><b>Descrição</b></td>';
            $tabela .= '<td><b>Status</b></td>';
        $tabela .= '</tr>';
    
    $read_caixa = Read('caixa', "WHERE caixa_id != '' {$_SESSION['caixa_load']} ORDER BY caixa_descricao ASC");
    if(NumQuery($read_caixa) > '0'){
        foreach($read_caixa as $read_caixa_view){
            if($read_caixa_view['caixa_status'] == '0'){
                $status_caixa = 'ATIVO';
            }else{
                $status_caixa = 'INATIVO';
            }
            $tabela .= '<tr>';
                $tabela .= '<td>'.$read_caixa_view['caixa_id'].'</td>';
                $tabela .= '<td>'.$read_caixa_view['caixa_descricao'].'</td>';
                $tabela .= '<td>'.$status_caixa.'</td>';
            $tabela .= '</tr>';
        }
    }
    
    header("Content-type: application/vnd.ms-excel");  
    header("Content-type: application/force-download"); 
    header("Content-Disposition: attachment; filename=file.xls");
    header("Pragma: no-cache");
    echo $tabela;
}elseif($acao == 'confirmar_operacao'){
    $id_ativacao = addslashes($_POST['id_ativacao']);
    
    
    $read_ativacao = Read('ativacao', "WHERE ativacao_id = '".$id_ativacao."'");
    if(NumQuery($read_ativacao) > '0'){
        foreach($read_ativacao as $read_ativacao_view);
        $read_itens_ativacao = Read('itens_ativacao', "WHERE itens_ativacao_id_ativacao = '".$id_ativacao."'");
        if(NumQuery($read_itens_ativacao) > '0'){
            foreach($read_itens_ativacao as $read_itens_ativacao_view);
            //print_r($read_itens_ativacao_view);
            $read_itens_pedido = Read('itens_pedido', "WHERE itens_pedido_id_chip = '".$read_itens_ativacao_view['itens_ativacao_id_chip']."' ORDER BY itens_pedido_id DESC");
            if(NumQuery($read_itens_pedido) > '0'){
                foreach($read_itens_pedido as $read_itens_pedido_view);
                $ID_PEDIDO_CORRETO = $read_itens_pedido_view['itens_pedido_id_pedido'];
                //print_r($read_itens_pedido_view);
            }
        }
        $pedido_form['pedido_id_cliente']    = $read_ativacao_view['ativacao_id_contato'];
        $pedido_form['pedido_data']       	 = $read_ativacao_view['ativacao_data'];
        $pedido_form['pedido_data_ativacao'] = $read_ativacao_view['ativacao_data'];
        $pedido_form['pedido_tipo']          = '0';
        $pedido_form['pedido_status']        = '1';
        $pedido_form['pedido_id_plano']      = GetDados('pedido', $read_itens_pedido_view['itens_pedido_id_pedido'], 'pedido_id', 'pedido_id_plano');
        $pedido_form['pedido_valor_plano']   = GetDados('pedido', $read_itens_pedido_view['itens_pedido_id_pedido'], 'pedido_id', 'pedido_valor_plano');
        $pedido_form['pedido_valor_ativacao']= GetDados('pedido', $read_itens_pedido_view['itens_pedido_id_pedido'], 'pedido_id', 'pedido_valor_ativacao');
        $pedido_form['pedido_id_user']       = $_SESSION[VSESSION]['user_id'];
        $pedido_form['pedido_tipo_frete']    = '0';
        $pedido_form['pedido_valor_frete']   = '0';
        $pedido_form['pedido_obs']           = 'PEDIDO FEITO AUTOMATICAMENTE PELO SISTEMA, OS CHIPS FORAM INSERIDOS PELO CLIENTE VIA PAINEL DO MESMO';
        $pedido_form['pedido_data_hora']     = date('Y-m-d H:i:s');
        //print_r($pedido_form);
        Create('pedido', $pedido_form);
        $id_pedido_ultimo = GetReg('pedido', 'pedido_id', "");
        if(NumQuery($read_itens_ativacao) > '0'){
            foreach($read_itens_ativacao as $read_itens_ativacao_view){
                $itens_pedido_form['itens_pedido_id_pedido'] = $id_pedido_ultimo;
                $itens_pedido_form['itens_pedido_id_chip']   = $read_itens_ativacao_view['itens_ativacao_id_chip'];
                $itens_pedido_form['itens_pedido_num_chip']  = GetDados('chip', $read_itens_ativacao_view['itens_ativacao_id_chip'], 'chip_id', 'chip_num');
                $itens_pedido_form['itens_pedido_iccid']     = GetDados('chip', $read_itens_ativacao_view['itens_ativacao_id_chip'], 'chip_id', 'chip_iccid');
                //print_r($itens_pedido_form);
                //print_r($read_itens_pedido_view);
				Create('itens_pedido', $itens_pedido_form);
				Delete('itens_pedido', "WHERE itens_pedido_id_chip = '".$read_itens_ativacao_view['itens_ativacao_id_chip']."' AND itens_pedido_id_pedido = '".$ID_PEDIDO_CORRETO."'");
                
                //echo $ID_PEDIDO_CORRETO;
                //echo $read_itens_ativacao_view['itens_ativacao_id_chip'].'-';
                //echo $ID_PEDIDO_CORRETO.'<br />';
                
            }
        }
        $UpDateDados['ativacao_status'] = '1';
        Update('ativacao', $UpDateDados, "WHERE ativacao_id = '".$id_ativacao."'");
        $UpDateDadosOK['itens_ativacao_status'] = '2';
        Update('itens_ativacao', $UpDateDadosOK, "WHERE itens_ativacao_id_ativacao = '".$id_ativacao."'");
    }
}elseif($acao == 'anexo_ticket'){
    $anexo_ticket_id_ticket = addslashes($_POST['anexo_ticket_id_ticket']);
    
    if($anexo_ticket_id_ticket == ''){
        $data['sucesso'] = false;

        $data['msg'] = 'Todos os campos devem ser preenchidos!';
    }else{
        $arquivo = $_FILES['arquivo'];

        $tipos = array('pdf', 'jpg', 'zip', 'eml');

        $enviar = uploadFile($arquivo, '../_uploads/'.GetEmpresa('empresa_nome_pasta').'/ticket/', $tipos);

        $data['sucesso'] = false;

        if($enviar['erro']){    
            $data['msg'] = $enviar['erro'];
        }else{
            $data['sucesso'] = true;

            $data['msg'] = $enviar['caminho'];
            
            $anexo_form['anexo_ticket_arquivo']        = $enviar['caminho'];
            $anexo_form['anexo_ticket_data_hora']      = date('Y-m-d H:i:s');
            $anexo_form['anexo_ticket_id_ticket']      = $anexo_ticket_id_ticket;
            $anexo_form['anexo_ticket_id_user']        = $_SESSION[VSESSION]['user_id'];
            
            Create('anexo_ticket', $anexo_form);
        }
    }
    echo json_encode($data);
}elseif($acao == 'load_gallery'){
    $post_id_ticket = addslashes($_POST['id']);
    
    $read_anexo_contrato = Read('anexo_ticket', "WHERE anexo_ticket_id_ticket = '".$post_id_ticket."'");
    if(NumQuery($read_anexo_contrato) > '0'){
        echo '<ul class="gallery">';
        foreach($read_anexo_contrato as $read_anexo_contrato_view){
            $ext = substr($read_anexo_contrato_view['anexo_ticket_arquivo'], -3);
            if($ext == 'jpg'){
                echo '<li>
                    <a href="#">
                        <img src="'.substr($read_anexo_contrato_view['anexo_ticket_arquivo'],3,500).'" width="100" height="100" alt="">
                    </a>
                    <div class="extras">
                        <div class="extras-inner">
                            <a href="'.substr($read_anexo_contrato_view['anexo_ticket_arquivo'],3,500).'" target="_blank" class="colorbox-image" rel="group-1">
                                <i class="fa fa-search"></i>
                            </a>
                            <a href="#" class="del-gallery-pic" onclick="vincular_arquivo('.$read_anexo_contrato_view['anexo_ticket_id'].');" title="Anexar esse arquivo a uma conversa">
                                <i class="fa fa-mail-reply"></i>
                            </a>
                        </div>
                    </div>
                </li>';
            }else{
                echo '<li>
                    <a href="#">
                        <img src="_img/download_pdf.png" width="100" height="100" alt="">
                    </a>
                    <div class="extras">
                        <div class="extras-inner">
                            <a href="'.substr($read_anexo_contrato_view['anexo_ticket_arquivo'],3,500).'" target="_blank" class="colorbox-image" rel="group-1">
                                <i class="fa fa-search"></i>
                            </a>
                            <a href="#" class="del-gallery-pic" onclick="vincular_arquivo('.$read_anexo_contrato_view['anexo_ticket_id'].');" title="Anexar esse arquivo a uma conversa">
                                <i class="fa fa-mail-reply"></i>
                            </a>
                        </div>
                    </div>
                </li>';
            }
        }
        echo '</ul>';
    }
}elseif($acao == 'confirmar_operacao_desativacao'){
    $id_ativacao = addslashes($_POST['id_desativacao']);
    $id_chip_desativacao = $_POST['id_chip'];
    $valor_chip_desativacao = $_POST['valor_chip'];
    
    
    $read_ativacao = Read('desativacao', "WHERE desativacao_id = '".$id_ativacao."'");
    if(NumQuery($read_ativacao) > '0'){
        foreach($read_ativacao as $read_ativacao_view);
        $read_itens_ativacao = Read('itens_desativacao', "WHERE itens_desativacao_id_ativacao = '".$id_ativacao."'");
        if(NumQuery($read_itens_ativacao) > '0'){
            foreach($read_itens_ativacao as $read_itens_ativacao_view);
            //print_r($read_itens_ativacao_view);
            $read_itens_pedido = Read('itens_pedido', "WHERE itens_pedido_id_chip = '".$read_itens_ativacao_view['itens_ativacao_id_chip']."' ORDER BY itens_pedido_id DESC");
            if(NumQuery($read_itens_pedido) > '0'){
                foreach($read_itens_pedido as $read_itens_pedido_view);
                $ID_PEDIDO_CORRETO = $read_itens_pedido_view['itens_pedido_id_pedido'];
                //print_r($read_itens_pedido_view);
            }
        }
        $pedido_form['pedido_id_cliente']    = $read_ativacao_view['desativacao_id_contato'];
        $pedido_form['pedido_data']       	 = $read_ativacao_view['desativacao_data'];
        $pedido_form['pedido_data_ativacao'] = $read_ativacao_view['desativacao_data'];
        $pedido_form['pedido_tipo']          = '1';
        $pedido_form['pedido_status']        = '1';
        $pedido_form['pedido_id_plano']      = GetDados('pedido', $read_itens_pedido_view['itens_pedido_id_pedido'], 'pedido_id', 'pedido_id_plano');
        $pedido_form['pedido_valor_plano']   = GetDados('pedido', $read_itens_pedido_view['itens_pedido_id_pedido'], 'pedido_id', 'pedido_valor_plano');
        $pedido_form['pedido_valor_ativacao']= GetDados('pedido', $read_itens_pedido_view['itens_pedido_id_pedido'], 'pedido_id', 'pedido_valor_ativacao');
        $pedido_form['pedido_id_user']       = $_SESSION[VSESSION]['user_id'];
        $pedido_form['pedido_tipo_frete']    = '0';
        $pedido_form['pedido_valor_frete']   = '0';
        $pedido_form['pedido_obs']           = 'PEDIDO FEITO AUTOMATICAMENTE PELO SISTEMA, OS CHIPS FORAM INSERIDOS PELO CLIENTE VIA PAINEL DO MESMO';
        $pedido_form['pedido_data_hora']     = date('Y-m-d H:i:s');
        Create('pedido', $pedido_form);
        $count_dados_chip = '0';
        $id_pedido_ultimo = GetReg('pedido', 'pedido_id', "");
        if(NumQuery($read_itens_ativacao) > '0'){
            foreach($read_itens_ativacao as $read_itens_ativacao_view){
                $itens_pedido_form['itens_pedido_id_pedido'] = $id_pedido_ultimo;
                $itens_pedido_form['itens_pedido_id_chip']   = $read_itens_ativacao_view['itens_desativacao_id_chip'];
                $itens_pedido_form['itens_pedido_num_chip']  = GetDados('chip', $read_itens_ativacao_view['itens_desativacao_id_chip'], 'chip_id', 'chip_num');
                $itens_pedido_form['itens_pedido_iccid']     = GetDados('chip', $read_itens_ativacao_view['itens_desativacao_id_chip'], 'chip_id', 'chip_iccid');
                Create('itens_pedido', $itens_pedido_form);
                $pedido_desinstalacao['pedido_desinstalacao_id_chip'] = $read_itens_ativacao_view['itens_desativacao_id_chip'];
                $pedido_desinstalacao['pedido_desinstalacao_valor_multa'] = '0';
                if($id_chip_desativacao[$count_dados_chip] == $read_itens_ativacao_view['itens_desativacao_id_chip']){
                    $faturar_chip_dados = '1';
                    $valor_fatira_chip_dados = $valor_chip_desativacao;
                }else{
                    $faturar_chip_dados = '0';
                    $valor_fatira_chip_dados = '0';
                }
                $count_dados_chip++;
                $pedido_desinstalacao['pedido_desinstalacao_faturar'] = $faturar_chip_dados;
                $pedido_desinstalacao['pedido_desinstalacao_valor_fatura'] = $valor_fatira_chip_dados;
                $pedido_desinstalacao['pedido_desinstalacao_data'] = $read_ativacao_view['desativacao_data'];
                $pedido_desinstalacao['pedido_desinstalacao_id_pedido'] = $id_pedido_ultimo;
                $pedido_desinstalacao['pedido_desinstalacao_valor_total'] = '10';
                Create('pedido_desinstalacao', $pedido_desinstalacao);
                $UpDatePedidoChip['chip_status'] = '0';
                Update('chip', $UpDatePedidoChip, "WHERE chip_id = '".$read_itens_ativacao_view['itens_desativacao_id_chip']."'");
            }
        }
        $UpDateDados['desativacao_status'] = '1';
        Update('desativacao', $UpDateDados, "WHERE desativacao_id = '".$id_ativacao."'");
        $UpDateDadosOK['itens_desativacao_status'] = '2';
        Update('itens_desativacao', $UpDateDadosOK, "WHERE itens_desativacao_id_ativacao = '".$id_ativacao."'");
    }
}elseif($acao == 'confirmar_operacao_desativacao_1'){
    $id_chip = $_POST['id_chip'];
    print_r($id_chip);
}
?>