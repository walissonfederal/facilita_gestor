<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Importar SPC
                            </h3>
                        </div>
                        <div class="box-content">
                            <form name="formUpload" id="formUpload" method="post">
                                <div class="row">
                                    <div class="form-group col-lg-12">
                                        <label>Arquivo(TXTP)</label>
                                        <input type="file" name="arquivo" id="arquivo" size="45" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-3">
                                        <label>Plano de Contas</label>
                                        <select class="form-control financeiro_id_plano_conta" name="financeiro_id_plano_conta"></select>
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <label>Tipo Documento</label>
                                        <select class="form-control financeiro_id_tipo_documento" name="financeiro_id_tipo_documento"></select>
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <label>Data Vencimento</label>
                                        <input type="date" class="form-control financeiro_data_vencimento" name="financeiro_data_vencimento"/>
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <label>Descrição</label>
                                        <input type="text" class="form-control financeiro_descricao" name="financeiro_descricao"/>
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
        var acao = "acao=load_plano_conta";
        $.ajax({
            type: 'GET',
            url: "_controller/_plano_conta.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                var data_return = jQuery.parseJSON(data);
                var options = '<option value=""></option>';
                for (var i = 0; i < data_return.data.length; i++) {
                    options += '<option value="' + data_return.data[i].plano_conta_id + '">' + data_return.data[i].plano_conta_classificacao + ' ' + data_return.data[i].plano_conta_descricao + '</option>';
                }
                $('.financeiro_id_plano_conta').html(options).show();
                load_out();
            }
        });
        load_out();
    });
    $(function(){
        var acao = "acao=load_tipo_documento";
        $.ajax({
            type: 'GET',
            url: "_controller/_tipo_documento.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                var data_return = jQuery.parseJSON(data);
                var options = '<option value=""></option>';
                for (var i = 0; i < data_return.data.length; i++) {
                    options += '<option value="' + data_return.data[i].tipo_documento_id + '">' + data_return.data[i].tipo_documento_descricao + '</option>';
                }
                $('.financeiro_id_tipo_documento').html(options).show();
                load_out();
            }
        });
        load_out();
    });
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
                        ler();
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
                url: '_controller/_spc.php?acao=enviar&id_plano_conta='+$(".financeiro_id_plano_conta").val()+"&id_tipo_documento="+$(".financeiro_id_tipo_documento").val()+"&descricao="+$(".financeiro_descricao").val()+"&data_vencimento="+$(".financeiro_data_vencimento").val(),
                resetForm: false
            }).submit();
        })
    })
    function ler(){
        var acao = "&acao=ler";
        $.ajax({
            type: 'GET',
            url: "_controller/_spc.php",
            data: acao,
            beforeSend: function (){
                $('#resposta').html('<div class="alert alert-success"><img src="_img/load.gif" class="load" /> <strong>Aguarde!</strong> Estou tentando ler o arquivo</div>');
            },
            success: function (data) {
                load_out();
                if(data.msg = 'OK'){
                    $('#resposta').html('<div class="alert alert-success"><strong>Parabéns!</strong> Gerei as vendas</div>');
                    ler_venda();
                }
            }
        });
    }
    function ler_venda(){
        var acao = "&acao=ler_venda";
        $.ajax({
            type: 'GET',
            url: "_controller/_spc.php",
            data: acao,
            beforeSend: function (){
                $('#resposta').html('<div class="alert alert-success"><img src="_img/load.gif" class="load" /> <strong>Aguarde!</strong> Estou organizando alguns itens</div>');
            },
            success: function (data) {
                load_out();
                if(data.msg = 'OK'){
                    $('#resposta').html('<div class="alert alert-success"><strong>Parabéns!</strong> Organizei os itens</div>');
                    ler_financeiro();
                }
            }
        });
    }
    function ler_financeiro(){
        var acao = "&acao=ler_financeiro";
        $.ajax({
            type: 'GET',
            url: "_controller/_spc.php",
            data: acao,
            beforeSend: function (){
                $('#resposta').html('<div class="alert alert-success"><img src="_img/load.gif" class="load" /> <strong>Aguarde!</strong> Estou gerando o financeiro, quase finalizado</div>');
            },
            success: function (data) {
                load_out();
                if(data.msg = 'OK'){
                    $('#resposta').html('<div class="alert alert-success"><strong>Parabéns!</strong> Pronto, finalizei a importação, pode aproveitar suas faturas!! Bom Trabalho...</div>');
                }
            }
        });
    }
    function limpar_campos(){
        $(".regiao_descricao").val('');
        $(".regiao_status").val('0');
    }
</script>