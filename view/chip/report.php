<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Chips
                            </h3>
                        </div>
                        <div class="box-content">
                            <div class="row">
                                <div class="form-group col-lg-2">
                                    <label>Data Inicial</label>
                                    <input type="date" class="form-control data_inicial"/>
                                </div>
                                <div class="form-group col-lg-2">
                                    <label>Data Final</label>
                                    <input type="date" class="form-control data_final"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-lg-12" align="right">
                                    <button type="button" class="btn btn-primary" onclick="gerar_pdf();">Gerar PDF (Chip)</button>
                                    <button type="button" class="btn btn-primary" onclick="gerar_excel();">Gerar EXCEL (Chip)</button>
                                    <button type="button" class="btn btn-primary" onclick="gerar_excel_cancelamento();">Gerar EXCEL (Cancelamento)</button>
                                    <button type="button" class="btn btn-primary" onclick="gerar_excel_desbloqueio();">Gerar EXCEL (Desbloqueios)</button>
                                </div>
                            </div>
							<div class="row">
								<div class="form-group col-lg-12" align="right">
									<button type="button" class="btn btn-primary" onclick="gerar_excel_bloqueados();">Gerar EXCEL (Pedidos Bloqueados)</button>
									<button type="button" class="btn btn-primary" onclick="gerar_excel_qtd_financeiro();">Gerar EXCEL (QTD Financeiro)</button>
								</div>
							</div>
							
							<div class="row">
								<div class="form-group col-lg-12" align="right">
									<button type="button" class="btn btn-primary" onclick="gerar_excel_vendedores();">Gerar EXCEL (Pedidos Vendedores)</button>
									<button type="button" class="btn btn-primary" onclick="gerar_excel_vendedores_resumido();">Gerar EXCEL (Pedidos Vendedores Resumido)</button>
								</div>
							</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
    $get_operacao = addslashes(trim(strip_tags($_GET['OP'])));
?>
<script>
    function gerar_excel(){
        window.open('_controller/_chip.php?acao=gerar_excel&OP=<?php echo $get_operacao;?>', '_blank');
    }
	function gerar_excel_bloqueados(){
		var data_inicial = $(".data_inicial").val();
        var data_final = $(".data_final").val();
        window.open('_controller/_chip.php?acao=gerar_excel_bloqueados&data_inicial='+data_inicial+'&data_final='+data_final+'', '_blank');
    }
    function gerar_excel_cancelamento(){
        var data_inicial = $(".data_inicial").val();
        var data_final = $(".data_final").val();
        window.open('_controller/_chip.php?acao=gerar_excel_cancelamento&data_inicial='+data_inicial+'&data_final='+data_final+'', '_blank');
    }
    function gerar_excel_desbloqueio(){
        var data_inicial = $(".data_inicial").val();
        var data_final = $(".data_final").val();
        window.open('_controller/_chip.php?acao=gerar_excel_desbloqueio&data_inicial='+data_inicial+'&data_final='+data_final+'', '_blank');
    }
	function gerar_excel_qtd_financeiro(){
        var data_inicial = $(".data_inicial").val();
        var data_final = $(".data_final").val();
        window.open('_controller/_chip.php?acao=gerar_excel_qtd_financeiro&data_inicial='+data_inicial+'&data_final='+data_final+'', '_blank');
    }
	function gerar_excel_vendedores(){
        var data_inicial = $(".data_inicial").val();
        var data_final = $(".data_final").val();
        window.open('_controller/_chip.php?acao=gerar_excel_vendedores&data_inicial='+data_inicial+'&data_final='+data_final+'', '_blank');
    }
	function gerar_excel_vendedores_resumido(){
        var data_inicial = $(".data_inicial").val();
        var data_final = $(".data_final").val();
        window.open('_controller/_chip.php?acao=gerar_excel_vendedores_resumido&data_inicial='+data_inicial+'&data_final='+data_final+'', '_blank');
    }
    function gerar_pdf(){
        window.open('_reports/_chip/report.php?OP=<?php echo $get_operacao;?>', '_blank');
    }
</script>