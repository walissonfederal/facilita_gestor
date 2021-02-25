<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Usuários
                                <a href="javascript::" onclick="carrega_pagina('mmn_user', 'index.php');" class="btn btn-primary">Voltar</a>
                            </h3>
                        </div>
                        <div class="box-content">
                            <form action="" id="create">
                                <fieldset>
                                    <div class="row">
                                        <div class="form-group col-lg-12">
                                            <label>Descrição</label>
                                            <textarea name="anotacao_texto" class="form-control anotacao_texto" cols="4" rows="4"></textarea>
                                            <input type="hidden" name="anotacao_id_user" value="<?php echo $_GET['id'];?>"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-12" align="right">
                                            <button type="button" class="btn btn-primary" onclick="create();">Gravar</button>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                            <hr />
                            <div id="load_anotacoes"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var viewIcon = function (value, data, cell, row, options) { //plain text value
        return "<i class='fa fa-search' style='vertical-align:middle; padding:2px 0;' title='Editar'></i> ";
    };
    $("#load_anotacoes").tabulator({
        height: "350px",
        fitColumns: true,
        ajaxURL: "_controller/_mmn_user.php",
        ajaxParams: {acao: "load_anotacoes", id_user: "<?php echo $_GET['id'];?>"},
        pagination: "remote",
        paginationSize: 100,
        paginationDataSent: {"page": "pageNo"},
        columns: [
            {
                formatter: viewIcon, width: 40, align: "center", onClick: function (e, cell, val, data) {
                    $("#_modal").modal('show');
                    $("#title_modal").html('Anotação: '+data.anotacao_id+' - '+data.anotacao_data_hora);
                    $("#texto_modal").html(data.anotacao_texto);
                    $("#buttons_modal").html('');
                }
            },
            {title: "ID", field: "anotacao_id", sorter: "int", width: 75},
            {title: "Data Hora", field: "anotacao_data_hora", sorter: "string"},
            {title: "Descrição", field: "anotacao_texto", sorter: "string"}
        ]
    });
    function create(){
        var dados = $("#create").serialize();
        var acao = "&acao=create_anotacao";
        
        $.ajax({
            type: 'POST',
            url: "_controller/_mmn_user.php",
            data: dados+acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                var data_return = jQuery.parseJSON(data);
                if(data_return.type === 'error'){
                    $("#_modal").modal('show');
                    $("#title_modal").html(data_return.title);
                    $("#texto_modal").html(data_return.msg);
                    $("#buttons_modal").html(data_return.buttons);
                }else{
                    carrega_pagina('mmn_user', 'view.php?id=<?php echo $_GET['id'];?>');
                }
            }
        });
    }
</script>