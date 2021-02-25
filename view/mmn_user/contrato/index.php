<?php
    session_start();
    ob_start();
    require_once '../../../_class_mmn/Ferramenta.php';
    $Id = addslashes($_GET['id']);
	
	$read_user = Read('user', "WHERE user_id = '".$Id."' LIMIT 1");
	if(NumQuery($read_user) > '0'):
		foreach($read_user as $read_user_view):
		endforeach;
	endif;
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
    <tbody>
    	<tr>
        	<td colspan="3">
            	<h1 style="text-decoration:underline; font-size:18px; text-align:center; padding-top:25px; font-family:Verdana, Geneva, sans-serif; margin-bottom:55px;">CONTRATO DE LOCAÇÃO DE CHIP DE DADOS E PRESTAÇÃO DE SERVIÇO DE TELEFONIA 4G</h1>
            </td>
        </tr>
        <tr>
        	<td colspan="3">
                <p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">
                    Pelo presente instrumento particular de Contrato, de um lado:
                </p>    
            	<p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">
                    <strong style="font-family: Arial; font-size: 13px;">Nome: </strong><span class="CLIENTE_NOME_FANTASIA token_d4s"><?php echo $read_user_view['user_nome'];?></span><br>

                    <strong style="font-family: Arial; font-size: 13px;">CPF: </strong><span class="CLIENTE_CPF_CNPJ token_d4s"><?php echo $read_user_view['user_cpf'];?></span><br>
                    <strong style="font-family: Arial; font-size: 13px;">Fones: </strong><span class="CLIENTE_TELEFONE token_d4s"><?php echo $read_user_view['user_telefone'];?></span> <strong style="font-family: Arial; font-size: 13px;">Fone 2: </strong><span class="CLIENTE_CELULAR token_d4s"><?php echo $read_user_view['user_celular'];?></span><br>
                    <strong style="font-family: Arial; font-size: 13px;">Endereço: </strong><span class="CLIENTE_ENDERECO token_d4s"><?php echo $read_user_view['user_endereco'];?></span>, <span class="CLIENTE_NUMERO token_d4s"><?php echo $read_user_view['user_numero'];?></span> - <span class="CLIENTE_BAIRRO token_d4s"><?php echo $read_user_view['user_bairro'];?></span>, <span class="CLIENTE_CIDADE token_d4s"><?php echo $read_user_view['user_cidade'];?></span> - <span class="CLIENTE_UF token_d4s"><?php echo $read_user_view['user_uf'];?></span><br>
                    <strong style="font-family: Arial; font-size: 13px;">Cep: </strong><span class="CLIENTE_CEP token_d4s"><?php echo $read_user_view['user_cep'];?></span><br>
                    <strong style="font-family: Arial; font-size: 13px;">Email: </strong><span class="CLIENTE_EMAIL token_d4s"><?php echo $read_user_view['user_email'];?></span><br>
                    
                </p>
				<p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">
                    Doravante denominada CONTRATANTE, e de outro lado, a parte CONTRATADA:
                </p>  
				<p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">
                    Contratado: Federal Sistemas de Segurança e Monitoramento Ltda. – ME CNPJ: 11.655.954/0001-59<br>
					Fones: (62) 3353-4350 e (62) 3307-2871<br>
					E-mails: financeiro@federalsistemas.com.br / suporte@federalsistemas.com.br / diretor@federalsistemas.com.br / contato@federalsistemas.com.br<br>
					Site: www.federalsistemas.com.br Endereço Avenida Presidente Vargas, nº 254 Centro, Ceres (GO)<br><br>
					<strong>Ambas têm entre si, justos e contratados o que se segue:</strong>
                </p>
                <h4>1. DO OBJETO</h4>
                <p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">1.1 - Estabelecer as condições gerais de locação de chip de dados 4G (operadora VIVO) e prestação de serviços de telefonia móvel pessoal pela Federal Sistemas ao Contratante.</p>
                <p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">1.2 - Especificar, formalizar e fazer o cadastramento do Contratante na condição de DISTRIBUIDOR INDEPENDENTE junto ao sistema de MMN (Marketing Multi nível) da empresa, para que possa ter acesso às ferramentas disponíveis no site da Federal Sistemas, com a finalidade de participação ao PIB – Programa de Benefício por Indicação oferecido pela empresa.</p>
                
                <h4>2. DO SERVIÇO DE TELEFONIA (INTERNET E VOZ) CONTRATADO</h4>
                <p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">2.1 - A Federal Sistemas locará ao Contratante chip 4G para que este possa utilizá-lo em suas atividades pessoais, realizar ligações para todas as operadoras (mesmo DDD) e navegar na internet, conforme descrição dos planos no quadro abaixo:</p>
				<table border="1" width="700px">
					<tr align="center">
						<td>INTERNET</td>
						<td>CHAMADAS</td>
						<td>VALOR DO PLANO</td>
					</tr>
					<tr align="center">
						<td style="font-size: 10px;">Mensal</td>
						<td style="font-size: 10px;">Todas as operadoras(mensal)</td>
						<td style="font-size: 10px;">Mensal</td>
					</tr>
					<tr align="center">
						<td style="font-size: 12px;">5 GB Ilimitada *</td>
						<td style="font-size: 12px;">X</td>
						<td style="font-size: 12px;">R$ 49,90</td>
					</tr>
					<tr align="center">
						<td style="font-size: 12px;">5 GB Ilimitada *</td>
						<td style="font-size: 12px;">200 min</td>
						<td style="font-size: 12px;">R$ 79,90</td>
					</tr>
					<tr align="center">
						<td style="font-size: 12px;">5 GB Ilimitada *</td>
						<td style="font-size: 12px;">Ilimitada</td>
						<td style="font-size: 12px;">R$ 99,90</td>
					</tr>
					<tr align="center">
						<td style="font-size: 12px;">20 GB Ilimitada *</td>
						<td style="font-size: 12px;">X</td>
						<td style="font-size: 12px;">R$ 99,90</td>
					</tr>
					<tr align="center">
						<td style="font-size: 12px;">20 GB Ilimitada *</td>
						<td style="font-size: 12px;">Ilimitada</td>
						<td style="font-size: 12px;">R$ 149,90</td>
					</tr>
					<tr align="center">
						<td style="font-size: 12px;">40 GB Ilimitada *</td>
						<td style="font-size: 12px;">X</td>
						<td style="font-size: 12px;">R$ 149,90</td>
					</tr>
					<tr align="center">
						<td style="font-size: 12px;">40 GB Ilimitada *</td>
						<td style="font-size: 12px;">Ilimitada</td>
						<td style="font-size: 12px;">R$ 199,90</td>
					</tr>
				</table>
                <p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">2.2 - O Contratante não possui autonomia sobre a linha telefônica, que poderá ser migrada ou substituída quando necessária. Em caso de migração e substituição de linha a Federal Sistemas estará totalmente isenta de responsabilidade, danos e perdas que o Contratante possa ter oriundas da migração da linha telefônica.</p>
				<p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">2.3 - Para qualquer tipo de Plano de Serviço escolhido, o Contratante poderá, a qualquer tempo, solicitar a transferência de plano, sendo de sua exclusiva responsabilidade arcar com os custos oriundos da alteração.</p>
				<p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">2.4 - O chip será locado ao Contratante, em regime de comodato, devendo ser devolvido à Federal Sistemas ou faturado em nome do Contratante quando da rescisão contratual de forma que o mesmo deve realizar o pagamento dos valores em aberto em seu nome. 2.3.1- Em caso de perda do chip o cliente é totalmente responsável pelo mesmo, assim devendo arcar com o valor de R$15,00 (quinze reais).</p>
				<p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">2.5 - A Federal Sistemas recomenda a utilização do chip de dados 4G apenas em aparelhos celular, sendo que a velocidade e capacidade de download da internet dependerá da área em que os dados estiverem trafegando, podendo variar dependendo da situação, considerando a área de abrangência do serviço de telefonia móvel VIVO.</p>
				<p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">2.6 - A Federal Sistemas fornecerá internet ilimitada para todos os planos da cláusula 2.1, porém a transmissão de dados para internet poderá ser reduzida quando o Contratante atingir o consumo total da franquia contratada no mês de utilização, tendo seu consumo liberado integralmente no dia 25 do mês seguinte.</p>
				<p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">2.7 - O Contratante poderá realizar chamadas de voz para todas as operadoras que possuam o mesmo DDD, de acordo com o plano e limites contratado na cláusula 2.1. Ao atingir o limite de minutos contratado no mês de utilização, a realização de chamadas de voz poderá ser bloqueada, sendo liberada integralmente quando da renovação do plano no mês seguinte.</p>
				<p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">2.8 - Não está incluído ou autorizado nos planos o envio de mensagens (SMS) através do chip 4G. Apenas estará habilitado o recebimento regular de mensagens SMS e o envio de mensagens sem saldo, na modalidade de “quem recebe é que paga”, comumente conhecido como “Torpedo a Cobrar”.
