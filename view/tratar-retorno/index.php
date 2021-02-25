<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Tratar Retorno Bancário
                            </h3>
                        </div>
                        <div class="box-content">
                            <form name="formUpload" id="formUpload" method="post">
                                <div class="row">
                                    <div class="form-group col-lg-12">
                                        <label>Arquivo(TXT / RET)</label>
                                        <input type="file" name="arquivo" id="arquivo" size="45" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-4">
                                        <label>Tipo Arquivo</label>
                                        <select class="form-control tipo_arquivo_retorno">
                                            <option value="0">Sicoob 240</option>
                                            <option value="1">Sicoob 400</option>
                                            <option value="2">Caixa CobCaixa</option>
                                            <option value="3">Caixa eCobrança</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-4">
                                        <label>Caixa</label>
                                        <select class="form-control caixa_download"></select>
                                    </div>
                                    <div class="form-group col-lg-4">
                                        <label>Data Pagamento</label>
                                        <input type="date" class="form-control data_pagamento"/>
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
                $('.caixa_download').html(options).show();
                load_out();
            }
        });
        load_out();
    });
    function tratar_retorno(){
        var acao = "&acao=tratar_retorno";
        $.ajax({
            type: 'GET',
            url: "_controller/_tratar_retorno.php",
            data: acao,
            beforeSend: function (){
                $('#resposta').html('<div class="alert alert-success"><img src="_img/load.gif" class="load" /> <strong>Aguarde!</strong> Estou tentando ler o arquivo</div>');
            },
            success: function (data) {
                load_out();
                if(data.msg = 'OK'){
                    $('#resposta').html('<div class="alert alert-success"><strong>Parabéns!</strong> Suas baixas foram verificadas</div>');
                }
            }
        });
    }
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
                        tratar_retorno();
                        $('#porcentagem').html('0%'); 
                    }
                    else{
                        $('#resposta').html('<div class="alert alert-danger"><strong>Ops!</strong> '+data.msg+'</div>');
                        $('#porcentagem').html('0%'); 
                    }                
                },
                error : function(){
                    $('#resposta').html('<div class="alert alert-danger"><strong>Ops!</strong> Erro ao enviar requisição!!!</div>');
                    $('#porcentagem').html('0%'); 
                },
                dataType: 'json',
                url: '_controller/_tratar_retorno.php?acao=enviar&tipo_arquivo='+$(".tipo_arquivo_retorno").val()+"&id_caixa="+$(".caixa_download").val()+"&data_pagamento="+$(".data_pagamento").val(),
                resetForm: false
            }).submit();
        })
    });
</script>