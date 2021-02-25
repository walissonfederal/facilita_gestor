<?php
    session_start();
    ob_start();
    require_once '_class/Ferramenta.php';
    
    
    $read_pedido = Read('pedido', "WHERE pedido_tipo != '2' AND pedido_id_cliente = '65' ORDER BY pedido_id ASC");
    if(NumQuery($read_pedido) > '0'):
        foreach($read_pedido as $read_pedido_view):
            if($read_pedido_view['pedido_tipo'] == '0'):
                $read_itens_pedido_ativacao = Read('itens_pedido', "WHERE itens_pedido_id_pedido = '".$read_pedido_view['pedido_id']."'");
                if(NumQuery($read_itens_pedido_ativacao) > '0'):
                    foreach($read_itens_pedido_ativacao as $read_itens_pedido_ativacao_view):
                        $SessionDados[$read_itens_pedido_ativacao_view['itens_pedido_id_chip']] = $read_itens_pedido_ativacao_view;
                    endforeach;
                endif;
            else:
                $read_itens_pedido_desativacao = Read('itens_pedido', "WHERE itens_pedido_id_pedido = '".$read_pedido_view['pedido_id']."'");
                if(NumQuery($read_itens_pedido_desativacao) > '0'):
                    foreach($read_itens_pedido_desativacao as $read_itens_pedido_desativacao_view):
                        unset($SessionDados[$read_itens_pedido_desativacao_view['itens_pedido_id_chip']]);
                    endforeach;
                endif;
            endif;
        endforeach;
    endif;
    echo count($SessionDados);
    echo '<pre>';   
        print_r($SessionDados);
    echo '</pre>';