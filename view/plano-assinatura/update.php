<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Planos Assinaturas
                                <a href="javascript::" onclick="carrega_pagina('plano-assinatura', 'index.php');" class="btn btn-primary">Voltar</a>
                            </h3>
                        </div>
                        <div class="box-content">
                            <form action="" id="update">
                                <fieldset>
                                    <div class="row">
                                        <div class="form-group col-lg-6">
                                            <label>Descrição</label>
                                            <input type="text" class="form-control plano_assinatura_descricao" name="plano_assinatura_descricao"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Valor</label>
                                            <input type="text" class="form-control plano_assinatura_valor" name="plano_assinatura_valor" id="valor_1"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Adesão</label>
                                            <input type="text" class="form-control plano_assinatura_adesao" name="plano_assinatura_adesao" id="valor_2"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Status</label>
                                            <select class="form-control plano_assinatura_status" name="plano_assinatura_status">
                                                <option value="0">Ativo</option>
                                                <option value="1">Inativo</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-12">
                                            <label>Descrição prévia</label>
                                            <textarea name="plano_assinatura_texto" class="form-control plano_assinatura_texto" cols="" rows=""></textarea>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-12" align="right">
                                            <button type="button" class="btn btn-primary" onclick="update();">Gravar</button>
                                            <a href="javascript::" onclick="carrega_pagina('plano-assinatura', 'index.php');" class="btn btn-danger">Voltar</a>
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
            url: "_controller/_plano_assinatura.php",
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
        var acao = "acao=load_update&id=<?=$_GET['id'];?>";
        $.ajax({
            type: 'POST',
            url: "_controller/_plano_assinatura.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                var data_return = jQuery.parseJSON(data);
                $(".plano_assinatura_adesao").val(data_return[0].plano_assinatura_adesao);
                $(".plano_assinatura_descricao").val(data_return[0].plano_assinatura_descricao);
                $(".plano_assinatura_status").val(data_return[0].plano_assinatura_status);
                $(".plano_assinatura_valor").val(data_return[0].plano_assinatura_valor);
                $(".plano_assinatura_texto").val(data_return[0].plano_assinatura_texto);
            }
        });
    });
    $("#valor_1").maskMoney({thousands:'', decimal:'.'});
    $("#valor_2").maskMoney({thousands:'', decimal:'.'});
</script>