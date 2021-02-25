<?php
    if(isset($_GET['id_ativacao'])){
        $get_id = addslashes($_GET['id_ativacao']);
        $read_ativacao = Read('ativacao', "WHERE ativacao_id = '".$get_id."' AND ativacao_status = '0'");
        if(NumQuery($read_ativacao) > '0'){
            foreach($read_ativacao as $read_ativacao_view);
        }else{
        }
        $tipo_operacao = 'Ativação';
        $tipo_operacao_operador = '0';
    }else{
        $get_id = addslashes($_GET['id_desativacao']);
        $read_desativacao = Read('desativacao', "WHERE desativacao_id = '".$get_id."' AND desativacao_status = '0'");
        if(NumQuery($read_desativacao) > '0'){
            foreach($read_desativacao as $read_desativacao_view);
        }else{
        }
        $tipo_operacao = 'Desativação';
        $tipo_operacao_operador = '1';
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
                                Solicitação <?php echo $tipo_operacao;?> Cliente
                            </h3>
                        </div>
                        <div class="box-content">
                            <div class="invoice-info">
                                <div class="invoice-name">
                                    Federal Sistemas
                                </div>
                                <div class="invoice-infos">
                                    <table>
                                        <tr>
                                            <th>Data:</th>
                                            <?php
                                                if($tipo_operacao_operador == '0'){
                                                    echo '<td>'.FormDataBr($read_ativacao_view['ativacao_data']).'</td>';
                                                }else{
                                                    echo '<td>'.FormDataBr($read_desativacao_view['desativacao_data']).'</td>';
                                                }
                                            ?>
                                        </tr>
                                        <tr>
                                            <th>ID Solicitação #:</th>
                                            <td><?php echo $get_id;?></td>
                                        </tr>
                                        <tr>
                                            <th>Cliente:</th>
                                            <?php
                                                if($tipo_operacao_operador == '0'){
                                                    echo '<td>'.GetDados('contato', $read_ativacao_view['ativacao_id_contato'], 'contato_id', 'contato_nome_razao').'</td>';
                                                }else{
                                                    echo '<td>'.GetDados('contato', $read_desativacao_view['desativacao_id_contato'], 'contato_id', 'contato_nome_razao').'</td>';
                                                }
                                            ?>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <form action="" name="formulario" id="desativacao" method="post">
                                <table class="table table-striped table-invoice">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>ICCID</th>
                                            <th>Linha</th>
                                            <th>Tipo</th>
                                            <?php
                                                if($tipo_operacao_operador == '1'){
                                                    echo '<th>Faturar Chip<input type="checkbox" name="SelTodos" onclick="selTodos(document.formulario)" value="1"></th>';
                                                }
                                            ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $count_chip = '0';
                                            if($tipo_operacao_operador == '0'){
                                                $read_chip = ReadComposta("SELECT itens_ativacao.itens_ativacao_id, chip.chip_iccid, chip.chip_num, chip.chip_plano FROM itens_ativacao INNER JOIN chip ON chip.chip_id = itens_ativacao.itens_ativacao_id_chip WHERE itens_ativacao.itens_ativacao_id_ativacao = '".$get_id."'");
                                            }else{
                                                $read_chip = ReadComposta("SELECT itens_desativacao.itens_desativacao_id, chip.chip_iccid, chip.chip_num, chip.chip_plano, chip.chip_id FROM itens_desativacao INNER JOIN chip ON chip.chip_id = itens_desativacao.itens_desativacao_id_chip WHERE itens_desativacao.itens_desativacao_id_ativacao = '".$get_id."'");
                                            }
                                            if(NumQuery($read_chip) > '0'){
                                                foreach($read_chip as $read_chip_view){
                                                    $count_chip++;
                                                    $iccid_completo = $read_chip_view['chip_iccid'].',';
                                        ?>
                                        <tr>
                                            <td><?php echo $count_chip;?></td>
                                            <td><?php echo $read_chip_view['chip_iccid'];?></td>
                                            <td><?php echo $read_chip_view['chip_num'];?></td>
                                            <td><?php echo $read_chip_view['chip_plano'];?></td>
                                            <?php
                                                if($tipo_operacao_operador == '1'){
                                                    echo '<td><input type="checkbox" checked value="'.$read_chip_view['chip_id'].'" name="id_chip[]" /></td>';
                                                }
                                            ?>
                                        </tr>
                                        <?php
                                                }
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </form>
                            <div class="invoice-payment">
                                <span>Assim que clicar no botão abaixo, não será mais possivel cancelar a operação sem ajuda do suporte. Caso clique sem querer é preciso abrir um ticket.</span>
                                <p><?php echo $iccid_completo;?></p>
                                <hr />
                                <p id="load_msg_dados"></p>
                                <?php
                                    if($tipo_operacao_operador == '0'){
                                        if(NumQuery($read_ativacao) > '0'){
                                            echo '<button class="btn btn-success barra_dados" onclick="confirmar_operacao();">Confirmar Operação</button>';
                                        }
                                    }else{
                                        if(NumQuery($read_desativacao) > '0'){
                                            echo '<div class="row">
                                                    <div class="form-group col-lg-12">
                                                        <label>Valor do chip</label>
                                                        <select class="form-control valor_chip">
                                                            <option value="0">R$ 5,00</option>
                                                            <option value="10">R$ 10,00</option>
                                                            <option value="15" selected="">R$ 15,00</option>
                                                        </select>
                                                    </div>
                                                </div>';
                                            echo '<button class="btn btn-success barra_dados" onclick="confirmar_operacao_desativacao();">Confirmar Operação</button>';
                                        }
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function load_in_pedido(){
        $('.load').fadeIn("fast");
        $('.barra_dados').fadeOut("fast");
    }
    function confirmar_operacao(){
        var acao = "&acao=confirmar_operacao&id_ativacao=<?=$get_id;?>";
        
        $.ajax({
            type: 'POST',
            url: "_controller/_ticket.php",
            data: acao,
            beforeSend: load_in_pedido(),
            success: function (data) {
                load_out();
                $("#load_msg_dados").html('Operação realizada com sucesso!');
                setTimeout(function(){window.close();}, 3000);
            }
        });
    }
    function confirmar_operacao_desativacao(){
        var dados = $("#desativacao").serialize();
        var valor_chip = $(".valor_chip").val();
        var acao = "&acao=confirmar_operacao_desativacao&id_desativacao=<?=$get_id;?>&valor_chip="+valor_chip;
        
        $.ajax({
            type: 'POST',
            url: "_controller/_ticket.php",
            data: dados+acao,
            beforeSend: load_in_pedido(),
            success: function (data) {
                load_out();
                $("#load_msg_dados").html('Operação realizada com sucesso!');
                setTimeout(function(){window.close();}, 3000);
            }
        });
    }
    function selTodos(Check) {
        if (document.formulario.SelTodos.checked == true) {
            for (i = 0; i < Check.length; i++) {
                Check[i].checked = true;
            }
        } else {
            for (i = 0; i < Check.length; i++) {
                Check[i].checked = false;
            }
        }
    }
</script>