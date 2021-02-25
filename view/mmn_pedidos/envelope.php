<?php
    session_start();
    ob_start();
    require_once '../../_class_mmn/Ferramenta.php';
    $UrlDoSistema = "http://".$_SERVER['SERVER_NAME'].''.$_SERVER['REQUEST_URI'];
    define('BASEATUAL', $UrlDoSistema);
    
    $GetId = addslashes($_GET['id_pedido']);
    $read_pedido = Read('pedido', "WHERE pedido_id = '".$GetId."'");
    if(NumQuery($read_pedido) > '0'){
        foreach ($read_pedido as $read_pedido_view);
		$read_endereco_pedido = Read('endereco_pedido', "WHERE endereco_pedido_id_pedido = '" . $read_pedido_view['pedido_id'] . "'");
		if (NumQuery($read_endereco_pedido) > '0') {
			foreach ($read_endereco_pedido as $read_endereco_pedido_view)
				;
		}
    }
    $readContato = Read('user', "WHERE user_id = '".$read_pedido_view['pedido_id_user']."'");
    if(NumQuery($readContato) > '0'){
        foreach($readContato as $readContatoView);
    }else{
        echo "<script>window.close();</script>";
    }
?>
<style type="text/css" >
.textoVertical {
	writing-mode:tb-rl;
	-webkit-transform:rotate(270deg); //tente 90 no lugar de 270
	-moz-transform:rotate(270deg);
	-o-transform: rotate(270deg);
	white-space:nowrap;
	display:block;
	bottom:0;
	width:20px;
	height:20px;
	font-family: Arial, Helvetica, sans-serif;
	font-size:24px;
	font-weight:normal;
	text-shadow: 0px 0px 1px #333;
	margin-top:50px;
}
.textoVerticalTable {
	writing-mode:tb-rl;
	-webkit-transform:rotate(180deg);
	-moz-transform:rotate(180deg);
	-o-transform: rotate(180deg);
        margin-left: 150px;
}
.textoVerticalTableIMG {
	writing-mode:tb-rl;
	-webkit-transform:rotate(270deg);
	-moz-transform:rotate(270deg);
	-o-transform: rotate(270deg);
        margin-left: -50px;
        margin-top: -170px;
}
</style>

<table width="" height="600" border="1" class="textoVerticalTable">
    <tr>
        <td colspan="4"><strong>DESTINATÁRIO</strong></td>
    </tr>
    <tr>
        <td colspan="4"><strong>Nome / Razão Social</strong></td>
    </tr>
    <tr>
        <td colspan="4"><?php echo $readContatoView['user_nome'];?></td>
    </tr>
    <tr>
        <td><strong>Endereço</strong></td>
        <td><strong>Número</strong></td>
        <td><strong>Bairro</strong></td>
        <td><strong>Complemento</strong></td>
    </tr>
    <tr>
        <td><?php echo $read_endereco_pedido_view['endereco_pedido_endereco'];?></td>
        <td><?php echo $read_endereco_pedido_view['endereco_pedido_numero'];?></td>
        <td><?php echo $read_endereco_pedido_view['endereco_pedido_bairro'];?></td>
        <td><?php echo $read_endereco_pedido_view['endereco_pedido_complemento'];?></td>
    </tr>
    <tr>
        <td><strong>Cep</strong></td>
        <td><strong>UF</strong></td>
        <td colspan="2"><strong>Cidade</strong></td>
    </tr>
    <tr>
        <td><?php echo $read_endereco_pedido_view['endereco_pedido_cep'];?></td>
        <td><?php echo $read_endereco_pedido_view['endereco_pedido_uf'];?></td>
        <td colspan="2"><?php echo $read_endereco_pedido_view['endereco_pedido_cidade'];?></td>
    </tr>
</table>
<img src="http://federalsistemas.com.br/erp/img/correios-logo.png" width="300" class="textoVerticalTableIMG" />