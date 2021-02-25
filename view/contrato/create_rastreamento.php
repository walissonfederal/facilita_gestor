<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Contrato Rastreamento
                                <a href="javascript::" onclick="carrega_pagina('contrato', 'index_rastreamento.php');" class="btn btn-primary">Voltar</a>
                            </h3>
                        </div>
                        <div class="box-content">
                            <form action="" id="create">
                                <fieldset>
                                    <div class="row">
                                        <div class="form-group col-lg-2">
                                            <label>ID Contato</label>
                                            <input type="text" class="form-control contrato_rastreamento_id_contato" onblur="buscar_contato();" name="contrato_rastreamento_id_contato"/>
                                        </div>
                                        <div class="form-group col-lg-10">
                                            <label>Contato(Nome Razão, Nome Fantasia, CPF / CNPJ)</label>
                                            <input type="text" class="form-control contrato_nome_contato"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-2">
                                            <label>Status</label>
                                            <select name="contrato_rastreamento_status" class="form-control contrato_rastreamento_status">
                                                <option value="0">EM VIGOR</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>Data Inicial</label>
                                            <input type="date" class="form-control contrato_rastreamento_data_inicial" name="contrato_rastreamento_data_inicial"/>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>Data Final</label>
                                            <input type="date" class="form-control contrato_rastreamento_data_final" name="contrato_rastreamento_data_final"/>
                                        </div>
                                        <div class="form-group col-lg-4">
                                            <label>Cliente Assinou?</label>
                                            <select name="contrato_rastreamento_cliente_assinou" class="form-control contrato_rastreamento_cliente_assinou">
                                                <option value="0">NÃO</option>
                                                <option value="1">SIM</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-12">
                                            <p style="font-weight: bold; border-left: 1px solid #000; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000; text-align: center">Caso queira gerar um contrato isentando algum valor deixe em branco e no final o sistema irá pedir uma senha gerencial para finalizar a operação</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-2">
                                            <label>Valor Adesão</label>
                                            <input type="text" class="form-control contrato_rastreamento_valor_adesao" name="contrato_rastreamento_valor_adesao" id="valor_1" value="150.00"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Valor Instalação</label>
                                            <input type="text" class="form-control contrato_rastreamento_valor_instalacao" name="contrato_rastreamento_valor_instalacao" id="valor_2" value="100.00"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Valor Desinstalação</label>
                                            <input type="text" class="form-control contrato_rastreamento_valor_desinstalacao" name="contrato_rastreamento_valor_desinstalacao" id="valor_3" value="59.00"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Valor Manutenção</label>
                                            <input type="text" class="form-control contrato_rastreamento_valor_manutencao" name="contrato_rastreamento_valor_manutencao" id="valor_4" value="59.00"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Valor Mensalidade</label>
                                            <input type="text" class="form-control contrato_rastreamento_valor_mensalidade" name="contrato_rastreamento_valor_mensalidade" id="valor_5" value="59.00"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Valor Equipamento</label>
                                            <input type="text" class="form-control contrato_rastreamento_valor_equipamento" name="contrato_rastreamento_valor_equipamento" id="valor_6" value="850.00"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-2">
                                            <label>Valor KM</label>
                                            <input type="text" class="form-control contrato_rastreamento_valor_km" name="contrato_rastreamento_valor_km" id="valor_7" value="1.00"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Valor Instalação Bloqueador</label>
                                            <input type="text" class="form-control contrato_rastreamento_valor_instalacao_bloqueador" name="contrato_rastreamento_valor_instalacao_bloqueador" id="valor_8" value="85.00"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Valor Mensalidade Bloqueador</label>
                                            <input type="text" class="form-control contrato_rastreamento_valor_mensalidade_bloqueador" name="contrato_rastreamento_valor_mensalidade_bloqueador" id="valor_9" value="16.00"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Valor Mensalidade Sensor</label>
                                            <input type="text" class="form-control contrato_rastreamento_valor_instalacao_sensor" name="contrato_rastreamento_valor_instalacao_sensor" id="valor_11" value="85.00"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Valor Instalação Sensor</label>
                                            <input type="text" class="form-control contrato_rastreamento_valor_mensalidade_sensor" name="contrato_rastreamento_valor_mensalidade_sensor" id="valor_12" value="6.00"/>
                                        </div>
                                        <input type="hidden" name="verificacao_contrato" class="verificacao_contrato" value="0"/>
                                        <input type="hidden" name="senha_gerencial_new" class="senha_gerencial_new" value=""/>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-12" align="right">
                                            <button type="button" class="btn btn-primary" onclick="create();">Gravar</button>
                                            <a href="javascript::" onclick="carrega_pagina('contrato', 'index_rastreamento.php');" class="btn btn-danger">Voltar</a>
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
        var acao = "&acao=create";
        
        $.ajax({
            type: 'POST',
            url: "_controller/_contrato_rastreamento.php",
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
                }else if(data_return.type === 'error_2'){
                    $("#texto_modal_p").html(data_return.msg);
                }else{
                    $("#texto_modal_p").html(data_return.msg);
                    setTimeout(function(){
                        $("#_modal").modal('hide');
                        carrega_pagina('contrato', 'index_rastreamento.php');
                    }, 4000);
                }
            }
        });
    }
    $(function() {
        $( ".contrato_nome_contato" ).autocomplete({
            source: "_controller/_contato.php?acao=load_contato",
            minLength: 2,
            focus: function(event, ui) {
            	event.preventDefault();
            },
            select: function( event, ui ) {
                $(".contrato_rastreamento_id_contato").val(ui.item.value);
                $(".contrato_nome_contato").val(ui.item.label);
                event.preventDefault();
            }
        });
    });
    function buscar_contato(){
        var contrato_chip_id_contato = $(".contrato_rastreamento_id_contato").val();
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
                        $(".contrato_rastreamento_id_contato").val('');
                        $(".contrato_nome_contato").val('');
                    }
                }
            });
        }
    }
    function verifica_contrato_rastreamento(){
        $(".verificacao_contrato").val('1');
        var senha_gerencial = $(".senha_gerencial_contrato_rastreamento").val();
        $(".senha_gerencial_new").val(senha_gerencial);
        create();
    }
    $("#valor_1").maskMoney({thousands:'', decimal:'.'});
    $("#valor_2").maskMoney({thousands:'', decimal:'.'});
    $("#valor_3").maskMoney({thousands:'', decimal:'.'});
    $("#valor_4").maskMoney({thousands:'', decimal:'.'});
    $("#valor_5").maskMoney({thousands:'', decimal:'.'});
    $("#valor_6").maskMoney({thousands:'', decimal:'.'});
    $("#valor_7").maskMoney({thousands:'', decimal:'.'});
    $("#valor_8").maskMoney({thousands:'', decimal:'.'});
    $("#valor_9").maskMoney({thousands:'', decimal:'.'});
    $("#valor_10").maskMoney({thousands:'', decimal:'.'});
    $("#valor_11").maskMoney({thousands:'', decimal:'.'});
    $("#valor_12").maskMoney({thousands:'', decimal:'.'});
</script>