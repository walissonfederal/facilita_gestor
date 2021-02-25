<?php
	session_start();
	ob_start();
	require_once '../../_class/Ferramenta.php';

	if($_GET['id_contato'] != ''){
		$md5_unico = md5(date('Y-m-dH:i:s').rand(9,99999999999));
		//$read_pedido = ReadComposta("SELECT * FROM pedido INNER JOIN contato ON contato.contato_id = pedido.pedido_id_cliente INNER JOIN itens_pedido ON itens_pedido.itens_pedido_id_pedido = pedido.pedido_id INNER JOIN chip ON chip.chip_id = itens_pedido.itens_pedido_id_chip WHERE pedido.pedido_id_cliente = '".$_GET['id_contato']."' ORDER BY pedido.pedido_id ASC");
		$read_pedido = Read('pedido', "WHERE pedido_id_cliente = '".$_GET['id_contato']."' AND pedido_status IN(0,1) ORDER BY pedido_id ASC");
		if(NumQuery($read_pedido) > '0'){
			foreach($read_pedido as $read_pedido_view){
				$read_contato = Read('contato', "WHERE contato_id = '".$read_pedido_view['pedido_id_cliente']."'");
				if(NumQuery($read_contato) > '0'){
					foreach($read_contato as $read_contato_view);
				}
				$read_chip = Read('itens_pedido', "WHERE itens_pedido_id_pedido = '".$read_pedido_view['pedido_id']."'");
				if(NumQuery($read_chip) > '0'){
					foreach($read_chip as $read_chip_view){
						$read_pedido_chip = Read('chip', "WHERE chip_id = '".$read_chip_view['itens_pedido_id_chip']."'");
						if(NumQuery($read_pedido_chip) > '0'){
							foreach($read_pedido_chip as $read_pedido_chip_view);
						}
						$excel_form['excel_pedido_id_pedido'] = $read_pedido_view['pedido_id'];
						$excel_form['excel_pedido_id_contato'] = $read_pedido_view['pedido_id_cliente'];
						$excel_form['excel_pedido_contato'] = $read_contato_view['contato_nome_razao'].' - '.$read_contato_view['contato_nome_fantasia'];
						$excel_form['excel_pedido_data_ativacao'] = $read_pedido_view['pedido_data_ativacao'];
						$excel_form['excel_pedido_status'] = $read_pedido_view['pedido_status'];
						$excel_form['excel_pedido_tipo'] = $read_pedido_view['pedido_tipo'];
						$excel_form['excel_pedido_iccid'] = $read_pedido_chip_view['chip_iccid'];
						$excel_form['excel_pedido_num'] = $read_pedido_chip_view['chip_num'];
						$excel_form['excel_md5'] = $md5_unico;
						$excel_form['excel_pedido_id_chip'] = $read_pedido_chip_view['chip_id'];
						if($read_pedido_view['pedido_tipo'] == '0'){
							$read_itens_faturamento_ja_existe = ReadComposta("SELECT excel_pedido_id FROM excel_pedido WHERE excel_md5 = '".$md5_unico."' AND excel_pedido_id_chip = '".$read_pedido_chip_view['chip_id']."'");
							if(NumQuery($read_itens_faturamento_ja_existe) == '0'){
								Create('excel_pedido', $excel_form);
							}
						}elseif($read_pedido_view['pedido_tipo'] == '1'){
							Delete('excel_pedido', "WHERE excel_pedido_id_chip = '".$read_pedido_chip_view['chip_id']."' AND excel_md5 = '".$md5_unico."'");
						}else{
							//Create('excel_pedido', $excel_form);	
						}
					}
				}
			}
		}
		
		$arquivo = 'cargo.xls';
		$tabela = '<table border="1" width="800px">';
			$tabela .= '<tr>';
				$tabela .= '<td colspan="8" align="center">Relação de cargos</tr>';
			$tabela .= '</tr>';
			$tabela .= '<tr>';
				$tabela .= '<td><b>ID PEDIDO</b></td>';
				$tabela .= '<td><b>ID CONTATO</b></td>';
				$tabela .= '<td><b>CONTATO</b></td>';
				$tabela .= '<td><b>DATA ATIVACAO</b></td>';
				$tabela .= '<td><b>STATUS</b></td>';
				$tabela .= '<td><b>TIPO</b></td>';
				$tabela .= '<td><b>ICCID</b></td>';
				$tabela .= '<td><b>NUM LINHA</b></td>';
			$tabela .= '</tr>';
		
		$read_periodo = Read('excel_pedido', "WHERE excel_md5 = '".$md5_unico."'");
		if(NumQuery($read_periodo) > '0'){
			foreach($read_periodo as $read_periodo_view){
				if($read_periodo_view['excel_pedido_status'] == '0'){
					$status_pedido = 'Em andamento';
				}elseif($read_periodo_view['excel_pedido_status'] == '1'){
					$status_pedido = 'Finalizado';
				}elseif($read_periodo_view['excel_pedido_status'] == '2'){
					$status_pedido = 'Cancelado';
				}elseif($read_periodo_view['excel_pedido_status'] == '3'){
					$status_pedido = 'Bloqueado';
				}
				if($read_periodo_view['excel_pedido_tipo'] == '0'){
					$type_pedido = 'Instalação';
				}elseif($read_periodo_view['excel_pedido_tipo'] == '1'){
					$type_pedido = 'Desinstalação';
				}elseif($read_periodo_view['excel_pedido_tipo'] == '2'){
					$type_pedido = 'SMS';
				}
				$tabela .= '<tr>';
					$tabela .= '<td>'.utf8_encode($read_periodo_view['excel_pedido_id_pedido']).'</td>';
					$tabela .= '<td>'.utf8_encode($read_periodo_view['excel_pedido_id_contato']).'</td>';
					$tabela .= '<td>'.utf8_encode($read_periodo_view['excel_pedido_contato']).'</td>';
					$tabela .= '<td>'.utf8_encode($read_periodo_view['excel_pedido_data_ativacao']).'</td>';
					$tabela .= '<td>'.utf8_encode($status_pedido).'</td>';
					$tabela .= '<td>'.utf8_encode($type_pedido).'</td>';
					$tabela .= '<td>'.utf8_encode($read_periodo_view['excel_pedido_iccid']).'-</td>';
					$tabela .= '<td>'.utf8_encode($read_periodo_view['excel_pedido_num']).'-</td>';
				$tabela .= '</tr>';
			}
		}
		
		header("Content-type: application/vnd.ms-excel");  
		header("Content-type: application/force-download"); 
		header("Content-Disposition: attachment; filename=file.xls");
		header("Pragma: no-cache");
		echo $tabela;
	}
?>