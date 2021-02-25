<?php

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
                $maximo = '50';
            else:
                $maximo = $_GET['size'];
            endif;
            $inicio = ($pag * $maximo) - $maximo;
            //ORDENATION
            if (empty($_GET['sort']) && empty($_GET['sort_dir'])):
                $order_by = "ORDER BY financeiro_id DESC";
            else:
                $sort = addslashes($_GET['sort']);
                $sort_dir = addslashes($_GET['sort_dir']);
                $order_by = "ORDER BY " . $sort . " " . $sort_dir . "";
            endif;
            //PESQUISAR
            if (isset($_POST['search']) && $_POST['search'] == 'true'):
                if ($Post['data_inicial'] != '' && $Post['data_final'] != ''):
                    $sql_periodo = "AND {$Post['tipo']} BETWEEN '" . $Post['data_inicial'] . "' AND '" . $Post['data_final'] . "'";
                else:
                    $sql_periodo = "";
                endif;
                if ($Post['id_contato'] != ''):
                    $sql_id_user = "AND financeiro_id_contato = '" . $Post['id_contato'] . "'";
                else:
                    $sql_id_user = "";
                endif;
                if ($Post['id'] != ''):
                    $sql_id = "AND financeiro_id = '" . $Post['id'] . "'";
                else:
                    $sql_id = "";
                endif;
                if ($Post['status_cliente'] != ''):
                    $sql_status_cliente = "AND contato_bloqueio_desbloqueio = '" . $Post['status_cliente'] . "'";
                else:
                    $sql_status_cliente = "";
                endif;
                $jSONsEARCH['type'] = 'ok';
                $jSONsEARCH['info'] = $Post;
                $_SESSION['search_bloqueio'] = $jSONsEARCH;
                $_SESSION['sql_bloqueio'] = " " . $sql_periodo . " " . $sql_id_user . " " . $sql_id . " " . $sql_status_cliente . " ";
            endif;
            //QUERY
            $date_vencimento = date('Y-m-d', strtotime('-1 days'));
            $read_pedido_paginator = ReadComposta("SELECT COUNT(*) AS contador FROM financeiro INNER JOIN contato ON contato_id = financeiro_id_contato WHERE financeiro_tipo = 'CR' AND financeiro_status = '0'  {$_SESSION['sql_bloqueio']} AND financeiro_data_vencimento BETWEEN '2010-01-01' AND '" . $date_vencimento . "' GROUP BY financeiro_id_contato HAVING COUNT(*) > 0");
            $read_pedido = ReadComposta("SELECT COUNT(*) AS contador, financeiro_id, contato_nome_razao, financeiro_id_contato, contato_bloqueio_desbloqueio FROM financeiro INNER JOIN contato ON contato_id = financeiro_id_contato WHERE financeiro_tipo = 'CR' AND financeiro_status = '0' {$_SESSION['sql_bloqueio']} AND financeiro_data_vencimento BETWEEN '2010-01-01' AND '" . $date_vencimento . "' GROUP BY financeiro_id_contato HAVING COUNT(*) > 0 " . $order_by . " LIMIT $inicio,$maximo");
            if (NumQuery($read_pedido) > '0'):
                $paginas = ceil(NumQuery($read_pedido_paginator) / $maximo);
                $jSON["last_page"] = $paginas;
                $jSON["pedido_quantidade"] = NumQuery($read_pedido_paginator);
                foreach ($read_pedido as $read_pedido_view):
                    if ($read_pedido_view['contato_bloqueio_desbloqueio'] == '0'):
                        $read_pedido_view['contato_bloqueio_desbloqueio'] = 'DESBLOQUEADO';
                    elseif ($read_pedido_view['contato_bloqueio_desbloqueio'] == '1'):
                        $read_pedido_view['contato_bloqueio_desbloqueio'] = 'BLOQUEADO';
                    endif;

                    $jSON['data'][] = $read_pedido_view;
                endforeach;
            endif;
            break;
        case 'search_load':
            unset($_SESSION['search_bloqueio']['info']['search']);
            $jSON = $_SESSION['search_bloqueio'];
            break;
        case 'finalizar_bloqueio':
            $bloqueio_form['contato_bloqueio_desbloqueio'] = '1';
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
                $date_vencimento = date('Y-m-d', strtotime('-1 days'));
                $read_user = ReadComposta("SELECT COUNT(*) AS contador, financeiro_id_contato FROM financeiro INNER JOIN contato ON contato_id = financeiro_id_contato WHERE financeiro_tipo = 'CR' AND financeiro_status = '0' {$_SESSION['sql_bloqueio']} AND financeiro_data_vencimento BETWEEN '2010-01-01' AND '" . $date_vencimento . "' GROUP BY financeiro_id_contato HAVING COUNT(*) > 0");
                if (NumQuery($read_user) > '0'):
                    foreach ($read_user as $read_user_view):
                        $user_id .= $read_user_view['financeiro_id_contato'] . ',';
                        $array_contador[$read_user_view['financeiro_id_contato']] = $read_user_view['contador'];
                    endforeach;
                endif;

                $user_id_dados = substr($user_id, 0, -1);
                if (Update('contato', $bloqueio_form, "WHERE contato_id IN(" . $user_id_dados . ")")):
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
                'TIPO' => 'string',
                'STATUS CLIENTE' => 'string',
                'STATUS PEDIDO' => 'string',
                'FATURAS EM ABERTO' => 'string'
            );

            $date_vencimento = date('Y-m-d', strtotime('-1 days'));

            $read_user = ReadComposta("SELECT COUNT(*) AS contador, financeiro_id_contato FROM financeiro INNER JOIN contato ON contato_id = financeiro_id_contato WHERE financeiro_tipo = 'CR' AND financeiro_status = '0' {$_SESSION['sql_bloqueio']} AND financeiro_data_vencimento BETWEEN '2010-01-01' AND '" . $date_vencimento . "' GROUP BY financeiro_id_contato HAVING COUNT(*) > 0");
            if (NumQuery($read_user) > '0'):
                foreach ($read_user as $read_user_view):
                    $user_id .= $read_user_view['financeiro_id_contato'] . ',';
                    $array_contador[$read_user_view['financeiro_id_contato']] = $read_user_view['contador'];
                endforeach;
            endif;

            $user_id_dados = substr($user_id, 0, -1);

            $read_excel_report = ReadComposta("SELECT contato_id, contato_nome_razao, contato_telefone, contato_celular, contato_email, chip_num, chip_iccid, chip_plano, contato_bloqueio_desbloqueio, chip_status FROM contato INNER JOIN chip_app ON id_contato = contato_id WHERE contato_id IN(" . $user_id_dados . ") ORDER BY contato_id ASC");
            if (NumQuery($read_excel_report) > '0'):
                foreach ($read_excel_report as $read_excel_report_view):
                    if ($read_excel_report_view['contato_bloqueio_desbloqueio'] == '0'):
                        $read_excel_report_view['contato_bloqueio_desbloqueio'] = 'DESBLOQUEADO';
                    elseif ($read_excel_report_view['contato_bloqueio_desbloqueio'] == '1'):
                        $read_excel_report_view['contato_bloqueio_desbloqueio'] = 'BLOQUEADO';
                    endif;
					$data = date("Y-m-d");
                    if($read_excel_report_view['chip_status'] == '0'):
						$read_excel_report_view['chip_status'] = 'ESTOQUE';
					elseif($read_excel_report_view['chip_status'] == '1'):
						$read_excel_report_view['chip_status'] = 'BLOQUEADO';
					elseif($read_excel_report_view['chip_status'] == '2'):
						$read_excel_report_view['chip_status'] = 'ATIVO';
					elseif($read_excel_report_view['chip_status'] == '3'):
						$read_excel_report_view['chip_status'] = 'CANCELADO';
					endif;
                    $read_excel_report_view['fatura_aberto'] = $array_contador[$read_excel_report_view['contato_id']];
                    $rows[] = $read_excel_report_view;
                endforeach;
                $writer = new XLSXWriter();

                $writer->writeSheetHeader('Sheet1', $header);
                foreach ($rows as $row)
                    $writer->writeSheetRow('Sheet1', $row);
                $writer->writeToFile('bloqueio.xlsx');

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
