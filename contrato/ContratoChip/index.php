<?php
    session_start();
    ob_start();
    require_once '../../_class/Ferramenta.php';
    $Id = addslashes($_GET['Id']);
    $read_contrato = Read('contrato_chip', "WHERE contrato_chip_id = '".$Id."'");
    if(NumQuery($read_contrato) > '0'){
        foreach($read_contrato as $read_contrato_view);
        $read_contato = Read('contato', "WHERE contato_id = '".$read_contrato_view['contrato_chip_id_contato']."'");
        if(NumQuery($read_contato) > '0'){
            foreach($read_contato as $read_contato_view);
        }
    }
?>
<html>
<head>
<style>
	tbody { text-align:justify;}
	.texto {text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;}
</style>
<meta charset="UTF-8">
</head>
<body>
<table border="0" align="center" width="100%">
	<tbody>
            <tr>
                    <td colspan="3">
                    <h2 style="text-decoration:underline; font-size:18px; text-align:center; padding-top:10px; font-family:Verdana, Geneva, sans-serif; margin-bottom:10px;">CONTRATO DE PRESTAÇÃO DE SERVIÇOS DE DADOS E<br /> COMODATO DE CHIPS</h2>
                </td>
            </tr>
            <tr>
                    <td colspan="3">
                    <p class="texto">
                      <strong style="font-family: Arial; font-size: 14px;">Dados do Cliente:</strong><br /><br />
                      <strong style="font-family: Arial; font-size: 12px;">Razão Social: </strong><?php echo $read_contato_view['contato_nome_razao'];?><br />
                      <strong style="font-family: Arial; font-size: 12px;">Nome Fantasia: </strong><?php echo $read_contato_view['contato_nome_fantasia'];?><br />
                      <strong style="font-family: Arial; font-size: 12px;">Endereço: </strong><?php echo $read_contato_view['contato_endereco'];?>, <?php echo $read_contato_view['contato_numero'];?><br />
                      <strong style="font-family: Arial; font-size: 12px;">Bairro: </strong><?php echo $read_contato_view['contato_bairro'];?><br />
                      <strong style="font-family: Arial; font-size: 12px;">Cidade/UF: </strong><?php echo $read_contato_view['contato_cidade'];?>/<?php echo $read_contato_view['contato_estado'];?><br />
                      <strong style="font-family: Arial; font-size: 12px;">CEP: </strong><?php echo $read_contato_view['contato_cep'];?><br />
                      <strong style="font-family: Arial; font-size: 12px;">CNPJ / CPF: </strong><?php echo $read_contato_view['contato_cpf_cnpj'];?><br />
                      <strong style="font-family: Arial; font-size: 12px;">Telefone: </strong><?php echo $read_contato_view['contato_telefone'];?><br />
                  </p>
                  <p class="texto">
                      <strong style="font-family: Arial; font-size: 14px;">Dados do Proponente:</strong><br /><br />
                      <strong style="font-family: Arial; font-size: 12px;">Razão Social: </strong>Federal Sistemas de Segurança e Monitoramento Ltda.<br />
                      <strong style="font-family: Arial; font-size: 12px;">Nome Fantasia: </strong>Federal Sistemas<br />
                      <strong style="font-family: Arial; font-size: 12px;">Endereço: </strong>Av. Presidente Vargas N°254<br />
                      <strong style="font-family: Arial; font-size: 12px;">Bairro: </strong>Centro<br />
                      <strong style="font-family: Arial; font-size: 12px;">Cidade/UF: </strong>Ceres/GO<br />
                      <strong style="font-family: Arial; font-size: 12px;">Inscrição Estadual: </strong>10.4655410<br />
                      <strong style="font-family: Arial; font-size: 12px;">CNPJ: </strong>11.655.954/0001-59<br />
                      <strong style="font-family: Arial; font-size: 12px;">Telefone: </strong>(62) 3307-2871<br />
                  </p>
                    <h6>Contrato de Prestação de Serviço</h6>
                    <p class="texto">Pelo presente instrumento particular e na melhor forma de direito, as partes abaixo qualificada têm entre si justa e acordada a celebração do presente contrato, que entra em vigor na data indicada no presente instrumento e que se regerá pelas seguintes cláusulas e condições, que mutuamente aceitam e outorgam, a saber:</p>
                    <h4>1. DESCRIÇÃO DOS SERVIÇOS:</h4>
                    <p class="texto">1.1 Pelo presente instrumento, a <strong>FEDERAL SISTEMAS DE SEGURANÇA E MONITORAMENTO Ltda</strong>. para a prestação dos serviços abaixo discriminados, doravante designados simplesmente SERVIÇOS, visando viabilizar a gestão das redes de dados das operadoras de comunicação móvel, aos produtos a serem disponibilizados no mercado pela CONTRATANTE (doravante denominados simplesmente PRODUTOS):</p>
                    <p class="texto"><strong>1.1.1 Serviços Básicos</strong></p>
                    <ol type="a" class="texto">
                      <li>Operação de Conectividade: Destinado ao tráfego de dados entre a Operadora de Comunicações Móveis e a CONTRATANTE. Dessa forma, a CONTRATADA receberá os dados daquela, retransmitindo-os ou promovendo sua entrega à CONTRATANTE;</li>
                      <li>Serviço de entrega de informação: Os serviços executados pela Federal Sistemas de Segurança e Monitoramento Ltda., controlam a aquisição de dados da CONTRATANTE com a Operadora de Comunicações Móveis e sua disponibilização, através de conexão previamente definida e acordada entre a CONTRATANTE e a Federal Sistemas de Segurança e Monitoramento Ltda., através dos meios de comunicação que deverão ser analisados entre as partes, levando-se em conta os aspectos técnicos estabelecidos pela Federal Sistemas de Segurança e Monitoramento Ltda. </li>
                    </ol>
                    <p class="texto"><strong>1.1.2 Serviços Adicionais (Opcionais)</strong></p>
                    <ol type="a" class="texto">
                      <li>Serviço de Contagem de Dados (Conta Detalhada): Disponibilização de dados quantitativos das informações de tráfego pela Federal Sistemas de Segurança e Monitoramento Ltda, de acordo com as necessidades e especificações da CONTRATANTE.</li>
                      <li>Serviço de Bilhetagem: Emissão de faturas de acordo com os dados disponibilizados através do sistema de contagem.</li>
                    </ol>
                    <p class="texto">1.2 Pelo prazo de vigência do presente instrumento, a CONTRATANTE compromete-se a fazer uso dos SERVIÇOS única e exclusivamente, para atendimento às comunicações de rastreadores e sistema de alarme e maquinas de cartões.</p>
                    <p class="texto">1.3 Para a gestão de dados fora do território nacional, em razão do roaming da operadora, faz-se necessário entrar em contato e aderir á plano específico.</p>
                    <h6>2. DAS OBRIGAÇÕES DA FEDERAL SISTEMAS:</h6>
                    <p class="texto">2.1 A Federal Sistemas de Segurança e Monitoramento Ltda. deverá:</p>
                    <ol type="a" class="texto">
                      <li>Utilizar equipe treinada, credenciada e identificada para a prestação dos SERVIÇOS, zelando para que todos os SERVIÇOS prestados sejam realizados de forma adequada, em horário e local previamente estabelecido entre as partes;</li>
                      <li>Arcar com as despesas necessárias para a execução dos SERVIÇOS inclusive os de instalação e manutenção de infraestrutura, exceto no que se refere à conexão do Contratante aos Servidores de Serviços da Federal Sistemas de Segurança e Monitoramento Ltda. </li>
                      <li>Encaminhar à CONTRATANTE, periodicamente, tabela de preços vigentes para períodos determinados, nos termos do presente instrumento, respeitando esses preços e respectivas condições de pagamento.</li>
                    </ol>
                    <h6>3. DAS OBRIGAÇÕES DA CONTRATANTE:</h6>
                    <p class="texto">3.1 A CONTRATANTE deverá encaminhar à Federal Sistemas de Segurança e Monitoramento Ltda. pedidos de ativação ou de desativação de PRODUTOS, sendo responsável pelo pagamento tanto das tarifas de ativação ou de desativação (quando houver), quanto da taxa mensal de atendimento por PRODUTO, sendo esta última devida a partir do mês de ativação, até o mês em que a desativação for solicitada incluindo ate o dia 30 do ciclo.</p>
                    <p class="texto">3.2 A CONTRATANTE deverá apresentar, no ato da ativação, documento do EQUIPAMENTO DE COMUNICAÇÃO MÓVEL que será conectado à rede de transmissão de dados, através da Federal Sistemas De Segurança e Monitoramento Ltda. Este documento deverá conter as informações cadastrais do PRODUTO (ou somente do Rádio-modem utilizado), bem como seu endereçamento (IP, MAN e etc.), que será utilizado para fins de cadastro no sistema da Federal Sistemas de Segurança e Monitoramento Ltda.</p>
                    <p class="texto">3.3 A CONTRATANTE para os pedidos de ativação e desativação de Produtos deverá encaminhar à Federal Sistemas De Segurança e Monitoramento Ltda. a solicitação por pedido escrito, papel timbrado da empresa ou e-mail (endereço eletrônico, o que será atendido pela Federal em até 7 (sete) dias úteis.</p>
                    <p class="texto">3.4 A contratante possui ciência e concorda que no momento da solicitação do Chip (produto) o mesmo já sai da federal ativo, portanto já considerando que os serviços contratados já estão sendo gerados/prestados, incidindo a contra-prestação (pagamento).</p>
                    <p class="texto">3.5 A CONTRATANTE assume o compromisso de usar o Chip na modalidade contratada, sendo que o uso indevido do mesmo poderá ser responsabilizada, assumindo o pagamento e indenização para com a Federal Sistemas de Segurança e Monitoramento Ltda. de eventuais multas da Anatel e operadora.</p>
                    <h6>4. DA REMUNERAÇÃO</h6>
                    <p class="texto">4.1 Como contraprestação dos serviços prestados, a Federal Sistemas de Segurança e Monitoramento Ltda. fará jus, mensalmente, ao recebimento dos valores, pelo número de produtos ativados, identificados e discriminados, através de valores negociados e fixados por e-mail e/ou pedidos online, sendo os valores reajustados conforme repasses da operadora.</p>
                    <p class="texto">4.2 Fica pré-estabelecido que a remuneração refere-se à gestão de dados de no máximo ao plano contratado/mês, sendo que o excedente será cobrado da Contratante, o que concorda em quitar.</p>
                    <p class="texto">4.3 O atraso no pagamento dos valores devidos, em decorrência da prestação de SERVIÇOS prevista neste instrumento, gera a incidência de multa moratória automática de 2% (dois por cento) ao mês, bem como de juros de mora, ambos incidentes a partir do vencimento e sobre o valor total do débito pendente de pagamento, sendo expressamente autorizada a Contratada a enviar a comunicação de inadimplência aos serviços de proteção ao crédito.</p>
                    <p class="texto">4.4 Se a CONTRATANTE deixar de regularizar o eventual atraso no pagamento de qualquer valor, decorrente deste contrato, poderá a Federal Sistemas de Segurança e Monitoramento Ltda., a seu exclusivo critério, após 15 (Quinze) dias contados da data do vencimento da respectiva da fatura em atraso, cumulativamente ou não, descontinuar, no todo ou em parte, sem a necessidade comunicação prévia por escrito, a prestação dos serviços contratados.</p>
                    <p class="texto">4.5 No caso da CONTRATANTE sofrer o bloqueio/suspensão dos serviços contratados, por falta, atraso de pagamento ou pagamento parcial/incompleto (Clausula 4.3), a Federal Sistemas de Segurança e Monitoramento Ltda. não será responsabilizada por qualquer evento danoso que posso ocorrer com a CONTRATANTE e ou seus clientes.</p>
                    <p class="texto">4.6 A CONTRATANTE verificando eventuais diferenças de consumo, faturamento e ou falhas, bem como eventuais interrupções de funcionamento, ou quaisquer reclamações, deverá comunicar por escrito a Federal Sistemas de Segurança e Monitoramento Ltda., no prazo impreterível de 15 dias contados da ocorrência.</p>
                    <p class="texto">4.7 Na hipótese de na data de pagamento a CONTRATANTE não ter recebido o boleto/duplicata para pagamento, deverá imprimir através do site, por contato telefônico ou mesmo solicitar a 2ª via através de e-mail à FEDERAL SISTEMAS.</p>
                    <p class="texto">4.8 A CONTRATANTE efetuando qualquer pagamento através de depósito deverá comunicar e encaminhar o referido comprovante para a Federal Sistemas de Segurança Ltda., com a devida identificação, através de email.</p>
                    <h6>5. DO PRAZO</h6>
                    <p class="texto">5.1 O presente contrato terá duração de 12(doze) meses, contados a partir da assinatura do Termo de Contratação de Serviços e Comodato.</p>
                    <p class="texto">5.2 Caso não haja manifestação por escrito até 30 (trinta) dias antes do vencimento do presente contrato, este se considerará renovado por igual período, nos mesmos termos já contratados, sem prejuízo de reajuste dos valores das mensalidades.</p>
                    <p class="texto">5.3 Exceto no que se refere à Cláusula 4.3. Acima, o presente contrato será automaticamente rescindido em caso uma eventual insolvência, falência ou concordata de qualquer das partes ou, se de algum modo, for admitida a sua insolvência.</p>
                    <h6>6. DAS DISPOSIÇÕES FINAIS</h6>
                    <p class="texto">6.1 A FEDERAL é a única e exclusiva responsável por todos e quaisquer ônus decorrentes da legislação trabalhista e previdenciária, bem como pelo pagamento dos tributos a que der causa e por eventuais reclamações e encargos trabalhistas, com relação à mão-de-obra por ela contratada e empregada, em decorrência do presente contrato.</p>
                    <p class="texto">6.2 Inexiste qualquer vínculo empregatício entre a CONTRATANTE e os funcionários e/ou prepostos da Federal Sistemas de Segurança Ltda., a qualquer título e inexiste qualquer vínculo empregatício entre a Federal Sistemas de Segurança Ltda. e os funcionários e/ou prepostos da CONTRATANTE, a qualquer título. Dessa forma, as partes comprometem-se reciprocamente a manter uma a outra a salvo de toda e qualquer medida ou ação eventualmente levada a efeito por funcionários ou prepostos da outra.</p>
                    <p class="texto">6.3 Nenhuma das partes poderá ceder ou transferir a terceiros, no todo ou em parte, os direitos e obrigações decorrentes deste instrumento, sem autorização prévia e por escrito da outra parte.</p>
                    <p class="texto">6.4 Eventuais alterações a este contrato somente serão válidas se celebradas por escrito entre as partes e devidamente registradas.</p>
                    <p class="texto">6.5 A eventual demora ou omissão de uma parte em exigir o cumprimento deste contrato pela outra parte, não será considerada novação das obrigações aqui constantes, podendo tal exigência ser feita a qualquer tempo.</p>
                    <p class="texto">6.6 A Federal Sistemas de Segurança Ltda. não se responsabiliza pelo conteúdo da informação trafegada, limitando-se apenas à integridade dos dados por ela transmitidos. Desta forma, a informação trafegada através dos servidores da Federal Sistemas de Segurança Ltda. não sofrerá modificação de conteúdo desde sua entrada, seja pela porta de conexão com a OPERADORA ou com a CONTRATANTE, até a sua saída pela porta de conexão com a CONTRATANTE ou com a OPERADORA respectivamente.</p>
                    <p class="texto">6.7 A Federal Sistemas de Segurança Ltda. não é responsável perante a CONTRATANTE, bem como perante os CLIENTES DA CONTRATANTE, pela qualidade dos PRODUTOS e/ou serviços prestados por terceiros não podendo assim, em qualquer hipótese ou sob qualquer circunstância, ser responsabilizada por eventuais ocorrências advindas de fato de terceiros, notadamente, mas não limitadamente, por problemas de integradores/softwares ou hardwares que componham o PRODUTO.</p>
                    <p class="texto">6.8 A operação de conectividade e de tráfego de dados pela é limitada ao valor total pago pela CONTRATANTE à CONTRATADA</p>
                    <p class="texto">6.9 A Federal Sistemas de Segurança Ltda. não será responsável pelos serviços prestados pelas operadoras de comunicação sendo, desta maneira, isenta de qualquer queda de tráfego de comunicação dos dados, que ocorra fora da Federal Sistemas de Segurança Ltda., os quais estão limitados, pelo lado das operadoras, as conexões de comunicação (linhas privadas de comunicação de dados), e pelo lado da CONTRATANTE, até o roteador de comunicação ao meio de transmissão do sistema de entrega especificado de acordo com a cláusula 1.1.1.b.</p>
                    <p class="texto">6.10 A Federal Sistemas de Segurança Ltda. é a única e legítima titular dos softwares e aplicativos informatizados e, eventualmente, de alguns equipamentos instalados nas dependências do CONTRATANTE para a prestação dos serviços objeto deste contrato, detendo o CONTRATANTE apenas e tão-somente a eventual posse(em caráter precário) desses equipamentos. Federal Sistemas de Segurança Ltda. detém todos os direitos de propriedade referentes aos SERVIÇOS. Todo(s) o(s) software(s), código(s) de fonte e de objeto (source code e object code), especificações, planos, processos, técnicas, conceitos, melhorias, descobertas e invenções realizadas ou desenvolvidas com relação aos serviços são e serão de propriedade e titularidade exclusiva da Federal Sistemas de Segurança e Monitoramento Ltda.</p>
                    <p class="texto">6.11 O CONTRATANTE não poderá, em hipótese alguma: (i) vender, transferir, arrendar, licenciar, ou sublicenciar quaisquer dos direitos conferidos pelo presente Contrato; (ii) fazer a descarga de dados (download) ou de outra forma obter cópia de qualquer software; (iii) descompilar, desmontar, ou deduzir o código de acesso de qualquer software; (iv) permitir acesso ao SERVIÇO a terceiros, além daqueles expressamente permitidos pelo presente Contrato; ou (v) utilizar o SERVIÇO para fornecer serviços a terceiros, ou ainda utilizá-lo para venda de serviços, a menos que de outra forma previsto neste contrato.</p>
                    <p class="texto">6.12 É expressamente vedado ao CONTRATANTE, por si, seus empregados, prepostos ou terceiros, ceder, modificar, reorganizar, desconectar, remover, reparar equipamentos de propriedade ou titularidade da Federal Sistemas de Segurança e Monitoramento Ltda., conforme seja aplicável.</p>
                    <p class="texto">6.13 No preço acordado não está inserida qualquer previsão inflacionária, na presunção de que a economia se manterá estável e, no que se refere aos insumos importados, de que o câmbio não sofrerá variações relevantes. Nesse sentido, ainda, serão aplicadas ao contrato as disposições legais referentes ao seu equilíbrio econômico-financeiro e à revisão dos preços contratuais ou o reajuste.</p>
                    <p class="texto">6.14 A Federal Sistemas de Segurança e Monitoramento Ltda. não é responsável por perdas, lucros cessantes, danos diretos ou indiretos, incidentes ou conseqüentes, ou multas decorrentes da utilização dos serviços pelo CONTRATANTE em desacordo com este contrato.</p>
                    <p class="texto">6.15 A Federal Sistemas de Segurança Ltda. não se responsabiliza, bem como não presta o serviço de localização do GPRS, sendo sempre necessária a empresa de monitoramento.</p>
                    <p class="texto">6.16 A Federal Sistemas de Segurança Ltda. não disponibiliza mecanismos de segurança da rede do CONTRATANTE, sendo do CONTRATANTE a responsabilidade pela preservação de seus dados, bem como pela introdução de restrições de acesso e controle de sua rede.</p>
                    <p class="texto">6.17 A Federal Sistemas de Segurança Ltda. controlará, em caráter preventivo, a existência de vírus durante a transmissão de informações. Entretanto, não garante a absoluta ausência de vírus por força das disposições contratuais constantes dos softwares a ela licenciados por terceiros, bem como de outros elementos nocivos que possam produzir alterações em seu sistema informático (software e hardware) ou nos documentos eletrônicos e arquivos armazenados em seu sistema informático.</p>
                    <p class="texto">6.18 A Federal Sistemas de Segurança Ltda. se exime de qualquer responsabilidade pelos danos e prejuízos de qualquer natureza que possam decorrer da presença de vírus ou de outros elementos nocivos nos conteúdos e que, desta forma, possam produzir alterações e/ou danos no sistema físico e/ou eletrônico dos equipamentos do CONTRATANTE.</p>
                    <p class="texto">6.19 A Federal Sistemas de Segurança Ltda. se exime, ainda, integralmente de qualquer responsabilidade por custos, prejuízos e/ou danos causados ao CONTRATANTE ou a terceiros por:</p>
                    <ol type="a" class="texto">
                      <li>Conteúdo, propaganda, produtos, serviços contidos ou oferecidos em sites visitados através de "links" oferecidos;</li>
                      <li>Negociações de qualquer natureza, promovidas pela CONTRATANTE, envolvendo usuários e anunciantes ou titulares de sites apontados, incluindo participação em promoções e sorteios, contratação de serviços ou fornecimento de mercadorias;</li>
                      <li>Falhas no acesso aos serviços, desde que comprovada à inexistência de culpa ou dolo da – Federal Sistemas de Segurança Ltda.</li>
                      <li>Atos de terceiros que possam prejudicar o uso do SERVIÇO ou problemas na Conexão com os servidores e provedores de acesso, bem como não será responsável pelos transtornos e prejuízos, como perda de dados e interrupção dos serviços, causados por erro, omissão ou negligência das companhias telefônicas estaduais, problemas no browser, queda de energia elétrica e outros fatos decorrentes de caso fortuito e força maior.</li>
                    </ol>
                    <p class="texto">6.20 Nenhuma das partes deste Contrato poderá ser responsabilizada pelo inadimplemento das obrigações assumidas ou pelas perdas e danos causados pelo descumprimento ou pela mora na execução deste instrumento se tal inadimplemento, descumprimento ou mora resultar de eventos de caso fortuito ou força maior, ou qualquer ato de terceiro.</p>
                    <p class="texto">6.21 Para os fins deste contrato, caso fortuito, força maior e/ou ato de terceiro significam todo e qualquer evento fora e além do controle das partes, imprevisíveis e insuperáveis, que tornem impossível a consecução das obrigações previstas neste contrato.</p>
                    <p class="texto">6.22 Uma parte somente poderá alegar a ocorrência de um evento de caso fortuito, força maior e/ou ato de terceiro se fizer um esforço contínuo e de boa-fé para diminuir ou evitar os efeitos do evento de força maior, caso fortuito ou ato de terceiro sobre a outra parte.</p>
                    <p class="texto">6.23 Qualquer notificação a ser enviada a outra parte deverá ser feita mediante telegrama, e-mail, fac-símile, carta ou qualquer outra forma de comunicação registrada.</p>
                    <p class="texto">6.24 Se, a qualquer momento, durante a vigência deste Contrato, qualquer cláusula do mesmo for julgada inexecutável ou sem efeito pelo Foro de Jurisdição Competente, tal cláusula deverá ser modificada apropriadamente segundo a lei ou, se tal modificação destruir a intenção das partes, tal cláusula deverá ser eliminada e este Contrato deverá ser interpretado sem referência à mesma.</p>
                    <p class="texto">6.25 Os valores previstos neste instrumento, ressalvadas as outras hipóteses aqui contempladas, sujeitam-se a atualização monetária com base nos valores repassados pela operadora.</p>
                    <p class="texto">6.26 A Federal Sistemas de Segurança e Monitoramento e a CONTRATANTE se comprometem a não divulgar informações confidenciais decorrentes deste contrato.</p>
                    <h6>7. DA FORO</h6>
                    <p class="texto">7.1 As partes elegem o Foro da Comarca de Ceres-Go, como competente para conhecer e julgar as questões que eventualmente decorram deste contrato, renunciando desde já qualquer outro, por mais privilegiado que seja. E, por estarem assim ajustadas, as partes assinam o presente instrumento em 02 (duas) vias de igual teor e forma e declaram que:</p>
                    <ol type="a" class="texto">
                      <li>Compreendem e aceitam o contrato de prestação de serviço da Federal Sistemas de Segurança e Monitoramento Ltda. que será assinado entre as partes.</li>
                      <li>Seus dados constantes acima são verdadeiros e corretos, obrigando-se a informar a Federal Sistemas de Segurança e Monitoramento Ltda qualquer alteração nos mesmos.</li>
                    </ol>
                    <h6>Anexo 01: Observações Gerais</h6>
                    <p class="texto">- Uma unidade só será considerada desativada quando for devolvida ou paga o valor de R$ 15,00 por chip a Federal Sistemas de Segurança e Monitoramento Ltda.</p>
                    <p class="texto">- (Somente para chips Vodafone mais) Quando o CLIENTE realizar tráfego nas redes denominadas Vodafone mais (oi,claro,vivo), mas em proporção maior que 50%(cinquenta por cento) dos MB do plano contrato em cada um dos acessos, o uso do sim card em tais redes será bloqueado até o inicio da próxima franquia mensal sendo transmitido no restante do ciclo pela rede Vodafone (tim).</p>
                    <p class="texto">- (Somente para chips VIVO) O Cliente terá acesso a plataforma de controle de dados online.</p>
					<p class="texto">- Os valores referentes ao tráfego excedente serão faturados no mês subseqüente à utilização.</p>
                    <p class="texto">- É obrigatório o uso da APN indicada pela Federal Sistemas de Segurança e Monitoramento Ltda.</p>
                    <h6>Anexo 02: Condições Comerciais</h6>
                    <h6>Regra de Tarifação</h6>
                    <p class="texto">Total a ser cobrado = quantidade de unidades x Taxa mensal + R$ 3,00 x gestão de quantidade de MB excedente em cada unidade</p>
                    <h6>Método de Contagem do Tráfego Excedente</h6>
                    <p class="texto">Lotes de 1 Kbyte excedentes ao pacote.</p>
                    <h6>Condição de Pagamento (Ativação + Serviço) </h6>
                    <p class="texto">Fechamento de período – Fatura enviada para o pagamento para o décimo dia de cada mês. </p>
                    <br />
                    <div align="center"><?php echo FormDataBr($read_contrato_view['contrato_chip_data_inicial']);?></div>
                    <br />
                    <br />
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <br /><br />_______________________________________<br />
                    <div align="center" style="font-size:12px"><?php echo $read_contato_view['contato_nome_razao'];?></div>
                    <div align="center" style="font-size:12px"><?php echo $read_contato_view['contato_cpf_cnpj'];?></div>
                    <div align="center" style="font-size:12px; margin-bottom:0px">CONTRATANTE</div>
                </td>
                <td>
                    <br /><br />_______________________________________<br />
                    <div align="center" style="font-size:12px">Federal Sistemas de Segurança e Monitoramento Ltda</div>
                    <div align="center" style="font-size:12px">CNPJ: 11655954/0001-59</div>
                    <div align="center" style="font-size:12px; margin-bottom:0px">CONTRATADA</div>
                </td>
            </tr>
        </tbody>
</table>
</body>
</html>
