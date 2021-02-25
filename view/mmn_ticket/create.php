<?php 
    session_start();
    ob_start();
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
                            <form action="" id="create">
                                <fieldset>
                                    <div class="row">
                                        <div class="form-group col-lg-3">
                                            <label>ID User</label>
                                            <input type="text" class="form-control search_nome_id_user" name="ticket_id_user" onblur="buscar_contato();"/>
                                        </div>
                                        <div class="form-group col-lg-9">
                                            <label>User (Nome, CPF)</label>
                                            <input type="text" class="form-control search_nome_user"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-3">
                                            <label>Responsável</label>
                                            <select class="form-control ticket_id_responsavel" name="ticket_id_responsavel"></select>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Assunto</label>
                                            <input type="text" class="form-control ticket_assunto" name="ticket_assunto"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Prazo</label>
                                            <input type="date" class="form-control ticket_data_final" name="ticket_data_final"/>
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
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-12">
                                            <label>Descrição</label>
                                            <textarea class="form-control itens_ticket_descricao" cols="" rows="" name="itens_ticket_descricao"></textarea>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-12" align="right">
                                            <button type="button" class="btn btn-primary" onclick="create();">Gravar</button>
                                            <a href="javascript::" onclick="carrega_pagina('mmn_ticket', 'index.php');" class="btn btn-danger">Voltar</a>
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
    $(function(){
        load_atendente();
    });
    $(function() {
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
                        $(".search_nome_contato").val(data_return[0].label);
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
    function create(){
        var dados = $("#create").serialize();
        var acao = "&acao=create";
        
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
    function limpar_campos(){
        $(".ticket_assunto").val('');
        $(".ticket_id_contato").val('');
        $(".ticket_nome_contato").val('');
        $(".ticket_prazo").val('');
    }
</script>