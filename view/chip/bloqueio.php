<?php
    $get_situacao = addslashes(trim(strip_tags($_GET['situacao'])));
    $get_id_contato = addslashes(trim(strip_tags($_GET['id_contato'])));
    
    if($get_id_contato != ''){
        $sql_id_contato = "AND financeiro.financeiro_id_contato = '".$get_id_contato."'";
    }else{
        $sql_id_contato = "";
    }
?>
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
                            <form action="">
                                <fieldset>
                                    <div class="row">
                                        <div class="form-group col-lg-2">
                                            <label>ID Contato</label>
                                            <input type="text" class="form-control search_nome_id_contato" onblur="buscar_contato();" value="<?php echo $get_id_contato;?>"/>
                                        </div>
                                        <div class="form-group col-lg-8">
                                            <label>Contato</label>
                                            <input type="text" class="form-control search_nome_contato"/>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label>Situação</label>
                                            <select class="form-control search_situacao">
                                                <option value=""></option>
                                                <option value="Liberado" <?php if($get_situacao == 'Liberado'){echo 'selected';}?>>Liberado</option>
                                                <option value="A liberar" <?php if($get_situacao == 'A liberar'){echo 'selected';}?>>A liberar</option>
                                                <option value="Bloqueado" <?php if($get_situacao == 'Bloqueado'){echo 'selected';}?>>Bloqueado</option>
                                                <option value="A bloquear" <?php if($get_situacao == 'A bloquear'){echo 'selected';}?>>A bloquear</option>
                                                <option value="Suspenso" <?php if($get_situacao == 'Suspenso'){echo 'selected';}?>>Suspenso</option>
                                                <option value="Migrado" <?php if($get_situacao == 'Migrado'){echo 'selected';}?>>Migrado</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-12">
                                            <label>.</label><br />
                                            <button type="button" class="btn btn-primary" onclick="search();">Pesquisar</button>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                            <hr />
                            <div style="width: 100%; height: 300px; overflow-x: scroll; overflow-y: scroll;">
                                <table class="table ls-table">
                                    <thead>
                                        <tr>
                                            <th style="font-size: 9px;">ID Contato</th>
                                            <th style="font-size: 9px;">Nome Razao</th>
                                            <th class="hidden-xs" style="font-size: 9px;">Nome Fantasia</th>
                                            <th style="font-size: 9px;">Telefone</th>
                                            <th class="hidden-xs" style="font-size: 9px;">Email</th>
                                            <th style="font-size: 9px;">Status</th>
                                            <th style="font-size: 9px;">Motivo</th>
                                            <th style="font-size: 9px;">Dias</th>
                                            <th colspan="4"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $read_bloqueio = ReadComposta("SELECT * FROM (SELECT financeiro.financeiro_id_contato, contato.contato_nome_fantasia, contato.contato_nome_razao, contato.contato_telefone, contato.contato_email, financeiro.financeiro_data_vencimento, financeiro.financeiro_status, contato.contato_bloqueio, bloqueio.bloqueio_id_contato, bloqueio.bloqueio_motivo, bloqueio.bloqueio_tipo FROM financeiro INNER JOIN contato ON contato.contato_id = financeiro.financeiro_id_contato LEFT JOIN bloqueio ON bloqueio.bloqueio_id_contato = financeiro.financeiro_id_contato WHERE financeiro.financeiro_id_tipo_documento = '5' AND financeiro.financeiro_data_vencimento < NOW() AND financeiro.financeiro_tipo = 'CR' {$sql_id_contato} ORDER BY financeiro.financeiro_id_contato DESC, bloqueio.bloqueio_id DESC) AS Conteudo GROUP BY financeiro_id_contato;");
                                        if (NumQuery($read_bloqueio) > '0') {
                                            foreach ($read_bloqueio as $read_bloqueio_view) {
                                                if ($read_bloqueio_view['contato_bloqueio'] == '0') {
                                                    $tipo_bloqueio = 'Liberado';
                                                    $ColorStatusConta = '#00FF00';
                                                } elseif ($read_bloqueio_view['contato_bloqueio'] == '1') {
                                                    $tipo_bloqueio = 'Bloqueado';
                                                    $ColorStatusConta = '#FF0000';
                                                } elseif ($read_bloqueio_view['contato_bloqueio'] == '2') {
                                                    $tipo_bloqueio = 'A liberar';
                                                    $ColorStatusConta = '#FFFF00';
                                                } elseif ($read_bloqueio_view['contato_bloqueio'] == '3') {
                                                    $tipo_bloqueio = 'Suspenso';
                                                    $ColorStatusConta = '#2E64FE';
                                                } elseif ($read_bloqueio_view['contato_bloqueio'] == '4') {
                                                    $tipo_bloqueio = 'Migrado';
                                                    $ColorStatusConta = '#FA58F4';
                                                }
                                                $data_fim = date('Y-m-d');
                                                $read_financeiro = Read('financeiro', "WHERE financeiro_id_contato = '" . $read_bloqueio_view['financeiro_id_contato'] . "' AND financeiro_id_tipo_documento = '5' ORDER BY financeiro_status DESC, financeiro_data_vencimento ASC");
                                                if (NumQuery($read_financeiro) > '0') {
                                                    foreach ($read_financeiro as $read_financeiro_view) {
                                                        $data_diff = diff_dias($read_financeiro_view['financeiro_data_vencimento'], $data_fim);
                                                        if ($read_financeiro_view['financeiro_status'] == '0') {
                                                            if ($data_diff > '15') {
                                                                if ($read_bloqueio_view['Bloqueio'] == '1') {
                                                                    $tipo_bloqueio = 'Bloqueado';
                                                                    $ColorStatusConta = '#FF0000';
                                                                } elseif ($read_bloqueio_view['Bloqueio'] == '3') {
                                                                    $tipo_bloqueio = 'Suspenso';
                                                                    $ColorStatusConta = '#2E64FE';
                                                                } else {
                                                                    if ($read_bloqueio_view['Bloqueio'] == '0') {
                                                                        $tipo_bloqueio = 'A bloquear';
                                                                        $ColorStatusConta = '#D8D8D8';
                                                                    } elseif ($read_bloqueio_view['Bloqueio'] == '2') {
                                                                        $tipo_bloqueio = 'A bloquear';
                                                                        $ColorStatusConta = '#D8D8D8';
                                                                    } else {
                                                                        $tipo_bloqueio = 'A bloquear';
                                                                        $ColorStatusConta = '#D8D8D8';
                                                                    }
                                                                }
                                                            } else {
                                                                if ($read_bloqueio_view['Bloqueio'] == '0') {
                                                                    $tipo_bloqueio = 'Liberado';
                                                                    $ColorStatusConta = '#00FF00';
                                                                } elseif ($read_bloqueio_view['Bloqueio'] == '1') {
                                                                    $tipo_bloqueio = 'A liberar';
                                                                    $ColorStatusConta = '#FFFF00';
                                                                } elseif ($read_bloqueio_view['Bloqueio'] == '2') {
                                                                    $tipo_bloqueio = 'A liberar';
                                                                    $ColorStatusConta = '#FFFF00';
                                                                } elseif ($read_bloqueio_view['Bloqueio'] == '3') {
                                                                    $tipo_bloqueio = 'A liberar';
                                                                    $ColorStatusConta = '#FFFF00';
                                                                }
                                                            }
                                                        } else {
                                                            if ($read_financeiro_view['financeiro_status'] == '1') {
                                                                $data_diff = '0';
                                                                if ($read_bloqueio_view['Bloqueio'] == '0') {
                                                                    $tipo_bloqueio = 'Liberado';
                                                                    $ColorStatusConta = '#00FF00';
                                                                } elseif ($read_bloqueio_view['Bloqueio'] == '1') {
                                                                    $tipo_bloqueio = 'A liberar';
                                                                    $ColorStatusConta = '#FFFF00';
                                                                } elseif ($read_bloqueio_view['Bloqueio'] == '2') {
                                                                    $tipo_bloqueio = 'A liberar';
                                                                    $ColorStatusConta = '#FFFF00';
                                                                } elseif ($read_bloqueio_view['Bloqueio'] == '3') {
                                                                    $tipo_bloqueio = 'A liberar';
                                                                    $ColorStatusConta = '#FFFF00';
                                                                }
                                                            } else {
                                                                $data_diff = '0';
                                                            }
                                                        }
                                                    }
                                                }
                                                if($get_situacao != ''){
                                                    if($get_situacao == $tipo_bloqueio){
                                                ?>
                                                <tr>
                                                    <td style="font-size: 9px;" bgcolor="<?php echo $ColorStatusConta; ?>"><?php echo $read_bloqueio_view['financeiro_id_contato']; ?></td>
                                                    <td style="font-size: 9px;"><?php echo $read_bloqueio_view['contato_nome_fantasia']; ?></td>
                                                    <td class="hidden-xs" style="font-size: 9px;"><?php echo $read_bloqueio_view['contato_nome_razao']; ?></td>
                                                    <td style="font-size: 9px;"><?php echo $read_bloqueio_view['contato_telefone']; ?></td>
                                                    <td style="font-size: 9px;"><?php echo $read_bloqueio_view['contato_email']; ?></td>
                                                    <td class="hidden-xs" style="font-size: 9px;"><?php echo $tipo_bloqueio; ?></td>
                                                    <td style="font-size: 9px;"><?php echo $read_bloqueio_view['bloqueio_motivo']; ?></td>
                                                    <td style="font-size: 9px;"><?php echo $data_diff; ?></td>
                                                    <td><a href="Home.php?model=chip&pg=bloqueio_view&Id=<?php echo $read_bloqueio_view['financeiro_id_contato']; ?>&consta=<?php echo $tipo_bloqueio; ?>" class="ico-address-book" data-toggle="tooltip" data-placement="top" title="Editar" /><i class='fa fa-pencil' style='vertical-align:middle; padding:2px 0;' title='Editar'></i></a></td>

                                                </tr>
                                                <?php
                                                    }
                                                }else{
                                                ?>
                                                <tr>
                                                    <td style="font-size: 9px;" bgcolor="<?php echo $ColorStatusConta; ?>"><?php echo $read_bloqueio_view['financeiro_id_contato']; ?></td>
                                                    <td style="font-size: 9px;"><?php echo $read_bloqueio_view['contato_nome_fantasia']; ?></td>
                                                    <td class="hidden-xs" style="font-size: 9px;"><?php echo $read_bloqueio_view['contato_nome_razao']; ?></td>
                                                    <td style="font-size: 9px;"><?php echo $read_bloqueio_view['contato_telefone']; ?></td>
                                                    <td style="font-size: 9px;"><?php echo $read_bloqueio_view['contato_email']; ?></td>
                                                    <td class="hidden-xs" style="font-size: 9px;"><?php echo $tipo_bloqueio; ?></td>
                                                    <td style="font-size: 9px;"><?php echo $read_bloqueio_view['bloqueio_motivo']; ?></td>
                                                    <td style="font-size: 9px;"><?php echo $data_diff; ?></td>
                                                    <td><a href="Home.php?model=chip&pg=bloqueio_view&Id=<?php echo $read_bloqueio_view['financeiro_id_contato']; ?>&consta=<?php echo $tipo_bloqueio; ?>" class="ico-address-book" data-toggle="tooltip" data-placement="top" title="Editar" /><i class='fa fa-pencil' style='vertical-align:middle; padding:2px 0;' title='Editar'></i></a></td>

                                                </tr>
                                                <?php
                                                }
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function() {
        $( ".search_nome_contato" ).autocomplete({
            source: "_controller/_contato.php?acao=load_contato",
            minLength: 2,
            focus: function(event, ui) {
            	event.preventDefault();
            },
            select: function( event, ui ) {
                $(".search_nome_id_contato").val(ui.item.value);
                $(".search_nome_contato").val(ui.item.label);
                event.preventDefault();
            }
        });
        buscar_contato();
    });
    function buscar_contato(){
        var search_nome_id_contato = $(".search_nome_id_contato").val();
        var acao = "acao=load_contato_id&id="+search_nome_id_contato;
        
        if(search_nome_id_contato){
            $.ajax({
                type: 'POST',
                url: "_controller/_contato.php",
                data: acao,
                beforeSend: load_in(),
                success: function (data) {
                    load_out();
                    var data_return = jQuery.parseJSON(data);
                    if(data_return[0].label !== ''){
                        $(".search_nome_contato").val(data_return[0].label);
                    }else{
                        $("#_modal").modal('show');
                        $("#title_modal").html('Erro');
                        $("#texto_modal").html('Ops, não foi encontrado nenhum registro');
                        $("#buttons_modal").html('');
                        $(".search_nome_id_contato").val('');
                        $(".search_nome_contato").val('');
                    }
                }
            });
        }
    }
    function search(){
        var search_situacao = $(".search_situacao").val();
        var search_nome_id_contato = $(".search_nome_id_contato").val();
        
        window.location = 'Home.php?model=chip&pg=bloqueio&situacao='+search_situacao+"&id_contato="+search_nome_id_contato;
    }
</script>