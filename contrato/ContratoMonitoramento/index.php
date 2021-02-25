<?php
    session_start();
    ob_start();
    require_once '../../_class/Ferramenta.php';
    $Id = addslashes($_GET['Id']);
    $read_contrato = Read('contrato_monitoramento', "WHERE contrato_monitoramento_id = '".$Id."'");
    if(NumQuery($read_contrato) > '0'){
        foreach($read_contrato as $read_contrato_view);
        $read_contato = Read('contato', "WHERE contato_id = '".$read_contrato_view['contrato_monitoramento_id_contato']."'");
        if(NumQuery($read_contato) > '0'){
            foreach($read_contato as $read_contato_view);
        }
    }
    if($read_contrato_view['contrato_monitoramento_possui_plano'] == '1'){
        $texto_possui_plano = '';
        $texto_primeira_plano = PRIMEIRA_FRASE_PLANO_COMPENSACAO;
        $texto_criterios = CRITERIO_PARTICIPAR_PLANO_COMPENSACAO;
        $texto_planos = valor_plano_compensacao($read_contrato_view['contrato_monitoramento_valor_plano']);
    }else{
        $texto_possui_plano = CASO_NAO_TENHA_PLANO;
        $texto_primeira_plano = '';
        $texto_criterios = '';
        $texto_planos = '';
    }
    if($read_contrato_view['contrato_monitoramento_possui_ronda'] == '1'){
        $texto_possui_ronda = CASO_TENHA_RONDA;
    }else{
        $texto_possui_ronda = CASO_NAO_TENHA_RONDA;
    }
    $read_itens_monitoramento = Read('contrato_monitoramento_itens', "WHERE contrato_monitoramento_itens_id_contrato = '".$Id."'");
    $html_clausula_nona = '';
    $html_clausula_nona .= '<h4>9. RELAÇÃO DE EQUIPAMENTOS LOCADOS</h4>';
    $html_clausula_nona .= '<table width="100%" border="0">';
    $html_clausula_nona .= '<tr bgcolor="#000000" style="font-size: 14px;">
                        <td style="color:#FFF">#</td>
                        <td style="color:#FFF">DATA</td>
                        <td style="color:#FFF">DESCRICAO</td>
                        <td style="color:#FFF">QUANTIDADE</td>
                        <td style="color:#FFF">VALOR UNITÁRIO</td>
                        <td style="color:#FFF">VALOR TOTAL</td>
                    </tr>';
    $count_nona = '0';
    foreach($read_itens_monitoramento as $read_itens_monitoramento_view){
        $count_nona++;
        $html_clausula_nona .= '<tr style="font-size: 10px;">';
            $html_clausula_nona .= '<td>'.$count_nona.'</td>';
            $html_clausula_nona .= '<td>'.FormDataBr($read_itens_monitoramento_view['contrato_monitoramento_itens_data']).'</td>';
            $html_clausula_nona .= '<td>'.$read_itens_monitoramento_view['contrato_monitoramento_itens_descricao'].'</td>';
            $html_clausula_nona .= '<td>'.$read_itens_monitoramento_view['contrato_monitoramento_itens_quantidade'].'</td>';
            $html_clausula_nona .= '<td>'.FormatMoney($read_itens_monitoramento_view['contrato_monitoramento_itens_valor_unitario']).'</td>';
            $html_clausula_nona .= '<td>'.FormatMoney($read_itens_monitoramento_view['contrato_monitoramento_itens_valor_total']).'</td>';
        $html_clausula_nona .= '</tr>';
    }
    $html_clausula_nona .= '</table>';
?>
<html>
<head>
<style>
    thead { display: table-header-group; }
    tfoot { display: table-footer-group;}
	tbody { text-align:justify;}
	.texto {text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;}
