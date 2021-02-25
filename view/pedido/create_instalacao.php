<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Pedidos
                                <a href="javascript::" onclick="carrega_pagina('pedido', 'index.php');" class="btn btn-primary">Voltar</a>
                            </h3>
                        </div>
                        <div class="box-content">
                            <form action="" id="create">
                                <fieldset>
                                    <div class="row">
                                        <div class="form-group col-lg-2">
                                            <label>ID Contato</label>
                                            <input type="text" class="form-control pedido_id_cliente" onblur="buscar_contato();" name="pedido_id_cliente"/>
                                        </div>
                                        <div class="form-group col-lg-10">
                                            <label>Contato(Nome Razão, Nome Fantasia, CPF / CNPJ)</label>
                                            <input type="text" class="form-control pedido_nome_cliente"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-2">
                                            <label>Data Pedido</label>
                                            <input type="date" class="form-control pedido_data" name="pedido_data"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Data Ativação</label>
                                            <input type="date" class="form-control pedido_data_ativacao" name="pedido_data_ativacao"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Status</label>
                                            <select name="pedido_status" class="form-control">
                                                <option value="0">Em andamento</option>
                                                <option value="1">Finalizado</option>
                                                <option value="2">Cancelado</option>
                                                <option value="3">Bloqueado</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Plano</label>
                                            <select name="pedido_id_plano" class="form-control pedido_id_plano" onchange="buscar_valor_plano();"></select>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Valor Plano</label>
                                            <input type="text" class="form-control pedido_valor_plano" name="pedido_valor_plano" id="valor_1"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Valor Ativação</label>
                                            <input type="text" class="form-control pedido_valor_ativacao" name="pedido_valor_ativacao" id="valor_2"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-2">
                                            <label>Tipo Frete</label>
                                            <select class="form-control pedido_tipo_frete" name="pedido_tipo_frete">
                                                <option value="0">Carta registrada</option>
                                                <option value="1">PAC</option>
                                                <option value="2">Sedex</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Valor Frete</label>
                                            <input type="text" class="form-control pedido_valor_frete" name="pedido_valor_frete" id="valor_4"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Código Rastreio</label>
                                            <input type="text" class="form-control pedido_codigo_rastreio" name="pedido_codigo_rastreio"/>
                                        </div>
                                        <div class="form-group col-lg-6">
                                            <label>Associado</label>
                                            <select name="pedido_id_associado" class="form-control pedido_id_associado"></select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-12">
                                            <label>Obs</label>
                                            <textarea name="pedido_obs" class="form-control pedido_obs"></textarea>
                                        </div>
                                    </div>
                                    <input type="hidden" name="pedido_tipo" value="0"/>
                                    <div class="row">
                                        <div class="form-group col-lg-12" align="right">
                                            <button type="button" class="btn btn-primary" onclick="create();">Gravar</button>
                                            <a href="javascript::" onclick="carrega_pagina('pedido', 'index.php');" class="btn btn-danger">Voltar</a>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function() {
        load_plano();
        load_plano_sms();
        $( ".pedido_nome_cliente" ).autocomplete({
            source: "_controller/_contato.php?acao=load_contato",
            minLength: 2,
            focus: function(event, ui) {
            	event.preventDefault();
            },
            select: function( event, ui ) {
                $(".pedido_id_cliente").val(ui.item.value);
                $(".pedido_nome_cliente").val(ui.item.label);
                event.preventDefault();
            }
        });
    });
    function buscar_contato(){
        var pedido_id_cliente = $(".pedido_id_cliente").val();
        var acao = "acao=load_contato_id&id="+pedido_id_cliente;
        
        if(pedido_id_cliente){
            $.ajax({
                type: 'POST',
                url: "_controller/_contato.php",
                data: acao,
                beforeSend: load_in(),
                success: function (data) {
                    load_out();
                    var data_return = jQuery.parseJSON(data);
                    if(data_return[0].label !== ''){
                        $(".pedido_nome_cliente").val(data_return[0].label);
                    }else{
                        $("#_modal").modal('show');
                        $("#title_modal").html('Erro');
                        $("#texto_modal").html('Ops, não foi encontrado nenhum registro');
                        $("#buttons_modal").html('');
                        $(".pedido_id_cliente").val('');
                        $(".pedido_nome_cliente").val('');
                    }
                }
            });
        }
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
                $('.pedido_id_plano').html(options).show();
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
                $('.pedido_id_plano_sms').html(options).show();
                load_out();
            }
        });
        load_out();
    }
    function buscar_valor_plano(){
        var valor_plano = $(".pedido_id_plano").val();
        var acao = "acao=search_valor_plano&valor_plano="+valor_plano;
        $.ajax({
            type: 'GET',
            url: "_controller/_pedido.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                $(".pedido_valor_plano").val(data);
                load_out();
            }
        });
        load_out();
    }
    function buscar_valor_plano_sms(){
        var valor_plano_sms = $(".pedido_id_plano_sms").val();
        var acao = "acao=search_valor_plano_sms&valor_plano_sms="+valor_plano_sms;
        $.ajax({
            type: 'GET',
            url: "_controller/_pedido.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                $(".pedido_valor_plano_sms").val(data);
                load_out();
            }
        });
        load_out();
    }
    function create(){
        var dados = $("#create").serialize();
        var acao = "&acao=create";
        
        $.ajax({
            type: 'POST',
            url: "_controller/_pedido.php",
            data: dados+acao,
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
    $(function(){
        var acao = "acao=load_associado_select";
        $.ajax({
            type: 'GET',
            url: "_controller/_associado.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                var data_return = jQuery.parseJSON(data);
                var options = '<option value="0"></option>';
                for (var i = 0; i < data_return.data.length; i++) {
                    options += '<option value="' + data_return.data[i].associado_id + '">' + data_return.data[i].associado_nome_razao + '</option>';
                }
                $('.pedido_id_associado').html(options).show();
                load_out();
            }
        });
        load_out();
    });
    function limpar_campos(){
        $(".cargo_descricao").val('');
        $(".cargo_status").val('0');
    }
    $("#valor_1").maskMoney({thousands:'', decimal:'.'});
    $("#valor_2").maskMoney({thousands:'', decimal:'.'});
    $("#valor_3").maskMoney({thousands:'', decimal:'.'});
    $("#valor_4").maskMoney({thousands:'', decimal:'.'});
</script>