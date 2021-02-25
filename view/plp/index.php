<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                PLP
                                <a href="javascript::" onclick="carrega_pagina('plp', 'create.php');" class="btn btn-primary">ABRIR PLP</a>
								<a href="javascript::" onclick="carrega_pagina('plp', 'close.php');" class="btn btn-primary">FECHAR PLP</a>
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
                                            <label>.</label><br />
                                            <button type="button" class="btn btn-primary" onclick="search();">Pesquisar</button>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                            <hr />
                            <div id="load_plp"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var updateIcon = function (value, data, cell, row, options) { //plain text value
        return "<i class='fa fa-info' style='vertical-align:middle; padding:2px 0;' title='Ver Informações'></i> ";
    };
    $("#load_plp").tabulator({
        height: "350px",
        fitColumns: true,
        ajaxURL: "_controller/_plp.php",
        ajaxParams: {acao: "load"},
        pagination: "remote",
        paginationSize: 100,
        paginationDataSent: {"page": "pageNo"},
        columns: [
            {
                formatter: updateIcon, width: 40, align: "center", onClick: function (e, cell, val, data) {
                    carrega_pagina('plp', 'insert.php?id='+data.plp_id);
                }
            },
            {title: "ID", field: "plp_id", sorter: "int", width: 75},
            {title: "TIPO", field: "plp_tipo", sorter: "string"},
            {title: "STATUS", field: "plp_status", sorter: "string"},
			{title: "DATA HORA", field: "plp_data_hora", sorter: "string"},
			{title: "DATA HORA FIM", field: "plp_data_hora_fim", sorter: "string"},
			{title: "ID CORREIOS", field: "plp_id_correios", sorter: "string"},
        ]
    });
    
    function search(){
        var search_id           = $(".search_id").val();
        
        var acao = "acao=load&search=true&id="+search_id;
        
        $.ajax({
            type: 'GET',
            url: "_controller/_plp.php",
            data: acao,
            beforeSend: load_in(),
            success: function () {
                load_out();
                $("#load_plp").tabulator("setData", "_controller/_plp.php?acao=load");
            }
        });
    }
</script>