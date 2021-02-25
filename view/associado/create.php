<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Associado
                                <a href="javascript::" onclick="carrega_pagina('associado', 'index.php');" class="btn btn-primary">Voltar</a>
                            </h3>
                        </div>
                        <div class="box-content">
                            <form action="" id="create">
                                <fieldset>
                                    <div class="row">
                                        <div class="form-group col-lg-12">
                                            <label>Status</label>
                                            <select class="form-control" name="associado_status">
                                                <option value="0">Ativo</option>
                                                <option value="1">Inativo</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-4">
                                            <label>Nome Razão</label>
                                            <input type="text" name="associado_nome_razao" class="form-control associado_nome_razao"/>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>Nome Fantasia</label>
                                            <input type="text" name="associado_nome_fantasia" class="form-control associado_nome_fantasia"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Inscrição Est / Rg</label>
                                            <input type="text" name="associado_ie_rg" class="form-control associado_ie_rg"/>
                                        </div>
                                        <div class="form-group col-lg-1">
                                            <label>CEP</label>
                                            <input type="text" class="form-control associado_cep cep" onblur="BuscaCEP(this.value);" name="associado_cep"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>CPF / CNPJ</label>
                                            <input type="text" name="associado_cpf_cnpj" class="form-control associado_cpf_cnpj"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-3">
                                            <label>Endereço</label>
                                            <input type="text" class="form-control associado_endereco" name="associado_endereco"/>
                                        </div>
                                        <div class="form-group col-lg-1">
                                            <label>Nº</label>
                                            <input type="text" class="form-control associado_numero" name="associado_numero"/>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>Complemento</label>
                                            <input type="text" class="form-control associado_complemento" name="associado_complemento"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Bairro</label>
                                            <input type="text" class="form-control associado_bairro" name="associado_bairro"/>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>Telefone</label>
                                            <input type="text" class="form-control associado_telefone telefone" name="associado_telefone"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-3">
                                            <label>Cidade</label>
                                            <input type="text" readonly="" class="form-control associado_cidade" name="associado_cidade"/>
                                            <input type="hidden" readonly="" class="form-control associado_cidade_ibge" name="associado_cidade_ibge"/>
                                        </div>
                                        <div class="form-group col-lg-1">
                                            <label>Estado</label>
                                            <input type="text" readonly="" class="form-control associado_estado" name="associado_estado"/>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>Email</label>
                                            <input type="text" class="form-control associado_email" name="associado_email"/>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>Email Alternativo</label>
                                            <input type="text" class="form-control associado_email_alternativo" name="associado_email_alternativo"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Celular</label>
                                            <input type="text" class="form-control associado_celular telefone" name="associado_celular"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-12">
                                            <label>Obs</label>
                                            <textarea name="associado_obs" class="form-control associado_obs" cols="" rows=""></textarea>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-12" align="right">
                                            <button type="button" class="btn btn-primary" onclick="create();">Gravar</button>
                                            <a href="javascript::" onclick="carrega_pagina('associado', 'index.php');" class="btn btn-danger">Cancelar</a>
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
            url: "_controller/_associado.php",
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
        $(".associado_bairro").val('');
        $(".associado_cep").val('');
        $(".associado_cidade").val('');
        $(".associado_cidade_ibge").val('');
        $(".associado_complemento").val('');
        $(".associado_cpf_cnpj").val('');
        $(".associado_email_alternativo").val('');
        $(".associado_email").val('');
        $(".associado_endereco").val('');
        $(".associado_estado").val('');
        $(".associado_ie_rg").val('');
        $(".associado_nome_fantasia").val('');
        $(".associado_nome_razao").val('');
        $(".associado_numero").val('');
        $(".associado_telefone").val('');
        $(".associado_celular").val('');
        $(".associado_obs").val('');
    }
    function BuscaCEP(CEP_INFO){
        $.ajax({
            type: "GET",
            beforeSend: load_in(),
            url: "http://viacep.com.br/ws/"+CEP_INFO+"/json/?callback=",
            dataType: "jsonp",
            success: function (data) {
                if (data != null) {
                    $(".associado_estado").val(data.uf);
                    $(".associado_cidade").val(data.localidade);
                    $(".associado_cidade_ibge").val(data.ibge);
                    if($(".associado_bairro").val() === ''){
                        $(".associado_bairro").val(data.bairro);
                    }
                    if($(".associado_complemento").val() === ''){
                        $(".associado_complemento").val(data.complemento);
                    }
                    if($(".associado_endereco").val() === ''){
                        $(".associado_endereco").val(data.logradouro);
                    }
                }
                load_out();
            }
        });
        load_out();
    }
</script>