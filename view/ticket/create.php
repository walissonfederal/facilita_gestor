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
                            <form action="" id="create">
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
                                        <div class="form-group col-lg-3">
                                            <label>Departamento</label>
                                            <select class="form-control ticket_id_departamento" name="ticket_id_departamento"></select>
                                        </div>
                                        <?php
                                            if($_SESSION[VSESSION]['user_tipo_ticket'] == '0'){
                                        ?>
                                        <div class="form-group col-lg-3">
                                            <label>Responsável</label>
                                            <select class="form-control ticket_id_user_final" name="ticket_id_user_final"></select>
                                        </div>
                                        <?php
                                            }
                                        ?>
                                        <div class="form-group col-lg-2">
                                            <label>Assunto</label>
                                            <input type="text" class="form-control ticket_assunto" name="ticket_assunto"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Prazo</label>
                                            <input type="date" class="form-control ticket_prazo" name="ticket_prazo"/>
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
                                            <a href="javascript::" onclick="carrega_pagina('ticket', 'index.php');" class="btn btn-danger">Voltar</a>
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
        load_departamento();
        load_responsavel();
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
    function load_departamento(){
        var acao = "acao=load_departamento";
        $.ajax({
            type: 'GET',
            url: "_controller/_departamento.php",
            data: acao,
            beforeSend: load_in(),
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
    function load_responsavel(){
        var acao = "acao=load_responsavel";
        $.ajax({
            type: 'GET',
            url: "_controller/_user.php",
            data: acao,
            beforeSend: load_in(),
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
    function create(){
        var dados = $("#create").serialize();
        var acao = "&acao=create";
        
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
    function limpar_campos(){
        $(".ticket_assunto").val('');
        $(".ticket_id_contato").val('');
        $(".ticket_nome_contato").val('');
        $(".ticket_prazo").val('');
    }
</script>