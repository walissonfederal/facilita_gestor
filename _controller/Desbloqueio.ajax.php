<?php

session_start();
ob_start();
session_start();
ob_start();
require_once '../_class/Ferramenta.php';
require_once '../../mmn_admin/_phpexcel/xlsxwriter.class.php';

$pasta_documentos = 'mmn';

$jSON = array();
$getPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
if (empty($getPost['action'])):
	$getPost['action'] = 'select';
endif;
if (empty($getPost['action'])):
    $jSON['msg'] = 'Uma ação não foi selecionada no formulário!';
    $jSON['type'] = 'error';
    $jSON['title'] = 'Erro';
else:
    $Post1 = array_map("strip_tags", $getPost);
    $Post = array_map("trim", $Post1);
    $Action = $Post['action'];
    unset($Post['action']);

    switch ($Action):
        case 'select':
            //PAGINATION
            $pag = (empty($_GET['pageNo']) ? '1' : $_GET['pageNo']);
            if (empty($_GET['size'])):
                $maximo = '5000';
            else:
                $maximo = $_GET['size'];
            endif;
            $inicio = ($pag * $maximo) - $maximo;
            //ORDENATION
            if (empty($_GET['sort']) && empty($_GET['sort_dir'])):
                $order_by = "ORDER BY contato_id DESC";
            else:
                $sort = addslashes($_GET['sort']);
                $sort_dir = addslashes($_GET['sort_dir']);
                $order_by = "ORDER BY " . $sort . " " . $sort_dir . "";
            endif;
            //PESQUISAR
            if (isset($_POST['search']) && $_POST['search'] == 'true'):
                if ($Post['id_contato'] != ''):
                    $sql_id_user = "AND contato_id = '" . $Post['id_contato'] . "'";
                else:
                    $sql_id_user = "";
                endif;
                $jSONsEARCH['type'] = 'ok';
                $jSONsEARCH['info'] = $Post;
                $_SESSION['search_desbloqueio'] = $jSONsEARCH;
                $_SESSION['sql_desbloqueio'] = " " . $sql_id_user . " ";
            endif;
            //QUERY
            $count_pedido = '0';
            $read_pedido_paginator = ReadComposta("SELECT contato_id FROM contato WHERE contato_bloqueio_desbloqueio = '1' {$_SESSION['sql_desbloqueio']} GROUP BY contato_id");
            if (NumQuery($read_pedido_paginator) > '0'):
                foreach ($read_pedido_paginator as $read_pedido_paginator_view):
                    $user_id .= $read_pedido_paginator_view['contato_id'] . ',';
                endforeach;
				$user_correto = substr($user_id, 0, -1);
                $data_corte = date('Y-m-d', strtotime('-1 days'));
                $read_pedido_pago = ReadComposta("SELECT financeiro_id, financeiro_id_contato FROM financeiro WHERE financeiro_tipo = 'CR' AND financeiro_status = '0' AND financeiro_data_vencimento < '" . $data_corte . "' AND financeiro_id_contato IN($user_correto)");
                if (NumQuery($read_pedido_pago) > '0'):
                    foreach ($read_pedido_pago as $read_pedido_pago_view):
                        $user_id_pedido .= $read_pedido_pago_view['financeiro_id_contato'] . ',';
                    endforeach;
                    $user_id_pedido_correto = substr($user_id_pedido, 0, -1);
                endif;
            endif;
            $read_pedido = ReadComposta("SELECT contato_id, contato_nome_razao, contato_bloqueio_desbloqueio FROM contato WHERE contato_bloqueio_desbloqueio = '1' AND contato_id NOT IN($user_id_pedido_correto) {$_SESSION['sql_desbloqueio']} GROUP BY contato_id " . $order_by . " LIMIT $inicio,$maximo");
            if (NumQuery($read_pedido) > '0'):
                foreach ($read_pedido as $read_pedido_view):
                    if ($read_pedido_view['contato_bloqueio_desbloqueio'] == '0'):
                        $read_pedido_view['contato_bloqueio_desbloqueio'] = 'DESBLOQUEADO';
                    elseif ($read_pedido_view['contato_bloqueio_desbloqueio'] == '1'):
                        $read_pedido_view['contato_bloqueio_desbloqueio'] = 'BLOQUEADO';
                    endif;

                    $read_pedido_view['oque_fazer'] = 'DESBLOQUEAR';

                    $count_pedido++;
                    $user_id_dados .= $read_pedido_view['contato_id'] . ',';
                    $jSON['data'][] = $read_pedido_view;
                endforeach;
                $paginas = ceil($count_pedido / $maximo);
                $jSON["last_page"] = $paginas;
                $jSON["pedido_quantidade"] = $count_pedido;
                $_SESSION['excel_desbloqueio'] = substr($user_id_dados, 0, -1);
            endif;
            break;
        case 'search_load':
            unset($_SESSION['search_desbloqueio']['info']['search']);
            $jSON = $_SESSION['search_desbloqueio'];
            break;
        case 'finalizar_desbloqueio':
            $bloqueio_form['contato_bloqueio_desbloqueio'] = '0';
            if ($Post['id'] != ''):
                if (Update('contato', $bloqueio_form, "WHERE contato_id IN(" . $Post['id'] . ")")):
                    $jSON['msg'] = "Operação realizada com sucesso!";
                    $jSON['type'] = 'ok';
                    $jSON['title'] = 'Parabéns';
                else:
                    $jSON['msg'] = 'Houve um erro, tente novamente mais tarde!';
                    $jSON['type'] = 'error';
                    $jSON['title'] = 'Erro';
                endif;
            else:
                if (Update('contato', $bloqueio_form, "WHERE contato_id IN(" . $_SESSION['excel_desbloqueio'] . ")")):
                    $jSON['msg'] = "Operação realizada com sucesso!";
                    $jSON['type'] = 'ok';
                    $jSON['title'] = 'Parabéns';
                else:
                    $jSON['msg'] = 'Houve um erro, tente novamente mais tarde!';
                    $jSON['type'] = 'error';
                    $jSON['title'] = 'Erro';
                endif;
            endif;
            break;
        case 'gerar_excel':
            $header = array(
                'ID' => 'string',
                'USUÁRIO' => 'string',
                'TELEFONE' => 'string',
                'CELULAR' => 'string',
                'EMAIL' => 'string',
                'NÚMERO LINHA' => 'string',
                'ICCID' => 'string',
                'TIPO' => 'string'
            );

            $read_excel_report = ReadComposta("SELECT contato_id, contato_nome_razao, contato_telefone, contato_celular, contato_email, chip_num, chip_iccid, chip_plano, chip_status FROM contato INNER JOIN chip_app ON id_contato = contato_id WHERE contato_id IN(" . $_SESSION['excel_desbloqueio'] . ") ORDER BY contato_id ASC");
            if (NumQuery($read_excel_report) > '0'):
                foreach ($read_excel_report as $read_excel_report_view):
					if($read_excel_report_view['chip_status'] == '0'):
						$read_excel_report_view['chip_status'] = 'ESTOQUE';
					elseif($read_excel_report_view['chip_status'] == '1'):
						$read_excel_report_view['chip_status'] = 'BLOQUEADO';
					elseif($read_excel_report_view['chip_status'] == '2'):
						$read_excel_report_view['chip_status'] = 'ATIVO';
					elseif($read_excel_report_view['chip_status'] == '3'):
						$read_excel_report_view['chip_status'] = 'CANCELADO';
					endif;
                    $rows[] = $read_excel_report_view;
                endforeach;
                $writer = new XLSXWriter();

                $writer->writeSheetHeader('Sheet1', $header);
                foreach ($rows as $row)
                    $writer->writeSheetRow('Sheet1', $row);
                $writer->writeToFile('desbloqueio.xlsx');

                $jSON['msg'] = "Documento foi gerado com sucesso!";
                $jSON['type'] = 'ok';
                $jSON['title'] = 'Parabéns';
            else:
                $jSON['msg'] = 'Não existe registros';
                $jSON['type'] = 'error';
                $jSON['title'] = 'Erro';
            endif;
            break;
    endswitch;
endif;
echo json_encode($jSON);
