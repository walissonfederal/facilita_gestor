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
        $order_by = "ORDER BY orcamento_venda_id DESC";
    }else{
        
        $sort       = addslashes($_GET['sort']);
        $sort_dir   = addslashes($_GET['sort_dir']);
        $order_by   = "ORDER BY ".$sort." ".$sort_dir."";
    }
    
    //PESQUISA VIA AJAX
    if(isset($_GET['search']) && $_GET['search'] == 'true'){
        $get_id             = addslashes($_GET['id']);
        $get_id_contato     = addslashes($_GET['id_contato']);
        $get_situacao       = addslashes($_GET['situacao']);
        $get_tipo_pesquisa  = addslashes($_GET['tipo_pesquisa']);
        $get_data_inicial   = addslashes($_GET['data_inicial']);
        $get_data_final     = addslashes($_GET['data_final']);
        
        if($get_id != ''){
            $sql_id = "AND orcamento_venda_id = '".$get_id."'";
        }else{
            $sql_id = "";
        }
        if($get_id_contato != ''){
            $sql_id_contato = "AND orcamento_venda_id_contato = '".$get_id_contato."'";
        }else{
            $sql_id_contato = "";
        }
        if($get_situacao != ''){
            $sql_situacao = "AND orcamento_venda_status = '".$get_situacao."'";
        }else{
            $sql_situacao = "";
        }
        if($get_data_inicial != '' && $get_data_inicial != ''){
            $sql_periodo = "AND ".$get_tipo_pesquisa." '".$get_data_inicial."' AND '".$get_data_final."'";
        }else{
            $sql_periodo = "";
        }
        $_SESSION['orcamento_venda_load_'] = "".$sql_id." ".$sql_id_contato." ".$sql_situacao." ".$sql_periodo." ";
    }
    
    $read_orcamento_venda_paginator = ReadComposta("SELECT orcamento_venda_id, orcamento_venda_tipo FROM orcamento_venda WHERE orcamento_venda_tipo = '1' {$_SESSION['orcamento_venda_load_']}");
    $read_orcamento_venda = Read('orcamento_venda', "WHERE orcamento_venda_tipo = '1' {$_SESSION['orcamento_venda_load_']} ".$order_by." LIMIT $inicio,$maximo");
    if(NumQuery($read_orcamento_venda) > '0'){
        //PEGA QUANTOS BOTÕES PARA PAGINAÇÃO DE RESULTADOS
        $paginas = ceil(NumQuery($read_orcamento_venda_paginator) / $maximo);
        //TABULATOR EXIGE ESSA INFORMAÇÃO PARA QUE PRECISE DA PAGINAÇÃO DE RESULTADOS 
        $json_orcamento_venda["last_page"] = $paginas;
        foreach($read_orcamento_venda as $read_orcamento_venda_view){
            $read_orcamento_venda_view['orcamento_venda_data'] = FormDataBr($read_orcamento_venda_view['orcamento_venda_data']);
            $read_orcamento_venda_view['orcamento_venda_id_contato'] = GetDados('contato', $read_orcamento_venda_view['orcamento_venda_id_contato'], 'contato_id', 'contato_nome_razao');
            $read_orcamento_venda_view['orcamento_venda_valor_produtos'] = FormatMoney($read_orcamento_venda_view['orcamento_venda_valor_produtos']);
            $read_orcamento_venda_view['orcamento_venda_valor_total'] = FormatMoney($read_orcamento_venda_view['orcamento_venda_valor_total']);
            if($read_orcamento_venda_view['orcamento_venda_status'] == '0'){
                $read_orcamento_venda_view['orcamento_venda_status'] = 'Em aberto';
            }elseif($read_orcamento_venda_view['orcamento_venda_status'] == '1'){
                $read_orcamento_venda_view['orcamento_venda_status'] = 'Em andamento';
            }elseif($read_orcamento_venda_view['orcamento_venda_status'] == '2'){
                $read_orcamento_venda_view['orcamento_venda_status'] = 'Atendido';
            }elseif($read_orcamento_venda_view['orcamento_venda_status'] == '3'){
                $read_orcamento_venda_view['orcamento_venda_status'] = 'Cancelado';
            }elseif($read_orcamento_venda_view['orcamento_venda_status'] == '4'){
                $read_orcamento_venda_view['orcamento_venda_status'] = 'Finalizado';
            }
            $json_orcamento_venda['data'][] = $read_orcamento_venda_view;
        }
    }else{
        $json_orcamento_venda['data'] = null;
    }
    echo json_encode($json_orcamento_venda);
}elseif($acao == 'insert_produto'){
    $post_id_produto        = addslashes($_POST['id_produto']);
    $post_qtd               = addslashes($_POST['qtd']);
    $post_valor_unitario    = addslashes($_POST['valor_unitario']);
    
    if(!isset($_SESSION['orcamento_venda'])){
        $_SESSION['orcamento_venda'] = array();
    }
    if(!isset($_SESSION['orcamento_venda_valor_unitario'])){
        $_SESSION['orcamento_venda_valor_unitario'] = array();
    }
    
    $read_produto_orcamento_venda = Read('produto', "WHERE produto_id = '".$post_id_produto."'");
    if(NumQuery($read_produto_orcamento_venda) > '0'){
        if(!isset($_SESSION['orcamento_venda'][$post_id_produto])){
            $_SESSION['orcamento_venda'][$post_id_produto] = $post_qtd;
        }else{
            $_SESSION['orcamento_venda'][$post_id_produto] += $post_qtd;
        }
        if(!isset($_SESSION['orcamento_venda_valor_unitario'][$post_id_produto])){
            $_SESSION['orcamento_venda_valor_unitario'][$post_id_produto] = $post_valor_unitario;
        }else{
            $_SESSION['orcamento_venda_valor_unitario'][$post_id_produto] = $post_valor_unitario;
        }
        $json_produto_orcamento_venda = array(
            'type' => 'success',
            'title' => 'OK'
        );
    }else{
        $json_produto_orcamento_venda = array(
            'type' => 'error',
            'title' => 'ERROR',
            'msg' => 'Ops, produto não pode ser encontrado!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }
    echo json_encode($json_produto_orcamento_venda);
}elseif($acao == 'load_produto_grid'){
    if(count($_SESSION['orcamento_venda']) > '0'){
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
            foreach($_SESSION['orcamento_venda'] as $produto_id_grid => $produto_qtd_grid){
                $valor_total_grid = $_SESSION['orcamento_venda_valor_unitario'][$produto_id_grid] * $produto_qtd_grid;
                $valor_unitario_grid = $_SESSION['orcamento_venda_valor_unitario'][$produto_id_grid];
                
                $sub_total += $valor_total_grid;
                echo '<tr>';
                    echo '<td>'.$count_produto_grid.'</td>';
                    echo '<td>'.GetDados('produto', $produto_id_grid, 'produto_id', 'produto_descricao').'</td>';
                    echo '<td>'.$produto_qtd_grid.'</td>';
                    echo '<td>'.FormatMoney($valor_unitario_grid).'</td>';
                    echo '<td>'.FormatMoney($valor_total_grid).'</td>';
                    echo '<td><button type="button" class="btn btn-danger" onclick="delete_prod_grid('.$produto_id_grid.');">Deletar</button></td>';
                echo '</th>';
                $count_produto_grid++;
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
    }
}elseif($acao == 'delete_produto_grid'){
    $post_id_produto = addslashes($_POST['id_produto']);
    unset($_SESSION['orcamento_venda'][$post_id_produto]);
    unset($_SESSION['orcamento_venda_valor_unitario'][$post_id_produto]);
}elseif($acao == 'load_valor_produtos'){
    if(count($_SESSION['orcamento_venda']) > '0'){
        foreach($_SESSION['orcamento_venda'] as $produto_id_grid => $produto_qtd_grid){
            $valor_total_grid += $_SESSION['orcamento_venda_valor_unitario'][$produto_id_grid] * $produto_qtd_grid;
        }
    }else{
        $valor_total_grid = '0';
    }
    $json_valor_produto_orcamento_venda = array(
        'valor_produtos' => $valor_total_grid
    );
    echo json_encode($json_valor_produto_orcamento_venda);
}elseif($acao == 'load_data'){
    $json_data = array(
        'type' => 'success',
        'title' => 'OK',
        'data' => date('Y-m-d')
    );
    echo json_encode($json_data);
}elseif($acao == 'load_valor_total'){
    if(count($_SESSION['orcamento_venda']) > '0'){
        foreach($_SESSION['orcamento_venda'] as $produto_id_grid => $produto_qtd_grid){
            $valor_total_grid = $_SESSION['orcamento_venda_valor_unitario'][$produto_id_grid] * $produto_qtd_grid;
            $valor_unitario_grid = $_SESSION['orcamento_venda_valor_unitario'][$produto_id_grid];

            $sub_total += $valor_total_grid;
        }
        echo number_format($sub_total,2,".","");
    }else{
        echo number_format('0',2,".","");
    }
}elseif($acao == 'load_info_pagamento'){
    $valor_total = addslashes($_POST['valor']);
    
    $data['valor_total'] = $valor_total;
    $data['data']        = date('Y-m-d');
    
    echo json_encode($data);
}elseif($acao == 'forma_pagamento_insert'){
    $forma_pagamento_id     = addslashes($_POST['forma_pagamento_id']);
    $forma_pagamento_valor  = addslashes($_POST['forma_pagamento_valor']);
    $forma_pagamento_data   = addslashes($_POST['forma_pagamento_data']);
    $forma_pagamento_obs    = addslashes($_POST['forma_pagamento_obs']);
    $forma_pagamento_tipo   = addslashes($_POST['forma_pagamento_tipo']);
    $valor_total            = addslashes($_POST['valor_total']);
    
    $_SESSION['valor_total_form_pagto'] = $valor_total;
    
    if(!isset($_SESSION['orcamento_venda_forma_pagamento'])){
        $_SESSION['orcamento_venda_forma_pagamento'] = array();
    }
    if(!isset($_SESSION['orcamento_venda_forma_pagamento_data'])){
        $_SESSION['orcamento_venda_forma_pagamento_data'] = array();
    }
    if(!isset($_SESSION['orcamento_venda_forma_pagamento_obs'])){
        $_SESSION['orcamento_venda_forma_pagamento_obs'] = array();
    }
    if(!isset($_SESSION['orcamento_venda_forma_pagamento_tipo'])){
        $_SESSION['orcamento_venda_forma_pagamento_tipo'] = array();
    }
    if(!isset($_SESSION['orcamento_venda_forma_pagamento_valor'])){
        $_SESSION['orcamento_venda_forma_pagamento_valor'] = array();
    }
    $valor_total_form_pagto = '0';
    if(count($_SESSION['orcamento_venda_forma_pagamento']) > '0'){
        foreach($_SESSION['orcamento_venda_forma_pagamento'] as $forma_pagamento_indice_grid => $forma_pagamento_id_grid){
            $valor_total_form_pagto += $_SESSION['orcamento_venda_forma_pagamento_valor'][$forma_pagamento_indice_grid];
        }
    }
    $valor_comparacao = $valor_total - $valor_total_form_pagto;
    $valor_comp_return = $valor_comparacao - $forma_pagamento_valor;
    
    if($valor_comparacao < $forma_pagamento_valor){
        $json_forma_pagamento = array(
            'type' => 'error',
            'title' => 'ERROR',
            'msg' => 'Ops, é preciso inserir um valor menor ou igual ao valor total da venda!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }elseif($valor_total < $forma_pagamento_valor){
        $json_forma_pagamento = array(
            'type' => 'error',
            'title' => 'ERROR',
            'msg' => 'Ops, é preciso inserir um valor menor ou igual ao valor total da venda!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }elseif($forma_pagamento_valor == '' || $forma_pagamento_valor == '0'){
        $json_forma_pagamento = array(
            'type' => 'error',
            'title' => 'ERROR',
            'msg' => 'Ops, é preciso inserir um valor!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        $_SESSION['orcamento_venda_forma_pagamento'][] = $forma_pagamento_id;
        $_SESSION['orcamento_venda_forma_pagamento_data'][] = $forma_pagamento_data;
        $_SESSION['orcamento_venda_forma_pagamento_obs'][] = $forma_pagamento_obs;
        $_SESSION['orcamento_venda_forma_pagamento_tipo'][] = $forma_pagamento_tipo;
        $_SESSION['orcamento_venda_forma_pagamento_valor'][] = $forma_pagamento_valor;
        $json_forma_pagamento = array(
            'type' => 'success',
            'valor_restante' => number_format($valor_comp_return,2,".","")
        );
    }
    echo json_encode($json_forma_pagamento);
}elseif($acao == 'load_forma_pagamento_grid'){
    if(count($_SESSION['orcamento_venda_forma_pagamento']) > '0'){
        echo '<table class="table table-hover table-nomargin">
            <thead>
                <tr>
                    <th>Forma Pagamento</th>
                    <th>Valor</th>
                    <th>Data</th>
                    <th>Tipo</th>
                    <th>Obs</th>
                    <th>Opções</th>
                </tr>
            </thead>
            <tbody>';
            foreach($_SESSION['orcamento_venda_forma_pagamento'] as $forma_pagamento_indice_grid => $forma_pagamento_id_grid){
                if($_SESSION['orcamento_venda_forma_pagamento_tipo'][$forma_pagamento_indice_grid] == '0'){
                    $tipo_forma_pagamento = 'A vista';
                }else{
                    $tipo_forma_pagamento = 'Financeiro';
                }
                echo '<tr>';
                    echo '<td>'.GetDados('forma_pagamento', $forma_pagamento_id_grid, 'forma_pagamento_id', 'forma_pagamento_descricao').'</td>';
                    echo '<td>'.FormatMoney($_SESSION['orcamento_venda_forma_pagamento_valor'][$forma_pagamento_indice_grid]).'</td>';
                    echo '<td>'.FormDataBr($_SESSION['orcamento_venda_forma_pagamento_data'][$forma_pagamento_indice_grid]).'</td>';
                    echo '<td>'.$tipo_forma_pagamento.'</td>';
                    echo '<td>'.$_SESSION['orcamento_venda_forma_pagamento_obs'][$forma_pagamento_indice_grid].'</td>';
                    echo '<td><button type="button" class="btn btn-danger" onclick="delete_form_pgto_grid('.$forma_pagamento_indice_grid.');">Deletar</button></td>';
                echo '</th>';
            }
        echo '</tbody>
        </table>';
    }
}elseif($acao == 'del_forma_pagamento'){
    unset($_SESSION['orcamento_venda_forma_pagamento']);
    unset($_SESSION['orcamento_venda_forma_pagamento_data']);
    unset($_SESSION['orcamento_venda_forma_pagamento_obs']);
    unset($_SESSION['orcamento_venda_forma_pagamento_tipo']);
    unset($_SESSION['orcamento_venda_forma_pagamento_valor']);
}elseif($acao == 'delete_forma_pagamento_grid'){
    $id_forma_pagamento = addslashes($_POST['id_forma_pagamento']);
    
    unset($_SESSION['orcamento_venda_forma_pagamento'][$id_forma_pagamento]);
    unset($_SESSION['orcamento_venda_forma_pagamento_data'][$id_forma_pagamento]);
    unset($_SESSION['orcamento_venda_forma_pagamento_obs'][$id_forma_pagamento]);
    unset($_SESSION['orcamento_venda_forma_pagamento_tipo'][$id_forma_pagamento]);
    unset($_SESSION['orcamento_venda_forma_pagamento_valor'][$id_forma_pagamento]);
}elseif($acao == 'val_valor_restante'){
    $valor_total_form_pagto = '0';
    if(count($_SESSION['orcamento_venda_forma_pagamento']) > '0'){
        foreach($_SESSION['orcamento_venda_forma_pagamento'] as $forma_pagamento_indice_grid => $forma_pagamento_id_grid){
            $valor_total_form_pagto += $_SESSION['orcamento_venda_forma_pagamento_valor'][$forma_pagamento_indice_grid];
        }
    }
    $valor_comp_return = $_SESSION['valor_total_form_pagto'] - $valor_total_form_pagto;
    $json_forma_pagamento = array(
        'type' => 'success',
        'valor_restante' => number_format($valor_comp_return,2,".","")
    );
    echo json_encode($json_forma_pagamento);
}elseif($acao == 'load_data'){
    $json_data = array(
        'type' => 'success',
        'title' => 'OK',
        'data' => date('Y-m-d')
    );
    echo json_encode($json_data);
}elseif($acao == 'create'){
    //RECUPERA O FORMULARIO
    $orcamento_venda_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($orcamento_venda_form['acao']);
    
    if($orcamento_venda_form['orcamento_venda_id_contato'] == ''){
        $json_orcamento_venda = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, é preciso selecionar um contato!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }elseif(count($_SESSION['orcamento_venda']) == '0'){
        $json_orcamento_venda = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, é preciso inserir produtos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }elseif(count($_SESSION['orcamento_venda_forma_pagamento']) == '0'){
        $json_orcamento_venda = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, é preciso inserir as formas de pagamento!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        $orcamento_venda_form['orcamento_venda_qtd_parcelas'] = count($_SESSION['orcamento_venda_forma_pagamento']);
        $orcamento_venda_form['orcamento_venda_status'] = '0';
        $orcamento_venda_form['orcamento_venda_tipo'] = '1';
        $orcamento_venda_form['orcamento_venda_id_user'] = $_SESSION[VSESSION]['user_id'];
        $orcamento_venda_form['orcamento_venda_data_hora'] = date('Y-m-d H:i:s');
        Create('orcamento_venda', $orcamento_venda_form);
        
        $id_orcamento_venda = GetReg('orcamento_venda', 'orcamento_venda_id', "");
        foreach($_SESSION['orcamento_venda'] as $produto_id_grid => $produto_qtd_grid){
            $valor_total_grid = $_SESSION['orcamento_venda_valor_unitario'][$produto_id_grid] * $produto_qtd_grid;
            $valor_unitario_grid = $_SESSION['orcamento_venda_valor_unitario'][$produto_id_grid];
            
            $itens_orcamento_venda_form['itens_orcamento_venda_id_produto'] = $produto_id_grid;
            $itens_orcamento_venda_form['itens_orcamento_venda_qtd'] = $produto_qtd_grid;
            $itens_orcamento_venda_form['itens_orcamento_venda_valor_unitario'] = $valor_unitario_grid;
            $itens_orcamento_venda_form['itens_orcamento_venda_valor_total'] = $valor_total_grid;
            $itens_orcamento_venda_form['itens_orcamento_venda_id_orcamento_venda'] = $id_orcamento_venda;
            Create('itens_orcamento_venda', $itens_orcamento_venda_form);
        }
        foreach($_SESSION['orcamento_venda_forma_pagamento'] as $forma_pagamento_indice_grid => $forma_pagamento_id_grid){
            $pagamento_form['pagamento_id_orcamento_venda'] = $id_orcamento_venda;
            $pagamento_form['pagamento_data'] = $_SESSION['orcamento_venda_forma_pagamento_data'][$forma_pagamento_indice_grid];
            $pagamento_form['pagamento_valor'] = $_SESSION['orcamento_venda_forma_pagamento_valor'][$forma_pagamento_indice_grid];
            $pagamento_form['pagamento_id_forma_pagamento'] = $forma_pagamento_id_grid;
            $pagamento_form['pagamento_obs'] = $_SESSION['orcamento_venda_forma_pagamento_obs'][$forma_pagamento_indice_grid];
            $pagamento_form['pagamento_tipo'] = $_SESSION['orcamento_venda_forma_pagamento_tipo'][$forma_pagamento_indice_grid];
            Create('pagamento', $pagamento_form);
            
            if($pagamento_form['pagamento_tipo'] == '0'){
                $caixa_conta_form['caixa_conta_id_venda'] = $id_orcamento_venda;
                $caixa_conta_form['caixa_conta_data_lancamento'] = $pagamento_form['pagamento_data'];
                $caixa_conta_form['caixa_conta_valor_lancamento'] = $pagamento_form['pagamento_valor'];
                $caixa_conta_form['caixa_conta_id_plano_contas'] = GetEmpresa('empresa_venda_id_plano_conta');
                $caixa_conta_form['caixa_conta_numero_doc'] = $id_orcamento_venda;
                $caixa_conta_form['caixa_conta_id_caixa'] = GetDados('user', $_SESSION[VSESSION]['user_id'], 'user_id', 'user_id_caixa');
                $caixa_conta_form['caixa_conta_descricao'] = 'Venda Nº '.$id_orcamento_venda;
                $caixa_conta_form['caixa_conta_tipo_lancamento'] = 'C';
                Create('caixa_conta', $caixa_conta_form);
            }else{
                $financeiro_form['financeiro_codigo'] = GetReg('financeiro', 'financeiro_codigo', "WHERE financeiro_tipo = 'CR'") + 1;
                $financeiro_form['financeiro_tipo'] = 'CR';
                $financeiro_form['financeiro_data_lancamento'] = date('Y-m-d');
                $financeiro_form['financeiro_data_vencimento'] = $pagamento_form['pagamento_data'];
                $financeiro_form['financeiro_valor'] = $pagamento_form['pagamento_valor'];
                $financeiro_form['financeiro_id_plano_conta'] = GetEmpresa('empresa_venda_id_plano_conta');
                $financeiro_form['financeiro_id_tipo_documento'] = GetEmpresa('empresa_venda_id_tipo_documento');
                $financeiro_form['financeiro_descricao'] = 'Recebimento Venda Nº'.$id_orcamento_venda;
                $financeiro_form['financeiro_obs'] = $financeiro_form['financeiro_descricao'];
                $financeiro_form['financeiro_id_contato'] = $orcamento_venda_form['orcamento_venda_id_contato'];
                $financeiro_form['financeiro_md5'] = md5(date('Y-m-dH:is').rand(9,99999999));
                $financeiro_form['financeiro_fixo'] = GetEmpresa('empresa_venda_fixo');
                $financeiro_form['financeiro_app_financeira'] = GetEmpresa('empresa_venda_app_financeiro');
                $financeiro_form['financeiro_status'] = '0';
                $financeiro_form['financeiro_numero_doc'] = $id_orcamento_venda;
                $financeiro_form['financeiro_id_venda'] = $id_orcamento_venda;
                Create('financeiro', $financeiro_form);
            }
        }
        $json_orcamento_venda = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<a href="view/venda/print.php?id='.$id_orcamento_venda.'" class="btn btn-default" target="_blank">Imprimir</a><a href="javascript::" onclick="fechar_modal();" data-dismiss="modal" class="btn btn-primary">Sair</a>'
        );
    }
    
    echo json_encode($json_orcamento_venda);
}elseif($acao == 'load_update'){
    $id_venda = addslashes($_POST['id']);
    
    $read_orcamento_venda = Read('orcamento_venda', "WHERE orcamento_venda_id = '".$id_venda."' AND orcamento_venda_status = '0'");
    if(NumQuery($read_orcamento_venda) > '0'){
        foreach($read_orcamento_venda as $read_orcamento_venda_view);
        $json_orcamento_venda[] = $read_orcamento_venda_view;
        $read_itens_orcamento_venda = Read('itens_orcamento_venda', "WHERE itens_orcamento_venda_id_orcamento_venda = '".$id_venda."'");
        if(NumQuery($read_itens_orcamento_venda) > '0'){
            foreach($read_itens_orcamento_venda as $read_itens_orcamento_venda_view){
                if(!isset($_SESSION['orcamento_venda'])){
                    $_SESSION['orcamento_venda'] = array();
                }
                if(!isset($_SESSION['orcamento_venda_valor_unitario'])){
                    $_SESSION['orcamento_venda_valor_unitario'] = array();
                }

                if(!isset($_SESSION['orcamento_venda'][$read_itens_orcamento_venda_view['itens_orcamento_venda_id_produto']])){
                    $_SESSION['orcamento_venda'][$read_itens_orcamento_venda_view['itens_orcamento_venda_id_produto']] = $read_itens_orcamento_venda_view['itens_orcamento_venda_qtd'];
                }else{
                    $_SESSION['orcamento_venda'][$read_itens_orcamento_venda_view['itens_orcamento_venda_id_produto']] += $read_itens_orcamento_venda_view['itens_orcamento_venda_qtd'];
                }
                if(!isset($_SESSION['orcamento_venda_valor_unitario'][$read_itens_orcamento_venda_view['itens_orcamento_venda_id_produto']])){
                    $_SESSION['orcamento_venda_valor_unitario'][$read_itens_orcamento_venda_view['itens_orcamento_venda_id_produto']] = $read_itens_orcamento_venda_view['itens_orcamento_venda_valor_unitario'];
                }else{
                    $_SESSION['orcamento_venda_valor_unitario'][$read_itens_orcamento_venda_view['itens_orcamento_venda_id_produto']] = $read_itens_orcamento_venda_view['itens_orcamento_venda_valor_unitario'];
                }
            }
        }
        $read_pagamento = Read('pagamento', "WHERE pagamento_id_orcamento_venda = '".$id_venda."'");
        if(NumQuery($read_pagamento) > '0'){
            foreach($read_pagamento as $read_pagamento_view){
                if(!isset($_SESSION['orcamento_venda_forma_pagamento'])){
                    $_SESSION['orcamento_venda_forma_pagamento'] = array();
                }
                if(!isset($_SESSION['orcamento_venda_forma_pagamento_data'])){
                    $_SESSION['orcamento_venda_forma_pagamento_data'] = array();
                }
                if(!isset($_SESSION['orcamento_venda_forma_pagamento_obs'])){
                    $_SESSION['orcamento_venda_forma_pagamento_obs'] = array();
                }
                if(!isset($_SESSION['orcamento_venda_forma_pagamento_tipo'])){
                    $_SESSION['orcamento_venda_forma_pagamento_tipo'] = array();
                }
                if(!isset($_SESSION['orcamento_venda_forma_pagamento_valor'])){
                    $_SESSION['orcamento_venda_forma_pagamento_valor'] = array();
                }
                
                $_SESSION['orcamento_venda_forma_pagamento'][] = $read_pagamento_view['pagamento_id_forma_pagamento'];
                $_SESSION['orcamento_venda_forma_pagamento_data'][] = $read_pagamento_view['pagamento_data'];
                $_SESSION['orcamento_venda_forma_pagamento_obs'][] = $read_pagamento_view['pagamento_obs'];
                $_SESSION['orcamento_venda_forma_pagamento_tipo'][] = $read_pagamento_view['pagamento_tipo'];
                $_SESSION['orcamento_venda_forma_pagamento_valor'][] = $read_pagamento_view['pagamento_valor'];
            }
        }
    }
    echo json_encode($json_orcamento_venda);
}elseif($acao == 'update'){
    //RECUPERA O FORMULARIO
    $orcamento_venda_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($orcamento_venda_form['acao']);
    
    if($orcamento_venda_form['orcamento_venda_id_contato'] == ''){
        $json_orcamento_venda = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, é preciso selecionar um contato!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }elseif(count($_SESSION['orcamento_venda']) == '0'){
        $json_orcamento_venda = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, é preciso inserir produtos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }elseif(count($_SESSION['orcamento_venda_forma_pagamento']) == '0'){
        $json_orcamento_venda = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, é preciso inserir as formas de pagamento!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        $orcamento_venda_form['orcamento_venda_qtd_parcelas'] = count($_SESSION['orcamento_venda_forma_pagamento']);
        $orcamento_venda_form['orcamento_venda_status'] = '0';
        $orcamento_venda_form['orcamento_venda_tipo'] = '1';
        $id_venda = addslashes($_POST['id']);
        unset($orcamento_venda_form['id']);
        Update('orcamento_venda', $orcamento_venda_form, "WHERE orcamento_venda_id = '".$id_venda."'");
        
        $id_orcamento_venda = $id_venda;
        Delete('itens_orcamento_venda', "WHERE itens_orcamento_venda_id_orcamento_venda = '".$id_venda."'");
        foreach($_SESSION['orcamento_venda'] as $produto_id_grid => $produto_qtd_grid){
            $valor_total_grid = $_SESSION['orcamento_venda_valor_unitario'][$produto_id_grid] * $produto_qtd_grid;
            $valor_unitario_grid = $_SESSION['orcamento_venda_valor_unitario'][$produto_id_grid];
            
            $itens_orcamento_venda_form['itens_orcamento_venda_id_produto'] = $produto_id_grid;
            $itens_orcamento_venda_form['itens_orcamento_venda_qtd'] = $produto_qtd_grid;
            $itens_orcamento_venda_form['itens_orcamento_venda_valor_unitario'] = $valor_unitario_grid;
            $itens_orcamento_venda_form['itens_orcamento_venda_valor_total'] = $valor_total_grid;
            $itens_orcamento_venda_form['itens_orcamento_venda_id_orcamento_venda'] = $id_orcamento_venda;
            Create('itens_orcamento_venda', $itens_orcamento_venda_form);
        }
        Delete('pagamento', "WHERE pagamento_id_orcamento_venda = '".$id_venda."'");
        $caixa_conta_update['caixa_conta_id_caixa'] = '0';
        Update('caixa_conta', $caixa_conta_update,  "WHERE caixa_conta_id_venda = '".$id_venda."'");
        $financeiro_update['financeiro_status'] = '2';
        $financeiro_update['financeiro_obs']      = GetDados('financeiro', $id_venda, 'financeiro_id_venda', 'financeiro_obs').'-----Motivo Cancelamento: VENDA EDITADA';
        Update('financeiro', $financeiro_update, "WHERE financeiro_id_venda = '".$id_venda."'");
        foreach($_SESSION['orcamento_venda_forma_pagamento'] as $forma_pagamento_indice_grid => $forma_pagamento_id_grid){
            $pagamento_form['pagamento_id_orcamento_venda'] = $id_orcamento_venda;
            $pagamento_form['pagamento_data'] = $_SESSION['orcamento_venda_forma_pagamento_data'][$forma_pagamento_indice_grid];
            $pagamento_form['pagamento_valor'] = $_SESSION['orcamento_venda_forma_pagamento_valor'][$forma_pagamento_indice_grid];
            $pagamento_form['pagamento_id_forma_pagamento'] = $forma_pagamento_id_grid;
            $pagamento_form['pagamento_obs'] = $_SESSION['orcamento_venda_forma_pagamento_obs'][$forma_pagamento_indice_grid];
            $pagamento_form['pagamento_tipo'] = $_SESSION['orcamento_venda_forma_pagamento_tipo'][$forma_pagamento_indice_grid];
            Create('pagamento', $pagamento_form);
            
            if($pagamento_form['pagamento_tipo'] == '0'){
                $caixa_conta_form['caixa_conta_id_venda'] = $id_orcamento_venda;
                $caixa_conta_form['caixa_conta_data_lancamento'] = $pagamento_form['pagamento_data'];
                $caixa_conta_form['caixa_conta_valor_lancamento'] = $pagamento_form['pagamento_valor'];
                $caixa_conta_form['caixa_conta_id_plano_contas'] = GetEmpresa('empresa_venda_id_plano_conta');
                $caixa_conta_form['caixa_conta_numero_doc'] = $id_orcamento_venda;
                $caixa_conta_form['caixa_conta_id_caixa'] = GetDados('user', $_SESSION[VSESSION]['user_id'], 'user_id', 'user_id_caixa');
                $caixa_conta_form['caixa_conta_descricao'] = 'Venda Nº '.$id_orcamento_venda;
                $caixa_conta_form['caixa_conta_tipo_lancamento'] = 'C';
                Create('caixa_conta', $caixa_conta_form);
            }else{
                $financeiro_form['financeiro_codigo'] = GetReg('financeiro', 'financeiro_codigo', "WHERE financeiro_tipo = 'CR'") + 1;
                $financeiro_form['financeiro_tipo'] = 'CR';
                $financeiro_form['financeiro_data_lancamento'] = date('Y-m-d');
                $financeiro_form['financeiro_data_vencimento'] = $pagamento_form['pagamento_data'];
                $financeiro_form['financeiro_valor'] = $pagamento_form['pagamento_valor'];
                $financeiro_form['financeiro_id_plano_conta'] = GetEmpresa('empresa_venda_id_plano_conta');
                $financeiro_form['financeiro_id_tipo_documento'] = GetEmpresa('empresa_venda_id_tipo_documento');
                $financeiro_form['financeiro_descricao'] = 'Recebimento Venda Nº'.$id_orcamento_venda;
                $financeiro_form['financeiro_obs'] = $financeiro_form['financeiro_descricao'];
                $financeiro_form['financeiro_id_contato'] = $orcamento_venda_form['orcamento_venda_id_contato'];
                $financeiro_form['financeiro_md5'] = md5(date('Y-m-dH:is').rand(9,99999999));
                $financeiro_form['financeiro_fixo'] = GetEmpresa('empresa_venda_fixo');
                $financeiro_form['financeiro_app_financeira'] = GetEmpresa('empresa_venda_app_financeiro');
                $financeiro_form['financeiro_status'] = '0';
                $financeiro_form['financeiro_numero_doc'] = $id_orcamento_venda;
                $financeiro_form['financeiro_id_venda'] = $id_orcamento_venda;
                Create('financeiro', $financeiro_form);
            }
        }
        $json_orcamento_venda = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<a href="view/venda/print.php?id='.$id_orcamento_venda.'" class="btn btn-default" target="_blank">Imprimir</a><a href="javascript::" onclick="fechar_modal();" data-dismiss="modal" class="btn btn-primary">Sair</a>'
        );
    }
    
    echo json_encode($json_orcamento_venda);
}
?>