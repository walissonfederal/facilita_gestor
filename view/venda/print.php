<?php
    session_start();
    ob_start();
    require_once '../../_class/Ferramenta.php';
    if(empty($_SESSION[VSESSION])){
        header("Location: ../../index.php");
    }
    $uid = addslashes($_GET['id']);
    $read_orcamento_venda = Read('orcamento_venda', "WHERE orcamento_venda_id = '".$uid."'");
    if(NumQuery($read_orcamento_venda) > '0'){
        foreach($read_orcamento_venda as $read_orcamento_venda_view);
        $read_contato = Read('contato', "WHERE contato_id = '".$read_orcamento_venda_view['orcamento_venda_id_contato']."'");
        if(NumQuery($read_contato) > '0'){
            foreach($read_contato as $read_contato_view);
        }
    }
?>
<style>
    body, tr, td{
        font:Arial, Helvetica, sans-serif;
        margin:0px;
        padding:0px;
        font-family:Arial;
        font-size:12px;
    }
    .style1{ font-size:16px; font-weight:bold; }
    .Td_Borda{ border-bottom:1px #999 dashed; }
</style>
<table width="750" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td colspan="4">
            <table width="750" border="0" align="center" cellpadding="2" cellspacing="2">
                <tr>
                    <td height="20" colspan="4" align="left">

                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td width="130" align="left">
                                    <img src="" border="0" />
                                </td>
                                <td align="right">
                                    <strong>
                                        <?php echo GetEmpresa('empresa_nome_razao');?>
                                    </strong>
                                    <br />
                                    <?php echo GetEmpresa('empresa_endereco');?>, <?php echo GetEmpresa('empresa_numero');?><br />
                                    <?php echo GetEmpresa('empresa_bairro');?>, <?php echo GetEmpresa('empresa_estado');?>, <?php echo GetEmpresa('empresa_cidade');?>
                                    <br />
                                    <?php echo GetEmpresa('empresa_telefone');?><br />
                                    <?php echo GetEmpresa('empresa_email');?><br />
                                    </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td height="20" colspan="4" align="center">
            <strong style="font-size:25px">VENDA <?php echo $uid;?></strong>
        </td>
    </tr>
    <tr>
        <td height="20" colspan="2" align="left">
            <strong style="font-size:13px">DADOS DO CLIENTE</strong>
        </td>
        <td height="20" colspan="2" align="right">
            VENDEDOR: <?php echo GetDados('user', $read_orcamento_venda_view['orcamento_venda_id_user'], 'user_id', 'user_nome');?> - Data: <?php echo FormDataBr($read_orcamento_venda_view['orcamento_venda_data']);?>
        </td>
    </tr>
    <tr>
        <td colspan="4" align="left"><hr color="#000000"></td>
    </tr>
    <tr>
        <td height="20" colspan="2" align="left">
            <strong>CLIENTE:</strong> <?php echo $read_contato_view['contato_nome_razao'];?>
        </td>
        <td height="20" align="left"><?php echo $read_contato_view['contato_endereco'];?>, <?php echo $read_contato_view['contato_numero'];?></td>
        <td height="20" align="left"><?php echo $read_contato_view['contato_email'];?></td>
    </tr>
</table>
<table width="750" border="0" align="center" cellpadding="2" cellspacing="2">
    <tr>
        <td colspan="8" align="left">&nbsp;</td>
    </tr>
    <tr>
        <td height="20" colspan="8" align="left">
            <strong style="font-size:13px">DADOS DO VENDA</strong>
        </td>
    </tr>
    <tr>
        <td colspan="8" align="left"><hr color="#000000"></td>
    </tr>
    <tr>
        <td align="left"><strong>DESCRI&Ccedil;&Atilde;O</strong></td>
        <td height="20" align="left"><strong>QTDE.</strong></td>
        <td height="20" align="right"><strong>VALOR UNIT.</strong></td>
        <td height="20" colspan="2" align="right"><strong>VALOR TOTAL</strong></td>
    </tr>
    <?php
        $count_itens = '1';
        $read_itens_orcamento_venda = Read('itens_orcamento_venda', "WHERE itens_orcamento_venda_id_orcamento_venda = '".$uid."'");
        if(NumQuery($read_itens_orcamento_venda) > '0'){
            foreach($read_itens_orcamento_venda as $read_itens_orcamento_venda_view){
    ?>
    <tr>
        <td align="left" valign="top" class="Td_Borda">
            <?php echo $count_itens;?>. 
            <?php echo GetDados('produto', $read_itens_orcamento_venda_view['itens_orcamento_venda_id_produto'], 'produto_id', 'produto_descricao');?>
        </td>
        <td height="20" align="left" valign="top" class="Td_Borda">
            <?php echo $read_itens_orcamento_venda_view['itens_orcamento_venda_qtd'];?>
        </td>
        <td height="20" align="right" valign="top" nowrap="nowrap" class="Td_Borda">
            R$ <?php echo FormatMoney($read_itens_orcamento_venda_view['itens_orcamento_venda_valor_unitario']);?>
        </td>
        <td height="20" colspan="2" align="right" valign="top" nowrap="nowrap" class="Td_Borda">
            R$ <?php echo FormatMoney($read_itens_orcamento_venda_view['itens_orcamento_venda_valor_total']);?>
        </td>
    </tr>
    <?php
            $count_itens++;
            }
        }
    ?>
</table>
<table width="750" border="0" align="center" cellpadding="2" cellspacing="2">
    <tr>
        <td colspan="3" align="left">&nbsp;</td>
    </tr>
    <tr>
        <td height="20" colspan="3" align="left">
            <strong style="font-size:13px">DADOS DE PAGAMENTO</strong>
        </td>
    </tr>
    <tr>
        <td colspan="4" align="left"><hr color="#000000"></td>
    </tr>
    <tr>
        <td height="20" align="left">
            <strong>TOTAL DE QUANTIDADES</strong>
        </td>
        <td height="20" colspan="2" align="left">
            <strong><?php echo $read_orcamento_venda_view['orcamento_venda_qtd_parcelas'];?></strong>
        </td>
    </tr>
    <tr>
        <td height="20" align="left">
            <strong>VALOR TOTAL DOS PRODUTOS</strong>
        </td>
        <td height="20" colspan="2" align="left">
            <strong>R$ <?php echo FormatMoney($read_orcamento_venda_view['orcamento_venda_valor_produtos']);?></strong>
        </td>
    </tr>
    <tr>
        <td height="20" align="left">
            <strong>VALOR DO FRETE</strong>
            (<?php echo $read_orcamento_venda_view['orcamento_venda_transportadora'];?>)
        </td>
        <td height="20" colspan="2" align="left">
            <strong>R$ <?php echo FormatMoney($read_orcamento_venda_view['orcamento_venda_valor_frete']);?></strong>
        </td>
    </tr>
    <tr>
        <td height="20" align="left"><strong>VALOR TOTAL DA VENDA</strong></td>
        <td height="20" colspan="2" align="left">
            <strong>R$ <?php echo FormatMoney($read_orcamento_venda_view['orcamento_venda_valor_total']);?></strong>
        </td>
    </tr>
    <tr>
        <td height="20" colspan="3" align="left">
            <table width="100%" border="0" cellspacing="1" cellpadding="1">
                <?php
                    $count_pagamento = '1';
                    $read_pagamento = Read('pagamento', "WHERE pagamento_id_orcamento_venda = '".$uid."'");
                    if(NumQuery($read_pagamento) > '0'){
                        foreach($read_pagamento as $read_pagamento_view){
                ?>
                <tr>
                    <td width="100">
                        <strong>PARCELA <?php echo $count_pagamento;?></strong>
                    </td>
                    <td valign="top">
                        <strong>DATA:</strong> 
                        <?php echo FormDataBr($read_pagamento_view['pagamento_data']);?>
                    </td>
                    <td valign="top">
                        <strong>VALOR:</strong> 
                        R$ <?php echo FormatMoney($read_pagamento_view['pagamento_valor']);?>
                    </td>
                    <td valign="top">
                        <strong>PAGAMENTO:</strong> <?php echo GetDados('forma_pagamento', $read_pagamento_view['pagamento_id_forma_pagamento'], 'forma_pagamento_id', 'forma_pagamento_descricao');?>
                    </td>
                    <td valign="top">
                    </td>
                </tr>
                <?php
                        $count_pagamento++;
                        }
                    }
                ?>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="3" align="left">&nbsp;</td>
    </tr>
    <tr>
        <td height="20" colspan="3" align="left">
            <strong style="font-size:13px">OBSERVA&Ccedil;&Otilde;ES</strong>
        </td>
    </tr>
    <tr>
        <td colspan="4" align="left"><hr color="#000000"></td>
    </tr>
    <tr>
        <td height="20" colspan="3" align="left">
            <span>OBS</span>
            <?php echo $read_orcamento_venda_view['orcamento_venda_obs'];?>
        </td>
    </tr>
    <tr>
        <td height="20" colspan="3">
            <strong>VALIDADE:</strong> <?php echo $read_orcamento_venda_view['orcamento_venda_validade'];?>&nbsp;</td>
    </tr>
    <tr>
        <td height="20" colspan="3">
            <strong>PRAZO DE ENTREGA:</strong> <?php echo FormDataBr($read_orcamento_venda_view['orcamento_venda_data_prazo']);?>&nbsp;</td>
    </tr>
    <tr>
        <td height="20" colspan="3" align="center">&nbsp;</td>
    </tr>
</table>    
<br>
<script>
    //window.print();
</script>
