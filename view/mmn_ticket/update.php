<?php 
    session_start();
    ob_start();
    require_once '../../_class_mmn/Ferramenta.php';
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
                                <a href="javascript::" onclick="carrega_pagina('mmn_ticket', 'index.php');" class="btn btn-primary">Voltar</a>
                            </h3>
                        </div>
                        <div class="box-content">
                            <form action="" id="update">
                                <fieldset>
                                    <div class="row">
                                        <div class="form-group col-lg-4">
                                            <label>ID User</label>
                                            <input type="text" class="form-control search_nome_id_user" name="ticket_id_user" onblur="buscar_contato();"/>
                                        </div>
                                        <div class="form-group col-lg-8">
                                            <label>User (Nome, CPF, Username, Email)</label>
                                            <input type="text" class="form-control search_nome_user"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-4">
                                            <label>Responsável</label>
                                            <select class="form-control ticket_id_responsavel" name="ticket_id_responsavel"></select>
                                        </div>
                                        <div class="form-group col-lg-4">
                                            <label>Status</label>
                                            <select class="form-control ticket_status" name="ticket_status">
                                                <option value="0">Pendente</option>
                                                <option value="1">Fechado</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-4">
                                            <label>Assunto</label>
                                            <input type="text" class="form-control ticket_assunto" name="ticket_assunto"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-12" align="right">
                                            <button type="button" class="btn btn-primary" onclick="update();">Gravar</button>
                                            <a href="javascript::" onclick="carrega_pagina('mmn_ticket', 'index.php');" class="btn btn-danger">Voltar</a>
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
        load_atendente();
        load_msg();
    });
    $(function() {
        load_atendente();
        $( ".search_nome_user" ).autocomplete({
            source: "_controller/_mmn_pedidos.php?acao=load_user",
            minLength: 2,
            focus: function(event, ui) {
            	event.preventDefault();
            },
            select: function( event, ui ) {
                $(".search_nome_id_user").val(ui.item.value);
                $(".search_nome_user").val(ui.item.label);
                event.preventDefault();
            }
        });
    });
    function buscar_contato(){
        var search_nome_id_contato = $(".search_nome_id_user").val();
        var acao = "acao=load_user_id&id="+search_nome_id_contato;
        
        if(search_nome_id_contato){
            $.ajax({
                type: 'POST',
                url: "_controller/_mmn_pedidos.php",
                data: acao,
                beforeSend: load_in(),
                success: function (data) {
                    load_out();
                    var data_return = jQuery.parseJSON(data);
                    if(data_return[0].label !== ''){
                        $(".search_nome_user").val(data_return[0].label);
                    }else{
                        $("#_modal").modal('show');
                        $("#title_modal").html('Erro');
                        $("#texto_modal").html('Ops, não foi encontrado nenhum registro');
                        $("#buttons_modal").html('');
                        $(".search_nome_id_user").val('');
                        $(".search_nome_user").val('');
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
            url: "_controller/_mmn_ticket.php",
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
            url: "_controller/_mmn_ticket.php",
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
    function refresh(){
        load_msg();
    }
    function load_atendente(){
        var acao = "acao=load_atendente";
        $.ajax({
            type: 'GET',
            url: "_controller/_mmn_ticket.php",
            data: acao,
            beforeSend: load_in(),
            async: false,
            success: function (data) {
                var data_return = jQuery.parseJSON(data);
                var options = '<option value=""></option>';
                for (var i = 0; i < data_return.data.length; i++) {
                    options += '<option value="' + data_return.data[i].atendente_id + '">' + data_return.data[i].atendente_nome + '</option>';
                }
                $('.ticket_id_responsavel').html(options).show();
                load_out();
            }
        });
        load_out();
    }
    function load_msg(){
        var acao = "&acao=load_msg&id=<?=$_GET['id'];?>";
        
        $.ajax({
            type: 'POST',
            url: "_controller/_mmn_ticket.php",
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
            url: "_controller/_mmn_ticket.php",
            data: acao,
            beforeSend: load_in(),
            async: false,
            success: function (data) {
                load_out();
                var data_return = jQuery.parseJSON(data);
                $(".ticket_id_responsavel").val(data_return[0].ticket_id_responsavel);
                $(".ticket_status").val(data_return[0].ticket_status);
                $(".ticket_assunto").val(data_return[0].ticket_assunto);
                $(".search_nome_id_user").val(data_return[0].ticket_id_user);
                buscar_contato();
            }
        });
    });
</script>