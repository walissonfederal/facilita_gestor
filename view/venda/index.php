<?php
    session_start();
    unset($_SESSION['orcamento_venda']);
    unset($_SESSION['orcamento_venda_valor_unitario']);
    unset($_SESSION['orcamento_venda_forma_pagamento']);
    unset($_SESSION['orcamento_venda_forma_pagamento_data']);
    unset($_SESSION['orcamento_venda_forma_pagamento_obs']);
    unset($_SESSION['orcamento_venda_forma_pagamento_valor']);
    unset($_SESSION['orcamento_venda_forma_pagamento_tipo']);
?>
<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Venda
                                <a href="javascript::" onclick="carrega_pagina('venda', 'create.php');" class="btn btn-primary">Cadastrar Novo</a>
                            </h3>
                        </div>
                        <div class="box-content">
                            <form action="">
                                <fieldset>
                                    <div class="row">
                                        <div class="form-group col-lg-1">
                                            <label>ID Contato</label>
                                            <input type="text" class="form-control search_nome_id_contato" onblur="buscar_contato();"/>
                                        </div>
                                        <div class="form-group col-lg-4">
                                            <label>Contato(Nome Razão, Nome Fantasia, CPF / CNPJ)</label>
                                            <input type="text" class="form-control search_nome_contato"/>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>Situação</label>
                                            <select class="form-control search_situacao">
                                                <option value=""></option>
                                                <option value="0">Em aberto</option>
                                                <option value="1">Em andamento</option>
                                                <option value="2">Atendido</option>
                                                <option value="3">Cancelado</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-1">
                                            <label>ID</label>
                                            <input type="text" class="form-control search_id"/>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>Tipo Pesquisa</label>
                                            <select class="form-control search_tipo_pesquisa">
                                                <option value="orcamento_venda_data">Data</option>
                                                <option value="orcamento_venda_data_prazo">Prazo Entrega</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-3">
                                            <label>Data Inicial</label>
                                            <input type="date" class="form-control search_data_inicial"/>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>Data Final</label>
                                            <input type="date" class="form-control search_data_final"/>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>.</label><br />
                                            <button type="button" class="btn btn-primary" onclick="search();">Pesquisar</button>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                            <hr />
                            <div id="load_orcamento_venda"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function() {
        $( ".search_nome_contato" ).autocomplete({
            source: "_controller/_contato.php?acao=load_contato",
            minLength: 2,
            focus: function(event, ui) {
            	event.preventDefault();
            },
            select: function( event, ui ) {
                $(".search_nome_id_contato").val(ui.item.value);
                $(".search_nome_contato").val(ui.item.label);
                event.preventDefault();
            }
        });
    });
    function buscar_contato(){
        var search_nome_id_contato = $(".search_nome_id_contato").val();
        var acao = "acao=load_contato_id&id="+search_nome_id_contato;
        
        if(search_nome_id_contato){
            $.ajax({
                type: 'POST',
                url: "_controller/_contato.php",
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
                        $(".search_nome_id_contato").val('');
                        $(".search_nome_contato").val('');
                    }
                }
            });
        }
    }
    var updateIcon = function (value, data, cell, row, options) { //plain text value
        return "<i class='fa fa-pencil' style='vertical-align:middle; padding:2px 0;' title='Editar'></i> ";
    };
    var printIcon = function (value, data, cell, row, options) { //plain text value
        return "<i class='fa fa-print' style='vertical-align:middle; padding:2px 0;' title='Imprimir'></i> ";
    };
    $("#load_orcamento_venda").tabulator({
        height: "350px",
        fitColumns: true,
        ajaxURL: "_controller/_venda.php",
        ajaxParams: {acao: "load"},
        pagination: "remote",
        paginationSize: 100,
        paginationDataSent: {"page": "pageNo"},
        columns: [
            {
                formatter: updateIcon, width: 40, align: "center", onClick: function (e, cell, val, data) {
                    carrega_pagina('venda', 'update.php?id='+data.orcamento_venda_id);
                }
            },
            {
                formatter: printIcon, width: 40, align: "center", onClick: function (e, cell, val, data) {
                    window.open('view/venda/print.php?id=' + data.orcamento_venda_id, '_blank');
                }
            },
            {title: "ID", field: "orcamento_venda_id", sorter: "int", width: 200},
            {title: "Data", field: "orcamento_venda_data", sorter: "string"},
            {title: "Cliente", field: "orcamento_venda_id_contato", sorter: "string"},
            {title: "Valor Produtos", field: "orcamento_venda_valor_produtos", sorter: "string"},
            {title: "Valor Total", field: "orcamento_venda_valor_total", sorter: "string"},
            {title: "Situação", field: "orcamento_venda_status", sorter: "string"}
        ]
    });
    
    function search(){
        var search_id               = $(".search_id").val();
        var search_nome_id_contato  = $(".search_nome_id_contato").val();
        var search_situacao         = $(".search_situacao").val();
        var search_tipo_pesquisa    = $(".search_tipo_pesquisa").val();
        var search_data_inicial     = $(".search_data_inicial").val();
        var search_data_final       = $(".search_data_final").val();
        
        var acao = "acao=load&search=true&id="+search_id+"&id_contato="+search_nome_id_contato+"&situacao="+search_situacao+"&tipo_pesquisa="+search_tipo_pesquisa+"&data_inicial="+search_data_inicial+"&data_final="+search_data_final;
        
        $.ajax({
            type: 'GET',
            url: "_controller/_venda.php",
            data: acao,
            beforeSend: load_in(),
            success: function () {
                load_out();
                $("#load_orcamento_venda").tabulator("setData", "_controller/_venda.php?acao=load");
            }
        });
    }
</script>