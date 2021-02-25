<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Contrato Chip - Aditivo
                                <a href="javascript::" onclick="carrega_pagina('contrato', 'aditivo_chip.php?id=<?=$_GET['id'];?>');" class="btn btn-primary">Voltar</a>
                            </h3>
                        </div>
                        <div class="box-content">
                            <form action="" id="create">
                                <fieldset>
                                    <div class="row">
                                        <div class="form-group col-lg-4">
                                            <label>ID Pedido</label>
                                            <input type="text" class="form-control contrato_chip_aditivo_id_pedido" name="contrato_chip_aditivo_id_pedido"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Data Criação</label>
                                            <input type="date" class="form-control contrato_chip_aditivo_data_criacao" name="contrato_chip_aditivo_data_criacao"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Data Final</label>
                                            <input type="date" class="form-control contrato_chip_aditivo_data_final" name="contrato_chip_aditivo_data_final"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Cliente Assinou?</label>
                                            <select name="contrato_chip_aditivo_cliente_assinou" class="form-control contrato_chip_aditivo_cliente_assinou">
                                                <option value="0">NÃO</option>
                                                <option value="1">SIM</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Status</label>
                                            <select class="form-control contrato_chip_aditivo_status" name="contrato_chip_aditivo_status">
                                                <option value="0">Ativo</option>
                                                <option value="1">Inativo</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-12" align="right">
                                            <button type="button" class="btn btn-primary" onclick="create_pedido_aditivo_chip();">Gravar</button>
                                            <a href="javascript::" onclick="carrega_pagina('contrato', 'aditivo_chip.php?id=<?=$_GET['id'];?>');" class="btn btn-danger">Voltar</a>
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
    function create_pedido_aditivo_chip(){
        var dados = $("#create").serialize();
        var acao = "&acao=create_aditivo_pedido&contrato_chip_aditivo_id_contrato=<?=$_GET['id'];?>";
        
        $.ajax({
            type: 'POST',
            url: "_controller/_contrato_chip.php",
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
        $(".caixa_descricao").val('');
        $(".caixa_status").val('0');
    }
</script>