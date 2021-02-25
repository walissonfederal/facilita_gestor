<?php
    session_start();
    ob_start();
    require_once '../../_class/Ferramenta.php';
    $Id = addslashes($_GET['Id']);
    $read_contrato = Read('contrato_rastreamento', "WHERE contrato_rastreamento_id = '".$Id."'");
    if(NumQuery($read_contrato) > '0'){
        foreach($read_contrato as $read_contrato_view);
        $read_contato = Read('contato', "WHERE contato_id = '".$read_contrato_view['contrato_rastreamento_id_contato']."'");
        if(NumQuery($read_contato) > '0'){
            foreach($read_contato as $read_contato_view);
        }
    }
?>
<html>
    <head>
        <style type="text/css">
            @media print {
                thead { display: table-header-group;}
                tbody { text-align:justify;}
            }
            .texto {text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;}
        </style>
        <meta charset="UTF-8">
        <title>Pagina</title>
    </head>
    <table>
        <tr>
            <th>
                <img src="logo.png" width="65" />
            </th>
            <th>
                <h2 style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif">RASTREAMENTO</h2>
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
                    <h1 style="text-decoration:underline; font-size:18px; text-align:center; padding-top:25px; font-family:Verdana, Geneva, sans-serif; margin-bottom:55px;">CONTRATO DE LOCAÇÃO DE EQUIPAMENTOS E PRESTAÇÃO DE SERVIÇOS DE RASTREAMENTO VEICULAR</h1>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <p class="texto">
                        Pelo presente instrumento particular de Contrato, de um lado:
                    </p>
                    <p class="texto">
                        <strong style="font-family: Arial; font-size: 13px;">Contratante/Cliente: </strong><?php echo $read_contato_view['contato_nome_razao'];?><br />
                        <strong style="font-family: Arial; font-size: 13px;">Nome Fantasia: </strong><?php echo $read_contato_view['contato_nome_fantasia'];?><br />
                        <strong style="font-family: Arial; font-size: 13px;">CNPJ / CPF: </strong><?php echo $read_contato_view['contato_cpf_cnpj'];?><br />
                        <strong style="font-family: Arial; font-size: 13px;">Fone 1: </strong><?php echo $read_contato_view['contato_telefone'];?> <strong style="font-family: Arial; font-size: 13px;">Fone 2: </strong><?php echo $read_contato_view['contato_celular'];?><br />
                        <strong style="font-family: Arial; font-size: 13px;">Endereço: </strong><?php echo $read_contato_view['contato_endereco'];?>, <?php echo $read_contato_view['contato_numero'];?> - <?php echo $read_contato_view['contato_bairro'];?>, <?php echo $read_contato_view['contato_cidade'];?> - <?php echo $read_contato_view['contato_estado'];?><br />
                        <strong style="font-family: Arial; font-size: 13px;">Cep: </strong><?php echo $read_contato_view['contato_cep'];?><br />
                        <strong style="font-family: Arial; font-size: 13px;">Email: </strong><?php echo $read_contato_view['contato_email'];?><br />
                    </p>
                    <p class="texto">
                        Doravante denominada <strong>CONTRATANTE</strong>, e de outro lado, a parte <strong>CONTRATADA</strong>:
                    </p>
                    <p class="texto">
                        <strong style="font-family: Arial; font-size: 13px;">Contratado: </strong>Federal Sistemas de Segurança e Monitoramento Ltda. – ME<br />
                        <strong style="font-family: Arial; font-size: 13px;">CNPJ: </strong>11.655.954/0001-59<br />
                        <strong style="font-family: Arial; font-size: 13px;">Fones: </strong>(62) 3353-4350 e (62) 3307-2871<br />
                        <strong style="font-family: Arial; font-size: 13px;">E-mails: </strong>financeiro@federalsistemas.com.br / suporte@federalsistemas.com.br / diretor@federalsistemas.com.br<br />
                        <strong style="font-family: Arial; font-size: 13px;">Site: </strong>www.federalsistemas.com.br<br />
                        <strong style="font-family: Arial; font-size: 13px;">Endereço </strong>Avenida Presidente Vargas, nº 254 Centro, Ceres (GO)<br />
                    </p>
                    <p class="texto">
                        Denominado doravante <strong>CONTRATADA</strong>, têm entre si, justos e contratados o que se segue:
                    </p>
                    <h4>Cláusula 1 – DO OBJETO</h4>
                    <p class="texto">1.1 - O presente instrumento tem por objetivo a <strong>Locação de Equipamentos</strong> para a prestação de serviços de localização, rastreamento, bloqueio e desbloqueio remoto (quando contratado) de veículos relacionados no Termo de Contratação de Serviços e Comodato (Aditivo Contratual) pela <strong>Federal Sistemas</strong> ao Contratante.</p>
                    <p class="texto"><strong>Localização:</strong> Identificação do local em que veículo está, desde que esteja devidamente equipado com os dispositivos necessários e em perfeitas condições de manutenção e funcionamento, encontrando-se dentro da área de cobertura da operadora de telefonia móvel – GSM/GPRS.</p>
                    <p class="texto"><strong>Rastreamento:</strong> Consiste no ato de, uma vez localizado o veículo, acompanha-lo durante o seu percurso através do envio de dados sistêmicos em períodos programados, com a utilização de telefonia celular móvel, a partir da informação de sinal GPS.</p>
                    <p class="texto"><strong>Bloqueio e Desbloqueio Remoto:</strong> Consiste na paralização via remota do veículo contratado, desativando a alimentação de combustível ou a alimentação elétrica do veículo. A paralisação será realizada somente por solicitação do Contratante, mediante utilização de usuário e senha pessoal, devidamente cadastrada na central da Contratada, ou por qualquer meio idôneo e comprovável que demonstre a intenção do Contratante em efetuar o serviço.</p>
                    <p class="texto"><strong>PARÁGRAFO ÚNICO – </strong>O contratante afirma estar ciente que é responsável pelo pedido de acionamento do comando de desligamento ou bloqueio do veículo, e que sua utilização pode ocasionar danos materiais ou a pessoas durante e depois a parada do veículo, sendo de sua exclusiva responsabilidade o acionamento do sistema.</p>
                    <p class="texto">1.2 - Fica convencionado que os serviços descritos serão prestados com a tecnologia GSM/GPRS e recepção de sinais de rede internacional de satélite GPS. Desse modo, para que haja a correta execução do serviço, é necessário que o veículo esteja com os equipamentos de rastreamento devidamente equipados e dentro de área de cobertura de sinal de telefonia móvel.</p>
                    <p class="texto">1.3 - O serviço contratado não é dispositivo antifurto, de modo que o Contratante declara estar ciente que a utilização dos serviços não impede a ação de terceiros que venham a furtar, roubar ou praticar quaisquer outros atos que privem o Contratante da posse ou propriedade de seu veículo, não existindo qualquer garantia/compromisso de devolução de bem ou ressarcimento de prejuízo pela Contratada.</p>
                    <p class="texto">1.4 - A Contratada fornecerá ao Contratante usuário e senha que o permita acompanhar o veículo por meio da plataforma de rastreamento na web ou aplicativo mobile, onde este terá acesso aos relatórios disponíveis e ofertados pelo serviço.</p>
                    <h4>Cláusula 2 – DOS EQUIPAMENTOS E RESPONSABILIDADES</h4>
                    <p class="texto">2.1 - O (s) veículo(s) monitorados e abrangidos pelos serviços contratados, nos quais serão           instalados os equipamentos necessários, constam no Termo de Contratação de Serviços e Comodato (aditivo contratual), preenchido e assinado pelo Contratante, e que permite a <strong>Federal</strong>, de forma expressa e sem ressalvas, a monitorar e rastrear o veículo, para o fim expresso desse contrato.</p>
                    <p class="texto">2.2 - A instalação dos equipamentos só será feita após a assinatura deste documento, bem como do Aditivo Contratual. Caberá ao Contratante informar os dados corretos tanto da pessoa contratual (física ou jurídica) quanto dos veículos em que serão instalados os equipamentos.</p>
                    <p class="texto">2.3 - A <strong>Federal</strong> irá fornecer ao Contratante, em regime de locação, os equipamentos necessários para a prestação adequada dos serviços contratados. Os equipamentos deverão ser devolvidos na totalidade e em perfeitas condições de uso para a Contratada ao findar deste contrato e, caso não seja, fica permitido que a Federal os fature para o cliente de acordo com a tabela de preço vigente.</p>
                    <p class="texto">2.4 - O Contratante também poderá comprar/adquirir os equipamentos para a prestação de serviço, acordando valor e condição de pagamento antecipadamente com o Contratado.</p>
                    <p class="texto">2.5 - Somente será utilizado equipamentos de fabricação da própria Contratada ou de fornecedores, certificados e autorizados, comercializados por ela. Não é permitida a instalação/desinstalação e manutenção por pessoal desautorizado pela Contratada, bem como a instalação de equipamentos adquiridos por terceiros ou, ainda, reaproveitamento de equipamentos utilizados em outros veículos.</p>
                    <p class="texto">2.6 - O SIM CARD (Chip de Dados), dispositivo necessário à plena execução dos serviços, é de total propriedade da <strong>Federal Sistemas</strong> e será cedida ao Contratante em regime de locação para uso exclusivo dos serviços contratados, devendo ser devolvido quando da rescisão deste contrato.</p>
                    <p class="texto">2.7 - A utilização indevida ou mau uso do SIM CARD que ocasione qualquer dano material, financeiro ou pessoal à Contratada, é de total responsabilidade do Contratante que arcará com os custos oriundos da utilização imprópria.</p>
                    <p class="texto">2.8 - O Contratante fica obrigado a conservar, como se sua própria fosse, os equipamentos emprestados, não podendo usá-los senão para os objetivos deste contrato ou de natureza dele, sob pena de responder por perdas e danos. </p>
                    <p class="texto">2.9 - O Contratante fica ciente de que, caso não autorizado pela concessionária, a garantia ou seguro do veículo no qual será instalado os equipamentos, poderá sofrer limitações (total ou parcial), e que a Contratada não se responsabiliza pela perda da referida garantia.</p>
                    <p class="texto">2.10 - Em qualquer das situações descritas neste documento, bem como nos casos de violação de qualquer equipamento por pessoas desautorizadas, ou também por falhas causadas por atuação do Contratante ou por terceiro que em seu nome/pedido infrinja qualquer cláusula deste, a contratada está inteiramente isenta de qualquer responsabilidade perante o contratante ou terceiro que possam ficar prejudicados em decorrência de prejuízo ou danos decorrentes da má utilização ou mau funcionamento dos serviços.</p>
                    <h4>Cláusula 3 – DOS PROCEDIMENTOS DE SERVIÇO E ASSISTÊNCIA TÉCNICA</h4>
                    <p class="texto">3.1 - Os procedimentos de instalação, desinstalação e manutenção de equipamentos necessários ao rastreamento veicular poderão ser realizados por técnico particular da Federal Sistemas ou por técnico (s) próprio (s) do cliente, desde que este (s) esteja (m) autorizado (s) e devidamente treinado (s) para o cumprimento destas atividades.</p>
                    <p class="texto">3.2 - As atividades de cunho técnico (instalação, desinstalação e manutenção) estão descritas no anexo 1 (tabela de preços) deste contrato e possuem valores fixados, tanto para a situação de serem realizadas por técnico da Federal quanto por técnicos do contratante.</p>
                    <p class="texto">3.3 – O contratante se responsabiliza pelas atividades de cunho técnico realizadas por técnico particular, isentando a contratada de qualquer dano físico ou financeiro causados pela atuação do técnico particular contratado.</p>
                    <p class="texto">3.4 - O contratante poderá solicitar à <strong>Federal Sistemas</strong> apoio técnico ou atendimento administrativo/financeiro via telefone ou e-mail descritos na apresentação das partes deste contrato.</p>
                    <p class="texto">3.5 - O contratante deverá enviar, via e-mail, solicitação de <strong>Ordem de Serviço</strong> para a Federal Sistemas para atividades de cunho técnico a serem realizadas pela contratada. A Federal terá até 4 dias úteis após a abertura da ordem de serviço para atender à solicitação da contratante, ou provisionar uma data correta para resolução integral da demanda.</p>
                    <p class="texto">3.6 - A instalação e assistência técnica poderão ser realizadas pela Federal através de técnicos treinados, qualificados e devidamente identificados, de segunda-feira a sábado, em horário comercial, nos postos de atendimento da <strong>Federal</strong> ou em local indicado pela contratante no raio de 100 km de uma das centrais da contratada.</p>
                    <p class="texto">3.7 - Não haverá, em nenhuma hipótese, a paralisação do serviço de rastreamento com aparelho de rastreador e componentes instalados em veículo, seja por parada entressafra, finalização de trabalho em campo, entre outros. Deste modo, será cobrado mensalidade normalmente enquanto o aparelho de rastreador estiver instalado em equipamento da contratante.</p>
                    <h4>Cláusula 4 – DO PREÇO E CONDIÇÕES DE PAGAMENTO</h4>
                    <p class="texto">4.1 - Pela consecução integral deste Contrato, o contratante pagará a Federal Sistemas os valores descritos no anexo 1 (Tabela de Preços) neste instrumento, referente à inclusão, instalação, desinstalação, mensalidades de locação, entre outros, de acordo com as condições descritas.</p>
                    <p class="texto">4.2 - O contratante declara estar ciente e de acordo com os valores descritos na Tabela de Preços, não tendo nada a reclamar presente e nem futuramente ainda que por meios judiciais. Declara, ainda, conhecer e estar de acordo com todas as condições que podem gerar ônus financeiro a pagar para <strong>Federal Sistemas</strong>.</p>
                    <p class="texto">4.3 – É dever do contratante solicitar à <strong>Federal Sistemas</strong> os boletos bancários para pagamento dos serviços contratados, não podendo alegar, em nenhuma hipótese, atraso ou falta de pagamento, bem como desconhecimento da dívida, por não ter recebido boleto/faturamento. Fica acordado, ainda, que a liquidação do pagamento pelo contratante deverá ser feita, impreterivelmente, no dia acordado para que não sejam geradas cobranças adicionais, como juros e multas de atraso.</p>
                    <p class="texto">4.4 - O atraso na liquidação de pagamento, por parte do contratante, acarretará 2% de multa do valor da (s) parcela (s) em atraso, correção monetária de acordo com a variação de IGPM/FGV ou de qualquer outro índice que possa substituí-lo, além de atualização diária de juro taxada a 0,2%. O atraso de pagamentos poderá, ainda, acarretar o registro e negativação do contratante pela <strong>Federal Sistemas</strong> nos órgãos de proteção ao crédito, com ou sem aviso prévio ao contratante.</p>
                    <p class="texto">4.5 - O atraso de pagamento em até 5 (cinco) dias poderá acarretar a suspensão dos serviços, bem como o acesso do Contratante à plataforma de rastreamento web ou por aplicativo mobile. A suspensão não isenta o cliente do pagamento das mensalidades subsequentes, ou seja, os valores serão cobrados enquanto os equipamentos estiverem instalados no(s) veículo(s) do cliente ou sobre sua posse.</p>
                    <p class="texto">4.6 - Os serviços suspensos somente serão reestabelecidos após o pagamento integral das faturas que estiverem pendentes, atualizadas com os encargos financeiros (juros e multas) passíveis do referido atraso.</p>
                    <p class="texto">4.7 - O pagamento de todo e qualquer valor deverá ser feito via boleto bancário, entregue antecipadamente ao Contratante pela Federal Sistemas. Pagamento via depósito bancário em conta do Contratado só poderá ser feito em situações específicas e com a aprovação da <strong>Federal Sistemas</strong>, e caberá ao Contratante enviar o comprovante via e-mail para averiguação.</p>
                    <p class="texto">4.8 - O pagamento de valores referentes à instalação, manutenção, desinstalação de qualquer componente ou serviço não serão reembolsados ao Contratante em nenhuma hipótese.</p>
                    <p class="texto">4.9 - Havendo necessidade de reinstalar ou desinstalar qualquer equipamento, será cobrado o valor constante no anexo 1 (Tabela de Preços) de serviços da Contratada para cobrir os custos de mão de obra técnica, conforme critérios descritos no anexo.</p>
                    <p class="texto">4.10 - O pagamento da mensalidade do serviço de rastreamento veicular é dado por <strong>Ciclos</strong>, ou seja, cobrado integralmente do primeiro 1º (primeiro) ao último dia de cada mês vigente. A cobrança é sempre referente ao rastreamento do mês anterior como, por exemplo, em fevereiro se paga o rastreamento do mês de janeiro, e assim por diante. Não há descontos ou possibilidade de pagamento proporcional para equipamentos retirados no mês a ser faturado.</p>
                    <p class="texto">4.11 - O contratante não poderá, jamais, recobrar da Contratada as despesas ou custos feitas com uso e gozo dos equipamentos locados.</p>
                    <h4>Cláusula 5 – DAS LIMITAÇÕES E ABRANGÊNCIA DOS SERVIÇOS</h4>
                    <p class="texto">5.1 - O contratante terá a possibilidade de acompanhar a situação de seu equipamento em todo o território nacional através do site da <strong>Federal Sistemas</strong> ou via aplicativo mobile, mediante cadastro prévio de usuário e senha, podendo emitir relatórios e demais informações de seu interesse.</p>
                    <p class="texto">5.2 - O contratante está ciente das limitações dos serviços concordando que, ainda que esteja com o veículo dentro da área de abrangência do serviço, podem ocorrer situações que prejudiquem e/ou impeçam momentaneamente o envio de comandos para o sistema embarcado no veículo, principalmente causadas por interferências eletromagnéticas, físicas, além das denominadas “<strong>Zonas de Sombra</strong>” como em interiores de túneis subterrâneo, proximidades de morro, serras, topografias diversas, entre outros. Concorda, ainda, que não poderá haver desconto ou abono de mensalidade de rastreamento pelo atraso ou dificuldade de transmissão de sinal de telefonia móvel ou redes de telecomunicações, uma vez que estas situações são alheias à atuação da <strong>Federal Sistemas</strong>.</p>
                    <p class="texto">5.3 - O contratante está ciente que é responsável pela avaliação periódica do funcionamento do sistema, devendo atestar seu funcionamento e informar a <strong>Federal Sistemas</strong> em casos de irregularidades ou indícios de defeitos do sistema embarcado nos equipamentos rastreados.</p>
                    <p class="texto">5.4 - É de conhecimento do contratante que a <strong>Federal Sistemas</strong> mantém salvo em servidor somente o registro dos 3 (três) últimos meses de rastreamento devendo, deste modo, o contratante fazer backups regularmente do sistema para salvaguardar os arquivos que possam lhe ser importantes. Em caso de desinstalação do equipamento, a <strong>Federal sistemas</strong> não manterá os registros do mesmo, sendo de total responsabilidade do contratante retirar as informações pertinentes antes da data da retirada.</p>
                    <p class="texto">5.5 - A contratada não será responsável por qualquer falha, atraso ou interrupção na prestação do serviço, proveniente de caso fortuito, força maior, áreas congestionadas de rádio frequência, utilização inadequada ou indevida do sistema por parte da contratante, ou ainda quaisquer outras causas fora de controle da contratada.</p>
                    <p class="texto">5.6 - A contratante deverá informar qualquer ocorrência que possa interferir no funcionamento do sistema, principalmente nos casos de envolvimento em acidentes de trânsito, instalação de outros equipamentos eletrônicos, tais como dispositivos de alarmes, sons, etc.</p>
                    <h4>Cláusula 6 – DO PRAZO </h4>
                    <p class="texto">6.1 - O presente contrato tem validade de 24 (vinte e quatro) meses a partir de sua assinatura; sendo que, após este período, será automaticamente renovado por iguais períodos e condições. Caso uma das partes intencione não renovar este contrato, deverá avisar o interesse à outra parte em até 30 (trinta) dias antes do prazo final, de forma idônea e comprovável, preferencialmente via e-mail.</p>
                    <p class="texto">6.2 - O contrato só poderá ser findado definitivamente e na totalidade caso haja assinatura entre as partes do “<strong>Distrato Contratual</strong>”, caso contrário este instrumento e todas as suas cláusulas continuam exercendo validade jurídica. O documento deverá ser encaminhado pela <strong>Federal Sistemas</strong> ao contratante para assinatura e nele deverá informar que não há nenhum tipo de pendência, principalmente financeira, oriundas da relação comercial entre as partes.</p>
                    <h4>Cláusula 7 – DOS CRITÉRIOS E CONDIÇÕES DE RESCISÃO CONTRATUAL</h4>
                    <p class="texto">7.1 - É motivo para a rescisão contratual entre as partes a data final de vencimento do contrato, tendo sido anunciado interesse entre as partes com no mínimo 30 dias anteriores e desde que não haja nenhuma pendência entre as partes, principalmente financeira.</p>
                    <p class="texto">7.2 - Em caso de inadimplência e impontualidade do contratante em relação aos pagamentos superior a 45 (quarenta e cinco) dias, a <strong>Federal</strong> poderá dar o contrato por rescindido mediante comunicação por escrito, ocasião em que o contratante será notificado para que efetue os pagamentos devidos, incluindo-se as parcelas vencidas até a efetiva devolução dos equipamentos instalados, sendo que fica previamente autorizado a retirada dos equipamentos instalados pela <strong>Federal</strong> Sistemas nos veículos do contratante, gerando ainda os custos de desinstalação ao contratante, de acordo com o anexo 1 (Tabela de Preços).</p>
                    <p class="texto">7.3 - Caso haja interesse de rescisão contratual antecipada de uma das partes sem motivo descrito nas cláusulas 7.1 e 7.2, que seja por livre vontade do contratante ou do contratado, a parte interessada deverá ressarcir a outra, a título de mora, 50% do valor remanescente do contrato.</p>
                    <p class="texto">7.4 – Quando da rescisão contratual, para todos os casos, o contratante obrigasse a disponibilizar e viabilizar, em uma das centrais de rastreamento da <strong>Federal Sistemas</strong>, o (s) veículo (s) para desinstalação de todos os equipamentos instalados pela contratada. Após esse prazo, o contratante ficará sujeito a pagamento da mensalidade e ao acréscimo de 20% no valor da mesma dos equipamentos instalados até a efetiva devolução, bem como serão faturados para pagamento pela contratante os demais componentes instalados, de acordo com o ANEXO 1 (tabela de preços).</p>
                    <p class="texto">7.5 - Os valores devidos pelo contratante à <strong>Federal Sistemas</strong>, oriundos deste contrato, só serão extinguidos quando do pagamento integral, ainda que a empresa contratante se apresente em situação e processo de Insolvência Civil, Recuperação Judicial ou Falência.</p>
                    <h4>Cláusula 8 - DISPOSIÇÕES GERAIS</h4>
                    <p class="texto">8.1 - O contratante obrigasse a manter frota mínima de 1 (um) equipamentos rastreados pela <strong>Federal Sistemas</strong>, tendo que ressarcir o valor das mensalidades caso não atinja a frota mínima estipulada.</p>
                    <p class="texto">8.2 - É reservado à contratada o direito de deixar de comercializar a qualquer tempo os equipamentos locados, caso em que comunicará o contratante com no mínimo 30 (trinta) dias de antecedência, por via postal, e-mail ou imprensa local, sem prejuízo para ambas as partes e que caberá a <strong>Federal Sistemas</strong> verificar a alternativa mais viável para este caso.</p>
                    <p class="texto">8.3 - Qualquer alteração da legislação tributária, regras ou pacote governamental que implique alteração do equilíbrio econômico do contrato, a <strong>Federal Sistemas</strong> poderá propor uma nova renegociação das disposições contratuais afetadas.</p>
                    <p class="texto">8.4 - O não exercício de direitos não implicará para qualquer das partes renúncia ou novação, tampouco aceitação tácita dos atos irregulares ou omitidos pela parte faltante.</p>
                    <p class="texto">8.5 - A eventual anulação de um dos itens do presente instrumento não invalidará as demais regras deste contrato.</p>
                    <h4>Cláusula 9 – DA IRRETRABILIDADE</h4>
                    <p class="texto">9.1 - O presente instrumento é celebrado em caráter irrevogável e irretratável, obrigando as partes e seus sucessores nas obrigações ora pactuadas.</p>
                    <h4>Cláusula 10 – DO FORO DE ELEIÇÃO</h4>
                    <p class="texto">10.1 - Fica eleito o Foro de Goianésia (GO), com exclusão de qualquer outro por mais privilegiado que seja, para dirimir dúvidas de interpretação ou execução decorrentes do presente contrato.</p>
                    <p class="texto">E, por estarem justas e contratadas, assinam o presente instrumento em duas vias de igual teor e forma, na presença de testemunhas, para que surta os regulares efeitos legais.</p>
                    <p class="texto">Goianésia, <?php echo FormDataBr($read_contrato_view['contrato_rastreamento_data_inicial']);?></p>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <br /><br /><br /><br /><br />
                    _______________________________________<br />
                    <div align="center" style="font-size:12px"><?php echo $read_contato_view['contato_nome_razao'];?></div>
                    <div align="center" style="font-size:12px"><?php echo $read_contato_view['contato_cpf_cnpj'];?></div>
                    <div align="center" style="font-size:12px; margin-bottom:0px">CONTRATANTE</div>
                </td>
                <td>
                    <br /><br /><br /><br /><br />
                    _______________________________________<br />
                    <div align="center" style="font-size:12px">Federal Sistemas de Segurança e Monitoramento Ltda</div>
                    <div align="center" style="font-size:12px">CNPJ: 11655954/0001-59</div>
                    <div align="center" style="font-size:12px; margin-bottom:85px">CONTRATADA</div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    _______________________________________<br />
                    <div align="center" style="font-size:12px">Testemunha</div><br /><br />
                    <div align="center" style="font-size:12px">CPF: ______________________________</div>
                </td>
                <td>
                    _______________________________________<br />
                    <div align="center" style="font-size:12px">Testemunha</div><br /><br />
                    <div align="center" style="font-size:12px">CPF: ______________________________</div>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <div style="page-break-after: always"></div>
                    <h4 align="center">ANEXO 1</h4>
                    <h4 align="center">TABELA DE PREÇOS</h4>
                    <ul type="a" class="texto">
                        <li><strong>Adesão:</strong> cadastro de cada veículo na plataforma de rastreamento (incluído custos de locação de sistema, administrativos e operacionais).</li>
                    </ul>
                    <p class="texto">Valor: <strong>R$ <?php echo FormatMoney($read_contrato_view['contrato_rastreamento_valor_adesao']);?> por equipamento.</strong></p>
                    <ul type="a" class="texto">
                        <li><strong>Instalação:</strong> instalação do aparelho rastreador e de todos os componentes necessários ao rastreamento veicular, cedidos ao cliente para utilização em forma de locação.</li>
                    </ul>
                    <p class="texto">Valor: <strong>R$ <?php echo FormatMoney($read_contrato_view['contrato_rastreamento_valor_instalacao']);?> por equipamento.</strong></p>
                    <ul type="a" class="texto">
                        <li><strong>Desinstalação:</strong> retirada do aparelho rastreador e devolução pela contratante à Federal Sistemas de todos os componentes necessários ao rastreamento veicular, cedidos ao cliente para utilização em forma de locação.</li>
                    </ul>
                    <p class="texto">Valor: <strong>R$ <?php echo FormatMoney($read_contrato_view['contrato_rastreamento_valor_desinstalacao']);?> por equipamento.</strong></p>
                    <ul type="a" class="texto">
                        <li><strong>Manutenção por defeitos causados:</strong> compreende-se como “defeitos causados” as ações e interferências humanas indevidas que, de algum modo, prejudique ou atrapalhe, impeça parcial ou integralmente a transmissão do sinal de rastreamento. São exemplos de defeitos causados os cortes de fios indevidos, solicitação de ordem de serviço com veículos parados em oficinas, manutenção por pessoal desautorizados, entre outros.</li>
                    </ul>
                    <p class="texto">Feita por técnico da Federal: <strong>R$ <?php echo FormatMoney($read_contrato_view['contrato_rastreamento_valor_manutencao']);?> por equipamento.</strong></p>
                    <ul type="a" class="texto">
                        <li><strong>Mensalidade do Rastreamento: R$ <?php echo FormatMoney($read_contrato_view['contrato_rastreamento_valor_mensalidade']);?></strong> (valor integral)</li>
                    </ul>
                    <ul type="a" class="texto">
                        <li><strong>Aparelho Rastreador:</strong> O aparelho é locado ao contratante, porém pode ser faturado ao contratante em caso de perda, roubo, extravio, defeito ocasionado por manutenção indevida e incorreta, entre outros.</li>
                    </ul>
                    <p class="texto">Valor: <strong>R$ <?php echo FormatMoney($read_contrato_view['contrato_rastreamento_valor_equipamento']);?> por equipamento.</strong></p>
                    <ul type="a" class="texto">
                        <li><strong>Quilometragem Rodada:</strong> Valor cobrado se for realizada, pela <strong>Federal Sistemas</strong>, manutenção, instalação, desinstalação ou outros, em locais a mais que 100 (cem) quilômetros de uma de nossas centrais de rastreamento.</li>
                    </ul>
                    <p class="texto">Valor: <strong>R$ <?php echo FormatMoney($read_contrato_view['contrato_rastreamento_valor_km']);?> por km rodado.</strong></p>
                    <ul type="a" class="texto">
                        <li><strong>Bloqueador (Instalação): R$ <?php echo FormatMoney($read_contrato_view['contrato_rastreamento_valor_instalacao_bloqueador']);?></strong> por equipamento instalado</li>
                    </ul>
                    <ul type="a" class="texto">
                        <li><strong>Bloqueador (Mensalidade): R$ <?php echo FormatMoney($read_contrato_view['contrato_rastreamento_valor_mensalidade_bloqueador']);?></strong> por equipamento instalado</li>
                    </ul>
                    <ul type="a" class="texto">
                        <li><strong>Sensores (Instalação): R$ <?php echo FormatMoney($read_contrato_view['contrato_rastreamento_valor_instalacao_sensor']);?></strong> por sensor instalado</li>
                    </ul>
                    <ul type="a" class="texto">
                        <li><strong>Sensores (Mensalidade): : R$ <?php echo FormatMoney($read_contrato_view['contrato_rastreamento_valor_mensalidade_sensor']);?></strong> por sensor instalado</li>
                    </ul>
                </td>
            </tr>
        </tbody>
    </table>
</html>