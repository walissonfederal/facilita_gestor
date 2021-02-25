<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Financeiro
                                <a href="javascript::" onclick="carrega_pagina('financeiro', 'index.php?OP=<?=$_GET['OP'];?>');" class="btn btn-primary">Voltar</a>
                            </h3>
                        </div>
                        <div class="box-content">
                            <form action="">
                                <fieldset>
                                    <div class="row">
                                        <div class="form-group col-lg-3">
                                            <label>Descrição</label>
                                            <input type="text" class="form-control update_descricao"/>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>Data Vencimento</label>
                                            <input type="date" class="form-control update_data_vencimento"/>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>Valor</label>
                                            <input type="text" class="form-control update_valor" id="valor_1"/>
                                        </div>
                                        <input type="hidden" class="update_id"/>
                                        <div class="form-group col-lg-3">
                                            <label>.</label><br />
                                            <button type="button" class="btn btn-primary" onclick="update_varios();">Atualizar</button>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                            <hr />
                            <div id="load_financeiro"></div>
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
    $("#load_financeiro").tabulator({
        height: "350px",
        fitColumns: true,
        ajaxURL: "_controller/_financeiro.php",
        ajaxParams: {acao: "load_financeiro_update", contas: "<?=$_GET['id'];?>"},
        pagination: "remote",
        paginationSize: 100,
        paginationDataSent: {"page": "pageNo"},
        columns: [
            {
                formatter: updateIcon, width: 40, align: "center", onClick: function (e, cell, val, data) {
                    load_update(data.financeiro_id);
                }
            },
            {title: "ID", field: "financeiro_codigo", sorter: "int", width: 200},
            {title: "Descrição", field: "financeiro_descricao", sorter: "string"},
            {title: "Data Vencimento", field: "financeiro_data_vencimento", sorter: "string"},
            {title: "Valor", field: "financeiro_valor", sorter: "string"}
        ]
    });
    function update_varios(){
        var descricao = $(".update_descricao").val();
        var id = $(".update_id").val();
        var data_vencimento = $(".update_data_vencimento").val();
        var valor = $(".update_valor").val();
        
        var acao = "&acao=update_varios&descricao="+descricao+"&id="+id+"&data_vencimento="+data_vencimento+"&valor="+valor;
        
        $.ajax({
            type: 'POST',
            url: "_controller/_financeiro.php",
            data: acao,
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
                    $("#load_financeiro").tabulator("setData", "_controller/_financeiro.php?acao=load_financeiro_update&contas=<?=$_GET['id'];?>&OP=<?=$_GET['OP'];?>");
                }
            }
        });
    }
    function load_update(id){
        var acao = "acao=load_update&id="+id;
        $.ajax({
            type: 'POST',
            url: "_controller/_financeiro.php",
            data: acao,
            beforeSend: load_in(),
            async: false,
            success: function (data) {
                load_out();
                var data_return = jQuery.parseJSON(data);
                if(data_return.type === 'error'){
                    $("#_modal").modal('show');
                    $("#title_modal").html(data_return.title);
                    $("#texto_modal").html(data_return.msg);
                    $("#buttons_modal").html(data_return.buttons);
                }else{
                    $(".update_id").val(data_return[0].financeiro_id);
                    $(".update_data_vencimento").val(data_return[0].financeiro_data_vencimento);
                    $(".update_valor").val(data_return[0].financeiro_valor);
                    $(".update_descricao").val(data_return[0].financeiro_descricao);
                }
            }
        });
    }
    $("#valor_1").maskMoney({thousands:'', decimal:'.'});
</script>