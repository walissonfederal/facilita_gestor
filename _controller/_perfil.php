<?php
session_start();
ob_start();
require_once '../_class/Ferramenta.php';

if(isset($_POST['acao'])){
    $acao = addslashes($_POST['acao']);
}else{
    $acao = addslashes($_GET['acao']);
}

if($acao == 'update'){
    //RECUPERA O FORMULARIO
    $user_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($user_form['acao']);
    $senha = $user_form['user_senha'];
    unset($user_form['user_senha']);
    unset($user_form['id']);
    
    if(in_array('', $user_form)){
        $json_user = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }elseif(strlen($senha) < '6'){
		$json_user = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a senha ser menor que 6 caracteres!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
	}else{
        $uid = addslashes($_SESSION[VSESSION]['user_id']);
        unset($user_form['id']);
        if(trim($senha) != ''){
            $user_form['user_senha'] = md5($senha);
			$user_form['user_troca_senha'] = '1';
        }
        if(Update('user', $user_form, "WHERE user_id = '".$uid."'")){
            $json_user = array(
                'type' => 'success',
                'title' => 'Parabéns:',
                'msg' => 'Operação realizada com sucesso',
                'buttons' => '<a href="javascript::" data-dismiss="modal" onclick="carrega_pagina(\'perfil\', \'update.php\');" class="btn btn-primary">Sair</a>'
            );
        }else{
            $json_user = array(
                'type' => 'error',
                'title' => 'Erro:',
                'msg' => 'Ops, operação não pode ser finalizada devido ao login já está com outra pessoa!',
                'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
            );
        }
    }
    echo json_encode($json_user);
}elseif($acao == 'load_update'){
    $uid = $_SESSION[VSESSION]['user_id'];
    
    $read_user = Read('user', "WHERE user_id = '".$uid."'");
    if(NumQuery($read_user) > '0'){
        foreach($read_user as $read_user_view);
        unset($read_user_view['user_senha']);
        $json_user[] = $read_user_view;
    }else{
        $json_user = null;
    }
    echo json_encode($json_user);
}
?>