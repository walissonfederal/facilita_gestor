<?php include_once 'Header.php';?>
<?php include_once 'Menu.php';?>
<div class="container-fluid nav-hidden carregar_paginas" id="content">
<?php
    function QueryString($key) {
        return isset($_GET[$key]) ? $_GET[$key] : null;
    }
    $Pg = QueryString('pg');
    $Pasta = addslashes($_GET['model']);
    if (is_file('view/'.$Pasta.'/' . $Pg . '.php')) {
	include 'view/'.$Pasta.'/' . $Pg . '.php';
    } else {
	include 'view/error/404.php';
    }
?> 
</div>
<?php include_once 'Footer.php';?>