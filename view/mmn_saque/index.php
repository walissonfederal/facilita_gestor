<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Saque
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
                                        <div class="form-group col-lg-2">
                                            <label>Data Inicial</label>
                                            <input type="date" class="form-control search_data_inicial"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Data Final</label>
                                            <input type="date" class="form-control search_data_final"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-1">
                                            <label>.</label><br />
                                            <button type="button" class="btn btn-primary" onclick="search();">Pesquisar</button>
                                            <a href="_controller/_mmn_saque.php?acao=gerar_excel" target="_blank" class="btn btn-primary">Gerar Excel</a>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                            <hr />
                            <div id="load_saque"></div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="box box-color blue box-condensed box-bordered">
                                        <div class="box-title" align="center">
                                            <h3>
                                                Contas
                                            </h3>
                                        </div>
                                        <div class="box-content">
                                            <div id="saque_quantidade_contas" align="center"><h4></h4></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="box box-color blue box-condensed box-bordered">
                                        <div class="box-title" align="center">
                                            <h3>
                                                Valor total
                                            </h3>
                                        </div>
                                        <div class="box-content">
                                            <div id="saque_valor_total" align="center"><h4></h4></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="box box-color blue box-condensed box-bordered">
                                        <div class="box-title" align="center">
                                            <h3>
                                                Valor taxa
                                            </h3>
                                        </div>
                                        <div class="box-content">
                                            <div id="saque_valor_taxa" align="center"><h4></h4></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="box box-color blue box-condensed box-bordered">
                                        <div class="box-title" align="center">
                                            <h3>
                                                Valor pago
                                            </h3>
                                        </div>
                                        <div class="box-content">
                                            <div id="saque_valor_pago" align="center"><h4></h4></div>
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
    $("#load_saque").tabulator({
        height: "350px",
        fitColumns: true,
        ajaxURL: "_controller/_mmn_saque.php",
        ajaxParams: {acao: "load"},
        pagination: "remote",
        paginationSize: 100,
        paginationDataSent: {"page": "pageNo"},
        columns: [
            {
                formatter: updateIcon, width: 40, align: "center", onClick: function (e, cell, val, data) {
                    carrega_pagina('mmn_saque', 'baixar.php?id='+data.saque_id);
                }
            },
            {title: "ID", field: "saque_id", sorter: "int", width: 75},
            {title: "Data Hora", field: "saque_data_hora", sorter: "string"},
            {title: "Data", field: "saque_data", sorter: "string"},
            {title: "Usuário", field: "user_nome", sorter: "string"},
            {title: "Valor", field: "saque_valor", sorter: "string"},
            {title: "Status", field: "saque_status", sorter: "string"},
            {title: "Taxa", field: "saque_taxa", sorter: "string"},
            {title: "Valor Pagamento", field: "saque_valor_pagamento", sorter: "string"},
            {title: "Data Pagamento", field: "saque_data_pagamento", sorter: "string"}
        ],
        ajaxResponse:function(url, params, response){
            $("#saque_quantidade_contas").html('<h4>'+response.quantidade_contas+'</h4>');
            $("#saque_valor_pago").html('<h4>R$ '+response.saque_valor_pagamento+'</h4>');
            $("#saque_valor_taxa").html('<h4>R$ '+response.saque_taxa+'</h4>');
            $("#saque_valor_total").html('<h4>R$ '+response.saque_valor+'</h4>');
            return response;
        }
    });
    
    function search(){
        var search_id           = $(".search_id").val();
        var search_status       = $(".search_status").val();
        var search_data_inical  = $(".search_data_inicial").val();
        var search_data_final   = $(".search_data_final").val();
        var search_id_user      = $(".search_nome_id_user").val();
        
        var acao = "acao=load&search=true&id="+search_id+"&status="+search_status+"&data_inicial="+search_data_inical+"&data_final="+search_data_final+"&id_user="+search_id_user;
        
        $.ajax({
            type: 'GET',
            url: "_controller/_mmn_saque.php",
            data: acao,
            beforeSend: load_in(),
            success: function () {
                load_out();
                $("#load_saque").tabulator("setData", "_controller/_mmn_saque.php?acao=load");
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