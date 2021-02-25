<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Campanha Email Itens
                                <a href="javascript::" onclick="carrega_pagina('campanha-mail', 'index.php');" class="btn btn-primary">Voltar</a>
                            </h3>
                        </div>
                        <div class="box-content">
                            <div id="load_campanha_mail"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $("#load_campanha_mail").tabulator({
        height: "350px",
        fitColumns: true,
        ajaxURL: "_controller/_campanha_mail.php",
        ajaxParams: {acao: "load_item", id_campanha: "<?php echo $_GET['id'];?>"},
        pagination: "remote",
        paginationSize: 100,
        paginationDataSent: {"page": "pageNo"},
        columns: [
            {title: "Email", field: "campanha_mail_itens_email", sorter: "string"},
            {title: "Contato", field: "campanha_mail_itens_id_contato_nome", sorter: "string"},
            {title: "Status", field: "campanha_mail_itens_status", sorter: "string"},
            {title: "Motivo", field: "campanha_mail_itens_motivo", sorter: "string"}
        ]
    });
</script>