<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Notificações
                            </h3>
                        </div>
                        <div class="box-content">
                            <div id="load_notificacao"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var updateIcon = function (value, data, cell, row, options) { //plain text value
        return "<i class='fa fa-envelope' style='vertical-align:middle; padding:2px 0;' title='Abrir notificação'></i> ";
    };
    $("#load_notificacao").tabulator({
        height: "350px",
        fitColumns: true,
        ajaxURL: "_controller/_mmn_notificacao.php",
        ajaxParams: {acao: "load"},
        pagination: "remote",
        paginationSize: 100,
        paginationDataSent: {"page": "pageNo"},
        columns: [
            {
                formatter: updateIcon, width: 40, align: "center", onClick: function (e, cell, val, data) {
                    carrega_pagina('mmn_notificacao', 'view.php?id='+data.notificacao_id);
                }
            },
            {title: "ID", field: "notificacao_id", sorter: "int", width: 75},
            {title: "Titulo", field: "notificacao_titulo", sorter: "string"},
            {title: "Status", field: "notificacao_status", sorter: "string"}
        ]
    });
</script>