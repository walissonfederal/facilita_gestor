<?php
session_start();
require_once 'Conn.php';

/**
 * FAZ O CADASTRO DINAMICO NO BANCO DE DADOS 
 * */
function Create($Tabela, array $Dados) {
    foreach ($Dados as $Key => $ValuesKey) {
        if (!is_array($ValuesKey)) {
            $Dados[$Key] = addslashes($ValuesKey);
            $descricao_query .= $Key . ' = '. addslashes($ValuesKey).' | ';
        }
    }
    $Campos = implode(',', array_keys($Dados));
    $Fields = "'" . implode("','", array_values($Dados)) . "'";
    $SqlCreate = "INSERT INTO {$Tabela} ({$Campos}) VALUES({$Fields})";
    $QuerySqlCreate = mysqli_query(Conn(), $SqlCreate);
    if ($QuerySqlCreate) {
        QueryAuditoria(date('Y-m-d'), date('Y-m-d H:i:s'), $Tabela, 'create', $descricao_query, $_SESSION['PROJETO_CDL']['user_id']);
        return true;
    } else {
        echo mysqli_error(Conn());
    }
}

/**
 * FAZ O SELECT COM O BANCO DE DADOS
 * */
function Read($Tabela, $Condicao = NULL) {
    $SqlRead = "SELECT * FROM {$Tabela} {$Condicao}";
    $QueryRead = mysqli_query(Conn(), $SqlRead);
    if ($QueryRead) {
        return $QueryRead;
    } else {
        return false;
    }
}

function ReadComposta($Query){
    //echo $Query;
    $QueryRead = mysqli_query(Conn(), $Query);
    if ($QueryRead) {
        return $QueryRead;
    } else {
        return false;
    }
}

/**
 * VERIFICA SE A LINHAS NA QUERY
 * */
function NumQuery($Query) {
    $CountQuery = mysqli_num_rows($Query);
    return $CountQuery;
}

/**
 * FAZ A ATUALIZACAO DO REGISTRO NA TABELA
 * */
function Update($Tabela, array $Dados, $Condicao = NULL) {
    foreach ($Dados as $KeyDados => $ValueKeyDados) {
        if (!is_array($ValueKeyDados)) {
            $Dados[$KeyDados] = addslashes($ValueKeyDados);
            $descricao_query .= $KeyDados . ' = '. addslashes($ValueKeyDados).' | ';
        }
    }
    foreach ($Dados as $Keys => $ValuesKeys) {
        if($ValuesKeys == 'NULL'){
            $CamposFields[] = "$Keys = null";
        }else{
            $CamposFields[] = "$Keys = '$ValuesKeys'";
        }
    }
    $CamposFields = implode(", ", $CamposFields);
    $SqlUpdate = "UPDATE {$Tabela} SET {$CamposFields} {$Condicao}";
    
    $QueryUpdate = mysqli_query(Conn(), $SqlUpdate);
    
    if ($QueryUpdate) {
        QueryAuditoria(date('Y-m-d'), date('Y-m-d H:i:s'), $Tabela, 'update', $descricao_query, $_SESSION['PROJETO_CDL']['user_id']);
        return true;
    } else {
        echo mysqli_error(Conn());
    }
}

/**
 * FAZ A EXCLUSAO DO REGISTRO
 * */
function Delete($Tabela, $Condicao = NULL) {
    $SqlDelete = "DELETE FROM {$Tabela} {$Condicao}";
    $QueryDelete = mysqli_query(Conn(), $SqlDelete);
    if ($QueryDelete) {
        QueryAuditoria(date('Y-m-d'), date('Y-m-d H:i:s'), $Tabela, 'delete', $Condicao, $_SESSION['PROJETO_CDL']['user_id']);
        return true;
    } else {
        echo mysqli_error(Conn());
    }
}

function QueryAuditoria($Data, $DataHora, $Tabela, $Operacao, $Texto, $IdUser){
    if($Tabela != 'auditoria'):
        $create_auditoria['auditoria_data'] = $Data;
        $create_auditoria['auditoria_data_hora'] = $DataHora;
        $create_auditoria['auditoria_tabela'] = $Tabela;
        $create_auditoria['auditoria_operacao'] = $Operacao;
        $create_auditoria['auditoria_texto'] = $Texto;
        $create_auditoria['auditoria_id_user'] = $IdUser;
        Create('auditoria', $create_auditoria);
    endif;
}
