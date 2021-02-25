<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Contrato Chip
                                <a href="javascript::" onclick="carrega_pagina('contrato', 'index_chip.php');" class="btn btn-primary">Voltar</a>
                            </h3>
                        </div>
                        <div class="box-content">
                            <form name="formUpload" id="formUpload" method="post">
                                <div class="row">
                                    <div class="form-group col-lg-12">
                                        <label>Arquivo(PDF / JPG / EML / ZIP / RAR)</label>
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
                                        <input type="hidden" name="acao" value="anexo"/>
                                        <input type="hidden" name="anexo_contrato_chip_id_contrato" value="<?= $_GET['id']; ?>"/>
                                        <button type="button" class="btn btn-primary" id="btnEnviar" >Gravar</button>
                                    </div>
                                </div>
                            </form>
                            <div id="resposta">
                            </div>
                            <hr />
                            <div id="gallery_contrato_chip"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        load_gallery();
    });
    function load_gallery(){
        var acao = "&acao=load_gallery&id=<?=$_GET['id'];?>";
        
        $.ajax({
            type: 'POST',
            url: "_controller/_contrato_chip.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                $("#gallery_contrato_chip").html(data);
            }
        });
    }
    function delete_gallery(id_arquivo){
        var acao = "&acao=delete_gallery&id_arquivo="+id_arquivo;
        
        $.ajax({
            type: 'POST',
            url: "_controller/_contrato_chip.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                load_gallery();
            }
        });
    }
    $(document).ready(function () {
        $('#btnEnviar').click(function () {
            $('#formUpload').ajaxForm({
                uploadProgress: function (event, position, total, percentComplete) {
                    $('.progress-bar .progress-bar-success').attr('aria-valuenow', percentComplete);
                    $('progress').attr('value', percentComplete);
                    $('#porcentagem').html(percentComplete + '% Enviado');
                    $('#resposta').html('<div class="alert alert-success"><img src="_img/load.gif" class="load" /> <strong>Aguarde!</strong> Estou enviando o arquivo</div>');
                },
                success: function (data) {
                    $('progress').attr('value', '100');
                    $('#porcentagem').html('100%');
                    if (data.sucesso == true) {
                        $('#resposta').html('<div class="alert alert-success"><strong>Parabéns!</strong> Arquivo enviado</div>');
                        load_gallery();
                        $('#porcentagem').html('0%');
                    } else {
                        $('#resposta').html('<div class="alert alert-danger"><strong>Ops!</strong> ' + data.msg + '</div>');
                        $('#porcentagem').html('0%');
                    }
                },
                error: function () {
                    $('#resposta').html('<div class="alert alert-danger"><strong>Ops!</strong> Erro ao enviar requisição!!!</div>');
                    $('#porcentagem').html('0%');
                },
                dataType: 'json',
                url: '_controller/_contrato_chip.php',
                resetForm: false
            }).submit();
        })
    });
</script>