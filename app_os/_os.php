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

if($acao == 'send_download'):
    $user_id = addslashes(strip_tags(trim($_POST['user_id'])));

    $read_os = Read('os', "WHERE os_id_user = '".$user_id."' AND os_status = '0'");
    if(NumQuery($read_os) > '0'):
        foreach($read_os as $read_os_view):
            $jSON['data'][] = $read_os_view;
        endforeach;
    endif;
    echo json_encode($jSON);
endif;