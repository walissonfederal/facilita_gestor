<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Financeiro(<?php echo $_GET['OP'];?>)
                                <a href="javascript::" onclick="carrega_pagina('financeiro', 'index.php?OP=<?php echo $_GET['OP'];?>');" class="btn btn-primary">Voltar</a>
                            </h3>
                        </div>
                        <div class="box-content">
                            <form action="" id="gerar_pesquisa">
                                <fieldset>
                                    <div class="row">
                                        <div class="form-group col-lg-1">
                                            <label>ID Contato</label>
                                            <input type="text" class="form-control search_nome_id_contato" name="search_nome_id_contato" onblur="buscar_contato();"/>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>Contato</label>
                                            <input type="text" class="form-control search_nome_contato"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Situação</label>
                                            <select class="form-control search_situacao" name="search_situacao">
                                                <option value=""></option>
                                                <option value="0">ABERTO</option>
                                                <option value="1">BAIXADO</option>
                                                <option value="2">CANCELADO</option>
                                                <option value="3">RENEGOCIADO</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Tipo Pesquisa</label>
                                            <select class="form-control search_tipo_pesquisa" name="search_tipo_pesquisa">
                                                <option value="financeiro_data_vencimento">Data Vencimento</option>
                                                <option value="financeiro_data_lancamento">Data Lançamento</option>
                                                <option value="financeiro_data_pagamento">Data Pagamento</option>
                                                <option value="financeiro_data_baixa">Data Baixa</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Data Inicial</label>
                                            <input type="date" class="form-control search_data_inicial" name="search_data_inicial"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Data Final</label>
                                            <input type="date" class="form-control search_data_final" name="search_data_final"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-2">
                                            <label>Itens Pesquisa</label>
                                            <select class="form-control search_itens_pesquisa" name="search_itens_pesquisa">
                                                <option value=""></option>
                                                <option value="financeiro_nosso_numero">Nosso Número</option>
                                                <option value="financeiro_codigo">Código</option>
                                                <option value="financeiro_descricao">Descrição</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Pesquisa</label>
                                            <input type="text" class="form-control search_pesquisa" name="search_pesquisa"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Fixa</label>
                                            <select class="form-control search_fixo" name="search_fixo">
                                                <option value=""></option>
                                                <option value="0">NÃO</option>
                                                <option value="1">SIM</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Aplicação Financeira?</label>
                                            <select class="form-control search_app_financeira" name="search_app_financeira">
                                                <option value=""></option>
                                                <option value="0">NÃO</option>
                                                <option value="1">SIM</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Plano de Contas</label>
                                            <select class="form-control search_id_plano_conta" name="search_id_plano_conta"></select>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Tipo Documento</label>
                                            <select class="form-control search_id_tipo_documento" name="search_id_tipo_documento"></select>
                                        </div>
                                    </div>
									<div class="row">
                                        <div class="form-group col-lg-2">
                                            <label>ID Vendedor</label>
                                            <input type="text" class="form-control search_id_vendedor" onblur="buscar_vendedor();" name="search_id_vendedor"/>
                                        </div>
                                        <div class="form-group col-lg-10">
                                            <label>Vendedor(Nome, CPF, Email)</label>
                                            <input type="text" class="form-control search_nome_vendedor"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-12" align="right">
                                            <button type="button" class="btn btn-primary" onclick="gerar_pesquisa();">Gerar Pesquisa</button>
                                            <button type="button" class="btn btn-primary" onclick="gerar_pdf();">Gerar PDF</button>
                                            <button type="button" class="btn btn-primary" onclick="gerar_excel();">Gerar Excel</button>
											<button type="button" class="btn btn-primary" onclick="gerar_excel_bloqueio();">Gerar Excel Com Linhas</button>
                                            <a href="javascript::" onclick="carrega_pagina('financeiro', 'index.php')" class="btn btn-danger">Cancelar</a>
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
		$( ".search_nome_vendedor" ).autocomplete({
            source: "_controller/_vendedor_franquiado.php?acao=load_vendedor",
            minLength: 2,
            focus: function(event, ui) {
            	event.preventDefault();
            },
            select: function( event, ui ) {
                $(".search_id_vendedor").val(ui.item.value);
                $(".search_nome_vendedor").val(ui.item.label);
                event.preventDefault();
            }
        });
    });
    $(function(){
        var acao = "acao=load_plano_conta";
        $.ajax({
            type: 'GET',
            url: "_controller/_plano_conta.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                var data_return = jQuery.parseJSON(data);
                var options = '<option value=""></option>';
                for (var i = 0; i < data_return.data.length; i++) {
                    options += '<option value="' + data_return.data[i].plano_conta_id + '">' + data_return.data[i].plano_conta_classificacao + ' ' + data_return.data[i].plano_conta_descricao + '</option>';
                }
                $('.search_id_plano_conta').html(options).show();
                load_out();
            }
        });
        load_out();
    });
    $(function(){
        var acao = "acao=load_tipo_documento";
        $.ajax({
            type: 'GET',
            url: "_controller/_tipo_documento.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                var data_return = jQuery.parseJSON(data);
                var options = '<option value=""></option>';
                for (var i = 0; i < data_return.data.length; i++) {
                    options += '<option value="' + data_return.data[i].tipo_documento_id + '">' + data_return.data[i].tipo_documento_descricao + '</option>';
                }
                $('.search_id_tipo_documento').html(options).show();
                load_out();
            }
        });
        load_out();
    });
	function buscar_vendedor(){
        var financeiro_id_vendedor = $(".search_id_vendedor").val();
        var acao = "acao=load_vendedor_id&id="+financeiro_id_vendedor;
        
        if(financeiro_id_vendedor){
            $.ajax({
                type: 'POST',
                url: "_controller/_vendedor_franquiado.php",
                data: acao,
                beforeSend: load_in(),
                success: function (data) {
                    load_out();
                    var data_return = jQuery.parseJSON(data);
                    if(data_return[0].label !== ''){
                        $(".search_nome_vendedor").val(data_return[0].label);
                    }else{
                        $("#_modal").modal('show');
                        $("#title_modal").html('Erro');
                        $("#texto_modal").html('Ops, não foi encontrado nenhum registro');
                        $("#buttons_modal").html('');
                        $(".search_id_vendedor").val('');
                        $(".search_nome_vendedor").val('');
                    }
                }
            });
        }
    }
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
    function gerar_excel(){
        window.open('_controller/_financeiro.php?acao=gerar_excel', '_blank');
    }
	function gerar_excel_bloqueio(){
        window.open('_controller/_financeiro.php?acao=gerar_excel_bloqueio', '_blank');
    }
    function gerar_pdf(){
        window.open('_reports/_financeiro/report.php', '_blank');
    }
    function gerar_pesquisa(){
        var dados = $("#gerar_pesquisa").serialize();
        var acao = "&acao=gerar_pesquisa&OP=<?php echo $_GET['OP'];?>";
        
        $.ajax({
            type: 'GET',
            url: "_controller/_financeiro.php",
            data: dados+acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
            }
        });
    }
    $(function() {
        $( ".contato_descricao_tipo_contato" ).autocomplete({
            source: "_controller/_tipo_contato.php?acao=load_tipo_contato",
            minLength: 2,
            focus: function(event, ui) {
            	event.preventDefault();
            },
            select: function( event, ui ) {
                $(".contato_id_tipo_contato").val(ui.item.value);
                $(".contato_descricao_tipo_contato").val(ui.item.label);
                event.preventDefault();
            }
        });
        $( ".contato_descricao_regiao" ).autocomplete({
            source: "_controller/_regiao.php?acao=load_regiao",
            minLength: 2,
            focus: function(event, ui) {
            	event.preventDefault();
            },
            select: function( event, ui ) {
                $(".contato_id_regiao").val(ui.item.value);
                $(".contato_descricao_regiao").val(ui.item.label);
                event.preventDefault();
            }
        });
        $( ".contato_descricao_rota" ).autocomplete({
            source: "_controller/_rota.php?acao=load_rota",
            minLength: 2,
            focus: function(event, ui) {
            	event.preventDefault();
            },
            select: function( event, ui ) {
                $(".contato_id_rota").val(ui.item.value);
                $(".contato_descricao_rota").val(ui.item.label);
                event.preventDefault();
            }
        });
    });
    function buscar_tipo_contato(){
        var tipo_contato_id = $(".contato_id_tipo_contato").val();
        var acao = "acao=load_tipo_contato_id&id="+tipo_contato_id;
        
        if(tipo_contato_id){
            $.ajax({
                type: 'POST',
                url: "_controller/_tipo_contato.php",
                data: acao,
                beforeSend: load_in(),
                success: function (data) {
                    load_out();
                    var data_return = jQuery.parseJSON(data);
                    if(data_return[0].label !== ''){
                        $(".contato_descricao_tipo_contato").val(data_return[0].label);
                    }else{
                        $("#_modal").modal('show');
                        $("#title_modal").html('Erro');
                        $("#texto_modal").html('Ops, não foi encontrado nenhum registro');
                        $("#buttons_modal").html('');
                        $(".contato_id_tipo_contato").val('');
                        $(".contato_descricao_tipo_contato").val('');
                    }
                }
            });
        }
    }
    function buscar_regiao(){
        var regiao_id = $(".contato_id_regiao").val();
        var acao = "acao=load_regiao_id&id="+regiao_id;
        
        if(regiao_id){
            $.ajax({
                type: 'POST',
                url: "_controller/_regiao.php",
                data: acao,
                beforeSend: load_in(),
                success: function (data) {
                    load_out();
                    var data_return = jQuery.parseJSON(data);
                    if(data_return[0].label !== ''){
                        $(".contato_descricao_regiao").val(data_return[0].label);
                    }else{
                        $("#_modal").modal('show');
                        $("#title_modal").html('Erro');
                        $("#texto_modal").html('Ops, não foi encontrado nenhum registro');
                        $("#buttons_modal").html('');
                        $(".contato_id_regiao").val('');
                        $(".contato_descricao_regiao").val('');
                    }
                }
            });
        }
    }
    function buscar_rota(){
        var rota_id = $(".contato_id_rota").val();
        var acao = "acao=load_rota_id&id="+rota_id;
        
        if(rota_id){
            $.ajax({
                type: 'POST',
                url: "_controller/_rota.php",
                data: acao,
                beforeSend: load_in(),
                success: function (data) {
                    load_out();
                    var data_return = jQuery.parseJSON(data);
                    if(data_return[0].label !== ''){
                        $(".contato_descricao_rota").val(data_return[0].label);
                    }else{
                        $("#_modal").modal('show');
                        $("#title_modal").html('Erro');
                        $("#texto_modal").html('Ops, não foi encontrado nenhum registro');
                        $("#buttons_modal").html('');
                        $(".contato_id_rota").val('');
                        $(".contato_descricao_rota").val('');
                    }
                }
            });
        }
    }
</script>