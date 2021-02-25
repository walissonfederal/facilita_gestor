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
        $order_by = "ORDER BY contato_id DESC";
    }else{
        
        $sort       = addslashes($_GET['sort']);
        $sort_dir   = addslashes($_GET['sort_dir']);
        if($sort == 'contato_status_view'){
            $order_by   = "ORDER BY contato_id ".$sort_dir."";
        }else{
            $order_by   = "ORDER BY ".$sort." ".$sort_dir."";
        }
    }
    
    //PESQUISA VIA AJAX
    if(isset($_GET['search']) && $_GET['search'] == 'true'){
        $get_id             = addslashes($_GET['id']);
        $get_nome_razao     = addslashes($_GET['nome_razao']);
        $get_status         = addslashes($_GET['status']);
        $get_nome_fantasia  = addslashes($_GET['nome_fantasia']);
        $get_cpf_cnpj       = addslashes($_GET['cpf_cnpj']);
        $get_email          = addslashes($_GET['email']);
        
        if($get_id != ''){
            $sql_id = "AND contato_id = '".$get_id."'";
        }else{
            $sql_id = "";
        }
        if($get_nome_razao != ''){
            $sql_nome_razao = "AND contato_nome_razao LIKE '%".$get_nome_razao."%'";
        }else{
            $sql_nome_razao = "";
        }
        if($get_status != ''){
            $sql_status = "AND contato_status = '".$get_status."'";
        }else{
            $sql_status = "";
        }
        if($get_nome_fantasia != ''){
            $sql_nome_fantasia = "AND contato_nome_fantasia LIKE '%".$get_nome_fantasia."%'";
        }else{
            $sql_nome_fantasia = "";
        }
        if($get_cpf_cnpj != ''){
            $sql_cpf_cnpj = "AND contato_cpf_cnpj LIKE '%".$get_cpf_cnpj."%'";
        }else{
            $sql_cpf_cnpj = "";
        }
        if($get_email != ''){
            $sql_email = "AND contato_email LIKE '%".$get_email."%'";
        }else{
            $sql_email = "";
        }
        
        $_SESSION['contato_load'] = "".$sql_id." ".$sql_nome_razao." ".$sql_status." ".$sql_nome_fantasia." ".$sql_cpf_cnpj." ".$sql_email." ";
    }
    
    $read_contato_paginator = ReadComposta("SELECT contato_id FROM contato WHERE contato_id != '' {$_SESSION['contato_load']}");
    $read_contato = Read('contato', "WHERE contato_id != '' {$_SESSION['contato_load']} ".$order_by." LIMIT $inicio,$maximo");
    if(NumQuery($read_contato) > '0'){
        //PEGA QUANTOS BOTÕES PARA PAGINAÇÃO DE RESULTADOS
        $paginas = ceil(NumQuery($read_contato_paginator) / $maximo);
        //TABULATOR EXIGE ESSA INFORMAÇÃO PARA QUE PRECISE DA PAGINAÇÃO DE RESULTADOS 
        $json_contato["last_page"] = $paginas;
        foreach($read_contato as $read_contato_view){
            if($read_contato_view['contato_status'] == '0'){
                $read_contato_view['contato_status_view'] = 'Ativo';
            }else{
                $read_contato_view['contato_status_view'] = 'Inativo';
            }
            //$json_contato['data'][] = $read_contato_view;
            $json_contato['data'][] = array_map('utf8_encode', $read_contato_view);
        }
    }else{
        $json_contato['data'] = null;
    }
    echo json_encode($json_contato);
}elseif($acao == 'create'){
    //RECUPERA O FORMULARIO
    $contato_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($contato_form['acao']);
    
    $read_contato = ReadComposta("SELECT contato_id FROM contato WHERE contato_cpf_cnpj = '".$contato_form['contato_cpf_cnpj']."'");
    
    if($contato_form['contato_nome_razao'] == '' || $contato_form['contato_cpf_cnpj'] == '' || $contato_form['contato_cep'] == ''){
        $json_contato = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }elseif(strlen($contato_form['contato_cpf_cnpj']) != '11' && strlen($contato_form['contato_cpf_cnpj']) != '14'){
        $json_contato = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada, campo aceita apenas 11 ou 14 caracters!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }elseif(NumQuery($read_contato)){
        $json_contato = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a já existir um CPF / CNPJ!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
		$contato_form['contato_bloqueio_desbloqueio'] = '0';
        Create('contato', $contato_form);
        $json_contato = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal" onclick="limpar_campos();">Apenas Fechar</button><a href="javascript::" onclick="carrega_pagina(\'contato\', \'index.php\');" data-dismiss="modal" class="btn btn-primary">Sair</a>'
        );
    }
    echo json_encode($json_contato);
}elseif($acao == 'update'){
    //RECUPERA O FORMULARIO
    $contato_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($contato_form['acao']);
    
    if($contato_form['contato_nome_razao'] == ''){
        $json_contato = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        $uid = addslashes($_POST['id']);
        unset($contato_form['id']);
        Update('contato', $contato_form, "WHERE contato_id = '".$uid."'");
        $json_contato = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<a href="javascript::" onclick="carrega_pagina(\'contato\', \'index.php\');" data-dismiss="modal" class="btn btn-primary">Sair</a>'
        );
    }
    echo json_encode($json_contato);
}elseif($acao == 'load_update'){
    $uid = addslashes($_POST['id']);
    
    $read_contato = Read('contato', "WHERE contato_id = '".$uid."'");
    if(NumQuery($read_contato) > '0'){
        foreach($read_contato as $read_contato_view);
        $json_contato[] = array_map('utf8_encode', $read_contato_view);
        //$json_contato[] = $read_contato_view;
    }else{
        $json_contato = null;
    }
    echo json_encode($json_contato);
}elseif($acao == 'load_estado'){
    $term = addslashes($_GET['term']);
    
    $read_estado_load = Read('estado', "WHERE nome_estado LIKE '%".$term."%' ORDER BY nome_estado ASC");
    if(NumQuery($read_estado_load) > '0'){
        $json_estado = '[';
        foreach($read_estado_load as $read_estado_load_view){
            $json_estado .= '{"label":"'.utf8_encode($read_estado_load_view['nome_estado']).'","value":"'.$read_estado_load_view['id_estado'].'"},';
        }
        $json_estado = substr($json_estado, 0,-1);
        $json_estado .= ']';
    }else{
        $json_estado = array(
                array( "label" => '', "value" => '' ),
        );
    }
    echo $json_estado;
}elseif($acao == 'load_estado_id'){
    $uid = addslashes($_POST['id']);
    
    $read_estado_load_id = Read('estado', "WHERE id_estado = '".$uid."' ORDER BY nome_estado ASC");
    if(NumQuery($read_estado_load_id) > '0'){
        $json_estado = '[';
        foreach($read_estado_load_id as $read_estado_load_id_view){
            $json_estado .= '{"label":"'.$read_estado_load_id_view['nome_estado'].'","value":"'.$read_estado_load_id_view['id_estado'].'"},';
        }
        $json_estado = substr($json_estado, 0,-1);
        $json_estado .= ']';
    }else{
        $json_estado = '[';
            $json_estado .= '{"label":"","value":""}';
        $json_estado .= ']';
    }
    echo $json_estado;
}elseif($acao == 'load_cidade'){
    $term = addslashes($_GET['term']);
    $id_estado = addslashes($_GET['id_estado']);
    
    if($id_estado != ''){
        $sql_id_estado = "AND id_estado = '".$id_estado."'";
    }else{
        $sql_id_estado = "";
    }
    
    $read_cidade_load = Read('cidade', "WHERE nome_cidade LIKE '%".$term."%' {$sql_id_estado} ORDER BY nome_cidade ASC");
    if(NumQuery($read_cidade_load) > '0'){
        $json_cidade = '[';
        foreach($read_cidade_load as $read_cidade_load_view){
            $json_cidade .= '{"label":"'.utf8_encode($read_cidade_load_view['nome_cidade']).'","value":"'.$read_cidade_load_view['id_cidade'].'"},';
        }
        $json_cidade = substr($json_cidade, 0,-1);
        $json_cidade .= ']';
    }else{
        $json_cidade = array(
                array( "label" => '', "value" => '' ),
        );
    }
    echo $json_cidade;
}elseif($acao == 'load_cidade_id'){
    $uid = addslashes($_POST['id']);
    
    $read_cidade_load_id = Read('cidade', "WHERE id_cidade = '".$uid."' ORDER BY nome_cidade ASC");
    if(NumQuery($read_cidade_load_id) > '0'){
        $json_cidade = '[';
        foreach($read_cidade_load_id as $read_cidade_load_id_view){
            $json_cidade .= '{"label":"'.$read_cidade_load_id_view['nome_cidade'].'","value":"'.$read_cidade_load_id_view['id_cidade'].'"},';
        }
        $json_cidade = substr($json_cidade, 0,-1);
        $json_cidade .= ']';
    }else{
        $json_cidade = '[';
            $json_cidade .= '{"label":"","value":""}';
        $json_cidade .= ']';
    }
    echo $json_cidade;
}elseif($acao == 'load_contato'){
    $term = addslashes($_GET['term']);
    
    $read_contato_load = Read('contato', "WHERE (contato_nome_razao LIKE '%".$term."%') OR (contato_nome_fantasia LIKE '%".$term."%') OR (contato_cpf_cnpj LIKE '%".$term."%') ORDER BY contato_nome_razao ASC");
    if(NumQuery($read_contato_load) > '0'){
        $json_contato = '[';
        foreach($read_contato_load as $read_contato_load_view){
            $json_contato .= '{"label":"'.$read_contato_load_view['contato_nome_razao'].' | '.$read_contato_load_view['contato_nome_fantasia'].' | '.$read_contato_load_view['contato_cpf_cnpj'].'","value":"'.$read_contato_load_view['contato_id'].'"},';
        }
        $json_contato = substr($json_contato, 0,-1);
        $json_contato .= ']';
    }else{
        $json_contato = array(
                array( "label" => '', "value" => '' ),
        );
    }
    echo $json_contato;
}elseif($acao == 'load_contato_id'){
    $uid = addslashes($_POST['id']);
    
    $read_contato_load_id = Read('contato', "WHERE contato_id = '".$uid."' ORDER BY contato_nome_razao ASC");
    if(NumQuery($read_contato_load_id) > '0'){
        $json_contato = '[';
        foreach($read_contato_load_id as $read_contato_load_id_view){
            $json_contato .= '{"label":"'.$read_contato_load_id_view['contato_nome_razao'].' | '.$read_contato_load_id_view['contato_nome_fantasia'].' | '.$read_contato_load_id_view['contato_cpf_cnpj'].'","value":"'.$read_contato_load_id_view['contato_id'].'"},';
        }
        $json_contato = substr($json_contato, 0,-1);
        $json_contato .= ']';
    }else{
        $json_contato = '[';
            $json_contato .= '{"label":"","value":""}';
        $json_contato .= ']';
    }
    echo $json_contato;
}elseif($acao == 'gerar_pesquisa'){
    $get_contato_cliente            = addslashes($_POST['contato_cliente']);
    $get_contato_cpf_cnpj           = addslashes($_POST['contato_cpf_cnpj']);
    $get_contato_fornecedor         = addslashes($_POST['contato_fornecedor']);
    $get_contato_id_regiao          = addslashes($_POST['contato_id_regiao']);
    $get_contato_id_rota            = addslashes($_POST['contato_id_rota']);
    $get_contato_id_tipo_contato    = addslashes($_POST['contato_id_tipo_contato']);
    $get_contato_status             = addslashes($_POST['contato_status']);
    $get_contato_transportador      = addslashes($_POST['contato_transportador']);
    
    $get_colunas = $_POST['my-select'];
    
    if($get_contato_cliente != ''){
        $sql_contato_cliente = "AND contato_cliente = '".$get_contato_cliente."'";
    }else{
        $sql_contato_cliente = "";
    }
    if($get_contato_cpf_cnpj != ''){
        $sql_contato_cpf_cnpj = "AND contato_cpf_cnpj = '".$get_contato_cpf_cnpj."'";
    }else{
        $sql_contato_cpf_cnpj = "";
    }
    if($get_contato_fornecedor != ''){
        $sql_contato_fornecedor = "AND contato_fornecedor = '".$get_contato_fornecedor."'";
    }else{
        $sql_contato_fornecedor = "";
    }
    if($get_contato_id_regiao != ''){
        $sql_contato_id_regiao = "AND contato_id_regiao = '".$get_contato_id_regiao."'";
    }else{
        $sql_contato_id_regiao = "";
    }
    if($get_contato_id_rota != ''){
        $sql_contato_id_rota = "AND contato_id_rota = '".$get_contato_id_rota."'";
    }else{
        $sql_contato_id_rota = "";
    }
    if($get_contato_id_tipo_contato != ''){
        $sql_contato_id_tipo_contato = "AND contato_id_tipo_contato = '".$get_contato_id_tipo_contato."'";
    }else{
        $sql_contato_id_tipo_contato = "";
    }
    if($get_contato_status != ''){
        $sql_contato_status = "AND contato_status = '".$get_contato_status."'";
    }else{
        $sql_contato_status = "";
    }
    if($get_contato_transportador != ''){
        $sql_contato_transportador = "AND contato_transportador = '".$get_contato_transportador."'";
    }else{
        $sql_contato_transportador = "";
    }
    
    $_SESSION['contato_load'] = " ".$sql_contato_cliente." ".$sql_contato_cpf_cnpj." ".$sql_contato_fornecedor." ".$sql_contato_id_regiao." ".$sql_contato_id_rota." ".$sql_contato_id_tipo_contato." ".$sql_contato_status." ".$sql_contato_transportador." ";
    if(count($get_colunas) < '7'){
        echo '<br /><br /><br /><p>É preciso selecionar pelo menos 7 opções do BI</p>';
    }else{
        $array_colunas = implode(",", $get_colunas);
        $_SESSION['report_pdf_contato'] = $array_colunas;
        echo '<button type="button" class="btn btn-primary" onclick="gerar_pdf();">Gerar PDF</button>
          <button type="button" class="btn btn-primary" onclick="gerar_excel();">Gerar EXCEL</button>';
    }
}elseif($acao == 'gerar_excel'){
    $arquivo = 'contato.xls';
    $tabela = '<table border="1" width="800px">';
        $tabela .= '<tr>';
            $tabela .= '<td colspan="3" align="center">Relação de contatos</tr>';
        $tabela .= '</tr>';
        $tabela .= '<tr>';
            $tabela .= '<td><b>Código</b></td>';
            $tabela .= '<td><b>Nome Razão Social</b></td>';
            $tabela .= '<td><b>Nome Fantasia</b></td>';
            $tabela .= '<td width="200px"><b>CPF / CNPJ</b></td>';
            $tabela .= '<td><b>IE / RG</b></td>';
            $tabela .= '<td><b>CEP</b></td>';
            $tabela .= '<td><b>Endereço</b></td>';
            $tabela .= '<td><b>Número</b></td>';
            $tabela .= '<td><b>Bairro</b></td>';
            $tabela .= '<td><b>Estado</b></td>';
            $tabela .= '<td><b>Cidade</b></td>';
            $tabela .= '<td><b>Telefone</b></td>';
            $tabela .= '<td><b>Email</b></td>';
            $tabela .= '<td><b>Status</b></td>';
        $tabela .= '</tr>';
    
    $read_contato = Read('contato', "WHERE contato_id != '' {$_SESSION['contato_load']} ORDER BY contato_nome_fantasia ASC");
    if(NumQuery($read_contato) > '0'){
        foreach($read_contato as $read_contato_view){
            if($read_regiao_view['contato_status'] == '0'){
                $status_contato = 'ATIVO';
            }else{
                $status_contato = 'INATIVO';
            }
            $tabela .= '<tr>';
                $tabela .= '<td>'.$read_contato_view['contato_id'].'</td>';
                $tabela .= '<td>'.$read_contato_view['contato_nome_razao'].'</td>';
                $tabela .= '<td>'.$read_contato_view['contato_nome_fantasia'].'</td>';
                $tabela .= '<td>-'.$read_contato_view['contato_cpf_cnpj'].'-</td>';
                $tabela .= '<td>-'.$read_contato_view['contato_ie_rg'].'-</td>';
                $tabela .= '<td>'.$read_contato_view['contato_cep'].'</td>';
                $tabela .= '<td>'.$read_contato_view['contato_endereco'].'</td>';
                $tabela .= '<td>'.$read_contato_view['contato_numero'].'</td>';
                $tabela .= '<td>'.$read_contato_view['contato_bairro'].'</td>';
                $tabela .= '<td>'.$read_contato_view['contato_estado'].'</td>';
                $tabela .= '<td>'.$read_contato_view['contato_cidade'].'</td>';
                $tabela .= '<td>'.$read_contato_view['contato_telefone'].'</td>';
                $tabela .= '<td>'.$read_contato_view['contato_email'].'</td>';
                $tabela .= '<td>'.$status_regiao.'</td>';
            $tabela .= '</tr>';
        }
    }
    
    header("Content-type: application/vnd.ms-excel");  
    header("Content-type: application/force-download"); 
    header("Content-Disposition: attachment; filename=file.xls");
    header("Pragma: no-cache");
    echo $tabela;
}elseif($acao == 'buscar_cnpj_cpf'){
    $dado = trim($_POST['dado']);
    
    if(strlen($dado) == '14'){
        if(!ValCnpj($dado)){
            $json_contato = array(
                'type' => 'error',
                'title' => 'Erro:',
                'msg' => 'Ops, cnpj inválido, por favor insira um válido!',
                'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
            );
        }else{
            $json_contato = array(
                'type' => 'success',
                'title' => 'Parabéns:',
                'msg' => '',
                'buttons' => '<a href="javascript::" onclick="carrega_pagina(\'contato\', \'index.php\');" data-dismiss="modal" class="btn btn-primary">Sair</a>'
            );
        }
    }elseif(strlen($dado) == '11'){
        if(!ValCpf($dado)){
            $json_contato = array(
                'type' => 'error',
                'title' => 'Erro:',
                'msg' => 'Ops, cpf inválido, por favor insira um válido!',
                'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
            );
        }else{
            $json_contato = array(
                'type' => 'success',
                'title' => 'Parabéns:',
                'msg' => '',
                'buttons' => '<a href="javascript::" onclick="carrega_pagina(\'contato\', \'index.php\');" data-dismiss="modal" class="btn btn-primary">Sair</a>'
            );
        }
    }else{
        $json_contato = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, registro inválido!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }
    echo json_encode($json_contato);
}
?>