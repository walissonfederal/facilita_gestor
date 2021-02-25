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
                            <form action="" id="update">
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
                                                <select class="form-control tributacao_icms_situacao_tributaria" name="tributacao_icms_situacao_tributaria" onchange="situacao_tributaria_icms(this.value);">
                                                </select>
                                            </div>
                                        </div>
                                        <div id="_form_icms"></div>
                                    </div>
                                    <div class="tab-pane" id="t_pis">
                                        <div class="row">
                                            <div class="form-group col-lg-12">
                                                <label>Situação Tributária</label>
                                                <select class="form-control tributacao_pis_situacao_tributaria" name="tributacao_pis_situacao_tributaria" onchange="situacao_tributaria_pis(this.value);">
                                                </select>
                                            </div>
                                        </div>
                                        <div id="_form_pis"></div>
                                    </div>
                                    <div class="tab-pane" id="t_ipi">
                                        <div class="row">
                                            <div class="form-group col-lg-12">
                                                <label>Situação Tributária</label>
                                                <select class="form-control tributacao_ipi_situacao_tributaria" name="tributacao_ipi_situacao_tributaria" onchange="situacao_tributaria_ipi(this.value);">
                                                </select>
                                            </div>
                                        </div>
                                        <div id="_form_ipi"></div>
                                    </div>
                                    <div class="tab-pane" id="t_cofins">
                                        <div class="row">
                                            <div class="form-group col-lg-12">
                                                <label>Situação Tributária</label>
                                                <select class="form-control tributacao_cofins_situacao_tributaria" name="tributacao_cofins_situacao_tributaria" onchange="situacao_tributaria_cofins(this.value);">
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
                                        <button type="button" class="btn btn-primary" onclick="update();">Gravar</button>
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
    function update(){
        var dados = $("#update").serialize();
        var acao = "&acao=update&id=<?=$_GET['id'];?>";
        
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
            async: false,
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
            async: false,
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
            async: false,
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
            async: false,
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
    function situacao_tributaria_icms(tributacao_icms_situacao_tributaria){
        $.post('view/tributacao/_icms/_icms_'+tributacao_icms_situacao_tributaria+'.php', function (html) {
            $('#_form_icms').html(html);
        });
    }
    function situacao_tributaria_ipi(tributacao_ipi_situacao_tributaria){
        $.post('view/tributacao/_ipi/_ipi_'+tributacao_ipi_situacao_tributaria+'.php', function (html) {
            $('#_form_ipi').html(html);
        });
    }
    function situacao_tributaria_pis(tributacao_pis_situacao_tributaria){
        $.post('view/tributacao/_pis/_pis_'+tributacao_pis_situacao_tributaria+'.php', function (html) {
            $('#_form_pis').html(html);
        });
    }
    function situacao_tributaria_cofins(tributacao_cofins_situacao_tributaria){
        $.post('view/tributacao/_cofins/_cofins_'+tributacao_cofins_situacao_tributaria+'.php', function (html) {
            $('#_form_cofins').html(html);
        });
    }
    function open_modal_cfop(){
        $("#_modal_cfop").modal('show');
    }
    $(function(){
        var acao = "acao=load_update&id=<?=$_GET['id'];?>";
        $.ajax({
            type: 'POST',
            url: "_controller/_tributacao.php",
            data: acao,
            beforeSend: load_in(),
            async: false,
            success: function (data) {
                load_out();
                var data_return = jQuery.parseJSON(data);
                $.post('view/tributacao/_icms/_icms_'+data_return[0].tributacao_icms_situacao_tributaria+'.php', function (html) {
                    $('#_form_icms').html(html);
                    $(".tributacao_icms_modalidade_bc").val(data_return[0].tributacao_icms_modalidade_bc);
                    $(".tributacao_icms_aliquota_icms").val(data_return[0].tributacao_icms_aliquota_icms);
                    $(".tributacao_icms_modalidade_bc_st").val(data_return[0].tributacao_icms_modalidade_bc_st);
                    $(".tributacao_icms_margem_valor_add").val(data_return[0].tributacao_icms_margem_valor_add);
                    $(".tributacao_icms_reducao_base_calc_st").val(data_return[0].tributacao_icms_reducao_base_calc_st);
                    $(".tributacao_icms_preco_un_pauta_st").val(data_return[0].tributacao_icms_preco_un_pauta_st);
                    $(".tributacao_icms_aliquota_icms_st").val(data_return[0].tributacao_icms_aliquota_icms_st);
                    $(".tributacao_icms_reducao_base_calc").val(data_return[0].tributacao_icms_reducao_base_calc);
                    $(".tributacao_icms_mot_desoneracao").val(data_return[0].tributacao_icms_mot_desoneracao);
                    $(".tributacao_icms_perc_bc_op_propria").val(data_return[0].tributacao_icms_perc_bc_op_propria);
                    $(".tributacao_icms_uf_icms_st").val(data_return[0].tributacao_icms_uf_icms_st);
                    $(".tributacao_icms_aliquota_calc_credito").val(data_return[0].tributacao_icms_aliquota_calc_credito);
                });
                $.post('view/tributacao/_ipi/_ipi_'+data_return[0].tributacao_ipi_situacao_tributaria+'.php', function (html) {
                    $('#_form_ipi').html(html);
                    //IPI
                    $(".tributacao_ipi_classe_enquadramento").val(data_return[0].tributacao_ipi_classe_enquadramento);
                    $(".tributacao_ipi_cnpj_produtor").val(data_return[0].tributacao_ipi_cnpj_produtor);
                    $(".tributacao_ipi_cod_selo_ipi").val(data_return[0].tributacao_ipi_cod_selo_ipi);
                    $(".tributacao_ipi_qtd_selo").val(data_return[0].tributacao_ipi_qtd_selo);
                    $(".tributacao_ipi_cod_enquadramento").val(data_return[0].tributacao_ipi_cod_enquadramento);
                    $(".tributacao_ipi_tipo_calculo").val(data_return[0].tributacao_ipi_tipo_calculo);
                    $(".tributacao_ipi_aliquota_ipi").val(data_return[0].tributacao_ipi_aliquota_ipi);
                    $(".tributacao_ipi_valor_ipi").val(data_return[0].tributacao_ipi_valor_ipi);
                });
                $.post('view/tributacao/_cofins/_cofins_'+data_return[0].tributacao_cofins_situacao_tributaria+'.php', function (html) {
                    $('#_form_cofins').html(html);
                    //COFINS
                    $.post('view/tributacao/_cofins/_tipo_calculo/_calc_'+data_return[0].tributacao_cofins_tipo_calculo_st+'.php', function (html) {
                        $('#_form_calc_cofins').html(html);
                        $(".tributacao_cofins_tipo_calculo_st").val(data_return[0].tributacao_cofins_tipo_calculo_st);
                        $(".tributacao_cofins_aliquota_cofins_st").val(data_return[0].tributacao_cofins_aliquota_cofins_st);
                        $(".tributacao_cofins_valor_cofins_st").val(data_return[0].tributacao_cofins_valor_cofins_st);
                    });
                    $(".tributacao_cofins_aliquota_cofins").val(data_return[0].tributacao_cofins_aliquota_cofins);
                    $(".tributacao_cofins_valor_cofins").val(data_return[0].tributacao_cofins_valor_cofins);
                    $(".tributacao_cofins_tipo_calculo").val(data_return[0].tributacao_cofins_tipo_calculo);
                });
                $.post('view/tributacao/_pis/_pis_'+data_return[0].tributacao_pis_situacao_tributaria+'.php', function (html) {
                    $('#_form_pis').html(html);
                    //PIS
                    $.post('view/tributacao/_pis/_tipo_calculo/_calc_'+data_return[0].tributacao_pis_tipo_calculo_st+'.php', function (html) {
                        $('#_form_calc_pis').html(html);
                        $(".tributacao_pis_tipo_calculo_st").val(data_return[0].tributacao_pis_tipo_calculo_st);
                        $(".tributacao_pis_aliquota_pis_st").val(data_return[0].tributacao_pis_aliquota_pis_st);
                        $(".tributacao_pis_valor_pis_st").val(data_return[0].tributacao_pis_valor_pis_st);
                    });
                    $(".tributacao_pis_aliquota_pis").val(data_return[0].tributacao_pis_aliquota_pis);
                    $(".tributacao_pis_valor_pis").val(data_return[0].tributacao_pis_valor_pis);
                    $(".tributacao_pis_tipo_calculo").val(data_return[0].tributacao_pis_tipo_calculo);
                });
                
                $(".tributacao_descricao").val(data_return[0].tributacao_descricao);
                $(".tributacao_cfop").val(data_return[0].tributacao_cfop);
                $(".tributacao_icms_situacao_tributaria").val(data_return[0].tributacao_icms_situacao_tributaria);
                $(".tributacao_pis_situacao_tributaria").val(data_return[0].tributacao_pis_situacao_tributaria);
                $(".tributacao_cofins_situacao_tributaria").val(data_return[0].tributacao_cofins_situacao_tributaria);
                $(".tributacao_ipi_situacao_tributaria").val(data_return[0].tributacao_ipi_situacao_tributaria);
                $(".tributacao_iss_aliquota_iss").val(data_return[0].tributacao_iss_aliquota_iss);
                
                $(".tributacao_icms_modalidade_bc_st").val('2');
            }
        });
    });
    $("#valor").maskMoney({thousands:'', decimal:'.'});
</script>