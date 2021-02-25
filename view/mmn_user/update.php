<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Usuário
                                <a href="javascript::" onclick="carrega_pagina('mmn_user', 'index.php');" class="btn btn-primary">Voltar</a>
                            </h3>
                        </div>
                        <div class="box-content">
                            <form action="" id="update">
                                <fieldset>
                                    <div class="row">
                                        <div class="form-group col-lg-6">
                                            <label>Nome</label>
                                            <input type="text" class="form-control user_nome" name="user_nome"/>
                                        </div>
                                        <div class="form-group col-lg-1">
                                            <label>Status</label><br />
                                            <span class="user_status"></span>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Username</label><br />
                                            <span class="user_username"></span>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>Email</label>
                                            <input type="text" class="form-control user_email" name="user_email"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-2">
                                            <label>Telefone</label>
                                            <input type="text" class="form-control user_telefone telefone" name="user_telefone"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Celular</label>
                                            <input type="text" class="form-control user_celular telefone" name="user_celular"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>CPF</label>
                                            <input type="text" class="form-control user_cpf cpf" name="user_cpf"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>RG</label>
                                            <input type="text" class="form-control user_rg" name="user_rg"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Data Nascimento</label>
                                            <input type="date" class="form-control user_data_nascimento" name="user_data_nascimento"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>CEP</label>
                                            <input type="text" class="form-control user_cep cep" name="user_cep"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-4">
                                            <label>Endereço</label>
                                            <input type="text" class="form-control user_endereco" name="user_endereco"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Número</label>
                                            <input type="text" class="form-control user_numero" name="user_numero"/>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>Bairro</label>
                                            <input type="text" class="form-control user_bairro" name="user_bairro"/>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>Complemento</label>
                                            <input type="text" class="form-control user_complemento" name="user_complemento"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-4">
                                            <label>Cidade</label>
                                            <input type="text" class="form-control user_cidade" name="user_cidade"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>UF</label>
                                            <input type="text" class="form-control user_uf" name="user_uf"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Data</label><br />
                                            <span class="user_data"></span>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Data Ativação</label><br />
                                            <span class="user_data_ativacao"></span>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Patrocinador</label><br />
                                            <span class="user_id_pai"></span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-2">
                                            <label>Status</label>
                                            <select name="user_status" class="form-control user_status_correto">
                                                <option value="0">Inativo</option>
                                                <option value="1">Ativo</option>
                                                <option value="2">Cancelado</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-12" align="right">
                                            <button type="button" class="btn btn-primary" onclick="update();">Gravar</button>
                                            <button type="button" class="btn btn-success" onclick="reenviar();">Reenviar Boas-Vindas</button>
                                            <a href="javascript::" onclick="carrega_pagina('mmn_user', 'index.php');" class="btn btn-danger">Voltar</a>
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
            url: "_controller/_mmn_user.php",
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
    function reenviar(){
        var acao = "&acao=reenviar&id=<?=$_GET['id'];?>";
        
        $.ajax({
            type: 'POST',
            url: "_controller/_mmn_user.php",
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
            url: "_controller/_mmn_user.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                var data_return = jQuery.parseJSON(data);
                $(".user_nome").val(data_return[0].user_nome);
                $(".user_status").html(data_return[0].user_status);
                $(".user_status_correto").val(data_return[0].user_status);
                $(".user_username").html(data_return[0].user_username);
                $(".user_email").val(data_return[0].user_email);
                
                $(".user_telefone").val(data_return[0].user_telefone);
                $(".user_celular").val(data_return[0].user_celular);
                $(".user_rg").val(data_return[0].user_rg);
                $(".user_cpf").val(data_return[0].user_cpf);
                $(".user_data_nascimento").val(data_return[0].user_data_nascimento);
                $(".user_cep").val(data_return[0].user_cep);
                
                $(".user_endereco").val(data_return[0].user_endereco);
                $(".user_numero").val(data_return[0].user_numero);
                $(".user_bairro").val(data_return[0].user_bairro);
                $(".user_complemento").val(data_return[0].user_complemento);
                $(".user_cidade").val(data_return[0].user_cidade);
                $(".user_uf").val(data_return[0].user_uf);
                
                $(".user_data").html(data_return[0].user_data);
                $(".user_id_pai").html(data_return[0].user_id_pai);
                $(".user_data_ativacao").html(data_return[0].user_data_ativacao);
            }
        });
    });
</script>