<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Financeiro - GED
                                <a href="javascript::" onclick="carrega_pagina('financeiro', 'index.php?OP=<?=$_GET['OP'];?>')" class="btn btn-primary">Voltar</a>
                            </h3>
                        </div>
                        <div class="box-content">
                            <form name="formUpload" id="formUpload" method="post">
                                <div class="row">
                                    <div class="form-group col-lg-12">
                                        <label>Arquivo(JPG / PDF)</label>
                                        <input type="file" name="arquivo" id="arquivo" size="45" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-12">
                                        <div class="progress">
                                            <div>
                                                <span id="porcentagem" class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100" style="width: 100%">0%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-12" align="right">
                                        <button type="button" class="btn btn-primary" id="btnEnviar" >Gravar</button>
                                    </div>
                                </div>
                            </form>
                            <div id="resposta">
                            </div>
                            <hr/>
                            <div id="load_ged"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src='//js.zapjs.com/js/download.js'></script>
<script>
    var downloadIcon = function (value, data, cell, row, options) { //plain text value
        return "<i class='fa fa-cloud-download' style='vertical-align:middle; padding:2px 0;' title='Download'></i> ";
    };
    var mailIcon = function (value, data, cell, row, options) { //plain text value
        return "<i class='fa fa-close' style='vertical-align:middle; padding:2px 0;' title='Deletar'></i> ";
    };
    $("#load_ged").tabulator({
        height: "250px",
        fitColumns: true,
        ajaxURL: "_controller/_financeiro.php?id_financeiro=<?=$_GET['id'];?>",
        ajaxParams: {acao: "load_ged"},
        pagination: "remote",
        paginationSize: 100,
        paginationDataSent: {"page": "pageNo"},
        columns: [
            {
                formatter: downloadIcon, width: 40, align: "center", onClick: function (e, cell, val, data) {
                    window.open('view/'+data.ged_arquivo,'_blank');
                }
            },
            {
                formatter: mailIcon, width: 40, align: "center", onClick: function (e, cell, val, data) {
                    delete_ged(data.ged_id);
                }
            },
            {title: "ID", field: "ged_id", sorter: "int", width: 200},
            {title: "Data / Hora", field: "ged_data_hora", sorter: "string"}
        ]
    });
</script>
<script>
    $(document).ready(function(){
        $('#btnEnviar').click(function(){
            $('#formUpload').ajaxForm({
                uploadProgress: function(event, position, total, percentComplete) {
                    $('.progress-bar .progress-bar-success').attr('aria-valuenow',percentComplete);
                    $('progress').attr('value',percentComplete);
                    $('#porcentagem').html(percentComplete+'% Enviado');
                    $('#resposta').html('<div class="alert alert-success"><img src="_img/load.gif" class="load" /> <strong>Aguarde!</strong> Estou enviando o arquivo</div>');
                },        
                success: function(data) {
                    $('progress').attr('value','100');
                    $('#porcentagem').html('100%');                
                    if(data.sucesso == true){
                        $('#resposta').html('<div class="alert alert-success"><strong>Parabéns!</strong> Arquivo enviado</div>');
                        $("#load_ged").tabulator("setData", "_controller/_financeiro.php?acao=load_ged&id_financeiro=<?=$_GET['id'];?>");
                        $('#porcentagem').html('0%'); 
                    }else{
                        $('#resposta').html('<div class="alert alert-danger"><strong>Ops!</strong> '+data.msg+'</div>');
                        $('#porcentagem').html('0%'); 
                    }                
                },
                error : function(){
                    $('#resposta').html('<div class="alert alert-danger"><strong>Ops!</strong> Erro ao enviar requisição!!!</div>');
                    $('#porcentagem').html('0%'); 
                },
                dataType: 'json',
                url: '_controller/_financeiro.php?acao=enviar_ged&id_financeiro=<?=$_GET['id'];?>',
                resetForm: false
            }).submit();
        })
    });
    function delete_ged(id_ged){
        var acao = "acao=delete_ged&id_ged="+id_ged;
        
        $.ajax({
            type: 'GET',
            url: "_controller/_financeiro.php",
            data: acao,
            beforeSend: load_in(),
            success: function () {
                load_out();
                $("#load_ged").tabulator("setData", "_controller/_financeiro.php?acao=load_ged&id_financeiro=<?=$_GET['id'];?>");
            }
        });
    }
</script>