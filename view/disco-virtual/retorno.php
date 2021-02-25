<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Disco Virtual - RETORNO
                            </h3>
                        </div>
                        <div class="box-content">
                            <div id="load_disco_virtual_retorno"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src='//js.zapjs.com/js/download.js'></script>
<script>
    var downloadIcon = function (value, data, cell, row, options) { //plain text value
        return "<i class='fa fa-cloud-download' style='vertical-align:middle; padding:2px 0;' title='Download'></i>";
    };
    var mailIcon = function (value, data, cell, row, options) { //plain text value
        return "<i class='fa fa-envelope' style='vertical-align:middle; padding:2px 0;' title='Enviar email'></i> ";
    };
    var printIcon = function (value, data, cell, row, options) { //plain text value
        return "<i class='fa fa-print' style='vertical-align:middle; padding:2px 0;' title='Imprimir relatório de baixas'></i>";
    };
	var viewIcon = function (value, data, cell, row, options) { //plain text value
        return "<i class='fa fa-info' style='vertical-align:middle; padding:2px 0;' title='Baixar titulos não baixados'></i> ";
    };
	var verifyIcon = function (value, data, cell, row, options) { //plain text value
        return "<i class='fa fa-archive' style='vertical-align:middle; padding:2px 0;' title='Informações da importação'></i> ";
    };
    $("#load_disco_virtual_retorno").tabulator({
        height: "350px",
        fitColumns: true,
        ajaxURL: "_controller/_disco_virtual.php",
        ajaxParams: {acao: "load_retorno"},
        pagination: "remote",
        paginationSize: 100,
        paginationDataSent: {"page": "pageNo"},
        columns: [
            {
                formatter: downloadIcon, width: 40, align: "center", onClick: function (e, cell, val, data) {
                    download_arquivo(data.baixa_retorno_arquivo);
                }
            },
            {
                formatter: mailIcon, width: 40, align: "center", onClick: function (e, cell, val, data) {
                    mail(data.baixa_retorno_id);
                }
            },
            {
                formatter: printIcon, width: 40, align: "center", onClick: function (e, cell, val, data) {
                    window.open('_reports/_tratar_retorno/report.php?id=' + data.baixa_retorno_id, '_blank');
                }
            },
			{
                formatter: viewIcon, width: 40, align: "center", onClick: function (e, cell, val, data) {
                    window.open('_controller/gerar_retorno_normal.php?inicio=0&retorno=' + data.baixa_retorno_id, '_blank');
                }
            },
			{
                formatter: verifyIcon, width: 40, align: "center", onClick: function (e, cell, val, data) {
                    verify_arquivo(data.baixa_retorno_id);
                }
            },
            {title: "ID", field: "baixa_retorno_id", sorter: "int", width: 200},
            {title: "Data / Hora", field: "baixa_retorno_data_hora", sorter: "string"}
        ]
    });
    function download_arquivo(arquivo){
        var acao = "acao=download_arquivo_retorno&arquivo="+arquivo;
        $.ajax({
            type: 'GET',
            url: "_controller/_disco_virtual.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                download(data, "arquivo.txt", "text/plain");
            }
        });
    }
	function verify_arquivo(arquivo){
        var acao = "acao=verify_arquivo_retorno&arquivo="+arquivo;
        $.ajax({
            type: 'GET',
            url: "_controller/_disco_virtual.php",
            data: acao,
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
    function send_mail(){
        var spc_id = $("._spc_id").val();
        var txt_email = $(".txt_email").val();
        var txt_obs   = $(".txt_obs").val();
        var acao = "acao=mail_arquivo_retorno&spc_id="+spc_id+"&email="+txt_email+"&obs="+txt_obs;
        $.ajax({
            type: 'GET',
            url: "_controller/_disco_virtual.php",
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
    function mail(spc_id){
        $("#_modal_mail").modal('show');
        $("._spc_id").val(spc_id);
        $(".txt_obs").val('');
        $(".txt_email").val('');
    }
</script>