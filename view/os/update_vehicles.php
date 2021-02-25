<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Ordem de Serviço - Veículos
                                <a href="javascript::" onclick="carrega_pagina('os', 'vehicles.php?id=<?=$_GET['os_veiculo_id_os'];?>');" class="btn btn-primary">Voltar</a>
                            </h3>
                        </div>
                        <div class="box-content">
                            <form action="" id="update">
                                <fieldset>
                                    <div class="row">
                                        <div class="form-group col-lg-3">
                                            <label>Frota</label>
                                            <input type="text" class="form-control os_veiculo_frota" name="os_veiculo_frota"/>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>Placa</label>
                                            <input type="text" class="form-control os_veiculo_placa" name="os_veiculo_placa"/>
                                        </div>
                                        <div class="form-group col-lg-4">
                                            <label>Modelo</label>
                                            <input type="text" class="form-control os_veiculo_modelo" name="os_veiculo_modelo"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Marca</label>
                                            <input type="text" class="form-control os_veiculo_marca" name="os_veiculo_marca"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-3">
                                            <label>Chassi</label>
                                            <input type="text" class="form-control os_veiculo_chassi" name="os_veiculo_chassi"/>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>ICCID</label>
                                            <input type="text" class="form-control os_veiculo_iccid" name="os_veiculo_iccid"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Cor</label>
                                            <input type="text" class="form-control os_veiculo_cor" name="os_veiculo_cor"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Ano</label>
                                            <input type="text" class="form-control os_veiculo_ano" name="os_veiculo_ano"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Serial</label>
                                            <input type="text" class="form-control os_veiculo_serial" name="os_veiculo_serial"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-12" align="right">
                                            <button type="button" class="btn btn-primary" onclick="update();">Gravar</button>
                                            <a href="javascript::" onclick="carrega_pagina('os', 'vehicles.php?id=<?=$_GET['os_veiculo_id_os'];?>');" class="btn btn-danger">Voltar</a>
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
        var acao = "&acao=update_veiculo&os_veiculo_id_os=<?=$_GET['os_veiculo_id_os'];?>&id=<?=$_GET['id'];?>";
        
        $.ajax({
            type: 'POST',
            url: "_controller/_os.php",
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
        var acao = "acao=load_update_veiculo&id=<?=$_GET['id'];?>";
        $.ajax({
            type: 'POST',
            url: "_controller/_os.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                var data_return = jQuery.parseJSON(data);
                $(".os_veiculo_frota").val(data_return[0].os_veiculo_frota);
                $(".os_veiculo_placa").val(data_return[0].os_veiculo_placa);
                $(".os_veiculo_modelo").val(data_return[0].os_veiculo_modelo);
                $(".os_veiculo_marca").val(data_return[0].os_veiculo_marca);
                $(".os_veiculo_cor").val(data_return[0].os_veiculo_cor);
                $(".os_veiculo_ano").val(data_return[0].os_veiculo_ano);
                $(".os_veiculo_chassi").val(data_return[0].os_veiculo_chassi);
                $(".os_veiculo_iccid").val(data_return[0].os_veiculo_iccid);
                $(".os_veiculo_serial").val(data_return[0].os_veiculo_serial);
            }
        });
    });
</script>