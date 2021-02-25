<?php
header('Access-Control-Allow-Origin: *');
session_start();
ob_start();
require_once '../_class/Ferramenta.php';

if(isset($_POST['acao'])):
    $acao = addslashes($_POST['acao']);
else:
    $acao = addslashes($_GET['acao']);
endif;

if($acao == 'send_login'):
    $login = addslashes(strip_tags(trim($_POST['login'])));
    $senha = addslashes(strip_tags(trim($_POST['senha'])));
    $read_login = Read('user', "WHERE user_login = '".$login."' AND user_senha = '".$senha."' LIMIT 1");
    if(NumQuery($read_login) > '0'):
        foreach($read_login as $read_login_view):
            $jSON['type'] = 'success';
            $jSON['msg'] = 'Operação realizada';
            $jSON['id_user'] = $read_login_view['user_id'];
        endforeach;
    else:
        $jSON['type'] = 'error';
        $jSON['msg'] = 'Login ou senha inválidos';
    endif;
    echo json_encode($jSON);
endif;
