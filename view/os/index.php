<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Ordem de Serviço
                                <a href="javascript::" onclick="carrega_pagina('os', 'create.php');" class="btn btn-primary">Cadastrar Novo</a>
                            </h3>
                        </div>
                        <div class="box-content">
                            <form action="">
                                <fieldset>
                                    <div class="row">
                                        <div class="form-group col-lg-1">
                                            <label>ID Contato</label>
                                            <input type="text" class="form-control os_id_contato" name="os_id_contato" onblur="buscar_contato();"/>
                                        </div>
                                        <div class="form-group col-lg-5">
                                            <label>Contato</label>
                                            <input type="text" class="form-control os_nome_contato" name=""/>
                                        </div>
                                        <div class="form-group col-lg-1">
                                            <label>ID Técnico</label>
                                            <input type="text" class="form-control os_id_user" name="os_id_user" onblur="buscar_user();"/>
                                        </div>
                                        <div class="form-group col-lg-5">
                                            <label>Técnico</label>
                                            <input type="text" class="form-control os_nome_user" name=""/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-2">
                                            <label>ID</label>
                                            <input type="text" class="form-control os_id"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Status</label>
                                            <select class="form-control os_status" name="os_status">
                                                <option value=""></option>
                                                <option value="0">Orçamento</option>
                                                <option value="1">Aberto</option>
                                                <option value="2">Faturado</option>
                                                <option value="3">Finalizado</option>
                                                <option value="4">Cancelado</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>ID Responsável</label>
                                            <input type="text" class="form-control os_id_responsavel" name="os_id_responsavel" onblur="buscar_user_responsavel();"/>
                                        </div>
                                        <div class="form-group col-lg-6">
                                            <label>Responsável</label>
                                            <input type="text" class="form-control os_nome_user_resp" name=""/>
                                        </div>
                                    </div>
									<div class="row">
										<div class="form-group col-lg-2">
                                            <label>ID Quem Abriu</label>
                                            <input type="text" class="form-control os_id_user_inicio" name="os_id_user_inicio" onblur="buscar_user_inicio();"/>
                                        </div>
                                        <div class="form-group col-lg-6">
                                            <label>Nome Quem Abriu</label>
                                            <input type="text" class="form-control os_nome_user_inicio" name=""/>
                                        </div>
										<div class="form-group col-lg-4">
                                            <label>SERIAL</label>
                                            <input type="text" class="form-control os_veiculo_serial" name="os_veiculo_serial"/>
                                        </div>
									</div>
                                    <div class="row">
                                        <div class="form-group col-lg-2">
                                            <label>Tipo Pesquisa</label>
                                            <select class="form-control tipo_pesquisa">
                                                <option value="os.os_data_inicial">Data Inicial</option>
                                                <option value="os.os_data_final">Data Final</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Data Inicial</label>
                                            <input type="date" class="form-control search_data_inicial"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Data Final</label>
                                            <input type="date" class="form-control search_data_final"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Placa</label>
                                            <input type="text" class="form-control os_veiculo_placa" name=""/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Frota</label>
                                            <input type="text" class="form-control os_veiculo_frota" name=""/>
                                        </div>
										<div class="form-group col-lg-2">
                                            <label>Nome Associado</label>
                                            <input type="text" class="form-control os_veiculo_nome_associado" name=""/>
                                        </div>
                                    </div>
									<div class="row">
										<div class="form-group col-lg-1">
                                            <label>.</label><br />
                                            <button type="button" class="btn btn-primary" onclick="search();">Pesquisar</button>
                                        </div>
									</div>
                                </fieldset>
                            </form>
                            <hr />
                            <div id="load_os"></div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="box box-color blue box-condensed box-bordered">
                                        <div class="box-title" align="center">
                                            <h3>
                                                Quantidade OS
                                            </h3>
                                        </div>
                                        <div class="box-content">
                                            <div id="os_quantidade_os" align="center"><h4></h4></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="box box-color blue box-condensed box-bordered">
                                        <div class="box-title" align="center">
                                            <h3>
                                                Quantidade Veículos
                                            </h3>
                                        </div>
                                        <div class="box-content">
                                            <div id="os_quantidade_veiculos" align="center"><h4></h4></div>
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
<script>
    var updateIcon = function (value, data, cell, row, options) { //plain text value
        return "<i class='fa fa-pencil' style='vertical-align:middle; padding:2px 0;' title='Editar'></i> ";
    };
    var printIcon = function (value, data, cell, row, options) { //plain text value
        return "<i class='fa fa-print' style='vertical-align:middle; padding:2px 0;' title='Imprimir'></i> ";
    };
    var productIcon = function (value, data, cell, row, options) { //plain text value
        return "<i class='fa fa-send' style='vertical-align:middle; padding:2px 0;' title='Produtos / serviços'></i> ";
    };
    var facturarIcon = function (value, data, cell, row, options) { //plain text value
        return "<i class='fa fa-money' style='vertical-align:middle; padding:2px 0;' title='Faturar'></i> ";
    };
    var carsIcon = function (value, data, cell, row, options) { //plain text value
        return "<i class='fa fa-car' style='vertical-align:middle; padding:2px 0;' title='Veículos'></i> ";
    };
	var userIcon = function (value, data, cell, row, options) { //plain text value
        return "<i class='fa fa-user' style='vertical-align:middle; padding:2px 0;' title='Dados Clientes'></i> ";
    };
    $("#load_os").tabulator({
        height: "350px",
        fitColumns: true,
        ajaxURL: "_controller/_os.php",
        ajaxParams: {acao: "load"},
        pagination: "remote",
        paginationSize: 100,
        paginationDataSent: {"page": "pageNo"},
		rowFormatter:function(row, data){
			if(data.os_status == "Finalizado"){
				row.css({"background-color":"green"});
			}
		},
        columns: [
            {
                formatter: updateIcon, width: 40, align: "center", onClick: function (e, cell, val, data) {
                    carrega_pagina('os', 'update.php?id='+data.os_id);
                }
            },
            {
                formatter: printIcon, width: 40, align: "center", onClick: function (e, cell, val, data) {
                    window.open('view/os/print.php?id=' + data.os_id, '_blank');
                }
            },
            {
                formatter: productIcon, width: 40, align: "center", onClick: function (e, cell, val, data) {
                    carrega_pagina('os', 'product.php?id='+data.os_id);
                }
            },
            {
                formatter: facturarIcon, width: 40, align: "center", onClick: function (e, cell, val, data) {
                    carrega_pagina('os', 'facturar.php?id='+data.os_id);
                }
            },
            {
                formatter: carsIcon, width: 40, align: "center", onClick: function (e, cell, val, data) {
                    carrega_pagina('os', 'vehicles.php?id='+data.os_id);
                }
            },
			{
                formatter: userIcon, width: 40, align: "center", onClick: function (e, cell, val, data) {
                    $("#_modal").modal('show');
					$("#title_modal").html('Informação');
					$("#texto_modal").html('<strong>NOME: </strong>'+data.os_client_name+'<br /><strong>EMAIL: </strong>'+data.os_client_email+'<br /><strong>CEP: </strong>'+data.os_client_cep+'<br /><strong>ENDEREÇO: </strong>'+data.os_client_endereco+'<br /><strong>NÚMERO: </strong>'+data.os_client_numero+'<br /><strong>COMPLEMENTO: </strong>'+data.os_client_complemento+'<br /><strong>BAIRRO: </strong>'+data.os_client_bairro+'<br /><strong>CIDADE: </strong>'+data.os_client_cidade+'<br /><strong>UF: </strong>'+data.os_client_uf+'<br /><strong>CPF: </strong>'+data.os_client_cpf+'<br /><strong>TELEFONE/CELULAR: </strong>'+data.os_client_telefone+'');
					$("#buttons_modal").html('');
                }
            },
            {title: "ID", field: "os_id", sorter: "int", width: 50},
            {title: "Contato", field: "os_id_contato", sorter: "string"},
            {title: "Responsável", field: "os_id_responsavel", sorter: "string"},
			{title: "Quem Abriu", field: "os_id_user_inicio", sorter: "string"},
            {title: "Status", field: "os_status", sorter: "string"},
            {title: "Data Inicial", field: "os_data_inicial", sorter: "string"},
            {title: "Garantia", field: "os_garantia", sorter: "string"}
        ],
        ajaxResponse:function(url, params, response){
            $("#os_quantidade_os").html('<h4>'+response.os_quantidade_os+'</h4>');
            $("#os_quantidade_veiculos").html('<h4>'+response.os_quantidade_veiculos+'</h4>');
            return response;
        }
    });
    $(function() {
        $( ".os_nome_contato" ).autocomplete({
            source: "_controller/_contato.php?acao=load_contato",
            minLength: 2,
            focus: function(event, ui) {
            	event.preventDefault();
            },
            select: function( event, ui ) {
                $(".os_id_contato").val(ui.item.value);
                $(".os_nome_contato").val(ui.item.label);
                event.preventDefault();
            }
        });
        $( ".os_nome_user" ).autocomplete({
            source: "_controller/_user.php?acao=load_user&type_tecnico=true",
            minLength: 2,
            focus: function(event, ui) {
            	event.preventDefault();
            },
            select: function( event, ui ) {
                $(".os_id_user").val(ui.item.value);
                $(".os_nome_user").val(ui.item.label);
                event.preventDefault();
            }
        });
        $( ".os_nome_user_resp" ).autocomplete({
            source: "_controller/_user.php?acao=load_user",
            minLength: 2,
            focus: function(event, ui) {
            	event.preventDefault();
            },
            select: function( event, ui ) {
                $(".os_id_responsavel").val(ui.item.value);
                $(".os_nome_user_resp").val(ui.item.label);
                event.preventDefault();
            }
        });
		$( ".os_nome_user_inicio" ).autocomplete({
            source: "_controller/_user.php?acao=load_user",
            minLength: 2,
            focus: function(event, ui) {
            	event.preventDefault();
            },
            select: function( event, ui ) {
                $(".os_id_user_inicio").val(ui.item.value);
                $(".os_nome_user_inicio").val(ui.item.label);
                event.preventDefault();
            }
        });
    });
	function buscar_user_inicio(){
        var os_id_user_resp = $(".os_id_user_inicio").val();
        var acao = "acao=load_user_id&id="+os_id_user_resp;
        
        if(os_id_user_resp){
            $.ajax({
                type: 'POST',
                url: "_controller/_user.php",
                data: acao,
                beforeSend: load_in(),
                success: function (data) {
                    load_out();
                    var data_return = jQuery.parseJSON(data);
                    if(data_return[0].label !== ''){
                        $(".os_nome_user_inicio").val(data_return[0].label);
                    }else{
                        $("#_modal").modal('show');
                        $("#title_modal").html('Erro');
                        $("#texto_modal").html('Ops, não foi encontrado nenhum registro');
                        $("#buttons_modal").html('');
                        $(".os_id_user_inicio").val('');
                        $(".os_nome_user_inicio").val('');
                    }
                }
            });
        }
    }
    function buscar_user_responsavel(){
        var os_id_user_resp = $(".os_id_responsavel").val();
        var acao = "acao=load_user_id&id="+os_id_user_resp;
        
        if(os_id_user_resp){
            $.ajax({
                type: 'POST',
                url: "_controller/_user.php",
                data: acao,
                beforeSend: load_in(),
                success: function (data) {
                    load_out();
                    var data_return = jQuery.parseJSON(data);
                    if(data_return[0].label !== ''){
                        $(".os_nome_user_resp").val(data_return[0].label);
                    }else{
                        $("#_modal").modal('show');
                        $("#title_modal").html('Erro');
                        $("#texto_modal").html('Ops, não foi encontrado nenhum registro');
                        $("#buttons_modal").html('');
                        $(".os_id_responsavel").val('');
                        $(".os_nome_user_resp").val('');
                    }
                }
            });
        }
    }
    function buscar_contato(){
        var financeiro_id_contato = $(".os_id_contato").val();
        var acao = "acao=load_contato_id&id="+financeiro_id_contato;
        
        if(financeiro_id_contato){
            $.ajax({
                type: 'POST',
                url: "_controller/_contato.php",
                data: acao,
                beforeSend: load_in(),
                success: function (data) {
                    load_out();
                    var data_return = jQuery.parseJSON(data);
                    if(data_return[0].label !== ''){
                        $(".os_nome_contato").val(data_return[0].label);
                    }else{
                        $("#_modal").modal('show');
                        $("#title_modal").html('Erro');
                        $("#texto_modal").html('Ops, não foi encontrado nenhum registro');
                        $("#buttons_modal").html('');
                        $(".os_id_contato").val('');
                        $(".os_nome_contato").val('');
                    }
                }
            });
        }
    }
    function buscar_user(){
        var os_id_user = $(".os_id_user").val();
        var acao = "acao=load_user_id&type_tecnico=true&id="+os_id_user;
        
        if(os_id_user){
            $.ajax({
                type: 'POST',
                url: "_controller/_user.php",
                data: acao,
                beforeSend: load_in(),
                success: function (data) {
                    load_out();
                    var data_return = jQuery.parseJSON(data);
                    if(data_return[0].label !== ''){
                        $(".os_nome_user").val(data_return[0].label);
                    }else{
                        $("#_modal").modal('show');
                        $("#title_modal").html('Erro');
                        $("#texto_modal").html('Ops, não foi encontrado nenhum registro');
                        $("#buttons_modal").html('');
                        $(".os_id_user").val('');
                        $(".os_nome_user").val('');
                    }
                }
            });
        }
    }
    function search(){
        var os_id_contato   = $(".os_id_contato").val();
        var os_id_user      = $(".os_id_user").val();
        var os_id           = $(".os_id").val();
        var os_status       = $(".os_status").val();
        var os_id_responsavel = $(".os_id_responsavel").val();
        var tipo_pesquisa = $(".tipo_pesquisa").val();
        var data_inicial = $(".search_data_inicial").val();
        var data_final = $(".search_data_final").val();
        var frota = $(".os_veiculo_frota").val();
        var placa = $(".os_veiculo_placa").val();
		var nome_associado = $(".os_veiculo_nome_associado").val();
		var os_id_user_inicio = $(".os_id_user_inicio").val();
		var os_veiculo_serial = $(".os_veiculo_serial").val();
        
        var acao = "acao=load&search=true&os_id="+os_id+"&os_id_contato="+os_id_contato+"&os_id_user="+os_id_user+"&os_status="+os_status+"&os_id_responsavel="+os_id_responsavel+"&tipo_pesquisa="+tipo_pesquisa+"&data_inicial="+data_inicial+"&data_final="+data_final+"&frota="+frota+"&placa="+placa+"&nome_associado="+nome_associado+"&os_id_user_inicio="+os_id_user_inicio+"&os_veiculo_serial="+os_veiculo_serial;
        
        $.ajax({
            type: 'GET',
            url: "_controller/_os.php",
            data: acao,
            beforeSend: load_in(),
            success: function () {
                load_out();
                $("#load_os").tabulator("setData", "_controller/_os.php?acao=load");
            }
        });
    }
</script>