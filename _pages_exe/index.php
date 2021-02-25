<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <!-- Apple devices fullscreen -->
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <!-- Apple devices fullscreen -->
    <meta names="apple-mobile-web-app-status-bar-style" content="black-translucent" />

    <title>Projeto ERP - MJ</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="_boot/css/bootstrap.min.css">
    <!-- icheck -->
    <link rel="stylesheet" href="_boot/css/plugins/icheck/all.css">
    <!-- Theme CSS -->
    <link rel="stylesheet" href="_boot/css/style.css">
    <!-- Color CSS -->
    <link rel="stylesheet" href="_boot/css/themes.css">


    <!-- jQuery -->
    <script src="_boot/js/jquery.min.js"></script>

    <!-- Nice Scroll -->
    <script src="_boot/js/plugins/nicescroll/jquery.nicescroll.min.js"></script>
    <!-- Validation -->
    <script src="_boot/js/plugins/validation/jquery.validate.min.js"></script>
    <script src="_boot/js/plugins/validation/additional-methods.min.js"></script>
    <!-- icheck -->
    <script src="_boot/js/plugins/icheck/jquery.icheck.min.js"></script>
    <!-- Bootstrap -->
    <script src="_boot/js/bootstrap.min.js"></script>
    <script src="_boot/js/eakroko.js"></script>

    <!--[if lte IE 9]>
        <script src="_boot/js/plugins/placeholder/jquery.placeholder.min.js"></script>
        <script>
            $(document).ready(function() {
                $('input, textarea').placeholder();
            });
        </script>
    <![endif]-->


    <!-- Favicon -->
    <link rel="shortcut icon" href="_boot/img/favicon.ico" />
    <!-- Apple devices Homescreen icon -->
    <link rel="apple-touch-icon-precomposed" href="_boot/img/apple-touch-icon-precomposed.png" />

</head>

<body class='login'>
    <div class="wrapper">
        <h1>
                <a href="index.php">
                    <img src="_boot/img/logo-big.png" alt="" class='retina-ready' width="59" height="49">
                </a>
        </h1>
        <div class="login-body">
            <h2>LOGIN</h2>
            <form action="" method='get' class='form-validate' id="test">
                <div class="form-group">
                    <div class="email controls">
                        <input type="text" name='uemail' placeholder="Email" class='form-control' data-rule-required="true" data-rule-email="true">
                    </div>
                </div>
                <div class="form-group">
                    <div class="pw controls">
                        <input type="password" name="upw" placeholder="Senha" class='form-control' data-rule-required="true">
                    </div>
                </div>
                <div class="submit">
                    <div class="remember">
                        <input type="checkbox" name="remember" class='icheck-me' data-skin="square" data-color="blue" id="remember">
                        <label for="remember">Lembre-me</label>
                    </div>
                    <input type="submit" value="Logar" class='btn btn-primary'>
                </div>
            </form>
            <div class="forget">
                <a href="#">
                    <span>Perdeu sua senha?</span>
                </a>
            </div>
        </div>
    </div>
</body>
</html>
