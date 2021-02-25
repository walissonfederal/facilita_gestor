<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Saque
                                <a href="javascript::" onclick="carrega_pagina('mmn_saque', 'index.php');" class="btn btn-primary">Voltar</a>
                            </h3>
                        </div>
                        <div class="box-content">
                            <form name="formUpload" id="formUpload" method="post">
                                <fieldset>
                                    <div class="row">
                                        <div class="form-group col-lg-2">
                                            <label>Data</label>
                                            <input type="date" class="form-control saque_data" name="saque_data"/>
                                        </div>
                                        <div class="form-group col-lg-1">
                                            <label>Solicitado</label><br />
                                            <span class="saque_valor"></span>
                                        </div>
                                        <div class="form-group col-lg-1">
                                            <label>Valor Taxa</label><br />
                                            <span class="saque_taxa"></span>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Usuário</label><br />
                                            <span class="saque_id_user"></span>
                                        </div>
                                        <div class="form-group col-lg-2" id="return_saque_status">
                                            <label>Status</label>
                                            <select name="saque_status" class="form-control saque_status">
                                                <option value="0">Aberto</option>
                                                <option value="1">Pago</option>
                                                <option value="2">Cancelado</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Data Pagamento</label>
                                            <input type="date" class="form-control saque_data_pagamento" name="saque_data_pagamento"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Valor Pagamento</label>
                                            <input type="text" class="form-control saque_valor_pagamento" id="valor_1" name="saque_valor_pagamento"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-12">
                                            <label>Obs Saque<small>(essa mensagem ficará disponível no escritorio virtual do cliente, até que ele veja)</small></label>
                                            <textarea name="msg_saque_msg" class="form-control msg_saque_msg" rows="5"></textarea>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-12">
                                            <label>Arquivo(PDF)</label>
                                            <input type="file" name="arquivo" id="arquivo" size="45" />
                                        </div>
                                    </div>
                                    <hr />
									<div id="estornar_saque">
										<a href="#_modal_estorno_saque" class="btn btn-danger" data-toggle="modal" role="button">Estornar Saque?</a>
									</div>
									<hr />
                                    <div id="resposta"></div>
                                    <div class="retorno_comprovante"></div>
                                    <hr />
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <strong>Banco: </strong><span class="conta_id_banco"></span><br />
                                            <strong>Tipo: </strong><span class="conta_tipo"></span><br />
                                            <strong>Títular: </strong><span class="conta_titular"></span><br />
                                            <strong>CPF Títular: </strong><span class="conta_cpf_titular"></span><br />
                                            <strong>Agência: </strong><span class="conta_agencia"></span><br />
                                            <strong>Conta: </strong><span class="conta_conta"></span><br />
                                            <strong>DG Conta: </strong><span class="conta_dg_conta"></span><br />
                                            <strong>Operação: </strong><span class="conta_operacao"></span><br />
                                            <strong>Obs: </strong><span class="conta_obs"></span><br />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-12" align="right">
                                            <button type="button" class="btn btn-primary" id="btnEnviar" >Gravar</button>
                                            <a href="javascript::" onclick="carrega_pagina('mmn_saque', 'index.php');" class="btn btn-danger">Voltar</a>
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
<div id="_modal_estorno_saque" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Estornar Saque</h4>
            </div>
            <!-- /.modal-header -->
            <div class="modal-body">
                <div class="row">
					<div class="form-group col-lg-12">
						<label>Mensagem</label>
						<input type="text" class="form-control mensagem_estorno"/> 
					</div>
				</div>
				<div class="row">
					<div class="form-group col-lg-12">
						<p><strong>Sugestão: </strong>Estorno feito devido a conta não ter sido preenchida da forma correta.</p>
					</div>
				</div>
				<div class="row">
					<div class="form-group col-lg-12">
						<button type="button" class="btn btn-primary" onclick="send_estorno();">Estornar</button>
					</div>
				</div>
				<div id="retorno_estorno"></div>
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
<script>
	function send_estorno(){
        var mensagem_estorno = $(".mensagem_estorno").val();
        
        var acao = "acao=estornar&id=<?=$_GET['id'];?>&mensagem="+mensagem_estorno;
        
        $.ajax({
            type: 'GET',
            url: "_controller/_mmn_saque.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
				var data_return = jQuery.parseJSON(data);
				if(data_return.type === 'success'){
					$("#retorno_estorno").html(data_return.msg);
					$("#_modal_estorno_saque").modal("hide");
					load_update_saque();
				}else{
					$("#retorno_estorno").html(data_return.msg);
				}
                
            }
        });
    }
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
                        tratar_retorno();
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
                url: '_controller/_mmn_saque.php?acao=enviar&id=<?=$_GET['id'];?>&saque_data='+$(".saque_data").val()+"&saque_status="+$(".saque_status").val()+"&saque_data_pagamento="+$(".saque_data_pagamento").val()+"&saque_valor_pagamento="+$(".saque_valor_pagamento").val()+"&msg_saque_msg="+$(".msg_saque_msg").val(),
                resetForm: false
            }).submit();
        });
    });
	function load_update_saque(){
		var acao = "acao=load_update&id=<?=$_GET['id'];?>";
        $.ajax({
            type: 'POST',
            url: "_controller/_mmn_saque.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                var data_return = jQuery.parseJSON(data);
                $(".saque_data").val(data_return[0].saque_data);
                $(".saque_valor").html('R$ '+data_return[0].saque_valor);
                $(".saque_taxa").html('R$ '+data_return[0].saque_taxa);
                $(".saque_id_user").html(data_return[0].saque_id_user);
                $(".saque_status").val(data_return[0].saque_status);
                $(".saque_data_pagamento").val(data_return[0].saque_data_pagamento);
                $(".saque_valor_pagamento").val(data_return[0].saque_valor_pagamento);
				
				if(data_return[0].saque_status === '0'){
					$("#estornar_saque").show();
				}else{
					$("#estornar_saque").hide();
				}
                
                
                $(".conta_id_banco").html(data_return[0].conta_id_banco);
                $(".conta_tipo").html(data_return[0].conta_tipo);
                $(".conta_titular").html(data_return[0].conta_titular);
                $(".conta_cpf_titular").html(data_return[0].conta_cpf_titular);
                $(".conta_agencia").html(data_return[0].conta_agencia);
                $(".conta_conta").html(data_return[0].conta_conta);
                $(".conta_dg_conta").html(data_return[0].conta_dg_conta);
                $(".conta_operacao").html(data_return[0].conta_operacao);
                $(".conta_obs").html(data_return[0].conta_obs);
                
                $(".retorno_comprovante").html('<a href="facilita_gestor/'+data_return[0].saque_comprovante+'" target="_blank">Comprovante</a>');
                load_out();
            }
        });
	}
    $(function(){
        var acao = "acao=load_update&id=<?=$_GET['id'];?>";
        $.ajax({
            type: 'POST',
            url: "_controller/_mmn_saque.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                var data_return = jQuery.parseJSON(data);
                $(".saque_data").val(data_return[0].saque_data);
                $(".saque_valor").html('R$ '+data_return[0].saque_valor);
                $(".saque_taxa").html('R$ '+data_return[0].saque_taxa);
                $(".saque_id_user").html(data_return[0].saque_id_user);
                $(".saque_status").val(data_return[0].saque_status);
                $(".saque_data_pagamento").val(data_return[0].saque_data_pagamento);
                $(".saque_valor_pagamento").val(data_return[0].saque_valor_pagamento);
				
				if(data_return[0].saque_status === '0'){
					$("#estornar_saque").show();
				}else{
					$("#estornar_saque").hide();
				}
                
                
                $(".conta_id_banco").html(data_return[0].conta_id_banco);
                $(".conta_tipo").html(data_return[0].conta_tipo);
                $(".conta_titular").html(data_return[0].conta_titular);
                $(".conta_cpf_titular").html(data_return[0].conta_cpf_titular);
                $(".conta_agencia").html(data_return[0].conta_agencia);
                $(".conta_conta").html(data_return[0].conta_conta);
                $(".conta_dg_conta").html(data_return[0].conta_dg_conta);
                $(".conta_operacao").html(data_return[0].conta_operacao);
                $(".conta_obs").html(data_return[0].conta_obs);
                
                $(".retorno_comprovante").html('<a href="facilita_gestor/'+data_return[0].saque_comprovante+'" target="_blank">Comprovante</a>');
                load_out();
            }
        });
    });
    $("#valor_1").maskMoney({thousands:'', decimal:'.'});
</script>