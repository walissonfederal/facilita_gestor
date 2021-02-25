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
        $order_by = "ORDER BY user_id DESC";
    }else{
        
        $sort       = addslashes($_GET['sort']);
        $sort_dir   = addslashes($_GET['sort_dir']);
        $order_by   = "ORDER BY ".$sort." ".$sort_dir."";
    }
    
    //PESQUISA VIA AJAX
    if(isset($_GET['search']) && $_GET['search'] == 'true'){
        $get_id         = addslashes($_GET['id']);
        $get_nome       = addslashes($_GET['nome']);
        $get_status     = addslashes($_GET['status']);
        $get_cpf        = addslashes($_GET['cpf']);
        $get_rg         = addslashes($_GET['rg']);
        $get_email      = addslashes($_GET['email']);
        
        if($get_id != ''){
            $sql_id = "AND user_id = '".$get_id."'";
        }else{
            $sql_id = "";
        }
        if($get_nome != ''){
            $sql_nome = "AND user_nome LIKE '%".$get_nome."%'";
        }else{
            $sql_nome = "";
        }
        if($get_cpf != ''){
            $sql_cpf = "AND user_cpf LIKE '%".$get_cpf."%'";
        }else{
            $sql_cpf = "";
        }
        if($get_rg != ''){
            $sql_rg = "AND user_rg LIKE '%".$get_rg."%'";
        }else{
            $sql_rg = "";
        }
        if($get_email != ''){
            $sql_email = "AND user_email LIKE '%".$get_email."%'";
        }else{
            $sql_email = "";
        }
        if($get_status != ''){
            $sql_status = "AND user_status = '".$get_status."'";
        }else{
            $sql_status = "";
        }
        
        $_SESSION['user_mmn_load'] = "".$sql_id." ".$sql_nome." ".$sql_status." ".$sql_cpf." ".$sql_rg." ".$sql_email." ";
    }
    
    $read_user_paginator = ReadComposta("SELECT user_id FROM user WHERE user_id != '' {$_SESSION['user_mmn_load']}");
    $read_user = Read('user', "WHERE user_id != '' {$_SESSION['user_mmn_load']} ".$order_by." LIMIT $inicio,$maximo");
    if(NumQuery($read_user) > '0'){
        //PEGA QUANTOS BOTÕES PARA PAGINAÇÃO DE RESULTADOS
        $paginas = ceil(NumQuery($read_user_paginator) / $maximo);
        //TABULATOR EXIGE ESSA INFORMAÇÃO PARA QUE PRECISE DA PAGINAÇÃO DE RESULTADOS 
        $json_user["last_page"] = $paginas;
        foreach($read_user as $read_user_view){
            if($read_user_view['user_status'] == '0'){
                $read_user_view['user_status'] = 'Inativo';
            }elseif($read_user_view['user_status'] == '1'){
                $read_user_view['user_status'] = 'Ativo';
            }else{
                $read_user_view['user_status'] = 'Cancelado';
            }
            $json_user['data'][] = $read_user_view;
        }
    }else{
        $json_user['data'] = null;
    }
    echo json_encode($json_user);
}elseif($acao == 'load_anotacoes'){
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
        $order_by = "ORDER BY anotacao_id DESC";
    }else{
        
        $sort       = addslashes($_GET['sort']);
        $sort_dir   = addslashes($_GET['sort_dir']);
        $order_by   = "ORDER BY ".$sort." ".$sort_dir."";
    }
    
    $read_user_paginator = ReadComposta("SELECT anotacao_id FROM anotacao WHERE anotacao_id_user = '".$_GET['id_user']."'");
    $read_user = Read('anotacao', "WHERE anotacao_id_user = '".$_GET['id_user']."' ".$order_by." LIMIT $inicio,$maximo");
    if(NumQuery($read_user) > '0'){
        //PEGA QUANTOS BOTÕES PARA PAGINAÇÃO DE RESULTADOS
        $paginas = ceil(NumQuery($read_user_paginator) / $maximo);
        //TABULATOR EXIGE ESSA INFORMAÇÃO PARA QUE PRECISE DA PAGINAÇÃO DE RESULTADOS 
        $json_user["last_page"] = $paginas;
        foreach($read_user as $read_user_view){
            $json_user['data'][] = $read_user_view;
        }
    }else{
        $json_user['data'] = null;
    }
    echo json_encode($json_user);
}elseif($acao == 'create_anotacao'){
    //RECUPERA O FORMULARIO
    $caixa_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($caixa_form['acao']);
    
    if(in_array('', $caixa_form)){
        $json_caixa = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        $caixa_form['anotacao_data_hora'] = date('Y-m-d H:i:s');
        Create('anotacao', $caixa_form);
        $json_caixa = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal" onclick="limpar_campos();">Apenas Fechar</button><a href="javascript::" onclick="carrega_pagina(\'mmn_user\', \'index.php\');" data-dismiss="modal" class="btn btn-primary">Sair</a>'
        );
    }
    echo json_encode($json_caixa);
}elseif($acao == 'update'){
    //RECUPERA O FORMULARIO
    $user_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($user_form['acao']);
    $rg_user = $user_form['user_rg'];
    unset($user_form['user_rg']);
    if(in_array('', $user_form)){
        $json_user = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        $user_form['user_rg'] = $rg_user;
        $uid = addslashes($_POST['id']);
        unset($user_form['id']);
        Update('user', $user_form, "WHERE user_id = '".$uid."'");
        $json_user = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<a href="javascript::" data-dismiss="modal" onclick="carrega_pagina(\'mmn_user\', \'index.php\');" class="btn btn-primary">Sair</a>'
        );
    }
    echo json_encode($json_user);
}elseif($acao == 'load_update'){
    $uid = addslashes($_POST['id']);
    
    $read_user = Read('user', "WHERE user_id = '".$uid."'");
    if(NumQuery($read_user) > '0'){
        foreach($read_user as $read_user_view);
        $read_user_view['user_id_pai'] = GetDados('user', $read_user_view['user_id_pai'], 'user_id', 'user_nome');
        $json_user[] = $read_user_view;
    }else{
        $json_user = null;
    }
    echo json_encode($json_user);
}elseif($acao == 'load_caixa'){
    $read_caixa = Read('caixa', "ORDER BY caixa_descricao ASC");
    if(NumQuery($read_caixa) > '0'){
        foreach($read_caixa as $read_caixa_view){
            $json_caixa["data"][] = $read_caixa_view;
        }
        echo json_encode($json_caixa);
    }
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
}elseif($acao == 'reenviar'){
    $get_uid = addslashes(trim(strip_tags($_POST['id'])));
    
    $read_user = Read('user', "WHERE user_id = '".$get_uid."'");
    if(NumQuery($read_user) > '0'){
        foreach($read_user as $read_user_view);
        $MailBody = "
            <p style='font-size: 1.4em;'>Prezado(a), ".$read_user_view['user_nome']."</p>
            <p>Goianésia - GO, ".date('d')." de mês ".date('m')." do ano de ".date('Y')."</p>
            <p>À ".$read_user_view['user_nome']."</p>
            <p>Seu login de acesso: <strong>".$read_user_view['user_email']."</strong></p>
            <p>Sua senha de acesso: <strong>".$read_user_view['user_senha']."</strong></p>
            <p style='font-size: 1.2em;'><a href='" . URL . "' title=''>ACESSAR ESCRITORIO VIRTUAL</a></p>
            <p>...</p>
            <p>OBS.: Caso não tenha solicitado, favor ignore essa mensagem!</p>  
            <p>IMPORTANTE: caso não tenha ciência desta mensagem por favor não prossiga!</p>
            <p>IMPORTANTE: caso não concorde com nossos termos favor não prossiga!</p>
            <p>...</p>
            <p>Qualquer dúvida ou problema não deixe de entrar em contato pelo e-mail " . SITE_ADDR_EMAIL . ", ficamos a disposição!</p>
            <p><em>Atenciosamente " . SITE_NAME . "!</em></p>
        ";
        $MailContent = '
            <table width="550" style="font-family: "Trebuchet MS", sans-serif;">
             <tr><td>
              <font face="Trebuchet MS" size="3">
               '.$MailBody.'
              </font>
              <p style="font-size: 0.875em;">
                ' . SITE_ADDR_NAME . '<br>Telefone: ' . SITE_ADDR_PHONE_A . '<br>E-mail: ' . SITE_ADDR_EMAIL . '<br><br>
               <a title="' . SITE_NAME . '" href="' . URL . '">' . SITE_NAME . '</a><br>
              </p>
              </td></tr>
            </table>
            <style>body, img{max-width: 550px !important; height: auto !important;} p{margin-botton: 15px 0 !important;}</style>';

        sendMail('CONTRATO FEDERALNETMÓVEL', $MailContent, SITE_ADDR_EMAIL, SITE_NAME, $read_user_view['user_email'], $read_user_view['user_nome']);
        $json_user = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<a href="javascript::" data-dismiss="modal" onclick="carrega_pagina(\'mmn_user\', \'index.php\');" class="btn btn-primary">Sair</a>'
        );
    }else{
        $json_user = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada, não foi encontrado o usuário!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }
    echo json_encode($json_user);
}
?>