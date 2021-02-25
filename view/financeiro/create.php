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
                            <form action="" id="create">
                                <fieldset>
                                    <div class="row">
                                        <div class="form-group col-lg-2">
                                            <label>ID Contato</label>
                                            <input type="text" class="form-control financeiro_id_contato" onblur="buscar_contato();" name="financeiro_id_contato"/>
                                        </div>
                                        <div class="form-group col-lg-10">
                                            <label>Contato(Nome Razão, Nome Fantasia, CPF / CNPJ)</label>
                                            <input type="text" class="form-control financeiro_nome_contato"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-2">
                                            <label>Data Vencimento</label>
                                            <input type="date" class="form-control financeiro_data_vencimento" name="financeiro_data_vencimento"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Valor</label>
                                            <input type="text" class="form-control financeiro_valor" name="financeiro_valor" id="valor_1"/>
                                        </div>
                                        <div class="form-group col-lg-4">
                                            <label>Descrição</label>
                                            <input type="text" class="form-control financeiro_descricao" name="financeiro_descricao"/>
                                        </div>
                                        <div class="form-group col-lg-4">
                                            <label>Plano de Contas</label>
                                            <select class="form-control financeiro_id_plano_conta" name="financeiro_id_plano_conta"></select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-2">
                                            <label>Tipo Documento</label>
                                            <select class="form-control financeiro_id_tipo_documento" name="financeiro_id_tipo_documento"></select>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Parcelas</label>
                                            <select class="form-control financeiro_parcela" name="financeiro_parcela">
                                                <option value="01">01</option>
                                                <option value="02">02</option>
                                                <option value="03">03</option>
                                                <option value="04">04</option>
                                                <option value="05">05</option>
                                                <option value="06">06</option>
                                                <option value="07">07</option>
                                                <option value="08">08</option>
                                                <option value="09">09</option>
                                                <option value="10">10</option>
                                                <option value="11">11</option>
                                                <option value="12">12</option>
                                                <option value="13">13</option>
                                                <option value="14">14</option>
                                                <option value="15">15</option>
                                                <option value="16">16</option>
                                                <option value="17">17</option>
                                                <option value="18">18</option>
                                                <option value="19">19</option>
                                                <option value="20">20</option>
                                                <option value="21">21</option>
                                                <option value="22">22</option>
                                                <option value="23">23</option>
                                                <option value="24">24</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Configuração</label>
                                            <select class="form-control financeiro_config" name="financeiro_config">
                                                <option value="0">REPETIR</option>
                                                <option value="1">DIVIDIR</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Fixa</label>
                                            <select class="form-control financeiro_fixo" name="financeiro_fixo">
                                                <option value="0">NÃO</option>
                                                <option value="1">SIM</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Aplicação Financeira?</label>
                                            <select class="form-control financeiro_app_financeira" name="financeiro_app_financeira">
                                                <option value="0">NÃO</option>
                                                <option value="1">SIM</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Número DOC</label>
                                            <input type="text" class="form-control financeiro_numero_doc" name="financeiro_numero_doc"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-2">
                                            <label>ID Vendedor</label>
                                            <input type="text" class="form-control financeiro_id_vendedor" onblur="buscar_vendedor();" name="financeiro_id_vendedor"/>
                                        </div>
                                        <div class="form-group col-lg-10">
                                            <label>Vendedor(Nome, CPF, Email)</label>
                                            <input type="text" class="form-control financeiro_nome_vendedor"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-12">
                                            <label>Obs</label>
                                            <textarea name="financeiro_obs" class="form-control financeiro_obs" cols="" rows=""></textarea>                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-12" align="right">
                                            <button type="button" class="btn btn-primary" onclick="create();">Gravar</button>
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
        $( ".financeiro_nome_vendedor" ).autocomplete({
            source: "_controller/_vendedor_franquiado.php?acao=load_vendedor",
            minLength: 2,
            focus: function(event, ui) {
            	event.preventDefault();
            },
            select: function( event, ui ) {
                $(".financeiro_id_vendedor").val(ui.item.value);
                $(".financeiro_nome_vendedor").val(ui.item.label);
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
    function buscar_vendedor(){
        var financeiro_id_vendedor = $(".financeiro_id_vendedor").val();
        var acao = "acao=load_vendedor_id&id="+financeiro_id_vendedor;
        
        if(financeiro_id_vendedor){
            $.ajax({
                type: 'POST',
                url: "_controller/_vendedor_franquiado.php",
                data: acao,
                beforeSend: load_in(),
                success: function (data) {
                    load_out();
                    var data_return = jQuery.parseJSON(data);
                    if(data_return[0].label !== ''){
                        $(".financeiro_nome_vendedor").val(data_return[0].label);
                    }else{
                        $("#_modal").modal('show');
                        $("#title_modal").html('Erro');
                        $("#texto_modal").html('Ops, não foi encontrado nenhum registro');
                        $("#buttons_modal").html('');
                        $(".financeiro_id_vendedor").val('');
                        $(".financeiro_nome_vendedor").val('');
                    }
                }
            });
        }
    }
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
    function create(){
        var dados = $("#create").serialize();
        var acao = "&acao=create&financeiro_tipo=<?=$_GET['OP'];?>";
        
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
    $("#valor_1").maskMoney({thousands:'', decimal:'.'});
</script>