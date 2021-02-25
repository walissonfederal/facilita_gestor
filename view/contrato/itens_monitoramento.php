<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Itens Contrato Monitoramento
                                <a href="javascript::" onclick="carrega_pagina('contrato', 'index_monitoramento.php');" class="btn btn-primary">Voltar</a>
                                <a href="javascript::" onclick="carrega_pagina('contrato', 'create_itens_monitoramento.php?id=<?=$_GET['id'];?>');" class="btn btn-primary">Cadastrar Novo</a>
                            </h3>
                        </div>
                        <div class="box-content">
                            <hr />
                            <div id="load_itens_monitoramento"></div>
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
    $("#load_itens_monitoramento").tabulator({
        height: "350px",
        fitColumns: true,
        ajaxURL: "_controller/_contrato_monitoramento.php",
        ajaxParams: {acao: "load_itens", id_contrato: "<?=$_GET['id'];?>"},
        pagination: "remote",
        paginationSize: 100,
        paginationDataSent: {"page": "pageNo"},
        columns: [
            {
                formatter: updateIcon, width: 40, align: "center", onClick: function (e, cell, val, data) {
                    carrega_pagina('contrato', 'update_itens_monitoramento.php?id='+data.contrato_monitoramento_itens_id+'&id_contrato='+data.contrato_monitoramento_itens_id_contrato);
                }
            },
            {title: "ID Contrato", field: "contrato_monitoramento_itens_id_contrato", sorter: "int", width: 120},
            {title: "Quantidade", field: "contrato_monitoramento_itens_quantidade", sorter: "string"},
            {title: "Descrição", field: "contrato_monitoramento_itens_descricao", sorter: "string"},
            {title: "Data", field: "contrato_monitoramento_itens_data", sorter: "string"},
            {title: "Valor Unitário", field: "contrato_monitoramento_itens_valor_unitario", sorter: "string"},
            {title: "Valor Total", field: "contrato_monitoramento_itens_valor_total", sorter: "string"}
        ]
    });
</script>