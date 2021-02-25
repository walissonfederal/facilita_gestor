<?php
require_once __DIR__.'/../../../configuracao_infor.php';

/*require_once('d4sign-php-master/sdk/vendor/autoload.php');

use D4sign\Client;

$client = new Client();
$client->setAccessToken("live_f8dc1a9ce037ca098d8b328e5efe42fd39e611e02e21976834dc1a7c1bdaf7fe");*/
  /**
   * ARMAZENAR DADOS DE CONEXAO*/

// define('HOSTFAC', 'federals.gagarin1965.hospedagemdesites.ws');
// define('USERFAC', 'federals_walisso');
// define('PASSFAC', '@Saitodomal123');
// define('DSBAFAC', 'federals_sistema');

  define('HOST', HOSTFAC);
  define('USER', USERFAC);
  define('PASS', PASSFAC);
  define('BASE', DSBAFAC);
 
 define('ID_CONTRATO_CHIP_D4SIGN', 'MzY5');
 define('ID_COFRE_CONTRATO_CHIP_D4SIGN', 'e3ad6131-404a-410b-bc1e-b44588303583');
 define('ID_CONTRATO_CHIP_ADITIVO_D4SIGN', 'Mzgw');
 define('ID_CONTRATO_RASTREAMENTO_D4SIGN', 'Mzcy');
 define('ID_CONTRATO_RASTREAMENTO_ADITIVO_D4SIGN', 'Mzc5');
 define('ID_COFRE_CONTRATO_RASTREAMENTO_D4SIGN', '227fa5c4-f1b3-4e1a-b838-fda556c13684');
 define('CASO_NAO_TENHA_PLANO', ', não existindo qualquer garantia/compromisso de devolução de bem ou ressarcimento de prejuízo pela “CONTRATADA”.');
 define('PRIMEIRA_FRASE_PLANO_COMPENSACAO', '1.3 - A <strong>Contratack Serviços de Seguranças Ltda</strong>, em caráter único e irrevogável, fará ressarcimento (conforme critérios) para os casos de furto nos estabelecimentos do clientes monitorados, desde que estes estejam com o sistema eletrônico embarcado (câmeras, cercas e outros) funcionando regularmente e que o <strong>Contratante</strong> não possua débitos vencidos/pendentes com a <strong>Contratada</strong>.');
 define('CRITERIO_PARTICIPAR_PLANO_COMPENSACAO', 'Dos critérios para participação no Programa:');
 define('CASO_TENHA_RONDA', 'será prestado');
 define('CASO_NAO_TENHA_RONDA', 'se contratado, poderá ser prestado');
 define('ID_CONTRATO_MONITORAMENTO_D4SIGN', 'Mzg5');
 define('ID_COFRE_CONTRATO_MONITORAMENTO_D4SIGN', 'cf3b584f-5dd7-46fc-8a95-48d8fcac62e1');
  
  
  /**
   * ARMAZENAR DADOS DE LOCALIZAÇÃO DE ARQUIVOS
  **/
 define('URL', 'http://federalsistemas.com.br/facilita_gestor');
 define('VSESSION', 'PROJETO_CDL');
 
 define('MULTA', '2');
 define('JUROS', '0.06');
 define('SMS_AVULSO', '0.60');
 
 define('MSGEMAIL', '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                        <html xmlns="http://www.w3.org/1999/xhtml">
                            <head>
                                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                                <title>#NOMEEMPRESA#</title>
                                <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
                            </head>
                        </html>
                        <body style="margin: 0; padding: 0;">
                            <table align="center" border="0" cellpadding="0" cellspacing="0" width="600">
                                <tr>
                                    <td align="center" bgcolor="#70bbd9" style="padding: 40px 0 30px 0;">
                                        <img src="#IMGEMPRESA#" alt="#NOMEEMPRESA#" width="" height="" style="display: block;" />
                                    </td>
                                </tr>
                                <tr>
                                    <td bgcolor="#ffffff" style="padding: 40px 30px 40px 30px;">
                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                            <tr>
                                                <td style="color: #153643; font-family: Arial, sans-serif; font-size: 24px;">
                                                    <b>#TITULOMAIL#</b>
                                                </td>
                                            </tr>
                                            <tr style="color: #153643; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;">
                                                <td style="padding: 20px 0 30px 0;">
                                                    #MSGMAIL#
                                                </td>
                                            </tr>
                                            <tr style="color: #153643; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;" align="center">
                                                <td>
                                                    #LINKBOLETO#
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                    <tr>
                                        <td bgcolor="#ee4c50" align="center">
                                            Dúvidas entre em contato: #MAILEMPRESA# ou #FONEEMPRESA#
                                        </td>
                                    </tr>
                            </table>
                        </body>
                        ');

?>

