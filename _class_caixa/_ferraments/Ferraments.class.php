<?php

/**
 * Classe responsável pelas ferramentas do nosso framework
 *
 * @author Marques Junior
 */
class Ferraments extends Database {

    public function GetDados($Table, $Indice, $IndicePrimario, $Value) {
        $DB = new Database();

        $read_getdados = $DB->ReadComposta("SELECT {$Indice} FROM {$Table} WHERE {$IndicePrimario} = '" . $Value . "' LIMIT 1");
        if ($DB->NumQuery($read_getdados) > '0'):
            foreach ($read_getdados as $read_getdados_view):
                return $read_getdados_view[$Indice];
            endforeach;
        endif;
    }

    public function DiffDatas($DataInicial, $DataFinal) {
        $Diferenca = strtotime($DataFinal) - strtotime($DataInicial);
        $Dias = floor($Diferenca / (60 * 60 * 24));
        return $Dias;
    }

    public function sendMail($assunto, $mensagem, $remetente, $nomeRemetente, $destino, $nomeDestino, $reply = NULL, $replyNome = NULL, $anexo = NULL, $nomeAnexo = NULL, $anexo2 = NULL, $nomeAnexo2 = NULL) {

        require_once('mail/class.phpmailer.php'); //Include pasta/classe do PHPMailer

        $mail = new PHPMailer(); //INICIA A CLASSE
        $mail->IsSMTP(); //Habilita envio SMPT
        $mail->SMTPAuth = true; //Ativa email autenticado
        $mail->SMTPSecure = 'ssl'; // SSL REQUERIDO pelo GMail
        $mail->IsHTML(true);

        $mail->Host = 'smtplw.com.br'; //Servidor de envio
        $mail->Port = '465'; //Porta de envio
        $mail->Username = 'federalsistemas'; //email para smtp autenticado
        $mail->Password = 'FeD468579!?'; //seleciona a porta de envio

        $mail->From = utf8_decode($remetente); //remtente
        $mail->FromName = utf8_decode($nomeRemetente); //remtetene nome

        if ($reply != NULL) {
            $mail->AddReplyTo(utf8_decode($reply), utf8_decode($replyNome));
        }
        if ($anexo != NULL) {
            $mail->AddAttachment($anexo, $nomeAnexo);
        }
        if ($anexo2 != NULL) {
            $mail->AddAttachment($anexo2, $nomeAnexo2);
        }

        $mail->Subject = utf8_decode($assunto); //assunto
        $mail->Body = utf8_decode($mensagem); //mensagem
        $mail->AddAddress(utf8_decode($destino), utf8_decode($nomeDestino)); //email e nome do destino

        if ($mail->Send()) {
            return true;
        } else {
            return false;
        }
    }

    public function CarregaSaldo($IdUser) {
        $DB = new Database();

        $valor_credito = '0';
        $valor_debito = '0';
        $read_saldo = $DB->ReadComposta("SELECT extrato_valor, extrato_tipo FROM extrato WHERE extrato_id_user = '" . $IdUser . "'");
        if ($DB->NumQuery($read_saldo) > '0') :
            foreach ($read_saldo as $read_saldo_view) :
                if ($read_saldo_view['extrato_tipo'] == 'C') :
                    $valor_credito += $read_saldo_view['extrato_valor'];
                else:
                    $valor_debito += $read_saldo_view['extrato_valor'];
                endif;
            endforeach;
            $saldo_total = $valor_credito - $valor_debito;
        else:
            $saldo_total = '0';
        endif;
        return $saldo_total;
    }

    public function GanhosHoje($IdUser) {
        $DB = new Database();

        $valor_total = '0';

        $read_ganhos = $DB->ReadComposta("SELECT extrato_valor, extrato_tipo FROM extrato WHERE extrato_id_user = '" . $IdUser . "' AND extrato_data = '" . date('Y-m-d') . "' AND extrato_tipo = 'C'");
        if ($DB->NumQuery($read_ganhos) > '0'):
            foreach ($read_ganhos as $read_ganhos_view):
                $valor_total += $read_ganhos_view['extrato_valor'];
            endforeach;
        endif;
        return $valor_total;
    }

