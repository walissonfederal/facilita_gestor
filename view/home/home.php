<div class="container-fluid nav-hidden" id="content">
    <div id="main">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Novidades Sistema - Versão 06/11/2017
                            </h3>
                        </div>
                        <div class="box-content">
                            <div class="row">
                                <div class="alert alert-danger alert-dismissable">
                                    <strong>Atenção!</strong> Pelo fato de clientes poderem abrir ticket pelo sistema, é de extrema urgência a mudança de senhas como 123 / 123456 etc, pois caso isso ocorra diminuirá a segurança do sistema. Obrigado pela compeenção.
                                </div>
                                <div class="alert alert-danger alert-dismissable">
                                    <strong>Atenção!</strong> Agora no ticket é permitido anexo de vários arquivos dentro de um mesmo ticket e possível também anexar dentro de conversas arquivos para download. 
                                </div>
                                <div class="alert alert-danger alert-dismissable">
                                    <strong>Atenção!</strong> Dentro de Operações->Chip->Informação Chip é um atalho para linha do tempo do chip.
                                </div>
                                <div class="alert alert-danger alert-dismissable">
                                    <strong>Atenção!</strong> Foi inserida a opção de Associado no pedido, porém não é um campo obrigatório. Esse campo deve ser preenchido apenas para vendas de chips 4G. Qualquer dúvida não finalize o pedido, abre um chamado.
                                </div>
                                <div class="col-sm-12">
                                    <div class="panel-group panel-widget" id="ac3">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a href="#c1" data-toggle="collapse" data-parent="#ac3">
                                                        Departamento Financeiro
                                                    </a>
                                                </h4>
                                                <!-- /.panel-title -->
                                            </div>
                                            <!-- /.panel-heading -->
                                            <div id="c1" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <ul>
                                                        <li>Cancelar <a href="#" onclick="onclick_varios();">vários</a></li>
                                                        <li>Pesquisa <a href="#" onclick="user_msg_open();">de</a> vendedores</li>
                                                        <li>Vendedor no financeiro</li>
                                                    </ul>
                                                </div>
                                                <!-- /.panel-body -->
                                            </div>
                                            <!-- /#c1.panel-collapse collapse in -->
                                        </div>
                                        <!-- /.panel panel-default -->
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a href="#c2" data-toggle="collapse" data-parent="#ac3">
                                                        Departamento de Suporte
                                                    </a>
                                                </h4>
                                                <!-- /.panel-title -->
                                            </div>
                                            <!-- /.panel-heading -->
                                            <div id="c2" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <ul>
                                                        <li>Infelizmente não tem nenhuma novidade para esse departamento</li>
                                                    </ul>
                                                </div>
                                                <!-- /.panel-body -->
                                            </div>
                                            <!-- /#c1.panel-collapse collapse in -->
                                        </div>
                                        <!-- /.panel panel-default -->
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a href="#c3" data-toggle="collapse" data-parent="#ac3">
                                                        Departamento de Chips
                                                    </a>
                                                </h4>
                                                <!-- /.panel-title -->
                                            </div>
                                            <!-- /.panel-heading -->
                                            <div id="c3" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <ul>
                                                        <li>Infelizmente não tem nenhuma novidade para esse departamento</li>
                                                    </ul>
                                                </div>
                                                <!-- /.panel-body -->
                                            </div>
                                            <!-- /#c1.panel-collapse collapse in -->
                                        </div>
                                        <!-- /.panel panel-default -->
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a href="#c4" data-toggle="collapse" data-parent="#ac3">
                                                        Para todos
                                                    </a>
                                                </h4>
                                                <!-- /.panel-title -->
                                            </div>
                                            <!-- /.panel-heading -->
                                            <div id="c4" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <ul>
                                                        <li>Pesquisa por data nos tickets</li>
                                                    </ul>
                                                </div>
                                                <!-- /.panel-body -->
                                            </div>
                                            <!-- /#c1.panel-collapse collapse in -->
                                        </div>
                                        <!-- /.panel panel-default -->
                                    </div>
                                    <!-- /.panel-group -->
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="_modal_notificacao" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel"><div id="title_modal"></div></h4>
            </div>
            <!-- /.modal-header -->
            <div class="modal-body">
                <p>
                    <div id="texto_modal"></div>
                </p>
            </div>
            <!-- /.modal-body -->
            <div class="modal-footer">
                <div id="buttons_modal"></div>
            </div>
        </div>
    </div>
</div>
<div id="_modal_cad_notificacao" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel"></h4>
            </div>
            <!-- /.modal-header -->
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-lg-6">
                        <label>Usuário</label>
                        <input type="text" class="form-control id_usuario"/>
                    </div>
                    <div class="form-group col-lg-6">
                        <label>Data</label>
                        <input type="date" class="form-control data"/>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-lg-12">
                        <label>Descrição</label>
                        <textarea class="form-control descricao_cad_notificacao" rows="5">Olá eu sou o sistema de inteligência do facilita gestor e percebi que você não está usando alguns módulos do sistema de acordo com o seu departamento, gostaria de saber o porque não está sendo usado. Eu tenho várias ferramentas que podem te ajudar e estou triste por você não está utilizando nossos módulos. Hoje venho informar que o <strong>contas a pagar</strong> não está sendo usado por você, peço que use pois é uma grande ferramente que vai ajudar na gestão.<br /><strong>Mensagem gerada automaticamente pelo sistema</strong></textarea>
                    </div>
                </div>
            </div>
            <!-- /.modal-body -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" onclick="create_cad_notificacao();">Gravar</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        user_msg_open();
    })
    function onclick_varios(){
        $("#_modal_cad_notificacao").modal('show');
    }
    function user_msg_open(){
        var acao = "&acao=load_user_msg";
        
        $.ajax({
            type: 'POST',
            url: "_controller/_user.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                var data_return = jQuery.parseJSON(data);
                if(data_return.type === 'error'){
                    $("#_modal_notificacao").modal('hide');
                }else{
                    $("#_modal_notificacao").modal('show');
                    $("#title_modal").html(data_return.title);
                    $("#texto_modal").html(data_return.msg);
                    $("#buttons_modal").html(data_return.buttons);
                }
            }
        });
    }
    function user_msg_update(id_update){
        var acao = "&acao=update_user_msg&id="+id_update;
        
        $.ajax({
            type: 'POST',
            url: "_controller/_user.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
            }
        });
    }
    function create_cad_notificacao(){
        var descricao = $(".descricao_cad_notificacao").val();
        var id_usuario = $(".id_usuario").val();
        var data = $(".data").val();
        var acao = "&acao=create_user_msg&descricao="+descricao+"&id_usuario="+id_usuario+"&data="+data;
        
        $.ajax({
            type: 'POST',
            url: "_controller/_user.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
            }
        });
    }
</script>