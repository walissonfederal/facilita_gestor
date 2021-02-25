<?php
ob_start();
session_start();
require_once '../../_class/Ferramenta.php';
require_once '../../_boleto_pdf/_mj_boleto_geracao/_fpdf/fpdf.php';

$id_faturamento = addslashes($_GET['id_faturamento']);
//$read_faturamento = Read('faturamento', "WHERE faturamento_id = '".$id_faturamento."'");
$read_faturamento = ReadComposta("SELECT faturamento.faturamento_referencia, contato.contato_id, contato.contato_nome_razao, contato.contato_nome_fantasia, contato.contato_telefone, contato.contato_cpf_cnpj, contato.contato_cidade, contato.contato_estado, contato.contato_email FROM faturamento INNER JOIN contato ON contato.contato_id = faturamento.faturamento_id_contato WHERE faturamento.faturamento_id = '".$id_faturamento."'");
if(NumQuery($read_faturamento) > '0'){
    foreach($read_faturamento as $read_faturamento_view);
}else{
    echo "<script>window.close();</script>";
}

$pdf = new FPDF();
$pdf->AddPage();

//Select Arial bold 8
$pdf->SetFont('Arial', 'B', 6);
//titulo da impressão
//$pdf->Image(GetEmpresa('empresa_logo'), 10, 5, 60, 20);
$pdf->Ln('15');
$pdf->SetFont('Arial', '', 8);
$pdf->Ln();
//titulo da impressão
$pdf->Cell(190, 5, utf8_decode("DEMONSTRATIVO DO FATURAMENTO #".$read_faturamento_view['faturamento_referencia']), '', 1, 'C');
//DADOS DO CLIENTE
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(20, 4, utf8_decode('Código'), 1, 0, 'L');
$pdf->Cell(85, 4, utf8_decode('Nome Fantasia'), 1, 0, 'L');
$pdf->Cell(85, 4, utf8_decode('Nome Razão'), 1, 0, 'L');
$pdf->Ln();
$pdf->SetFont('Arial','B',5);
$pdf->Cell(20, 4, utf8_decode($read_faturamento_view['contato_id']), 1, 0, 'L');
$pdf->Cell(85, 4, utf8_decode($read_faturamento_view['contato_nome_fantasia']), 1, 0, 'L');
$pdf->Cell(85, 4, utf8_decode($read_faturamento_view['contato_nome_razao']), 1, 0, 'L');
$pdf->Ln();
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(20, 4, utf8_decode('Telefone'), 1, 0, 'L');
$pdf->Cell(85, 4, utf8_decode('Email'), 1, 0, 'L');
$pdf->Cell(40, 4, utf8_decode('CPF / CNPJ'), 1, 0, 'L');
$pdf->Cell(40, 4, utf8_decode('Cidade'), 1, 0, 'L');
$pdf->Cell(5, 4, utf8_decode('UF'), 1, 0, 'L');
$pdf->Ln();
$pdf->SetFont('Arial','B',5);
$pdf->Cell(20, 4, utf8_decode($read_faturamento_view['contato_telefone']), 1, 0, 'L');
$pdf->Cell(85, 4, utf8_decode($read_faturamento_view['contato_email']), 1, 0, 'L');
$pdf->Cell(40, 4, utf8_decode($read_faturamento_view['contato_cpf_cnpj']), 1, 0, 'L');
$pdf->Cell(40, 4, utf8_decode($read_faturamento_view['contato_cidade']), 1, 0, 'L');
$pdf->Cell(5, 4, utf8_decode($read_faturamento_view['contato_estado']), 1, 0, 'L');
$pdf->Ln();
$pdf->Ln();
//FIM DADOS CLIENTE
//INICIO CHIPS DE INSTALAÇÃO
$COUNT_LINHAS = '0';
if($id_faturamento < '15685'):
	$read_itens_instalacao = ReadComposta("SELECT chip.chip_plano, chip.chip_num, chip.chip_iccid, itens_faturamento.itens_faturamento_plano, itens_faturamento.itens_faturamento_valor_cobrado, itens_faturamento.itens_faturamento_descricao, pedido.pedido_data_ativacao, pedido.pedido_valor_ativacao, itens_faturamento.itens_faturamento_valor_ativacao FROM itens_faturamento INNER JOIN chip ON chip.chip_id = itens_faturamento.itens_faturamento_id_chip INNER JOIN pedido ON pedido.pedido_id = itens_faturamento.itens_faturamento_id_pedido WHERE itens_faturamento.itens_faturamento_tipo = '0' AND itens_faturamento.itens_faturamento_id_faturamento = '".$id_faturamento."'");
