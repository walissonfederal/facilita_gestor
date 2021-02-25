<?php
	session_start();
	ob_start();
	require_once '../../_class/Ferramenta.php';

	if($_GET['id_contato'] != ''){
		$md5_unico = '98989898';
		//$read_pedido = ReadComposta("SELECT * FROM pedido INNER JOIN contato ON contato.contato_id = pedido.pedido_id_cliente INNER JOIN itens_pedido ON itens_pedido.itens_pedido_id_pedido = pedido.pedido_id INNER JOIN chip ON chip.chip_id = itens_pedido.itens_pedido_id_chip WHERE pedido.pedido_id_cliente = '".$_GET['id_contato']."' ORDER BY pedido.pedido_id ASC");
		$read_pedido = Read('pedido', "WHERE pedido_id_cliente IN(
65,
76,
364,
428,
428,
509,
619,
668,
1057,
1062,
1090,
1092,
1111,
1124,
1130,
1131,
1132,
1134,
1141,
1147,
1148,
1148,
1153,
1158,
1173,
1175,
1176,
1177,
1180,
1187,
1188,
1190,
1216,
1226,
1271,
1289,
1300,
1314,
1317,
1318,
1323,
1337,
1341,
1352,
1352,
1354,
1359,
1380,
1387,
1405,
1406,
1410,
1410,
1425,
1479,
1487,
1502,
1511,
1518,
1521,
1522,
1524,
1538,
1578,
1604,
1611,
1624,
1629,
1630,
1647,
1650,
1653,
1662,
1677,
1687,
1700,
1700,
1700,
1700,
1716,
1725,
1726,
1727,
1730,
1758,
1783,
1783,
1783,
1799,
1800,
1802,
1804,
1806,
1809,
1817,
1828,
1832,
1836,
1837,
1842,
1850,
1852,
1854,
1855,
1868,
1869,
1869,
1873,
1875,
1886,
1891,
1894,
1904,
1910,
1917,
1933,
1937,
1942,
1943,
1944,
1952,
1961,
1962,
1963,
1965,
1972,
1977,
1979,
1985,
1992,
2008,
2011,
2019,
2021,
2022,
2023,
2029,
2030,
2035,
2036,
2036,
2038,
2043,
2052,
2054,
2058,
2061,
2068,
2069,
2071,
2075,
2076,
2077,
2081,
2081,
2082,
2083,
2084,
2086,
2094,
2096,
2098,
2103,
2111,
2112,
2113,
2116,
2117,
2118,
2124,
2126,
2127,
2128,
2139,
2152,
2154,
2155,
2156,
2157,
2161,
2165,
2165,
2165,
2170,
2170,
2170,
2172,
2175,
2176,
2178,
2178,
2178,
2180,
2182,
2186,
2193,
2194,
2198,
2210,
2212,
2214,
2220,
2221,
2226,
2229,
2233,
2235,
2236,
2237,
2251,
2254,
2265,
2269,
2276,
2277,
2289,
2293,
2295,
2295,
2296,
2300,
2305,
2308,
2323,
2324,
2329,
2331,
2332,
2332,
2338,
2338,
2343,
2344,
2345,
2346,
2351,
2356,
2361,
2362,
2362,
2368,
2372,
2374,
2374,
2374,
2376,
2386,
2389,
2391,
2395,
2398,
2400,
2408,
2412,
2413,
2414,
2415,
2416,
2419,
2425,
2426,
2442,
2443,
2449,
2452,
2455,
2458,
2458,
2465,
2467,
2471,
2471,
2471,
2471,
2483,
2484,
2485,
2487,
2491,
2493,
2496,
2498,
2499,
2504,
2504,
2505,
2511,
2513,
2514,
2516,
2518,
2520,
2522,
2525,
2531,
2534,
2534,
2542,
2542,
2543,
2546,
2547,
2549,
2557,
2558,
2559,
2561,
2565,
2577,
2578,
2580,
2584,
2585,
2588,
2593,
2595,
2596,
2617,
2622,
2622,
2628,
2629,
2634,
2637,
2641,
2645,
2646,
2647,
2648,
2655,
2657,
2666,
2679,
2681,
2683,
2689,
2707,
2716,
2718,
2719,
2722,
2724,
2725,
2735,
2751,
2759) AND pedido_status IN(0,1) ORDER BY pedido_id ASC LIMIT 0,1");
		if(NumQuery($read_pedido) > '0'){
			foreach($read_pedido as $read_pedido_view){
				$read_contato = Read('contato', "WHERE contato_id = '".$read_pedido_view['pedido_id_cliente']."'");
				if(NumQuery($read_contato) > '0'){
					foreach($read_contato as $read_contato_view);
				}
				$read_chip = Read('itens_pedido', "WHERE itens_pedido_id_pedido = '".$read_pedido_view['pedido_id']."'");
				if(NumQuery($read_chip) > '0'){
					foreach($read_chip as $read_chip_view){
						$read_pedido_chip = Read('chip', "WHERE chip_id = '".$read_chip_view['itens_pedido_id_chip']."'");
						if(NumQuery($read_pedido_chip) > '0'){
							foreach($read_pedido_chip as $read_pedido_chip_view);
						}
						$excel_form['excel_pedido_id_pedido'] = $read_pedido_view['pedido_id'];
						$excel_form['excel_pedido_id_contato'] = $read_pedido_view['pedido_id_cliente'];
						$excel_form['excel_pedido_contato'] = $read_contato_view['contato_nome_razao'].' - '.$read_contato_view['contato_nome_fantasia'];
						$excel_form['excel_pedido_data_ativacao'] = $read_pedido_view['pedido_data_ativacao'];
						$excel_form['excel_pedido_status'] = $read_pedido_view['pedido_status'];
						$excel_form['excel_pedido_tipo'] = $read_pedido_view['pedido_tipo'];
						$excel_form['excel_pedido_iccid'] = $read_pedido_chip_view['chip_iccid'];
						$excel_form['excel_pedido_num'] = $read_pedido_chip_view['chip_num'];
						$excel_form['excel_md5'] = $md5_unico;
						$excel_form['excel_pedido_id_chip'] = $read_pedido_chip_view['chip_id'];
						if($read_pedido_view['pedido_tipo'] == '0'){
							$read_itens_faturamento_ja_existe = ReadComposta("SELECT excel_pedido_id FROM excel_pedido WHERE excel_md5 = '".$md5_unico."' AND excel_pedido_id_chip = '".$read_pedido_chip_view['chip_id']."'");
							if(NumQuery($read_itens_faturamento_ja_existe) == '0'){
								Create('excel_pedido', $excel_form);
							}
						}elseif($read_pedido_view['pedido_tipo'] == '1'){
							Delete('excel_pedido', "WHERE excel_pedido_id_chip = '".$read_pedido_chip_view['chip_id']."' AND excel_md5 = '".$md5_unico."'");
						}else{
							//Create('excel_pedido', $excel_form);	
						}
					}
				}
			}
		}
		/*
		$arquivo = 'cargo.xls';
		$tabela = '<table border="1" width="800px">';
			$tabela .= '<tr>';
				$tabela .= '<td colspan="8" align="center">Relação de cargos</tr>';
			$tabela .= '</tr>';
			$tabela .= '<tr>';
				$tabela .= '<td><b>ID PEDIDO</b></td>';
				$tabela .= '<td><b>ID CONTATO</b></td>';
				$tabela .= '<td><b>CONTATO</b></td>';
				$tabela .= '<td><b>DATA ATIVACAO</b></td>';
				$tabela .= '<td><b>STATUS</b></td>';
				$tabela .= '<td><b>TIPO</b></td>';
				$tabela .= '<td><b>ICCID</b></td>';
				$tabela .= '<td><b>NUM LINHA</b></td>';
			$tabela .= '</tr>';
		
		$read_periodo = Read('excel_pedido', "WHERE excel_md5 = '".$md5_unico."'");
		if(NumQuery($read_periodo) > '0'){
			foreach($read_periodo as $read_periodo_view){
				if($read_periodo_view['excel_pedido_status'] == '0'){
					$status_pedido = 'Em andamento';
				}elseif($read_periodo_view['excel_pedido_status'] == '1'){
					$status_pedido = 'Finalizado';
				}elseif($read_periodo_view['excel_pedido_status'] == '2'){
					$status_pedido = 'Cancelado';
				}elseif($read_periodo_view['excel_pedido_status'] == '3'){
					$status_pedido = 'Bloqueado';
				}
				if($read_periodo_view['excel_pedido_tipo'] == '0'){
					$type_pedido = 'Instalação';
				}elseif($read_periodo_view['excel_pedido_tipo'] == '1'){
					$type_pedido = 'Desinstalação';
				}elseif($read_periodo_view['excel_pedido_tipo'] == '2'){
					$type_pedido = 'SMS';
				}
				$tabela .= '<tr>';
					$tabela .= '<td>'.utf8_encode($read_periodo_view['excel_pedido_id_pedido']).'</td>';
					$tabela .= '<td>'.utf8_encode($read_periodo_view['excel_pedido_id_contato']).'</td>';
					$tabela .= '<td>'.utf8_encode($read_periodo_view['excel_pedido_contato']).'</td>';
					$tabela .= '<td>'.utf8_encode($read_periodo_view['excel_pedido_data_ativacao']).'</td>';
					$tabela .= '<td>'.utf8_encode($status_pedido).'</td>';
					$tabela .= '<td>'.utf8_encode($type_pedido).'</td>';
					$tabela .= '<td>'.utf8_encode($read_periodo_view['excel_pedido_iccid']).'-</td>';
					$tabela .= '<td>'.utf8_encode($read_periodo_view['excel_pedido_num']).'-</td>';
				$tabela .= '</tr>';
			}
		}
		
		header("Content-type: application/vnd.ms-excel");  
		header("Content-type: application/force-download"); 
		header("Content-Disposition: attachment; filename=file.xls");
		header("Pragma: no-cache");
		echo $tabela;
		*/
	}
?>