<?php
session_start();
ob_start();
require_once '../_class/Ferramenta.php';

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
        $order_by = "ORDER BY os.os_id DESC";
    }else{
        
        $sort       = addslashes($_GET['sort']);
        $sort_dir   = addslashes($_GET['sort_dir']);
        $order_by   = "ORDER BY ".$sort." ".$sort_dir."";
    }
    
    //PESQUISA VIA AJAX
    if(isset($_GET['search']) && $_GET['search'] == 'true'){
        $get_os_id          = addslashes($_GET['os_id']);
        $get_os_id_contato  = addslashes($_GET['os_id_contato']);
        $get_os_id_user     = addslashes($_GET['os_id_user']);
        $get_os_status      = addslashes($_GET['os_status']);
        $get_os_id_responsavel = addslashes($_GET['os_id_responsavel']);
        $get_os_tipo_pesquisa = addslashes($_GET['tipo_pesquisa']);
        $get_os_data_inicial = addslashes($_GET['data_inicial']);
        $get_os_data_final   = addslashes($_GET['data_final']);
        $get_frota = addslashes($_GET['frota']);
        $get_placa = addslashes($_GET['placa']);
		$get_nome_associado = addslashes($_GET['nome_associado']);
		$get_id_inicio = addslashes($_GET['os_id_user_inicio']);
		$get_os_veiculo_serial = addslashes($_GET['os_veiculo_serial']);
		
		if($get_nome_associado != ''){
            $sql_nome_associado = "AND os_client.os_client_name LIKE '%".$get_nome_associado."%'";
        }else{
            $sql_nome_associado = "";
        }
		if($get_os_veiculo_serial != ''){
            $sql_os_veiculo_serial = "AND os_veiculo.os_veiculo_serial LIKE '%".$get_os_veiculo_serial."%'";
        }else{
            $sql_os_veiculo_serial = "";
        }
        
        if($get_placa != ''){
            $sql_placa = "AND os_veiculo.os_veiculo_placa LIKE '%".$get_placa."%'";
        }else{
            $sql_placa = "";
        }
        if($get_frota != ''){
            $sql_frota = "AND os_veiculo.os_veiculo_frota LIKE '%".$get_frota."%'";
        }else{
            $sql_frota = "";
        }
        if($get_os_data_inicial != '' && $get_os_data_final != ''){
            $sql_periodo = "AND $get_os_tipo_pesquisa BETWEEN '".$get_os_data_inicial."' AND '".$get_os_data_final."'";
        }else{
            $sql_periodo = "";
        }
        
        if($get_os_id != ''){
            $sql_id = "AND os.os_id = '".$get_os_id."'";
        }else{
            $sql_id = "";
        }
        if($get_os_id_contato != ''){
            $sql_id_contato = "AND os.os_id_contato LIKE '%".$get_os_id_contato."%'";
        }else{
            $sql_id_contato = "";
        }
        if($get_os_id_user != ''){
            $sql_id_user = "AND os.os_id_user = '".$get_os_id_user."'";
        }else{
            $sql_id_user = "";
        }
        if($get_os_id_responsavel != ''){
            $sql_id_responsavel = "AND os.os_id_responsavel = '".$get_os_id_responsavel."'";
        }else{
            $sql_id_responsavel = "";
        }
        if($get_os_status != ''){
            $sql_status = "AND os.os_status = '".$get_os_status."'";
        }else{
            $sql_status = "";
        }
		if($get_id_inicio != ''){
			$sql_id_inicio = "AND os.os_id_user_inicio = '".$get_id_inicio."'";
		}else{
			$sql_id_inicio = "";
		}
		
        
        $_SESSION['os_load'] = "".$sql_id." ".$sql_id_contato." ".$sql_id_user." ".$sql_status." ".$sql_id_responsavel." ".$sql_periodo." ".$sql_placa." ".$sql_frota." ".$sql_nome_associado." ".$sql_id_inicio." ".$sql_os_veiculo_serial." ";
    }
	$os_quantidade_os = '0';
    $os_quantidade_veiculos = '0';
    $read_os_paginator = ReadComposta("SELECT os.os_id FROM os WHERE os.os_id != '' {$_SESSION['os_load']}");
    if(NumQuery($read_os_paginator) > '0'){
        foreach($read_os_paginator as $read_os_paginator_view){
            $in_os_paginator .= $read_os_paginator_view['os_id'].',';
        }
    }
    $in_completo = substr($in_os_paginator, 0,-1);
    $read_veiculo_os = ReadComposta("SELECT os_veiculo_id FROM os_veiculo WHERE os_veiculo_id_os IN(".$in_completo.")");
    //$read_os = Read('os', "WHERE os_id != '' {$_SESSION['os_load']} ".$order_by." LIMIT $inicio,$maximo");
    $read_os = ReadComposta("SELECT * FROM os LEFT JOIN os_veiculo ON os_veiculo.os_veiculo_id_os = os.os_id LEFT JOIN os_client ON os.os_id = os_client.os_client_id_os WHERE os.os_id != '' {$_SESSION['os_load']} GROUP BY os.os_id ".$order_by." LIMIT $inicio,$maximo");
    if(NumQuery($read_os) > '0'){
        //PEGA QUANTOS BOTÕES PARA PAGINAÇÃO DE RESULTADOS
        $paginas = ceil(NumQuery($read_os_paginator) / $maximo);
        //TABULATOR EXIGE ESSA INFORMAÇÃO PARA QUE PRECISE DA PAGINAÇÃO DE RESULTADOS 
        $json_os["last_page"] = $paginas;
        $json_os["os_quantidade_os"] = NumQuery($read_os_paginator);
        $json_os["os_quantidade_veiculos"] = NumQuery($read_veiculo_os);
        foreach($read_os as $read_os_view){
            $read_os_view['os_id_contato'] = GetDados('contato', $read_os_view['os_id_contato'], 'contato_id', 'contato_nome_fantasia');
            $read_os_view['os_id_responsavel'] = GetDados('user', $read_os_view['os_id_responsavel'], 'user_id', 'user_nome');
			$read_os_view['os_id_user_inicio'] = GetDados('user', $read_os_view['os_id_user_inicio'], 'user_id', 'user_nome');
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
            $read_os_view['os_data_inicial'] = FormDataBr($read_os_view['os_data_inicial']);
			$utfEncodedArray = array_map("utf8_encode", $read_os_view );
            $json_os['data'][] = $utfEncodedArray;
        }
    }else{
        $json_os['data'] = null;
    }
    echo json_encode($json_os);
}elseif($acao == 'create'){
    //RECUPERA O FORMULARIO
    $os_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($os_form['acao']);
    
	$os_form['os_id_user_inicio'] = $_SESSION[VSESSION]['user_id'];
	
    if($os_form['os_id_contato'] == '' || $os_form['os_id_responsavel'] == '' || $os_form['os_data_inicial'] == '' || $os_form['os_descricao'] == ''){
        $json_os = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        $os_form['os_data'] = date('Y-m-d');
        if(Create('os', $os_form)):
			$json_os = array(
				'type' => 'success',
				'title' => 'Parabéns:',
				'msg' => 'Operação realizada com sucesso',
				'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal" onclick="limpar_campos();">Apenas Fechar</button><a href="javascript::" onclick="carrega_pagina(\'os\', \'index.php\');" data-dismiss="modal" class="btn btn-primary">Sair</a>'
			);
		else:
			$json_os = array(
				'type' => 'error',
				'title' => 'Erro:',
				'msg' => 'Ops, operação não pode ser finalizada!',
				'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
			);
		endif;	
    }
    echo json_encode($json_os);
}elseif($acao == 'update'){
    //RECUPERA O FORMULARIO
    $os_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($os_form['acao']);
    
	//$os_form['os_id_user_inicio'] = $_SESSION[VSESSION]['user_id'];
	
    if($os_form['os_id_contato'] == '' || $os_form['os_id_responsavel'] == '' || $os_form['os_data_inicial'] == '' || $os_form['os_descricao'] == ''){
        $json_os = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        $uid = addslashes($_POST['id']);
        unset($os_form['id']);
        if(Update('os', $os_form, "WHERE os_id = '".$uid."'")):
			$json_os = array(
				'type' => 'success',
				'title' => 'Parabéns:',
				'msg' => 'Operação realizada com sucesso',
				'buttons' => '<a href="javascript::" onclick="carrega_pagina(\'os\', \'index.php\');" data-dismiss="modal" class="btn btn-primary">Sair</a>'
			);
		else:
			$json_os = array(
				'type' => 'error',
				'title' => 'Erro:',
				'msg' => 'Ops, operação não pode ser finalizada!',
				'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
			);
		endif;
    }
    echo json_encode($json_os);
}elseif($acao == 'load_update'){
    $uid = addslashes($_POST['id']);
    
    $read_os = Read('os', "WHERE os_id = '".$uid."'");
    if(NumQuery($read_os) > '0'){
        foreach($read_os as $read_os_view);
        $json_os[] = $read_os_view;
    }else{
        $json_os = null;
    }
    echo json_encode($json_os);
}elseif($acao == 'insert_produto'){
    $id_produto     = addslashes($_POST['id_produto']);
    $qtd            = addslashes($_POST['qtd']);
    $valor_unitario = addslashes($_POST['valor_unitario']);
    
    $id_os = addslashes($_POST['id_os']);
    
    $read_produto = Read('produto', "WHERE produto_id = '".$id_produto."'");
    if(NumQuery($read_produto) == '0'){
        $json_produto = array(
            'type' => 'error',
            'title' => 'ERROR',
            'msg' => 'Ops, produto não pode ser encontrado!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        $os_form['itens_os_id_os']          = $id_os;
        $os_form['itens_os_id_produto']     = $id_produto;
        $os_form['itens_os_quantidade']     = $qtd;
        $os_form['itens_os_valor_unitario'] = $valor_unitario;
        $os_form['itens_os_valor_total']    = $valor_unitario * $qtd;
        Create('itens_os', $os_form);
        $json_produto = array(
            'type' => 'success',
            'title' => 'OK'
        );
    }
    echo json_encode($json_produto);
}elseif($acao == 'load_produto_grid'){
    $id_os = addslashes($_POST['id_os']);
    echo '<table class="table table-hover table-nomargin">
            <thead>
                <tr>
                    <th>Cód</th>
                    <th>Descrição</th>
                    <th>Quantidade</th>
                    <th>Valor Unitário</th>
                    <th>Valor Total</th>
                    <th>Opções</th>
                </tr>
            </thead>
            <tbody>';
            $count_produto_grid = '1';
            $read_produto = Read('itens_os', "WHERE itens_os_id_os = '".$id_os."'");
            if(NumQuery($read_produto) > '0'){
                foreach($read_produto as $read_produto_view){
                    $sub_total += $read_produto_view['itens_os_valor_total'];
                    echo '<tr>';
                        echo '<td>'.$count_produto_grid.'</td>';
                        echo '<td>'.GetDados('produto', $read_produto_view['itens_os_id_produto'], 'produto_id', 'produto_descricao').'</td>';
                        echo '<td>'.$read_produto_view['itens_os_quantidade'].'</td>';
                        echo '<td>'.FormatMoney($read_produto_view['itens_os_valor_unitario']).'</td>';
                        echo '<td>'.FormatMoney($read_produto_view['itens_os_valor_total']).'</td>';
                        echo '<td><button type="button" class="btn btn-danger" onclick="delete_prod_grid('.$read_produto_view['itens_os_id'].');">Deletar</button></td>';
                    echo '</th>';
                    $count_produto_grid++;
                }
            }
            echo '<tr>
                    <td colspan="5"></td>
                    <td class="taxes">
                        <p>
                            <span class="light"><strong>SubTotal</strong></span>
                            <span><strong>R$ '.  FormatMoney($sub_total).'</strong></span>
                        </p>
                    </td>
                </tr>';
        echo '</tbody>
        </table>';
}elseif($acao == 'delete_produto_grid'){
    $id_produto = addslashes($_POST['id_produto']);
    $id_os = addslashes($_POST['id_os']);
    Delete('itens_os', "WHERE itens_os_id_os = '".$id_os."' AND itens_os_id = '".$id_produto."'");
}elseif($acao == 'load_os_faturar'){
    $uid = addslashes($_POST['id']);
    
    $read_fatura_os = ReadComposta("SELECT SUM(itens_os_valor_total) AS valor_total_os FROM itens_os WHERE itens_os_id_os = '".$uid."'");
    if(NumQuery($read_fatura_os) > '0'){
        foreach($read_fatura_os as $read_fatura_os_view);
        $sub_total = $read_fatura_os_view['valor_total_os'];
    }
    $read_os = Read('os', "WHERE os_id = '".$uid."'");
    if(NumQuery($read_os) > '0'){
        foreach($read_os as $read_os_view);
    }
    
    if($sub_total > '0'){
        $json_os = array(
            'type' => 'success',
            'valor_os' => $sub_total,
            'id_contato' => $read_os_view['os_id_contato'],
            'data_vencimento' => date('Y-m-d'),
            'descricao' => 'FATURA OS Nº '.$uid,
            'num_doc' => $uid
        );
    }else{
        $json_os = array(
            'type' => 'error',
            'title' => 'ERROR',
            'msg' => 'Ops, os não pode ser faturada!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }
    echo json_encode($json_os);
}elseif($acao == 'create_financeiro'){
    //RECUPERA O FORMULARIO
    $financeiro_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($financeiro_form['acao']);
    
    if(in_array('', $financeiro_form)){
        $json_financeiro = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        $primeira_parcela   = $financeiro_form['financeiro_data_vencimento'];
        $parcela            = $financeiro_form['financeiro_parcela'];
        $config             = $financeiro_form['financeiro_config'];
        $valor              = $financeiro_form['financeiro_valor'];
        
        $financeiro_form['financeiro_status'] = '0';
        
        unset($financeiro_form['financeiro_config']);
        unset($financeiro_form['financeiro_parcela']);
        unset($financeiro_form['financeiro_data_vencimento']);
        unset($financeiro_form['financeiro_valor']);
        
        $financeiro_form['financeiro_data_lancamento']  = date('Y-m-d');
        $financeiro_form['financeiro_md5']              = md5(date('Y-m-d').rand(9,99999999999999));
        
        for($x=0;$x<$parcela;$x++){
            $financeiro_form['financeiro_data_vencimento'] = date('Y-m-d', strtotime('+'.$x.'month', strtotime($primeira_parcela)));
            if($config == '0'){
                $financeiro_form['financeiro_valor'] = $valor;
            }else{
                $financeiro_form['financeiro_valor'] = $valor / $parcela;
            }
            
            $financeiro_form['financeiro_codigo'] = GetReg('financeiro', 'financeiro_codigo', "WHERE financeiro_tipo = '".$financeiro_form['financeiro_tipo']."'") + 1;
            Create('financeiro', $financeiro_form);
        }
        $json_financeiro = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal" onclick="limpar_campos();">Apenas Fechar</button><a href="javascript::" onclick="carrega_pagina(\'financeiro\', \'index.php?OP='.$financeiro_form['financeiro_tipo'].'\');" data-dismiss="modal" class="btn btn-primary">Sair</a>'
        );
    }
    echo json_encode($json_financeiro);
}elseif($acao == 'load_os'){
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
        $order_by = "ORDER BY os_veiculo_id DESC";
    }else{
        $sort       = addslashes($_GET['sort']);
        $sort_dir   = addslashes($_GET['sort_dir']);
        $order_by   = "ORDER BY ".$sort." ".$sort_dir."";
    }
    
    $id_os_load = addslashes($_GET['id_os']);
    
    $read_os_veiculo_paginator = ReadComposta("SELECT os_veiculo_id FROM os_veiculo WHERE os_veiculo_id != '' AND os_veiculo_id_os = '".$id_os_load."'");
    $read_os_veiculo = Read('os_veiculo', "WHERE os_veiculo_id != '' AND os_veiculo_id_os = '".$id_os_load."' ".$order_by." LIMIT $inicio,$maximo");
    if(NumQuery($read_os_veiculo) > '0'){
        //PEGA QUANTOS BOTÕES PARA PAGINAÇÃO DE RESULTADOS
        $paginas = ceil(NumQuery($read_os_veiculo_paginator) / $maximo);
        //TABULATOR EXIGE ESSA INFORMAÇÃO PARA QUE PRECISE DA PAGINAÇÃO DE RESULTADOS 
        $json_os["last_page"] = $paginas;
        foreach($read_os_veiculo as $read_os_veiculo_view){
            $json_os['data'][] = $read_os_veiculo_view;
        }
    }else{
        $json_os['data'] = null;
    }
    echo json_encode($json_os);
}elseif($acao == 'create_veiculo'){
    //RECUPERA O FORMULARIO
    $os_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($os_form['acao']);
    
    if($os_form['os_veiculo_modelo'] == ''){
        $json_os_veiculo = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        if(Create('os_veiculo', $os_form)):
			$json_os_veiculo = array(
				'type' => 'success',
				'title' => 'Parabéns:',
				'msg' => 'Operação realizada com sucesso',
				'buttons' => '<a href="javascript::" onclick="carrega_pagina(\'os\', \'vehicles.php?id='.$os_form['os_veiculo_id_os'].'\');" data-dismiss="modal" class="btn btn-primary">Sair</a>'
			);
		else:
			$json_os_veiculo = array(
				'type' => 'error',
				'title' => 'Erro:',
				'msg' => 'Ops, operação não pode ser finalizada!',
				'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
			);
		endif;
    }
    echo json_encode($json_os_veiculo);
}elseif($acao == 'load_update_veiculo'){
    $uid = addslashes($_POST['id']);
    
    $read_os = Read('os_veiculo', "WHERE os_veiculo_id = '".$uid."'");
    if(NumQuery($read_os) > '0'){
        foreach($read_os as $read_os_view);
        $json_os[] = $read_os_view;
    }else{
        $json_os = null;
    }
    echo json_encode($json_os);
}elseif($acao == 'update_veiculo'){
    //RECUPERA O FORMULARIO
    $os_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($os_form['acao']);
    
    if($os_form['os_veiculo_modelo'] == ''){
        $json_os = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        $uid = addslashes($_POST['id']);
        unset($os_form['id']);
        $uid_os = $os_form['os_veiculo_id_os'];
        unset($os_form['os_veiculo_id_os']);
        Update('os_veiculo', $os_form, "WHERE os_veiculo_id = '".$uid."'");
        $json_os = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<a href="javascript::" onclick="carrega_pagina(\'os\', \'vehicles.php?id='.$uid_os.'\');" data-dismiss="modal" class="btn btn-primary">Sair</a>'
        );
    }
    echo json_encode($json_os);
}elseif($acao == 'import_veiculo'){
	$id_aditivo = addslashes($_POST['id']);
	$id_os = addslashes($_POST['id_os']);
	
	
	$read_aditivo = Read('contrato_rastreamento_veiculo', "WHERE contrato_rastreamento_veiculo_id_aditivo = '".$id_aditivo."'");
	if(NumQuery($read_aditivo) > '0'){
		foreach($read_aditivo as $read_aditivo_view){
			$os_import['os_veiculo_id_os'] = $id_os;
			$os_import['os_veiculo_frota'] = $read_aditivo_view['contrato_rastreamento_veiculo_frota'];
			$os_import['os_veiculo_placa'] = $read_aditivo_view['contrato_rastreamento_veiculo_placa'];
			$os_import['os_veiculo_modelo'] = $read_aditivo_view['contrato_rastreamento_veiculo_modelo'];
			$os_import['os_veiculo_marca'] = $read_aditivo_view['contrato_rastreamento_veiculo_marca'];
			$os_import['os_veiculo_cor'] = $read_aditivo_view['contrato_rastreamento_veiculo_cor'];
			$os_import['os_veiculo_ano'] = $read_aditivo_view['contrato_rastreamento_veiculo_ano'];
			$os_import['os_veiculo_chassi'] = $read_aditivo_view['contrato_rastreamento_veiculo_chassi'];	
			Create('os_veiculo', $os_import);
		}
	}
}elseif($acao == 'delete_veiculo'){
    $get_id = addslashes(trim(strip_tags($_POST['id'])));
    
    Delete('os_veiculo', "WHERE os_veiculo_id = '".$get_id."'");
}
?>