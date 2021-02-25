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
                                <a href="javascript::" onclick="carrega_pagina('venda', 'index.php');" class="btn btn-primary">Voltar</a>
                            </h3>
                        </div>
                        <div class="box-content">
                            <form action="" id="update">
                                <fieldset>
                                    <div class="row">
                                        <div class="form-group col-lg-2">
                                            <label>ID Contato</label>
                                            <input type="text" class="form-control orcamento_venda_id_contato" onblur="buscar_contato();" name="orcamento_venda_id_contato"/>
                                        </div>
                                        <div class="form-group col-lg-10">
                                            <label>Contato(Nome Razão, Nome Fantasia, CPF / CNPJ)</label>
                                            <input type="text" class="form-control orcamento_venda_nome_contato"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-2">
                                            <label>ID Produto</label>
                                            <input type="text" class="form-control itens_orcamento_venda_id_produto" onblur="buscar_produto();"/>
                                        </div>
                                        <div class="form-group col-lg-5">
                                            <label>Produto(Descrição, Código Personalizado)</label>
                                            <input type="text" class="form-control itens_orcamento_venda_nome_produto"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Quantidade</label>
                                            <input type="text" class="form-control itens_orcamento_venda_qtd" id="peso_1" value="1.000"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Valor Produto</label>
                                            <input type="text" class="form-control itens_orcamento_venda_valor_unitario" id="valor_1"/>
                                        </div>
                                        <div class="form-group col-lg-1">
                                            <label>.</label><br />
                                            <button type="button" class="btn btn-primary" onclick="insert_produto();">Inserir</button>
                                        </div>
                                        <div id="load_produto_grid"></div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-12" align="right">
                                            <button type="button" class="btn btn-primary" onclick="faturar();">Faturar (F2)</button>
                                            <a href="javascript::" onclick="carrega_pagina('venda', 'index.php');" class="btn btn-danger">Cancelar</a>
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
<div id="faturar_venda_orcamento" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Faturar</h4>
            </div>
            <div class="modal-body">
                <form action="">
                    <fieldset>
                        <div class="row">
                            <div class="form-group col-lg-3">
                                <label>Valor Produtos</label>
                                <input type="text" class="form-control valor_produtos" id="valor_2" readonly="" />
                            </div>
                            <div class="form-group col-lg-3">
                                <label>Valor Frete (R$)</label>
                                <input type="text" class="form-control valor_frete" id="valor_3" onblur="valor_total_geral()" />
                            </div>
                            <div class="form-group col-lg-3">
                                <label>Desconto (R$)</label>
                                <input type="text" class="form-control valor_desconto_real" id="valor_4" onblur="valor_total_geral()" />
                            </div>
                            <div class="form-group col-lg-3">
                                <label>Desconto (%)</label>
                                <input type="text" class="form-control valor_desconto_perc" id="valor_5" onblur="valor_total_geral()" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-12">
                                <label>Valor Total (R$)</label>
                                <input type="text" class="form-control valor_total" readonly="" />
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="frete();">Próximo Passo (F4)</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
<div id="frete_venda_orcamento" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Frete</h4>
            </div>
            <div class="modal-body">
                <form action="">
                    <fieldset>
                        <div class="row">
                            <div class="form-group col-lg-4">
                                <label>Modalidade Frete</label>
                                <select class="form-control orcamento_venda_modalidade_frete" name="orcamento_venda_modalidade_frete">
                                    <option value="0">Remetente</option>
                                    <option value="1">Destinatário</option>
                                    <option value="2">Sem Frete</option>
                                </select>
                            </div>
                            <div class="form-group col-lg-8">
                                <label>Transportadora</label>
                                <input type="text" class="form-control orcamento_venda_transportadora" name="orcamento_venda_transportadora" />
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="detalhe();">Próximo Passo (F7)</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
<div id="detalhe_venda_orcamento" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Detalhes</h4>
            </div>
            <div class="modal-body">
                <form action="">
                    <fieldset>
                        <div class="row">
                            <div class="form-group col-lg-6">
                                <label>Data</label>
                                <input type="date" class="form-control orcamento_venda_data" name="orcamento_venda_data" />
                            </div>
                            <div class="form-group col-lg-6">
                                <label>Prazo Entrega</label>
                                <input type="date" class="form-control orcamento_venda_data_prazo" name="orcamento_venda_data_prazo" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-6">
                                <label>Validade</label>
                                <input type="text" class="form-control orcamento_venda_validade" name="orcamento_venda_validade" />
                            </div>
                            <div class="form-group col-lg-6">
                                <label>Referência</label>
                                <input type="text" class="form-control orcamento_venda_ref" name="orcamento_venda_ref" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-6">
                                <label>Observações</label>
                                <textarea name="orcamento_venda_obs" class="form-control orcamento_venda_obs" cols="" rows=""></textarea>
                            </div>
                            <div class="form-group col-lg-6">
                                <label>Observações Internas</label>
                                <textarea name="orcamento_venda_obs_interno" class="form-control orcamento_venda_obs_interno" cols="" rows=""></textarea>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="pagamento();">Próximo Passo (F8)</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
