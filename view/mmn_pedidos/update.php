<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Pedidos
                                <a href="javascript::" onclick="carrega_pagina('mmn_pedidos', 'index.php');" class="btn btn-primary">Voltar</a>
                            </h3>
                        </div>
                        <div class="box-content">
                            <form action="" id="update">
                                <fieldset>
                                    <div id="dados_pedido"></div>
                                    <div class="row">
                                        <div class="form-group col-lg-3">
                                            <label>Enviado?</label>
                                            <select name="pedido_envio" class="form-control pedido_envio">
                                                <option value="0">Não</option>
                                                <option value="1">Sim</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>Código Rastreio</label>
                                            <input type="text" class="form-control pedido_codigo_rastreio" name="pedido_codigo_rastreio" />
                                        </div>
                                        <div class="form-group col-lg-3" style="display: none;">
                                            <label>Valor Total</label>
                                            <input type="text" class="form-control pedido_valor_total" id="valor_2" />
                                        </div>
                                        <div class="form-group col-lg-3" style="display: none;">
                                            <label>Valor Plano</label>
                                            <input type="text" class="form-control pedido_valor_total_chip" id="valor_3" />
                                        </div>
										<div class="form-group col-lg-6">
                                            <label>Status Pedido</label>
                                            <select name="pedido_status" class="form-control pedido_status">
                                                <option value="1">Pago</option>
                                                <option value="2">Cancelado</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-12" align="right">
                                            <button type="button" class="btn btn-primary" onclick="update();">Gravar</button>
                                            <button type="button" class="btn btn-success" onclick="baixar_modal();">Baixar</button>
                                            <a href="view/mmn_pedidos/envelope.php?id_pedido=<?php echo $_GET['id'];?>" class="btn btn-blue" target="_blank">Envelope Correios</a>
											<a href="view/mmn_pedidos/envelope_contrato.php?id_pedido=<?php echo $_GET['id'];?>" class="btn btn-gray" target="_blank">Contrato</a>
                                            <button type="button" class="btn btn-grey-4" onclick="open_notificar_cliente();">Notificar Cliente Envio</button>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                            <hr />
                            <ul class="tabs tabs-inline tabs-top">
                                <li>
                                    <a href="#t_principal" data-toggle="tab">PEDIDO</a>
                                </li>
                                <li class="active">
                                    <a href="#t_list" data-toggle="tab">CHIP</a>
                                </li>
                            </ul>
                            <div class="tab-content padding tab-content-inline tab-content-bottom">
                                <div class="tab-pane" id="t_principal">
                                    <form action="" id="create">
                                        <fieldset>
                                            <div id="load_pedido_grid"></div>
                                        </fieldset>
                                    </form>
                                </div>
                                <div class="tab-pane active" id="t_list">
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
<div id="_modal_baixar_mmn" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Baixar Pedido</h4>
            </div>
            <!-- /.modal-header -->
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-lg-6">
                        <label>Data Pagamento</label>
                        <input type="date" class="form-control pedido_data_pagamento" />
                    </div>
                    <div class="form-group col-lg-6">
                        <label>Valor Pagamento</label>
                        <input type="text" class="form-control pedido_valor_pagamento" id="valor_1"/>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-lg-12">
                        <label>Gerar Comissão</label>
                        <select class="form-control pedido_gerar_comissao">
                            <option value="0">Sim</option>
                            <option value="1">Não</option>
                        </select>
                    </div>
                </div>
                <p>
                <div id="texto_modal_mmn_p"></div>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="baixar();">Realizar Operação</button>
            </div>
        </div>
    </div>
