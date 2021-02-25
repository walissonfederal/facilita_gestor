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
        $order_by = "ORDER BY pedido.pedido_id DESC";
    }else{
        $sort       = addslashes($_GET['sort']);
        $sort_dir   = addslashes($_GET['sort_dir']);
        $order_by   = "ORDER BY ".$sort." ".$sort_dir."";
    }
    
    //PESQUISA VIA AJAX
    if(isset($_GET['search']) && $_GET['search'] == 'true'){
        $get_id             = addslashes($_GET['id']);
        $get_id_contato     = addslashes($_GET['id_contato']);
        $get_id_plano       = addslashes($_GET['id_plano']);
        $get_id_plano_sms   = addslashes($_GET['id_plano_sms']);
        $get_tipo           = addslashes($_GET['tipo']);
        $get_tipo_frete     = addslashes($_GET['tipo_frete']);
        $get_status         = addslashes($_GET['status']);
        $get_codigo_rastreio = addslashes($_GET['codigo_rastreio']);
        
        if($get_codigo_rastreio != ''){
            $sql_codigo_rastreio = "AND pedido_codigo_rastreio LIKE '%".$get_codigo_rastreio."%'";
        }else{
            $sql_codigo_rastreio = "";
        }
        
        if($get_id != ''){
            $sql_id = "AND pedido.pedido_id = '".$get_id."'";
        }else{
            $sql_id = "";
        }
        if($get_id_contato != ''){
            $sql_id_contato = "AND pedido.pedido_id_cliente = '".$get_id_contato."'";
        }else{
            $sql_id_contato = "";
        }
        if($get_id_plano != ''){
            $sql_id_plano = "AND pedido.pedido_id_plano = '".$get_id_plano."'";
        }else{
            $sql_id_plano = "";
        }
        if($get_id_plano_sms != ''){
            $sql_id_plano_sms = "AND pedido.pedido_id_plano_sms = '".$get_id_plano_sms."'";
        }else{
            $sql_id_plano_sms = "";
        }
        if($get_tipo != ''){
            $sql_tipo = "AND pedido.pedido_tipo = '".$get_tipo."'";
        }else{
            $sql_tipo = "";
        }
        if($get_tipo_frete != ''){
            $sql_tipo_frete = "AND pedido.pedido_tipo_frete = '".$get_tipo_frete."'";
        }else{
            $sql_tipo_frete = "";
        }
        if($get_status != ''){
            $sql_status = "AND pedido.pedido_status = '".$get_status."'";
        }else{
            $sql_status = "";
        }
        
        $_SESSION['pedido_load'] = " ".$sql_id." ".$sql_id_contato." ".$sql_id_plano." ".$sql_id_plano_sms." ".$sql_tipo." ".$sql_tipo_frete." ".$sql_status." ".$sql_codigo_rastreio." ";
    }else{
        if($_SESSION['pedido_load'] == ''){
            $_SESSION['pedido_load'] = "AND pedido.pedido_id = '1'";
        }
    }
    $quantidade_pedido = '0';
    $quantidade_chip_pedido = '0';
    $quantidade_chip_pedido_bloqueado = '0';
    $quantidade_chip_pedido_cancelado = '0';
    $quantidade_chip_pedido_finalizado = '0';
    $read_pedido_paginator = ReadComposta("SELECT pedido.pedido_id FROM pedido WHERE pedido.pedido_id != '' {$_SESSION['pedido_load']}");
    if(NumQuery($read_pedido_paginator) > '0'):
        foreach($read_pedido_paginator as $read_pedido_paginator_view):
            if($read_pedido_paginator_view['pedido_status'] == '0' || $read_pedido_paginator_view['pedido_status'] == '1'):
                $quantidade_chip_pedido_finalizado += 1;
            elseif($read_pedido_paginator_view['pedido_status'] == '2'):
                $quantidade_chip_pedido_cancelado += 1;
            elseif($read_pedido_paginator_view['pedido_status'] == '3'):
                $quantidade_chip_pedido_bloqueado += 1;
            endif;
            
        endforeach;
    endif;
    //$read_pedido = Read('pedido', "WHERE pedido_id != '' {$_SESSION['pedido_load']} ".$order_by." LIMIT $inicio,$maximo");
    $read_pedido = ReadComposta("SELECT pedido_id, contato_nome_razao, pedido_data_ativacao, pedido_valor_plano_sms, pedido_valor_ativacao, pedido_status, pedido_tipo, (SELECT COUNT(*) FROM itens_pedido WHERE itens_pedido.itens_pedido_id_pedido = pedido.pedido_id) AS pedido_qtd_chips, (SELECT COUNT(*) FROM itens_pedido WHERE itens_pedido.itens_pedido_id_pedido = pedido.pedido_id) * pedido.pedido_valor_ativacao AS valor_total_ativacao FROM pedido INNER JOIN contato ON contato.contato_id = pedido.pedido_id_cliente WHERE pedido.pedido_id != '' {$_SESSION['pedido_load']} ORDER BY pedido.pedido_id DESC");
    if(NumQuery($read_pedido) > '0'){
        //PEGA QUANTOS BOTÕES PARA PAGINAÇÃO DE RESULTADOS
        $paginas = ceil(NumQuery($read_pedido_paginator) / $maximo);
        //TABULATOR EXIGE ESSA INFORMAÇÃO PARA QUE PRECISE DA PAGINAÇÃO DE RESULTADOS 
        $json_pedido["last_page"] = $paginas;
        $json_pedido["quantidade_pedido"] = NumQuery($read_pedido_paginator);
        $json_pedido["quantidade_pedido_finalizado"] = $quantidade_chip_pedido_finalizado;
        $json_pedido["quantidade_pedido_cancelado"] = $quantidade_chip_pedido_cancelado;
        $json_pedido["quantidade_pedido_bloqueado"] = $quantidade_chip_pedido_bloqueado;
        
        foreach($read_pedido as $read_pedido_view){
            $quantidade_chip_pedido += $read_pedido_view['pedido_qtd_chips'];
            if($read_pedido_view['pedido_status'] == '0'){
                $read_pedido_view['pedido_status'] = 'Em andamento';
            }elseif($read_pedido_view['pedido_status'] == '1'){
                $read_pedido_view['pedido_status'] = 'Finalizado';
            }elseif($read_pedido_view['pedido_status'] == '2'){
                $read_pedido_view['pedido_status'] = 'Cancelado';
            }elseif($read_pedido_view['pedido_status'] == '3'){
                $read_pedido_view['pedido_status'] = 'Bloqueado';
            }
            if($read_pedido_view['pedido_tipo'] == '0'){
                $read_pedido_view['pedido_tipo'] = 'Instalação';
                $read_pedido_view['pedido_tipo_type'] = '0';
            }elseif($read_pedido_view['pedido_tipo'] == '1'){
                $read_pedido_view['pedido_tipo'] = 'Desinstalação';
                $read_pedido_view['pedido_tipo_type'] = '1';
            }elseif($read_pedido_view['pedido_tipo'] == '2'){
                $read_pedido_view['pedido_tipo'] = 'SMS';
                $read_pedido_view['pedido_tipo_type'] = '2';
            }
            $json_pedido['data'][] = $read_pedido_view;
        }
        $json_pedido["quantidade_pedido_chip"] = $quantidade_chip_pedido;
    }else{
        $json_pedido['data'] = null;
    }
    echo json_encode($json_pedido);
}elseif($acao == 'create'){
    //RECUPERA O FORMULARIO
    $pedido_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($pedido_form['acao']);
    
    $valor_frete = $pedido_form['pedido_valor_frete'];
    unset($pedido_form['pedido_valor_frete']);
    $codigo_rastreio = $pedido_form['pedido_codigo_rastreio'];
    unset($pedido_form['pedido_codigo_rastreio']);
    $valor_ativacao = $pedido_form['pedido_valor_ativacao'];
    unset($pedido_form['pedido_valor_ativacao']);
    
    $read_contrato_chip_sem_contrato = ReadComposta("SELECT contrato_chip_id_contato FROM contrato_chip WHERE contrato_chip_id_contato = '".$pedido_form['pedido_id_cliente']."'");
    $read_contrato_chip_sem_contrato_assinado = ReadComposta("SELECT contrato_chip_id_contato FROM contrato_chip WHERE contrato_chip_id_contato = '".$pedido_form['pedido_id_cliente']."' AND contrato_chip_cliente_assinou = '1'");
    if(in_array('', $pedido_form)){
        $json_pedido = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }elseif(NumQuery($read_contrato_chip_sem_contrato) == '0' && GetEmpresa('empresa_pedido_realizar_sem_contrato') == '1'){
        $json_pedido = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada, cliente sem contrato!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }elseif(NumQuery($read_contrato_chip_sem_contrato_assinado) == '0' && GetEmpresa('empresa_pedido_realizar_sem_contrato_assinado') == '1'){
        $json_pedido = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada, cliente sem contrato assinado!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        $pedido_form['pedido_codigo_rastreio'] = $codigo_rastreio;
        $pedido_form['pedido_valor_ativacao'] = $valor_ativacao;
        $pedido_form['pedido_valor_frete'] = $valor_frete;
        $pedido_form['pedido_id_user'] = $_SESSION[VSESSION]['user_id'];
        if($pedido_form['search_contato'] == 'true'){
            $read_pedido_sms = ReadComposta("SELECT pedido_id FROM pedido WHERE pedido_tipo = '2' AND pedido_id_cliente = '".$pedido_form['pedido_id_cliente']."'");
            if(NumQuery($read_pedido_sms) > '0'){
                $json_pedido = array(
                    'type' => 'error',
                    'title' => 'Ocorreu um problema:',
                    'msg' => 'Operação não realizada, já existe um pedido de sms para esse cliente',
                    'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal" onclick="limpar_campos();">Apenas Fechar</button><a href="javascript::" onclick="carrega_pagina(\'pedido\', \'index.php\');" data-dismiss="modal" class="btn btn-primary">Sair</a>'
                );
            }else{
                unset($pedido_form['search_contato']);
                Create('pedido', $pedido_form);
                $json_pedido = array(
                    'type' => 'success',
                    'title' => 'Parabéns:',
                    'msg' => 'Operação realizada com sucesso',
                    'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal" onclick="limpar_campos();">Apenas Fechar</button><a href="javascript::" onclick="carrega_pagina(\'pedido\', \'index.php\');" data-dismiss="modal" class="btn btn-primary">Sair</a>'
                );
            }
        }else{
            unset($pedido_form['search_contato']);
            Create('pedido', $pedido_form);
            $pedido_form['pedido_codigo_rastreio'] = $codigo_rastreio;
            $pedido_form['pedido_valor_ativacao'] = $valor_ativacao;
            $json_pedido = array(
                'type' => 'success',
                'title' => 'Parabéns:',
                'msg' => 'Operação realizada com sucesso',
                'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal" onclick="limpar_campos();">Apenas Fechar</button><a href="javascript::" onclick="carrega_pagina(\'pedido\', \'index.php\');" data-dismiss="modal" class="btn btn-primary">Sair</a>'
            );
        }
    }
    echo json_encode($json_pedido);
}elseif($acao == 'update'){
    //RECUPERA O FORMULARIO
    $pedido_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($pedido_form['acao']);
    
    if(in_array('', $pedido_form)){
        $json_pedido = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        $uid = addslashes($_POST['id']);
        unset($pedido_form['id']);
        Update('pedido', $pedido_form, "WHERE pedido_id = '".$uid."'");
        $json_pedido = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<a href="javascript::" data-dismiss="modal" onclick="carrega_pagina(\'pedido\', \'index.php\');" class="btn btn-primary">Sair</a>'
        );
    }
    echo json_encode($json_pedido);
}elseif($acao == 'load_update'){
    $uid = addslashes($_POST['id']);
    
    $read_pedido = Read('pedido', "WHERE pedido_id = '".$uid."'");
    if(NumQuery($read_pedido) > '0'){
        foreach($read_pedido as $read_pedido_view);
        $json_pedido[] = $read_pedido_view;
    }else{
        $json_pedido = null;
    }
    echo json_encode($json_pedido);
}elseif($acao == 'load_periodo'){
    $read_periodo = Read('periodo', "ORDER BY periodo_descricao ASC");
    if(NumQuery($read_periodo) > '0'){
        foreach($read_periodo as $read_periodo_view){
            $json_periodo["data"][] = $read_periodo_view;
        }
        echo json_encode($json_periodo);
    }
}elseif($acao == 'gerar_excel'){
    $arquivo = 'cargo.xls';
    $tabela = '<table border="1" width="800px">';
        $tabela .= '<tr>';
            $tabela .= '<td colspan="3" align="center">Relação de cargos</tr>';
        $tabela .= '</tr>';
        $tabela .= '<tr>';
            $tabela .= '<td><b>Código</b></td>';
            $tabela .= '<td><b>Descrição</b></td>';
            $tabela .= '<td><b>Data Inicial</b></td>';
            $tabela .= '<td><b>Data Final</b></td>';
        $tabela .= '</tr>';
    
    $read_periodo = Read('periodo', "WHERE periodo_id != '' {$_SESSION['periodo_load']} ORDER BY periodo_descricao ASC");
    if(NumQuery($read_periodo) > '0'){
        foreach($read_periodo as $read_periodo_view){
            $tabela .= '<tr>';
                $tabela .= '<td>'.$read_periodo_view['periodo_id'].'</td>';
                $tabela .= '<td>'.$read_periodo_view['periodo_descricao'].'</td>';
                $tabela .= '<td>'.$read_periodo_view['periodo_data_inicial'].'</td>';
                $tabela .= '<td>'.$read_periodo_view['periodo_data_final'].'</td>';
            $tabela .= '</tr>';
        }
    }
    
    header("Content-type: application/vnd.ms-excel");  
    header("Content-type: application/force-download"); 
    header("Content-Disposition: attachment; filename=file.xls");
    header("Pragma: no-cache");
    echo $tabela;
}elseif($acao == 'load_plano'){
    $read_plano = Read('plano', "WHERE plano_tipo = '0' ORDER BY plano_descricao ASC");
    if(NumQuery($read_plano) > '0'){
        foreach($read_plano as $read_plano_view){
            $json_plano["data"][] = $read_plano_view;
        }
        echo json_encode($json_plano);
    }
}elseif($acao == 'load_plano_sms'){
    $read_plano = Read('plano', "WHERE plano_tipo = '1' ORDER BY plano_descricao ASC");
    if(NumQuery($read_plano) > '0'){
        foreach($read_plano as $read_plano_view){
            $json_plano["data"][] = $read_plano_view;
        }
        echo json_encode($json_plano);
    }
}elseif($acao == 'search_valor_plano'){
    $get_id = addslashes($_GET['valor_plano']);
    $read_plano = Read('plano', "WHERE plano_id = '".$get_id."' ORDER BY plano_descricao ASC");
    if(NumQuery($read_plano) > '0'){
        foreach($read_plano as $read_plano_view){
            echo $read_plano_view['plano_valor'];
        }
    }
}elseif($acao == 'search_valor_plano_sms'){
    $get_id = addslashes($_GET['valor_plano_sms']);
    $read_plano = Read('plano', "WHERE plano_id = '".$get_id."' ORDER BY plano_descricao ASC");
    if(NumQuery($read_plano) > '0'){
        foreach($read_plano as $read_plano_view){
            echo $read_plano_view['plano_valor'];
        }
    }
}elseif($acao == 'verificar_chip'){
    unset($_SESSION['pedido_session']);
    if(!isset($_SESSION['pedido_session'])){
        $_SESSION['pedido_session'] = array();
    }
    $array_chip = addslashes($_POST['arquivo_txt']);
    $explode_chip_completo = explode( PHP_EOL, $array_chip );
    $count_explode = count($explode_chip_completo);
    for($x=0;$x<$count_explode;$x++){
        $read_chip = ReadComposta("SELECT chip_id, chip_num, chip_iccid FROM chip WHERE chip_iccid = '".trim($explode_chip_completo[$x])."'");
        if(NumQuery($read_chip) > '0'){
            foreach($read_chip as $read_chip_view);
            $_SESSION['pedido_session'][$read_chip_view['chip_id']] = $explode_chip_completo[$x];
        }else{
            $md5 = md5(rand(9,9999999999999999999999999999));
            $_SESSION['pedido_session'][$md5] = $explode_chip_completo[0];
        }
    }
    if(count($_SESSION['pedido_session']) > '0'){
        echo '<table class="table">';
            echo '<thead>';
                echo '<tr>';
					echo '<th>#</th>';
					echo '<th>Tipo</th>';
                    echo '<th>Número</th>';
                    echo '<th>ICCID</th>';
                    echo '<th>Informação</th>';
                    echo '<th>ações</th>';
                echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
			$count_chip = '0';
        foreach($_SESSION['pedido_session'] as $id => $iccid){
            if(is_numeric($id)){
                $read_chip_is_numeric = ReadComposta("SELECT chip_num, chip_id, chip_iccid, chip_plano FROM chip WHERE chip_id = '".$id."' AND chip_status = '0'");
                if(NumQuery($read_chip_is_numeric) > '0'){
                    foreach($read_chip_is_numeric as $read_chip_is_numeric_view);
					$style = '';
					$tipo_chip = $read_chip_is_numeric_view['chip_plano'];
                    $numero_chip = $read_chip_is_numeric_view['chip_num'];
                    $iccid_chip = $read_chip_is_numeric_view['chip_iccid'];
                    $status_chip = 'Liberado para uso';
                    $linha_tempo_chip = '<a href="Home.php?model=pedido&pg=linha_tempo_chip&id_chip='.$read_chip_is_numeric_view['chip_id'].'" target="_blank">Ver Linha do tempo</a>';
                }else{
					$tipo_chip = '';
					$style = '#069';
                    $numero_chip = 'Não localizado';
                    $iccid_chip = $iccid;
                    $status_chip = 'Já em uso';
                    $linha_tempo_chip = '<a href="Home.php?model=pedido&pg=linha_tempo_chip&id_chip='.$id.'" target="_blank">Ver Linha do tempo</a>';
                }
            }else{
				$tipo_chip = '';
                $numero_chip = 'Inválido';
                $iccid_chip = 'Não localizado';
                $status_chip = 'Não liberado';
                $linha_tempo_chip = '';
            }
			$count_chip++;
            echo '<tr>';
				echo '<td bgcolor="'.$style.'">'.$count_chip.'</td>';
                echo '<td>'.$tipo_chip.'</td>';
				echo '<td>'.$numero_chip.'</td>';
                echo '<td>'.$iccid_chip.'</td>';
                echo '<td>'.$status_chip.'</td>';
                echo '<td>'.$linha_tempo_chip.'</td>';
            echo '</tr>';
                
        }
            echo '</tbody>';
        echo '</table>';
        echo '<button type="button" class="btn btn-primary" onclick="create_chip();">Gravar Chips</button>';
    }
}elseif($acao == 'load_chip_pedido'){
    $get_id_pedido = addslashes($_POST['id_pedido']);
    
    $read_itens_pedido = ReadComposta("SELECT * FROM itens_pedido INNER JOIN chip ON chip.chip_id = itens_pedido.itens_pedido_id_chip WHERE itens_pedido_id_pedido = '".$get_id_pedido."'");
    if(NumQuery($read_itens_pedido) > '0'){
        echo '<table class="table">';
            echo '<thead>';
                echo '<tr>';
                    echo '<th>#</th>';
                    echo '<th>Número</th>';
                    echo '<th>ICCID</th>';
                    echo '<th>Data Final Desconto</th>';
                    echo '<th>Desconto (%)</th>';
                    echo '<th colspan="2">ações</th>';
                echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            $count_itens_pedidos = '0';
        foreach($read_itens_pedido as $read_itens_pedido_view){
            $count_itens_pedidos++;
			$iccid_new_page .= $read_itens_pedido_view['chip_iccid'].'.<br />';
            echo '<tr>';
                echo '<td>'.$count_itens_pedidos.'</td>';
                echo '<td>'.$read_itens_pedido_view['chip_num'].'</td>';
                echo '<td>'.$read_itens_pedido_view['chip_iccid'].'</td>';
                echo '<td>'.$read_itens_pedido_view['itens_pedido_data_final'].'</td>';
                echo '<td>'.$read_itens_pedido_view['itens_pedido_desconto'].'</td>';
                echo '<td><a href="#" onclick="delete_chip_pedido('.$read_itens_pedido_view['itens_pedido_id'].', '.$read_itens_pedido_view['chip_id'].');">Deletar</a></td>';
                echo '<td><a href="#" onclick="update_chip_pedido('.$read_itens_pedido_view['itens_pedido_id'].', '.$read_itens_pedido_view['chip_id'].');">Editar</a></td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
        $_SESSION['chip_iccid_pedido_load_chip'] = $iccid_new_page;
		echo '<a href="view/pedido/load_iccid.php" target="_blank" class="btn btn-primary">Mostrar ICCID</a>';
    }
}elseif($acao == 'update_chip_pedido'){
    $id_chip_pedido = addslashes($_POST['id_chip_pedido']);
    $id_chip = addslashes($_POST['id_chip']);
	
    if(GetEmpresa('empresa_pedido_update_chip') == '0'){
        $json_pedido = array(
            'type' => 'info',
            'title' => 'Informação:',
            'msg' => '<div class="row">
                        <div class="form-group col-lg-12">
                            <label>Senha</label>
                            <input type="password" class="form-control senha_delete_chip"/>
                        </div>
                        <div class="form-group col-lg-12">
                            <label>Data Final Desconto</label>
                            <input type="date" class="form-control data_final"/>
                        </div>
                        <div class="form-group col-lg-12">
                            <label>Desconto (%)</label>
                            <input type="text" placeholder="10.00" class="form-control desconto"/>
                        </div>
                    </div>',
            'buttons' => '<button type="button" class="btn btn-primary" onclick="update_chip_pedido_ok('.$id_chip_pedido.', '.$id_chip.');">Realizar Operação</button>'
        );
    }else{
        $json_pedido = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'id_pedido' => $id_chip_pedido,
            'id_chip' => $id_chip,
            'msg' => '<div class="row">
                        <div class="form-group col-lg-12">
                            <label>Data Final Desconto</label>
                            <input type="date" class="form-control data_final"/>
                        </div>
                        <div class="form-group col-lg-12">
                            <label>Desconto (%)</label>
                            <input type="text" placeholder="10.00" class="form-control desconto"/>
                        </div>
                    </div>',
            'buttons' => '<button type="button" class="btn btn-primary" data-dismiss="modal" onclick="update_chip_pedido_ok('.$id_chip_pedido.', '.$id_chip.');">Realizar Operação</button>'
        );
    }
    echo json_encode($json_pedido);
}elseif($acao == 'update_chip_pedido_ok'){
    $id_chip_pedido = addslashes($_POST['id_chip_pedido']);
    $id_chip = addslashes($_POST['id_chip']);
    $senha_chip = addslashes($_POST['senha_chip']);
    $data_final = addslashes($_POST['data_final']);
    $desconto = addslashes($_POST['desconto']);
            
    
    if(GetEmpresa('empresa_pedido_update_chip') == '0'){
        if($senha_chip == GetEmpresa('empresa_pedido_update_chip_senha')){
            $id_pedido = GetDados('itens_pedido', $id_chip_pedido, 'itens_pedido_id', 'itens_pedido_id_pedido');
            $id_contato = GetDados('pedido', $id_pedido, 'pedido_id', 'pedido_id_cliente');
            linha_tempo_chip($id_chip, 'Editou chip', 'Editou chip do pedido #'.$id_pedido.' com autorização feita por senha, mudando data final e/ou desconto', $id_contato);
            $chip_pedido_update['itens_pedido_data_final'] = $data_final;
            $chip_pedido_update['itens_pedido_desconto'] = $desconto;
            Update('itens_pedido', $chip_pedido_update, "WHERE itens_pedido_id = '".$id_chip_pedido."'");
            $json_pedido = array(
                'type' => 'success',
                'title' => 'Parabéns:',
                'msg' => 'Parabéns, operação realizada com sucesso!',
                'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
            );
        }else{
            $json_pedido = array(
                'type' => 'error',
                'title' => 'Erro:',
                'msg' => 'Ops, operação não pode ser finalizada devido a senha ser inválida!',
                'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
            );
        }
    }else{
        $id_pedido = GetDados('itens_pedido', $id_chip_pedido, 'itens_pedido_id', 'itens_pedido_id_pedido');
        $id_contato = GetDados('pedido', $id_pedido, 'pedido_id', 'pedido_id_cliente');
        linha_tempo_chip($id_chip, 'Editou chip', 'Editou chip do pedido #'.$id_pedido.' sem autorização feita por senha mudando a data final e/ou desconto', $id_contato);
        $chip_pedido_update['itens_pedido_data_final'] = $data_final;
        $chip_pedido_update['itens_pedido_desconto'] = $desconto;
        Update('itens_pedido', $chip_pedido_update, "WHERE itens_pedido_id = '".$id_chip_pedido."'");
        $json_pedido = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Parabéns, operação realizada com sucesso!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }
    echo json_encode($json_pedido);
}elseif($acao == 'delete_chip_pedido'){
    $id_chip_pedido = addslashes($_POST['id_chip_pedido']);
    $id_chip = addslashes($_POST['id_chip']);
    if(GetEmpresa('empresa_pedido_deletar_chip') == '0'){
        $json_pedido = array(
            'type' => 'info',
            'title' => 'Informação:',
            'msg' => '<div class="row">
                        <div class="form-group col-lg-12">
                            <label>Senha</label>
                            <input type="password" class="form-control senha_delete_chip"/>
                        </div>
                    </div>',
            'buttons' => '<button type="button" class="btn btn-primary" onclick="delete_chip_pedido_ok('.$id_chip_pedido.', '.$id_chip.');">Realizar Operação</button>'
        );
    }else{
        $json_pedido = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'id_pedido' => $id_chip_pedido,
            'id_chip' => $id_chip,
            'buttons' => '<button type="button" class="btn btn-primary" data-dismiss="modal" onclick="delete_chip_pedido_ok('.$id_chip_pedido.', '.$id_chip.');">Realizar Operação</button>'
        );
    }
    echo json_encode($json_pedido);
}elseif($acao == 'delete_chip_pedido_ok'){
    $id_chip_pedido = addslashes($_POST['id_chip_pedido']);
    $id_chip = addslashes($_POST['id_chip']);
    $senha_chip = addslashes($_POST['senha_chip']);
    
    if(GetEmpresa('empresa_pedido_deletar_chip') == '0'){
        if($senha_chip == GetEmpresa('empresa_pedido_deletar_chip_senha')){
            $id_pedido = GetDados('itens_pedido', $id_chip_pedido, 'itens_pedido_id', 'itens_pedido_id_pedido');
            $id_contato = GetDados('pedido', $id_pedido, 'pedido_id', 'pedido_id_cliente');
            linha_tempo_chip($id_chip, 'Deletou chip', 'Deletou chip do pedido #'.$id_pedido.' com autorização feita por senha', $id_contato);
            Delete('itens_pedido', "WHERE itens_pedido_id = '".$id_chip_pedido."'");
            $up_chip_pedido['chip_status'] = '0';
            Update('chip', $up_chip_pedido, "WHERE chip_id = '".$id_chip."'");
            $json_pedido = array(
                'type' => 'success',
                'title' => 'Parabéns:',
                'msg' => 'Parabéns, operação realizada com sucesso!',
                'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
            );
        }else{
            $json_pedido = array(
                'type' => 'error',
                'title' => 'Erro:',
                'msg' => 'Ops, operação não pode ser finalizada devido a senha ser inválida!',
                'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
            );
        }
    }else{
        $id_pedido = GetDados('itens_pedido', $id_chip_pedido, 'itens_pedido_id', 'itens_pedido_id_pedido');
        $id_contato = GetDados('pedido', $id_pedido, 'pedido_id', 'pedido_id_cliente');
        linha_tempo_chip($id_chip, 'Deletou chip', 'Deletou chip do pedido #'.$id_pedido.' sem autorização feita por senha', $id_contato);
        Delete('itens_pedido', "WHERE itens_pedido_id = '".$id_chip_pedido."'");
        $up_chip_pedido['chip_status'] = '0';
        Update('chip', $up_chip_pedido, "WHERE chip_id = '".$id_chip."'");
        $json_pedido = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Parabéns, operação realizada com sucesso!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }
    echo json_encode($json_pedido);
}elseif($acao == 'create_chip'){
    $chips_nao_inseridos = null;
    $id_pedido = addslashes($_POST['id_pedido']);
    if(count($_SESSION['pedido_session']) > '0'){
        foreach($_SESSION['pedido_session'] as $id_chip => $num_chip){
            $read_chip_fim_pedido = ReadComposta("SELECT chip_id, chip_num, chip_iccid FROM chip WHERE chip_id = '".$id_chip."' AND chip_status = '0'");
            if(NumQuery($read_chip_fim_pedido) > '0'){
                foreach($read_chip_fim_pedido as $read_chip_fim_pedido_view);
                $itens_pedido_form['itens_pedido_id_pedido'] = $id_pedido;
                $itens_pedido_form['itens_pedido_id_chip'] = $read_chip_fim_pedido_view['chip_id'];
                $itens_pedido_form['itens_pedido_num_chip'] = $read_chip_fim_pedido_view['chip_num'];
                $itens_pedido_form['itens_pedido_iccid'] = $read_chip_fim_pedido_view['chip_iccid'];
                Create('itens_pedido', $itens_pedido_form);
                $up_chip_insert['chip_status'] = '1';
                Update('chip', $up_chip_insert, "WHERE chip_id = '".$read_chip_fim_pedido_view['chip_id']."'");
                $id_contato = GetDados('pedido', $id_pedido, 'pedido_id', 'pedido_id_cliente');
                linha_tempo_chip($read_chip_fim_pedido_view['chip_id'], 'Inseriu chip', 'Inseriu chip do pedido #'.$id_pedido.' feita em massa', $id_contato);
            }else{
                $chips_nao_inseridos[] = $num_chip;
            }
        }
    }
    if(count($chips_nao_inseridos) > '0'){
        echo 'Chips não inseridos';
        echo '<ul>';
        foreach($chips_nao_inseridos as $cod => $num){
            echo '<li>'.$num.'</li>';
        }
        echo '</ul>';
    }
    echo '<p>Operação realizada!</p>';
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
}elseif($acao == 'load_chip_insert_info'){
    $term = addslashes($_GET['term']);
    
    $read_chip_load = Read('chip', "WHERE ((chip_num LIKE '%".$term."%') OR (chip_iccid LIKE '%".$term."%')) ORDER BY chip_id ASC LIMIT 10");
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
}elseif($acao == 'create_chip_pedido'){
    $id_pedido = addslashes($_POST['id_pedido']);
    $id_chip   = addslashes($_POST['id_chip']);
    
    $read_chip = ReadComposta("SELECT chip_id, chip_num, chip_iccid FROM chip WHERE chip_id = '".$id_chip."' AND chip_status = '0'");
    if(NumQuery($read_chip) > '0'){
        foreach($read_chip as $read_chip_view);
        $itens_pedido_form['itens_pedido_id_pedido'] = $id_pedido;
        $itens_pedido_form['itens_pedido_id_chip'] = $read_chip_view['chip_id'];
        $itens_pedido_form['itens_pedido_num_chip'] = $read_chip_view['chip_num'];
        $itens_pedido_form['itens_pedido_iccid'] = $read_chip_view['itens_pedido_iccid'];
        Create('itens_pedido', $itens_pedido_form);
        $update_chip['chip_status'] = '1';
        Update('chip', $update_chip, "WHERE chip_id = '".$id_chip."'");
        $id_contato = GetDados('pedido', $id_pedido, 'pedido_id', 'pedido_id_cliente');
        linha_tempo_chip($read_chip_view['chip_id'], 'Inseriu chip', 'Inseriu chip do pedido #'.$id_pedido.' feita de forma avulsa', $id_contato);
        $json_pedido = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Parabéns, tudo ok!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        $json_pedido = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido ao chip já está disponível!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }
    echo json_encode($json_pedido);
}
?>