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
                                    <form action="" id="verificar_chip">
                                        <fieldset>
                                            <div class="row">
                                                <div class="form-group col-lg-12">
                                                    <label>Importar Arquivo - <a href="_modelo_arq/modelo_arq.txt" target="_blank">Baixe Modelo do Arquivo</a></label>
                                                    <textarea name="arquivo_txt" id="arquivo_txt" class="form-control" cols="" rows="10" placeholder="8955066732900040174
8955066732900040174
8955066732900040174
8955066732900040174"></textarea>
                                                </div>
                                                <input type="hidden" id="linhas" value="0">
                                                <p>Linhas: <span id="contador"></span></p>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-lg-12" align="right">
                                                    <button type="button" class="btn btn-primary" onclick="verificar_chip();">Verificar</button>
                                                    <a href="javascript::" onclick="carrega_pagina('pedido', 'index.php');" class="btn btn-danger">Voltar</a>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </form>
                                    <hr/>
                                    <div id="chip_localizacao"></div>
                                </div>
                                <div class="tab-pane" id="t_list">
                                    <div class="form-group col-lg-10">
                                        <label>Chip(Linha e ICCID)</label>
                                        <input type="hidden" class="id_chip_insert"/>
                                        <input type="text" class="form-control search_chip_linha_iccid"/>
                                    </div>
                                    <div class="form-group col-lg-2">
                                        <label>.</label><br />
                                        <button type="button" class="btn btn-primary" onclick="insert_chip_pedido();">Inserir</button>
                                    </div>
                                    <div id="load_chip_pedido"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function () {
        load_chip_pedido();
    });
    $(function() {
        $( ".search_chip_linha_iccid" ).autocomplete({
            source: "_controller/_pedido.php?acao=load_chip_insert",
            minLength: 2,
            focus: function(event, ui) {
            	event.preventDefault();
            },
            select: function( event, ui ) {
                $(".id_chip_insert").val(ui.item.value);
                $(".search_chip_linha_iccid").val(ui.item.label);
                event.preventDefault();
            }
        });
    });
    function insert_chip_pedido() {
        var id_chip = $(".id_chip_insert").val();
        var acao = "&acao=create_chip_pedido&id_pedido=<?= $_GET['id']; ?>&id_chip="+id_chip;

        $.ajax({
            type: 'POST',
            url: "_controller/_pedido.php",
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
                    load_chip_pedido();
                }
            }
        });
    }
    function verificar_chip() {
        var dados = $("#verificar_chip").serialize();
        var acao = "&acao=verificar_chip";

        $.ajax({
            type: 'POST',
            url: "_controller/_pedido.php",
            data: dados + acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                $("#chip_localizacao").html(data);
            }
        });
    }
    function load_chip_pedido() {
        var acao = "&acao=load_chip_pedido&id_pedido=<?= $_GET['id']; ?>";

        $.ajax({
            type: 'POST',
            url: "_controller/_pedido.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                $("#load_chip_pedido").html(data);
            }
        });
    }
    function update_chip_pedido(id_chip_pedido, id_chip) {
        var acao = "&acao=update_chip_pedido&id_chip_pedido=" + id_chip_pedido + "&id_chip=" + id_chip;

        $.ajax({
            type: 'POST',
            url: "_controller/_pedido.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                var data_return = jQuery.parseJSON(data);
                if(data_return.type === 'info'){
                    $("#_modal").modal('show');
                    $("#title_modal").html(data_return.title);
                    $("#texto_modal").html(data_return.msg);
                    $("#buttons_modal").html(data_return.buttons);
                }else{
                    update_chip_pedido_ok(data_return.id_pedido, data_return.id_chip);
                }
            }
        });
    }
    function update_chip_pedido_ok(id_chip_pedido, id_chip) {
        var senha_delete_chip = $(".senha_delete_chip").val();
        var data_final        = $(".data_final").val();
        var desconto          = $(".desconto").val();
        var acao = "&acao=update_chip_pedido_ok&id_chip_pedido=" + id_chip_pedido + "&id_chip=" + id_chip+"&senha_chip="+senha_delete_chip+"&data_final="+data_final+"&desconto="+desconto;
        $.ajax({
            type: 'POST',
            url: "_controller/_pedido.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                var data_return = jQuery.parseJSON(data);
                if(data_return.type === 'error'){
                    $("#texto_modal_p").html(data_return.msg);
                }else{
                    $("#_modal").modal('hide');
                    load_chip_pedido();
                }
            }
        });
    }
    function delete_chip_pedido(id_chip_pedido, id_chip) {
        var acao = "&acao=delete_chip_pedido&id_chip_pedido=" + id_chip_pedido + "&id_chip=" + id_chip;

        $.ajax({
            type: 'POST',
            url: "_controller/_pedido.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                var data_return = jQuery.parseJSON(data);
                if(data_return.type === 'info'){
                    $("#_modal").modal('show');
                    $("#title_modal").html(data_return.title);
                    $("#texto_modal").html(data_return.msg);
                    $("#buttons_modal").html(data_return.buttons);
                }else{
                    delete_chip_pedido_ok(data_return.id_pedido, data_return.id_chip);
                }
            }
        });
    }
    function delete_chip_pedido_ok(id_chip_pedido, id_chip) {
        var senha_delete_chip = $(".senha_delete_chip").val();
        var acao = "&acao=delete_chip_pedido_ok&id_chip_pedido=" + id_chip_pedido + "&id_chip=" + id_chip+"&senha_chip="+senha_delete_chip;
        $.ajax({
            type: 'POST',
            url: "_controller/_pedido.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                var data_return = jQuery.parseJSON(data);
                if(data_return.type === 'error'){
                    $("#texto_modal_p").html(data_return.msg);
                }else{
                    $("#_modal").modal('hide');
                    load_chip_pedido();
                }
            }
        });
    }
    function create_chip() {
        var acao = "&acao=create_chip&id_pedido=<?= $_GET['id']; ?>";

        $.ajax({
            type: 'POST',
            url: "_controller/_pedido.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                $("#_modal").modal('show');
                $("#title_modal").html('Retorno');
                $("#texto_modal").html(data);
                $("#buttons_modal").html('<a href="javascript::" onclick="carrega_pagina(\'pedido\', \'index.php\');" data-dismiss="modal" class="btn btn-primary">Sair</a>');
            }
        });
    }
    document.getElementById('arquivo_txt').onkeyup = function () {
        count = this.value.split("\n").length;
        document.getElementById('contador').innerHTML = count;
        document.getElementById('linhas').value = count;
    }
</script>