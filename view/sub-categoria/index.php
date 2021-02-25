<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Subcategoria
                                <a href="javascript::" onclick="carrega_pagina('sub-categoria', 'create.php');" class="btn btn-primary">Cadastrar Novo</a>
                            </h3>
                        </div>
                        <div class="box-content">
                            <form action="">
                                <fieldset>
                                    <div class="row">
                                        <div class="form-group col-lg-2">
                                            <label>ID</label>
                                            <input type="text" class="form-control search_id"/>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>Descrição</label>
                                            <input type="text" class="form-control search_descricao"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Status</label>
                                            <select class="form-control search_status">
                                                <option value=""></option>
                                                <option value="0">Ativo</option>
                                                <option value="1">Inativo</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>Categoria</label>
                                            <select class="form-control search_id_categoria">
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>.</label><br />
                                            <button type="button" class="btn btn-primary" onclick="search();">Pesquisar</button>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                            <hr />
                            <div id="load_sub_categoria"></div>
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
    $("#load_sub_categoria").tabulator({
        height: "350px",
        fitColumns: true,
        ajaxURL: "_controller/_sub_categoria.php",
        ajaxParams: {acao: "load"},
        pagination: "remote",
        paginationSize: 100,
        paginationDataSent: {"page": "pageNo"},
        columns: [
            {
                formatter: updateIcon, width: 40, align: "center", onClick: function (e, cell, val, data) {
                    carrega_pagina('sub-categoria', 'update.php?id='+data.sub_categoria_id);
                }
            },
            {title: "ID", field: "sub_categoria_id", sorter: "int", width: 200},
            {title: "Categoria", field: "sub_categoria_descricao_categoria", sorter: "string", width: 200},
            {title: "Descrição", field: "sub_categoria_descricao", sorter: "string"},
            {title: "Status", field: "sub_categoria_status_view", sorter: "string"}
        ]
    });
    
    function search(){
        var search_id           = $(".search_id").val();
        var search_descricao    = $(".search_descricao").val();
        var search_status       = $(".search_status").val();
        var search_id_categoria = $(".search_id_categoria").val();
        
        var acao = "acao=load&search=true&id="+search_id+"&descricao="+search_descricao+"&status="+search_status+"&id_categoria="+search_id_categoria;
        
        $.ajax({
            type: 'GET',
            url: "_controller/_sub_categoria.php",
            data: acao,
            beforeSend: load_in(),
            success: function () {
                load_out();
                $("#load_sub_categoria").tabulator("setData", "_controller/_sub_categoria.php?acao=load");
            }
        });
    }
    $(function(){
        var acao = "acao=load_categoria";
        $.ajax({
            type: 'GET',
            url: "_controller/_sub_categoria.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                var data_return = jQuery.parseJSON(data);
                var options = '<option value=""></option>';
                for (var i = 0; i < data_return.data.length; i++) {
                    options += '<option value="' + data_return.data[i].categoria_id + '">' + data_return.data[i].categoria_descricao + '</option>';
                }
                $('.search_id_categoria').html(options).show();
                load_out();
            }
        });
        load_out();
    });
</script>