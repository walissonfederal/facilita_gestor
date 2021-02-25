<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Nível
                                <a href="javascript::" onclick="carrega_pagina('nivel', 'index.php');" class="btn btn-primary">Voltar</a>
                            </h3>
                        </div>
                        <div class="box-content">
                            <form action="" id="create_permissao_page" name="formulario">
                                <fieldset>
                                    <div class="check-line">
                                        <input type="checkbox" name="SelTodos" onclick="selTodos(document.formulario)" value="1">
                                        <label class="inline"><strong>Marcar Todos</strong></label>
                                    </div>
                                    <hr />
                                    <div id="nivel_pagina"></div>
                                    <div class="row">
                                        <div class="form-group col-lg-12" align="right">
                                            <button type="button" class="btn btn-primary" onclick="create_permissao_page();">Gravar</button>
                                            <a href="javascript::" onclick="carrega_pagina('nivel', 'index.php');" class="btn btn-danger">Cancelar</a>
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
        load_update();
    });
    function create_permissao_page(){
        var dados = $("#create_permissao_page").serialize();
        var acao = "&acao=create_permissao_page&id=<?=$_GET['id'];?>";
        
        $.ajax({
            type: 'POST',
            url: "_controller/_nivel.php",
            data: dados+acao,
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
    function load_update(){
        var acao = "&acao=load_permissao_page&id=<?=$_GET['id'];?>";
        
        $.ajax({
            type: 'POST',
            url: "_controller/_nivel.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                $("#nivel_pagina").html(data);
            }
        });
    } 
    function selTodos(Check) {
        if (document.formulario.SelTodos.checked == true) {
            for (i = 0; i < Check.length; i++) {
                Check[i].checked = true;
            }
        } else {
            for (i = 0; i < Check.length; i++) {
                Check[i].checked = false;
            }
        }
    }
</script>