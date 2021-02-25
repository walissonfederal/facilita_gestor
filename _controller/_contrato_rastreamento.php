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
        $order_by = "ORDER BY contrato_rastreamento.contrato_rastreamento_id DESC";
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
            $sql_id = "AND contrato_rastreamento.contrato_rastreamento_id = '".$get_id."'";
        }else{
            $sql_id = "";
        }
        if($get_id_contato != ''){
            $sql_id_contato = "AND contrato_rastreamento.contrato_rastreamento_id_contato = '".$get_id_contato."'";
        }else{
            $sql_id_contato = "";
        }
        if($get_status != ''){
            $sql_status = "AND contrato_rastreamento.contrato_rastreamento_status = '".$get_status."'";
        }else{
            $sql_status = "";
        }
        
        $_SESSION['contrato_rastreamento_load'] = "".$sql_id." ".$sql_descricao." ".$sql_status." ".$sql_id_contato." ";
    }
    
    $read_contrato_rastreamento_paginator = ReadComposta("SELECT contrato_rastreamento_id FROM contrato_rastreamento WHERE contrato_rastreamento_id != '' {$_SESSION['contrato_rastreamento_load']}");
    $read_contrato_rastreamento = ReadComposta("SELECT contrato_rastreamento_id, contato_nome_razao, contrato_rastreamento_data_inicial, contrato_rastreamento_data_final, contrato_rastreamento_status, contrato_rastreamento_cliente_assinou  FROM contrato_rastreamento LEFT JOIN contato ON contato.contato_id = contrato_rastreamento.contrato_rastreamento_id_contato WHERE contrato_rastreamento.contrato_rastreamento_id != '' {$_SESSION['contrato_rastreamento_load']} ".$order_by." LIMIT $inicio,$maximo");
    if(NumQuery($read_contrato_rastreamento) > '0'){
        //PEGA QUANTOS BOTÕES PARA PAGINAÇÃO DE RESULTADOS
        $paginas = ceil(NumQuery($read_contrato_rastreamento_paginator) / $maximo);
        //TABULATOR EXIGE ESSA INFORMAÇÃO PARA QUE PRECISE DA PAGINAÇÃO DE RESULTADOS 
        $json_contrato["last_page"] = $paginas;
        foreach($read_contrato_rastreamento as $read_contrato_rastreamento_view){
            if($read_contrato_rastreamento_view['contrato_rastreamento_status'] == '0'){
                $read_contrato_rastreamento_view['contrato_rastreamento_status'] = 'EM VIGOR';
            }elseif($read_contrato_rastreamento_view['contrato_rastreamento_status'] == '1'){
                $read_contrato_rastreamento_view['contrato_rastreamento_status'] = 'PROCESSANDO';
            }elseif($read_contrato_rastreamento_view['contrato_rastreamento_status'] == '2'){
                $read_contrato_rastreamento_view['contrato_rastreamento_status'] = 'AGUARDANDO SIGNATÁRIOS';
            }elseif($read_contrato_rastreamento_view['contrato_rastreamento_status'] == '3'){
                $read_contrato_rastreamento_view['contrato_rastreamento_status'] = 'AGUARDANDO ASSINATURAS';
            }elseif($read_contrato_rastreamento_view['contrato_rastreamento_status'] == '5'){
                $read_contrato_rastreamento_view['contrato_rastreamento_status'] = 'ARQUIVADO';
            }elseif($read_contrato_rastreamento_view['contrato_rastreamento_status'] == '6'){
                $read_contrato_rastreamento_view['contrato_rastreamento_status'] = 'CANCELADO';
            }
            if($read_contrato_rastreamento_view['contrato_rastreamento_cliente_assinou'] == '0'){
                $read_contrato_rastreamento_view['contrato_rastreamento_cliente_assinou'] = 'NÃO';
            }else{
                $read_contrato_rastreamento_view['contrato_rastreamento_cliente_assinou'] = 'SIM';
            }
            $json_contrato['data'][] = $read_contrato_rastreamento_view;
        }
    }else{
        $json_contrato['data'] = null;
    }
    echo json_encode($json_contrato);
}elseif($acao == 'create'){
    //RECUPERA O FORMULARIO
    $contrato_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($contrato_form['acao']);
    
    $read_contrato_rastreamento = ReadComposta("SELECT contrato_rastreamento_id_contato FROM contrato_rastreamento WHERE contrato_rastreamento_id_contato = '".$contrato_form['contrato_rastreamento_id_contato']."' AND contrato_rastreamento_status NOT IN(5,6)");
    
    if($contrato_form['contrato_rastreamento_id_contato'] == '' || $contrato_form['contrato_rastreamento_data_inicial'] == '' || $contrato_form['contrato_rastreamento_data_final'] == '' || $contrato_form['contrato_rastreamento_status'] == '' || $contrato_form['contrato_rastreamento_cliente_assinou'] == ''){
        $json_contrato = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido a existir campos sem serem preenchidos!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }elseif(NumQuery($read_contrato_rastreamento) > '0'){
        $json_contrato = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada, cliente já possui contrato vigente!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        if($contrato_form['verificacao_contrato'] == '0' && ($contrato_form['contrato_rastreamento_valor_adesao'] == '' || $contrato_form['contrato_rastreamento_valor_desinstalacao'] == '' || $contrato_form['contrato_rastreamento_valor_instalacao'] == '' || $contrato_form['contrato_rastreamento_valor_manutencao'] == '' || $contrato_form['contrato_rastreamento_valor_mensalidade'] == '' || $contrato_form['contrato_rastreamento_valor_equipamento'] == '' || $contrato_form['contrato_rastreamento_valor_km'] == '' || $contrato_form['contrato_rastreamento_valor_instalacao_bloqueador'] == '' || $contrato_form['contrato_rastreamento_valor_mensalidade_bloqueador'] == '' || $contrato_form['contrato_rastreamento_valor_instalacao_sensor'] == '' || $contrato_form['contrato_rastreamento_valor_mensalidade_sensor'] == '')){
            $json_contrato = array(
                'type' => 'error',
                'title' => 'Erro:',
                'msg' => '<div class="row">
                            <div class="form-group col-lg-12">
                                <label>Senha Gerencial</label>
                                <input type="password" class="form-control senha_gerencial_contrato_rastreamento"/>
                            </div>
                        </div>',
                'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button><button type="button" class="btn btn-primary" onclick="verifica_contrato_rastreamento();">Realizar Operação</button>'
            );
        }else{
            $senha_gerencial = addslashes($contrato_form['senha_gerencial_new']);
            unset($contrato_form['verificacao_contrato']);
            unset($contrato_form['senha_gerencial_new']);
            if('123' == GetEmpresa('empresa_contrato_rastreamento_senha')){
                Create('contrato_rastreamento', $contrato_form);
                $json_contrato = array(
                    'type' => 'success',
                    'title' => 'Parabéns:',
                    'msg' => 'Parabéns, operação realizada com sucesso!',
                    'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal" onclick="carrega_pagina(\'contrato\', \'index_rastreamento.php\');">Apenas Fechar</button>'
                );
            }else{
                $json_contrato = array(
                    'type' => 'error_2',
                    'title' => 'Erro:',
                    'msg' => 'Ops, operação não pode ser finalizada, senha gerencial não confere!',
                    'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
                );
            }
        }
    }
    echo json_encode($json_contrato);
}elseif($acao == 'gerar_documento'){
    $id_contrato = addslashes($_POST['id']);
    
    $read_contrato_rastreamento = ReadComposta("SELECT * FROM contrato_rastreamento INNER JOIN contato ON contato.contato_id = contrato_rastreamento.contrato_rastreamento_id_contato WHERE contrato_rastreamento.contrato_rastreamento_id = '".$id_contrato."' AND contrato_rastreamento.contrato_rastreamento_status = '0'");
    if(NumQuery($read_contrato_rastreamento) > '0'){
        foreach($read_contrato_rastreamento as $read_contrato_rastreamento_view);
        if($read_contrato_rastreamento_view['contrato_rastreamento_id_d4sign'] == ''){
            $templates = array(
                ID_CONTRATO_RASTREAMENTO_D4SIGN => array(
                    'CLIENTE_NOME_RAZAO' => $read_contrato_rastreamento_view['contato_nome_razao'],
                    'CLIENTE_NOME_FANTASIA' => $read_contrato_rastreamento_view['contato_nome_fantasia'],
                    'CLIENTE_CPF_CNPJ' => $read_contrato_rastreamento_view['contato_cpf_cnpj'],
                    'CLIENTE_TELEFONE' => $read_contrato_rastreamento_view['contato_telefone'],
                    'CLIENTE_CELULAR' => $read_contrato_rastreamento_view['contato_celular'],
                    'CLIENTE_ENDERECO' => $read_contrato_rastreamento_view['contato_endereco'],
                    'CLIENTE_NUMERO' => $read_contrato_rastreamento_view['contato_numero'],
                    'CLIENTE_BAIRRO' => $read_contrato_rastreamento_view['contato_bairro'],
                    'CLIENTE_CIDADE' => $read_contrato_rastreamento_view['contato_cidade'],
                    'CLIENTE_UF' => $read_contrato_rastreamento_view['contato_estado'],
                    'CLIENTE_CEP' => $read_contrato_rastreamento_view['contato_cep'],
                    'CLIENTE_EMAIL' => $read_contrato_rastreamento_view['contato_email'],
                    'VALOR_ADESAO' => $read_contrato_rastreamento_view['contrato_rastreamento_valor_adesao'],
                    'VALOR_INSTALACAO' => $read_contrato_rastreamento_view['contrato_rastreamento_valor_instalacao'],
                    'VALOR_DESINSTALACAO' => $read_contrato_rastreamento_view['contrato_rastreamento_valor_desinstalacao'],
                    'VALOR_MANUTENCAO' => $read_contrato_rastreamento_view['contrato_rastreamento_valor_manutencao'],
                    'VALOR_MENSALIDADE' => $read_contrato_rastreamento_view['contrato_rastreamento_valor_mensalidade'],
                    'VALOR_EQUIPAMENTO' => $read_contrato_rastreamento_view['contrato_rastreamento_valor_equipamento'],
                    'VALOR_KM' => $read_contrato_rastreamento_view['contrato_rastreamento_valor_km'],
                    'VALOR_INSTALACAO_BLOQUEADOR' => $read_contrato_rastreamento_view['contrato_rastreamento_valor_instalacao_bloqueador'],
                    'VALOR_MENSALIDADE_BLOQUEADOR' => $read_contrato_rastreamento_view['contrato_rastreamento_valor_mensalidade_bloqueador'],
                    'VALOR_INSTALACAO_SENSOR' => $read_contrato_rastreamento_view['contrato_rastreamento_valor_instalacao_sensor'],
                    'VALOR_MENSALIDADE_SENSOR' => $read_contrato_rastreamento_view['contrato_rastreamento_valor_mensalidade_sensor']
                )
            );
            $name_document = "Contrato Rastreamento";
            $uuid_cofre = ID_COFRE_CONTRATO_RASTREAMENTO_D4SIGN;
            $return = $client->documents->makedocumentbytemplate($uuid_cofre, $name_document, $templates);
            if($return->message == 'success'){
                $UPDATE_CONTRATO_CHIP['contrato_rastreamento_id_d4sign'] = $return->uuid;
                Update('contrato_rastreamento', $UPDATE_CONTRATO_CHIP, "WHERE contrato_rastreamento_id = '".$id_contrato."'");
                $signers = array(
                    array("email" => $read_contrato_rastreamento_view['contato_email'], "act" => '1', "foreign" => '0', "certificadoicpbr" => '0', "assinatura_presencial" => '0', "embed_methodauth" => 'email', "embed_smsnumber" => '')
                );
                $return_sign = $client->documents->createList($UPDATE_CONTRATO_CHIP['contrato_rastreamento_id_d4sign'], $signers);
                $message = 'Prezado(a), segue o contrato eletrônico para assinatura.';
                $workflow = 0;
                $skip_email = 0;

                $doc = $client->documents->sendToSigner($UPDATE_CONTRATO_CHIP['contrato_rastreamento_id_d4sign'], $message, $workflow, $skip_email);
                $json_contrato = array(
                    'type' => 'success',
                    'title' => 'Parabéns:',
                    'msg' => 'Operação realizada com sucesso',
                    'buttons' => '<a href="javascript::" onclick="carrega_pagina(\'contrato\', \'index_rastreamento.php\');" data-dismiss="modal" class="btn btn-primary">Sair</a>'
                );
            }
        }else{
            $json_contrato = array(
                'type' => 'error',
                'title' => 'Erro:',
                'msg' => 'Ops, operação não pode ser finalizada, contrato já gerado!',
                'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
            );
        }
    }else{
        $json_contrato = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada, contrato inválido!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }
    echo json_encode($json_contrato);
}elseif($acao == 'load_update'){
    $uid = addslashes($_POST['id']);
    
    $read_contrato = Read('contrato_rastreamento', "WHERE contrato_rastreamento_id = '".$uid."'");
    if(NumQuery($read_contrato) > '0'){
        foreach($read_contrato as $read_contrato_view);
        $json_contrato[] = $read_contrato_view;
    }else{
        $json_contrato = null;
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
        if(Update('contrato_rastreamento', $contrato_form, "WHERE contrato_rastreamento_id = '".$uid."'")){
            $json_contrato = array(
                'type' => 'success',
                'title' => 'Parabéns:',
                'msg' => 'Operação realizada com sucesso',
                'buttons' => '<a href="javascript::" data-dismiss="modal" onclick="carrega_pagina(\'contrato\', \'index_rastreamento.php\');" class="btn btn-primary">Sair</a>'
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
        $order_by = "ORDER BY contrato_rastreamento_aditivo_id_aditivo DESC";
    }else{
        $sort       = addslashes($_GET['sort']);
        $sort_dir   = addslashes($_GET['sort_dir']);
        $order_by   = "ORDER BY ".$sort." ".$sort_dir."";
    }
    
    $id_contrato = addslashes($_GET['id_contrato']);
    
    $read_contrato_rastreamento_paginator = ReadComposta("SELECT contrato_rastreamento_aditivo_id FROM contrato_rastreamento_aditivo WHERE contrato_rastreamento_aditivo_id_contrato = '".$id_contrato."'");
    $read_contrato_rastreamento = ReadComposta("SELECT * FROM contrato_rastreamento_aditivo WHERE contrato_rastreamento_aditivo_id_contrato = '".$id_contrato."' ".$order_by." LIMIT $inicio,$maximo");
    if(NumQuery($read_contrato_rastreamento) > '0'){
        //PEGA QUANTOS BOTÕES PARA PAGINAÇÃO DE RESULTADOS
        $paginas = ceil(NumQuery($read_contrato_rastreamento_paginator) / $maximo);
        //TABULATOR EXIGE ESSA INFORMAÇÃO PARA QUE PRECISE DA PAGINAÇÃO DE RESULTADOS 
        $json_contrato["last_page"] = $paginas;
        foreach($read_contrato_rastreamento as $read_contrato_rastreamento_view){
            if($read_contrato_rastreamento_view['contrato_rastreamento_aditivo_status'] == '0'){
                $read_contrato_rastreamento_view['contrato_rastreamento_aditivo_status'] = 'EM VIGOR';
            }else{
                $read_contrato_rastreamento_view['contrato_rastreamento_aditivo_status'] = 'CANCELADO';
            }
            if($read_contrato_rastreamento_view['contrato_rastreamento_aditivo_cliente_assinou'] == '0'){
                $read_contrato_rastreamento_view['contrato_rastreamento_aditivo_cliente_assinou'] = 'NÃO';
            }else{
                $read_contrato_rastreamento_view['contrato_rastreamento_aditivo_cliente_assinou'] = 'SIM';
            }
            $json_contrato['data'][] = $read_contrato_rastreamento_view;
        }
    }else{
        $json_contrato['data'] = null;
    }
    echo json_encode($json_contrato);
}elseif($acao == 'create_aditivo'){
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
        $read_ultimo_aditivo_contrato_rastreamento = ReadComposta("SELECT contrato_rastreamento_aditivo_id_aditivo FROM contrato_rastreamento_aditivo WHERE contrato_rastreamento_aditivo_id_contrato = '".$contrato_form['contrato_rastreamento_aditivo_id_contrato']."' ORDER BY contrato_rastreamento_aditivo_id_aditivo DESC LIMIT 1");
        if(NumQuery($read_ultimo_aditivo_contrato_rastreamento) > '0'){
            foreach($read_ultimo_aditivo_contrato_rastreamento as $read_ultimo_aditivo_contrato_rastreamento_view);
            $contrato_form['contrato_rastreamento_aditivo_id_aditivo'] = $read_ultimo_aditivo_contrato_rastreamento_view['contrato_rastreamento_aditivo_id_aditivo'] + 1;
        }else{
            $contrato_form['contrato_rastreamento_aditivo_id_aditivo'] = '1';
        }
        $contrato_form['contrato_rastreamento_aditivo_data'] = date('Y-m-d');
        Create('contrato_rastreamento_aditivo', $contrato_form);
        $id_aditivo_criado = GetReg('contrato_rastreamento_aditivo', 'contrato_rastreamento_aditivo_id', "");
        $json_contrato = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Parabéns, operação realizada com sucesso!',
            'buttons' => '<a href="javascript::" data-dismiss="modal" onclick="carrega_pagina(\'contrato\', \'aditivo_rastreamento.php?id='.$contrato_form['contrato_rastreamento_aditivo_id_contrato'].'\');" class="btn btn-default">Sair</a><a href="javascript::" data-dismiss="modal" onclick="carrega_pagina(\'contrato\', \'create_veiculo_aditivo_rastreamento.php?id_contrato='.$contrato_form['contrato_rastreamento_aditivo_id_contrato'].'&id_aditivo='.$id_aditivo_criado.'\');" class="btn btn-primary">Lançar Veiculos</a>'
        );
    }
    echo json_encode($json_contrato);
}elseif($acao == 'enviar_contrato_aditivo'){
    $id_aditivo_contrato = addslashes($_POST['id_aditivo_contrato']);
    $email = addslashes($_POST['email']);
    $msg   = addslashes($_POST['msg']);
    $html_instalacao = '';
    $html_desinstalacao = '';
    $read_contrato_veiculo = ReadComposta("SELECT * FROM contrato_rastreamento_veiculo WHERE contrato_rastreamento_veiculo_id_aditivo = '".$id_aditivo_contrato."'");
    if(NumQuery($read_contrato_veiculo) > '0'){
        $read_aditivo_contrato_rastreamento = Read('contrato_rastreamento_aditivo', "WHERE contrato_rastreamento_aditivo_id = '".$id_aditivo_contrato."' AND contrato_rastreamento_aditivo_id_d4sign IS NULL");
        if(NumQuery($read_aditivo_contrato_rastreamento) > '0'){
            $html_instalacao .= '<table width="100%" border="0">';
            $html_instalacao .= '<tr bgcolor="#000000" style="font-size: 14px;">
                                <td style="color:#FFF"></td>
                                <td style="color:#FFF">PLACA</td>
                                <td style="color:#FFF">FROTA</td>
                                <td style="color:#FFF">MARCA</td>
                                <td style="color:#FFF">MODELO</td>
                                <td style="color:#FFF">ANO</td>
                                <td style="color:#FFF">COR</td>
                                <td style="color:#FFF">CHASSI</td>
                            </tr>';
            $html_desinstalacao .= '<table width="100%" border="0">';
            $html_desinstalacao .= '<tr bgcolor="#000000" style="font-size: 14px;">
                                <td style="color:#FFF"></td>
                                <td style="color:#FFF">PLACA</td>
                                <td style="color:#FFF">FROTA</td>
                                <td style="color:#FFF">MARCA</td>
                                <td style="color:#FFF">MODELO</td>
                                <td style="color:#FFF">ANO</td>
                                <td style="color:#FFF">COR</td>
                                <td style="color:#FFF">CHASSI</td>
                            </tr>';
            $count_instalacao = '0';
            $count_desinstalacao = '0';
            foreach($read_aditivo_contrato_rastreamento as $read_aditivo_contrato_rastreamento_view);
            foreach($read_contrato_veiculo as $read_contrato_veiculo_view){
                if($read_contrato_veiculo_view['contrato_rastreamento_veiculo_status'] == '0'){
                    $count_instalacao++;
                    $html_instalacao .= '<tr style="font-size: 10px;">';
                        $html_instalacao .= '<td>'.$count_instalacao.'</td>';
                        $html_instalacao .= '<td>'.$read_contrato_veiculo_view['contrato_rastreamento_veiculo_placa'].'</td>';
                        $html_instalacao .= '<td>'.$read_contrato_veiculo_view['contrato_rastreamento_veiculo_frota'].'</td>';
                        $html_instalacao .= '<td>'.$read_contrato_veiculo_view['contrato_rastreamento_veiculo_marca'].'</td>';
                        $html_instalacao .= '<td>'.$read_contrato_veiculo_view['contrato_rastreamento_veiculo_modelo'].'</td>';
                        $html_instalacao .= '<td>'.$read_contrato_veiculo_view['contrato_rastreamento_veiculo_ano'].'</td>';
                        $html_instalacao .= '<td>'.$read_contrato_veiculo_view['contrato_rastreamento_veiculo_cor'].'</td>';
                        $html_instalacao .= '<td>'.$read_contrato_veiculo_view['contrato_rastreamento_veiculo_chassi'].'</td>';
                    $html_instalacao .= '</tr>';
                }elseif($read_contrato_veiculo_view['contrato_rastreamento_veiculo_status'] == '1'){
                    $count_desinstalacao++;
                    $html_desinstalacao .= '<tr style="font-size: 10px;">';
                        $html_desinstalacao .= '<td>'.$count_desinstalacao.'</td>';
                        $html_desinstalacao .= '<td>'.$read_contrato_veiculo_view['contrato_rastreamento_veiculo_placa'].'</td>';
                        $html_desinstalacao .= '<td>'.$read_contrato_veiculo_view['contrato_rastreamento_veiculo_frota'].'</td>';
                        $html_desinstalacao .= '<td>'.$read_contrato_veiculo_view['contrato_rastreamento_veiculo_marca'].'</td>';
                        $html_desinstalacao .= '<td>'.$read_contrato_veiculo_view['contrato_rastreamento_veiculo_modelo'].'</td>';
                        $html_desinstalacao .= '<td>'.$read_contrato_veiculo_view['contrato_rastreamento_veiculo_ano'].'</td>';
                        $html_desinstalacao .= '<td>'.$read_contrato_veiculo_view['contrato_rastreamento_veiculo_cor'].'</td>';
                        $html_desinstalacao .= '<td>'.$read_contrato_veiculo_view['contrato_rastreamento_veiculo_chassi'].'</td>';
                    $html_desinstalacao .= '</tr>';
                }
            }
            $html_instalacao .= '</table>';
            $html_desinstalacao .= '</table>';
            $templates = array(
                ID_CONTRATO_RASTREAMENTO_ADITIVO_D4SIGN => array(
                        'NUMERO_ADITIVO' => $read_aditivo_contrato_rastreamento_view['contrato_rastreamento_aditivo_id_aditivo'],
                        'CODIGO_CONTRATO' => $read_aditivo_contrato_rastreamento_view['contrato_rastreamento_aditivo_id_contrato'],
                        'ADITIVO_INSTALACAO' => $html_instalacao,
                        'ADITIVO_DESINSTALACAO' => $html_desinstalacao,
                        'VALOR_MENSALIDADE' => $read_aditivo_contrato_rastreamento_view['contrato_rastreamento_aditivo_valor_mensalidade'],
                        'VALOR_MENSALIDADE_EXTENSO' => escreverValorMoeda($read_aditivo_contrato_rastreamento_view['contrato_rastreamento_aditivo_valor_mensalidade']),
                        'ADITIVO_DATA' => FormDataBr($read_aditivo_contrato_rastreamento_view['contrato_rastreamento_aditivo_data'])
                    )
                );							

            $name_document = "Aditivo Contrato Rastreamento";
            $uuid_cofre = ID_COFRE_CONTRATO_RASTREAMENTO_D4SIGN;
            $return = $client->documents->makedocumentbytemplate($uuid_cofre, $name_document, $templates);
            if($return->message == 'success'){
                $UPDATE_CONTRATO_ADITIVO_RASTREAMENTO['contrato_rastreamento_aditivo_id_d4sign'] = $return->uuid;
                Update('contrato_rastreamento_aditivo', $UPDATE_CONTRATO_ADITIVO_RASTREAMENTO, "WHERE contrato_rastreamento_aditivo_id = '".$id_aditivo_contrato."'");
                $signers = array(
                    array("email" => $email, "act" => '1', "foreign" => '0', "certificadoicpbr" => '0', "assinatura_presencial" => '0', "embed_methodauth" => 'email', "embed_smsnumber" => '')
                );
                $return_sign = $client->documents->createList($UPDATE_CONTRATO_ADITIVO_RASTREAMENTO['contrato_rastreamento_aditivo_id_d4sign'], $signers);
                $message = $msg;
                $workflow = 0;
                $skip_email = 0;

                $doc = $client->documents->sendToSigner($UPDATE_CONTRATO_ADITIVO_RASTREAMENTO['contrato_rastreamento_aditivo_id_d4sign'], $message, $workflow, $skip_email);
                echo '<p>Operação realizada com sucesso!</p>';
            }
        }else{
            echo '<p>Contrato não pode ser gerado, o mesmo já foi gerado.</p>';
        }
    }else{
        echo '<p>Operação não pode ser realizada, não existe veículos nesse aditivo.</p>';
    }
}elseif($acao == 'load_aditivo_veiculo'){
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
        $order_by = "ORDER BY contrato_rastreamento_veiculo_id DESC";
    }else{
        $sort       = addslashes($_GET['sort']);
        $sort_dir   = addslashes($_GET['sort_dir']);
        $order_by   = "ORDER BY ".$sort." ".$sort_dir."";
    }
    
    $id_contrato = addslashes($_GET['id_contrato']);
    $id_aditivo  = addslashes($_GET['id_aditivo']);
    
    $read_contrato_rastreamento_paginator = ReadComposta("SELECT contrato_rastreamento_veiculo_id FROM contrato_rastreamento_veiculo WHERE contrato_rastreamento_veiculo_id_aditivo = '".$id_aditivo."'");
    $read_contrato_rastreamento = ReadComposta("SELECT * FROM contrato_rastreamento_veiculo WHERE contrato_rastreamento_veiculo_id_aditivo = '".$id_aditivo."' ".$order_by." LIMIT $inicio,$maximo");
    if(NumQuery($read_contrato_rastreamento) > '0'){
        //PEGA QUANTOS BOTÕES PARA PAGINAÇÃO DE RESULTADOS
        $paginas = ceil(NumQuery($read_contrato_rastreamento_paginator) / $maximo);
        //TABULATOR EXIGE ESSA INFORMAÇÃO PARA QUE PRECISE DA PAGINAÇÃO DE RESULTADOS 
        $json_contrato["last_page"] = $paginas;
        foreach($read_contrato_rastreamento as $read_contrato_rastreamento_view){
            if($read_contrato_rastreamento_view['contrato_rastreamento_veiculo_status'] == '0'){
                $read_contrato_rastreamento_view['contrato_rastreamento_veiculo_status'] = 'Instalação';
            }else{
                $read_contrato_rastreamento_view['contrato_rastreamento_veiculo_status'] = 'Desinstalação';
            }
            $json_contrato['data'][] = $read_contrato_rastreamento_view;
        }
    }else{
        $json_contrato['data'] = null;
    }
    echo json_encode($json_contrato);
}elseif($acao == 'create_veiculo_rastreamento'){
    //RECUPERA O FORMULARIO
    $contrato_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($contrato_form['acao']);
    
    if($contrato_form['contrato_rastreamento_veiculo_modelo'] == ''){
        $json_contrato = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido existir campos em branco!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        $id_contrato = $contrato_form['id_contrato'];
        unset($contrato_form['id_contrato']);
        $id_aditivo = $contrato_form['contrato_rastreamento_veiculo_id_aditivo'];
        Create('contrato_rastreamento_veiculo', $contrato_form);
        $json_contrato = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Parabéns, operação realizada com sucesso!',
            'buttons' => '<a href="javascript::" data-dismiss="modal" onclick="carrega_pagina(\'contrato\', \'veiculo_aditivo_rastreamento.php?id_contrato='.$id_contrato.'&id_aditivo='.$id_aditivo.'\');" class="btn btn-default">Sair</a><a href="javascript::" data-dismiss="modal" onclick="carrega_pagina(\'contrato\', \'create_veiculo_aditivo_rastreamento.php?id_contrato='.$id_contrato.'&id_aditivo='.$id_aditivo.'\');" class="btn btn-primary">Lançar Mais Veiculos</a>'
        );
    }
    echo json_encode($json_contrato);
}elseif($acao == 'load_update_veiculo'){
    $uid = addslashes($_POST['id']);
    
    $read_contrato = Read('contrato_rastreamento_veiculo', "WHERE contrato_rastreamento_veiculo_id = '".$uid."'");
    if(NumQuery($read_contrato) > '0'){
        foreach($read_contrato as $read_contrato_view);
        $json_contrato[] = $read_contrato_view;
    }else{
        $json_contrato = null;
    }
    echo json_encode($json_contrato);
}elseif($acao == 'update_veiculo_rastreamento'){
    //RECUPERA O FORMULARIO
    $contrato_form = filter_input_array(trim(INPUT_POST, FILTER_DEFAULT));
    unset($contrato_form['acao']);
    
    if($contrato_form['contrato_rastreamento_veiculo_modelo'] == ''){
        $json_contrato = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, operação não pode ser finalizada devido existir campos em branco!',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
    }else{
        $id_contrato = $contrato_form['id_contrato'];
        unset($contrato_form['id_contrato']);
        $id_aditivo = $contrato_form['contrato_rastreamento_veiculo_id_aditivo'];
        $uid = $contrato_form['contrato_rastreamento_veiculo_id'];
        unset($contrato_form['contrato_rastreamento_veiculo_id']);
        Update('contrato_rastreamento_veiculo', $contrato_form, "WHERE contrato_rastreamento_veiculo_id = '".$uid."'");
        $json_contrato = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Parabéns, operação realizada com sucesso!',
            'buttons' => '<a href="javascript::" data-dismiss="modal" onclick="carrega_pagina(\'contrato\', \'veiculo_aditivo_rastreamento.php?id_contrato='.$id_contrato.'&id_aditivo='.$id_aditivo.'\');" class="btn btn-default">Sair</a>'
        );
    }
    echo json_encode($json_contrato);
}