else:
	$read_itens_instalacao = ReadComposta("SELECT chip_app.chip_plano, chip_app.chip_num, chip_app.chip_iccid, itens_faturamento.itens_faturamento_plano, itens_faturamento.itens_faturamento_valor_cobrado, itens_faturamento.itens_faturamento_descricao, itens_faturamento.itens_faturamento_valor_ativacao FROM itens_faturamento LEFT JOIN chip_app ON chip_app.chip_id = itens_faturamento.itens_faturamento_id_chip WHERE itens_faturamento.itens_faturamento_tipo = '0' AND itens_faturamento.itens_faturamento_id_faturamento = '".$id_faturamento."'");
endif;
if(NumQuery($read_itens_instalacao) > '0'){
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(190, 5, utf8_decode("CHIPS / FATURAS"), '', 1, 'C');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(10, 4, utf8_decode('#'), 1, 0, 'L');
    $pdf->Cell(20, 4, utf8_decode('Número'), 1, 0, 'L');
    $pdf->Cell(30, 4, utf8_decode('ICCID'), 1, 0, 'L');
    $pdf->Cell(15, 4, utf8_decode('Tipo'), 1, 0, 'L');
    $pdf->Cell(30, 4, utf8_decode('Plano'), 1, 0, 'L');
    $pdf->Cell(10, 4, utf8_decode('Valor'), 1, 0, 'L');
    $pdf->Cell(15, 4, utf8_decode('Ativação'), 1, 0, 'L');
    $pdf->Cell(20, 4, utf8_decode('Vlr Ativação'), 1, 0, 'L');
    $pdf->Cell(20, 4, utf8_decode('Total a Pagar'), 1, 0, 'L');
    $pdf->Cell(20, 4, utf8_decode('Descrição'), 1, 0, 'L');
    $pdf->Ln();
    $pdf->SetFont('Arial','B',5);
    foreach($read_itens_instalacao as $read_itens_instalacao_view){
		if($read_itens_instalacao_view['itens_faturamento_descricao'] == ' CICLO CANCELAMENTO'):
			$valor_ciclo_cancelamento += $read_itens_instalacao_view['itens_faturamento_valor_cobrado'];
		endif;
        $valor_total_faturamento_faturas += $read_itens_instalacao_view['itens_faturamento_valor_cobrado'];
        $valor_total_faturamento_ativacao += $read_itens_instalacao_view['itens_faturamento_valor_ativacao'];
        $COUNT_LINHAS++;
        $pdf->Cell(10, 4, utf8_decode($COUNT_LINHAS), 1, 0, 'L');
        $pdf->Cell(20, 4, utf8_decode($read_itens_instalacao_view['chip_num']), 1, 0, 'L');
        $pdf->Cell(30, 4, utf8_decode($read_itens_instalacao_view['chip_iccid']), 1, 0, 'L');
        $pdf->Cell(15, 4, utf8_decode($read_itens_instalacao_view['chip_plano']), 1, 0, 'L');
        $pdf->Cell(30, 4, utf8_decode($read_itens_instalacao_view['itens_faturamento_plano']), 1, 0, 'L');
        $pdf->Cell(10, 4, utf8_decode(FormatMoney($read_itens_instalacao_view['itens_faturamento_valor_cobrado'])), 1, 0, 'L');
        if($id_faturamento < '15685'):
			$pdf->Cell(15, 4, utf8_decode(FormDataBr($read_itens_instalacao_view['pedido_data_ativacao'])), 1, 0, 'L');
		else:
			$pdf->Cell(15, 4, utf8_decode(''), 1, 0, 'L');
		endif;
        $pdf->Cell(20, 4, utf8_decode(FormatMoney($read_itens_instalacao_view['itens_faturamento_valor_ativacao'])), 1, 0, 'L');
        $pdf->Cell(20, 4, utf8_decode(FormatMoney($read_itens_instalacao_view['itens_faturamento_valor_ativacao'] + $read_itens_instalacao_view['itens_faturamento_valor_cobrado'])), 1, 0, 'L');
        $pdf->Cell(20, 4, utf8_decode($read_itens_instalacao_view['itens_faturamento_descricao']), 1, 0, 'L');
        $pdf->Ln();
    }
}
//FIM CHIPS INSTALAÇÃO
//INICIO COBRANÇA DE CORREIOS
if($id_faturamento < '15685'):
	$read_itens_correios = ReadComposta("SELECT itens_faturamento.itens_faturamento_plano, itens_faturamento.itens_faturamento_valor_cobrado, itens_faturamento.itens_faturamento_descricao, pedido.pedido_data_ativacao, pedido.pedido_valor_ativacao, pedido.pedido_id, pedido.pedido_data, pedido.pedido_tipo_frete FROM itens_faturamento INNER JOIN pedido ON pedido.pedido_id = itens_faturamento.itens_faturamento_id_pedido WHERE itens_faturamento.itens_faturamento_tipo = '3' AND itens_faturamento.itens_faturamento_id_faturamento = '".$id_faturamento."'");
