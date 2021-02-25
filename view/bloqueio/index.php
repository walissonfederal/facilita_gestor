<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Bloqueio
                            </h3>
                        </div>
                        <div class="box-content">
                            <form class="wc_form_search" action="" method="POST">
                                <fieldset>
                                    <div class="row">
                                        <div class="form-group col-lg-1">
                                            <label>ID CONTATO</label>
                                            <input type="text" class="form-control autocomplete_id_contato" name="id_contato" autocomplete="off" onblur="buscar_contato();"/>
                                        </div>
                                        <div class="form-group col-lg-5">
                                            <label>CONTATO (Nome, CPF, Email e Username)</label>
                                            <input type="text" class="form-control autocomplete_contato" name="nome_contato" autocomplete="off"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>TIPO</label>
                                            <select class="form-control" name="tipo">
                                                <option value="financeiro_data_vencimento">DT VENCIMENTO</option>
                                                <option value="financeiro_data_pagamento">DT PAGAMENTO</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>DT INICIAL</label>
                                            <input type="date" class="form-control" name="data_inicial"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>DT FINAL</label>
                                            <input type="date" class="form-control" name="data_final"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-1">
                                            <label>ID</label>
                                            <input type="text" class="form-control" name="id"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>STATUS CLIENTE</label>
                                            <select class="form-control" name="status_cliente">
                                                <option value=""></option>
                                                <option value="0">DESBLOQUEADO</option>
                                                <option value="1">BLOQUEADO</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-7">
                                            <label>.</label><br />
                                            <input type="hidden" name="action" value="select"/>
                                            <input type="hidden" name="search" value="true"/>
                                            <button class="btn btn-primary">PESQUISAR</button>
                                            <a class="btn btn-primary" onclick="link_bloqueio_gerar();">BLOQUEAR USUÁRIOS</a>
                                            <a class="btn btn-primary" onclick="link_bloqueio_todos_gerar();">BLOQUEAR TODOS DA PESQUISA</a>
                                            <a class="btn btn-orange" onclick="gerar_excel_bloqueio();">GERAR EXCEL DE BLOQUEIO</a>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                            <hr />
                            <div id="load_pedido"></div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="box box-color blue box-condensed box-bordered">
                                        <div class="box-title" align="center">
                                            <h3>
                                                QUANTIDADE
                                            </h3>
                                        </div>
                                        <div class="box-content">
                                            <div id="pedido_quantidade" align="center"><h4></h4></div>
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
    var selectionFormatter = function (value, data, cell, row, options, formatterParams) {
        var rowSelect = $("<input type='checkbox' class='row-select' value='" + data.financeiro_id_contato + "'>");

        rowSelect.on("change", function () {
            if ($(this).is(":checked")) {
                $(this).closest(".tabulator-row").addClass("selected");
            } else {
                $(this).closest(".tabulator-row").removeClass("selected");
            }
        });
        return rowSelect;
    }
    $("#load_pedido").tabulator({
        height: "350px",
        fitColumns: true,
        ajaxURL: "_controller/Report.ajax.php",
        ajaxParams: {action: "select"},
        pagination: "remote",
        paginationSize: 100,
        paginationDataSent: {"page": "pageNo"},
        columns: [
            {title: "<input type='checkbox' class='select-all'>", formatter: selectionFormatter, width: 35},
            {title: "ID", field: "financeiro_id", sorter: "int", width: 75},
            {title: "USUÁRIO", field: "contato_nome_razao", sorter: "string"},
            {title: "QTD FATURAS", field: "contador", sorter: "string"},
            {title: "STATUS CLIENTE", field: "contato_bloqueio_desbloqueio", sorter: false}
        ],
        ajaxResponse: function (url, params, response) {
            $("#pedido_quantidade").html('<h4>' + response.pedido_quantidade + '</h4>');
            return response;
        }
    });
    $("#load_pedido .select-all").on("change", function () {
        if ($(this).is(":checked")) {
            $("#load_pedido .row-select").prop("checked", true).closest(".tabulator-row").addClass("selected");
        } else {
            $("#load_pedido .row-select").prop("checked", false).closest(".tabulator-row").removeClass("selected");
        }
    });
    $('.wc_form_search').submit(function () {
        var Form = $(this);
        var Data = Form.serialize();

        $.ajax({
            url: "_controller/Report.ajax.php",
            data: Data,
            type: 'POST',
            dataType: 'json',
            beforeSend: load_in(),
            success: function (data) {
                carrega_pagina('bloqueio', 'index.php');
                load_out();
            }
        });
        return false;
    });
    function load_search() {
        var Data = "&action=search_load";
        $.ajax({
            url: "_controller/Report.ajax.php",
            data: Data,
            type: 'POST',
            dataType: 'json',
            beforeSend: load_in(),
            success: function (data) {
                if (data.type === 'ok') {
                    var Form = $(".wc_form_search");
                    $.each(data.info, function (key, value) {
                        Form.find("input[name='" + key + "'], select[name='" + key + "']").val(value);
                    });
                }
                load_out();
            }
        });
        return false;
    }
    function link_bloqueio_gerar() {
        var objCheckBox = $("#load_pedido .row-select");
        var sel = 0;
        var selecionadas = "";
        for (i = 0; i < objCheckBox.length; i++) {
            if (objCheckBox[i].checked) {
                sel++;
                selecionadas += objCheckBox[i].value + ",";
            }
        }
        if (Number(sel) === 0) {
            alert('Ops, é preciso selecionar um registro');
        } else {
            var url = selecionadas.substr(0, (selecionadas.length - 1));
            window.location = 'Home.php?model=bloqueio&pg=operation&id=' + url;
        }
    }
    function link_bloqueio_todos_gerar() {
        window.location = 'Home.php?model=bloqueio&pg=operation';
    }
    function gerar_excel_bloqueio() {
        var Data = "&action=gerar_excel";
        $.ajax({
            url: "_controller/Report.ajax.php",
            data: Data,
            type: 'POST',
            dataType: 'json',
            beforeSend: load_in(),
            success: function (data) {
                if (data.type === 'ok') {
                    window.open('_controller/bloqueio.xlsx', '_blank');
                } else {
					alert(data.msg);
                }
                load_out();
            },
			error(){
				alert('Erro');
			}
        });
        return false;
    }
    $(function () {
        $(".autocomplete_contato").autocomplete({
            source: "_controller/_contato.php?acao=load_contato",
            minLength: 2,
            focus: function (event, ui) {
                event.preventDefault();
            },
            select: function (event, ui) {
                $(".autocomplete_id_contato").val(ui.item.value);
                $(".autocomplete_contato").val(ui.item.label);
                event.preventDefault();
            }
        });
        load_search();
    });
	function buscar_contato(){
        var financeiro_id_contato = $(".autocomplete_id_contato").val();
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
                        $(".autocomplete_contato").val(data_return[0].label);
                    }else{
                        alert('Ops, não foi encontrado nenhum registro');
                        $(".autocomplete_id_contato").val('');
                        $(".autocomplete_contato").val('');
                    }
                }
            });
        }
    }
</script>