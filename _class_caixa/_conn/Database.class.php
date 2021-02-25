<?php

/**
 * Classe responsável pelo gerenciamento dos bancos de dados
 *
 * @author Marques Junior
 */
class Database {

    public $Host = HOSTFAC;
    public $User = USERFAC;
    public $Pass = PASSFAC;
    private $Dbsa = "federalsistemas_federal20";
    private $MyConn = "";

    //FAZ A CONEXÃO COM O BANCO DE DADOS
    private function Conn() {
        $this->MyConn = mysqli_connect($this->Host, $this->User, $this->Pass, $this->Dbsa, '3306');
        return $this->MyConn;
    }

    //FAZ O CADASTRO
    public function Create($Tabela, array $Dados) {
        $Campos = implode(',', array_keys($Dados));
        $Fields = "'" . implode("','", array_values($Dados)) . "'";
        $SqlCreate = "INSERT INTO {$Tabela} ({$Campos}) VALUES({$Fields})";
        $QuerySqlCreate = mysqli_query($this->Conn(), $SqlCreate);
        if ($QuerySqlCreate) :

            //GRAVAR AUDITORIA
            $os = array("pedido", "atendente", "chip", "itens_pedido", "itens_pedido_chip", "itens_ticket", "saque", "ticket", "user");

            if ($Tabela == 'pedido'):
                $indice_auditoria = 'pedido_id';
            elseif ($Tabela == 'atendente'):
                $indice_auditoria = 'atendente_id';
            elseif ($Tabela == 'chip'):
                $indice_auditoria = 'chip_id';
            elseif ($Tabela == 'itens_pedido'):
                $indice_auditoria = 'itens_pedido_id';
            elseif ($Tabela == 'itens_pedido_chip'):
                $indice_auditoria = 'itens_pedido_chip_id';
            elseif ($Tabela == 'itens_ticket'):
                $indice_auditoria = 'itens_ticket_id';
            elseif ($Tabela == 'saque'):
                $indice_auditoria = 'saque_id';
            elseif ($Tabela == 'ticket'):
                $indice_auditoria = 'ticket_id';
            elseif ($Tabela == 'user'):
                $indice_auditoria = 'user_id';
            endif;

            if (count($Dados) > '0' && in_array($Tabela, $os)):
                foreach ($Dados as $IndiceCreate => $ValorCreate):
                    $descricao_auditoria .= $IndiceCreate . ' = ' . $ValorCreate . ' | ';
                endforeach;

                $read_ultimo_reg = mysqli_query($this->Conn(), "SELECT {$indice_auditoria} FROM {$Tabela} ORDER BY {$indice_auditoria} DESC LIMIT 1");
                if (mysqli_num_rows($read_ultimo_reg) > '0'):
                    foreach ($read_ultimo_reg as $read_ultimo_reg_view):
                        $id_tabela = $read_ultimo_reg_view[$indice_auditoria];
                    endforeach;
                endif;

                $sql_auditoria = "INSERT INTO auditoria_mmn(auditoria_tabela, auditoria_operacao, auditoria_id_user, auditoria_texto_novo, auditoria_data_hora, auditoria_id_tabela) VALUES('" . $Tabela . "', 'create', '" . $_SESSION[VSESSION]['user_id'] . "', '" . $descricao_auditoria . "', '" . date('Y-m-d H:i:s') . "', '" . $id_tabela . "')";
                $query_auditoria = mysqli_query($this->Conn(), $sql_auditoria);
            endif;

            return true;
        else:
            //echo $SqlCreate;
            return false;
        endif;
    }

    //FAZ LEITURA
    public function Read($Tabela, $Condicao = NULL) {
        $SqlRead = "SELECT * FROM {$Tabela} {$Condicao}";
        $QueryRead = mysqli_query($this->Conn(), $SqlRead);
        if ($QueryRead):
            return $QueryRead;
        else:
            //echo $SqlRead;
            return false;
        endif;
    }

    //FAZ LEITURA COM INNER JOIN
    public function ReadComposta($Query) {
        $QueryRead = mysqli_query($this->Conn(), $Query);
        if ($QueryRead):
            return $QueryRead;
        else:
            //echo $Query;
            return false;
        endif;
    }