</p>
				<p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">2.9 - O chip de dados não poderá ser utilizado em centrais, módulos GPRS, modens, sistemas de compartilhamento de dados, fins comerciais ou empresariais, ou outros que não sejam em aparelhos celulares de uso pessoal/individual. Caso a Federal Sistemas perceba ou identifique uso indevido do chip, poderá fazer o bloqueio do Contratante por 10 dias até conclusão e decisão sobre a análise.</p>
				
				
				
                <h4>3. DO PROGRAMA DE BENEFÍCIO POR INDICAÇÃO</h4>
                <p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">3.1 - O PBI (Programa de Benefício por Indicação) é uma forma que a Federal Sistemas encontrou para recompensar e agradecer aos nossos clientes e parceiros que nos indicarem a novos clientes, gerando novos contratos à nossa empresa.</p>
                <p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">3.2 - O Contratante poderá se vincular ao PBI (Programa de Benefício por Indicação) vigente da Federal Sistemas, onde este receberá um bônus financeiro, de acordo com os índices percentuais pré-estabelecidos e normas de instrução disponíveis no site, por cada indicação sua que celebrar contrato de Telefonia com a nossa empresa.</p>
                <p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">3.3 - O Contratante poderá fazer controle e gestão do PBI (Programa de Benefício por Indicação), bem como realizar indicações, através de plataforma utilizada e autorizada pela Federal Sistemas, mediante cadastro prévio de usuário e senha.</p>
                <p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">3.4 - O Contratante não pagará adicionais por participação no PBI (Programa de Benefício por Indicação), não pagará pela utilização da plataforma e também é totalmente desobrigado de fazer indicações, ou seja, só irá participar efetivamente do programa se for de seu interesse.</p>
                <p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">3.5 - Se optar por participar do PBI (Programa de Benefício por Indicação), o Contratante reconhece que não fará parte do corpo de colaboradores/funcionários efetivos da Federal Sistemas, não gerando entre as partes nenhum ônus, obrigação ou responsabilidade de ordem trabalhista, e nem mesmo haverá garantia ou certeza de rendimento, lucro, sucesso ou qualquer vantagem por ter aderido ao programa.</p>
                <p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">3.6 - A Federal Sistemas poderá a qualquer momento, com ou sem aviso prévio, rever e alterar as regras e participação do Contratante no PBI (Programa de Benefício por Indicação), não podendo ser responsabilizada em nenhuma hipótese por perdas ou danos que o Contratante possa ter pelas alterações no programa.</p>
                <p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">3.7 - Não é autorizada a venda dos chips por sites de vendas (Ex: mercado livre, OLX entre outros). 3.7.1 – Durante as negociações o Indicador deverá deixar explicito que é um cliente da rede, jamais deverá se passar por um colaborador da FEDERAL SISTEMAS, sob pena de perda do seu BackOffice. 3.7.2 –Não é autorizada o repasse de informações erronia sobre os planos ofertados, sob pena de perda do BackOffice incluindo os valores disponíveis para saque que o Indicador possui.</p>
                
                <h4>4. DAS LIMITAÇÕES DO SERVIÇO</h4>
                <p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">4.1 - O Contratante declara, neste ato, ter conhecimento que os serviços de acesso à internet e voz são fornecidos através da utilização da tecnologia 4G (LTE), 3G (HSDPA) ou GPRS,
 
