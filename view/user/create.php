<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Usuários
                                <a href="javascript::" onclick="carrega_pagina('user', 'index.php');" class="btn btn-primary">Voltar</a>
                            </h3>
                        </div>
                        <div class="box-content">
                            <ul class="tabs tabs-inline tabs-top">
                                <li class="active">
                                    <a href="#t_principal" data-toggle="tab">PRINCIPAL</a>
                                </li>
                                <li>
                                    <a href="#t_info" data-toggle="tab">INFORMAÇÕES</a>
                                </li>
                                <li>
                                    <a href="#t_perfil" data-toggle="tab">PERFIL</a>
                                </li>
                                <li>
                                    <a href="#t_cliente" data-toggle="tab">CLIENTE</a>
                                </li>
                            </ul>
                            <form action="" id="create" method="post">
                                <div class="tab-content padding tab-content-inline tab-content-bottom">
                                    <div class="tab-pane active" id="t_principal">
                                        <div class="row">
                                            <div class="form-group col-lg-6">
                                                <label>Nome</label>
                                                <input type="text" class="form-control user_nome" name="user_nome"/>
                                            </div>
                                            <div class="form-group col-lg-3">
                                                <label>Login</label>
                                                <input type="text" class="form-control user_login" name="user_login"/>
                                            </div>
                                            <div class="form-group col-lg-3">
                                                <label>Senha</label>
                                                <input type="password" class="form-control user_senha" name="user_senha"/>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-lg-12">
                                                <label>Email</label>
                                                <input type="text" class="form-control user_email" name="user_email"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="t_info">
                                        <div class="row">
                                            <div class="form-group col-lg-6">
                                                <label>Nível</label>
                                                <select class="form-control user_id_nivel" name="user_id_nivel"></select>
                                            </div>
                                            <div class="form-group col-lg-6">
                                                <label>Status</label>
                                                <select class="form-control user_status" name="user_status">
                                                    <option value="0">Ativo</option>
                                                    <option value="1">Inativo</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-lg-6">
                                                <label>Caixa</label>
                                                <select class="form-control user_id_caixa" name="user_id_caixa"></select>
                                            </div>
                                            <div class="form-group col-lg-6">
                                                <label>Técnico</label>
                                                <select class="form-control user_tipo_tecnico" name="user_tipo_tecnico">
                                                    <option value="0">NÃO</option>
                                                    <option value="1">SIM</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-lg-12">
                                                <label>Usa CRM</label>
                                                <select class="form-control user_ticket" name="user_ticket">
                                                    <option value="0">NÃO</option>
                                                    <option value="1">SIM</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="t_perfil">
                                        <div class="row">
                                            <div class="row">
                                                <div class="form-group col-lg-12">
                                                    <label>Foto</label>
                                                    <input type="file" name="arquivo" id="arquivo" size="45" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="t_cliente">
                                        <div class="row">
                                            <div class="form-group col-lg-2">
                                                <label>ID Contato</label>
                                                <input type="text" class="form-control user_id_contato" onblur="buscar_contato();" name="user_id_contato"/>
                                            </div>
                                            <div class="form-group col-lg-10">
                                                <label>Contato(Nome Razão, Nome Fantasia, CPF / CNPJ)</label>
                                                <input type="text" class="form-control ticket_nome_contato"/>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-lg-2">
                                                <label>Ticket de terceitos?</label>
                                                <select class="form-control user_tipo_ticket" name="user_tipo_ticket">
                                                    <option value="1">NÃO</option>
                                                    <option value="0">SIM</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-12" align="right">
                                        <button type="button" class="btn btn-primary" onclick="create();">Gravar</button>
                                        <a href="javascript::" onclick="carrega_pagina('user', 'index.php');" class="btn btn-danger">Cancelar</a>
                                    </div>
                                </div>
                            </form>
                            <div class="row">
                                <div class="form-group col-lg-12">
                                    <div class="progress">
                                        <div>
                                            <span id="porcentagem" class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100" style="width: 100%">0%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="resposta">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function create(){
        $('#create').ajaxForm({
            uploadProgress: function(event, position, total, percentComplete) {
                $('.progress-bar .progress-bar-success').attr('aria-valuenow',percentComplete);
                $('progress').attr('value',percentComplete);
                $('#porcentagem').html(percentComplete+'% Enviado');
                $('#resposta').html('<div class="alert alert-success"><img src="_img/load.gif" class="load" /> <strong>Aguarde!</strong> Estou enviando o arquivo</div>');
            },        
            success: function(data) {
                $('progress').attr('value','100');
                $('#porcentagem').html('100%');                
                if(data.sucesso == true){
                    $('#resposta').html('<div class="alert alert-success"><strong>Parabéns!</strong> Arquivo enviado</div>');
                    $('#porcentagem').html('0%'); 
                }
                else{
                    $('#resposta').html('<div class="alert alert-danger"><strong>Ops!</strong> '+data.msg+'</div>');
                    $('#porcentagem').html('0%'); 
                }                
            },
            error : function(){
                $('#resposta').html('<div class="alert alert-danger"><strong>Ops!</strong> Erro ao enviar requisição!!!</div>');
                $('#porcentagem').html('0%'); 
            },
            dataType: 'json',
            url: '_controller/_user.php?acao=create',
            resetForm: false
        }).submit();
    }
    $(function(){
        var acao = "acao=load_nivel";
        $.ajax({
            type: 'GET',
            url: "_controller/_nivel.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                var data_return = jQuery.parseJSON(data);
                var options = '<option value=""></option>';
                for (var i = 0; i < data_return.data.length; i++) {
                    options += '<option value="' + data_return.data[i].nivel_id + '">' + data_return.data[i].nivel_descricao + '</option>';
                }
                $('.user_id_nivel').html(options).show();
                load_out();
            }
        });
        load_out();
    });
    $(function(){
        var acao = "acao=load_caixa";
        $.ajax({
            type: 'GET',
            url: "_controller/_caixa.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                var data_return = jQuery.parseJSON(data);
                var options = '<option value=""></option>';
                for (var i = 0; i < data_return.data.length; i++) {
                    options += '<option value="' + data_return.data[i].caixa_id + '">' + data_return.data[i].caixa_descricao + '</option>';
                }
                $('.user_id_caixa').html(options).show();
                load_out();
            }
        });
        load_out();
    });
    $(function() {
        $( ".ticket_nome_contato" ).autocomplete({
            source: "_controller/_contato.php?acao=load_contato",
            minLength: 2,
            focus: function(event, ui) {
            	event.preventDefault();
            },
            select: function( event, ui ) {
                $(".user_id_contato").val(ui.item.value);
                $(".ticket_nome_contato").val(ui.item.label);
                event.preventDefault();
            }
        });
    });
    function buscar_contato(){
        var ticket_id_contato = $(".user_id_contato").val();
        var acao = "acao=load_contato_id&id="+ticket_id_contato;
        
        if(ticket_id_contato){
            $.ajax({
                type: 'POST',
                url: "_controller/_contato.php",
                data: acao,
                beforeSend: load_in(),
                success: function (data) {
                    load_out();
                    var data_return = jQuery.parseJSON(data);
                    if(data_return[0].label !== ''){
                        $(".ticket_nome_contato").val(data_return[0].label);
                    }else{
                        $("#_modal").modal('show');
                        $("#title_modal").html('Erro');
                        $("#texto_modal").html('Ops, não foi encontrado nenhum registro');
                        $("#buttons_modal").html('');
                        $(".user_id_contato").val('');
                        $(".ticket_nome_contato").val('');
                    }
                }
            });
        }
    }
    function limpar_campos(){
        $(".caixa_descricao").val('');
        $(".caixa_status").val('0');
    }
</script>