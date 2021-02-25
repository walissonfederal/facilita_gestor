<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Nível
                                <a href="javascript::" onclick="carrega_pagina('nivel', 'create.php');" class="btn btn-primary">Cadastrar Novo</a>
                            </h3>
                        </div>
                        <div class="box-content">
                            <form action="">
                                <fieldset>
                                    <div class="row">
                                        <div class="form-group col-lg-3">
                                            <label>ID</label>
                                            <input type="text" class="form-control search_id"/>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>Descrição</label>
                                            <input type="text" class="form-control search_descricao"/>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>Status</label>
                                            <select class="form-control search_status">
                                                <option value=""></option>
                                                <option value="0">Ativo</option>
                                                <option value="1">Inativo</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>.</label><br />
                                            <button type="button" class="btn btn-primary" onclick="search();">Pesquisar</button>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                            <hr />
                            <div id="load_nivel"></div>
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
    var menuIcon = function (value, data, cell, row, options) { //plain text value
        return "<i class='fa fa-navicon' style='vertical-align:middle; padding:2px 0;' title='Permissão dos menus'></i> ";
    };
    var pageIcon = function (value, data, cell, row, options) { //plain text value
        return "<i class='fa fa-lock' style='vertical-align:middle; padding:2px 0;' title='Permissão das páginas'></i> ";
    };
    $("#load_nivel").tabulator({
        height: "350px",
        fitColumns: true,
        ajaxURL: "_controller/_nivel.php",
        ajaxParams: {acao: "load"},
        pagination: "remote",
        paginationSize: 100,
        paginationDataSent: {"page": "pageNo"},
        columns: [
            {
                formatter: updateIcon, width: 40, align: "center", onClick: function (e, cell, val, data) {
                    carrega_pagina('nivel', 'update.php?id='+data.nivel_id);
                }
            },
            {
                formatter: menuIcon, width: 40, align: "center", onClick: function (e, cell, val, data) {
                    carrega_pagina('nivel', 'menu.php?id='+data.nivel_id);
                }
            },
            {
                formatter: pageIcon, width: 40, align: "center", onClick: function (e, cell, val, data) {
                    carrega_pagina('nivel', 'pagina.php?id='+data.nivel_id);
                }
            },
            {title: "ID", field: "nivel_id", sorter: "int", width: 200},
            {title: "Descrição", field: "nivel_descricao", sorter: "string"},
            {title: "Status", field: "nivel_status_view", sorter: "string"}
        ]
    });
    
    function search(){
        var search_id           = $(".search_id").val();
        var search_descricao    = $(".search_descricao").val();
        var search_status       = $(".search_status").val();
        
        var acao = "acao=load&search=true&id="+search_id+"&descricao="+search_descricao+"&status="+search_status;
        
        $.ajax({
            type: 'GET',
            url: "_controller/_nivel.php",
            data: acao,
            beforeSend: load_in(),
            success: function () {
                load_out();
                $("#load_nivel").tabulator("setData", "_controller/_nivel.php?acao=load");
            }
        });
    }
</script>