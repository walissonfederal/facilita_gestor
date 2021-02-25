<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Ordem de Serviço
                                <a href="javascript::" onclick="carrega_pagina('os', 'index.php');" class="btn btn-primary">Voltar</a>
                            </h3>
                        </div>
                        <div class="box-content">
                            <form action="" id="update">
                                <fieldset>
                                    <div class="row">
                                        <div class="form-group col-lg-1">
                                            <label>ID Contato</label>
                                            <input type="text" class="form-control os_id_contato" name="os_id_contato" onblur="buscar_contato();"/>
                                        </div>
                                        <div class="form-group col-lg-5">
                                            <label>Contato</label>
                                            <input type="text" class="form-control os_nome_contato" name=""/>
                                        </div>
                                        <div class="form-group col-lg-1">
                                            <label>ID Técnico</label>
                                            <input type="text" class="form-control os_id_user" name="os_id_user" onblur="buscar_user();"/>
                                        </div>
                                        <div class="form-group col-lg-5">
                                            <label>Técnico</label>
                                            <input type="text" class="form-control os_nome_user" name=""/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-3">
                                            <label>ID Responsável</label>
                                            <input type="text" class="form-control os_id_responsavel" name="os_id_responsavel" onblur="buscar_user_responsavel();"/>
                                        </div>
                                        <div class="form-group col-lg-9">
                                            <label>Responsável</label>
                                            <input type="text" class="form-control os_nome_user_resp" name=""/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-3">
                                            <label>Status</label>
                                            <select class="form-control os_status" name="os_status">
                                                <option value="0">Orçamento</option>
                                                <option value="1">Aberto</option>
                                                <option value="2">Faturado</option>
                                                <option value="3">Finalizado</option>
                                                <option value="4">Cancelado</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>Data Inicial</label>
                                            <input type="date" class="form-control os_data_inicial" name="os_data_inicial"/>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>Data Final</label>
                                            <input type="date" class="form-control os_data_final" name="os_data_final"/>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>Garantia</label>
                                            <input type="text" class="form-control os_garantia" name="os_garantia"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-6">
                                            <label>Descrição Produto/Serviço</label>
                                            <textarea name="os_descricao" class="form-control os_descricao" cols="" rows=""></textarea>
                                        </div>
                                        <div class="form-group col-lg-6">
                                            <label>Defeito</label>
                                            <textarea name="os_defeito" class="form-control os_defeito" cols="" rows=""></textarea>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-6">
                                            <label>Obs</label>
                                            <textarea name="os_obs" class="form-control os_obs" cols="" rows=""></textarea>
                                        </div>
                                        <div class="form-group col-lg-6">
                                            <label>Laudo Técnico</label>
                                            <textarea name="os_laudo_tecnico" class="form-control os_laudo_tecnico" cols="" rows=""></textarea>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-12" align="right">
                                            <button type="button" class="btn btn-primary" onclick="update();">Gravar</button>
                                            <a href="javascript::" onclick="carrega_pagina('os', 'index.php');" class="btn btn-danger">Cancelar</a>
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
        $( ".os_nome_contato" ).autocomplete({
            source: "_controller/_contato.php?acao=load_contato",
            minLength: 2,
            focus: function(event, ui) {
            	event.preventDefault();
            },
            select: function( event, ui ) {
                $(".os_id_contato").val(ui.item.value);
                $(".os_nome_contato").val(ui.item.label);
                event.preventDefault();
            }
        });
        $( ".os_nome_user" ).autocomplete({
            source: "_controller/_user.php?acao=load_user",
            minLength: 2,
            focus: function(event, ui) {
            	event.preventDefault();
            },
            select: function( event, ui ) {
                $(".os_id_user").val(ui.item.value);
                $(".os_nome_user").val(ui.item.label);
                event.preventDefault();
            }
        });
        $( ".os_nome_user_resp" ).autocomplete({
            source: "_controller/_user.php?acao=load_user",
            minLength: 2,
            focus: function(event, ui) {
            	event.preventDefault();
            },
            select: function( event, ui ) {
                $(".os_id_responsavel").val(ui.item.value);
                $(".os_nome_user_resp").val(ui.item.label);
                event.preventDefault();
            }
        });
    });
    function buscar_user_responsavel(){
        var os_id_user_resp = $(".os_id_responsavel").val();
        var acao = "acao=load_user_id&id="+os_id_user_resp;
        
        if(os_id_user_resp){
            $.ajax({
                type: 'POST',
                url: "_controller/_user.php",
                data: acao,
                beforeSend: load_in(),
                success: function (data) {
                    load_out();
                    var data_return = jQuery.parseJSON(data);
                    if(data_return[0].label !== ''){
                        $(".os_nome_user_resp").val(data_return[0].label);
                    }else{
                        $("#_modal").modal('show');
                        $("#title_modal").html('Erro');
                        $("#texto_modal").html('Ops, não foi encontrado nenhum registro');
                        $("#buttons_modal").html('');
                        $(".os_id_responsavel").val('');
                        $(".os_nome_user_resp").val('');
                    }
                }
            });
        }
    }
    function buscar_contato(){
        var financeiro_id_contato = $(".os_id_contato").val();
        var acao = "acao=load_contato_id&id="+financeiro_id_contato;
        
        if(financeiro_id_contato){
            $.ajax({
                type: 'POST',
                url: "_controller/_contato.php",
                data: acao,
                beforeSend: load_in(),
                success: function (data) {
                    load_out();
                    var data_return = jQuery.parseJSON(data);
                    if(data_return[0].label !== ''){
                        $(".os_nome_contato").val(data_return[0].label);
                    }else{
                        $("#_modal").modal('show');
                        $("#title_modal").html('Erro');
                        $("#texto_modal").html('Ops, não foi encontrado nenhum registro');
                        $("#buttons_modal").html('');
                        $(".os_id_contato").val('');
                        $(".os_nome_contato").val('');
                    }
                }
            });
        }
    }
    function buscar_user(){
        var os_id_user = $(".os_id_user").val();
        var acao = "acao=load_user_id&id="+os_id_user;
        
        if(os_id_user){
            $.ajax({
                type: 'POST',
                url: "_controller/_user.php",
                data: acao,
                beforeSend: load_in(),
                success: function (data) {
                    load_out();
                    var data_return = jQuery.parseJSON(data);
                    if(data_return[0].label !== ''){
                        $(".os_nome_user").val(data_return[0].label);
                    }else{
                        $("#_modal").modal('show');
                        $("#title_modal").html('Erro');
                        $("#texto_modal").html('Ops, não foi encontrado nenhum registro');
                        $("#buttons_modal").html('');
                        $(".os_id_user").val('');
                        $(".os_nome_user").val('');
                    }
                }
            });
        }
    }
    function update(){
        var dados = $("#update").serialize();
        var acao = "&acao=update&id=<?=$_GET['id'];?>";
        
        $.ajax({
            type: 'POST',
            url: "_controller/_os.php",
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
            url: "_controller/_os.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                var data_return = jQuery.parseJSON(data);
                $(".os_id_contato").val(data_return[0].os_id_contato);
                $(".os_id_user").val(data_return[0].os_id_user);
                $(".os_status").val(data_return[0].os_status);
                $(".os_data_inicial").val(data_return[0].os_data_inicial);
                $(".os_data_final").val(data_return[0].os_data_final);
                $(".os_garantia").val(data_return[0].os_garantia);
                $(".os_descricao").val(data_return[0].os_descricao);
                $(".os_defeito").val(data_return[0].os_defeito);
                $(".os_obs").val(data_return[0].os_obs);
                $(".os_laudo_tecnico").val(data_return[0].os_laudo_tecnico);
                $(".os_id_responsavel").val(data_return[0].os_id_responsavel);
                buscar_contato();
                buscar_user();
                buscar_user_responsavel();
            }
        });
    });
    function limpar_campos(){
        $(".caixa_descricao").val('');
        $(".caixa_status").val('0');
    }
</script>