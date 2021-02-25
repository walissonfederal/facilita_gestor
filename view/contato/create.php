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
                            <form action="" id="create">
                                <fieldset>
                                    <div class="row">
                                        <div class="form-group col-lg-3">
                                            <label>Monitoramento</label>
                                            <select name="contato_monitoramento" class="form-control">
                                                <option value="0">NÃO</option>
                                                <option value="1">SIM</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>Rastreamento</label>
                                            <select name="contato_rastreamento" class="form-control">
                                                <option value="0">NÃO</option>
                                                <option value="1">SIM</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>Telefonia</label>
                                            <select name="contato_telefonia" class="form-control">
                                                <option value="0">NÃO</option>
                                                <option value="1">SIM</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>Associado</label>
                                            <select name="contato_id_associado" class="form-control contato_id_associado"></select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-3">
                                            <label>Cliente</label>
                                            <select name="contato_cliente" class="form-control">
                                                <option value="0">NÃO</option>
                                                <option value="1">SIM</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>Fornecedor</label>
                                            <select name="contato_fornecedor" class="form-control">
                                                <option value="0">NÃO</option>
                                                <option value="1">SIM</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>Transportador</label>
                                            <select name="contato_transportador" class="form-control">
                                                <option value="0">NÃO</option>
                                                <option value="1">SIM</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>Status</label>
                                            <select class="form-control" name="contato_status">
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
                                            <input type="text" onkeyup="somenteNumeros(this);" name="contato_cpf_cnpj" onblur="BuscaCNPJ();" class="form-control contato_cpf_cnpj"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-4">
                                            <label>Nome Razão</label>
                                            <input type="text" name="contato_nome_razao" class="form-control contato_nome_razao"/>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>Nome Fantasia</label>
                                            <input type="text" name="contato_nome_fantasia" class="form-control contato_nome_fantasia"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Inscrição Est / Rg</label>
                                            <input type="text" name="contato_ie_rg" class="form-control contato_ie_rg"/>
                                        </div>
                                        <div class="form-group col-lg-1">
                                            <label>Inscrição Suframa</label>
                                            <input type="text" name="contato_isuframa" class="form-control contato_isuframa"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>CEP</label>
                                            <input type="text" class="form-control contato_cep cep" onblur="BuscaCEP(this.value);" name="contato_cep"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-3">
                                            <label>Endereço</label>
                                            <input type="text" class="form-control contato_endereco" name="contato_endereco"/>
                                        </div>
                                        <div class="form-group col-lg-1">
                                            <label>Nº</label>
                                            <input type="text" class="form-control contato_numero" name="contato_numero"/>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>Complemento</label>
                                            <input type="text" class="form-control contato_complemento" name="contato_complemento"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Bairro</label>
                                            <input type="text" class="form-control contato_bairro" name="contato_bairro"/>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>Telefone</label>
                                            <input type="text" class="form-control contato_telefone telefone" name="contato_telefone"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-3">
                                            <label>Cidade</label>
                                            <input type="text" class="form-control contato_cidade" name="contato_cidade"/>
                                            <input type="hidden" readonly="" class="form-control contato_cidade_ibge" name="contato_cidade_ibge"/>
                                        </div>
                                        <div class="form-group col-lg-1">
                                            <label>Estado</label>
                                            <input type="text" class="form-control contato_estado" name="contato_estado"/>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>Email</label>
                                            <input type="text" class="form-control contato_email" name="contato_email"/>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>Email Alternativo</label>
                                            <input type="text" class="form-control contato_email_alternativo" name="contato_email_alternativo"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Celular</label>
                                            <input type="text" class="form-control contato_celular telefone" name="contato_celular"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-3">
                                            <label>Protestado</label>
                                            <select name="contato_protestado" class="form-control contato_protestado">
                                                <option value="0">Não</option>
                                                <option value="1">Sim</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>Protestado</label>
                                            <input type="date" class="form-control contato_data_protestado" name="contato_data_protestado" />
                                        </div>
										<div class="form-group col-lg-3">
                                            <label>Ativar Subcliente?</label>
                                            <select name="contato_subcliente" class="form-control contato_protestado">
                                                <option value="0">Não</option>
                                                <option value="1">Sim</option>
                                            </select>
                                        </div>
										<div class="form-group col-lg-3">
                                            <label>Cliente Rastrek?</label>
                                            <select name="contato_rastrek" class="form-control contato_rastrek">
                                                <option value="0">Não</option>
                                                <option value="1">Sim</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-12">
                                            <label>Obs</label>
                                            <textarea name="contato_obs" class="form-control contato_obs" cols="" rows=""></textarea>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-12" align="right">
                                            <button type="button" class="btn btn-primary" onclick="create();">Gravar</button>
                                            <a href="javascript::" onclick="carrega_pagina('contato', 'index.php');" class="btn btn-danger">Cancelar</a>
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
    function create(){
        var dados = $("#create").serialize();
        var acao = "&acao=create";
        
        $.ajax({
            type: 'POST',
            url: "_controller/_contato.php",
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
    function limpar_campos(){
        $(".contato_bairro").val('');
        $(".contato_cep").val('');
        $(".contato_cidade").val('');
        $(".contato_cidade_ibge").val('');
        $(".contato_complemento").val('');
        $(".contato_cpf_cnpj").val('');
        $(".contato_descricao_regiao").val('');
        $(".contato_descricao_rota").val('');
        $(".contato_descricao_tipo_contato").val('');
        $(".contato_email_alternativo").val('');
        $(".contato_email").val('');
        $(".contato_endereco").val('');
        $(".contato_estado").val('');
        $(".contato_id_regiao").val('');
        $(".contato_id_rota").val('');
        $(".contato_id_tipo_contato").val('');
        $(".contato_ie_rg").val('');
        $(".contato_isuframa").val('');
        $(".contato_nome_fantasia").val('');
        $(".contato_nome_razao").val('');
        $(".contato_numero").val('');
        $(".contato_telefone").val('');
        $(".contato_celular").val('');
        $(".contato_obs").val('');
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
    function BuscaCNPJ(){
        var CNPJ = $(".contato_cpf_cnpj").val();
        var acao = "acao=buscar_cnpj_cpf&dado="+CNPJ;
        
        $.ajax({
            type: 'POST',
            url: "_controller/_contato.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                var data_return = jQuery.parseJSON(data);
                if(data_return.type === 'error'){
                    $("#_modal").modal('show');
                    $("#title_modal").html(data_return.title);
                    $("#texto_modal").html(data_return.msg);
                    $("#buttons_modal").html(data_return.buttons);
                    $('.contato_cpf_cnpj').val('');
                }else{
                    BuscaCNPJDepois();
                }
            }
        });
    }
    function BuscaCNPJDepois() {
        var CNPJ = $(".contato_cpf_cnpj").val();
        $.ajax({
            type: "GET",
            beforeSend: load_in(),
            url: "https://www.receitaws.com.br/v1/cnpj/" + CNPJ,
            dataType: "jsonp",
            success: function (data) {
                if (data != null) {
                    $(".contato_nome_razao").val(data.nome);
                    $(".contato_nome_fantasia").val(data.fantasia);
                    $(".contato_numero").val(data.numero);
                    
                    var CEP = data.cep
                    var CEP_UNO = CEP.replace(".", "");
                    var CEP_DUO = CEP_UNO.replace("-", "");
                    $(".contato_cep").val(CEP_DUO);
                    $(".contato_bairro").val(data.bairro);
                    $(".contato_endereco").val(data.logradouro);
                    $(".contato_complemento").val(data.complemento);
                    $(".contato_email").val(data.email);
                    $(".contato_telefone").val(data.telefone);
                    BuscaCEP(CEP_DUO);
                }
                load_out();
            }
        });
        load_out();
    }
    function BuscaCEP(CEP_INFO){
        $.ajax({
            type: "GET",
            beforeSend: load_in(),
            url: "https://viacep.com.br/ws/"+CEP_INFO+"/json/?callback=",
            dataType: "jsonp",
            success: function (data) {
                if (data != null) {
                    $(".contato_estado").val(data.uf);
                    $(".contato_cidade").val(data.localidade);
                    $(".contato_cidade_ibge").val(data.ibge);
                    if($(".contato_bairro").val() === ''){
                        $(".contato_bairro").val(data.bairro);
                    }
                    if($(".contato_complemento").val() === ''){
                        $(".contato_complemento").val(data.complemento);
                    }
                    if($(".contato_endereco").val() === ''){
                        $(".contato_endereco").val(data.logradouro);
                    }
                }
                load_out();
            }
        });
        load_out();
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
                $('.contato_id_associado').html(options).show();
                load_out();
            }
        });
        load_out();
    });
    function somenteNumeros(num) {
        var er = /[^0-9.]/;
        er.lastIndex = 0;
        var campo = num;
        if (er.test(campo.value)) {
          campo.value = "";
        }
    }
</script>