    public function GanhosOntem($IdUser) {
        $DB = new Database();

        $valor_total = '0';

        $data_ontem = date('Y-m-d', strtotime('-1 days'));

        $read_ganhos = $DB->ReadComposta("SELECT extrato_valor, extrato_tipo FROM extrato WHERE extrato_id_user = '" . $IdUser . "' AND extrato_data = '" . $data_ontem . "' AND extrato_tipo = 'C'");
        if ($DB->NumQuery($read_ganhos) > '0'):
            foreach ($read_ganhos as $read_ganhos_view):
                $valor_total += $read_ganhos_view['extrato_valor'];
            endforeach;
        endif;
        return $valor_total;
    }

    public function SaldoBloqueado($IdUser) {
        $DB = new Database();

        $valor_total = '0';

        $read_saque = $DB->ReadComposta("SELECT SUM(saque_valor) AS Valor FROM saque WHERE saque_id_user = '" . $IdUser . "' AND saque_status = '0'");
        if ($DB->NumQuery($read_saque) > '0'):
            foreach ($read_saque as $read_saque_view):
                $valor_total = $read_saque_view['Valor'];
            endforeach;
        else:
            $valor_total = '0';
        endif;
        return $valor_total;
    }

    public function GanhosMesAtual($IdUser) {
        $DB = new Database();

        $valor_total = '0';

        $mes_atual = date('Y-m-');

        $read_ganhos = $DB->ReadComposta("SELECT extrato_valor, extrato_tipo FROM extrato WHERE extrato_id_user = '" . $IdUser . "' AND extrato_data LIKE '%" . $mes_atual . "%' AND extrato_tipo = 'C'");
        if ($DB->NumQuery($read_ganhos) > '0'):
            foreach ($read_ganhos as $read_ganhos_view):
                $valor_total += $read_ganhos_view['extrato_valor'];
            endforeach;
        endif;
        return $valor_total;
    }

    public function GanhosMesPassado($IdUser) {
        $DB = new Database();

        $valor_total = '0';

        $mes_passado = date('Y-m-', strtotime('-1 month'));

        $read_ganhos = $DB->ReadComposta("SELECT extrato_valor, extrato_tipo FROM extrato WHERE extrato_id_user = '" . $IdUser . "' AND extrato_data LIKE '%" . $mes_passado . "%' AND extrato_tipo = 'C'");
        if ($DB->NumQuery($read_ganhos) > '0'):
            foreach ($read_ganhos as $read_ganhos_view):
                $valor_total += $read_ganhos_view['extrato_valor'];
            endforeach;
        endif;
        return $valor_total;
    }

    public function RedePrimeiroNivel($IdUser) {
        $DB = new Database();

        $read_rede = $DB->ReadComposta("SELECT user_id FROM user WHERE user_id_pai = '" . $IdUser . "'");
        if ($DB->NumQuery($read_rede) > '0'):
            return $DB->NumQuery($read_rede);
        else:
            return '0';
        endif;
    }

    public function RedeHoje($IdUser) {
        $DB = new Database();

        $read_primeiro_nivel = $DB->ReadComposta("SELECT user_id FROM user WHERE user_id_pai = '" . $IdUser . "' AND user_data = '" . date('Y-m-d') . "'");
        if ($DB->NumQuery($read_primeiro_nivel) > '0'):
            $count_primeiro_nivel = $DB->NumQuery($read_primeiro_nivel);
            foreach ($read_primeiro_nivel as $read_primeiro_nivel_view):
                $read_segundo_nivel = $DB->ReadComposta("SELECT user_id FROM user WHERE user_id_pai = '" . $read_primeiro_nivel_view['user_id'] . "' AND user_data = '" . date('Y-m-d') . "'");
                if ($DB->NumQuery($read_segundo_nivel) > '0'):
                    $count_segundo_nivel = $DB->NumQuery($read_segundo_nivel);
                    foreach ($read_segundo_nivel as $read_segundo_nivel_view):
                        $read_terceiro = $DB->ReadComposta("SELECT user_id FROM user WHERE user_id_pai = '" . $read_segundo_nivel_view['user_id'] . "' AND user_data = '" . date('Y-m-d') . "'");
                        if ($DB->NumQuery($read_terceiro) > '0'):
                            $count_terceiro_nivel = $DB->NumQuery($read_segundo_nivel);
                            foreach ($read_terceiro as $read_terceiro_view):
                                $read_quarto = $DB->ReadComposta("SELECT user_id FROM user WHERE user_id_pai = '" . $read_terceiro_view['user_id'] . "' AND user_data = '" . date('Y-m-d') . "'");
                                if ($DB->NumQuery($read_quarto) > '0'):
                                    $count_quarto_nivel = $DB->NumQuery($read_quarto);
                                endif;
                            endforeach;
                        endif;
                    endforeach;
                endif;
            endforeach;
        endif;

        $count_dados = $count_primeiro_nivel + $count_segundo_nivel + $count_terceiro_nivel + $count_quarto_nivel;
        if ($count_dados):
            return $count_dados;
        else:
            return '0';
        endif;
    }

