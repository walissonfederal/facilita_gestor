<?php
session_start();
ob_start();
require_once '_class_mmn/Ferramenta.php';
function tirarAcentos($string){
    return strtoupper(preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/"),explode(" ","a A e E i I o O u U n N"),$string));
}

$read_user = Read('user', "ORDER BY user_nome ASC");
if(NumQuery($read_user) > '0'){
    foreach($read_user as $read_user_view){
        $update_user['user_nome'] = tirarAcentos($read_user_view['user_nome']);
        Update('user', $update_user, "WHERE user_id = '".$read_user_view['user_id']."'");
    }
}
