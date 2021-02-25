<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Contrato Rastreamento - Aditivo
                                <a href="javascript::" onclick="carrega_pagina('contrato', 'aditivo_rastreamento.php?id=<?=$_GET['id_contrato'];?>');" class="btn btn-primary">Voltar</a>
                                <a href="javascript::" onclick="carrega_pagina('contrato', 'create_veiculo_aditivo_rastreamento.php?id_contrato=<?=$_GET['id_contrato'];?>&id_aditivo=<?=$_GET['id_aditivo'];?>');" class="btn btn-primary">Cadastrar Novo</a>
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
    var updateIcon = function (value, data, cell, row, options) { //plain text value
        return "<i class='fa fa-pencil' style='vertical-align:middle; padding:2px 0;' title='Editar'></i> ";
    };
    var closeIcon = function (value, data, cell, row, options) { //plain text value
        return "<i class='fa fa-close' style='vertical-align:middle; padding:2px 0;' title='Excluir'></i> ";
    };
    $("#load_contrato_aditivo").tabulator({
        height: "350px",
        fitColumns: true,
        ajaxURL: "_controller/_contrato_rastreamento.php",
        ajaxParams: {acao: "load_aditivo_veiculo", id_contrato: <?=$_GET['id_contrato'];?>, id_aditivo: <?=$_GET['id_aditivo'];?>},
        pagination: "remote",
        paginationSize: 100,
        paginationDataSent: {"page": "pageNo"},
        columns: [
            {
                formatter: updateIcon, width: 40, align: "center", onClick: function (e, cell, val, data) {
                    carrega_pagina('contrato', 'update_veiculo_aditivo_rastreamento.php?id_contrato=<?=$_GET['id_contrato'];?>&id_aditivo=<?=$_GET['id_aditivo'];?>&id='+data.contrato_rastreamento_veiculo_id);
                }
            },
            {title: "ID Aditivo", field: "contrato_rastreamento_veiculo_id_aditivo", sorter: "int", width: 100},
            {title: "Veículo", field: "contrato_rastreamento_veiculo_veiculo", sorter: "string"},
            {title: "Frota", field: "contrato_rastreamento_veiculo_frota", sorter: "string"},
            {title: "Placa", field: "contrato_rastreamento_veiculo_placa", sorter: "string"},
            {title: "Modelo", field: "contrato_rastreamento_veiculo_modelo", sorter: "string"},
            {title: "Marca", field: "contrato_rastreamento_veiculo_marca", sorter: "string"},
            {title: "Cor", field: "contrato_rastreamento_veiculo_cor", sorter: "string"},
            {title: "Ano", field: "contrato_rastreamento_veiculo_ano", sorter: "string"},
            {title: "Tipo", field: "contrato_rastreamento_veiculo_status", sorter: "string"},
            {title: "Data", field: "contrato_rastreamento_veiculo_data", sorter: "string"}
        ]
    });
</script>