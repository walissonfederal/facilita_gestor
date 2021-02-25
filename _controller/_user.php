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
        $order_by = "ORDER BY user_id DESC";
    }else{
        
        $sort       = addslashes($_GET['sort']);
        $sort_dir   = addslashes($_GET['sort_dir']);
        $order_by   = "ORDER BY ".$sort." ".$sort_dir."";
    }
    
    $read_user_paginator = ReadComposta("SELECT user_id FROM user WHERE user_id != ''");
    $read_user = Read('user', "WHERE user_id != '' ".$order_by." LIMIT $inicio,$maximo");
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
}elseif($acao == 'create'){
    $user_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($user_form['acao']);
    
    $read_user = Read('user', "WHERE user_login = '".$user_form['user_login']."' OR user_email = '".$user_form['user_email']."'");
    
    if($user_form['user_nome'] == '' || $user_form['user_login'] == '' || $user_form['user_senha'] == '' || $user_form['user_email'] == '' || $user_form['user_id_nivel'] == '' || $user_form['user_id_caixa'] == ''){
        $data['sucesso'] = false;
        $data['msg'] = 'Existem campos sem preencher!';
    }else{
    
        $arquivo = $_FILES['arquivo'];

        $tipos = array('jpg', 'png');

        $enviar = uploadFile($arquivo, '../_uploads/'.GetEmpresa('empresa_nome_pasta').'/user/', $tipos);

        $data['sucesso'] = false;
        $user_form['user_identificador'] = md5(date('Y-m-d').rand(9,9999999));
        $user_form['user_foto'] = $enviar['caminho'];
        if($enviar['erro'] == 'Arquivo nao setado'){    
            if(NumQuery($read_user) > '0'){
                $data['msg'] = 'Já existe um login';
            }else{
                $data['sucesso'] = true;
                $data['msg'] = 'Operação realizada com sucesso';
                unset($user_form['arquivo']);
                Create('user', $user_form);
            }
        }elseif(NumQuery($read_user) > '0'){
            $data['msg'] = 'Já existe um login';
        }elseif($enviar['erro']){
            $data['msg'] = $enviar['erro'];
        }else{
            $data['sucesso'] = true;
				
            $data['msg'] = $enviar['caminho'];
            unset($user_form['arquivo']);
			$senha = $user_form['user_senha'];
			$user_form['user_senha'] = md5($senha);
			$user_form['user_code'] = base64_encode($senha);
			$user_form['user_troca_senha'] = '0';
            Create('user', $user_form);
        }
    }
    echo json_encode($data);
}elseif($acao == 'update'){
    $user_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($user_form['acao']);
    
    if($user_form['user_senha'] == ''){
        unset($user_form['user_senha']);
    }else{
		$senha = $user_form['user_senha'];
		$user_form['user_senha'] = md5($senha);
		$user_form['user_code'] = base64_encode($senha);
		$user_form['user_troca_senha'] = '1';
	}
    
    $id = addslashes($_GET['id']);
    
    $read_user = Read('user', "WHERE (user_login = '".$user_form['user_login']."' OR user_email = '".$user_form['user_email']."') AND user_id != '".$id."' ");
    
    if($user_form['user_nome'] == '' || $user_form['user_login'] == '' || $user_form['user_email'] == '' || $user_form['user_id_nivel'] == '' || $user_form['user_id_caixa'] == ''){
        $data['sucesso'] = false;
        $data['msg'] = 'Existem campos sem preencher!';
    }else{
    
        $arquivo = $_FILES['arquivo'];

        $tipos = array('jpg', 'png');

        $enviar = uploadFile($arquivo, '../_uploads/'.GetEmpresa('empresa_nome_pasta').'/user/', $tipos);

        $data['sucesso'] = false;
        if($enviar['erro'] == 'Arquivo nao setado'){    
            if(NumQuery($read_user) > '0'){
                $data['msg'] = 'Já existe um login';
            }else{
                $data['sucesso'] = true;
                $data['msg'] = 'Operação realizada com sucesso';
                unset($user_form['arquivo']);
                Update('user', $user_form, "WHERE user_id = '".$id."'");
            }
        }elseif(NumQuery($read_user) > '0'){
            $data['msg'] = 'Já existe um login';
        }elseif($enviar['erro']){
            $data['msg'] = $enviar['erro'];
        }else{
            $data['sucesso'] = true;

            $data['msg'] = $enviar['caminho'];
            unset($user_form['arquivo']);
            $foto_antiga = GetDados('user', $id, 'user_id', 'user_foto');
            if($foto_antiga != ''){
                unlink($foto_antiga);
            }
            $user_form['user_foto'] = $enviar['caminho'];
            Update('user', $user_form, "WHERE user_id = '".$id."'");
        }
    }
    echo json_encode($data);
}elseif($acao == 'load_update'){
    $uid = addslashes($_POST['id']);
    
    $read_user = Read('user', "WHERE user_id = '".$uid."'");
    if(NumQuery($read_user) > '0'){
        foreach($read_user as $read_user_view);
        unset($read_user_view['user_senha']);
        $read_user_view['user_foto'] = substr($read_user_view['user_foto'],3,300);
        $json_user[] = $read_user_view;
    }else{
        $json_user = null;
    }
    echo json_encode($json_user);
}elseif($acao == 'load_user'){
    $term = addslashes($_GET['term']);
    $id_type = addslashes($_GET['type_tecnico']);
    
    if($id_type == 'true'){
        $sql_user = "AND user_tipo_tecnico = '1'";
    }else{
        $sql_user = "";
    }
    
    $read_user_load = Read('user', "WHERE user_nome LIKE '%".$term."%' {$sql_user} ORDER BY user_nome ASC");
    if(NumQuery($read_user_load) > '0'){
        $json_user = '[';
        foreach($read_user_load as $read_user_load_view){
            $json_user .= '{"label":"'.$read_user_load_view['user_nome'].'","value":"'.$read_user_load_view['user_id'].'"},';
        }
        $json_user = substr($json_user, 0,-1);
        $json_user .= ']';
    }else{
        $json_user = array(
            array( "label" => '', "value" => '' ),
        );
    }
    echo $json_user;
}elseif($acao == 'load_user_id'){
    $uid = addslashes($_POST['id']);
    $id_type = addslashes($_POST['type_tecnico']);
    
    if($id_type == 'true'){
        $sql_user = "AND user_tipo_tecnico = '1'";
    }else{
        $sql_user = "";
    }
    
    $read_user_load_id = Read('user', "WHERE user_id = '".$uid."' {$sql_user} ORDER BY user_nome ASC");
    if(NumQuery($read_user_load_id) > '0'){
        $json_user = '[';
        foreach($read_user_load_id as $read_user_load_id_view){
            $json_user .= '{"label":"'.$read_user_load_id_view['user_nome'].'","value":"'.$read_user_load_id_view['user_id'].'"},';
        }
        $json_user = substr($json_user, 0,-1);
        $json_user .= ']';
    }else{
        $json_user = '[';
            $json_user .= '{"label":"","value":""}';
        $json_user .= ']';
    }
    echo $json_user;
}elseif($acao == 'load_responsavel'){
    $read_user = Read('user', "WHERE user_ticket = '1' ORDER BY user_nome ASC");
    if(NumQuery($read_user) > '0'){
        foreach($read_user as $read_user_view){
            $json_user["data"][] = $read_user_view;
        }
        echo json_encode($json_user);
    }
}elseif($acao == 'load_user_msg'){
    $id_user = $_SESSION[VSESSION]['user_id'];
    $data    = date('Y-m-d');
    
    $read_user_msg = Read('user_msg', "WHERE user_msg_id_user = '".$id_user."' AND user_msg_data = '".$data."' AND user_msg_status = '0'");
    if(NumQuery($read_user_msg) > '0'){
        foreach($read_user_msg as $read_user_msg_view);
        $json_retorno = array(
            'type' => 'success',
            'title' => 'Notificação:',
            'msg' => $read_user_msg_view['user_msg_descricao'],
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button><button type="button" class="btn btn-default" data-dismiss="modal" onclick="user_msg_update('.$read_user_msg_view['user_msg_id'].');">Fechar e não mostrar mais</button>'
        );
    }else{
        $json_retorno = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }
    echo json_encode($json_retorno);
}elseif($acao == 'update_user_msg'){
    $get_id = addslashes(trim($_POST['id']));
    
    $UpDateUserMsg['user_msg_status'] = '1';
    Update('user_msg', $UpDateUserMsg, "WHERE user_msg_id = '".$get_id."'");
}elseif($acao == 'create_user_msg'){
    $CreateMsg['user_msg_id_user'] = addslashes(trim(strip_tags($_POST['id_usuario'])));
    $CreateMsg['user_msg_data']    = addslashes(trim(strip_tags($_POST['data'])));
    $CreateMsg['user_msg_descricao'] = addslashes($_POST['descricao']);
    $CreateMsg['user_msg_status']    = '0';
    Create('user_msg', $CreateMsg);
}
?>