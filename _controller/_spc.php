<?php
session_start();
ob_start();
require_once '../_class/Ferramenta.php';

if(isset($_POST['acao'])){
    $acao = addslashes($_POST['acao']);
}else{
    $acao = addslashes($_GET['acao']);
}

if($acao == 'enviar'){
    
    $get_id_plano_conta     = addslashes($_GET['id_plano_conta']);
    $get_id_tipo_documento  = addslashes($_GET['id_tipo_documento']);
    $get_descricao          = addslashes($_GET['descricao']);
    $get_data_vencimento    = addslashes($_GET['data_vencimento']);
    
    if($get_id_plano_conta == '' || $get_id_tipo_documento == '' || $get_descricao == '' || $get_data_vencimento == ''){
        $data['sucesso'] = false;

        /* Caminho do arquivo */
        $data['msg'] = 'Todos os campos devem ser preenchidos!';
    }else{
        
        $_SESSION['spc_id_plano_conta']     = $get_id_plano_conta;
        $_SESSION['spc_id_tipo_documento']  = $get_id_tipo_documento;
        $_SESSION['spc_descricao']          = $get_descricao;
        $_SESSION['spc_data_vencimento']    = $get_data_vencimento;
        
        /* Captura o arquivo selecionado */
        $arquivo = $_FILES['arquivo'];

        /*Define os tipos de arquivos válidos (No nosso caso, só imagens)*/
        $tipos = array('txt');

        /* Chama a função para enviar o arquivo */
        $enviar = uploadFile($arquivo, '../_uploads/'.GetEmpresa('empresa_nome_pasta').'/spc/', $tipos);

        $data['sucesso'] = false;

        if($enviar['erro']){    
            $data['msg'] = $enviar['erro'];
        }
        else{
            $data['sucesso'] = true;

            /* Caminho do arquivo */
            $data['msg'] = $enviar['caminho'];
            
            //inserir
            $spc_form['spc_arquivo'] = $enviar['caminho'];
            $spc_form['spc_data_hora'] = date('Y-m-d H:i:s');
            Create('spc', $spc_form);
        }
    }
    /* Codifica a variável array $data para o formato JSON */
    echo json_encode($data);
}elseif($acao == 'ler'){
    $uid = GetReg('spc', 'spc_id', "");
    $read_spc = Read('spc', "WHERE spc_id = '".$uid."'");
    if(NumQuery($read_spc) > '0'){
        foreach($read_spc as $read_spc_view);
        
        $arquivo = $read_spc_view['spc_arquivo'];
        $arquivoArr = array();
        $arq = fopen($arquivo, 'r');
        $total_linhas_importadas = 0;
        while(!feof($arq)){
            $conteudo = fgets($arq);

            $linha = explode(';', $conteudo);

            $arquivoArr[$total_linhas_importadas] = $linha;

            $total_linhas_importadas++;
        }
        foreach($arquivoArr as $linha){
            foreach($linha as $campo){
                if(substr($campo, 0, 1) == '3'){
                    $CodigoCliente = addslashes(trim(substr($campo, 42, 8)));
                    if(substr($CodigoCliente, 0, 7) == '0000000'){
                        $ClienteCodigo = substr($CodigoCliente, 7, 1);
                    }elseif(substr($CodigoCliente, 0, 6) == '000000'){
                        $ClienteCodigo = substr($CodigoCliente, 6, 2);
                    }elseif(substr($CodigoCliente, 0, 5) == '00000'){
                        $ClienteCodigo = substr($CodigoCliente, 5, 3);
                    }elseif(substr($CodigoCliente, 0, 4) == '0000'){
                        $ClienteCodigo = substr($CodigoCliente, 4, 4);
                    }elseif(substr($CodigoCliente, 0, 3) == '000'){
                        $ClienteCodigo = substr($CodigoCliente, 3, 5);
                    }elseif(substr($CodigoCliente, 0, 2) == '00'){
                        $ClienteCodigo = substr($CodigoCliente, 2, 6);
                    }elseif(substr($CodigoCliente, 0, 1) == '0'){
                        $ClienteCodigo = substr($CodigoCliente, 1, 7);
                    }else{
                        $ClienteCodigo = substr($CodigoCliente, 0, 8);
                    }
                    $NomeRazaoSocial    = addslashes(trim(substr($campo, 50, 50)));
                    $Endereco		= addslashes(trim(substr($campo, 100, 62)));
                    $Bairro		= addslashes(trim(substr($campo, 162, 30)));
                    $Cep		= addslashes(trim(substr($campo, 193, 8)));
                    $Cidade		= addslashes(trim(substr($campo, 201, 30)));
                    $Uf			= addslashes(trim(substr($campo, 231, 2)));
                    $Telefone		= addslashes(trim(substr($campo, 238, 10)));
                    $CpfCnpj		= addslashes(trim(substr($campo, 269, 14)));
                    $RgIe		= addslashes(trim(substr($campo, 283, 13)));
                    $Fantasia		= addslashes(trim(substr($campo, 511, 58)));

                    $ClienteCreateUpdate['contato_id']              = $ClienteCodigo;
                    $ClienteCreateUpdate['contato_id_tipo_contato'] = GetEmpresa('empresa_spc_id_tipo_contato');
                    $ClienteCreateUpdate['contato_id_rota']         = GetEmpresa('empresa_spc_id_rota');
                    $ClienteCreateUpdate['contato_id_regiao']       = GetEmpresa('empresa_spc_id_regiao');
                    $ClienteCreateUpdate['contato_status']          = GetEmpresa('empresa_spc_status_contato');
                    $ClienteCreateUpdate['contato_nome_razao']      = utf8_encode($NomeRazaoSocial);
                    $ClienteCreateUpdate['contato_nome_fantasia']   = utf8_encode($Fantasia);
                    $ClienteCreateUpdate['contato_cpf_cnpj']        = $CpfCnpj;
                    $ClienteCreateUpdate['contato_ie_rg']           = utf8_encode($RgIe);
                    $ClienteCreateUpdate['contato_endereco']        = utf8_encode($Endereco);
                    $ClienteCreateUpdate['contato_bairro']          = utf8_encode($Bairro);
                    $ClienteCreateUpdate['contato_cep']             = utf8_encode($Cep);
                    $ClienteCreateUpdate['contato_telefone']        = utf8_encode($Telefone);
                    $ClienteCreateUpdate['contato_cidade']          = $Cidade;
                    $ClienteCreateUpdate['contato_estado']          = $Uf;

                    $readClientes = read('contato', "WHERE contato_id = '".$ClienteCodigo."'");
                    if(NumQuery($readClientes) == '0'){
                        Create('contato', $ClienteCreateUpdate);
                    }
                }

                if(substr($campo, 0, 1) == '4' || substr($campo, 0, 1) == '3'){
                    $CodigoCliente = addslashes(trim(substr($campo, 42, 8)));
                    if(substr($CodigoCliente, 0, 7) == '0000000'){
                        $ClienteCodigo = substr($CodigoCliente, 7, 1);
                    }elseif(substr($CodigoCliente, 0, 6) == '000000'){
                        $ClienteCodigo = substr($CodigoCliente, 6, 2);
                    }elseif(substr($CodigoCliente, 0, 5) == '00000'){
                        $ClienteCodigo = substr($CodigoCliente, 5, 3);
                    }elseif(substr($CodigoCliente, 0, 4) == '0000'){
                        $ClienteCodigo = substr($CodigoCliente, 4, 4);
                    }elseif(substr($CodigoCliente, 0, 3) == '000'){
                        $ClienteCodigo = substr($CodigoCliente, 3, 5);
                    }elseif(substr($CodigoCliente, 0, 2) == '00'){
                        $ClienteCodigo = substr($CodigoCliente, 2, 6);
                    }elseif(substr($CodigoCliente, 0, 1) == '0'){
                        $ClienteCodigo = substr($CodigoCliente, 1, 7);
                    }else{
                        $ClienteCodigo = substr($CodigoCliente, 0, 8);
                    }
                    if(substr($campo, 0, 1) == '3'){
                        $CliCodigo = $ClienteCodigo;
                        if(substr($CodigoCliente, 0, 7) == '0000000'){
                            $ClienteCodigo1 = substr($CodigoCliente, 7, 1);
                        }elseif(substr($CodigoCliente, 0, 6) == '000000'){
                            $ClienteCodigo1 = substr($CodigoCliente, 6, 2);
                        }elseif(substr($CodigoCliente, 0, 5) == '00000'){
                            $ClienteCodigo1 = substr($CodigoCliente, 5, 3);
                        }elseif(substr($CodigoCliente, 0, 4) == '0000'){
                            $ClienteCodigo1 = substr($CodigoCliente, 4, 4);
                        }elseif(substr($CodigoCliente, 0, 3) == '000'){
                            $ClienteCodigo1 = substr($CodigoCliente, 3, 5);
                        }elseif(substr($CodigoCliente, 0, 2) == '00'){
                            $ClienteCodigo1 = substr($CodigoCliente, 2, 6);
                        }elseif(substr($CodigoCliente, 0, 1) == '0'){
                            $ClienteCodigo1 = substr($CodigoCliente, 1, 7);
                        }else{
                            $ClienteCodigo1 = substr($CodigoCliente, 0, 8);
                        }
                    }
                }
                
                if(substr($campo, 0, 1) == '4'){
                    $ClienteCodigo1     = $ClienteCodigo1;
                    $TotalPagar1 	= addslashes(trim(substr($campo, 59,6) / 100));
                    $Produto 		= addslashes(trim(substr($campo, 79, 20)));
                }
                
                if(substr($campo, 0, 1) == '3'){
                    $TotalPagar                             = addslashes(trim(substr($campo, 29,13) / 100));
                    $v['orcamento_venda_data']              = date('Y-m-d');
                    $v['orcamento_venda_data_prazo']        = date('Y-m-d');
                    $v['orcamento_venda_data_hora']         = date('Y-m-d H:i:s');
                    $v['orcamento_venda_tipo']              = '1';
                    $v['orcamento_venda_status']            = '0';
                    $v['orcamento_venda_id_user']           = $_SESSION[VSESSION]['user_id'];
                    $v['orcamento_venda_id_contato']        = $ClienteCodigo1;
                    $v['orcamento_venda_ref']               = $_SESSION['spc_descricao'];
                    $v['orcamento_venda_obs']               = $_SESSION['spc_descricao'];
                    $v['orcamento_venda_codigo_spc']        = addslashes(trim(substr($campo, 14, 15)));
                    $v['orcamento_venda_obs_interno']       = addslashes(trim(substr($campo, 21, 8)));
                    $v['orcamento_venda_valor_produtos']    = $TotalPagar;
                    $v['orcamento_venda_valor_total']       = $TotalPagar;
                    Create('orcamento_venda', $v);
                    $id_orcamento_venda = GetReg('orcamento_venda', 'orcamento_venda_id', "WHERE orcamento_venda_tipo = '0'");
                    $fp['pagamento_id_orcamento_venda'] = $id_orcamento_venda;
                    $fp['pagamento_data']               = date('Y-m-d');
                    $fp['pagamento_valor']              = $TotalPagar;
                    $fp['pagamento_id_forma_pagamento'] = GetEmpresa('empresa_spc_id_forma_pagamento');
                    $fp['pagamento_tipo']               = '1';
                    Create('pagamento', $fp);
                }
                if(substr($campo, 0, 1) == '4'){
                    $p['produto_id']            = $Produto;
                    $p['produto_descricao'] 	= addslashes(trim(substr($campo, 8, 50)));
                    $p['produto_descricao']	= utf8_encode($p['produto_descricao']);
                    $p['produto_preco_custo']	= $TotalPagar1;
                    $p['produto_preco_venda']	= $TotalPagar1;
                    $readProdutos = Read('produto', "WHERE produto_id = '".$p['Id']."'");
                    if(NumQuery($readProdutos) == '0'){
                        Create('produto', $p);
                    }
                    $i['itens_orcamento_venda_id_orcamento_venda']  = $v['orcamento_venda_codigo_spc'];
                    $i['itens_orcamento_venda_id_produto']          = $Produto;
                    $i['itens_orcamento_venda_qtd']                 = addslashes(trim(substr($campo, 3, 5)));
                    $i['itens_orcamento_venda_valor_unitario']      = $TotalPagar1;
                    $i['itens_orcamento_venda_valor_total']         = $TotalPagar1 * $i['itens_orcamento_venda_qtd'];
                    Create('itens_orcamento_venda', $i);
                }
            }
        }
    }
    $data['sucesso'] = true;
    $data['msg'] = 'OK';
    echo json_encode($data);
}elseif($acao == 'ler_venda'){
    
    $readVendasZerarCodigoSpc = Read('orcamento_venda', "WHERE orcamento_venda_codigo_spc IS NOT NULL");
    if(NumQuery($readVendasZerarCodigoSpc) > '0'){
        foreach($readVendasZerarCodigoSpc as $readVendasZerarCodigoSpcView){
            $readItensVendasZerarCodigoSpc = Read('itens_orcamento_venda', "WHERE itens_orcamento_venda_id_orcamento_venda = '".$readVendasZerarCodigoSpcView['orcamento_venda_codigo_spc']."'");
            if(NumQuery($readItensVendasZerarCodigoSpc) > '0'){
                foreach($readItensVendasZerarCodigoSpc as $readItensVendasZerarCodigoSpcView){
                    $ei['itens_orcamento_venda_id_orcamento_venda'] = $readVendasZerarCodigoSpcView['orcamento_venda_id'];
                    Update('itens_orcamento_venda', $ei, "WHERE itens_orcamento_venda_id = '".$readItensVendasZerarCodigoSpcView['itens_orcamento_venda_id']."'");
                }
            }
        }
    }
    
    $data['sucesso'] = true;
    $data['msg'] = 'OK';
    echo json_encode($data);
}elseif($acao == 'ler_financeiro'){
    
    $readContasReceber = read('orcamento_venda', "WHERE orcamento_venda_codigo_spc IS NOT NULL");
    if($readContasReceber){
        foreach($readContasReceber as $readContasReceberView){
            $cr['financeiro_id_venda']          = $readContasReceberView['orcamento_venda_id'];
            $cr['financeiro_obs']               = $_SESSION['spc_descricao'];
            $cr['financeiro_descricao'] 	= $_SESSION['spc_descricao'];
            $cr['financeiro_codigo']            = GetReg('financeiro', 'financeiro_codigo', "WHERE financeiro_tipo = 'CR'") + 1;
            $cr['financeiro_valor']             = $readContasReceberView['orcamento_venda_valor_total'];
            $cr['financeiro_id_tipo_documento'] = $_SESSION['spc_id_tipo_documento'];
            $cr['financeiro_id_plano_conta'] 	= $_SESSION['spc_id_plano_conta'];
            $cr['financeiro_id_contato']	= $readContasReceberView['orcamento_venda_id_contato'];


            $cr['financeiro_tipo']              = 'CR';
            $cr['financeiro_data_lancamento']   = date('Y-m-d');
            $cr['financeiro_data_vencimento']   = $_SESSION['spc_data_vencimento'];
            $cr['financeiro_numero_doc']        = $readContasReceberView['orcamento_venda_obs_interno'];
            $cr['financeiro_status']            = '0';
            $cr['financeiro_md5']               = md5(date('Y-m-dH:i:s').rand(9,99999999999));
            $cr['financeiro_fixo']              = '0';
            $cr['financeiro_app_financeira']    = '0';
            $cr['financeiro_id_venda']          = $readContasReceberView['orcamento_venda_id'];
            create('financeiro', $cr);
        }
    }
    @mysqli_query(Conn(),"UPDATE orcamento_venda SET orcamento_venda_codigo_spc = NULL");
    
    $data['sucesso'] = true;
    $data['msg'] = 'OK';
    echo json_encode($data);
}
?>