<?php
    session_start();
    ob_start();
    require_once '_class/Ferramenta.php';
    if(empty($_SESSION[VSESSION])){
        header("Location: index.php");
    }
    $pasta      = addslashes($_GET['model']);
    $arquivo    = addslashes($_GET['pg']).'.php';
    $id_nivel   = $_SESSION[VSESSION]['user_id_nivel'];
    
    
    
    $read_permissao = ReadComposta("SELECT pagina.pagina_arquivo,
                                        pagina.pagina_modulo,
                                        pagina.pagina_id,
                                        permissao_page.permissao_page_id_nivel,
                                        permissao_page.permissao_page_id_page
                                    FROM pagina
                                    INNER JOIN permissao_page
                                    ON permissao_page.permissao_page_id_page = pagina.pagina_id
                                    WHERE pagina.pagina_arquivo = '".$arquivo."'
                                    AND pagina.pagina_modulo = '".$pasta."'
                                    AND permissao_page.permissao_page_id_nivel = '".$id_nivel."'");
    if(NumQuery($read_permissao) == '0'){
        //echo 'sem permissão';
        echo "<script>alert('Opsss, sem premissão');</script>";
        echo "<script>window.location = 'index.php'</script>";
        //header("Location: index.php");
    }
    unset($_SESSION['orcamento_venda']);
    unset($_SESSION['orcamento_venda_valor_unitario']);
    unset($_SESSION['orcamento_venda_forma_pagamento']);
    unset($_SESSION['orcamento_venda_forma_pagamento_data']);
    unset($_SESSION['orcamento_venda_forma_pagamento_obs']);
    unset($_SESSION['orcamento_venda_forma_pagamento_valor']);
    unset($_SESSION['orcamento_venda_forma_pagamento_tipo']);
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <!-- Apple devices fullscreen -->
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <!-- Apple devices fullscreen -->
    <meta names="apple-mobile-web-app-status-bar-style" content="black-translucent" />

    <title>FederalGestor</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="_boot/css/bootstrap.min.css">
    <!-- jQuery UI -->
    <link rel="stylesheet" href="_boot/css/plugins/jquery-ui/jquery-ui.min.css">
    <link rel="stylesheet" href="_boot/css/plugins/jquery-ui/smoothness/jquery-ui.html">
    <link rel="stylesheet" href="_boot/css/plugins/jquery-ui/smoothness/jquery.ui.theme.html">
    <!-- Notify -->
    <link rel="stylesheet" href="_boot/css/plugins/gritter/jquery.gritter.css">
    <!-- Theme CSS -->
    <link rel="stylesheet" href="_boot/css/style.css">
    <!-- Color CSS -->
    <link rel="stylesheet" href="_boot/css/themes.css">
    <!-- timepicker -->
    <link rel="stylesheet" href="_boot/css/plugins/timepicker/bootstrap-timepicker.min.css">
    <!-- colorpicker -->
    <link rel="stylesheet" href="_boot/css/plugins/colorpicker/colorpicker.css">
    <!-- Datepicker -->
    <link rel="stylesheet" href="_boot/css/plugins/datepicker/datepicker.css">
    <!-- Daterangepicker -->
    <link rel="stylesheet" href="_boot/css/plugins/daterangepicker/daterangepicker.css">
    
    <link rel="stylesheet" href="_boot/css/plugins/icheck/all.css">


    <!-- jQuery -->
    <script src="_boot/js/jquery.min.js"></script>
    <script src="_boot/js/jquery.form.js"></script>
    <!-- Nice Scroll -->
    <script src="_boot/js/plugins/nicescroll/jquery.nicescroll.min.js"></script>
    <!-- jQuery UI -->
    <script src="_boot/js/plugins/jquery-ui/jquery-ui.js"></script>
    <!-- imagesLoaded -->
    <script src="_boot/js/plugins/imagesLoaded/jquery.imagesloaded.min.js"></script>
    <!-- slimScroll -->
    <script src="_boot/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <!-- Bootstrap -->
    <script src="_boot/js/bootstrap.min.js"></script>
    <!-- Form -->
    <script src="_boot/js/plugins/form/jquery.form.min.js"></script>
    <script src="_boot/js/jquery.maskMoney.js"></script>
    <!-- Datepicker -->
    <script src="_boot/js/plugins/datepicker/bootstrap-datepicker.js"></script>
    <!-- Daterangepicker -->
    <script src="_boot/js/plugins/daterangepicker/moment.min.js"></script>
    <script src="_boot/js/plugins/daterangepicker/daterangepicker.js"></script>
    <!-- Timepicker -->
    <script src="_boot/js/plugins/timepicker/bootstrap-timepicker.min.js"></script>
    <!-- Colorpicker -->
    <script src="_boot/js/plugins/colorpicker/bootstrap-colorpicker.js"></script>

    <!-- Theme framework -->
    <script src="_boot/js/eakroko.min.js"></script>
    <!-- Theme scripts -->
    <script src="_boot/js/application.min.js"></script>
    <!-- Just for demonstration -->
    <script src="_boot/js/demonstration.min.js"></script>
    <!-- jQuery UI -->
    <script src="_boot/js/plugins/jquery-ui/jquery.ui.core.min.html"></script>
    <script src="_boot/js/plugins/jquery-ui/jquery.ui.widget.min.html"></script>
    <script src="_boot/js/plugins/jquery-ui/jquery.ui.mouse.min.html"></script>
    <script src="_boot/js/plugins/jquery-ui/jquery.ui.resizable.min.html"></script>
    <script src="_boot/js/plugins/jquery-ui/jquery.ui.sortable.min.html"></script>
    <!-- Bootbox -->
    <script src="_boot/js/plugins/bootbox/jquery.bootbox.js"></script>
    <!-- Notify -->
    <script src="_boot/js/plugins/gritter/jquery.gritter.min.js"></script>
    <!-- multi select -->
    <link rel="stylesheet" href="_boot/css/plugins/multiselect/multi-select.css">
    <script src="_boot/js/plugins/multiselect/jquery.multi-select.js"></script>
    <script src="_boot/jquery.blockUI.js"></script>

    <!--[if lte IE 9]>
        <script src="_boot/js/plugins/placeholder/jquery.placeholder.min.js"></script>
        <script>
            $(document).ready(function() {
                $('input, textarea').placeholder();
            });
        </script>
    <![endif]-->

    <!-- Favicon -->
    <!--<link rel="shortcut icon" href="_boot/img/favicon.ico" />-->
    <!-- Apple devices Homescreen icon -->
    <link rel="apple-touch-icon-precomposed" href="_boot/img/apple-touch-icon-precomposed.png" />
    
    <script>
        /*function load_in(){
            $('.load').fadeIn("fast");
        }
        function load_out(){
            $('.load').fadeOut("fast");
        }*/
        function trim(str) {
            return str.replace(/^\s+|\s+$/g,"");
        }
        $(document).ajaxStop($.unblockUI);
        function load_in() {
            $.blockUI({message: '<H1><img src="_img/load.gif" /></H1><H1></H1>'});
        }
        function load_out() {
            $.unblockUI();
        }
        function notificacao_mmn() {
            var acao = "&acao=notificacao";
        
            $.ajax({
                type: 'POST',
                url: "_controller/_mmn_ticket.php",
                data: acao,
                beforeSend: load_in(),
                success: function (data) {
                    load_out();
                    $(".load_ticket_mmn").html(data);
                }
            });
        }
        $(function(){
           notificacao_mmn(); 
        });
    </script>
    
    <!-- tabulator -->
    <script type="text/javascript" src="_boot/tabulator-master/tabulator.js"></script>
    <link rel="stylesheet" href="_boot/tabulator-master/tabulator.css">
    
    <script type="text/javascript" src="_boot/js/jquery.priceformat.js"></script>
    <script src="_boot/js/jquery.mask.js"></script>
    <script src="_boot/js/jquery.mask.min.js"></script>
</head>
<body class="theme-grey" data-theme="theme-grey">