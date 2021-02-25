<?php

/**
 * Classe responsável pela validação de diversos documentos
 *
 * @author Marques Junior
 */

class Validator {
    
    
    public function CPF($Cpf) {
        $CPFDIG = preg_replace('/[^0-9]/', '', $Cpf);

        if (strlen($CPFDIG) != 11):
            return false;
        endif;

        $digitoA = 0;
        $digitoB = 0;

        for ($i = 0, $x = 10; $i <= 8; $i++, $x--) {
            $digitoA += $CPFDIG[$i] * $x;
        }

        for ($i = 0, $x = 11; $i <= 9; $i++, $x--) {
            if (str_repeat($i, 11) == $CPFDIG) {
                return false;
            }
            $digitoB += $CPFDIG[$i] * $x;
        }

        $somaA = (($digitoA % 11) < 2 ) ? 0 : 11 - ($digitoA % 11);
        $somaB = (($digitoB % 11) < 2 ) ? 0 : 11 - ($digitoB % 11);

        if ($somaA != $CPFDIG[9] || $somaB != $CPFDIG[10]) {
            return false;
        } else {
            return true;
        }
    }
    
    public function CNPJ($Cnpj) {
        $CNPJDIG = (string) $Cnpj;
        $CNPJDIG = preg_replace('/[^0-9]/', '', $CNPJDIG);

        if (strlen($CNPJDIG) != 14):
            return false;
        endif;

        $A = 0;
        $B = 0;

        for ($i = 0, $c = 5; $i <= 11; $i++, $c--):
            $c = ($c == 1 ? 9 : $c);
            $A += $CNPJDIG[$i] * $c;
        endfor;

        for ($i = 0, $c = 6; $i <= 12; $i++, $c--):
            if (str_repeat($i, 14) == $CNPJDIG):
                return false;
            endif;
            $c = ($c == 1 ? 9 : $c);
            $B += $CNPJDIG[$i] * $c;
        endfor;

        $somaA = (($A % 11) < 2) ? 0 : 11 - ($A % 11);
        $somaB = (($B % 11) < 2) ? 0 : 11 - ($B % 11);

        if (strlen($CNPJDIG) != 14):
            return false;
        elseif ($somaA != $CNPJDIG[12] || $somaB != $CNPJDIG[13]):
            return false;
        else:
            return true;
        endif;
    }
    
    private function mod($dividendo, $divisor) {
        return round($dividendo - (floor($dividendo / $divisor) * $divisor));
    }
    
    public function GeraCPF($mascara = "1") {
        $n1 = rand(0, 9);
        $n2 = rand(0, 9);
        $n3 = rand(0, 9);
        $n4 = rand(0, 9);
        $n5 = rand(0, 9);
        $n6 = rand(0, 9);
        $n7 = rand(0, 9);
        $n8 = rand(0, 9);
        $n9 = rand(0, 9);
        $d1 = $n9 * 2 + $n8 * 3 + $n7 * 4 + $n6 * 5 + $n5 * 6 + $n4 * 7 + $n3 * 8 + $n2 * 9 + $n1 * 10;
        $d1 = 11 - ($this->mod($d1, 11) );
        if ($d1 >= 10) {
            $d1 = 0;
        }
        $d2 = $d1 * 2 + $n9 * 3 + $n8 * 4 + $n7 * 5 + $n6 * 6 + $n5 * 7 + $n4 * 8 + $n3 * 9 + $n2 * 10 + $n1 * 11;
        $d2 = 11 - ($this->mod($d2, 11) );
        if ($d2 >= 10) {
            $d2 = 0;
        }
        $retorno = '';
        if ($mascara == 1) {
            $retorno = '' . $n1 . $n2 . $n3 . "." . $n4 . $n5 . $n6 . "." . $n7 . $n8 . $n9 . "-" . $d1 . $d2;
        } else {
            $retorno = '' . $n1 . $n2 . $n3 . $n4 . $n5 . $n6 . $n7 . $n8 . $n9 . $d1 . $d2;
        }
        return $retorno;
    }
    
    public function GeraCNPJ($mascara = "1") {
        $n1 = rand(0, 9);
        $n2 = rand(0, 9);
        $n3 = rand(0, 9);
        $n4 = rand(0, 9);
        $n5 = rand(0, 9);
        $n6 = rand(0, 9);
        $n7 = rand(0, 9);
        $n8 = rand(0, 9);
        $n9 = 0;
        $n10 = 0;
        $n11 = 0;
        $n12 = 1;
        $d1 = $n12 * 2 + $n11 * 3 + $n10 * 4 + $n9 * 5 + $n8 * 6 + $n7 * 7 + $n6 * 8 + $n5 * 9 + $n4 * 2 + $n3 * 3 + $n2 * 4 + $n1 * 5;
        $d1 = 11 - ($this->mod($d1, 11) );
        if ($d1 >= 10) {
            $d1 = 0;
        }
        $d2 = $d1 * 2 + $n12 * 3 + $n11 * 4 + $n10 * 5 + $n9 * 6 + $n8 * 7 + $n7 * 8 + $n6 * 9 + $n5 * 2 + $n4 * 3 + $n3 * 4 + $n2 * 5 + $n1 * 6;
        $d2 = 11 - ($this->mod($d2, 11) );
        if ($d2 >= 10) {
            $d2 = 0;
        }
        $retorno = '';
        if ($mascara == 1) {
            $retorno = '' . $n1 . $n2 . "." . $n3 . $n4 . $n5 . "." . $n6 . $n7 . $n8 . "/" . $n9 . $n10 . $n11 . $n12 . "-" . $d1 . $d2;
        } else {
            $retorno = '' . $n1 . $n2 . $n3 . $n4 . $n5 . $n6 . $n7 . $n8 . $n9 . $n10 . $n11 . $n12 . $d1 . $d2;
        }
        return $retorno;
    }
    
    public function Email($Email){
        $EmailDIG = (string) $Email;
        $EmailFORMAT = '/[a-z0-9_\.\-]+@[a-z0-9_\.\-]*[a-z0-9_\.\-]+\.[a-z]{2,4}$/';
        
        if(preg_match($EmailFORMAT, $EmailDIG)):
            return true;
        else:
            return false;
        endif;
    }
    
    
    
}