<div id="navigation">
    <div class="container-fluid">
        <?php
            /*if($_SESSION[VSESSION]['user_tipo_ticket'] == '1'){
                echo '<a href="Home.php?model=home&pg=cliente" id="brand">FederalGestor</a>';
            } else {
                echo '<a href="Home.php?model=home&pg=home" id="brand">FederalGestor</a>';
            }*/
        ?>
        
        <?php
            echo $_SESSION['menu_facilita_entidades'];
        ?>
        <?php /*
        <ul class='main-nav' style="display: none">
            <li>
                <a href="Home.php?model=home&pg=home">
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="#" data-toggle="dropdown" class='dropdown-toggle'>
                    <span>Cadastros</span>
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    <li class='dropdown-submenu'>
                        <a href="#">Contatos</a>
                        <ul class="dropdown-menu">
                            <li><a href="javascript::" onclick="carrega_pagina('tipo-contato', 'index.php')">Tipo Contato</a></li>
                            <li><a href="javascript::" onclick="carrega_pagina('regiao', 'index.php')">Região</a></li>
                            <li><a href="javascript::" onclick="carrega_pagina('rota', 'index.php')">Rota</a></li>
                            <li><a href="javascript::" onclick="carrega_pagina('contato', 'index.php')">Contato</a></li>
                        </ul>
                    </li>
                    <li class='dropdown-submenu'>
                        <a href="#">Produtos</a>
                        <ul class="dropdown-menu">
                            <li><a href="javascript::" onclick="carrega_pagina('categoria', 'index.php');">Categoria</a></li>
                            <li><a href="javascript::" onclick="carrega_pagina('sub-categoria', 'index.php');">SubCategoria</a></li>
                            <li><a href="javascript::" onclick="carrega_pagina('produto', 'index.php');">Produto</a></li>
                            <li><a href="javascript::" onclick="carrega_pagina('tributacao', 'index.php');">Tributação</a></li>
                        </ul>
                    </li>
                    <li class='dropdown-submenu'>
                        <a href="#">Financeiro</a>
                        <ul class="dropdown-menu">
                            <li><a href="javascript::" onclick="carrega_pagina('caixa', 'index.php');">Caixa</a></li>
                            <li><a href="javascript::" onclick="carrega_pagina('plano-conta', 'index.php');">Plano de Contas</a></li>
                            <li><a href="javascript::" onclick="carrega_pagina('tipo-documento', 'index.php');">Tipo de Documento</a></li>
                            <li><a href="javascript::" onclick="carrega_pagina('forma-pagamento', 'index.php');">Forma de Pagamento</a></li>
                        </ul>
                    </li>
                    <li class='dropdown-submenu'>
                        <a href="#">Perfil</a>
                        <ul class="dropdown-menu">
                            <li><a href="javascript::" onclick="carrega_pagina('user', 'index.php');">Usuário</a></li>
                            <li><a href="javascript::" onclick="carrega_pagina('nivel', 'index.php');">Nível</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#" data-toggle="dropdown" class='dropdown-toggle'>
                    <span>Operações</span>
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="javascript::" onclick="carrega_pagina('orcamento', 'index.php');">Orçamentos</a></li>
                    <li><a href="javascript::" onclick="carrega_pagina('venda', 'index.php');">Vendas</a></li>
                    <li><a href="javascript::" onclick="carrega_pagina('os', 'index.php');">Ordem Serviço</a></li>
                </ul>
            </li>
            <li>
                <a href="#" data-toggle="dropdown" class='dropdown-toggle'>
                    <span>Financeiro</span>
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="javascript::" onclick="carrega_pagina('financeiro', 'index.php?OP=CP');">Contas a Pagar</a></li>
                    <li><a href="javascript::" onclick="carrega_pagina('financeiro', 'index.php?OP=CR');">Contas a Receber</a></li>
                    <li><a href="javascript::" onclick="carrega_pagina('caixa-conta', 'index.php');">Caixa / Conta</a></li>
                </ul>
            </li>
            <li>
                <a href="#" data-toggle="dropdown" class='dropdown-toggle'>
                    <span>CRM</span>
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="javascript::" onclick="carrega_pagina('departamento', 'index.php');">Departamento</a></li>
                    <li><a href="javascript::" onclick="carrega_pagina('ticket', 'index.php');">Ticket</a></li>
                </ul>
            </li>
            <li>
                <a href="#" data-toggle="dropdown" class='dropdown-toggle'>
                    <span>Relatórios</span>
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    <li class='dropdown-submenu'>
                        <a href="#">Cadastros</a>
                        <ul class="dropdown-menu">
                            <li><a href="javascript::" onclick="carrega_pagina('tipo-contato', 'report.php');">Tipo Contato</a></li>
                            <li><a href="javascript::" onclick="carrega_pagina('regiao', 'report.php');">Região</a></li>
                            <li><a href="javascript::" onclick="carrega_pagina('rota', 'report.php');">Rota</a></li>
                            <li><a href="javascript::" onclick="carrega_pagina('contato', 'report.php');">Contato</a></li>
                            <li><a href="javascript::" onclick="carrega_pagina('categoria', 'report.php');">Categoria</a></li>
                            <li><a href="javascript::" onclick="carrega_pagina('sub-categoria', 'report.php');">SubCategoria</a></li>
                            <li><a href="javascript::" onclick="carrega_pagina('produto', 'report.php');">Produto</a></li>
                            <li><a href="javascript::" onclick="carrega_pagina('caixa', 'report.php');">Caixa</a></li>
                            <li><a href="javascript::" onclick="carrega_pagina('plano-conta', 'report.php');">Plano de Contas</a></li>
                            <li><a href="javascript::" onclick="carrega_pagina('tipo-documento', 'report.php');">Tipo de Documento</a></li>
                            <li><a href="javascript::" onclick="carrega_pagina('forma-pagamento', 'report.php');">Forma de Pagamento</a></li>
                            <li><a href="javascript::">Usuário</a></li>
                            <li><a href="javascript::" onclick="carrega_pagina('nivel', 'report.php');">Nível</a></li>
                        </ul>
                    </li>
                    <li class='dropdown-submenu'>
                        <a href="#">Financeiro</a>
                        <ul class="dropdown-menu">
                            <li><a href="javascript::" onclick="carrega_pagina('financeiro', 'report.php?OP=CP');">Contas a Pagar</a></li>
                            <li><a href="javascript::" onclick="carrega_pagina('financeiro', 'report.php?OP=CR');">Contas a Receber</a></li>
                            <li><a href="javascript::" onclick="carrega_pagina('financeiro', 'report.php');">Financeiro</a></li>
                            <li><a href="javascript::" onclick="carrega_pagina('caixa-conta', 'report.php');">Fluxo Financeiro</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#" data-toggle="dropdown" class='dropdown-toggle'>
                    <span>Utilidades</span>
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="javascript::" onclick="carrega_pagina('tratar-retorno', 'index.php');">Tratar Retorno</a></li>
                    <li><a href="javascript::" onclick="carrega_pagina('tratar-spc', 'index.php');">Tratar SPC</a></li>
                    <li class='dropdown-submenu'>
                        <a href="#">Disco Virtual</a>
                        <ul class="dropdown-menu">
                            <li><a href="javascript::" onclick="carrega_pagina('disco-virtual', 'spc.php');">SPC</a></li>
                            <li><a href="javascript::" onclick="carrega_pagina('disco-virtual', 'remessa.php');">Remessa</a></li>
                            <li><a href="javascript::" onclick="carrega_pagina('disco-virtual', 'retorno.php');">Retorno</a></li>
                        </ul>
                    </li>
                    <li><a href="javascript::" onclick="carrega_pagina('msg-financeiro', 'index.php');">Mensagem Financeiro</a></li>
                    <li><a href="javascript::" onclick="carrega_pagina('campanha-mail', 'index.php');">Campanhas Emails</a></li>
                </ul>
            </li>
        </ul>*/?>
        <?php
            $read_notificacao = Read('notificacao', "WHERE notificacao_id_user = '".$_SESSION[VSESSION]['user_id']."' AND notificacao_status = '0'");
            if(NumQuery($read_notificacao)){
                $count_notificacao = NumQuery($read_notificacao);
            }else{
                $count_notificacao = '0';
            }
        ?>
        <div class="user">
            <ul class="icon-nav">
                <li class='dropdown'>
                    <a href="#" class='dropdown-toggle' data-toggle="dropdown">
                        <i class="fa fa-envelope"></i>
                        <span class="label label-lightred"><?php echo $count_notificacao;?></span>
                    </a>
                    <ul class="dropdown-menu pull-right message-ul">
                        <?php
                            if(NumQuery($read_notificacao) > '0'){
                                foreach($read_notificacao as $read_notificacao_view){
                        ?>
                        <li>
                            <a href="javascript::" onclick="carrega_pagina('notificacao', 'view.php?id=<?php echo $read_notificacao_view['notificacao_id'];?>');">
                                <div class="details">
                                    <div class="name"><?php echo $read_notificacao_view['notificacao_titulo'];?></div>
                                    <div class="message">
                                        <?php echo $read_notificacao_view['notificacao_descricao'];?>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <?php
                                }
                            }
                        ?>
                        <li>
                            <a href="javascript::" class='more-messages' onclick="carrega_pagina('notificacao', 'index.php');">Veja todas as notificações (Facilita)
                                <i class="fa fa-arrow-right"></i>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
            <div class="dropdown">
                    <a href="#" class='dropdown-toggle' data-toggle="dropdown"><?php echo $_SESSION[VSESSION]['user_nome'];?>
                        <!--<img src="_boot/img/demo/user-avatar.jpg" alt="">-->
                    </a>
                    <ul class="dropdown-menu pull-right">
                        <li>
                            <a href="javascript::" onclick="carrega_pagina('perfil', 'update.php');">Meu Perfil</a>
                        </li>
                        <?php
                            if($_SESSION[VSESSION]['user_tipo_ticket'] == '0'){
                        ?>
                        <li>
                            <a href="javascript::" onclick="carrega_pagina('empresa', 'update.php')">Empresa</a>
                        </li>
						<li>
                            <a href="javascript::" onclick="carrega_pagina('plp', 'index.php')">PLP</a>
                        </li>
						<li>
                            <a href="Home.php?model=chip&pg=bloqueio">Bloqueio</a>
                        </li>
                        <?php
                            }
                        ?>
                        <li>
                            <a href="Sair.php">Sair</a>
                        </li>
                    </ul>
            </div>
        </div>
    </div>
</div>