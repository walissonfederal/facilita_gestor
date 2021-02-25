<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Ordem de Serviço - Veículos
                                <a href="javascript::" onclick="carrega_pagina('os', 'index.php');" class="btn btn-primary">Voltar</a>
                                <a href="javascript::" onclick="carrega_pagina('os', 'create_vehicles.php?id_os=<?=$_GET['id'];?>');" class="btn btn-primary">Cadastrar Novo</a>
								<a href="javascript::" onclick="open_veiculo_import();" class="btn btn-success">Importar Veículos</a>
                            </h3>
                        </div>
                        <div class="box-content">
                            <div id="load_os_veiculo"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="modal_veiculos" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Importar Veículos</h4>
				</div>
				<!-- /.modal-header -->
				<div class="modal-body">
					<div class="row">
						<div class="form-group col-lg-12">
							<label>ID ADITIVO(Esse é o id geral do aditivo)</label>
							<input type="number" class="form-control id_aditivo" />
						</div>
					</div>
				</div>
				<!-- /.modal-body -->
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" data-dismiss="modal" onclick="gerar_import_dados();">Importar</button>
				</div>
				<!-- /.modal-footer -->
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
<script>
	function open_veiculo_import(){
		$("#modal_veiculos").modal('show');
	}
	function gerar_import_dados(){
        var os_id_aditivo = $(".id_aditivo").val();
        var acao = "acao=import_veiculo&id="+os_id_aditivo+"&id_os=<?=$_GET['id'];?>";
        
        if(os_id_aditivo){
            $.ajax({
                type: 'POST',
                url: "_controller/_os.php",
                data: acao,
                beforeSend: load_in(),
                success: function (data) {
                    load_out();
                    carrega_pagina('os', 'vehicles.php?id=<?=$_GET['id'];?>');
                }
            });
        }
    }
    var updateIcon = function (value, data, cell, row, options) { //plain text value
        return "<i class='fa fa-pencil' style='vertical-align:middle; padding:2px 0;' title='Editar'></i> ";
    };
    var deleteIcon = function (value, data, cell, row, options) { //plain text value
        return "<i class='fa fa-close' style='vertical-align:middle; padding:2px 0;' title='Excluir'></i> ";
    };
    $("#load_os_veiculo").tabulator({
        height: "350px",
        fitColumns: true,
        ajaxURL: "_controller/_os.php",
        ajaxParams: {acao: "load_os", id_os: "<?=$_GET['id'];?>"},
        pagination: "remote",
        paginationSize: 100,
        paginationDataSent: {"page": "pageNo"},
        columns: [
            {
                formatter: updateIcon, width: 40, align: "center", onClick: function (e, cell, val, data) {
                    carrega_pagina('os', 'update_vehicles.php?id='+data.os_veiculo_id+'&os_veiculo_id_os='+data.os_veiculo_id_os);
                }
            },
            {
                formatter: deleteIcon, width: 40, align: "center", onClick: function (e, cell, val, data) {
                    open_delete(data.os_veiculo_id);
                }
            },
            {title: "Frota", field: "os_veiculo_frota", sorter: "string"},
            {title: "Placa", field: "os_veiculo_placa", sorter: "string"},
            {title: "Modelo", field: "os_veiculo_modelo", sorter: "string"},
            {title: "Marca", field: "os_veiculo_marca", sorter: "string"},
            {title: "Cor", field: "os_veiculo_cor", sorter: "string"},
            {title: "Ano", field: "os_veiculo_ano", sorter: "string"},
            {title: "Chassi", field: "os_veiculo_chassi", sorter: "string"},
            {title: "ICCID", field: "os_veiculo_iccid", sorter: "string"},
            {title: "Serial", field: "os_veiculo_serial", sorter: "string"}
        ]
    });
    function open_delete(id_veiculo){
        $("#_modal").modal('show');
        $("#title_modal").html('Informação');
        $("#texto_modal").html('Deseja realmente excluir veiculo?');
        $("#buttons_modal").html('<button type="button" class="btn btn-default" data-dismiss="modal">Não</button><a href="javascript::" onclick="open_delete_ok('+id_veiculo+');" data-dismiss="modal" class="btn btn-primary">Sim</a>');
    }
    function open_delete_ok(id_veiculo){
        var acao = "acao=delete_veiculo&id="+id_veiculo;
        
        if(id_veiculo){
            $.ajax({
                type: 'POST',
                url: "_controller/_os.php",
                data: acao,
                beforeSend: load_in(),
                success: function (data) {
                    load_out();
                    carrega_pagina('os', 'vehicles.php?id=<?=$_GET['id'];?>');
                }
            });
        }
    }
</script>