<?php
session_start();
ob_start();
require_once '../_class/Ferramenta.php';

if(isset($_POST['acao'])){
    $acao = addslashes($_POST['acao']);
}else{
    $acao = addslashes($_GET['acao']);
}

if($acao == 'home_tipo_documento_receita'){
    
    $home_receita_status = addslashes($_GET['home_receita_status']);
    $home_receita_valores = addslashes($_GET['home_receita_valores']);
    $home_receita_data_inicial = addslashes($_GET['home_receita_data_inicial']);
    $home_receita_data_final = addslashes($_GET['home_receita_data_final']);
    
    if($home_receita_data_final != '' && $home_receita_data_inicial != ''){
        if($home_receita_valores != ''){
            if($home_receita_valores == '0'){
                $type_financeiro = 'financeiro_data_vencimento';
            }else{
                $type_financeiro = 'financeiro_data_pagamento';
            }
        }else{
            $type_financeiro = 'financeiro_data_vencimento';
        }
        $sql_periodo = "AND $type_financeiro BETWEEN '".$home_receita_data_inicial."' AND '".$home_receita_data_final."'";
    }else{
        $sql_periodo = "";
    }
    if($home_receita_status != ''){
        $sql_status = "AND financeiro_status = '".$home_receita_status."'";
    }else{
        $sql_status = "";
    }
    if($home_receita_valores != ''){
        if($home_receita_valores == '0'){
            $type_valor = 'financeiro_valor';
        }else{
            $type_valor = 'financeiro_valor_pagamento';
        }
    }else{
        $type_valor = 'financeiro_valor';
    }
    
    echo '{
            "cols": [
                  {"id":"","label":"Topping","pattern":"","type":"string"},
                  {"id":"","label":"Slices","pattern":"","type":"number"}
                ],
            "rows": [';
            $read_tipo_documento = Read('tipo_documento', "ORDER BY tipo_documento_descricao ASC");
            if(NumQuery($read_tipo_documento) > '0'){
                foreach($read_tipo_documento as $read_tipo_documento_view){
                    $read_financeiro_receita = ReadComposta("SELECT SUM($type_valor) AS financeiro_valor FROM financeiro WHERE financeiro_id_tipo_documento = '".$read_tipo_documento_view['tipo_documento_id']."' AND financeiro_tipo = 'CR' {$sql_periodo} {$sql_status}");
                    if(NumQuery($read_financeiro_receita) > '0'){
                        foreach($read_financeiro_receita as $read_financeiro_receita_view);
                        $valor_financeiro = $read_financeiro_receita_view['financeiro_valor'];
                    }else{
                        $valor_financeiro = '0.00';
                    }
                    if($valor_financeiro == ''){
                        $valor_financeiro = '0.00';
                    }
                    echo '{"c":[{"v":"'.$read_tipo_documento_view['tipo_documento_descricao'].'","f":null},{"v":'.$valor_financeiro.',"f":null}]},';
                }
            }
    echo '      ]
            }';
}elseif($acao == 'home_tipo_documento_despesa'){
    
    $home_despesa_status = addslashes($_GET['home_despesa_status']);
    $home_despesa_valores = addslashes($_GET['home_despesa_valores']);
    $home_despesa_data_inicial = addslashes($_GET['home_despesa_data_inicial']);
    $home_despesa_data_final = addslashes($_GET['home_despesa_data_final']);
    
    if($home_despesa_data_final != '' && $home_despesa_data_inicial != ''){
        if($home_despesa_valores != ''){
            if($home_despesa_valores == '0'){
                $type_financeiro = 'financeiro_data_vencimento';
            }else{
                $type_financeiro = 'financeiro_data_pagamento';
            }
        }else{
            $type_financeiro = 'financeiro_data_vencimento';
        }
        $sql_periodo = "AND $type_financeiro BETWEEN '".$home_despesa_data_inicial."' AND '".$home_despesa_data_final."'";
    }else{
        $sql_periodo = "";
    }
    if($home_despesa_status != ''){
        $sql_status = "AND financeiro_status = '".$home_despesa_status."'";
    }else{
        $sql_status = "";
    }
    if($home_despesa_valores != ''){
        if($home_despesa_valores == '0'){
            $type_valor = 'financeiro_valor';
        }else{
            $type_valor = 'financeiro_valor_pagamento';
        }
    }else{
        $type_valor = 'financeiro_valor';
    }
    
    echo '{
            "cols": [
                  {"id":"","label":"Topping","pattern":"","type":"string"},
                  {"id":"","label":"Slices","pattern":"","type":"number"}
                ],
            "rows": [';
            $read_tipo_documento = Read('tipo_documento', "ORDER BY tipo_documento_descricao ASC");
            if(NumQuery($read_tipo_documento) > '0'){
                foreach($read_tipo_documento as $read_tipo_documento_view){
                    $read_financeiro_despesa = ReadComposta("SELECT SUM($type_valor) AS financeiro_valor FROM financeiro WHERE financeiro_id_tipo_documento = '".$read_tipo_documento_view['tipo_documento_id']."' AND financeiro_tipo = 'CP' {$sql_periodo} {$sql_status}");
                    if(NumQuery($read_financeiro_despesa) > '0'){
                        foreach($read_financeiro_despesa as $read_financeiro_despesa_view);
                        $valor_financeiro = $read_financeiro_despesa_view['financeiro_valor'];
                    }else{
                        $valor_financeiro = '0.00';
                    }
                    if($valor_financeiro == ''){
                        $valor_financeiro = '0.00';
                    }
                    echo '{"c":[{"v":"'.$read_tipo_documento_view['tipo_documento_descricao'].'","f":null},{"v":'.$valor_financeiro.',"f":null}]},';
                }
            }
    echo '      ]
            }';
}

?>