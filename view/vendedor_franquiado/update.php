<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Vendedor / Franquiado
                                <a href="javascript::" onclick="carrega_pagina('vendedor_franquiado', 'index.php');" class="btn btn-primary">Voltar</a>
                            </h3>
                        </div>
                        <div class="box-content">
                            <form action="" id="update">
                                <fieldset>
                                    <div class="row">
                                        <div class="form-group col-lg-6">
                                            <label>Nome</label>
                                            <input type="text" class="form-control vendedor_nome" name="vendedor_nome"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Telefone</label>
                                            <input type="text" class="form-control vendedor_telefone telefone" name="vendedor_telefone"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Celular</label>
                                            <input type="text" class="form-control vendedor_celular telefone" name="vendedor_celular"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>CPF</label>
                                            <input type="text" class="form-control vendedor_cpf" name="vendedor_cpf"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-4">
                                            <label>Email</label>
                                            <input type="text" class="form-control vendedor_email" name="vendedor_email"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Senha (Caso queira alterar)</label>
                                            <input type="password" class="form-control vendedor_senha" name="vendedor_senha"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>CEP</label>
                                            <input type="text" class="form-control vendedor_cep cep" onblur="BuscaCEP(this.value);" name="vendedor_cep"/>
                                        </div>
                                        <div class="form-group col-lg-4">
                                            <label>Endereço</label>
                                            <input type="text" class="form-control vendedor_endereco" name="vendedor_endereco"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-2">
                                            <label>Número</label>
                                            <input type="text" class="form-control vendedor_numero" name="vendedor_numero"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Bairro</label>
                                            <input type="text" class="form-control vendedor_bairro" name="vendedor_bairro"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Complemento</label>
                                            <input type="text" class="form-control vendedor_complemento" name="vendedor_complemento"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Cidade</label>
                                            <input type="text" readonly="" class="form-control vendedor_cidade" name="vendedor_cidade"/>
                                        </div>
                                        <div class="form-group col-lg-1">
                                            <label>UF</label>
                                            <input type="text" readonly="" class="form-control vendedor_estado" name="vendedor_estado"/>
                                        </div>
                                        <div class="form-group col-lg-1">
                                            <label>Telefonia</label>
                                            <input type="text" class="form-control vendedor_comissao_telefonia" name="vendedor_comissao_telefonia" id="valor_1"/>
                                        </div>
                                        <div class="form-group col-lg-1">
                                            <label>Monitoramento</label>
                                            <input type="text" class="form-control vendedor_comissao_monitoramento" name="vendedor_comissao_monitoramento" id="valor_2"/>
                                        </div>
                                        <div class="form-group col-lg-1">
                                            <label>Rastreamento</label>
                                            <input type="text" class="form-control vendedor_comissao_rastreamento" name="vendedor_comissao_rastreamento" id="valor_3"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-12">
                                            <label>Obs</label>
                                            <textarea class="form-control vendedor_obs" name="vendedor_obs" cols="" rows=""></textarea>
                                        </div>
                                    </div>
                                    <input type="hidden" name="vendedor_cidade_ibge" class="vendedor_cidade_ibge"/>
                                    <div class="row">
                                        <div class="form-group col-lg-12" align="right">
                                            <button type="button" class="btn btn-primary" onclick="update();">Gravar</button>
                                            <a href="javascript::" onclick="carrega_pagina('vendedor_franquiado', 'index.php');" class="btn btn-danger">Voltar</a>
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
    function update(){
        var dados = $("#update").serialize();
        var acao = "&acao=update&id=<?=$_GET['id'];?>";
        
        $.ajax({
            type: 'POST',
            url: "_controller/_vendedor_franquiado.php",
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
        var acao = "acao=load_update&id=<?=$_GET['id'];?>";
        $.ajax({
            type: 'POST',
            url: "_controller/_vendedor_franquiado.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                var data_return = jQuery.parseJSON(data);
                $(".vendedor_nome").val(data_return[0].vendedor_nome);
                $(".vendedor_telefone").val(data_return[0].vendedor_telefone);
                $(".vendedor_celular").val(data_return[0].vendedor_celular);
                $(".vendedor_email").val(data_return[0].vendedor_email);
                $(".vendedor_obs").val(data_return[0].vendedor_obs);
                $(".vendedor_cpf").val(data_return[0].vendedor_cpf);
                $(".vendedor_cep").val(data_return[0].vendedor_cep);
                $(".vendedor_endereco").val(data_return[0].vendedor_endereco);
                $(".vendedor_numero").val(data_return[0].vendedor_numero);
                $(".vendedor_bairro").val(data_return[0].vendedor_bairro);
                $(".vendedor_complemento").val(data_return[0].vendedor_complemento);
                $(".vendedor_cidade").val(data_return[0].vendedor_cidade);
                $(".vendedor_estado").val(data_return[0].vendedor_estado);
                $(".vendedor_cidade_ibge").val(data_return[0].vendedor_cidade_ibge);
                $(".vendedor_comissao_telefonia").val(data_return[0].vendedor_comissao_telefonia);
                $(".vendedor_comissao_monitoramento").val(data_return[0].vendedor_comissao_monitoramento);
                $(".vendedor_comissao_rastreamento").val(data_return[0].vendedor_comissao_rastreamento);
            }
        });
    });
    function BuscaCEP(CEP_INFO){
        $.ajax({
            type: "GET",
            beforeSend: load_in(),
            url: "http://viacep.com.br/ws/"+CEP_INFO+"/json/?callback=",
            dataType: "jsonp",
            success: function (data) {
                if (data != null) {
                    $(".vendedor_estado").val(data.uf);
                    $(".vendedor_cidade").val(data.localidade);
                    $(".vendedor_cidade_ibge").val(data.ibge);
                    if($(".vendedor_bairro").val() === ''){
                        $(".vendedor_bairro").val(data.bairro);
                    }
                    if($(".vendedor_complemento").val() === ''){
                        $(".vendedor_complemento").val(data.complemento);
                    }
                    if($(".vendedor_endereco").val() === ''){
                        $(".vendedor_endereco").val(data.logradouro);
                    }
                }
                load_out();
            }
        });
        load_out();
    }
    $("#valor_1").maskMoney({thousands:'', decimal:'.'});
    $("#valor_2").maskMoney({thousands:'', decimal:'.'});
    $("#valor_3").maskMoney({thousands:'', decimal:'.'});
</script>