    public function RedeOntem($IdUser) {
        $DB = new Database();

        $data_ontem = date('Y-m-d', strtotime('-1 days'));

        $read_primeiro_nivel = $DB->ReadComposta("SELECT user_id FROM user WHERE user_id_pai = '" . $IdUser . "' AND user_data = '" . $data_ontem . "'");
        if ($DB->NumQuery($read_primeiro_nivel) > '0'):
            $count_primeiro_nivel = $DB->NumQuery($read_primeiro_nivel);
            foreach ($read_primeiro_nivel as $read_primeiro_nivel_view):
                $read_segundo_nivel = $DB->ReadComposta("SELECT user_id FROM user WHERE user_id_pai = '" . $read_primeiro_nivel_view['user_id'] . "' AND user_data = '" . $data_ontem . "'");
                if ($DB->NumQuery($read_segundo_nivel) > '0'):
                    $count_segundo_nivel = $DB->NumQuery($read_segundo_nivel);
                    foreach ($read_segundo_nivel as $read_segundo_nivel_view):
                        $read_terceiro = $DB->ReadComposta("SELECT user_id FROM user WHERE user_id_pai = '" . $read_segundo_nivel_view['user_id'] . "' AND user_data = '" . $data_ontem . "'");
                        if ($DB->NumQuery($read_terceiro) > '0'):
                            $count_terceiro_nivel = $DB->NumQuery($read_segundo_nivel);
                            foreach ($read_terceiro as $read_terceiro_view):
                                $read_quarto = $DB->ReadComposta("SELECT user_id FROM user WHERE user_id_pai = '" . $read_terceiro_view['user_id'] . "' AND user_data = '" . $data_ontem . "'");
                                if ($DB->NumQuery($read_quarto) > '0'):
                                    $count_quarto_nivel = $DB->NumQuery($read_quarto);
                                endif;
                            endforeach;
                        endif;
                    endforeach;
                endif;
            endforeach;
        endif;

        $count_dados = $count_primeiro_nivel + $count_segundo_nivel + $count_terceiro_nivel + $count_quarto_nivel;
        if ($count_dados):
            return $count_dados;
        else:
            return '0';
        endif;
    }

    public function RedeTotal($IdUser) {
        $DB = new Database();

        $read_primeiro_nivel = $DB->ReadComposta("SELECT user_id FROM user WHERE user_id_pai = '" . $IdUser . "'");
        if ($DB->NumQuery($read_primeiro_nivel) > '0'):
            $count_primeiro_nivel = $DB->NumQuery($read_primeiro_nivel);
            foreach ($read_primeiro_nivel as $read_primeiro_nivel_view):
                $read_segundo_nivel = $DB->ReadComposta("SELECT user_id FROM user WHERE user_id_pai = '" . $read_primeiro_nivel_view['user_id'] . "'");
                if ($DB->NumQuery($read_segundo_nivel) > '0'):
                    $count_segundo_nivel = $DB->NumQuery($read_segundo_nivel);
                    foreach ($read_segundo_nivel as $read_segundo_nivel_view):
                        $read_terceiro = $DB->ReadComposta("SELECT user_id FROM user WHERE user_id_pai = '" . $read_segundo_nivel_view['user_id'] . "'");
                        if ($DB->NumQuery($read_terceiro) > '0'):
                            $count_terceiro_nivel = $DB->NumQuery($read_segundo_nivel);
                            foreach ($read_terceiro as $read_terceiro_view):
                                $read_quarto = $DB->ReadComposta("SELECT user_id FROM user WHERE user_id_pai = '" . $read_terceiro_view['user_id'] . "'");
                                if ($DB->NumQuery($read_quarto) > '0'):
                                    $count_quarto_nivel = $DB->NumQuery($read_quarto);
                                endif;
                            endforeach;
                        endif;
                    endforeach;
                endif;
            endforeach;
        endif;

        $count_dados = $count_primeiro_nivel + $count_segundo_nivel + $count_terceiro_nivel + $count_quarto_nivel;
        if ($count_dados):
            return $count_dados;
        else:
            return '0';
        endif;
    }