</style>
<meta charset="UTF-8">
</head>
<body>
<table border="0" align="center" width="100%">
    <tr>
        <th>
            <img src="logo.png" width="65" />
        </th>
        <th>
            <h2 style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif">MONITORAMENTO</h2>
        </th>
        <th>
            <h3 style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif"><?php echo $Id;?></h3>
        </th>
    </tr>
    <tr>
        <th colspan="3">
            <hr />
        </th>
    </tr>
    <tbody>
    	<tr>
        	<td colspan="3">
            	<h1 style="text-decoration:underline; font-size:18px; text-align:center; padding-top:25px; font-family:Verdana, Geneva, sans-serif; margin-bottom:55px;">CONTRATO DE LOCAÇÃO DE EQUIPAMENTOS PARA PRESTAÇÃO DE SERVIÇOS DE MONITORAMENTO REMOTO</h1>
            </td>
        </tr>
        <tr>
        	<td colspan="3">
                <p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">
                    Pelo presente instrumento particular de Contrato, de um lado:
                </p>    
            	<p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;"><strong style="font-family: Arial; font-size: 13px;">Contratante/Cliente: </strong><span class="CLIENTE_NOME_RAZAO token_d4s"><?php echo $read_contato_view['contato_nome_razao'];?></span><br>
                    <strong style="font-family: Arial; font-size: 13px;">Nome Fantasia: </strong><span class="CLIENTE_NOME_FANTASIA token_d4s"><?php echo $read_contato_view['contato_nome_fantasia'];?></span><br>

                    <strong style="font-family: Arial; font-size: 13px;">CNPJ / CPF: </strong><span class="CLIENTE_CPF_CNPJ token_d4s"><?php echo $read_contato_view['contato_cpf_cnpj'];?></span><br>
                    <strong style="font-family: Arial; font-size: 13px;">Fone 1: </strong><span class="CLIENTE_TELEFONE token_d4s"><?php echo $read_contato_view['contato_telefone'];?></span> <strong style="font-family: Arial; font-size: 13px;">Fone 2: </strong><span class="CLIENTE_CELULAR token_d4s"><?php echo $read_contato_view['contato_celular'];?></span><br>
                    <strong style="font-family: Arial; font-size: 13px;">Endereço: </strong><span class="CLIENTE_ENDERECO token_d4s"><?php echo $read_contato_view['contato_endereco'];?></span>, <span class="CLIENTE_NUMERO token_d4s"><?php echo $read_contato_view['contato_numero'];?></span> - <span class="CLIENTE_BAIRRO token_d4s"><?php echo $read_contato_view['contato_bairro'];?></span>, <span class="CLIENTE_CIDADE token_d4s"><?php echo $read_contato_view['contato_cidade'];?></span> - <span class="CLIENTE_UF token_d4s"><?php echo $read_contato_view['contato_estado'];?></span><br>
                    <strong style="font-family: Arial; font-size: 13px;">Cep: </strong><span class="CLIENTE_CEP token_d4s"><?php echo $read_contato_view['contato_cep'];?></span><br>
                    <strong style="font-family: Arial; font-size: 13px;">Email: </strong><span class="CLIENTE_EMAIL token_d4s"><?php echo $read_contato_view['contato_email'];?></span><br>
                    
                </p>
                <h4>1.DO OBJETO</h4>
                <p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">1.1 - O presente Contrato tem como finalidade a locação de equipamentos eletrônicos para a prestação de serviços de monitoramento remoto por parte da “CONTRATADA” à “CONTRATANTE”, mediante as condições abaixo estabelecidas.</p>
                <p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">1.2 - O serviço contratado não é dispositivo antifurto, de modo que o “CONTRATANTE” declara estar ciente que a utilização dos serviços não impede a ação de terceiros que venham a furtar, roubar ou praticar quaisquer atos danosos em pontos com os equipamentos instalados<span class="CASO_TENHA token_d4s"><span class="CASO_TENHA_PLANO token_d4s"><span class="CASO_TENHA_PLANO_COMP token_d4s"><span class="CASO_TENHA_PLANO_COMPE token_d4s"><span class="CASO_TENHA_PLANO_COMPENSA token_d4s"><span class="CASO_TENHA_PLANO_COMPENSACAO token_d4s"><?php echo $texto_possui_plano;?></span></span></span></span></span></span></p>
                <p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;"><span class="PRIMEIRA_FRASE_PLANO_COMPENSACAO token_d4s"><?php echo $texto_primeira_plano;?></span></p>
                <p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;"><strong><span class="CRITERIOS_PARTICIPAR_PLANO_COMPENSACAO token_d4s"><?php echo $texto_criterios;?></span></strong></p>
                <span class="VALORES_PLANO_COMPENSACAO token_d4s"><?php echo $texto_planos;?></span>
                
                <h4>2. DO LOCAL DA PRESTAÇÃO DE SERVIÇOS</h4>
                <p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">2.1 - Os serviços ora contratados serão executados fora dos locais onde estão instalados os equipamentos eletrônicos de alarme, ou seja, no centro de operações utilizado pela “CONTRATADA”. Além disso, <span class="RONDA token_d4s"><?php echo $texto_possui_ronda;?></span> um serviço de pronto-atendimento no local do estabelecimento onde se encontra instalado o sistema de alarme, com vistas a evitar maiores prejuízos ao local violado.</p>
                
                <h4>3. DOS EQUIPAMENTOS ELETRÔNICOS E RESPONSABILIDADES</h4>
                <p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">3.1 - Os equipamentos eletrônicos de câmeras e alarmes, entre outros necessários para a eficiente prestação do serviço, serão locados à “CONTRATANTE”, de modo que será necessário devolvê-los à “CONTRATADA” quando da rescisão deste, sob pena de indenizá-la o valor integral dos produtos locados em caso de descumprimento.</p>
                <p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">3.2 - A “CONTRATANTE” poderá optar pela compra dos produtos eletrônicos necessários à prestação do serviço, devendo acordar com a “CONTRATADA”, à parte deste, o valor e condições de compra dos produtos.</p>
                <p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">3.3 - A “CONTRATANTE” fica obrigada a conservar, como se sua própria fosse, os equipamentos locados, não podendo utilizá-los senão para os objetivos deste Contrato ou de natureza dele, sob pena de responder por perdas e danos.</p>
                <p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">3.4 - A linha telefônica que se comunica com a central da “CONTRATADA” não poderá sofrer nenhum tipo de dano em função de cortes ou por qualquer outro motivo. A “CONTRATANTE” deverá sempre verificar se a linha está em perfeito funcionamento pois, caso contrário, a comunicação do sistema de alarmes com a Central da “CONTRATADA” estará comprometida e quaisquer eventualidades não serão do nosso conhecimento e responsabilidade.</p>
                <p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">3.5 - A “CONTRATANTE” se compromete a evitar a ocorrência e emissão de alarmes falsos, ocasionados por disparos indevidos, seja a que título for, aí se incluindo o manuseio incorreto do sistema de alarme, falta de manutenção e limpeza junto aos sensores, portas e janelas mal fechadas, intrusão de animais e pessoas nos locais protegidos por sensores, correntes de ar no interior de ambientes fechados, existências de vegetações próximas a cercas elétricas e sensores, além de outros fatores que possam implicar na emissão indevida de sinais, bem como se compromete a evitar qualquer tipo de obstrução física aos equipamentos e que possam prejudicar o funcionamento adequado do sistema. </p>
                <p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">3.6 - A “CONTRATANTE” deverá realizar, a cada 12 meses, a troca de bateria do sistema de alarmes, sendo que o custo de aquisição do produto correrá por sua própria conta. A não troca de bateria poderá implicar no mal funcionamento do sistema de alarmes e a “CONTRATADA” estará isenta de qualquer prejuízo ou dano recorrente deste fato.</p>
                <p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">3.7 - Caso seja necessária algum tipo de manutenção no sistema eletrônico instalado, a “CONTRATADA” se compromete a enviar profissionais qualificados para verificação do problema. Se verificado a necessidade de substituir ou acrescentar qualquer equipamento, a “CONTRATADA” deverá apresentar um orçamento à “CONTRATANTE” para aprovação e autorização para realização do serviço.</p>
                <p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">3.8 - A “CONTRATANTE” será responsabilizada e deverá ressarcir a “CONTRATADA” caso os equipamentos locados venham a sofrer danos ou defeitos provenientes de desgastes naturais, como raios, chuvas e outros, bem como por realização de manutenção indevida por funcionários particulares, prepostos ou terceiros.</p>
                
                <h4>4. DO SERVIÇO DE MONITORAMENTO REMOTO</h4>
                <p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">4.1 - A “CONTRATADA” prestará à “CONTRATANTE”, durante o período de contratação e desde que atendida os critérios deste contrato, os serviços de captação dos sinais provenientes do painel de alarme instalado no local monitorado.</p>
                <p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">4.2 - O serviço será prestado à “CONTRATANTE” no período em que estiver rigorosamente em dia com suas obrigações contratuais, especialmente no que tange ao pagamento das mensalidades e outros serviços acessórios contratados, cessando essa condição simultaneamente a qualquer atraso de pagamento, por qualquer motivo, desobrigando a “CONTRATADA” a prestar o serviço e isentando-a em caso de danos ou prejuízos oriundos da paralisação do serviço.</p>
                <p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">4.3 - O monitoramento do sistema de alarme instalado no local indicado pela “CONTRATANTE” depende de sua ativação por ele, através de senha pessoal ou controle remoto, cujo controle das senhas e posse dos controles é responsabilidade da “CONTRATANTE”, sendo que o serviço de monitoramento somente é prestado enquanto estiver armado/ativado o sistema de alarme. A responsabilidade pela ativação e desativação do sistema é unicamente da “CONTRATANTE”, não cabendo qualquer intervenção da “CONTRATADA” no caso de alarmes não ativados.</p>
                <p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">4.3- Ocorrendo um evento, a “CONTRATANTE” será devidamente comunicada pela “CONTRATADA”, de modo a tomar ciência do ocorrido, através dos telefones informados neste Contrato e na Central de monitoramento da “CONTRATADA”.</p>
                
                <h4>5 – DO PREÇO DO SERVIÇO</h4>
                <p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">5.1 – Pela consecução integral deste Contrato, a “CONTRATANTE” pagará mensalmente à Contratack o valor de R$ <strong><span class="VALOR_MENSALIDADE token_d4s"><?php echo FormatMoney($read_contrato_view['contrato_monitoramento_valor_mensalidade']);?></span></strong> (<span class="VALOR_MENSALIDADE_EXTENSO token_d4s"><?php echo escreverValorMoeda($read_contrato_view['contrato_monitoramento_valor_mensalidade']);?></span>) <strong>pela locação dos equipamentos necessários à prestação de serviço de monitoramento remoto</strong>.</p>
                <p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">5.2 - A “CONTRATANTE” pagará a Contratack o valor de R$ <span class="VALOR_INSTALACAO token_d4s"><?php echo FormatMoney($read_contrato_view['contrato_monitoramento_valor_instalacao']);?></span> (<span class="VALOR_INSTALACAO_EXTENSO token_d4s"><?php echo escreverValorMoeda($read_contrato_view['contrato_monitoramento_valor_instalacao']);?></span>) pela instalação/adesão dos serviços contratados.</p>
                <p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">5.3 - É dever da “CONTRATANTE” solicitar à <strong>Contratack</strong> os boletos bancários para pagamento dos serviços contratados, não podendo alegar, em nenhuma hipótese, atraso ou falta de pagamento, bem como desconhecimento da dívida, por não ter recebido boleto/faturamento. Fica acordado, ainda, que a liquidação do pagamento pela “CONTRATANTE” deverá ser feita, impreterivelmente, no dia acordado para que não sejam geradas cobranças adicionais, como juros e multas de atraso.</p>
                <p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">5.5 - O atraso na liquidação de pagamentos por parte da “CONTRATANTE”, acarretará 2% de multa do valor da (s) parcela (s) em atraso, correção monetária de acordo com a variação de IGPM/FGV ou de qualquer outro índice que possa substituí-lo, além de atualização diária de juro taxada a 0,2%. O atraso de pagamentos poderá, ainda, acarretar o registro e negativação do contratante pela <strong>Contratack</strong> nos órgãos de proteção ao crédito, com ou sem aviso prévio a “CONTRATANTE”.</p>
                <p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">5.6 - O atraso de pagamento poderá acarretar a suspensão dos serviços, bem como o acesso da “CONTRATANTE” para armar e desarmar o sistema de sensores/alarmes. A suspensão não isenta o cliente do pagamento das mensalidades subsequentes, ou seja, os valores serão cobrados enquanto os equipamentos estiverem instalados no(s) estabelecimento(s) do cliente ou sobre sua posse.</p>
                <p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">5.7 - Os serviços suspensos somente serão reestabelecidos após o pagamento integral das faturas que estiverem pendentes, atualizadas com os encargos financeiros (juros e multas) passíveis do referido atraso.</p>
                <p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">5.8 - O pagamento da mensalidade de locação dos equipamentos é dado por <strong>Ciclos</strong>, ou seja, cobrado integralmente do primeiro 1º (primeiro) ao último dia de cada mês vigente. A cobrança é sempre referente a locação do mês anterior como, por exemplo, em fevereiro se paga a locação integral do mês de janeiro, e assim por diante. Não há descontos ou possibilidade de pagamento proporcional para equipamentos retirados no mês a ser faturado.</p>
                
                <h4>6. DO PRAZO</h4>
                <p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">6.1 - O presente Contrato tem validade de <span class="DURACAO_CONTRATO token_d4s"><?php echo $read_contrato_view['contrato_monitoramento_duracao'];?></span> (<span class="DURACAO_CONTRATO_EXTENSO token_d4s"><?php echo convert_number_to_words($read_contrato_view['contrato_monitoramento_duracao']);?></span>) meses a partir de sua assinatura; sendo que, após este período, será automaticamente renovado por iguais períodos e condições. Caso uma das partes intencione não renová-lo, deverá avisar o interesse à outra parte em até 30 (trinta) dias antes do prazo final, de forma idônea e comprovável, preferencialmente via e-mail.</p>
                <p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">6.2 - O valor deste Contrato para cálculos de fins rescisórios é de <strong>R$ <span class="VALOR_TOTAL_CONTRATO token_d4s"><?php echo FormatMoney($read_contrato_view['contrato_monitoramento_valor_total_contrato']);?></span></strong> (<span class="VALOR_TOTAL_CONTRATO_EXTENSO token_d4s"><?php echo escreverValorMoeda($read_contrato_view['contrato_monitoramento_valor_total_contrato']);?></span>), levando em consideração o valor pago mensalmente e o prazo de duração.</p>
                <p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">6.3 - O contrato só poderá ser findado definitivamente e na totalidade caso haja assinatura entre as partes do “<strong>Distrato Contratual</strong>”, caso contrário este instrumento e todas as suas cláusulas continuam exercendo validade jurídica. O documento deverá ser encaminhado pela <strong>Contratack</strong> ao contratante para assinatura e nele deverá informar que não há nenhum tipo de pendência, principalmente financeira, oriundas da relação comercial entre as partes.</p>
                
                <h4>7. DOS CRITÉRIOS E CONDIÇÕES DE RESCISÃO CONTRATUAL</h4>
                <p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">7.1 - É motivo para a rescisão contratual entre as partes a data final de vencimento do contrato, tendo sido anunciado interesse entre as partes com no mínimo 30 dias anteriores e desde que não haja nenhuma pendência entre as partes, principalmente financeira.</p>
                <p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">7.2 - Em caso de inadimplência e impontualidade da “CONTRATANTE” em relação aos pagamentos superior a 45 (quarenta e cinco) dias, a <strong>Contratack</strong> poderá dar o contrato por rescindido mediante comunicação por escrito, ocasião em que o contratante será notificado para que efetue os pagamentos devidos, incluindo-se as parcelas vencidas até a efetiva devolução dos equipamentos locados, sendo que fica previamente autorizado a retirada dos equipamentos locados pela  <strong>Contratack</strong> nos estabelecimentos/pontos da “CONTRATANTE”- que deverá ainda arcar com os custos de desinstalação.</p>
                <p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">7.3 - Caso haja interesse de rescisão contratual antecipada de uma das partes sem motivo descrito nas cláusulas 7.1 e 7.2, que seja por livre vontade da “CONTRATANTE” ou da “CONTRATADA”, a parte interessada deverá ressarcir a outra, a título de mora, 50% do valor remanescente do contrato.</p>
                <p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">7.4 – Quando da rescisão contratual, para todos os casos, a “CONTRATANTE” obrigasse a devolver, disponibilizar e viabilizar a retirada dos equipamentos locados da <strong>Contratack</strong>, sob pena de serem faturados para pagamento em nome da “CONTRATANTE”.</p>
                <p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">7.5 - Os valores devidos pelo contratante à <strong>Contratack</strong>, oriundos deste contrato, só serão extinguidos quando do pagamento integral, ainda que a pessoa/empresa contratante se apresente em situação e processo de Insolvência Civil, Recuperação Judicial ou Falência.</p>
                
                <h4>8. DISPOSIÇÕES GERAIS</h4>
                <p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">8.1 - É reservado à “CONTRATADA” o direito de deixar de comercializar a qualquer tempo os equipamentos locados, caso em que comunicará a “CONTRATANTE” com no mínimo 30 (trinta) dias de antecedência, por via postal, e-mail ou imprensa local, sem prejuízo para ambas as partes e que caberá a <strong>Contratack</strong> verificar a alternativa mais viável para este caso.</p>
                <p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">8.2 - Qualquer alteração da legislação tributária, regras ou pacote governamental que implique alteração do equilíbrio econômico do contrato, a <strong>Contratack</strong> poderá propor uma nova renegociação das disposições contratuais afetadas.</p>
                <p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">8.3 - O não exercício de direitos não implicará para qualquer das partes renúncia ou novação, tampouco aceitação tácita dos atos irregulares ou omitidos pela parte faltante.</p>
                <p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">8.4 - A eventual anulação de um dos itens do presente instrumento não invalidará as demais regras deste Contrato.</p>
                <p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">8.5 – A “CONTRATANTE” declara expressamente neste ato a sua ciência de que a responsabilidade da “CONTRATADA” está limitada ao dever de comunicar o evento ocorrido às pessoas indicadas pelo “CONTRATANTE” em nossa central de monitoramento, podendo, facultativamente, sem adentrar os limites de suas dependências, proceder a verificação das condições externas do local monitorado, através de agente/viatura própria quando contratado, ficando em qualquer hipótese a “CONTRATADA” isentada de culpa e excluída de responsabilidade por atos, providências, omissões ou atrasos praticados por terceiros, em especial, daqueles a quem e a seu tempo, se deu notícia e comunicou a respectiva ocorrência.</p>
                <p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">8.6  - A “CONTRATADA” igualmente não se responsabiliza pelo bom e regular funcionamento das linhas telefônicas do “CONTRATANTE” ou de outros meios de comunicação utilizados para transmissão de dados, dos quais depende a eficácia dos serviços contratados e prestados, ficando a “CONTRATANTE” também ciente do fato de que eventuais ocorrências de defeitos, desligamentos ou rompimentos de cabos, sem exceção, implicam na total interrupção do recebimento e envio dos sinais de alarme, que, assim, pela anomalia verificada, não serão identificados pela central de monitoramento.</p>
                
                <?php echo $html_clausula_nona;?>
				
				<p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;"><?php echo $read_contrato_view['contrato_monitoramento_obs'];?></p>
                
                <h4>10 – DA IRRETRABILIDADE</h4>
                <p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">10.1 - O presente instrumento é celebrado em caráter irrevogável e irretratável, obrigando as partes e seus sucessores nas obrigações ora pactuadas.</p>
                
                <h4>11 – DO FORO DE ELEIÇÃO</h4>
                <p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">11.1 - Fica eleito o Foro de Goianésia (GO), com exclusão de qualquer outro por mais privilegiado que seja, para dirimir dúvidas de interpretação ou execução decorrentes do presente contrato. E, por estarem justas e contratadas, assinam o presente instrumento em duas vias de igual teor e forma, na presença de testemunhas, para que surta os regulares efeitos legais.</p>
                
                <p class="texto">Goianésia, <?php echo FormDataBr($read_contrato_view['contrato_monitoramento_data_inicial']);?></p>
            </td>
        </tr>
        <tr>
        	<td colspan="2">
            	_______________________________________<br />
                <div align="center" style="font-size:12px"><?php echo $read_contato_view['contato_nome_razao'];?></div>
                <div align="center" style="font-size:12px"><?php echo $read_contato_view['contato_cpf_cnpj'];?></div>
                <div align="center" style="font-size:12px; margin-bottom:0px">CONTRATANTE</div>
            </td>
            <td>
            	_______________________________________<br />
                <div align="center" style="font-size:12px">Contratack Serviços de Seguranças Ltda</div>
                <div align="center" style="font-size:12px">CNPJ: 28.087.399/0001-09</div>
                <div align="center" style="font-size:12px; margin-bottom:85px">CONTRATADA</div>
            </td>
        </tr>
        <tr>
        	<td colspan="2">
            	_______________________________________<br />
                <div align="center" style="font-size:12px">Testemunha</div>
                <div align="center" style="font-size:12px">CPF: ______________________________</div>
            </td>
            <td>
            	_______________________________________<br />
                <div align="center" style="font-size:12px">Testemunha</div>
                <div align="center" style="font-size:12px">CPF: ______________________________</div>
            </td>
        </tr>
    </tbody>
</table>
</body>
</html>