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
							<form action="" id="create_insert">
                                <fieldset>
									<div class="row">
										<div class="form-group col-lg-12">
											<label>Contato(Nome Razão, Nome Fantasia, CPF / CNPJ)</label>
											<input type="hidden" class="id_contato"/>
											<input type="text" class="form-control search_nome_contato"/>
										</div>
									</div>
									<div class="row">
										<div class="form-group col-lg-4">
											<label>Nome</label>
											<input type="text" name="itens_plp_nome" class="form-control itens_plp_nome" autocomplete="off"/>
										</div>
										<div class="form-group col-lg-2">
											<label>CEP</label>
											<input type="text" class="form-control itens_plp_cep cep" name="itens_plp_cep" autocomplete="off"/>
										</div>
										<div class="form-group col-lg-5">
											<label>Endereço</label>
											<input type="text" class="form-control itens_plp_endereco" name="itens_plp_endereco" autocomplete="off"/>
										</div>
										<div class="form-group col-lg-1">
											<label>Nº</label>
											<input type="text" class="form-control itens_plp_numero" name="itens_plp_numero" autocomplete="off"/>
										</div>
									</div>
									<div class="row">
										<div class="form-group col-lg-4">
											<label>Complemento</label>
											<input type="text" class="form-control itens_plp_complemento" name="itens_plp_complemento" autocomplete="off"/>
										</div>
										<div class="form-group col-lg-2">
											<label>Bairro</label>
											<input type="text" class="form-control itens_plp_bairro" name="itens_plp_bairro" autocomplete="off"/>
										</div>
										<div class="form-group col-lg-3">
											<label>Cidade</label>
											<input type="text" class="form-control itens_plp_cidade" name="itens_plp_cidade" autocomplete="off"/>
										</div>
										<div class="form-group col-lg-1">
											<label>Estado</label>
											<input type="text" class="form-control itens_plp_uf" name="itens_plp_uf" autocomplete="off"/>
										</div>
										<div class="form-group col-lg-2">
											<label>PESO(0.050 = 50 Gramas) (0.500 = 500 Gramas)</label>
											<input type="text" class="form-control itens_plp_peso" name="itens_plp_peso" autocomplete="off"/>
											<input type="hidden" name="itens_plp_id_plp" value="<?=$_GET['id'];?>"/>
										</div>
									</div>
									<div class="row">
										<div class="form-group col-lg-12" align="right">
											<button type="button" class="btn btn-primary" onclick="create_insert();">Gravar</button>
											<a href="javascript::" onclick="carrega_pagina('plp', 'index.php');" class="btn btn-danger">Voltar</a>
										</div>
									</div>
								</fieldset>
                            </form>
							<hr />
                            <div class="row">
                                <div id="itens_plp"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function load_plp_grid(){
        var acao = "&acao=load_plp_grid&id_pedido=<?=$_GET['id'];?>";
        $.ajax({
            type: 'POST',
            url: "_controller/_plp.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                $("#itens_plp").html(data);
            }
        });
    }
	function delete_iten_etiqueta_sistema(id_item){
        var acao = "&acao=delete_iten_etiqueta_sistema&id_pedido="+id_item;
        $.ajax({
            type: 'POST',
            url: "_controller/_plp.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                load_plp_grid();
            }
        });
    }
	$(function() {
		load_plp_grid();
        $( ".search_nome_contato" ).autocomplete({
            source: "_controller/_plp.php?acao=load_contato_insert",
            minLength: 2,
            focus: function(event, ui) {
            	event.preventDefault();
            },
            select: function( event, ui ) {
                $(".id_contato").val(ui.item.value);
                $(".search_nome_contato").val(ui.item.label);
				load_campos_select_contato(ui.item.value);
                event.preventDefault();
            }
        });
    });
	function load_campos_select_contato(id_contato){
        var acao = "&acao=load_update_campos&id_contato="+id_contato;
        $.ajax({
            type: 'POST',
            url: "_controller/_plp.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                var data_return = jQuery.parseJSON(data);
                $(".itens_plp_nome").val(data_return[0].contato_nome_razao);
				$(".itens_plp_cep").val(data_return[0].contato_cep);
				$(".itens_plp_endereco").val(data_return[0].contato_endereco);
				$(".itens_plp_numero").val(data_return[0].contato_numero);
				$(".itens_plp_complemento").val(data_return[0].contato_complemento);
				$(".itens_plp_bairro").val(data_return[0].contato_bairro);
				$(".itens_plp_cidade").val(data_return[0].contato_cidade);
				$(".itens_plp_uf").val(data_return[0].contato_estado);
            }
        });
    }
	function create_insert(){
        var dados = $("#create_insert").serialize();
        var acao = "&acao=create_insert";
        
        $.ajax({
            type: 'POST',
            url: "_controller/_plp.php",
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
</script>