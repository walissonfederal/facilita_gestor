<?php

require_once 'ConfigDados.php';

function Conn(){
    $ConexaoBase = mysqli_connect(HOST, USER, PASS, BASE);
    return $ConexaoBase;
}

?>

