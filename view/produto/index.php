<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Produto
                                <a href="javascript::" onclick="carrega_pagina('produto', 'create.php');" class="btn btn-primary">Cadastrar Novo</a>
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
                                            <label>.</label><br />
                                            <button type="button" class="btn btn-primary" onclick="search();">Pesquisar</button>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                            <hr />
                            <div id="load_produto"></div>
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
    $("#load_produto").tabulator({
        height: "350px",
        fitColumns: true,
        ajaxURL: "_controller/_produto.php",
        ajaxParams: {acao: "load"},
        pagination: "remote",
        paginationSize: 50,
        paginationDataSent: {"page": "pageNo"},
        columns: [
            {
                formatter: updateIcon, width: 40, align: "center", onClick: function (e, cell, val, data) {
                    carrega_pagina('produto', 'update.php?id='+data.produto_id);
                }
            },
            {title: "ID", field: "produto_id", sorter: "int", width: 200},
            {title: "Descrição", field: "produto_descricao", sorter: "string"},
            {title: "Categoria", field: "categoria_descricao"},
            {title: "Subcategoria", field: "sub_categoria_descricao"},
            {title: "Preço Venda", field: "produto_preco_venda", sorter: "string"},
            {title: "Estoque", field: "produto_estoque_atual", sorter: "string"}
        ]
    });
    
    function search(){
        var search_id           = $(".search_id").val();
        var search_descricao    = $(".search_descricao").val();
        
        var acao = "acao=load&search=true&id="+search_id+"&descricao="+search_descricao;
        
        $.ajax({
            type: 'GET',
            url: "_controller/_produto.php",
            data: acao,
            beforeSend: load_in(),
            success: function () {
                load_out();
                $("#load_produto").tabulator("setData", "_controller/_produto.php?acao=load");
            }
        });
    }
</script>