</div>
<div id="_modal_notificar_cliente" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Notificar Cliente Envio Chip</h4>
            </div>
            <!-- /.modal-header -->
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-lg-12">
                        <label>Email</label>
                        <input type="text" class="form-control notificar_email" />
                    </div>
                </div>
                <p>
                <div id="texto_modal_mmn_notificar_p"></div>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="notificar();">Notificar Usuário</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        load_produto_grid();
        load_update();
        load_campos();
        load_chip_pedido();
    });
    $(function() {
        $( ".search_chip_linha_iccid" ).autocomplete({
            source: "_controller/_mmn_pedidos.php?acao=load_chip_insert",
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
            url: "_controller/_mmn_pedidos.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                load_chip_pedido();
            }
        });
    }
    function load_chip_pedido() {
        var acao = "&acao=load_chip_pedido&id_pedido=<?= $_GET['id']; ?>";

        $.ajax({
            type: 'POST',
            url: "_controller/_mmn_pedidos.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                $("#load_chip_pedido").html(data);
            }
        });
    }
    function delete_chip_pedido_ok(id_chip_pedido, id_chip) {
        var acao = "&acao=delete_chip_pedido_ok&id_chip_pedido=" + id_chip_pedido + "&id_chip=" + id_chip;
        $.ajax({
            type: 'POST',
            url: "_controller/_mmn_pedidos.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                load_chip_pedido();
            }
        });
    }
    function load_produto_grid(){
        var acao = "&acao=load_pedido_grid&id_pedido=<?=$_GET['id'];?>";
        $.ajax({
            type: 'POST',
            url: "_controller/_mmn_pedidos.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                $("#load_pedido_grid").html(data);
            }
        });
    }
    function update(){
        var pedido_envio = $(".pedido_envio").val();
        var pedido_codigo_rastreio = $(".pedido_codigo_rastreio").val();
        var pedido_valor_total = $(".pedido_valor_total").val();
        var pedido_valor_total_chip = $(".pedido_valor_total_chip").val();
        var pedido_status = $(".pedido_status").val();
        var acao = "&acao=update&id_pedido=<?=$_GET['id'];?>&pedido_envio="+pedido_envio+"&pedido_codigo_rastreio="+pedido_codigo_rastreio+"&pedido_valor="+pedido_valor_total+"&pedido_valor_chip="+pedido_valor_total_chip+"&pedido_status="+pedido_status;
        $.ajax({
            type: 'POST',
            url: "_controller/_mmn_pedidos.php",
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
    function baixar(){
        var data_pagamento = $(".pedido_data_pagamento").val();
        var valor_pagamento = $(".pedido_valor_pagamento").val();
        var gerar_comissao = $(".pedido_gerar_comissao").val();
        var acao = "&acao=baixar&id_pedido=<?=$_GET['id'];?>&data_pagamento="+data_pagamento+"&valor_pagamento="+valor_pagamento+"&gerar_comissao="+gerar_comissao;
        $.ajax({
            type: 'POST',
            url: "_controller/_mmn_pedidos.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                var data_return = jQuery.parseJSON(data);
                $("#texto_modal_mmn_p").html(data_return.msg);
            }
        });
    }
    function load_campos(){
        var acao = "&acao=load_update_campos&id_pedido=<?=$_GET['id'];?>";
        $.ajax({
            type: 'POST',
            url: "_controller/_mmn_pedidos.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                var data_return = jQuery.parseJSON(data);
                $(".pedido_envio").val(data_return[0].pedido_envio);
                $(".pedido_codigo_rastreio").val(data_return[0].pedido_codigo_rastreio);
                $(".pedido_valor_total").val(data_return[0].pedido_valor);
                $(".pedido_valor_total_chip").val(data_return[0].pedido_valor_chip);
                if(data_return[0].pedido_status === 'Aberto'){
                    $(".pedido_status").val('0');
                }else if(data_return[0].pedido_status === 'Pago'){
                    $(".pedido_status").val('1');
                }else if(data_return[0].pedido_status === 'Cancelado'){
                    $(".pedido_status").val('2');
                }
            }
        });
    }
    function load_update(){
        var acao = "&acao=load_update&id_pedido=<?=$_GET['id'];?>";
        $.ajax({
            type: 'POST',
            url: "_controller/_mmn_pedidos.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                                
                $("#dados_pedido").html(data);
            }
        });
    }
    function baixar_modal(){
        $("#_modal_baixar_mmn").modal('show');
    }
    function open_notificar_cliente(){
        $(".notificar_email").val('');
        $("#_modal_notificar_cliente").modal('show');
        var acao = "&acao=load_email_cliente&id_pedido=<?=$_GET['id'];?>";
        $.ajax({
            type: 'POST',
            url: "_controller/_mmn_pedidos.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                $(".notificar_email").val(data);
            }
        });
    }
    function notificar(){
        var email_notificar = $(".notificar_email").val();
        var codigo_rastreio = $(".pedido_codigo_rastreio").val();
        var acao = "&acao=notificar_cliente&email="+email_notificar+"&codigo_rastreio="+codigo_rastreio;
        $.ajax({
            type: 'POST',
            url: "_controller/_mmn_pedidos.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                $("#texto_modal_mmn_notificar_p").html(data);
                setTimeout(function(){ $("#_modal_notificar_cliente").modal('hide'); }, 5000);
            }
        });
    }
    $("#valor_1").maskMoney({thousands:'', decimal:'.'});
    $("#valor_2").maskMoney({thousands:'', decimal:'.'});
    $("#valor_3").maskMoney({thousands:'', decimal:'.'});
</script>