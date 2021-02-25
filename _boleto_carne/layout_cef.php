<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<style type=text/css>
.cp {  font: bold 9px Arial; color: black}
.ti {  font: 8px Arial, Helvetica, sans-serif}
.ld { font: bold 14px Arial; color: #000000}
.ct { FONT: 8px "Arial Narrow"; COLOR: #000033} 
.cn { FONT: 8px Arial; COLOR: black }
.bc { font: bold 20px Arial; color: #000000 }
.ld2 { font: bold 10px Arial; color: #000000 }
.bc {font: bold 20px Arial; color: #000000 }
.style1 {font-size: 7px}
</style>


 
</head>

<body>
<div class="folha">
<table width="500" height="300" border="0">
  <tr>
    <td width="188" height="20" valign="top" scope="col"><span class="campo"><img src="imagens/logocaixa.jpg" width="145" height="30" border="0" /></span>
    <td width="140" colspan="9" rowspan="10" valign="top" scope="col"><table width="666" height="32" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="cp" width="150"><span class="campo"><img src="imagens/logocaixa.jpg" width="145" height="30" border="0" /></span></td>
        <td width="3" valign="bottom"><img height="22" src="imagens/3.png" width="2" border="0" /></td>
        <td class="cpt" width="58" valign="bottom"><div align="center"><font class="bc"><?php echo $dadosboleto["codigo_banco_com_dv"]?></font></div></td>
        <td width="3" valign="bottom"><img height="22" src="imagens/3.png" width="2" border="0" /></td>
        <td class="ld" align="right" width="453" valign="bottom"><span class="campotitulo"><?php echo $dadosboleto["linha_digitavel"]?></span></td>
      </tr>
      <tbody>
        <tr>
          <td colspan="5"><img height="2" src="imagens/2.png" width="666" border="0" /></td>
        </tr>
      </tbody>
    </table>
      <table cellspacing="0" cellpadding="0" border="0">
        <tbody>
          <tr>
            <td class="ct" valign="top" width="7" height="13"><img height="13" src="imagens/1.png" width="1" border="0" /></td>
            <td class="ct" valign="top" width="472" height="13">Local de pagamento</td>
            <td class="ct" valign="top" width="7" height="13"><img height="13" src="imagens/1.png" width="1" border="0" /></td>
            <td class="ct" valign="top" width="180" height="13">Vencimento</td>
          </tr>
          <tr>
            <td class="cp" valign="top" width="7" height="12"><img height="12" src="imagens/1.png" width="1" border="0" /></td>
            <td class="cp" valign="top" width="472" height="12">Pagável em qualquer Banco até o vencimento</td>
            <td class="cp" valign="top" width="7" height="12"><img height="12" src="imagens/1.png" width="1" border="0" /></td>
            <td class="cp" valign="top" align="right" width="180" height="12"><span class="campo"><?php echo ($data_venc != "") ? $dadosboleto["data_vencimento"] : "Contra Apresentação" ?></span></td>
          </tr>
          <tr>
            <td valign="top" width="7" height="1"><img height="1" src="imagens/2.png" width="7" border="0" /></td>
            <td valign="top" width="472" height="1"><img height="1" src="imagens/2.png" width="472" border="0" /></td>
            <td valign="top" width="7" height="1"><img height="1" src="imagens/2.png" width="7" border="0" /></td>
            <td valign="top" width="180" height="1"><img height="1" src="imagens/2.png" width="180" border="0" /></td>
          </tr>
        </tbody>
      </table>
      <table cellspacing="0" cellpadding="0" border="0">
        <tbody>
          <tr>
            <td class="ct" valign="top" width="7" height="13"><img height="13" src="imagens/1.png" width="1" border="0" /></td>
            <td class="ct" valign="top" width="472" height="13">Cedente</td>
            <td class="ct" valign="top" width="7" height="13"><img height="13" src="imagens/1.png" width="1" border="0" /></td>
            <td class="ct" valign="top" width="180" height="13">Agência/Código 
              cedente</td>
          </tr>
          <tr>
            <td class="cp" valign="top" width="7" height="12"><img height="12" src="imagens/1.png" width="1" border="0" /></td>
            <td class="cp" valign="top" width="472" height="10"><?php echo $dadosboleto["cedente"]?></td>
            <td class="cp" valign="top" width="7" height="12"><img height="12" src="imagens/1.png" width="1" border="0" /></td>
            <td class="cp" valign="top" align="right" width="180" height="10"><?php echo $dadosboleto["agencia_codigo"]?></td>
          </tr>
          <tr>
            <td valign="top" width="7" height="1"><img height="1" src="imagens/2.png" width="7" border="0" /></td>
            <td valign="top" width="472" height="1"><img height="1" src="imagens/2.png" width="472" border="0" /></td>
            <td valign="top" width="7" height="1"><img height="1" src="imagens/2.png" width="7" border="0" /></td>
            <td valign="top" width="180" height="1"><img height="1" src="imagens/2.png" width="180" border="0" /></td>
          </tr>
        </tbody>
      </table>
      <table cellspacing="0" cellpadding="0" border="0">
        <tbody>
          <tr>
            <td class="ct" valign="top" width="7" height="13"><img height="13" src="imagens/1.png" width="1" border="0" /></td>
            <td class="ct" valign="top" width="113" height="13">Data do documento</td>
            <td class="ct" valign="top" width="7" height="13"><img height="13" src="imagens/1.png" width="1" border="0" /></td>
            <td class="ct" valign="top" width="133" height="13">N<u>o</u>documento</td>
            <td class="ct" valign="top" width="7" height="13"><img height="13" src="imagens/1.png" width="1" border="0" /></td>
            <td class="ct" valign="top" width="62" height="13">Espécie doc.</td>
            <td class="ct" valign="top" width="7" height="13"><img height="13" src="imagens/1.png" width="1" border="0" /></td>
            <td class="ct" valign="top" width="34" height="13">Aceite</td>
            <td class="ct" valign="top" width="7" height="13"><img height="13" src="imagens/1.png" width="1" border="0" /></td>
            <td class="ct" valign="top" width="102" height="13">Data processamento</td>
            <td class="ct" valign="top" width="7" height="13"><img height="13" src="imagens/1.png" width="1" border="0" /></td>
            <td class="ct" valign="top" width="180" height="13">Nosso número</td>
          </tr>
          <tr>
            <td class="cp" valign="top" width="7" height="12"><img height="12" src="imagens/1.png" width="1" border="0" /></td>
            <td class="cp" valign="top"  width="113" height="10"><div align="left"><?php echo $dadosboleto["data_documento"]?></div></td>
            <td class="cp" valign="top" width="7" height="12"><img height="12" src="imagens/1.png" width="1" border="0" /></td>
            <td class="cp" valign="top" width="133" height="10"><span class="campo"><?php echo $dadosboleto["numero_documento"]?> </span></td>
            <td class="cp" valign="top" width="7" height="12"><img height="12" src="imagens/1.png" width="1" border="0" /></td>
            <td class="cp" valign="top"  width="62" height="10"><div align="left"><span class="campo"><?php echo $dadosboleto["especie_doc"]?></span></div></td>
            <td class="cp" valign="top" width="7" height="12"><img height="12" src="imagens/1.png" width="1" border="0" /></td>
            <td class="cp" valign="top"  width="34" height="10"><div align="left"><span class="campo"><?php echo $dadosboleto["aceite"]?> </span></div></td>
            <td class="cp" valign="top" width="7" height="12"><img height="12" src="imagens/1.png" width="1" border="0" /></td>
            <td class="cp" valign="top"  width="102" height="10"><div align="left"><span class="campo"><?php echo $dadosboleto["data_processamento"]?> </span></div></td>
            <td class="cp" valign="top" width="7" height="12"><img height="12" src="imagens/1.png" width="1" border="0" /></td>
            <td class="cp" valign="top" align="right" width="180" height="10"><span class="campo"><?php echo $dadosboleto["nosso_numero"]?></span></td>
          </tr>
          <tr>
            <td valign="top" width="7" height="1"><img height="1" src="imagens/2.png" width="7" border="0" /></td>
            <td valign="top" width="113" height="1"><img height="1" src="imagens/2.png" width="113" border="0" /></td>
            <td valign="top" width="7" height="1"><img height="1" src="imagens/2.png" width="7" border="0" /></td>
            <td valign="top" width="133" height="1"><img height="1" src="imagens/2.png" width="133" border="0" /></td>
            <td valign="top" width="7" height="1"><img height="1" src="imagens/2.png" width="7" border="0" /></td>
            <td valign="top" width="62" height="1"><img height="1" src="imagens/2.png" width="62" border="0" /></td>
            <td valign="top" width="7" height="1"><img height="1" src="imagens/2.png" width="7" border="0" /></td>
            <td valign="top" width="34" height="1"><img height="1" src="imagens/2.png" width="34" border="0" /></td>
            <td valign="top" width="7" height="1"><img height="1" src="imagens/2.png" width="7" border="0" /></td>
            <td valign="top" width="102" height="1"><img height="1" src="imagens/2.png" width="102" border="0" /></td>
            <td valign="top" width="7" height="1"><img height="1" src="imagens/2.png" width="7" border="0" /></td>
            <td valign="top" width="180" height="1"><img height="1" src="imagens/2.png" width="180" border="0" /></td>
          </tr>
        </tbody>
      </table>
      <table cellspacing="0" cellpadding="0" border="0">
        <tbody>
          <tr>
            <td class="ct" valign="top" width="7" height="13"><img height="13" src="imagens/1.png" width="1" border="0" /></td>
            <td class="ct" valign="top" colspan="3" height="13">Uso do banco</td>
            <td class="ct" valign="top" height="13" width="7"><img height="13" src="imagens/1.png" width="1" border="0" /></td>
            <td class="ct" valign="top" width="83" height="13">Carteira</td>
            <td class="ct" valign="top" height="13" width="7"><img height="13" src="imagens/1.png" width="1" border="0" /></td>
            <td class="ct" valign="top" width="43" height="13">Espécie</td>
            <td class="ct" valign="top" height="13" width="7"><img height="13" src="imagens/1.png" width="1" border="0" /></td>
            <td class="ct" valign="top" width="103" height="13">Quantidade</td>
            <td class="ct" valign="top" height="13" width="7"><img height="13" src="imagens/1.png" width="1" border="0" /></td>
            <td class="ct" valign="top" width="102" height="13">Valor Documento</td>
            <td class="ct" valign="top" width="7" height="13"><img height="13" src="imagens/1.png" width="1" border="0" /></td>
            <td class="ct" valign="top" width="180" height="13">(=) Valor documento</td>
          </tr>
          <tr>
            <td class="cp" valign="top" width="7" height="12"><img height="12" src="imagens/1.png" width="1" border="0" /></td>
            <td valign="top" class="cp" height="12" colspan="3"><div align="left"></div></td>
            <td class="cp" valign="top" width="7" height="12"><img height="12" src="imagens/1.png" width="1" border="0" /></td>
            <td class="cp" valign="top"  width="83"><div align="left"> <span class="campo"><?php echo $dadosboleto["carteira"]?> </span></div></td>
            <td class="cp" valign="top" width="7" height="12"><img height="12" src="imagens/1.png" width="1" border="0" /></td>
            <td class="cp" valign="top"  width="43"><div align="left"><span class="campo"><?php echo $dadosboleto["especie"]?></span> </div></td>
            <td class="cp" valign="top" width="7" height="12"><img height="12" src="imagens/1.png" width="1" border="0" /></td>
            <td class="cp" valign="top"  width="103"><span class="campo"><?php echo $dadosboleto["quantidade"]?></span></td>
            <td class="cp" valign="top" width="7" height="12"><img height="12" src="imagens/1.png" width="1" border="0" /></td>
            <td class="cp" valign="top"  width="102"><span class="campo"><?php echo $dadosboleto["valor_unitario"]?></span></td>
            <td class="cp" valign="top" width="7" height="12"><img height="12" src="imagens/1.png" width="1" border="0" /></td>
            <td class="cp" valign="top" align="right" width="180" height="12"><span class="campo"><?php echo $dadosboleto["valor_boleto"]?> </span></td>
          </tr>
          <tr>
            <td valign="top" width="7" height="1"><img height="1" src="imagens/2.png" width="7" border="0" /></td>
            <td valign="top" width="7" height="1"><img height="1" src="imagens/2.png" width="75" border="0" /></td>
            <td valign="top" width="7" height="1"><img height="1" src="imagens/2.png" width="7" border="0" /></td>
            <td valign="top" width="31" height="1"><img height="1" src="imagens/2.png" width="31" border="0" /></td>
            <td valign="top" width="7" height="1"><img height="1" src="imagens/2.png" width="7" border="0" /></td>
            <td valign="top" width="83" height="1"><img height="1" src="imagens/2.png" width="83" border="0" /></td>
            <td valign="top" width="7" height="1"><img height="1" src="imagens/2.png" width="7" border="0" /></td>
            <td valign="top" width="43" height="1"><img height="1" src="imagens/2.png" width="43" border="0" /></td>
            <td valign="top" width="7" height="1"><img height="1" src="imagens/2.png" width="7" border="0" /></td>
            <td valign="top" width="103" height="1"><img height="1" src="imagens/2.png" width="103" border="0" /></td>
            <td valign="top" width="7" height="1"><img height="1" src="imagens/2.png" width="7" border="0" /></td>
            <td valign="top" width="102" height="1"><img height="1" src="imagens/2.png" width="102" border="0" /></td>
            <td valign="top" width="7" height="1"><img height="1" src="imagens/2.png" width="7" border="0" /></td>
            <td valign="top" width="180" height="1"><img height="1" src="imagens/2.png" width="180" border="0" /></td>
          </tr>
        </tbody>
      </table>
      <table cellspacing="0" cellpadding="0" width="666" border="0">
        <tbody>
          <tr>
            <td align="right" width="10"><table cellspacing="0" cellpadding="0" border="0" align="left">
                <tr> </tr>
              <tbody>
                </tbody>
              <tr>
                <td class="ct" valign="top" width="7" height="13"><img height="13" src="imagens/1.png" width="1" border="0" /></td>
              </tr>
                <tr>
                  <td class="cp" valign="top" width="7" height="12"><img height="12" src="imagens/1.png" width="1" border="0" /></td>
                </tr>
                <tr>
                  <td valign="top" width="7" height="1"><img height="1" src="imagens/2.png" width="1" border="0" /></td>
                </tr>
            </table></td>
            <td valign="top" width="468" rowspan="5"><font class="ct">Instruções Texto de responsabilidade do cedente)</font><br />
                <span class="cp"> <font class="campo"> <?php echo $dadosboleto["instrucoes1"]; ?><br />
                <?php echo $dadosboleto["instrucoes2"]; ?><br />
                  <?php echo $dadosboleto["instrucoes3"]; ?><br /><hr />
                      <?php echo $dadosboleto["instrucoes4"]; ?></font></span></td>
            <td align="right" width="188"><table cellspacing="0" cellpadding="0" border="0">
                <tbody>
                  <tr>
                    <td class="ct" valign="top" width="7" height="13"><img height="13" src="imagens/1.png" width="1" border="0" /></td>
                    <td class="ct" valign="top" width="180" height="13">Desconto</td>
                  </tr>
                  <tr>
                    <td class="cp" valign="top" width="7" height="12"><img height="12" src="imagens/1.png" width="1" border="0" /></td>
                    <td class="cp" valign="top" align="right" width="180" height="12"></td>
                  </tr>
                  <tr>
                    <td valign="top" width="7" height="1"><img height="1" src="imagens/2.png" width="7" border="0" /></td>
                    <td valign="top" width="180" height="1"><img height="1" src="imagens/2.png" width="180" border="0" /></td>
                  </tr>
                </tbody>
            </table></td>
          </tr>
          <tr>
            <td align="right" width="10"><table cellspacing="0" cellpadding="0" border="0" align="left">
                <tbody>
                  <tr>
                    <td class="ct" valign="top" width="7" height="13"><img height="13" src="imagens/1.png" width="1" border="0" /></td>
                  </tr>
                  <tr>
                    <td class="cp" valign="top" width="7" height="12"><img height="12" src="imagens/1.png" width="1" border="0" /></td>
                  </tr>
                  <tr>
                    <td valign="top" width="7" height="1"><img height="1" src="imagens/2.png" width="1" border="0" /></td>
                  </tr>
                </tbody>
            </table></td>
            <td align="right" width="188"><table cellspacing="0" cellpadding="0" border="0">
                <tbody>
                  <tr>
                    <td class="ct" valign="top" width="7" height="13"><img height="13" src="imagens/1.png" width="1" border="0" /></td>
                    <td class="ct" valign="top" width="180" height="13">(-) 
                      Outras dedu&ccedil;&otilde;es</td>
                  </tr>
                  <tr>
                    <td class="cp" valign="top" width="7" height="12"><img height="12" src="imagens/1.png" width="1" border="0" /></td>
                    <td class="cp" valign="top" align="right" width="180" height="12"></td>
                  </tr>
                  <tr>
                    <td valign="top" width="7" height="1"><img height="1" src="imagens/2.png" width="7" border="0" /></td>
                    <td valign="top" width="180" height="1"><img height="1" src="imagens/2.png" width="180" border="0" /></td>
                  </tr>
                </tbody>
            </table></td>
          </tr>
          <tr>
            <td align="right" width="10"><table cellspacing="0" cellpadding="0" border="0" align="left">
                <tbody>
                  <tr>
                    <td class="ct" valign="top" width="7" height="13"><img height="13" src="imagens/1.png" width="1" border="0" /></td>
                  </tr>
                  <tr>
                    <td class="cp" valign="top" width="7" height="12"><img height="12" src="imagens/1.png" width="1" border="0" /></td>
                  </tr>
                  <tr>
                    <td valign="top" width="7" height="1"><img height="1" src="imagens/2.png" width="1" border="0" /></td>
                  </tr>
                </tbody>
            </table></td>
            <td align="right" width="188"><table cellspacing="0" cellpadding="0" border="0">
                <tbody>
                  <tr>
                    <td class="ct" valign="top" width="7" height="13"><img height="13" src="imagens/1.png" width="1" border="0" /></td>
                    <td class="ct" valign="top" width="180" height="13">(+) 
                      Mora / Multa</td>
                  </tr>
                  <tr>
                    <td class="cp" valign="top" width="7" height="12"><img height="12" src="imagens/1.png" width="1" border="0" /></td>
                    <td class="cp" valign="top" align="right" width="180" height="12"></td>
                  </tr>
                  <tr>
                    <td valign="top" width="7" height="1"><img height="1" src="imagens/2.png" width="7" border="0" /></td>
                    <td valign="top" width="180" height="1"><img height="1" src="imagens/2.png" width="180" border="0" /></td>
                  </tr>
                </tbody>
            </table></td>
          </tr>
          <tr>
            <td align="right" width="10"><table cellspacing="0" cellpadding="0" border="0" align="left">
                <tbody>
                  <tr>
                    <td class="ct" valign="top" width="7" height="13"><img height="13" src="imagens/1.png" width="1" border="0" /></td>
                  </tr>
                  <tr>
                    <td class="cp" valign="top" width="7" height="12"><img height="12" src="imagens/1.png" width="1" border="0" /></td>
                  </tr>
                  <tr>
                    <td valign="top" width="7" height="1"><img height="1" src="imagens/2.png" width="1" border="0" /></td>
                  </tr>
                </tbody>
            </table></td>
            <td align="right" width="188"><table cellspacing="0" cellpadding="0" border="0">
                <tbody>
                  <tr>
                    <td class="ct" valign="top" width="7" height="13"><img height="13" src="imagens/1.png" width="1" border="0" /></td>
                    <td class="ct" valign="top" width="180" height="13">((+) Outros acr&eacute;scimos</td>
                  </tr>
                  <tr>
                    <td class="cp" valign="top" width="7" height="12"><img height="12" src="imagens/1.png" width="1" border="0" /></td>
                    <td class="cp" valign="top" align="right" width="180" height="12"></td>
                  </tr>
                </tbody>
            </table></td>
          </tr>
        </tbody>
      </table>
      <table cellspacing="0" cellpadding="0" width="666" border="0">
        <tbody>
          <tr>
            <td valign="top" width="666" height="1"><img height="1" src="imagens/2.png" width="666" border="0" /></td>
          </tr>
        </tbody>
      </table>
      <table width="666" border="0" cellpadding="0" cellspacing="0">
        <tbody>
          <tr>
            <td class="ct" valign="top" width="248" height="13"><img height="13" src="imagens/1.png" width="1" border="0" />Sacador/Avalista</td>
            <td class="ct" valign="top" width="230" height="13"></td>
            <td width="188" rowspan="2" valign="top" class="ct"><table cellspacing="0" cellpadding="0" border="0">
                <tbody>
                  <tr>
                    <td class="ct" valign="top" width="7" height="13"><img height="13" src="imagens/1.png" width="1" border="0" /></td>
                    <td class="ct" valign="top" width="180" height="13">(=) Valor cobrado</td>
                  </tr>
                  <tr>
                    <td class="cp" valign="top" width="7" height="12"><img height="12" src="imagens/1.png" width="1" border="0" /></td>
                    <td class="cp" valign="top" align="right" width="180" height="12"></td>
                  </tr>
                </tbody>
            </table></td>
          </tr>
          <tr>
            <td height="12" colspan="2" valign="top" class="cp"><img height="12" src="imagens/1.png" width="1" border="0" /> <?php echo $dadosboleto["sacado"]?></td>
          </tr>
        </tbody>
      </table>
      <table width="666" border="0" cellpadding="0" cellspacing="0">
        <tbody>
          <tr>
            <td height="12" valign="top" class="cp"> <img height="12" src="imagens/1.png" width="1" border="0" /> <?php echo $dadosboleto["endereco1"]?> / <?php echo $dadosboleto["endereco2"]?></td>
          </tr>
        </tbody>
      </table>
      <img src="imagens/2.png" alt="" width="666" height="2" border="0" />
      <table cellspacing="0" cellpadding="0" width="666" border="0">
        <tr>
          <td  width="666"><table width="666" height="30" border="0" cellpadding="2" cellspacing="0">
              <tbody>
                <tr>
                  <td width="558" height="30" align="left" valign="bottom" bordercolor="#000000"><?php fbarcode($dadosboleto["codigo_barras"]); ?>                  </td>
                  <td width="108" class="ct" align="center" valign="top">Ficha de Compensa&ccedil;&atilde;o Autentica&ccedil;&atilde;o mec&acirc;nica </td>
                </tr>
              </tbody>
          </table><p>
            </td>
        </tr>
        <tbody>
          <tr>
            <td class="ct" width="666"><img height="1" src="imagens/6.png" width="665" border="0" /></td>
          </tr>
        </tbody>
    </table>
     </td>
  </tr>
  <tr valign="top">
    <td width="188" height="20" align="right"><table cellspacing="0" cellpadding="0" border="0">
      <tbody>
        <tr>
          <td class="ct" valign="top" width="7" height="13"><img height="13" src="imagens/1.png" width="1" border="0" /></td>
          <td class="ct" valign="top" width="180" height="13">Nº Documento</td>
        </tr>
        <tr>
          <td class="cp" valign="top" width="7" height="12"><img height="12" src="imagens/1.png" width="1" border="0" /></td>
          <td class="cp" valign="top" align="right" width="180" height="7"><div align="left"><?php echo $dadosboleto["numero_documento"]?></div></td>
        </tr>
        <tr>
          <td valign="top" width="7" height="1"><img height="1" src="imagens/2.png" width="7" border="0" /></td>
          <td valign="top" width="180" height="1"><img height="1" src="imagens/2.png" width="180" border="0" /></td>
        </tr>
      </tbody>
    </table></td>
  </tr>
  <tr valign="top">
    <td width="188" height="20" align="right"><table cellspacing="0" cellpadding="0" border="0">
      <tbody>
        <tr>
          <td class="ct" valign="top" width="7" height="13"><img height="13" src="imagens/1.png" width="1" border="0" /></td>
          <td class="ct" valign="top" width="180" height="13">Vencimento</td>
        </tr>
        <tr>
          <td class="cp" valign="top" width="7" height="12"><img height="12" src="imagens/1.png" width="1" border="0" /></td>
          <td class="cp" valign="top" align="left" width="180" height="7"><?php echo ($data_venc != "") ? $dadosboleto["data_vencimento"] : "Contra Apresentação" ?></td>
        </tr>
        <tr>
          <td valign="top" width="7" height="1"><img height="1" src="imagens/2.png" width="7" border="0" /></td>
          <td valign="top" width="180" height="1"><img height="1" src="imagens/2.png" width="180" border="0" /></td>
        </tr>
      </tbody>
    </table></td>
  </tr>
  <tr valign="top">
    <td width="188" height="20" align="right"><table cellspacing="0" cellpadding="0" border="0">
      <tbody>
        <tr>
          <td class="ct" valign="top" width="7" height="13"><img height="13" src="imagens/1.png" width="1" border="0" /></td>
          <td class="ct" valign="top" width="180" height="13">Nosso Numero</td>
        </tr>
        <tr>
          <td class="cp" valign="top" width="7" height="12"><img height="12" src="imagens/1.png" width="1" border="0" /></td>
          <td class="cp" valign="top" align="left" width="180" height="7"><?php echo $dadosboleto["nosso_numero"]?></td>
        </tr>
        <tr>
          <td valign="top" width="7" height="1"><img height="1" src="imagens/2.png" width="7" border="0" /></td>
          <td valign="top" width="180" height="1"><img height="1" src="imagens/2.png" width="180" border="0" /></td>
        </tr>
      </tbody>
    </table></td>
  </tr>
  <tr valign="top">
    <td width="188" height="20" align="right"><table cellspacing="0" cellpadding="0" border="0">
      <tbody>
        <tr>
          <td class="ct" valign="top" width="7" height="13"><img height="13" src="imagens/1.png" width="1" border="0" /></td>
          <td class="ct" valign="top" width="180" height="13">Ag/cod. Cendente</td>
        </tr>
        <tr>
          <td class="cp" valign="top" width="7" height="12"><img height="12" src="imagens/1.png" width="1" border="0" /></td>
          <td class="cp" valign="top" align="left" width="180" height="7"><?php echo $dadosboleto["agencia_codigo"]?></td>
        </tr>
        <tr>
          <td valign="top" width="7" height="1"><img height="1" src="imagens/2.png" width="7" border="0" /></td>
          <td valign="top" width="180" height="1"><img height="1" src="imagens/2.png" width="180" border="0" /></td>
        </tr>
      </tbody>
    </table></td>
  </tr>
  <tr valign="top">
    <td width="188" height="20" align="right"><table cellspacing="0" cellpadding="0" border="0">
      <tbody>
        <tr>
          <td class="ct" valign="top" width="7" height="13"><img height="13" src="imagens/1.png" width="1" border="0" /></td>
          <td class="ct" valign="top" width="180" height="13">Vl. documento</td>
        </tr>
        <tr>
          <td class="cp" valign="top" width="7" height="12"><img height="12" src="imagens/1.png" width="1" border="0" /></td>
          <td class="cp" valign="top" align="left" width="180" height="12"><?php echo $dadosboleto["valor_boleto"]?></td>
        </tr>
        <tr>
          <td valign="top" width="7" height="2"><img height="1" src="imagens/2.png" width="7" border="0" /></td>
          <td valign="top" width="180" height="2"><img height="1" src="imagens/2.png" width="180" border="0" /></td>
        </tr>
      </tbody>
    </table></td>
  </tr>
  <tr valign="top">
    <td width="188" height="20" align="right"><table cellspacing="0" cellpadding="0" border="0">
      <tbody>
        <tr>
          <td class="ct" valign="top" width="7" height="13"><img height="13" src="imagens/1.png" width="1" border="0" /></td>
          <td class="ct" valign="top" width="180" height="13">outras Deduc/Abat</td>
        </tr>
        <tr>
          <td class="cp" valign="top" width="7" height="12"><img height="12" src="imagens/1.png" width="1" border="0" /></td>
          <td class="cp" valign="top" align="right" width="180" height="12"></td>
        </tr>
        <tr>
          <td valign="top" width="7" height="1"><img height="1" src="imagens/2.png" width="7" border="0" /></td>
          <td valign="top" width="180" height="1"><img height="1" src="imagens/2.png" width="180" border="0" /></td>
        </tr>
      </tbody>
    </table></td>
  </tr>
  <tr valign="top">
    <td width="188" height="20" align="right"><table cellspacing="0" cellpadding="0" border="0">
      <tbody>
        <tr>
          <td class="ct" valign="top" width="7" height="13"><img height="13" src="imagens/1.png" width="1" border="0" /></td>
          <td class="ct" valign="top" width="180" height="13">Mora/Multa</td>
        </tr>
        <tr>
          <td class="cp" valign="top" width="7" height="12"><img height="12" src="imagens/1.png" width="1" border="0" /></td>
          <td class="cp" valign="top" align="right" width="180" height="12"></td>
        </tr>
        <tr>
          <td valign="top" width="7" height="1"><img height="1" src="imagens/2.png" width="7" border="0" /></td>
          <td valign="top" width="180" height="1"><img height="1" src="imagens/2.png" width="180" border="0" /></td>
        </tr>
      </tbody>
    </table></td>
  </tr>
  <tr valign="top">
    <td width="188" height="20" align="right"><table cellspacing="0" cellpadding="0" border="0">
      <tbody>
        <tr>
          <td class="ct" valign="top" width="7" height="13"><img height="13" src="imagens/1.png" width="1" border="0" /></td>
          <td class="ct" valign="top" width="180" height="13">Outros Acrec</td>
        </tr>
        <tr>
          <td class="cp" valign="top" width="7" height="12"><img height="12" src="imagens/1.png" width="1" border="0" /></td>
          <td class="cp" valign="top" align="right" width="180" height="12"></td>
        </tr>
        <tr>
          <td valign="top" width="7" height="1"><img height="1" src="imagens/2.png" width="7" border="0" /></td>
          <td valign="top" width="180" height="1"><img height="1" src="imagens/2.png" width="180" border="0" /></td>
        </tr>
      </tbody>
    </table></td>
  </tr>
  <tr valign="top">
    <td width="188" height="20" align="right"><table cellspacing="0" cellpadding="0" border="0">
      <tbody>
        <tr>
          <td class="ct" valign="top" width="7" height="13"><img height="13" src="imagens/1.png" width="1" border="0" /></td>
          <td class="ct" valign="top" width="180" height="13">Vl. Cobrado</td>
        </tr>
        <tr>
          <td class="cp" valign="top" width="7" height="12"><img height="12" src="imagens/1.png" width="1" border="0" /></td>
          <td class="cp" valign="top" align="left" width="180" height="12"></td>
        </tr>
        <tr>
          <td valign="top" width="7" height="1"><img height="1" src="imagens/2.png" width="7" border="0" /></td>
          <td valign="top" width="180" height="1"><img height="1" src="imagens/2.png" width="180" border="0" /></td>
        </tr>
      </tbody>
    </table>
      <table cellspacing="0" cellpadding="0" border="0">
        <tbody>
          <tr>
            <td class="ct" valign="top" width="7" height="13"><img height="13" src="imagens/1.png" width="1" border="0" /></td>
            <td class="ct" valign="top" width="180" height="13">Sacado</td>
          </tr>
          <tr>
            <td class="cp" valign="top" width="7" height="12"><img height="12" src="imagens/1.png" width="1" border="0" /></td>
            <td class="cp" valign="top" align="left" width="180" height="12"><?php echo $dadosboleto["sacado"]?></td>
          </tr>
          <tr>
            <td valign="top" width="7" height="1"><img height="1" src="imagens/2.png" width="7" border="0" /></td>
            <td valign="top" width="180" height="1"><img height="1" src="imagens/2.png" width="180" border="0" /></td>
          </tr>
        </tbody>
      </table>
      <p>&nbsp;</p></td>
  </tr>
</table>
</div>
</body>
</html>
<?php
    if($xBoleto < '2'){
        $xBoleto++;
    }else{
        echo '<div style="page-break-after: always"></div>';
        $xBoleto = 0;
    }
?>
