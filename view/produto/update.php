<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Produto
                                <a href="javascript::" onclick="carrega_pagina('produto', 'index.php');" class="btn btn-primary">Voltar</a>
                            </h3>
                        </div>
                        <div class="box-content">
                            <ul class="tabs tabs-inline tabs-top">
                                <li class="active">
                                    <a href="#t_principal" data-toggle="tab">PRINCIPAL</a>
                                </li>
                                <li>
                                    <a href="#t_valor" data-toggle="tab">VALORES</a>
                                </li>
                                <li>
                                    <a href="#t_estoque" data-toggle="tab">ESTOQUE</a>
                                </li>
                                <li>
                                    <a href="#t_tributacao" data-toggle="tab">TRIBUTAÇÃO</a>
                                </li>
                            </ul>
                            <form action="" id="update">
                                <div class="tab-content padding tab-content-inline tab-content-bottom">
                                    <div class="tab-pane active" id="t_principal">
                                        <div class="row">
                                            <div class="form-group col-lg-6">
                                                <label>Descrição</label>
                                                <input type="text" class="form-control produto_descricao" name="produto_descricao"/>
                                            </div>
                                            <div class="form-group col-lg-3">
                                                <label>Produto / Serviço</label>
                                                <select class="form-control produto_tipo" name="produto_tipo">
                                                    <option value="0">Produto</option>
                                                    <option value="1">Serviço</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-lg-3">
                                                <label>Código</label>
                                                <input type="text" class="form-control produto_codigo" name="produto_codigo"/>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-lg-3">
                                                <label>Categoria</label>
                                                <select name="produto_id_categoria" class="form-control produto_id_categoria" onchange="load_subcategoria();"></select>
                                            </div>
                                            <div class="form-group col-lg-3">
                                                <label>Sub categoria</label>
                                                <select name="produto_id_sub_categoria" class="form-control produto_id_sub_categoria"></select>
                                            </div>
                                            <div class="form-group col-lg-6">
                                                <label>Palavras Chaves</label>
                                                <input type="text" class="form-control produto_palavras_chave" name="produto_palavras_chave"/>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-lg-12">
                                                <label>Obs</label>
                                                <textarea name="produto_obs" class="form-control produto_obs" cols="" rows=""></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="t_valor">
                                        <div class="row">
                                            <div class="form-group col-lg-4">
                                                <label>Preço Custo (R$)</label>
                                                <input type="text" class="form-control produto_preco_custo" name="produto_preco_custo" id="valor_1"/>
                                            </div>
                                            <div class="form-group col-lg-4">
                                                <label>Margem de Lucro (%)</label>
                                                <input type="text" class="form-control produto_margem_lucro" name="produto_margem_lucro" id="valor_2" onblur="margem_lucro();"/>
                                            </div>
                                            <div class="form-group col-lg-4">
                                                <label>Preço Venda (R$)</label>
                                                <input type="text" class="form-control produto_preco_venda" name="produto_preco_venda" id="valor_3"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="t_estoque">
                                        <div class="row">
                                            <div class="form-group col-lg-4">
                                                <label>Controlar Estoque</label>
                                                <select class="form-control produto_controlar_estoque" name="produto_controlar_estoque">
                                                    <option value="0">SIM</option>
                                                    <option value="1">NÃO</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-lg-4">
                                                <label>Estoque Atual</label>
                                                <input type="number" class="form-control produto_estoque_atual" name="produto_estoque_atual"/>
                                            </div>
                                            <div class="form-group col-lg-4">
                                                <label>Estoque Mínimo</label>
                                                <input type="number" class="form-control produto_estoque_minimo" name="produto_estoque_minimo"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="t_tributacao">
                                        <div class="row">
                                            <div class="form-group col-lg-12">
                                                <label>Origem</label>
                                                <select name="produto_origem" class="form-control produto_origem"></select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-lg-3">
                                                <label>Unidade</label>
                                                <input type="text" class="form-control produto_unidade_tributaria" name="produto_unidade_tributaria"/>
                                            </div>
                                            <div class="form-group col-lg-3">
                                                <label>EAN</label>
                                                <input type="text" class="form-control produto_ean" name="produto_ean"/>
                                            </div>
                                            <div class="form-group col-lg-3">
                                                <label>Peso Líquido (KG)</label>
                                                <input type="text" class="form-control produto_peso_liquido" name="produto_peso_liquido" id="peso_1"/>
                                            </div>
                                            <div class="form-group col-lg-3">
                                                <label>Peso Bruto (KG)</label>
                                                <input type="text" class="form-control produto_peso_bruto" name="produto_peso_bruto" id="peso_2"/>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-lg-3">
                                                <label>NCM</label>
                                                <input type="text" class="form-control produto_ncm" name="produto_ncm"/>
                                            </div>
                                            <div class="form-group col-lg-3">
                                                <label>Exceção tabela IPI</label>
                                                <select class="form-control produto_excecao_ipi" name="produto_excecao_ipi">
                                                    <option value="0"></option>
                                                    <option value="1">01</option>
                                                    <option value="2">02</option>
                                                    <option value="3">03</option>
                                                    <option value="4">04</option>
                                                    <option value="5">05</option>
                                                    <option value="6">06</option>
                                                    <option value="7">07</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-lg-6">
                                                <label>CEST</label>
                                                <input type="text" class="form-control produto_codigo_cest" name="produto_codigo_cest"/>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-lg-12">
                                                <label>Anotações para Nota Fiscal eletrônica</label>
                                                <input type="text" class="form-control produto_obs_nf" name="produto_obs_nf"/>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-lg-12">
                                                <label>Grupos Tributários</label>
                                                <select name="produto_id_tributacao" class="form-control produto_id_tributacao"></select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-12" align="right">
                                        <button type="button" class="btn btn-primary" onclick="update();">Gravar</button>
                                        <a href="javascript::" onclick="carrega_pagina('produto', 'index.php');" class="btn btn-danger">Cancelar</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function update(){
        var dados = $("#update").serialize();
        var acao = "&acao=update&id=<?=$_GET['id'];?>";
        
        $.ajax({
            type: 'POST',
            url: "_controller/_produto.php",
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
    function format_moeda(n, currency) {
        return currency + "" + n.toFixed(2).replace(/./g, function(c, i, a) {
            return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "" + c : c;
        });
    }
    function margem_lucro(){
        var produto_preco_custo = $(".produto_preco_custo").val();
        var produto_margem_lucro = $(".produto_margem_lucro").val();
        
        if(produto_preco_custo !== '' && produto_margem_lucro !== ''){
            var perc_preco_venda = (produto_margem_lucro / 100) * produto_preco_custo;
            var preco_venda = (Number(perc_preco_venda) + Number(produto_preco_custo));
            $(".produto_preco_venda").val(format_moeda(preco_venda, ""));
        }
    }
    function limpar_campos(){
        $(".produto_descricao").val('');
        $(".produto_codigo").val('');
        $(".produto_id_categoria").val('');
        $(".produto_id_sub_categoria").val('');
        $(".produto_palavras_chave").val('');
        $(".produto_obs").val('');
        $(".produto_preco_custo").val('');
        $(".produto_margem_lucro").val('');
        $(".produto_preco_venda").val('');
        $(".produto_estoque_atual").val('');
        $(".produto_estoque_minimo").val('');
        $(".produto_origem").val('');
        $(".produto_unidade_tributaria").val('');
        $(".produto_ean").val('');
        $(".produto_peso_bruto").val('');
        $(".produto_peso_liquido").val('');
        $(".produto_ncm").val('');
        $(".produto_excecao_ipi").val('0');
        $(".produto_codigo_cest").val('');
        $(".produto_obs_nf").val('');
        $(".produto_id_tributacao").val('');
    }
    $(function(){
        var acao = "acao=load_categoria";
        $.ajax({
            type: 'GET',
            url: "_controller/_sub_categoria.php",
            data: acao,
            beforeSend: load_in(),
            async: false,
            success: function (data) {
                var data_return = jQuery.parseJSON(data);
                var options = '<option value=""></option>';
                for (var i = 0; i < data_return.data.length; i++) {
                    options += '<option value="' + data_return.data[i].categoria_id + '">' + data_return.data[i].categoria_descricao + '</option>';
                }
                $('.produto_id_categoria').html(options).show();
                load_out();
            }
        });
        load_out();
    });
    $(function(){
        var acao = "acao=load_origem";
        $.ajax({
            type: 'GET',
            url: "_controller/_produto.php",
            data: acao,
            beforeSend: load_in(),
            async: false,
            success: function (data) {
                var data_return = jQuery.parseJSON(data);
                var options = '<option value=""></option>';
                for (var i = 0; i < data_return.data.length; i++) {
                    options += '<option value="' + data_return.data[i].codigo_icms_origem + '">' + data_return.data[i].codigo_icms_origem + ' ' + data_return.data[i].desc_icms_origem + '</option>';
                }
                $('.produto_origem').html(options).show();
                load_out();
            }
        });
        load_out();
    });
    $(function(){
        var acao = "acao=load_grupo_tributarios";
        $.ajax({
            type: 'GET',
            url: "_controller/_produto.php",
            data: acao,
            beforeSend: load_in(),
            async: false,
            success: function (data) {
                var data_return = jQuery.parseJSON(data);
                var options = '<option value=""></option>';
                for (var i = 0; i < data_return.data.length; i++) {
                    options += '<option value="' + data_return.data[i].tributacao_id + '">' + data_return.data[i].tributacao_descricao + ' - ' + data_return.data[i].tributacao_cfop + '</option>';
                }
                $('.produto_id_tributacao').html(options).show();
                load_out();
            }
        });
        load_out();
    });
    function load_subcategoria(){
        var produto_id_categoria = $(".produto_id_categoria").val();
        var acao = "acao=load_sub_categoria&id_categoria="+produto_id_categoria;
        $.ajax({
            type: 'POST',
            url: "_controller/_produto.php",
            data: acao,
            beforeSend: load_in(),
            async: false,
            success: function (data) {
                var data_return = jQuery.parseJSON(data);
                if(data_return.type === 'error'){
                    var options = '<option value=""></option>';
                    $('.produto_id_sub_categoria').html(options).show();
                    load_out();
                }else{
                    var options = '<option value=""></option>';
                    for (var i = 0; i < data_return.data.length; i++) {
                        options += '<option value="' + data_return.data[i].sub_categoria_id + '">' + data_return.data[i].sub_categoria_descricao + '</option>';
                    }
                    $('.produto_id_sub_categoria').html(options).show();
                    load_out();
                }
            }
        });
        load_out();
    }
    $(function(){
        var acao = "acao=load_update&id=<?=$_GET['id'];?>";
        $.ajax({
            type: 'POST',
            url: "_controller/_produto.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                var data_return = jQuery.parseJSON(data);
                $(".produto_descricao").val(data_return[0].produto_descricao);
                $(".produto_codigo").val(data_return[0].produto_codigo);
                $(".produto_id_categoria").val(data_return[0].produto_id_categoria);
                load_subcategoria(data_return[0].produto_id_categoria);
                $(".produto_id_sub_categoria").val(data_return[0].produto_id_sub_categoria);
                $(".produto_palavras_chave").val(data_return[0].produto_palavras_chave);
                $(".produto_preco_custo").val(data_return[0].produto_preco_custo);
                $(".produto_preco_venda").val(data_return[0].produto_preco_venda);
                $(".produto_margem_lucro").val(data_return[0].produto_margem_lucro);
                $(".produto_controlar_estoque").val(data_return[0].produto_controlar_estoque);
                $(".produto_estoque_atual").val(data_return[0].produto_estoque_atual);
                $(".produto_estoque_minimo").val(data_return[0].produto_estoque_minimo);
                $(".produto_obs").val(data_return[0].produto_obs);
                $(".produto_origem").val(data_return[0].produto_origem);
                $(".produto_unidade_tributaria").val(data_return[0].produto_unidade_tributaria);
                $(".produto_ean").val(data_return[0].produto_ean);
                $(".produto_peso_liquido").val(data_return[0].produto_peso_liquido);
                $(".produto_peso_bruto").val(data_return[0].produto_peso_bruto);
                $(".produto_ncm").val(data_return[0].produto_ncm);
                $(".produto_excecao_ipi").val(data_return[0].produto_excecao_ipi);
                $(".produto_codigo_cest").val(data_return[0].produto_codigo_cest);
                $(".produto_obs_nf").val(data_return[0].produto_obs_nf);
                $(".produto_id_tributacao").val(data_return[0].produto_id_tributacao);
                $(".produto_tipo").val(data_return[0].produto_tipo);
            }
        });
    });
    $("#valor_1").maskMoney({thousands:'', decimal:'.'});
    $("#valor_2").maskMoney({thousands:'', decimal:'.'});
    $("#valor_3").maskMoney({thousands:'', decimal:'.'});
    $('#peso_1').priceFormat({prefix: '', centsSeparator: '.', thousandsSeparator: '',centsLimit: 3});
    $('#peso_2').priceFormat({prefix: '', centsSeparator: '.', thousandsSeparator: '',centsLimit: 3});
</script>