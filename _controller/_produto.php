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
        $order_by = "ORDER BY produto.produto_id DESC";
    }else{
        $sort       = addslashes($_GET['sort']);
        $sort_dir   = addslashes($_GET['sort_dir']);
        if($sort == 'categoria_descricao' || $sort == 'sub_categoria_descricao'){
            $order_by = "";
        }else{
            $order_by   = "ORDER BY produto.".$sort." ".$sort_dir."";
        }
    }
    
    //PESQUISA VIA AJAX
    if(isset($_GET['search']) && $_GET['search'] == 'true'){
        $get_id         = addslashes($_GET['id']);
        $get_descricao  = addslashes($_GET['descricao']);
        
        if($get_id != ''){
            $sql_id = "AND produto.produto_id = '".$get_id."'";
        }else{
            $sql_id = "";
        }
        if($get_descricao != ''){
            $sql_descricao = "AND produto.produto_descricao LIKE '%".$get_descricao."%'";
        }else{
            $sql_descricao = "";
        }
        
        $_SESSION['produto_load'] = "".$sql_id." ".$sql_descricao." ";
    }
    
    $read_produto_paginator = ReadComposta("SELECT produto.produto_id FROM produto WHERE produto.produto_id != '' {$_SESSION['produto_load']}");
    //$read_produto = Read('produto', "WHERE produto_id != '' {$_SESSION['produto_load']} ".$order_by." LIMIT $inicio,$maximo");
    $read_produto = ReadComposta("SELECT produto.produto_id, produto.produto_descricao, produto.produto_id_categoria, produto.produto_id_sub_categoria, produto.produto_preco_venda, produto.produto_estoque_atual, categoria.categoria_id, categoria.categoria_descricao, sub_categoria.sub_categoria_id, sub_categoria.sub_categoria_descricao FROM produto LEFT JOIN categoria ON produto.produto_id_categoria = categoria.categoria_id LEFT JOIN sub_categoria ON sub_categoria.sub_categoria_id = produto.produto_id_sub_categoria WHERE produto.produto_id != '' {$_SESSION['produto_load']} ".$order_by."");
    if(NumQuery($read_produto) > '0'){
        //PEGA QUANTOS BOTÕES PARA PAGINAÇÃO DE RESULTADOS
        $paginas = ceil(NumQuery($read_produto_paginator) / $maximo);
        //TABULATOR EXIGE ESSA INFORMAÇÃO PARA QUE PRECISE DA PAGINAÇÃO DE RESULTADOS 
        $json_produto["last_page"] = $paginas;
        foreach($read_produto as $read_produto_view){
            $read_produto_view['produto_preco_venda'] = FormatMoney($read_produto_view['produto_preco_venda']);
            $json_produto['data'][] = $read_produto_view;
        }
    }else{
        $json_produto['data'] = null;
    }
    echo json_encode($json_produto);
}elseif($acao == 'create'){
    //RECUPERA O FORMULARIO
    $produto_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($produto_form['acao']);
    
    if($produto_form['produto_descricao'] == '' || $produto_form['produto_preco_venda'] == ''){
        $json_produto = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        Create('produto', $produto_form);
        $json_produto = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal" onclick="limpar_campos();">Apenas Fechar</button><a href="javascript::" onclick="carrega_pagina(\'produto\', \'index.php\');" data-dismiss="modal" class="btn btn-primary">Sair</a>'
        );
    }
    echo json_encode($json_produto);
}elseif($acao == 'update'){
    //RECUPERA O FORMULARIO
    $produto_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($produto_form['acao']);
    
    if($produto_form['produto_descricao'] == '' || $produto_form['produto_preco_venda'] == ''){
        $json_produto = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        $uid = addslashes($_POST['id']);
        unset($produto_form['id']);
        Update('produto', $produto_form, "WHERE produto_id = '".$uid."'");
        $json_produto = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<a href="javascript::" onclick="carrega_pagina(\'produto\', \'index.php\');" data-dismiss="modal" class="btn btn-primary">Sair</a>'
        );
    }
    echo json_encode($json_produto);
}elseif($acao == 'load_update'){
    $uid = addslashes($_POST['id']);
    
    $read_produto = Read('produto', "WHERE produto_id = '".$uid."'");
    if(NumQuery($read_produto) > '0'){
        foreach($read_produto as $read_produto_view);
        $json_produto[] = $read_produto_view;
    }else{
        $json_produto = null;
    }
    echo json_encode($json_produto);
}elseif($acao == 'load_sub_categoria'){
    $id_categoria = addslashes($_POST['id_categoria']);
    $read_sub_categoria = Read('sub_categoria', "WHERE sub_categoria_id_categoria = '".$id_categoria."' ORDER BY sub_categoria_descricao ASC");
    if(NumQuery($read_sub_categoria) > '0'){
        foreach($read_sub_categoria as $read_sub_categoria_view){
            $json_sub_categoria["data"][] = $read_sub_categoria_view;
        }
        echo json_encode($json_sub_categoria);
    }else{
        $json_sub_categoria = array(
            'type' => 'error'
        );
        echo json_encode($json_sub_categoria);
    }
}elseif($acao == 'load_origem'){
    $read_origem = Read('icms_origem', "ORDER BY codigo_icms_origem ASC");
    if(NumQuery($read_origem) > '0'){
        foreach($read_origem as $read_origem_view){
            $read_origem_view['desc_icms_origem'] = utf8_encode($read_origem_view['desc_icms_origem']);
            $json_origem["data"][] = $read_origem_view;
        }
        echo json_encode($json_origem);
    }
}elseif($acao == 'load_grupo_tributarios'){
    $read_tributacao = Read('tributacao', "ORDER BY tributacao_descricao ASC");
    if(NumQuery($read_tributacao) > '0'){
        foreach($read_tributacao as $read_tributacao_view){
            $json_tributacao["data"][] = $read_tributacao_view;
        }
        echo json_encode($json_tributacao);
    }
}elseif($acao == 'load_produto'){
    $term = addslashes($_GET['term']);
    
    $read_produto_load = Read('produto', "WHERE (produto_descricao LIKE '%".$term."%') OR (produto_codigo LIKE '%".$term."%') ORDER BY produto_descricao ASC");
    if(NumQuery($read_produto_load) > '0'){
        $json_produto = '[';
        foreach($read_produto_load as $read_produto_load_view){
            $json_produto .= '{"label":"'.$read_produto_load_view['produto_descricao'].'","value":"'.$read_produto_load_view['produto_id'].'", "valor_produto":"'.$read_produto_load_view['produto_preco_venda'].'"},';
        }
        $json_produto = substr($json_produto, 0,-1);
        $json_produto .= ']';
    }else{
        $json_produto = array(
                array( "label" => '', "value" => '' ),
        );
    }
    echo $json_produto;
}elseif($acao == 'load_produto_id'){
    $uid = addslashes($_POST['id']);
    
    $read_produto_load_id = Read('produto', "WHERE produto_id = '".$uid."' ORDER BY produto_descricao ASC");
    if(NumQuery($read_produto_load_id) > '0'){
        $json_produto = '[';
        foreach($read_produto_load_id as $read_produto_load_id_view){
            $json_produto .= '{"label":"'.$read_produto_load_id_view['produto_descricao'].'","value":"'.$read_produto_load_id_view['produto_id'].'", "valor_produto":"'.$read_produto_load_id_view['produto_preco_venda'].'"},';
        }
        $json_produto = substr($json_produto, 0,-1);
        $json_produto .= ']';
    }else{
        $json_produto = '[';
            $json_produto .= '{"label":"","value":""}';
        $json_produto .= ']';
    }
    echo $json_produto;
}elseif($acao == 'gerar_excel'){
    $arquivo = 'produto.xls';
    $tabela = '<table border="1" width="800px">';
        $tabela .= '<tr>';
            $tabela .= '<td colspan="6" align="center">Relação de produtos</tr>';
        $tabela .= '</tr>';
        $tabela .= '<tr>';
            $tabela .= '<td><b>Código</b></td>';
            $tabela .= '<td><b>Descrição</b></td>';
            $tabela .= '<td><b>Categoria</b></td>';
            $tabela .= '<td><b>SubCategoria</b></td>';
            $tabela .= '<td><b>Preço Venda</b></td>';
            $tabela .= '<td><b>Estoque</b></td>';
        $tabela .= '</tr>';
    
    $read_produto = ReadComposta("SELECT produto.produto_id, produto.produto_descricao, produto.produto_id_categoria, produto.produto_id_sub_categoria, produto.produto_preco_venda, produto.produto_estoque_atual, categoria.categoria_id, categoria.categoria_descricao, sub_categoria.sub_categoria_id, sub_categoria.sub_categoria_descricao FROM produto LEFT JOIN categoria ON produto.produto_id_categoria = categoria.categoria_id LEFT JOIN sub_categoria ON sub_categoria.sub_categoria_id = produto.produto_id_sub_categoria WHERE produto.produto_id != '' {$_SESSION['produto_load']} ORDER BY produto.produto_descricao ASC");
    if(NumQuery($read_produto) > '0'){
        foreach($read_produto as $read_produto_view){
            $tabela .= '<tr>';
                $tabela .= '<td>'.$read_produto_view['produto_id'].'</td>';
                $tabela .= '<td>'.$read_produto_view['produto_descricao'].'</td>';
                $tabela .= '<td>'.$read_produto_view['categoria_descricao'].'</td>';
                $tabela .= '<td>'.$read_produto_view['sub_categoria_descricao'].'</td>';
                $tabela .= '<td>'.FormatMoney($read_produto_view['produto_preco_venda']).'</td>';
                $tabela .= '<td>'.$read_produto_view['produto_estoque_atual'].'</td>';
            $tabela .= '</tr>';
        }
    }
    
    header("Content-type: application/vnd.ms-excel");  
    header("Content-type: application/force-download"); 
    header("Content-Disposition: attachment; filename=file.xls");
    header("Pragma: no-cache");
    echo $tabela;
}
?>