    //FAZ EDIÇÃO
    public function Update($Tabela, array $Dados, $Condicao = NULL) {
        foreach ($Dados as $Keys => $ValuesKeys) {
            $CamposFields[] = "$Keys = '$ValuesKeys'";
            $gera_in .= $Keys . ', ';
            $descricao_auditoria .= $Keys . ' = ' . $ValuesKeys . ' | ';
        }

        $CamposFields = implode(", ", $CamposFields);
        $SqlUpdate = "UPDATE {$Tabela} SET {$CamposFields} {$Condicao}";

        $osup = array("pedido", "atendente", "chip", "itens_pedido", "itens_pedido_chip", "itens_ticket", "saque", "ticket", "user");

        if ($Tabela == 'pedido'):
            $indice_auditoria = 'pedido_id';
        elseif ($Tabela == 'atendente'):
            $indice_auditoria = 'atendente_id';
        elseif ($Tabela == 'chip'):
            $indice_auditoria = 'chip_id';
        elseif ($Tabela == 'itens_pedido'):
            $indice_auditoria = 'itens_pedido_id';
        elseif ($Tabela == 'itens_pedido_chip'):
            $indice_auditoria = 'itens_pedido_chip_id';
        elseif ($Tabela == 'itens_ticket'):
            $indice_auditoria = 'itens_ticket_id';
        elseif ($Tabela == 'ticket'):
            $indice_auditoria = 'ticket_id';
        elseif ($Tabela == 'user'):
            $indice_auditoria = 'user_id';
        endif;

        if (in_array($Tabela, $osup)):

            $gera_indice = $gera_in . $indice_auditoria;

            $read_ultimo_reg = mysqli_query($this->Conn(), "SELECT {$gera_indice} FROM {$Tabela} {$Condicao} LIMIT 1");
            if (mysqli_num_rows($read_ultimo_reg) > '0'):
                foreach ($read_ultimo_reg as $read_ultimo_reg_view):
                    $id_tabela = $read_ultimo_reg_view[$indice_auditoria];
                    foreach ($read_ultimo_reg_view as $key_update => $value_update):
                        $descricao_auditoria_antiga .= $key_update . ' = ' . $value_update . ' | ';
                    endforeach;
                endforeach;
            endif;
        endif;

        $QueryUpdate = mysqli_query($this->Conn(), $SqlUpdate);



        if ($QueryUpdate):
            if (in_array($Tabela, $osup)):
                $sql_auditoria = "INSERT INTO auditoria_mmn(auditoria_tabela, auditoria_operacao, auditoria_id_user, auditoria_texto_novo, auditoria_texto_antigo, auditoria_data_hora, auditoria_id_tabela) VALUES('" . $Tabela . "', 'update', '" . $_SESSION[VSESSION]['user_id'] . "', '" . $descricao_auditoria . "', '" . $descricao_auditoria_antiga . "', '" . date('Y-m-d H:i:s') . "', '" . $id_tabela . "')";
                $query_auditoria = mysqli_query($this->Conn(), $sql_auditoria);
            endif;

            return true;
        else:
            //echo $SqlUpdate;
            return false;
        endif;
    }

    //FAZ DELETE
    public function Delete($Tabela, $Condicao = NULL) {
        $SqlDelete = "DELETE FROM {$Tabela} {$Condicao}";

        if ($Tabela == 'itens_pedido'):

            $query_itens_pedido = mysqli_query($this->Conn(), "SELECT * FROM {$Tabela} {$Condicao} LIMIT 1");
            if (mysqli_num_rows($query_itens_pedido) > '0'):
                foreach ($query_itens_pedido as $query_itens_pedido_view):
                    $query_produto = mysqli_query($this->Conn(), "SELECT * FROM produto WHERE produto_id = '" . $query_itens_pedido_view['itens_pedido_id_produto'] . "' LIMIT 1");
                    if (mysqli_num_rows($query_produto) > '0'):
                        foreach ($query_produto as $query_produto_view):
                            $desc_pedido_id = 'pedido_id = ' . $query_itens_pedido_view['itens_pedido_id_pedido'];
                            $desc_nome_produto = ' | produto_descricao = ' . $query_produto_view['produto_descricao'];
                            $descricao_completa_auditoria_antiga = $desc_pedido_id . $desc_nome_produto;
                        endforeach;
                    endif;
                endforeach;
            endif;

        elseif ($Tabela == 'itens_pedido_chip'):
            $query_itens_pedido = mysqli_query($this->Conn(), "SELECT * FROM {$Tabela} {$Condicao} LIMIT 1");
            if (mysqli_num_rows($query_itens_pedido) > '0'):
                foreach ($query_itens_pedido as $query_itens_pedido_view):
                    $query_produto = mysqli_query($this->Conn(), "SELECT * FROM chip WHERE chip_id = '" . $query_itens_pedido_view['itens_pedido_chip_id_chip'] . "' LIMIT 1");
                    if (mysqli_num_rows($query_produto) > '0'):
                        foreach ($query_produto as $query_produto_view):
                            $desc_pedido_id = 'pedido_id = ' . $query_itens_pedido_view['itens_pedido_chip_id_pedido'];
                            $desc_iccid = ' | chip_iccid = ' . $query_produto_view['chip_iccid'];
                            $desc_linha = ' | chip_num = ' . $query_produto_view['chip_num'];
                            $descricao_completa_auditoria_antiga = $desc_pedido_id . $desc_iccid . $desc_linha;
                        endforeach;
                    endif;
                endforeach;
            endif;
        endif;

        $QueryDelete = mysqli_query($this->Conn(), $SqlDelete);

        if ($QueryDelete):
            $osup = array("itens_pedido", "itens_pedido_chip");
            if (in_array($Tabela, $osup)):
                $sql_auditoria = "INSERT INTO auditoria_mmn(auditoria_tabela, auditoria_operacao, auditoria_id_user, auditoria_texto_novo, auditoria_texto_antigo, auditoria_data_hora, auditoria_id_tabela) VALUES('" . $Tabela . "', 'delete', '" . $_SESSION[VSESSION]['user_id'] . "', 'deletado', '" . $descricao_completa_auditoria_antiga . "', '" . date('Y-m-d H:i:s') . "', '0')";
                $query_auditoria = mysqli_query($this->Conn(), $sql_auditoria);
            endif;

            return true;
        else:
            //echo $SqlDelete;
            return false;
        endif;
    }

    //BUSCA QUANTIDADE DE LINHAS
    public function NumQuery($Query) {
        $CountQuery = mysqli_num_rows($Query);
        return $CountQuery;
    }

}
