<?php
session_start();
ob_start();
require_once '../_class/Ferramenta.php';

if(isset($_POST['acao'])){
    $acao = addslashes($_POST['acao']);
}else{
    $acao = addslashes($_GET['acao']);
}

if($acao == 'load_update'){
    $read_empresa = Read('empresa', "WHERE empresa_id = '1'");
    if(NumQuery($read_empresa) > '0'){
        foreach($read_empresa as $read_empresa_view);
        $json_empresa[] = $read_empresa_view;
    }else{
        $json_empresa = null;
    }
    echo json_encode($json_empresa);
}elseif($acao == 'update'){
    //RECUPERA O FORMULARIO
    $empresa_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($empresa_form['acao']);
    
    if($empresa_form['empresa_nome_razao'] == ''){
        $json_empresa = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        Update('empresa', $empresa_form, "WHERE empresa_id = '1'");
        $json_empresa = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<a href="javascript::" onclick="carrega_pagina(\'empresa\', \'update.php\');" data-dismiss="modal" class="btn btn-primary">Sair</a>'
        );
    }
    echo json_encode($json_empresa);
}elseif($acao == 'load_boleto'){
    $read_boleto = Read('boleto', "ORDER BY boleto_descricao ASC");
    echo '<table class="table table-hover table-nomargin">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Descrição</th>
                    <th>Banco</th>
                    <th colspan="2">Opções</th>
                </tr>
            </thead>
            <tbody>';
    if(NumQuery($read_boleto) > '0'){
        foreach($read_boleto as $read_boleto_view){
            if($read_boleto_view['boleto_banco'] == '0'){
                $banco_name = 'CAIXA  - CEF';
            }
            echo '<tr>';
                echo '<td>'.$read_boleto_view['boleto_id'].'</td>';
                echo '<td>'.$read_boleto_view['boleto_descricao'].'</td>';
                echo '<td>'.$banco_name.'</td>';
                echo '<td><button type="button" class="btn btn-primary" onclick="load_update_boleto('.$read_boleto_view['boleto_id'].');">Editar</button></td>';
            echo '</th>';
            $count_produto_grid++;
        }
    }
    echo '</tbody>
        </table>';
}elseif($acao == 'create_boleto'){
    //RECUPERA O FORMULARIO
    $boleto_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($boleto_form['acao']);
    
    if($boleto_form['boleto_descricao'] == ''){
        $json_boleto = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        Create('boleto', $boleto_form);
        $json_boleto = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<a href="javascript::" onclick="close_empresa();" data-dismiss="modal" class="btn btn-primary">Sair</a>'
        );
    }
    echo json_encode($json_boleto);
}elseif($acao == 'load_update_boleto'){
    $uid = addslashes($_POST['id']);
    $read_boleto = Read('boleto', "WHERE boleto_id = '".$uid."'");
    if(NumQuery($read_boleto) > '0'){
        foreach($read_boleto as $read_boleto_view);
        $json_boleto[] = $read_boleto_view;
    }else{
        $json_boleto = null;
    }
    echo json_encode($json_boleto);
}elseif($acao == 'update_boleto'){
    //RECUPERA O FORMULARIO
    $boleto_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($boleto_form['acao']);
    
    if($boleto_form['boleto_descricao'] == ''){
        $json_boleto = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        $uid = addslashes($boleto_form['id']);
        unset($boleto_form['id']);
        Update('boleto', $boleto_form, "WHERE boleto_id = '".$uid."'");
        $json_boleto = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<a href="javascript::" onclick="close_empresa();" class="btn btn-primary">Sair</a>'
        );
    }
    echo json_encode($json_boleto);
}elseif($acao == 'load_boleto_financeiro'){
    $read_boleto = Read('boleto', "ORDER BY boleto_descricao ASC");
    echo '<table class="table table-hover table-nomargin">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Descrição</th>
                    <th>Banco</th>
                    <th colspan="2">Opções</th>
                </tr>
            </thead>
            <tbody>';
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
            echo '<tr>';
                echo '<td>'.$read_boleto_view['boleto_id'].'</td>';
                echo '<td>'.$read_boleto_view['boleto_descricao'].'</td>';
                echo '<td>'.$banco_name.'</td>';
                echo '<td><a href="_boleto_pdf/_mj_boleto_geracao/_boletos/gerar.php?00='.$type_boleto.'&01='.$model_boleto.'&02='.  base64_encode($read_boleto_view['boleto_id']).'&03=uri_boleto" class="btn btn-primary" target="_blank">Imprimir</button></td>';
            echo '</th>';
        }
    }
    echo '</tbody>
        </table>';
}elseif($acao == 'load_carne_financeiro'){
    $read_boleto = Read('boleto', "ORDER BY boleto_descricao ASC");
    echo '<table class="table table-hover table-nomargin">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Descrição</th>
                    <th>Banco</th>
                    <th colspan="2">Opções</th>
                </tr>
            </thead>
            <tbody>';
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
            echo '<tr>';
                echo '<td>'.$read_boleto_view['boleto_id'].'</td>';
                echo '<td>'.$read_boleto_view['boleto_descricao'].'</td>';
                echo '<td>'.$banco_name.'</td>';
                echo '<td><a href="_boleto_carne/boleto_cef.php?00='.$type_boleto.'&01='.$model_boleto.'&02='.  base64_encode($read_boleto_view['boleto_id']).'&03=uri_boleto" class="btn btn-primary" target="_blank">Imprimir</button></td>';
            echo '</th>';
        }
    }
    echo '</tbody>
        </table>';
}elseif($acao == 'load_remessa_financeiro'){
    $read_boleto = Read('boleto', "ORDER BY boleto_descricao ASC");
    echo '<table class="table table-hover table-nomargin">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Descrição</th>
                    <th>Banco</th>
                    <th colspan="2">Opções</th>
                </tr>
            </thead>
            <tbody>';
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
                $banco_name_com = '0';
            }
            echo '<tr>';
                echo '<td>'.$read_boleto_view['boleto_id'].'</td>';
                echo '<td>'.$read_boleto_view['boleto_descricao'].'</td>';
                echo '<td>'.$banco_name.'</td>';
                echo '<td><a href="#" class="btn btn-primary" onclick="gerar_remessa('.$banco_name_com.', '.$read_boleto_view['boleto_id'].');">Gerar Remessa</button></td>';
            echo '</th>';
        }
    }
    echo '</tbody>
        </table>';
}elseif($acao == 'load_boletos_empresa'){
    $read_boleto = Read('boleto', "ORDER BY boleto_descricao ASC");
    if(NumQuery($read_boleto) > '0'){
        foreach($read_boleto as $read_boleto_view){
            $json_boleto["data"][] = $read_boleto_view;
        }
        echo json_encode($json_boleto);
    }
}
?>