sujeitos, por sua própria natureza, a oscilações e/ou variações de sinal e velocidade de tráfego de dados, em razão de condições topográficas, geográficas, urbanas, climáticas, velocidade de movimento, distância e disponibilidade da rede de telefonia, configuração do hardware e software do equipamento (telefone) utilizado pelo Contratante, tráfego de dados na internet, dentre outros fatores que podem interferir na intensidade do sinal.
</p>
                <p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">4.2 - O Contratante, portanto, tem conhecimento que os serviços poderão eventualmente ser afetados, ou temporariamente interrompidos, não sendo a Federal Sistemas responsável por eventuais falhas, atrasos ou interrupções na prestação de serviços.</p>
                <p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">4.3 - A Federal Sistemas não poderá ser responsabilizada pela interrupção ou dificuldade de sinal contidos nas cláusulas 4.1 e 4.2, de modo que o Contratante não será desobrigado do pagamento do valor do plano, considerando as condições contratadas.</p>
                
                <h4>5. DO VALOR</h4>
                <p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">5.1 – Pela consecução integral deste Contrato, o Contratante pagará a Federal Sistemas o valor mensal do plano contratado, sendo R$ 49,90 (plano 5GB), R$ 79,90 (plano 5GB + 200min) R$ 99,90 (plano 5GB + Minutos Ilimitados), R$ 99,90 (Plano 20GB), R$ 149,90 (plano 20GB + Minutos Ilimitados), R$ 149,90 (plano 40GB) ou R$ 199,90 (plano 40GB + Minutos Ilimitados) conforme descrito na cláusula 2.1.</p>
				<p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">5.2 - O Contratante arcará com os custos de envio do (s) chip (s) contratados e caso exceda o limite de voz contratado, ficará previamente autorizado que a Federal cobre o excedente, sendo R$ 1,00 (um real) por minuto.</p>
                <p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">5.3 - O Contratante receberá mensalmente, via e-mail cadastrado, o boleto bancário para pagamento da parcela ou poderá imprimi-lo, utilizando CPF cadastrado, no site da Federal Sistemas. 5.3.1- Ou caso o mesmo prefira pode estar sempre acessando suas faturas em seu BackOffice.</p>
				<p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">5.4 - Caso não receba os boletos de pagamento, será dever do Contratante solicitar à Federal Sistemas os boletos bancários para pagamento dos serviços contratados, não podendo alegar, em nenhuma hipótese, atraso ou falta de pagamento, bem como desconhecimento da dívida, por não ter recebido boleto/faturamento. Fica acordado, ainda, que a liquidação do pagamento pelo contratante deverá ser feita, impreterivelmente, no dia acordado para que não sejam geradas cobranças adicionais, como juros e multas de atraso.</p>
                <p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">5.5 - O Contratante declara ser o único responsável pelo pagamento dos boletos/parcelas, tarifas e preços decorrentes deste Contrato, independentemente de quem tenha utilizado o seu chip contratado.</p>
                
                <h4>6. DO ATRAZO DE PAGAMENTO</h4>
                <p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">6.1 - O atraso na liquidação de pagamento, por parte do Contratante, acarretará 2% de multa do valor da (s) parcela (s) em atraso, correção monetária de acordo com a variação de IGPM/FGV ou de qualquer outro índice que possa substituí-lo, além de atualização diária de juro taxada a 0,2%. O atraso de pagamentos poderá, ainda, acarretar o registro e negativação
 
