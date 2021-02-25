<?php
session_start();
unset($_SESSION['desinstalacao_chip']);
unset($_SESSION['desinstalacao_chip_multa']);
?>
<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Pedidos
                                <a href="javascript::" onclick="carrega_pagina('pedido', 'index.php');" class="btn btn-primary">Voltar</a>
                            </h3>
                        </div>
                        <div class="box-content">
                            <form action="" id="create">
                                <fieldset>
                                    <div class="row">
                                        <div class="form-group col-lg-2">
                                            <label>ID Contato</label>
                                            <input type="text" class="form-control pedido_id_cliente" onblur="buscar_contato();" name="pedido_id_cliente"/>
                                        </div>
                                        <div class="form-group col-lg-8">
                                            <label>Contato(Nome Razão, Nome Fantasia, CPF / CNPJ)</label>
                                            <input type="text" class="form-control pedido_nome_cliente"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Tipo Cancelamento</label>
                                            <select class="form-control tipo_cancelamento" name="pedido_tipo_cancelamento">
                                                <option value=""></option>
                                                <option value="0">Chip Dados</option>
                                                <option value="1">Chip SMS</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-2">
                                            <label>Código Rastreio</label>
                                            <input type="text" class="form-control codigo_rastreio" name="pedido_codigo_rastreio"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Data Pedido</label>
                                            <input type="date" class="form-control pedido_data" name="pedido_data"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Data Ativação</label>
                                            <input type="date" class="form-control pedido_data_ativacao" name="pedido_data_ativacao"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Status</label>
                                            <select name="pedido_status" class="form-control">
                                                <option value="0">Em andamento</option>
                                                <option value="1">Finalizado</option>
                                                <option value="2">Cancelado</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Plano</label>
                                            <select name="pedido_id_plano" class="form-control pedido_id_plano" onchange="buscar_valor_plano();"></select>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Valor Plano</label>
                                            <input type="text" class="form-control pedido_valor_plano" name="pedido_valor_plano" id="valor_1"/>
                                        </div>
                                    </div>
                                    <input type="hidden" name="pedido_tipo" value="1"/>
                                    <div class="row">
                                        <div class="form-group col-lg-12">
                                            <label>Obs</label>
                                            <textarea name="pedido_obs" class="form-control pedido_obs"></textarea>
                                        </div>
                                    </div>
                                    <ul class="tabs tabs-inline tabs-top">
                                        <li class="active">
                                            <a href="#t_principal" data-toggle="tab">NOVO</a>
                                        </li>
                                        <li>
                                            <a href="#t_list" data-toggle="tab">LISTAR</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content padding tab-content-inline tab-content-bottom">
                                        <div class="tab-pane active" id="t_principal">
                                            <div class="row">
                                                <div class="form-group col-lg-12">
                                                    <label>Importar Arquivo</label>
                                                    <textarea id="arquivo_txt" class="form-control" cols="" rows="10" placeholder="8955066732900040174
                                                              8955066732900040174
                                                              8955066732900040174
                                                              8955066732900040174"></textarea>
                                                </div>
                                                <input type="hidden" id="linhas" value="0">
                                                <p>Linhas: <span id="contador"></span></p>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-lg-12" align="right">
                                                    <button type="button" class="btn btn-primary" onclick="insert_pedido_desinstalacao_massa();">Inserir</button>
                                                </div>
                                            </div>
                                            <hr/>
                                        </div>
                                        <div class="tab-pane" id="t_list">
                                            <div class="form-group col-lg-8">
                                                <label>Chip(Linha e ICCID)</label>
                                                <input type="hidden" class="id_chip_insert"/>
                                                <input type="text" class="form-control search_chip_linha_iccid"/>
                                            </div>
                                            <div class="form-group col-lg-2">
                                                <label>Devolução do chip?</label>
                                                <select class="form-control devolucao_chip">
                                                    <option value="1">Não</option>
                                                    <option value="0">Sim</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-lg-2">
                                                <label>.</label><br />
                                                <button type="button" class="btn btn-primary" onclick="insert_pedido_desinstalacao();">Inserir</button>
                                            </div>
                                            <hr />
                                            <div id="load_chip_pedido_desinstalacao"></div>
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
    document.getElementById('arquivo_txt').onkeyup = function () {
        count = this.value.split("\n").length;
        document.getElementById('contador').innerHTML = count;
        document.getElementById('linhas').value = count;
    }
    $(function () {
        load_plano();
        $(".pedido_nome_cliente").autocomplete({
            source: "_controller/_contato.php?acao=load_contato",
            minLength: 2,
            focus: function (event, ui) {
                event.preventDefault();
            },
            select: function (event, ui) {
                $(".pedido_id_cliente").val(ui.item.value);
                $(".pedido_nome_cliente").val(ui.item.label);
                event.preventDefault();
            }
        });
    });
    $(function () {
        $(".search_chip_linha_iccid").autocomplete({
            source: "_controller/_contrato_chip.php?acao=load_chip_insert",
            minLength: 2,
            focus: function (event, ui) {
                event.preventDefault();
            },
            select: function (event, ui) {
                $(".id_chip_insert").val(ui.item.value);
                $(".search_chip_linha_iccid").val(ui.item.label);
                event.preventDefault();
            }
        });
    });
    function load_chip_pedido() {
        var acao = "&acao=load_chip_desinstalacao";

        $.ajax({
            type: 'POST',
            url: "_controller/_contrato_chip.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                $("#load_chip_pedido_desinstalacao").html(data);
            }
        });
    }
    function delete_chip_pedido(id_chip) {
        var acao = "&acao=delete_chip_desinstalacao&id_chip=" + id_chip;

        $.ajax({
            type: 'POST',
            url: "_controller/_contrato_chip.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                load_chip_pedido();
            }
        });
    }
    function insert_pedido_desinstalacao() {
        var id_chip = $(".id_chip_insert").val();
        var id_contato = $(".pedido_id_cliente").val();
        var devolucao_chip = $(".devolucao_chip").val();
        var acao = "&acao=insert_chip_desinstalacao&id_contato=" + id_contato + "&id_chip=" + id_chip + "&devolucao_chip=" + devolucao_chip;

        $.ajax({
            type: 'POST',
            url: "_controller/_contrato_chip.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                var data_return = jQuery.parseJSON(data);
                if (data_return.type === 'error') {
                    $("#_modal").modal('show');
                    $("#title_modal").html(data_return.title);
                    $("#texto_modal").html(data_return.msg);
                    $("#buttons_modal").html(data_return.buttons);
                } else {
                    load_chip_pedido();
                }
            }
        });
    }
    function insert_pedido_desinstalacao_massa() {
        var arquivo_txt = $("#arquivo_txt").val();
        var id_contato = $(".pedido_id_cliente").val();
        var devolucao_chip = $(".devolucao_chip").val();
        var acao = "&acao=insert_chip_desinstalacao_massa&id_contato=" + id_contato + "&arquivo_txt=" + arquivo_txt + "&devolucao_chip=" + devolucao_chip;

        $.ajax({
            type: 'POST',
            url: "_controller/_contrato_chip.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                var data_return = jQuery.parseJSON(data);
                if (data_return.type === 'error') {

                } else {
                    load_chip_pedido();
                }
            }
        });
    }
    function buscar_contato() {
        var pedido_id_cliente = $(".pedido_id_cliente").val();
        var acao = "acao=load_contato_id&id=" + pedido_id_cliente;

        if (pedido_id_cliente) {
            $.ajax({
                type: 'POST',
                url: "_controller/_contato.php",
                data: acao,
                beforeSend: load_in(),
                success: function (data) {
                    load_out();
                    var data_return = jQuery.parseJSON(data);
                    if (data_return[0].label !== '') {
                        $(".pedido_nome_cliente").val(data_return[0].label);
                    } else {
                        $("#_modal").modal('show');
                        $("#title_modal").html('Erro');
                        $("#texto_modal").html('Ops, não foi encontrado nenhum registro');
                        $("#buttons_modal").html('');
                        $(".pedido_id_cliente").val('');
                        $(".pedido_nome_cliente").val('');
                    }
                }
            });
        }
    }
    function buscar_valor_plano() {
        var valor_plano = $(".pedido_id_plano").val();
        var acao = "acao=search_valor_plano&valor_plano=" + valor_plano;
        $.ajax({
            type: 'GET',
            url: "_controller/_pedido.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                $(".pedido_valor_plano").val(data);
                load_out();
            }
        });
        load_out();
    }
    function validar() {
        var dados = $("#create").serialize();
        var acao = "&acao=create_desinstalacao";

        $.ajax({
            type: 'POST',
            url: "_controller/_contrato_chip.php",
            data: dados + acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                var data_return = jQuery.parseJSON(data);
                if (data_return.type === 'error') {
                    $("#_modal").modal('show');
                    $("#title_modal").html(data_return.title);
                    $("#texto_modal").html(data_return.msg);
                    $("#buttons_modal").html(data_return.buttons);
                } else {
                    $("#_modal").modal('show');
                    $("#title_modal").html(data_return.title);
                    $("#texto_modal").html(data_return.msg);
                    $("#buttons_modal").html(data_return.buttons);
                }
            }
        });
    }
    function load_plano() {
        var acao = "acao=load_plano";
        $.ajax({
            type: 'GET',
            url: "_controller/_pedido.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                var data_return = jQuery.parseJSON(data);
                var options = '<option value=""></option>';
                for (var i = 0; i < data_return.data.length; i++) {
                    options += '<option value="' + data_return.data[i].plano_id + '">' + data_return.data[i].plano_descricao + '</option>';
                }
                $('.pedido_id_plano').html(options).show();
                load_out();
            }
        });
        load_out();
    }
    function limpar_campos() {
        $(".caixa_descricao").val('');
        $(".caixa_status").val('0');
    }
</script>