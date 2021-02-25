<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                PLP
                                <a href="javascript::" onclick="carrega_pagina('plp', 'index.php');" class="btn btn-primary">Voltar</a>
                            </h3>
                        </div>
                        <div class="box-content">
                            <div class="row">
								<div class="form-group col-lg-12" align="right">
									<button type="button" class="btn btn-primary" onclick="close_plp();">Fechar PLP</button>
									<a href="javascript::" onclick="carrega_pagina('plp', 'index.php');" class="btn btn-danger">Voltar</a>
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
    function close_plp(){
        var acao = "&acao=close_plp";
        
        $.ajax({
            type: 'POST',
            url: "_controller/_plp.php",
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
                    $("#_modal").modal('show');
                    $("#title_modal").html(data_return.title);
                    $("#texto_modal").html(data_return.msg);
                    $("#buttons_modal").html(data_return.buttons);
                }
            }
        });
    }
</script>