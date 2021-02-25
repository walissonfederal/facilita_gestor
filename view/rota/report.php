<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Rota
                                <a href="javascript::" onclick="carrega_pagina('regiao', 'index.php');" class="btn btn-primary">Voltar</a>
                            </h3>
                        </div>
                        <div class="box-content">
                            <div class="row">
                                <div class="form-group col-lg-12" align="right">
                                    <button type="button" class="btn btn-primary" onclick="gerar_pdf();">Gerar PDF</button>
                                    <button type="button" class="btn btn-primary" onclick="gerar_excel();">Gerar EXCEL</button>
                                    <a href="javascript::" onclick="carrega_pagina('rota', 'index.php')" class="btn btn-danger">Cancelar</a>
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
    function gerar_excel(){
        window.open('_controller/_rota.php?acao=gerar_excel', '_blank');
    }
    function gerar_pdf(){
        window.open('_reports/_rota/report.php', '_blank');
    }
</script>