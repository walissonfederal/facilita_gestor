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
        $order_by = "ORDER BY pessoa_id DESC";
    }else{
        
        $sort       = addslashes($_GET['sort']);
        $sort_dir   = addslashes($_GET['sort_dir']);
        $order_by   = "ORDER BY ".$sort." ".$sort_dir."";
    }
    
    //PESQUISA VIA AJAX
    if(isset($_GET['search']) && $_GET['search'] == 'true'){
        $get_id    = addslashes($_GET['id']);
        $get_nome  = addslashes($_GET['nome']);
        $get_email = addslashes($_GET['email']);
        
        if($get_id != ''){
            $sql_id = "AND pessoa_id = '".$get_id."'";
        }else{
            $sql_id = "";
        }
        if($get_nome != ''){
            $sql_nome = "AND pessoa_nome LIKE '%".$get_nome."%'";
        }else{
            $sql_nome = "";
        }
        if($get_email != ''){
            $sql_email = "AND pessoa_email LIKE '%".$get_email."%'";
        }else{
            $sql_email = "";
        }
        
        $_SESSION['pessoa_load'] = "".$sql_id." ".$sql_nome." ".$sql_email." ";
    }
    
    $read_pessoa_paginator = ReadComposta("SELECT pessoa_id FROM pessoa WHERE pessoa_id != '' {$_SESSION['pessoa_load']}");
    $read_pessoa = Read('pessoa', "WHERE pessoa_id != '' {$_SESSION['pessoa_load']} ".$order_by." LIMIT $inicio,$maximo");
    if(NumQuery($read_pessoa) > '0'){
        //PEGA QUANTOS BOTÕES PARA PAGINAÇÃO DE RESULTADOS
        $paginas = ceil(NumQuery($read_pessoa_paginator) / $maximo);
        //TABULATOR EXIGE ESSA INFORMAÇÃO PARA QUE PRECISE DA PAGINAÇÃO DE RESULTADOS 
        $json_pessoa["last_page"] = $paginas;
        foreach($read_pessoa as $read_pessoa_view){
            $json_pessoa['data'][] = $read_pessoa_view;
        }
    }else{
        $json_pessoa['data'] = null;
    }
    echo json_encode($json_pessoa);
}elseif($acao == 'create'){
    //RECUPERA O FORMULARIO
    $pessoa_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($pessoa_form['acao']);
    
    if(in_array('', $pessoa_form)){
        $json_pessoa = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        Create('pessoa', $pessoa_form);
        $json_pessoa = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal" onclick="limpar_campos();">Apenas Fechar</button><a href="javascript::" onclick="carrega_pagina(\'pessoa\', \'index.php\');" data-dismiss="modal" class="btn btn-primary">Sair</a>'
        );
    }
    echo json_encode($json_pessoa);
}elseif($acao == 'update'){
    //RECUPERA O FORMULARIO
    $pessoa_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($pessoa_form['acao']);
    
    if(in_array('', $pessoa_form)){
        $json_pessoa = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        $uid = addslashes($_POST['id']);
        unset($pessoa_form['id']);
        Update('pessoa', $pessoa_form, "WHERE pessoa_id = '".$uid."'");
        $json_pessoa = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<a href="javascript::" data-dismiss="modal" onclick="carrega_pagina(\'pessoa\', \'index.php\');" class="btn btn-primary">Sair</a>'
        );
    }
    echo json_encode($json_pessoa);
}elseif($acao == 'load_update'){
    $uid = addslashes($_POST['id']);
    
    $read_pessoa = Read('pessoa', "WHERE pessoa_id = '".$uid."'");
    if(NumQuery($read_pessoa) > '0'){
        foreach($read_pessoa as $read_pessoa_view);
        $json_pessoa[] = $read_pessoa_view;
    }else{
        $json_pessoa = null;
    }
    echo json_encode($json_pessoa);
}elseif($acao == 'gerar_excel'){
    $arquivo = 'cargo.xls';
    $tabela = '<table border="1" width="800px">';
        $tabela .= '<tr>';
            $tabela .= '<td colspan="3" align="center">Relação de cargos</tr>';
        $tabela .= '</tr>';
        $tabela .= '<tr>';
            $tabela .= '<td><b>Código</b></td>';
            $tabela .= '<td><b>Nome</b></td>';
            $tabela .= '<td><b>Email</b></td>';
            $tabela .= '<td><b>Telefone</b></td>';
            $tabela .= '<td><b>Celular</b></td>';
        $tabela .= '</tr>';
    
    $read_pessoa = Read('pessoa', "WHERE pessoa_id != '' {$_SESSION['pessoa_load']} ORDER BY pessoa_descricao ASC");
    if(NumQuery($read_pessoa) > '0'){
        foreach($read_pessoa as $read_pessoa_view){
            $tabela .= '<tr>';
                $tabela .= '<td>'.$read_pessoa_view['pessoa_id'].'</td>';
                $tabela .= '<td>'.$read_pessoa_view['pessoa_nome'].'</td>';
                $tabela .= '<td>'.$read_pessoa_view['pessoa_email'].'</td>';
                $tabela .= '<td>'.$read_pessoa_view['pessoa_telefone'].'</td>';
                $tabela .= '<td>'.$read_pessoa_view['pessoa_celular'].'</td>';
            $tabela .= '</tr>';
        }
    }
    
    header("Content-type: application/vnd.ms-excel");  
    header("Content-type: application/force-download"); 
    header("Content-Disposition: attachment; filename=file.xls");
    header("Pragma: no-cache");
    echo $tabela;
}elseif($acao == 'load_pessoa'){
    $term = addslashes($_GET['term']);
    
    $read_pessoa_load = Read('pessoa', "WHERE (pessoa_nome LIKE '%".$term."%') OR (pessoa_email LIKE '%".$term."%') OR (pessoa_obs LIKE '%".$term."%') ORDER BY pessoa_nome ASC");
    if(NumQuery($read_pessoa_load) > '0'){
        $json_pessoa = '[';
        foreach($read_pessoa_load as $read_pessoa_load_view){
            $json_pessoa .= '{"label":"'.$read_pessoa_load_view['pessoa_nome'].' | '.$read_pessoa_load_view['pessoa_email'].' | '.$read_pessoa_load_view['pessoa_telefone'].'","value":"'.$read_pessoa_load_view['pessoa_id'].'"},';
        }
        $json_pessoa = substr($json_pessoa, 0,-1);
        $json_pessoa .= ']';
    }else{
        $json_pessoa = array(
                array( "label" => '', "value" => '' ),
        );
    }
    echo $json_pessoa;
}elseif($acao == 'load_pessoa_id'){
    $uid = addslashes($_POST['id']);
    
    $read_pessoa_load = Read('pessoa', "WHERE pessoa_id = '".$uid."' ORDER BY pessoa_nome ASC");
    if(NumQuery($read_pessoa_load) > '0'){
        $json_pessoa = '[';
        foreach($read_pessoa_load as $read_pessoa_load_view){
            $json_pessoa .= '{"label":"'.$read_pessoa_load_view['pessoa_nome'].' | '.$read_pessoa_load_view['pessoa_email'].' | '.$read_pessoa_load_view['pessoa_telefone'].'","value":"'.$read_pessoa_load_view['pessoa_id'].'"},';
        }
        $json_pessoa = substr($json_pessoa, 0,-1);
        $json_pessoa .= ']';
    }else{
        $json_pessoa = array(
                array( "label" => '', "value" => '' ),
        );
    }
    echo $json_pessoa;
}
?>