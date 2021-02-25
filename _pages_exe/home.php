<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <!-- Apple devices fullscreen -->
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <!-- Apple devices fullscreen -->
    <meta names="apple-mobile-web-app-status-bar-style" content="black-translucent" />

    <title>FLAT - Blank Page</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="_boot/css/bootstrap.min.css">
    <!-- jQuery UI -->
    <link rel="stylesheet" href="_boot/css/plugins/jquery-ui/jquery-ui.min.css">
    <!-- Theme CSS -->
    <link rel="stylesheet" href="_boot/css/style.css">
    <!-- Color CSS -->
    <link rel="stylesheet" href="_boot/css/themes.css">


    <!-- jQuery -->
    <script src="_boot/js/jquery.min.js"></script>
    <!-- Nice Scroll -->
    <script src="_boot/js/plugins/nicescroll/jquery.nicescroll.min.js"></script>
    <!-- jQuery UI -->
    <script src="_boot/js/plugins/jquery-ui/jquery-ui.js"></script>
    <!-- slimScroll -->
    <script src="_boot/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <!-- Bootstrap -->
    <script src="_boot/js/bootstrap.min.js"></script>
    <!-- Form -->
    <script src="_boot/js/plugins/form/jquery.form.min.js"></script>

    <!-- Theme framework -->
    <script src="_boot/js/eakroko.min.js"></script>
    <!-- Theme scripts -->
    <script src="_boot/js/application.min.js"></script>
    <!-- Just for demonstration -->
    <script src="_boot/js/demonstration.min.js"></script>

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

<body>
    <div id="navigation">
        <div class="container-fluid">
            <a href="#" id="brand">FLAT</a>
            <ul class='main-nav'>
                <li>
                    <a href="index-2.html">
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="#" data-toggle="dropdown" class='dropdown-toggle'>
                        <span>Layouts</span>
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="layouts-fixed-topside.html">Fixed topbar and sidebar</a>
                        </li>
                        <li class='dropdown-submenu'>
                            <a href="#">Mobile sidebar</a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="layouts-mobile-slide.html">Slide</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
            </ul>
            <div class="user">
                <ul class="icon-nav">
                    <li class='dropdown'>
                        <a href="#" class='dropdown-toggle' data-toggle="dropdown">
                            <i class="fa fa-envelope"></i>
                            <span class="label label-lightred">1</span>
                        </a>
                        <ul class="dropdown-menu pull-right message-ul">
                            <li>
                                <a href="#">
                                    <img src="_boot/img/demo/user-3.jpg" alt="">
                                    <div class="details">
                                        <div class="name">Bob Doe</div>
                                        <div class="message">
                                            Excepteur Duis magna dolor!
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="components-messages.html" class='more-messages'>Go to Message center
                                    <i class="fa fa-arrow-right"></i>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
                <div class="dropdown">
                        <a href="#" class='dropdown-toggle' data-toggle="dropdown">John Doe
                            <img src="_boot/img/demo/user-avatar.jpg" alt="">
                        </a>
                        <ul class="dropdown-menu pull-right">
                            <li>
                                <a href="more-userprofile.html">Edit profile</a>
                            </li>
                            <li>
                                <a href="#">Account settings</a>
                            </li>
                            <li>
                                <a href="more-login.html">Sign out</a>
                            </li>
                        </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid nav-hidden" id="content">
        <div id="main">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="box">
                            <div class="box-title">
                                <h3>
                                    Basic Widget
                                </h3>
                            </div>
                            <div class="box-content">
                                Content
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