else:
	$read_itens_correios = ReadComposta("SELECT itens_faturamento.itens_faturamento_plano, itens_faturamento.itens_faturamento_valor_cobrado, itens_faturamento.itens_faturamento_descricao FROM itens_faturamento WHERE itens_faturamento.itens_faturamento_tipo = '3' AND itens_faturamento.itens_faturamento_id_faturamento = '".$id_faturamento."'");
endif;
if(NumQuery($read_itens_correios) > '0'){
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(190, 5, utf8_decode("CORREIOS"), '', 1, 'C');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(65, 4, utf8_decode('Descrição'), 1, 0, 'L');
    $pdf->Cell(30, 4, utf8_decode('Nº Pedido'), 1, 0, 'L');
    $pdf->Cell(25, 4, utf8_decode('Valor'), 1, 0, 'L');
    $pdf->Cell(40, 4, utf8_decode('Data Pedido'), 1, 0, 'L');
    $pdf->Cell(30, 4, utf8_decode('Tipo Frete'), 1, 0, 'L');
    $pdf->Ln();
    $pdf->SetFont('Arial','B',5);
    foreach($read_itens_correios as $read_itens_correios_view){
        $valor_total_faturamento_correios += $read_itens_correios_view['itens_faturamento_valor_cobrado'];
		if($id_faturamento < '15685'):
			if($read_itens_correios_view['pedido_tipo_frete'] == '0'){
				$tipo_frete = 'Carta Registrada';
			}elseif($read_itens_correios_view['pedido_tipo_frete'] == '1'){
				$tipo_frete = 'PAC';
			}elseif($read_itens_correios_view['pedido_tipo_frete'] == '2'){
				$tipo_frete = 'Sedex';
			}
		endif;
        $pdf->Cell(65, 4, utf8_decode($read_itens_correios_view['itens_faturamento_descricao']), 1, 0, 'L');
		if($id_faturamento < '15685'):
			$pdf->Cell(30, 4, utf8_decode($read_itens_correios_view['pedido_id']), 1, 0, 'L');
		else:
			$pdf->Cell(30, 4, utf8_decode(''), 1, 0, 'L');
		endif;
        $pdf->Cell(25, 4, utf8_decode(FormatMoney($read_itens_correios_view['itens_faturamento_valor_cobrado'])), 1, 0, 'L');
		if($id_faturamento < '15685'):
			$pdf->Cell(40, 4, utf8_decode(FormDataBr($read_itens_correios_view['pedido_data'])), 1, 0, 'L');
			$pdf->Cell(30, 4, utf8_decode($tipo_frete), 1, 0, 'L');
		else:
			$pdf->Cell(40, 4, utf8_decode(''), 1, 0, 'L');
			$pdf->Cell(30, 4, utf8_decode(''), 1, 0, 'L');
		endif;
        $pdf->Ln();
    }
}
//FIM COBRANÇA SMS
//INICIO COBRANÇA DO SMS
if($id_faturamento < '15685'):
	$read_itens_sms = ReadComposta("SELECT itens_faturamento.itens_faturamento_plano, itens_faturamento.itens_faturamento_valor_cobrado, itens_faturamento.itens_faturamento_descricao, pedido.pedido_data_ativacao, pedido.pedido_valor_ativacao, pedido.pedido_id, pedido.pedido_data, pedido.pedido_tipo_frete FROM itens_faturamento INNER JOIN pedido ON pedido.pedido_id = itens_faturamento.itens_faturamento_id_pedido WHERE itens_faturamento.itens_faturamento_tipo = '2' AND itens_faturamento.itens_faturamento_id_faturamento = '".$id_faturamento."'");
