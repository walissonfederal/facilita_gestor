<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Diretoria - Feito
                                <a href="javascript::" onclick="carrega_pagina('diretoria', 'index_feito.php?id_diretoria=<?=$_GET['id_diretoria'];?>');" class="btn btn-primary">Voltar</a>
                            </h3>
                        </div>
                        <div class="box-content">
                            <form action="" id="update">
                                <fieldset>
                                    <div class="row">
                                        <div class="col-lg-3 form-group">
                                            <label>Data</label>
                                            <input type="date" class="form-control feito_diretoria_data" name="feito_diretoria_data" value=""/>
                                        </div>
                                        <div class="col-lg-9 form-group">
                                            <label>Descrição</label>
                                            <input type="text" class="form-control feito_diretoria_descricao" name="feito_diretoria_descricao" value=""/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 form-group">
                                            <label>Obs</label>
                                            <textarea name="feito_diretoria_obs" class="form-control feito_diretoria_obs" cols="" rows=""></textarea>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-12" align="right">
                                            <button type="button" class="btn btn-primary" onclick="update();">Gravar</button>
                                            <a href="javascript::" onclick="carrega_pagina('diretoria', 'index_feito.php?id_diretoria=<?=$_GET['id_diretoria'];?>');" class="btn btn-danger">Voltar</a>
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
        var acao = "&acao=update&feito_diretoria_id_diretoria=<?=$_GET['id_diretoria'];?>&id=<?=$_GET['id'];?>";
        
        $.ajax({
            type: 'POST',
            url: "_controller/_diretoria_feito.php",
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
    function limpar_campos(){
        $(".feito_diretoria_data").val('');
        $(".feito_diretoria_descricao").val('');
        $(".feito_diretoria_obs").val('');
    }
    $(function(){
        var acao = "acao=load_update&id=<?=$_GET['id'];?>";
        $.ajax({
            type: 'POST',
            url: "_controller/_diretoria_feito.php",
            data: acao,
            beforeSend: load_in(),
            async: false,
            success: function (data) {
                load_out();
                var data_return = jQuery.parseJSON(data);
                $(".feito_diretoria_data").val(data_return[0].feito_diretoria_data);
                $(".feito_diretoria_descricao").val(data_return[0].feito_diretoria_descricao);
                $(".feito_diretoria_obs").val(data_return[0].feito_diretoria_obs);
                buscar_pessoa();
            }
        });
    });
</script>