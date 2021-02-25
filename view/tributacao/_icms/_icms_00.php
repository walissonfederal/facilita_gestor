<div class="row">
    <div class="form-group col-lg-6">
        <label>Modalidade BC</label>
        <select class="form-control tributacao_icms_modalidade_bc" name="tributacao_icms_modalidade_bc"></select>
    </div>
    <div class="form-group col-lg-6">
        <label>Alíq. do ICMS (%)</label>
        <input type="text" class="form-control tributacao_icms_aliquota_icms" name="tributacao_icms_aliquota_icms" id="icms_valor_1"/>
    </div>
</div>
<script>
    $("#icms_valor_1").maskMoney({thousands:'', decimal:'.'});
    $(function(){
        var acao = "acao=load_icms_modalidade_bc";
        $.ajax({
            type: 'GET',
            url: "_controller/_tributacao.php",
            data: acao,
            beforeSend: load_in(),
            async: false,
            success: function (data) {
                var data_return = jQuery.parseJSON(data);
                var options = '';
                for (var i = 0; i < data_return.data.length; i++) {
                    options += '<option value="' + data_return.data[i].codigo_icms_modalidade_bc + '">' + data_return.data[i].desc_icms_modalidade_bc + '</option>';
                }
                $('.tributacao_icms_modalidade_bc').html(options).show();
                load_out();
            }
        });
        load_out();
    });
</script>