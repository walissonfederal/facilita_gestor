<div class="container-fluid nav-hidden" id="content">
    <div id="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                Linha do tempo (Chips)
                                <a href="javascript::" onclick="carrega_pagina('pedido', 'index.php');" class="btn btn-primary">Voltar</a>
                            </h3>
                        </div>
                        <div class="box-content">
                            <?php
                                $get_id_chip = addslashes($_GET['id_chip']);
                                $read_itens_pedido = ReadComposta("SELECT * FROM itens_pedido INNER JOIN pedido ON pedido.pedido_id = itens_pedido.itens_pedido_id_pedido WHERE itens_pedido.itens_pedido_id_chip = '".$get_id_chip."' ORDER BY itens_pedido.itens_pedido_id_pedido DESC");
                                if(NumQuery($read_itens_pedido) > '0'){
                                    foreach($read_itens_pedido as $read_itens_pedido_view){
										if($read_itens_pedido_view['pedido_tipo'] == '0'){
											$tipo_pedido = 'Instalação';
										}elseif($read_itens_pedido_view['pedido_tipo'] == '1'){
											$tipo_pedido = 'Desinstalação';
										}elseif($read_itens_pedido_view['pedido_tipo'] == '2'){
											$tipo_pedido = 'SMS';
										}
										$tipo_status_chip = GetDados('chip', $get_id_chip, 'chip_id', 'chip_status');
										if($tipo_status_chip == '0'){
											$chip_status = 'Chip disponível';
										}else{
											$chip_status = 'Chip em uso';
										}
										echo '<ul class="timeline">';
											echo '<li>';
												echo '<div class="timeline-content">';
													echo '<div class="activity">
															<div class="user">
																<a href="javascript::">'.$tipo_pedido.' - '.$chip_status.'</a>
																<span></span>
																<div class="date">Data Pedido: '.  FormDataBr($read_itens_pedido_view['pedido_data']).'</div>
															</div>
															<p>
																Pedido onde está inserido: '.$read_itens_pedido_view['pedido_id'].'
															</p>
															<p>
																<strong>Contato: </strong> '.  GetDados('contato', $read_itens_pedido_view['pedido_id_cliente'], 'contato_id', 'contato_nome_razao').'
															</p>
														</div>';
												echo '</div>';
											echo '</li>';
										echo '</ul>';
									}
                                }
                            ?>
                            <hr />
                            <ul class="timeline" style="display: none;">
                                <?php
                                    
                                    $read_linha_tempo = ReadComposta("SELECT * FROM linha_tempo_chip LEFT JOIN user ON linha_tempo_chip.linha_tempo_chip_id_user = user.user_id LEFT JOIN contato ON contato.contato_id = linha_tempo_chip.linha_tempo_chip_id_contato WHERE linha_tempo_chip.linha_tempo_chip_id_chip = '".$get_id_chip."'");
                                    if(NumQuery($read_linha_tempo) > '0'){
                                        foreach($read_linha_tempo as $read_linha_tempo_view){
                                ?>
                                <li>
                                    <div class="timeline-content">
                                        <div class="left">
                                            <img src="<?php echo substr($read_linha_tempo_view['user_foto'],3,500);?>" width="85" height="" style="float: left; margin-right: 5px;"/>
                                        </div>
                                        <div class="activity">
                                            <div class="user">
                                                <a href="javascript::"><?php echo $read_linha_tempo_view['user_nome'];?></a>
                                                <span><?php echo $read_linha_tempo_view['linha_tempo_chip_operacao'];?></span>
                                                <div class="date"><?php echo FormDataBrTudo($read_linha_tempo_view['linha_tempo_chip_data_hora']);?></div>
                                            </div>
                                            <p>
                                                <?php echo $read_linha_tempo_view['linha_tempo_chip_texto'];?>
                                            </p>
                                            <p>
                                                <strong>Contato: </strong> <?php echo $read_linha_tempo_view['contato_nome_razao'];?>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="line"></div>
                                </li>
                                <?php
                                        }
                                    }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>