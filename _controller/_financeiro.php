<?php
ob_start();
session_start();
require_once '../_class/Ferramenta.php';
require_once '../_phpexcel/xlsxwriter.class.php';
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
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
        $order_by = "ORDER BY financeiro_status ASC, financeiro_data_vencimento ASC";
    }else{
        
        $sort       = addslashes($_GET['sort']);
        $sort_dir   = addslashes($_GET['sort_dir']);
        //$order_by   = "ORDER BY ".$sort." ".$sort_dir."";
		$order_by = "ORDER BY financeiro_status ASC, financeiro_data_vencimento ASC";
    }
    
    //PESQUISA VIA AJAX
    if(isset($_GET['search']) && $_GET['search'] == 'true'){
        $get_id_contato         = addslashes($_GET['id_contato']);
        $get_situacao           = addslashes($_GET['situacao']);
        $get_tipo_pesquisa      = addslashes($_GET['tipo_pesquisa']);
        $get_data_inicial       = addslashes($_GET['data_inicial']);
        $get_data_final         = addslashes($_GET['data_final']);
        $get_itens_pesquisa     = addslashes($_GET['itens_pesquisa']);
        $get_pesquisa           = addslashes($_GET['pesquisa']);
        $get_fixo               = addslashes($_GET['fixo']);
        $get_app_financeira     = addslashes($_GET['app_financeira']);
        $get_id_plano_conta     = addslashes($_GET['id_plano_conta']);
        $get_id_tipo_documento  = addslashes($_GET['id_tipo_documento']);
        $get_id_vendedor        = addslashes($_GET['id_vendedor']);
        $get_id_associado       = addslashes($_GET['id_associado']);
        $get_boleto             = addslashes($_GET['boleto']);
        $get_remessa            = addslashes($_GET['remessa']);
        
        $_SESSION['registros_financeiro'] = addslashes($_GET['registros']);
        
        //GRAVA SESSION
        $_SESSION['search_financeiro_id_contato'] = $get_id_contato;
        $_SESSION['search_financeiro_situacao'] = $get_situacao;
        $_SESSION['search_financeiro_tipo_pesquisa'] = $get_tipo_pesquisa;
        $_SESSION['search_financeiro_data_inicial'] = $get_data_inicial;
        $_SESSION['search_financeiro_data_final'] = $get_data_final;
        $_SESSION['search_financeiro_itens_pesquisa'] = $get_itens_pesquisa;
        $_SESSION['search_financeiro_pesquisa'] = $get_pesquisa;
        $_SESSION['search_financeiro_fixo'] = $get_fixo;
        $_SESSION['search_financeiro_app_financeira'] = $get_app_financeira;
        $_SESSION['search_financeiro_id_plano_conta'] = $get_id_plano_conta;
        $_SESSION['search_financeiro_id_tipo_documento'] = $get_id_tipo_documento;
        $_SESSION['search_financeiro_id_vendedor'] = $get_id_vendedor;
        $_SESSION['search_financeiro_remessa'] = $get_remessa;
        $_SESSION['search_financeiro_boleto'] = $get_boleto;
        
        if($get_remessa != ''){
            $sql_remessa = "AND financeiro_remessa = '".$get_remessa."'";
        }else{
            $sql_remessa = "";
        }
        if($get_boleto == '0'){
            $sql_boleto = "AND financeiro_nosso_numero IS NULL";
        }elseif($get_boleto == '1'){
            $sql_boleto = "AND financeiro_nosso_numero IS NOT NULL";
        }else{
            $sql_boleto = "";
        }
        if($get_id_associado != 'undefined'){
            $sql_id_associado = "AND financeiro_id_associado = '".$get_id_associado."'";
        }else{
            $sql_id_associado = "";
        }
        if($get_id_contato != ''){
            $sql_id_contato = "AND financeiro_id_contato = '".$get_id_contato."'";
            $info_protestado = GetDados('contato', $get_id_contato, 'contato_id', 'contato_protestado');
        }else{
            $sql_id_contato = "";
        }
        if($get_situacao != ''){
            $sql_situacao = "AND financeiro_status = '".$get_situacao."'";
        }else{
            $sql_situacao = "";
        }
        if($get_tipo_pesquisa != '' && $get_data_inicial != '' && $get_data_final != ''){
            $sql_periodo = "AND $get_tipo_pesquisa BETWEEN '".$get_data_inicial."' AND '".$get_data_final."'";
        }else{
            $sql_periodo = "";
        }
        if($get_itens_pesquisa != '' && $get_pesquisa != ''){
            $sql_pesquisa = "AND $get_itens_pesquisa LIKE '%".$get_pesquisa."%'";
        }else{
            $sql_pesquisa = "";
        }
        if($get_fixo != ''){
            $sql_fixo = "AND financeiro_fixo = '".$get_fixo."'";
        }else{
            $sql_fixo = "";
        }
        if($get_app_financeira != ''){
            $sql_app_financeira = "AND financeiro_app_financeira = '".$get_app_financeira."'";
        }else{
            $sql_app_financeira = "";
        }
        if($get_id_plano_conta != ''){
            $sql_id_plano_conta = "AND financeiro_id_plano_conta = '".$get_id_plano_conta."'";
        }else{
            $sql_id_plano_conta = "";
        }
        if($get_id_tipo_documento != ''){
            $sql_id_tipo_documento = "AND financeiro_id_tipo_documento = '".$get_id_tipo_documento."'";
        }else{
            $sql_id_tipo_documento = "";
        }
        if($get_id_vendedor != ''){
            $sql_id_vendedor = "AND financeiro_id_vendedor = '".$get_id_vendedor."'";
        }else{
            $sql_id_vendedor = "";
        }
        
        $_SESSION['financeiro_load'] = trim("".$sql_id_contato." ".$sql_situacao." ".$sql_periodo." ".$sql_pesquisa." ".$sql_fixo." ".$sql_app_financeira." ".$sql_id_plano_conta." ".$sql_id_tipo_documento." ".$sql_id_vendedor." ".$sql_id_associado." ".$sql_boleto." ".$sql_remessa." ");
        $_SESSION['financeiro_load_status'] = "".$sql_id_contato." ".$sql_periodo." ".$sql_pesquisa." ".$sql_fixo." ".$sql_app_financeira." ".$sql_id_plano_conta." ".$sql_id_tipo_documento." ".$sql_id_vendedor." ".$sql_id_associado." ".$sql_boleto." ".$sql_remessa." ";
    }
    $valor_financeiro_aberto = '0';
    $valor_financeiro_pago = '0';
    $valor_financeiro_cancelado = '0';
    $valor_financeiro_renegociado = '0';
    $valor_financeiro_total = '0';
    $valor_financeiro_total_vencido = '0';
    $valor_financeiro_total_juros = '0';
    $valor_financeiro_total_multa = '0';
    $valor_financeiro_total_atualizado = '0';
	unset($_SESSION['erro_info_retorno']);
    $read_financeiro_paginator = ReadComposta("SELECT financeiro_id, financeiro_valor, financeiro_status, financeiro_valor_pagamento, financeiro_data_vencimento, financeiro_id_contato FROM financeiro WHERE financeiro_tipo = '".$_GET['OP']."' {$_SESSION['financeiro_load']}");
    if(NumQuery($read_financeiro_paginator) > '0'){
        foreach($read_financeiro_paginator as $read_financeiro_paginator_view){
			$_SESSION['erro_info_retorno'] .= $read_financeiro_paginator_view['financeiro_id_contato'].',';
            if($read_financeiro_paginator_view['financeiro_status'] == '0'){
                $valor_financeiro_aberto += $read_financeiro_paginator_view['financeiro_valor'];
                $ValorMulta = '0';
                $ValorJuros = '0';
                $ValorAtualizado = '0';
                $NovaDtVenc = date('Y-m-d');
                if(strtotime($read_financeiro_paginator_view['financeiro_data_vencimento']) < strtotime(date('Y-m-d'))){
                    $IntervadoVencido = strtotime($NovaDtVenc) - strtotime($read_financeiro_paginator_view['financeiro_data_vencimento']);
                    //$DiasIntervalo = DiferencaDias(FormDataBr($read_financeiro_paginator_view['financeiro_data_vencimento']), FormDataBr($NovaDtVenc));
                    $DiasIntervalo = floor($IntervadoVencido / (60 * 60 * 24));
                    //echo $DiasIntervalo.'<br />';
                    $ValorContaOriginal = $read_financeiro_paginator_view['financeiro_valor'];
                    $ValorMulta = (MULTA / 100) * $ValorContaOriginal;
                    $ValorJuros = ((JUROS * $ValorContaOriginal) / 100) * $DiasIntervalo;
                    $ValorJurosDia = ((JUROS * $ValorContaOriginal) / 100);
                    $ValorMultaDia = (MULTA / 100) * $ValorContaOriginal;
                    if($ValorJuros > '0'){
                        $ValorAtualizado = $ValorContaOriginal + $ValorMulta + $ValorJuros;
                        $DataVenc = FormDataBr($NovaDtVenc);
                    }else{
                        $ValorAtualizado = $ValorContaOriginal;
                        $DataVenc = FormDataBr($NovaDtVenc);
                        $ValorJurosDia = '0';
                        $ValorMultaDia = '0';
                    }
                }else{
                    $ValorAtualizado = $read_financeiro_paginator_view['financeiro_valor'];
                    $DataVenc = FormDataBr($read_financeiro_paginator_view['financeiro_data_vencimento']);
                }
                $valor_financeiro_total_juros += $ValorJuros;
                $valor_financeiro_total_multa += $ValorMulta;
                $valor_financeiro_total_atualizado += $ValorAtualizado;
            }elseif($read_financeiro_paginator_view['financeiro_status'] == '1'){
                $valor_financeiro_pago += $read_financeiro_paginator_view['financeiro_valor_pagamento'];
            }elseif($read_financeiro_paginator_view['financeiro_status'] == '2'){
                $valor_financeiro_cancelado += $read_financeiro_paginator_view['financeiro_valor'];
            }elseif($read_financeiro_paginator_view['financeiro_status'] == '3'){
                $valor_financeiro_renegociado += $read_financeiro_paginator_view['financeiro_valor'];
            }
            $valor_financeiro_total += $read_financeiro_paginator_view['financeiro_valor'];
        }
    }
    $read_financeiro = Read('financeiro', "WHERE financeiro_tipo = '".$_GET['OP']."' {$_SESSION['financeiro_load']} ".$order_by." LIMIT $inicio,$maximo");
    //$read_financeiro = ReadComposta("SELECT * FROM financeiro WHERE financeiro_tipo = '".$_GET['OP']."' {$_SESSION['financeiro_load']} LIMIT $inicio,$maximo");
    //$read_financeiro = ReadComposta("SELECT financeiro.financeiro_id, financeiro.financeiro_codigo, financeiro.financeiro_descricao, financeiro.financeiro_id_contato, contato.contato_id, contato.contato_nome_fantasia FROM financeiro INNER JOIN contato ON financeiro.financeiro_id_contato = contato.contato.id WHERE financeiro.financeiro_id != '' {$_SESSION['financeiro_load']} ".$order_by." LIMIT $inicio,$maximo");
    if(NumQuery($read_financeiro) > '0'){
        //PEGA QUANTOS BOTÕES PARA PAGINAÇÃO DE RESULTADOS
        $paginas = ceil(NumQuery($read_financeiro_paginator) / $maximo);
        //TABULATOR EXIGE ESSA INFORMAÇÃO PARA QUE PRECISE DA PAGINAÇÃO DE RESULTADOS 
        $json_financeiro["last_page"] = $paginas;
        if(trim($_SESSION['financeiro_load']) != ''){
            $json_financeiro["quantidade_contas"] = NumQuery($read_financeiro_paginator);
            $json_financeiro["valor_aberto"] = FormatMoney($valor_financeiro_aberto);
            $json_financeiro["valor_pago"] = FormatMoney($valor_financeiro_pago);
            $json_financeiro["valor_cancelado"] = FormatMoney($valor_financeiro_cancelado);
            $json_financeiro["valor_renegociado"] = FormatMoney($valor_financeiro_renegociado);
            $json_financeiro["valor_total"] = FormatMoney($valor_financeiro_total);
            $json_financeiro["valor_total_multa"] = FormatMoney($valor_financeiro_total_multa);
            $json_financeiro["valor_total_juros"] = FormatMoney($valor_financeiro_total_juros);
            $json_financeiro["valor_total_atualizado"] = FormatMoney($valor_financeiro_total_atualizado);
            if($info_protestado == '0'){
                $json_financeiro["financeiro_protestado"] = 'Não';
            }else if($info_protestado == '1'){
                $json_financeiro["financeiro_protestado"] = 'Sim';
            }else{
                $json_financeiro["financeiro_protestado"] = 'Não Identificado';
            }
        }
        foreach($read_financeiro as $read_financeiro_view){
            $read_financeiro_view['financeiro_id_contato']  = GetDados('contato', $read_financeiro_view['financeiro_id_contato'], 'contato_id', 'contato_nome_fantasia');
            $read_financeiro_view['financeiro_valor']       = FormatMoney($read_financeiro_view['financeiro_valor']);
            $read_financeiro_view['financeiro_data_vencimento'] = FormDataBr($read_financeiro_view['financeiro_data_vencimento']);
            if($read_financeiro_view['financeiro_status'] == '0'){
                $read_financeiro_view['financeiro_status'] = 'ABERTO';
                $read_financeiro_view['financeiro_data_pagamento'] = '';
                $read_financeiro_view['financeiro_valor_pagamento'] = '';
            }elseif($read_financeiro_view['financeiro_status'] == '1'){
                $read_financeiro_view['financeiro_status'] = 'BAIXADO';
                $read_financeiro_view['financeiro_data_pagamento'] = FormDataBr($read_financeiro_view['financeiro_data_pagamento']);
                $read_financeiro_view['financeiro_valor_pagamento'] = FormatMoney($read_financeiro_view['financeiro_valor_pagamento']);
            }elseif($read_financeiro_view['financeiro_status'] == '2'){
                $read_financeiro_view['financeiro_status'] = 'CANCELADO';
                $read_financeiro_view['financeiro_data_pagamento'] = '';
                $read_financeiro_view['financeiro_valor_pagamento'] = '';
            }elseif($read_financeiro_view['financeiro_status'] == '3'){
                $read_financeiro_view['financeiro_status'] = 'RENEGOCIADO';
                $read_financeiro_view['financeiro_data_pagamento'] = '';
                $read_financeiro_view['financeiro_valor_pagamento'] = '';
            }elseif($read_financeiro_view['financeiro_status'] == '4'){
                $read_financeiro_view['financeiro_status'] = 'PAGO / BAIXADO';
                $read_financeiro_view['financeiro_data_pagamento'] = FormDataBr($read_financeiro_view['financeiro_data_pagamento']);
                $read_financeiro_view['financeiro_valor_pagamento'] = FormatMoney($read_financeiro_view['financeiro_valor_pagamento']);
            }
            
            $json_financeiro['data'][] = array_map('utf8_encode', $read_financeiro_view);
        }
    }else{
        $json_financeiro['data'] = null;
    }
    echo json_encode($json_financeiro);
}elseif($acao == 'create'){
    //RECUPERA O FORMULARIO
    $financeiro_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($financeiro_form['acao']);
    $id_vendedor = $financeiro_form['financeiro_id_vendedor'];
    unset($financeiro_form['financeiro_id_vendedor']);
    
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
        
        if($id_vendedor != ''){
            $financeiro_form['financeiro_id_vendedor'] = $id_vendedor;
        }
        
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
}elseif($acao == 'update'){
    //RECUPERA O FORMULARIO
    $financeiro_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($financeiro_form['acao']);
    
    $id_vendedor = $financeiro_form['financeiro_id_vendedor'];
    unset($financeiro_form['financeiro_id_vendedor']);
    
    if(in_array('', $financeiro_form)){
        $json_financeiro = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        if($id_vendedor != ''){
            $financeiro_form['financeiro_id_vendedor'] = $id_vendedor;
        }
        $uid = addslashes($_POST['id']);
        $op  = addslashes($_POST['OP']);
        unset($financeiro_form['id']);
        unset($financeiro_form['OP']);
        Update('financeiro', $financeiro_form, "WHERE financeiro_id = '".$uid."'");
        $json_financeiro = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<a href="javascript::" onclick="carrega_pagina(\'financeiro\', \'index.php?OP='.$op.'\');" data-dismiss="modal" class="btn btn-primary">Sair</a>'
        );
    }
    echo json_encode($json_financeiro);
}elseif($acao == 'load_update'){
    $uid = addslashes($_POST['id']);
    
    $read_financeiro = Read('financeiro', "WHERE financeiro_id = '".$uid."' AND financeiro_status IN(0,2,3)");
    if(NumQuery($read_financeiro) > '0'){
        foreach($read_financeiro as $read_financeiro_view);
        $json_financeiro[] = $read_financeiro_view;
    }else{
        $json_financeiro = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser realizada, conta não identificada',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }
    echo json_encode($json_financeiro);
}elseif($acao == 'load_update_reverse'){
    $uid = addslashes($_POST['id']);
    
    $read_financeiro = Read('financeiro', "WHERE financeiro_id = '".$uid."' AND financeiro_status IN(1)");
    if(NumQuery($read_financeiro) > '0'){
        foreach($read_financeiro as $read_financeiro_view);
        $json_financeiro[] = $read_financeiro_view;
    }else{
        $json_financeiro = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser realizada, conta não identificada',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }
    echo json_encode($json_financeiro);
}elseif($acao == 'download'){
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
        $uid = addslashes($_POST['id']);
        $op  = addslashes($_POST['OP']);
        $id_caixa = addslashes($_POST['financeiro_caixa']);
        $pass = addslashes($_POST['pass']);
        unset($financeiro_form['financeiro_caixa']);
        unset($financeiro_form['id']);
        unset($financeiro_form['OP']);
        unset($financeiro_form['pass']);
        
        if(GetEmpresa('empresa_download_config') == '0'){
            if(GetEmpresa('empresa_download_pass') == $pass){
                $get_pass = '1';
            }else{
                $get_pass = '0';
            }
        }else{
            $get_pass = '1';
        }
        
        $financeiro_form['financeiro_status'] = '1';
        $financeiro_form['financeiro_data_baixa'] = date('Y-m-d');
        
        $read_financeiro = ReadComposta("SELECT financeiro_id FROM financeiro WHERE financeiro_id = '".$uid."' AND financeiro_status = '0'");
        if(NumQuery($read_financeiro) > '0'){
            foreach($read_financeiro as $read_financeiro_view);
            if($get_pass == '1'){
                Update('financeiro', $financeiro_form, "WHERE financeiro_id = '".$uid."'");
                $caixa_conta_form['caixa_conta_id_financeiro']      = $uid;
                $caixa_conta_form['caixa_conta_data_lancamento']    = $financeiro_form['financeiro_data_pagamento'];
                $caixa_conta_form['caixa_conta_valor_lancamento']   = $financeiro_form['financeiro_valor_pagamento'];
                $caixa_conta_form['caixa_conta_id_plano_contas']    = GetDados('financeiro', $uid, 'financeiro_id', 'financeiro_id_plano_conta');
                $caixa_conta_form['caixa_conta_numero_doc']         = GetDados('financeiro', $uid, 'financeiro_id', 'financeiro_numero_doc');
                $caixa_conta_form['caixa_conta_id_caixa']           = $id_caixa;
                if($op == 'CP'){
                    $caixa_conta_form['caixa_conta_descricao']      = 'Pagto. '.  GetDados('contato', GetDados('financeiro', $uid, 'financeiro_id', 'financeiro_id_contato'), 'contato_id', 'contato_nome_fantasia').' / Ref. '.GetDados('financeiro', $uid, 'financeiro_id', 'financeiro_descricao');
                    $caixa_conta_form['caixa_conta_tipo_lancamento']= 'D';
                }else{
                    $caixa_conta_form['caixa_conta_descricao']      = 'Rcbto. '.  GetDados('contato', GetDados('financeiro', $uid, 'financeiro_id', 'financeiro_id_contato'), 'contato_id', 'contato_nome_fantasia').' / Ref. '.GetDados('financeiro', $uid, 'financeiro_id', 'financeiro_descricao');
                    $caixa_conta_form['caixa_conta_tipo_lancamento']= 'C';
                }
                Create('caixa_conta', $caixa_conta_form);

                $json_financeiro = array(
                    'type' => 'success',
                    'title' => 'Parabéns:',
                    'msg' => 'Operação realizada com sucesso',
                    'buttons' => '<a href="javascript::" onclick="carrega_pagina(\'financeiro\', \'index.php?OP='.$op.'\');" data-dismiss="modal" class="btn btn-primary">Sair</a>'
                );
            }else{
                $json_financeiro = array(
                    'type' => 'success',
                    'title' => 'Ops:',
                    'msg' => 'Ops, senha incorreta',
                    'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
                );
            }
        }else{
            $json_financeiro = array(
                'type' => 'error',
                'title' => 'Erro:',
                'msg' => 'Ops, operação não pode ser finalizada devido a já está baixada!',
                'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
            );
        }
    }
    echo json_encode($json_financeiro);
}elseif($acao == 'reverse'){
    //RECUPERA O FORMULARIO
    $financeiro_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($financeiro_form['acao']);
    
    $uid    = addslashes($_POST['id']);
    $op     = addslashes($_POST['OP']);
    $pass   = addslashes($_POST['pass']);
    
    unset($financeiro_form['id']);
    unset($financeiro_form['OP']);
    unset($financeiro_form['pass']);
    
    if(GetEmpresa('empresa_reverse_config') == '0'){
        if(GetEmpresa('empresa_reverse_pass') == $pass){
            $get_pass = '1';
        }else{
            $get_pass = '0';
        }
    }else{
        $get_pass = '1';
    }
    if($get_pass == '1'){
        $financeiro_form['financeiro_valor_pagamento']  = NULL;
        $financeiro_form['financeiro_data_pagamento']   = NULL;
        $financeiro_form['financeiro_data_baixa']       = NULL;
        $financeiro_form['financeiro_status']           = '0';
        Update('financeiro', $financeiro_form, "WHERE financeiro_id = '".$uid."'");

        $caixa_conta_form['caixa_conta_id_caixa'] = '0';
        Update('caixa_conta', $caixa_conta_form, "WHERE caixa_conta_id_financeiro = '".$uid."'");
        $json_financeiro = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<a href="javascript::" onclick="carrega_pagina(\'financeiro\', \'index.php?OP='.$op.'\');" data-dismiss="modal" class="btn btn-primary">Sair</a>'
        );
    }else{
        $json_financeiro = array(
            'type' => 'success',
            'title' => 'Ops:',
            'msg' => 'Ops, senha incorreta',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }
    echo json_encode($json_financeiro);
}elseif($acao == 'cancel'){
    //RECUPERA O FORMULARIO
    $financeiro_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($financeiro_form['acao']);
    
    $uid    = addslashes($_POST['id']);
    $op     = addslashes($_POST['OP']);
    $pass   = addslashes($_POST['pass']);
    $obs    = addslashes($_POST['financeiro_obs']);
    
    unset($financeiro_form['id']);
    unset($financeiro_form['OP']);
    unset($financeiro_form['pass']);
    unset($financeiro_form['financeiro_obs']);
    
    if(GetEmpresa('empresa_cancel_config') == '0'){
        if(GetEmpresa('empresa_cancel_pass') == $pass){
            $get_pass = '1';
        }else{
            $get_pass = '0';
        }
    }else{
        $get_pass = '1';
    }
    if($get_pass == '1'){
        $financeiro_form['financeiro_status']   = '2';
        $financeiro_form['financeiro_obs']      = GetDados('financeiro', $uid, 'financeiro_id', 'financeiro_obs').'-----Motivo Cancelamento:'.$obs;
        Update('financeiro', $financeiro_form, "WHERE financeiro_id IN($uid)");
        $json_financeiro = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<a href="javascript::" onclick="carrega_pagina(\'financeiro\', \'index.php?OP='.$op.'\');" data-dismiss="modal" class="btn btn-primary">Sair</a>'
        );
    }else{
        $json_financeiro = array(
            'type' => 'success',
            'title' => 'Ops:',
            'msg' => 'Ops, senha incorreta',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }
    echo json_encode($json_financeiro);
}elseif($acao == 'gerar_remessa'){
    
    $banco = addslashes($_POST['banco']);
    $boleto = addslashes($_POST['boleto']);
    
    if($banco == '1'){
        function remover_acentos($str) 
        { 
          $a = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'Ð', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', '?', '?', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', '?', '?', 'L', 'l', 'N', 'n', 'N', 'n', 'N', 'n', '?', 'O', 'o', 'O', 'o', 'O', 'o', 'Œ', 'œ', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'Š', 'š', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Ÿ', 'Z', 'z', 'Z', 'z', 'Ž', 'ž', '?', 'ƒ', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', '?', '?', '?', '?', '?', '?', 'ç', 'Ç', "'" ); 
          $b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o','c','C', ' '); 
          return str_replace($a, $b, $str); 
        } 

        function post_slug($str) 
        { 
          return strtoupper(preg_replace(array('/[^a-zA-Z0-9 -]/', '/[ -]+/', '/^-|-$/'), 
          array('', '-', ''), remover_acentos($str))); 
        } 

        # ******************************************************************************************************************************************************************
        /*Campos Numéricos (“Picture 9”)
        • Alinhamento: sempre à direita, preenchido com zeros à esquerda, sem máscara de edição;
        • Não utilizados: preencher com zeros.
        */
        # ******************************************************************************************************************************************************************

        function picture_9($palavra,$limite){
                $var=str_pad($palavra, $limite, "0", STR_PAD_LEFT);
                return $var;
        }

        # ******************************************************************************************************************************************************************
        /*
        Campos Alfanuméricos (“Picture X”)
        • Alinhamento: sempre à esquerda, preenchido com brancos à direita;
        • Não utilizados: preencher com brancos;
        • Caracteres: maiúsculos, sem acentuação, sem ‘ç’, sem caracteres especiais.
        */
        # ******************************************************************************************************************************************************************

        function picture_x( $palavra, $limite ){
                $var = str_pad( $palavra, $limite, " ", STR_PAD_RIGHT );
                $var = remover_acentos( $var );
                if( strlen( $palavra ) >= $limite ){
                        $var = substr( $palavra, 0, $limite );
                }
                $var = strtoupper( $var );// converte em letra maiuscula
                return $var;
        }	 

        # ******************************************************************************************************************************************************************	 

        function sequencial($i)
        {
        if($i < 10)
        {
        return zeros(0,5).$i;
        }
        else if($i > 10 && $i < 100)
        {
        return zeros(0,4).$i;
        }
        else if($i > 100 && $i < 1000)
        {
        return zeros(0,3).$i;
        }
        else if($i > 1000 && $i < 10000)
        {
        return zeros(0,2).$i;
        }
        else if($i > 10000 && $i < 100000)
        {
        return zeros(0,1).$i;
        }
        }

        # ******************************************************************************************************************************************************************

        function zeros($min,$max)
        {
        $x = ($max - strlen($min));
        for($i = 0; $i < $x; $i++)
        {
        $zeros .= '0';
        }
        return $zeros.$min;
        }

        function complementoRegistro($int,$tipo)
        {
        if($tipo == "zeros")
        {
        $space = '';
        for($i = 1; $i <= $int; $i++)
        {
        $space .= '0';
        }
        }
        else if($tipo == "brancos")
        {
        $space = '';
        for($i = 1; $i <= $int; $i++)
        {
        $space .= ' ';
        }
        }

        return $space;
        }

        # ******************************************************************************************************************************************************************
        # FIM DAS FUNCOES
        # ******************************************************************************************************************************************************************


        # ******************************************************************************************************************************************************************
        # NAO ALTERAR ==> DADOS FIXOS - NAO ALTERAR
        # ******************************************************************************************************************************************************************
        # NAO ALTERAR ==> DADOS PARA A CRIACAO DO ARQUIVO
        # ******************************************************************************************************************************************************************

        $fusohorario     = 3; // como o servidor de hospedagem é a dreamhost pego o fuso para o horario do brasil
        $timestamp       = mktime(date("H") - $fusohorario, date("i"), date("s"), date("m"), date("d"), date("Y"));
        $DATAHORA['PT']  = gmdate("d/m/Y H:i:s", $timestamp);
        $DATAHORA['EN']  = gmdate("Y-m-d H:i:s", $timestamp);
        $DATA['PT']      = gmdate("d/m/Y", $timestamp);
        $DATA['EN']      = gmdate("Y-m-d", $timestamp);
        $DATA['DIA']     = gmdate("d",$timestamp);
        $DATA['MES']     = gmdate("m",$timestamp);
        $DATA['ANO']     = gmdate("Y",$timestamp);
        $HORA            = gmdate("H:i:s", $timestamp);
        $HORA1           = gmdate("His", $timestamp);

        # ******************************************************************************************************************************************************************
        # NAO ALTERAR ==> DADOS PARA A CRIACAO DO CONTEUDO DO ARQUIVO
        # ******************************************************************************************************************************************************************

        $conteudo        = '';                                    // conteudo do arquivo de remessa
        $lote_sequencial = 1;                                     // nº do lote. Sempre sera = 1
        $lote_servico    = 1;                                     // lote de servico. sera = 1
        $header          = '';                                    // 1ª linha do arquivo = header
        $header_lote     = '';                                    // 2ª linha do arquivo = header de lote
        $linha_p         = '';                                    // segmento 3 - linha 'P'
        $linha_q         = '';                                    // segmento 3 - linha 'Q'
        $linha_r         = '';                                    // segmento 3 - linha 'R'
        $linha_5         = '';                                    // Linha segmento 5
        $linha_9         = '';                                    // Linha segmento 9
        $conteudo_meio   = '';                                    // meio ou miolo do arquivo (linhas P+Q+R) 
        $qtd_titulos     = 0;                                     // qtd. de titulos
        $total_valor     = 0;                                     // valor total dos titulos

        define("REMESSA",$PATH."",true);

        # ******************************************************************************************************************************************************************
        # ALTERE AQUI ==> * * * COLOQUE AQUI OS DADOS DA SUA EMPRESA E DA CONTA CORRENTE E ALTERE COMO PREFERIR * * * 
        # ******************************************************************************************************************************************************************
        $read_boleto = Read('boleto', "WHERE boleto_id = '".$boleto."'");
        if(NumQuery($read_boleto) > '0'){
            foreach($read_boleto as $read_boleto_view);
        }
        $valor_multa                = 0; // 200 => 2,00 %       // porcentagem de multa com 2 casas decimais     
        $carteira                   = 14;                         // codigo da carteira de cobranca registrada
        $cpf_cnpj                   = GetEmpresa('empresa_cnpj');           // cnpj da empresa
        $agencia                    = $read_boleto_view['boleto_agencia'];                     // agencia
        $dv_agencia                 = '';                        // digito verificador da agencia
        $codigo_beneficiario        = $read_boleto_view['boleto_conta_cedente'];                   // Codigo do cedente / beneficiario
        $empresa_beneficario        = GetEmpresa('empresa_nome_fantasia');        // nome da empresa
        $numero_sequencial_arquivo  = 4;                          // Nº remessa tem que ser sequencial e unico
        $xid_remessa                = picture_9($numero_sequencial_arquivo,7); // Nº da remessa
        $arquivo                    = "E".$xid_remessa.".REM";    // nome do arquivo de remessa a ser gerado
        $filename                   = $arquivo;                   // nome do arquivo de remessa a ser gerado


        # ******************************************************************************************************************************************************************
        # NAO ALTERAR ==> CRIANDO O CONTEUDO DO ARQUIVO
        # ******************************************************************************************************************************************************************
        # REGISTRO HEADER - ( TIPO 0 )
        # PARTE 1
        # ******************************************************************************************************************************************************************

        $header .= '104';                                       // 01.0 -> Cod. do banco no caso da caixa = 104 
        $header .= complementoRegistro(4,"zeros");              // 02.0 -> Cod. do lote
        $header .= complementoRegistro(1,"zeros");              // 03.0 -> Tipo de Registro
        $header .= complementoRegistro(9,"brancos");            // 04.0 -> CNAB literal remessa escr. extenso 003 009 X(07)
        $header .= '2';                                         // 05.0 -> Tipo de inscrição do beneficiario : um se pessoa fisico (1) ou juridica (2)
        $header .= picture_9($cpf_cnpj,14);                     // 06.0 -> Nº de Inscrição do  Beneficiario cpf ou cnpj
        $header .= complementoRegistro(20,"zeros");             // 07.0 -> Uso exclusivo da caixa, preencher com zeros
        $header .= picture_9($agencia,5);                       // 08.0 -> Cod. da agencia mantenedora da conta
        $header .= picture_9($dv_agencia,1);                    // 09.0 -> Digito verificador
        $header .= picture_9($codigo_beneficiario,6);           // 10.0 -> Cod. do beneficiário fornecido pelo banco - Nº do cedente
        $header .= complementoRegistro(7,"zeros");              // 11.0 -> Uso exclusivo da caixa, preencher com 7 zeros
        $header .= complementoRegistro(1,"zeros");              // 12.0 -> Uso exclusivo da caixa, preencher com 1 zeros
        $header .= picture_x($empresa_beneficario,30);          // 13.0 -> Nome da empresa
        $header .= picture_x('CAIXA ECONOMICA FEDERAL',30);     // 14.0 -> Nome do banco, neste caso: CAIXA ECONOMICA FEDERAL ate completar 30 espacos
        $header .= complementoRegistro(10,"brancos");           // 15.0 -> 10 espaços em banco
        $header .='1';                                          // 16.0 -> Cod. (1) = Remessa ou (2) = Retorno.
        $header .= $DATA['DIA'].$DATA['MES'].$DATA['ANO'];      // 17.0 -> Data da geracao arquivo 
        $header .= $HORA1;                                      // 18.0 -> Hora da geracao arquivo 
        $header .= picture_9($numero_sequencial_arquivo,6);     // 19.0 -> Sequencial do arquivo um numero novo para cada arquivo de remessa que for gerado
        $header .='050';                                        // 20.0 -> Nova versao da leitura
        $header .= complementoRegistro(5,"zeros");              // 21.0 -> Densidade de Gravacao do Arquivo
        $header .= complementoRegistro(20,"brancos");           // 22.0 -> Filler
        $header .= picture_x('REMESSA-PRODUCAO',20);               // 23.0 -> Preencher com ‘REMESSA-TESTE' na fase de testes(simulado) ou REMESSA-PRODUCAO quando OK
        $header .= complementoRegistro(4,"brancos");            // 24.0 -> Preencher com espacos
        $header .= complementoRegistro(25,"brancos");           // 25.0 -> Preencher com espacos
        $header .= chr(13).chr(10);                             // QUEBRA DE LINHA

        # ******************************************************************************************************************************************************************
        # NAO ALTERAR ==> CRIANDO O CONTEUDO DO ARQUIVO
        # ******************************************************************************************************************************************************************
        // DESCRICAO DE REGISTRO - ( TIPO 1 )
        // HEADER DE LOTE DE ARQUIVO REMESSA
        // PARTE 2
        # ******************************************************************************************************************************************************************

        $header_lote .= '104';                                       // 01.1 -> Cod. do banco, neste caso = 104
        $header_lote .= picture_9($lote_servico,4);                  // 02.1 -> Lote de servico = igual ao campo 02.1 do header acima
        $header_lote .='1';                                          // 03.1 -> Preencher '1’ (equivale a Header de Lote)
        $header_lote .='R';                                          // 04.1 -> Preencher ‘R’ (equivale a Arquivo Remessa)
        $header_lote .='01';                                         // 05.1 -> Preencher com ‘01', se Cobrança Registrada; ou ‘02’, se Cobrança Sem Registro/Serviços
        $header_lote .= complementoRegistro(2,"zeros");              // 06.1 -> Preencher com zeros
        $header_lote .='030';                                        // 07.1 -> No. da versão do layout. Preencher com 030
        $header_lote .= complementoRegistro(1,"brancos");            // 08.1 -> Preencher com espacos
        $header_lote .= '2';                                         // 09.1 -> Tipo de inscrição do beneficiario : um se pessoa fisico (1) ou juridica (2)
        $header_lote .= picture_9($cpf_cnpj,15);                     // 10.1 -> CNPJ = Número de Inscrição do  Beneficiário cpf ou cnpj
        $header_lote .= picture_9($codigo_beneficiario,6);           // 11.1 -> COD. CEDENTE ou COD. DO CONVENIO NO BANCO = código do beneficiário fornecido pelo banco 
        $header_lote .= complementoRegistro(14,"zeros");             // 11.1 -> Uso exclusivo da caixa, preencher com zeros
        $header_lote .= picture_9($agencia,5);                       // 12.1 -> Agencia mantenedora da conta
        $header_lote .= picture_9($dv_agencia,1);                    // 13.1 -> Digito verificador
        $header_lote .= picture_9($codigo_beneficiario,6);           // 14.1 -> CEDENTE = Cod do beneficiário fornecido pelo banco 
        $header_lote .= complementoRegistro(7,"zeros");              // 15.1 -> Cod fornecido pela CAIXA/Gráfica,utilizado se boleto personalizado; do contrário,preencher com zeros
        $header_lote .= complementoRegistro(1,"zeros");              // 16.1 -> Uso exclusivo da caixa
        $header_lote .= picture_x($empresa_beneficario,30);          // 17.1 -> Nome da empresa
        $header_lote .= complementoRegistro(40,"brancos");           // 18.1 -> mensagem 1
        $header_lote .= complementoRegistro(40,"brancos");           // 19.1 -> mensagem 2
        $header_lote .= picture_9($numero_sequencial_arquivo,8);     // 20.1 -> Controle de cobranca - No. da remessa, mesmo que 19.0
        $header_lote .= $DATA['DIA'].$DATA['MES'].$DATA['ANO'];      // 21.1 -> Controle de cobranca - Data de gravacao do arquivo de remessa
        $header_lote .= complementoRegistro(8,"zeros");              // 22.1 -> Data do credito. Preencher com zeros
        $header_lote .= complementoRegistro(33,"brancos");           // 23.1 -> CNAB. Preencher com espacos 
        $header_lote .= chr(13).chr(10);                             // Quebra de linha

        # ******************************************************************************************************************************************************************
        # NAO ALTERAR ==> CRIANDO O CONTEUDO DO ARQUIVO
        // *****************************************************************************************************************
        // DADOS DOS CLIENTES PARA TESTE
        // *****************************************************************************************************************

        $num_seg_linha_p_q_r = 1;

        $total_boletos = 1;  // quantidade de boletos a serem registrados no arquivo de remessa



        $read_financeiro = Read('financeiro', "WHERE financeiro_tipo = 'CR' {$_SESSION['financeiro_load']} ".$order_by."");
        if(NumQuery($read_financeiro) > '0'){
            foreach($read_financeiro as $read_financeiro_view){
                $UpdateRemessa['financeiro_remessa'] = '1';
                Update('financeiro', $UpdateRemessa, "WHERE financeiro_id = '".$read_financeiro_view['financeiro_id']."'");
                $read_contato = Read('contato', "WHERE contato_id = '".$read_financeiro_view['financeiro_id_contato']."'");
                if(NumQuery($read_contato) > '0'){
                    foreach($read_contato as $read_contato_view);
                    $cpf_cnpj_contato = str_replace('/', '', $read_contato_view['contato_cpf_cnpj']);
                    $cpf_cnpj_contato = str_replace('.', '', $cpf_cnpj_contato);
                    $cpf_cnpj_contato = str_replace('-', '', $cpf_cnpj_contato);
                    if(strlen($cpf_cnpj_contato) == '14'){
                        $tipo_cpf_cnpj_contato = '2';
                    }else{
                        $tipo_cpf_cnpj_contato = '1';
                    }
                    $cep_sem_hifem = str_replace('-', '', $read_contato_view['contato_cep']);
                    $primeiro_cep = substr($cep_sem_hifem, 0,5);
                    $segundo_cep  = substr($cep_sem_hifem, 6,3);
                }  // loop para obter os dados dos boletos da sua base de dados e montar as linhas P, Q e R do arquivo de remessa

                // *****************************************************************************************************************
                // DESCRICAO DE REGISTRO - ( TIPO 3 ) , Segmento "P":
                // DADOS DO TITULO
                // PARTE 3
                // TAMANHO DO REGISTRO = 240 CARACTERES
                // *****************************************************************************************************************

                // *****************************************************************************************************************
                // ALTERE DE ACORDO COM SEU BANCO DE DADOS - REGISTRO DETALHE (OBRIGATORIO)  - VARIAVEIS DO TITULO E DO PAGADOR
                // *****************************************************************************************************************

                // variaveis do titulo ou boleto -> pode alterar

                $nosso_numero                    = $read_financeiro_view['financeiro_nosso_numero'];         // nosso numero do seu boleto
                $numero_documento                = $nosso_numero;    // nosso numero do seu boleto
                $data_vencimento_boleto          = str_replace('/', '', FormDataBr($read_financeiro_view['financeiro_data_vencimento']));       // data de vencimento do boleto
                $data_multa                      = '06022017';       // data da multa
                $data_emissao_boleto             = str_replace('/', '', FormDataBr($read_financeiro_view['financeiro_data_lancamento']));       // data da emissao do boleto
                $valor_boleto                    = $read_financeiro_view['financeiro_valor'];          // valor nominal do titulo ==> 35000 ==> // 350,00
                $data_juros                      = '06022017';       // data a partir daqual incidira juros
                $i = 2;
                $valor_juros                     = '000';           // 0034 ou 350,00, depende se em valor ou em taxa
                $data_desconto                   = '01012016';       // desconto ate o dia.......
                $valor_desconto                  = '000';            // valor expresso em porcentagem
                $valor_iof                       = '000';            // valor do iof
            $valor_abatimento                = '000';            // valor do abatimento que nao e a mesma coisa que desconto

                // variaveis ou dados do pagador do boleto (cliente) -> pode alterar

                $tipo_inscricao_pagador          = $tipo_cpf_cnpj_contato;              // tipo de inscrição do pagador 1 pessoa fisica 2 pessoa juridica
                $numero_inscricao_pagador        = $cpf_cnpj_contato;    // cpf
                $nome_pagador                    = $read_contato_view['contato_nome_razao']; // nome
                $endereco_pagador                = $read_contato_view['contato_endereco']; // endereco
                $bairro_pagador                  = $read_contato_view['contato_bairro'];         // bairro
                $cep_pagador                     = $primeiro_cep;          // cep prefixo
                $cep_pagador_sufixo              = $segundo_cep;            // cep sufixo
                $cidade_pagador                  = $read_contato_view['contato_cidade'];       // cidade
                $estado_pagador                  = $read_contato_view['contato_estado'];             // estado
                $email_pagador                   = $read_contato_view['contato_email'];  // email


                // NAO ALTERAR ==> Montando a linha P do boleto do loop

                $linha_p .= '104';                                   // 01.3P -> CCONTROLE. COD. DO BANCO, Neste caso = 104
                $linha_p .= picture_9($lote_servico,4);              // 02.3P -> CONTROLE. LOTE DE SERVICO. TEM QUE SER IGUAL AO HEADER DE LOTE DO CAMPO 02.1 
                $linha_p .= '3';                                     // 03.3P -> CONTROLE. TIPO DE REGISTRO. Preencher com 3 (EQUIVALE A DETALHE DO LOTE)
                $linha_p .= picture_9($num_seg_linha_p_q_r,5);       // 04.3P -> SERVICO. Nº Sequencial do Registro no Lote. (G038). EVOLUIR DE 1 EM 1 PARA CADA SEGMENTO DO LOTE
                $linha_p .= 'P';                                     // 05.3P -> SERVICO. Cód. Segmento do Registro Detalhe, PREENCHER P
                $linha_p .= complementoRegistro(1,"brancos");        // 06.3P -> SERVICO. Preencher com espaco
                $linha_p .= picture_9('01',2);                       // 07.3P -> SERVICO. Cod. de movimento remessa. 1=entrada/2=baixa/6=alterar vencimento (C004)
                $linha_p .= picture_9($agencia,5);                   // 08.3P -> COD. ID. BENEFICIARIO. Agencia mantenedora da conta
                $linha_p .= picture_9($dv_agencia,1);                // 09.3P -> COD. ID. BENEFICIARIO. Digito verificador
                $linha_p .= picture_9($codigo_beneficiario,6);       // 10.3P -> COD. ID. BENEFICIARIO. Cod. do convenio no banco ou Cód. CEDENTE. 
                $linha_p .= complementoRegistro(8,"zeros");          // 11.3P -> COD. ID. BENEFICIARIO. Uso Exclusivo CAIXA - Filler
                $linha_p .= complementoRegistro(3,"zeros");          // 12.3P -> USO EXCLUSIVO DA CAIXA. Filler
                $linha_p .= picture_9($carteira,2);                  // 13.3P -> CARTEIRA/NOSSO NUMERO. Modalidade de Carteira = tipo de carteira do boleto (14 OU 24) com ou sem registro
                $linha_p .= picture_9($nosso_numero,15);             // 13.3P -> CARTEIRA/NOSSO NUMERO. Identificacao do titulo no banco = Nosso numero 
                $linha_p .='1';                                      // 14.3P -> codigo da carteira (1) equivale a cobrança simples (C006)
                $linha_p .='1';                                      // 15.3P -> Forma de Cadastramento do Título no Banco. 1=cobranca registrada / 2=cobranca sem registro
                $linha_p .='2';                                      // 16.3P -> Tipo de Documento - Preencher '2’ (equivale a Escritural)
                $linha_p .='2';                                      // 17.3P -> Identificação da Emissao do boleto. 1 = Banco emite/ 2 = Cliente emite (C009)
                $linha_p .='0';                                      // 18.3P -> Identificacao da Entrega do boleto. (C010)
                $linha_p .= picture_x($numero_documento,11);         // 19.3P -> Numero do documento de cobranca. (C011) = meu numero de boleto
                $linha_p .= complementoRegistro(4,"brancos");        // 19.3P -> espacos
                $linha_p .= picture_9($data_vencimento_boleto,8);    // 20.3P -> Data de vencimento do título, no formato DDMMAAAA (Dia, Mêse Ano);
                $linha_p .= picture_9($valor_boleto,15);             // 21.3p -> Valor nominal do título,utilizando 2 casas decimais (exemplo:título de valor 530,44 - preencher 0000000053044)
                $linha_p .= complementoRegistro(5,"zeros");          // 22.3P -> Agência Encarregada da Cobrança (Preencher com zeros)
                $linha_p .= complementoRegistro(1,"zeros");          // 23.3P -> DV (Preencher com zeros)
                $linha_p .= picture_x('99',2);                       // 24.3P -> Espécie do Título (NF: NOTA FISCAL, DD:DOCUMENTO DE DIVIDA, CPR: CÉDULA DE PRODUTO RURAL, OU:OUTROS = 99
                $linha_p .= picture_x('N',1);                        // 25.3P -> Aceite. preencher com ‘A’ (Aceite) ou‘N’ (Não Aceite)
                $linha_p .= picture_9($data_emissao_boleto,8);       // 26.3P -> Data de emissjão do título, no formato DDMMAAAA (Dia, Mêse Ano);
                $linha_p .= picture_9('2',1);                        // 27.3P -> Juros de mora;preencher com o tipo de preferência:‘1’ (Valor por Dia); ou ‘2’ (Taxa Mensal); ou ‘3’(Isento)
                $linha_p .= picture_9($data_juros,8);                // 28.3P -> Data para início da cobrança de Juros de Mora, no formato DDMMAAAA (Dia, Mês e Ano). 0 = dia posterior venc. 
                                                                     //          devendo ser maior que a Data de Vencimento; ATENÇÃO, caso a informação seja inválida ou nãoinformada, 
                                                                                                         //          o sistema assumirá data igual à Datade Vencimento + 1
                $linha_p .= picture_9($valor_juros,15);              // 29.3P -> Juros de Mora por Dia/Taxa

                // Se houver taxa de desconto nesse boleto

                if( $valor_desconto >0 ){
                        $linha_p .= picture_9('2',1);                    // 30.3P -> DESCONTO 1. Cod. do desconto. tipo desconto Pagador / 0=Sem Desconto / 1=Valor Fixo / 2 = Percentual
                        $linha_p .= picture_9($data_desconto,8);         // 31.3P -> DESCONTO 1. Data do desconto
                        $linha_p .= picture_9($valor_desconto,15);       // 32.3P -> DESCONTO 1. Valor/percentual do desconto a ser concedido
                }else{
                        $linha_p .= picture_9('0',1);                    // 30.3P -> DESCONTO 1. Cod. do desconto. tipo desconto Pagador / 0=Sem Desconto / 1=Valor Fixo / 2 = Percentual
                        $linha_p .= picture_9('0',8);                    // 31.3P -> DESCONTO 1. Data do desconto
                        $linha_p .= picture_9('0',15);                   // 32.3P -> DESCONTO 1. Valor/percentual do desconto a ser concedido
                }

                $linha_p .= picture_9($valor_iof,15);                // 33.3P -> VLR. IOF. Valor do IOF a ser recolhido
                $linha_p .= picture_9($valor_abatimento,15);         // 34.3P -> Valor do abatimento
                $linha_p .= picture_x($numero_documento,25);         // 35.3P -> Uso empresa cedente. Identificacao do titulo na empresa. Identico ao campo 19.3P
                $linha_p .= '3';                                     // 36.3P -> Código para Protesto. 1 = protestar / 3 = nao protestar
                $linha_p .= '00';                                    // 37.3P -> Prazo para protesto. Numero de dias para  Protesto
                $linha_p .= '1';                                     // 38.3P -> Código p/ Baixa/Devolução: Preencher - vencido: '1’ (Baixar/ Devolver) ou ‘2’ (Não Baixar / Não Devolver
                $linha_p .= picture_9('030',3);                      // 39.3P -> Prazo p/ baixa/devolucao. Numero de dias para baixa/devolucao
                $linha_p .= picture_9('9',2);                        // 40.3P -> Codigo da moeda. 09 = REAL
                $linha_p .= complementoRegistro(10,"zeros");         // 41.3P -> Preencher com zeros
                $linha_p .= complementoRegistro(1,"brancos");        // 42.3P -> Preencher com espacos
                $linha_p .= chr(13).chr(10);                         // essa é a quebra de linha

                $num_seg_linha_p_q_r++;

                $qtd_titulos++;

                $total_valor+=$valor_boleto;



                // NAO ALTERAR ==> Montando a linha Q do boleto do loop

                // *****************************************************************************************************************
                // DESCRICAO DE REGISTRO - ( TIPO 3 ) , Segmento "Q":
                // DADOS DO PAGADOR E SACADOR/AVALISTA
                // PARTE 4
                // TAMANHO DO REGISTRO = 240 CARACTERES
                // *****************************************************************************************************************

                $linha_q .= '104';                                   // 01.3Q -> Cod. Banco. Caixa = 104 
                $linha_q .= picture_9($lote_servico,4);              // 02.3Q -> Lote de serviço
                $linha_q .= '3';                                     // 03.3Q -> tipo de registro. Equivalente a detalhe de lote. preencher '3'
                $linha_q .= picture_9($num_seg_linha_p_q_r,5);       // 04.3Q -> Nº Sequencial do Registro no Lote
                $linha_q .= 'Q';                                     // 05.3Q -> Cód. Segmento do Registro Detalhe
                $linha_q .= complementoRegistro(1,"brancos");        // 06.3Q -> Espaco
                $linha_q .= picture_9('01',2);                       // 07.3Q -> Cod de Movimento Remessa
                $linha_q .= $tipo_inscricao_pagador;                 // 08.3Q -> Tipo de Inscricao do Pagador (1) CPF (pessoa física) (2) CNPJ Pessoa jurídica
                $linha_q .= picture_9($numero_inscricao_pagador,15); // 09.3Q -> cpf ou cnpj
                $linha_q .= picture_x($nome_pagador,40);             // 10.3Q -> Nome do pagador
                $linha_q .= picture_x($endereco_pagador,40);         // 11.3Q -> Endereco do pagador
                $linha_q .= picture_x($bairro_pagador,15);           // 12.3Q -> Bairro
                $linha_q .= picture_9($cep_pagador,5);               // 13.3Q -> Cep
                $linha_q .= picture_9($cep_pagador_sufixo,3);        // 14.3Q -> Cep (sufixo)
                $linha_q .= picture_x($cidade_pagador,15);           // 15.3Q -> Cidade
                $linha_q .= picture_x($estado_pagador,2);            // 16.3Q -> UF
                $linha_q .= '0';                                     // 17.3Q -> Tipo de Inscrição do sacador AVALISTA (0) nenhum (1) CPF (pessoa física) (2) CNPJ Pessoa jurídica
                $linha_q .= picture_9('0',15);                       // 18.3Q -> CPF ou CNPJ do Sacador avalista
                $linha_q .= complementoRegistro(40,"brancos");       // 19.3Q -> nome do sacador avalista
                $linha_q .= complementoRegistro(3,"brancos");        // 20.3Q -> Zeros
                $linha_q .= complementoRegistro(20,"brancos");       // 21.3Q -> Espaco
                $linha_q .= complementoRegistro(8,"brancos");        // 22.3Q -> Espaco

                $tam_linha_q  = strlen($linha_q);

                $zeros_rest_2 = 240 - $tam_linha_q;

                $linha_q      = $linha_q.complementoRegistro($zeros_rest_2,"zeros");

                $linha_q .= chr(13).chr(10);                         // essa é a quebra de linha

                $num_seg_linha_p_q_r++;


                // *****************************************************************************************************************
                // DESCRICAO DE REGISTRO - ( TIPO 3 ) , Segmento "R":
                // DADOS DO PAGADOR E SACADOR/AVALISTA
                // PARTE 4
                // TAMANHO DO REGISTRO = 240 CARACTERES
                // *****************************************************************************************************************

                // NAO ALTERAR ==> Montando a linha R do boleto do loop

                $linha_r .= '104';                                   // 01.3R -> Cod. Banco. Caixa = 104 
                $linha_r .= picture_9($lote_servico,4);              // 02.3R -> Lote de serviço
                $linha_r .= '3';                                     // 03.3R -> tipo de registro. Equivalente a detalhe de lote. preencher '3'
                $linha_r .= picture_9($num_seg_linha_p_q_r,5);       // 04.3R -> Nº Sequencial do Registro no Lote
                $linha_r .= 'R';                                     // 05.3R -> Cód. Segmento do Registro Detalhe
                $linha_r .= complementoRegistro(1,"brancos");        // 06.3R -> Espaco
                $linha_r .= picture_9('01',2);                       // 07.3R -> Cod. Movimento Rem = 01 => Entrada de titulo Nota Explicativa: (C004)
                $linha_r .= '0';                                     // 08.3R -> DESCONTO-2. COD. DESCONTO / 0=sem / 1=valor fixo / 2=valor percentual
            $linha_r .= picture_9('0',8);                        // 09.3R -> DESCONTO-2. DATA DESCONTO 
            $linha_r .=	picture_9('0',15);                       // 10.3R -> DESCONTO-2. VALOR DO DESCONTO
                $linha_r .= '0';                                     // 11.3R -> DESCONTO-3. COD. DESCONTO / 0=sem / 1=valor fixo / 2=valor percentual
            $linha_r .= picture_9('0',8);                        // 12.3R -> DESCONTO-3. DATA DESCONTO 
            $linha_r .=	picture_9('0',15);                       // 13.3R -> DESCONTO-3. VALOR DO DESCONTO
                $linha_r .= '2';                                     // 14.3R -> MULTA. COD. DESCONTO / 0=sem / 1=valor fixo / 2=valor percentual
            $linha_r .= picture_9($data_juros,8);                // 15.3R -> MULTA. DATA DA MULTA 
            $linha_r .=	picture_9('200',15);                     // 16.3R -> MULTA. VALOR DO DESCONTO
                $linha_r .= complementoRegistro(10,"brancos");       // 17.3R -> INFORMACAO AO PAGADOR - preencher com espacos
                $linha_r .= complementoRegistro(40,"brancos");       // 18.3R -> INFORMACAO 3 - mensagem 3
                $linha_r .= complementoRegistro(40,"brancos");       // 19.3R -> INFORMACAO 4 - Mensagem 4
                $linha_r .= complementoRegistro(50,"brancos");       // 20.3R -> e-mail pagador - e-mail pagador para envio de informacoes
                $linha_r .= complementoRegistro(11,"brancos");       // 21.3R -> Zeros
                $linha_r .= chr(13).chr(10);                         // essa é a quebra de linha

                $lote_sequencial++;

                $num_seg_linha_p_q_r++;

                $conteudo_meio .= $linha_p.$linha_q.$linha_r;

                $linha_p = "";
                $linha_q = "";
                $linha_r = "";


        }
        }// final do LOOP para obter os dados dos boletos e dos clientes e montar o conteudo do meio do arquivo (linhas P, Q e R)



        // *****************************************************************************************************************
        // NAO ALTERAR ==> Montando o segmento 5 do arquivo de remessa => rodape ou trailer de lote => final do lote
        // *****************************************************************************************************************
        // DESCRICAO DE REGISTRO TIPO "5"
        // TRAILER DE LOTE DE ARQUIVO REMESSA
        // PARTE 5 - PNULTIMA LINHA DO ARQUIVO
        // TAMANHO DO REGISTRO = 240 CARACTERES
        // *****************************************************************************************************************

        $linha_5 .= '104';                                   // 01.5 -> COD. DO BANCO. CAIXA = 104
        $linha_5 .= picture_9($lote_servico,4);              // 02.5 -> CONTROLE -> Lote de servico equivalente a 02.1
        $linha_5 .= '5';                                     // 03.5 -> CONTROLE -> Tipo de registro, preencher com '5'
                                                                                                                 //         equivalente a (Trailer de Lote).
        $linha_5 .= complementoRegistro(9,"brancos");        // 04.9 -> CNAB. FIller, preencher com espacos

        $qtd_registros = ($lote_sequencial*3)+2-1-1;
        $linha_5 .= picture_9(($qtd_registros-1),6);         // 05.5 -> Qtd. de registros no lote. Somatoria dos registros
                                                                                                                 //         de tipo 1, 3 e 5 ( obs alex = total de linhas -2 )

        $linha_5 .= picture_9($qtd_titulos,6);               // 06.5 -> TOTALIZACAO COBRANCA SIMPLES - Preencher com a qtd.
                                                                                                                 //         de titulos informados no lote
        $linha_5 .=	picture_9($total_valor,17);              // 07.5 -> TOTALIZACAO COBRANCA SIMPLES - Preencher com o valor
                                                                                                                 //         total de titulos informados no lote   
        $linha_5 .= complementoRegistro(6,"zeros");          // 08.5 -> Preencher com zeros     
        $linha_5 .= complementoRegistro(17,"zeros");         // 09.5 -> Preencher com zeros     
        $linha_5 .= complementoRegistro(6,"zeros");          // 10.5 -> Preencher com zeros     
        $linha_5 .= complementoRegistro(17,"zeros");         // 11.5 -> Preencher com zeros     
        $linha_5 .= complementoRegistro(31,"brancos");       // 12.5 -> CNAB -> Filler -> Preencher com espacos
        $linha_5 .= complementoRegistro(117,"brancos");      // 13.5 -> CNAB -> Filler -> Preencher com espacos

        $linha_5 .= chr(13).chr(10);                         // essa é a quebra de linha

        // *****************************************************************************************************************
        // NAO ALTERAR ==> Montando o segmento 9 do arquivo de remessa => rodape final do arquivo
        // *****************************************************************************************************************
        // DESCRICAO DE REGISTRO TIPO "9"
        // TRAILER DE ARQUIVO REMESSA
        // PARTE 5 - FINAL OU RODAPE DO ARQUIVO
        // TAMANHO DO REGISTRO = 240 CARACTERES
        // *****************************************************************************************************************

        $linha_9 .= '104';                                   // 01.9 -> COD. DO BANCO. CAIXA = 104
        $linha_9 .= '9999';                                  // 02.9 -> lote de serviço. Preencher '9999'
        $linha_9 .= '9';                                     // 03.9 -> Tipo de registro. Preencher '9'
        $linha_9 .= complementoRegistro(9,"brancos");        // 04.9 -> CNAB. FIller
        $qtd_lotes_arquivo = $lote_servico;
        $linha_9 .= picture_9($qtd_lotes_arquivo,6);         // 05.9 -> Quantidade de lotes do arquivo

        $qtd_reg_arq = ($lote_sequencial*3)+2-1+1-1;                 
        $linha_9 .= picture_9($qtd_reg_arq,6);               // 06.9 -> Quantidade de registros no arquivo

        $linha_9 .= complementoRegistro(6,"brancos");        // 07.9 -> Espacos
        $linha_9 .= complementoRegistro(105,"brancos");      // 08.9 -> Espacos
        $linha_9 .= complementoRegistro(100,"brancos");      // ajuste alex para completar as 240 posicoes dessa linha de rodape

        $conteudo = $header.$header_lote.$conteudo_meio.$linha_5.$linha_9;

        // *****************************************************************************************************************



        // *****************************************************************************************************************
        # CRIAR O ARQUIVO FISICO DA REMESSA
        // *****************************************************************************************************************

        if (!$handle = fopen($filename, 'w+')){
                erro("<br>&nbsp;Não foi possível abrir o arquivo ($filename)");
        }

        if (fwrite($handle, "$conteudo") === FALSE){
                echo "<br>&nbsp;Não foi possível escrever no arquivo ($filename)";
        }

        fclose($handle);

        //echo "<br>&nbsp;Arquivo de remessa gerado com sucesso!";

        // TRANSFERIR O ARQUIVO PARA O SERVIDOR
        $nome_pasta_arquivo = '../_uploads/'.GetEmpresa('empresa_nome_pasta').'/remessa/'.$filename.'';
        
        $remessa['remessa_arquivo'] = $nome_pasta_arquivo;
        $remessa['remessa_data_hora'] = date('Y-m-d H:i:s');
        $remessa['remessa_id_boleto'] = $boleto;
        Create('remessa', $remessa);
        $xdestino = $nome_pasta_arquivo;
        $xorigem = $filename;

        @copy($xorigem,$xdestino);

        $arquivo = $filename;
        $json_remessa = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso, sua remessa se encontra no disco virtual!',
            'buttons' => '<a href="javascript::" onclick="carrega_pagina(\'financeiro\', \'index.php?OP=CR\');" data-dismiss="modal" class="btn btn-primary">Sair</a>'
        );
        echo json_encode($json_remessa);
    }elseif($banco == '10'){
        include '../_remessa/CnabPHP-master/vendor/autoload.php';
        $codigo_banco = Cnab\Banco::CEF;
        $arquivo = new Cnab\Remessa\Cnab240\Arquivo($codigo_banco);
        $read_boleto = Read('boleto', "WHERE boleto_id = '".$boleto."'");
        if(NumQuery($read_boleto) > '0'){
            foreach($read_boleto as $read_boleto_view);
        }
        $arquivo->configure(array(
            'data_geracao'  => new DateTime(),
            'data_gravacao' => new DateTime(), 
            'nome_fantasia' => GetEmpresa('empresa_nome_fantasia'), // seu nome de empresa
            'razao_social'  => GetEmpresa('empresa_nome_razao'),  // sua razão social
            'cnpj'          => GetEmpresa('empresa_cnpj'), // seu cnpj completo
            'banco'         => $codigo_banco, //código do banco
            'logradouro'    => GetEmpresa('empresa_endereco'),
            'numero'        => GetEmpresa('empresa_numero'),
            'bairro'        => GetEmpresa('empresa_bairro'), 
            'cidade'        => GetEmpresa('empresa_cidade'),
            'uf'            => GetEmpresa('empresa_estado'),
            'cep'           => GetEmpresa('empresa_cep'),
            'agencia'       => $read_boleto_view['boleto_agencia'], 
            'conta'         => $read_boleto_view['boleto_conta'], // número da conta
            'conta_dac'     => $read_boleto_view['boleto_conta_digito'], // digito da conta
        ));

        // você pode adicionar vários boletos em uma remessa
        $read_financeiro = Read('financeiro', "WHERE financeiro_tipo = 'CR' {$_SESSION['financeiro_load']} ".$order_by."");
        if(NumQuery($read_financeiro) > '0'){
            foreach($read_financeiro as $read_financeiro_view){
                $UpdateRemessa['financeiro_remessa'] = '1';
                Update('financeiro', $UpdateRemessa, "WHERE financeiro_id = '".$read_financeiro_view['financeiro_id']."'");
                $read_contato = Read('contato', "WHERE contato_id = '".$read_financeiro_view['financeiro_id_contato']."'");
                if(NumQuery($read_contato) > '0'){
                    foreach($read_contato as $read_contato_view);
                    $cpf_cnpj_contato = str_replace('/', '', $read_contato_view['contato_cpf_cnpj']);
                    $cpf_cnpj_contato = str_replace('.', '', $cpf_cnpj_contato);
                    $cpf_cnpj_contato = str_replace('-', '', $cpf_cnpj_contato);
                    if(strlen($cpf_cnpj_contato) == '14'){
                        $tipo_cpf_cnpj_contato = 'cnpj';
                    }else{
                        $tipo_cpf_cnpj_contato = 'cpf';
                    }
                }
                $arquivo->insertDetalhe(array(
                    'cod_movimento'     => '01',
                    'codigo_ocorrencia' => 1, // 1 = Entrada de título, futuramente poderemos ter uma constante
                    'nosso_numero'      => $read_financeiro['financeiro_nosso_numero'],
                    'numero_documento'  => $read_financeiro_view['financeiro_nosso_numero'],
                    'carteira'          => $read_boleto_view['carteira'],
                    'especie'           => Cnab\Especie::CEF_DUPLICATA_DE_PRESTACAO_DE_SERVICOS, // Você pode consultar as especies Cnab\Especie
                    'valor'             => $read_financeiro_view['financeiro_valor'], // Valor do boleto
                    'instrucao1'        => 2, // 1 = Protestar com (Prazo) dias, 2 = Devolver após (Prazo) dias, futuramente poderemos ter uma constante
                    'instrucao2'        => 0, // preenchido com zeros
                    'sacado_nome'       => $read_contato_view['contato_nome_razao'], // O Sacado é o cliente, preste atenção nos campos abaixo
                    'sacado_tipo'       => $tipo_cpf_cnpj_contato, //campo fixo, escreva 'cpf' (sim as letras cpf) se for pessoa fisica, cnpj se for pessoa juridica
                    'sacado_cpf'        => $read_contato_view['contato_cpf_cnpj'],
                    'sacado_logradouro' => $read_contato_view['contato_endereco'],
                    'sacado_bairro'     => $read_contato_view['contato_bairro'],
                    'sacado_cep'        => $read_contato_view['contato_cep'], // sem hífem
                    'sacado_cidade'     => $read_contato_view['contato_cidade'],
                    'sacado_uf'         => $read_contato_view['contato_estado'],
                    'data_vencimento'   => new DateTime($read_financeiro_view['financeiro_data_vencimento']),
                    'data_cadastro'     => new DateTime($read_financeiro_view['financeiro_data_lancamento']),
                    'juros_de_um_dia'     => 0, // Valor do juros de 1 dia'
                    'data_desconto'       => new DateTime('2014-06-01'),
                    'valor_desconto'      => 0, // Valor do desconto
                    'prazo'               => 0, // prazo de dias para o cliente pagar após o vencimento
                    'taxa_de_permanencia' => '0', //00 = Acata Comissão por Dia (recomendável), 51 Acata Condições de Cadastramento na CAIXA
                    'mensagem'            => $read_financeiro_view['financeiro_descricao'],
                    'data_multa'          => new DateTime('2014-06-09'), // data da multa
                    'valor_multa'         => 1.00 // valor da multa
                ));
            }
        }

        // para salvar
        $nome_arquivo = md5(date('Y-m-dH:i:s').rand(9,999999999));
        $nome_pasta_arquivo = '../_uploads/'.GetEmpresa('empresa_nome_pasta').'/remessa/'.$nome_arquivo.'.txt';
        $arquivo->save('../_uploads/'.GetEmpresa('empresa_nome_pasta').'/remessa/'.$nome_arquivo.'.txt');
        
        $remessa['remessa_arquivo'] = $nome_pasta_arquivo;
        $remessa['remessa_data_hora'] = date('Y-m-d H:i:s');
        $remessa['remessa_id_boleto'] = $boleto;
        Create('remessa', $remessa);
        $json_remessa = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso, sua remessa se encontra no disco virtual!',
            'buttons' => '<a href="javascript::" onclick="carrega_pagina(\'financeiro\', \'index.php?OP=CR\');" data-dismiss="modal" class="btn btn-primary">Sair</a>'
        );
        echo json_encode($json_remessa);
    }elseif($banco == '0'){
        include '../_remessa/OpenCnabPHP-master/autoloader.php';
        include '../_remessa/OpenCnabPHP-master/vendor/autoload.php';
        $arquivo = new Remessa(104,'cnab240_SIGCB',array(
            'nome_empresa' =>"Empresa ABC", // seu nome de empresa
            'tipo_inscricao'  => 2, // 1 para cpf, 2 cnpj 
            'numero_inscricao' => $empresa->empresas_cnpjcpf, // seu cpf ou cnpj completo
            'agencia'       => '1234', // agencia sem o digito verificador 
            'agencia_dv'    => 1, // somente o digito verificador da agencia 
            'conta'         => '12345', // número da conta
            'conta_dac'     => 1, // digito da conta
            'codigo_beneficiario'     => '123456', // codigo fornecido pelo banco
            'numero_sequencial_arquivo'     => 1, // sequencial do arquivo um numero novo para cada arquivo gerado
        ));
        $lote  = $arquivo->addLote(array('tipo_servico'=> 1)); // tipo_servico  = 1 para cobrança registrada, 2 para sem registro

        $lote->inserirDetalhe(array(
            'codigo_ocorrencia' => 1, //1 = Entrada de título, para outras opçoes ver nota explicativa C004 manual Cnab_SIGCB na pasta docs
            'nosso_numero'      => 1, // numero sequencial de boleto
            'seu_numero'        => 1,// se nao informado usarei o nosso numero 

            /* campos necessarios somente para itau cnab400, não precisa comentar se for outro layout    */
            'carteira_banco'    => 109, // codigo da carteira ex: 109,RG esse vai o nome da carteira no banco
            'cod_carteira'      => "I", // I para a maioria ddas carteiras do itau
            /* campos necessarios somente para itau, não precisa comentar se for outro layout   */

            'especie_titulo'    => "DM", // informar dm e sera convertido para codigo em qualquer laytou conferir em especie.php
            'valor'             => 100.00, // Valor do boleto como float valido em php
            'emissao_boleto'        => 2, // tipo de emissao do boleto informar 2 para emissao pelo beneficiario e 1 para emissao pelo banco
            'protestar'        => 2, // 1 = Protestar com (Prazo) dias, 2 = Devolver após (Prazo) dias
            'nome_pagador'      => "JOSÉ da SILVA ALVES", // O Pagador é o cliente, preste atenção nos campos abaixo
            'tipo_inscricao'    => 1, //campo fixo, escreva '1' se for pessoa fisica, 2 se for pessoa juridica
            'numero_inscricao'  => '123.122.123-56',//cpf ou ncpj do pagador
            'endereco_pagador'  => 'Rua dos developers,123 sl 103',
            'bairro_pagador'     => 'Bairro da insonia',
            'cep_pagador'        => '12345-123', // com hífem
            'cidade_pagador'     => 'Londrina',
            'uf_pagador'         => 'PR',
            'data_vencimento'    => '2016-04-09', // informar a data neste formato
            'data_emissao'       => '2016-04-09', // informar a data neste formato
            'vlr_juros'          => 0.15, // Valor do juros de 1 dia'
            'data_desconto'      => '2016-04-09', // informar a data neste formato
            'vlr_desconto'       => '0', // Valor do desconto
            'prazo'              => 5, // prazo de dias para o cliente pagar após o vencimento
            'mensagem'           => 'JUROS de R$0,15 ao dia'.PHP_EOL."Não receber apos 30 dias",
            'email_pagador'         => 'rogerio@ciatec.net', // data da multa
            'data_multa'         => '2016-04-09', // informar a data neste formato, // data da multa
            'valor_multa'        => 30.00, // valor da multa
        ));        
        echo $arquivo->getText();
    }
}elseif($acao == 'load_ged'){
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
        $order_by = "ORDER BY ged_id DESC";
    }else{
        
        $sort       = addslashes($_GET['sort']);
        $sort_dir   = addslashes($_GET['sort_dir']);
        $order_by   = "ORDER BY ".$sort." ".$sort_dir."";
    }
    
    
    $read_ged_paginator = ReadComposta("SELECT ged_id FROM ged");
    $read_ged = Read('ged', "WHERE ged_id_financeiro = '".$_GET['id_financeiro']."' ".$order_by." LIMIT $inicio,$maximo");
    if(NumQuery($read_ged) > '0'){
        //PEGA QUANTOS BOTÕES PARA PAGINAÇÃO DE RESULTADOS
        $paginas = ceil(NumQuery($read_ged_paginator) / $maximo);
        //TABULATOR EXIGE ESSA INFORMAÇÃO PARA QUE PRECISE DA PAGINAÇÃO DE RESULTADOS 
        $json_ged["last_page"] = $paginas;
        foreach($read_ged as $read_ged_view){
            $json_ged['data'][] = $read_ged_view;
        }
    }else{
        $json_ged['data'] = null;
    }
    echo json_encode($json_ged);
}elseif($acao == 'enviar_ged'){
    $arquivo = $_FILES['arquivo'];

    $tipos = array('jpg', 'pdf');

    $enviar = uploadFile($arquivo, '../_uploads/'.GetEmpresa('empresa_nome_pasta').'/ged/', $tipos);

    $data['sucesso'] = false;

    if($enviar['erro']){    
        $data['msg'] = $enviar['erro'];
    }else{
        $data['sucesso'] = true;

        $data['msg'] = $enviar['caminho'];

        $ged_form['ged_arquivo']        = $enviar['caminho'];
        $ged_form['ged_data_hora']      = date('Y-m-d H:i:s');
        $ged_form['ged_id_financeiro']  = $_GET['id_financeiro'];
        Create('ged', $ged_form);
    }
    echo json_encode($data);
}elseif($acao == 'delete_ged'){
    $id_ged = addslashes($_GET['id_ged']);
    
    $read_ged = Read('ged', "WHERE ged_id = '".$id_ged."'");
    if(NumQuery($read_ged) > '0'){
        foreach($read_ged as $read_ged_view);
        unlink($read_ged_view['ged_arquivo']);
        Delete('ged', "WHERE ged_id = '".$id_ged."'");
    }
}elseif($acao == 'load_boleto_financeiro'){
    $url = addslashes($_POST['url']);
    
    $read_msg_financeiro = Read('msg_financeiro', "WHERE msg_financeiro_status = '0'");
    if(NumQuery($read_msg_financeiro) > '0'){
        echo '<select class="form-control txt_mail_msg" onchange="carrega_msg_financeiro();">';
        echo '<option value=""></option>';
        foreach($read_msg_financeiro as $read_msg_financeiro_view){
            echo '<option value="'.$read_msg_financeiro_view['msg_financeiro_id'].'">'.$read_msg_financeiro_view['msg_financeiro_assunto'].'</option>';
        }
        echo '</select>';
        echo '<input type="hidden" class="msg_financeiro_id" value="'.$url.'"/>';
    }
    
    $read_financeiro = Read('financeiro', "WHERE financeiro_id IN($url) AND financeiro_status = '0' AND financeiro_tipo = 'CR'");
    if(NumQuery($read_financeiro) > '0'){
        foreach($read_financeiro as $read_financeiro_view){
            
            
        }
    }
}elseif($acao == 'load_msg_financeiro'){
    $url = addslashes($_POST['id_msg_financeiro']);
    
    $read_msg_financeiro = Read('msg_financeiro', "WHERE msg_financeiro_status = '0' AND msg_financeiro_id = '".$url."'");
    if(NumQuery($read_msg_financeiro) > '0'){
        foreach($read_msg_financeiro as $read_msg_financeiro_view);
        echo '<hr />';
        echo '<textarea class="form-control msg_financeiro_texto" cols="" rows="10">'.$read_msg_financeiro_view['msg_financeiro_texto'].'</textarea>';
        echo '<hr />';
        echo '<select class="form-control msg_financeiro_boleto"><option value="0">Não Enviar Boleto</option><option value="1">Enviar Boleto</option></select>';
    }
}elseif($acao == 'mail_financeiro'){
    $msg_financeiro_texto   = addslashes($_POST['msg_financeiro_texto']);
    $msg_financeiro_id      = addslashes($_POST['msg_financeiro_id']);
    $msg_financeiro_md5     = addslashes($_POST['msg_financeiro_md5']);
    $msg_financeiro_boleto  = addslashes($_POST['msg_financeiro_boleto']);
    $msg_financeiro_data_vencimento = addslashes($_POST['msg_financeiro_data_vencimento']);
    $msg_envio_juros = addslashes($_POST['msg_envio_juros']);
    $msg_envio_email = addslashes($_POST['msg_envio_email']);
    $msg_envio_fatura = addslashes($_POST['msg_envio_fatura']);
    
    $assunto_mail = GetDados('msg_financeiro', $msg_financeiro_md5, 'msg_financeiro_id', 'msg_financeiro_assunto');
    $MSG_1 = str_replace('#NOMEEMPRESA#', GetEmpresa('empresa_nome_razao'), MSGEMAIL);
    $MSG_2 = str_replace('#IMGEMPRESA#', GetEmpresa('empresa_logo'), $MSG_1);
    $MSG_3 = str_replace('#TITULOMAIL#', $assunto_mail, $MSG_2);
    $MSG_4 = str_replace('#MSGMAIL#', $msg_financeiro_texto, $MSG_3);
    $MSG_5 = str_replace('#MAILEMPRESA#', GetEmpresa('empresa_email'), $MSG_4);
    $MSG_6 = str_replace('#FONEEMPRESA#', GetEmpresa('empresa_telefone'), $MSG_5);
    if($msg_financeiro_boleto == '1'){
        $read_boleto = Read('boleto', "WHERE boleto_id = '".  GetEmpresa('empresa_boleto_id_mail')."' ORDER BY boleto_descricao ASC");
        if(NumQuery($read_boleto) > '0'){
            foreach($read_boleto as $read_boleto_view){
                if($read_boleto_view['boleto_banco'] == '0'){
                    $banco_name = 'CAIXA  - CEF';
                    if($read_boleto_view['boleto_modelo'] == '0'){
                        $model_boleto = base64_encode('sicob');
                    }elseif($read_boleto_view['boleto_modelo'] == '1'){
                        $model_boleto = base64_encode('sinco');
                    }elseif($read_boleto_view['boleto_modelo'] == '2'){
                        $model_boleto = base64_encode('sigcb');
                    }
                    $type_boleto = base64_encode('boleto_cef');
                }
            }
        }
        if($msg_envio_fatura == '0'){
            $MSG_7 = str_replace('#LINKBOLETO#', '<a href="'.URL.'/_boleto_pdf/_mj_boleto_geracao/_boletos/gerar.php?00='.$type_boleto.'&01='.$model_boleto.'&02='.  base64_encode($read_boleto_view['boleto_id']).'&03='.  base64_encode($msg_financeiro_id).'&04='.base64_encode(BASE).'&05=web&06='. base64_encode($msg_financeiro_data_vencimento).'&07='. base64_encode($msg_envio_juros).'" target="_blank">Clique Aqui</a>', $MSG_6);
        }else{
            $MSG_7 = str_replace('#LINKBOLETO#', '<a href="'.URL.'/pagseguro/app/fatura.php?00='.$type_boleto.'&01='.$model_boleto.'&02='.  base64_encode($read_boleto_view['boleto_id']).'&03='.  base64_encode($msg_financeiro_id).'&04='.base64_encode(BASE).'&05=web&06='. base64_encode($msg_financeiro_data_vencimento).'&07='. base64_encode($msg_envio_juros).'" target="_blank">Clique Aqui</a>', $MSG_6);
        }
    }else{
        $MSG_7 = $MSG_6;
    }
    
    $count_mails = '0';
    $count_mails_send = '0';
    $count_mails_no_send = '0';
    
    $campanha_mail['campanha_mail_id_msg_financeiro']   = $msg_financeiro_md5;
    $campanha_mail['campanha_mail_data']                = date('Y-m-d');
    $campanha_mail['campanha_mail_data_hora_inicio']    = date('Y-m-d H:i:s');
    $campanha_mail['campanha_mail_send_fatura']         = $msg_financeiro_boleto;
    $campanha_mail['campanha_mail_status']              = '0';
    Create('campanha_mail', $campanha_mail);
    
    $id_campanha_mail = GetReg('campanha_mail', "campanha_mail_id", "");
    
    $read_financeiro = Read('financeiro', "WHERE financeiro_id IN($msg_financeiro_id) AND financeiro_status = '0' AND financeiro_tipo = 'CR'");
    if(NumQuery($read_financeiro) > '10'){
        foreach($read_financeiro as $read_financeiro_view){
            $count_mails++;
            $read_contato = Read('contato', "WHERE contato_id = '".$read_financeiro_view['financeiro_id_contato']."'");
            if(NumQuery($read_contato) > '0'){
                foreach($read_contato as $read_contato_view);
                $email_cliente = strtolower($read_contato_view['contato_email']);
                $nome_cliente = $read_contato_view['contato_nome_razao'];
            }else{
                $email_cliente = '';
                $nome_cliente = '';
            }
            if($msg_envio_email != ''){
                $email_cliente = $msg_envio_email;
            }
            if(valMail($email_cliente)){
                $campanha_mail_itens['campanha_mail_itens_id_campanha_mail'] = $id_campanha_mail;
                $campanha_mail_itens['campanha_mail_itens_id_contato']       = $read_financeiro_view['financeiro_id_contato'];
                $campanha_mail_itens['campanha_mail_itens_email']            = $email_cliente;
                $campanha_mail_itens['campanha_mail_itens_status'] = '0';
                $campanha_mail_itens['campanha_mail_itens_motivo'] = '';
                Create('campanha_mail_itens', $campanha_mail_itens);
                $count_mails_send++;
            }else{
                $campanha_mail_itens['campanha_mail_itens_id_campanha_mail'] = $id_campanha_mail;
                $campanha_mail_itens['campanha_mail_itens_id_contato']       = $read_financeiro_view['financeiro_id_contato'];
                $campanha_mail_itens['campanha_mail_itens_email']            = $email_cliente;
                $campanha_mail_itens['campanha_mail_itens_status'] = '2';
                $campanha_mail_itens['campanha_mail_itens_motivo'] = 'Não enviado devido a não ter email cadastrado';
                Create('campanha_mail_itens', $campanha_mail_itens);
                $count_mails_no_send++;
            }
        }
        echo 'De '.$count_mails.' email(s) a serem enviado(s), '.$count_mails_send.' foram solicitado(s) para envio com sucesso e '.$count_mails_no_send.' não foram se quer solicitado(s). Isso acontece pois foram enviados mais de 10 emails, dessa forma o sistema vai enviar de forma mais lenta para que não tenha nenhum email perdido.';
    }elseif(NumQuery($read_financeiro) > '0'){
        foreach($read_financeiro as $read_financeiro_view){
            $count_mails++;
            $read_contato = Read('contato', "WHERE contato_id = '".$read_financeiro_view['financeiro_id_contato']."'");
            if(NumQuery($read_contato) > '0'){
                foreach($read_contato as $read_contato_view);
                $email_cliente = strtolower($read_contato_view['contato_email']);
                $nome_cliente = $read_contato_view['contato_nome_razao'];
            }else{
                $email_cliente = '';
                $nome_cliente = '';
            }
            if($msg_envio_email != ''){
                $email_cliente = $msg_envio_email;
            }
            if(valMail($email_cliente)){
                $campanha_mail_itens['campanha_mail_itens_id_campanha_mail'] = $id_campanha_mail;
                $campanha_mail_itens['campanha_mail_itens_id_contato']       = $read_financeiro_view['financeiro_id_contato'];
                $campanha_mail_itens['campanha_mail_itens_email']            = $email_cliente;
                $retorno = sendMailCampanha($assunto_mail, $MSG_7, GetEmpresa('empresa_email'), GetEmpresa('empresa_nome_fantasia'), $email_cliente, $nome_cliente);
                if($retorno){
                    $campanha_mail_itens['campanha_mail_itens_status'] = '1';
                    $campanha_mail_itens['campanha_mail_itens_motivo'] = '';
                    $count_mails_send++;
                }else{
                    $campanha_mail_itens['campanha_mail_itens_status'] = '2';
                    $campanha_mail_itens['campanha_mail_itens_motivo'] = $retorno;
                    $count_mails_no_send++;
                }
                Create('campanha_mail_itens', $campanha_mail_itens);
                
            }else{
                $campanha_mail_itens['campanha_mail_itens_id_campanha_mail'] = $id_campanha_mail;
                $campanha_mail_itens['campanha_mail_itens_id_contato']       = $read_financeiro_view['financeiro_id_contato'];
                $campanha_mail_itens['campanha_mail_itens_email']            = $email_cliente;
                $campanha_mail_itens['campanha_mail_itens_status'] = '2';
                $campanha_mail_itens['campanha_mail_itens_motivo'] = 'Não enviado devido a não ter email cadastrado';
                Create('campanha_mail_itens', $campanha_mail_itens);
                $count_mails_no_send++;
            }
        }
        $update_campanha_mail['campanha_mail_data_hora_fim'] = date('Y-m-d H:i:s');
        $update_campanha_mail['campanha_mail_status'] = '1';
        Update('campanha_mail', $update_campanha_mail, "WHERE campanha_mail_id = '".$id_campanha_mail."'");
        echo 'De '.$count_mails.' email(s) a serem enviado(s), '.$count_mails_send.' foram enviado(s) com sucesso e '.$count_mails_no_send.' não foram enviado(s)';
    }
}elseif($acao == 'gerar_pesquisa'){
    if(isset($_SESSION['financeiro_load_report'])){
        unset($_SESSION['financeiro_load_report']);
    }
    $get_id_contato         = addslashes($_GET['search_nome_id_contato']);
    $get_situacao           = addslashes($_GET['search_situacao']);
    $get_tipo_pesquisa      = addslashes($_GET['search_pesquisa']);
    $get_data_inicial       = addslashes($_GET['search_data_inicial']);
    $get_data_final         = addslashes($_GET['search_data_final']);
    $get_itens_pesquisa     = addslashes($_GET['search_tipo_pesquisa']);
    $get_pesquisa           = addslashes($_GET['search_pesquisa']);
    $get_fixo               = addslashes($_GET['search_fixo']);
    $get_app_financeira     = addslashes($_GET['search_app_financeira']);
    $get_id_plano_conta     = addslashes($_GET['search_id_plano_conta']);
    $get_id_tipo_documento  = addslashes($_GET['search_id_tipo_documento']);
	$get_id_vendedor        = addslashes($_GET['search_id_vendedor']);
    
    if($get_id_contato != ''){
        $sql_id_contato = "AND financeiro_id_contato = '".$get_id_contato."'";
    }else{
        $sql_id_contato = "";
    }
    if($get_situacao != ''){
        $sql_situacao = "AND financeiro_status = '".$get_situacao."'";
    }else{
        $sql_situacao = "";
    }
    if($get_itens_pesquisa != '' && $get_data_inicial != '' && $get_data_final != ''){
        $sql_periodo = "AND $get_itens_pesquisa BETWEEN '".$get_data_inicial."' AND '".$get_data_final."'";
    }else{
        $sql_periodo = "";
    }
    if($get_itens_pesquisa != '' && $get_pesquisa != ''){
        $sql_pesquisa = "AND $get_itens_pesquisa LIKE '%".$get_pesquisa."%'";
    }else{
        $sql_pesquisa = "";
    }
    if($get_fixo != ''){
        $sql_fixo = "AND financeiro_fixo = '".$get_fixo."'";
    }else{
        $sql_fixo = "";
    }
    if($get_app_financeira != ''){
        $sql_app_financeira = "AND financeiro_app_financeira = '".$get_app_financeira."'";
    }else{
        $sql_app_financeira = "";
    }
    if($get_id_plano_conta != ''){
        $sql_id_plano_conta = "AND financeiro_id_plano_conta = '".$get_id_plano_conta."'";
    }else{
        $sql_id_plano_conta = "";
    }
    if($get_id_tipo_documento != ''){
        $sql_id_tipo_documento = "AND financeiro_id_tipo_documento = '".$get_id_tipo_documento."'";
    }else{
        $sql_id_tipo_documento = "";
    }
	
	if($get_id_vendedor != ''){
		$sql_id_vendedor = "AND financeiro_id_vendedor = '".$get_id_vendedor."'";
	}else{
		$sql_id_vendedor = "";
	}
    
    $get_op = addslashes($_GET['OP']);
    
    if($get_op != ''){
        $sql_op = "AND financeiro_tipo = '".$get_op."'";
    }else{
        $sql_op = "";
    }
    
    $_SESSION['report_financeiro_op'] = $get_op;
    if($get_tipo_pesquisa == 'financeiro_data_vencimento' || $get_tipo_pesquisa == 'financeiro_data_lancamento'){
        $_SESSION['financeiro_data_pesquisa'] = 'financeiro_valor';
    }else{
        $_SESSION['financeiro_data_pesquisa'] = 'financeiro_valor_pagamento';
    }
    $_SESSION['financeiro_load_report'] = " ".$sql_op." ".$sql_id_vendedor." ".$sql_id_contato." ".$sql_situacao." ".$sql_periodo." ".$sql_pesquisa." ".$sql_fixo." ".$sql_app_financeira." ".$sql_id_plano_conta." ".$sql_id_tipo_documento." ";
    //echo $_SESSION['financeiro_load_report'];
    
}elseif($acao == 'gerar_excel'){
    $valor_financeiro_aberto = '0';
    $valor_financeiro_pago = '0';
    $valor_financeiro_cancelado = '0';
    $valor_financeiro_renegociado = '0';
    $valor_financeiro_total = '0';
    $qtd_financeiroCP = '0';
    $qtd_financeiroCR = '0';
    /*$arquivo = 'financeiro.xls';
    $tabela = '<table border="1" width="800px">';
        $tabela .= '<tr>';
            $tabela .= '<td colspan="10" align="center">Relação de contas</tr>';
        $tabela .= '</tr>';
        $tabela .= '<tr>';
            $tabela .= '<td><b>Código</b></td>';
            $tabela .= '<td><b>Tipo</b></td>';
            $tabela .= '<td><b>Descrição</b></td>';
            $tabela .= '<td><b>Contato</b></td>';
            $tabela .= '<td><b>Telefone</b></td>';
            $tabela .= '<td><b>Email</b></td>';
            $tabela .= '<td><b>Tipo Documento</b></td>';
            $tabela .= '<td><b>Situação</b></td>';
            $tabela .= '<td><b>Vencimento</b></td>';
            $tabela .= '<td><b>Valor</b></td>';
            $tabela .= '<td><b>Data Pagamento</b></td>';
            $tabela .= '<td><b>Valor Pagamento</b></td>';
            $tabela .= '<td><b>Multa</b></td>';
            $tabela .= '<td><b>Juros</b></td>';
            $tabela .= '<td><b>Total Pagar</b></td>';
        $tabela .= '</tr>';*/
	$header = array(
		'CÓDIGO'=>'string',
		'TIPO'=>'string',
		'DESCRIÇÃO'=>'string',
		'CONTATO'=>'string',
		'TELEFONE'=>'string',
		'EMAIL'=>'string',
		'TIPO DOCUMENTO'=>'string',
		'SITUAÇÃO'=>'string',
		'VENCIMENTO'=>'string',
		'VALOR'=>'string',
		'DATA PAGAMENTO'=>'string',
		'VALOR PAGAMENTO'=>'string',
		'MULTA'=>'string',
		'JUROS'=>'string',
		'TOTAL PAGAR'=>'string'
	);	
    
    //$read_financeiro = Read('financeiro', "WHERE financeiro_id != '' {$_SESSION['financeiro_load_report']} ORDER BY financeiro_data_vencimento ASC");
    $read_financeiro = ReadComposta("SELECT financeiro_codigo, financeiro_tipo, financeiro_descricao, contato_nome_razao, contato_telefone, contato_email, tipo_documento_descricao, financeiro_status, financeiro_data_vencimento, financeiro_valor, financeiro_data_pagamento, financeiro_valor_pagamento FROM financeiro LEFT JOIN contato ON contato_id = financeiro_id_contato LEFT JOIN tipo_documento ON tipo_documento_id = financeiro_id_tipo_documento WHERE financeiro_id != '' {$_SESSION['financeiro_load_report']} ORDER BY financeiro_data_vencimento ASC, financeiro_id_contato ASC");
	if(NumQuery($read_financeiro) > '0'){
        foreach($read_financeiro as $read_financeiro_view){
            if($read_financeiro_view['financeiro_status'] == '0'){
                $status_financeiro = 'ABERTO';
                $data_pagamento_financeiro = '';
                $valor_pagamento_financeiro = '';
            }elseif($read_financeiro_view['financeiro_status'] == '1'){
                $status_financeiro = 'BAIXADO';
                $data_pagamento_financeiro = FormDataBr($read_financeiro_view['financeiro_data_pagamento']);
                $valor_pagamento_financeiro = FormatMoney($read_financeiro_view['financeiro_valor_pagamento']);
            }elseif($read_financeiro_view['financeiro_status'] == '2'){
                $status_financeiro = 'CANCELADO';
                $data_pagamento_financeiro = '';
                $valor_pagamento_financeiro = '';
            }elseif($read_financeiro_view['financeiro_status'] == '3'){
                $status_financeiro = 'RENEGOCIADO';
                $data_pagamento_financeiro = '';
                $valor_pagamento_financeiro = '';
            }
            if($read_financeiro_view['financeiro_tipo'] == 'CR'){
                $tipo_financeiro = 'RECEITA';
                if($read_financeiro_view['financeiro_status'] == '0'){
                    $valor_financeiroCR_aberto += $read_financeiro_view['financeiro_valor'];
                }elseif($read_financeiro_view['financeiro_status'] == '1'){
                    $valor_financeiroCR_pago += $read_financeiro_view['financeiro_valor_pagamento'];
                }elseif($read_financeiro_view['financeiro_status'] == '2'){
                    $valor_financeiroCR_cancelado += $read_financeiro_view['financeiro_valor'];
                }elseif($read_financeiro_view['financeiro_status'] == '3'){
                    $valor_financeiroCR_renegociado += $read_financeiro_view['financeiro_valor'];
                }
                $valor_financeiroCR_total += $read_financeiro_view['financeiro_valor'];
                $qtd_financeiroCR+=1;
            }else{
                $tipo_financeiro = 'DESPESA';
                if($read_financeiro_view['financeiro_status'] == '0'){
                    $valor_financeiroCP_aberto += $read_financeiro_view['financeiro_valor'];
                }elseif($read_financeiro_view['financeiro_status'] == '1'){
                    $valor_financeiroCP_pago += $read_financeiro_view['financeiro_valor_pagamento'];
                }elseif($read_financeiro_view['financeiro_status'] == '2'){
                    $valor_financeiroCP_cancelado += $read_financeiro_view['financeiro_valor'];
                }elseif($read_financeiro_view['financeiro_status'] == '3'){
                    $valor_financeiroCP_renegociado += $read_financeiro_view['financeiro_valor'];
                }
                $qtd_financeiroCP +=1;
                $valor_financeiroCP_total += $read_financeiro_view['financeiro_valor'];
            }
            if($read_financeiro_view['financeiro_status'] == '0'){
                $valor_financeiro_aberto += $read_financeiro_view['financeiro_valor'];
            }elseif($read_financeiro_view['financeiro_status'] == '1'){
                $valor_financeiro_pago += $read_financeiro_view['financeiro_valor_pagamento'];
            }elseif($read_financeiro_view['financeiro_status'] == '2'){
                $valor_financeiro_cancelado += $read_financeiro_view['financeiro_valor'];
            }elseif($read_financeiro_view['financeiro_status'] == '3'){
                $valor_financeiro_renegociado += $read_financeiro_view['financeiro_valor'];
            }
            $valor_financeiro_total += $read_financeiro_view['financeiro_valor'];
            if($read_financeiro_view['financeiro_status'] == '0'){
                $NovaDtVenc = date('Y-m-d');
                if (strtotime($read_financeiro_view['financeiro_data_vencimento']) < strtotime(date('Y-m-d'))) {
                    $IntervadoVencido = strtotime($NovaDtVenc) - strtotime($read_financeiro_view['financeiro_data_vencimento']);
                    $DiasIntervalo = floor($IntervadoVencido / (60 * 60 * 24));
                    $ValorContaOriginal = $read_financeiro_view['financeiro_valor'];
                    $ValorMulta = (MULTA / 100) * $ValorContaOriginal;
                    $ValorJuros = ((JUROS * $ValorContaOriginal) / 100) * $DiasIntervalo;
                    $ValorJurosDia = ((JUROS * $ValorContaOriginal) / 100);
                    $ValorMultaDia = (MULTA / 100) * $ValorContaOriginal;
                    if ($ValorJuros > '0') {
                        $ValorAtualizado = $ValorContaOriginal + $ValorMulta + $ValorJuros;
                        $DataVenc = FormDataBr($NovaDtVenc);
                    } else {
                        $ValorAtualizado = $ValorContaOriginal;
                        $DataVenc = FormDataBr($NovaDtVenc);
                        $ValorJurosDia = '0';
                        $ValorMultaDia = '0';
                    }
                } else {
                    $ValorAtualizado = $read_financeiro_view['financeiro_valor'];
                    $DataVenc = FormDataBr($read_financeiro_view['financeiro_data_vencimento']);
                }
            }else{
                $ValorMulta = '';
                $ValorJuros = '';
                $ValorAtualizado = '';
            }
			$read_financeiro_view['financeiro_tipo'] = $tipo_financeiro;
			$read_financeiro_view['financeiro_status'] = $status_financeiro;
			$read_financeiro_view['financeiro_data_pagamento'] = $data_pagamento_financeiro;
			$read_financeiro_view['financeiro_valor_pagamento'] = FormatMoney($valor_pagamento_financeiro);
			$read_financeiro_view['financeiro_valor'] = FormatMoney($read_financeiro_view['financeiro_valor']);
			$read_financeiro_view['financeiro_multa'] = FormatMoney($ValorMulta);
			$read_financeiro_view['financeiro_juros'] = FormatMoney($ValorJuros);
			$read_financeiro_view['financeiro_atualizado'] = FormatMoney($ValorAtualizado);
			
			$rows[] = $read_financeiro_view	;
        }
    }
    $time = time();
    $writer = new XLSXWriter();
	$writer->writeSheetHeader('Sheet1', $header);
	foreach($rows as $row)
    $writer->writeSheetRow('Sheet1', $row);
    $writer->writeToFile(__DIR__."/excel/financeiro_excel_{$time}.xlsx");
	header("Location: excel/financeiro_excel_{$time}.xlsx");
    echo $tabela;
}elseif($acao == 'load_itens_vendas'){
    $get_id_financeiro = addslashes($_POST['id']);
    
    $data['empresa']['nome_fantasia'] = GetEmpresa('empresa_nome_fantasia');
    $data['empresa']['endereco_numero'] = GetEmpresa('empresa_endereco').','.  GetEmpresa('empresa_numero');
    $data['empresa']['cidade'] = GetEmpresa('empresa_cidade');
    $data['empresa']['uf'] = GetEmpresa('empresa_estado');
    $data['empresa']['cep'] = GetEmpresa('empresa_cep');
    $data['empresa']['telefone'] = 'Telefone:'.GetEmpresa('empresa_telefone');
    $data['empresa']['celular'] = 'Celular:'.GetEmpresa('empresa_celular');
    
    $read_financeiro = Read('financeiro', "WHERE financeiro_id = '".$get_id_financeiro."'");
    if(NumQuery($read_financeiro) > '0'){
        foreach($read_financeiro as $read_financeiro_view);
        $read_contato = Read('contato', "WHERE contato_id = '".$read_financeiro_view['financeiro_id_contato']."'");
        if(NumQuery($read_contato) > '0'){
            foreach($read_contato as $read_contato_view);
            $data['contato']['nome_fantasia'] = $read_contato_view['contato_nome_fantasia'];
            $data['contato']['endereco_numero'] = $read_contato_view['contato_endereco'].','.$read_contato_view['contato_numero'];
            $data['contato']['cidade'] = $read_contato_view['contato_cidade'];
            $data['contato']['uf'] = $read_contato_view['contato_estado'];
            $data['contato']['cep'] = $read_contato_view['contato_cep'];
            $data['contato']['telefone'] = 'Telefone: '.$read_contato_view['contato_telefone'];
            $data['contato']['celular'] = 'Celular: '.$read_contato_view['contato_celular'];
        }
        
        $read_orcamento_venda = Read('orcamento_venda', "WHERE orcamento_venda_id = '".$read_financeiro_view['financeiro_id_venda']."'");
        if(NumQuery($read_orcamento_venda) > '0'){
            foreach($read_orcamento_venda as $read_orcamento_venda_view);
            $data['orcamento_venda']['data'] = FormDataBr($read_orcamento_venda_view['orcamento_venda_data']);
        }
    }
    
    echo json_encode($data);
}elseif($acao == 'load_itens_vendas_produtos'){
    $get_id_financeiro = addslashes($_POST['id']);
    
    $read_financeiro = Read('financeiro', "WHERE financeiro_id = '".$get_id_financeiro."'");
    if(NumQuery($read_financeiro) > '0'){
        foreach($read_financeiro as $read_financeiro_view);
        $read_contato = Read('contato', "WHERE contato_id = '".$read_financeiro_view['financeiro_id_contato']."'");
        if(NumQuery($read_contato) > '0'){
            foreach($read_contato as $read_contato_view);
            $data['contato']['nome_fantasia'] = $read_contato_view['contato_nome_fantasia'];
            $data['contato']['endereco_numero'] = $read_contato_view['contato_endereco'].','.$read_contato_view['contato_numero'];
            $data['contato']['cidade'] = $read_contato_view['contato_cidade'];
            $data['contato']['uf'] = $read_contato_view['contato_estado'];
            $data['contato']['cep'] = $read_contato_view['contato_cep'];
            $data['contato']['telefone'] = 'Telefone: '.$read_contato_view['contato_telefone'];
            $data['contato']['celular'] = 'Celular: '.$read_contato_view['contato_celular'];
        }
        
        $read_orcamento_venda = Read('orcamento_venda', "WHERE orcamento_venda_id = '".$read_financeiro_view['financeiro_id_venda']."'");
        if(NumQuery($read_orcamento_venda) > '0'){
            foreach($read_orcamento_venda as $read_orcamento_venda_view);
            echo '<table class="table table-striped table-invoice">';
                echo '<thead>
                        <tr>
                            <th>Descrição</th>
                            <th>Valor Unitário</th>
                            <th>Quantidade</th>
                            <th class="tr">Valor Total</th>
                        </tr>
                    </thead>';
                echo '<tbody>';
                    $read_itens_orcamento_venda = Read('itens_orcamento_venda', "WHERE itens_orcamento_venda_id_orcamento_venda = '".$read_financeiro_view['financeiro_id_venda']."'");
                    if(NumQuery($read_itens_orcamento_venda) > '0'){
                        foreach($read_itens_orcamento_venda as $read_itens_orcamento_venda_view){
                            $sub_total += $read_itens_orcamento_venda_view['itens_orcamento_venda_valor_total'];
                            echo '<tr>
                                    <td class="name">'.GetDados('produto', $read_itens_orcamento_venda_view['itens_orcamento_venda_id_produto'], 'produto_id', 'produto_descricao').'</td>
                                    <td class="price">'.FormatMoney($read_itens_orcamento_venda_view['itens_orcamento_venda_valor_unitario']).'</td>
                                    <td class="qty">'.$read_itens_orcamento_venda_view['itens_orcamento_venda_qtd'].'</td>
                                    <td class="total">'.FormatMoney($read_itens_orcamento_venda_view['itens_orcamento_venda_valor_total']).'</td>
                                </tr>';
                        }
                    }
                    echo '<tr>
                            <td colspan="3"></td>
                            <td class="taxes">
                                <p>
                                    <span class="light">Subtotal</span>
                                    <span>R$ '.  FormatMoney($sub_total).'</span>
                                </p>
                                <p>
                                    <span class="light">Frete</span>
                                    <span>R$ '.  FormatMoney($read_orcamento_venda_view['orcamento_venda_valor_frete']).'</span>
                                </p>
                                <p>
                                    <span class="light">Total</span>
                                    <span class="totalprice">
                                        R$ '.  FormatMoney($read_orcamento_venda_view['orcamento_venda_valor_total']).'
                                    </span>
                                </p>
                            </td>
                        </tr>';
                echo '</tbody>';
            echo '</table>';
        }
    }
}elseif($acao == 'info_financeiro'){
    $id_financeiro = addslashes($_POST['id']);
    
    $read_financeiro = Read('financeiro', "WHERE financeiro_id = '".$id_financeiro."'");
    if(NumQuery($read_financeiro) > '0'){
        foreach($read_financeiro as $read_financeiro_view);
        if($read_financeiro_view['financeiro_status'] == '0'){
            $read_financeiro_view['financeiro_status'] = 'ABERTO';
            $read_financeiro_view['financeiro_data_pagamento'] = '';
            $read_financeiro_view['financeiro_valor_pagamento'] = '';
        }elseif($read_financeiro_view['financeiro_status'] == '1'){
            $read_financeiro_view['financeiro_status'] = 'BAIXADO';
            $read_financeiro_view['financeiro_data_pagamento'] = FormDataBr($read_financeiro_view['financeiro_data_pagamento']);
            $read_financeiro_view['financeiro_valor_pagamento'] = FormatMoney($read_financeiro_view['financeiro_valor_pagamento']);
        }elseif($read_financeiro_view['financeiro_status'] == '2'){
            $read_financeiro_view['financeiro_status'] = 'CANCELADO';
            $read_financeiro_view['financeiro_data_pagamento'] = '';
            $read_financeiro_view['financeiro_valor_pagamento'] = '';
        }elseif($read_financeiro_view['financeiro_status'] == '3'){
            $read_financeiro_view['financeiro_status'] = 'RENEGOCIADO';
            $read_financeiro_view['financeiro_data_pagamento'] = '';
            $read_financeiro_view['financeiro_valor_pagamento'] = '';
        }
        echo '<strong>Nosso Número: </strong> '.$read_financeiro_view['financeiro_nosso_numero'].'<br />';
        echo '<strong>Data Vencimento: </strong> '.$read_financeiro_view['financeiro_data_vencimento'].'<br />';
        echo '<strong>Valor: </strong> '.$read_financeiro_view['financeiro_valor'].'<br />';
        echo '<strong>Status: </strong> '.$read_financeiro_view['financeiro_status'].'<br />';
        echo '<strong>Valor Pagamento: </strong> '.$read_financeiro_view['financeiro_valor_pagamento'].'<br />';
        echo '<strong>Data Pagamento: </strong> '.$read_financeiro_view['financeiro_data_pagamento'].'<br />';
        echo '<strong>Data Baixa: </strong> '.$read_financeiro_view['financeiro_data_baixa'].'<br />';
        echo '<strong>Obs: </strong> '.$read_financeiro_view['financeiro_obs'].'<br />';
    }else{
        echo 'Nada foi encontrado';
    }
}elseif($acao == 'load_financeiro_update'){
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
        $order_by = "ORDER BY financeiro_id DESC";
    }else{
        $sort       = addslashes($_GET['sort']);
        $sort_dir   = addslashes($_GET['sort_dir']);
        $order_by   = "ORDER BY ".$sort." ".$sort_dir."";
    }
    
    
    $get_id_contas = addslashes($_GET['contas']);
    
    //PESQUISA VIA AJAX
    $read_financeiro_paginator = ReadComposta("SELECT financeiro_id FROM financeiro WHERE financeiro_status = '0' AND financeiro_id IN($get_id_contas)");
    $read_financeiro = Read('financeiro', "WHERE financeiro_status = '0' AND financeiro_id IN($get_id_contas) ".$order_by." LIMIT $inicio,$maximo");
    if(NumQuery($read_financeiro) > '0'){
        //PEGA QUANTOS BOTÕES PARA PAGINAÇÃO DE RESULTADOS
        $paginas = ceil(NumQuery($read_financeiro_paginator) / $maximo);
        //TABULATOR EXIGE ESSA INFORMAÇÃO PARA QUE PRECISE DA PAGINAÇÃO DE RESULTADOS 
        $json_financeiro["last_page"] = $paginas;
        foreach($read_financeiro as $read_financeiro_view){
            $json_financeiro['data'][] = $read_financeiro_view;
        }
    }else{
        $json_financeiro['data'] = null;
    }
    echo json_encode($json_financeiro);
}elseif($acao == 'update_varios'){
    $financeiro_form['financeiro_descricao'] = addslashes($_POST['descricao']);
    $financeiro_form['financeiro_data_vencimento'] = addslashes($_POST['data_vencimento']);
    $financeiro_form['financeiro_valor'] = addslashes($_POST['valor']);
    
    if(in_array('', $financeiro_form)){
        $json_financeiro = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser realizada, conta não identificada',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        $id = addslashes($_POST['id']);
        Update('financeiro', $financeiro_form, "WHERE financeiro_status = '0' AND financeiro_id = '".$id."'");
        $json_financeiro = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }
    echo json_encode($json_financeiro);
}elseif($acao == 'gerar_excel_bloqueio'){
	
	$read_financeiro = ReadComposta("SELECT financeiro_id_contato FROM financeiro WHERE financeiro_id != '' {$_SESSION['financeiro_load_report']}");
	if(NumQuery($read_financeiro) > '0'):
		foreach($read_financeiro as $read_financeiro_view):
			$id_contato .= $read_financeiro_view['financeiro_id_contato'].',';
		endforeach;
	endif;
	
	$id_contato_excel = substr($id_contato, 0,-1);
	
	$header = array(
		'ID CLIENTE'=>'string',
		'NOME FANTASIA'=>'string',
		'NOME RAZÃO SOCIAL'=>'string',
		'ICCID'=>'string',
		'NUM LINHA'=>'string',
		'TIPO LINHA'=>'string'
	);
	
	$read_chip_app = ReadComposta("SELECT contato_id, contato_nome_fantasia, contato_nome_razao, chip_iccid, chip_num, chip_plano FROM chip_app INNER JOIN contato ON contato_id = id_contato WHERE id_contato IN(".$id_contato_excel.")");
	if(NumQuery($read_chip_app) > '0'):
		foreach($read_chip_app as $read_chip_app_view):
			$rows[] = $read_chip_app_view	;
		endforeach;
		
		$writer = new XLSXWriter();

		$writer->writeSheetHeader('Sheet1', $header);
		foreach($rows as $row)
		$writer->writeSheetRow('Sheet1', $row);
		$writer->writeToFile('bloqueio_desbloqueio.xlsx');
		header("Location: bloqueio_desbloqueio.xlsx");
	endif;
}
?>