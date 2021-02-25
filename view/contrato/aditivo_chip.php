<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Contrato Chip - Aditivo
                                <a href="javascript::" onclick="carrega_pagina('contrato', 'index_chip.php');" class="btn btn-primary">Voltar</a>
                                <a href="javascript::" onclick="carrega_pagina('contrato', 'create_aditivo_chip.php?id=<?=$_GET['id'];?>');" class="btn btn-primary">Cadastrar Novo</a>
                            </h3>
                        </div>
                        <div class="box-content">
                            <div id="load_contrato_aditivo"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var refreshIcon = function (value, data, cell, row, options) { //plain text value
        return "<i class='fa fa-refresh' style='vertical-align:middle; padding:2px 0;' title='Gerar documento'></i> ";
    };
    var downloadIcon = function (value, data, cell, row, options) { //plain text value
        return "<i class='fa fa-cloud-download' style='vertical-align:middle; padding:2px 0;' title='Download arquivos'></i> ";
    };
    var printIcon = function (value, data, cell, row, options) { //plain text value
        return "<i class='fa fa-print' style='vertical-align:middle; padding:2px 0;' title='Imprimir Contrato'></i> ";
    };
    $("#load_contrato_aditivo").tabulator({
        height: "350px",
        fitColumns: true,
        ajaxURL: "_controller/_contrato_chip.php",
        ajaxParams: {acao: "load_aditivo", id_contrato: <?=$_GET['id'];?>},
        pagination: "remote",
        paginationSize: 100,
        paginationDataSent: {"page": "pageNo"},
        columns: [
            {
                formatter: refreshIcon, width: 40, align: "center", onClick: function (e, cell, val, data) {
                    $("#_modal_assinatura_contrato_chip").modal('show');
                    $(".id_contrato_chip").val(data.contrato_chip_aditivo_id);
                    $("#ok_contrato_chip").html('');
                }
            },
            {
                formatter: downloadIcon, width: 40, align: "center", onClick: function (e, cell, val, data) {
                    download_aditivo_contrato_chip(data.contrato_chip_aditivo_id_d4sign);
                }
            },
            {
                formatter: printIcon, width: 40, align: "center", onClick: function (e, cell, val, data) {
                    window.open('contrato/ContratoChipAditivo/index.php?Id='+data.contrato_chip_aditivo_id,'_blank');
                }
            },
            {title: "ID Aditivo", field: "contrato_chip_aditivo_id_aditivo", sorter: "int", width: 200},
            {title: "ID Pedido", field: "contrato_chip_aditivo_id_pedido", sorter: "string"},
            {title: "Data Inicial", field: "contrato_chip_aditivo_data_criacao", sorter: "string"},
            {title: "Data Final", field: "contrato_chip_aditivo_data_final", sorter: "string"},
            {title: "Cliente Assinou", field: "contrato_chip_aditivo_cliente_assinou", sorter: "string"},
            {title: "Status", field: "contrato_chip_aditivo_status", sorter: "string"}
        ]
    });
    function download_aditivo_contrato_chip(documento){
        window.open('view/contrato/download_aditivo_contrato_chip.php?arquivo='+documento,'_blank');
    }
    function enviar_contrato_chip(){
        var email       = $(".emails_contrato_chip").val();
        var id_contrato = $(".id_contrato_chip").val();
        var msg         = $(".msg_contrato_chip").val();
        
        if(email === ''){
            $("#ok_contrato_chip").html('<p>Email está em branco</p>');
        }else if(id_contrato === ''){
            $("#ok_contrato_chip").html('<p>O contrato por algum motivo não pode ser gerado</p>');
        }else if(msg === ''){
            $("#ok_contrato_chip").html('<p>É preciso escrever uma mensagem</p>');
        }else{
            var acao = "acao=enviar_contrato_aditivo&email="+email+"&msg="+msg+"&id_aditivo_contrato="+id_contrato;
        
            $.ajax({
                type: 'POST',
                url: "_controller/_contrato_chip.php",
                data: acao,
                beforeSend: load_in(),
                success: function (data) {
                    load_out();
                    $("#ok_contrato_chip").html(data);
                }
            });
        }
    }
</script>
<div id="_modal_assinatura_contrato_chip" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Enviar Email Para Assinatura</h4>
            </div>
            <!-- /.modal-header -->
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-lg-12">
                        <label>Emails</label>
                        <input type="text" class="form-control emails_contrato_chip"/>
                        <input type="hidden" class="form-control id_contrato_chip"/>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-lg-12">
                        <label>Mensagem</label>
                        <textarea class="form-control msg_contrato_chip"></textarea>
                    </div>
                </div>
                <div id="ok_contrato_chip"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="enviar_contrato_chip();">Enviar</button>
            </div>
        </div>
    </div>
</div>