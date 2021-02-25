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
                            <form action="" id="update">
                                <fieldset>
                                    <div class="row">
                                        <div class="form-group col-lg-2">
                                            <label>ID Contato</label>
                                            <input type="text" class="form-control contrato_rastreamento_id_contato" onblur="buscar_contato();" name="contrato_rastreamento_id_contato"/>
                                        </div>
                                        <div class="form-group col-lg-6">
                                            <label>Contato(Nome Razão, Nome Fantasia, CPF / CNPJ)</label>
                                            <input type="text" class="form-control contrato_nome_contato"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Data Inicial</label>
                                            <input type="date" class="form-control contrato_rastreamento_data_inicial" name="contrato_rastreamento_data_inicial"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Data Final</label>
                                            <input type="date" class="form-control contrato_rastreamento_data_final" name="contrato_rastreamento_data_final"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-2">
                                            <label>Valor Adesão</label>
                                            <input type="text" class="form-control contrato_rastreamento_valor_adesao" name="contrato_rastreamento_valor_adesao" id="valor_1"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Valor Instalação</label>
                                            <input type="text" class="form-control contrato_rastreamento_valor_instalacao" name="contrato_rastreamento_valor_instalacao" id="valor_2"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Valor Desinstalação</label>
                                            <input type="text" class="form-control contrato_rastreamento_valor_desinstalacao" name="contrato_rastreamento_valor_desinstalacao" id="valor_3"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Valor Manutenção</label>
                                            <input type="text" class="form-control contrato_rastreamento_valor_manutencao" name="contrato_rastreamento_valor_manutencao" id="valor_4"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Valor Mensalidade</label>
                                            <input type="text" class="form-control contrato_rastreamento_valor_mensalidade" name="contrato_rastreamento_valor_mensalidade" id="valor_5"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Valor Equipamento</label>
                                            <input type="text" class="form-control contrato_rastreamento_valor_equipamento" name="contrato_rastreamento_valor_equipamento" id="valor_6"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-2">
                                            <label>Valor KM</label>
                                            <input type="text" class="form-control contrato_rastreamento_valor_km" name="contrato_rastreamento_valor_km" id="valor_7"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Valor Instalação Bloqueador</label>
                                            <input type="text" class="form-control contrato_rastreamento_valor_instalacao_bloqueador" name="contrato_rastreamento_valor_instalacao_bloqueador" id="valor_8"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Valor Mensalidade Bloqueador</label>
                                            <input type="text" class="form-control contrato_rastreamento_valor_mensalidade_bloqueador" name="contrato_rastreamento_valor_mensalidade_bloqueador" id="valor_9"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Valor Mensalidade Sensor</label>
                                            <input type="text" class="form-control contrato_rastreamento_valor_instalacao_sensor" name="contrato_rastreamento_valor_instalacao_sensor" id="valor_11"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Valor Instalação Sensor</label>
                                            <input type="text" class="form-control contrato_rastreamento_valor_mensalidade_sensor" name="contrato_rastreamento_valor_mensalidade_sensor" id="valor_12"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-12" align="right">
                                            <button type="button" class="btn btn-primary" onclick="update();">Gravar</button>
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
    $(function(){
        load_update();
    });
    function update(){
        var dados = $("#update").serialize();
        var acao = "&acao=update&id=<?=$_GET['id'];?>";
        
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
                }else{
                    $("#_modal").modal('show');
                    $("#title_modal").html(data_return.title);
                    $("#texto_modal").html(data_return.msg);
                    $("#buttons_modal").html(data_return.buttons);
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
    function load_update(){
        var acao = "acao=load_update&id=<?=$_GET['id'];?>";
        
        $.ajax({
            type: 'POST',
            url: "_controller/_contrato_rastreamento.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                var data_return = jQuery.parseJSON(data);
                $(".contrato_rastreamento_id_contato").val(data_return[0].contrato_rastreamento_id_contato);
                buscar_contato();
                $(".contrato_rastreamento_valor_adesao").val(data_return[0].contrato_rastreamento_valor_adesao);
                $(".contrato_rastreamento_valor_desinstalacao").val(data_return[0].contrato_rastreamento_valor_desinstalacao);
                $(".contrato_rastreamento_valor_instalacao").val(data_return[0].contrato_rastreamento_valor_instalacao);
                $(".contrato_rastreamento_valor_manutencao").val(data_return[0].contrato_rastreamento_valor_manutencao);
                $(".contrato_rastreamento_valor_mensalidade").val(data_return[0].contrato_rastreamento_valor_mensalidade);
                $(".contrato_rastreamento_valor_equipamento").val(data_return[0].contrato_rastreamento_valor_equipamento);
                $(".contrato_rastreamento_valor_km").val(data_return[0].contrato_rastreamento_valor_km);
                $(".contrato_rastreamento_valor_instalacao_bloqueador").val(data_return[0].contrato_rastreamento_valor_instalacao_bloqueador);
                $(".contrato_rastreamento_valor_mensalidade_bloqueador").val(data_return[0].contrato_rastreamento_valor_mensalidade_bloqueador);
                $(".contrato_rastreamento_valor_instalacao_sensor").val(data_return[0].contrato_rastreamento_valor_instalacao_sensor);
                $(".contrato_rastreamento_valor_mensalidade_sensor").val(data_return[0].contrato_rastreamento_valor_mensalidade_sensor);
                $(".contrato_rastreamento_data_inicial").val(data_return[0].contrato_rastreamento_data_inicial);
                $(".contrato_rastreamento_data_final").val(data_return[0].contrato_rastreamento_data_final);
            }
        });
    }
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