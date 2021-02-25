<?php require_once '../../_class/Ferramenta.php';?>
<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Financeiro(<?=$_GET['OP'];?>)
                                <a href="javascript::" onclick="carrega_pagina('financeiro', 'index.php?OP=<?=$_GET['OP'];?>')" class="btn btn-primary">Voltar</a>
                            </h3>
                        </div>
                        <div class="box-content">
                            <form action="" id="download">
                                <fieldset>
                                    <div class="row">
                                        <div class="form-group col-lg-2">
                                            <label>Data Vencimento</label>
                                            <input disabled="" type="date" class="form-control financeiro_data_vencimento" />
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Valor</label>
                                            <input disabled="" type="text" class="form-control financeiro_valor" id="valor_1"/>
                                        </div>
                                        <div class="form-group col-lg-4">
                                            <label>Descrição</label>
                                            <input disabled="" type="text" class="form-control financeiro_descricao" />
                                        </div>
                                        <div class="form-group col-lg-4">
                                            <label>Plano de Contas</label>
                                            <select disabled="" class="form-control financeiro_id_plano_conta" ></select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-12">
                                            <label>Obs</label>
                                            <textarea class="form-control financeiro_obs" name="financeiro_obs" cols="" rows=""></textarea>      
                                        </div>
                                    </div>
                                    <hr />
                                    <?php if(GetEmpresa('empresa_download_config') == '0'){?>
                                    <div class="row">
                                        <div class="form-group col-lg-12">
                                            <label>Senha</label>
                                            <input type="password" class="form-control" name="pass"/>
                                        </div>
                                    </div>
                                    <?php }?>
                                    <div class="row">
                                        <div class="form-group col-lg-3">
                                            <label>Acréscimos (R$)</label>
                                            <input type="text" class="form-control financeiro_acrescimo" id="valor_2" onblur="set_valor();"/>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>Descontos (R$)</label>
                                            <input type="text" class="form-control financeiro_desconto" id="valor_3" onblur="set_valor();"/>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>Valor Pagamento</label>
                                            <input type="text" name="financeiro_valor_pagamento" class="form-control financeiro_valor_pagamento" id="valor_4" onblur="set_valor();"/>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>Data Pagamento</label>
                                            <input type="date" name="financeiro_data_pagamento" class="form-control fiannceiro_data_pagamento" value="<?=date('Y-m-d');?>"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-12">
                                            <label>Caixa</label>
                                            <select class="form-control financeiro_caixa" name="financeiro_caixa" ></select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-12" align="right">
                                            <button type="button" class="btn btn-primary" onclick="download();">Baixar</button>
                                            <a href="javascript::" onclick="carrega_pagina('financeiro', 'index.php?OP=<?=$_GET['OP'];?>')" class="btn btn-danger">Cancelar</a>
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
    function format_moeda(n, currency) {
        return currency + "" + n.toFixed(2).replace(/./g, function(c, i, a) {
            return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "" + c : c;
        });
    }
    function set_valor(){
        var financeiro_acrescimo = $(".financeiro_acrescimo").val();
        var financeiro_desconto  = $(".financeiro_desconto").val();
        var financeiro_valor_pagamento = $(".financeiro_valor").val();
        
        if(Number(financeiro_valor_pagamento) >= Number(financeiro_desconto)){
            var total = (Number(financeiro_acrescimo) + Number(financeiro_valor_pagamento) - Number(financeiro_desconto));
            $(".financeiro_valor_pagamento").val(format_moeda(total, ""));
        }
    }
    $(function() {
        $( ".financeiro_nome_contato" ).autocomplete({
            source: "_controller/_contato.php?acao=load_contato",
            minLength: 2,
            focus: function(event, ui) {
            	event.preventDefault();
            },
            select: function( event, ui ) {
                $(".financeiro_id_contato").val(ui.item.value);
                $(".financeiro_nome_contato").val(ui.item.label);
                event.preventDefault();
            }
        });
    });
    $(function(){
        var acao = "acao=load_plano_conta";
        $.ajax({
            type: 'GET',
            url: "_controller/_plano_conta.php",
            data: acao,
            beforeSend: load_in(),
            async: false,
            success: function (data) {
                var data_return = jQuery.parseJSON(data);
                var options = '<option value=""></option>';
                for (var i = 0; i < data_return.data.length; i++) {
                    options += '<option value="' + data_return.data[i].plano_conta_id + '">' + data_return.data[i].plano_conta_classificacao + ' ' + data_return.data[i].plano_conta_descricao + '</option>';
                }
                $('.financeiro_id_plano_conta').html(options).show();
                load_out();
            }
        });
        load_out();
    });
    $(function(){
        var acao = "acao=load_tipo_documento";
        $.ajax({
            type: 'GET',
            url: "_controller/_tipo_documento.php",
            data: acao,
            beforeSend: load_in(),
            async: false,
            success: function (data) {
                var data_return = jQuery.parseJSON(data);
                var options = '<option value=""></option>';
                for (var i = 0; i < data_return.data.length; i++) {
                    options += '<option value="' + data_return.data[i].tipo_documento_id + '">' + data_return.data[i].tipo_documento_descricao + '</option>';
                }
                $('.financeiro_id_tipo_documento').html(options).show();
                load_out();
            }
        });
        load_out();
    });
    $(function(){
        var acao = "acao=load_caixa";
        $.ajax({
            type: 'GET',
            url: "_controller/_caixa.php",
            data: acao,
            beforeSend: load_in(),
            async: false,
            success: function (data) {
                var data_return = jQuery.parseJSON(data);
                var options = '<option value=""></option>';
                for (var i = 0; i < data_return.data.length; i++) {
                    options += '<option value="' + data_return.data[i].caixa_id + '">' + data_return.data[i].caixa_descricao + '</option>';
                }
                $('.financeiro_caixa').html(options).show();
                load_out();
            }
        });
        load_out();
    });
    function buscar_contato(){
        var financeiro_id_contato = $(".financeiro_id_contato").val();
        var acao = "acao=load_contato_id&id="+financeiro_id_contato;
        
        if(financeiro_id_contato){
            $.ajax({
                type: 'POST',
                url: "_controller/_contato.php",
                data: acao,
                beforeSend: load_in(),
                success: function (data) {
                    load_out();
                    var data_return = jQuery.parseJSON(data);
                    if(data_return[0].label !== ''){
                        $(".financeiro_nome_contato").val(data_return[0].label);
                    }else{
                        $("#_modal").modal('show');
                        $("#title_modal").html('Erro');
                        $("#texto_modal").html('Ops, não foi encontrado nenhum registro');
                        $("#buttons_modal").html('');
                        $(".financeiro_id_contato").val('');
                        $(".financeiro_nome_contato").val('');
                    }
                }
            });
        }
    }
    function download(){
        var dados = $("#download").serialize();
        var acao = "&acao=download&OP=<?=$_GET['OP'];?>&id=<?=$_GET['id'];?>";
        
        $.ajax({
            type: 'POST',
            url: "_controller/_financeiro.php",
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
    function limpar_campos(){
        $(".financeiro_id_contato").val('');
        $(".financeiro_nome_contato").val('');
        $(".financeiro_data_vencimento").val('');
        $(".financeiro_valor").val('');
        $(".financeiro_descricao").val('');
        $(".financeiro_id_plano_conta").val('');
        $(".financeiro_id_tipo_documento").val('');
        $(".financeiro_parcela").val('01');
        $(".financeiro_config").val('0');
        $(".financeiro_fixo").val('0');
        $(".financeiro_app_financeira").val('0');
        $(".financeiro_obs").val('');
    }
    $(function(){
        var acao = "acao=load_update&id=<?=$_GET['id'];?>";
        $.ajax({
            type: 'POST',
            url: "_controller/_financeiro.php",
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
                    $(".financeiro_id_contato").val(data_return[0].financeiro_id_contato);
                    $(".financeiro_data_vencimento").val(data_return[0].financeiro_data_vencimento);
                    $(".financeiro_descricao").val(data_return[0].financeiro_descricao);
                    $(".financeiro_valor").val(data_return[0].financeiro_valor);
                    $(".financeiro_id_plano_conta").val(data_return[0].financeiro_id_plano_conta);
                    $(".financeiro_id_tipo_documento").val(data_return[0].financeiro_id_tipo_documento);
                    $(".financeiro_fixo").val(data_return[0].financeiro_fixo);
                    $(".financeiro_app_financeira").val(data_return[0].financeiro_id_contato);
                    $(".financeiro_obs").val(data_return[0].financeiro_obs);
                    $(".financeiro_valor_pagamento").val(data_return[0].financeiro_valor);
                }
            }
        });
    });
    $("#valor_1").maskMoney({thousands:'', decimal:'.'});
    $("#valor_2").maskMoney({thousands:'', decimal:'.'});
    $("#valor_3").maskMoney({thousands:'', decimal:'.'});
    $("#valor_4").maskMoney({thousands:'', decimal:'.'});
</script>