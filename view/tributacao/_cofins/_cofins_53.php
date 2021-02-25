<div class="row">
    <div class="form-group col-lg-6">
        <label>Tipo de cálculo ST</label>
        <select class="form-control tributacao_cofins_tipo_calculo_st" name="tributacao_cofins_tipo_calculo_st" onchange="tipo_calculo_cofins();" >
            <option value="0">Não usar</option>
            <option value="1">Portentagem</option>
            <option value="2">Em valor</option>
        </select>
    </div>
    <div id="_form_calc_cofins"></div>
</div>
<script>
    function tipo_calculo_cofins(){
        var tributacao_cofins_tipo_calculo_st = $(".tributacao_cofins_tipo_calculo_st").val();
        $.post('view/tributacao/_cofins/_tipo_calculo/_calc_'+tributacao_cofins_tipo_calculo_st+'.php', function (html) {
            $('#_form_calc_cofins').html(html);
        });
    }
</script>