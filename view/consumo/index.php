<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Consumo Mensal SMS
                            </h3>
                        </div>
                        <div class="box-content">
                            <form action="">
                                <fieldset>
                                    <div class="row">
                                        <div class="form-group col-lg-2">
                                            <label>ID Pedido</label>
                                            <input type="text" class="form-control search_id"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Referência</label>
                                            <input type="text" class="form-control search_referencia referencia_faturamento_mask" value="<?php echo date('m/Y');?>"/>
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
                                            <label>.</label><br />
                                            <button type="button" class="btn btn-primary" onclick="search();">Pesquisar</button>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                            <hr />
                            <div id="load_consumo"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="_modal_consumo" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Informaçõs de Consumo SMS</h4>
            </div>
            <!-- /.modal-header -->
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-sm-12">
                        <label>Refrência</label>
                        <input type="text" class="form-control referencia_faturamento_mask referencia_informar"/>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-12">
                        <label>Quantidade de SMS utilizados?</label>
                        <input type="number" class="form-control qtd_informar" onblur="somar_excedente();"/>
                        <input type="hidden" class="id_pedido_informar"/>
                        <input type="hidden" class="id_contato_informar"/>
                    </div>
                </div>
                <div id="somar_excedente"></div>
            </div>
            <!-- /.modal-body -->
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="gravar_sms();">Gravar</button>
            </div>
            <!-- /.modal-footer -->
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<script>
    var updateIcon = function (value, data, cell, row, options) { //plain text value
        return "<i class='fa fa-credit-card' style='vertical-align:middle; padding:2px 0;' title='Informar Consumo'></i> ";
    };
    $("#load_consumo").tabulator({
        height: "350px",
        fitColumns: true,
        ajaxURL: "_controller/_consumo.php",
        ajaxParams: {acao: "load"},
        pagination: "remote",
        paginationSize: 100,
        paginationDataSent: {"page": "pageNo"},
        columns: [
            {
                formatter: updateIcon, width: 40, align: "center", onClick: function (e, cell, val, data) {
                    //carrega_pagina('caixa', 'update.php?id='+data.pedido_id+);
                    $("#_modal_consumo").modal('show');
                    $(".id_pedido_informar").val(data.pedido_id);
                    $(".id_contato_informar").val(data.pedido_id_cliente);
                    $("#somar_excedente").html('');
                    $(".referencia_informar").val('');
                    $(".qtd_informar").val('');
                }
            },
            {title: "ID Contato", field: "pedido_id_cliente", sorter: "int", width: 100},
            {title: "Contato", field: "contato_nome_razao", sorter: "string"},
            {title: "ID Pedido", field: "pedido_id", sorter: "string"},
            {title: "Referência", field: "consumo_referencia", sorter: "string"},
            {title: "Valor Excedente", field: "consumo_valor_excedente", sorter: "string"}
        ]
    });
    $(function(){
        search();
    });
    function search(){
        var search_id           = $(".search_id").val();
        var search_referencia   = $(".search_referencia").val();
        var search_id_contato   = $(".search_id_contato").val();
        
        var acao = "acao=load&search=true&id="+search_id+"&referencia="+search_referencia+"&id_contato="+search_id_contato;
        
        $.ajax({
            type: 'GET',
            url: "_controller/_consumo.php",
            data: acao,
            beforeSend: load_in(),
            success: function () {
                load_out();
                $("#load_consumo").tabulator("setData", "_controller/_consumo.php?acao=load");
            }
        });
    }
    function somar_excedente(){
        var referencia_informar = $(".referencia_informar").val();
        var qtd_informar        = $(".qtd_informar").val();
        var id_pedido_informar  = $(".id_pedido_informar").val();
        var id_contato_informar = $(".id_contato_informar").val();
        
        var acao = "acao=excedente&referencia="+referencia_informar+"&quantidade="+qtd_informar+"&id_pedido="+id_pedido_informar+"&id_contato="+id_contato_informar;
        
        $.ajax({
            type: 'POST',
            url: "_controller/_consumo.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                $("#somar_excedente").html(data);
            }
        });
    }
    function gravar_sms(){
        var referencia_informar = $(".referencia_informar").val();
        var qtd_informar        = $(".qtd_informar").val();
        var id_pedido_informar  = $(".id_pedido_informar").val();
        var id_contato_informar = $(".id_contato_informar").val();
        
        var acao = "acao=informar&referencia="+referencia_informar+"&quantidade="+qtd_informar+"&id_pedido="+id_pedido_informar+"&id_contato="+id_contato_informar;
        
        $.ajax({
            type: 'POST',
            url: "_controller/_consumo.php",
            data: acao,
            beforeSend: load_in(),
            success: function () {
                load_out();
                $("#load_consumo").tabulator("setData", "_controller/_consumo.php?acao=load");
                $("#_modal_consumo").modal('hide');
                $(".id_pedido_informar").val('');
                $(".id_contato_informar").val('');
            }
        });
    }
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
</script>