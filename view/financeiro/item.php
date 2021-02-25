<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Financeiro - Itens Vendas
                                <a href="javascript::" onclick="carrega_pagina('financeiro', 'index.php?OP=<?php echo $_GET['OP'];?>');" class="btn btn-primary">Voltar</a>
                            </h3>
                        </div>
                        <div class="box-content">
                            <div class="invoice-info">
                                <div class="invoice-from">
                                    <span>Empresa</span>
                                    <strong class="empresa_nome_fantasia">Company Name</strong>
                                    <address>
                                        <em class="empresa_endereco_numero">Street Address</em>
                                        <br><em class="empresa_cidade">City</em>, <em class="empresa_uf">ST</em> <em class="empresa_cep">ZIP Code</em>
                                        <br>
                                        <abbr title="Telefone" class="empresa_telefone">Telefone:(125) 358123-581</abbr>
                                        <br>
                                        <abbr title="Celular" class="empresa_celular">Celular:(125) 251656-222</abbr>
                                    </address>
                                </div>
                                <div class="invoice-to">
                                    <span>Contato</span>
                                    <strong class="contato_nome_fantasia">Max Mustermann</strong>
                                    <address>
                                        <em class="contato_endereco_numero">Street Address</em>
                                        <br><em class="contato_cidade">City</em>, <em class="contato_uf">ST</em> <em class="contato_cep">ZIP Code</em>
                                        <br>
                                        <abbr title="Phone" class="contato_telefone">Phone:(125) 358123-581</abbr>
                                        <br>
                                        <abbr title="Fax" class="contato_celular">Fax:(125) 251656-222</abbr>
                                    </address>
                                </div>
                                <div class="invoice-infos">
                                    <table>
                                        <tr>
                                            <th>Date:</th>
                                            <td class="orcamento_venda_data">Aug 06, 2012</td>
                                        </tr>
                                        <tr>
                                            <th>Código:</th>
                                            <td>#<?php echo $_GET['id'];?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div id="produto_grid"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        var acao = "acao=load_itens_vendas&id=<?=$_GET['id'];?>";
        $.ajax({
            type: 'POST',
            url: "_controller/_financeiro.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                var data_return = jQuery.parseJSON(data);
                $(".empresa_nome_fantasia").html(data_return.empresa.nome_fantasia);
                $(".empresa_endereco_numero").html(data_return.empresa.endereco_numero);
                $(".empresa_uf").html(data_return.empresa.uf);
                $(".empresa_cidade").html(data_return.empresa.cidade);
                $(".empresa_cep").html(data_return.empresa.cep);
                $(".empresa_telefone").html(data_return.empresa.telefone);
                $(".empresa_celular").html(data_return.empresa.celular);
                
                $(".contato_nome_fantasia").html(data_return.contato.nome_fantasia);
                $(".contato_endereco_numero").html(data_return.contato.endereco_numero);
                $(".contato_cidade").html(data_return.contato.cidade);
                $(".contato_uf").html(data_return.contato.uf);
                $(".contato_cep").html(data_return.contato.cep);
                $(".contato_telefone").html(data_return.contato.telefone);
                $(".contato_celular").html(data_return.contato.celular);
                
                $(".orcamento_venda_data").html(data_return.orcamento_venda.data);
                
                load_produtos();
            }
        });
    });
    function load_produtos(){
        var acao = "acao=load_itens_vendas_produtos&id=<?=$_GET['id'];?>";
        $.ajax({
            type: 'POST',
            url: "_controller/_financeiro.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                $("#produto_grid").html(data);
            }
        });
    }
</script>