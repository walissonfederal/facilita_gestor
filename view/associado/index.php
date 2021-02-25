<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Associado
                                <a href="javascript::" onclick="carrega_pagina('associado', 'create.php');" class="btn btn-primary">Cadastrar Novo</a>
                            </h3>
                        </div>
                        <div class="box-content">
                            <form action="">
                                <fieldset>
                                    <div class="row">
                                        <div class="form-group col-lg-1">
                                            <label>ID</label>
                                            <input type="text" class="form-control search_id"/>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>Nome Razão</label>
                                            <input type="text" class="form-control search_nome_razao"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Nome Fantasia</label>
                                            <input type="text" class="form-control search_nome_fantasia"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>CPF / CNPJ</label>
                                            <input type="text" class="form-control search_cpf_cnpj"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Status</label>
                                            <select class="form-control search_status">
                                                <option value=""></option>
                                                <option value="0">Ativo</option>
                                                <option value="1">Inativo</option>
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
                            <div id="load_associado"></div>
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
    var printIcon = function (value, data, cell, row, options) { //plain text value
        return "<i class='fa fa-print' style='vertical-align:middle; padding:2px 0;' title='Imprimir Envelope'></i> ";
    };
    $("#load_associado").tabulator({
        height: "350px",
        fitColumns: true,
        ajaxURL: "_controller/_associado.php",
        ajaxParams: {acao: "load"},
        pagination: "remote",
        paginationSize: 100,
        paginationDataSent: {"page": "pageNo"},
        columns: [
            {
                formatter: updateIcon, width: 40, align: "center", onClick: function (e, cell, val, data) {
                    carrega_pagina('associado', 'update.php?id='+data.associado_id);
                }
            },
            {title: "ID", field: "associado_id", sorter: "int", width: 75},
            {title: "Nome Razão", field: "associado_nome_razao", sorter: "string"},
            {title: "Nome Fantasia", field: "associado_nome_fantasia", sorter: "string"},
            {title: "CPF / CNPJ", field: "associado_cpf_cnpj", sorter: "string"},
            {title: "Telefone", field: "associado_telefone", sorter: "string"},
            {title: "Status", field: "associado_status_view", sorter: "string"}
        ]
    });
    
    function search(){
        var search_id             = $(".search_id").val();
        var search_status         = $(".search_status").val();
        var search_nome_razao     = $(".search_nome_razao").val();
        var search_nome_fantasia  = $(".search_nome_fantasia").val();
        var search_cpf_cnpj       = $(".search_cpf_cnpj").val();
        
        
        var acao = "acao=load&search=true&id="+search_id+"&nome_razao="+search_nome_razao+"&status="+search_status+"&nome_fantasia="+search_nome_fantasia+"&cpf_cnpj="+search_cpf_cnpj;
        
        $.ajax({
            type: 'GET',
            url: "_controller/_associado.php",
            data: acao,
            beforeSend: load_in(),
            success: function () {
                load_out();
                $("#load_associado").tabulator("setData", "_controller/_associado.php?acao=load");
            }
        });
    }
</script>