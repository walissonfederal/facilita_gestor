<?php
    session_start();
    ob_start();
    require_once '../../_class/Ferramenta.php';
    $Id = addslashes($_GET['Id']);
    $read_contrato = Read('contrato_chip_aditivo', "WHERE contrato_chip_aditivo_id = '".$Id."'");
    if(NumQuery($read_contrato) > '0'){
        foreach($read_contrato as $read_contrato_view);
        $IdPlano = GetDados('pedido', $read_contrato_view['contrato_chip_aditivo_id_pedido'], 'pedido_id', 'pedido_id_plano');
        $ValorPlano = GetDados('pedido', $read_contrato_view['contrato_chip_aditivo_id_pedido'], 'pedido_id', 'pedido_valor_plano');
        $IdClienteGet = GetDados('contrato_chip', $read_contrato_view['contrato_chip_aditivo_id_contrato'], 'contrato_chip_id', 'contrato_chip_id_contato');
        $read_contato = Read('contato', "WHERE contato_id = '".$IdClienteGet."'");
        if(NumQuery($read_contato) > '0'){
            foreach($read_contato as $read_contato_view);
        }
    }
?>
﻿<html>
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
                        <h1 style="font-size:14px; text-align:center; padding-top:25px; font-family:Verdana, Geneva, sans-serif; margin-bottom:0px;">ADITIVO CONTRATUAL Nº. <?php echo $read_contrato_view['contrato_chip_aditivo_id_aditivo'];?></h1>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <p class="texto">
                            Pelo presente instrumento de Contrato, de um lado a FEDERAL SISTEMA DE SEGURANÇA E MONITORAMENTO LTDA, Situada na Av. Presidente Vargas Nº 254 Centro Ceres, Estado de Goiás, CEP: 76300-000, inscrita no CNPJ sob o Nº 11655954/0001-59, operadora do serviço de bloqueio, rastreamento e desligamento de veículo automotor, em área de cobertura de telefonia celular GSM/GPRS, doravante designada FEDERAL, do outro lado CONTRATANTE, qualificado no TERMO DE CONTRATAÇÂO DE SERVIÇOS E COMODATO, de Nº <?php echo $readContratoView['IdContrato'];?>, que faz parte integrante deste, tem entre si, justo e contratado o que se segue mediante aditivo contratual;
                        </p>
                        <h4>PARÁGRAFO ÚNICO</h4>
                        <h4>1. IMPLANTAÇÂO EM NOVOS CHIPS</h4>
                        <p class="texto">Fica acordado entre as partes que os novos chips a serem implantados o sistema contratados são:</p>
                        <table width="100%" border="0">
                            <tr bgcolor="#000000">
                                <td style="color:#FFF;">NÚMERO</td>
                                <td style="color:#FFF">ICCID</td>
                                <td style="color:#FFF">MSISDN</td>
                                <td style="color:#FFF">PACOTE</td>
                            </tr>
                            <?php
                                $CountInstacao = '0';
                                $readItensInstalacao = Read('contrato_chip_aditivo_chip', "WHERE contrato_chip_aditivo_chip_id_aditivo = '".$Id."' AND contrato_chip_aditivo_chip_tipo = '0'");
                                if(NumQuery($readItensInstalacao) > '0'){
                                    foreach($readItensInstalacao as $readItensInstalacaoView){
                                        $readChip = Read('chip', "WHERE chip_id = '".$readItensInstalacaoView['contrato_chip_aditivo_chip_id_chip']."'");
                                        if(NumQuery($readChip) > '0'){
                                            foreach($readChip as $readChipView);
                                        }
                                        $CountInstacao++;
                            ?>
                            <tr>
                                <td><?php echo $CountInstacao;?></td>
                                <td><?php echo $readChipView['chip_num'];?></td>
                                <td><?php echo $readChipView['chip_iccid'];?></td>
                                <td><?php echo GetDados('plano', $IdPlano, 'plano_id', 'plano_descricao');?></td>
                            </tr>
                            <?php
                                    }
                                }
                            ?>
                        </table>
                        <h4>2. DESATIVAÇÃO DE CHIPS</h4>
                        <p class="texto">Fica acordado entre as partes que os chips desativados do sistema ora contratado são:</p>
                        <table width="100%" border="0">
                            <tr bgcolor="#000000">
                                <td style="color:#FFF;">NÚMERO</td>
                                <td style="color:#FFF">ICCID</td>
                                <td style="color:#FFF">MSISDN</td>
                                <td style="color:#FFF">PACOTE</td>
                            </tr>
                            <?php
                                $CountDesinstacao = '0';
                                $readItensDesinstalacao = Read('contrato_chip_aditivo_chip', "WHERE contrato_chip_aditivo_chip_id_aditivo = '".$Id."' AND contrato_chip_aditivo_chip_tipo = '1'");
                                if(NumQuery($readItensDesinstalacao) > '0'){
                                    foreach($readItensDesinstalacao as $readItensDesinstalacaoView){
                                        $readChip = Read('chip', "WHERE chip_id = '".$readItensDesinstalacaoView['IdChip']."'");
                                        if(NumQuery($readChip) > '0'){
                                            foreach($readChip as $readChipView);
                                        }
                                        $CountInstacao++;
                            ?>
                            <tr>
                                <td><?php echo $CountDesinstacao;?></td>
                                <td><?php echo $readChipView['chip_num'];?></td>
                                <td><?php echo $readChipView['chip_iccid'];?></td>
                                <td><?php echo GetDados('plano', $IdPlano, 'plano_id', 'plano_descricao');?></td>
                            </tr>
                            <?php
                                    }
                                }
                            ?>
                        </table>
                        <h4>3. TARIFA DE PREÇOS</h4>
                        <p class="texto">
                            Pela prestação do serviço ora contratado, o <strong>CONTRATANTE</strong> pagará à <strong>FEDERAL</strong>, mensalmente, por chips adicionado, o valor de R$ <?php echo FormatMoney($ValorPlano);?> (<?php echo escreverValorMoeda($ValorPlano) ?>), no momento da ativação do serviço, sendo o equipamento em forma de comodato, valor este que pode ser acrescido na parcela mensal que será paga à <strong>FEDERAL SISTEMAS</strong>, pela prestação dos serviços.Os produtos se trata de um chip m2m (machine to machine) o mesmo e somente para uso de máquina para máquina e não para uso humano como (acesso à internet, downloads ou uploads de vídeos, downloads ou uploads de fotos etc.…) E por estarem assim, ajustados e contratados entre si, fizeram lavrar o presente contrato em duas vias de igual teor e forma, para um só efeito, assinam na presença de duas testemunhas abaixo designadas.
                        </p>
						<br />
						<div align="center"><?php echo FormDataBr($read_contrato_view['contrato_chip_aditivo_data_criacao']);?></div>
						<br />
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        _______________________________________<br />
                        <div align="center" style="font-size:12px"><?php echo $read_contato_view['contato_nome_razao'];?></div>
                        <div align="center" style="font-size:12px"><?php echo $read_contato_view['contato_cpf_cnpj'];?></div>
                        <div align="center" style="font-size:12px; margin-bottom:60px">CONTRATANTE</div>
                    </td>
                    <td>
                        _______________________________________<br />
                        <div align="center" style="font-size:12px">Federal Sistemas de Segurança e Monitoramento Ltda</div>
                        <div align="center" style="font-size:12px">CNPJ: 11655954/0001-59</div>
                        <div align="center" style="font-size:12px; margin-bottom:60px">CONTRATADA</div>
                    </td>
                </tr>
            </tbody>
        </table>
    </body>
</html>