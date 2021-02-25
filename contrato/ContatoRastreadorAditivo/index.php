<?php
session_start();
ob_start();
require_once '../../_class/Ferramenta.php';
$Id = addslashes($_GET['Id']);
$read_contrato_aditivo = Read('contrato_rastreamento_aditivo', "WHERE contrato_rastreamento_aditivo_id = '" . $Id . "'");
if (NumQuery($read_contrato_aditivo) > '0') {
    foreach ($read_contrato_aditivo as $read_contrato_aditivo_view);
    if($read_contrato_aditivo_view['contrato_rastreamento_aditivo_tipo'] == '0'){
        $update_veiculos['contrato_rastreamento_veiculo_status'] = '0';
    }else{
        $update_veiculos['contrato_rastreamento_veiculo_status'] = '1';
    }
    $read_contrato_rastreamento = Read('contrato_rastreamento', "WHERE contrato_rastreamento_id = '".$read_contrato_aditivo_view['contrato_rastreamento_aditivo_id_contrato']."'");
    if(NumQuery($read_contrato_rastreamento) > '0'){
        foreach($read_contrato_rastreamento as $read_contrato_rastreamento_view);
    }
    $read_contato = Read('contato', "WHERE contato_id = '" . $read_contrato_rastreamento_view['contrato_rastreamento_id_contato'] . "'");
    if (NumQuery($read_contato) > '0') {
        foreach ($read_contato as $read_contato_view);
    }
    
    Update('contrato_rastreamento_veiculo', $update_veiculos, "WHERE contrato_rastreamento_veiculo_id_aditivo = '".$read_contrato_aditivo_view['contrato_rastreamento_aditivo_id']."'");
}
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
                    <td>
                        <img src="logo.png" width="65" />
                    </td>
                    <td>
                        <h1 style="font-size:14px; text-align:center; padding-top:25px; font-family:Verdana, Geneva, sans-serif; margin-bottom:55px;">ADITIVO CONTRATUAL Nº. <?php echo $read_contrato_aditivo_view['contrato_rastreamento_aditivo_id_aditivo']; ?></h1>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="3">
                        <p class="texto">
                            Pelo presente instrumento de Contrato, de um lado a <strong>FEDERAL SISTEMA DE SEGURANÇA E MONITORAMENTO LTDA</strong>, Situada na Av. Presidente Vargas Nº 254 Centro Ceres, Estado de Goiás, CEP: 76300-000, inscrita no <strong>CNPJ sob o Nº 11655954/0001-59</strong>, operadora do serviço de bloqueio, rastreamento e desligamento de veículo automotor, em área de cobertura de telefonia celular GSM/GPRS, doravante designada <strong>FEDERAL</strong>, do outro lado <strong>CONTRATANTE</strong>, qualificado no <strong>TERMO DE CONTRATAÇÂO DE SERVIÇOS E COMODATO</strong>, de Nº <?php echo $read_contrato_rastreamento_view['contrato_rastreamento_id']; ?>, que faz parte integrante deste, tem entre si, justo e contratado o que se segue mediante aditivo contratual;
                        </p>
                        <h4>PARÁGRAFO ÚNICO</h4>
                        <h4>1. IMPLANTAÇÂO EM NOVOS VEÍCULOS</h4>
                        <p class="texto">Fica acordado entre as partes que os novos veículos a serem implantados o sistema para prestação de serviços contratados são:</p>
                        <table width="100%" border="0">
                            <tr bgcolor="#000000" style="font-size: 14px;">
                                <td style="color:#FFF"></td>
                                <td style="color:#FFF">VEÍCULO</td>
                                <td style="color:#FFF">PLACA</td>
                                <td style="color:#FFF">FROTA</td>
                                <td style="color:#FFF">MARCA</td>
                                <td style="color:#FFF">MODELO</td>
                                <td style="color:#FFF">ANO</td>
                                <td style="color:#FFF">COR</td>
                                <td style="color:#FFF">CHASSI</td>
                            </tr>
                            <?php
                                $CountInstacao = '0';
                                $readAditivoInstalacao = Read('contrato_rastreamento_veiculo', "WHERE contrato_rastreamento_veiculo_id_aditivo = '".$read_contrato_aditivo_view['contrato_rastreamento_aditivo_id']."' AND contrato_rastreamento_veiculo_status = '0'");
                                if(NumQuery($readAditivoInstalacao) > '0'){
                                    foreach($readAditivoInstalacao as $readAditivoInstalacaoView){
                                        $CountInstacao++;
                            ?>
                            <tr style="font-size: 10px;">
                                <td><?php echo $CountInstacao;?></td>
                                <td><?php echo $readAditivoInstalacaoView['contrato_rastreamento_veiculo_veiculo'];?></td>
                                <td><?php echo $readAditivoInstalacaoView['contrato_rastreamento_veiculo_placa'];?></td>
                                <td><?php echo $readAditivoInstalacaoView['contrato_rastreamento_veiculo_frota'];?></td>
                                <td><?php echo $readAditivoInstalacaoView['contrato_rastreamento_veiculo_marca'];?></td>
                                <td><?php echo $readAditivoInstalacaoView['contrato_rastreamento_veiculo_modelo'];?></td>
                                <td><?php echo $readAditivoInstalacaoView['contrato_rastreamento_veiculo_ano'];?></td>
                                <td><?php echo $readAditivoInstalacaoView['contrato_rastreamento_veiculo_cor'];?></td>
                                <td><?php echo $readAditivoInstalacaoView['contrato_rastreamento_veiculo_chassi'];?></td>
                            </tr>
                            <?php
                                    }
                                }
                            ?>
                        </table>
                        <h4>2. DESATIVAÇÃO DE VEÍCULOS</h4>
                        <p class="texto">Fica acordado entre as partes que os veículos desativados do sistema ora contratado são:</p>
                        <table width="100%" border="0">
                            <tr bgcolor="#000000" style="font-size: 14px;">
                                <td style="color:#FFF"></td>
                                <td style="color:#FFF">VEÍCULO</td>
                                <td style="color:#FFF">PLACA</td>
                                <td style="color:#FFF">FROTA</td>
                                <td style="color:#FFF">MARCA</td>
                                <td style="color:#FFF">MODELO</td>
                                <td style="color:#FFF">ANO</td>
                                <td style="color:#FFF">COR</td>
                                <td style="color:#FFF">CHASSI</td>
                            </tr>
                            <?php
                                $CountDesinstacao = '0';
				$readAditivoDesinstalacao = Read('contrato_rastreamento_veiculo', "WHERE contrato_rastreamento_veiculo_id_aditivo = '".$read_contrato_aditivo_view['contrato_rastreamento_aditivo_id']."' AND contrato_rastreamento_veiculo_status = '1'");
                                //$readAditivoDesinstalacao = Read('aditivo_contrato_rastreamento', "WHERE Id = '".$Id."' AND Status = '1'");
                                if(NumQuery($readAditivoDesinstalacao) > '0'){
                                    foreach($readAditivoDesinstalacao as $readAditivoDesinstalacaoView){
                                        $CountDesinstacao++;
                            ?>
                            <tr style="font-size: 10px;">
                                <td><?php echo $CountDesinstacao;?></td>
                                <td><?php echo $readAditivoDesinstalacaoView['contrato_rastreamento_veiculo_veiculo'];?></td>
                                <td><?php echo $readAditivoDesinstalacaoView['contrato_rastreamento_veiculo_placa'];?></td>
                                <td><?php echo $readAditivoDesinstalacaoView['contrato_rastreamento_veiculo_frota'];?></td>
                                <td><?php echo $readAditivoDesinstalacaoView['contrato_rastreamento_veiculo_marca'];?></td>
                                <td><?php echo $readAditivoDesinstalacaoView['contrato_rastreamento_veiculo_modelo'];?></td>
                                <td><?php echo $readAditivoDesinstalacaoView['contrato_rastreamento_veiculo_ano'];?></td>
                                <td><?php echo $readAditivoDesinstalacaoView['contrato_rastreamento_veiculo_cor'];?></td>
                                <td><?php echo $readAditivoDesinstalacaoView['contrato_rastreamento_veiculo_chassi'];?></td>
                            </tr>
                            <?php
                                    }
                                }
                            ?>
                        </table>
                        <h4>3. TARIFA DE PREÇOS</h4>
                        <p class="texto">
                            Pela prestação do serviço ora contratado, o <strong>CONTRATANTE</strong> pagará à <strong>FEDERAL</strong>, mensalmente, por veículo adicionado, o valor de R$ <?php echo FormatMoney($read_contrato_aditivo_view['contrato_rastreamento_aditivo_valor_mensalidade']);?> (<?php echo escreverValorMoeda($read_contrato_aditivo_view['contrato_rastreamento_aditivo_valor_mensalidade']);?>), por veiculo no momento da ativação do serviço, sendo o equipamento em forma de comodato, valor este que pode ser acrescido na parcela mensal que será paga à <strong>FEDERAL SISTEMAS</strong>, pela prestação dos serviços. E por estarem assim, ajustados e contratados entre si, fizeram lavrar o presente contrato em duas vias de igual teor e forma, para um só efeito, assinam na presença de duas testemunhas abaixo designadas.
                        </p>
                        <p class="texto" style="text-align:center">Goianésia, <?php echo FormDataBr($read_contrato_aditivo_view['contrato_rastreamento_aditivo_data']); ?>.</p>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <br /><br /><br />
                        _______________________________________<br />
                        <div align="center" style="font-size:12px"><?php echo $read_contato_view['contato_nome_razao'];?></div>
                        <div align="center" style="font-size:12px"><?php echo $read_contato_view['contato_cpf_cnpj'];?></div>
                        <div align="center" style="font-size:12px; margin-bottom:60px">CONTRATANTE</div>
                    </td>
                    <td>
                        <br /><br /><br />
                        _______________________________________<br />
                        <div align="center" style="font-size:12px">Federal Sistemas de Segurança e Monitoramento Ltda</div>
                        <div align="center" style="font-size:12px">CNPJ: 11655954/0001-59</div>
                        <div align="center" style="font-size:12px; margin-bottom:60px">CONTRATADA</div>
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
