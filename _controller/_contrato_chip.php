<?php
session_start();
ob_start();
//fim verificação d4sign
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
        $order_by = "ORDER BY contrato_chip.contrato_chip_id DESC";
    }else{
        
        $sort       = addslashes($_GET['sort']);
        $sort_dir   = addslashes($_GET['sort_dir']);
        $order_by   = "ORDER BY ".$sort." ".$sort_dir."";
    }
    
    //PESQUISA VIA AJAX
    if(isset($_GET['search']) && $_GET['search'] == 'true'){
        $get_id          = addslashes($_GET['id']);
        $get_id_contato  = addslashes($_GET['id_contato']);
        $get_status      = addslashes($_GET['status']);
        
        if($get_id != ''){
            $sql_id = "AND contrato_chip.contrato_chip_id = '".$get_id."'";
        }else{
            $sql_id = "";
        }
        if($get_id_contato != ''){
            $sql_id_contato = "AND contrato_chip.contrato_chip_id_contato = '".$get_id_contato."'";
        }else{
            $sql_id_contato = "";
        }
        if($get_status != ''){
            $sql_status = "AND contrato_chip.contrato_chip_status = '".$get_status."'";
        }else{
            $sql_status = "";
        }
        
        $_SESSION['contrato_chip_load'] = "".$sql_id." ".$sql_id_contato." ".$sql_status." ";
    }
    
    $read_contrato_chip_paginator = ReadComposta("SELECT contrato_chip_id FROM contrato_chip WHERE contrato_chip_id != '' {$_SESSION['contrato_chip_load']}");
    $read_contrato_chip = ReadComposta("SELECT contrato_chip_id, contato_nome_razao, contrato_chip_data_inicial, contrato_chip_data_final, contrato_chip_status, contrato_chip_cliente_assinou FROM contrato_chip LEFT JOIN contato ON contato.contato_id = contrato_chip.contrato_chip_id_contato WHERE contrato_chip.contrato_chip_id != '' {$_SESSION['contrato_chip_load']} ".$order_by." LIMIT $inicio,$maximo");
    if(NumQuery($read_contrato_chip) > '0'){
        //PEGA QUANTOS BOTÕES PARA PAGINAÇÃO DE RESULTADOS
        $paginas = ceil(NumQuery($read_contrato_chip_paginator) / $maximo);
        //TABULATOR EXIGE ESSA INFORMAÇÃO PARA QUE PRECISE DA PAGINAÇÃO DE RESULTADOS 
        $json_contrato["last_page"] = $paginas;
        foreach($read_contrato_chip as $read_contrato_chip_view){
            if($read_contrato_chip_view['contrato_chip_status'] == '0'){
                $read_contrato_chip_view['contrato_chip_status'] = 'EM VIGOR';
            }elseif($read_contrato_chip_view['contrato_chip_status'] == '1'){
                $read_contrato_chip_view['contrato_chip_status'] = 'PROCESSANDO';
            }elseif($read_contrato_chip_view['contrato_chip_status'] == '2'){
                $read_contrato_chip_view['contrato_chip_status'] = 'AGUARDANDO SIGNATÁRIOS';
            }elseif($read_contrato_chip_view['contrato_chip_status'] == '3'){
                $read_contrato_chip_view['contrato_chip_status'] = 'AGUARDANDO ASSINATURAS';
            }elseif($read_contrato_chip_view['contrato_chip_status'] == '5'){
                $read_contrato_chip_view['contrato_chip_status'] = 'ARQUIVADO';
            }elseif($read_contrato_chip_view['contrato_chip_status'] == '6'){
                $read_contrato_chip_view['contrato_chip_status'] = 'CANCELADO';
            }
            if($read_contrato_chip_view['contrato_chip_cliente_assinou'] == '0'){
                $read_contrato_chip_view['contrato_chip_cliente_assinou'] = 'NÃO';
            }else{
                $read_contrato_chip_view['contrato_chip_cliente_assinou'] = 'SIM';
            }
            $json_contrato['data'][] = $read_contrato_chip_view;
        }
    }else{
        $json_contrato['data'] = null;
    }
    echo json_encode($json_contrato);
}elseif($acao == 'create'){
    //RECUPERA O FORMULARIO
    $contrato_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($contrato_form['acao']);
    
    if(in_array('', $contrato_form)){
        $json_contrato = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        if(Create('contrato_chip', $contrato_form)){
            $id_contrato_ultimo = GetReg('contrato_chip', 'contrato_chip_id', "");
            $read_contrato_chip = ReadComposta("SELECT contato.contato_nome_razao, contato.contato_nome_fantasia, contato.contato_cpf_cnpj, contato.contato_cep, contato.contato_endereco, contato.contato_numero, contato.contato_bairro, contato.contato_estado, contato.contato_cidade, contato.contato_telefone FROM contrato_chip INNER JOIN contato ON contato.contato_id = contrato_chip.contrato_chip_id_contato WHERE contrato_chip.contrato_chip_id = '".$id_contrato_ultimo."'");
            if(NumQuery($read_contrato_chip) > '0'){
                foreach($read_contrato_chip as $read_contrato_chip_view);
            }
            $templates = array(
                ID_CONTRATO_CHIP_D4SIGN => array(
                    'CLIENTE_NOME_RAZAO_SOCIAL' => $read_contrato_chip_view['contato_nome_razao'],
                    'CLIENTE_NOME_FANTASIA' => $read_contrato_chip_view['contato_nome_fantasia'],
                    'CLIENTE_ENDERECO' => $read_contrato_chip_view['contato_endereco'],
                    'CLIENTE_NUMERO' => $read_contrato_chip_view['contato_numero'],
                    'CLIENTE_BAIRRO' => $read_contrato_chip_view['contato_bairro'],
                    'CLIENTE_CIDADE' => $read_contrato_chip_view['contato_cidade'],
                    'CLIENTE_ESTADO' => $read_contrato_chip_view['contato_estado'],
                    'CLIENTE_CEP' => $read_contrato_chip_view['contato_cep'],
                    'CLIENTE_CNPJ_CPF' => $read_contrato_chip_view['contato_cpf_cnpj'],
                    'CLIENTE_TELEFONE' => $read_contrato_chip_view['contato_telefone']
                )
            );

            $name_document = "Contrato Chip";
            $uuid_cofre = ID_COFRE_CONTRATO_CHIP_D4SIGN;

            $return = $client->documents->makedocumentbytemplate($uuid_cofre, $name_document, $templates);
            if($return->message == 'success'){
                $UPDATE_CONTRATO_CHIP['contrato_chip_id_d4sign'] = $return->uuid;
                Update('contrato_chip', $UPDATE_CONTRATO_CHIP, "WHERE contrato_chip_id = '".$id_contrato_ultimo."'");
            }
            $json_contrato = array(
                'type' => 'success',
                'title' => 'Parabéns:',
                'msg' => 'Operação realizada com sucesso',
                'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal" onclick="limpar_campos();">Apenas Fechar</button><a href="javascript::" onclick="carrega_pagina(\'contrato\', \'index_chip.php\');" data-dismiss="modal" class="btn btn-primary">Sair</a>'
            );
        }else{
            $json_contrato = array(
                'type' => 'error',
                'title' => 'Erro:',
                'msg' => 'Ops, operação não pode ser finalizada devido ao contato já ter um cadastro em nosso sistema!',
                'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
            );
        }
    }
    echo json_encode($json_contrato);
}elseif($acao == 'update'){
    //RECUPERA O FORMULARIO
    $contrato_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($contrato_form['acao']);
    
    if(in_array('', $contrato_form)){
        $json_contrato = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        $uid = addslashes($_POST['id']);
        unset($contrato_form['id']);
        if(Update('contrato_chip', $contrato_form, "WHERE contrato_chip_id = '".$uid."'")){
            $json_contrato = array(
                'type' => 'success',
                'title' => 'Parabéns:',
                'msg' => 'Operação realizada com sucesso',
                'buttons' => '<a href="javascript::" data-dismiss="modal" onclick="carrega_pagina(\'contrato\', \'index_chip.php\');" class="btn btn-primary">Sair</a>'
            );
        }else{
            $json_contrato = array(
                'type' => 'error',
                'title' => 'Erro:',
                'msg' => 'Ops, operação não pode ser finalizada devido ao contato já ter um cadastro em nosso sistema!',
                'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
            );
        }
    }
    echo json_encode($json_contrato);
}elseif($acao == 'load_update'){
    $uid = addslashes($_POST['id']);
    
    $read_contrato = Read('contrato_chip', "WHERE contrato_chip_id = '".$uid."'");
    if(NumQuery($read_contrato) > '0'){
        foreach($read_contrato as $read_contrato_view);
        $json_contrato[] = $read_contrato_view;
    }else{
        $json_contrato = null;
    }
    echo json_encode($json_contrato);
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
}elseif($acao == 'anexo'){
    $anexo_contrato_chip_id_contrato = addslashes($_POST['anexo_contrato_chip_id_contrato']);
    
    if($anexo_contrato_chip_id_contrato == ''){
        $data['sucesso'] = false;

        $data['msg'] = 'Todos os campos devem ser preenchidos!';
    }else{
        $arquivo = $_FILES['arquivo'];

        $tipos = array('pdf', 'jpg', 'eml', 'zip', 'rar');

        $enviar = uploadFile($arquivo, '../_uploads/'.GetEmpresa('empresa_nome_pasta').'/contrato_chip/', $tipos);

        $data['sucesso'] = false;

        if($enviar['erro']){    
            $data['msg'] = $enviar['erro'];
        }else{
            $data['sucesso'] = true;

            $data['msg'] = $enviar['caminho'];
            
            $anexo_contrato_form['anexo_contrato_chip_arquivo']      = $enviar['caminho'];
            $anexo_contrato_form['anexo_contrato_chip_data_hora']    = date('Y-m-d H:i:s');
            $anexo_contrato_form['anexo_contrato_chip_id_contrato']  = $anexo_contrato_chip_id_contrato;
            Create('anexo_contrato_chip', $anexo_contrato_form);
        }
    }
    echo json_encode($data);
}elseif($acao == 'load_gallery'){
    $post_id_contrato = addslashes($_POST['id']);
    
    $read_anexo_contrato = Read('anexo_contrato_chip', "WHERE anexo_contrato_chip_id_contrato = '".$post_id_contrato."'");
    if(NumQuery($read_anexo_contrato) > '0'){
        echo '<ul class="gallery">';
        foreach($read_anexo_contrato as $read_anexo_contrato_view){
            $ext = substr($read_anexo_contrato_view['anexo_contrato_chip_arquivo'], -3);
            if($ext == 'jpg'){
                echo '<li>
                    <a href="#">
                        <img src="'.substr($read_anexo_contrato_view['anexo_contrato_chip_arquivo'],3,500).'" width="200" height="200" alt="">
                    </a>
                    <div class="extras">
                        <div class="extras-inner">
                            <a href="'.substr($read_anexo_contrato_view['anexo_contrato_chip_arquivo'],3,500).'" target="_blank" class="colorbox-image" rel="group-1">
                                <i class="fa fa-search"></i>
                            </a>
                            <a href="#" class="del-gallery-pic" onclick="delete_gallery('.$read_anexo_contrato_view['anexo_contrato_chip_id'].');">
                                <i class="fa fa-trash-o"></i>
                            </a>
                        </div>
                    </div>
                </li>';
            }else{
                echo '<li>
                    <a href="#">
                        <img src="_img/download_pdf.png" width="200" height="200" alt="">
                    </a>
                    <div class="extras">
                        <div class="extras-inner">
                            <a href="'.substr($read_anexo_contrato_view['anexo_contrato_chip_arquivo'],3,500).'" target="_blank" class="colorbox-image" rel="group-1">
                                <i class="fa fa-search"></i>
                            </a>
                            <a href="#" class="del-gallery-pic" onclick="delete_gallery('.$read_anexo_contrato_view['anexo_contrato_chip_id'].');">
                                <i class="fa fa-trash-o"></i>
                            </a>
                        </div>
                    </div>
                </li>';
            }
        }
        echo '</ul>';
    }
}elseif($acao == 'delete_gallery'){
    $post_arquivo = addslashes($_POST['id_arquivo']);
    
    $read_arquivo = Read('anexo_contrato_chip', "WHERE anexo_contrato_chip_id = '".$post_arquivo."'");
    if(NumQuery($read_arquivo) > '0'){
        foreach($read_arquivo as $read_arquivo_view);
        Delete('anexo_contrato_chip', "WHERE anexo_contrato_chip_id = '".$post_arquivo."'");
        unlink($read_arquivo_view['anexo_contrato_chip_arquivo']);
    }
}elseif($acao == 'load_aditivo'){
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
        $order_by = "ORDER BY contrato_chip_aditivo_id_aditivo DESC";
    }else{
        $sort       = addslashes($_GET['sort']);
        $sort_dir   = addslashes($_GET['sort_dir']);
        $order_by   = "ORDER BY ".$sort." ".$sort_dir."";
    }
    
    $id_contrato = addslashes($_GET['id_contrato']);
    
    $read_contrato_chip_paginator = ReadComposta("SELECT contrato_chip_aditivo_id FROM contrato_chip_aditivo WHERE contrato_chip_aditivo_id_contrato = '".$id_contrato."'");
    $read_contrato_chip = ReadComposta("SELECT * FROM contrato_chip_aditivo WHERE contrato_chip_aditivo_id_contrato = '".$id_contrato."' ".$order_by." LIMIT $inicio,$maximo");
    if(NumQuery($read_contrato_chip) > '0'){
        //PEGA QUANTOS BOTÕES PARA PAGINAÇÃO DE RESULTADOS
        $paginas = ceil(NumQuery($read_contrato_chip_paginator) / $maximo);
        //TABULATOR EXIGE ESSA INFORMAÇÃO PARA QUE PRECISE DA PAGINAÇÃO DE RESULTADOS 
        $json_contrato["last_page"] = $paginas;
        foreach($read_contrato_chip as $read_contrato_chip_view){
            if($read_contrato_chip_view['contrato_chip_aditivo_status'] == '0'){
                $read_contrato_chip_view['contrato_chip_aditivo_status'] = 'EM VIGOR';
            }else{
                $read_contrato_chip_view['contrato_chip_aditivo_status'] = 'CANCELADO';
            }
            if($read_contrato_chip_view['contrato_chip_aditivo_cliente_assinou'] == '0'){
                $read_contrato_chip_view['contrato_chip_aditivo_cliente_assinou'] = 'NÃO';
            }else{
                $read_contrato_chip_view['contrato_chip_aditivo_cliente_assinou'] = 'SIM';
            }
            $json_contrato['data'][] = $read_contrato_chip_view;
        }
    }else{
        $json_contrato['data'] = null;
    }
    echo json_encode($json_contrato);
}elseif($acao == 'create_aditivo_pedido'){
    //RECUPERA O FORMULARIO
    $contrato_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($contrato_form['acao']);
    
    if(in_array('', $contrato_form)){
        $json_contrato = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        $id_pedido = $contrato_form['contrato_chip_aditivo_id_pedido'];
        
        $read_pedido = ReadComposta("SELECT pedido_id, pedido_tipo FROM pedido WHERE pedido_id = '".$id_pedido."'");
        if(NumQuery($read_pedido) > '0'){
            foreach($read_pedido as $read_pedido_view);
            $read_aditivo = ReadComposta("SELECT contrato_chip_aditivo_id_pedido FROM contrato_chip_aditivo WHERE contrato_chip_aditivo_id_pedido = '".$id_pedido."'");
            if(NumQuery($read_aditivo) > '0'){
                $json_contrato = array(
                    'type' => 'error',
                    'title' => 'Erro:',
                    'msg' => 'Ops, operação não pode ser finalizada devido já existir um aditivo para esse pedido!',
                    'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
                );
            }else{
                $read_itens_pedido = ReadComposta("SELECT itens_pedido_id_chip FROM itens_pedido WHERE itens_pedido_id_pedido = '".$id_pedido."'");
                if(NumQuery($read_itens_pedido) > '0'){
                    $read_pedido_aditivo = ReadComposta("SELECT contrato_chip_aditivo_id_aditivo FROM contrato_chip_aditivo WHERE contrato_chip_aditivo_id_contrato = '".$contrato_form['contrato_chip_aditivo_id_contrato']."' ORDER BY contrato_chip_aditivo_id_aditivo DESC LIMIT 1");
                    if(NumQuery($read_pedido_aditivo) > '0'){
                        foreach($read_pedido_aditivo as $read_pedido_aditivo_view);
                        $id_aditivo_tipo = $read_pedido_aditivo_view['contrato_chip_aditivo_id_aditivo'] + 1;
                    }else{
                        $id_aditivo_tipo = '1';
                    }
                    $contrato_form['contrato_chip_aditivo_id_aditivo'] = $id_aditivo_tipo;
                    Create('contrato_chip_aditivo', $contrato_form);
                    $read_pedido_add = ReadComposta("SELECT contrato_chip_aditivo_id FROM contrato_chip_aditivo WHERE contrato_chip_aditivo_id_contrato = '".$contrato_form['contrato_chip_aditivo_id_contrato']."' ORDER BY contrato_chip_aditivo_id DESC LIMIT 1");
                    if(NumQuery($read_pedido_add) > '0'){
                        foreach($read_pedido_add as $read_pedido_add_view);
                        foreach($read_itens_pedido as $read_itens_pedido_view){
                            $add_pedido['contrato_chip_aditivo_chip_id_aditivo'] = $read_pedido_add_view['contrato_chip_aditivo_id'];
                            $add_pedido['contrato_chip_aditivo_chip_id_chip'] = $read_itens_pedido_view['itens_pedido_id_chip'];
                            $add_pedido['contrato_chip_aditivo_chip_tipo'] = $read_pedido_view['pedido_tipo'];
                            Create('contrato_chip_aditivo_chip', $add_pedido);
                        }
                    }
                    $json_contrato = array(
                        'type' => 'success',
                        'title' => 'Parabéns:',
                        'msg' => 'Operação realizada com sucesso',
                        'buttons' => '<a href="javascript::" data-dismiss="modal" onclick="carrega_pagina(\'contrato\', \'index_chip.php\');" class="btn btn-primary">Sair</a>'
                    );
                }else{
                    $json_contrato = array(
                        'type' => 'error',
                        'title' => 'Erro:',
                        'msg' => 'Ops, operação não pode ser finalizada devido a não existir chips vinculados a esse pedido!',
                        'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
                    );
                }
            }
        }else{
            $json_contrato = array(
                'type' => 'error',
                'title' => 'Erro:',
                'msg' => 'Ops, operação não pode ser finalizada devido ao pedido selecionado não existir em nossa base de dados!',
                'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
            );
        }
    }
    echo json_encode($json_contrato);
}elseif($acao == 'load_chip_insert'){
    $term = addslashes($_GET['term']);
    
    $read_chip_load = Read('chip', "WHERE (chip_status = '1') AND  ((chip_num LIKE '%".$term."%') OR (chip_iccid LIKE '%".$term."%')) ORDER BY chip_id ASC LIMIT 10");
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
}elseif($acao == 'insert_chip_desinstalacao'){
    if(!isset($_SESSION['desinstalacao_chip'])){
        $_SESSION['desinstalacao_chip'] = array();
    }
    if(!isset($_SESSION['desinstalacao_chip_multa'])){
        $_SESSION['desinstalacao_chip_multa'] = array();
    }
    $id_contato = addslashes($_POST['id_contato']);
    $id_chip    = addslashes($_POST['id_chip']);
    $devolucao_chip = addslashes($_POST['devolucao_chip']);
    
    $read_itens_pedido = Read('itens_pedido', "WHERE itens_pedido_id_chip = '".$id_chip."' ORDER BY itens_pedido_id DESC LIMIT 1");
    if(NumQuery($read_itens_pedido) > '0'){
        foreach($read_itens_pedido as $read_itens_pedido_view);
        $read_pedido = Read('pedido', "WHERE pedido_id = '".$read_itens_pedido_view['itens_pedido_id_pedido']."' AND pedido_id_cliente = '".$id_contato."'");
        if(NumQuery($read_pedido) > '0'){
            foreach($read_pedido as $read_pedido_view);
            $read_aditivo_contrato = Read('contrato_chip_aditivo', "WHERE contrato_chip_aditivo_id_pedido = '".$read_pedido_view['pedido_id']."'");
            if(NumQuery($read_aditivo_contrato) > '0'){
                foreach($read_aditivo_contrato as $read_aditivo_contrato_view);
            }else{
                $valor_plano_mensal = ($read_pedido_view['pedido_valor_plano'] / 30);
                //$quant_dia_fim = DiferencaDias(date('d/m/Y'), FormDataBr($read_aditivo_contrato_view['contrato_chip_aditivo_data_final']));
                //$calcular_valor_multa = ((20 / 100) * $valor_plano_mensal) * $quant_dia_fim;
                if(isset($_SESSION['desinstalacao_chip'][$id_chip])){
                    $_SESSION['desinstalacao_chip'][$id_chip] = $devolucao_chip;
                }else{
                    $_SESSION['desinstalacao_chip'][$id_chip] = $devolucao_chip;
                }
		$calcular_valor_multa = '0';
                if(isset($_SESSION['desinstalacao_chip_multa'][$id_chip])){
                    if($calcular_valor_multa <= '0'){
                        $calcular_valor_multa = '0';
                    }
                    $_SESSION['desinstalacao_chip_multa'][$id_chip] = $calcular_valor_multa;
                }else{
                    if($calcular_valor_multa <= '0'){
                        $calcular_valor_multa = '0';
                    }
                    $_SESSION['desinstalacao_chip_multa'][$id_chip] = $calcular_valor_multa;
                }
                $json_contrato = array(
                    'type' => 'success',
                    'title' => 'Parabéns:',
                    'msg' => 'Ops, operação não pode ser realizada, chip pertence a outro cliente!',
                    'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
                );
            }
        }else{
            $json_contrato = array(
                'type' => 'error',
                'title' => 'Erro:',
                'msg' => 'Ops, operação não pode ser realizada, chip pertence a outro cliente!',
                'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
            );
        }
    }else{
        $json_contrato = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser realizada, chip inválido!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }
    echo json_encode($json_contrato);
}elseif($acao == 'load_chip_desinstalacao'){
    $count_itens_pedidos = '0';
    if(count($_SESSION['desinstalacao_chip']) > '0'){
        echo '<table class="table">';
            echo '<thead>';
                echo '<tr>';
                    echo '<th>#</th>';
                    echo '<th>Id Chip</th>';
                    echo '<th>Multa</th>';
                    echo '<th>Faturar Chip</th>';
                    echo '<th>Valor Chip</th>';
                    echo '<th>Valor Total</th>';
                    echo '<th colspan="2">ações</th>';
                echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
        foreach($_SESSION['desinstalacao_chip'] as $id_chip => $devolucao_chip){
            $count_itens_pedidos++;
            if($devolucao_chip == '0'){
                $devolucao_dados = 'Não';
                $valor_chip = '0';
            }else{
                $devolucao_dados = 'Sim';
                $valor_chip = '15';
            }
            $valor_total_chip = $valor_chip + $_SESSION['desinstalacao_chip_multa'][$id_chip];
            $valor_total_chips_completos += $valor_total_chip;
            echo '<tr>';
                echo '<td>'.$count_itens_pedidos.'</td>';
                echo '<td>'.GetDados('chip', $id_chip, 'chip_id', 'chip_num').'</td>';
                echo '<td>'. FormatMoney($_SESSION['desinstalacao_chip_multa'][$id_chip]).'</td>';
                echo '<td>'.$devolucao_dados.'</td>';
                echo '<td>'. FormatMoney($valor_chip).'</td>';
                echo '<td>'. FormatMoney($valor_total_chip).'</td>';
                echo '<td><a href="#" onclick="delete_chip_pedido('.$id_chip.');">X</a></td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
        echo '<p><strong>Valor total que será cobrado no faturamento: '. FormatMoney($valor_total_chips_completos).'</strong></p>';
        echo '<div class="row">
            <div class="form-group col-lg-12" align="right">
                <button type="button" class="btn btn-primary" onclick="validar();">Gravar</button>
                <a href="javascript::" onclick="carrega_pagina(\'pedido\', \'index.php\');" class="btn btn-danger">Voltar</a>
            </div>
        </div>';
    }
}elseif($acao == 'delete_chip_desinstalacao'){
    $id_chip = addslashes($_POST['id_chip']);
    
    unset($_SESSION['desinstalacao_chip'][$id_chip]);
    unset($_SESSION['desinstalacao_chip_multa'][$id_chip]);
}elseif($acao == 'create_desinstalacao'){
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
        Create('pedido', $pedido_form);
        $read_pedido = ReadComposta("SELECT pedido_id FROM pedido WHERE pedido_tipo = '1' ORDER BY pedido_id DESC LIMIT 1");
        if(NumQuery($read_pedido) > '0'){
            foreach($read_pedido as $read_pedido_view);
            if(count($_SESSION['desinstalacao_chip']) > '0'){
                foreach($_SESSION['desinstalacao_chip'] as $id_chip => $devolucao_chip){
                    if($devolucao_chip == '0'){
                        $devolucao_dados = 'Não';
                        $valor_chip = '0';
                    }else{
                        $devolucao_dados = 'Sim';
                        $valor_chip = '15';
                    }
                    $valor_total_chip = $valor_chip + $_SESSION['desinstalacao_chip_multa'][$id_chip];
                    $pedido_desinstalacao['pedido_desinstalacao_id_chip'] = $id_chip;
                    $pedido_desinstalacao['pedido_desinstalacao_valor_multa'] = $_SESSION['desinstalacao_chip_multa'][$id_chip];
                    $pedido_desinstalacao['pedido_desinstalacao_faturar'] = $devolucao_chip;
                    $pedido_desinstalacao['pedido_desinstalacao_valor_fatura'] = $valor_chip;
                    $pedido_desinstalacao['pedido_desinstalacao_data'] = date('Y-m-d');
                    $pedido_desinstalacao['pedido_desinstalacao_id_pedido'] = $read_pedido_view['pedido_id'];
                    $pedido_desinstalacao['pedido_desinstalacao_valor_total'] = $valor_total_chip;
                    Create('pedido_desinstalacao', $pedido_desinstalacao);
                    $itens_pedido['itens_pedido_id_pedido'] = $read_pedido_view['pedido_id'];
                    $itens_pedido['itens_pedido_id_chip'] = $id_chip;
                    $itens_pedido['itens_pedido_num_chip'] = GetDados('chip', $id_chip, 'chip_id', 'chip_num');
                    $itens_pedido['itens_pedido_iccid'] = GetDados('chip', $id_chip, 'chip_id', 'chip_iccid');
                    Create('itens_pedido', $itens_pedido);
                    $UpDatePedidoChip['chip_status'] = '0';
                    Update('chip', $UpDatePedidoChip, "WHERE chip_id = '".$id_chip."'");
                }
            }
        }
        $json_pedido = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso',
            'buttons' => '<a href="javascript::" onclick="carrega_pagina(\'pedido\', \'index.php\');" data-dismiss="modal" class="btn btn-primary">Sair</a>'
        );
    }
    echo json_encode($json_pedido);
}elseif($acao == 'enviar_contrato'){
    $emails  = addslashes($_POST['emails']);
    $msg     = addslashes($_POST['msg']);
    $arquivo = addslashes(trim($_POST['arquivo']));
    
    
    if($emails == '' || $msg == ''){
        echo '<p>Existem campos a serem preenchidos</p>';
    }else{
        $signers = array(
            array("email" => $emails, "act" => '1', "foreign" => '0', "certificadoicpbr" => '0', "assinatura_presencial" => '0', "embed_methodauth" => 'email', "embed_smsnumber" => ''),
        );
        //$return = $client->documents->resend($arquivo, $emails);
        //$return = $client->documents->createList($arquivo, $signers);
        $return_correto = $client->documents->resend($arquivo, $emails);
        //print_r($return_correto);
        if($return->message['0']->status == 'created'){
            $message = $msg;
            $workflow = 0;
            $skip_email = 0;
            $doc = $client->documents->sendToSigner($arquivo, $message, $workflow, $skip_email);
            //$return_correto = $client->documents->resend($arquivo, $emails);
            echo '<p>Operação realizada com sucesso!</p>';
        }else{
            echo '<p>Houve um erro de comunicação</p>';
        }
    }
}elseif($acao == 'enviar_contrato_aditivo'){
    $id_aditivo_contrato = addslashes($_POST['id_aditivo_contrato']);
    $email = addslashes($_POST['email']);
    $msg   = addslashes($_POST['msg']);
    $html_instalacao = '';
    $html_desinstalacao = '';
    $read_contrato_chip = ReadComposta("SELECT * FROM contrato_chip_aditivo_chip WHERE contrato_chip_aditivo_chip_id_aditivo = '".$id_aditivo_contrato."'");
    if(NumQuery($read_contrato_chip) > '0'){
        $read_aditivo_contrato_aditivo = Read('contrato_chip_aditivo', "WHERE contrato_chip_aditivo_id_contrato = '".$id_aditivo_contrato."' AND contrato_chip_aditivo_id_d4sign IS NULL");
        if(NumQuery($read_aditivo_contrato_aditivo) > '0'){
            $html_instalacao .= '<table width="100%" border="0">';
            $html_instalacao .= '<tr bgcolor="#000000" style="font-size: 14px;">
                                <td style="color:#FFF">#</td>
                                <td style="color:#FFF">ICCID</td>
                                <td style="color:#FFF">MSISDN</td>
                                <td style="color:#FFF">PACOTE</td>
                            </tr>';
            $html_desinstalacao .= '<table width="100%" border="0">';
            $html_desinstalacao .= '<tr bgcolor="#000000" style="font-size: 14px;">
                                <td style="color:#FFF">#</td>
                                <td style="color:#FFF">ICCID</td>
                                <td style="color:#FFF">MSISDN</td>
                                <td style="color:#FFF">PACOTE</td>
                            </tr>';
            $count_instalacao = '0';
            $count_desinstalacao = '0';
            foreach($read_aditivo_contrato_aditivo as $read_aditivo_contrato_aditivo_view);
            foreach($read_contrato_chip as $read_contrato_chip_view){
                $read_chip = Read('chip', "WHERE chip_id = '".$read_contrato_chip_view['contrato_chip_aditivo_chip_id_chip']."'");
                if(NumQuery($read_chip) > '0'){
                    foreach($read_chip as $read_chip_view);
                    $id_plano = GetDados('pedido', $read_aditivo_contrato_aditivo_view['contrato_chip_aditivo_id_pedido'], 'pedido_id', 'pedido_id_plano');
                    $descricao_plano = GetDados('plano', $id_plano, 'plano_id', 'plano_descricao');
                }
                if($read_contrato_chip_view['contrato_chip_aditivo_chip_tipo'] == '0'){
                    $count_instalacao++;
                    $html_instalacao .= '<tr style="font-size: 10px;">';
                        $html_instalacao .= '<td>'.$count_instalacao.'</td>';
                        $html_instalacao .= '<td>'.$read_chip_view['chip_iccid'].'</td>';
                        $html_instalacao .= '<td>'.$read_chip_view['chip_num'].'</td>';
                        $html_instalacao .= '<td>'.$descricao_plano.'</td>';
                    $html_instalacao .= '</tr>';
                }elseif($read_contrato_chip_view['contrato_chip_aditivo_chip_tipo'] == '1'){
                    $count_desinstalacao++;
                    $html_desinstalacao .= '<tr style="font-size: 10px;">';
                        $html_desinstalacao .= '<td>'.$count_desinstalacao.'</td>';
                        $html_desinstalacao .= '<td>'.$read_chip_view['chip_iccid'].'</td>';
                        $html_desinstalacao .= '<td>'.$read_chip_view['chip_num'].'</td>';
                        $html_desinstalacao .= '<td>'.$descricao_plano.'</td>';
                    $html_desinstalacao .= '</tr>';
                }
            }
            $html_instalacao .= '</table>';
            $html_desinstalacao .= '</table>';
            $templates = array(
                ID_CONTRATO_CHIP_ADITIVO_D4SIGN => array(
                        'NUMERO_ADITIVO' => $read_aditivo_contrato_aditivo_view['contrato_chip_aditivo_id_aditivo'],
                        'ADITIVO_ATIVACAO' => $html_instalacao,
                        'ADITIVO_DESATIVACAO' => $html_desinstalacao,
                        'VALOR_PLANO' => FormatMoney(GetDados('pedido', $read_aditivo_contrato_aditivo_view['contrato_chip_aditivo_id_pedido'], 'pedido_id', 'pedido_valor_plano')),
                        'VALOR_PLANO_EXTENSO' => escreverValorMoeda(GetDados('pedido', $read_aditivo_contrato_aditivo_view['contrato_chip_aditivo_id_pedido'], 'pedido_id', 'pedido_valor_plano')),
                        'DATA_EXTENSO' => FormDataBr($read_aditivo_contrato_aditivo_view['contrato_chip_aditivo_data_criacao'])
                    )
                );							

            $name_document = "Aditivo Contrato Chip";
            $uuid_cofre = ID_COFRE_CONTRATO_CHIP_D4SIGN;
            $return = $client->documents->makedocumentbytemplate($uuid_cofre, $name_document, $templates);
            if($return->message == 'success'){
                $UPDATE_CONTRATO_ADITIVO_CHIP['contrato_chip_aditivo_id_d4sign'] = $return->uuid;
                Update('contrato_chip_aditivo', $UPDATE_CONTRATO_ADITIVO_CHIP, "WHERE contrato_chip_aditivo_id = '".$id_aditivo_contrato."'");
                $signers = array(
                    array("email" => $email, "act" => '1', "foreign" => '0', "certificadoicpbr" => '0', "assinatura_presencial" => '0', "embed_methodauth" => 'email', "embed_smsnumber" => '')
                );
                $return_sign = $client->documents->createList($UPDATE_CONTRATO_ADITIVO_CHIP['contrato_chip_aditivo_id_d4sign'], $signers);
                $message = $msg;
                $workflow = 0;
                $skip_email = 0;

                $doc = $client->documents->sendToSigner($UPDATE_CONTRATO_ADITIVO_CHIP['contrato_chip_aditivo_id_d4sign'], $message, $workflow, $skip_email);
                echo '<p>Operação realizada com sucesso!</p>';
            }
        }else{
            echo '<p>Contrato não pode ser gerado, o mesmo já foi gerado.</p>';
        }
    }else{
        echo '<p>Operação não pode ser realizada, não existe veículos nesse aditivo.</p>';
    }
}elseif($acao == 'insert_chip_desinstalacao_massa'){
    if(!isset($_SESSION['desinstalacao_chip'])){
        $_SESSION['desinstalacao_chip'] = array();
    }
    if(!isset($_SESSION['desinstalacao_chip_multa'])){
        $_SESSION['desinstalacao_chip_multa'] = array();
    }
	$devolucao_chip = addslashes($_POST['devolucao_chip']);
	$array_chip = addslashes($_POST['arquivo_txt']);
    $explode_chip_completo = explode( PHP_EOL, $array_chip );
    $count_explode = count($explode_chip_completo);
	$id_contato = addslashes($_POST['id_contato']);
	for($x=0;$x<$count_explode;$x++){
		$read_chip = ReadComposta("SELECT chip_id, chip_num, chip_iccid FROM chip WHERE chip_iccid = '".trim($explode_chip_completo[$x])."'");
        if(NumQuery($read_chip) > '0'){
            foreach($read_chip as $read_chip_view);
			$id_chip    	= addslashes($read_chip_view['chip_id']);
			
			$read_itens_pedido = Read('itens_pedido', "WHERE itens_pedido_id_chip = '".$id_chip."' ORDER BY itens_pedido_id DESC LIMIT 1");
			if(NumQuery($read_itens_pedido) > '0'){
				foreach($read_itens_pedido as $read_itens_pedido_view);
				$read_pedido = Read('pedido', "WHERE pedido_id = '".$read_itens_pedido_view['itens_pedido_id_pedido']."' AND pedido_id_cliente = '".$id_contato."'");
				if(NumQuery($read_pedido) > '0'){
					foreach($read_pedido as $read_pedido_view);
					$read_aditivo_contrato = Read('contrato_chip_aditivo', "WHERE contrato_chip_aditivo_id_pedido = '".$read_pedido_view['pedido_id']."'");
					if(NumQuery($read_aditivo_contrato) > '0'){
						foreach($read_aditivo_contrato as $read_aditivo_contrato_view);
						$valor_plano_mensal = ($read_pedido_view['pedido_valor_plano'] / 30);
						$quant_dia_fim = DiferencaDias(date('d/m/Y'), FormDataBr($read_aditivo_contrato_view['contrato_chip_aditivo_data_final']));
						$calcular_valor_multa = ((20 / 100) * $valor_plano_mensal) * $quant_dia_fim;
						if(isset($_SESSION['desinstalacao_chip'][$id_chip])){
							$_SESSION['desinstalacao_chip'][$id_chip] = $devolucao_chip;
						}else{
							$_SESSION['desinstalacao_chip'][$id_chip] = $devolucao_chip;
						}
						$calcular_valor_multa = '0';
						if(isset($_SESSION['desinstalacao_chip_multa'][$id_chip])){
							if($calcular_valor_multa <= '0'){
								$calcular_valor_multa = '0';
							}
							$_SESSION['desinstalacao_chip_multa'][$id_chip] = $calcular_valor_multa;
						}else{
							if($calcular_valor_multa <= '0'){
								$calcular_valor_multa = '0';
							}
							$_SESSION['desinstalacao_chip_multa'][$id_chip] = $calcular_valor_multa;
						}
						$json_contrato = array(
							'type' => 'success',
							'title' => 'Parabéns:',
							'msg' => 'Ops, operação não pode ser realizada, chip pertence a outro cliente!',
							'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
						);
					}
				}else{
					$json_contrato = array(
						'type' => 'error',
						'title' => 'Erro:',
						'msg' => 'Ops, operação não pode ser realizada, chip pertence a outro cliente!',
						'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
					);
				}
			}else{
				$json_contrato = array(
					'type' => 'error',
					'title' => 'Erro:',
					'msg' => 'Ops, operação não pode ser realizada, chip inválido!',
					'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
				);
			}
        }else{
            $md5 = md5(rand(9,9999999999999999999999999999));
            $_SESSION['pedido_session'][$md5] = $explode_chip_completo[0];
        }
	}
    echo json_encode($json_contrato);
}
?>