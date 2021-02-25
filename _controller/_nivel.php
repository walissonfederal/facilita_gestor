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
        $order_by = "ORDER BY nivel_id DESC";
    }else{
        
        $sort       = addslashes($_GET['sort']);
        $sort_dir   = addslashes($_GET['sort_dir']);
        if($sort == 'nivel_status_view'){
            $order_by   = "ORDER BY nivel_id ".$sort_dir."";
        }else{
            $order_by   = "ORDER BY ".$sort." ".$sort_dir."";
        }
    }
    
    //PESQUISA VIA AJAX
    if(isset($_GET['search']) && $_GET['search'] == 'true'){
        $get_id         = addslashes($_GET['id']);
        $get_descricao  = addslashes($_GET['descricao']);
        $get_status     = addslashes($_GET['status']);
        
        if($get_id != ''){
            $sql_id = "AND nivel_id = '".$get_id."'";
        }else{
            $sql_id = "";
        }
        if($get_descricao != ''){
            $sql_descricao = "AND nivel_descricao LIKE '%".$get_descricao."%'";
        }else{
            $sql_descricao = "";
        }
        if($get_status != ''){
            $sql_status = "AND nivel_status = '".$get_status."'";
        }else{
            $sql_status = "";
        }
        
        $_SESSION['nivel_load'] = "".$sql_id." ".$sql_descricao." ".$sql_status." ";
    }
    
    $read_nivel_paginator = ReadComposta("SELECT nivel_id FROM nivel WHERE nivel_id != '' {$_SESSION['nivel_load']}");
    $read_nivel = Read('nivel', "WHERE nivel_id != '' {$_SESSION['nivel_load']} ".$order_by." LIMIT $inicio,$maximo");
    if(NumQuery($read_nivel) > '0'){
        //PEGA QUANTOS BOTÕES PARA PAGINAÇÃO DE RESULTADOS
        $paginas = ceil(NumQuery($read_nivel_paginator) / $maximo);
        //TABULATOR EXIGE ESSA INFORMAÇÃO PARA QUE PRECISE DA PAGINAÇÃO DE RESULTADOS 
        $json_nivel["last_page"] = $paginas;
        foreach($read_nivel as $read_nivel_view){
            if($read_nivel_view['nivel_status'] == '0'){
                $read_nivel_view['nivel_status_view'] = 'Ativo';
            }else{
                $read_nivel_view['nivel_status_view'] = 'Inativo';
            }
            $json_nivel['data'][] = $read_nivel_view;
        }
    }else{
        $json_nivel['data'] = null;
    }
    echo json_encode($json_nivel);
}elseif($acao == 'create'){
    //RECUPERA O FORMULARIO
    $nivel_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($nivel_form['acao']);
    
    if(in_array('', $nivel_form)){
        $json_nivel = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        Create('nivel', $nivel_form);
        $json_nivel = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal" onclick="limpar_campos();">Apenas Fechar</button><a href="javascript::" onclick="carrega_pagina(\'nivel\', \'index.php\');" data-dismiss="modal" class="btn btn-primary">Sair</a>'
        );
    }
    echo json_encode($json_nivel);
}elseif($acao == 'update'){
    //RECUPERA O FORMULARIO
    $nivel_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($nivel_form['acao']);
    
    if(in_array('', $nivel_form)){
        $json_nivel = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        $uid = addslashes($_POST['id']);
        unset($nivel_form['id']);
        Update('nivel', $nivel_form, "WHERE nivel_id = '".$uid."'");
        $json_nivel = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<a href="javascript::" onclick="carrega_pagina(\'nivel\', \'index.php\');" data-dismiss="modal" class="btn btn-primary">Sair</a>'
        );
    }
    echo json_encode($json_nivel);
}elseif($acao == 'load_update'){
    $uid = addslashes($_POST['id']);
    
    $read_nivel = Read('nivel', "WHERE nivel_id = '".$uid."'");
    if(NumQuery($read_nivel) > '0'){
        foreach($read_nivel as $read_nivel_view);
        $json_nivel[] = $read_nivel_view;
    }else{
        $json_nivel = null;
    }
    echo json_encode($json_nivel);
}elseif($acao == 'gerar_excel'){
    $arquivo = 'regiao.xls';
    $tabela = '<table border="1" width="800px">';
        $tabela .= '<tr>';
            $tabela .= '<td colspan="3" align="center">Relação de níveis</tr>';
        $tabela .= '</tr>';
        $tabela .= '<tr>';
            $tabela .= '<td><b>Código</b></td>';
            $tabela .= '<td><b>Descrição</b></td>';
            $tabela .= '<td><b>Status</b></td>';
        $tabela .= '</tr>';
    
    $read_nivel = Read('nivel', "WHERE nivel_id != '' {$_SESSION['nivel_load']} ORDER BY nivel_descricao ASC");
    if(NumQuery($read_nivel) > '0'){
        foreach($read_nivel as $read_nivel_view){
            if($read_nivel_view['nivel_status'] == '0'){
                $status_nivel = 'ATIVO';
            }else{
                $status_nivel = 'INATIVO';
            }
            $tabela .= '<tr>';
                $tabela .= '<td>'.$read_nivel_view['nivel_id'].'</td>';
                $tabela .= '<td>'.$read_nivel_view['nivel_descricao'].'</td>';
                $tabela .= '<td>'.$status_nivel.'</td>';
            $tabela .= '</tr>';
        }
    }
    
    header("Content-type: application/vnd.ms-excel");  
    header("Content-type: application/force-download"); 
    header("Content-Disposition: attachment; filename=file.xls");
    header("Pragma: no-cache");
    echo $tabela;
}elseif($acao == 'load_nivel'){
    $read_nivel = Read('nivel', "ORDER BY nivel_descricao ASC");
    if(NumQuery($read_nivel) > '0'){
        foreach($read_nivel as $read_nivel_view){
            $json_nivel["data"][] = $read_nivel_view;
        }
        echo json_encode($json_nivel);
    }
}elseif($acao == 'create_permissao_menu'){
    $uid = addslashes($_POST['id']);
    $menu_home  = $_POST['menu_home'];
    $count_home = count($menu_home);
    
    if($count_home > '0'){
        Delete('permissao_menu', "WHERE permissao_menu_id_nivel = '".$uid."'");
        for($x = 0; $x < $count_home; $x++){
            $permissao_menu_form['permissao_menu_id_menu'] = $menu_home[$x];
            $permissao_menu_form['permissao_menu_id_nivel'] = $uid;
            Create('permissao_menu', $permissao_menu_form);
        }
        $json_permissao_menu = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<a href="javascript::" onclick="carrega_pagina(\'nivel\', \'index.php\');" data-dismiss="modal" class="btn btn-primary">Sair</a>'
        );
    }else{
        $json_permissao_menu = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }
    echo json_encode($json_permissao_menu);
}elseif($acao == 'load_permissao_menu'){
    $uid = addslashes($_POST['id']);
    
    $read_permissao_menu = Read('permissao_menu', "WHERE permissao_menu_id_nivel = '".$uid."'");
    if(NumQuery($read_permissao_menu) > '0'){
        foreach($read_permissao_menu as $read_permissao_menu_view){
            $json_permissao_menu[] = $read_permissao_menu_view;
        }
    }else{
        $json_permissao_menu = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, permissão não encontrada',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }
    echo json_encode($json_permissao_menu);
}elseif($acao == 'load_permissao_page'){
    $uid = addslashes($_POST['id']);
    
    $read_modulo = Read('modulo', "");
    if(NumQuery($read_modulo) > '0'){
        foreach($read_modulo as $read_modulo_view){
            echo '<strong>'.$read_modulo_view['modulo_descricao'].'</strong>';
            echo '<div class="checkbox">';
                    $read_pagina = Read('pagina', "WHERE pagina_id_modulo = '".$read_modulo_view['modulo_id']."'");
                    if(NumQuery($read_pagina) > '0'){
                        foreach($read_pagina as $read_pagina_view){
                            $read_permissao_page = ReadComposta("SELECT permissao_page_id FROM permissao_page WHERE permissao_page_id_nivel = '".$uid."' AND permissao_page_id_page = '".$read_pagina_view['pagina_id']."'");
                            if(NumQuery($read_permissao_page) > '0'){
                                $checked_permissao_page = 'checked';
                            }else{
                                $checked_permissao_page = '';
                            }
                            echo '<label>
                                <input type="checkbox" name="page_home[]" value="'.$read_pagina_view['pagina_id'].'" '.$checked_permissao_page.'>'.$read_pagina_view['pagina_descricao'].'
                            </label>&nbsp;';
                        }
                    }
            echo '</div>';
            echo '<hr />';
        }
    }
}elseif($acao == 'create_permissao_page'){
    $uid = addslashes($_POST['id']);
    $page_home  = $_POST['page_home'];
    $count_home = count($page_home);
    
    if($count_home > '0'){
        Delete('permissao_page', "WHERE permissao_page_id_nivel = '".$uid."'");
        for($x = 0; $x < $count_home; $x++){
            $permissao_page_form['permissao_page_id_page'] = $page_home[$x];
            $permissao_page_form['permissao_page_id_nivel'] = $uid;
            Create('permissao_page', $permissao_page_form);
        }
        $json_permissao_page = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<a href="javascript::" onclick="carrega_pagina(\'nivel\', \'index.php\');" data-dismiss="modal" class="btn btn-primary">Sair</a>'
        );
    }else{
        $json_permissao_page = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }
    echo json_encode($json_permissao_page);
}elseif($acao == 'verifica_permissao'){
    $pasta      = addslashes($_POST['pasta']);
    $arquivo    = addslashes($_POST['arquivo']);
    $id_nivel   = $_SESSION[VSESSION]['user_id_nivel'];
    
    $read_permissao = ReadComposta("SELECT pagina.pagina_arquivo,
                                        pagina.pagina_modulo,
                                        pagina.pagina_id,
                                        permissao_page.permissao_page_id_nivel,
                                        permissao_page.permissao_page_id_page
                                    FROM pagina
                                    INNER JOIN permissao_page
                                    ON permissao_page.permissao_page_id_page = pagina.pagina_id
                                    WHERE pagina.pagina_arquivo = '".$arquivo."'
                                    AND pagina.pagina_modulo = '".$pasta."'
                                    AND permissao_page.permissao_page_id_nivel = '".$id_nivel."'");
    if(NumQuery($read_permissao) == '0'){
        $json_permissao_page = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, sem permissão!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        $json_permissao_page = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => ''
        );
    }
    echo json_encode($json_permissao_page);
}
?>