<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Usuários
                                <a href="javascript::" onclick="carrega_pagina('user', 'create.php');" class="btn btn-primary">Cadastrar Novo</a>
                            </h3>
                        </div>
                        <div class="box-content">
                            <div id="load_user"></div>
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
    $("#load_user").tabulator({
        height: "350px",
        fitColumns: true,
        ajaxURL: "_controller/_user.php",
        ajaxParams: {acao: "load"},
        pagination: "remote",
        paginationSize: 100,
        paginationDataSent: {"page": "pageNo"},
        columns: [
            {
                formatter: updateIcon, width: 40, align: "center", onClick: function (e, cell, val, data) {
                    carrega_pagina('user', 'update.php?id='+data.user_id);
                }
            },
            {title: "ID", field: "user_id", sorter: "int", width: 200},
            {title: "Nome", field: "user_nome", sorter: "string"},
            {title: "Login", field: "user_login", sorter: "string"},
            {title: "Email", field: "user_email", sorter: "string"}
        ]
    });
</script>