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
        $order_by = "ORDER BY saque_id DESC";
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
        $get_id_user      = addslashes($_GET['id_user']);;
        
        if($get_id != ''){
            $sql_id = "AND saque.saque_id = '".$get_id."'";
        }else{
            $sql_id = "";
        }
        if($get_data_inicial != '' && $get_data_final != ''){
            $sql_periodo = "AND saque.saque_data BETWEEN '".$get_data_inicial."' AND '".$get_data_final."'";
        }else{
            $sql_periodo = "";
        }
        if($get_status != ''){
            $sql_status = "AND saque.saque_status = '".$get_status."'";
        }else{
            $sql_status = "";
        }
        if($get_id_user != ''){
            $sql_id_user = "AND saque.saque_id_user = '".$get_id_user."'";
        }else{
            $sql_id_user = "";
        }
        
        $_SESSION['saque_load'] = "".$sql_id." ".$sql_id_user." ".$sql_status." ".$sql_periodo." ";
    }
    
    $read_saque_paginator = ReadComposta("SELECT saque_id, saque_valor, saque_taxa, saque_valor_pagamento, saque_status FROM saque WHERE saque_id != '' {$_SESSION['saque_load']}");
    if(NumQuery($read_saque_paginator) > '0'){
        foreach($read_saque_paginator as $read_saque_paginator_view){
            if($read_saque_paginator_view['saque_status'] == '1'){
                $saque_pago_valor_pagamento += $read_saque_paginator_view['saque_valor_pagamento'];
            }
            $saque_valor += $read_saque_paginator_view['saque_valor'];
            $saque_taxa += $read_saque_paginator_view['saque_taxa'];
        }
    }
    //$read_saque = Read('saque', "WHERE saque_id != '' {$_SESSION['saque_load']} ".$order_by." LIMIT $inicio,$maximo");
    $read_saque = ReadComposta("SELECT saque.saque_id, saque.saque_data_hora, saque.saque_data, saque.saque_status, saque.saque_valor_pagamento, saque.saque_data_pagamento, saque.saque_valor, saque.saque_taxa, user.user_nome FROM saque INNER JOIN user ON user.user_id = saque.saque_id_user WHERE saque.saque_id != '' {$_SESSION['saque_load']} ORDER BY saque.saque_id DESC");
    if(NumQuery($read_saque) > '0'){
        //PEGA QUANTOS BOTÕES PARA PAGINAÇÃO DE RESULTADOS
        $paginas = ceil(NumQuery($read_saque_paginator) / $maximo);
        //TABULATOR EXIGE ESSA INFORMAÇÃO PARA QUE PRECISE DA PAGINAÇÃO DE RESULTADOS 
        $json_saque["last_page"] = $paginas;
        $json_saque["quantidade_contas"] = NumQuery($read_saque_paginator);
        $json_saque["saque_valor"] = FormatMoney($saque_valor);
        $json_saque["saque_taxa"] = FormatMoney($saque_taxa);
        $json_saque["saque_valor_pagamento"] = FormatMoney($saque_pago_valor_pagamento);
        foreach($read_saque as $read_saque_view){
            if($read_saque_view['saque_status'] == '0'){
                $read_saque_view['saque_status'] = 'Pendente';
            }elseif($read_saque_view['saque_status'] == '1'){
                $read_saque_view['saque_status'] = 'Concluído';
            }
            $json_saque['data'][] = $read_saque_view;
        }
    }else{
        $json_saque['data'] = null;
    }
    echo json_encode($json_saque);
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
    $saque_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($saque_form['acao']);
    
    if($saque_form['saque_status'] == '1' && $saque_form['saque_valor_pagamento'] == '' || $saque_form['saque_data_pagamento'] == ''){
        $json_saque = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finaliza devido a escolhe de ser finalizada o saque, portando é preciso digitar a data de pagamento e valor do pagamento!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }elseif($saque_form['saque_data'] == ''){
        $json_saque = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a data não ter cido digitada!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        $uid = addslashes($_POST['id']);
        unset($saque_form['id']);
        Update('saque', $saque_form, "WHERE saque_id = '".$uid."'");
        $json_saque = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<a href="javascript::" data-dismiss="modal" onclick="carrega_pagina(\'mmn_saque\', \'index.php\');" class="btn btn-primary">Sair</a>'
        );
    }
    echo json_encode($json_saque);
}elseif($acao == 'load_update'){
    $uid = addslashes($_POST['id']);
    
    $read_saque = Read('saque', "WHERE saque_id = '".$uid."'");
    if(NumQuery($read_saque) > '0'){
        foreach($read_saque as $read_saque_view);
        $read_conta = Read('conta', "WHERE conta_id = '".$read_saque_view['saque_id_conta']."' AND conta_id_user = '".$read_saque_view['saque_id_user']."'");
        if(NumQuery($read_conta) > '0'){
            foreach($read_conta as $read_conta_view);
            $read_saque_view['saque_id_user'] = GetDados('user', $read_saque_view['saque_id_user'], 'user_id', 'user_nome');
            $read_saque_view['conta_id_banco'] = GetDados('banco', $read_conta_view['conta_id_banco'], 'banco_id', 'banco_descricao');
            if($read_conta_view['conta_tipo'] == '1'){
                $read_saque_view['conta_tipo'] = 'Conta corrente';
            }else{
                $read_saque_view['conta_tipo'] = 'Conta poupança';
            }
            $read_saque_view['conta_conta'] = $read_conta_view['conta_conta'];
            $read_saque_view['conta_titular'] = $read_conta_view['conta_titular'];
            $read_saque_view['conta_cpf_titular'] = $read_conta_view['conta_cpf_titular'];
            $read_saque_view['conta_agencia'] = $read_conta_view['conta_agencia'];
            $read_saque_view['conta_dg_conta'] = $read_conta_view['conta_dg_conta'];
            $read_saque_view['conta_operacao'] = $read_conta_view['conta_operacao'];
            $read_saque_view['conta_obs'] = $read_conta_view['conta_obs'];
        }else{
        }
        $json_saque[] = $read_saque_view;
    }else{
        $json_saque = null;
    }
    echo json_encode($json_saque);
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
}elseif($acao == 'enviar'){
    
    $saque_form['saque_data']   = addslashes($_GET['saque_data']);
    $saque_form['saque_status'] = addslashes($_GET['saque_status']);
    $saque_form['saque_data_pagamento'] = addslashes($_GET['saque_data_pagamento']);
    $saque_form['saque_valor_pagamento'] = addslashes($_GET['saque_valor_pagamento']);
    
    if($saque_form['saque_status'] == '1' && $saque_form['saque_valor_pagamento'] == '' || $saque_form['saque_data_pagamento'] == ''){
        $data['sucesso'] = false;

        $data['msg'] = 'Todos os campos devem ser preenchidos!';
    }elseif($saque_form['saque_data'] == ''){
        $data['sucesso'] = false;

        $data['msg'] = 'Data é inválida!';
    }else{
        $arquivo = $_FILES['arquivo'];

        $tipos = array('pdf');

        $enviar = uploadFile($arquivo, '../_uploads/federal_sistemas/saque/', $tipos);

        $data['sucesso'] = false;

        if($enviar['erro']){    
            $data['msg'] = $enviar['erro'];
        }else{
            $data['sucesso'] = true;

            $data['msg'] = $enviar['caminho'];
            
            $saque_form['saque_comprovante'] = $enviar['caminho'];
            $uid = addslashes($_GET['id']);
            Update('saque', $saque_form, "WHERE saque_id = '".$uid."'");
        }
    }
    echo json_encode($data);
}
?>