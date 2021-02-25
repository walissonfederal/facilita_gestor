<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Notificação
                                <a href="javascript::" onclick="carrega_pagina('mmn_notificacao', 'index.php');" class="btn btn-primary">Voltar</a>
                            </h3>
                        </div>
                        <div class="box-content">
                            <div id="load_notificacao_view"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        var acao = "acao=load_notificacao&id=<?=$_GET['id'];?>";
        $.ajax({
            type: 'POST',
            url: "_controller/_mmn_notificacao.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                $("#load_notificacao_view").html(data);
            }
        });
    });
</script>