do Contratante pela Federal Sistemas nos órgãos de proteção ao crédito, com ou sem aviso prévio ao Contratante.
</p>
                <p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">6.2 - O atraso de pagamento das parcelas poderá acarretar, ainda, o bloqueio/suspensão de dados e chamadas da linha contratada, o que não isenta o cliente do pagamento das parcelas vencidas e do ciclo vigente.</p>
                <p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">6.3 - Caso o Contratante atrase o pagamento por mais de 10 (Dez) dias após seu vencimento, ficará já autorizado que a Federal Sistemas faça reutilização da linha do Contratante, ou seja, migre o número vinculado ao chip do Contratante para um outro cliente, que poderá utilizá-lo livremente, conforme suas necessidades.</p>
                <p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">6.4 - Em caso de reutilização de linhas, conforme cláusula 6.3, a Federal Sistemas estará totalmente isenta de responsabilidade, danos e perdas que o Contratante possa ter oriundas da migração da linha telefônica.</p>
				<p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">6.5 - O atraso de pagamento pelo Contratante poderá acarretar, ainda, o bloqueio ou suspensão de sua participação no PBI (Programa de Benefício por Indicação), não podendo solicitar saldos disponíveis na plataforma.</p>
				<p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">6.6 - Em caso de suspenção do serviço por inadimplência o tempo para reativação após o pagamento e comunicado realizado pelo Contratante à Federal Sistemas é de até 3 dias úteis (72 horas úteis).</p>
				
				
                <h4>7. DOS CRITÉRIOS E CONDIÇÕES DE RESCISÃO CONTRATUAL</h4>
                <p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">7.1 - Mesmo em caso de inadimplência o contrato só poderá ser rescindido mediante solicitação do contratante por escrito, via e-mail.</p>
                <p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">7.2 - Em caso de inadimplência e impontualidade do Contratante em relação aos pagamentos superior a 60 (sessenta) dias, a Federal Sistemas poderá estar levando o caso aos órgãos de proteção ao crédito e realizando uma negativação do nome do Contratante.</p>
                <p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">7.3 - Quando da rescisão contratual, para todos os casos, fica previamente autorizado que a Federal Sistemas fature o valor do chip (R$ 15,00) para que possa ser liquidado pelo Contratante e mais o valor do ciclo em vigor.</p>
                <p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">7.4 – Os valores devidos pelo contratante à Federal Sistemas, oriundos deste contrato, só serão extinguidos após o pagamento integral, ainda que a pessoa Contratante se apresente em situação e processo de Insolvência Civil, Recuperação Judicial ou Falência.</p>
                <p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">7.5 - O estorno da quantia paga apenas será realizada sob as condições do CDC - Lei nº 8.078 de 11 de Setembro de 1990 sendo ela referente à devolução de valores.</p>
                
                <h4>8. DISPOSIÇÕES GERAIS</h4>
                <p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">8.1 - Qualquer alteração da legislação tributária, regras ou pacote governamental que implique alteração do equilíbrio econômico do contrato, a Federal Sistemas poderá propor uma nova renegociação das disposições contratuais afetadas.</p>
                <p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">8.2 - O não exercício de direitos não implicará para qualquer das partes renúncia ou novação, tampouco aceitação tácita dos atos irregulares ou omitidos pela parte faltante.</p>
                <p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">8.3 - A eventual anulação de um dos itens do presente instrumento não invalidará as demais regras deste contrato.</p>
                
                
                <h4>9. DA IRRETRABILIDADE</h4>
                <p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">9.1 - O presente instrumento é celebrado em caráter irrevogável e irretratável, obrigando as partes e seus sucessores nas obrigações ora pactuadas.</p>
                
                
                <p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">PARTES: Conﬁrmo, via assinatura eletrônica, nos moldes do art. 10 da MP 2.200/01 em vigor no Brasil, que estou De Acordo com o presente CONTRATO, e, por estar plenamente ciente dos termos, reaﬁrmo meu dever de observar e fazer cumprir as cláusulas aqui estabelecidas, em vista do que posso acessar minha via do contrato através do endereço https://federalsistemas.com.br/mmn e gerar versão impressa do mesmo, considerado o fato de já tê-lo recebido por e-mail.</p>
				<p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">TESTEMUNHA: Conﬁrmo, via assinatura eletrônica, nos moldes do art. 10 da MP 2.200/01 em vigor no Brasil, a celebração, entre as partes, do CONTRATO, em vista do que posso acessar minha via do contrato através o endereço https://federalsistemas.com.br/mmn e gerar versão impressa do mesmo, considerado o fato de já tê-lo recebido por e-mail. </p>
                <h4>10. DO FORO</h4>
				<p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">Fica eleito o foro da Comarca de GOIANÉSIA, Estado de Goiás, para dirimir eventuais controvérsias decorrentes do presente contrato, quer na sua aplicação ou interpretação, com renúncia a qualquer outro privilegio que seja.</p>
				
                <p class="texto">Goianésia, <?php echo date('d/m/Y');?></p>
				
				<br /><br /><br /><br /><br />
				<p style="text-align:center; font-family:Arial, Helvetica, sans-serif; font-size:12px;">x________________________________________________________________</p>
				
				<br /><br />
				
				<p style="text-align:center; font-family:Arial, Helvetica, sans-serif; font-size:12px;"><strong>Instruções de ativação do SIM CARD</strong></p><br />
				<p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">Bem vindo a Federal Net. Por questão de segurança o seu SIMCARD se encontra com pré-bloqueio, para ativar siga as instruções abaixo:</p>
				<p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">Para solicitar a ativação de seu chip, primeiro assine todas ás páginas do contrato nas áreas indicadas.</p>
				<p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">Após assinar, tire uma foto nítida de todas ás páginas assinadas, de seu documento de identidade ou CNH (frente e verso), e uma self com o documento de  identidade (verso) ao lado de seu rosto.</p>
				<p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">Agora envie os arquivos para nosso e-mail de ativação: contasareceber@federalsistemas.com.br e aguarde a sua ativação! </p>
				<p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;"><strong>ATENÇÃO:</strong> É muito importante que não forneça CPF ou qualquer outra solicitação recebida ao inserir o chip em seu aparelho (ex. DDD). Pois danificará o seu SIMCARD impossibilitando a ativação.</p>
				<p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">Através do link: federalsistemas.com.br/mmn você terá  acesso ao seu escritório virtual.</p>
				<p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">No escritório virtual você pode consultar suas faturas, imprimir boletos, adquirir ou cancelar chips ou até fazer novas indicações, recebendo comissões de acordo o Programa de Benefícios Mensais. </p>
				<p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">Para consultar o seu consumo de dados envie um SMS do próprio chip 4G com a palavra dados para o número 1058.Agradecemos a preferência e parceria</p>
				<p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">Para mais informações ligue (62) 3353-4350 ou atendimento@federalsistemas.com.br</p>
				<p style="text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px;">Lembrando que não possuímos fidelidade, na Federal Net o cliente fica porque gosta!</p>
				
            </td>
        </tr>
        
    </tbody>
</table>
</body>
</html>