<div class="row">
    <div class="form-group col-lg-3">
        <label>Classe cigarros/bebidas</label>
        <input type="text" class="form-control tributacao_ipi_classe_enquadramento" name="tributacao_ipi_classe_enquadramento"/>
    </div>
    <div class="form-group col-lg-3">
        <label>CNPJ Produtor</label>
        <input type="text" class="form-control tributacao_ipi_cnpj_produtor" name="tributacao_ipi_cnpj_produtor"/>
    </div>
    <div class="form-group col-lg-2">
        <label>Cod. selo IPI</label>
        <input type="text" class="form-control tributacao_ipi_cod_selo_ipi" name="tributacao_ipi_cod_selo_ipi"/>
    </div>
    <div class="form-group col-lg-2">
        <label>Quant. selo IPI</label>
        <input type="number" class="form-control tributacao_ipi_qtd_selo" name="tributacao_ipi_qtd_selo"/>
    </div>
    <div class="form-group col-lg-2">
        <label>Cód. enquadramento</label>
        <input type="number" class="form-control tributacao_ipi_cod_enquadramento" name="tributacao_ipi_cod_enquadramento"/>
    </div>
</div>
<div class="row">
    <div class="form-group col-lg-6">
        <label>Tipo de cálculo</label>
        <select class="form-control tributacao_ipi_tipo_calculo" name="tributacao_ipi_tipo_calculo" onchange="tipo_calculo_ipi();" >
            <option value="1">Portentagem</option>
            <option value="2">Em valor</option>
        </select>
    </div>
    <div id="_form_calc_ipi">
        <div class="form-group col-lg-6">
            <label>Alíquota IPI</label>
            <input type="text" class="form-control tributacao_ipi_aliquota_ipi" name="tributacao_ipi_aliquota_ipi" id="ipi_valor_1"/>
        </div>
    </div>
</div>
<script>
    $("#ipi_valor_1").maskMoney({thousands:'', decimal:'.'});
    function tipo_calculo_ipi(){
        var tributacao_ipi_tipo_calculo = $(".tributacao_ipi_tipo_calculo").val();
        $.post('view/tributacao/_ipi/_tipo_calculo/_calc_'+tributacao_ipi_tipo_calculo+'.php', function (html) {
            $('#_form_calc_ipi').html(html);
        });
    }
</script>