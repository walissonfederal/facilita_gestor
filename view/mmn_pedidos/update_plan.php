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
                                        <div class="form-group col-lg-3">
                                            <label>Valor Total</label>
                                            <input type="text" class="form-control pedido_valor_total" id="valor_2" />
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>Valor Plano</label>
                                            <input type="text" class="form-control pedido_valor_total_chip" id="valor_3" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-6">
                                            <label>Status Pedido</label>
                                            <select name="pedido_status" class="form-control pedido_status">
                                                <option value="0">Aberto</option>
                                                <option value="1">Pago</option>
                                                <option value="2">Cancelado</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-12" align="right">
                                            <button type="button" class="btn btn-primary" onclick="update();">Gravar</button>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                            <hr />
                            <form action="" id="create">
                                <fieldset>
                                    <div class="form-group col-lg-8">
                                        <label>Produto (Descrição)</label>
                                        <input type="hidden" class="id_produto_insert"/>
                                        <input type="text" class="form-control search_produto_descricao"/>
                                    </div>
                                    <div class="form-group col-lg-2">
                                        <label>Quantidade</label>
                                        <input type="number" class="form-control quantidade_produto_pedidos"/>
                                    </div>
                                    <div class="form-group col-lg-2">
                                        <label>.</label><br />
                                        <button type="button" class="btn btn-primary" onclick="insert_produto_pedido();">Inserir</button>
                                    </div><br />
                                    <div id="load_pedido_grid"></div>
                                </fieldset>
                            </form>
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
        load_produto_grid_del();
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
        $( ".search_produto_descricao" ).autocomplete({
            source: "_controller/_mmn_pedidos.php?acao=load_produto_insert",
            minLength: 2,
            focus: function(event, ui) {
            	event.preventDefault();
            },
            select: function( event, ui ) {
                $(".id_produto_insert").val(ui.item.value);
                $(".search_produto_descricao").val(ui.item.label);
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
    function insert_produto_pedido() {
        var id_produto = $(".id_produto_insert").val();
        var quantidade_produto_pedidos = $(".quantidade_produto_pedidos").val();
        var acao = "&acao=create_produto_pedido&id_pedido=<?= $_GET['id']; ?>&id_produto="+id_produto+"&quantidade="+quantidade_produto_pedidos;

        $.ajax({
            type: 'POST',
            url: "_controller/_mmn_pedidos.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                load_produto_grid_del();
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
    function delete_produto_grid_pedido(id_produto) {
        var acao = "&acao=delete_produto_pedido_ok&id_produto=" + id_produto;
        $.ajax({
            type: 'POST',
            url: "_controller/_mmn_pedidos.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                load_produto_grid_del();
            }
        });
    }
    function load_produto_grid_del(){
        var acao = "&acao=load_pedido_grid_del&id_pedido=<?=$_GET['id'];?>";
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