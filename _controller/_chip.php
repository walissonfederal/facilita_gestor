<?php
session_start();
ob_start();
require_once '../_class/Ferramenta.php';
require_once '../../mmn_admin/_phpexcel/xlsxwriter.class.php';

if(isset($_POST['acao'])){
    $acao = addslashes($_POST['acao']);
}else{
    $acao = addslashes($_GET['acao']);
}

if($acao == 'gerar_excel'){
    $operacao_excel = addslashes(trim(strip_tags($_GET['OP'])));
    if($operacao_excel == 'CD'){
        $_SESSION['chip_excel_pdf'] = "AND chip_status = '1'";
        $descricao_chip = 'Indisponíveis';
    }elseif($operacao_excel == 'CI'){
        $_SESSION['chip_excel_pdf'] = "AND chip_status = '0'";
        $descricao_chip = 'Disponíveis';
    }else{
        $_SESSION['chip_excel_pdf'] = "";
        $descricao_chip = 'Todos';
    }
    $arquivo = 'chip.xls';
    $tabela = '<table border="1" width="800px">';
        $tabela .= '<tr>';
            $tabela .= '<td colspan="3" align="center">Relação de chips('.$descricao_chip.')</tr>';
        $tabela .= '</tr>';
        $tabela .= '<tr>';
            $tabela .= '<td><b>Código</b></td>';
            $tabela .= '<td><b>Plano</b></td>';
            $tabela .= '<td><b>Status</b></td>';
            $tabela .= '<td><b>Número</b></td>';
            $tabela .= '<td><b>ICCID</b></td>';
            $tabela .= '<td><b>OBS</b></td>';
        $tabela .= '</tr>';
    
    $read_chip = Read('chip', "WHERE chip_id != '' {$_SESSION['chip_excel_pdf']} ORDER BY chip_id ASC");
    if(NumQuery($read_chip) > '0'){
        foreach($read_chip as $read_chip_view){
            if($read_chip_view['chip_status'] == '0'){
                $status_chip = 'DISPONÍVEL';
            }elseif($read_chip_view['chip_status'] == '1'){
                $status_chip = 'EM USO / INDISPONÍVEL';
            }
            $tabela .= '<tr>';
                $tabela .= '<td>'.$read_chip_view['chip_id'].'</td>';
                $tabela .= '<td>'.$read_chip_view['chip_plano'].'</td>';
                $tabela .= '<td>'.$status_chip.'</td>';
                $tabela .= '<td>'.$read_chip_view['chip_num'].'-</td>';
                $tabela .= '<td>'.$read_chip_view['chip_iccid'].'-</td>';
                $tabela .= '<td>'.$read_chip_view['chip_obs'].'</td>';
            $tabela .= '</tr>';
        }
    }
    
    header("Content-type: application/vnd.ms-excel");  
    header("Content-type: application/force-download"); 
    header("Content-Disposition: attachment; filename=".$arquivo."");
    header("Pragma: no-cache");
    echo $tabela;
}elseif($acao == 'gerar_excel_desbloqueio'){
    $data_inicial = addslashes(trim(strip_tags($_GET['data_inicial'])));
    $data_final = addslashes(trim(strip_tags($_GET['data_final'])));
    $arquivo = 'desbloqueios.xls';
    $tabela = '<table border="1" width="800px">';
        $tabela .= '<tr>';
            $tabela .= '<td colspan="5" align="center">Relação de cancelamentos</tr>';
        $tabela .= '</tr>';
        $tabela .= '<tr>';
            $tabela .= '<td><b>ICCID</b></td>';
            $tabela .= '<td><b>Número</b></td>';
            $tabela .= '<td><b>Data</b></td>';
            $tabela .= '<td><b>ID Contato</b></td>';
            $tabela .= '<td><b>Nome Contato</b></td>';
        $tabela .= '</tr>';
    
    //$read_pedido_cancelamento = Read('pedido_cancelamento', "WHERE pedido_cancelamento_id != '' AND pedido_cancelamento_data BETWEEN '".$data_inicial."' AND '".$data_final."' ORDER BY pedido_cancelamento_data ASC");
    $read_pedido_desbloqueio = ReadComposta("SELECT chip.chip_iccid, chip.chip_num, ativacao.ativacao_data, contato.contato_id, contato.contato_nome_fantasia, contato.contato_nome_razao FROM itens_ativacao INNER JOIN ativacao ON ativacao.ativacao_id = itens_ativacao.itens_ativacao_id_ativacao INNER JOIN contato ON contato.contato_id = ativacao.ativacao_id_contato INNER JOIN chip ON chip.chip_id = itens_ativacao.itens_ativacao_id_chip WHERE ativacao.ativacao_data BETWEEN '".$data_inicial."' AND '".$data_final."';");
    if(NumQuery($read_pedido_desbloqueio) > '0'){
        foreach($read_pedido_desbloqueio as $read_pedido_desbloqueio_view){
            $tabela .= '<tr>';
                $tabela .= '<td>'.$read_pedido_desbloqueio_view['chip_iccid'].'-</td>';
                $tabela .= '<td>'.$read_pedido_desbloqueio_view['chip_num'].'-</td>';
                $tabela .= '<td>'.$read_pedido_desbloqueio_view['ativacao_data'].'</td>';
                $tabela .= '<td>'.$read_pedido_desbloqueio_view['contato_id'].'</td>';
                $tabela .= '<td>'.$read_pedido_desbloqueio_view['contato_nome_razao'].' - '.$read_pedido_desbloqueio_view['contato_nome_fantasia'].'</td>';
            $tabela .= '</tr>';
        }
    }
    
    header("Content-type: application/vnd.ms-excel");  
    header("Content-type: application/force-download"); 
    header("Content-Disposition: attachment; filename=".$arquivo."");
    header("Pragma: no-cache");
    echo $tabela;
}elseif($acao == 'gerar_excel_cancelamento'){
    $data_inicial = addslashes(trim(strip_tags($_GET['data_inicial'])));
    $data_final = addslashes(trim(strip_tags($_GET['data_final'])));
    $arquivo = 'cancelamentos.xls';
    $tabela = '<table border="1" width="800px">';
        $tabela .= '<tr>';
            $tabela .= '<td colspan="5" align="center">Relação de cancelamentos</tr>';
        $tabela .= '</tr>';
        $tabela .= '<tr>';
			$tabela .= '<td><b>Plano</b></td>';
            $tabela .= '<td><b>ICCID</b></td>';
            $tabela .= '<td><b>Número</b></td>';
            $tabela .= '<td><b>Data</b></td>';
            $tabela .= '<td><b>ID Contato</b></td>';
            $tabela .= '<td><b>Nome Contato</b></td>';
        $tabela .= '</tr>';
    
    $read_pedido_desbloqueio = Read('pedido_desinstalacao', "WHERE pedido_desinstalacao_id != '' AND pedido_desinstalacao_data BETWEEN '".$data_inicial."' AND '".$data_final."' ORDER BY pedido_desinstalacao_data ASC");
    //$read_pedido_desbloqueio = ReadComposta("SELECT chip.chip_iccid, chip.chip_num, ativacao.ativacao_data, contato.contato_id, contato.contato_nome_fantasia, contato.contato_nome_razao FROM itens_ativacao INNER JOIN ativacao ON ativacao.ativacao_id = itens_ativacao.itens_ativacao_id_ativacao INNER JOIN contato ON contato.contato_id = ativacao.ativacao_id_contato INNER JOIN chip ON chip.chip_id = itens_ativacao.itens_ativacao_id_chip WHERE ativacao.ativacao_data BETWEEN '".$data_inicial."' AND '".$data_final."';");
    if(NumQuery($read_pedido_desbloqueio) > '0'){
        foreach($read_pedido_desbloqueio as $read_pedido_desbloqueio_view){
            $read_chip = Read('chip', "WHERE chip_id = '".$read_pedido_desbloqueio_view['pedido_desinstalacao_id_chip']."'");
            if(NumQuery($read_chip) > '0'){
                foreach($read_chip as $read_chip_view);
            }
            $id_contato = GetDados('pedido', $read_pedido_desbloqueio_view['pedido_desinstalacao_id_pedido'], 'pedido_id', 'pedido_id_cliente');
            $nome_cliente_completo = GetDados('contato', $id_contato, 'contato_id', 'contato_nome_razao').' - '.GetDados('contato', $id_contato, 'contato_id', 'contato_nome_fantasia');
            $tabela .= '<tr>';
				$tabela .= '<td>'.$read_chip_view['chip_plano'].'</td>';
                $tabela .= '<td>'.$read_chip_view['chip_iccid'].'-</td>';
                $tabela .= '<td>'.$read_chip_view['chip_num'].'-</td>';
                $tabela .= '<td>'.$read_pedido_desbloqueio_view['pedido_desinstalacao_data'].'</td>';
                $tabela .= '<td>'.$id_contato.'</td>';
                $tabela .= '<td>'.$nome_cliente_completo.'</td>';
            $tabela .= '</tr>';
        }
    }
    
    header("Content-type: application/vnd.ms-excel");  
    header("Content-type: application/force-download"); 
    header("Content-Disposition: attachment; filename=".$arquivo."");
    header("Pragma: no-cache");
    echo $tabela;
}elseif($acao == 'gerar_excel_bloqueados'){
    $data_inicial = addslashes(trim(strip_tags($_GET['data_inicial'])));
    $data_final = addslashes(trim(strip_tags($_GET['data_final'])));
    $arquivo = 'chips_bloqueados.xls';
    $tabela = '<table border="1" width="800px">';
        $tabela .= '<tr>';
            $tabela .= '<td colspan="6" align="center">Relação de chips bloqueados</tr>';
        $tabela .= '</tr>';
        $tabela .= '<tr>';
			$tabela .= '<td><b>ID PEDIDO</b></td>';
            $tabela .= '<td><b>ICCID</b></td>';
            $tabela .= '<td><b>Número</b></td>';
            $tabela .= '<td><b>Data</b></td>';
            $tabela .= '<td><b>ID Contato</b></td>';
            $tabela .= '<td><b>Nome Contato</b></td>';
        $tabela .= '</tr>';
    
    //$read_pedido_cancelamento = Read('pedido_cancelamento', "WHERE pedido_cancelamento_id != '' AND pedido_cancelamento_data BETWEEN '".$data_inicial."' AND '".$data_final."' ORDER BY pedido_cancelamento_data ASC");
    $read_pedido_desbloqueio = ReadComposta("SELECT * FROM pedido INNER JOIN itens_pedido ON itens_pedido.itens_pedido_id_pedido = pedido.pedido_id INNER JOIN chip ON chip.chip_id = itens_pedido.itens_pedido_id_chip INNER JOIN contato ON contato.contato_id = pedido.pedido_id_cliente WHERE pedido.pedido_status = '3' AND pedido.pedido_data_ativacao BETWEEN '".$data_inicial."' AND '".$data_final."';");
    if(NumQuery($read_pedido_desbloqueio) > '0'){
        foreach($read_pedido_desbloqueio as $read_pedido_desbloqueio_view){
			$tabela .= '<tr>';
				$tabela .= '<td>'.$read_pedido_desbloqueio_view['pedido_id'].'</td>';
				$tabela .= '<td>'.$read_pedido_desbloqueio_view['chip_iccid'].'-</td>';
				$tabela .= '<td>'.$read_pedido_desbloqueio_view['chip_num'].'-</td>';
				$tabela .= '<td>'.$read_pedido_desbloqueio_view['pedido_data_ativacao'].'</td>';
				$tabela .= '<td>'.$read_pedido_desbloqueio_view['contato_id'].'</td>';
				$tabela .= '<td>'.$read_pedido_desbloqueio_view['contato_nome_razao'].' - '.$read_pedido_desbloqueio_view['contato_nome_fantasia'].'</td>';
			$tabela .= '</tr>';
        }
    }
    
    header("Content-type: application/vnd.ms-excel");  
    header("Content-type: application/force-download"); 
    header("Content-Disposition: attachment; filename=".$arquivo."");
    header("Pragma: no-cache");
    echo $tabela;
}elseif($acao == 'gerar_excel_qtd_financeiro'){
    $data_inicial = addslashes(trim(strip_tags($_GET['data_inicial'])));
    $data_final = addslashes(trim(strip_tags($_GET['data_final'])));
    $arquivo = 'faturas_aberto.xls';
    $tabela = '<table border="1" width="800px">';
        $tabela .= '<tr>';
            $tabela .= '<td colspan="6" align="center">Relação de chips bloqueados</tr>';
        $tabela .= '</tr>';
        $tabela .= '<tr>';
			$tabela .= '<td><b>ID Contato</b></td>';
            $tabela .= '<td><b>Nome Razao</b></td>';
            $tabela .= '<td><b>Nome Fantasia</b></td>';
            $tabela .= '<td><b>Telefone</b></td>';
            $tabela .= '<td><b>Email</b></td>';
			$tabela .= '<td><b>Faturas em aberto</b></td>';
        $tabela .= '</tr>';
    
    $read_pedido_desbloqueio = ReadComposta("SELECT *, COUNT(*) duplicados FROM financeiro INNER JOIN contato ON contato_id = financeiro_id_contato WHERE financeiro_status = '0' AND financeiro_id_tipo_documento = '5' AND financeiro_data_vencimento BETWEEN '".$data_inicial."' AND '".$data_final."' GROUP BY financeiro_id_contato HAVING duplicados > 1;");
    if(NumQuery($read_pedido_desbloqueio) > '0'){
        foreach($read_pedido_desbloqueio as $read_pedido_desbloqueio_view){
            $tabela .= '<tr>';
                $tabela .= '<td>'.$read_pedido_desbloqueio_view['contato_id'].'-</td>';
                $tabela .= '<td>'.$read_pedido_desbloqueio_view['contato_nome_razao'].'-</td>';
                $tabela .= '<td>'.$read_pedido_desbloqueio_view['contato_nome_fantasia'].'</td>';
                $tabela .= '<td>'.$read_pedido_desbloqueio_view['contato_telefone'].'</td>';
                $tabela .= '<td>'.$read_pedido_desbloqueio_view['contato_email'].'</td>';
				$tabela .= '<td>'.$read_pedido_desbloqueio_view['duplicados'].'</td>';
            $tabela .= '</tr>';
        }
    }
    
    header("Content-type: application/vnd.ms-excel");  
    header("Content-type: application/force-download"); 
    header("Content-Disposition: attachment; filename=".$arquivo."");
    header("Pragma: no-cache");
    echo $tabela;
}elseif($acao == 'gerar_excel_vendedores'){
	
	$data_inicial = addslashes(trim(strip_tags($_GET['data_inicial'])));
    $data_final = addslashes(trim(strip_tags($_GET['data_final'])));
	
	$header = array(
		'ID CLIENTE'=>'string',
		'NOME FANTASIA'=>'string',
		'NOME RAZÃO SOCIAL'=>'string',
		'DATA PEDIDO'=>'string',
		'VENDEDOR'=>'string',
		'SITUAÇÃO'=>'string',
		'QUANTIDADE'=>'string'
	);
	
	$read_chip_app = ReadComposta("SELECT id_contato, contato_nome_fantasia, contato_nome_razao, data_pedido, vendedor, situacao, quantidade FROM pedidonovo INNER JOIN contato ON contato_id = id_contato WHERE data_pedido BETWEEN '".$data_inicial."' AND '".$data_final."' ORDER BY vendedor ASC, id_contato ASC");
	if(NumQuery($read_chip_app) > '0'):
		foreach($read_chip_app as $read_chip_app_view):
			$rows[] = $read_chip_app_view	;
		endforeach;
		
		$writer = new XLSXWriter();

		$writer->writeSheetHeader('Sheet1', $header);
		foreach($rows as $row)
		$writer->writeSheetRow('Sheet1', $row);
		$writer->writeToFile('relatorio_pedido.xlsx');
		header("Location: relatorio_pedido.xlsx");
	endif;
}elseif($acao == 'gerar_excel_vendedores_resumido'){
	
	$data_inicial = addslashes(trim(strip_tags($_GET['data_inicial'])));
    $data_final = addslashes(trim(strip_tags($_GET['data_final'])));
	
	$header = array(
		'VENDEDOR'=>'string',
		'QUANTIDADE'=>'string'
	);
	
	$read_chip_app = ReadComposta("SELECT id_contato, contato_nome_fantasia, contato_nome_razao, data_pedido, vendedor, situacao, quantidade FROM pedidonovo INNER JOIN contato ON contato_id = id_contato WHERE data_pedido BETWEEN '".$data_inicial."' AND '".$data_final."' AND rastreio != ''");
	if(NumQuery($read_chip_app) > '0'):
		foreach($read_chip_app as $read_chip_app_view):
			$array_vendedores[$read_chip_app_view['vendedor']] += $read_chip_app_view['quantidade'];
		endforeach;
		if(count($array_vendedores) > '0'):
			foreach($array_vendedores as $vendedor => $quantidade):
				if($vendedor == ''):
					$vendedor = 'Sem vendedor';
				endif;
				$array_completo['vendedor'] = $vendedor;
				$array_completo['quantidade'] = $quantidade;
				$rows[] = $array_completo;
			endforeach;
		endif;
		
		$writer = new XLSXWriter();

		$writer->writeSheetHeader('Sheet1', $header);
		foreach($rows as $row)
		$writer->writeSheetRow('Sheet1', $row);
		$writer->writeToFile('relatorio_pedido_resumido.xlsx');
		header("Location: relatorio_pedido_resumido.xlsx");
	endif;
}
?>