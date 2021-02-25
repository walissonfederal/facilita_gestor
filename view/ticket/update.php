<?php 
    session_start();
    ob_start();
    require_once '../../_class/Ferramenta.php';
?>
<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Ticket
                                <a href="javascript::" onclick="carrega_pagina('ticket', 'index.php');" class="btn btn-primary">Voltar</a>
                            </h3>
                        </div>
                        <div class="box-content">
                            <form action="" id="update">
                                <p>Id Protocolo: <?=$_GET['id'];?></p>
                                <fieldset>
                                    <?php
                                        if($_SESSION[VSESSION]['user_tipo_ticket'] == '0'){
                                    ?>
                                    <div class="row">
                                        <div class="form-group col-lg-3">
                                            <label>ID Contato</label>
                                            <input type="text" class="form-control ticket_id_contato" onblur="buscar_contato();" name="ticket_id_contato"/>
                                        </div>
                                        <div class="form-group col-lg-9">
                                            <label>Contato(Nome Razão, Nome Fantasia, CPF / CNPJ)</label>
                                            <input type="text" class="form-control ticket_nome_contato"/>
                                        </div>
                                    </div>
                                    <?php
                                        }
                                    ?>
                                    <div class="row">
                                        <div class="form-group col-lg-2">
                                            <label>Departamento</label>
                                            <select class="form-control ticket_id_departamento" name="ticket_id_departamento"></select>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Prioridade</label>
                                            <select class="form-control ticket_prioridade" name="ticket_prioridade">
                                                <option value="0">Sem prioridade</option>
                                                <option value="1">Baixa</option>
                                                <option value="2">Regular</option>
                                                <option value="3">Alta</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Prazo</label>
                                            <input type="date" class="form-control ticket_prazo" name="ticket_prazo"/>
                                        </div>
                                        <?php
                                            if($_SESSION[VSESSION]['user_tipo_ticket'] == '0'){
                                        ?>
                                        <div class="form-group col-lg-2">
                                            <label>Responsável</label>
                                            <select class="form-control ticket_id_user_final" name="ticket_id_user_final"></select>
                                        </div>
                                        <?php
                                            }
                                        ?>
                                        <div class="form-group col-lg-2">
                                            <label>Status</label>
                                            <select class="form-control ticket_status" name="ticket_status">
                                                <option value="0">Pendente</option>
                                                <option value="1">Fechado</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Assunto</label>
                                            <input type="text" class="form-control ticket_assunto" name="ticket_assunto"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-12" align="right">
                                            <button type="button" class="btn btn-primary" onclick="update();">Gravar</button>
                                            <a href="javascript::" onclick="carrega_pagina('ticket', 'index.php');" class="btn btn-danger">Voltar</a>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                            <div id="load_msg"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        load_departamento();
        load_responsavel();
        load_msg();
    });
    $(function() {
        $( ".ticket_nome_contato" ).autocomplete({
            source: "_controller/_contato.php?acao=load_contato",
            minLength: 2,
            focus: function(event, ui) {
            	event.preventDefault();
            },
            select: function( event, ui ) {
                $(".ticket_id_contato").val(ui.item.value);
                $(".ticket_nome_contato").val(ui.item.label);
                event.preventDefault();
            }
        });
    });
    function buscar_contato(){
        var ticket_id_contato = $(".ticket_id_contato").val();
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
                        $(".ticket_id_contato").val('');
                        $(".ticket_nome_contato").val('');
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
            url: "_controller/_ticket.php",
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
    function insert_ticket(){
        var msg_atendimento = $(".msg_itens_atendimento").val();
        var id_anexo = $(".id_anexo").val();
        var acao = "acao=insert_ticket&id=<?=$_GET['id'];?>&msg_atendimento="+msg_atendimento+"&id_anexo="+id_anexo;
        
        $.ajax({
            type: 'POST',
            url: "_controller/_ticket.php",
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
                    $(".id_anexo").val('');
                }
            }
        });
    }
    function refresh(){
        load_msg();
    }
    function load_departamento(){
        var acao = "acao=load_departamento";
        $.ajax({
            type: 'GET',
            url: "_controller/_departamento.php",
            data: acao,
            beforeSend: load_in(),
            async: false,
            success: function (data) {
                var data_return = jQuery.parseJSON(data);
                var options = '<option value=""></option>';
                for (var i = 0; i < data_return.data.length; i++) {
                    options += '<option value="' + data_return.data[i].departamento_id + '">' + data_return.data[i].departamento_descricao + '</option>';
                }
                $('.ticket_id_departamento').html(options).show();
                load_out();
            }
        });
        load_out();
    }
    function vincular_arquivo(id_arquivo){
        $(".id_anexo").val(id_arquivo);
        $("#arquivo_anexado").html('1 Anexo');
        $("#_modal_anexo_ticket").modal('hide');
    }
    function delete_arquivo_anexo(){
        $("#arquivo_anexado").html('');
        $(".id_anexo").val('');
    }
    function load_responsavel(){
        var acao = "acao=load_responsavel";
        $.ajax({
            type: 'GET',
            url: "_controller/_user.php",
            data: acao,
            beforeSend: load_in(),
            async: false,
            success: function (data) {
                var data_return = jQuery.parseJSON(data);
                var options = '<option value=""></option>';
                for (var i = 0; i < data_return.data.length; i++) {
                    options += '<option value="' + data_return.data[i].user_id + '">' + data_return.data[i].user_nome + '</option>';
                }
                $('.ticket_id_user_final').html(options).show();
                load_out();
            }
        });
        load_out();
    }
    function load_msg(){
        var acao = "&acao=load_msg&id=<?=$_GET['id'];?>";
        
        $.ajax({
            type: 'POST',
            url: "_controller/_ticket.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                $("#load_msg").html(data);
            }
        });
    }
    $(function(){
        var acao = "acao=load_update&id=<?=$_GET['id'];?>";
        $.ajax({
            type: 'POST',
            url: "_controller/_ticket.php",
            data: acao,
            beforeSend: load_in(),
            async: false,
            success: function (data) {
                load_out();
                var data_return = jQuery.parseJSON(data);
                $(".ticket_prioridade").val(data_return[0].ticket_prioridade);
                $(".ticket_id_departamento").val(data_return[0].ticket_id_departamento);
                $(".ticket_prazo").val(data_return[0].ticket_prazo);
                $(".ticket_id_user_final").val(data_return[0].ticket_id_user_final);
                $(".ticket_status").val(data_return[0].ticket_status);
                $(".ticket_assunto").val(data_return[0].ticket_assunto);
                $(".ticket_id_contato").val(data_return[0].ticket_id_contato);
                buscar_contato();
            }
        });
    });
    function open_anexo(){
        $("#_modal_anexo_ticket").modal('show');
        load_gallery();
    }
    function load_gallery(){
        var acao = "&acao=load_gallery&id=<?=$_GET['id'];?>";
        
        $.ajax({
            type: 'POST',
            url: "_controller/_ticket.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                $("#gallery_ticket").html(data);
            }
        });
    }
    $(document).ready(function () {
        $('#btnEnviar').click(function () {
            $('#formUpload').ajaxForm({
                uploadProgress: function (event, position, total, percentComplete) {
                    $('.progress-bar .progress-bar-success').attr('aria-valuenow', percentComplete);
                    $('progress').attr('value', percentComplete);
                    $('#porcentagem').html(percentComplete + '% Enviado');
                    $('#resposta').html('<div class="alert alert-success"><img src="_img/load.gif" class="load" /> <strong>Aguarde!</strong> Estou enviando o arquivo</div>');
                },
                success: function (data) {
                    $('progress').attr('value', '100');
                    $('#porcentagem').html('100%');
                    if (data.sucesso == true) {
                        $('#resposta').html('<div class="alert alert-success"><strong>Parabéns!</strong> Arquivo enviado</div>');
                        load_gallery();
                        $('#porcentagem').html('0%');
                    } else {
                        $('#resposta').html('<div class="alert alert-danger"><strong>Ops!</strong> ' + data.msg + '</div>');
                        $('#porcentagem').html('0%');
                    }
                },
                error: function () {
                    $('#resposta').html('<div class="alert alert-danger"><strong>Ops!</strong> Erro ao enviar requisição!!!</div>');
                    $('#porcentagem').html('0%');
                },
                dataType: 'json',
                url: '_controller/_ticket.php',
                resetForm: false
            }).submit();
        })
    });
</script>
<div id="_modal_anexo_ticket" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Anexar Arquivos ao atendimento</h4>
            </div>
            <div class="modal-body">
                <form name="formUpload" id="formUpload" method="post">
                    <div class="row">
                        <div class="form-group col-lg-12">
                            <label>Arquivo(PDF / JPG)</label>
                            <input type="file" name="arquivo" id="arquivo" size="45" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-12">
                            <div class="progress">
                                <div>
                                    <span id="porcentagem" class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100" style="width: 100%">0%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-12" align="right">
                            <input type="hidden" name="acao" value="anexo_ticket"/>
                            <input type="hidden" name="anexo_ticket_id_ticket" value="<?= $_GET['id']; ?>"/>
                            <button type="button" class="btn btn-primary" id="btnEnviar" >Gravar</button>
                        </div>
                    </div>
                </form>
                <div id="resposta">
                </div>
            </div>
            <div class="modal-footer">
                <div id="gallery_ticket"></div>
            </div>
        </div>
    </div>
</div>