<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Contrato Rastreamento - Aditivo
                                <a href="javascript::" onclick="carrega_pagina('contrato', 'aditivo_rastreamento.php?id=<?=$_GET['id'];?>');" class="btn btn-primary">Voltar</a>
                            </h3>
                        </div>
                        <div class="box-content">
                            <form action="" id="create">
                                <fieldset>
                                    <div class="row">
                                        <div class="form-group col-lg-2">
                                            <label>Data Inicial</label>
                                            <input type="date" class="form-control contrato_rastreamento_aditivo_data_inicial" name="contrato_rastreamento_aditivo_data_inicial"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Data Final</label>
                                            <input type="date" class="form-control contrato_rastreamento_aditivo_data_final" name="contrato_rastreamento_aditivo_data_final"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Cliente Assinou?</label>
                                            <select name="contrato_rastreamento_aditivo_cliente_assinou" class="form-control contrato_rastreamento_aditivo_cliente_assinou">
                                                <option value="0">NÃO</option>
                                                <option value="1">SIM</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Status</label>
                                            <select name="contrato_rastreamento_aditivo_status" class="form-control contrato_rastreamento_aditivo_status">
                                                <option value="0">EM VIGOR</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Valor Mensalidade</label>
                                            <input type="text" class="form-control contrato_rastreamento_aditivo_valor_mensalidade" name="contrato_rastreamento_aditivo_valor_mensalidade" id="valor_1"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Tipo</label>
                                            <select name="contrato_rastreamento_aditivo_tipo" class="form-control contrato_rastreamento_aditivo_tipo">
                                                <option value="0">INSTALAÇÃO</option>
                                                <option value="1">DESINSTALAÇÃO</option>
                                            </select>
                                        </div>
                                        <input type="hidden" name="contrato_rastreamento_aditivo_id_contrato" value="<?php echo $_GET['id'];?>"/>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-12" align="right">
                                            <button type="button" class="btn btn-primary" onclick="create();">Gravar</button>
                                            <a href="javascript::" onclick="carrega_pagina('contrato', 'aditivo_rastreamento.php?id=<?=$_GET['id'];?>');" class="btn btn-danger">Voltar</a>
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
    function create(){
        var dados = $("#create").serialize();
        var acao = "&acao=create_aditivo";
        
        $.ajax({
            type: 'POST',
            url: "_controller/_contrato_rastreamento.php",
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
    $("#valor_1").maskMoney({thousands:'', decimal:'.'});
</script>