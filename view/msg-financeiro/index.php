<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Mensagem Financeiro
                                <a href="javascript::" onclick="carrega_pagina('msg-financeiro', 'create.php');" class="btn btn-primary">Cadastrar Novo</a>
                            </h3>
                        </div>
                        <div class="box-content">
                            <div id="load_msg_financeiro"></div>
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
    $("#load_msg_financeiro").tabulator({
        height: "350px",
        fitColumns: true,
        ajaxURL: "_controller/_msg_financeiro.php",
        ajaxParams: {acao: "load"},
        pagination: "remote",
        paginationSize: 100,
        paginationDataSent: {"page": "pageNo"},
        columns: [
            {
                formatter: updateIcon, width: 40, align: "center", onClick: function (e, cell, val, data) {
                    carrega_pagina('msg-financeiro', 'update.php?id='+data.msg_financeiro_id);
                }
            },
            {title: "ID", field: "msg_financeiro_id", sorter: "int", width: 200},
            {title: "Descrição", field: "msg_financeiro_assunto", sorter: "string"},
            {title: "Status", field: "msg_financeiro_status_view", sorter: "string"}
        ]
    });
</script>