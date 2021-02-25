<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Diretoria
                                <a href="javascript::" onclick="carrega_pagina('diretoria', 'index.php');" class="btn btn-primary">Voltar</a>
                            </h3>
                        </div>
                        <div class="box-content">
                            <form action="" id="create">
                                <fieldset>
                                    <div class="row">
                                        <div class="form-group col-lg-9">
                                            <label>Descrição</label>
                                            <input type="text" class="form-control diretoria_descricao" name="diretoria_descricao"/>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>Período</label>
                                            <select class="form-control diretoria_id_periodo" name="diretoria_id_periodo"></select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-12">
                                            <label>Obs</label>
                                            <textarea name="diretoria_obs" class="diretoria_obs form-control" cols="" rows=""></textarea>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-12" align="right">
                                            <button type="button" class="btn btn-primary" onclick="create();">Gravar</button>
                                            <a href="javascript::" onclick="carrega_pagina('diretoria', 'index.php');" class="btn btn-danger">Voltar</a>
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
    function update(){
        var dados = $("#update").serialize();
        var acao = "&acao=update&id=<?=$_GET['id'];?>";
        
        $.ajax({
            type: 'POST',
            url: "_controller/_cargo.php",
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
    $(function(){
        var acao = "acao=load_periodo";
        $.ajax({
            type: 'GET',
            url: "_controller/_periodo.php",
            data: acao,
            beforeSend: load_in(),
            async: false,
            success: function (data) {
                var data_return = jQuery.parseJSON(data);
                var options = '<option value=""></option>';
                for (var i = 0; i < data_return.data.length; i++) {
                    options += '<option value="' + data_return.data[i].periodo_id + '">' + data_return.data[i].periodo_descricao + '</option>';
                }
                $('.diretoria_id_periodo').html(options).show();
                load_out();
            }
        });
        load_out();
    });
    $(function(){
        var acao = "acao=load_update&id=<?=$_GET['id'];?>";
        $.ajax({
            type: 'POST',
            url: "_controller/_diretoria.php",
            data: acao,
            beforeSend: load_in(),
            async: false,
            success: function (data) {
                load_out();
                var data_return = jQuery.parseJSON(data);
                $(".diretoria_descricao").val(data_return[0].diretoria_descricao);
                $(".diretoria_id_periodo").val(data_return[0].diretoria_id_periodo);
                $(".diretoria_obs").val(data_return[0].diretoria_obs);
            }
        });
    });
</script>