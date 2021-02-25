<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Assinaturas
                                <a href="javascript::" onclick="carrega_pagina('assinatura', 'index.php');" class="btn btn-primary">Voltar</a>
                            </h3>
                        </div>
                        <div class="box-content">
                            <form action="" id="create">
                                <fieldset>
                                    <div class="row">
                                        <div class="form-group col-lg-2">
                                            <label>ID Contato</label>
                                            <input type="text" class="form-control assinatura_id_contato" onblur="buscar_contato();" name="assinatura_id_contato"/>
                                        </div>
                                        <div class="form-group col-lg-10">
                                            <label>Contato(Nome Razão, Nome Fantasia, CPF / CNPJ)</label>
                                            <input type="text" class="form-control nome_contato"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-2">
                                            <label>Data Inicial</label>
                                            <input type="date" class="form-control assinatura_data_criacao" name="assinatura_data_criacao"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Data Final</label>
                                            <input type="date" class="form-control assinatura_data_final" name="assinatura_data_final"/>
                                        </div>
                                        <div class="form-group col-lg-4">
                                            <label>Deseja lançar as mensalidades automaticamente?</label>
                                            <select class="form-control assinatura_lancamento_auto" name="assinatura_lancamento_auto">
                                                <option value="0">Não</option>
                                                <option value="1">Sim</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Dia para débito</label>
                                            <select class="form-control assinatura_dia" name="assinatura_dia">
                                                <option value="1">01</option>
                                                <option value="5">05</option>
                                                <option value="10">10</option>
                                                <option value="15">15</option>
                                                <option value="20">20</option>
                                                <option value="25">25</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Plano</label>
                                            <select class="form-control assinatura_id_plano" name="assinatura_id_plano"></select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-12" align="right">
                                            <button type="button" class="btn btn-primary" onclick="create();">Gravar</button>
                                            <a href="javascript::" onclick="carrega_pagina('caixa', 'index.php');" class="btn btn-danger">Voltar</a>
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
    $(function() {
        $( ".nome_contato" ).autocomplete({
            source: "_controller/_contato.php?acao=load_contato",
            minLength: 2,
            focus: function(event, ui) {
            	event.preventDefault();
            },
            select: function( event, ui ) {
                $(".assinatura_id_contato").val(ui.item.value);
                $(".nome_contato").val(ui.item.label);
                event.preventDefault();
            }
        });
    });
    $(function(){
        var acao = "acao=load_plano_assinatura";
        $.ajax({
            type: 'GET',
            url: "_controller/_plano_assinatura.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                var data_return = jQuery.parseJSON(data);
                var options = '<option value=""></option>';
                for (var i = 0; i < data_return.data.length; i++) {
                    options += '<option value="' + data_return.data[i].plano_assinatura_id + '">' + data_return.data[i].plano_assinatura_descricao + '</option>';
                }
                $('.assinatura_id_plano').html(options).show();
                load_out();
            }
        });
        load_out();
    });
    function buscar_contato(){
        var id_contato = $(".assinatura_id_contato").val();
        var acao = "acao=load_contato_id&id="+id_contato;
        
        if(id_contato){
            $.ajax({
                type: 'POST',
                url: "_controller/_contato.php",
                data: acao,
                beforeSend: load_in(),
                success: function (data) {
                    load_out();
                    var data_return = jQuery.parseJSON(data);
                    if(data_return[0].label !== ''){
                        $(".nome_contato").val(data_return[0].label);
                    }else{
                        $("#_modal").modal('show');
                        $("#title_modal").html('Erro');
                        $("#texto_modal").html('Ops, não foi encontrado nenhum registro');
                        $("#buttons_modal").html('');
                        $(".assinatura_id_contato").val('');
                        $(".nome_contato").val('');
                    }
                }
            });
        }
    }
    function create(){
        var dados = $("#create").serialize();
        var acao = "&acao=create";
        
        $.ajax({
            type: 'POST',
            url: "_controller/_assinatura.php",
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
        $(".caixa_descricao").val('');
        $(".caixa_status").val('0');
    }
</script>