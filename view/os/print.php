<?php
    session_start();
    ob_start();
    require_once '../../_class/Ferramenta.php';
    if(empty($_SESSION[VSESSION])){
        header("Location: ../../index.php");
    }
    $uid = addslashes($_GET['id']);
    $read_os = Read('os', "WHERE os_id = '".$uid."'");
    if(NumQuery($read_os) > '0'){
        foreach($read_os as $read_os_view);
        $read_contato = Read('contato', "WHERE contato_id = '".$read_os_view['os_id_contato']."'");
        if(NumQuery($read_contato) > '0'){
            foreach($read_contato as $read_contato_view);
            if($read_os_view['os_status'] == '0'){
                $read_os_view['os_status'] = 'Orçamento';
            }elseif($read_os_view['os_status'] == '1'){
                $read_os_view['os_status'] = 'Aberto';
            }elseif($read_os_view['os_status'] == '2'){
                $read_os_view['os_status'] = 'Faturado';
            }elseif($read_os_view['os_status'] == '3'){
                $read_os_view['os_status'] = 'Finalizado';
            }elseif($read_os_view['os_status'] == '4'){
                $read_os_view['os_status'] = 'Cancelado';
            }
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
            <strong style="font-size:25px">ORDEM DE SERVIÇO #<?php echo $uid;?></strong>
        </td>
    </tr>
    <tr>
        <td height="20" colspan="2" align="left">
            <strong style="font-size:13px">DADOS DO CLIENTE</strong>
        </td>
        <td height="20" colspan="2" align="right">
            RESPONSÁVEL / TÉCNICO: <?php echo GetDados('user', $read_os_view['os_id_user'], 'user_id', 'user_nome');?> - Data Inicial: <?php echo FormDataBr($read_os_view['os_data_inicial']);?>
        </td>
    </tr>
    <tr>
        <td colspan="4" align="left"><hr color="#000000"></td>
    </tr>
    <tr>
        <td height="20" colspan="4" align="left">
            <strong>CLIENTE:</strong> <?php echo $read_contato_view['contato_nome_razao'];?>
        </td>
    </tr>
	<tr>
		<td height="20" align="left" colspan="2"><strong>ENDEREÇO COMPLETO:</strong> <?php echo $read_contato_view['contato_endereco'];?>, <?php echo $read_contato_view['contato_numero'];?></td>
        <td height="20" align="left"><strong>BAIRRO:</strong><?php echo $read_contato_view['contato_bairro'];?></td>
		<td height="20" align="left"><strong>COMPLEMENTO:</strong><?php echo $read_contato_view['contato_complemento'];?></td>
	</tr>
	<tr>
		<td height="20" align="left" colspan="2"><strong>UF/CIDADE:</strong> <?php echo $read_contato_view['contato_cidade'];?> / <?php echo $read_contato_view['contato_uf'];?></td>
        <td height="20" align="left" colspan="2"><strong>EMAIL:</strong><?php echo $read_contato_view['contato_email'];?></td>
	</tr>
	<tr>
        <td colspan="4" align="left"><hr color="#000000"></td>
    </tr>
	<?php
		$read_associado = Read('os_client', "WHERE os_client_id_os = '".$read_os_view['os_id']."'");
		if(NumQuery($read_associado) > '0'){
			foreach($read_associado as $read_associado_view){
	?>
	<tr>
        <td height="20" colspan="3" align="left">
            <strong>ASSOCIADO:</strong> <?php echo $read_associado_view['os_client_name'];?>
        </td>
		<td height="20" align="left">
            <strong>CPF:</strong> <?php echo $read_associado_view['os_client_cpf'];?>
        </td>
    </tr>
	<tr>
		<td height="20" align="left" colspan="2"><strong>ENDEREÇO COMPLETO:</strong> <?php echo $read_associado_view['os_client_endereco'];?>, <?php echo $read_associado_view['os_client_numero'];?></td>
        <td height="20" align="left"><strong>BAIRRO:</strong><?php echo $read_associado_view['os_client_bairro'];?></td>
		<td height="20" align="left"><strong>TELEFONES:</strong><?php echo $read_associado_view['os_client_telefone'];?></td>
	</tr>
	<tr>
		<td height="20" align="left" colspan="2"><strong>UF/CIDADE:</strong> <?php echo $read_associado_view['contato_cidade'];?> / <?php echo $read_associado_view['os_client_uf'];?></td>
        <td height="20" align="left" colspan="2"><strong>EMAIL:</strong><?php echo $read_associado_view['os_client_email'];?></td>
	</tr>
    <tr>
        <td colspan="4" align="left"><hr color="#000000"></td>
    </tr>
	<?php
			}
		}
	?>
</table>
<table width="750" border="0" align="center" cellpadding="2" cellspacing="2">
    <?php
        if($read_os_view['os_descricao'] != ''){
    ?>
    <tr>
        <td height="20" colspan="3" align="left">
            <strong style="font-size:13px">DESCRIÇÃO:</strong><br /><br />
            <?php echo $read_os_view['os_descricao'];?>
        </td>
    </tr>
    <tr>
        <td colspan="4" align="left"><hr color="#000000"></td>
    </tr>
    <?php
        }
    ?>
    <?php
        if($read_os_view['os_defeito'] != ''){
    ?>
    <tr>
        <td height="20" colspan="3" align="left">
            <strong style="font-size:13px">DEFEITO:</strong><br /><br />
            <?php echo $read_os_view['os_defeito'];?>
        </td>
    </tr>
    <tr>
        <td colspan="4" align="left"><hr color="#000000"></td>
    </tr>
    <?php
        }
    ?>
    <?php
        if($read_os_view['os_obs'] != ''){
    ?>
    <tr>
        <td height="20" colspan="3" align="left">
            <strong style="font-size:13px">OBS:</strong><br /><br />
            <?php echo $read_os_view['os_obs'];?>
        </td>
    </tr>
    <tr>
        <td colspan="4" align="left"><hr color="#000000"></td>
    </tr>
    <?php
        }
    ?>
    <?php
        if($read_os_view['os_laudo_tecnico'] != ''){
    ?>
    <tr>
        <td height="20" colspan="3" align="left">
            <strong style="font-size:13px">LAUDO TÉCNICO:</strong><br /><br />
            <?php echo $read_os_view['os_laudo_tecnico'];?>
        </td>
    </tr>
    <tr>
        <td colspan="4" align="left"><hr color="#000000"></td>
    </tr>
    <?php
        }
    ?>
    <tr>
        <td height="20" colspan="3">
            <strong>GARANTIA:</strong> <?php echo $read_os_view['os_garantia'];?>&nbsp;</td>
    </tr>
    <tr>
        <td height="20" colspan="3">
            <strong>DATA FINAL:</strong> <?php if($read_os_view['os_data_final'] == '0000-00-00'){echo '';}else{FormDataBr($read_os_view['orcamento_venda_data_prazo']);}?>&nbsp;</td>
    </tr>
    <tr>
        <td height="20" colspan="3">
            <strong>STATUS:</strong> <?php echo $read_os_view['os_status'];?>&nbsp;</td>
    </tr>
    <tr>
        <td height="20" colspan="3" align="center">&nbsp;</td>
    </tr>
</table>
<?php
$read_itens_orcamento_venda = Read('itens_os', "WHERE itens_os_id_os = '".$uid."'");
if(NumQuery($read_itens_orcamento_venda) > '0'){
?>
<table width="750" border="0" align="center" cellpadding="2" cellspacing="2">
    <tr>
        <td colspan="8" align="left">&nbsp;</td>
    </tr>
    <tr>
        <td height="20" colspan="8" align="left">
            <strong style="font-size:13px">DADOS DA ORDEM DE SERVIÇO</strong>
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
        
        if(NumQuery($read_itens_orcamento_venda) > '0'){
            foreach($read_itens_orcamento_venda as $read_itens_orcamento_venda_view){
                $sub_total_os += $read_itens_orcamento_venda_view['itens_os_valor_total'];
    ?>
    <tr>
        <td align="left" valign="top" class="Td_Borda">
            <?php echo $count_itens;?>. 
            <?php echo GetDados('produto', $read_itens_orcamento_venda_view['itens_os_id_produto'], 'produto_id', 'produto_descricao');?>
        </td>
        <td height="20" align="left" valign="top" class="Td_Borda">
            <?php echo $read_itens_orcamento_venda_view['itens_os_quantidade'];?>
        </td>
        <td height="20" align="right" valign="top" nowrap="nowrap" class="Td_Borda">
            R$ <?php echo FormatMoney($read_itens_orcamento_venda_view['itens_os_valor_unitario']);?>
        </td>
        <td height="20" colspan="2" align="right" valign="top" nowrap="nowrap" class="Td_Borda">
            R$ <?php echo FormatMoney($read_itens_orcamento_venda_view['itens_os_valor_total']);?>
        </td>
    </tr>
    <?php
            $count_itens++;
            }
        }
    ?>
    <tr>
        <td height="20" align="right" colspan="4">
            <strong>TOTAL</strong>
        </td>
        <td height="20" align="right">
            <strong>R$ <?php echo FormatMoney($sub_total_os);?></strong>
        </td>
    </tr>
</table>
<?php
}
?>
<?php
    $read_veiculo_os = Read('os_veiculo', "WHERE os_veiculo_id_os = '".$uid."'");
    if(NumQuery($read_veiculo_os) > '0'){
?>
<table width="750" border="0" align="center" cellpadding="2" cellspacing="2">
    <tr>
        <td colspan="9" align="left">&nbsp;</td>
    </tr>
    <tr>
        <td height="20" colspan="9" align="left">
            <strong style="font-size:13px">VEÍCULOS DA ORDEM DE SERVIÇO</strong>
        </td>
    </tr>
    <tr>
        <td colspan="9" align="left"><hr color="#000000"></td>
    </tr>
    <tr>
        <td align="left"><strong>FROTA</strong></td>
        <td height="20" align="left"><strong>PLACA</strong></td>
        <td height="20" align="left"><strong>MODELO</strong></td>
        <td height="20" align="left"><strong>MARCA</strong></td>
        <td height="20" align="left"><strong>COR</strong></td>
        <td height="20" align="left"><strong>ANO</strong></td>
        <td height="20" align="left"><strong>CHASSI</strong></td>
        <td height="20" align="left"><strong>ICCID</strong></td>
        <td height="20" align="left"><strong>SERIAL</strong></td>
    </tr>
    <?php
        $count_itens = '1';
        if(NumQuery($read_veiculo_os) > '0'){
            foreach($read_veiculo_os as $read_veiculo_os_view){
    ?>
    <tr>
        <td align="left" valign="top" class="Td_Borda">
            <?php echo $count_itens;?>. 
            <?php echo $read_veiculo_os_view['os_veiculo_frota'];?>
        </td>
        <td height="20" align="left" valign="top" class="Td_Borda">
            <?php echo $read_veiculo_os_view['os_veiculo_placa'];?>
        </td>
        <td height="20" align="left" valign="top" nowrap="nowrap" class="Td_Borda">
            <?php echo $read_veiculo_os_view['os_veiculo_modelo'];?>
        </td>
        <td height="20" colspan="" align="left" valign="top" nowrap="nowrap" class="Td_Borda">
            <?php echo $read_veiculo_os_view['os_veiculo_marca'];?>
        </td>
        <td height="20" colspan="" align="left" valign="top" nowrap="nowrap" class="Td_Borda">
            <?php echo $read_veiculo_os_view['os_veiculo_cor'];?>
        </td>
        <td height="20" colspan="" align="left" valign="top" nowrap="nowrap" class="Td_Borda">
            <?php echo $read_veiculo_os_view['os_veiculo_ano'];?>
        </td>
        <td height="20" colspan="" align="left" valign="top" nowrap="nowrap" class="Td_Borda">
            <?php echo $read_veiculo_os_view['os_veiculo_chassi'];?>
        </td>
        <td height="20" colspan="" align="left" valign="top" nowrap="nowrap" class="Td_Borda">
            <?php echo $read_veiculo_os_view['os_veiculo_iccid'];?>
        </td>
        <td height="20" colspan="" align="left" valign="top" nowrap="nowrap" class="Td_Borda">
            <?php echo $read_veiculo_os_view['os_veiculo_serial'];?>
        </td>
    </tr>
    <?php
            $count_itens++;
            }
        }
    ?>
</table>
<?php
    }
?>
<div style="font-size:13px; font-variant: small-caps; font-weight: bold;" align="center">OBSERVAÇÕES DO TÉCNICO</div>
<table width="750" border="1" align="center">
    <tr>
        <td align="left" width="100%">.</td>
    </tr>
    <tr>
        <td align="left" width="100%">.</td>
    </tr>
    <tr>
        <td align="left" width="100%">.</td>
    </tr>
    <tr>
        <td align="left" width="100%">.</td>
    </tr>
    <tr>
        <td align="left" width="100%">.</td>
    </tr>
</table>
<table border="0">
    <tr>
        <td>
            <br /><br /><br /><br /><br />
            _______________________________________<br />
            <div align="center" style="font-size:12px">TÉCNICO</div>
            <div align="center" style="font-size:12px">____/____/_________</div>
        </td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>
            <br /><br /><br /><br /><br />
            _______________________________________<br />
            <div align="center" style="font-size:12px">CLIENTE</div>
            <div align="center" style="font-size:12px">____/____/_________</div>
        </td>
    </tr>
</table>
<script>
    //window.print();
</script>
