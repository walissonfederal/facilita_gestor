<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Contrato Monitoramento
                                <a href="javascript::" onclick="carrega_pagina('contrato', 'create_monitoramento.php');" class="btn btn-primary">Cadastrar Novo</a>
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
                                            <label>ID Contato</label>
                                            <input type="text" class="form-control search_id_contato" onblur="buscar_contato();"/>
                                        </div>
                                        <div class="form-group col-lg-4">
                                            <label>Contato(Nome Razão, Nome Fantasia, CPF / CNPJ)</label>
                                            <input type="text" class="form-control contrato_nome_contato"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Status</label>
                                            <select class="form-control search_status">
                                                <option value=""></option>
                                                <option value="0">EM VIGOR</option>
                                                <option value="1">FINALIZADO</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>.</label><br />
                                            <button type="button" class="btn btn-primary" onclick="search();">Pesquisar</button>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                            <hr />
                            <div id="load_contrato_monitoramento"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function() {
        $( ".contrato_nome_contato" ).autocomplete({
            source: "_controller/_contato.php?acao=load_contato",
            minLength: 2,
            focus: function(event, ui) {
            	event.preventDefault();
            },
            select: function( event, ui ) {
                $(".search_id_contato").val(ui.item.value);
                $(".contrato_nome_contato").val(ui.item.label);
                event.preventDefault();
            }
        });
    });
    function buscar_contato(){
        var contrato_chip_id_contato = $(".search_id_contato").val();
        var acao = "acao=load_contato_id&id="+contrato_chip_id_contato;
        
        if(contrato_chip_id_contato){
            $.ajax({
                type: 'POST',
                url: "_controller/_contato.php",
                data: acao,
                beforeSend: load_in(),
                success: function (data) {
                    load_out();
                    var data_return = jQuery.parseJSON(data);
                    if(data_return[0].label !== ''){
                        $(".contrato_nome_contato").val(data_return[0].label);
                    }else{
                        $("#_modal").modal('show');
                        $("#title_modal").html('Erro');
                        $("#texto_modal").html('Ops, não foi encontrado nenhum registro');
                        $("#buttons_modal").html('');
                        $(".search_id_contato").val('');
                        $(".contrato_nome_contato").val('');
                    }
                }
            });
        }
    }
    function enviar_contrato_monitoramento(){
        var email       = $(".emails_contrato_monitoramento").val();
        var id_contrato = $(".id_contrato_monitoramento").val();
        var msg         = $(".msg_contrato_monitoramento").val();
        
        if(email === ''){
            $("#ok_contrato_monitoramento").html('<p>Email está em branco</p>');
        }else if(id_contrato === ''){
            $("#ok_contrato_monitoramento").html('<p>O contrato por algum motivo não pode ser gerado</p>');
        }else if(msg === ''){
            $("#ok_contrato_monitoramento").html('<p>É preciso escrever uma mensagem</p>');
        }else{
            var acao = "acao=enviar_contrato_monitoramento&email="+email+"&msg="+msg+"&id_contrato="+id_contrato;
        
            $.ajax({
                type: 'POST',
                url: "_controller/_contrato_monitoramento.php",
                data: acao,
                beforeSend: load_in(),
                success: function (data) {
                    load_out();
                    $("#ok_contrato_monitoramento").html(data);
                }
            });
        }
    }
    var updateIcon = function (value, data, cell, row, options) { //plain text value
        return "<i class='fa fa-pencil' style='vertical-align:middle; padding:2px 0;' title='Editar'></i> ";
    };
    var refreshIcon = function (value, data, cell, row, options) { //plain text value
        return "<i class='fa fa-refresh' style='vertical-align:middle; padding:2px 0;' title='Gerar documento'></i> ";
    };
    var aditivoIcon = function (value, data, cell, row, options) { //plain text value
        return "<i class='fa fa-bars' style='vertical-align:middle; padding:2px 0;' title='Aditivos contrato'></i> ";
    };
    var downloadIcon = function (value, data, cell, row, options) { //plain text value
        return "<i class='fa fa-cloud-download' style='vertical-align:middle; padding:2px 0;' title='Download arquivos'></i> ";
    };
    var printIcon = function (value, data, cell, row, options) { //plain text value
        return "<i class='fa fa-print' style='vertical-align:middle; padding:2px 0;' title='Imprimir Contrato'></i> ";
    };
    $("#load_contrato_monitoramento").tabulator({
        height: "350px",
        fitColumns: true,
        ajaxURL: "_controller/_contrato_monitoramento.php",
        ajaxParams: {acao: "load"},
        pagination: "remote",
        paginationSize: 100,
        paginationDataSent: {"page": "pageNo"},
        columns: [
            {
                formatter: updateIcon, width: 40, align: "center", onClick: function (e, cell, val, data) {
                    carrega_pagina('contrato', 'update_monitoramento.php?id='+data.contrato_monitoramento_id);
                }
            },
            {
                formatter: refreshIcon, width: 40, align: "center", onClick: function (e, cell, val, data) {
                    $("#_modal_assinatura_contrato_monitoramento").modal('show');
                    $(".id_contrato_monitoramento").val(data.contrato_monitoramento_id);
                    $("#ok_contrato_monitoramento").html('');
                }
            },
            {
                formatter: aditivoIcon, width: 40, align: "center", onClick: function (e, cell, val, data) {
                    carrega_pagina('contrato', 'itens_monitoramento.php?id='+data.contrato_monitoramento_id);
                }
            },
            {
                formatter: downloadIcon, width: 40, align: "center", onClick: function (e, cell, val, data) {
                    download_contrato_monitoramento(data.contrato_monitoramento_id_d4sign);
                }
            },
            {
                formatter: printIcon, width: 40, align: "center", onClick: function (e, cell, val, data) {
                    window.open('contrato/ContratoMonitoramento/index.php?Id='+data.contrato_monitoramento_id,'_blank');
                }
            },
            {title: "ID", field: "contrato_monitoramento_id", sorter: "int", width: 100},
            {title: "Contato", field: "contato_nome_razao", sorter: "string"},
            {title: "Data Inicio", field: "contrato_monitoramento_data_inicial", sorter: "string"},
            {title: "Data Fim", field: "contrato_monitoramento_data_final", sorter: "string"},
            {title: "Status", field: "contrato_monitoramento_status", sorter: "string"},
            {title: "Cliente Assinou?", field: "contrato_monitoramento_cliente_assinou", sorter: "string"}
        ]
    });
    
    function search(){
        var search_id           = $(".search_id").val();
        var search_id_contato   = $(".search_id_contato").val();
        var search_status       = $(".search_status").val();
        
        var acao = "acao=load&search=true&id="+search_id+"&id_contato="+search_id_contato+"&status="+search_status;
        
        $.ajax({
            type: 'GET',
            url: "_controller/_contrato_monitoramento.php",
            data: acao,
            beforeSend: load_in(),
            success: function () {
                load_out();
                $("#load_contrato_monitoramento").tabulator("setData", "_controller/_contrato_monitoramento.php?acao=load");
            }
        });
    }
    function download_contrato_monitoramento(documento){
        window.open('view/contrato/download_contrato_monitoramento.php?arquivo='+documento,'_blank');
    }
</script>
<div id="_modal_assinatura_contrato_monitoramento" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Enviar Email Para Assinatura</h4>
            </div>
            <!-- /.modal-header -->
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-lg-12">
                        <label>Emails</label>
                        <input type="text" class="form-control emails_contrato_monitoramento"/>
                        <input type="hidden" class="form-control id_contrato_monitoramento"/>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-lg-12">
                        <label>Mensagem</label>
                        <textarea class="form-control msg_contrato_monitoramento"></textarea>
                    </div>
                </div>
                <div id="ok_contrato_monitoramento"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="enviar_contrato_monitoramento();">Enviar</button>
            </div>
        </div>
    </div>
</div>