else:
	$read_itens_sms = ReadComposta("SELECT itens_faturamento.itens_faturamento_plano, itens_faturamento.itens_faturamento_valor_cobrado, itens_faturamento.itens_faturamento_descricao FROM itens_faturamento WHERE itens_faturamento.itens_faturamento_tipo = '2' AND itens_faturamento.itens_faturamento_id_faturamento = '".$id_faturamento."'");
endif;
if(NumQuery($read_itens_sms) > '0'){
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(190, 5, utf8_decode("SMS / APLICAÇÃO / RASTREADOR"), '', 1, 'C');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(95, 4, utf8_decode('Descrição'), 1, 0, 'L');
    $pdf->Cell(25, 4, utf8_decode('Plano'), 1, 0, 'L');
    $pdf->Cell(40, 4, utf8_decode('Valor'), 1, 0, 'L');
    $pdf->Cell(30, 4, utf8_decode('Data Pedido'), 1, 0, 'L');
    $pdf->Ln();
    $pdf->SetFont('Arial','B',5);
    foreach($read_itens_sms as $read_itens_sms_view){
        $valor_total_faturamento_sms += $read_itens_sms_view['itens_faturamento_valor_cobrado'];
        $pdf->Cell(95, 4, utf8_decode($read_itens_sms_view['itens_faturamento_descricao']), 1, 0, 'L');
		if($id_faturamento < '15685'):
			$pdf->Cell(25, 4, utf8_decode($read_itens_sms_view['pedido_id']), 1, 0, 'L');
		else:
			$pdf->Cell(25, 4, utf8_decode($read_itens_sms_view['itens_faturamento_plano']), 1, 0, 'L');
		endif;
        $pdf->Cell(40, 4, utf8_decode(FormatMoney($read_itens_sms_view['itens_faturamento_valor_cobrado'])), 1, 0, 'L');
		if($id_faturamento < '15685'):
			$pdf->Cell(30, 4, utf8_decode(FormDataBr($read_itens_sms_view['pedido_data'])), 1, 0, 'L');
		else:
			$pdf->Cell(30, 4, utf8_decode(''), 1, 0, 'L');
		endif;
        $pdf->Ln();
    }
}
if($id_faturamento < '15685'):
	$quantidade_excedente_sms = '0';
	$valor_total_sms_excedente = '0';
	$read_itens_sms_excedente = ReadComposta("SELECT itens_faturamento.itens_faturamento_id_pedido, itens_faturamento.itens_faturamento_plano, itens_faturamento.itens_faturamento_valor_cobrado, itens_faturamento.itens_faturamento_descricao FROM itens_faturamento WHERE itens_faturamento.itens_faturamento_tipo = '5' AND itens_faturamento.itens_faturamento_id_faturamento = '".$id_faturamento."'");
	if(NumQuery($read_itens_sms_excedente) > '0'){
		foreach($read_itens_sms_excedente as $read_itens_sms_excedente_view);
		$quantidade_excedente_sms = $read_itens_sms_excedente_view['itens_faturamento_id_pedido'];
		$valor_total_sms_excedente = $read_itens_sms_excedente_view['itens_faturamento_valor_cobrado'];
	}
	if($quantidade_excedente_sms == ''){
		$quantidade_excedente_sms = '0';
	}
endif;
//FIM COBRANÇA DO SMS
//INICIO COBRANÇA DO CICLO
if($id_faturamento < '15685'):
	$read_itens_ciclo = ReadComposta("SELECT chip.chip_plano, chip.chip_num, chip.chip_iccid, itens_faturamento.itens_faturamento_plano, itens_faturamento.itens_faturamento_valor_cobrado, itens_faturamento.itens_faturamento_descricao, pedido.pedido_data_ativacao, pedido.pedido_valor_ativacao, itens_faturamento.itens_faturamento_valor_ativacao, itens_faturamento.itens_faturamento_id_pedido FROM itens_faturamento INNER JOIN chip ON chip.chip_id = itens_faturamento.itens_faturamento_id_chip INNER JOIN pedido ON pedido.pedido_id = itens_faturamento.itens_faturamento_id_pedido WHERE itens_faturamento.itens_faturamento_tipo = '1' AND itens_faturamento.itens_faturamento_id_faturamento = '".$id_faturamento."'");
	if(NumQuery($read_itens_ciclo) > '0'){
		foreach($read_itens_ciclo as $read_itens_ciclo_view){
			if($id_faturamento == '10549'){
				$valor_total_faturamento_ciclos = '0';
			}else{
				$valor_total_faturamento_ciclos += $read_itens_ciclo_view['itens_faturamento_valor_cobrado'];
			}
		}
	}
