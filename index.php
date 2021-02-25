<?php
session_start();
ob_start();
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

        <title>FederalGestor22			</title>

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
        <!--<link rel="shortcut icon" href="_boot/img/favicon.ico" />-->
        <!-- Apple devices Homescreen icon -->
        <link rel="apple-touch-icon-precomposed" href="_boot/img/apple-touch-icon-precomposed.png" />

    </head>

    <body class='login theme-grey' data-theme="theme-grey">
        <div class="wrapper">
            <div class="login-body">
                <h2>LOGIN FACILITA</h2>


                <?php
                $iphone = strpos($_SERVER['HTTP_USER_AGENT'], "iPhone");
                $ipad = strpos($_SERVER['HTTP_USER_AGENT'], "iPad");
                $android = strpos($_SERVER['HTTP_USER_AGENT'], "Android");
                $palmpre = strpos($_SERVER['HTTP_USER_AGENT'], "webOS");
                $berry = strpos($_SERVER['HTTP_USER_AGENT'], "BlackBerry");
                $ipod = strpos($_SERVER['HTTP_USER_AGENT'], "iPod");
                $symbian = strpos($_SERVER['HTTP_USER_AGENT'], "Symbian");

                $get_entidade = addslashes($_GET['entidade']);
                $get_login = addslashes($_GET['login']);
                $get_senha = addslashes($_GET['senha']);
                if (isset($_POST['sendLogin'])) {
                    $uemail = addslashes($_POST['uemail']);
                    $upw = addslashes(md5($_POST['upw']));
                    $ueentidade = addslashes($_POST['ueentidade']);
                    $_SESSION['BASE_ENTIDADE'] = $ueentidade;

                    require_once '_class/Ferramenta.php';

                    $readLogin = Read('user', "WHERE user_login = '" . $uemail . "' AND user_senha = '" . $upw . "' AND user_status = '0'");
                    if (NumQuery($readLogin) > '0') {
                        foreach ($readLogin as $readLoginView)
                            ;
                        $_SESSION[VSESSION] = $readLoginView;

                        $id_nivel = $readLoginView['user_id_nivel'];
                        /* if ($iphone || $ipad || $android || $palmpre || $ipod || $berry || $symbian == true) {
                          $menu_session = '';
                          $menu_session .= '<ul class="main-nav">';
                          if (GetPermMenu($id_nivel, '1')) {
                          $menu_session .= '<li>
                          <a href="Home.php?model=home&pg=home">
                          <span>Dashboard</span>
                          </a>
                          </li>';
                          }
                          if (GetPermMenu($id_nivel, '2')) {
                          $menu_session .= '<li>';
                          $menu_session .= '<a href="#" data-toggle="dropdown" class="dropdown-toggle">
                          <span>Cadastros</span>
                          <span class="caret"></span>
                          </a>';
                          $menu_session .= '<ul class="dropdown-menu">';
                          if (GetPermMenu($id_nivel, '3')) {
                          $menu_session .= '<li class="dropdown-submenu">';
                          $menu_session .= '<a href="#">Contatos</a>';
                          $menu_session .= '<ul class="dropdown-menu">';
                          if (GetPermMenu($id_nivel, '4')) {
                          $menu_session .= '<li><a href="Home.php?model=tipo-contato&pg=index" onclick="carrega_pagina(\'tipo-contato\', \'index.php\')">Tipo Contato</a></li>';
                          }
                          if (GetPermMenu($id_nivel, '5')) {
                          $menu_session .= '<li><a href="Home.php?model=regiao&pg=index" onclick="carrega_pagina(\'regiao\', \'index.php\')">Região</a></li>';
                          }
                          if (GetPermMenu($id_nivel, '6')) {
                          $menu_session .= '<li><a href="Home.php?model=rota&pg=index" onclick="carrega_pagina(\'rota\', \'index.php\')">Rota</a></li>';
                          }
                          if (GetPermMenu($id_nivel, '7')) {
                          $menu_session .= '<li><a href="Home.php?model=contato&pg=index" onclick="carrega_pagina(\'contato\', \'index.php\')">Contato</a></li>';
                          }
                          $menu_session .= '</ul>';
                          $menu_session .= '</li>';
                          }
                          if (GetPermMenu($id_nivel, '8')) {
                          $menu_session .= '<li class="dropdown-submenu">';
                          $menu_session .= '<a href="#">Produtos</a>';
                          $menu_session .= '<ul class="dropdown-menu">';
                          if (GetPermMenu($id_nivel, '9')) {
                          $menu_session .= '<li><a href="Home.php?model=categoria&pg=index" onclick="carrega_pagina(\'categoria\', \'index.php\');">Categoria</a></li>';
                          }
                          if (GetPermMenu($id_nivel, '10')) {
                          $menu_session .= '<li><a href="Home.php?model=sub-categoria&pg=index" onclick="carrega_pagina(\'sub-categoria\', \'index.php\');">SubCategoria</a></li>';
                          }
                          if (GetPermMenu($id_nivel, '11')) {
                          $menu_session .= '<li><a href="Home.php?model=produto&pg=index" onclick="carrega_pagina(\'produto\', \'index.php\');">Produto</a></li>';
                          }
                          if (GetPermMenu($id_nivel, '12')) {
                          $menu_session .= '<li><a href="Home.php?model=tributacao&pg=index" onclick="carrega_pagina(\'tributacao\', \'index.php\');">Tributação</a></li>';
                          }
                          $menu_session .= '</ul>';
                          $menu_session .= '</li>';
                          }
                          if (GetPermMenu($id_nivel, '13')) {
                          $menu_session .= '<li class="dropdown-submenu">';
                          $menu_session .= '<a href="#">Financeiro</a>';
                          $menu_session .= '<ul class="dropdown-menu">';
                          if (GetPermMenu($id_nivel, '14')) {
                          $menu_session .= '<li><a href="Home.php?model=caixa&pg=index" onclick="carrega_pagina(\'caixa\', \'index.php\');">Caixa</a></li>';
                          }
                          if (GetPermMenu($id_nivel, '15')) {
                          $menu_session .= '<li><a href="Home.php?model=plano-conta&pg=index" onclick="carrega_pagina(\'plano-conta\', \'index.php\');">Plano de Contas</a></li>';
                          }
                          if (GetPermMenu($id_nivel, '16')) {
                          $menu_session .= '<li><a href="Home.php?model=tipo-documento&pg=index" onclick="carrega_pagina(\'tipo-documento\', \'index.php\');">Tipo de Documento</a></li>';
                          }
                          if (GetPermMenu($id_nivel, '17')) {
                          $menu_session .= '<li><a href="Home.php?model=forma-pagamento&pg=index" onclick="carrega_pagina(\'forma-pagamento\', \'index.php\');">Forma de Pagamento</a></li>';
                          }
                          $menu_session .= '</ul>';
                          $menu_session .= '</li>';
                          }
                          if (GetPermMenu($id_nivel, '18')) {
                          $menu_session .= '<li class="dropdown-submenu">';
                          $menu_session .= '<a href="#">Perfil</a>';
                          $menu_session .= '<ul class="dropdown-menu">';
                          if (GetPermMenu($id_nivel, '19')) {
                          $menu_session .= '<li><a href="Home.php?model=user&pg=index" onclick="carrega_pagina(\'user\', \'index.php\');">Usuário</a></li>';
                          }
                          if (GetPermMenu($id_nivel, '20')) {
                          $menu_session .= '<li><a href="Home.php?model=nivel&pg=index" onclick="carrega_pagina(\'nivel\', \'index.php\');">Nível</a></li>';
                          }
                          $menu_session .= '</ul>';
                          $menu_session .= '</li>';
                          }
                          if (GetPermMenu($id_nivel, '77')) {
                          $menu_session .= '<li><a href="Home.php?model=vendedor_franquiado&pg=index" onclick="carrega_pagina(\'vendedor_franquiado\', \'index.php\');">Vendedor / Franquiado</a></li>';
                          }
                          if (GetPermMenu($id_nivel, '78')) {
                          $menu_session .= '<li><a href="Home.php?model=associado&pg=index" onclick="carrega_pagina(\'associado\', \'index.php\');">Associado</a></li>';
                          }
                          $menu_session .= '</ul>';
                          $menu_session .= '</li>';
                          }
                          if (GetPermMenu($id_nivel, '21')) {
                          $menu_session .= '<li>';
                          $menu_session .= '<a href="#" data-toggle="dropdown" class="dropdown-toggle">
                          <span>Operações</span>
                          <span class="caret"></span>
                          </a>';
                          $menu_session .= '<ul class="dropdown-menu">';
                          if (GetPermMenu($id_nivel, '22')) {
                          $menu_session .= '<li><a href="Home.php?model=orcamento&pg=index" onclick="carrega_pagina(\'orcamento\', \'index.php\');">Orçamentos</a></li>';
                          }
                          if (GetPermMenu($id_nivel, '23')) {
                          $menu_session .= '<li><a href="Home.php?model=venda&pg=index" onclick="carrega_pagina(\'venda\', \'index.php\');">Vendas</a></li>';
                          }
                          if (GetPermMenu($id_nivel, '24')) {
                          $menu_session .= '<li><a href="Home.php?model=os&pg=index" onclick="carrega_pagina(\'os\', \'index.php\');">Ordem Serviço</a></li>';
                          }
                          if (GetPermMenu($id_nivel, '67')) {
                          $menu_session .= '<li class="dropdown-submenu">';
                          $menu_session .= '<a href="#">Chips</a>';
                          $menu_session .= '<ul class="dropdown-menu">';
                          if (GetPermMenu($id_nivel, '68')) {
                          $menu_session .= '<li><a href="Home.php?model=pedido&pg=index" onclick="carrega_pagina(\'pedido\', \'index.php\');">Pedidos</a></li>';
                          }
                          if (GetPermMenu($id_nivel, '69')) {
                          $menu_session .= '<li><a href="Home.php?model=faturamento&pg=index" onclick="carrega_pagina(\'faturamento\', \'index.php\');">Faturamento</a></li>';
                          }
                          if (GetPermMenu($id_nivel, '79')) {
                          $menu_session .= '<li><a href="Home.php?model=consumo&pg=index" onclick="carrega_pagina(\'consumo\', \'index.php\');">Consumo Mensal SMS</a></li>';
                          }
                          if (GetPermMenu($id_nivel, '81')) {
                          $menu_session .= '<li><a href="Home.php?model=chip&pg=index" onclick="carrega_pagina(\'chip\', \'index.php\');">Informação Chip</a></li>';
                          }
                          $menu_session .= '</ul>';
                          $menu_session .= '</li>';
                          }
                          $menu_session .= '</ul>';
                          $menu_session .= '</li>';
                          }
                          if (GetPermMenu($id_nivel, '25')) {
                          $menu_session .= '<li>';
                          $menu_session .= '<a href="#" data-toggle="dropdown" class="dropdown-toggle">
                          <span>Financeiro</span>
                          <span class="caret"></span>
                          </a>';
                          $menu_session .= '<ul class="dropdown-menu">';
                          if (GetPermMenu($id_nivel, '26')) {
                          $menu_session .= '<li><a href="Home.php?model=financeiro&pg=index&OP=CP" onclick="carrega_pagina(\'financeiro\', \'index.php?OP=CP\');">Contas a Pagar</a></li>';
                          }
                          if (GetPermMenu($id_nivel, '27')) {
                          $menu_session .= '<li><a href="Home.php?model=financeiro&pg=index&OP=CR" onclick="carrega_pagina(\'financeiro\', \'index.php?OP=CR\');">Contas a Receber</a></li>';
                          }
                          if (GetPermMenu($id_nivel, '28')) {
                          $menu_session .= '<li><a href="Home.php?model=caixa-conta&pg=index" onclick="carrega_pagina(\'caixa-conta\', \'index.php\');">Caixa / Conta</a></li>';
                          }
                          if (GetPermMenu($id_nivel, '74')) {
                          $menu_session .= '<li class="dropdown-submenu">';
                          $menu_session .= '<a href="#">Assinaturas</a>';
                          $menu_session .= '<ul class="dropdown-menu">';
                          if (GetPermMenu($id_nivel, '75')) {
                          $menu_session .= '<li><a href="Home.php?model=plano-assinatura&pg=index" onclick="carrega_pagina(\'plano-assinatura\', \'index.php\');">Plano</a></li>';
                          }
                          if (GetPermMenu($id_nivel, '76')) {
                          $menu_session .= '<li><a href="Home.php?model=assinatura&pg=index" onclick="carrega_pagina(\'assinatura\', \'index.php\');">Assinatura</a></li>';
                          }
                          $menu_session .= '</ul>';
                          $menu_session .= '</li>';
                          }
                          $menu_session .= '</ul>';
                          $menu_session .= '</li>';
                          }
                          if (GetPermMenu($id_nivel, '29')) {
                          $menu_session .= '<li>';
                          $menu_session .= '<a href="#" data-toggle="dropdown" class="dropdown-toggle">
                          <span>CRM</span>
                          <span class="caret"></span>
                          </a>';
                          $menu_session .= '<ul class="dropdown-menu">';
                          if (GetPermMenu($id_nivel, '30')) {
                          $menu_session .= '<li><a href="Home.php?model=departamento&pg=index" onclick="carrega_pagina(\'departamento\', \'index.php\');">Departamento</a></li>';
                          }
                          if (GetPermMenu($id_nivel, '31')) {
                          $menu_session .= '<li><a href="Home.php?model=ticket&pg=index" onclick="carrega_pagina(\'ticket\', \'index.php\');">Ticket</a></li>';
                          }
                          $menu_session .= '</ul>';
                          $menu_session .= '</li>';
                          }
                          if (GetPermMenu($id_nivel, '32')) {
                          $menu_session .= '<li>';
                          $menu_session .= '<a href="#" data-toggle="dropdown" class="dropdown-toggle">
                          <span>Relatórios</span>
                          <span class="caret"></span>
                          </a>';
                          $menu_session .= '<ul class="dropdown-menu">';
                          if (GetPermMenu($id_nivel, '33')) {
                          $menu_session .= '<li class="dropdown-submenu">';
                          $menu_session .= '<a href="#">Cadastros</a>';
                          $menu_session .= '<ul class="dropdown-menu">';
                          if (GetPermMenu($id_nivel, '34')) {
                          $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'tipo-contato\', \'report.php\');">Tipo Contato</a></li>';
                          }
                          if (GetPermMenu($id_nivel, '35')) {
                          $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'regiao\', \'report.php\');">Região</a></li>';
                          }
                          if (GetPermMenu($id_nivel, '36')) {
                          $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'rota\', \'report.php\');">Rota</a></li>';
                          }
                          if (GetPermMenu($id_nivel, '37')) {
                          $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'contato\', \'report.php\');">Contato</a></li>';
                          }
                          if (GetPermMenu($id_nivel, '38')) {
                          $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'categoria\', \'report.php\');">Categoria</a></li>';
                          }
                          if (GetPermMenu($id_nivel, '39')) {
                          $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'sub-categoria\', \'report.php\');">SubCategoria</a></li>';
                          }
                          if (GetPermMenu($id_nivel, '40')) {
                          $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'produto\', \'report.php\');">Produto</a></li>';
                          }
                          if (GetPermMenu($id_nivel, '41')) {
                          $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'caixa\', \'report.php\');">Caixa</a></li>';
                          }
                          if (GetPermMenu($id_nivel, '42')) {
                          $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'plano-conta\', \'report.php\');">Plano de Contas</a></li>';
                          }
                          if (GetPermMenu($id_nivel, '43')) {
                          $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'tipo-documento\', \'report.php\');">Tipo de Documento</a></li>';
                          }
                          if (GetPermMenu($id_nivel, '44')) {
                          $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'forma-pagamento\', \'report.php\');">Forma de Pagamento</a></li>';
                          }
                          if (GetPermMenu($id_nivel, '45')) {
                          $menu_session .= '<li><a href="javascript::">Usuário</a></li>';
                          }
                          if (GetPermMenu($id_nivel, '46')) {
                          $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'nivel\', \'report.php\');">Nível</a></li>';
                          }
                          $menu_session .= '</ul>';
                          $menu_session .= '</li>';
                          }
                          if (GetPermMenu($id_nivel, '47')) {
                          $menu_session .= '<li class="dropdown-submenu">';
                          $menu_session .= '<a href="#">Financeiro</a>';
                          $menu_session .= '<ul class="dropdown-menu">';
                          if (GetPermMenu($id_nivel, '48')) {
                          $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'financeiro\', \'report.php?OP=CP\');">Contas a Pagar</a></li>';
                          }
                          if (GetPermMenu($id_nivel, '49')) {
                          $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'financeiro\', \'report.php?OP=CR\');">Contas a Receber</a></li>';
                          }
                          if (GetPermMenu($id_nivel, '50')) {
                          $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'financeiro\', \'report.php\');">Financeiro</a></li>';
                          }
                          if (GetPermMenu($id_nivel, '51')) {
                          $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'caixa-conta\', \'report.php\');">Fluxo Financeiro</a></li>';
                          }
                          if (GetPermMenu($id_nivel, '61')) {
                          $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'plano-conta\', \'report_financeiro.php\');">Plano de Contas</a></li>';
                          }
                          $menu_session .= '</ul>';
                          $menu_session .= '</li>';
                          }
                          $menu_session .= '</ul>';
                          $menu_session .= '</li>';
                          }
                          if (GetPermMenu($id_nivel, '52')) {
                          $menu_session .= '<li>';
                          $menu_session .= '<a href="#" data-toggle="dropdown" class="dropdown-toggle">
                          <span>Utilidades</span>
                          <span class="caret"></span>
                          </a>';
                          $menu_session .= '<ul class="dropdown-menu">';
                          if (GetPermMenu($id_nivel, '53')) {
                          $menu_session .= '<li><a href="Home.php?model=tratar-retorno&pg=index" onclick="carrega_pagina(\'tratar-retorno\', \'index.php\');">Tratar Retorno</a></li>';
                          }
                          if (GetPermMenu($id_nivel, '54')) {
                          $menu_session .= '<li><a href="Home.php?model=tratar-spc&pg=index" onclick="carrega_pagina(\'tratar-spc\', \'index.php\');">Tratar SPC</a></li>';
                          }
                          if (GetPermMenu($id_nivel, '55')) {
                          $menu_session .= '<li class="dropdown-submenu">';
                          $menu_session .= '<a href="#">Disco Virtual</a>';
                          $menu_session .= '<ul class="dropdown-menu">';
                          if (GetPermMenu($id_nivel, '56')) {
                          $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'disco-virtual\', \'spc.php\');">SPC</a></li>';
                          }
                          if (GetPermMenu($id_nivel, '57')) {
                          $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'disco-virtual\', \'remessa.php\');">Remessa</a></li>';
                          }
                          if (GetPermMenu($id_nivel, '58')) {
                          $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'disco-virtual\', \'retorno.php\');">Retorno</a></li>';
                          }
                          $menu_session .= '</ul>';
                          $menu_session .= '</li>';
                          }
                          if (GetPermMenu($id_nivel, '59')) {
                          $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'msg-financeiro\', \'index.php\');">Mensagem Financeiro</a></li>';
                          }
                          if (GetPermMenu($id_nivel, '60')) {
                          $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'campanha-mail\', \'index.php\');">Campanhas Emails</a></li>';
                          }
                          if (GetPermMenu($id_nivel, '80')) {
                          $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'agendamento\', \'index.php\');">Agendamento de Operações</a></li>';
                          }
                          if (GetPermMenu($id_nivel, '62')) {
                          $menu_session .= '<li class="dropdown-submenu">';
                          $menu_session .= '<a href="#">Diretorias</a>';
                          $menu_session .= '<ul class="dropdown-menu">';
                          if (GetPermMenu($id_nivel, '63')) {
                          $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'cargo\', \'index.php\');">Cargo</a></li>';
                          }
                          if (GetPermMenu($id_nivel, '64')) {
                          $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'pessoa\', \'index.php\');">Pessoa</a></li>';
                          }
                          if (GetPermMenu($id_nivel, '65')) {
                          $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'periodo\', \'index.php\');">Período</a></li>';
                          }
                          if (GetPermMenu($id_nivel, '66')) {
                          $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'diretoria\', \'index.php\');">Diretoria</a></li>';
                          }
                          $menu_session .= '</ul>';
                          $menu_session .= '</li>';
                          }
                          $menu_session .= '</ul>';
                          $menu_session .= '</li>';
                          }
                          if (GetPermMenu($id_nivel, '70')) {
                          $menu_session .= '<li>';
                          $menu_session .= '<a href="#" data-toggle="dropdown" class="dropdown-toggle">
                          <span>Gestão Contratos</span>
                          <span class="caret"></span>
                          </a>';
                          $menu_session .= '<ul class="dropdown-menu">';
                          if (GetPermMenu($id_nivel, '71')) {
                          $menu_session .= '<li><a href="Home.php?model=contrato&pg=index_rastreamento" onclick="carrega_pagina(\'contrato\', \'index_rastreamento.php\');">Rastreamento</a></li>';
                          }
                          if (GetPermMenu($id_nivel, '72')) {
                          $menu_session .= '<li><a href="Home.php?model=contrato&pg=index_monitoramento" onclick="carrega_pagina(\'contrato\', \'index_monitoramento.php\');">Monitoramento</a></li>';
                          }
                          if (GetPermMenu($id_nivel, '73')) {
                          $menu_session .= '<li><a href="Home.php?model=contrato&pg=index_chip" onclick="carrega_pagina(\'contrato\', \'index_chip.php\');">Chip</a></li>';
                          }
                          $menu_session .= '</ul>';
                          $menu_session .= '</li>';
                          }
                          $menu_session .= '</ul>';
                          } else {

                          } */
                        $menu_session = '';
                        $menu_session .= '<ul class="main-nav">';
                        if (GetPermMenu($id_nivel, '1')) {
                            $menu_session .= '<li>
														<a href="Home.php?model=home&pg=home">
															<span>Dashboard</span>
														</a>
													</li>';
                        }
                        if (GetPermMenu($id_nivel, '2')) {
                            $menu_session .= '<li>';
                            $menu_session .= '<a href="#" data-toggle="dropdown" class="dropdown-toggle">
															<span>Cadastros</span>
															<span class="caret"></span>
														</a>';
                            $menu_session .= '<ul class="dropdown-menu">';
                            if (GetPermMenu($id_nivel, '3')) {
                                $menu_session .= '<li class="dropdown-submenu">';
                                $menu_session .= '<a href="#">Contatos</a>';
                                $menu_session .= '<ul class="dropdown-menu">';
                                if (GetPermMenu($id_nivel, '4')) {
                                    $menu_session .= '<li><a href="javascript:carrega_pagina(\'tipo-contato\', \'index.php\')">Tipo Contato</a></li>';
                                }
                                if (GetPermMenu($id_nivel, '5')) {
                                    $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'regiao\', \'index.php\')">Região</a></li>';
                                }
                                if (GetPermMenu($id_nivel, '6')) {
                                    $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'rota\', \'index.php\')">Rota</a></li>';
                                }
                                if (GetPermMenu($id_nivel, '7')) {
                                    $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'contato\', \'index.php\')">Contato</a></li>';
                                }
                                $menu_session .= '</ul>';
                                $menu_session .= '</li>';
                            }
                            if (GetPermMenu($id_nivel, '8')) {
                                $menu_session .= '<li class="dropdown-submenu">';
                                $menu_session .= '<a href="#">Produtos</a>';
                                $menu_session .= '<ul class="dropdown-menu">';
                                if (GetPermMenu($id_nivel, '9')) {
                                    $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'categoria\', \'index.php\');">Categoria</a></li>';
                                }
                                if (GetPermMenu($id_nivel, '10')) {
                                    $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'sub-categoria\', \'index.php\');">SubCategoria</a></li>';
                                }
                                if (GetPermMenu($id_nivel, '11')) {
                                    $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'produto\', \'index.php\');">Produto</a></li>';
                                }
                                if (GetPermMenu($id_nivel, '12')) {
                                    $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'tributacao\', \'index.php\');">Tributação</a></li>';
                                }
                                $menu_session .= '</ul>';
                                $menu_session .= '</li>';
                            }
                            if (GetPermMenu($id_nivel, '13')) {
                                $menu_session .= '<li class="dropdown-submenu">';
                                $menu_session .= '<a href="#">Financeiro</a>';
                                $menu_session .= '<ul class="dropdown-menu">';
                                if (GetPermMenu($id_nivel, '14')) {
                                    $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'caixa\', \'index.php\');">Caixa</a></li>';
                                }
                                if (GetPermMenu($id_nivel, '15')) {
                                    $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'plano-conta\', \'index.php\');">Plano de Contas</a></li>';
                                }
                                if (GetPermMenu($id_nivel, '16')) {
                                    $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'tipo-documento\', \'index.php\');">Tipo de Documento</a></li>';
                                }
                                if (GetPermMenu($id_nivel, '17')) {
                                    $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'forma-pagamento\', \'index.php\');">Forma de Pagamento</a></li>';
                                }
                                $menu_session .= '</ul>';
                                $menu_session .= '</li>';
                            }
                            if (GetPermMenu($id_nivel, '18')) {
                                $menu_session .= '<li class="dropdown-submenu">';
                                $menu_session .= '<a href="#">Perfil</a>';
                                $menu_session .= '<ul class="dropdown-menu">';
                                if (GetPermMenu($id_nivel, '19')) {
                                    $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'user\', \'index.php\');">Usuário</a></li>';
                                }
                                if (GetPermMenu($id_nivel, '20')) {
                                    $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'nivel\', \'index.php\');">Nível</a></li>';
                                }
                                $menu_session .= '</ul>';
                                $menu_session .= '</li>';
                            }
                            if (GetPermMenu($id_nivel, '77')) {
                                $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'vendedor_franquiado\', \'index.php\');">Vendedor / Franquiado</a></li>';
                            }
                            if (GetPermMenu($id_nivel, '78')) {
                                $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'associado\', \'index.php\');">Associado</a></li>';
                            }
                            $menu_session .= '</ul>';
                            $menu_session .= '</li>';
                        }
                        if (GetPermMenu($id_nivel, '21')) {
                            $menu_session .= '<li>';
                            $menu_session .= '<a href="#" data-toggle="dropdown" class="dropdown-toggle">
															<span>Operações</span>
															<span class="caret"></span>
														</a>';
                            $menu_session .= '<ul class="dropdown-menu">';
                            if (GetPermMenu($id_nivel, '22')) {
                                $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'orcamento\', \'index.php\');">Orçamentos</a></li>';
                            }
                            if (GetPermMenu($id_nivel, '23')) {
                                $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'venda\', \'index.php\');">Vendas</a></li>';
                            }
                            if (GetPermMenu($id_nivel, '24')) {
                                $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'os\', \'index.php\');">Ordem Serviço</a></li>';
                            }
                            if (GetPermMenu($id_nivel, '67')) {
                                $menu_session .= '<li class="dropdown-submenu">';
                                $menu_session .= '<a href="#">Chips</a>';
                                $menu_session .= '<ul class="dropdown-menu">';
                                if (GetPermMenu($id_nivel, '68')) {
                                    $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'pedido\', \'index.php\');">Pedidos</a></li>';
                                }
                                if (GetPermMenu($id_nivel, '69')) {
                                    $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'faturamento\', \'index.php\');">Faturamento</a></li>';
                                }
                                if (GetPermMenu($id_nivel, '79')) {
                                    $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'consumo\', \'index.php\');">Consumo Mensal SMS</a></li>';
                                }
                                if (GetPermMenu($id_nivel, '81')) {
                                    $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'chip\', \'index.php\');">Informação Chip</a></li>';
                                }
                                $menu_session .= '</ul>';
                                $menu_session .= '</li>';
                            }
                            $menu_session .= '</ul>';
                            $menu_session .= '</li>';
                        }
                        if (GetPermMenu($id_nivel, '25')) {
                            $menu_session .= '<li>';
                            $menu_session .= '<a href="#" data-toggle="dropdown" class="dropdown-toggle">
															<span>Financeiro</span>
															<span class="caret"></span>
														</a>';
                            $menu_session .= '<ul class="dropdown-menu">';
                            if (GetPermMenu($id_nivel, '26')) {
                                $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'financeiro\', \'index.php?OP=CP\');">Contas a Pagar</a></li>';
                            }
                            if (GetPermMenu($id_nivel, '27')) {
                                $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'financeiro\', \'index.php?OP=CR\');">Contas a Receber</a></li>';
                            }
                            if (GetPermMenu($id_nivel, '28')) {
                                $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'caixa-conta\', \'index.php\');">Caixa / Conta</a></li>';
                            }
                            if (GetPermMenu($id_nivel, '74')) {
                                $menu_session .= '<li class="dropdown-submenu">';
                                $menu_session .= '<a href="#">Assinaturas</a>';
                                $menu_session .= '<ul class="dropdown-menu">';
                                if (GetPermMenu($id_nivel, '75')) {
                                    $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'plano-assinatura\', \'index.php\');">Plano</a></li>';
                                }
                                if (GetPermMenu($id_nivel, '76')) {
                                    $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'assinatura\', \'index.php\');">Assinatura</a></li>';
                                }
                                $menu_session .= '</ul>';
                                $menu_session .= '</li>';
                            }
                            $menu_session .= '</ul>';
                            $menu_session .= '</li>';
                        }
                        if (GetPermMenu($id_nivel, '29')) {
                            $menu_session .= '<li>';
                            $menu_session .= '<a href="#" data-toggle="dropdown" class="dropdown-toggle">
															<span>CRM</span>
															<span class="caret"></span>
														</a>';
                            $menu_session .= '<ul class="dropdown-menu">';
                            if (GetPermMenu($id_nivel, '30')) {
                                $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'departamento\', \'index.php\');">Departamento</a></li>';
                            }
                            if (GetPermMenu($id_nivel, '31')) {
                                $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'ticket\', \'index.php\');">Ticket</a></li>';
                            }
                            $menu_session .= '</ul>';
                            $menu_session .= '</li>';
                        }
                        if (GetPermMenu($id_nivel, '32')) {
                            $menu_session .= '<li>';
                            $menu_session .= '<a href="#" data-toggle="dropdown" class="dropdown-toggle">
															<span>Relatórios</span>
															<span class="caret"></span>
														</a>';
                            $menu_session .= '<ul class="dropdown-menu">';
                            if (GetPermMenu($id_nivel, '33')) {
                                $menu_session .= '<li class="dropdown-submenu">';
                                $menu_session .= '<a href="#">Cadastros</a>';
                                $menu_session .= '<ul class="dropdown-menu">';
                                if (GetPermMenu($id_nivel, '34')) {
                                    $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'tipo-contato\', \'report.php\');">Tipo Contato</a></li>';
                                }
                                if (GetPermMenu($id_nivel, '35')) {
                                    $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'regiao\', \'report.php\');">Região</a></li>';
                                }
                                if (GetPermMenu($id_nivel, '36')) {
                                    $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'rota\', \'report.php\');">Rota</a></li>';
                                }
                                if (GetPermMenu($id_nivel, '37')) {
                                    $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'contato\', \'report.php\');">Contato</a></li>';
                                }
                                if (GetPermMenu($id_nivel, '38')) {
                                    $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'categoria\', \'report.php\');">Categoria</a></li>';
                                }
                                if (GetPermMenu($id_nivel, '39')) {
                                    $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'sub-categoria\', \'report.php\');">SubCategoria</a></li>';
                                }
                                if (GetPermMenu($id_nivel, '40')) {
                                    $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'produto\', \'report.php\');">Produto</a></li>';
                                }
                                if (GetPermMenu($id_nivel, '41')) {
                                    $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'caixa\', \'report.php\');">Caixa</a></li>';
                                }
                                if (GetPermMenu($id_nivel, '42')) {
                                    $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'plano-conta\', \'report.php\');">Plano de Contas</a></li>';
                                }
                                if (GetPermMenu($id_nivel, '43')) {
                                    $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'tipo-documento\', \'report.php\');">Tipo de Documento</a></li>';
                                }
                                if (GetPermMenu($id_nivel, '44')) {
                                    $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'forma-pagamento\', \'report.php\');">Forma de Pagamento</a></li>';
                                }
                                if (GetPermMenu($id_nivel, '45')) {
                                    $menu_session .= '<li><a href="javascript::">Usuário</a></li>';
                                }
                                if (GetPermMenu($id_nivel, '46')) {
                                    $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'nivel\', \'report.php\');">Nível</a></li>';
                                }
                                $menu_session .= '</ul>';
                                $menu_session .= '</li>';
                            }
                            if (GetPermMenu($id_nivel, '47')) {
                                $menu_session .= '<li class="dropdown-submenu">';
                                $menu_session .= '<a href="#">Financeiro</a>';
                                $menu_session .= '<ul class="dropdown-menu">';
                                if (GetPermMenu($id_nivel, '48')) {
                                    $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'financeiro\', \'report.php?OP=CP\');">Contas a Pagar</a></li>';
                                }
                                if (GetPermMenu($id_nivel, '49')) {
                                    $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'financeiro\', \'report.php?OP=CR\');">Contas a Receber</a></li>';
                                }
                                if (GetPermMenu($id_nivel, '50')) {
                                    $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'financeiro\', \'report.php\');">Financeiro</a></li>';
                                }
                                if (GetPermMenu($id_nivel, '51')) {
                                    $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'caixa-conta\', \'report.php\');">Fluxo Financeiro</a></li>';
                                }
                                if (GetPermMenu($id_nivel, '61')) {
                                    $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'plano-conta\', \'report_financeiro.php\');">Plano de Contas</a></li>';
                                }
                                $menu_session .= '</ul>';
                                $menu_session .= '</li>';
                            }
                            if (GetPermMenu($id_nivel, '88')) {
                                $menu_session .= '<li class="dropdown-submenu">';
                                $menu_session .= '<a href="#">Chips</a>';
                                $menu_session .= '<ul class="dropdown-menu">';
                                if (GetPermMenu($id_nivel, '89')) {
                                    $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'chip\', \'report.php?OP=CI\');">Chip Disponíveis</a></li>';
                                }
                                if (GetPermMenu($id_nivel, '90')) {
                                    $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'chip\', \'report.php?OP=CD\');">Chip Indisponíveis</a></li>';
                                }
                                if (GetPermMenu($id_nivel, '91')) {
                                    $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'chip\', \'report.php\');">Chip</a></li>';
                                }
                                $menu_session .= '</ul>';
                                $menu_session .= '</li>';
                            }
							if (GetPermMenu($id_nivel, '94')) {
                                $menu_session .= '<li class="dropdown-submenu">';
                                $menu_session .= '<a href="#">Bloq / Desbl</a>';
                                $menu_session .= '<ul class="dropdown-menu">';
                                if (GetPermMenu($id_nivel, '95')) {
                                    $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'desbloqueio\', \'index.php\');">Desbloqueio</a></li>';
                                }
                                if (GetPermMenu($id_nivel, '96')) {
                                    $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'bloqueio\', \'index.php\');">Bloqueio</a></li>';
                                }
                                $menu_session .= '</ul>';
                                $menu_session .= '</li>';
                            }
                            $menu_session .= '</ul>';
                            $menu_session .= '</li>';
                        }
                        if (GetPermMenu($id_nivel, '52')) {
                            $menu_session .= '<li>';
                            $menu_session .= '<a href="#" data-toggle="dropdown" class="dropdown-toggle">
															<span>Utilidades</span>
															<span class="caret"></span>
														</a>';
                            $menu_session .= '<ul class="dropdown-menu">';
                            if (GetPermMenu($id_nivel, '53')) {
                                $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'tratar-retorno\', \'index.php\');">Tratar Retorno</a></li>';
                            }
                            if (GetPermMenu($id_nivel, '54')) {
                                $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'tratar-spc\', \'index.php\');">Tratar SPC</a></li>';
                            }
                            if (GetPermMenu($id_nivel, '55')) {
                                $menu_session .= '<li class="dropdown-submenu">';
                                $menu_session .= '<a href="#">Disco Virtual</a>';
                                $menu_session .= '<ul class="dropdown-menu">';
                                if (GetPermMenu($id_nivel, '56')) {
                                    $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'disco-virtual\', \'spc.php\');">SPC</a></li>';
                                }
                                if (GetPermMenu($id_nivel, '57')) {
                                    $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'disco-virtual\', \'remessa.php\');">Remessa</a></li>';
                                }
                                if (GetPermMenu($id_nivel, '58')) {
                                    $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'disco-virtual\', \'retorno.php\');">Retorno</a></li>';
                                }
                                $menu_session .= '</ul>';
                                $menu_session .= '</li>';
                            }
                            if (GetPermMenu($id_nivel, '59')) {
                                $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'msg-financeiro\', \'index.php\');">Mensagem Financeiro</a></li>';
                            }
                            if (GetPermMenu($id_nivel, '60')) {
                                $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'campanha-mail\', \'index.php\');">Campanhas Emails</a></li>';
                            }
                            if (GetPermMenu($id_nivel, '80')) {
                                $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'agendamento\', \'index.php\');">Agendamento de Operações</a></li>';
                            }
                            if (GetPermMenu($id_nivel, '62')) {
                                $menu_session .= '<li class="dropdown-submenu">';
                                $menu_session .= '<a href="#">Diretorias</a>';
                                $menu_session .= '<ul class="dropdown-menu">';
                                if (GetPermMenu($id_nivel, '63')) {
                                    $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'cargo\', \'index.php\');">Cargo</a></li>';
                                }
                                if (GetPermMenu($id_nivel, '64')) {
                                    $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'pessoa\', \'index.php\');">Pessoa</a></li>';
                                }
                                if (GetPermMenu($id_nivel, '65')) {
                                    $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'periodo\', \'index.php\');">Período</a></li>';
                                }
                                if (GetPermMenu($id_nivel, '66')) {
                                    $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'diretoria\', \'index.php\');">Diretoria</a></li>';
                                }
                                $menu_session .= '</ul>';
                                $menu_session .= '</li>';
                            }
                            $menu_session .= '</ul>';
                            $menu_session .= '</li>';
                        }
                        if (GetPermMenu($id_nivel, '70')) {
                            $menu_session .= '<li>';
                            $menu_session .= '<a href="#" data-toggle="dropdown" class="dropdown-toggle">
															<span>Gestão Contratos</span>
															<span class="caret"></span>
														</a>';
                            $menu_session .= '<ul class="dropdown-menu">';
                            if (GetPermMenu($id_nivel, '71')) {
                                $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'contrato\', \'index_rastreamento.php\');">Rastreamento</a></li>';
                            }
                            if (GetPermMenu($id_nivel, '72')) {
                                $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'contrato\', \'index_monitoramento.php\');">Monitoramento</a></li>';
                            }
                            if (GetPermMenu($id_nivel, '73')) {
                                $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'contrato\', \'index_chip.php\');">Chip</a></li>';
                            }
                            $menu_session .= '</ul>';
                            $menu_session .= '</li>';
                        }
                        if (GetPermMenu($id_nivel, '82')) {
                            $menu_session .= '<li>';
                            $menu_session .= '<a href="#" data-toggle="dropdown" class="dropdown-toggle">
															<span>MMN</span>
															<span class="caret"></span>
														</a>';
                            $menu_session .= '<ul class="dropdown-menu">';
                            if (GetPermMenu($id_nivel, '87')) {
                                $menu_session .= '<li><a href="javascript::" onclick="carrega_pagina(\'mmn_importar\', \'index.php\');">Importar</a></li>';
                            }
                            if (GetPermMenu($id_nivel, '82')) {
                                $menu_session .= '<li><a href="https://federalmultinivel.com.br/mmn_admin/Home.php?model=home&pg=home" target="_blank">Acessar Novo Sistema MMN</a></li>';
                            }
                            $menu_session .= '</ul>';
                            $menu_session .= '</li>';
                        }
                        $menu_session .= '</ul>';

                        $_SESSION['menu_facilita_entidades'] = $menu_session;

                        //QUERY PARA NOVOS BANCOS DE DADOS                        
                        $query_001 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('111', 'report_financeiro.php', 'plano-conta', 'Relatório Financeiro', '18');");
                        $query_002 = mysqli_query(Conn(), "INSERT INTO modulo(modulo_id, modulo_descricao) VALUES('32', 'Cargo');");
                        $query_003 = mysqli_query(Conn(), "INSERT INTO modulo(modulo_id, modulo_descricao) VALUES('33', 'Pessoa');");
                        $query_004 = mysqli_query(Conn(), "INSERT INTO modulo(modulo_id, modulo_descricao) VALUES('34', 'Período');");
                        $query_005 = mysqli_query(Conn(), "INSERT INTO modulo(modulo_id, modulo_descricao) VALUES('35', 'Diretoria');");
                        $query_006 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('112', 'create.php', 'cargo', 'Cadastrar', '32');");
                        $query_007 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('113', 'index.php', 'cargo', 'Listar', '32');");
                        $query_008 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('114', 'update.php', 'cargo', 'Editar', '32');");
                        $query_009 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('115', 'create.php', 'pessoa', 'Cadastrar', '33');");
                        $query_010 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('116', 'index.php', 'pessoa', 'Listar', '33');");
                        $query_011 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('117', 'update.php', 'pessoa', 'Editar', '33');");
                        $query_012 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('118', 'create.php', 'periodo', 'Cadastrar', '34');");
                        $query_013 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('119', 'index.php', 'periodo', 'Listar', '34');");
                        $query_014 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('120', 'update.php', 'periodo', 'Editar', '34');");
                        $query_015 = mysqli_query(Conn(), "UPDATE modulo SET modulo_descricao = 'Notificação' WHERE modulo_id = '15'");
                        $query_016 = mysqli_query(Conn(), "UPDATE modulo SET modulo_descricao = 'Região' WHERE modulo_id = '20'");
                        $query_017 = mysqli_query(Conn(), "UPDATE modulo SET modulo_descricao = 'Usuário' WHERE modulo_id = '29'");
                        $query_018 = mysqli_query(Conn(), "UPDATE modulo SET modulo_descricao = 'Tributação' WHERE modulo_id = '28'");
                        $query_019 = mysqli_query(Conn(), "UPDATE modulo SET modulo_descricao = 'Orçamento' WHERE modulo_id = '31'");
                        $query_020 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('121', 'create.php', 'diretoria', 'Cadastrar', '35');");
                        $query_021 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('122', 'index.php', 'diretoria', 'Listar', '35');");
                        $query_022 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('123', 'update.php', 'diretoria', 'Editar', '35');");
                        $query_023 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('124', 'index_pessoa.php', 'diretoria', 'Listar Pessoas', '35');");
                        $query_024 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('125', 'create_pessoa.php', 'diretoria', 'Cadastrar Pessoas', '35');");
                        $query_025 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('126', 'update_pessoa.php', 'diretoria', 'Editar Pessoas', '35');");
                        $query_026 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('127', 'index_feito.php', 'diretoria', 'Listar Feitos', '35');");
                        $query_027 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('128', 'create_feito.php', 'diretoria', 'Cadastrar Feitos', '35');");
                        $query_028 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('129', 'update_feito.php', 'diretoria', 'Editar Feitos', '35');");
                        $query_029 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('130', 'facturar.php', 'os', 'Faturar', '16');");
                        $query_030 = mysqli_query(Conn(), "ALTER TABLE `financeiro` ADD COLUMN `financeiro_id_os`  int(11) NULL AFTER `financeiro_id_venda`;");
                        $query_031 = mysqli_query(Conn(), "CREATE TABLE `pessoa` (
                                                                `pessoa_id`  int(11) NOT NULL AUTO_INCREMENT ,
                                                                `pessoa_nome`  varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL ,
                                                                `pessoa_email`  varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL ,
                                                                `pessoa_telefone`  varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL ,
                                                                `pessoa_celular`  varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL ,
                                                                `pessoa_obs`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
                                                                PRIMARY KEY (`pessoa_id`)
                                                            );");
                        $query_032 = mysqli_query(Conn(), "CREATE TABLE `pessoa_diretoria` (
                                                                `pessoa_diretoria_id`  int(11) NOT NULL AUTO_INCREMENT ,
                                                                `pessoa_diretoria_id_diretoria`  int(11) NULL DEFAULT NULL ,
                                                                `pessoa_diretoria_id_pessoa`  int(11) NULL DEFAULT NULL ,
                                                                `pessoa_diretoria_id_cargo`  int(11) NULL DEFAULT NULL ,
                                                                PRIMARY KEY (`pessoa_diretoria_id`)
                                                            );");
                        $query_033 = mysqli_query(Conn(), "CREATE TABLE `cargo` (
                                                                `cargo_id`  int(11) NOT NULL AUTO_INCREMENT ,
                                                                `cargo_descricao`  varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL ,
                                                                `cargo_status`  int(1) NULL DEFAULT NULL ,
                                                                PRIMARY KEY (`cargo_id`)
                                                            );");
                        $query_034 = mysqli_query(Conn(), "CREATE TABLE `diretoria` (
                                                                `diretoria_id`  int(11) NOT NULL AUTO_INCREMENT ,
                                                                `diretoria_id_periodo`  int(11) NULL DEFAULT NULL ,
                                                                `diretoria_descricao`  varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL ,
                                                                `diretoria_obs`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
                                                                PRIMARY KEY (`diretoria_id`)
                                                            );");
                        $query_035 = mysqli_query(Conn(), "CREATE TABLE `feito_diretoria` (
                                                                `feito_diretoria_id`  int(11) NOT NULL AUTO_INCREMENT ,
                                                                `feito_diretoria_id_diretoria`  int(11) NULL DEFAULT NULL ,
                                                                `feito_diretoria_data`  date NULL DEFAULT NULL ,
                                                                `feito_diretoria_descricao`  varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL ,
                                                                `feito_diretoria_obs`  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
                                                                PRIMARY KEY (`feito_diretoria_id`)
                                                            );");
                        $query_036 = mysqli_query(Conn(), "CREATE TABLE `periodo` (
                                                                `periodo_id`  int(11) NOT NULL AUTO_INCREMENT ,
                                                                `periodo_descricao`  varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL ,
                                                                `periodo_data_inicial`  year NULL DEFAULT NULL ,
                                                                `periodo_data_final`  year NULL DEFAULT NULL ,
                                                                PRIMARY KEY (`periodo_id`)
                                                            );");
                        $query_037 = mysqli_query(Conn(), "");
                        $query_038 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('132', 'create_instalacao.php', 'pedido', 'Cadastrar Instalação', '36');");
                        $query_039 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('133', 'chip.php', 'pedido', 'Chips', '36');");
                        $query_040 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('134', 'create_sms.php', 'pedido', 'SMS', '36');");
                        $query_041 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('135', 'update_instalacao.php', 'pedido', 'Editar Instalação', '36');");
                        $query_042 = mysqli_query(Conn(), "UPDATE pagina SET pagina_descricao = 'Cadastrar SMS' WHERE pagina_id = '134'");
                        $query_043 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('136', 'update_sms.php', 'pedido', 'Editar SMS', '36');");
                        $query_044 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('137', 'index_chip.php', 'contrato', 'Listar', '38');");
                        $query_045 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('138', 'create_chip.php', 'contrato', 'Cadastrar', '38');");
                        $query_046 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('139', 'update_chip.php', 'contrato', 'Editar', '38');");
                        $query_047 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('140', 'anexo_chip.php', 'contrato', 'Anexar', '38');");
                        $query_048 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('141', 'aditivo_chip.php', 'contrato', 'Listar Aditivos', '38');");
                        $query_049 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('142', 'create_desinstalacao.php', 'contrato', 'Cadastrar Desinstalação', '36');");
                        $query_050 = mysqli_query(Conn(), "UPDATE pagina SET pagina_modulo = 'pedido' WHERE pagina_id = '142'");
                        $query_051 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('143', 'index.php', 'assinatura', 'Listar', '39');");
                        $query_052 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('144', 'create.php', 'assinatura', 'Cadastrar', '39');");
                        $query_053 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('145', 'update.php', 'assinatura', 'Editar', '39');");
                        $query_054 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('146', 'index.php', 'plano', 'Listar', '40');");
                        $query_055 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('147', 'create.php', 'plano', 'Cadastrar', '40');");
                        $query_056 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('148', 'update.php', 'plano', 'Editar', '40');");
                        $query_057 = mysqli_query(Conn(), "UPDATE pagina SET pagina_modulo = 'plano-assinatura' WHERE pagina_id = '146'");
                        $query_058 = mysqli_query(Conn(), "UPDATE pagina SET pagina_modulo = 'plano-assinatura' WHERE pagina_id = '147'");
                        $query_059 = mysqli_query(Conn(), "UPDATE pagina SET pagina_modulo = 'plano-assinatura' WHERE pagina_id = '148'");
                        $query_060 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('149', 'create_aditivo_chip.php', 'contrato', 'Cadastrar Aditivo', '38');");
                        $query_061 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('150', 'index.php', 'faturamento', 'Listar', '37');");
                        $query_062 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('151', 'create.php', 'faturamento', 'Cadastrar', '37');");
                        $query_063 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('152', 'index_rastreamento.php', 'contrato', 'Listar', '41');");
                        $query_064 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('153', 'create_rastreamento.php', 'contrato', 'Cadastrar', '41');");
                        $query_065 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('154', 'update_rastreamento.php', 'contrato', 'Editar', '41');");
                        $query_066 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('155', 'aditivo_rastreamento.php', 'contrato', 'Listar Aditivo', '41');");
                        $query_067 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('156', 'create_aditivo_rastreamento.php', 'contrato', 'Cadastrar Aditivo', '41');");
                        $query_068 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('157', 'create_veiculo_aditivo_rastreamento.php', 'contrato', 'Cadastrar Veiculos Aditivo', '41');");
                        $query_069 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('158', 'veiculo_aditivo_rastreamento.php', 'contrato', 'Listar Veiculos Aditivo', '41');");
                        $query_070 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('159', 'update_veiculo_aditivo_rastreamento.php', 'contrato', 'Editar Veiculos Aditivo', '41');");
                        $query_071 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('160', 'index_monitoramento.php', 'contrato', 'Listar', '42');");
                        $query_072 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('161', 'create_monitoramento.php', 'contrato', 'Cadastrar', '42');");
                        $query_073 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('162', 'update_monitoramento.php', 'contrato', 'Editar', '42');");
                        $query_074 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('163', 'itens_monitoramento.php', 'contrato', 'Itens Monitoramento', '42');");
                        $query_075 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('164', 'create_itens_monitoramento.php', 'contrato', 'Cadastrar Itens Monitoramento', '42');");
                        $query_076 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('165', 'update_itens_monitoramento.php', 'contrato', 'Editar Itens Monitoramento', '42');");
                        $query_077 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('166', 'vehicles.php', 'os', 'Veiculos', '16');");
                        $query_078 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('167', 'create_vehicles.php', 'os', 'Cadastrar Veiculos', '16');");
                        $query_079 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('168', 'update_vehicles.php', 'os', 'Editar Veiculos', '16');");
                        $query_080 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('169', 'index.php', 'vendedor_franquiado', 'Listar', '43');");
                        $query_081 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('170', 'create.php', 'vendedor_franquiado', 'Cadastrar', '43');");
                        $query_082 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('171', 'update.php', 'vendedor_franquiado', 'Editar', '43');");
                        $query_083 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('172', 'update_varios.php', 'financeiro', 'Editar Vários', '9');");
                        $query_084 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('173', 'update_varios.php', 'financeiro', 'Editar Vários', '9');");
                        $query_085 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('174', 'index.php', 'associado', 'Listar', '44');");
                        $query_086 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('175', 'create.php', 'associado', 'Cadastrar', '44');");
                        $query_087 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('176', 'update.php', 'associado', 'Editar', '44');");

                        $query_088 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('177', 'index.php', 'consumo', 'Listar', '45');");

                        $query_089 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('178', 'index.php', 'agendamento', 'Listar', '46');");
                        $query_090 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('179', 'create.php', 'agendamento', 'Cadastrar', '46');");
                        $query_091 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('180', 'update.php', 'agendamento', 'Editar', '46');");

                        $query_092 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('181', 'index.php', 'chip', 'Info', '47');");
                        $query_093 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('182', 'cliente.php', 'home', 'Tela Inicial', '48');");
                        $query_094 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('183', 'pedido.php', 'ticket', 'Pedido', '23');");
                        $query_095 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('184', 'linha_tempo_chip.php', 'pedido', 'Linha Tempo Chip', '36');");
                        $query_096 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('185', 'bloqueio.php', 'chip', 'Bloqueio', '47');");
                        $query_097 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('186', 'bloqueio_view.php', 'chip', 'Bloqueio View', '47');");
                        $query_098 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('187', 'index.php', 'mmn_pedidos', 'Listar', '49');");
                        $query_099 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('188', 'update.php', 'mmn_pedidos', 'Editar', '49');");
                        $query_100 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('189', 'index.php', 'mmn_user', 'Listar', '50');");
                        $query_101 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('190', 'update.php', 'mmn_user', 'Editar', '50');");
                        
                        $query_102 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('191', 'index.php', 'mmn_saque', 'Listar', '51');");
                        $query_103 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('192', 'baixar.php', 'mmn_saque', 'Baixar', '51');");
                        
                        $query_104 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('193', 'index.php', 'mmn_fatura', 'Listar', '52');");
                        $query_105 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('194', 'baixar.php', 'mmn_fatura', 'Baixar', '52');");
                        $query_106 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('195', 'update.php', 'mmn_fatura', 'Editar', '52');");
                        $query_107 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('196', 'cancel.php', 'mmn_fatura', 'Cancelar', '52');");
                        $query_108 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('197', 'estornar.php', 'mmn_fatura', 'Estornar', '52');");
                        
                        $query_109 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('198', 'index.php', 'mmn_importar', 'Listar', '53');");
                        $query_110 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('199', 'create.php', 'mmn_importar', 'Importar', '53');");
                        
                        $query_111 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('200', 'report.php', 'chip', 'Relatório', '53');");
                        $query_112 = mysqli_query(Conn(), "UPDATE pagina SET pagina_modulo = 'chip', pagina_descricao = 'Relatório', pagina_id_modulo = '47' WHERE pagina_id = '200'");
                        $query_113 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('201', 'index.php', 'mmn_chip', 'Listar', '54');");
                        $query_114 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('202', 'update.php', 'mmn_chip', 'Editar', '54');");
                        $query_115 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('203', 'create.php', 'mmn_chip', 'Cadastrar', '54');");
                        
                        $query_116 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('204', 'create.php', 'mmn_ticket', 'Cadastrar', '55');");
                        $query_117 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('205', 'index.php', 'mmn_ticket', 'Listar', '55');");
                        $query_118 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('206', 'update.php', 'mmn_ticket', 'Editar', '55');");
                        $query_119 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('207', 'create.php', 'mmn_fatura', 'Cadastrar', '52');");
                        $query_120 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('208', 'update_plan.php', 'mmn_pedidos', 'Editar Plano', '49');");
                        $query_121 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('209', 'view.php', 'mmn_notificacao', 'Ver', '56');");
                        $query_122 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('210', 'index.php', 'mmn_notificacao', 'Listar', '56');");
                        $query_123 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('211', 'create.php', 'pedido', 'Cadastrar', '36');");
                        $query_124 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('212', 'update.php', 'pedido', 'Editar', '36');");
                        $query_125 = mysqli_query(Conn(), "INSERT INTO pagina(pagina_id, pagina_arquivo, pagina_modulo, pagina_descricao, pagina_id_modulo) VALUES('213', 'view.php', 'mmn_user', 'Anotações', '50');");
                        if ($_SESSION[VSESSION]['user_tipo_ticket'] == '1') {
                            header("Location: Home.php?model=home&pg=cliente");
                        } else {
							$_SESSION['id_verificar'] = '1';
							$_SESSION['id_usuario'] = $_SESSION[VSESSION]['user_id'];
                            header("Location: Home.php?model=home&pg=home");
                        }
                    } else {
                        echo '<div class="alert alert-danger alert-dismissable">
                                <strong>Erro!</strong> Dados não conferem.
                        </div>';
                    }
                }
                ?>
                <form action="" method='post' class='form-validate' id="test">
                    <div class="form-group">
                        <div class="email controls">
                            <input type="text" name='ueentidade' placeholder="Identificador Entidade" class='form-control' data-rule-required="true" data-rule-email="false" autocomplete="off" value="federal20">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="email controls">
                            <input type="text" name='uemail' placeholder="Usuário" class='form-control' data-rule-required="true" data-rule-email="false" autocomplete="off" value="<?php echo $get_login; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="pw controls">
                            <input type="password" name="upw" placeholder="Senha" class='form-control' data-rule-required="true" value="<?php echo $get_senha; ?>">
                        </div>
                    </div>
                    <div class="submit">
                        <input type="submit" value="Logar" name="sendLogin" class='btn btn-primary'>
                    </div>
                </form>
                <div class="forget">
                    <a href="#">
                        <span>.</span>
                    </a>
                </div>
            </div>
        </div>
    </body>
</html>
