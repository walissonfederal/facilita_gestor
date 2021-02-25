<?php
session_start();
ob_start();
require_once '../_class_mmn/Ferramenta.php';

if(isset($_POST['acao'])){
    $acao = addslashes($_POST['acao']);
}else{
    $acao = addslashes($_GET['acao']);
}

if($acao == 'load'){
    //VERIFICAÇÃO PADRÃO PARA PAGINAÇÃO DOS RESULTADOS
    $pag = (empty($_GET['pageNo']) ? '1' : $_GET['pageNo']);
    if(empty($_GET['size'])){
        $maximo = '100';
    }else{
        $maximo = $_GET['size'];
    }
    $inicio = ($pag * $maximo) - $maximo;
    
    //ORDENAÇÃO DO TABULATOR
    if(empty($_GET['sort']) && empty($_GET['sort_dir'])){
        $order_by = "ORDER BY pedido.pedido_id DESC";
    }else{
        
        $sort       = addslashes($_GET['sort']);
        $sort_dir   = addslashes($_GET['sort_dir']);
        $order_by   = "ORDER BY ".$sort." ".$sort_dir."";
    }
    
    //PESQUISA VIA AJAX
    if(isset($_GET['search']) && $_GET['search'] == 'true'){
        $get_id         = addslashes($_GET['id']);
        $get_status     = addslashes($_GET['status']);
        $get_data_inicial = addslashes($_GET['data_inicial']);
        $get_data_final   = addslashes($_GET['data_final']);
        $get_id_user      = addslashes($_GET['id_user']);
        $get_tipo_pesquisa = addslashes($_GET['tipo_pesquisa']);
        
        if($get_id != ''){
            $sql_id = "AND pedido.pedido_id = '".$get_id."'";
        }else{
            $sql_id = "";
        }
        if($get_data_inicial != '' && $get_data_final != ''){
            $sql_periodo = "AND pedido.{$get_tipo_pesquisa} BETWEEN '".$get_data_inicial."' AND '".$get_data_final."'";
        }else{
            $sql_periodo = "";
        }
        if($get_status != ''){
            $sql_status = "AND pedido.pedido_status = '".$get_status."'";
        }else{
            $sql_status = "";
        }
        if($get_id_user != ''){
            $sql_id_user = "AND pedido.pedido_id_user = '".$get_id_user."'";
        }else{
            $sql_id_user = "";
        }
        
        $_SESSION['fatura_load'] = "".$sql_id." ".$sql_id_user." ".$sql_status." ".$sql_periodo." ";
    }
    
    $read_fatura_paginator = ReadComposta("SELECT pedido_id, pedido_status, pedido_valor, pedido_valor_pagamento FROM pedido WHERE pedido_id != '' AND pedido_tipo = '1' {$_SESSION['fatura_load']}");
    if(NumQuery($read_fatura_paginator) > '0'){
        foreach($read_fatura_paginator as $read_fatura_paginator_view){
            if($read_fatura_paginator_view['pedido_status'] == '0'){
                $valor_aberto += $read_fatura_paginator_view['pedido_valor'];
            }elseif($read_fatura_paginator_view['pedido_status'] == '1'){
                $valor_pago += $read_fatura_paginator_view['pedido_valor_pagamento'];
            }else{
                $valor_cancelado += $read_fatura_paginator_view['pedido_valor'];
            }
        }
    }
    //$read_fatura = Read('pedido', "WHERE pedido_id != '' AND pedido_tipo = '1' {$_SESSION['fatura_load']} ".$order_by." LIMIT $inicio,$maximo");
    $read_fatura = ReadComposta("SELECT * FROM pedido INNER JOIN user ON user.user_id = pedido.pedido_id_user WHERE pedido.pedido_tipo IN(1,2) {$_SESSION['fatura_load']} ORDER BY pedido.pedido_data_vencimento DESC LIMIT $inicio,$maximo");
    if(NumQuery($read_fatura) > '0'){
        //PEGA QUANTOS BOTÕES PARA PAGINAÇÃO DE RESULTADOS
        $paginas = ceil(NumQuery($read_fatura_paginator) / $maximo);
        //TABULATOR EXIGE ESSA INFORMAÇÃO PARA QUE PRECISE DA PAGINAÇÃO DE RESULTADOS 
        $json_fatura["last_page"] = $paginas;
        $json_fatura["query_dados"] = $_SESSION['fatura_load'];
        $json_fatura["quantidade_contas"] = NumQuery($read_fatura_paginator);
        $json_fatura["valor_aberto"] = FormatMoney($valor_aberto);
        $json_fatura["valor_pago"] = FormatMoney($valor_pago);
        $json_fatura["valor_cancelado"] = FormatMoney($valor_cancelado);
        $json_fatura["valor_total"] = FormatMoney($valor_cancelado + $valor_pago + $valor_aberto);
        foreach($read_fatura as $read_fatura_view){
            if($read_fatura_view['pedido_status'] == '0'){
                $read_fatura_view['pedido_status'] = 'Aberto';
            }elseif($read_fatura_view['pedido_status'] == '1'){
                $read_fatura_view['pedido_status'] = 'Pago';
            }else{
                $read_fatura_view['pedido_status'] = 'Cancelado';
            }
            $json_fatura['data'][] = $read_fatura_view;
        }
    }else{
        $json_fatura['data'] = null;
    }
    echo json_encode($json_fatura);
}elseif($acao == 'update'){
    //RECUPERA O FORMULARIO
    $fatura_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($fatura_form['acao']);
    
    if(in_array('', $fatura_form)){
        $json_fatura = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        $uid = addslashes($_POST['id']);
        unset($fatura_form['id']);
        Update('pedido', $fatura_form, "WHERE pedido_id = '".$uid."'");
        $json_fatura = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<a href="javascript::" data-dismiss="modal" onclick="carrega_pagina(\'mmn_fatura\', \'index.php\');" class="btn btn-primary">Sair</a>'
        );
    }
    echo json_encode($json_fatura);
}elseif($acao == 'load_update'){
    $uid = addslashes($_POST['id']);
    
    $read_fatura = Read('pedido', "WHERE pedido_id = '".$uid."'");
    if(NumQuery($read_fatura) > '0'){
        foreach($read_fatura as $read_fatura_view);
        $json_fatura[] = $read_fatura_view;
    }else{
        $json_fatura = null;
    }
    echo json_encode($json_fatura);
}elseif($acao == 'load_update_baixar'){
    $uid = addslashes($_POST['id']);
    
    $read_fatura = Read('pedido', "WHERE pedido_id = '".$uid."' AND pedido_status = '0'");
    if(NumQuery($read_fatura) > '0'){
        foreach($read_fatura as $read_fatura_view);
        $json_fatura[] = $read_fatura_view;
    }else{
        $json_fatura = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser realizada!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }
    echo json_encode($json_fatura);
}elseif($acao == 'baixar'){
    $baixar_form['pedido_data_pagamento'] = addslashes(trim(strip_tags($_POST['pedido_data_pagamento'])));
    $baixar_form['pedido_valor_pagamento'] = addslashes(trim(strip_tags($_POST['pedido_valor_pagamento'])));
    
    $gerar_comissao = addslashes(trim($_POST['gerar_comissao']));
    $id = addslashes(trim($_POST['id']));
    
    if(in_array('', $baixar_form)){
        $json_fatura = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser realizada, campos sem preencher!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        $baixar_form['pedido_status'] = '1';
        Update('pedido', $baixar_form, "WHERE pedido_id = '".$id."'");
        if($gerar_comissao == '0'){
            $read_pedido = Read('pedido', "WHERE pedido_id = '".$id."'");
            if(NumQuery($read_pedido) > '0'){
                foreach($read_pedido as $read_pedido_view);
            }
            GerarComissao($read_pedido_view['pedido_id_user'], $read_pedido_view['pedido_valor_chip']);
        }
        $json_fatura = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<a href="javascript::" data-dismiss="modal" onclick="carrega_pagina(\'mmn_fatura\', \'index.php\');" class="btn btn-primary">Sair</a>'
        );
    }
    echo json_encode($json_fatura);
}elseif($acao == 'gerar_excel'){
    $arquivo = 'mmn_faturas.xls';
    $tabela = '<table border="1" width="800px">';
        $tabela .= '<tr>';
            $tabela .= '<td colspan="10" align="center">Relação de faturas mmn</tr>';
        $tabela .= '</tr>';
        $tabela .= '<tr>';
            $tabela .= '<td><b>Código</b></td>';
            $tabela .= '<td><b>Usuário</b></td>';
            $tabela .= '<td><b>Status</b></td>';
            $tabela .= '<td><b>Data Vencimento</b></td>';
            $tabela .= '<td><b>Valor</b></td>';
            $tabela .= '<td><b>Data Pagamento</b></td>';
            $tabela .= '<td><b>Valor Pagamento</b></td>';
            $tabela .= '<td><b>Telefone</b></td>';
            $tabela .= '<td><b>Celular</b></td>';
            $tabela .= '<td><b>Email</b></td>';
        $tabela .= '</tr>';
    
    $read_fatura = ReadComposta("SELECT * FROM pedido INNER JOIN user ON user.user_id = pedido.pedido_id_user WHERE pedido.pedido_tipo = '1' {$_SESSION['fatura_load']} ORDER BY pedido.pedido_data_vencimento DESC");
    if(NumQuery($read_fatura) > '0'){
        foreach($read_fatura as $read_fatura_view){
            if($read_fatura_view['pedido_status'] == '0'){
                $status_fatura = 'Aberto';
            }elseif($read_fatura_view['pedido_status'] == '1'){
                $status_fatura = 'Pago';
            }else{
                $status_fatura = 'Cancelado';
            }
            $tabela .= '<tr>';
                $tabela .= '<td>'.$read_fatura_view['pedido_id'].'</td>';
                $tabela .= '<td>'.$read_fatura_view['user_nome'].'</td>';
                $tabela .= '<td>'.$status_fatura.'</td>';
                $tabela .= '<td>'.$read_fatura_view['pedido_data_vencimento'].'</td>';
                $tabela .= '<td>'.$read_fatura_view['pedido_valor'].'</td>';
                $tabela .= '<td>'.$read_fatura_view['pedido_data_pagamento'].'</td>';
                $tabela .= '<td>'.$read_fatura_view['pedido_valor_pagamento'].'</td>';
                $tabela .= '<td>'.$read_fatura_view['user_telefone'].'</td>';
                $tabela .= '<td>'.$read_fatura_view['user_celular'].'</td>';
                $tabela .= '<td>'.$read_fatura_view['user_email'].'</td>';
            $tabela .= '</tr>';
        }
    }
    
    header("Content-type: application/vnd.ms-excel");  
    header("Content-type: application/force-download"); 
    header("Content-Disposition: attachment; filename=file.xls");
    header("Pragma: no-cache");
    echo utf8_decode($tabela);
}elseif($acao == 'gerar_excel_chip'){
    /*$arquivo = 'mmn_faturas.xls';
    $tabela = '<table border="1" width="800px">';
        $tabela .= '<tr>';
            $tabela .= '<td colspan="12" align="center">Relação de faturas mmn - para cancelamentos</tr>';
        $tabela .= '</tr>';
        $tabela .= '<tr>';
            $tabela .= '<td><b>Código</b></td>';
            $tabela .= '<td><b>Usuário</b></td>';
            $tabela .= '<td><b>Status</b></td>';
            $tabela .= '<td><b>Data Vencimento</b></td>';
            $tabela .= '<td><b>Valor</b></td>';
            $tabela .= '<td><b>Data Pagamento</b></td>';
            $tabela .= '<td><b>Valor Pagamento</b></td>';
            $tabela .= '<td><b>Telefone</b></td>';
            $tabela .= '<td><b>Celular</b></td>';
            $tabela .= '<td><b>Email</b></td>';
            $tabela .= '<td><b>Número Linha</b></td>';
            $tabela .= '<td><b>ICCID</b></td>';
        $tabela .= '</tr>';*/
    //echo $_SESSION['fatura_load'];
    $read_fatura = ReadComposta("SELECT * FROM pedido INNER JOIN `user` ON `user`.user_id = pedido.pedido_id_user WHERE pedido.pedido_tipo = '1' {$_SESSION['fatura_load']} ORDER BY pedido.pedido_data_vencimento DESC");
    if(NumQuery($read_fatura) > '0'){
        foreach($read_fatura as $read_fatura_view){
            if($read_fatura_view['pedido_status'] == '0'){
                $status_fatura = 'Aberto';
            }elseif($read_fatura_view['pedido_status'] == '1'){
                $status_fatura = 'Pago';
            }else{
                $status_fatura = 'Cancelado';
            }
			$user_id .= $read_fatura_view['user_id'].',';
			$dados_data_user[$read_fatura_view['user_id']] = $read_fatura_view['pedido_data_vencimento'];
			$dados_valor_user[$read_fatura_view['user_id']] = $read_fatura_view['pedido_valor'];
			/*
			$tabela .= '<tr>';
				$tabela .= '<td>'.$read_fatura_view['pedido_id'].'</td>';
				$tabela .= '<td>'.$read_fatura_view['user_nome'].'</td>';
				$tabela .= '<td>'.$status_fatura.'</td>';
				$tabela .= '<td>'.$read_fatura_view['pedido_data_vencimento'].'</td>';
				$tabela .= '<td>'.$read_fatura_view['pedido_valor'].'</td>';
				$tabela .= '<td>'.$read_fatura_view['pedido_data_pagamento'].'</td>';
				$tabela .= '<td>'.$read_fatura_view['pedido_valor_pagamento'].'</td>';
				$tabela .= '<td>'.$read_fatura_view['user_telefone'].'</td>';
				$tabela .= '<td>'.$read_fatura_view['user_celular'].'</td>';
				$tabela .= '<td>'.$read_fatura_view['user_email'].'</td>';
				$tabela .= '<td>'.$read_Pedido_view['chip_num'].'-</td>';
				$tabela .= '<td>'.$read_Pedido_view['chip_iccid'].'-</td>';
			$tabela .= '</tr>';
			/*
			$read_Pedido = ReadComposta("SELECT * FROM pedido INNER JOIN itens_pedido_chip ON itens_pedido_chip.itens_pedido_chip_id_pedido = pedido.pedido_id WHERE pedido.pedido_tipo = '0' AND pedido.pedido_id_user = '".$read_fatura_view['user_id']."' AND pedido.pedido_status = '1'");
			if(NumQuery($read_Pedido) > '0'){
				foreach($read_Pedido as $read_Pedido_view){
					
				}
			}
			*/
        }
    }
    
    /*header("Content-type: application/vnd.ms-excel");  
    header("Content-type: application/force-download"); 
    header("Content-Disposition: attachment; filename=file.xls");
    header("Pragma: no-cache");
    echo utf8_decode($tabela);*/
	
	$user_id = substr($user_id, 0,-1);
	
	$arquivo = 'mmn_faturas.xls';
    $tabela .= '<table border="1" width="800px">';
        $tabela .= '<tr>';
            $tabela .= '<td colspan="12" align="center">Relação de chips - para cancelamentos</tr>';
        $tabela .= '</tr>';
        $tabela .= '<tr>';
            $tabela .= '<td><b>Código</b></td>';
            $tabela .= '<td><b>Usuário</b></td>';
            $tabela .= '<td><b>Telefone</b></td>';
            $tabela .= '<td><b>Celular</b></td>';
            $tabela .= '<td><b>Email</b></td>';
			$tabela .= '<td><b>Data Vencimento</b></td>';
			$tabela .= '<td><b>Valor</b></td>';
            $tabela .= '<td><b>Número Linha</b></td>';
            $tabela .= '<td><b>ICCID</b></td>';
        $tabela .= '</tr>';
    $read_fatura = ReadComposta("SELECT * FROM pedido INNER JOIN `user` ON `user`.user_id = pedido.pedido_id_user INNER JOIN itens_pedido_chip ON itens_pedido_chip.itens_pedido_chip_id_pedido = pedido.pedido_id INNER JOIN chip ON chip.chip_id = itens_pedido_chip.itens_pedido_chip_id_chip WHERE pedido.pedido_tipo = '0' AND pedido.pedido_id_user IN(".$user_id.") ORDER BY pedido.pedido_id DESC");
    if(NumQuery($read_fatura) > '0'){
        foreach($read_fatura as $read_fatura_view){
			$tabela .= '<tr>';
				$tabela .= '<td>'.$read_fatura_view['pedido_id'].'</td>';
				$tabela .= '<td>'.$read_fatura_view['user_nome'].'</td>';
				$tabela .= '<td>'.$read_fatura_view['user_telefone'].'</td>';
				$tabela .= '<td>'.$read_fatura_view['user_celular'].'</td>';
				$tabela .= '<td>'.$read_fatura_view['user_email'].'</td>';
				$tabela .= '<td>'.$dados_data_user[$read_fatura_view['pedido_id_user']].'</td>';
				$tabela .= '<td>'.$dados_valor_user[$read_fatura_view['pedido_id_user']].'</td>';
				$tabela .= '<td>'.$read_fatura_view['chip_num'].'-</td>';
				$tabela .= '<td>'.$read_fatura_view['chip_iccid'].'-</td>';
			$tabela .= '</tr>';
        }
    }
    
    header("Content-type: application/vnd.ms-excel");  
    header("Content-type: application/force-download"); 
    header("Content-Disposition: attachment; filename=file.xls");
    header("Pragma: no-cache");
    echo utf8_decode($tabela);
}elseif($acao == 'create'){
    //RECUPERA O FORMULARIO
    $fatura_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($fatura_form['acao']);
    
    if(in_array('', $fatura_form)){
        $json_pedido = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        $fatura_form['pedido_data']         = date('Y-m-d');
        $fatura_form['pedido_data_hora']    = date('Y-m-d H:i:s');
        $fatura_form['pedido_status']       = '0';
        $fatura_form['pedido_tipo']         = '2';
        $fatura_form['pedido_md5']          = md5(date('Y-m-dH:i:s').rand(9,999999999999999999999));
        Create('pedido', $fatura_form);
        $json_pedido = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal" onclick="limpar_campos();">Apenas Fechar</button><a href="javascript::" onclick="carrega_pagina(\'mmn_fatura\', \'index.php\');" data-dismiss="modal" class="btn btn-primary">Sair</a>'
        );
    }
    echo json_encode($json_pedido);
}
?>