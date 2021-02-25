<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Plano Contas
                                <a href="javascript::" onclick="carrega_pagina('plano-conta', 'index.php');" class="btn btn-primary">Voltar</a>
                            </h3>
                        </div>
                        <div class="box-content">
                            <form action="" id="update">
                                <fieldset>
                                    <div class="row">
                                        <div class="form-group col-lg-3">
                                            <label>Tipo Plano</label>
                                            <select class="form-control plano_conta_tipo" name="plano_conta_tipo">
                                                <option value=""></option>
                                                <option value="0">RECEITA</option>
                                                <option value="1">DESPESA</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>Pai</label>
                                            <select class="form-control plano_conta_id_pai" name="plano_conta_id_pai">
                                                <option value="0">Sem Pai</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>Classificação</label>
                                            <input type="text" class="form-control plano_conta_classificacao" name="plano_conta_classificacao"/>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>Descrição</label>
                                            <input type="text" class="form-control plano_conta_descricao" name="plano_conta_descricao"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-12" align="right">
                                            <button type="button" class="btn btn-primary" onclick="update();">Gravar</button>
                                            <a href="javascript::" onclick="carrega_pagina('plano-conta', 'index.php');" class="btn btn-danger">Cancelar</a>
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
            url: "_controller/_plano_conta.php",
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
        var acao = "acao=load_pai_plano_conta";
        $.ajax({
            type: 'GET',
            url: "_controller/_plano_conta.php",
            data: acao,
            beforeSend: load_in(),
            async: false,
            success: function (data) {
                var data_return = jQuery.parseJSON(data);
                var options = '<option value="0">Sem Pai</option>';
                for (var i = 0; i < data_return.data.length; i++) {
                    options += '<option value="' + data_return.data[i].plano_conta_id + '">' + data_return.data[i].plano_conta_classificacao + ' - ' + data_return.data[i].plano_conta_descricao + '</option>';
                }
                $('.plano_conta_id_pai').html(options).show();
                load_out();
            }
        });
        load_out();
    });
    $(function(){
        var acao = "acao=load_update&id=<?=$_GET['id'];?>";
        $.ajax({
            type: 'POST',
            url: "_controller/_plano_conta.php",
            data: acao,
            beforeSend: load_in(),
            async: false,
            success: function (data) {
                load_out();
                var data_return = jQuery.parseJSON(data);
                $(".plano_conta_classificacao").val(data_return[0].plano_conta_classificacao);
                $(".plano_conta_descricao").val(data_return[0].plano_conta_descricao);
                $(".plano_conta_tipo").val(data_return[0].plano_conta_tipo);
                $(".plano_conta_id_pai").val(data_return[0].plano_conta_id_pai);
            }
        });
    });
    function limpar_campos(){
        $(".plano_conta_tipo").val('');
        $(".plano_conta_classificacao").val('');
        $(".plano_conta_descricao").val('');
        $(".plano_conta_tipo").val('0');
    }
</script>