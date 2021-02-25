<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Bloqueio
                            </h3>
                        </div>
                        <div class="box-content">
                            <div class="row">
                                <div class="form-group col-lg-12">
                                    <button class="btn btn-success" onclick="finalizar_desbloqueio();">FINALIZAR DESBLOQUEIO</button>
                                </div>
                            </div>
                            <hr />
                            <small>Desbloqueando o cliente no sistema não desbloqueia o mesmo em outras aplicações.</small><br />
                            <small>O status que aqui é informado serve apenas a título de controle e informação.</small><br />
                            <small>Só faça essa operação caso tenha realmente certeza da operação.</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function finalizar_desbloqueio(){
        var Data = "&action=finalizar_desbloqueio&id=<?php echo addslashes($_GET['id']);?>";
        $.ajax({
            url: "_controller/Desbloqueio.ajax.php",
            data: Data,
            type: 'POST',
            dataType: 'json',
            beforeSend: load_in(),
            success: function (data) {
                if(data.type === 'ok'){
                    alert(data.msg);
                    carrega_pagina('desbloqueio', 'index.php');
                }else{
                    alert(data.msg);
                }
                load_out();
            }
        });
        return false;
    }
</script>