<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Contato
                                <a href="javascript::" onclick="carrega_pagina('contato', 'index.php');" class="btn btn-primary">Voltar</a>
                            </h3>
                        </div>
                        <div class="box-content">
                            <form action="" id="gerar_pesquisa">
                                <fieldset>
                                    <div class="row">
                                        <div class="form-group col-lg-3">
                                            <label>Cliente</label>
                                            <select name="contato_cliente" class="form-control">
                                                <option value=""></option>
                                                <option value="0">NÃO</option>
                                                <option value="1">SIM</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>Fornecedor</label>
                                            <select name="contato_fornecedor" class="form-control">
                                                <option value=""></option>
                                                <option value="0">NÃO</option>
                                                <option value="1">SIM</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>Transportador</label>
                                            <select name="contato_transportador" class="form-control">
                                                <option value=""></option>
                                                <option value="0">NÃO</option>
                                                <option value="1">SIM</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>Status</label>
                                            <select class="form-control" name="contato_status">
                                                <option value=""></option>
                                                <option value="0">Ativo</option>
                                                <option value="1">Inativo</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-1">
                                            <label>ID Tipo Con.</label>
                                            <input type="text" class="form-control contato_id_tipo_contato" onblur="buscar_tipo_contato();" name="contato_id_tipo_contato"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Tipo Contato</label>
                                            <input type="text" class="form-control contato_descricao_tipo_contato"/>
                                        </div>
                                        <div class="form-group col-lg-1">
                                            <label>ID Região</label>
                                            <input type="text" class="form-control contato_id_regiao" onblur="buscar_regiao();" name="contato_id_regiao"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Região</label>
                                            <input type="text" class="form-control contato_descricao_regiao"/>
                                        </div>
                                        <div class="form-group col-lg-1">
                                            <label>ID Rota</label>
                                            <input type="text" class="form-control contato_id_rota" onblur="buscar_rota();" name="contato_id_rota"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Rota</label>
                                            <input type="text" class="form-control contato_descricao_rota"/>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>CPF / CNPJ</label>
                                            <input type="text" name="contato_cpf_cnpj" class="form-control contato_cpf_cnpj"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-12">
                                            <label>BI Relatório</label>
                                            <select multiple="multiple" id="my-select" name="my-select[]" class="multiselect form-control">
                                                <option value="contato_id" selected="">Código</option>
                                                <option value="contato_nome_razao" selected="">Nome Razão Social</option>
                                                <option value="contato_nome_fantasia" selected="">Nome Fantasia</option>
                                                <option value="contato_cpf_cnpj" selected="">CPF / CNPJ</option>
                                                <option value="contato_ie_rg">IE / RG</option>
                                                <option value="contato_cep">CEP</option>
                                                <option value="contato_endereco">Endereço</option>
                                                <option value="contato_numero">Número</option>
                                                <option value="contato_bairro">Bairro</option>
                                                <option value="contato_estado">Estado</option>
                                                <option value="contato_cidade">Cidade</option>
                                                <option value="contato_status" selected="">Status</option>
                                                <option value="contato_telefone" selected="">Telefone</option>
                                                <option value="contato_email" selected="">Email</option>
                                            </select>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                            <div class="row">
                                <div class="form-group col-lg-12" align="right">
                                    <button type="button" class="btn btn-primary" onclick="gerar_pesquisa();">Gerar Pesquisa</button>
                                    <div id="excel_pdf" style="float: right; margin-left: 3px;"></div>
                                    <a href="javascript::" onclick="carrega_pagina('contato', 'index.php')" class="btn btn-danger">Cancelar</a>
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
    function gerar_excel(){
        window.open('_controller/_contato.php?acao=gerar_excel', '_blank');
    }
    function gerar_pdf(){
        window.open('_reports/_contato/report.php', '_blank');
    }
    function gerar_pesquisa(){
        var dados = $("#gerar_pesquisa").serialize();
        var acao = "&acao=gerar_pesquisa";
        
        $.ajax({
            type: 'POST',
            url: "_controller/_contato.php",
            data: dados+acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                $("#excel_pdf").html(data);
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