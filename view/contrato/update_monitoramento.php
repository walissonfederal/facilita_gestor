<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Contrato Monitoramento
                                <a href="javascript::" onclick="carrega_pagina('contrato', 'index_monitoramento.php');" class="btn btn-primary">Voltar</a>
                            </h3>
                        </div>
                        <div class="box-content">
                            <form action="" id="update">
                                <fieldset>
                                    <div class="row">
                                        <div class="form-group col-lg-2">
                                            <label>ID Contato</label>
                                            <input type="text" class="form-control contrato_monitoramento_id_contato" onblur="buscar_contato();" name="contrato_monitoramento_id_contato"/>
                                        </div>
                                        <div class="form-group col-lg-10">
                                            <label>Contato(Nome Razão, Nome Fantasia, CPF / CNPJ)</label>
                                            <input type="text" class="form-control contrato_nome_contato"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-2">
                                            <label>Possui Plano Ressarcimento?</label>
                                            <select name="contrato_monitoramento_possui_plano" class="form-control contrato_monitoramento_possui_plano">
                                                <option value="0">NÃO</option>
                                                <option value="1">SIM</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Valor Plano Ressarcimento</label>
                                            <input type="text" class="form-control contrato_monitoramento_valor_plano" name="contrato_monitoramento_valor_plano" id="valor_1"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Possui Ronda?</label>
                                            <select name="contrato_monitoramento_possui_ronda" class="form-control contrato_monitoramento_possui_ronda">
                                                <option value="0">NÃO</option>
                                                <option value="1">SIM</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Valor Mensalidade</label>
                                            <input type="text" onblur="calculo_valor_total_contrato();" class="form-control contrato_monitoramento_valor_mensalidade" name="contrato_monitoramento_valor_mensalidade" id="valor_2"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Duração Contrato</label>
                                            <select name="contrato_monitoramento_duracao" class="form-control contrato_monitoramento_duracao" onchange="calculo_valor_total_contrato();">
                                                <option value="1">1 MÊS</option>
                                                <option value="2">2 MESES</option>
                                                <option value="3">3 MESES</option>
                                                <option value="4">4 MESES</option>
                                                <option value="5">5 MESES</option>
                                                <option value="6">6 MESES</option>
                                                <option value="7">7 MESES</option>
                                                <option value="8">8 MESES</option>
                                                <option value="9">9 MESES</option>
                                                <option value="10">10 MESES</option>
                                                <option value="11">11 MESES</option>
                                                <option value="12">12 MESES</option>
                                                <option value="13">13 MESES</option>
                                                <option value="14">14 MESES</option>
                                                <option value="15">15 MESES</option>
                                                <option value="16">16 MESES</option>
                                                <option value="17">17 MESES</option>
                                                <option value="18">18 MESES</option>
                                                <option value="19">19 MESES</option>
                                                <option value="20">20 MESES</option>
                                                <option value="21">21 MESES</option>
                                                <option value="22">22 MESES</option>
                                                <option value="23">23 MESES</option>
                                                <option value="24">24 MESES</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Valor Total Contrato</label>
                                            <input type="text" readonly="" class="form-control contrato_monitoramento_valor_total_contrato" name="contrato_monitoramento_valor_total_contrato"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-2">
                                            <label>Data Inicial</label>
                                            <input type="date" class="form-control contrato_monitoramento_data_inicial" name="contrato_monitoramento_data_inicial"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Data Final</label>
                                            <input type="date" class="form-control contrato_monitoramento_data_final" name="contrato_monitoramento_data_final"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Cliente Assinou?</label>
                                            <select name="contrato_monitoramento_cliente_assinou" class="form-control contrato_monitoramento_cliente_assinou">
                                                <option value="0">NÃO</option>
                                                <option value="1">SIM</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Status</label>
                                            <select name="contrato_monitoramento_status" class="form-control contrato_monitoramento_status">
                                                <option value="0">EM VIGOR</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Valor Instalação</label>
                                            <input type="text" class="form-control contrato_monitoramento_valor_instalacao" name="contrato_monitoramento_valor_instalacao" id="valor_4"/>
                                        </div>
                                    </div>
									<div class="row">
                                        <div class="form-group col-lg-12">
                                            <label>Obs</label>
                                            <input type="text" class="form-control contrato_monitoramento_obs" name="contrato_monitoramento_obs"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-12" align="right">
                                            <button type="button" class="btn btn-primary" onclick="update();">Gravar</button>
                                            <a href="javascript::" onclick="carrega_pagina('contrato', 'index_monitoramento.php');" class="btn btn-danger">Voltar</a>
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
                $(".contrato_monitoramento_id_contato").val(ui.item.value);
                $(".contrato_nome_contato").val(ui.item.label);
                event.preventDefault();
            }
        });
    });
    function buscar_contato(){
        var contrato_monitoramento_id_contato = $(".contrato_monitoramento_id_contato").val();
        var acao = "acao=load_contato_id&id="+contrato_monitoramento_id_contato;
        
        if(contrato_monitoramento_id_contato){
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
                        $(".contrato_monitoramento_id_contato").val('');
                        $(".contrato_nome_contato").val('');
                    }
                }
            });
        }
    }
    function update(){
        var dados = $("#update").serialize();
        var acao = "&acao=update&id=<?=$_GET['id'];?>";
        
        $.ajax({
            type: 'POST',
            url: "_controller/_contrato_monitoramento.php",
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
    function calculo_valor_total_contrato(){
        var valor_mensalidade = $(".contrato_monitoramento_valor_mensalidade").val();
        var periodo = $(".contrato_monitoramento_duracao").val();
        var valor_mensalidade_convertido = Number(valor_mensalidade);
        var valor_total_contrato = valor_mensalidade_convertido * periodo;
        $(".contrato_monitoramento_valor_total_contrato").val(format_moeda(valor_total_contrato, ""));
    }
    function format_moeda(n, currency) {
        return currency + "" + n.toFixed(2).replace(/./g, function(c, i, a) {
            return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "" + c : c;
        });
    }
    $(function(){
        var acao = "acao=load_update&id=<?=$_GET['id'];?>";
        $.ajax({
            type: 'POST',
            url: "_controller/_contrato_monitoramento.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                var data_return = jQuery.parseJSON(data);
                if(data_return.type === 'error'){
                    $("#_modal").modal('show');
                    $("#title_modal").html(data_return.title);
                    $("#texto_modal").html(data_return.msg);
                    $("#buttons_modal").html(data_return.buttons);
                    setTimeout(function(){
                        $("#_modal").modal('hide');
                        carrega_pagina('contrato', 'index_monitoramento.php');
                    }, 3000);
                }else{
                    $(".contrato_monitoramento_id_contato").val(data_return[0].contrato_monitoramento_id_contato);
                    $(".contrato_monitoramento_possui_plano").val(data_return[0].contrato_monitoramento_possui_plano);
                    $(".contrato_monitoramento_valor_plano").val(data_return[0].contrato_monitoramento_valor_plano);
                    $(".contrato_monitoramento_possui_ronda").val(data_return[0].contrato_monitoramento_possui_ronda);
                    buscar_contato();
                    $(".contrato_monitoramento_valor_mensalidade").val(data_return[0].contrato_monitoramento_valor_mensalidade);
                    $(".contrato_monitoramento_duracao").val(data_return[0].contrato_monitoramento_duracao);
                    $(".contrato_monitoramento_valor_total_contrato").val(data_return[0].contrato_monitoramento_valor_total_contrato);
                    $(".contrato_monitoramento_data_inicial").val(data_return[0].contrato_monitoramento_data_inicial);
                    $(".contrato_monitoramento_data_final").val(data_return[0].contrato_monitoramento_data_final);
                    $(".contrato_monitoramento_status").val(data_return[0].contrato_monitoramento_status);
                    $(".contrato_monitoramento_cliente_assinou").val(data_return[0].contrato_monitoramento_cliente_assinou);
                    $(".contrato_monitoramento_valor_instalacao").val(data_return[0].contrato_monitoramento_valor_instalacao);
					$(".contrato_monitoramento_obs").val(data_return[0].contrato_monitoramento_obs);
                }
            }
        });
    });
    $("#valor_1").maskMoney({thousands:'', decimal:'.'});
    $("#valor_2").maskMoney({thousands:'', decimal:'.'});
    $("#valor_3").maskMoney({thousands:'', decimal:'.'});
</script>