    //FAZ A PAGINAÇÃO DE RESULTADOS
    public function Paginator($Tabela, $Condicao, $Maximo, $Link, $Pag, $Width = NULL, $MaxLinks = 2) {
        $DB = new Database();

        $ReadPaginator = $DB->Read($Tabela, "{$Condicao}");
        $Total = $DB->NumQuery($ReadPaginator);

        if ($Total > $Maximo):
            $Paginas = ceil($Total / $Maximo);
            echo '<ul class="pagination">';
            echo '<li class="paginate_button page-item"><a href="' . $Link . '1" class="page-link">Primeira Pagina</a></li>';
            for ($i = $Pag - $MaxLinks; $i <= $Pag - 1; $i++):
                if ($i >= 1):
                    echo '<li class="paginate_button page-item"><a href="' . $Link . $i . '" class="page-link">' . $i . '</a></li>';
                endif;
            endfor;
            echo '<li class="paginate_button page-item active"><a href="#" class="page-link">' . $Pag . '</a></li>';
            for ($i = $Pag + 1; $i <= $Pag + $MaxLinks; $i++):
                if ($i <= $Paginas):
                    echo '<li class="paginate_button page-item"><a href="' . $Link . $i . '" class="page-link">' . $i . '</a></li>';
                endif;
            endfor;
            echo '<li class="paginate_button page-item"><a href="' . $Link . $Paginas . '" class="page-link">Ultima Pagina</a></li>';
            echo '</ul>';
        endif;
    }

