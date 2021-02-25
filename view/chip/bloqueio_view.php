<?php
$get_id_cliente = addslashes($_GET['Id']);
$consta = addslashes($_GET['consta']);
?>
<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Bloqueio - <?php echo GetDados('contato', $get_id_cliente, 'contato_id', 'contato_nome_razao');?> - (<?php echo $consta;?>)
                            </h3>
                        </div>
                        <div class="box-content">
                            <?php
                    if(isset($_POST['sendCreate'])){
                        $create_bloqueio['bloqueio_id_contato'] = $get_id_cliente;
                        $create_bloqueio['bloqueio_data'] = date('Y-m-d');
                        $create_bloqueio['bloqueio_motivo'] = addslashes($_POST['motivo']);
                        $create_bloqueio['bloqueio_tipo'] = addslashes($_POST['tipo']);
                        $create_bloqueio['bloqueio_consta'] = $consta;
                        if(in_array('', $create_bloqueio)){
                            echo "<script>alert('Campos inválidos')</script>";
                        }else{
                            Create('bloqueio', $create_bloqueio);
                            if (trim($create_bloqueio['bloqueio_tipo']) == '0') {
                                $tipo_bloqueio_up = '0';
                            } elseif (trim($create_bloqueio['bloqueio_tipo']) == '1') {
                                $tipo_bloqueio_up = '1';
                            } elseif (trim($create_bloqueio['bloqueio_tipo']) == '2') {
                                $tipo_bloqueio_up = '0';
                            } elseif (trim($create_bloqueio['bloqueio_tipo']) == '3') {
                                $tipo_bloqueio_up = '3';
                            } elseif (trim($create_bloqueio['bloqueio_tipo']) == '4') {
                                $tipo_bloqueio_up = '4';
                            }
                            $UpCliBloqueio['contato_bloqueio'] = $tipo_bloqueio_up;
                            Update('contato', $UpCliBloqueio, "WHERE contato_id = '".$get_id_cliente."'");
                            echo "<script>alert('Operação realizada com sucesso!')</script>";
                            echo "<script>window.location = '/Home.php?model=chip&pg=bloqueio_viewId=".$get_id_cliente."&consta=".$consta."'</script>";
                        }
                    }
                ?>
                <form action="" method="post">
                    <fieldset>
                        <div class="row">
                            <div class="form-group col-lg-12">
                                <label>Tipo</label>
                                <select name="tipo" class="form-control">
                                    <option value="0">LIBERADO</option>
                                    <option value="1">BLOQUEADO</option>
                                    <option value="3">SUSPENSO</option>
									<option value="4">MIGRADOS</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-12">
                                <label>Motivo</label>
                                <textarea name="motivo" class="form-control" rows="" cols=""></textarea>
                            </div>
                        </div>
                        <div class="form-group col-lg-12">
                            <button type="submit" class="btn btn-primary" name="sendCreate">Cadastrar</button>
                            <a href="Home.php?model=chip&pg=bloqueio" class="btn btn-danger">Voltar</a>
                        </div>
                    </fieldset>
                </form>
                <hr />
                <div style="width: 100%; height: 300px; overflow-x: scroll; overflow-y: scroll;">
                    <table class="table ls-table">
                        <thead>
                            <tr>
                                <th style="font-size: 9px;">Data</th>
                                <th style="font-size: 9px;">Motivo</th>
                                <th class="hidden-xs" style="font-size: 9px;">Tipo</th>
                                <th style="font-size: 9px;">Constava</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $read_bloqueio = ReadComposta("SELECT * FROM bloqueio WHERE bloqueio_id_contato= '".$get_id_cliente."' ORDER BY bloqueio_data DESC");
                            if (NumQuery($read_bloqueio) > '0') {
                                foreach ($read_bloqueio as $read_bloqueio_view) {
                                    if ($read_bloqueio_view['bloqueio_tipo'] == '0') {
                                        $tipo_bloqueio = 'Liberado';
                                    } elseif ($read_bloqueio_view['bloqueio_tipo'] == '1') {
                                        $tipo_bloqueio = 'Bloqueado';
                                    } elseif ($read_bloqueio_view['bloqueio_tipo'] == '3') {
                                        $tipo_bloqueio = 'Suspenso';
                                    } elseif ($read_bloqueio_view['bloqueio_tipo'] == '4') {
                                        $tipo_bloqueio = 'Migrado';
                                    }
                                    ?>
                                    <tr>
                                        <td style="font-size: 9px;"><?php echo FormDataBr($read_bloqueio_view['bloqueio_data']); ?></td>
                                        <td style="font-size: 9px;"><?php echo $read_bloqueio_view['bloqueio_motivo']; ?></td>
                                        <td class="hidden-xs" style="font-size: 9px;"><?php echo $tipo_bloqueio; ?></td>
                                        <td style="font-size: 9px;"><?php echo $read_bloqueio_view['bloqueio_consta']; ?></td>
                                    </tr>
                                    <?php
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

</script>