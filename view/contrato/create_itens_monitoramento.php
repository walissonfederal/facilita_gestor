<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Itens Contrato Monitoramento
                                <a href="javascript::" onclick="carrega_pagina('contrato', 'itens_monitoramento.php?id=<?=$_GET['id'];?>');" class="btn btn-primary">Voltar</a>
                            </h3>
                        </div>
                        <div class="box-content">
                            <form action="" id="create">
                                <fieldset>
                                    <div class="row">
                                        <div class="form-group col-lg-4">
                                            <label>Descrição</label>
                                            <input type="text" class="form-control contrato_monitoramento_itens_descricao" name="contrato_monitoramento_itens_descricao"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Quantidade</label>
                                            <input type="text" onblur="calcular_valor_total();" class="form-control contrato_monitoramento_itens_quantidade" name="contrato_monitoramento_itens_quantidade" id="peso_1"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Data</label>
                                            <input type="date" class="form-control contrato_monitoramento_itens_data" name="contrato_monitoramento_itens_data"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Valor Unitário</label>
                                            <input type="text" onblur="calcular_valor_total();" class="form-control contrato_monitoramento_itens_valor_unitario" name="contrato_monitoramento_itens_valor_unitario" id="valor_1"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Valor Total</label>
                                            <input type="text" readonly="" class="form-control contrato_monitoramento_itens_valor_total" name="contrato_monitoramento_itens_valor_total"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-12" align="right">
                                            <button type="button" class="btn btn-primary" onclick="create();">Gravar</button>
                                            <a href="javascript::" onclick="carrega_pagina('contrato', 'itens_monitoramento.php?id=<?=$_GET['id'];?>');" class="btn btn-danger">Voltar</a>
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
    function create(){
        var dados = $("#create").serialize();
        var acao = "&acao=create_itens&contrato_monitoramento_itens_id_contrato=<?=$_GET['id'];?>";
        
        $.ajax({
            type: 'POST',
            url: "_controller/_contrato_monitoramento.php",
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
    function calcular_valor_total(){
        var quantidade = $(".contrato_monitoramento_itens_quantidade").val();
        var valor_unitario = $(".contrato_monitoramento_itens_valor_unitario").val();
        var valor_total = Number(quantidade) * Number(valor_unitario);
        $(".contrato_monitoramento_itens_valor_total").val(format_moeda(valor_total, ""));
    }
    function format_moeda(n, currency) {
        return currency + "" + n.toFixed(2).replace(/./g, function(c, i, a) {
            return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "" + c : c;
        });
    }
    $("#valor_1").maskMoney({thousands:'', decimal:'.'});
    $('#peso_1').priceFormat({prefix: '', centsSeparator: '.', thousandsSeparator: '',centsLimit: 3});
</script>