    public function GeraComissao($IdUser, $Valor) {
        $DB = new Database();

        $COMISSAOPRIMEIRONIVEL = '10';
        $COMISSAOSEGUNDONIVEL = '5';
        $COMISSAOTERCEIRONIVEL = '3';
        $COMISSAOQUARTONIVEL = '2';

        $read_user_pagamento = $DB->ReadComposta("SELECT user_id, user_id_pai FROM user WHERE user_id = '" . $IdUser . "' LIMIT 1");
        if ($DB->NumQuery($read_user_pagamento) > '0'):
            foreach ($read_user_pagamento as $read_user_pagamento_view):

                $valor_comissao_primeiro_nivel = ($COMISSAOPRIMEIRONIVEL / 100) * $Valor;
                $valor_comissao_aplicada = (2 / 100) * $Valor;

                $read_user_primeiro_nivel = $DB->Read('user', "WHERE user_id = '" . $read_user_pagamento_view['user_id_pai'] . "' LIMIT 1");
                if ($DB->NumQuery($read_user_primeiro_nivel) > '0'):
                    foreach ($read_user_primeiro_nivel as $read_user_primeiro_nivel_view):
                        $extrato_comissao_aplicada['extrato_data_hora'] = date('Y-m-d H:i:s');
                        $extrato_comissao_aplicada['extrato_data'] = date('Y-m-d');
                        $extrato_comissao_aplicada['extrato_id_user'] = '1';
                        $extrato_comissao_aplicada['extrato_tipo'] = 'C';
                        $extrato_comissao_aplicada['extrato_valor'] = $valor_comissao_aplicada;
                        $extrato_comissao_aplicada['extrato_descricao'] = 'Comissão aplicada 4º nível';
                        $extrato_comissao_aplicada['extrato_tipologia'] = '0';

                        $DB->Create('extrato', $extrato_comissao_aplicada);

                        $extrato_comissao_primeiro_nivel['extrato_data_hora'] = date('Y-m-d H:i:s');
                        $extrato_comissao_primeiro_nivel['extrato_data'] = date('Y-m-d');
                        $extrato_comissao_primeiro_nivel['extrato_id_user'] = $read_user_primeiro_nivel_view['user_id'];
                        $extrato_comissao_primeiro_nivel['extrato_tipo'] = 'C';
                        $extrato_comissao_primeiro_nivel['extrato_valor'] = $valor_comissao_primeiro_nivel;
                        $extrato_comissao_primeiro_nivel['extrato_descricao'] = 'Comissão aplicada 1º nível';
                        $extrato_comissao_primeiro_nivel['extrato_tipologia'] = '0';

                        $DB->Create('extrato', $extrato_comissao_primeiro_nivel);

                        $valor_comissao_segundo_nivel = ($COMISSAOSEGUNDONIVEL / 100) * $Valor;
                        $read_user_segundo_nivel = $DB->Read('user', "WHERE user_id = '" . $read_user_primeiro_nivel_view['user_id_pai'] . "' LIMIT 1");
                        if ($DB->NumQuery($read_user_segundo_nivel) > '0'):
                            foreach ($read_user_segundo_nivel as $read_user_segundo_nivel_view):
                                $extrato_comissao_segundo_nivel['extrato_data_hora'] = date('Y-m-d H:i:s');
                                $extrato_comissao_segundo_nivel['extrato_data'] = date('Y-m-d');
                                $extrato_comissao_segundo_nivel['extrato_id_user'] = $read_user_segundo_nivel_view['user_id'];
                                $extrato_comissao_segundo_nivel['extrato_tipo'] = 'C';
                                $extrato_comissao_segundo_nivel['extrato_valor'] = $valor_comissao_segundo_nivel;
                                $extrato_comissao_segundo_nivel['extrato_descricao'] = 'Comissão aplicada 2º nível';
                                $extrato_comissao_segundo_nivel['extrato_tipologia'] = '0';

                                $DB->Create('extrato', $extrato_comissao_segundo_nivel);

                                $valor_comissao_terceiro_nivel = ($COMISSAOTERCEIRONIVEL / 100) * $Valor;
                                $read_user_terceiro_nivel = $DB->Read('user', "WHERE user_id = '" . $read_user_segundo_nivel_view['user_id_pai'] . "' LIMIT 1");
                                if ($DB->NumQuery($read_user_terceiro_nivel) > '0'):
                                    foreach ($read_user_terceiro_nivel as $read_user_terceiro_nivel_view):
                                        $extrato_comissao_terceiro_nivel['extrato_data_hora'] = date('Y-m-d H:i:s');
                                        $extrato_comissao_terceiro_nivel['extrato_data'] = date('Y-m-d');
                                        $extrato_comissao_terceiro_nivel['extrato_id_user'] = $read_user_terceiro_nivel_view['user_id'];
                                        $extrato_comissao_terceiro_nivel['extrato_tipo'] = 'C';
                                        $extrato_comissao_terceiro_nivel['extrato_valor'] = $valor_comissao_terceiro_nivel;
                                        $extrato_comissao_terceiro_nivel['extrato_descricao'] = 'Comissão aplicada 3º nível';
                                        $extrato_comissao_terceiro_nivel['extrato_tipologia'] = '0';

                                        $DB->Create('extrato', $extrato_comissao_terceiro_nivel);

                                        $valor_comissao_quarto_nivel = ($COMISSAOQUARTONIVEL / 100) * $Valor;
                                        $read_user_quarto_nivel = $DB->Read('user', "WHERE user_id = '" . $read_user_terceiro_nivel_view['user_id_pai'] . "' LIMIT 1");
                                        if ($DB->NumQuery($read_user_quarto_nivel) > '0'):
                                            foreach ($read_user_quarto_nivel as $read_user_quarto_nivel_view):
                                                $extrato_comissao_quarto_nivel['extrato_data_hora'] = date('Y-m-d H:i:s');
                                                $extrato_comissao_quarto_nivel['extrato_data'] = date('Y-m-d');
                                                $extrato_comissao_quarto_nivel['extrato_id_user'] = $read_user_quarto_nivel_view['user_id'];
                                                $extrato_comissao_quarto_nivel['extrato_tipo'] = 'C';
                                                $extrato_comissao_quarto_nivel['extrato_valor'] = $valor_comissao_quarto_nivel;
                                                $extrato_comissao_quarto_nivel['extrato_descricao'] = 'Comissão aplicada 4º nível';
                                                $extrato_comissao_quarto_nivel['extrato_tipologia'] = '0';

                                                $DB->Create('extrato', $extrato_comissao_quarto_nivel);
                                            endforeach;
                                        endif;
                                    endforeach;
                                endif;
                            endforeach;
                        endif;
                    endforeach;
                endif;
            endforeach;
        endif;
    }

    private function formata_numero($numero, $loop, $insert, $tipo = "geral") {
        if ($tipo == "geral") {
            $numero = str_replace(",", "", $numero);
            while (strlen($numero) < $loop) {
                $numero = $insert . $numero;
            }
        }
        if ($tipo == "valor") {
            /*
              retira as virgulas
              formata o numero
              preenche com zeros
             */
            $numero = str_replace(",", "", $numero);
            while (strlen($numero) < $loop) {
                $numero = $insert . $numero;
            }
        }
        if ($tipo == "convenio") {
            while (strlen($numero) < $loop) {
                $numero = $numero . $insert;
            }
        }
        return $numero;
    }

