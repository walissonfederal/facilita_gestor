<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Faturamento
                                <a href="javascript::" onclick="carrega_pagina('faturamento', 'create.php');" class="btn btn-primary">Cadastrar Novo</a>
                            </h3>
                        </div>
                        <div class="box-content">
                            <form action="">
                                <fieldset>
                                    <div class="row">
                                        <div class="form-group col-lg-2">
                                            <label>ID</label>
                                            <input type="text" class="form-control id_faturamento"/>
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
                                            <label>Referência (MM/AAAA)</label>
                                            <input type="text" class="form-control referencia"/>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>.</label><br />
                                            <button type="button" class="btn btn-primary" onclick="search();">Pesquisar</button>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                            <hr />
                            <div id="load_faturamento"></div>
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
    var printIcon = function (value, data, cell, row, options) { //plain text value
        return "<i class='fa fa-print' style='vertical-align:middle; padding:2px 0;' title='Imprimir Faturamento'></i> ";
    };
    var infoIcon = function (value, data, cell, row, options) { //plain text value
        return "<i class='fa fa-info' style='vertical-align:middle; padding:2px 0;' title='Informações Faturamento'></i> ";
    };
    $("#load_faturamento").tabulator({
        height: "350px",
        fitColumns: true,
        ajaxURL: "_controller/_faturamento.php",
        ajaxParams: {acao: "load"},
        pagination: "remote",
        paginationSize: 100,
        paginationDataSent: {"page": "pageNo"},
        columns: [
            {
                formatter: printIcon, width: 40, align: "center", onClick: function (e, cell, val, data) {
                    window.open('view/faturamento/report.php?id_faturamento='+data.faturamento_id,'_blank');
                }
            },
            {
                formatter: infoIcon, width: 40, align: "center", onClick: function (e, cell, val, data) {
                    info_faturamento(data.faturamento_id);
                }
            },
            {title: "ID", field: "faturamento_id", sorter: "int", width: 50},
            {title: "Referência", field: "faturamento_referencia", sorter: "string", width: 110},
            {title: "ID Contato", field: "contato_id", sorter: "string", width: 105},
            {title: "Contato", field: "contato_nome_fantasia", sorter: "string"}
        ]
    });
    function info_faturamento(id_faturamento){
        var acao = "acao=info_faturamento&id_faturamento="+id_faturamento;
        $.ajax({
            type: 'POST',
            url: "_controller/_faturamento.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                var data_return = jQuery.parseJSON(data);
                $("#_modal").modal('show');
                $("#title_modal").html('Informações do faturamento');
                $("#texto_modal").html(data_return.msg);
                $("#buttons_modal").html('');
            }
        });
    }
    function search(){
        var id_faturamento  = $(".id_faturamento").val();
        var id_contato      = $(".search_id_contato").val();
        var referencia      = $(".referencia").val();
        
        var acao = "acao=load&search=true&id="+id_faturamento+"&id_contato="+id_contato+"&referencia="+referencia;
        
        $.ajax({
            type: 'GET',
            url: "_controller/_faturamento.php",
            data: acao,
            beforeSend: load_in(),
            success: function () {
                load_out();
                $("#load_faturamento").tabulator("setData", "_controller/_faturamento.php?acao=load");
            }
        });
    }
</script>