<?php
session_start();
ob_start();
require_once '_class_mmn/Ferramenta.php';

if($_SESSION['id_verificar'] == '1'){
    $log_access['log_access_id_user'] = addslashes(trim(strip_tags($_POST['id_user'])));
    $log_access['log_access_day'] = date('Y-m-d');
    $log_access['log_access_date_time_start'] = date('Y-m-d H:i:s');
    $log_access['log_access_date_time_end'] = date('Y-m-d H:i:s', strtotime('+2 min'));
    Create('log_access', $log_access);
}else{
    $log_access_update['log_access_date_time_end'] = date('Y-m-d H:i:s', strtotime('+2 min'));
    Update('log_access', $log_access_update, "WHERE log_access_id_user = '".addslashes(trim(strip_tags($_POST['id_user'])))."' ORDER BY log_access_id DESC LIMIT 1");
}

unset($_SESSION['id_verificar']);
