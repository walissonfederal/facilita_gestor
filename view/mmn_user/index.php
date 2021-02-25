<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Usuário
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
                                            <input type="text" class="form-control search_nome"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>RG</label>
                                            <input type="text" class="form-control search_rg"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>CPF</label>
                                            <input type="text" class="form-control search_cpf"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Status</label>
                                            <select class="form-control search_status">
                                                <option value=""></option>
                                                <option value="1">Ativo</option>
                                                <option value="0">Inativo</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Email</label>
                                            <input type="text" class="form-control search_email"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-2">
                                            <label>.</label><br />
                                            <button type="button" class="btn btn-primary" onclick="search();">Pesquisar</button>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                            <hr />
                            <div id="load_user_mmn"></div>
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
    var viewIcon = function (value, data, cell, row, options) { //plain text value
        return "<i class='fa fa-search' style='vertical-align:middle; padding:2px 0;' title='Anotações'></i> ";
    };
	var printIcon = function (value, data, cell, row, options) { //plain text value
        return "<i class='fa fa-print' style='vertical-align:middle; padding:2px 0;' title='Imprimir Contrato'></i> ";
    };
    $("#load_user_mmn").tabulator({
        height: "350px",
        fitColumns: true,
        ajaxURL: "_controller/_mmn_user.php",
        ajaxParams: {acao: "load"},
        pagination: "remote",
        paginationSize: 100,
        paginationDataSent: {"page": "pageNo"},
        columns: [
            {
                formatter: updateIcon, width: 40, align: "center", onClick: function (e, cell, val, data) {
                    carrega_pagina('mmn_user', 'update.php?id='+data.user_id);
                }
            },
            {
                formatter: viewIcon, width: 40, align: "center", onClick: function (e, cell, val, data) {
                    carrega_pagina('mmn_user', 'view.php?id='+data.user_id);
                }
            },
			{
                formatter: printIcon, width: 40, align: "center", onClick: function (e, cell, val, data) {
                    window.open('view/mmn_user/contrato/index.php?id='+data.user_id,'_blank');
                }
            },
            {title: "ID", field: "user_id", sorter: "int", width: 75},
            {title: "Nome", field: "user_nome", sorter: "string"},
            {title: "CPF", field: "user_cpf", sorter: "string"},
            {title: "RG", field: "user_rg", sorter: "string"},
            {title: "E-mail", field: "user_email", sorter: "string"},
            {title: "Telefone", field: "user_telefone", sorter: "string"},
            {title: "Status", field: "user_status", sorter: "string"}
        ]
    });
    
    function search(){
        var search_id           = $(".search_id").val();
        var search_status       = $(".search_status").val();
        var search_nome         = $(".search_nome").val();
        var search_rg           = $(".search_rg").val();
        var search_cpf          = $(".search_cpf").val();
        var search_email        = $(".search_email").val();
        
        
        var acao = "acao=load&search=true&id="+search_id+"&nome="+search_nome+"&status="+search_status+"&rg="+search_rg+"&cpf="+search_cpf+"&email="+search_email;
        
        $.ajax({
            type: 'GET',
            url: "_controller/_mmn_user.php",
            data: acao,
            beforeSend: load_in(),
            success: function () {
                load_out();
                $("#load_user_mmn").tabulator("setData", "_controller/_mmn_user.php?acao=load");
            }
        });
    }
</script>