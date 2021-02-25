<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Pedidos
                            </h3>
                        </div>
                        <div class="box-content">
                            <form action="">
                                <fieldset>
                                    <div class="row">
                                        <div class="form-group col-lg-1">
                                            <label>ID</label>
                                            <input type="text" class="form-control search_id"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Data Inicial</label>
                                            <input type="date" class="form-control search_data_inicial"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Data Final</label>
                                            <input type="date" class="form-control search_data_final"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Status</label>
                                            <select class="form-control search_status">
                                                <option value=""></option>
                                                <option value="0">Aberto</option>
                                                <option value="1">Pago</option>
                                                <option value="2">Cancelado</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-1">
                                            <label>ID User</label>
                                            <input type="text" class="form-control search_nome_id_user" onblur="buscar_contato();"/>
                                        </div>
                                        <div class="form-group col-lg-4">
                                            <label>User (Nome, CPF)</label>
                                            <input type="text" class="form-control search_nome_user"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-2">
                                            <label>Enviado?</label>
                                            <select class="form-control search_enviado">
                                                <option value=""></option>
                                                <option value="0">Não</option>
                                                <option value="1">Sim</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>.</label><br />
                                            <button type="button" class="btn btn-primary" onclick="search();">Pesquisar</button>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                            <hr />
                            <div id="load_pedidos"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var updateIcon = function (value, data, cell, row, options) { //plain text value
        return "<i class='fa fa-print' style='vertical-align:middle; padding:2px 0;' title='Editar'></i> ";
    };
    $("#load_pedidos").tabulator({
        height: "350px",
        fitColumns: true,
        ajaxURL: "_controller/_mmn_pedidos.php",
        ajaxParams: {acao: "load"},
        pagination: "remote",
        paginationSize: 100,
        paginationDataSent: {"page": "pageNo"},
        columns: [
            {
                formatter: updateIcon, width: 40, align: "center", onClick: function (e, cell, val, data) {
                    carrega_pagina('mmn_pedidos', 'update.php?id='+data.pedido_id);
                }
            },
            {title: "ID", field: "pedido_id", sorter: "int", width: 75},
            {title: "Usuário", field: "user_nome", sorter: "string"},
            {title: "Data", field: "pedido_data", sorter: "string"},
            {title: "Valor Planos", field: "pedido_valor_chip", sorter: "string"},
            {title: "Valor Frete", field: "valor_frete", sorter: "string"},
            {title: "Status", field: "pedido_status", sorter: "string"},
            {title: "Valor Total", field: "pedido_valor", sorter: "string"},
            {title: "Enviado?", field: "pedido_envio", sorter: "string"},
            {title: "Código Rastreio", field: "pedido_codigo_rastreio", sorter: "string"}
        ]
    });
    
    function search(){
        var search_id           = $(".search_id").val();
        var search_data_inicial = $(".search_data_inicial").val();
        var search_data_final   = $(".search_data_final").val();
        var search_status       = $(".search_status").val();
        var search_enviado      = $(".search_enviado").val();
        var search_nome_id_user = $(".search_nome_id_user").val();
        
        var acao = "acao=load&search=true&id="+search_id+"&data_inicial="+search_data_inicial+"&data_final="+search_data_final+"&status="+search_status+"&enviado="+search_enviado+"&id_user="+search_nome_id_user;
        
        $.ajax({
            type: 'GET',
            url: "_controller/_mmn_pedidos.php",
            data: acao,
            beforeSend: load_in(),
            success: function () {
                load_out();
                $("#load_pedidos").tabulator("setData", "_controller/_mmn_pedidos.php?acao=load");
            }
        });
    }
    $(function() {
        $( ".search_nome_user" ).autocomplete({
            source: "_controller/_mmn_pedidos.php?acao=load_user",
            minLength: 2,
            focus: function(event, ui) {
            	event.preventDefault();
            },
            select: function( event, ui ) {
                $(".search_nome_id_user").val(ui.item.value);
                $(".search_nome_user").val(ui.item.label);
                event.preventDefault();
            }
        });
    });
    function buscar_contato(){
        var search_nome_id_contato = $(".search_nome_id_user").val();
        var acao = "acao=load_user_id&id="+search_nome_id_contato;
        
        if(search_nome_id_contato){
            $.ajax({
                type: 'POST',
                url: "_controller/_mmn_pedidos.php",
                data: acao,
                beforeSend: load_in(),
                success: function (data) {
                    load_out();
                    var data_return = jQuery.parseJSON(data);
                    if(data_return[0].label !== ''){
                        $(".search_nome_contato").val(data_return[0].label);
                    }else{
                        $("#_modal").modal('show');
                        $("#title_modal").html('Erro');
                        $("#texto_modal").html('Ops, não foi encontrado nenhum registro');
                        $("#buttons_modal").html('');
                        $(".search_nome_id_user").val('');
                        $(".search_nome_user").val('');
                    }
                }
            });
        }
    }
</script>