endif;
//FIM COBRANÇA DO CICLO
//INICIO COBRANÇA DOS CANCELAMENTOS OU MULTAS


//FIM COBRANÇA DOS CANCELAMENTOS OU MULTAS
if($id_faturamento < '15685'):
$read_faturamento_cancelamento = ReadComposta("SELECT faturamento_referencia FROM faturamento WHERE faturamento_id = '".$id_faturamento."'");
if(NumQuery($read_faturamento_cancelamento) > '0'){
    foreach ($read_faturamento_cancelamento as $read_faturamento_cancelamento_view);
}
$read_itens_cancelamentos = ReadComposta("SELECT chip.chip_plano, chip.chip_num, chip.chip_iccid, itens_faturamento.itens_faturamento_plano, itens_faturamento.itens_faturamento_valor_cobrado, itens_faturamento.itens_faturamento_descricao, pedido.pedido_data_ativacao, pedido.pedido_valor_ativacao, itens_faturamento.itens_faturamento_valor_ativacao FROM itens_faturamento INNER JOIN chip ON chip.chip_id = itens_faturamento.itens_faturamento_id_chip INNER JOIN pedido ON pedido.pedido_id = itens_faturamento.itens_faturamento_id_pedido WHERE itens_faturamento.itens_faturamento_tipo = '1' AND itens_faturamento.itens_faturamento_id_faturamento = '".$id_faturamento."'");
if(NumQuery($read_itens_cancelamentos) > '0'){
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(190, 5, utf8_decode("CANCELAMENTOS"), '', 1, 'C');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(10, 4, utf8_decode('#'), 1, 0, 'L');
    $pdf->Cell(40, 4, utf8_decode('Número'), 1, 0, 'L');
    $pdf->Cell(50, 4, utf8_decode('ICCID'), 1, 0, 'L');
    $pdf->Cell(50, 4, utf8_decode('ID Pedido'), 1, 0, 'L');
    $pdf->Cell(20, 4, utf8_decode('Valor Ciclo'), 1, 0, 'L');
    $pdf->Cell(20, 4, utf8_decode('Valor Chip'), 1, 0, 'L');
    $pdf->Ln();
    $pdf->SetFont('Arial','B',5);
    $CountDadosCancelamento = '0';
    //$read_itens_faturamento_cancelamento = Read('itens_faturamento', "WHERE itens_faturamento_id_faturamento = '".$id_faturamento."' AND itens_faturamento_tipo = '1'");
    $read_itens_faturamento_cancelamento = ReadComposta("SELECT * FROM itens_faturamento INNER JOIN chip ON chip.chip_id = itens_faturamento.itens_faturamento_id_chip WHERE itens_faturamento.itens_faturamento_id_faturamento = '".$id_faturamento."' AND itens_faturamento.itens_faturamento_tipo = '1' GROUP BY itens_faturamento.itens_faturamento_id_chip");
    if(NumQuery($read_itens_faturamento_cancelamento) > '0'){
        foreach($read_itens_faturamento_cancelamento as $read_itens_faturamento_cancelamento_view){
            $read_pedido_desinstalacao = Read('pedido_desinstalacao', "WHERE pedido_desinstalacao_id_chip = '".$read_itens_faturamento_cancelamento_view['itens_faturamento_id_chip']."'");
            if(NumQuery($read_pedido_desinstalacao) > '0'){
                foreach($read_pedido_desinstalacao as $read_pedido_desinstalacao_view){
                    if($read_pedido_desinstalacao_view['pedido_desinstalacao_cobrar'] == $read_faturamento_view['faturamento_referencia']){
						if($id_faturamento == '10549'){
							$valor_total_faturamento_multa += '0';
						}else{
							$valor_total_faturamento_multa += $read_pedido_desinstalacao_view['pedido_desinstalacao_valor_total'];
						}
                    }else{
                        $valor_total_faturamento_multa += '0';
                    }
					if($id_faturamento == '10549'){
						$valor_cobrado_chip = '0';
					}else{
						$valor_cobrado_chip = $read_pedido_desinstalacao_view['pedido_desinstalacao_valor_total'];
					}
                }
            }
            $CountDadosCancelamento++;
            $pdf->Cell(10, 4, utf8_decode($CountDadosCancelamento), 1, 0, 'L');
            $pdf->Cell(40, 4, utf8_decode($read_itens_faturamento_cancelamento_view['chip_num']), 1, 0, 'L');
            $pdf->Cell(50, 4, utf8_decode($read_itens_faturamento_cancelamento_view['chip_iccid']), 1, 0, 'L');
            $pdf->Cell(50, 4, utf8_decode($read_itens_faturamento_cancelamento_view['itens_faturamento_id_pedido']), 1, 0, 'L');
			if($id_faturamento == '10549'){
				$pdf->Cell(20, 4, utf8_decode(FormatMoney('0')), 1, 0, 'L');
			}else{
				$pdf->Cell(20, 4, utf8_decode(FormatMoney($read_itens_faturamento_cancelamento_view['itens_faturamento_valor_cobrado'])), 1, 0, 'L');
			}
            $pdf->Cell(20, 4, utf8_decode(FormatMoney($valor_cobrado_chip)), 1, 0, 'L');
            $pdf->Ln();
        }
    }
}
endif;

