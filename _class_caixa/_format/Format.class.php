<?php

/**
 * Classe responsável pela validação de diversos documentos
 *
 * @author Marques Junior
 */

class Format {
    
    public function FormatMoney($Valor){
        $return = number_format($Valor,2,",",".");
        return $return;
    }
    
    public function FormatDataBr($Data){
        $explode_data = explode('-', $Data);
        return $explode_data['2'].'/'.$explode_data['1'].'/'.$explode_data['0'];
    }
    
    public function FormatDataHoraBr($DataHora){
        $explode_data_hora = explode(' ', $DataHora);
        $explode_data = explode('-', $explode_data_hora['0']);
        return $explode_data['2'].'/'.$explode_data['1'].'/'.$explode_data['0'].' '.$explode_data_hora['1'];
    }
    
    public function FormatDataEua($Data){
        $explode_data = explode('/', $Data);
        return $explode_data['2'].'-'.$explode_data['1'].'-'.$explode_data['0'];
    }
    
    public function FormatDataHoraEua($DataHora){
        $explode_data_hora = explode(' ', $DataHora);
        $explode_data = explode('/', $explode_data_hora['0']);
        return $explode_data['2'].'-'.$explode_data['1'].'-'.$explode_data['0'].' '.$explode_data_hora['1'];
    }
    
}