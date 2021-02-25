<?php require_once '../../_class/Ferramenta.php';?>
<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Caixa / Conta
                                <a href="javascript::" onclick="carrega_pagina('caixa-conta', 'index.php');" class="btn btn-primary">Voltar</a>
                            </h3>
                        </div>
                        <div class="box-content">
                            <div class="alert alert-success"><strong>Opa, preciso te informar uma coisa!</strong> Essa ferramenta interfere diretamente no financeiro, ou seja as mudanças aqui serão também impactadas lá. Favor ter certeza da operação :)</div>
                            <form action="" id="update">
                                <fieldset>
                                    <?php if(GetEmpresa('empresa_caixa_conta_config') == '0'){?>
                                    <div class="row">
                                        <div class="form-group col-lg-12">
                                            <label>Senha</label>
                                            <input type="password" class="form-control" name="pass"/>
                                        </div>
                                    </div>
                                    <?php }?>
                                    <div class="row">
                                        <div class="form-group col-lg-4">
                                            <label>Data Lançamento</label>
                                            <input type="date" class="form-control caixa_conta_data_lancamento" name="caixa_conta_data_lancamento"/>
                                        </div>
                                        <div class="form-group col-lg-4">
                                            <label>Valor Lançamento</label>
                                            <input type="text" class="form-control caixa_conta_valor_lancamento" name="caixa_conta_valor_lancamento" id="valor_1"/>
                                        </div>
                                        <div class="form-group col-lg-4">
                                            <label>Descrição</label>
                                            <input type="text" class="form-control caixa_conta_descricao" name="caixa_conta_descricao"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-12" align="right">
                                            <button type="button" class="btn btn-primary" onclick="update();">Gravar</button>
                                            <a href="javascript::" onclick="carrega_pagina('caixa-conta', 'index.php');" class="btn btn-danger">Cancelar</a>
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
    function update(){
        var dados = $("#update").serialize();
        var acao = "&acao=update&id=<?=$_GET['id'];?>";
        
        $.ajax({
            type: 'POST',
            url: "_controller/_caixa_conta.php",
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
        var acao = "acao=load_update&id=<?=$_GET['id'];?>";
        $.ajax({
            type: 'POST',
            url: "_controller/_caixa_conta.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                var data_return = jQuery.parseJSON(data);
                $(".caixa_conta_data_lancamento").val(data_return[0].caixa_conta_data_lancamento);
                $(".caixa_conta_descricao").val(data_return[0].caixa_conta_descricao);
                $(".caixa_conta_valor_lancamento").val(data_return[0].caixa_conta_valor_lancamento);
            }
        });
    });
    $("#valor_1").maskMoney({thousands:'', decimal:'.'});
</script>