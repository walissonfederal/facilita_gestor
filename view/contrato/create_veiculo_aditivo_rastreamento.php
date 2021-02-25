<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Contrato Rastreamento - Aditivo
                                <a href="javascript::" onclick="carrega_pagina('contrato', 'veiculo_aditivo_rastreamento.php?id_contrato=<?=$_GET['id_contrato'];?>&id_aditivo=<?=$_GET['id_aditivo'];?>');" class="btn btn-primary">Voltar</a>
                            </h3>
                        </div>
                        <div class="box-content">
                            <form action="" id="create">
                                <fieldset>
                                    <div class="row">
                                        <div class="form-group col-lg-3">
                                            <label>Frota</label>
                                            <input type="text" class="form-control contrato_rastreamento_veiculo_frota" name="contrato_rastreamento_veiculo_frota"/>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>Placa</label>
                                            <input type="text" class="form-control contrato_rastreamento_veiculo_placa" name="contrato_rastreamento_veiculo_placa"/>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>Modelo</label>
                                            <input type="text" class="form-control contrato_rastreamento_veiculo_modelo" name="contrato_rastreamento_veiculo_modelo"/>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>Marca</label>
                                            <input type="text" class="form-control contrato_rastreamento_veiculo_marca" name="contrato_rastreamento_veiculo_marca"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-3">
                                            <label>Cor</label>
                                            <input type="text" class="form-control contrato_rastreamento_veiculo_cor" name="contrato_rastreamento_veiculo_cor"/>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>Ano</label>
                                            <input type="text" class="form-control contrato_rastreamento_veiculo_ano" name="contrato_rastreamento_veiculo_ano"/>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>Chassi</label>
                                            <input type="text" class="form-control contrato_rastreamento_veiculo_chassi" name="contrato_rastreamento_veiculo_chassi"/>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>Data</label>
                                            <input type="date" class="form-control contrato_rastreamento_veiculo_data" name="contrato_rastreamento_veiculo_data"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-6">
                                            <label>Tipo</label>
                                            <select name="contrato_rastreamento_veiculo_status" class="form-control contrato_rastreamento_veiculo_status">
                                                <option value="0">Instalação</option>
                                                <option value="1">Desinstalação</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-6">
                                            <label>Veículo</label>
                                            <input type="text" class="form-control contrato_rastreamento_veiculo_veiculo" name="contrato_rastreamento_veiculo_veiculo"/>
                                        </div>
                                    </div>
                                    <input type="hidden" name="contrato_rastreamento_veiculo_id_aditivo" value="<?=$_GET['id_aditivo'];?>"/>
                                    <input type="hidden" name="id_contrato" value="<?=$_GET['id_contrato'];?>"/>
                                    <div class="row">
                                        <div class="form-group col-lg-12" align="right">
                                            <button type="button" class="btn btn-primary" onclick="create();">Gravar</button>
                                            <a href="javascript::" onclick="carrega_pagina('contrato', 'veiculo_aditivo_rastreamento.php?id_contrato=<?=$_GET['id_contrato'];?>&id_aditivo=<?=$_GET['id_aditivo'];?>');" class="btn btn-danger">Voltar</a>
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
        var acao = "&acao=create_veiculo_rastreamento";
        
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
</script>