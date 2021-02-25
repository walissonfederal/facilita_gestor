<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Fluxo Financeiro
                                <a href="javascript::" onclick="carrega_pagina('caixa-conta', 'index.php');" class="btn btn-primary">Voltar</a>
                            </h3>
                        </div>
                        <div class="box-content">
                            <form action="">
                                <fieldset>
                                    <div class="row">
                                        <div class="form-group col-lg-4">
                                            <label>Data Inicial</label>
                                            <input type="date" class="form-control search_data_inicial"/>
                                        </div>
                                        <div class="form-group col-lg-4">
                                            <label>Data Final</label>
                                            <input type="date" class="form-control search_data_final"/>
                                        </div>
                                        <div class="form-group col-lg-4">
                                            <label>Caixa</label>
                                            <select class="form-control search_id_caixa"></select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-12" align="right">
                                            <button type="button" class="btn btn-primary" onclick="search();">Gerar Pesquisa</button>
                                            <button type="button" class="btn btn-primary" onclick="gerar_pdf();">Gerar PDF</button>
                                            <button type="button" class="btn btn-primary" onclick="gerar_excel();">Gerar EXCEL</button>
                                            <a href="javascript::" onclick="carrega_pagina('caixa-conta', 'index.php')" class="btn btn-danger">Cancelar</a>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
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
    function gerar_excel(){
        window.open('_controller/_caixa_conta.php?acao=gerar_excel', '_blank');
    }
    function gerar_pdf(){
        window.open('_reports/_caixa_conta/report.php', '_blank');
    }
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
            }
        });
    }
</script>