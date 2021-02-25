<div class="form-group col-lg-6">
    <label>Valor IPI/unidade (R$)</label>
    <input type="text" class="form-control tributacao_ipi_valor_ipi" name="tributacao_ipi_valor_ipi" id="ipi_valor_calc_2"/>
</div>
<script>
    $("#ipi_valor_calc_2").maskMoney({thousands:'', decimal:'.'});
</script>