<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Contrato Rastreamento
                                <a href="javascript::" onclick="carrega_pagina('contrato', 'create_rastreamento.php');" class="btn btn-primary">Cadastrar Novo</a>
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
                            <div id="load_contrato_rastreamento"></div>
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
    function gerar_contrato(id_contrato){
        var acao = "acao=gerar_documento&id="+id_contrato;
        
        if(id_contrato){
            $.ajax({
                type: 'POST',
                url: "_controller/_contrato_rastreamento.php",
                data: acao,
                beforeSend: load_in(),
                success: function (data) {
                    load_out();
                    var data_return = jQuery.parseJSON(data);
                    if(data_return.type === 'error'){
                        $("#_modal").modal('show');
                        $("#title_modal").html(data_return.title);
                        $("#texto_modal").html(data_return.msg);
                        $("#buttons_modal").html(data_return.buttons);
                    }else{
                        $("#_modal").modal('show');
                        $("#title_modal").html(data_return.title);
                        $("#texto_modal").html(data_return.msg);
                        $("#buttons_modal").html(data_return.buttons);
                    }
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
    $("#load_contrato_rastreamento").tabulator({
        height: "350px",
        fitColumns: true,
        ajaxURL: "_controller/_contrato_rastreamento.php",
        ajaxParams: {acao: "load"},
        pagination: "remote",
        paginationSize: 100,
        paginationDataSent: {"page": "pageNo"},
        columns: [
            {
                formatter: updateIcon, width: 40, align: "center", onClick: function (e, cell, val, data) {
                    carrega_pagina('contrato', 'update_rastreamento.php?id='+data.contrato_rastreamento_id);
                }
            },
            {
                formatter: refreshIcon, width: 40, align: "center", onClick: function (e, cell, val, data) {
                    gerar_contrato(data.contrato_rastreamento_id);
                }
            },
            {
                formatter: aditivoIcon, width: 40, align: "center", onClick: function (e, cell, val, data) {
                    carrega_pagina('contrato', 'aditivo_rastreamento.php?id='+data.contrato_rastreamento_id);
                }
            },
            {
                formatter: downloadIcon, width: 40, align: "center", onClick: function (e, cell, val, data) {
                    download_contrato_chip(data.contrato_rastreamento_id_d4sign);
                }
            },
            {
                formatter: printIcon, width: 40, align: "center", onClick: function (e, cell, val, data) {
                    window.open('contrato/ContratoRastreador/index.php?Id='+data.contrato_rastreamento_id,'_blank');
                }
            },
            {title: "ID", field: "contrato_rastreamento_id", sorter: "int", width: 100},
            {title: "Contato", field: "contato_nome_razao", sorter: "string"},
            {title: "Data Inicio", field: "contrato_rastreamento_data_inicial", sorter: "string"},
            {title: "Data Fim", field: "contrato_rastreamento_data_final", sorter: "string"},
            {title: "Status", field: "contrato_rastreamento_status", sorter: "string"},
            {title: "Cliente Assinou?", field: "contrato_rastreamento_cliente_assinou", sorter: "string"}
        ]
    });
    
    function search(){
        var search_id           = $(".search_id").val();
        var search_id_contato   = $(".search_id_contato").val();
        var search_status       = $(".search_status").val();
        
        var acao = "acao=load&search=true&id="+search_id+"&id_contato="+search_id_contato+"&status="+search_status;
        
        $.ajax({
            type: 'GET',
            url: "_controller/_contrato_rastreamento.php",
            data: acao,
            beforeSend: load_in(),
            success: function () {
                load_out();
                $("#load_contrato_rastreamento").tabulator("setData", "_controller/_contrato_rastreamento.php?acao=load");
            }
        });
    }
    function download_contrato_chip(documento){
        window.open('view/contrato/download_contrato_rastreamento.php?arquivo='+documento,'_blank');
    }
</script>