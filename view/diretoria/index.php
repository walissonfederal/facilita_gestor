<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Diretoria
                                <a href="javascript::" onclick="carrega_pagina('diretoria', 'create.php');" class="btn btn-primary">Cadastrar Novo</a>
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
                                            <label>Período</label>
                                            <select class="form-control search_id_periodo"></select>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>.</label><br />
                                            <button type="button" class="btn btn-primary" onclick="search();">Pesquisar</button>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                            <hr />
                            <div id="load_diretoria"></div>
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
    var personIcon = function (value, data, cell, row, options) { //plain text value
        return "<i class='fa fa-user' style='vertical-align:middle; padding:2px 0;' title='Pessoas'></i> ";
    };
    var madeIcon = function (value, data, cell, row, options) { //plain text value
        return "<i class='fa fa-cog' style='vertical-align:middle; padding:2px 0;' title='Feitos da diretoria'></i> ";
    };
    $("#load_diretoria").tabulator({
        height: "350px",
        fitColumns: true,
        ajaxURL: "_controller/_diretoria.php",
        ajaxParams: {acao: "load"},
        pagination: "remote",
        paginationSize: 100,
        paginationDataSent: {"page": "pageNo"},
        columns: [
            {
                formatter: updateIcon, width: 40, align: "center", onClick: function (e, cell, val, data) {
                    carrega_pagina('diretoria', 'update.php?id='+data.diretoria_id);
                }
            },
            {
                formatter: personIcon, width: 40, align: "center", onClick: function (e, cell, val, data) {
                    carrega_pagina('diretoria', 'index_pessoa.php?id_diretoria='+data.diretoria_id);
                }
            },
            {
                formatter: madeIcon, width: 40, align: "center", onClick: function (e, cell, val, data) {
                    carrega_pagina('diretoria', 'index_feito.php?id_diretoria='+data.diretoria_id);
                }
            },
            {title: "ID", field: "diretoria_id", sorter: "int", width: 200},
            {title: "Descrição", field: "diretoria_descricao", sorter: "string"},
            {title: "Período", field: "diretoria_id_periodo", sorter: "string"}
        ]
    });
    
    function search(){
        var search_id           = $(".search_id").val();
        var search_descricao    = $(".search_descricao").val();
        var search_id_periodo   = $(".search_id_periodo").val();
        
        var acao = "acao=load&search=true&id="+search_id+"&descricao="+search_descricao+"&id_periodo="+search_id_periodo;
        
        $.ajax({
            type: 'GET',
            url: "_controller/_diretoria.php",
            data: acao,
            beforeSend: load_in(),
            success: function () {
                load_out();
                $("#load_diretoria").tabulator("setData", "_controller/_diretoria.php?acao=load");
            }
        });
    }
    
    $(function(){
        var acao = "acao=load_periodo";
        $.ajax({
            type: 'GET',
            url: "_controller/_periodo.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                var data_return = jQuery.parseJSON(data);
                var options = '<option value=""></option>';
                for (var i = 0; i < data_return.data.length; i++) {
                    options += '<option value="' + data_return.data[i].periodo_id + '">' + data_return.data[i].periodo_descricao + '</option>';
                }
                $('.search_id_periodo').html(options).show();
                load_out();
            }
        });
        load_out();
    });
</script>