<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Tributação
                                <a href="javascript::" onclick="carrega_pagina('tributacao', 'index.php');" class="btn btn-primary">Voltar</a>
                            </h3>
                        </div>
                        <div class="box-content">
                            <ul class="tabs tabs-inline tabs-top">
                                <li class="active">
                                    <a href="#t_principal" data-toggle="tab">PRINCIPAL</a>
                                </li>
                                <li>
                                    <a href="#t_icms" data-toggle="tab">ICMS</a>
                                </li>
                                <li>
                                    <a href="#t_pis" data-toggle="tab">PIS</a>
                                </li>
                                <li>
                                    <a href="#t_ipi" data-toggle="tab">IPI</a>
                                </li>
                                <li>
                                    <a href="#t_cofins" data-toggle="tab">COFINS</a>
                                </li>
                                <li>
                                    <a href="#t_iss" data-toggle="tab">ISS</a>
                                </li>
                            </ul>
                            <form action="" id="create">
                                <div class="tab-content padding tab-content-inline tab-content-bottom">
                                    <div class="tab-pane active" id="t_principal">
                                        <div class="row">
                                            <div class="form-group col-lg-8">
                                                <label>Descrição</label>
                                                <input type="text" class="form-control tributacao_descricao" name="tributacao_descricao"/>
                                            </div>
                                            <div class="col-sm-4">
                                                <label>CFOP</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control tributacao_cfop" name="tributacao_cfop"/>
                                                    <a href="#" class="btn input-group-addon">
                                                        <i class="fa fa-search"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="t_icms">
                                        <div class="row">
                                            <div class="form-group col-lg-12">
                                                <label>Situação Tributária</label>
                                                <select class="form-control tributacao_icms_situacao_tributaria" name="tributacao_icms_situacao_tributaria" onchange="situacao_tributaria_icms();">
                                                </select>
                                            </div>
                                        </div>
                                        <div id="_form_icms"></div>
                                    </div>
                                    <div class="tab-pane" id="t_pis">
                                        <div class="row">
                                            <div class="form-group col-lg-12">
                                                <label>Situação Tributária</label>
                                                <select class="form-control tributacao_pis_situacao_tributaria" name="tributacao_pis_situacao_tributaria" onchange="situacao_tributaria_pis();">
                                                </select>
                                            </div>
                                        </div>
                                        <div id="_form_pis"></div>
                                    </div>
                                    <div class="tab-pane" id="t_ipi">
                                        <div class="row">
                                            <div class="form-group col-lg-12">
                                                <label>Situação Tributária</label>
                                                <select class="form-control tributacao_ipi_situacao_tributaria" name="tributacao_ipi_situacao_tributaria" onchange="situacao_tributaria_ipi();">
                                                </select>
                                            </div>
                                        </div>
                                        <div id="_form_ipi"></div>
                                    </div>
                                    <div class="tab-pane" id="t_cofins">
                                        <div class="row">
                                            <div class="form-group col-lg-12">
                                                <label>Situação Tributária</label>
                                                <select class="form-control tributacao_cofins_situacao_tributaria" name="tributacao_cofins_situacao_tributaria" onchange="situacao_tributaria_cofins();">
                                                </select>
                                            </div>
                                        </div>
                                        <div id="_form_cofins"></div>
                                    </div>
                                    <div class="tab-pane" id="t_iss">
                                        <div class="row">
                                            <div class="form-group col-lg-12">
                                                <label>Alíquota ISS (%)</label>
                                                <input type="text" class="form-control tributacao_iss_aliquota_iss" name="tributacao_iss_aliquota_iss" id="valor"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-12" align="right">
                                        <button type="button" class="btn btn-primary" onclick="create();">Gravar</button>
                                        <a href="javascript::" onclick="carrega_pagina('tributacao', 'index.php');" class="btn btn-danger">Cancelar</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function create(){
        var dados = $("#create").serialize();
        var acao = "&acao=create";
        
        $.ajax({
            type: 'POST',
            url: "_controller/_tributacao.php",
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
        $(".rota_descricao").val('');
        $(".rota_status").val('0');
    }
    $(function(){
        var acao = "acao=load_icms_st";
        $.ajax({
            type: 'GET',
            url: "_controller/_tributacao.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                var data_return = jQuery.parseJSON(data);
                var options = '<option value=""></option>';
                for (var i = 0; i < data_return.data.length; i++) {
                    options += '<option value="' + data_return.data[i].codigo_icms + '">' + data_return.data[i].desc_icms + '</option>';
                }
                $('.tributacao_icms_situacao_tributaria').html(options).show();
                load_out();
            }
        });
        load_out();
    });
    $(function(){
        var acao = "acao=load_ipi_st";
        $.ajax({
            type: 'GET',
            url: "_controller/_tributacao.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                var data_return = jQuery.parseJSON(data);
                var options = '<option value=""></option>';
                for (var i = 0; i < data_return.data.length; i++) {
                    options += '<option value="' + data_return.data[i].codigo_ipi + '">' + data_return.data[i].desc_ipi + '</option>';
                }
                $('.tributacao_ipi_situacao_tributaria').html(options).show();
                load_out();
            }
        });
        load_out();
    });
    $(function(){
        var acao = "acao=load_pis_st";
        $.ajax({
            type: 'GET',
            url: "_controller/_tributacao.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                var data_return = jQuery.parseJSON(data);
                var options = '<option value=""></option>';
                for (var i = 0; i < data_return.data.length; i++) {
                    options += '<option value="' + data_return.data[i].codigo_pis + '">' + data_return.data[i].desc_pis + '</option>';
                }
                $('.tributacao_pis_situacao_tributaria').html(options).show();
                load_out();
            }
        });
        load_out();
    });
    $(function(){
        var acao = "acao=load_cofins_st";
        $.ajax({
            type: 'GET',
            url: "_controller/_tributacao.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                var data_return = jQuery.parseJSON(data);
                var options = '<option value=""></option>';
                for (var i = 0; i < data_return.data.length; i++) {
                    options += '<option value="' + data_return.data[i].codigo_cofins + '">' + data_return.data[i].desc_cofins + '</option>';
                }
                $('.tributacao_cofins_situacao_tributaria').html(options).show();
                load_out();
            }
        });
        load_out();
    });
    function situacao_tributaria_icms(){
        var tributacao_icms_situacao_tributaria = $(".tributacao_icms_situacao_tributaria").val();
        $.post('view/tributacao/_icms/_icms_'+tributacao_icms_situacao_tributaria+'.php', function (html) {
            $('#_form_icms').html(html);
        });
    }
    function situacao_tributaria_ipi(){
        var tributacao_ipi_situacao_tributaria = $(".tributacao_ipi_situacao_tributaria").val();
        $.post('view/tributacao/_ipi/_ipi_'+tributacao_ipi_situacao_tributaria+'.php', function (html) {
            $('#_form_ipi').html(html);
        });
    }
    function situacao_tributaria_pis(){
        var tributacao_pis_situacao_tributaria = $(".tributacao_pis_situacao_tributaria").val();
        $.post('view/tributacao/_pis/_pis_'+tributacao_pis_situacao_tributaria+'.php', function (html) {
            $('#_form_pis').html(html);
        });
    }
    function situacao_tributaria_cofins(){
        var tributacao_cofins_situacao_tributaria = $(".tributacao_cofins_situacao_tributaria").val();
        $.post('view/tributacao/_cofins/_cofins_'+tributacao_cofins_situacao_tributaria+'.php', function (html) {
            $('#_form_cofins').html(html);
        });
    }
    function open_modal_cfop(){
        $("#_modal_cfop").modal('show');
    }
    $("#valor").maskMoney({thousands:'', decimal:'.'});
</script>