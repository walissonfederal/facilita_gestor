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
        $order_by = "ORDER BY contrato_monitoramento.contrato_monitoramento_id DESC";
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
            $sql_id = "AND contrato_monitoramento.contrato_monitoramento_id = '".$get_id."'";
        }else{
            $sql_id = "";
        }
        if($get_id_contato != ''){
            $sql_id_contato = "AND contrato_monitoramento.contrato_monitoramento_id_contato = '".$get_id_contato."'";
        }else{
            $sql_id_contato = "";
        }
        if($get_status != ''){
            $sql_status = "AND contrato_monitoramento.contrato_monitoramento_status = '".$get_status."'";
        }else{
            $sql_status = "";
        }
        
        $_SESSION['contrato_monitoramento_load'] = "".$sql_id." ".$sql_id_contato." ".$sql_status." ";
    }
    
    $read_contrato_monitoramento_paginator = ReadComposta("SELECT contrato_monitoramento_id FROM contrato_monitoramento WHERE contrato_monitoramento_id != '' {$_SESSION['contrato_monitoramento_load']}");
    $read_contrato_monitoramento = ReadComposta("SELECT contrato_monitoramento_id, contato_nome_razao, contrato_monitoramento_data_inicial, contrato_monitoramento_data_final, contrato_monitoramento_status, contrato_monitoramento_cliente_assinou FROM contrato_monitoramento LEFT JOIN contato ON contato.contato_id = contrato_monitoramento.contrato_monitoramento_id_contato WHERE contrato_monitoramento.contrato_monitoramento_id != '' {$_SESSION['contrato_monitoramento_load']} ".$order_by." LIMIT $inicio,$maximo");
    if(NumQuery($read_contrato_monitoramento) > '0'){
        //PEGA QUANTOS BOTÕES PARA PAGINAÇÃO DE RESULTADOS
        $paginas = ceil(NumQuery($read_contrato_monitoramento_paginator) / $maximo);
        //TABULATOR EXIGE ESSA INFORMAÇÃO PARA QUE PRECISE DA PAGINAÇÃO DE RESULTADOS 
        $json_contrato["last_page"] = $paginas;
        foreach($read_contrato_monitoramento as $read_contrato_monitoramento_view){
            if($read_contrato_monitoramento_view['contrato_monitoramento_status'] == '0'){
                $read_contrato_monitoramento_view['contrato_monitoramento_status'] = 'EM VIGOR';
            }elseif($read_contrato_monitoramento_view['contrato_monitoramento_status'] == '1'){
                $read_contrato_monitoramento_view['contrato_monitoramento_status'] = 'PROCESSANDO';
            }elseif($read_contrato_monitoramento_view['contrato_monitoramento_status'] == '2'){
                $read_contrato_monitoramento_view['contrato_monitoramento_status'] = 'AGUARDANDO SIGNATÁRIOS';
            }elseif($read_contrato_monitoramento_view['contrato_monitoramento_status'] == '3'){
                $read_contrato_monitoramento_view['contrato_monitoramento_status'] = 'AGUARDANDO ASSINATURAS';
            }elseif($read_contrato_monitoramento_view['contrato_monitoramento_status'] == '5'){
                $read_contrato_monitoramento_view['contrato_monitoramento_status'] = 'ARQUIVADO';
            }elseif($read_contrato_monitoramento_view['contrato_monitoramento_status'] == '6'){
                $read_contrato_monitoramento_view['contrato_monitoramento_status'] = 'CANCELADO';
            }
            if($read_contrato_monitoramento_view['contrato_monitoramento_cliente_assinou'] == '0'){
                $read_contrato_monitoramento_view['contrato_monitoramento_cliente_assinou'] = 'NÃO';
            }else{
                $read_contrato_monitoramento_view['contrato_monitoramento_cliente_assinou'] = 'SIM';
            }
            $json_contrato['data'][] = $read_contrato_monitoramento_view;
        }
    }else{
        $json_contrato['data'] = null;
    }
    echo json_encode($json_contrato);
}elseif($acao == 'create'){
    //RECUPERA O FORMULARIO
    $contrato_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($contrato_form['acao']);
    
    $read_contrato_monitoramento = ReadComposta("SELECT contrato_monitoramento_id_contato FROM contrato_monitoramento WHERE contrato_monitoramento_id_contato = '".$contrato_form['contrato_monitoramento_id_contato']."' AND contrato_monitoramento_status = '0'");
    if(NumQuery($read_contrato_monitoramento) > '0'){
        $json_contrato = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada, cliente já possui contrato vigente!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }elseif($contrato_form['contrato_monitoramento_id_contato'] == ''){
        $json_contrato = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada, sem contato selecionado!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }elseif($contrato_form['contrato_monitoramento_possui_plano'] == '1' && $contrato_form['contrato_monitoramento_valor_plano'] == ''){
        $json_contrato = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada, como o cliente possui plano é preciso escolher o valor!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }elseif($contrato_form['contrato_monitoramento_valor_mensalidade'] == ''){
        $json_contrato = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada, é preciso escolher um valor para a mensalidade!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }elseif($contrato_form['contrato_monitoramento_data_inicial'] == '' || $contrato_form['contrato_monitoramento_data_final'] == ''){
        $json_contrato = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada, é preciso digitar uma data inicial e final para o contrato!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        $contrato_form['contrato_monitoramento_data'] = date('Y-m-d');
        Create('contrato_monitoramento', $contrato_form);
        $ultimo_id_contrato_monitoramento = GetReg('contrato_monitoramento', 'contrato_monitoramento_id', "");
        $json_contrato = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Parabéns, operação realizada com sucesso!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal" onclick="carrega_pagina(\'contrato\', \'index_monitoramento.php\');">Apenas Fechar</button><button type="button" class="btn btn-primary" data-dismiss="modal" onclick="carrega_pagina(\'contrato\', \'create_itens_monitoramento.php?id_contrato='.$ultimo_id_contrato_monitoramento.'\');">Lançar Itens</button>'
        );
    }
    echo json_encode($json_contrato);
}elseif($acao == 'load_update'){
    $uid = addslashes($_POST['id']);
    
    $read_contrato_monitoramento = Read('contrato_monitoramento', "WHERE contrato_monitoramento_id = '".$uid."'");
    if(NumQuery($read_contrato_monitoramento) > '0'){
        foreach($read_contrato_monitoramento as $read_contrato_monitoramento_view);
        if($read_contrato_monitoramento_view['contrato_monitoramento_cliente_assinou'] == '1'){
            $json_contrato = array(
                'type' => 'error',
                'title' => 'Erro:',
                'msg' => 'Ops, operação não pode ser finalizada, contrato não pode ser editado. Cliente já assinou!',
                'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal" onclick="carrega_pagina(\'contrato\', \'index_monitoramento.php\');">Apenas Fechar</button>'
            );
        }else{
            $json_contrato[] = $read_contrato_monitoramento_view;
        }
    }else{
        $json_contrato = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada, contrato não encontrado!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal" onclick="carrega_pagina(\'contrato\', \'index_monitoramento.php\');">Apenas Fechar</button>'
        );
    }
    echo json_encode($json_contrato);
}elseif($acao == 'update'){
    //RECUPERA O FORMULARIO
    $contrato_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($contrato_form['acao']);
    
    if($contrato_form['contrato_monitoramento_id_contato'] == ''){
        $json_contrato = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada, sem contato selecionado!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }elseif($contrato_form['contrato_monitoramento_possui_plano'] == '1' && $contrato_form['contrato_monitoramento_valor_plano'] == ''){
        $json_contrato = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada, como o cliente possui plano é preciso escolher o valor!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }elseif($contrato_form['contrato_monitoramento_valor_mensalidade'] == ''){
        $json_contrato = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada, é preciso escolher um valor para a mensalidade!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }elseif($contrato_form['contrato_monitoramento_data_inicial'] == '' || $contrato_form['contrato_monitoramento_data_final'] == ''){
        $json_contrato = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada, é preciso digitar uma data inicial e final para o contrato!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        $uid = $contrato_form['id'];
        unset($contrato_form['id']);
        Update('contrato_monitoramento', $contrato_form, "WHERE contrato_monitoramento_id = '".$uid."'");
        $json_contrato = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Parabéns, operação realizada com sucesso!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal" onclick="carrega_pagina(\'contrato\', \'index_monitoramento.php\');">Apenas Fechar</button><button type="button" class="btn btn-primary" data-dismiss="modal" onclick="carrega_pagina(\'contrato\', \'create_itens_monitoramento.php?id='.$uid.'\');">Lançar Itens</button>'
        );
    }
    echo json_encode($json_contrato);
}elseif($acao == 'load_itens'){
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
        $order_by = "ORDER BY contrato_monitoramento_itens_id DESC";
    }else{
        
        $sort       = addslashes($_GET['sort']);
        $sort_dir   = addslashes($_GET['sort_dir']);
        $order_by   = "ORDER BY ".$sort." ".$sort_dir."";
    }
    $id_contrato = addslashes($_GET['id_contrato']);
    
    $read_contrato_monitoramento_paginator = ReadComposta("SELECT contrato_monitoramento_itens_id FROM contrato_monitoramento_itens WHERE contrato_monitoramento_itens_id_contrato = '".$id_contrato."'");
    $read_contrato_monitoramento = ReadComposta("SELECT * FROM contrato_monitoramento_itens WHERE contrato_monitoramento_itens_id_contrato = '".$id_contrato."' ".$order_by." LIMIT $inicio,$maximo");
    if(NumQuery($read_contrato_monitoramento) > '0'){
        //PEGA QUANTOS BOTÕES PARA PAGINAÇÃO DE RESULTADOS
        $paginas = ceil(NumQuery($read_contrato_monitoramento_paginator) / $maximo);
        //TABULATOR EXIGE ESSA INFORMAÇÃO PARA QUE PRECISE DA PAGINAÇÃO DE RESULTADOS 
        $json_contrato["last_page"] = $paginas;
        foreach($read_contrato_monitoramento as $read_contrato_monitoramento_view){
            $json_contrato['data'][] = $read_contrato_monitoramento_view;
        }
    }else{
        $json_contrato['data'] = null;
    }
    echo json_encode($json_contrato);
}elseif($acao == 'create_itens'){
    //RECUPERA O FORMULARIO
    $contrato_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($contrato_form['acao']);
    
    if(in_array('', $contrato_form)){
        $json_contrato = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada, campos em branco!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        Create('contrato_monitoramento_itens', $contrato_form);
        $json_contrato = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Parabéns, operação realizada com sucesso!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal" onclick="carrega_pagina(\'contrato\', \'itens_monitoramento.php?id='.$contrato_form['contrato_monitoramento_itens_id_contrato'].'\');">Apenas Fechar</button><button type="button" class="btn btn-primary" data-dismiss="modal" onclick="carrega_pagina(\'contrato\', \'create_itens_monitoramento.php?id='.$contrato_form['contrato_monitoramento_itens_id_contrato'].'\');">Lançar Itens</button>'
        );
    }
    echo json_encode($json_contrato);
}elseif($acao == 'load_update_itens'){
    $uid = addslashes($_POST['id']);
    
    $read_itens_monitoramento = Read('contrato_monitoramento_itens', "WHERE contrato_monitoramento_itens_id = '".$uid."'");
    if(NumQuery($read_itens_monitoramento) > '0'){
        foreach($read_itens_monitoramento as $read_itens_monitoramento_view);
        $json_contrato[] = $read_itens_monitoramento_view;
    }else{
        $json_contrato = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada, item não encontrado!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }
    echo json_encode($json_contrato);
}elseif($acao == 'update_itens'){
    //RECUPERA O FORMULARIO
    $contrato_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($contrato_form['acao']);
    
    if(in_array('', $contrato_form)){
        $json_contrato = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada, campos em branco!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        $uid = $contrato_form['id'];
        $id_contrato = $contrato_form['id_contrato'];
        unset($contrato_form['id_contrato']);
        unset($contrato_form['id']);
        Update('contrato_monitoramento_itens', $contrato_form, "WHERE contrato_monitoramento_itens_id = '".$uid."'");
        $json_contrato = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Parabéns, operação realizada com sucesso!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal" onclick="carrega_pagina(\'contrato\', \'itens_monitoramento.php?id='.$id_contrato.'\');">Apenas Fechar</button>'
        );
    }
    echo json_encode($json_contrato);
}elseif($acao == 'enviar_contrato_monitoramento'){
    $email = addslashes($_POST['email']);
    $msg   = addslashes($_POST['msg']);
    $id_contrato = addslashes($_POST['id_contrato']);
    
    $read_itens_monitoramento = ReadComposta("SELECT contrato_monitoramento_itens_id_contrato FROM contrato_monitoramento_itens WHERE contrato_monitoramento_itens_id_contrato = '".$id_contrato."'");
    if(NumQuery($read_itens_monitoramento) == '0'){
        echo '<p>Ops, operação não pode ser finalizada, não possui itens a esse contrato!</p>';
    }else{
        $read_contrato_monitoramento = ReadComposta("SELECT * FROM contrato_monitoramento INNER JOIN contato ON contato.contato_id = contrato_monitoramento.contrato_monitoramento_id_contato WHERE contrato_monitoramento.contrato_monitoramento_id = '".$id_contrato."' AND contrato_monitoramento.contrato_monitoramento_status = '0' AND contrato_monitoramento_id_d4sign IS NULL");
        if(NumQuery($read_contrato_monitoramento) > '0'){
            foreach($read_contrato_monitoramento as $read_contrato_monitoramento_view);
            if($read_contrato_monitoramento_view['contrato_monitoramento_possui_plano'] == '1'){
                $texto_possui_plano = '';
                $texto_primeira_plano = PRIMEIRA_FRASE_PLANO_COMPENSACAO;
                $texto_criterios = CRITERIO_PARTICIPAR_PLANO_COMPENSACAO;
                $texto_planos = valor_plano_compensacao($read_contrato_monitoramento_view['contrato_monitoramento_valor_plano']);
            }else{
                $texto_possui_plano = CASO_NAO_TENHA_PLANO;
                $texto_primeira_plano = '';
                $texto_criterios = '';
                $texto_planos = '';
            }
            if($read_contrato_monitoramento_view['contrato_monitoramento_possui_ronda'] == '1'){
                $texto_possui_ronda = CASO_TENHA_RONDA;
            }else{
                $texto_possui_ronda = CASO_NAO_TENHA_RONDA;
            }
            $html_clausula_nona = '';
            $html_clausula_nona .= '<h4>9. RELAÇÃO DE EQUIPAMENTOS LOCADOS</h4>';
            $html_clausula_nona .= '<table width="100%" border="0">';
            $html_clausula_nona .= '<tr bgcolor="#000000" style="font-size: 14px;">
                                <td style="color:#FFF">#</td>
                                <td style="color:#FFF">DATA</td>
                                <td style="color:#FFF">DESCRICAO</td>
                                <td style="color:#FFF">QUANTIDADE</td>
                                <td style="color:#FFF">VALOR UNITÁRIO</td>
                                <td style="color:#FFF">VALOR TOTAL</td>
                            </tr>';
            $count_nona = '0';
            foreach($read_itens_monitoramento as $read_itens_monitoramento_view){
                $count_nona++;
                $html_clausula_nona .= '<tr style="font-size: 10px;">';
                    $html_clausula_nona .= '<td>'.$count_nona.'</td>';
                    $html_clausula_nona .= '<td>'.FormDataBr($read_itens_monitoramento_view['contrato_monitoramento_itens_data']).'</td>';
                    $html_clausula_nona .= '<td>'.$read_itens_monitoramento_view['contrato_monitoramento_itens_descricao'].'</td>';
                    $html_clausula_nona .= '<td>'.$read_itens_monitoramento_view['contrato_monitoramento_itens_quantidade'].'</td>';
                    $html_clausula_nona .= '<td>'.FormatMoney($read_itens_monitoramento_view['contrato_monitoramento_itens_valor_unitario']).'</td>';
                    $html_clausula_nona .= '<td>'.FormatMoney($read_itens_monitoramento_view['contrato_monitoramento_itens_valor_total']).'</td>';
                $html_clausula_nona .= '</tr>';
            }
            $html_clausula_nona .= '</table>';
            $templates = array(
                ID_CONTRATO_MONITORAMENTO_D4SIGN => array(
                    'CLIENTE_NOME_RAZAO' => $read_contrato_monitoramento_view['contato_nome_razao'],
                    'CLIENTE_NOME_FANTASIA' => $read_contrato_monitoramento_view['contato_nome_fantasia'],
                    'CLIENTE_CPF_CNPJ' => $read_contrato_monitoramento_view['contato_cpf_cnpj'],
                    'CLIENTE_TELEFONE' => $read_contrato_monitoramento_view['contato_telefone'],
                    'CLIENTE_CELULAR' => $read_contrato_monitoramento_view['contato_celular'],
                    'CLIENTE_ENDERECO' => $read_contrato_monitoramento_view['contato_endereco'],
                    'CLIENTE_NUMERO' => $read_contrato_monitoramento_view['contato_numero'],
                    'CLIENTE_BAIRRO' => $read_contrato_monitoramento_view['contato_bairro'],
                    'CLIENTE_CIDADE' => $read_contrato_monitoramento_view['contato_cidade'],
                    'CLIENTE_UF' => $read_contrato_monitoramento_view['contato_estado'],
                    'CLIENTE_CEP' => $read_contrato_monitoramento_view['contato_cep'],
                    'CLIENTE_EMAIL' => $read_contrato_monitoramento_view['contato_email'],
                    'CASO_TENHA_PLANO_COMPENSACAO' => $texto_possui_plano,
                    'PRIMEIRA_FRASE_PLANO_COMPENSACAO' => $texto_primeira_plano,
                    'CRITERIOS_PARTICIPAR_PLANO_COMPENSACAO' => $texto_criterios,
                    'VALORES_PLANO_COMPENSACAO' => $texto_planos,
                    'RONDA' => $texto_possui_ronda,
                    'VALOR_MENSALIDADE' => FormatMoney($read_contrato_monitoramento_view['contrato_monitoramento_valor_mensalidade']),
                    'VALOR_MENSALIDADE_EXTENSO' => escreverValorMoeda($read_contrato_monitoramento_view['contrato_monitoramento_valor_mensalidade']),
                    'VALOR_INSTALACAO' => FormatMoney($read_contrato_monitoramento_view['contrato_monitoramento_valor_instalacao']),
                    'VALOR_INSTALACAO_EXTENSO' => escreverValorMoeda($read_contrato_monitoramento_view['contrato_monitoramento_valor_instalacao']),
                    'DURACAO_CONTRATO' => $read_contrato_monitoramento_view['contrato_monitoramento_duracao'],
                    'DURACAO_CONTRATO_EXTENSO' => convert_number_to_words($read_contrato_monitoramento_view['contrato_monitoramento_duracao']),
                    'VALOR_TOTAL_CONTRATO' => FormatMoney($read_contrato_monitoramento_view['contrato_monitoramento_valor_total_contrato']),
                    'VALOR_TOTAL_CONTRATO_EXTENSO' => escreverValorMoeda($read_contrato_monitoramento_view['contrato_monitoramento_valor_total_contrato']),
                    'CLAUSULA_NONA' => $html_clausula_nona,
                    'ADITIVO_DATA' => FormDataBr($read_contrato_monitoramento_view['contrato_monitoramento_data'])
                    )
                );
            $name_document = "Contrato Monitoramento";
            $uuid_cofre = ID_COFRE_CONTRATO_MONITORAMENTO_D4SIGN;

            $return = $client->documents->makedocumentbytemplate($uuid_cofre, $name_document, $templates);
            if($return->message == 'success'){
                $UPDATE_CONTRATO_MONITORAMENTO['contrato_monitoramento_id_d4sign'] = $return->uuid;
                Update('contrato_monitoramento', $UPDATE_CONTRATO_MONITORAMENTO, "WHERE contrato_monitoramento_id = '".$id_contrato."'");
                $signers = array(
                    array("email" => $email, "act" => '1', "foreign" => '0', "certificadoicpbr" => '0', "assinatura_presencial" => '0', "embed_methodauth" => 'email', "embed_smsnumber" => '')
                );
                $return_sign = $client->documents->createList($UPDATE_CONTRATO_MONITORAMENTO['contrato_monitoramento_id_d4sign'], $signers);
                $message = $msg;
                $workflow = 0;
                $skip_email = 0;

                $doc = $client->documents->sendToSigner($UPDATE_CONTRATO_MONITORAMENTO['contrato_monitoramento_id_d4sign'], $message, $workflow, $skip_email);
                echo '<p>Operação realizada com sucesso!</p>';
            }
        }else{
            echo '<p>Ops, operação não pode ser finalizada, contrato já gerado!</p>';
        }
    }
}