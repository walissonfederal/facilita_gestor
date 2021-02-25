<div class="row">
    <div class="form-group col-lg-6">
        <label>Modalidade BC</label>
        <select class="form-control tributacao_icms_modalidade_bc" name="tributacao_icms_modalidade_bc"></select>
    </div>
    <div class="form-group col-lg-3">
        <label>Alíq. do ICMS (%)</label>
        <input type="text" class="form-control tributacao_icms_aliquota_icms" name="tributacao_icms_aliquota_icms" id="icms_valor_1"/>
    </div>
    <div class="form-group col-lg-3">
        <label>Redução base calc. (%)</label>
        <input type="text" class="form-control tributacao_icms_reducao_base_calc" name="tributacao_icms_reducao_base_calc" id="icms_valor_2"/>
    </div>
</div>
<div class="row">
    <div class="form-group col-lg-4">
        <label>Modalidade BC ST</label>
        <select class="form-control tributacao_icms_modalidade_bc_st" name="tributacao_icms_modalidade_bc_st"></select>
    </div>
    <div class="form-group col-lg-2">
        <label>Margem valor adic. (%)</label>
        <input type="text" class="form-control tributacao_icms_margem_valor_add" name="tributacao_icms_margem_valor_add" id="icms_valor_3"/>
    </div>
    <div class="form-group col-lg-2">
        <label>Redução Base Calc ST (%)</label>
        <input type="text" class="form-control tributacao_icms_reducao_base_calc_st" name="tributacao_icms_reducao_base_calc_st" id="icms_valor_4"/>
    </div>
    <div class="form-group col-lg-2">
        <label>Preço unit. Pauta ST (R$)</label>
        <input type="text" class="form-control tributacao_icms_preco_un_pauta_st" name="tributacao_icms_preco_un_pauta_st" id="icms_valor_5"/>
    </div>
    <div class="form-group col-lg-2">
        <label>Alíq. do ICMS ST (%)</label>
        <input type="text" class="form-control tributacao_icms_aliquota_icms_st" name="tributacao_icms_aliquota_icms_st" id="icms_valor_6"/>
    </div>
</div>
<div class="row">
    <div class="form-group col-lg-6">
        <label>Perc. BC op. própria (%)</label>
        <input type="text" class="form-control tributacao_icms_perc_bc_op_propria" name="tributacao_icms_perc_bc_op_propria" id="icms_valor_7"/>
    </div>
    <div class="form-group col-lg-6">
        <label>UF pgto ICMS ST</label>
        <select class="form-control tributacao_icms_uf_icms_st" name="tributacao_icms_uf_icms_st"></select>
    </div>
</div>
<script>
    $("#icms_valor_1").maskMoney({thousands:'', decimal:'.'});
    $("#icms_valor_2").maskMoney({thousands:'', decimal:'.'});
    $("#icms_valor_3").maskMoney({thousands:'', decimal:'.'});
    $("#icms_valor_4").maskMoney({thousands:'', decimal:'.'});
    $("#icms_valor_5").maskMoney({thousands:'', decimal:'.'});
    $("#icms_valor_6").maskMoney({thousands:'', decimal:'.'});
    $("#icms_valor_7").maskMoney({thousands:'', decimal:'.'});
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
    $(function(){
        var acao = "acao=load_icms_modalidade_bc_st";
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
                    options += '<option value="' + data_return.data[i].codigo_icms_modalidade_st + '">' + data_return.data[i].desc_icms_modalidade_st + '</option>';
                }
                $('.tributacao_icms_modalidade_bc_st').html(options).show();
                load_out();
            }
        });
        load_out();
    });
    $(function(){
        var acao = "acao=load_icms_motivo_desoneracao";
        $.ajax({
            type: 'GET',
            url: "_controller/_tributacao.php",
            data: acao,
            beforeSend: load_in(),
            async: false,
            success: function (data) {
                var data_return = jQuery.parseJSON(data);
                var options = '<option value="0">Não desejo usar</option>';
                for (var i = 0; i < data_return.data.length; i++) {
                    options += '<option value="' + data_return.data[i].codigo_icms_desoneracao + '">' + data_return.data[i].desc_icms_desoneracao + '</option>';
                }
                $('.tributacao_icms_mot_desoneracao').html(options).show();
                load_out();
            }
        });
        load_out();
    });
    $(function(){
        var acao = "acao=load_icms_uf";
        $.ajax({
            type: 'GET',
            url: "_controller/_tributacao.php",
            data: acao,
            beforeSend: load_in(),
            async: false,
            success: function (data) {
                var data_return = jQuery.parseJSON(data);
                var options = '<option value=""></option>';
                for (var i = 0; i < data_return.data.length; i++) {
                    options += '<option value="' + data_return.data[i].uf_estado + '">' + data_return.data[i].uf_estado + '</option>';
                }
                $('.tributacao_icms_uf_icms_st').html(options).show();
                load_out();
            }
        });
        load_out();
    });
</script>