    public function GeraNossoNumero($nosso_numero) {
        $return = $this->formata_numero('1', 1, 0) . $this->formata_numero('4', 1, 0) . $this->formata_numero('000', 3, 0) . $this->formata_numero('000', 3, 0) . $this->formata_numero($nosso_numero, 9, 0);
        return $return;
    }

    private function digitoVerificador_nossonumero($numero) {
        $resto2 = $this->modulo_11($numero, 9, 1);
        $digito = 11 - $resto2;
        if ($digito == 10 || $digito == 11) {
            $dv = 0;
        } else {
            $dv = $digito;
        }
        return $dv;
    }

    private function modulo_11($num, $base = 9, $r = 0) {
        $soma = 0;
        $fator = 2;

        /* Separacao dos numeros */
        for ($i = strlen($num); $i > 0; $i--) {
            // pega cada numero isoladamente
            $numeros[$i] = substr($num, $i - 1, 1);
            // Efetua multiplicacao do numero pelo falor
            $parcial[$i] = $numeros[$i] * $fator;
            // Soma dos digitos
            $soma += $parcial[$i];
            if ($fator == $base) {
                // restaura fator de multiplicacao para 2 
                $fator = 1;
            }
            $fator++;
        }

        /* Calculo do modulo 11 */
        if ($r == 0) {
            $soma *= 10;
            $digito = $soma % 11;
            if ($digito == 10) {
                $digito = 0;
            }
            return $digito;
        } elseif ($r == 1) {
            $resto = $soma % 11;
            return $resto;
        }
    }

    public function GeraNossoNumeroCompleto($nosso_numero) {
        $var_nosso_numero = $this->formata_numero('1', 1, 0) . $this->formata_numero('4', 1, 0) . $this->formata_numero('000', 3, 0) . $this->formata_numero('000', 3, 0) . $this->formata_numero($nosso_numero, 9, 0);
        $return = $var_nosso_numero . $this->digitoVerificador_nossonumero($var_nosso_numero);
        return $return;
    }

    public function NotificationPanel($Title, $Description, $Status, $IdUser, $DateTime, $Type, $IdTicket = 0) {
        $DB = new Database();
        $notification_form['notificacao_titulo'] = $Title;
        $notification_form['notificacao_descricao'] = $Description;
        $notification_form['notificacao_status'] = $Status;
        $notification_form['notificacao_id_user'] = $IdUser;
        $notification_form['notificacao_data_hora'] = $DateTime;
        $notification_form['notificacao_tipo'] = $Type;
        $notification_form['notificacao_id_ticket'] = $IdTicket;
        if ($DB->Create('notificacao', $notification_form)):
            return true;
        else:
            return false;
        endif;
    }

    public function NotificationPanelUser($Title, $Description, $Status, $IdUser, $DateTime, $Type, $IdTicket = 0) {
        $DB = new Database();
        $notification_form['notificacao_titulo'] = $Title;
        $notification_form['notificacao_descricao'] = $Description;
        $notification_form['notificacao_status'] = $Status;
        $notification_form['notificacao_id_user'] = $IdUser;
        $notification_form['notificacao_data_hora'] = $DateTime;
        $notification_form['notificacao_tipo'] = $Type;
        $notification_form['notificacao_id_ticket'] = $IdTicket;
        if ($DB->Create('notificacao_user', $notification_form)):
            return true;
        else:
            return false;
        endif;
    }

    public function LmWord($Text, $Limit) {
        if (strlen($Text) <= $Limit):
            return $Text;
        else:
            return substr($Text, 0, $Limit) . '...';
        endif;
    }

    public function CountTicketAberto($IdUser) {
        $DB = new Database();

        $read_ticket = $DB->ReadComposta("SELECT ticket_id FROM ticket WHERE ticket_id_responsavel = '" . $IdUser . "' AND ticket_status = '0'");
        if ($DB->NumQuery($read_ticket) > '0'):
            return $DB->NumQuery($read_ticket);
        else:
            return '0';
        endif;
    }

