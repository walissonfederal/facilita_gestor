<?php
namespace Simonetti\Boleto\Util;


class Numero
{
    /**
     * @return string Número formatado
     */
    public static function formataNumero($numero, $loop, $insert, $tipo = "geral")
    {
        if ($tipo == "geral") {
            $numero = str_replace(",", "", $numero);
            while (strlen($numero) < $loop) {
                $numero = $insert . $numero;
            }
        }
        if ($tipo == "valor") {
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
} 