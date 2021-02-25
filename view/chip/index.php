<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Informação de Chip
                            </h3>
                        </div>
                        <div class="box-content">
                            <form action="" id="create">
                                <fieldset>
                                    <div class="row">
                                        <div class="form-group col-lg-12">
                                            <label>ICCID / Número Linha</label>
                                            <input type="hidden" class="id_chip_insert"/>
                                            <input type="text" class="form-control search_chip_linha_iccid"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-12" align="right">
                                            <button type="button" class="btn btn-primary" onclick="link_chip();">Linha Tempo Chip</button>
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
<script>
    $(function() {
        $( ".search_chip_linha_iccid" ).autocomplete({
            source: "_controller/_pedido.php?acao=load_chip_insert_info",
            minLength: 2,
            focus: function(event, ui) {
            	event.preventDefault();
            },
            select: function( event, ui ) {
                $(".id_chip_insert").val(ui.item.value);
                $(".search_chip_linha_iccid").val(ui.item.label);
                event.preventDefault();
            }
        });
    });
    function link_chip(){
        var id_chip = $(".id_chip_insert").val();
        if(id_chip){
            window.open('Home.php?model=pedido&pg=linha_tempo_chip&id_chip='+id_chip, '_blank');
        }else{
            $("#_modal").modal('show');
            $("#title_modal").html('Informação');
            $("#texto_modal").html('Chip não foi encontrato dentro do sistema!');
            $("#buttons_modal").html('');
        }
    }
</script>