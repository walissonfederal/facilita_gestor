<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Pedidos
                                <a href="javascript::" onclick="carrega_pagina('pedido', 'create_instalacao.php');" class="btn btn-primary">Cadastrar Instalação</a>
                                <a href="javascript::" onclick="carrega_pagina('pedido', 'create_desinstalacao.php');" class="btn btn-danger">Cadastrar Desinstalação</a>
                                <a href="javascript::" onclick="carrega_pagina('pedido', 'create_sms.php');" class="btn btn-success">Cadastrar SMS</a>
                            </h3>
                        </div>
                        <div class="box-content">
                            <form action="">
                                <fieldset>
                                    <div class="row">
                                        <div class="form-group col-lg-2">
                                            <label>ID Contato</label>
                                            <input type="text" class="form-control search_id_contato" onblur="buscar_contato();"/>
                                        </div>
                                        <div class="form-group col-lg-4">
                                            <label>Contato(Nome Razão, Nome Fantasia, CPF / CNPJ)</label>
                                            <input type="text" class="form-control search_nome_cliente"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>ID</label>
                                            <input type="text" class="form-control search_id"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Plano</label>
                                            <select class="form-control search_id_plano"></select>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Tipo</label>
                                            <select class="form-control search_tipo">
                                                <option value=""></option>
                                                <option value="0">Instalação</option>
                                                <option value="1">Desinstalação</option>
                                                <option value="2">SMS</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-2">
                                            <label>Tipo Frete</label>
                                            <select class="form-control search_tipo_frete">
                                                <option value=""></option>
                                                <option value="0">Carta registrada</option>
                                                <option value="1">PAC</option>
                                                <option value="2">Sedex</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Plano SMS</label>
                                            <select class="form-control search_id_plano_sms"></select>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Status</label>
                                            <select class="form-control search_status">
                                                <option value=""></option>
                                                <option value="0">Em andamento</option>
                                                <option value="1">Finalizado</option>
                                                <option value="2">Cancelado</option>
                                                <option value="3">Bloqueado</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Código Rastreio</label>
                                            <input type="text" class="form-control search_codigo_rastreio"/>
                                        </div>
                                        <div class="form-group col-lg-1">
                                            <label>.</label><br />
                                            <button type="button" class="btn btn-primary" onclick="search();">Pesquisar</button>
											 | 
											<a href="view/pedido/excel_chip.php?id_contato=" target="_blank" class="btn btn-primary">Chips</a>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                            <hr />
                            <div id="load_pedido"></div>
                            <div class="row">
                                <div class="col-sm-2">
                                    <div class="box box-color blue box-condensed box-bordered">
                                        <div class="box-title" align="center">
                                            <h3>
                                                Pedido
                                            </h3>
                                        </div>
                                        <div class="box-content">
                                            <div id="pedido_quantidade" align="center"><h4></h4></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="box box-color blue box-condensed box-bordered">
                                        <div class="box-title" align="center">
                                            <h3>
                                                Finalizado
                                            </h3>
                                        </div>
                                        <div class="box-content">
                                            <div id="pedido_quantidade_finalizado" align="center"><h4></h4></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="box box-color blue box-condensed box-bordered">
                                        <div class="box-title" align="center">
                                            <h3>
                                                Cancelado
                                            </h3>
                                        </div>
                                        <div class="box-content">
                                            <div id="pedido_quantidade_cancelado" align="center"><h4></h4></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="box box-color blue box-condensed box-bordered">
                                        <div class="box-title" align="center">
                                            <h3>
                                                Bloqueado
                                            </h3>
                                        </div>
                                        <div class="box-content">
                                            <div id="quantidade_pedido_bloqueado" align="center"><h4></h4></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="box box-color blue box-condensed box-bordered">
                                        <div class="box-title" align="center">
                                            <h3>
                                                Quantidade Chip
                                            </h3>
                                        </div>
                                        <div class="box-content">
                                            <div id="pedido_quantidade_chip" align="center"><h4></h4></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var updateIcon = function (value, data, cell, row, options) { //plain text value
        return "<i class='fa fa-pencil' style='vertical-align:middle; padding:2px 0;' title='Editar'></i> ";
    };
    var chipIcon = function (value, data, cell, row, options) { //plain text value
        return "<i class='fa fa-columns' style='vertical-align:middle; padding:2px 0;' title='Chips'></i> ";
    };
    $("#load_pedido").tabulator({
        height: "350px",
        fitColumns: true,
        ajaxURL: "_controller/_pedido.php",
        ajaxParams: {acao: "load"},
        pagination: "remote",
        paginationSize: 100,
        paginationDataSent: {"page": "pageNo"},
        columns: [
            {
                formatter: updateIcon, width: 40, align: "center", onClick: function (e, cell, val, data) {
                    if(data.pedido_tipo_type === '0'){
                        carrega_pagina('pedido', 'update_instalacao.php?id='+data.pedido_id);
                    }else if(data.pedido_tipo_type === '1'){
                        carrega_pagina('pedido', 'update_desinstalacao.php?id='+data.pedido_id);
                    }else if(data.pedido_tipo_type === '2'){
                        carrega_pagina('pedido', 'update_sms.php?id='+data.pedido_id);
                    }
                }
            },
            {
                formatter: chipIcon, width: 40, align: "center", onClick: function (e, cell, val, data) {
                    carrega_pagina('pedido', 'chip.php?id='+data.pedido_id);
                }
            },
            {title: "ID", field: "pedido_id", sorter: "int", width: 50},
            {title: "Cliente", field: "contato_nome_razao", sorter: "string"},
            {title: "Data Ativação", field: "pedido_data_ativacao", sorter: "string"},
            {title: "Valor Plano SMS", field: "pedido_valor_plano_sms", sorter: "string"},
            {title: "Valor Ativação", field: "pedido_valor_ativacao", sorter: "string"},
            {title: "Status", field: "pedido_status", sorter: "string"},
            {title: "Tipo", field: "pedido_tipo", sorter: "string"},
            {title: "Qtd Chips", field: "pedido_qtd_chips", sorter: "string"},
            {title: "Total Ativação", field: "valor_total_ativacao", sorter: "string"}
        ],
        ajaxResponse:function(url, params, response){
            $("#pedido_quantidade").html('<h4>'+response.quantidade_pedido+'</h4>');
            $("#pedido_quantidade_finalizado").html('<h4>'+response.quantidade_pedido_finalizado+'</h4>');
            $("#pedido_quantidade_cancelado").html('<h4>'+response.quantidade_pedido_cancelado+'</h4>');
            $("#pedido_quantidade_bloqueado").html('<h4>'+response.quantidade_pedido_bloqueado+'</h4>');
            $("#pedido_quantidade_chip").html('<h4>'+response.quantidade_pedido_chip+'</h4>');
            return response;
        }
    });
    $(function() {
        load_plano();
        load_plano_sms();
        $( ".search_nome_cliente" ).autocomplete({
            source: "_controller/_contato.php?acao=load_contato",
            minLength: 2,
            focus: function(event, ui) {
            	event.preventDefault();
            },
            select: function( event, ui ) {
                $(".search_id_contato").val(ui.item.value);
                $(".search_nome_cliente").val(ui.item.label);
                event.preventDefault();
            }
        });
    });
    function buscar_contato(){
        var id_cliente = $(".search_id_contato").val();
        var acao = "acao=load_contato_id&id="+id_cliente;
        
        if(id_cliente){
            $.ajax({
                type: 'POST',
                url: "_controller/_contato.php",
                data: acao,
                beforeSend: load_in(),
                success: function (data) {
                    load_out();
                    var data_return = jQuery.parseJSON(data);
                    if(data_return[0].label !== ''){
                        $(".search_nome_cliente").val(data_return[0].label);
                    }else{
                        $("#_modal").modal('show');
                        $("#title_modal").html('Erro');
                        $("#texto_modal").html('Ops, não foi encontrado nenhum registro');
                        $("#buttons_modal").html('');
                        $(".search_id_contato").val('');
                        $(".search_nome_cliente").val('');
                    }
                }
            });
        }
    }
    function search(){
        var search_id_contato   = $(".search_id_contato").val();
        var search_id           = $(".search_id").val();
        var search_id_plano     = $(".search_id_plano").val();
        var search_tipo         = $(".search_tipo").val();
        var search_tipo_frete   = $(".search_tipo_frete").val();
        var search_id_plano_sms = $(".search_id_plano_sms").val();
        var search_status       = $(".search_status").val();
        var search_codigo_rastreio = $(".search_codigo_rastreio").val();
        
        var acao = "acao=load&search=true&id="+search_id+"&id_contato="+search_id_contato+"&id_plano="+search_id_plano+"&tipo="+search_tipo+"&tipo_frete="+search_tipo_frete+"&id_plano_sms="+search_id_plano_sms+"&status="+search_status+"&codigo_rastreio="+search_codigo_rastreio;
        
        $.ajax({
            type: 'GET',
            url: "_controller/_pedido.php",
            data: acao,
            beforeSend: load_in(),
            success: function () {
                load_out();
                $("#load_pedido").tabulator("setData", "_controller/_pedido.php?acao=load");
            }
        });
    }
    function load_plano(){
        var acao = "acao=load_plano";
        $.ajax({
            type: 'GET',
            url: "_controller/_pedido.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                var data_return = jQuery.parseJSON(data);
                var options = '<option value=""></option>';
                for (var i = 0; i < data_return.data.length; i++) {
                    options += '<option value="' + data_return.data[i].plano_id + '">' + data_return.data[i].plano_descricao + '</option>';
                }
                $('.search_id_plano').html(options).show();
                load_out();
            }
        });
        load_out();
    }
    function load_plano_sms(){
        var acao = "acao=load_plano_sms";
        $.ajax({
            type: 'GET',
            url: "_controller/_pedido.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                var data_return = jQuery.parseJSON(data);
                var options = '<option value=""></option>';
                for (var i = 0; i < data_return.data.length; i++) {
                    options += '<option value="' + data_return.data[i].plano_id + '">' + data_return.data[i].plano_descricao + '</option>';
                }
                $('.search_id_plano_sms').html(options).show();
                load_out();
            }
        });
        load_out();
    }
</script>