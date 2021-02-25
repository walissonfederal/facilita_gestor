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
        $get_id_user_final      = addslashes($_GET['id_user_final']);
        $get_data_inicial       = addslashes($_GET['data_inicial']);
        $get_data_final         = addslashes($_GET['data_final']);
        $get_tipo_pesquisa      = addslashes($_GET['tipo_pesquisa']);
        
        if($get_data_inicial != '' && $get_data_final != ''){
            $sql_periodo = "AND $get_tipo_pesquisa BETWEEN '".$get_data_inicial."' AND '".$get_data_final."'";
        }else{
            $sql_periodo = "";
        }
        if($get_id_user_final != ''){
            $sql_id_user_final = "AND ticket_id_responsavel = '".$get_id_user_final."'";
        }else{
            $sql_id_user_final = "";
        }
        if($get_id_contato != ''){
            $sql_id_contato = "AND ticket_id_user = '".$get_id_contato."'";
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
        $_SESSION['ticket_load'] = "".$sql_id." ".$sql_descricao." ".$sql_status." ".$sql_id_contato." ".$sql_id_user_final." ".$sql_periodo." ";
        
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
            $read_contato = ReadComposta("SELECT user_nome, user_email FROM user WHERE user_id = '".$read_ticket_view['ticket_id_user']."'");
            if(NumQuery($read_contato) > '0'){
                foreach($read_contato as $read_contato_view);
            }
            $read_ticket_view['ticket_id_responsavel'] = GetDados('atendente', $read_ticket_view['ticket_id_responsavel'], 'atendente_id', 'atendente_nome');
            $read_ticket_view['ticket_id_user'] = $read_contato_view['user_nome']. ' - ' .$read_contato_view['user_email'];
            $json_ticket['data'][] = $read_ticket_view;
        }
    }else{
        $json_ticket['data'] = null;
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
            $read_ticket = ReadComposta("SELECT ticket_status FROM ticket WHERE ticket_id = '".$uid."'");
            if(NumQuery($read_ticket) > '0'){
                foreach($read_ticket as $read_ticket_view);
                if($read_ticket_view['ticket_status'] == '0'){
                    $ticket_form['ticket_data_final'] = date('Y-m-d');
                }
            }
        }
        Update('ticket', $ticket_form, "WHERE ticket_id = '".$uid."'");
        $json_ticket = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<a href="javascript::" data-dismiss="modal" onclick="carrega_pagina(\'mmn_ticket\', \'index.php\');" class="btn btn-primary">Sair</a><a href="javascript::" data-dismiss="modal" onclick="refresh();" class="btn btn-success">Continuar nesse atendimento</a>'
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
        foreach($read_ticket as $read_ticket_view);
        $read_itens_ticket = Read('itens_ticket', "WHERE itens_ticket_id_ticket = '".$uid."' ORDER BY itens_ticket_data_cadastro ASC");
        if(NumQuery($read_itens_ticket) > '0'){
            echo '<div class="box-content nopadding">';
                echo '<ul class="messages">';
                    foreach($read_itens_ticket as $read_itens_ticket_view){
                        if($read_itens_ticket_view['itens_ticket_id_user'] == $_SESSION['PROJETO_CDL']['user_id']){
                            $mostra_foto_user_inicio = '<img src="_boot/img/demo/sem-foto.png" alt="" width="85">';
                            echo '<li class="right">
                                    <div class="image">
                                        '.$mostra_foto_user_inicio.'
                                    </div>
                                    <div class="message">
                                        <span class="caret"></span>
                                        <span class="name">'.GetDados('atendente', $read_itens_ticket_view['itens_ticket_id_user'], 'atendente_id', 'atendente_nome').'</span>
                                        <p>'.$read_itens_ticket_view['itens_ticket_descricao'].'</p>
                                        
                                        <span class="time">
                                            '.date('d/m/Y H:i',  strtotime($read_itens_ticket_view['itens_ticket_data_cadastro'])).'
                                        </span>
                                    </div>
                                </li>';
                        }else{
                            $mostra_foto_user_fim = '<img src="_boot/img/demo/sem-foto.png" alt="" width="85">';
                            if($read_itens_ticket_view['itens_ticket_id_user'] == '0'){
                                echo '<li class="left">
                                    <div class="image">
                                        '.$mostra_foto_user_fim.'
                                    </div>
                                    <div class="message">
                                        <span class="caret"></span>
                                        <span class="name">'.GetDados('user', $read_ticket_view['ticket_id_user'], 'user_id', 'user_nome').'</span>
                                        <p>'.$read_itens_ticket_view['itens_ticket_descricao'].'</p>
                                        <span class="time">
                                            '.date('d/m/Y H:i',  strtotime($read_itens_ticket_view['itens_ticket_data_cadastro'])).'
                                        </span>
                                    </div>
                                </li>';
                            }else{
                                echo '<li class="left">
                                    <div class="image">
                                        '.$mostra_foto_user_fim.'
                                    </div>
                                    <div class="message">
                                        <span class="caret"></span>
                                        <span class="name">'.GetDados('atendente', $read_itens_ticket_view['itens_ticket_id_user'], 'atendente_id', 'atendente_nome').'</span>
                                        <p>'.$read_itens_ticket_view['itens_ticket_descricao'].'</p>
                                        <span class="time">
                                            '.date('d/m/Y H:i',  strtotime($read_itens_ticket_view['itens_ticket_data_cadastro'])).'
                                        </span>
                                    </div>
                                </li>';
                            }
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
        $itens_ticket_form['itens_ticket_id_user'] = $_SESSION['PROJETO_CDL']['user_id'];
        Create('itens_ticket', $itens_ticket_form);
        $user_inicio_ticket = GetDados('ticket', $uid, 'ticket_id', 'ticket_id_responsavel');
        notification_ticket('Nova interação no ticket #'.$uid, 'Verifique o que aconteceu...', $user_inicio_ticket, $uid);
        $json_itens_ticket = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<a href="javascript::" data-dismiss="modal" onclick="carrega_pagina(\'mmn_ticket\', \'index.php\');" class="btn btn-primary">Sair</a><a href="javascript::" data-dismiss="modal" onclick="refresh();" class="btn btn-success">Continuar nesse atendimento</a>'
        );
    }
    echo json_encode($json_itens_ticket);
}elseif($acao == 'load_atendente'){
    $read_atendente = Read('atendente', "ORDER BY atendente_nome ASC");
    if(NumQuery($read_atendente) > '0'){
        foreach($read_atendente as $read_atendente_view){
            $json_atendente["data"][] = $read_atendente_view;
        }
        echo json_encode($json_atendente);
    }
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
        $ticket_form['ticket_data_inicial'] = date('Y-m-d');
        $ticket_form['ticket_data_cadastro'] = date('Y-m-d H:i:s');
        $ticket_form['ticket_status'] = '0';
        $itens_ticket_form['itens_ticket_descricao'] = $ticket_form['itens_ticket_descricao'];
        unset($ticket_form['itens_ticket_descricao']);
        $ticket_form['ticket_tipo'] = '1';
        Create('ticket', $ticket_form);
        $itens_ticket_form['itens_ticket_id_ticket'] = GetReg('ticket', "ticket_id", "");
        $itens_ticket_form['itens_ticket_data_cadastro'] = date('Y-m-d H:i:s');
        $itens_ticket_form['itens_ticket_id_user'] = $_SESSION['PROJETO_CDL']['user_id'];
        Create('itens_ticket', $itens_ticket_form);
        $id_ticket = GetReg('ticket', 'ticket_id', "");
        notification_ticket('Criado um ticket', 'Ticket criado e você ficou como responsável', $ticket_form['ticket_id_responsavel'], $id_ticket);
        $json_ticket = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal" onclick="limpar_campos();">Apenas Fechar</button><a href="javascript::" onclick="carrega_pagina(\'mmn_ticket\', \'index.php\');" data-dismiss="modal" class="btn btn-primary">Sair</a>'
        );
    }
    echo json_encode($json_ticket);
}elseif($acao == 'notificacao'){
    $read_notificacao = Read('notificacao', "WHERE notificacao_id_user = '".$_SESSION['PROJETO_CDL']['user_id']."' AND notificacao_status = '0'");
    if(NumQuery($read_notificacao) > '0'):
        echo '<ul class="icon-nav">';
            echo '<li class="dropdown">';
                echo '<a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-envelope"></i>
                        <span class="label label-lightred">'.  NumQuery($read_notificacao).'</span>
                    </a>';
                echo '<ul class="dropdown-menu pull-right message-ul">';
                    foreach($read_notificacao as $read_notificacao_view):
                        echo '<li>
                                <a href="javascript::" onclick="carrega_pagina(\'mmn_notificacao\', \'view.php?id='.$read_notificacao_view['notificacao_id'].'\');">
                                    <div class="details">
                                        <div class="name">'.$read_notificacao_view['notificacao_titulo'].'</div>
                                        <div class="message">
                                            '.$read_notificacao_view['notificacao_descricao'].'
                                        </div>
                                    </div>
                                </a>
                            </li>';
                    endforeach;
                    echo '<li>
                            <a href="javascript::" class="more-messages" onclick="carrega_pagina(\'mmn_notificacao\', \'index.php\');">Veja todas as notificações (MMN)
                                <i class="fa fa-arrow-right"></i>
                            </a>
                        </li>';
                echo '</ul>';
            echo '</li>';
        echo '<ul>';
    endif;
}
?>