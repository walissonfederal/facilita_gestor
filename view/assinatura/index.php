<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Assinaturas
                                <a href="javascript::" onclick="carrega_pagina('assinatura', 'create.php');" class="btn btn-primary">Cadastrar Novo</a>
                            </h3>
                        </div>
                        <div class="box-content">
                            <form action="">
                                <fieldset>
                                    <div class="row">
                                        <div class="form-group col-lg-2">
                                            <label>ID</label>
                                            <input type="text" class="form-control search_id"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Status</label>
                                            <select class="form-control search_status">
                                                <option value=""></option>
                                                <option value="0">Pendente</option>
                                                <option value="1">Ativa</option>
                                                <option value="2">Cancelado</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>ID Contato</label>
                                            <input type="text" class="form-control search_id_contato" onblur="buscar_contato();"/>
                                        </div>
                                        <div class="form-group col-lg-5">
                                            <label>Contato(Nome Razão, Nome Fantasia, CPF / CNPJ)</label>
                                            <input type="text" class="form-control nome_contato"/>
                                        </div>
                                        <div class="form-group col-lg-1">
                                            <label>.</label><br />
                                            <button type="button" class="btn btn-primary" onclick="search();">Pesquisar</button>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                            <hr />
                            <div id="load_assinatura"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /#modal-1.modal fade -->
<div id="_modal_count_mail" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Enviar Email</h4>
            </div>
            <!-- /.modal-header -->
            <div class="modal-body">
                <div id="retorno_mail"></div>
                <div id="retorno_mail_msg"></div>
                <input type="hidden" class="id_assinatura_completa"/>
            </div>
            <!-- /.modal-body -->
            <div class="modal-footer">
                <div id="resposta_mail"></div>
                <button type="button" class="btn btn-default" onclick="send_mail_assinatura();">Enviar E-Mail</button>
            </div>
            <!-- /.modal-footer -->
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<script>
    function load_conta_mail(id_assinatura){
        var acao = "acao=load_msg_assinatura&url="+id_assinatura;
        $.ajax({
            type: 'POST',
            url: "_controller/_assinatura.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                $("#_modal_count_mail").modal('show');
                $("#retorno_mail").html(data);
                $("#retorno_mail_msg").html('');
                $("#resposta_mail").html('');
                $(".id_assinatura_completa").val(id_assinatura);
            }
        });
    }
    function carrega_msg_assinatura(){
        var id_msg_assinatura = $(".txt_mail_msg").val();
        var acao = "acao=load_msgs_assinatura&id_msg_assinatura="+id_msg_assinatura;
        $.ajax({
            type: 'POST',
            url: "_controller/_assinatura.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                $("#retorno_mail_msg").html(data);
            }
        });
    }
    function send_mail_assinatura(){
        var msg_financeiro_texto = $(".msg_financeiro_texto").val();
        var msg_financeiro_id = $(".msg_financeiro_id").val();
        var msg_financeiro_md5 = $(".txt_mail_msg").val();
        var id_assinatura_completa = $(".id_assinatura_completa").val();
        var acao = "acao=mail_assinatura&msg_financeiro_id="+msg_financeiro_id+"&msg_financeiro_texto="+msg_financeiro_texto+"&msg_financeiro_md5="+msg_financeiro_md5+"&id_assinatura="+id_assinatura_completa;
        $.ajax({
            type: 'POST',
            url: "_controller/_assinatura.php",
            data: acao,
            beforeSend: function (){
                $('#resposta_mail').html('<div class="alert alert-success"><img src="_img/load.gif" class="load" /> <strong>Aguarde!</strong> Estou fazendo o envio do arquivo</div>');
            },
            success: function (data) {
                load_out();
                $('#resposta_mail').html('<div class="alert alert-success">'+data+'</div>');
            }
        });
    }
    $(function() {
        $( ".nome_contato" ).autocomplete({
            source: "_controller/_contato.php?acao=load_contato",
            minLength: 2,
            focus: function(event, ui) {
            	event.preventDefault();
            },
            select: function( event, ui ) {
                $(".assinatura_id_contato").val(ui.item.value);
                $(".nome_contato").val(ui.item.label);
                event.preventDefault();
            }
        });
    });
    $(function(){
        var acao = "acao=load_plano_assinatura";
        $.ajax({
            type: 'GET',
            url: "_controller/_plano_assinatura.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                var data_return = jQuery.parseJSON(data);
                var options = '<option value=""></option>';
                for (var i = 0; i < data_return.data.length; i++) {
                    options += '<option value="' + data_return.data[i].plano_assinatura_id + '">' + data_return.data[i].plano_assinatura_descricao + '</option>';
                }
                $('.assinatura_id_plano').html(options).show();
                load_out();
            }
        });
        load_out();
    });
    var updateIcon = function (value, data, cell, row, options) { //plain text value
        return "<i class='fa fa-pencil' style='vertical-align:middle; padding:2px 0;' title='Editar'></i> ";
    };
    var mailIcon = function (value, data, cell, row, options) { //plain text value
        return "<i class='fa fa-envelope' style='vertical-align:middle; padding:2px 0;' title='Enviar Email'></i> ";
    };
    $("#load_assinatura").tabulator({
        height: "350px",
        fitColumns: true,
        ajaxURL: "_controller/_assinatura.php",
        ajaxParams: {acao: "load"},
        pagination: "remote",
        paginationSize: 100,
        paginationDataSent: {"page": "pageNo"},
        columns: [
            {
                formatter: updateIcon, width: 40, align: "center", onClick: function (e, cell, val, data) {
                    carrega_pagina('assinatura', 'update.php?id='+data.assinatura_id);
                }
            },
            {
                formatter: mailIcon, width: 40, align: "center", onClick: function (e, cell, val, data) {
                    load_conta_mail('1');
                }
            },
            {title: "ID", field: "assinatura_id", sorter: "int", width: 50},
            {title: "Contato", field: "contato_nome_razao", sorter: "string"},
            {title: "Status", field: "assinatura_status", sorter: "string"},
            {title: "Lançamento Automático", field: "assinatura_lancamento_auto", sorter: "string"},
            {title: "Data Inicial", field: "assinatura_data_criacao", sorter: "string"},
            {title: "Data Final", field: "assinatura_data_final", sorter: "string"},
            {title: "Plano", field: "plano_assinatura_descricao", sorter: "string"}
        ]
    });
    
    function search(){
        var search_id           = $(".search_id").val();
        var search_status       = $(".search_status").val();
        var search_id_contato   = $(".search_id_contato").val();
        
        var acao = "acao=load&search=true&id="+search_id+"&id_contato="+search_id_contato+"&status="+search_status;
        
        $.ajax({
            type: 'GET',
            url: "_controller/_assinatura.php",
            data: acao,
            beforeSend: load_in(),
            success: function () {
                load_out();
                $("#load_assinatura").tabulator("setData", "_controller/_assinatura.php?acao=load");
            }
        });
    }
</script>