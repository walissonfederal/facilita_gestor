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
                                        <div class="form-group col-lg-12" align="right">
                                            <button type="button" class="btn btn-primary" onclick="update();">Gravar</button>
                                            <button type="button" class="btn btn-success" onclick="baixar_modal();">Baixar</button>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                            <hr />
                            <form action="" id="create">
                                <fieldset>
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
<script>
    $(function(){
        load_produto_grid();
        load_update();
        load_campos();
    });
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
        var acao = "&acao=update&id_pedido=<?=$_GET['id'];?>&pedido_envio="+pedido_envio+"&pedido_codigo_rastreio="+pedido_codigo_rastreio+"&pedido_valor="+pedido_valor_total+"&pedido_valor_chip="+pedido_valor_total_chip;
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
    $("#valor_1").maskMoney({thousands:'', decimal:'.'});
    $("#valor_2").maskMoney({thousands:'', decimal:'.'});
    $("#valor_3").maskMoney({thousands:'', decimal:'.'});
</script>