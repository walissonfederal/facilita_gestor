<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Chip
                                <a href="javascript::" onclick="carrega_pagina('mmn_chip', 'create.php');" class="btn btn-primary">Cadastrar Novo</a>
                            </h3>
                        </div>
                        <div class="box-content">
                            <form action="">
                                <fieldset>
                                    <div class="row">
                                        <div class="form-group col-lg-1">
                                            <label>ID</label>
                                            <input type="text" class="form-control search_id"/>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>Número</label>
                                            <input type="text" class="form-control search_numero"/>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>Iccid</label>
                                            <input type="text" class="form-control search_iccid"/>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>Status</label>
                                            <select class="form-control search_status">
                                                <option value=""></option>
                                                <option value="0">Disponível</option>
                                                <option value="1">Indisponível</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>.</label><br />
                                            <button type="button" class="btn btn-primary" onclick="search();">Pesquisar</button>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                            <hr />
                            <div id="load_mmn_chip"></div>
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
    var infoIcon = function (value, data, cell, row, options) { //plain text value
        return "<i class='fa fa-info' style='vertical-align:middle; padding:2px 0;' title='Ver Informações'></i> ";
    };
    $("#load_mmn_chip").tabulator({
        height: "350px",
        fitColumns: true,
        ajaxURL: "_controller/_mmn_chip.php",
        ajaxParams: {acao: "load"},
        pagination: "remote",
        paginationSize: 100,
        paginationDataSent: {"page": "pageNo"},
        columns: [
            {
                formatter: updateIcon, width: 40, align: "center", onClick: function (e, cell, val, data) {
                    carrega_pagina('mmn_chip', 'update.php?id='+data.chip_id);
                }
            },
            {
                formatter: infoIcon, width: 40, align: "center", onClick: function (e, cell, val, data) {
                    info_chip(data.chip_id);
                }
            },
            {title: "ID", field: "chip_id", sorter: "int", width: 75},
            {title: "Número", field: "chip_num", sorter: "string"},
            {title: "ICCID", field: "chip_iccid", sorter: "string"},
            {title: "Status", field: "chip_status", sorter: "string"}
        ]
    });
    
    function search(){
        var search_id           = $(".search_id").val();
        var search_numero       = $(".search_numero").val();
        var search_iccid        = $(".search_iccid").val();
        var search_status       = $(".search_status").val();
        
        var acao = "acao=load&search=true&id="+search_id+"&numero="+search_numero+"&status="+search_status+"&iccid="+search_iccid;
        
        $.ajax({
            type: 'GET',
            url: "_controller/_mmn_chip.php",
            data: acao,
            beforeSend: load_in(),
            success: function () {
                load_out();
                $("#load_mmn_chip").tabulator("setData", "_controller/_mmn_chip.php?acao=load");
            }
        });
    }
    function info_chip(id_chip){
        
        var acao = "acao=info_chip&id="+id_chip;
        $("#mmn_informacao_completa_chip").html('');
        $.ajax({
            type: 'GET',
            url: "_controller/_mmn_chip.php",
            data: acao,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                $("#_modal_info_chip").modal('show');
                $("#mmn_informacao_completa_chip").html(data);
            }
        });
    }
</script>
<div id="_modal_info_chip" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Informação do Chip</h4>
            </div>
            <div class="modal-body">
                <div id="mmn_informacao_completa_chip"></div>
            </div>
            <div class="modal-footer">
                <div id="buttons_modal"></div>
            </div>
        </div>
    </div>
</div>