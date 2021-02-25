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
        $get_id             = addslashes($_GET['id']);
        $get_data_inicial   = addslashes($_GET['data_inicial']);
        $get_data_final     = addslashes($_GET['data_final']);
        $get_status         = addslashes($_GET['status']);
        $get_enviado        = addslashes($_GET['enviado']);
        $get_id_user        = addslashes($_GET['id_user']);
        $get_tipo_pesquisa  = addslashes($_GET['tipo_pesquisa']);
        
        if($get_id != ''){
            $sql_id = "AND pedido.pedido_id = '".$get_id."'";
        }else{
            $sql_id = "";
        }
        if($get_id_user != ''){
            $sql_id_user = "AND pedido.pedido_id_user = '".$get_id_user."'";
        }else{
            $sql_id_user = "";
        }
        if($get_data_inicial != '' && $get_data_final != ''){
            $sql_periodo = "AND $get_tipo_pesquisa BETWEEN '".$get_data_inicial."' AND '".$get_data_final."'";
        }else{
            $sql_periodo = "";
        }
        if($get_status != ''){
            $sql_status = "AND pedido.pedido_status = '".$get_status."'";
        }else{
            $sql_status = "";
        }
        if($get_enviado != ''){
            $sql_enviado = "AND pedido.pedido_envio = '".$get_enviado."'";
        }else{
            $sql_enviado = "";
        }
        $_SESSION['pedidommn_load'] = "".$sql_id." ".$sql_periodo." ".$sql_status." ".$sql_enviado." ".$sql_id_user." ";
    }
    
    $read_pedido_paginator = ReadComposta("SELECT pedido_id, pedido_status, pedido_valor_pagamento, pedido_valor  FROM pedido WHERE pedido_id != '' AND pedido_tipo = '0' {$_SESSION['pedidommn_load']}");
    if(NumQuery($read_pedido_paginator) > '0'){
        foreach($read_pedido_paginator as $read_pedido_paginator_view){
            if($read_pedido_paginator_view['pedido_status'] == '0'){
                $valor_aberto += $read_pedido_paginator_view['pedido_valor'];
            }elseif($read_pedido_paginator_view['pedido_status'] == '1'){
                $valor_pago += $read_pedido_paginator_view['pedido_valor_pagamento'];
            }else{
                $valor_cancelado += $read_pedido_paginator_view['pedido_valor'];
            }
        }
    }
    //$read_pedido = Read('pedido', "WHERE pedido_id != '' AND pedido_tipo = '0' {$_SESSION['pedidommn_load']} ".$order_by." LIMIT $inicio,$maximo");
    $read_pedido = ReadComposta("SELECT * FROM pedido INNER JOIN user ON user.user_id = pedido.pedido_id_user WHERE pedido.pedido_id != '' AND pedido.pedido_tipo = '0' {$_SESSION['pedidommn_load']} ".$order_by." LIMIT $inicio,$maximo");
    if(NumQuery($read_pedido) > '0'){
        //PEGA QUANTOS BOTÕES PARA PAGINAÇÃO DE RESULTADOS
        $paginas = ceil(NumQuery($read_pedido_paginator) / $maximo);
        //TABULATOR EXIGE ESSA INFORMAÇÃO PARA QUE PRECISE DA PAGINAÇÃO DE RESULTADOS 
        $json_pedido["last_page"] = $paginas;
        $json_pedido["quantidade_contas"] = NumQuery($read_pedido_paginator);
        $json_pedido["valor_aberto"] = FormatMoney($valor_aberto);
        $json_pedido["valor_pago"] = FormatMoney($valor_pago);
        $json_pedido["valor_cancelado"] = FormatMoney($valor_cancelado);
        $json_pedido["valor_total"] = FormatMoney($valor_cancelado + $valor_pago + $valor_aberto);
        foreach($read_pedido as $read_pedido_view){
            if($read_pedido_view['pedido_status'] == '0'){
                $read_pedido_view['pedido_status'] = 'Aberto';
            }elseif($read_pedido_view['pedido_status'] == '1'){
                $read_pedido_view['pedido_status'] = 'Pago';
            }else{
                $read_pedido_view['pedido_status'] = 'Cancelado';
            }
            if($read_pedido_view['pedido_envio'] == '0'){
                $read_pedido_view['pedido_envio'] = 'Não';
            }else{
                $read_pedido_view['pedido_envio'] = 'Sim';
            }
            $read_pedido_view['valor_frete'] = FormatMoney($read_pedido_view['pedido_valor'] - $read_pedido_view['pedido_valor_chip']);
            $read_pedido_view['pedido_valor'] = FormatMoney($read_pedido_view['pedido_valor']);
            $read_pedido_view['pedido_valor_chip'] = FormatMoney($read_pedido_view['pedido_valor_chip']);
			$json_pedido['data'][] = array_map('utf8_encode', $read_pedido_view);
            //$json_pedido['data'][] = $read_pedido_view;
        }
    }else{
        $json_pedido['data'] = null;
    }
    echo json_encode($json_pedido);
}elseif($acao == 'create'){
    //RECUPERA O FORMULARIO
    $caixa_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($caixa_form['acao']);
    
    if(in_array('', $caixa_form)){
        $json_caixa = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        Create('caixa', $caixa_form);
        $json_caixa = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal" onclick="limpar_campos();">Apenas Fechar</button><a href="javascript::" onclick="carrega_pagina(\'caixa\', \'index.php\');" data-dismiss="modal" class="btn btn-primary">Sair</a>'
        );
    }
    echo json_encode($json_caixa);
}elseif($acao == 'update'){
    //RECUPERA O FORMULARIO
    $pedido_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($pedido_form['acao']);
    
    $uid = addslashes($_POST['id_pedido']);
    unset($pedido_form['id_pedido']);
    if(Update('pedido', $pedido_form, "WHERE pedido_id = '".$uid."'")){
        $json_pedido = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<a href="javascript::" data-dismiss="modal" onclick="carrega_pagina(\'mmn_pedidos\', \'index.php\');" class="btn btn-primary">Sair</a>'
        );
    }else{
        $json_pedido = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, houve um erro!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }
    echo json_encode($json_pedido);
}elseif($acao == 'load_update'){
    $uid = addslashes($_POST['id_pedido']);
    
    $read_pedido = Read('pedido', "WHERE pedido_id = '".$uid."'");
    if(NumQuery($read_pedido) > '0'){
        foreach($read_pedido as $read_pedido_view);
        if($read_pedido_view['pedido_status'] == '0'){
            $read_pedido_view['pedido_status'] = 'Aberto';
            $data_pagamento = '';
            $valor_pagamento = '';
        }elseif($read_pedido_view['pedido_status'] == '1'){
            $read_pedido_view['pedido_status'] = 'Pago';
            $data_pagamento = FormatDataBr($read_pedido_view['pedido_data_pagamento']);
            $valor_pagamento = FormatMoney($read_pedido_view['pedido_valor_pagamento']);
        }else{
            $read_pedido_view['pedido_status'] = 'Cancelado';
            $data_pagamento = '';
            $valor_pagamento = '';
        }
		$read_fatura = Read('pedido', "WHERE pedido_tipo = '1' AND pedido_id_user = '".$read_pedido_view['pedido_id_user']."' AND pedido_status = '0' AND pedido_data_vencimento < NOW()");
		if(NumQuery($read_fatura) > '0'){
			echo '<div class="row">
				<h3 align="center">Esse Cliente Possui Faturas em Aberto</h3>
			</div>';
			echo '<table class="table table-hover table-nomargin">
				<thead>
					<tr>
						<th>Cód</th>
						<th>Data Vencimento</th>
						<th>Valor</th>
					</tr>
				</thead>
				<tbody>';
			$count_pedido_grid = '1';
			foreach($read_fatura as $read_fatura_view){
				$sub_total += $read_fatura_view['pedido_valor'];
				echo '<tr>';
					echo '<td>'.$count_pedido_grid.'</td>';
					echo '<td>'.$read_fatura_view['pedido_data_vencimento'].'</td>';
					echo '<td>'.FormatMoney($read_fatura_view['pedido_valor']).'</td>';
				echo '</th>';
				$count_produto_grid++;
			}
			echo '<tr>
                    <td colspan="2"></td>
                    <td class="taxes">
                        <p>
                            <span class="light"><strong>Valor Total</strong></span>
                            <span><strong>R$ '.  FormatMoney($sub_total).'</strong></span>
                        </p>
                    </td>
                </tr>';
			echo '</tbody>
        </table>';
		}
		echo '<hr />';
        echo '<div class="row">
                <div class="form-group col-lg-2">
                    <label>Data</label>
                    <p id="pedido_data">'.FormatDataBr($read_pedido_view['pedido_data']).'</p>
                </div>
                <div class="form-group col-lg-2">
                    <label>Valor</label>
                    <p id="pedido_valor">'.FormatMoney($read_pedido_view['pedido_valor']).'</p>
                </div>
                <div class="form-group col-lg-2">
                    <label>Status</label>
                    <p id="pedido_status">'.$read_pedido_view['pedido_status'].'</p>
                </div>
                <div class="form-group col-lg-2">
                    <label>Data Pagamento</label>
                    <p id="pedido_data_pagamento">'.$data_pagamento.'</p>
                </div>
                <div class="form-group col-lg-2">
                    <label>Valor Pagamento</label>
                    <p id="pedido_valor_pagamento">'.$valor_pagamento.'</p>
                </div>
                <div class="form-group col-lg-2">
                    <label>Pedido Valor Plano</label>
                    <p id="pedido_valor_chip">'.FormatMoney($read_pedido_view['pedido_valor_chip']).'</p>
                </div>
            </div>';
    }else{
        $json_pedido = null;
    }
}elseif($acao == 'load_user'){
    $term = addslashes($_GET['term']);
    
    $read_contato_load = Read('user', "WHERE (user_nome LIKE '%".$term."%') OR (user_username LIKE '%".$term."%') OR (user_cpf LIKE '%".$term."%') OR (user_email LIKE '%".$term."%') ORDER BY user_nome ASC");
    if(NumQuery($read_contato_load) > '0'){
        $json_contato = '[';
        foreach($read_contato_load as $read_contato_load_view){
            $json_contato .= '{"label":"'.$read_contato_load_view['user_nome'].' | '.$read_contato_load_view['user_username'].' | '.$read_contato_load_view['user_cpf'].'","value":"'.$read_contato_load_view['user_id'].'"},';
        }
        $json_contato = substr($json_contato, 0,-1);
        $json_contato .= ']';
    }else{
        $json_contato = array(
                array( "label" => '', "value" => '' ),
        );
    }
    echo $json_contato;
}elseif($acao == 'load_user_id'){
    $uid = addslashes($_POST['id']);
    
    $read_contato_load_id = Read('user', "WHERE user_id = '".$uid."' ORDER BY user_nome ASC");
    if(NumQuery($read_contato_load_id) > '0'){
        $json_contato = '[';
        foreach($read_contato_load_id as $read_contato_load_id_view){
            $json_contato .= '{"label":"'.$read_contato_load_id_view['user_nome'].' | '.$read_contato_load_id_view['user_username'].' | '.$read_contato_load_id_view['user_cpf'].'","value":"'.$read_contato_load_id_view['user_id'].'"},';
        }
        $json_contato = substr($json_contato, 0,-1);
        $json_contato .= ']';
    }else{
        $json_contato = '[';
            $json_contato .= '{"label":"","value":""}';
        $json_contato .= ']';
    }
    echo $json_contato;
}elseif($acao == 'load_pedido_grid'){
    $id_pedido = addslashes(trim(strip_tags($_POST['id_pedido'])));
	
	$pedido_valor_frete = GetDados('pedido', $id_pedido, 'pedido_id', 'pedido_valor_frete');
	$pedido_tipo_frete = GetDados('pedido', $id_pedido, 'pedido_id', 'pedido_frete');
	
	if($pedido_tipo_frete == '4014'){
		$descricao_tipo_frete = 'SEDEX';
	}elseif($pedido_tipo_frete == '4510'){
		$descricao_tipo_frete = 'PAC';
	}elseif($pedido_tipo_frete == '1'){
		$descricao_tipo_frete = 'CARTA REGISTRADA';
	}else{
		$descricao_tipo_frete = 'SEM FRETE';
	}
    
    $read_itens_pedido = Read('itens_pedido', "WHERE itens_pedido_id_pedido = '".$id_pedido."'");
    if(NumQuery($read_itens_pedido) > '0'){
        echo '<table class="table table-hover table-nomargin">
            <thead>
                <tr>
                    <th>Cód</th>
                    <th>Descrição</th>
                    <th>Quantidade</th>
                    <th>Valor Unitário</th>
                    <th>Valor Total</th>
                </tr>
            </thead>
            <tbody>';
        $count_pedido_grid = '1';
        foreach($read_itens_pedido as $read_itens_pedido_view){
            $sub_total += $read_itens_pedido_view['itens_pedido_valor_total'];
            echo '<tr>';
                echo '<td>'.$count_produto_grid.'</td>';
                echo '<td>'.GetDados('produto', $read_itens_pedido_view['itens_pedido_id_produto'], 'produto_id', 'produto_descricao').'</td>';
                echo '<td>'.$read_itens_pedido_view['itens_pedido_quantidade'].'</td>';
                echo '<td>'.FormatMoney($read_itens_pedido_view['itens_pedido_valor_unitario']).'</td>';
                echo '<td>'.FormatMoney($read_itens_pedido_view['itens_pedido_valor_total']).'</td>';
            echo '</th>';
            $count_produto_grid++;
        }
        
        echo '<tr>
                    <td colspan="4"></td>
                    <td class="taxes">
                        <p>
                            <span class="light"><strong>Frete('.$descricao_tipo_frete.')</strong></span>
                            <span><strong>R$ '.  FormatMoney($pedido_valor_frete).'</strong></span>
                        </p>
                    </td>
                </tr>';
        echo '<tr>
                    <td colspan="4"></td>
                    <td class="taxes">
                        <p>
                            <span class="light"><strong>SubTotal</strong></span>
                            <span><strong>R$ '.  FormatMoney($sub_total + $pedido_valor_frete).'</strong></span>
                        </p>
                    </td>
                </tr>';
        echo '</tbody>
        </table>';
    }else{
        
    }
}elseif($acao == 'load_pedido_grid_del'){
    $id_pedido = addslashes(trim(strip_tags($_POST['id_pedido'])));
	
	$pedido_valor_frete = GetDados('pedido', $id_pedido, 'pedido_id', 'pedido_valor_frete');
	$pedido_tipo_frete = GetDados('pedido', $id_pedido, 'pedido_id', 'pedido_frete');
	
	if($pedido_tipo_frete == '4014'){
		$descricao_tipo_frete = 'SEDEX';
	}elseif($pedido_tipo_frete == '4510'){
		$descricao_tipo_frete = 'PAC';
	}elseif($pedido_tipo_frete == '1'){
		$descricao_tipo_frete = 'CARTA REGISTRADA';
	}else{
		$descricao_tipo_frete = 'SEM FRETE';
	}
    
    $read_itens_pedido = Read('itens_pedido', "WHERE itens_pedido_id_pedido = '".$id_pedido."'");
    if(NumQuery($read_itens_pedido) > '0'){
        echo '<table class="table table-hover table-nomargin">
            <thead>
                <tr>
                    <th>Cód</th>
                    <th>Descrição</th>
                    <th>Quantidade</th>
                    <th>Valor Unitário</th>
                    <th>Valor Total</th>
                    <th>ações</th>
                </tr>
            </thead>
            <tbody>';
        $count_pedido_grid = '1';
        foreach($read_itens_pedido as $read_itens_pedido_view){
            $sub_total += $read_itens_pedido_view['itens_pedido_valor_total'];
            echo '<tr>';
                echo '<td>'.$count_produto_grid.'</td>';
                echo '<td>'.GetDados('produto', $read_itens_pedido_view['itens_pedido_id_produto'], 'produto_id', 'produto_descricao').'</td>';
                echo '<td>'.$read_itens_pedido_view['itens_pedido_quantidade'].'</td>';
                echo '<td>'.FormatMoney($read_itens_pedido_view['itens_pedido_valor_unitario']).'</td>';
                echo '<td>'.FormatMoney($read_itens_pedido_view['itens_pedido_valor_total']).'</td>';
                echo '<td><a href="#" onclick="delete_produto_grid_pedido('.$read_itens_pedido_view['itens_pedido_id'].')">Deletar</a></td>';
            echo '</th>';
            $count_produto_grid++;
        }
        
        echo '<tr>
                    <td colspan="5"></td>
                    <td class="taxes">
                        <p>
                            <span class="light"><strong>Frete('.$descricao_tipo_frete.')</strong></span>
                            <span><strong>R$ '.  FormatMoney($pedido_valor_frete).'</strong></span>
                        </p>
                    </td>
                </tr>';
        echo '<tr>
                    <td colspan="5"></td>
                    <td class="taxes">
                        <p>
                            <span class="light"><strong>SubTotal</strong></span>
                            <span><strong>R$ '.  FormatMoney($sub_total + 10).'</strong></span>
                        </p>
                    </td>
                </tr>';
        echo '</tbody>
        </table>';
    }else{
        
    }
}elseif($acao == 'load_update_campos'){
    $uid = addslashes($_POST['id_pedido']);
    
    $read_pedido = Read('pedido', "WHERE pedido_id = '".$uid."'");
    if(NumQuery($read_pedido) > '0'){
        foreach($read_pedido as $read_pedido_view);
        if($read_pedido_view['pedido_status'] == '0'){
            $read_pedido_view['pedido_status'] = 'Aberto';
            $data_pagamento = '';
            $valor_pagamento = '';
        }elseif($read_pedido_view['pedido_status'] == '1'){
            $read_pedido_view['pedido_status'] = 'Pago';
            $data_pagamento = FormatDataBr($read_pedido_view['pedido_data_pagamento']);
            $valor_pagamento = FormatMoney($read_pedido_view['pedido_valor_pagamento']);
        }else{
            $read_pedido_view['pedido_status'] = 'Cancelado';
            $data_pagamento = '';
            $valor_pagamento = '';
        }
        $json_pedido[] = $read_pedido_view;
    }else{
        $json_pedido = null;
    }
    echo json_encode($json_pedido);
}elseif($acao == 'baixar'){
    $id_pedido = addslashes(trim(strip_tags($_POST['id_pedido'])));
    
    $data_pagamento = addslashes(trim(strip_tags($_POST['data_pagamento'])));
    $valor_pagamento = addslashes(trim(strip_tags($_POST['valor_pagamento'])));
    $gerar_comimssao = addslashes(trim(strip_tags($_POST['gerar_comissao'])));
    
    if($data_pagamento == '' || $valor_pagamento == ''){
        $json_retorno = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, campos não podem ser nulos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        $read_pedido = Read('pedido', "WHERE pedido_id = '".$id_pedido."' AND pedido_status = '0'");
        if(NumQuery($read_pedido) > '0'){
            foreach($read_pedido as $read_pedido_view);
            if($read_pedido_view['pedido_tipologia'] == '1'){
                $user_update['user_status'] = '1';
                $user_update['user_data_ativacao'] = date('Y-m-d');
                Update('user', $user_update, "WHERE user_id = '".$read_pedido_view['pedido_id_user']."'");
            }
            $pedido_update['pedido_status'] = '1';
            $pedido_update['pedido_data_pagamento'] = $data_pagamento;
            $pedido_update['pedido_valor_pagamento'] = $valor_pagamento;
            Update('pedido', $pedido_update, "WHERE pedido_id = '".$id_pedido."'");
            if($gerar_comimssao == '0'){
                GerarComissao($read_pedido_view['pedido_id_user'], $valor_pagamento);
            }
            $json_retorno = array(
                'type' => 'success',
                'title' => 'Parabéns:',
                'msg' => 'Operação realizada com sucesso',
                'buttons' => '<a href="javascript::" data-dismiss="modal" onclick="carrega_pagina(\'mmn_pedidos\', \'index.php\');" class="btn btn-primary">Sair</a>'
            );
        }else{
            $json_retorno = array(
                'type' => 'error',
                'title' => 'Erro:',
                'msg' => 'Ops, houve um erro!',
                'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
            );
        }
    }
    echo json_encode($json_retorno);
}elseif($acao == 'load_chip_insert'){
    $term = addslashes($_GET['term']);
    
    $read_chip_load = Read('chip', "WHERE (chip_status = '0') AND  ((chip_num LIKE '%".$term."%') OR (chip_iccid LIKE '%".$term."%')) ORDER BY chip_id ASC LIMIT 10");
    if(NumQuery($read_chip_load) > '0'){
        $json_chip = '[';
        foreach($read_chip_load as $read_chip_load_view){
            $json_chip .= '{"label":"'.$read_chip_load_view['chip_num'].' | '.$read_chip_load_view['chip_iccid'].'","value":"'.$read_chip_load_view['chip_id'].'"},';
        }
        $json_chip = substr($json_chip, 0,-1);
        $json_chip .= ']';
    }else{
        $json_chip = array(
                array( "label" => '', "value" => '' ),
        );
    }
    echo $json_chip;
}elseif($acao == 'load_produto_insert'){
    $term = addslashes($_GET['term']);
    
    $read_produto_load = Read('produto', "WHERE (produto_status = '0') AND  (produto_descricao LIKE '%".$term."%') ORDER BY produto_id ASC LIMIT 10");
    if(NumQuery($read_produto_load) > '0'){
        $json_produto = '[';
        foreach($read_produto_load as $read_produto_load_view){
            $json_produto .= '{"label":"'.$read_produto_load_view['produto_descricao'].' | '.$read_produto_load_view['produto_valor'].'","value":"'.$read_produto_load_view['produto_id'].'"},';
        }
        $json_produto = substr($json_produto, 0,-1);
        $json_produto .= ']';
    }else{
        $json_produto = array(
                array( "label" => '', "value" => '' ),
        );
    }
    echo $json_produto;
}elseif($acao == 'load_chip_pedido'){
    $get_id_pedido = addslashes($_POST['id_pedido']);
    
    $read_itens_pedido = ReadComposta("SELECT * FROM itens_pedido_chip INNER JOIN chip ON chip.chip_id = itens_pedido_chip.itens_pedido_chip_id_chip WHERE itens_pedido_chip.itens_pedido_chip_id_pedido = '".$get_id_pedido."'");
    if(NumQuery($read_itens_pedido) > '0'){
        echo '<table class="table">';
            echo '<thead>';
                echo '<tr>';
                    echo '<th>#</th>';
                    echo '<th>Número</th>';
                    echo '<th>ICCID</th>';
                    echo '<th colspan="2">ações</th>';
                echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            $count_itens_pedidos = '0';
        foreach($read_itens_pedido as $read_itens_pedido_view){
            $count_itens_pedidos++;
            echo '<tr>';
                echo '<td>'.$count_itens_pedidos.'</td>';
                echo '<td>'.$read_itens_pedido_view['chip_num'].'</td>';
                echo '<td>'.$read_itens_pedido_view['chip_iccid'].'</td>';
                echo '<td><a href="#" onclick="delete_chip_pedido_ok('.$read_itens_pedido_view['itens_pedido_chip_id'].', '.$read_itens_pedido_view['chip_id'].');">Deletar</a></td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
        
    }
}elseif($acao == 'create_chip_pedido'){
    $id_pedido = addslashes(trim(strip_tags($_POST['id_pedido'])));
    $id_chip   = addslashes(trim(strip_tags($_POST['id_chip'])));
    
    $read_chip = Read('chip', "WHERE chip_id = '".$id_chip."' AND chip_status = '0'");
    
    if(NumQuery($read_chip) > '0'){
        $itens_chip['itens_pedido_chip_id_pedido'] = $id_pedido;
        $itens_chip['itens_pedido_chip_id_chip'] = $id_chip;
        Create('itens_pedido_chip', $itens_chip);
        $update_chip_pedido['chip_status'] = '1';
        Update('chip', $update_chip_pedido, "WHERE chip_id = '".$id_chip."'");
    }else{
        $json_retorno = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, houve um erro, chip já deve está em uso!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }
    echo json_encode($json_retorno);
}elseif($acao == 'create_produto_pedido'){
    $id_pedido = addslashes(trim(strip_tags($_POST['id_pedido'])));
    $id_produto   = addslashes(trim(strip_tags($_POST['id_produto'])));
    $quantidade   = addslashes(trim(strip_tags($_POST['quantidade'])));
    
    $read_produto = Read('produto', "WHERE produto_id = '".$id_produto."' AND produto_status = '0'");
    
    if(NumQuery($read_produto) > '0'){
        $itens_produto['itens_pedido_id_pedido'] = $id_pedido;
        $itens_produto['itens_pedido_id_produto'] = $id_produto;
        $itens_produto['itens_pedido_valor_unitario'] = GetDados('produto', $id_produto, 'produto_id', 'produto_valor');
        $itens_produto['itens_pedido_quantidade'] = $quantidade;
        $itens_produto['itens_pedido_valor_total'] = GetDados('produto', $id_produto, 'produto_id', 'produto_valor') * $quantidade;
        Create('itens_pedido', $itens_produto);
    }else{
        $json_retorno = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, houve um erro, chip já deve está em uso!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }
    echo json_encode($json_retorno);
}elseif($acao == 'delete_chip_pedido_ok'){
    $id_chip_pedido = addslashes(trim(strip_tags($_POST['id_chip_pedido'])));
    $id_chip        = addslashes(trim(strip_tags($_POST['id_chip'])));
    
    Delete('itens_pedido_chip', "WHERE itens_pedido_chip_id = '".$id_chip_pedido."' AND itens_pedido_chip_id_chip = '".$id_chip."'");
    $update_chip_pedido['chip_status'] = '0';
    Update('chip', $update_chip_pedido, "WHERE chip_id = '".$id_chip."'");
}elseif($acao == 'load_email_cliente'){
    $id_pedido = addslashes(trim(strip_tags($_POST['id_pedido'])));
    $read_pedido = Read('pedido', "WHERE pedido_id = '".$id_pedido."'");
    if(NumQuery($read_pedido) > '0'){
        foreach($read_pedido as $read_pedido_view);
        $read_user = Read('user', "WHERE user_id = '".$read_pedido_view['pedido_id_user']."'");
        if(NumQuery($read_user) > '0'){
            foreach($read_user as $read_user_view);
            echo $read_user_view['user_email'];
        }
    }
}elseif($acao == 'notificar_cliente'){
    $email = addslashes(trim(strip_tags($_POST['email'])));
    $codigo_rastreio = addslashes(trim(strip_tags($_POST['codigo_rastreio'])));
    
    if(ValMail($email)){
        $MailBody = "
            <p style='font-size: 1.4em;'>Prezado(a)</p>
            <p>Goianésia - GO, ".date('d')." de mês ".date('m')." do ano de ".date('Y')."</p>
            <p>Abaixo segue o link para acessar o codigo de rastreio do seu pedido, o código é <strong>".$codigo_rastreio."</strong></p>
            <p style='font-size: 1.2em;'><a href='http://www2.correios.com.br/sistemas/rastreamento/' title='' target='_blank'>ACESSAR</a></p>
            <p>...</p>
            <p>OBS.: Caso não tenha solicitado, favor ignore essa mensagem!</p>  
            <p>IMPORTANTE: caso não tenha ciência desta mensagem por favor não prossiga!</p>
            <p>IMPORTANTE: caso não concorde com nossos termos favor não prossiga!</p>
            <p>...</p>
            <p>Qualquer dúvida ou problema não deixe de entrar em contato pelo e-mail financeiro@federalsistemas.com.br, ficamos a disposição!</p>
            <p><em>Atenciosamente FederalNetMóvel!</em></p>
        ";
        $MailContent = '
            <table width="550" style="font-family: "Trebuchet MS", sans-serif;">
             <tr><td>
              <font face="Trebuchet MS" size="3">
               '.$MailBody.'
              </font>
              </td></tr>
            </table>
            <style>body, img{max-width: 550px !important; height: auto !important;} p{margin-botton: 15px 0 !important;}</style>';

        sendMail('PEDIDO ENVIADO FEDERALNETMÓVEL', $MailContent, 'financeiro@federalsistemas.com.br', 'FederalNetMóvel', $email, 'Cliente');
        echo 'Ok';
    }else{
        echo 'Mensagem não pode ser enviada, email incorreto!';
    }
}elseif($acao == 'delete_produto_pedido_ok'){
    $id_produto        = addslashes(trim(strip_tags($_POST['id_produto'])));
    
    Delete('itens_pedido', "WHERE itens_pedido_id = '".$id_produto."'");
}
?>