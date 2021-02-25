<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Faturas
                                <a href="javascript::" onclick="carrega_pagina('mmn_fatura', 'index.php');" class="btn btn-primary">Voltar</a>
                            </h3>
                        </div>
                        <div class="box-content">
                            <form action="" id="baixar">
                                <fieldset>
                                    <div class="row">
                                        <div class="form-group col-lg-2">
                                            <label>Data Vencimento</label>
                                            <input type="date" class="form-control pedido_data_vencimento" name="" readonly="" />
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Valor Total</label>
                                            <input type="text" class="form-control pedido_valor" name="" id="" readonly=""/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Valor Plano</label>
                                            <input type="text" class="form-control pedido_valor_chip" name="" id="" readonly=""/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Data Pagamento</label>
                                            <input type="date" class="form-control pedido_data_pagamento" name="pedido_data_pagamento" />
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Valor Pagamento</label>
                                            <input type="text" class="form-control pedido_valor_pagamento" name="pedido_valor_pagamento" id="valor_1" />
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Gerar Comissão</label>
                                            <select name="gerar_comissao" class="form-control">
                                                <option value="0">Sim</option>
                                                <option value="1">Não</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-12" align="right">
                                            <button type="button" class="btn btn-primary" onclick="baixar();">Gravar</button>
                                            <a href="javascript::" onclick="carrega_pagina('mmn_fatura', 'index.php');" class="btn btn-danger">Voltar</a>
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
    function baixar(){
        var dados = $("#baixar").serialize();
        var acao = "&acao=baixar&id=<?=$_GET['id'];?>";
        
        $.ajax({
            type: 'POST',
            url: "_controller/_mmn_fatura.php",
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
    $(function(){
        var acao = "acao=load_update_baixar&id=<?=$_GET['id'];?>";
        $.ajax({
            type: 'POST',
            url: "_controller/_mmn_fatura.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                var data_return = jQuery.parseJSON(data);
                if(data_return.type === 'error'){
                    $("#_modal").modal('show');
                    $("#title_modal").html(data_return.title);
                    $("#texto_modal").html(data_return.msg);
                    $("#buttons_modal").html(data_return.buttons);
                }else{
                    $(".pedido_data_vencimento").val(data_return[0].pedido_data_vencimento);
                    $(".pedido_valor").val(data_return[0].pedido_valor);
                    $(".pedido_valor_chip").val(data_return[0].pedido_valor_chip);
                }
                load_out();
            }
        });
    });
    $("#valor_1").maskMoney({thousands:'', decimal:'.'});
    $("#valor_2").maskMoney({thousands:'', decimal:'.'});
</script>