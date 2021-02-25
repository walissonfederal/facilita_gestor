<?php session_start();?>
<?php
    if(!isset($_SESSION['registros_financeiro'])){
        $_SESSION['registros_financeiro'] = '100';
    }
?>
<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Financeiro(<?=$_GET['OP'];?>)
                                <a href="javascript::" onclick="carrega_pagina('financeiro', 'create.php?OP=<?=$_GET['OP'];?>')" class="btn btn-primary">Cadastrar Novo</a>
								<a href="teste_tec.php" target="_blank" class="btn btn-primary">Clientes Sem Boleto</a>
                            </h3>
                        </div>
                        <div class="box-content">
                            <form action="">
                                <fieldset>
                                    <div class="row">
                                        <div class="form-group col-lg-1">
                                            <label>ID Contato</label>
                                            <input type="text" class="form-control search_nome_id_contato" onblur="buscar_contato();" value="<?php echo $_SESSION['search_financeiro_id_contato'];?>"/>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>Contato</label>
                                            <input type="text" class="form-control search_nome_contato"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Situação</label>
                                            <select class="form-control search_situacao">
                                                <option value=""></option>
                                                <option value="0" <?php if($_SESSION['search_financeiro_situacao'] == '0'){echo 'selected';}?>>ABERTO</option>
                                                <option value="1" <?php if($_SESSION['search_financeiro_situacao'] == '1'){echo 'selected';}?>>BAIXADO</option>
                                                <option value="2" <?php if($_SESSION['search_financeiro_situacao'] == '2'){echo 'selected';}?>>CANCELADO</option>
                                                <option value="3" <?php if($_SESSION['search_financeiro_situacao'] == '3'){echo 'selected';}?>>RENEGOCIADO</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Tipo Pesquisa</label>
                                            <select class="form-control search_tipo_pesquisa">
                                                <option value="financeiro_data_vencimento" <?php if($_SESSION['search_financeiro_tipo_pesquisa'] == 'financeiro_data_vencimento'){echo 'selected';}?>>Data Vencimento</option>
                                                <option value="financeiro_data_lancamento" <?php if($_SESSION['search_financeiro_tipo_pesquisa'] == 'financeiro_data_lancamento'){echo 'selected';}?>>Data Lançamento</option>
                                                <option value="financeiro_data_pagamento" <?php if($_SESSION['search_financeiro_tipo_pesquisa'] == 'financeiro_data_pagamento'){echo 'selected';}?>>Data Pagamento</option>
                                                <option value="financeiro_data_baixa" <?php if($_SESSION['search_financeiro_tipo_pesquisa'] == 'financeiro_data_baixa'){echo 'selected';}?>>Data Baixa</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Data Inicial</label>
                                            <input type="date" class="form-control search_data_inicial" value="<?php echo $_SESSION['search_financeiro_data_inicial'];?>"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Data Final</label>
                                            <input type="date" class="form-control search_data_final" value="<?php echo $_SESSION['search_financeiro_data_final'];?>"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-2">
                                            <label>Itens Pesquisa</label>
                                            <select class="form-control search_itens_pesquisa">
                                                <option value=""></option>
                                                <option value="financeiro_nosso_numero" <?php if($_SESSION['search_financeiro_itens_pesquisa'] == 'financeiro_nosso_numero'){echo 'selected';}?>>Nosso Número</option>
                                                <option value="financeiro_codigo" <?php if($_SESSION['search_financeiro_itens_pesquisa'] == 'financeiro_codigo'){echo 'selected';}?>>Código</option>
                                                <option value="financeiro_descricao" <?php if($_SESSION['search_financeiro_itens_pesquisa'] == 'financeiro_descricao'){echo 'selected';}?>>Descrição</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Pesquisa</label>
                                            <input type="text" class="form-control search_pesquisa" value="<?php echo $_SESSION['search_financeiro_pesquisa'];?>"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Fixa</label>
                                            <select class="form-control search_fixo">
                                                <option value=""></option>
                                                <option value="0" <?php if($_SESSION['search_financeiro_fixo'] == '0'){echo 'selected';}?>>NÃO</option>
                                                <option value="1" <?php if($_SESSION['search_financeiro_fixo'] == '1'){echo 'selected';}?>>SIM</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Aplicação Financeira?</label>
                                            <select class="form-control search_app_financeira">
                                                <option value=""></option>
                                                <option value="0" <?php if($_SESSION['search_financeiro_app_financeira'] == '0'){echo 'selected';}?>>NÃO</option>
                                                <option value="1" <?php if($_SESSION['search_financeiro_app_financeira'] == '1'){echo 'selected';}?>>SIM</option>
												<option value="2" <?php if($_SESSION['search_financeiro_app_financeira'] == '2'){echo 'selected';}?>>NULO</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Plano de Contas</label>
                                            <select class="form-control search_id_plano_conta"></select>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Tipo Documento</label>
                                            <select class="form-control search_id_tipo_documento"></select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-2">
                                            <label>Registros</label>
                                            <select class="form-control search_registros">
                                                <option value="100" <?php if($_SESSION['registros_financeiro'] == '100'){echo 'selected';}?>>100</option>
                                                <option value="200" <?php if($_SESSION['registros_financeiro'] == '200'){echo 'selected';}?>>200</option>
                                                <option value="300" <?php if($_SESSION['registros_financeiro'] == '300'){echo 'selected';}?>>300</option>
                                                <option value="400" <?php if($_SESSION['registros_financeiro'] == '400'){echo 'selected';}?>>400</option>
                                                <option value="500" <?php if($_SESSION['registros_financeiro'] == '500'){echo 'selected';}?>>500</option>
                                                <option value="600" <?php if($_SESSION['registros_financeiro'] == '600'){echo 'selected';}?>>600</option>
                                                <option value="700" <?php if($_SESSION['registros_financeiro'] == '700'){echo 'selected';}?>>700</option>
                                                <option value="800" <?php if($_SESSION['registros_financeiro'] == '800'){echo 'selected';}?>>800</option>
                                                <option value="900" <?php if($_SESSION['registros_financeiro'] == '900'){echo 'selected';}?>>900</option>
                                                <option value="1000" <?php if($_SESSION['registros_financeiro'] == '1000'){echo 'selected';}?>>1000</option>
												<option value="50" <?php if($_SESSION['registros_financeiro'] == '50'){echo 'selected';}?>>50</option>
												<option value="25" <?php if($_SESSION['registros_financeiro'] == '25'){echo 'selected';}?>>25</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>ID Vendedor</label>
                                            <input type="text" class="form-control financeiro_id_vendedor" onblur="buscar_vendedor();" name="financeiro_id_vendedor" value="<?php echo $_SESSION['search_financeiro_id_vendedor'];?>"/>
                                        </div>
                                        <div class="form-group col-lg-4">
                                            <label>Vendedor(Nome, CPF, Email)</label>
                                            <input type="text" class="form-control financeiro_nome_vendedor"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Remessa Gerada</label>
                                            <select class="form-control search_remessa">
                                                <option value=""></option>
                                                <option value="0" <?php if($_SESSION['search_financeiro_remessa'] == '0'){echo 'selected';}?>>NÃO</option>
                                                <option value="1" <?php if($_SESSION['search_financeiro_remessa'] == '1'){echo 'selected';}?>>SIM</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Boleto Emitido</label>
                                            <select class="form-control search_boleto">
                                                <option value=""></option>
                                                <option value="0" <?php if($_SESSION['search_financeiro_boleto'] == '0'){echo 'selected';}?>>NÃO</option>
                                                <option value="1" <?php if($_SESSION['search_financeiro_boleto'] == '1'){echo 'selected';}?>>SIM</option>
                                            </select>
                                        </div>
                                        <!--<div class="form-group col-lg-4">
                                            <label>Associado</label>
                                            <select class="form-control financeiro_id_associado"></select>
                                        </div>-->
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-12">
                                            <label>.</label><br />
                                            <button type="button" class="btn btn-primary" onclick="search();">Pesquisar</button>
                                            <button type="button" class="btn btn-primary" onclick="link_update();">Editar</button>
                                            <button type="button" class="btn btn-primary" onclick="link_update_varios();">Editar Vários</button>
                                            <button type="button" class="btn btn-primary" onclick="link_download();">Baixar</button>
                                            <button type="button" class="btn btn-primary" onclick="link_reverse();">Estornar</button>
                                            <button type="button" class="btn btn-primary" onclick="link_cancel();">Cancelar</button>
                                            <?php if($_GET['OP'] == 'CR'){?>
                                            <button type="button" class="btn btn-primary" onclick="link_boleto();">Gerar Boleto</button>
                                            <button type="button" class="btn btn-primary" onclick="link_carne();">Gerar Carnê</button>
                                            <button type="button" class="btn btn-primary" onclick="link_remessa();">Gerar Remessa</button>
                                            <button type="button" class="btn btn-primary" onclick="link_enviar_mail();">Enviar Email</button>
                                            <button type="button" class="btn btn-primary" onclick="link_itens_vendas();">Itens Vendas</button>
                                            <button type="button" class="btn btn-primary" onclick="link_segunda_via();">Segunda Via</button>
                                            <button type="button" class="btn btn-primary" onclick="event.preventDefault();email_massa(this);">Email Em Massa</button>

<!--                                            <a href="../envio_info/envio_mail/index.php?inicio=0" target="_blank" class="btn btn-primary">Massa</a>-->
											
                                            <?php }?>
                                            <button type="button" class="btn btn-primary" onclick="link_ged();">GED</button>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                            <form name="formEmailMassa" action="../envio_info/envio_mail/index.php?inicio=0" method="POST">
                                <input name="input_ids" type="hidden" value=""/>

                            </form>
                            <hr />
                            <div id="load_financeiro"></div>
                            <div class="row">
                                <div class="col-sm-2">
                                    <div class="box box-color blue box-condensed box-bordered">
                                        <div class="box-title" align="center">
                                            <h3>
                                                Contas
                                            </h3>
                                        </div>
                                        <div class="box-content">
                                            <div id="financeiro_quantidade_contas" align="center"><h4></h4></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="box box-color blue box-condensed box-bordered">
                                        <div class="box-title" align="center">
                                            <h3>
                                                Valor total
                                            </h3>
                                        </div>
                                        <div class="box-content">
                                            <div id="financeiro_valor_total" align="center"><h4></h4></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="box box-color blue box-condensed box-bordered">
                                        <div class="box-title" align="center">
                                            <h3>
                                                Valor aberto
                                            </h3>
                                        </div>
                                        <div class="box-content">
                                            <div id="financeiro_valor_aberto" align="center"><h4></h4></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="box box-color blue box-condensed box-bordered">
                                        <div class="box-title" align="center">
                                            <h3>
                                                Valor pago
                                            </h3>
                                        </div>
                                        <div class="box-content">
                                            <div id="financeiro_valor_pago" align="center"><h4></h4></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="box box-color blue box-condensed box-bordered">
                                        <div class="box-title" align="center">
                                            <h3>
                                                Valor cancelado
                                            </h3>
                                        </div>
                                        <div class="box-content">
                                            <div id="financeiro_valor_cancelado" align="center"><h4></h4></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="box box-color blue box-condensed box-bordered">
                                        <div class="box-title" align="center">
                                            <h3>
                                                Valor renegociado
                                            </h3>
                                        </div>
                                        <div class="box-content">
                                            <div id="financeiro_valor_renegociado" align="center"><h4></h4></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-2">
                                    <div class="box box-color blue box-condensed box-bordered">
                                        <div class="box-title" align="center">
                                            <h3>
                                                Valor juros
                                            </h3>
                                        </div>
                                        <div class="box-content">
                                            <div id="financeiro_valor_juros" align="center"><h4></h4></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="box box-color blue box-condensed box-bordered">
                                        <div class="box-title" align="center">
                                            <h3>
                                                Valor multa
                                            </h3>
                                        </div>
                                        <div class="box-content">
                                            <div id="financeiro_valor_multa" align="center"><h4></h4></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="box box-color blue box-condensed box-bordered">
                                        <div class="box-title" align="center">
                                            <h3>
                                                Valor atualizado
                                            </h3>
                                        </div>
                                        <div class="box-content">
                                            <div id="financeiro_valor_atualizado" align="center"><h4></h4></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="box box-color blue box-condensed box-bordered">
                                        <div class="box-title" align="center">
                                            <h3>
                                                Protestado?
                                            </h3>
                                        </div>
                                        <div class="box-content">
                                            <div id="financeiro_protestado" align="center"><h4></h4></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="_modal_segunda_via" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Segunda Via</h4>
            </div>
            <!-- /.modal-header -->
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-lg-6">
                        <label>Data Vencimento</label>
                        <input type="date" class="form-control segunda_data_vencimento" />
                        <input type="hidden" class="form-control segunda_id" />
                    </div>
                    <div class="form-group col-lg-6">
                        <label>Envio com juros?</label>
                        <select class="form-control segunda_envio_juros">
                            <option value="0">Sim</option>
                            <option value="1">Não</option>
                        </select>
                    </div>
                </div>
            </div>
            <!-- /.modal-body -->
            <div class="modal-footer">
                <div id="buttons_modal_segunda_via"></div>
            </div>
            <!-- /.modal-footer -->
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div id="_modal_count" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Conta</h4>
            </div>
            <!-- /.modal-header -->
            <div class="modal-body">
                <div id="load_boleto"></div>
            </div>
            <!-- /.modal-body -->
            <div class="modal-footer">
            </div>
            <!-- /.modal-footer -->
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /#modal-1.modal fade -->
<div id="_modal_count_remessa" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Conta</h4>
            </div>
            <!-- /.modal-header -->
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-lg-12">
                        <label>Remessa Esperada</label>
                        <input type="number" class="form-control num_remessa"/>
                    </div>
                </div>
            </div>
            <!-- /.modal-body -->
            <div class="modal-footer">
                <div id="retorno_remessa_msg"></div>
                <button type="button" class="btn btn-primary" onclick="gerar_remessa_completa();">Gerar Remessa</button>
            </div>
            <!-- /.modal-footer -->
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /#modal-1.modal fade -->
<div id="_modal_count_mail" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Enviar Email</h4>
            </div>
            <!-- /.modal-header -->
            <div class="modal-body">
                <div id="retorno_mail"></div>
                <div id="retorno_mail_msg"></div>
                <hr />
                <div class="row">
                    <div class="form-group col-lg-6">
                        <label>Data Vencimento</label>
                        <input type="date" class="form-control financeiro_new_data_vencimento" />
                    </div>
                    <div class="form-group col-lg-6">
                        <label>Envio com juros?</label>
                        <select class="form-control financeiro_envio_juros">
                            <option value="0">Sim</option>
                            <option value="1">Não</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-lg-12">
                        <label>Envio de fatura por?</label>
                        <select class="form-control financeiro_envio_fatura">
                            <option value="0">Boleto</option>
                            <option value="1">Pagseguro</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-lg-12">
                        <label>Email</label>
                        <input type="text" class="form-control financeiro_email_envio" />
                    </div>
                </div>
            </div>
            <!-- /.modal-body -->
            <div class="modal-footer">
                <div id="resposta_mail"></div>
                <button type="button" class="btn btn-default" onclick="send_mail();">Enviar E-Mail</button>
            </div>
            <!-- /.modal-footer -->
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<script>
    $(function() {
        $( ".search_nome_contato" ).autocomplete({
            source: "_controller/_contato.php?acao=load_contato",
            minLength: 2,
            focus: function(event, ui) {
            	event.preventDefault();
            },
            select: function( event, ui ) {
                $(".search_nome_id_contato").val(ui.item.value);
                $(".search_nome_contato").val(ui.item.label);
                event.preventDefault();
            }
        });
    });
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
                $('.search_id_plano_conta').html(options).show();
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
                $('.search_id_tipo_documento').html(options).show();
                load_out();
            }
        });
        load_out();
    });
    function gerar_remessa (banco, boleto){
        var acao = "acao=gerar_remessa&banco="+banco+"&boleto="+boleto;
        $.ajax({
            type: 'POST',
            url: "_controller/_remessa.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                var data_return = jQuery.parseJSON(data);
                if(data_return.type === 'success'){
                    //$("#_modal").modal('show');
                    $("#title_modal").html(data_return.title);
                    $("#texto_modal").html(data_return.msg);
                    $("#buttons_modal").html(data_return.buttons);
                    $('#retorno_remessa_msg').html('<div class="alert alert-success">'+data_return.msg+'</div>');
                }else{
                    $('#retorno_remessa_msg').html('<div class="alert alert-danger">'+data+'</div>');
                }
            }
        });
    }
    function load_conta_boleto(url){
        var acao = "acao=load_boleto_financeiro";
        $.ajax({
            type: 'POST',
            url: "_controller/_empresa.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                var Base64={_keyStr:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",encode:function(e){var t="";var n,r,i,s,o,u,a;var f=0;e=Base64._utf8_encode(e);while(f<e.length){n=e.charCodeAt(f++);r=e.charCodeAt(f++);i=e.charCodeAt(f++);s=n>>2;o=(n&3)<<4|r>>4;u=(r&15)<<2|i>>6;a=i&63;if(isNaN(r)){u=a=64}else if(isNaN(i)){a=64}t=t+this._keyStr.charAt(s)+this._keyStr.charAt(o)+this._keyStr.charAt(u)+this._keyStr.charAt(a)}return t},decode:function(e){var t="";var n,r,i;var s,o,u,a;var f=0;e=e.replace(/[^A-Za-z0-9+/=]/g,"");while(f<e.length){s=this._keyStr.indexOf(e.charAt(f++));o=this._keyStr.indexOf(e.charAt(f++));u=this._keyStr.indexOf(e.charAt(f++));a=this._keyStr.indexOf(e.charAt(f++));n=s<<2|o>>4;r=(o&15)<<4|u>>2;i=(u&3)<<6|a;t=t+String.fromCharCode(n);if(u!=64){t=t+String.fromCharCode(r)}if(a!=64){t=t+String.fromCharCode(i)}}t=Base64._utf8_decode(t);return t},_utf8_encode:function(e){e=e.replace(/rn/g,"n");var t="";for(var n=0;n<e.length;n++){var r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r)}else if(r>127&&r<2048){t+=String.fromCharCode(r>>6|192);t+=String.fromCharCode(r&63|128)}else{t+=String.fromCharCode(r>>12|224);t+=String.fromCharCode(r>>6&63|128);t+=String.fromCharCode(r&63|128)}}return t},_utf8_decode:function(e){var t="";var n=0;var r=c1=c2=0;while(n<e.length){r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r);n++}else if(r>191&&r<224){c2=e.charCodeAt(n+1);t+=String.fromCharCode((r&31)<<6|c2&63);n+=2}else{c2=e.charCodeAt(n+1);c3=e.charCodeAt(n+2);t+=String.fromCharCode((r&15)<<12|(c2&63)<<6|c3&63);n+=3}}return t}}
                
                var data_return = data.replace('uri_boleto', Base64.encode(url));
                
                $("#_modal_count").modal('show');
                $("#load_boleto").html(data_return);
            }
        });
    }
    function load_conta_carne(url){
        var acao = "acao=load_carne_financeiro";
        $.ajax({
            type: 'POST',
            url: "_controller/_empresa.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                var Base64={_keyStr:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",encode:function(e){var t="";var n,r,i,s,o,u,a;var f=0;e=Base64._utf8_encode(e);while(f<e.length){n=e.charCodeAt(f++);r=e.charCodeAt(f++);i=e.charCodeAt(f++);s=n>>2;o=(n&3)<<4|r>>4;u=(r&15)<<2|i>>6;a=i&63;if(isNaN(r)){u=a=64}else if(isNaN(i)){a=64}t=t+this._keyStr.charAt(s)+this._keyStr.charAt(o)+this._keyStr.charAt(u)+this._keyStr.charAt(a)}return t},decode:function(e){var t="";var n,r,i;var s,o,u,a;var f=0;e=e.replace(/[^A-Za-z0-9+/=]/g,"");while(f<e.length){s=this._keyStr.indexOf(e.charAt(f++));o=this._keyStr.indexOf(e.charAt(f++));u=this._keyStr.indexOf(e.charAt(f++));a=this._keyStr.indexOf(e.charAt(f++));n=s<<2|o>>4;r=(o&15)<<4|u>>2;i=(u&3)<<6|a;t=t+String.fromCharCode(n);if(u!=64){t=t+String.fromCharCode(r)}if(a!=64){t=t+String.fromCharCode(i)}}t=Base64._utf8_decode(t);return t},_utf8_encode:function(e){e=e.replace(/rn/g,"n");var t="";for(var n=0;n<e.length;n++){var r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r)}else if(r>127&&r<2048){t+=String.fromCharCode(r>>6|192);t+=String.fromCharCode(r&63|128)}else{t+=String.fromCharCode(r>>12|224);t+=String.fromCharCode(r>>6&63|128);t+=String.fromCharCode(r&63|128)}}return t},_utf8_decode:function(e){var t="";var n=0;var r=c1=c2=0;while(n<e.length){r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r);n++}else if(r>191&&r<224){c2=e.charCodeAt(n+1);t+=String.fromCharCode((r&31)<<6|c2&63);n+=2}else{c2=e.charCodeAt(n+1);c3=e.charCodeAt(n+2);t+=String.fromCharCode((r&15)<<12|(c2&63)<<6|c3&63);n+=3}}return t}}
                
                var data_return = data.replace('uri_boleto', Base64.encode(url));
                
                $("#_modal_count").modal('show');
                $("#load_boleto").html(data_return);
            }
        });
    }
    function load_conta_mail(url){
        var acao = "acao=load_boleto_financeiro&url="+url;
        $.ajax({
            type: 'POST',
            url: "_controller/_financeiro.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                var Base64={_keyStr:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",encode:function(e){var t="";var n,r,i,s,o,u,a;var f=0;e=Base64._utf8_encode(e);while(f<e.length){n=e.charCodeAt(f++);r=e.charCodeAt(f++);i=e.charCodeAt(f++);s=n>>2;o=(n&3)<<4|r>>4;u=(r&15)<<2|i>>6;a=i&63;if(isNaN(r)){u=a=64}else if(isNaN(i)){a=64}t=t+this._keyStr.charAt(s)+this._keyStr.charAt(o)+this._keyStr.charAt(u)+this._keyStr.charAt(a)}return t},decode:function(e){var t="";var n,r,i;var s,o,u,a;var f=0;e=e.replace(/[^A-Za-z0-9+/=]/g,"");while(f<e.length){s=this._keyStr.indexOf(e.charAt(f++));o=this._keyStr.indexOf(e.charAt(f++));u=this._keyStr.indexOf(e.charAt(f++));a=this._keyStr.indexOf(e.charAt(f++));n=s<<2|o>>4;r=(o&15)<<4|u>>2;i=(u&3)<<6|a;t=t+String.fromCharCode(n);if(u!=64){t=t+String.fromCharCode(r)}if(a!=64){t=t+String.fromCharCode(i)}}t=Base64._utf8_decode(t);return t},_utf8_encode:function(e){e=e.replace(/rn/g,"n");var t="";for(var n=0;n<e.length;n++){var r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r)}else if(r>127&&r<2048){t+=String.fromCharCode(r>>6|192);t+=String.fromCharCode(r&63|128)}else{t+=String.fromCharCode(r>>12|224);t+=String.fromCharCode(r>>6&63|128);t+=String.fromCharCode(r&63|128)}}return t},_utf8_decode:function(e){var t="";var n=0;var r=c1=c2=0;while(n<e.length){r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r);n++}else if(r>191&&r<224){c2=e.charCodeAt(n+1);t+=String.fromCharCode((r&31)<<6|c2&63);n+=2}else{c2=e.charCodeAt(n+1);c3=e.charCodeAt(n+2);t+=String.fromCharCode((r&15)<<12|(c2&63)<<6|c3&63);n+=3}}return t}}
                
                var data_return = data.replace('uri_boleto', Base64.encode(url));
                
                $("#_modal_count_mail").modal('show');
                $("#retorno_mail").html(data_return);
                $("#retorno_mail_msg").html('');
                $("#resposta_mail").html('');
            }
        });
    }
    function carrega_msg_financeiro(){
        var id_msg_financeiro = $(".txt_mail_msg").val();
        var acao = "acao=load_msg_financeiro&id_msg_financeiro="+id_msg_financeiro;
        $.ajax({
            type: 'POST',
            url: "_controller/_financeiro.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                $("#retorno_mail_msg").html(data);
            }
        });
    }
    function send_mail(){
        var msg_financeiro_texto = $(".msg_financeiro_texto").val();
        var msg_financeiro_id = $(".msg_financeiro_id").val();
        var msg_financeiro_md5 = $(".txt_mail_msg").val();
        var msg_financeiro_boleto = $(".msg_financeiro_boleto").val();
        var msg_data_vencimento = $(".financeiro_new_data_vencimento").val();
        var msg_envio_juros = $(".financeiro_envio_juros").val();
        var msg_envio_email = $(".financeiro_email_envio").val();
        var msg_envio_fatura = $(".financeiro_envio_fatura").val();
        var acao = "acao=mail_financeiro&msg_financeiro_id="+msg_financeiro_id+"&msg_financeiro_texto="+msg_financeiro_texto+"&msg_financeiro_md5="+msg_financeiro_md5+"&msg_financeiro_boleto="+msg_financeiro_boleto+"&msg_financeiro_data_vencimento="+msg_data_vencimento+"&msg_envio_juros="+msg_envio_juros+"&msg_envio_email="+msg_envio_email+"&msg_envio_fatura="+msg_envio_fatura;
        $.ajax({
            type: 'POST',
            url: "_controller/_financeiro.php",
            data: acao,
            beforeSend: function (){
                $('#resposta_mail').html('<div class="alert alert-success"><img src="_img/load.gif" class="load" /> <strong>Aguarde!</strong> Estou fazendo o envio do arquivo</div>');
            },
            success: function (data) {
                load_out();
                $('#resposta_mail').html('<div class="alert alert-success">'+data+'</div>');
            }
        });
    }
    function load_conta_remessa(){
        var acao = "acao=load_remessa_financeiro";
        $.ajax({
            type: 'POST',
            url: "_controller/_empresa.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                $("#_modal_count_remessa").modal('show');
                $("#load_remessa").html(data);
            }
        });
    }
    function buscar_contato(){
        var search_nome_id_contato = $(".search_nome_id_contato").val();
        var acao = "acao=load_contato_id&id="+search_nome_id_contato;
        
        if(search_nome_id_contato){
            $.ajax({
                type: 'POST',
                url: "_controller/_contato.php",
                data: acao,
                beforeSend: load_in(),
                success: function (data) {
                    load_out();
                    var data_return = jQuery.parseJSON(data);
                    if(data_return[0].label !== ''){
                        $(".search_nome_contato").val(data_return[0].label);
                    }else{
                        $("#_modal").modal('show');
                        $("#title_modal").html('Erro');
                        $("#texto_modal").html('Ops, não foi encontrado nenhum registro');
                        $("#buttons_modal").html('');
                        $(".search_nome_id_contato").val('');
                        $(".search_nome_contato").val('');
                    }
                }
            });
        }
    }
    var selectionFormatter = function(value, data, cell, row, options, formatterParams){
        var rowSelect = $("<input type='checkbox' class='row-select' value='"+data.financeiro_id+"'>");
        
        rowSelect.on("change", function(){
            if($(this).is(":checked")){
                $(this).closest(".tabulator-row").addClass("selected");
            }else{
                $(this).closest(".tabulator-row").removeClass("selected");
            }
        });
        return rowSelect;
    }
    var infoIcon = function (value, data, cell, row, options) { //plain text value
        return "<i class='fa fa-info' style='vertical-align:middle; padding:2px 0;' title='Informações Financeiro'></i> ";
    };
	var registerIcon = function (value, data, cell, row, options) { //plain text value
        return "<i class='fa fa-cubes' style='vertical-align:middle; padding:2px 0;' title='Registrar Boleto'></i> ";
    };
    $("#load_financeiro").tabulator({
        height: "250px",
        fitColumns: true,
        ajaxURL: "_controller/_financeiro.php?OP=<?=$_GET['OP'];?>",
        ajaxParams: {acao: "load"},
        pagination: "remote",
        paginationSize: <?php echo $_SESSION['registros_financeiro'];?>,
        paginationDataSent: {"page": "pageNo"},
        columns: [
            {
                formatter: infoIcon, width: 40, align: "center", onClick: function (e, cell, val, data) {
                    info_financeiro(data.financeiro_id);
                }
            },
			{
                formatter: registerIcon, width: 40, align: "center", onClick: function (e, cell, val, data) {
                    window.open('caixa-webservice-master/index.php?id='+data.financeiro_id, '_blank');
                }
            },
            {title:"<input type='checkbox' class='select-all'>", formatter:selectionFormatter, width: 35},
            {title: "ID", field: "financeiro_codigo", sorter: "int", width: 100},
            {title: "Descrição", field: "financeiro_descricao", sorter: "string"},
            {title: "Contato", field: "financeiro_id_contato", sorter: "string"},
            {title: "Valor", field: "financeiro_valor", sorter: "string"},
            {title: "Data Vencimento", field: "financeiro_data_vencimento", sorter: "string"},
            {title: "Situação", field: "financeiro_status", sorter: "string"},
            {title: "Valor Pagamento", field: "financeiro_valor_pagamento", sorter: "string"},
            {title: "Data Pagamento", field: "financeiro_data_pagamento", sorter: "string"}
        ],
        ajaxResponse:function(url, params, response){
            $("#financeiro_quantidade_contas").html('<h4>'+response.quantidade_contas+'</h4>');
            $("#financeiro_valor_aberto").html('<h4>R$ '+response.valor_aberto+'</h4>');
            $("#financeiro_valor_pago").html('<h4>R$ '+response.valor_pago+'</h4>');
            $("#financeiro_valor_cancelado").html('<h4>R$ '+response.valor_cancelado+'</h4>');
            $("#financeiro_valor_total").html('<h4>R$ '+response.valor_total+'</h4>');
            $("#financeiro_valor_renegociado").html('<h4>R$ '+response.valor_renegociado+'</h4>');
            $("#financeiro_valor_juros").html('<h4>R$ '+response.valor_total_juros+'</h4>');
            $("#financeiro_valor_multa").html('<h4>R$ '+response.valor_total_multa+'</h4>');
            $("#financeiro_valor_atualizado").html('<h4>R$ '+response.valor_total_atualizado+'</h4>');
            $("#financeiro_protestado").html('<h4>'+response.financeiro_protestado+'</h4>');
            return response;
        }
    });
    $("#load_financeiro .select-all").on("change", function(){
        if($(this).is(":checked")){
            $("#load_financeiro .row-select").prop("checked", true).closest(".tabulator-row").addClass("selected");
        }else{
            $("#load_financeiro .row-select").prop("checked", false).closest(".tabulator-row").removeClass("selected");
        }
    });
    function info_financeiro(financeiro_id){
        var acao = "acao=info_financeiro&id="+financeiro_id;
        
        if(financeiro_id){
            $.ajax({
                type: 'POST',
                url: "_controller/_financeiro.php",
                data: acao,
                beforeSend: load_in(),
                success: function (data) {
                    load_out();
                    $("#_modal").modal('show');
                    $("#title_modal").html('Informações Financeiro');
                    $("#texto_modal").html(data);
                    $("#buttons_modal").html('');
                }
            });
        }
    }
    function link_update(){
        var objCheckBox = $("#load_financeiro .row-select");
        var sel = 0;
        var selecionadas = "";
        for (i=0; i < objCheckBox.length; i++) {
            if (objCheckBox[i].checked) {
                sel++;
                selecionadas += objCheckBox[i].value+",";
            }
	}
        if(Number(sel) === 0){
            $("#_modal").modal('show');
            $("#title_modal").html('Erro');
            $("#texto_modal").html('Ops, é preciso selecionar contas');
            $("#buttons_modal").html('');
        }else if(sel > 1){
            $("#_modal").modal('show');
            $("#title_modal").html('Erro');
            $("#texto_modal").html('Ops, é preciso selecionar apenas uma conta');
            $("#buttons_modal").html('');
        }else{
            var url = selecionadas.substr(0,(selecionadas.length - 1));
            carrega_pagina('financeiro', 'update.php?OP=<?=$_GET['OP'];?>&id='+url);
        }
    }
    function link_update_varios(){
        var objCheckBox = $("#load_financeiro .row-select");
        var sel = 0;
        var selecionadas = "";
        for (i=0; i < objCheckBox.length; i++) {
            if (objCheckBox[i].checked) {
                sel++;
                selecionadas += objCheckBox[i].value+",";
            }
	}
        if(Number(sel) === 0){
            $("#_modal").modal('show');
            $("#title_modal").html('Erro');
            $("#texto_modal").html('Ops, é preciso selecionar contas');
            $("#buttons_modal").html('');
        }else{
            var url = selecionadas.substr(0,(selecionadas.length - 1));
            carrega_pagina('financeiro', 'update_varios.php?OP=<?=$_GET['OP'];?>&id='+url);
        }
    }
    function link_download(){
        var objCheckBox = $("#load_financeiro .row-select");
        var sel = 0;
        var selecionadas = "";
        for (i=0; i < objCheckBox.length; i++) {
            if (objCheckBox[i].checked) {
                sel++;
                selecionadas += objCheckBox[i].value+",";
            }
	}
        if(Number(sel) === 0){
            $("#_modal").modal('show');
            $("#title_modal").html('Erro');
            $("#texto_modal").html('Ops, é preciso selecionar contas');
            $("#buttons_modal").html('');
        }else if(sel > 1){
            $("#_modal").modal('show');
            $("#title_modal").html('Erro');
            $("#texto_modal").html('Ops, é preciso selecionar apenas uma conta');
            $("#buttons_modal").html('');
        }else{
            var url = selecionadas.substr(0,(selecionadas.length - 1));
            carrega_pagina('financeiro', 'download.php?OP=<?=$_GET['OP'];?>&id='+url);
        }
    }
    function link_reverse(){
        var objCheckBox = $("#load_financeiro .row-select");
        var sel = 0;
        var selecionadas = "";
        for (i=0; i < objCheckBox.length; i++) {
            if (objCheckBox[i].checked) {
                sel++;
                selecionadas += objCheckBox[i].value+",";
            }
	}
        if(Number(sel) === 0){
            $("#_modal").modal('show');
            $("#title_modal").html('Erro');
            $("#texto_modal").html('Ops, é preciso selecionar contas');
            $("#buttons_modal").html('');
        }else if(sel > 1){
            $("#_modal").modal('show');
            $("#title_modal").html('Erro');
            $("#texto_modal").html('Ops, é preciso selecionar apenas uma conta');
            $("#buttons_modal").html('');
        }else{
            var url = selecionadas.substr(0,(selecionadas.length - 1));
            carrega_pagina('financeiro', 'reverse.php?OP=<?=$_GET['OP'];?>&id='+url);
        }
    }
    function link_cancel(){
        var objCheckBox = $("#load_financeiro .row-select");
        var sel = 0;
        var selecionadas = "";
        for (i=0; i < objCheckBox.length; i++) {
            if (objCheckBox[i].checked) {
                sel++;
                selecionadas += objCheckBox[i].value+",";
            }
	}
        if(Number(sel) === 0){
            $("#_modal").modal('show');
            $("#title_modal").html('Erro');
            $("#texto_modal").html('Ops, é preciso selecionar contas');
            $("#buttons_modal").html('');
        }else{
            var url = selecionadas.substr(0,(selecionadas.length - 1));
            carrega_pagina('financeiro', 'cancel.php?OP=<?=$_GET['OP'];?>&id='+url);
        }
    }
    function link_itens_vendas(){
        var objCheckBox = $("#load_financeiro .row-select");
        var sel = 0;
        var selecionadas = "";
        for (i=0; i < objCheckBox.length; i++) {
            if (objCheckBox[i].checked) {
                sel++;
                selecionadas += objCheckBox[i].value+",";
            }
	}
        if(Number(sel) === 0){
            $("#_modal").modal('show');
            $("#title_modal").html('Erro');
            $("#texto_modal").html('Ops, é preciso selecionar contas');
            $("#buttons_modal").html('');
        }else if(sel > 1){
            $("#_modal").modal('show');
            $("#title_modal").html('Erro');
            $("#texto_modal").html('Ops, é preciso selecionar apenas uma conta');
            $("#buttons_modal").html('');
        }else{
            var url = selecionadas.substr(0,(selecionadas.length - 1));
            carrega_pagina('financeiro', 'item.php?OP=<?=$_GET['OP'];?>&id='+url);
        }
    }
    function link_segunda_via(){
        var objCheckBox = $("#load_financeiro .row-select");
        var sel = 0;
        var selecionadas = "";
        for (i=0; i < objCheckBox.length; i++) {
            if (objCheckBox[i].checked) {
                sel++;
                selecionadas += objCheckBox[i].value+",";
            }
	}
        if(Number(sel) === 0){
            $("#_modal").modal('show');
            $("#title_modal").html('Erro');
            $("#texto_modal").html('Ops, é preciso selecionar contas');
            $("#buttons_modal").html('');
        }else{
            var url = selecionadas.substr(0,(selecionadas.length - 1));
            $("#_modal_segunda_via").modal('show');
            $("#title_modal").html('Segunda Via');
            $("#texto_modal").html('');
            $('.segunda_id').val(url);
            $("#buttons_modal_segunda_via").html('<a href="#" onclick="abrir_carne_segunda_via();" class="btn btn-primary">Gerar Boleto</a>');
        }
    }
    function abrir_carne_segunda_via(){
        var data_vencimento = $('.segunda_data_vencimento').val();
        var juros_multa     = $('.segunda_envio_juros').val();
        var codigo_contas   = $('.segunda_id').val();
        window.open('_boleto_carne/boleto_cef.php?03='+codigo_contas+'&01='+data_vencimento+'&04='+juros_multa+'&ide=00','_blank');
    }
    function link_boleto(){
        var objCheckBox = $("#load_financeiro .row-select");
        var sel = 0;
        var selecionadas = "";
        for (i=0; i < objCheckBox.length; i++) {
            if (objCheckBox[i].checked) {
                sel++;
                selecionadas += objCheckBox[i].value+",";
            }
	}
        if(Number(sel) === 0){
            $("#_modal").modal('show');
            $("#title_modal").html('Erro');
            $("#texto_modal").html('Ops, é preciso selecionar contas');
            $("#buttons_modal").html('');
        }else{
            var url = selecionadas.substr(0,(selecionadas.length - 1));
            load_conta_boleto(url);
        }
    }
    function link_carne(){
        var objCheckBox = $("#load_financeiro .row-select");
        var sel = 0;
        var selecionadas = "";
        for (i=0; i < objCheckBox.length; i++) {
            if (objCheckBox[i].checked) {
                sel++;
                selecionadas += objCheckBox[i].value+",";
            }
	}
        if(Number(sel) === 0){
            $("#_modal").modal('show');
            $("#title_modal").html('Erro');
            $("#texto_modal").html('Ops, é preciso selecionar contas');
            $("#buttons_modal").html('');
        }else{
            var url = selecionadas.substr(0,(selecionadas.length - 1));
            load_conta_carne(url);
        }
    }
    function link_enviar_mail(){
        var objCheckBox = $("#load_financeiro .row-select");
        var sel = 0;
        var selecionadas = "";
        for (i=0; i < objCheckBox.length; i++) {
            if (objCheckBox[i].checked) {
                sel++;
                selecionadas += objCheckBox[i].value+",";
            }
	}
        if(Number(sel) === 0){
            $("#_modal").modal('show');
            $("#title_modal").html('Erro');
            $("#texto_modal").html('Ops, é preciso selecionar contas');
            $("#buttons_modal").html('');
        }else{
            var url = selecionadas.substr(0,(selecionadas.length - 1));
            load_conta_mail(url);
        }
    }
    function email_massa(element){

        var objCheckBox = $("#load_financeiro .row-select");
        var sel = 0;
        var selecionadas = "";
        for (i=0; i < objCheckBox.length; i++) {
            if (objCheckBox[i].checked) {
                sel++;
                selecionadas += objCheckBox[i].value+",";
            }
        }
        if(Number(sel) === 0){
            $("#_modal").modal('show');
            $("#title_modal").html('Erro');
            $("#texto_modal").html('Ops, é preciso selecionar contas');
            $("#buttons_modal").html('');
        }else{
            var data = selecionadas.substr(0,(selecionadas.length - 1));
            console.log(data);
            var input_ids = $("input[name=input_ids]");
            input_ids.val(data)
            var formEmailMassa = $("form[name=formEmailMassa]");
            formEmailMassa.submit();
            // console.log(formEmailMassa.attr("action"))
        }
    }
    function link_remessa(){
        var objCheckBox = $("#load_financeiro .row-select");
        var sel = 0;
        var selecionadas = "";
        for (i=0; i < objCheckBox.length; i++) {
            if (objCheckBox[i].checked) {
                sel++;
                selecionadas += objCheckBox[i].value+",";
            }
	}
        load_conta_remessa();
    }
    function link_ged(){
        var objCheckBox = $("#load_financeiro .row-select");
        var sel = 0;
        var selecionadas = "";
        for (i=0; i < objCheckBox.length; i++) {
            if (objCheckBox[i].checked) {
                sel++;
                selecionadas += objCheckBox[i].value+",";
            }
	}
        if(Number(sel) === 0){
            $("#_modal").modal('show');
            $("#title_modal").html('Erro');
            $("#texto_modal").html('Ops, é preciso selecionar contas');
            $("#buttons_modal").html('');
        }else if(sel > 1){
            $("#_modal").modal('show');
            $("#title_modal").html('Erro');
            $("#texto_modal").html('Ops, é preciso selecionar apenas uma conta');
            $("#buttons_modal").html('');
        }else{
            var url = selecionadas.substr(0,(selecionadas.length - 1));
            carrega_pagina('financeiro', 'ged.php?OP=<?=$_GET['OP'];?>&id='+url);
        }
    }
    
    function search(){
        var search_nome_id_contato      = $(".search_nome_id_contato").val();
        var search_situacao             = $(".search_situacao").val();
        var search_tipo_pesquisa        = $(".search_tipo_pesquisa").val();
        var search_data_inicial         = $(".search_data_inicial").val();
        var search_data_final           = $(".search_data_final").val();
        var search_itens_pesquisa       = $(".search_itens_pesquisa").val();
        var search_pesquisa             = $(".search_pesquisa").val();
        var search_fixo                 = $(".search_fixo").val();
        var search_app_financeira       = $(".search_app_financeira").val();
        var search_id_plano_conta       = $(".search_id_plano_conta").val();
        var search_id_tipo_documento    = $(".search_id_tipo_documento").val();
        var search_registros            = $(".search_registros").val();
        var search_id_vendedor          = $(".financeiro_id_vendedor").val();
        var search_id_associado         = $(".financeiro_id_associado").val();
        var search_remessa              = $(".search_remessa").val();
        var search_boleto               = $(".search_boleto").val();
        
        var acao = "acao=load&search=true&id_contato="+search_nome_id_contato+"&situacao="+search_situacao+"&tipo_pesquisa="+search_tipo_pesquisa+"&data_inicial="+search_data_inicial+"&data_final="+search_data_final+"&itens_pesquisa="+search_itens_pesquisa+"&pesquisa="+search_pesquisa+"&fixo="+search_fixo+"&app_financeira="+search_app_financeira+"&id_plano_conta="+search_id_plano_conta+"&id_tipo_documento="+search_id_tipo_documento+"&registros="+search_registros+"&id_vendedor="+search_id_vendedor+"&id_associado="+search_id_associado+"&remessa="+search_remessa+"&boleto="+search_boleto;
        
        $.ajax({
            type: 'GET',
            url: "_controller/_financeiro.php",
            data: acao,
            beforeSend: load_in(),
            success: function () {
                load_out();
                //$("#load_financeiro").tabulator("setPageSize", Number(search_registros));
                //$("#load_financeiro").tabulator("setData", "_controller/_financeiro.php?acao=load&OP=<?=$_GET['OP'];?>");
                carrega_pagina('financeiro', 'index.php?OP=<?=$_GET['OP'];?>');
            }
        });
    }
    $(function(){
        var acao = "acao=load_associado_select";
        $.ajax({
            type: 'GET',
            url: "_controller/_associado.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                var data_return = jQuery.parseJSON(data);
                var options = '<option value="0"></option>';
                for (var i = 0; i < data_return.data.length; i++) {
                    options += '<option value="' + data_return.data[i].associado_id + '">' + data_return.data[i].associado_nome_razao + '</option>';
                }
                $('.financeiro_id_associado').html(options).show();
                load_out();
            }
        });
        load_out();
    });
    function gerar_remessa_completa(){
        var num_remessa = $(".num_remessa").val();
        
        if(num_remessa){
            window.open('view/financeiro/remessa.php?id='+num_remessa,'_blank');
        }
    }
</script>