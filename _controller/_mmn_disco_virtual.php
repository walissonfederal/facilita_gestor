<?php
session_start();
ob_start();
require_once '../_class_mmn/Ferramenta.php';

if(isset($_POST['acao'])){
    $acao = addslashes($_POST['acao']);
}else{
    $acao = addslashes($_GET['acao']);
}

if($acao == 'load_spc'){
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
        $order_by = "ORDER BY spc_id DESC";
    }else{
        
        $sort       = addslashes($_GET['sort']);
        $sort_dir   = addslashes($_GET['sort_dir']);
        $order_by   = "ORDER BY ".$sort." ".$sort_dir."";
    }
    
    
    $read_spc_paginator = ReadComposta("SELECT spc_id FROM spc");
    $read_spc = Read('spc', "WHERE spc_id != '' ".$order_by." LIMIT $inicio,$maximo");
    if(NumQuery($read_spc) > '0'){
        //PEGA QUANTOS BOTÕES PARA PAGINAÇÃO DE RESULTADOS
        $paginas = ceil(NumQuery($read_spc_paginator) / $maximo);
        //TABULATOR EXIGE ESSA INFORMAÇÃO PARA QUE PRECISE DA PAGINAÇÃO DE RESULTADOS 
        $json_spc["last_page"] = $paginas;
        foreach($read_spc as $read_spc_view){
            $json_spc['data'][] = $read_spc_view;
        }
    }else{
        $json_spc['data'] = null;
    }
    echo json_encode($json_spc);
}elseif($acao == 'download_arquivo_spc'){
    $arquivo = addslashes($_GET['arquivo']);
    
    $novoNome = $arquivo;
    // Configuramos os headers que serão enviados para o browser
    header('Content-Description: File Transfer');
    header('Content-Disposition: attachment; filename="'.$novoNome.'"');
    header('Content-Type: application/octet-stream');
    header('Content-Transfer-Encoding: binary');
    header('Content-Length: ' . filesize($novoNome));
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header('Expires: 0');
    // Envia o arquivo para o cliente
    readfile($novoNome);
}elseif($acao == 'mail_arquivo_spc'){
    $spc_id     = addslashes($_GET['spc_id']);
    $spc_mail   = addslashes($_GET['email']);
    $spc_obs    = addslashes($_GET['obs']);
    
    if($spc_id == '' || $spc_mail == '' || $spc_obs == ''){
        echo 'É preciso preencher todos os campos';
    }else{
        $read_spc = Read('spc', "WHERE spc_id = '".$spc_id."'");
        if(NumQuery($read_spc) > '0'){
            foreach($read_spc as $read_spc_view);
            sendMail('Arquivo SPC', $spc_obs, GetEmpresa('empresa_email'), GetEmpresa('empresa_nome_fantasia'), $spc_mail, "", '', '', $read_spc_view['spc_arquivo'], 'arquivo.txt');
            echo 'Enviado com sucesso';
        }
    }
}elseif($acao == 'load_remessa'){
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
        $order_by = "ORDER BY remessa_id DESC";
    }else{
        
        $sort       = addslashes($_GET['sort']);
        $sort_dir   = addslashes($_GET['sort_dir']);
        $order_by   = "ORDER BY ".$sort." ".$sort_dir."";
    }
    
    
    $read_remessa_paginator = ReadComposta("SELECT remessa_id FROM remessa");
    $read_remessa = Read('remessa', "WHERE remessa_id != '' ".$order_by." LIMIT $inicio,$maximo");
    if(NumQuery($read_remessa) > '0'){
        //PEGA QUANTOS BOTÕES PARA PAGINAÇÃO DE RESULTADOS
        $paginas = ceil(NumQuery($read_remessa_paginator) / $maximo);
        //TABULATOR EXIGE ESSA INFORMAÇÃO PARA QUE PRECISE DA PAGINAÇÃO DE RESULTADOS 
        $json_remessa["last_page"] = $paginas;
        foreach($read_remessa as $read_remessa_view){
            $json_remessa['data'][] = $read_remessa_view;
        }
    }else{
        $json_remessa['data'] = null;
    }
    echo json_encode($json_remessa);
}elseif($acao == 'download_arquivo_remessa'){
    $arquivo = addslashes($_GET['arquivo']);
    
    $novoNome = $arquivo;
    // Configuramos os headers que serão enviados para o browser
    header('Content-Description: File Transfer');
    header('Content-Disposition: attachment; filename="'.$novoNome.'"');
    header('Content-Type: application/octet-stream');
    header('Content-Transfer-Encoding: binary');
    header('Content-Length: ' . filesize($novoNome));
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header('Expires: 0');
    // Envia o arquivo para o cliente
    readfile($novoNome);
}elseif($acao == 'mail_arquivo_remessa'){
    $remessa_id     = addslashes($_GET['spc_id']);
    $remessa_mail   = addslashes($_GET['email']);
    $remessa_obs    = addslashes($_GET['obs']);
    
    if($remessa_id == '' || $remessa_mail == '' || $remessa_obs == ''){
        echo 'É preciso preencher todos os campos';
    }else{
        $read_remessa = Read('remessa', "WHERE remessa_id = '".$remessa_id."'");
        if(NumQuery($read_remessa) > '0'){
            foreach($read_remessa as $read_remessa_view);
            sendMail('Arquivo REMESSA', $remessa_obs, GetEmpresa('empresa_email'), GetEmpresa('empresa_nome_fantasia'), $remessa_mail, "", '', '', $read_remessa_view['remessa_arquivo'], 'arquivo.txt');
            echo 'Enviado com sucesso';
        }
    }
}elseif($acao == 'load_retorno'){
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
        $order_by = "ORDER BY baixa_retorno_id DESC";
    }else{
        
        $sort       = addslashes($_GET['sort']);
        $sort_dir   = addslashes($_GET['sort_dir']);
        $order_by   = "ORDER BY ".$sort." ".$sort_dir."";
    }
    
    
    $read_retorno_paginator = ReadComposta("SELECT baixa_retorno_id FROM baixa_retorno");
    $read_retorno = Read('baixa_retorno', "WHERE baixa_retorno_id != '' ".$order_by." LIMIT $inicio,$maximo");
    if(NumQuery($read_retorno) > '0'){
        //PEGA QUANTOS BOTÕES PARA PAGINAÇÃO DE RESULTADOS
        $paginas = ceil(NumQuery($read_retorno_paginator) / $maximo);
        //TABULATOR EXIGE ESSA INFORMAÇÃO PARA QUE PRECISE DA PAGINAÇÃO DE RESULTADOS 
        $json_retorno["last_page"] = $paginas;
        foreach($read_retorno as $read_retorno_view){
            $json_retorno['data'][] = $read_retorno_view;
        }
    }else{
        $json_retorno['data'] = null;
    }
    echo json_encode($json_retorno);
}elseif($acao == 'download_arquivo_retorno'){
    $arquivo = addslashes($_GET['arquivo']);
    
    $novoNome = $arquivo;
    // Configuramos os headers que serão enviados para o browser
    header('Content-Description: File Transfer');
    header('Content-Disposition: attachment; filename="'.$novoNome.'"');
    header('Content-Type: application/octet-stream');
    header('Content-Transfer-Encoding: binary');
    header('Content-Length: ' . filesize($novoNome));
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header('Expires: 0');
    // Envia o arquivo para o cliente
    readfile($novoNome);
}elseif($acao == 'mail_arquivo_retorno'){
    $remessa_id     = addslashes($_GET['spc_id']);
    $remessa_mail   = addslashes($_GET['email']);
    $remessa_obs    = addslashes($_GET['obs']);
    
    if($remessa_id == '' || $remessa_mail == '' || $remessa_obs == ''){
        echo 'É preciso preencher todos os campos';
    }else{
        $read_retorno = Read('baixa_retorno', "WHERE baixa_retorno_id = '".$remessa_id."'");
        if(NumQuery($read_retorno) > '0'){
            foreach($read_retorno as $read_retorno_view);
            sendMail('Arquivo RETORNO', $remessa_obs, GetEmpresa('empresa_email'), GetEmpresa('empresa_nome_fantasia'), $remessa_mail, "", '', '', $read_remessa_view['baixa_retorno_arquivo'], 'arquivo.txt');
            echo 'Enviado com sucesso';
        }
    }
}elseif($acao == 'verify_arquivo_retorno'){
	$arquivo = addslashes($_GET['arquivo']);
	
	$read_retorno = Read('baixa', "WHERE baixa_id_baixa_retorno = '".$arquivo."'");
	if(NumQuery($read_retorno) > '0'):
		$read_retorno_completo = ReadComposta("SELECT baixa_id, pedido_id FROM baixa INNER JOIN pedido ON pedido_nosso_numero = baixa_nosso_numero WHERE pedido_status = '0' AND baixa_id_baixa_retorno = '".$arquivo."'");
		$var_retorno .= '<strong>Registros importados: </strong>'.NumQuery($read_retorno);
		$var_retorno .= '<br /><strong>Registros em aberto: </strong>'.NumQuery($read_retorno_completo);
		$json_retorno = array(
            'type' => 'success',
            'title' => 'Parabéns:',
            'msg' => 'Retorno<br /><br /><br />'.$var_retorno,
            'buttons' => '<a href="javascript::" data-dismiss="modal" class="btn btn-primary">Fechar</a>'
        );
	else:
		$json_retorno = array(
            'type' => 'error',
            'title' => 'Erro:',
            'msg' => 'Ops, houve um problema na operação',
            'buttons' => '<button type="button" class="btn btn-default" data-dismiss="modal">Apenas Fechar</button>'
        );
	endif;
	echo json_encode($json_retorno);
}
?>