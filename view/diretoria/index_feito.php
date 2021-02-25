<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Diretoria - Feito
                                <a href="javascript::" onclick="carrega_pagina('diretoria', 'create_feito.php?id_diretoria=<?=$_GET['id_diretoria'];?>');" class="btn btn-primary">Cadastrar Novo</a>
                                <a href="javascript::" onclick="carrega_pagina('diretoria', 'index.php');" class="btn btn-default">Voltar</a>
                            </h3>
                        </div>
                        <div class="box-content">
                            <div id="load_diretoria_feito"></div>
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
    $("#load_diretoria_feito").tabulator({
        height: "350px",
        fitColumns: true,
        ajaxURL: "_controller/_diretoria_feito.php",
        ajaxParams: {acao: "load", id_diretoria: <?=$_GET['id_diretoria'];?>},
        pagination: "remote",
        paginationSize: 100,
        paginationDataSent: {"page": "pageNo"},
        columns: [
            {
                formatter: updateIcon, width: 40, align: "center", onClick: function (e, cell, val, data) {
                    carrega_pagina('diretoria', 'update_feito.php?id_diretoria='+data.id_diretoria+'&id='+data.feito_diretoria_id);
                }
            },
            {title: "Diretoria", field: "feito_diretoria_id_diretoria", sorter: "int", width: 200},
            {title: "Data", field: "feito_diretoria_data", sorter: "string"},
            {title: "Descrição", field: "feito_diretoria_descricao", sorter: "string"}
        ]
    });
</script>