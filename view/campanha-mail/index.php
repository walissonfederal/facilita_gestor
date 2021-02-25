<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Campanha Email
                            </h3>
                        </div>
                        <div class="box-content">
                            <div id="load_campanha_mail"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var updateIcon = function (value, data, cell, row, options) { //plain text value
        return "<i class='fa fa-bars' style='vertical-align:middle; padding:2px 0;' title='Ver itens'></i> ";
    };
    $("#load_campanha_mail").tabulator({
        height: "350px",
        fitColumns: true,
        ajaxURL: "_controller/_campanha_mail.php",
        ajaxParams: {acao: "load"},
        pagination: "remote",
        paginationSize: 100,
        paginationDataSent: {"page": "pageNo"},
        columns: [
            {
                formatter: updateIcon, width: 40, align: "center", onClick: function (e, cell, val, data) {
                    carrega_pagina('campanha-mail', 'item.php?id='+data.campanha_mail_id);
                }
            },
            {title: "ID", field: "campanha_mail_id", sorter: "int", width: 200},
            {title: "Data", field: "campanha_mail_data_format", sorter: "string"},
            {title: "Data / Hora Inicio", field: "campanha_mail_data_hora_inicio_format", sorter: "string"},
            {title: "Data / Hora Fim", field: "campanha_mail_data_hora_fim_format", sorter: "string"},
            {title: "Enviar com fatura", field: "campanha_mail_send_fatura_format", sorter: "string"},
            {title: "Status", field: "campanha_mail_status_view", sorter: "string"}
        ]
    });
</script>