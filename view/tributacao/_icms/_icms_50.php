<div class="row">
    <div class="form-group col-lg-12">
        <label>Motivo desoneração</label>
        <select class="form-control tributacao_icms_mot_desoneracao" name="tributacao_icms_mot_desoneracao"></select>
    </div>
</div>
<script>
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
</script>