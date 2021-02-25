<div class="row">
    <div class="form-group col-lg-6">
        <label>Tipo de cálculo</label>
        <select class="form-control tributacao_pis_tipo_calculo" name="tributacao_pis_tipo_calculo" onchange="tipo_calculo_new_pis();" >
            <option value="1">Portentagem</option>
            <option value="2" selected="">Em valor</option>
        </select>
    </div>
    <div id="_form_calc_pis_new">
        <div class="form-group col-lg-6">
            <label>Valor PIS/unidade (R$)</label>
            <input type="text" class="form-control tributacao_pis_valor_pis" name="tributacao_pis_valor_pis" id="pis_valor_1"/>
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-lg-6">
        <label>Tipo de cálculo ST</label>
        <select class="form-control tributacao_pis_tipo_calculo_st" name="tributacao_pis_tipo_calculo_st" onchange="tipo_calculo_pis();" >
            <option value="0">Não usar</option>
            <option value="1">Portentagem</option>
            <option value="2">Em valor</option>
        </select>
    </div>
    <div id="_form_calc_pis"></div>
</div>
<script>
    $("#pis_valor_1").maskMoney({thousands:'', decimal:'.'});
    function tipo_calculo_pis(){
        var tributacao_pis_tipo_calculo_st = $(".tributacao_pis_tipo_calculo_st").val();
        $.post('view/tributacao/_pis/_tipo_calculo/_calc_'+tributacao_pis_tipo_calculo_st+'.php', function (html) {
            $('#_form_calc_pis').html(html);
        });
    }
    function tipo_calculo_new_pis(){
        var tributacao_pis_tipo_calculo = $(".tributacao_pis_tipo_calculo").val();
        $.post('view/tributacao/_pis/_tipo_calculo_new/_calc_'+tributacao_pis_tipo_calculo+'.php', function (html) {
            $('#_form_calc_pis_new').html(html);
        });
    }
</script>