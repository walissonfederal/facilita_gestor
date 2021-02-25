<?php
session_start();
ob_start();
require_once '../_class/Ferramenta.php';

require_once ("../_remessa/OpenCnabPHP-master/autoloader.php");


if(isset($_POST['acao'])){
    $acao = addslashes($_POST['acao']);
}else{
    $acao = addslashes($_GET['acao']);
}

if($acao == 'gerar_remessa'){
    $banco = addslashes($_POST['banco']);
    $boleto = addslashes($_POST['boleto']);
    
    $read_boleto = Read('boleto', "WHERE boleto_id = '".$boleto."'");
    if(NumQuery($read_boleto) > '0'){
        foreach($read_boleto as $read_boleto_view);
        $juros_dia = ($read_boleto_view['boleto_juros'] / 30);
    }
    
    if($banco == '0'){
        $nome_arquivo = md5(date('Y-m-dH:i:s').rand(9,999999999));
        $nome_pasta_arquivo = '../_uploads/'.GetEmpresa('empresa_nome_pasta').'/remessa/'.$nome_arquivo.'.txt';
        
        $remessa['remessa_arquivo'] = $nome_pasta_arquivo;
        $remessa['remessa_data_hora'] = date('Y-m-d H:i:s');
        $remessa['remessa_id_boleto'] = $boleto;
        Create('remessa', $remessa);
        $read_remessa = ReadComposta("SELECT remessa_id, remessa_arquivo FROM remessa ORDER BY remessa_id DESC LIMIT 1");
        if(NumQuery($read_remessa) > '0'){
            foreach($read_remessa as $read_remessa_view);
        }
        include '../_remessa/CnabPHP-master/vendor/autoload.php';

        $codigo_banco = Cnab\Banco::CEF;
        $arquivo = new Cnab\Remessa\Cnab240\Arquivo($codigo_banco, 'sigcb');
        //$arquivo = new Cnab\Remessa\Cnab240\Arquivo($codigo_banco);
        $arquivo->configure(array(
            'data_geracao'  => new DateTime(),
            'data_gravacao' => new DateTime(), 
            'nome_fantasia' => GetEmpresa('empresa_nome_fantasia'), // seu nome de empresa
            'razao_social'  => GetEmpresa('empresa_nome_razao'),  // sua razão social
            'cnpj'          => GetEmpresa('empresa_cnpj'), // seu cnpj completo
            'banco'         => $codigo_banco, //código do banco
            'logradouro'    => GetEmpresa('empresa_endereco'),
            'numero'        => GetEmpresa('empresa_numero'),
            'bairro'        => GetEmpresa('empresa_bairro'), 
            'cidade'        => GetEmpresa('empresa_cidade'),
            'uf'            => GetEmpresa('empresa_estado'),
            'cep'           => GetEmpresa('empresa_cep'),
            'agencia'       => $read_boleto_view['boleto_agencia'], 
            'conta'         => $read_boleto_view['boleto_conta'], // número da conta
            'conta_dac'     => $read_boleto_view['boleto_conta_digito'], // digito da conta
            'codigo_cedente'=> $read_boleto_view['boleto_conta_cedente'],
            'codigo_cedente_dv'=> $read_boleto_view['boleto_conta_cedente_digito'],
            'agencia_dv'    => '0',
            'numero_sequencial_arquivo' => $read_remessa_view['remessa_id'],
            'operacao'      => ''
            
        ));

        // você pode adicionar vários boletos em uma remessa
        $read_financeiro_remessa = Read('financeiro', "WHERE financeiro_tipo = 'CR' {$_SESSION['financeiro_load']}");
        if(NumQuery($read_financeiro_remessa) > '0'){
            foreach($read_financeiro_remessa as $read_financeiro_remessa_view){
                $read_contato_remessa = Read('contato', "WHERE contato_id = '".$read_financeiro_remessa_view['financeiro_id_contato']."'");
                if(NumQuery($read_contato_remessa) > '0'){
                    foreach($read_contato_remessa as $read_contato_remessa_view);
                    $cnpj_sem_caracter = str_replace('.', '', $read_contato_remessa_view['contato_cpf_cnpj']);
                    $cnpj_sem_caracter_1 = str_replace('/', '', $cnpj_sem_caracter);
                    $cnpj_sem_caracter_2 = str_replace('-', '', $cnpj_sem_caracter_1);
                    if(strlen($cnpj_sem_caracter_2) == '14'){
                        $type_identificacao = 'cnpj';
                    }else{
                        $type_identificacao = 'cpf';
                    }
                    $cep_sem_caracter = str_replace('.', '', $read_contato_remessa_view['contato_cep']);
                    $cep_sem_caracter_1 = str_replace('-', '', $cep_sem_caracter);
                    
                }else{
                    echo 'ok';
                }
                $nosso_numero_primeiro = str_replace('-', '', substr($read_financeiro_remessa_view['financeiro_nosso_numero'], 5,20));
                $nosso_numero_segundo  = substr($read_financeiro_remessa_view['financeiro_nosso_numero'],0,2).$nosso_numero_primeiro;
                $arquivo->insertDetalhe(array(
                    'codigo_ocorrencia' => 1, // 1 = Entrada de título, futuramente poderemos ter uma constante
                    'nosso_numero'      => $nosso_numero_segundo,
                    'numero_documento'  => $read_financeiro_remessa_view['financeiro_codigo'],
                    'modalidade_carteira' => $read_boleto_view['boleto_carteira'],
                    'carteira'          => $read_boleto_view['boleto_carteira'],
                    'especie'           => Cnab\Especie::CEF_OUTROS, // Você pode consultar as especies Cnab\Especie
                    'valor'             => $read_financeiro_remessa_view['financeiro_valor'], // Valor do boleto
                    'instrucao1'        => 2, // 1 = Protestar com (Prazo) dias, 2 = Devolver após (Prazo) dias, futuramente poderemos ter uma constante
                    'instrucao2'        => 0, // preenchido com zeros
                    'sacado_nome'       => $read_contato_remessa_view['contato_nome_razao'], // O Sacado é o cliente, preste atenção nos campos abaixo
                    'sacado_tipo'       => $type_identificacao, //campo fixo, escreva 'cpf' (sim as letras cpf) se for pessoa fisica, cnpj se for pessoa juridica
                    'sacado_'.$type_identificacao        => $cnpj_sem_caracter_2,
                    'sacado_logradouro' => $read_contato_remessa_view['contato_endereco'],
                    'sacado_bairro'     => $read_contato_remessa_view['contato_bairro'],
                    'sacado_cep'        => $cep_sem_caracter_1, // sem hífem
                    'sacado_cidade'     => $read_contato_remessa_view['contato_cidade'],
                    'sacado_uf'         => $read_contato_remessa_view['contato_estado'],
                    'data_vencimento'   => new DateTime($read_financeiro_remessa_view['financeiro_data_vencimento']),
                    'data_cadastro'     => new DateTime($read_financeiro_remessa_view['financeiro_data_lancamento']),
                    'juros_de_um_dia'     => $juros_dia * $read_financeiro_remessa_view['financeiro_valor'], // Valor do juros de 1 dia'
                    'data_desconto'       => new DateTime('2014-06-01'),
                    'valor_desconto'      => 0, // Valor do desconto
                    'prazo'               => 0, // prazo de dias para o cliente pagar após o vencimento
                    'taxa_de_permanencia' => '0', //00 = Acata Comissão por Dia (recomendável), 51 Acata Condições de Cadastramento na CAIXA
                    'mensagem'            => $read_financeiro_remessa_view['financeiro_descricao'],
                    'data_multa'          => new DateTime($read_financeiro_remessa_view['financeiro_data_vencimento']), // data da multa
                    'valor_multa'         => ($read_boleto_view['boleto_multa'] / 100) * $read_financeiro_remessa_view['financeiro_valor'], // valor da multa
                    'aceite'              => 'N'
                ));
            }
        }
        $arquivo->save($read_remessa_view['remessa_arquivo']);
        
        
        $json_remessa = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Operação realizada com sucesso, sua remessa se encontra no disco virtual!',
            'buttons' => '<a href="javascript::" onclick="carrega_pagina(\'financeiro\', \'index.php?OP=CR\');" data-dismiss="modal" class="btn btn-primary">Sair</a>'
        );
        echo json_encode($json_remessa);
    }
}
?>