<div id="pagamento_venda_orcamento" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Pagamento</h4>
            </div>
            <div class="modal-body">
                <form action="">
                    <fieldset>
                        <div class="row">
                            <div class="form-group col-lg-4">
                                <label>Forma Pagamento</label>
                                <select class="form-control forma_pagamento_id"></select>
                            </div>
                            <div class="form-group col-lg-4">
                                <label>Valor</label>
                                <input type="text" class="form-control forma_pagamento_valor" id="valor_6" />
                            </div>
                            <div class="form-group col-lg-4">
                                <label>Data (aaaa-mm-dd)</label>
                                <input type="date" class="form-control forma_pagamento_data" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-6">
                                <label>Obs</label>
                                <input type="text" class="form-control forma_pagamento_obs" />
                            </div>
                            <div class="form-group col-lg-6">
                                <label>Tipo</label>
                                <select class="form-control forma_pagamento_tipo">
                                    <option value="0">A vista</option>
                                    <option value="1">Financeiro</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-12" align="right">
                                <button type="button" class="btn btn-success" onclick="forma_pagamento();">Inserir (F9)</button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                            </div>
                        </div>
                    </fieldset>
                </form>
                <hr />
                <div id="load_forma_pagamento_grid"></div>
                <hr />
                <div id="load_button_create"></div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function() {
        $( ".orcamento_venda_nome_contato" ).autocomplete({
            source: "_controller/_contato.php?acao=load_contato",
            minLength: 2,
            focus: function(event, ui) {
            	event.preventDefault();
            },
            select: function( event, ui ) {
                $(".orcamento_venda_id_contato").val(ui.item.value);
                $(".orcamento_venda_nome_contato").val(ui.item.label);
                event.preventDefault();
            }
        });
        $( ".itens_orcamento_venda_nome_produto" ).autocomplete({
            source: "_controller/_produto.php?acao=load_produto",
            minLength: 2,
            focus: function(event, ui) {
            	event.preventDefault();
            },
            select: function( event, ui ) {
                $(".itens_orcamento_venda_id_produto").val(ui.item.value);
                $(".itens_orcamento_venda_nome_produto").val(ui.item.label);
                $(".itens_orcamento_venda_valor_unitario").val(ui.item.valor_produto);
                event.preventDefault();
            }
        });
    });
    function load_forma_pagamento(){
        var acao = "acao=load_forma_pagamento";
        $.ajax({
            type: 'GET',
            url: "_controller/_forma_pagamento.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                var data_return = jQuery.parseJSON(data);
                var options = '';
                for (var i = 0; i < data_return.data.length; i++) {
                    options += '<option value="' + data_return.data[i].forma_pagamento_id + '">' + data_return.data[i].forma_pagamento_descricao + '</option>';
                }
                $('.forma_pagamento_id').html(options).show();
                load_out();
            }
        });
        load_out();
    }
    function buscar_contato(){
        var orcamento_venda_id_contato = $(".orcamento_venda_id_contato").val();
        var acao = "acao=load_contato_id&id="+orcamento_venda_id_contato;
        
        if(orcamento_venda_id_contato){
            $.ajax({
                type: 'POST',
                url: "_controller/_contato.php",
                data: acao,
                beforeSend: load_in(),
                success: function (data) {
                    load_out();
                    var data_return = jQuery.parseJSON(data);
                    if(data_return[0].label !== ''){
                        $(".orcamento_venda_nome_contato").val(data_return[0].label);
                    }else{
                        $("#_modal").modal('show');
                        $("#title_modal").html('Erro');
                        $("#texto_modal").html('Ops, não foi encontrado nenhum registro');
                        $("#buttons_modal").html('');
                        $(".orcamento_venda_id_contato").val('');
                        $(".orcamento_venda_nome_contato").val('');
                    }
                }
            });
        }
    }
    function buscar_produto(){
        var itens_orcamento_venda_id_produto = $(".itens_orcamento_venda_id_produto").val();
        var acao = "acao=load_produto_id&id="+itens_orcamento_venda_id_produto;
        
        if(itens_orcamento_venda_id_produto){
            $.ajax({
                type: 'POST',
                url: "_controller/_produto.php",
                data: acao,
                beforeSend: load_in(),
                success: function (data) {
                    load_out();
                    var data_return = jQuery.parseJSON(data);
                    if(data_return[0].label !== ''){
                        $(".itens_orcamento_venda_nome_produto").val(data_return[0].label);
                        $(".itens_orcamento_venda_valor_unitario").val(data_return[0].valor_produto);
                    }else{
                        $("#_modal").modal('show');
                        $("#title_modal").html('Erro');
                        $("#texto_modal").html('Ops, não foi encontrado nenhum registro');
                        $("#buttons_modal").html('');
                        $(".itens_orcamento_venda_id_produto").val('');
                        $(".itens_orcamento_venda_nome_produto").val('');
                        $(".itens_orcamento_venda_valor_unitario").val('');
                        $(".itens_orcamento_venda_qtd").val('1.000');
                    }
                }
            });
        }
    }
    function set_data_1(){
        var acao = "acao=load_data";
        $.ajax({
            type: 'POST',
            url: "_controller/_venda.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                var data_return = jQuery.parseJSON(data);
                $(".orcamento_venda_data").val(data_return.data);
            }
        });
    }
    function update(){
        var dados = $("#update").serialize();
        var faturar = "&orcamento_venda_valor_produtos="+$(".valor_produtos").val()+"&orcamento_venda_valor_frete="+$(".valor_frete").val()+"&orcamento_venda_valor_desconto="+$(".valor_desconto_real").val()+"&orcamento_venda_perc_desconto="+$(".valor_desconto_perc").val()+"&orcamento_venda_valor_total="+$(".valor_total").val();
        var frete = "&orcamento_venda_modalidade_frete="+$(".orcamento_venda_modalidade_frete").val()+"&orcamento_venda_transportadora="+$(".orcamento_venda_transportadora").val();
        var detalhe = "&orcamento_venda_data="+$(".orcamento_venda_data").val()+"&orcamento_venda_data_prazo="+$(".orcamento_venda_data_prazo").val()+"&orcamento_venda_validade="+$(".orcamento_venda_validade").val()+"&orcamento_venda_ref="+$(".orcamento_venda_ref").val()+"&orcamento_venda_obs="+$(".orcamento_venda_obs").val()+"&orcamento_venda_obs_interno="+$(".orcamento_venda_obs_interno").val();
        var acao = "&acao=update&id=<?=$_GET['id'];?>";
        
        $.ajax({
            type: 'POST',
            url: "_controller/_venda.php",
            data: dados+faturar+frete+detalhe+acao,
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
                    $("#faturar_venda_orcamento").modal('hide');
                    $("#frete_venda_orcamento").modal('hide');
                    $("#pagamento_venda_orcamento").modal('hide');
                    $("#detalhe_venda_orcamento").modal('hide');
                }
            }
        });
    }
    function fechar_modal(){
        $("#_modal").modal('hide');
        $("#faturar_venda_orcamento").modal('hide');
        $("#frete_venda_orcamento").modal('hide');
        $("#pagamento_venda_orcamento").modal('hide');
        $("#detalhe_venda_orcamento").modal('hide');
        carrega_pagina('venda', 'index.php');
    }
    document.onkeyup=function(e){
        if(e.which === 113){
            faturar();
        }else if(e.which === 115){
            frete();
        }else if(e.which === 118){
            detalhe();
        }else if(e.which === 119){
            pagamento();
        }else if(e.which === 120){
            forma_pagamento();
        }else if(e.which === 121){
            create();
        }
    }
    function detalhe(){
        $("#faturar_venda_orcamento").modal('hide');
        $("#frete_venda_orcamento").modal('hide');
        $("#pagamento_venda_orcamento").modal('hide');
        $("#detalhe_venda_orcamento").modal('show');
    }
    function frete(){
        $("#faturar_venda_orcamento").modal('hide');
        $("#frete_venda_orcamento").modal('show');
        $("#pagamento_venda_orcamento").modal('hide');
        $("#detalhe_venda_orcamento").modal('hide');
        set_data_1();
    }
    function faturar(){
        var acao = "&acao=load_valor_total";
        
        $.ajax({
            type: 'POST',
            url: "_controller/_venda.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                $("#faturar_venda_orcamento").modal('show');
                $("#frete_venda_orcamento").modal('hide');
                $("#pagamento_venda_orcamento").modal('hide');
                $("#detalhe_venda_orcamento").modal('hide');
                $(".valor_produtos").val(data);
                $(".valor_total").val(data);
                
                zerar_forma_pagamento();
                load_forma_pagamento_grid();
                valor_total_geral();
            }
        });
        
    }
    function load_info_pagamento(valor){
        var acao = "&acao=load_info_pagamento&valor="+valor;
        $.ajax({
            type: 'POST',
            url: "_controller/_venda.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                var data_return = jQuery.parseJSON(data);
                $(".forma_pagamento_valor").val(data_return.valor_total);
                $(".forma_pagamento_data").val(data_return.data);
            }
        });
        
    }
    function pagamento(){
        $("#faturar_venda_orcamento").modal('hide');
        $("#frete_venda_orcamento").modal('hide');
        $("#pagamento_venda_orcamento").modal('show');
        $("#detalhe_venda_orcamento").modal('hide');
        load_forma_pagamento();
        var valor_total = $(".valor_total").val();
        load_info_pagamento(valor_total);
    }
    function forma_pagamento(){
        var forma_pagamento_id      = $(".forma_pagamento_id").val();
        var forma_pagamento_valor   = $(".forma_pagamento_valor").val();
        var forma_pagamento_data    = $(".forma_pagamento_data").val();
        var forma_pagamento_obs     = $(".forma_pagamento_obs").val();
        var forma_pagamento_tipo    = $(".forma_pagamento_tipo").val();
        var valor_total             = $(".valor_total").val();
        
        var acao = "acao=forma_pagamento_insert&forma_pagamento_id="+forma_pagamento_id+"&forma_pagamento_valor="+forma_pagamento_valor+"&forma_pagamento_data="+forma_pagamento_data+"&forma_pagamento_obs="+forma_pagamento_obs+"&forma_pagamento_tipo="+forma_pagamento_tipo+"&valor_total="+valor_total;
        
        $.ajax({
            type: 'POST',
            url: "_controller/_venda.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                load_forma_pagamento_grid();
                var data_return = jQuery.parseJSON(data);
                if(data_return.type === 'error'){
                    $("#_modal").modal('show');
                    $("#title_modal").html(data_return.title);
                    $("#texto_modal").html(data_return.msg);
                    $("#buttons_modal").html(data_return.buttons);
                }else{
                    $(".forma_pagamento_valor").val(data_return.valor_restante);
                    if(data_return.valor_restante === '0.00'){
                        $("#load_button_create").html('<button type="button" class="btn btn-primary" onclick="update();">Gravar Venda (F10)</button>');
                    }else{
                        $("#load_button_create").html('');
                    }
                }
            }
        });
    }
    function valor_total_geral(){
        var orcamento_venda_valor_produtos = $(".valor_produtos").val();
        var orcamento_venda_valor_frete = $(".valor_frete").val();
        var orcamento_venda_valor_desconto = $(".valor_desconto_real").val();
        var orcamento_venda_perc_desconto = $(".valor_desconto_perc").val();
        
        if(orcamento_venda_valor_produtos > '0'){
            
            //var preco_venda = (Number(perc_preco_venda) + Number(produto_preco_custo));
            var valor_total_inicio = Number(orcamento_venda_valor_produtos) + Number(orcamento_venda_valor_frete);
            var valor_total_segundo = valor_total_inicio - Number(orcamento_venda_valor_desconto);
            var perc_valor_total = (orcamento_venda_perc_desconto / 100) * valor_total_inicio;
            var valor_total_terceiro = valor_total_segundo - perc_valor_total;
            $(".valor_total").val(format_moeda(valor_total_terceiro, ""));
        }
    }
    function limpar_campos(){
        $(".rota_descricao").val('');
        $(".rota_status").val('0');
    }
    function format_moeda(n, currency) {
        return currency + "" + n.toFixed(2).replace(/./g, function(c, i, a) {
            return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "" + c : c;
        });
    }
    function load_insert_produto(){
        var itens_orcamento_venda_id_produto = $(".itens_orcamento_venda_id_produto").val();
        var acao_insert = "acao=load_produto_id&id="+itens_orcamento_venda_id_produto;
        
        if(itens_orcamento_venda_id_produto){
            $.ajax({
                type: 'POST',
                url: "_controller/_produto.php",
                data: acao_insert,
                beforeSend: load_in(),
                async: false,
                success: function (data) {
                    load_out();
                    var data_return = jQuery.parseJSON(data);
                    $(".itens_orcamento_venda_nome_produto").val(data_return[0].label);
                    $(".itens_orcamento_venda_valor_unitario").val(data_return[0].valor_produto);
                }
            });
        }
    }
    function insert_produto(){
        var itens_orcamento_venda_id_produto = $(".itens_orcamento_venda_id_produto").val();
        var itens_orcamento_venda_qtd = $(".itens_orcamento_venda_qtd").val();
        var itens_orcamento_venda_valor_unitario = $(".itens_orcamento_venda_valor_unitario").val();
        
        if(itens_orcamento_venda_id_produto !== '' || itens_orcamento_venda_qtd !== '' || itens_orcamento_venda_valor_unitario !== ''){
            var acao = "&acao=insert_produto&id_produto="+itens_orcamento_venda_id_produto+"&qtd="+itens_orcamento_venda_qtd+"&valor_unitario="+itens_orcamento_venda_valor_unitario;
            $.ajax({
                type: 'POST',
                url: "_controller/_venda.php",
                data: acao,
                beforeSend: load_in(),
                async: false,
                success: function (data) {
                    load_out();
                    var data_return = jQuery.parseJSON(data);
                    if(data_return.type === 'error'){
                        $("#_modal").modal('show');
                        $("#title_modal").html(data_return.title);
                        $("#texto_modal").html(data_return.msg);
                        $("#buttons_modal").html(data_return.buttons);
                    }else{
                        $(".itens_orcamento_venda_id_produto").val('');
                        $(".itens_orcamento_venda_nome_produto").val('');
                        $(".itens_orcamento_venda_valor_unitario").val('');
                        $(".itens_orcamento_venda_nome_produto").focus();
                        $(".itens_orcamento_venda_qtd").val('1.000');
                        load_produto_grid();
                    }
                }
            });
        }
    }
    function load_produto_grid(){
        var acao = "&acao=load_produto_grid";
        $.ajax({
            type: 'POST',
            url: "_controller/_venda.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                $("#load_produto_grid").html(data);
            }
        });
    }
    function load_forma_pagamento_grid(){
        var acao = "&acao=load_forma_pagamento_grid";
        $.ajax({
            type: 'POST',
            url: "_controller/_venda.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                $("#load_forma_pagamento_grid").html(data);
            }
        });
    }
    function zerar_forma_pagamento(){
        var acao = "&acao=del_forma_pagamento";
        $.ajax({
            type: 'POST',
            url: "_controller/_venda.php",
            data: acao,
            beforeSend: load_in(),
            success: function () {
                load_out();
            }
        });
    }
    function delete_prod_grid(id_produto){
        $("#_modal").modal('show');
        $("#title_modal").html('Confirmação');
        $("#texto_modal").html('Opa, preciso que você confirme se deseja mesmo apagar esse produto da grid!');
        $("#buttons_modal").html('<button type="button" class="btn btn-default" data-dismiss="modal" onclick="delete_prod_grid_ok('+id_produto+');">Sim</button><button type="button" class="btn btn-default" data-dismiss="modal">Não</button>');
    }
    function delete_prod_grid_ok(id_produto){
        var acao = "&acao=delete_produto_grid&id_produto="+id_produto;
        $.ajax({
            type: 'POST',
            url: "_controller/_venda.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                load_produto_grid();
            }
        });
    }
    function delete_form_pgto_grid(id_forma_pagamento){
        $("#_modal").modal('show');
        $("#title_modal").html('Confirmação');
        $("#texto_modal").html('Opa, preciso que você confirme se deseja mesmo apagar essa forma de pagamento da grid!');
        $("#buttons_modal").html('<button type="button" class="btn btn-default" data-dismiss="modal" onclick="delete_form_pgto_grid_ok('+id_forma_pagamento+');">Sim</button><button type="button" class="btn btn-default" data-dismiss="modal">Não</button>');
    }
    function delete_form_pgto_grid_ok(id_forma_pagamento){
        var acao = "&acao=delete_forma_pagamento_grid&id_forma_pagamento="+id_forma_pagamento;
        $.ajax({
            type: 'POST',
            url: "_controller/_venda.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                load_forma_pagamento_grid();
                var acao_return = "acao=val_valor_restante";
                $.ajax({
                    type: 'POST',
                    url: "_controller/_venda.php",
                    data: acao_return,
                    beforeSend: load_in(),
                    success: function (data) {
                        load_out();
                        var data_return = jQuery.parseJSON(data);
                        if(data_return.type === 'success'){
                            $(".forma_pagamento_valor").val(data_return.valor_restante);
                            if(data_return.valor_restante === '0.00'){
                                $("#load_button_create").html('<button type="button" class="btn btn-primary" onclick="update();">Gravar Venda</button>');
                            }else{
                                $("#load_button_create").html('');
                            }
                        }
                    }
                });
            }
        });
    }
    $(function(){
        var acao = "acao=load_update&id=<?=$_GET['id'];?>";
        $.ajax({
            type: 'POST',
            url: "_controller/_venda.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                var data_return = jQuery.parseJSON(data);
                $(".orcamento_venda_id_contato").val(data_return[0].orcamento_venda_id_contato);
                $(".valor_frete").val(data_return[0].orcamento_venda_valor_frete);
                $(".valor_desconto_real").val(data_return[0].orcamento_venda_valor_desconto);
                $(".valor_desconto_perc").val(data_return[0].orcamento_venda_perc_desconto);
                $(".orcamento_venda_modalidade_frete").val(data_return[0].orcamento_venda_modalidade_frete);
                $(".orcamento_venda_transportadora").val(data_return[0].orcamento_venda_transportadora);
                $(".orcamento_venda_data").val(data_return[0].orcamento_venda_data);
                $(".orcamento_venda_data_prazo").val(data_return[0].orcamento_venda_data_prazo);
                $(".orcamento_venda_validade").val(data_return[0].orcamento_venda_validade);
                $(".orcamento_venda_ref").val(data_return[0].orcamento_venda_ref);
                $(".orcamento_venda_obs").val(data_return[0].orcamento_venda_obs);
                $(".orcamento_venda_obs_interno").val(data_return[0].orcamento_venda_obs_interno);
                load_produto_grid();
                load_forma_pagamento_grid();
            }
        });
    });
    $("#valor_1").maskMoney({thousands:'', decimal:'.'});
    $("#valor_2").maskMoney({thousands:'', decimal:'.'});
    $("#valor_3").maskMoney({thousands:'', decimal:'.'});
    $("#valor_4").maskMoney({thousands:'', decimal:'.'});
    $("#valor_5").maskMoney({thousands:'', decimal:'.'});
    $("#valor_6").maskMoney({thousands:'', decimal:'.'});
    $("#valor_7").maskMoney({thousands:'', decimal:'.'});
    $("#valor_8").maskMoney({thousands:'', decimal:'.'});
    $('#peso_1').priceFormat({prefix: '', centsSeparator: '.', thousandsSeparator: '',centsLimit: 3});
    $('#peso_2').priceFormat({prefix: '', centsSeparator: '.', thousandsSeparator: '',centsLimit: 3});
</script>