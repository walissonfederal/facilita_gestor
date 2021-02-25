<?php
	session_start();
	ob_start();
	require_once '_class/Ferramenta.php';
	
	$read_ativacao = Read('ativacao', "WHERE ativacao_data BETWEEN '2018-08-01' AND '2018-08-31' AND ativacao_status = '0' LIMIT 0,30;");
    if(NumQuery($read_ativacao) > '0'){
        foreach($read_ativacao as $read_ativacao_view){
			$read_itens_ativacao = Read('itens_ativacao', "WHERE itens_ativacao_id_ativacao = '".$read_ativacao_view['ativacao_id']."'");
			if(NumQuery($read_itens_ativacao) > '0'){
				foreach($read_itens_ativacao as $read_itens_ativacao_view);
				//print_r($read_itens_ativacao_view);
				$read_itens_pedido = Read('itens_pedido', "WHERE itens_pedido_id_chip = '".$read_itens_ativacao_view['itens_ativacao_id_chip']."' ORDER BY itens_pedido_id DESC");
				if(NumQuery($read_itens_pedido) > '0'){
					foreach($read_itens_pedido as $read_itens_pedido_view);
					$ID_PEDIDO_CORRETO = $read_itens_pedido_view['itens_pedido_id_pedido'];
					//print_r($read_itens_pedido_view);
				}
			}
			$pedido_form['pedido_id_cliente']    = $read_ativacao_view['ativacao_id_contato'];
			$pedido_form['pedido_data']       	 = $read_ativacao_view['ativacao_data'];
			$pedido_form['pedido_data_ativacao'] = $read_ativacao_view['ativacao_data'];
			$pedido_form['pedido_tipo']          = '0';
			$pedido_form['pedido_status']        = '1';
			$pedido_form['pedido_id_plano']      = GetDados('pedido', $read_itens_pedido_view['itens_pedido_id_pedido'], 'pedido_id', 'pedido_id_plano');
			$pedido_form['pedido_valor_plano']   = GetDados('pedido', $read_itens_pedido_view['itens_pedido_id_pedido'], 'pedido_id', 'pedido_valor_plano');
			$pedido_form['pedido_valor_ativacao']= GetDados('pedido', $read_itens_pedido_view['itens_pedido_id_pedido'], 'pedido_id', 'pedido_valor_ativacao');
			$pedido_form['pedido_id_user']       = $_SESSION[VSESSION]['user_id'];
			$pedido_form['pedido_tipo_frete']    = '0';
			$pedido_form['pedido_valor_frete']   = '0';
			$pedido_form['pedido_obs']           = 'PEDIDO FEITO AUTOMATICAMENTE PELO SISTEMA, OS CHIPS FORAM INSERIDOS PELO CLIENTE VIA PAINEL DO MESMO';
			$pedido_form['pedido_data_hora']     = date('Y-m-d H:i:s');
			//print_r($pedido_form);
			Create('pedido', $pedido_form);
			$id_pedido_ultimo = GetReg('pedido', 'pedido_id', "");
			if(NumQuery($read_itens_ativacao) > '0'){
				foreach($read_itens_ativacao as $read_itens_ativacao_view){
					$itens_pedido_form['itens_pedido_id_pedido'] = $id_pedido_ultimo;
					$itens_pedido_form['itens_pedido_id_chip']   = $read_itens_ativacao_view['itens_ativacao_id_chip'];
					$itens_pedido_form['itens_pedido_num_chip']  = GetDados('chip', $read_itens_ativacao_view['itens_ativacao_id_chip'], 'chip_id', 'chip_num');
					$itens_pedido_form['itens_pedido_iccid']     = GetDados('chip', $read_itens_ativacao_view['itens_ativacao_id_chip'], 'chip_id', 'chip_iccid');
					//print_r($itens_pedido_form);
					//print_r($read_itens_pedido_view);
					Create('itens_pedido', $itens_pedido_form);
					Delete('itens_pedido', "WHERE itens_pedido_id_chip = '".$read_itens_ativacao_view['itens_ativacao_id_chip']."' AND itens_pedido_id_pedido = '".$ID_PEDIDO_CORRETO."'");
					
					//echo $ID_PEDIDO_CORRETO;
					//echo $read_itens_ativacao_view['itens_ativacao_id_chip'].'-';
					//echo $ID_PEDIDO_CORRETO.'<br />';
					
				}
			}
			$UpDateDados['ativacao_status'] = '1';
			Update('ativacao', $UpDateDados, "WHERE ativacao_id = '".$read_ativacao_view['ativacao_id']."'");
			$UpDateDadosOK['itens_ativacao_status'] = '2';
			Update('itens_ativacao', $UpDateDadosOK, "WHERE itens_ativacao_id_ativacao = '".$read_ativacao_view['ativacao_id']."'");
		}
    }
	
	/*
	$var_chip = '5562996796990,89550680137001030548;5562998535895,89550680137001030878;5562998437843,89550680137001014757;5562999442206,89550680137000997473;5562998123614,89550680137001017297;5562999334689,89550680137001017305;5562999973355,89550680137001017461;5562999613046,89550680137001017271;5562999326274,89550680137001017826;5562999411157,89550680137001017818;5562999297238,89550680137001017479;5562998498469,89550680137001010441;5562998502803,89550680137001010839;5562996107499,89550680137001010433;5562996366244,89550680137001017289;5562999047980,89550680137001017545;5562999677572,89550680137001017867;5562998227564,89550680137001017834;5562999005906,89550680137001017859;5562999531293,89550680137001010284;5562999351786,89550680137001017537;5562996589618,89550680137001017941;5562998252360,89550680137001017933;5562996161619,89550680137001010656;5562999451647,89550680137001014542;5562996280395,89550680137001014799;5562999371847,89550680137001017149;5562999234498,89550680137001014567;5562996443884,89550680137001010862;5562999539865,89550680137001017917;5562996885476,89550680137001017958;5562999901579,89550680137001014807;5562999368819,89550680137001017099;5562999788524,89550680137001017107;5562999063338,89550680137001017909;5562998529345,89550680137001017925;5562996935942,89550680137001014831;5562999841556,89550680137001017800;5562996713168,89550680137000951082;5562998119351,89550680137000951090;5562999578570,89550680137000951116;5562999414318,89550680137000951124;5562999839181,89550680137000951132;5562999591452,89550680137000951728;5562999619141,89550680137000951066;5562999619783,89550680137000951074;5562998220720,89550680137001017685;5562999745702,89550680137001017875;5562999509178,89550680137001014609;5562999631374,89550680137001014591;5562998246724,89550680137001014583;5562998230784,89550680137001017693;5562999670286,89550680137001017701;5562998482856,89550680137001017347;5562998430314,89550680137001017750;5562996546384,89550680137001017669;5562999417967,89550680137001017354;5562999576778,89550680137001017503;5562996832202,89550680137001017495;5562999894853,89550680137001018022;5562999970260,89550680137001018030;5562998350049,89550680137001018014;5562999157642,89550680137001017982;5562999396778,89550680137001014856;5562999615620,89550680137001017156;5562998143594,89550680137001017842;5562999156731,89550680137001017784;5562999690550,89550680137001017966;5562999275338,89550680137001014575;5562996477392,89550680137000951108;5562999494215,89550680137001018048;5562996742508,89550680137001014948;5562996441759,89550680137001017370;5562996864298,89550680137001017164;5562998562659,89550680137001017511;5562998324844,89550680137001017990;5562999321068,89550680137001018006;5562996855768,89550680137001014732;5562998351746,89550680137001018063;5562996495826,89550680137001017362;5562996190976,89550680137001017123;5562999032515,89550680137001017115;5562996896532,89550680137001017792;5562999234511,89550680137001017776;5562999313250,89550680137001014823;5562996158049,89550680137001018055;5562998225930,89550680137001017677;5562998325397,89550680137001017719;5562998123238,89550680137001014914;5562996179834,89550680137001014930;5562996053174,89550680137001017529;5562996610995,89550680137001017487;5562998693131,89550680137001017891;5562998263024,89550680137001017974;5562998362885,89550680137001014922;5562998264811,89550680137001014740';
	$explode_chip = explode(";", $var_chip);
	echo '<pre>';
		print_r($explode_chip);
	echo '</pre>';
        echo '<hr />';
	$chip_encontrado = '0';
	for($x=0;$x<count($explode_chip);$x++){
		$explode_dados = explode(",", $explode_chip[$x]);
		$read_chip = Read('chip', "WHERE chip_num = '".trim($explode_dados[0])."'");
		if(NumQuery($read_chip) > '0'){
			$chip_encontrado += 1;
			$UpDate['chip_iccid'] = trim($explode_dados[1]);
                        echo '<pre>';
                            print_r($UpDate);
                        echo '</pre>';
                        echo 'encontrado';
			Update('chip', $UpDate, "WHERE chip_num = '".trim($explode_dados[0])."'");
                        echo '<hr />';
		}
		echo '<pre>';
			print_r($explode_dados);
		echo '</pre>';
                echo '<hr />';
		
	}
	echo $chip_encontrado;
?>
<?php
	/*
	if(isset($_POST['send_ok'])){
		$iccid_dados = $_POST['iccid'];
		$count_dados_chip = '0';
		$explode_iccid = explode(';', $iccid_dados);
		$count_explode_iccid = count($explode_iccid);
		for($x=0;$x<$count_explode_iccid;$x++){
			$iccid_novo_velho = $explode_iccid[$x];
			$explode_iccid_novo_velho = explode(',', $iccid_novo_velho);
			
			$read_chip = Read('chip', "WHERE chip_iccid = '".trim($explode_iccid_novo_velho[1])."'");
			if(NumQuery($read_chip) > '0'){
				$count_dados_chip++;
				//echo $count_dados_chip.'<br />';
				//echo $explode_iccid_novo_velho[1].'<br />';
			}
		}
		$_SESSION['dados_chip'] = $iccid_dados;
		$contagem_correta = $count_dados_chip - 1;
		echo 'Foi encontrado '.$contagem_correta.' chip(s) para mudanca, deseja realmente finalizar essa operacao? Clique <a href="mudar-chip.php?acao=true">Aqui</a>';
	}
	if(isset($_GET['acao']) && $_GET['acao'] == 'true'){
		$iccid_dados = $_SESSION['dados_chip'];
		$count_dados_chip = '0';
		$explode_iccid = explode(';', $iccid_dados);
		$count_explode_iccid = count($explode_iccid);
		for($x=0;$x<$count_explode_iccid;$x++){
			$iccid_novo_velho = $explode_iccid[$x];
			$explode_iccid_novo_velho = explode(',', $iccid_novo_velho);
			
			$read_chip = Read('chip', "WHERE chip_iccid = '".trim($explode_iccid_novo_velho[1])."'");
			if(NumQuery($read_chip) > '0'){
				$count_dados_chip++;
				foreach($read_chip as $read_chip_view);
				$UpICCID['chip_iccid'] = trim($explode_iccid_novo_velho[0]);
				Update('chip', $UpICCID, "WHERE chip_id  = '".$read_chip_view['chip_id']."'");
			}
		}
		unset($_SESSION['dados_chip']);
		echo "<script>alert('OK, Obrigado');</script>";
		echo "<script>window.location = 'mudar-chip.php'</script>";
	}*/
	/*
?>
<form action="" method="post">
  Chips - iccid novo, iccid velho;<br>
  <textarea name="iccid"></textarea>
  
  <input type="submit" name="send_ok" value="Submit">
</form>*/?>