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
        $order_by = "ORDER BY caixa_id DESC";
    }else{
        
        $sort       = addslashes($_GET['sort']);
        $sort_dir   = addslashes($_GET['sort_dir']);
        if($sort == 'caixa_status_view'){
            $order_by   = "ORDER BY caixa_id ".$sort_dir."";
        }else{
            $order_by   = "ORDER BY ".$sort." ".$sort_dir."";
        }
    }
    
    //PESQUISA VIA AJAX
    if(isset($_GET['search']) && $_GET['search'] == 'true'){
        $get_faturamento_id             = addslashes($_GET['id']);
        $get_faturamento_id_contato     = addslashes($_GET['id_contato']);
        $get_faturamento_referencia     = addslashes($_GET['referencia']);
        
        if($get_faturamento_id != ''){
            $sql_faturamento_id = "AND faturamento.faturamento_id = '".$get_faturamento_id."'";
        }else{
            $sql_faturamento_id = "";
        }
        if($get_faturamento_id_contato != ''){
            $sql_faturamento_id_contato = "AND faturamento.faturamento_id_contato = '".$get_faturamento_id_contato."'";
        }else{
            $sql_faturamento_id_contato = "";
        }
        if($get_faturamento_referencia != ''){
            $sql_faturamento_referencia = "AND faturamento.faturamento_referencia = '".$get_faturamento_referencia."'";
        }else{
            $sql_faturamento_referencia = "";
        }
        
        $_SESSION['faturamento_load'] = "".$sql_faturamento_id." ".$sql_faturamento_id_contato." ".$sql_faturamento_referencia." ";
    }
    
    $read_faturamento_paginator = ReadComposta("SELECT faturamento_id FROM faturamento WHERE faturamento_id != '' {$_SESSION['faturamento_load']}");
    $read_faturamento = ReadComposta("SELECT faturamento_id, faturamento_referencia, contato_id, contato_nome_fantasia  FROM faturamento INNER JOIN contato ON contato.contato_id = faturamento.faturamento_id_contato WHERE faturamento.faturamento_id != '' {$_SESSION['faturamento_load']} ORDER BY faturamento.faturamento_id DESC LIMIT $inicio,$maximo");
    if(NumQuery($read_faturamento) > '0'){
        //PEGA QUANTOS BOTÕES PARA PAGINAÇÃO DE RESULTADOS
        $paginas = ceil(NumQuery($read_faturamento_paginator) / $maximo);
        //TABULATOR EXIGE ESSA INFORMAÇÃO PARA QUE PRECISE DA PAGINAÇÃO DE RESULTADOS 
        $json_faturamento["last_page"] = $paginas;
        foreach($read_faturamento as $read_faturamento_view){
            $json_faturamento['data'][] = $read_faturamento_view;
        }
    }else{
        $json_faturamento['data'] = null;
    }
    echo json_encode($json_faturamento);
}elseif($acao == 'create'){
    //RECUPERA O FORMULARIO
    $faturamento_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($faturamento_form['acao']);
    $verificacao_faturamento = $faturamento_form['verificacao'];
    unset($faturamento_form['verificacao']);
    
    $id_contato = $faturamento_form['id_contato'];
    unset($faturamento_form['id_contato']);
    
    $read_faturamento_verificacao = ReadComposta("SELECT faturamento_id FROM faturamento WHERE faturamento_referencia = '".$faturamento_form['faturamento_referencia']."'");
    
    if(in_array('', $faturamento_form)){
        $json_faturamento = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }elseif(NumQuery($read_faturamento_verificacao) > '0' && $verificacao_faturamento == '0'){
        $json_faturamento = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, já existe um faturamento para essa referência!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button><button type="button" class="btn btn-primary" data-dismiss="modal" onclick="create_faturamento_update();">Quero sobrescrever</button>'
        );
    }else{
        $financeiro_form['financeiro_descricao'] = $faturamento_form['financeiro_descricao'];
        $financeiro_form['financeiro_id_plano_conta'] = $faturamento_form['financeiro_id_plano_conta'];
        $financeiro_form['financeiro_id_tipo_documento'] = $faturamento_form['financeiro_id_tipo_documento'];
        $financeiro_form['financeiro_data_vencimento'] = $faturamento_form['financeiro_data_vencimento'];
        unset($faturamento_form['financeiro_descricao']);
        unset($faturamento_form['financeiro_id_plano_conta']);
        unset($faturamento_form['financeiro_id_tipo_documento']);
        unset($faturamento_form['financeiro_data_vencimento']);
        
        $inicio  = (int)$faturamento_form['inicio'];
        //$fim     = (int)$faturamento_form['fim'];
        //$read_contato = ReadComposta("SELECT contato_id FROM contato WHERE contato_id = '1620' ORDER BY contato_id ASC");
	//$read_contato = ReadComposta("SELECT * FROM pedido WHERE pedido_status IN(0,1) GROUP BY pedido_id_cliente");
        if($id_contato != ''){
            $sql_id_contato_faturamento = "AND pedido_id_cliente = '".$id_contato."'";
            $sql_id_faturamento_contato = "AND pedido.pedido_id_cliente = '".$id_contato."'";
            //$sql_id_contato_faturamento = "AND pedido.pedido_id_cliente NOT IN(1620,1507,1899,65,1135)";
            //$sql_id_faturamento_contato = "AND pedido.pedido_id_cliente NOT IN(1620,1507,1899,65,1135)";
        }else{
            $sql_id_contato_faturamento = "";
            $sql_id_faturamento_contato = "";
        }
        $read_contato = ReadComposta("SELECT * FROM pedido WHERE pedido_status IN(0,1) {$sql_id_contato_faturamento}  GROUP BY pedido_id_cliente");
        $fim = NumQuery($read_contato);
        $calc_inicio = $inicio + 1;
        $calc_perc = round(($calc_inicio * 100) / $fim,0);
        if($inicio == '0'){
            $calc_inicio_completo = '0';
        }else{
            $calc_inicio_completo = $inicio;
        }
        $read_contato_list = ReadComposta("SELECT contato.contato_nome_razao, contato.contato_id, pedido.pedido_id FROM pedido INNER JOIN contato ON contato.contato_id = pedido.pedido_id_cliente WHERE pedido.pedido_status IN(0,1) {$sql_id_faturamento_contato}  GROUP BY pedido.pedido_id_cliente LIMIT $calc_inicio_completo,1");
        //$read_contato_list = ReadComposta("SELECT contato.contato_nome_razao, contato.contato_id, pedido.pedido_id FROM pedido INNER JOIN contato ON contato.contato_id = pedido.pedido_id_cliente WHERE pedido.pedido_status IN(0,1) GROUP BY pedido.pedido_id_cliente LIMIT $calc_inicio_completo,1");
        //$read_contato_list = ReadComposta("SELECT contato_nome_razao, contato_id FROM contato WHERE contato_id IN() ORDER BY contato_id ASC LIMIT $calc_inicio_completo,1");
        if(NumQuery($read_contato_list) > '0'){
            foreach($read_contato_list as $read_contato_list_view);
        }
        if($verificacao_faturamento == '1'){
            $read_faturamento_delete = ReadComposta("SELECT faturamento_id FROM faturamento WHERE faturamento_id_contato = '".$read_contato_list_view['contato_id']."' AND faturamento_referencia = '".$faturamento_form['faturamento_referencia']."'");
            if(NumQuery($read_faturamento_delete) > '0'){
                foreach($read_faturamento_delete as $read_faturamento_delete_view);
                Delete('faturamento', "WHERE faturamento_id = '".$read_faturamento_delete_view['faturamento_id']."'");
                Delete('itens_faturamento', "WHERE itens_faturamento_id_faturamento = '".$read_faturamento_delete_view['faturamento_id']."'");
            }
        }
        if($inicio == $fim){
            $json_faturamento = array(
                'type' => 'success',
                'title' => 'Parabéns:',
                'msg' => 'Operação realizada com sucesso',
                'fim' => 'sim',
                'perc' => '100',
                'verificacao' => '1',
                'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
            );
        }else{
            //INICIO CRIAÇÃO FATURAMENTO
            $faturamento_create['faturamento_data']         = $faturamento_form['faturamento_data'];
            $faturamento_create['faturamento_id_contato']   = $read_contato_list_view['contato_id'];
            $faturamento_create['faturamento_data_hora']    = date('Y-m-d H:i:s');
            $faturamento_create['faturamento_referencia']   = $faturamento_form['faturamento_referencia'];
            Create('faturamento', $faturamento_create);
            //FIM FATURAMENTO
            //READ DO ULTIMO FATURAMENTO CRIADO
            $read_ultimo_faturamento = ReadComposta("SELECT faturamento_id, faturamento_data, faturamento_id_contato, faturamento_referencia FROM faturamento ORDER BY faturamento_id DESC LIMIT 1");
            if(NumQuery($read_ultimo_faturamento) > '0'){
                foreach($read_ultimo_faturamento as $read_ultimo_faturamento_view);
            }
            
            //FIM READ ULTIMO FATURAMENTO CRIADO
            $valor_total_correios = '0';
			$read_pedido_bloqueado = Read('pedido', "WHERE pedido_id_cliente = '".$read_contato_list_view['contato_id']."' AND pedido_data_ativacao BETWEEN '2010-01-01' AND '".$faturamento_form['faturamento_data']."' AND pedido_status IN(3) ORDER BY pedido_id ASC");
            if(NumQuery($read_pedido_bloqueado) > '0'){
                foreach($read_pedido_bloqueado as $read_pedido_bloqueado_view){
					//COBRAR A CORREIOS
					$explode_referencia = explode('/', $faturamento_form['faturamento_referencia']);
					$referencia_ativacao = $explode_referencia['1'].'-'.$explode_referencia['0'];
					$explode_data_ativacao = explode('-', $read_pedido_view['pedido_data_ativacao']);
					$data_ativacao = $explode_data_ativacao['0'].'-'.$explode_data_ativacao['1'];
					$data_ativacao_correta = date('Y-m', strtotime('-1 month', strtotime($explode_data_ativacao['0'].'-'.$explode_data_ativacao['1'].'-01')));
					if($referencia_ativacao == $data_ativacao){
						$itens_faturamento_create_correios['itens_faturamento_id_faturamento']   = $read_ultimo_faturamento_view['faturamento_id'];
						$itens_faturamento_create_correios['itens_faturamento_id_chip']          = '0';
						$itens_faturamento_create_correios['itens_faturamento_id_pedido']        = $read_pedido_view['pedido_id'];
						$itens_faturamento_create_correios['itens_faturamento_tipo']             = '3';
						$itens_faturamento_create_correios['itens_faturamento_descricao']        = 'CORREIOS';
						$itens_faturamento_create_correios['itens_faturamento_valor_cobrado']    = $read_pedido_view['pedido_valor_frete'];
						Create('itens_faturamento', $itens_faturamento_create_correios);
					}else{
						$valor_total_correios += '0';
					}
					//FIM COBRAR A CORREIOS
				}
			}
            $read_pedido = Read('pedido', "WHERE pedido_id_cliente = '".$read_contato_list_view['contato_id']."' AND pedido_data_ativacao BETWEEN '2010-01-01' AND '".$faturamento_form['faturamento_data']."' AND pedido_status IN(0,1) ORDER BY pedido_id ASC");
            if(NumQuery($read_pedido) > '0'){
                foreach($read_pedido as $read_pedido_view){
                    $dias_prazo_ativacao = DiferencaDias(FormDataBr($read_pedido_view['pedido_data_ativacao']), FormDataBr($faturamento_form['faturamento_data']));
                    if($read_pedido_view['pedido_tipo'] == '0'){
                        //COBRAR A CORREIOS
                        $explode_referencia = explode('/', $faturamento_form['faturamento_referencia']);
                        $referencia_ativacao = $explode_referencia['1'].'-'.$explode_referencia['0'];
                        $explode_data_ativacao = explode('-', $read_pedido_view['pedido_data_ativacao']);
                        $data_ativacao = $explode_data_ativacao['0'].'-'.$explode_data_ativacao['1'];
                        $data_ativacao_correta = date('Y-m', strtotime('-1 month', strtotime($explode_data_ativacao['0'].'-'.$explode_data_ativacao['1'].'-01')));
                        if($referencia_ativacao == $data_ativacao){
                            $itens_faturamento_create_correios['itens_faturamento_id_faturamento']   = $read_ultimo_faturamento_view['faturamento_id'];
                            $itens_faturamento_create_correios['itens_faturamento_id_chip']          = '0';
                            $itens_faturamento_create_correios['itens_faturamento_id_pedido']        = $read_pedido_view['pedido_id'];
                            $itens_faturamento_create_correios['itens_faturamento_tipo']             = '3';
                            $itens_faturamento_create_correios['itens_faturamento_descricao']        = 'CORREIOS';
                            $itens_faturamento_create_correios['itens_faturamento_valor_cobrado']    = $read_pedido_view['pedido_valor_frete'];
                            Create('itens_faturamento', $itens_faturamento_create_correios);
                        }else{
                            $valor_total_correios += '0';
                        }
                        //FIM COBRAR A CORREIOS
                        $read_itens_pedido = Read('itens_pedido', "WHERE itens_pedido_id_pedido = '".$read_pedido_view['pedido_id']."'");
                        if(NumQuery($read_itens_pedido) > '0'){
                            foreach($read_itens_pedido as $read_itens_pedido_view){
                                //COBRAR A ATIVAÇÃO
                                $explode_referencia = explode('/', $faturamento_form['faturamento_referencia']);
                                $referencia_ativacao = $explode_referencia['1'].'-'.$explode_referencia['0'];
                                $explode_data_ativacao = explode('-', $read_pedido_view['pedido_data_ativacao']);
                                $data_ativacao = $explode_data_ativacao['0'].'-'.$explode_data_ativacao['1'];
                                $data_ativacao_correta = date('Y-m', strtotime('-1 month', strtotime($explode_data_ativacao['0'].'-'.$explode_data_ativacao['1'].'-01')));
                                if($referencia_ativacao == $data_ativacao){
                                    $itens_faturamento_create['itens_faturamento_valor_ativacao'] = $read_pedido_view['pedido_valor_ativacao'];
                                }else{
                                    $itens_faturamento_create['itens_faturamento_valor_ativacao'] = '0';
                                    $valor_total_correios += '0';
                                }
                                //FIM COBRAR A ATIVAÇÃO
                                
                                $itens_faturamento_create['itens_faturamento_id_faturamento']   = $read_ultimo_faturamento_view['faturamento_id'];
                                $itens_faturamento_create['itens_faturamento_id_chip']          = $read_itens_pedido_view['itens_pedido_id_chip'];
                                $itens_faturamento_create['itens_faturamento_id_pedido']        = $read_pedido_view['pedido_id'];
                                $itens_faturamento_create['itens_faturamento_tipo']             = '0';
                                if($dias_prazo_ativacao < '30'){
                                    $valor_cobrado_faturamento = ($read_pedido_view['pedido_valor_plano'] / 30) * $dias_prazo_ativacao;
                                    $itens_faturamento_create['itens_faturamento_descricao']     = 'FATURA PROPORCIONAL';
                                }else{
                                    $valor_cobrado_faturamento = $read_pedido_view['pedido_valor_plano'];
                                    $itens_faturamento_create['itens_faturamento_descricao']     = 'FATURA';
                                }
                                if($read_itens_pedido_view['itens_pedido_data_final'] > date('Y-m-d')){
                                    $itens_faturamento_create['itens_faturamento_valor_cobrado'] = $valor_cobrado_faturamento - (($read_itens_pedido_view['itens_pedido_desconto'] / 100) * $valor_cobrado_faturamento);
                                }else{
                                    $itens_faturamento_create['itens_faturamento_valor_cobrado'] = $valor_cobrado_faturamento;
                                }
								$read_itens_faturamento_ja_existe = ReadComposta("SELECT itens_faturamento_id FROM itens_faturamento WHERE itens_faturamento_id_chip = '".$read_itens_pedido_view['itens_pedido_id_chip']."' AND itens_faturamento_id_faturamento = '".$read_ultimo_faturamento_view['faturamento_id']."'");
								if(NumQuery($read_itens_faturamento_ja_existe) == '0'){
									Create('itens_faturamento', $itens_faturamento_create);
								}
                            }
                        }
                    }elseif($read_pedido_view['pedido_tipo'] == '1'){
                        $retorno_ciclo = return_ciclo_chip($read_pedido_view['pedido_data_ativacao'], $faturamento_form['faturamento_data'], $faturamento_form['faturamento_referencia']);
                        $read_itens_pedido = Read('itens_pedido', "WHERE itens_pedido_id_pedido = '".$read_pedido_view['pedido_id']."'");
                        if(NumQuery($read_itens_pedido) > '0'){
                            foreach($read_itens_pedido as $read_itens_pedido_view){
                                $read_itens_pedido_verificacao = ReadComposta("SELECT itens_faturamento_id FROM itens_faturamento WHERE itens_faturamento_id_chip = '".$read_itens_pedido_view['itens_pedido_id_chip']."' AND itens_faturamento_id_faturamento = '".$read_ultimo_faturamento_view['faturamento_id']."'");
                                if(NumQuery($read_itens_pedido_verificacao) > '0'){
                                    if($retorno_ciclo == '1'){
                                        $itens_faturamento_create['itens_faturamento_id_faturamento']   = $read_ultimo_faturamento_view['faturamento_id'];
                                        $itens_faturamento_create['itens_faturamento_id_chip']          = $read_itens_pedido_view['itens_pedido_id_chip'];
                                        $itens_faturamento_create['itens_faturamento_id_pedido']        = $read_pedido_view['pedido_id'];
                                        $itens_faturamento_create['itens_faturamento_tipo']             = '1';
                                        $valor_cobrado_faturamento = $read_pedido_view['pedido_valor_plano'];
                                        $itens_faturamento_create['itens_faturamento_descricao']     = 'FATURA / CICLO';
                                        $itens_faturamento_create['itens_faturamento_plano']         = GetReg('plano', 'plano_descricao', "WHERE plano_id = '".$read_pedido_view['pedido_id_plano']."'");
                                        $itens_faturamento_create['itens_faturamento_valor_cobrado'] = $valor_cobrado_faturamento;
                                        Create('itens_faturamento', $itens_faturamento_create);
                                        Delete('itens_faturamento', "WHERE itens_faturamento_id_chip = '".$read_itens_pedido_view['itens_pedido_id_chip']."' AND itens_faturamento_id_faturamento = '".$read_ultimo_faturamento_view['faturamento_id']."' AND itens_faturamento_tipo = '0'");
                                    }else{
                                        Delete('itens_faturamento', "WHERE itens_faturamento_id_chip = '".$read_itens_pedido_view['itens_pedido_id_chip']."' AND itens_faturamento_id_faturamento = '".$read_ultimo_faturamento_view['faturamento_id']."'");
                                    }
                                }
                            }
                        }    
                    }else{
                        $itens_faturamento_create['itens_faturamento_id_faturamento']   = $read_ultimo_faturamento_view['faturamento_id'];
                        $itens_faturamento_create['itens_faturamento_id_chip']          = '0';
                        $itens_faturamento_create['itens_faturamento_id_pedido']        = $read_pedido_view['pedido_id'];
                        $itens_faturamento_create['itens_faturamento_tipo']             = '2';
                        $valor_cobrado_faturamento = $read_pedido_view['pedido_valor_plano_sms'];
                        $itens_faturamento_create['itens_faturamento_descricao']     = 'FATURA / SMS';
                        $itens_faturamento_create['itens_faturamento_valor_cobrado'] = $valor_cobrado_faturamento;
                        Create('itens_faturamento', $itens_faturamento_create);
                    }
                }
            }
            //CREATE EXEDENTE SMS
            $read_consumo_sms = Read('consumo', "WHERE consumo_id_contato = '".$read_contato_list_view['contato_id']."' AND consumo_referencia = '".$faturamento_create['faturamento_referencia']."'");
            if(NumQuery($read_consumo_sms) > '0'){
                foreach($read_consumo_sms as $read_consumo_sms_view);
                $itens_faturamento_create['itens_faturamento_id_faturamento']   = $read_ultimo_faturamento_view['faturamento_id'];
                $itens_faturamento_create['itens_faturamento_id_chip']          = '0';
                $itens_faturamento_create['itens_faturamento_id_pedido']        = $read_consumo_sms_view['consumo_qtd_sms'];
                $itens_faturamento_create['itens_faturamento_tipo']             = '5';
                $itens_faturamento_create['itens_faturamento_descricao']     = 'SMS EXCEDENTE';
                $itens_faturamento_create['itens_faturamento_valor_cobrado'] = $read_consumo_sms_view['consumo_valor_excedente'];
                Create('itens_faturamento', $itens_faturamento_create);
            }
            $read_itens_faturamento = ReadComposta("SELECT itens_faturamento_id, itens_faturamento_id_pedido, itens_faturamento_id_chip FROM itens_faturamento WHERE itens_faturamento_id_faturamento = '".$read_ultimo_faturamento_view['faturamento_id']."' AND itens_faturamento_tipo IN(0)");
            if(NumQuery($read_itens_faturamento) > '0'){
                $quantidade_itens_faturamento = NumQuery($read_itens_faturamento);
                foreach($read_itens_faturamento as $read_itens_faturamento_view){
                    //$plano_pedido = GetReg('pedido', 'pedido_id_plano', "WHERE pedido_id = '".$read_itens_faturamento_view['itens_faturamento_id_pedido']."'");
                    $read_pedido_verificacao = ReadComposta("SELECT pedido.pedido_id_plano, pedido.pedido_data_ativacao, pedido.pedido_id, plano.plano_descricao FROM pedido INNER JOIN plano ON plano.plano_id = pedido.pedido_id_plano WHERE pedido.pedido_id = '".$read_itens_faturamento_view['itens_faturamento_id_pedido']."'");
                    if(NumQuery($read_pedido_verificacao) > '0'){
                        foreach($read_pedido_verificacao as $read_pedido_verificacao_view);
                        $plano_pedido = $read_pedido_verificacao_view['pedido_id_plano'];
                        $plano_pedido_data_ativacao = $read_pedido_verificacao_view['pedido_data_ativacao'];
                        $diferenca_dias = DiferencaDias(FormDataBr($plano_pedido_data_ativacao), FormDataBr($read_ultimo_faturamento_view['faturamento_data']));
                    }
                    $read_plano_carreira = ReadComposta("SELECT plano_carreira_valor, plano_carreira_descricao, plano_carreira_paga_ativacao FROM plano_carreira WHERE plano_carreira_id_plano = '".$plano_pedido."' AND plano_carreira_inicio <= '".$quantidade_itens_faturamento."' AND plano_carreira_fim >= '".$quantidade_itens_faturamento."'");
                    if(NumQuery($read_plano_carreira) > '0'){
                        foreach($read_plano_carreira as $read_plano_carreira_view);
                        if($diferenca_dias < '30'){
                            $valor_cobrado_faturamento = ($read_plano_carreira_view['plano_carreira_valor'] / 30) * $diferenca_dias;
                        }else{
                            $valor_cobrado_faturamento = $read_plano_carreira_view['plano_carreira_valor'];
                        }
                        $read_itens_pedido_verificacao_cobranca = ReadComposta("SELECT itens_pedido_data_final, itens_pedido_desconto FROM itens_pedido WHERE itens_pedido_id_pedido = '".$read_pedido_verificacao_view['pedido_id']."' AND itens_pedido_id_chip = '".$read_itens_faturamento_view['itens_faturamento_id_chip']."'");
                        if(NumQuery($read_itens_pedido_verificacao_cobranca) > '0'){
                            foreach($read_itens_pedido_verificacao_cobranca as $read_itens_pedido_verificacao_cobranca_view);
                        }
                        if($read_plano_carreira_view['plano_carreira_paga_ativacao'] == '1'){
                            $itens_faturamento_update['itens_faturamento_valor_ativacao'] = '0';
                        }
                        if($read_itens_pedido_verificacao_cobranca_view['itens_pedido_data_final'] > date('Y-m-d')){
                            $itens_faturamento_update['itens_faturamento_valor_cobrado'] = $valor_cobrado_faturamento - (($read_itens_pedido_verificacao_cobranca_view['itens_pedido_desconto'] / 100) * $valor_cobrado_faturamento);
                        }else{
                            $itens_faturamento_update['itens_faturamento_valor_cobrado'] = $valor_cobrado_faturamento;
                        }
                        $itens_faturamento_update['itens_faturamento_plano'] = $read_pedido_verificacao_view['plano_descricao'].'('.$read_plano_carreira_view['plano_carreira_descricao'].')';
                        Update('itens_faturamento', $itens_faturamento_update, "WHERE itens_faturamento_id = '".$read_itens_faturamento_view['itens_faturamento_id']."'");
                    }else{
                        $itens_faturamento_update_teste['itens_faturamento_plano'] = $read_pedido_verificacao_view['plano_descricao'];
                        Update('itens_faturamento', $itens_faturamento_update_teste, "WHERE itens_faturamento_id = '".$read_itens_faturamento_view['itens_faturamento_id']."'");
                    }
                }
            }
            $read_itens_faturamento_correios = ReadComposta("SELECT itens_faturamento_id, itens_faturamento_id_pedido, itens_faturamento_id_chip FROM itens_faturamento WHERE itens_faturamento_id_faturamento = '".$read_ultimo_faturamento_view['faturamento_id']."' AND itens_faturamento_tipo IN(3)");
            if(NumQuery($read_itens_faturamento) > '0'){
                foreach($read_itens_faturamento_correios as $read_itens_faturamento_correios_view){
                    $read_pedido_verificacao = ReadComposta("SELECT pedido.pedido_id_plano, pedido.pedido_data_ativacao, pedido.pedido_id, pedido.pedido_tipo_frete, plano.plano_descricao FROM pedido INNER JOIN plano ON plano.plano_id = pedido.pedido_id_plano WHERE pedido.pedido_id = '".$read_itens_faturamento_correios_view['itens_faturamento_id_pedido']."'");
                    if(NumQuery($read_pedido_verificacao) > '0'){
                        foreach($read_pedido_verificacao as $read_pedido_verificacao_view);
                        $plano_pedido = $read_pedido_verificacao_view['pedido_id_plano'];
                        $plano_pedido_data_ativacao = $read_pedido_verificacao_view['pedido_data_ativacao'];
                        $tipo_frete = $read_pedido_verificacao_view['pedido_tipo_frete'];
                        $diferenca_dias = DiferencaDias(FormDataBr($plano_pedido_data_ativacao), FormDataBr($read_ultimo_faturamento_view['faturamento_data']));
                    }
                    $read_plano_carreira = ReadComposta("SELECT plano_carreira_valor, plano_carreira_descricao, plano_carreira_paga_frete_carta, plano_carreira_paga_frete_pac, plano_carreira_paga_frete_sedex FROM plano_carreira WHERE plano_carreira_id_plano = '".$plano_pedido."' AND plano_carreira_inicio <= '".$quantidade_itens_faturamento."' AND plano_carreira_fim >= '".$quantidade_itens_faturamento."'");
                    if(NumQuery($read_plano_carreira) > '0'){
                        foreach($read_plano_carreira as $read_plano_carreira_view);
                        if($read_plano_carreira_view['plano_carreira_paga_frete_carta'] == '1'){
                            if($tipo_frete == '0'){
                                $itens_faturamento_update['itens_faturamento_valor_cobrado'] = '0';
                                Update('itens_faturamento', $itens_faturamento_update, "WHERE itens_faturamento_id = '".$read_itens_faturamento_correios_view['itens_faturamento_id']."'");
                            }
                        }elseif($read_plano_carreira_view['plano_carreira_paga_frete_pac'] == '1'){
                            if($tipo_frete == '1'){
                                $itens_faturamento_update['itens_faturamento_valor_cobrado'] = '0';
                                Update('itens_faturamento', $itens_faturamento_update, "WHERE itens_faturamento_id = '".$read_itens_faturamento_correios_view['itens_faturamento_id']."'");
                            }
                        }elseif($read_plano_carreira_view['plano_carreira_paga_frete_sedex'] == '1'){
                            if($tipo_frete == '2'){
                                $itens_faturamento_update['itens_faturamento_valor_cobrado'] = '0';
                                Update('itens_faturamento', $itens_faturamento_update, "WHERE itens_faturamento_id = '".$read_itens_faturamento_correios_view['itens_faturamento_id']."'");
                            }
                        }
                    }
                }
            }
            
            if(return_valor_total_faturamento($read_ultimo_faturamento_view['faturamento_id']) > '0'){
                $read_financeiro = ReadComposta("SELECT financeiro_id, financeiro_status FROM financeiro WHERE financeiro_referencia_faturamento = '".$read_ultimo_faturamento_view['faturamento_referencia']."' AND financeiro_id_contato = '".$read_ultimo_faturamento_view['faturamento_id_contato']."'");
                if(NumQuery($read_financeiro) > '0'){
                    foreach($read_financeiro as $read_financeiro_view);
                    if($read_financeiro_view['financeiro_status'] == '0'){
                        $financeiro_form['financeiro_valor'] = return_valor_total_faturamento($read_ultimo_faturamento_view['faturamento_id']);
                        Update('financeiro', $financeiro_form, "WHERE financeiro_id = '".$read_financeiro_view['financeiro_id']."'");
                    }
                }else{
                    $financeiro_form['financeiro_codigo'] = GetReg('financeiro', 'financeiro_codigo', "WHERE financeiro_tipo = 'CR'") + 1;
                    $financeiro_form['financeiro_tipo'] = 'CR';
                    $financeiro_form['financeiro_data_lancamento'] = date('Y-m-d');
                    $financeiro_form['financeiro_valor'] = return_valor_total_faturamento($read_ultimo_faturamento_view['faturamento_id']);
                    $financeiro_form['financeiro_obs'] = $financeiro_form['financeiro_descricao'];
                    $financeiro_form['financeiro_id_contato'] = $read_ultimo_faturamento_view['faturamento_id_contato'];
                    $financeiro_form['financeiro_md5'] = md5(date('Y-m-dH:i:s').rand(9,9999999999999999999999999999));
                    $financeiro_form['financeiro_fixo'] = '0';
                    $financeiro_form['financeiro_app_financeira'] = '0';
                    $financeiro_form['financeiro_status'] = '0';
                    $financeiro_form['financeiro_numero_doc'] = rand(10000,99999);
                    $financeiro_form['financeiro_referencia_faturamento'] = $read_ultimo_faturamento_view['faturamento_referencia'];
                    Create('financeiro', $financeiro_form);
                }
                $assunto_mail = GetDados('msg_financeiro', '3', 'msg_financeiro_id', 'msg_financeiro_assunto');
                $msg_financeiro_texto = GetDados('msg_financeiro', '3', 'msg_financeiro_id', 'msg_financeiro_texto');
                $MSG_1 = str_replace('#NOMEEMPRESA#', GetEmpresa('empresa_nome_razao'), MSGEMAIL);
                $MSG_2 = str_replace('#IMGEMPRESA#', GetEmpresa('empresa_logo'), $MSG_1);
                $MSG_3 = str_replace('#TITULOMAIL#', $assunto_mail, $MSG_2);
                $MSG_4 = str_replace('#MSGMAIL#', $msg_financeiro_texto, $MSG_3);
                $MSG_5 = str_replace('#MAILEMPRESA#', GetEmpresa('empresa_email'), $MSG_4);
                $MSG_6 = str_replace('#FONEEMPRESA#', GetEmpresa('empresa_telefone'), $MSG_5);
                $MSG_7 = str_replace('#LINKBOLETO#', '<a href="http://federalsistemas.com.br/boleto_online/" target="_blank">Clique Aqui</a>', $MSG_6);
                $read_contato = Read('contato', "WHERE contato_id = '".$read_ultimo_faturamento_view['faturamento_id_contato']."'");
                if(NumQuery($read_contato) > '0'){
                    foreach($read_contato as $read_contato_view);
                    $email_cliente = strtolower($read_contato_view['contato_email']);
                    $nome_cliente = $read_contato_view['contato_nome_razao'];
                }else{
                    $email_cliente = '';
                    $nome_cliente = '';
                }
                if(valMail($email_cliente)){
                    //$retorno = sendMailCampanha($assunto_mail, $MSG_7, GetEmpresa('empresa_email'), GetEmpresa('empresa_nome_fantasia'), $email_cliente, $nome_cliente);
                }
            }
            
            $json_faturamento = array(
                'type' => 'success',
                'title' => 'Parabéns:',
                'msg' => 'Operação realizada com sucesso',
                'fim' => 'nao',
                'inicio' => $inicio + 1,
                'final' => $fim,
                'perc' => $calc_perc,
                'nome_cliente' => $read_contato_list_view['contato_nome_razao'],
                'verificacao' => '1',
                'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
            );
        }
    }
    echo json_encode($json_faturamento);
}elseif($acao == 'update'){
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
        $uid = addslashes($_POST['id']);
        unset($caixa_form['id']);
        Update('caixa', $caixa_form, "WHERE caixa_id = '".$uid."'");
        $json_caixa = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<a href="javascript::" data-dismiss="modal" onclick="carrega_pagina(\'caixa\', \'index.php\');" class="btn btn-primary">Sair</a>'
        );
    }
    echo json_encode($json_caixa);
}elseif($acao == 'load_update'){
    $uid = addslashes($_POST['id']);
    
    $read_caixa = Read('caixa', "WHERE caixa_id = '".$uid."'");
    if(NumQuery($read_caixa) > '0'){
        foreach($read_caixa as $read_caixa_view);
        $json_caixa[] = $read_caixa_view;
    }else{
        $json_caixa = null;
    }
    echo json_encode($json_caixa);
}elseif($acao == 'load_caixa'){
    $read_caixa = Read('caixa', "ORDER BY caixa_descricao ASC");
    if(NumQuery($read_caixa) > '0'){
        foreach($read_caixa as $read_caixa_view){
            $json_caixa["data"][] = $read_caixa_view;
        }
        echo json_encode($json_caixa);
    }
}elseif($acao == 'gerar_excel'){
    $arquivo = 'caixa.xls';
    $tabela = '<table border="1" width="800px">';
        $tabela .= '<tr>';
            $tabela .= '<td colspan="3" align="center">Relação de caixas</tr>';
        $tabela .= '</tr>';
        $tabela .= '<tr>';
            $tabela .= '<td><b>Código</b></td>';
            $tabela .= '<td><b>Descrição</b></td>';
            $tabela .= '<td><b>Status</b></td>';
        $tabela .= '</tr>';
    
    $read_caixa = Read('caixa', "WHERE caixa_id != '' {$_SESSION['caixa_load']} ORDER BY caixa_descricao ASC");
    if(NumQuery($read_caixa) > '0'){
        foreach($read_caixa as $read_caixa_view){
            if($read_caixa_view['caixa_status'] == '0'){
                $status_caixa = 'ATIVO';
            }else{
                $status_caixa = 'INATIVO';
            }
            $tabela .= '<tr>';
                $tabela .= '<td>'.$read_caixa_view['caixa_id'].'</td>';
                $tabela .= '<td>'.$read_caixa_view['caixa_descricao'].'</td>';
                $tabela .= '<td>'.$status_caixa.'</td>';
            $tabela .= '</tr>';
        }
    }
    
    header("Content-type: application/vnd.ms-excel");  
    header("Content-type: application/force-download"); 
    header("Content-Disposition: attachment; filename=file.xls");
    header("Pragma: no-cache");
    echo $tabela;
}elseif($acao == 'info_faturamento'){
    $post_id_faturamento = addslashes($_POST['id_faturamento']);
    
    $read_faturamento = ReadComposta("SELECT faturamento_referencia FROM faturamento WHERE faturamento_id = '".$post_id_faturamento."'");
    if(NumQuery($read_faturamento) > '0'){
        foreach ($read_faturamento as $read_faturamento_view);
    }
    
    $read_itens_faturamento = Read('itens_faturamento', "WHERE itens_faturamento_id_faturamento = '".$post_id_faturamento."'");
    if(NumQuery($read_itens_faturamento) > '0'){
        foreach($read_itens_faturamento as $read_itens_faturamento_view){
            $data_ativacao_pedido = GetReg('pedido', 'pedido_data_ativacao', "WHERE pedido_id = '".$read_itens_faturamento_view['itens_faturamento_id_pedido']."'");
            $read_pedido_desinstalacao = Read('pedido_desinstalacao', "WHERE pedido_desinstalacao_id_chip = '".$read_itens_faturamento_view['itens_faturamento_id_chip']."'");
            if(NumQuery($read_pedido_desinstalacao) > '0'){
                foreach($read_pedido_desinstalacao as $read_pedido_desinstalacao_view){
                    $verificacao_cobrar_no_cobrar = return_cobrar_no_cobrar($read_faturamento_view['faturamento_referencia'], $data_ativacao_pedido);
                    if($read_pedido_desinstalacao_view['pedido_desinstalacao_cobrar'] == $read_faturamento_view['faturamento_referencia']){
                        $valor_multa_chip += $read_pedido_desinstalacao_view['pedido_desinstalacao_valor_total'];
                    }else{
                        $valor_multa_chip += '0';
                    }
                }
            }
            if($read_itens_faturamento_view['itens_faturamento_tipo'] == '0'){
                $valor_cobrado += $read_itens_faturamento_view['itens_faturamento_valor_cobrado'];
                $valor_ativacao += $read_itens_faturamento_view['itens_faturamento_valor_ativacao'];
            }elseif($read_itens_faturamento_view['itens_faturamento_tipo'] == '1'){
                $valor_cobrado_ciclo += $read_itens_faturamento_view['itens_faturamento_valor_cobrado'];
            }elseif($read_itens_faturamento_view['itens_faturamento_tipo'] == '2'){
                $valor_cobrado_sms += $read_itens_faturamento_view['itens_faturamento_valor_cobrado'];
            }elseif($read_itens_faturamento_view['itens_faturamento_tipo'] == '3'){
                $valor_cobrado_correios += $read_itens_faturamento_view['itens_faturamento_valor_cobrado'];
            }elseif($read_itens_faturamento_view['itens_faturamento_tipo'] == '5'){
                $valor_cobrado_sms_excedente += $read_itens_faturamento_view['itens_faturamento_valor_cobrado'];
                $quantidade_sms_excedente += $read_itens_faturamento_view['itens_faturamento_id_pedido'];
            }
        }
    }
    $valor_total = $valor_cobrado + $valor_ativacao + $valor_cobrado_ciclo + $valor_cobrado_sms + $valor_cobrado_correios + $valor_multa_chip + $valor_cobrado_sms_excedente;
    $json_faturamento = array(
        'msg' => '<strong>Valor Faturas: </strong>'. FormatMoney($valor_cobrado).'<br /><strong>Valor Ativações: </strong>'. FormatMoney($valor_ativacao).'<br /><strong>Valor Ciclos: </strong>'. FormatMoney($valor_cobrado_ciclo).'<br /><strong>Valor SMS: </strong>'. FormatMoney($valor_cobrado_sms).'<br /><strong>Valor Correios: </strong>'. FormatMoney($valor_cobrado_correios).'<br /><strong>Valor Multa / Chip não devolvido: </strong>'. FormatMoney($valor_multa_chip).'<br /><strong>SMS Excedentes: </strong>'. $quantidade_sms_excedente.'<br /><strong>Valor SMS Excedentes: </strong>'. FormatMoney($valor_cobrado_sms_excedente).'<br /><strong>Valor Total: </strong>'. FormatMoney($valor_total).'<br />',
        'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
    );
    echo json_encode($json_faturamento);
}
?>