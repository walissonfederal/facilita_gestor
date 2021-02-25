<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Pessoa
                                <a href="javascript::" onclick="carrega_pagina('pessoa', 'create.php');" class="btn btn-primary">Cadastrar Novo</a>
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
                                            <label>Nome</label>
                                            <input type="text" class="form-control search_nome"/>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>Email</label>
                                            <input type="text" class="form-control search_email"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>.</label><br />
                                            <button type="button" class="btn btn-primary" onclick="search();">Pesquisar</button>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                            <hr />
                            <div id="load_pessoa"></div>
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
    $("#load_pessoa").tabulator({
        height: "350px",
        fitColumns: true,
        ajaxURL: "_controller/_pessoa.php",
        ajaxParams: {acao: "load"},
        pagination: "remote",
        paginationSize: 100,
        paginationDataSent: {"page": "pageNo"},
        columns: [
            {
                formatter: updateIcon, width: 40, align: "center", onClick: function (e, cell, val, data) {
                    carrega_pagina('pessoa', 'update.php?id='+data.pessoa_id);
                }
            },
            {title: "ID", field: "pessoa_id", sorter: "int", width: 200},
            {title: "Nome", field: "pessoa_nome", sorter: "string"},
            {title: "Email", field: "pessoa_email", sorter: "string"},
            {title: "Telefone", field: "pessoa_telefone", sorter: "string"},
            {title: "Celular", field: "pessoa_celular", sorter: "string"}
        ]
    });
    
    function search(){
        var search_id    = $(".search_id").val();
        var search_nome  = $(".search_nome").val();
        var search_email = $(".search_email").val();
        
        var acao = "acao=load&search=true&id="+search_id+"&nome="+search_nome+"&email="+search_email;
        
        $.ajax({
            type: 'GET',
            url: "_controller/_pessoa.php",
            data: acao,
            beforeSend: load_in(),
            success: function () {
                load_out();
                $("#load_pessoa").tabulator("setData", "_controller/_pessoa.php?acao=load");
            }
        });
    }
</script>