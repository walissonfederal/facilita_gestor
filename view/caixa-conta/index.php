<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Caixa / Conta
                            </h3>
                        </div>
                        <div class="box-content">
                            <form action="">
                                <fieldset>
                                    <div class="row">
                                        <div class="form-group col-lg-3">
                                            <label>Data Inicial</label>
                                            <input type="date" class="form-control search_data_inicial"/>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>Data Final</label>
                                            <input type="date" class="form-control search_data_final"/>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>Caixa</label>
                                            <select class="form-control search_id_caixa"></select>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>.</label><br />
                                            <button type="button" class="btn btn-primary" onclick="search();">Pesquisar</button>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                            <hr />
                            <div id="load_caixa_conta"></div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="box box-color blue box-condensed box-bordered">
                                        <div class="box-title" align="center">
                                            <h3>
                                                Saldo anterior
                                            </h3>
                                        </div>
                                        <div class="box-content">
                                            <div id="caixa_conta_saldo_anterior" align="center"><h4></h4></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="box box-color blue box-condensed box-bordered">
                                        <div class="box-title" align="center">
                                            <h3>
                                                Entradas no período
                                            </h3>
                                        </div>
                                        <div class="box-content">
                                            <div id="caixa_conta_entradas_periodo" align="center"><h4></h4></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="box box-color blue box-condensed box-bordered">
                                        <div class="box-title" align="center">
                                            <h3>
                                                Saídas no período
                                            </h3>
                                        </div>
                                        <div class="box-content">
                                            <div id="caixa_conta_saidas_periodo" align="center"><h4></h4></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="box box-color blue box-condensed box-bordered">
                                        <div class="box-title" align="center">
                                            <h3>
                                                Saldo final
                                            </h3>
                                        </div>
                                        <div class="box-content">
                                            <div id="caixa_conta_saldo_final" align="center"><h4></h4></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        var acao = "acao=load_caixa";
        $.ajax({
            type: 'GET',
            url: "_controller/_caixa.php",
            data: acao,
            beforeSend: load_in(),
            async: false,
            success: function (data) {
                var data_return = jQuery.parseJSON(data);
                var options = '<option value=""></option>';
                for (var i = 0; i < data_return.data.length; i++) {
                    options += '<option value="' + data_return.data[i].caixa_id + '">' + data_return.data[i].caixa_descricao + '</option>';
                }
                $('.search_id_caixa').html(options).show();
                load_out();
            }
        });
        load_out();
    });
    var updateIcon = function (value, data, cell, row, options) { //plain text value
        return "<i class='fa fa-pencil' style='vertical-align:middle; padding:2px 0;' title='Editar'></i> ";
    };
    $("#load_caixa_conta").tabulator({
        height: "350px",
        fitColumns: true,
        ajaxURL: "_controller/_caixa_conta.php",
        ajaxParams: {acao: "load"},
        pagination: "remote",
        paginationSize: 100,
        paginationDataSent: {"page": "pageNo"},
        columns: [
            {
                formatter: updateIcon, width: 40, align: "center", onClick: function (e, cell, val, data) {
                    carrega_pagina('caixa-conta', 'update.php?id='+data.caixa_conta_id)
                }
            },
            {title: "Data Lançamento", field: "caixa_conta_data_lancamento", sorter: "string", width: 200},
            {title: "Valor Lançamento", field: "caixa_conta_valor_lancamento", sorter: "string"},
            {title: "Tipo Lançamento", field: "caixa_conta_tipo_lancamento", sorter: "string"},
            {title: "Descrição", field: "caixa_conta_descricao", sorter: "string"}
        ],
        ajaxResponse:function(url, params, response){
            //url - the URL of the request
            //params - the parameters passed with the request
            //response - the JSON object returned in the body of the response.

            //set max and min values
            $("#caixa_conta_saldo_anterior").html('<h4>'+response.saldo_anterior+'</h4>');
            $("#caixa_conta_entradas_periodo").html('<h4>R$ '+response.entradas_periodo+'</h4>');
            $("#caixa_conta_saidas_periodo").html('<h4>'+response.saidas_periodo+'</h4>');
            $("#caixa_conta_saldo_final").html('<h4>R$ '+response.saldo_final+'</h4>');

            return response; //return the response data to tabulator (you MUST include this bit)
        }
    });
    
    function search(){
        var search_data_inicial   = $(".search_data_inicial").val();
        var search_data_final     = $(".search_data_final").val();
        var search_id_caixa       = $(".search_id_caixa").val();
        
        var acao = "acao=load&search=true&data_inicial="+search_data_inicial+"&data_final="+search_data_final+"&id_caixa="+search_id_caixa;
        
        $.ajax({
            type: 'GET',
            url: "_controller/_caixa_conta.php",
            data: acao,
            beforeSend: load_in(),
            success: function () {
                load_out();
                $("#load_caixa_conta").tabulator("setData", "_controller/_caixa_conta.php?acao=load");
            }
        });
    }
</script>