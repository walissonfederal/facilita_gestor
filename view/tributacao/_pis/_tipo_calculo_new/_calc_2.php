<div class="form-group col-lg-6">
    <label>Valor PIS/unidade (R$)</label>
    <input type="text" class="form-control tributacao_pis_valor_pis" name="tributacao_pis_valor_pis" id="pis_valor_new_2"/>
</div>
<script>
    $("#pis_valor_new_2").maskMoney({thousands:'', decimal:'.'});
</script>