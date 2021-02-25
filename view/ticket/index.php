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
                                <a href="javascript::" onclick="carrega_pagina('ticket', 'create.php');" class="btn btn-primary">Cadastrar Novo</a>
                            </h3>
                        </div>
                        <div class="box-content">
                            <form action="">
                                <fieldset>
                                    <?php
                                        if($_SESSION[VSESSION]['user_tipo_ticket'] == '0'){
                                    ?>
                                    <div class="form-group col-lg-2">
                                        <label>ID Contato</label>
                                        <input type="text" class="form-control search_id_contato" onblur="buscar_contato();"/>
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label>Contato(Nome Razão, Nome Fantasia, CPF / CNPJ)</label>
                                        <input type="text" class="form-control ticket_nome_contato"/>
                                    </div>
                                    <?php
                                        }
                                    ?>
                                    <div class="form-group col-lg-2">
                                        <label>Departamento</label>
                                        <select class="form-control ticket_id_departamento"></select>
                                    </div>
                                    <?php
                                        if($_SESSION[VSESSION]['user_tipo_ticket'] == '0'){
                                    ?>
                                    <div class="form-group col-lg-2">
                                        <label>Responsável</label>
                                        <select class="form-control ticket_id_user_final"></select>
                                    </div>
                                    <?php
                                        }
                                    ?>
                                    <div class="form-group col-lg-2">
                                        <label>ID</label>
                                        <input type="text" class="form-control search_id" value="<?php echo $_SESSION['ticket_id'];?>"/>
                                    </div>
                                    <div class="form-group col-lg-2">
                                        <label>Assunto</label>
                                        <input type="text" class="form-control search_assunto" value="<?php echo $_SESSION['ticket_assunto'];?>"/>
                                    </div>
                                    <div class="form-group col-lg-2">
                                        <label>Status</label>
                                        <select class="form-control search_status">
                                            <option value=""></option>
                                            <option value="0" <?php if($_SESSION['ticket_status'] == '0'){echo 'selected';}?>>Pendente</option>
                                            <option value="1" <?php if($_SESSION['ticket_status'] == '1'){echo 'selected';}?>>Fechado</option>
                                            <option value="2" <?php if($_SESSION['ticket_status'] == '2'){echo 'selected';}?>>Cancelado</option>
                                        </select>
                                    </div>
                                    <?php
                                        if($_SESSION[VSESSION]['user_tipo_ticket'] == '0'){
                                    ?>
                                    <div class="form-group col-lg-2">
                                        <label>Solicitante</label>
                                        <select class="form-control ticket_id_user_inicial"></select>
                                    </div>
                                    <?php
                                        }
                                    ?>
                                    <div class="form-group col-lg-2">
                                        <label>Tipo Pesquisa</label>
                                        <select class="form-control search_tipo_pesquisa">
                                            <option value="ticket_prazo" <?php if($_SESSION['ticket_tipo_pesquisa'] == 'ticket_prazo'){echo 'selected';}?>>Prazo</option>
                                            <option value="ticket_data_criacao" <?php if($_SESSION['ticket_tipo_pesquisa'] == 'ticket_data_criacao'){echo 'selected';}?>>Data Criação</option>
                                            <option value="ticket_data_fim" <?php if($_SESSION['ticket_tipo_pesquisa'] == 'ticket_data_fim'){echo 'selected';}?>>Data Fim</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-2">
                                        <label>Data Inicial</label>
                                        <input type="date" class="form-control search_data_inicial" value="<?php echo $_SESSION['ticket_data_inicial'];?>"/>
                                    </div>
                                    <div class="form-group col-lg-2">
                                        <label>Data Final</label>
                                        <input type="date" class="form-control search_data_final" value="<?php echo $_SESSION['ticket_data_final'];?>"/>
                                    </div>
                                    <div class="form-group col-lg-2">
                                        <label>.</label><br />
                                        <button type="button" class="btn btn-primary" onclick="search();">Pesquisar</button>
                                    </div>
                                </fieldset>
                            </form>
                            <hr />
                            <div id="load_ticket"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function() {
        load_departamento();
        load_responsavel();
        load_solicitante();
        $( ".ticket_nome_contato" ).autocomplete({
            source: "_controller/_contato.php?acao=load_contato",
            minLength: 2,
            focus: function(event, ui) {
            	event.preventDefault();
            },
            select: function( event, ui ) {
                $(".search_id_contato").val(ui.item.value);
                $(".ticket_nome_contato").val(ui.item.label);
                event.preventDefault();
            }
        });
    });
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
    $(function(){
        $(".ticket_id_departamento").val(<?php echo $_SESSION['ticket_departamento'];?>);
    });
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
    function load_solicitante(){
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
                $('.ticket_id_user_inicial').html(options).show();
                load_out();
            }
        });
        load_out();
    }
    function buscar_contato(){
        var ticket_id_contato = $(".search_id_contato").val();
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
                        $(".search_id_contato").val('');
                        $(".ticket_nome_contato").val('');
                    }
                }
            });
        }
    }
    var updateIcon = function (value, data, cell, row, options) { //plain text value
        return "<i class='fa fa-pencil' style='vertical-align:middle; padding:2px 0;' title='Editar'></i> ";
    };
    $("#load_ticket").tabulator({
        height: "350px",
        fitColumns: true,
        ajaxURL: "_controller/_ticket.php",
        ajaxParams: {acao: "load"},
        pagination: "remote",
        paginationSize: 100,
        paginationDataSent: {"page": "pageNo"},
        columns: [
            {
                formatter: updateIcon, width: 40, align: "center", onClick: function (e, cell, val, data) {
                    carrega_pagina('ticket', 'update.php?id='+data.ticket_id);
                }
            },
            {title: "ID", field: "ticket_id", sorter: "int", width: 75},
            {title: "Cliente", field: "ticket_id_contato", sorter: "string"},
            {title: "Assunto", field: "ticket_assunto", sorter: "string"},
            {title: "Status", field: "ticket_status", sorter: "string"},
            {title: "Responsável", field: "ticket_id_user_final", sorter: "string"},
            {title: "Prazo", field: "ticket_prazo", sorter: "string"},
            {title: "Prioridade", field: "ticket_prioridade", sorter: "string"},
            {title: "Data Inicial", field: "ticket_data_criacao", sorter: "string"},
            {title: "Data Fim", field: "ticket_data_fim", sorter: "string"}
        ]
    });
    
    function search(){
        var search_id           = $(".search_id").val();
        var search_assunto      = $(".search_assunto").val();
        var search_status       = $(".search_status").val();
        var search_id_contato   = $(".search_id_contato").val();
        var search_id_departamento  = $(".ticket_id_departamento").val();
        var search_id_user_final    = $(".ticket_id_user_final").val();
        var search_id_user_inicial    = $(".ticket_id_user_inicial").val();
        var search_data_inicial = $(".search_data_inicial").val();
        var search_data_final = $(".search_data_final").val();
        var search_tipo_pesquisa = $(".search_tipo_pesquisa").val();
        
        var acao = "acao=load&search=true&id="+search_id+"&assunto="+search_assunto+"&status="+search_status+"&id_contato="+search_id_contato+"&id_departamento="+search_id_departamento+"&id_user_final="+search_id_user_final+"&id_user_inicial="+search_id_user_inicial+"&data_inicial="+search_data_inicial+"&data_final="+search_data_final+"&tipo_pesquisa="+search_tipo_pesquisa;
        
        $.ajax({
            type: 'GET',
            url: "_controller/_ticket.php",
            data: acao,
            beforeSend: load_in(),
            success: function () {
                load_out();
                <?php
                    if($_SESSION[VSESSION]['user_tipo_ticket'] == '0'){
                ?>
                $("#load_ticket").tabulator("setData", "_controller/_ticket.php?acao=load");
                <?php
                    }else{
                ?>
                carrega_pagina('ticket', 'index.php');
                <?php
                    }
                ?>                
            }
        });
    }
</script>