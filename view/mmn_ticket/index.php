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
                                <a href="javascript::" onclick="carrega_pagina('mmn_ticket', 'create.php');" class="btn btn-primary">Cadastrar Novo(Não usável)</a>
                            </h3>
                        </div>
                        <div class="box-content">
                            <form action="">
                                <fieldset>
                                    <div class="form-group col-lg-2">
                                        <label>ID User</label>
                                        <input type="text" class="form-control search_nome_id_user" onblur="buscar_contato();"/>
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label>User (Nome, CPF, Username, Email)</label>
                                        <input type="text" class="form-control search_nome_user"/>
                                    </div>
                                    <div class="form-group col-lg-2">
                                        <label>Responsável</label>
                                        <select class="form-control ticket_id_user_final"></select>
                                    </div>
                                    <div class="form-group col-lg-2">
                                        <label>ID</label>
                                        <input type="text" class="form-control search_id" value=""/>
                                    </div>
                                    <div class="form-group col-lg-2">
                                        <label>Assunto</label>
                                        <input type="text" class="form-control search_assunto" value=""/>
                                    </div>
                                    <div class="form-group col-lg-2">
                                        <label>Status</label>
                                        <select class="form-control search_status">
                                            <option value=""></option>
                                            <option value="0" >Pendente</option>
                                            <option value="1" >Fechado</option>
                                            <option value="2" >Cancelado</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-2">
                                        <label>Tipo Pesquisa</label>
                                        <select class="form-control search_tipo_pesquisa">
                                            <option value="ticket_data_criacao">Data Criação</option>
                                            <option value="ticket_data_fim">Data Fim</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-2">
                                        <label>Data Inicial</label>
                                        <input type="date" class="form-control search_data_inicial" value=""/>
                                    </div>
                                    <div class="form-group col-lg-2">
                                        <label>Data Final</label>
                                        <input type="date" class="form-control search_data_final" value=""/>
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
    var updateIcon = function (value, data, cell, row, options) { //plain text value
        return "<i class='fa fa-pencil' style='vertical-align:middle; padding:2px 0;' title='Editar'></i> ";
    };
    $("#load_ticket").tabulator({
        height: "350px",
        fitColumns: true,
        ajaxURL: "_controller/_mmn_ticket.php",
        ajaxParams: {acao: "load"},
        pagination: "remote",
        paginationSize: 100,
        paginationDataSent: {"page": "pageNo"},
        columns: [
            {
                formatter: updateIcon, width: 40, align: "center", onClick: function (e, cell, val, data) {
                    carrega_pagina('mmn_ticket', 'update.php?id='+data.ticket_id);
                }
            },
            {title: "ID", field: "ticket_id", sorter: "int", width: 75},
            {title: "Cliente", field: "ticket_id_user", sorter: "string"},
            {title: "Assunto", field: "ticket_assunto", sorter: "string"},
            {title: "Status", field: "ticket_status", sorter: "string"},
            {title: "Responsável", field: "ticket_id_responsavel", sorter: "string"},
            {title: "Data Inicial", field: "ticket_data_inicial", sorter: "string"},
            {title: "Data Fim", field: "ticket_data_final", sorter: "string"}
        ]
    });
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
                $('.ticket_id_user_final').html(options).show();
                load_out();
            }
        });
        load_out();
    }
    function search(){
        var search_id               = $(".search_id").val();
        var search_assunto          = $(".search_assunto").val();
        var search_status           = $(".search_status").val();
        var search_id_contato       = $(".search_nome_id_user").val();
        var search_id_user_final    = $(".ticket_id_user_final").val();
        var search_data_inicial     = $(".search_data_inicial").val();
        var search_data_final       = $(".search_data_final").val();
        var search_tipo_pesquisa    = $(".search_tipo_pesquisa").val();
        
        var acao = "acao=load&search=true&id="+search_id+"&assunto="+search_assunto+"&status="+search_status+"&id_contato="+search_id_contato+"&id_user_final="+search_id_user_final+"&data_inicial="+search_data_inicial+"&data_final="+search_data_final+"&tipo_pesquisa="+search_tipo_pesquisa;
        
        $.ajax({
            type: 'GET',
            url: "_controller/_mmn_ticket.php",
            data: acao,
            beforeSend: load_in(),
            success: function () {
                load_out();       
                $("#load_ticket").tabulator("setData", "_controller/_mmn_ticket.php?acao=load");
            }
        });
    }
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
</script>