    public function CountTicketFechado($IdUser) {
        $DB = new Database();

        $read_ticket = $DB->ReadComposta("SELECT ticket_id FROM ticket WHERE ticket_id_responsavel = '" . $IdUser . "' AND ticket_status = '1'");
        if ($DB->NumQuery($read_ticket) > '0'):
            return $DB->NumQuery($read_ticket);
        else:
            return '0';
        endif;
    }

    public function CountTicketFechadoHoje($IdUser) {
        $DB = new Database();

        $read_ticket = $DB->ReadComposta("SELECT ticket_id FROM ticket WHERE ticket_id_responsavel = '" . $IdUser . "' AND ticket_status = '1' AND ticket_data_final = '" . date('Y-m-d') . "'");
        if ($DB->NumQuery($read_ticket) > '0'):
            return $DB->NumQuery($read_ticket);
        else:
            return '0';
        endif;
    }

    public function GetEmpresa($Indice) {
        $DB = new Database();

        $read_empresa = $DB->ReadComposta("SELECT {$Indice} FROM empresa WHERE empresa_id = '1' LIMIT 1");
        if ($DB->NumQuery($read_empresa) > '0'):
            foreach ($read_empresa as $read_empresa_view):
                return $read_empresa_view[$Indice];
            endforeach;
        else:
            return false;
        endif;
    }

    public function GetDocumentacao($IdUser) {
        $DB = new Database();
        $read_user = $DB->ReadComposta("SELECT user_doc_aprovado FROM user WHERE user_doc_aprovado = '1' AND user_id = '".$IdUser."'");
		if($DB->NumQuery($read_user) > '0'):
			return true;
		else:
			return false;
		endif;
    }
    
    public function SumMensalidade($IdUser, $Referencia) {
        $DB = new Database();

        $read_mensalidade = $DB->ReadComposta("SELECT SUM(faturamento_valor) AS faturamento_valor FROM faturamento WHERE faturamento_id_user = '".$IdUser."' AND faturamento_referencia = '".$Referencia."' LIMIT 1");
        if ($DB->NumQuery($read_mensalidade) > '0'):
            foreach ($read_mensalidade as $read_mensalidade_view):
                return $read_mensalidade_view['faturamento_valor'];
            endforeach;
        else:
            return '0';
        endif;
    }
    
    public function GetPlaca($Placa){
        $DB = new Database();
        
        if($Placa == ''):
            return false;
        else:
            $read_placa = $DB->Read('api_veiculo', "WHERE api_veiculo_placa = '".$Placa."' LIMIT 1");
            if($DB->NumQuery($read_placa) > '0'):
                foreach($read_placa as $read_placa_view):
                    return $read_placa_view;
                endforeach;
            else:
                $link = "http://www.placaapi.com/api/reg.asmx/CheckBrazil?RegistrationNumber=".$Placa."&username=marquesjunior";
                $xml = simplexml_load_file($link);
                $json_decode = json_decode($xml->vehicleJson, true);

                if($json_decode['Description'] != ''):
                    $placa_form['api_veiculo_placa']        = $Placa;
                    $placa_form['api_veiculo_descricao']    = $json_decode['Description'];
                    $placa_form['api_veiculo_ano']          = $json_decode['RegistrationYear'];
                    $placa_form['api_veiculo_fabricante']   = $json_decode['CarMake']['CurrentTextValue'];
                    $placa_form['api_veiculo_modelo']       = $json_decode['CarModel']['CurrentTextValue'];
                    $placa_form['api_veiculo_local']        = $json_decode['Location'];
                    $placa_form['api_veiculo_chassi']       = $json_decode['Vin'];
                    $placa_form['api_veiculo_combustivel']  = $json_decode['Fuel'];
                    $placa_form['api_veiculo_cor']          = $json_decode['Colour'];
                    $placa_form['api_veiculo_potencia']     = $json_decode['Power'];
                    $placa_form['api_veiculo_cilindrada']   = $json_decode['EngineCC'];
                    $placa_form['api_veiculo_tipo']         = $json_decode['Type'];
                    $placa_form['api_veiculo_assentos']     = $json_decode['Seats'];
                    $placa_form['api_veiculo_tracao']       = $json_decode['MaxTraction'];
                    $placa_form['api_veiculo_img']          = $json_decode['ImageUrl'];
                    $DB->Create('api_veiculo', $placa_form);
                    return $placa_form;
                else:
                    return false;
                endif;
            endif;     
        endif;   
    }

}
