<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Contrato Chip
                                <a href="javascript::" onclick="carrega_pagina('contrato', 'index_chip.php');" class="btn btn-primary">Voltar</a>
                            </h3>
                        </div>
                        <div class="box-content">
                            <form action="" id="create">
                                <fieldset>
                                    <div class="row">
                                        <div class="form-group col-lg-2">
                                            <label>ID Contato</label>
                                            <input type="text" class="form-control contrato_chip_id_contato" onblur="buscar_contato();" name="contrato_chip_id_contato"/>
                                        </div>
                                        <div class="form-group col-lg-10">
                                            <label>Contato(Nome Razão, Nome Fantasia, CPF / CNPJ)</label>
                                            <input type="text" class="form-control contrato_nome_contato"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-2">
                                            <label>Status</label>
                                            <select name="contrato_chip_status" class="form-control contrato_chip_status">
                                                <option value="0">EM VIGOR</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Data Inicial</label>
                                            <input type="date" class="form-control contrato_chip_data_inicial" name="contrato_chip_data_inicial"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Data Final</label>
                                            <input type="date" class="form-control contrato_chip_data_final" name="contrato_chip_data_final"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Cliente Assinou?</label>
                                            <select name="contrato_chip_cliente_assinou" class="form-control contrato_chip_cliente_assinou">
                                                <option value="0">NÃO</option>
                                                <option value="1">SIM</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-12" align="right">
                                            <button type="button" class="btn btn-primary" onclick="create();">Gravar</button>
                                            <a href="javascript::" onclick="carrega_pagina('contrato', 'index_chip.php');" class="btn btn-danger">Voltar</a>
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
        $( ".contrato_nome_contato" ).autocomplete({
            source: "_controller/_contato.php?acao=load_contato",
            minLength: 2,
            focus: function(event, ui) {
            	event.preventDefault();
            },
            select: function( event, ui ) {
                $(".contrato_chip_id_contato").val(ui.item.value);
                $(".contrato_nome_contato").val(ui.item.label);
                event.preventDefault();
            }
        });
    });
    function buscar_contato(){
        var contrato_chip_id_contato = $(".contrato_chip_id_contato").val();
        var acao = "acao=load_contato_id&id="+contrato_chip_id_contato;
        
        if(contrato_chip_id_contato){
            $.ajax({
                type: 'POST',
                url: "_controller/_contato.php",
                data: acao,
                beforeSend: load_in(),
                success: function (data) {
                    load_out();
                    var data_return = jQuery.parseJSON(data);
                    if(data_return[0].label !== ''){
                        $(".contrato_nome_contato").val(data_return[0].label);
                    }else{
                        $("#_modal").modal('show');
                        $("#title_modal").html('Erro');
                        $("#texto_modal").html('Ops, não foi encontrado nenhum registro');
                        $("#buttons_modal").html('');
                        $(".contrato_chip_id_contato").val('');
                        $(".contrato_nome_contato").val('');
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
            url: "_controller/_contrato_chip.php",
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