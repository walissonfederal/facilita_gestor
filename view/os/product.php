<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Ordem de Serviço
                                <a href="javascript::" onclick="carrega_pagina('os', 'index.php');" class="btn btn-primary">Voltar</a>
                            </h3>
                        </div>
                        <div class="box-content">
                            <form action="" id="create">
                                <fieldset>
                                    <div class="row">
                                        <div class="form-group col-lg-2">
                                            <label>ID Produto</label>
                                            <input type="text" class="form-control os_id_produto" onblur="buscar_produto();"/>
                                        </div>
                                        <div class="form-group col-lg-6">
                                            <label>Produto(Descrição, Código Personalizado)</label>
                                            <input type="text" class="form-control os_descricao_produto"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Quantidade</label>
                                            <input type="text" class="form-control os_qtd" id="peso_1" value="1.000"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Valor Produto</label>
                                            <input type="text" class="form-control os_valor_unitario" id="valor_1"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-12" align="right">
                                            <button type="button" class="btn btn-primary" onclick="insert_produto();">Inserir</button>
                                            <a href="javascript::" onclick="carrega_pagina('os', 'index.php');" class="btn btn-danger">Cancelar</a>
                                        </div>
                                    </div>
                                    <hr />
                                    <div id="load_produto_grid"></div>
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
    $(function() {
        $( ".os_descricao_produto" ).autocomplete({
            source: "_controller/_produto.php?acao=load_produto",
            minLength: 2,
            focus: function(event, ui) {
            	event.preventDefault();
            },
            select: function( event, ui ) {
                $(".os_id_produto").val(ui.item.value);
                $(".os_descricao_produto").val(ui.item.label);
                $(".os_valor_unitario").val(ui.item.valor_produto);
                event.preventDefault();
            }
        });
        load_produto_grid();
    });
    function buscar_produto(){
        var os_id_produto = $(".os_id_produto").val();
        var acao = "acao=load_produto_id&id="+os_id_produto;
        
        if(os_id_produto){
            $.ajax({
                type: 'POST',
                url: "_controller/_produto.php",
                data: acao,
                beforeSend: load_in(),
                success: function (data) {
                    load_out();
                    var data_return = jQuery.parseJSON(data);
                    if(data_return[0].label !== ''){
                        $(".os_descricao_produto").val(data_return[0].label);
                        $(".os_valor_unitario").val(data_return[0].valor_produto);
                    }else{
                        $("#_modal").modal('show');
                        $("#title_modal").html('Erro');
                        $("#texto_modal").html('Ops, não foi encontrado nenhum registro');
                        $("#buttons_modal").html('');
                        $(".os_id_produto").val('');
                        $(".os_descricao_produto").val('');
                        $(".os_valor_unitario").val('');
                        $(".os_qtd").val('1.000');
                    }
                }
            });
        }
    }
    function insert_produto(){
        var os_id_produto = $(".os_id_produto").val();
        var os_qtd = $(".os_qtd").val();
        var os_valor_unitario = $(".os_valor_unitario").val();
        
        if(os_id_produto !== '' || os_qtd !== '' || os_valor_unitario !== ''){
            var acao = "&acao=insert_produto&id_produto="+os_id_produto+"&qtd="+os_qtd+"&valor_unitario="+os_valor_unitario+"&id_os=<?=$_GET['id'];?>";
            $.ajax({
                type: 'POST',
                url: "_controller/_os.php",
                data: acao,
                beforeSend: load_in(),
                async: false,
                success: function (data) {
                    load_out();
                    var data_return = jQuery.parseJSON(data);
                    if(data_return.type === 'error'){
                        $("#_modal").modal('show');
                        $("#title_modal").html(data_return.title);
                        $("#texto_modal").html(data_return.msg);
                        $("#buttons_modal").html(data_return.buttons);
                    }else{
                        $(".os_id_produto").val('');
                        $(".os_descricao_produto").val('');
                        $(".os_valor_unitario").val('');
                        $(".os_descricao_produto").focus();
                        $(".os_qtd").val('1.000');
                        load_produto_grid();
                    }
                }
            });
        }
    }
    function load_produto_grid(){
        var acao = "&acao=load_produto_grid&id_os=<?=$_GET['id'];?>";
        $.ajax({
            type: 'POST',
            url: "_controller/_os.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                $("#load_produto_grid").html(data);
            }
        });
    }
    function delete_prod_grid(id_produto){
        var acao = "&acao=delete_produto_grid&id_produto="+id_produto+"&id_os=<?=$_GET['id'];?>";
        $.ajax({
            type: 'POST',
            url: "_controller/_os.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                load_produto_grid();
            }
        });
    }
    $("#valor_1").maskMoney({thousands:'', decimal:'.'});
    $("#valor_2").maskMoney({thousands:'', decimal:'.'});
    $("#valor_3").maskMoney({thousands:'', decimal:'.'});
    $("#valor_4").maskMoney({thousands:'', decimal:'.'});
    $("#valor_5").maskMoney({thousands:'', decimal:'.'});
    $("#valor_6").maskMoney({thousands:'', decimal:'.'});
    $("#valor_7").maskMoney({thousands:'', decimal:'.'});
    $("#valor_8").maskMoney({thousands:'', decimal:'.'});
    $('#peso_1').priceFormat({prefix: '', centsSeparator: '.', thousandsSeparator: '',centsLimit: 3});
    $('#peso_2').priceFormat({prefix: '', centsSeparator: '.', thousandsSeparator: '',centsLimit: 3});
</script>