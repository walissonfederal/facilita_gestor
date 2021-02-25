<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Diretoria - Pessoas
                                <a href="javascript::" onclick="carrega_pagina('diretoria', 'index_pessoa.php?id_diretoria=<?=$_GET['id_diretoria'];?>');" class="btn btn-primary">Voltar</a>
                            </h3>
                        </div>
                        <div class="box-content">
                            <form action="" id="update">
                                <fieldset>
                                    <div class="row">
                                        <div class="form-group col-lg-3">
                                            <label>ID Pessoa</label>
                                            <input type="text" class="form-control pessoa_diretoria_id_pessoa" name="pessoa_diretoria_id_pessoa" onblur="buscar_pessoa();"/>
                                        </div>
                                        <div class="form-group col-lg-6">
                                            <label>Pessoa</label>
                                            <input type="text" class="form-control pessoa_diretoria_nome"/>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>Cargo</label>
                                            <select class="form-control pessoa_diretoria_id_cargo" name="pessoa_diretoria_id_cargo"></select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-12" align="right">
                                            <button type="button" class="btn btn-primary" onclick="update();">Gravar</button>
                                            <a href="javascript::" onclick="carrega_pagina('diretoria', 'index_pessoa.php?id_diretoria=<?=$_GET['id_diretoria'];?>');" class="btn btn-danger">Voltar</a>
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
    function update(){
        var dados = $("#update").serialize();
        var acao = "&acao=update&pessoa_diretoria_id_diretoria=<?=$_GET['id_diretoria'];?>&id=<?=$_GET['id'];?>";
        
        $.ajax({
            type: 'POST',
            url: "_controller/_diretoria_pessoa.php",
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
    function limpar_campos(){
        $(".pessoa_diretoria_id_pessoa").val('');
        $(".pessoa_diretoria_id_cargo").val('');
        $(".pessoa_diretoria_nome").val('');
    }
    $(function() {
        $( ".pessoa_diretoria_nome" ).autocomplete({
            source: "_controller/_pessoa.php?acao=load_pessoa",
            minLength: 2,
            focus: function(event, ui) {
            	event.preventDefault();
            },
            select: function( event, ui ) {
                $(".pessoa_diretoria_id_pessoa").val(ui.item.value);
                $(".pessoa_diretoria_nome").val(ui.item.label);
                event.preventDefault();
            }
        });
    });
    function buscar_pessoa(){
        var id_pessoa = $(".pessoa_diretoria_id_pessoa").val();
        var acao = "acao=load_pessoa_id&id="+id_pessoa;
        
        if(id_pessoa){
            $.ajax({
                type: 'POST',
                url: "_controller/_pessoa.php",
                data: acao,
                beforeSend: load_in(),
                success: function (data) {
                    load_out();
                    var data_return = jQuery.parseJSON(data);
                    if(data_return[0].label !== ''){
                        $(".pessoa_diretoria_nome").val(data_return[0].label);
                    }else{
                        $("#_modal").modal('show');
                        $("#title_modal").html('Erro');
                        $("#texto_modal").html('Ops, não foi encontrado nenhum registro');
                        $("#buttons_modal").html('');
                        $(".pessoa_diretoria_id_pessoa").val('');
                        $(".pessoa_diretoria_nome").val('');
                    }
                }
            });
        }
    }
    $(function(){
        var acao = "acao=load_cargo";
        $.ajax({
            type: 'GET',
            url: "_controller/_cargo.php",
            data: acao,
            beforeSend: load_in(),
            async: false,
            success: function (data) {
                var data_return = jQuery.parseJSON(data);
                var options = '<option value=""></option>';
                for (var i = 0; i < data_return.data.length; i++) {
                    options += '<option value="' + data_return.data[i].cargo_id + '">' + data_return.data[i].cargo_descricao + '</option>';
                }
                $('.pessoa_diretoria_id_cargo').html(options).show();
                load_out();
            }
        });
        load_out();
    });
    $(function(){
        var acao = "acao=load_update&id=<?=$_GET['id'];?>";
        $.ajax({
            type: 'POST',
            url: "_controller/_diretoria_pessoa.php",
            data: acao,
            beforeSend: load_in(),
            async: false,
            success: function (data) {
                load_out();
                var data_return = jQuery.parseJSON(data);
                $(".pessoa_diretoria_id_pessoa").val(data_return[0].pessoa_diretoria_id_pessoa);
                $(".pessoa_diretoria_id_cargo").val(data_return[0].pessoa_diretoria_id_cargo);
                buscar_pessoa();
            }
        });
    });
</script>