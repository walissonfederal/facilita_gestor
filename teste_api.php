<?php
    session_start();
    ob_start();
    require_once '_class/Ferramenta.php';
    
    /*$read_contato = Read('contato', "WHERE contato_email != ''");
    if(NumQuery($read_contato) > '0'){
        foreach($read_contato as $read_contato_view){
            echo $read_contato_view['contato_email'].'<br />';
        }
    }*/
    
    $read_faturamento = Read('faturamento', "WHERE faturamento_referencia = '10/2017'");
    if(NumQuery($read_faturamento) > '0'){
        foreach($read_faturamento as $read_faturamento_view){
            $read_itens = Read('itens_faturamento', "WHERE itens_faturamento_id_faturamento = '".$read_faturamento_view['faturamento_id']."' AND itens_faturamento_tipo = '0'");
            if(NumQuery($read_itens) <= '19'){
                foreach($read_itens as $read_itens_view);
                if($read_itens_view['itens_faturamento_plano'] == 'PLANO M2M(BRONZE)'){
                    echo $read_faturamento_view['faturamento_id'].'-'.$read_itens_view['itens_faturamento_plano'].'<br />';
                    echo "SELECT * FROM faturamento WHERE faturamento_id = '".$read_faturamento_view['faturamento_id']."';
                    SELECT * FROM itens_faturamento WHERE itens_faturamento_id_faturamento = '".$read_faturamento_view['faturamento_id']."' AND itens_faturamento_tipo = '0';
                    SELECT * FROM financeiro WHERE financeiro_referencia_faturamento = '10/2017' AND financeiro_id_contato = '".$read_faturamento_view['faturamento_id_contato']."';";
                    echo '<hr />';
                }
            }
        }
    }