//TOTAIS DO FATURAMENTO
$pdf->SetFont('Arial', '', 8);
$pdf->Ln();
$pdf->Cell(190, 5, utf8_decode("TOTAIS DO FATURAMENTO"), '', 1, 'C');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(95, 4, utf8_decode('SMS Excedentes / Avulso'), 1, 0, 'L');
$pdf->Cell(95, 4, utf8_decode('Valor Pagar SMS Excedentes / Avulso'), 1, 0, 'L');
$pdf->Ln();
$pdf->SetFont('Arial','B',5);
$pdf->Cell(95, 4, utf8_decode($quantidade_excedente_sms), 1, 0, 'L');
$pdf->Cell(95, 4, utf8_decode('R$ '.FormatMoney($valor_total_sms_excedente)), 1, 0, 'L');
$pdf->Ln();
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(31, 4, utf8_decode('Valor Faturas'), 1, 0, 'L');
$pdf->Cell(31, 4, utf8_decode('Valor Ativações'), 1, 0, 'L');
$pdf->Cell(31, 4, utf8_decode('Valor SMS'), 1, 0, 'L');
$pdf->Cell(31, 4, utf8_decode('Valor Correios'), 1, 0, 'L');
$pdf->Cell(35, 4, utf8_decode('Valor Cancelamentos'), 1, 0, 'L');
$pdf->Cell(31, 4, utf8_decode('Total'), 1, 0, 'L');
$pdf->Ln();
$pdf->SetFont('Arial','B',5);
$pdf->Cell(31, 4, utf8_decode('R$ '.FormatMoney($valor_total_faturamento_faturas)), 1, 0, 'L');
$pdf->Cell(31, 4, utf8_decode('R$ '.FormatMoney($valor_total_faturamento_ativacao)), 1, 0, 'L');
$pdf->Cell(31, 4, utf8_decode('R$ '.FormatMoney($valor_total_faturamento_sms)), 1, 0, 'L');
$pdf->Cell(31, 4, utf8_decode('R$ '.FormatMoney($valor_total_faturamento_correios)), 1, 0, 'L');
$pdf->Cell(35, 4, utf8_decode('R$ '.FormatMoney($valor_total_faturamento_multa + $valor_total_faturamento_ciclos)), 1, 0, 'L');
$pdf->Cell(31, 4, utf8_decode('R$ '.FormatMoney($valor_total_faturamento_faturas + $valor_total_faturamento_ativacao + $valor_total_faturamento_ciclos + $valor_total_faturamento_sms + $valor_total_faturamento_correios + $valor_total_faturamento_multa + $valor_total_sms_excedente)), 1, 0, 'L');
//FOOTER
$pdf->Ln();
$pdf->Output();
