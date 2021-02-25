<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Faturamento
                                <a href="javascript::" onclick="carrega_pagina('faturamento', 'index.php');" class="btn btn-primary">Voltar</a>
                            </h3>
                        </div>
                        <div class="box-content">
                            <div class="progress">
                                <div class="progress-bar progress-bar-success perc_p" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                                    <div id="perc_progress">0%</div>
                                </div>
                            </div>
                            <form action="" id="create">
                                <fieldset>
                                    <div class="row">
                                        <div class="form-group col-lg-2">
                                            <label>ID Contato</label>
                                            <input type="text" class="form-control search_id_contato" onblur="buscar_contato();"/>
                                        </div>
                                        <div class="form-group col-lg-10">
                                            <label>Contato(Nome Razão, Nome Fantasia, CPF / CNPJ)</label>
                                            <input type="text" class="form-control search_nome_cliente"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-2">
                                            <label>Referência (MM/AAAA)</label>
                                            <input type="text" class="form-control faturamento_referencia referencia_faturamento_mask" name="faturamento_referencia"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Data Encerramento</label>
                                            <input type="date" class="form-control faturamento_data" name="faturamento_data"/>
                                        </div>
                                        <div class="form-group col-lg-4">
                                            <label>Descrição</label>
                                            <input type="text" class="form-control financeiro_descricao" name="financeiro_descricao"/>
                                        </div>
                                        <div class="form-group col-lg-4">
                                            <label>Plano de Contas</label>
                                            <select class="form-control financeiro_id_plano_conta" name="financeiro_id_plano_conta"></select>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Tipo Documento</label>
                                            <select class="form-control financeiro_id_tipo_documento" name="financeiro_id_tipo_documento"></select>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Data Vencimento</label>
                                            <input type="date" class="form-control financeiro_data_vencimento" name="financeiro_data_vencimento"/>
                                        </div>
                                        <input type="hidden" class="inicio" value="0"/>
                                        <input type="hidden" class="final" value="1"/>
                                        <input type="hidden" class="verificacao" value="0"/>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-12" align="right">
                                            <button type="button" class="btn btn-primary" onclick="create_faturamento();">Gravar</button>
                                            <a href="javascript::" onclick="carrega_pagina('faturamento', 'index.php');" class="btn btn-danger">Voltar</a>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                            <div id="resposta_faturamento"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        var acao = "acao=load_plano_conta";
        $.ajax({
            type: 'GET',
            url: "_controller/_plano_conta.php",
            data: acao,
            beforeSend: load_in(),
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
    function mudar_perc(width){
        $(".perc_p").css("width", width+"%");
    }
    function create_faturamento_update(){
        $(".verificacao").val('1');
        create_faturamento();
    }
    function create_faturamento(){
        var faturamento_referencia = $(".faturamento_referencia").val();
        var faturamento_data  = $(".faturamento_data").val();
        var inicio  = $(".inicio").val();
        var fim  = $(".final").val();
        var verificacao  = $(".verificacao").val();
        var financeiro_descricao = $(".financeiro_descricao").val();
        var financeiro_id_plano_conta = $(".financeiro_id_plano_conta").val();
        var financeiro_id_tipo_documento = $(".financeiro_id_tipo_documento").val();
        var financeiro_data_vencimento = $(".financeiro_data_vencimento").val();
        var search_id_contato = $(".search_id_contato").val();

        $.ajax({
            type: 'POST',
            url: "_controller/_faturamento.php",
            data: {
                acao: 'create',
                faturamento_referencia: faturamento_referencia,
                faturamento_data: faturamento_data,
                inicio: inicio,
                fim: fim,
                verificacao: verificacao,
                financeiro_descricao: financeiro_descricao,
                financeiro_id_plano_conta: financeiro_id_plano_conta,
                financeiro_id_tipo_documento: financeiro_id_tipo_documento,
                financeiro_data_vencimento: financeiro_data_vencimento,
                id_contato: search_id_contato
            },
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
                    if(data_return.fim === 'nao'){
                        //muda a barra de progresso
                        mudar_perc(data_return.perc);
                        $("#perc_progress").html(data_return.perc+"%");
                        $(".inicio").val(data_return.inicio);
                        $(".final").val(data_return.final);
                        $(".verificacao").val(data_return.verificacao);
                        $('#resposta_faturamento').html('<div class="alert alert-success"><img src="_img/load.gif" class="load" /> <strong>Aguarde!</strong> Estou gerando o faturamento do <strong>'+data_return.nome_cliente+'</strong></div>');
                        //reinicia o processo após 5 segundos
                        setTimeout(function(){
                            create_faturamento();
                        }, 2000);
                    }else{
                        mudar_perc(data_return.perc);
                        $("#perc_progress").html(data_return.perc+"%");
                        $("#_modal").modal('show');
                        $("#title_modal").html(data_return.title);
                        $("#texto_modal").html(data_return.msg);
                        $("#buttons_modal").html(data_return.buttons);
                    }
                }
            }
        });
    }
    function limpar_campos(){
        $(".caixa_descricao").val('');
        $(".caixa_status").val('0');
    }
    $(function() {
        $( ".search_nome_cliente" ).autocomplete({
            source: "_controller/_contato.php?acao=load_contato",
            minLength: 2,
            focus: function(event, ui) {
            	event.preventDefault();
            },
            select: function( event, ui ) {
                $(".search_id_contato").val(ui.item.value);
                $(".search_nome_cliente").val(ui.item.label);
                event.preventDefault();
            }
        });
    });
    function buscar_contato(){
        var id_cliente = $(".search_id_contato").val();
        var acao = "acao=load_contato_id&id="+id_cliente;
        
        if(id_cliente){
            $.ajax({
                type: 'POST',
                url: "_controller/_contato.php",
                data: acao,
                beforeSend: load_in(),
                success: function (data) {
                    load_out();
                    var data_return = jQuery.parseJSON(data);
                    if(data_return[0].label !== ''){
                        $(".search_nome_cliente").val(data_return[0].label);
                    }else{
                        $("#_modal").modal('show');
                        $("#title_modal").html('Erro');
                        $("#texto_modal").html('Ops, não foi encontrado nenhum registro');
                        $("#buttons_modal").html('');
                        $(".search_id_contato").val('');
                        $(".search_nome_cliente").val('');
                    }
                }
            });
        }
    }
</script>