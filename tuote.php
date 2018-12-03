<?php

	function getKaikkituote(){
		$tuote_json = file_get_contents("tuote.json"); // lista kaikista tuotteista
		$tuote = json_decode($tuote_json, true); // dekoodaa string objektista arrayhyn
		return $tuote;
	}

	function gettuotekategoria($kategoria){
		$tuote_json = file_get_contents("tuote.json");
		$tuote = json_decode($tuote_json, true);
		//filteröi array kategorioittain
		$tuote_kategorioittain = array_filter($tuote, function ($var) use ($kategoria) {
			return ($var['kategoria'] == $kategoria);
		});
		return $tuote_kategorioittain;
	}

	//tuote id:itäin
	function gettuoteId($id){
		$tuote_json = file_get_contents("tuote.json");
		$tuote = json_decode($tuote_json, true);
		$tuote_by_id = array_filter($tuote, function ($var) use ($id) {
			return ($var['id'] == $id);
		});
		//muutetaan yksittäiseksi olioksi
		//$return = new stdClass();
		$return = array();
		foreach ($tuote_by_id as $key => $value) {
			$return[] = $value;
		}
		return $return;
	}

	function gettuoteMaxhinta($hinta){
		$tuote_json = file_get_contents("tuote.json");
		$tuote = json_decode($tuote_json, true);
		$tuote_by_hinta = array_filter($tuote, function ($var) use ($hinta) {
			return ($var['hinta'] < $hinta);
		});
		//olio arrayksi
		$return = array();
		foreach ($tuote_by_hinta as $key => $value) {
			$return[] = $value;
		}
		return $return;
	}

	//haetaan parametrista
	$hinta = empty($_GET['hinta'])?'':$_GET['hinta'];
	$kategoria = empty($_GET['kategoria'])?'':$_GET['kategoria'];
	$id = empty($_GET['id'])?'':$_GET['id'];
	//jos kategoria ei ole tyhjä, haetaan kategorioittain
	if($kategoria!=''){
		$tuote = gettuotekategoria($kategoria);
	//jos hinta ei ole tyhjä, haetaan hinnan mukaan
	}elseif($hinta!=''){
		$tuote = gettuoteMaxhinta($hinta);
	//id:n mukaan
	}elseif($id!=''){
		$tuote = gettuoteId($id);
	//muuten kaikki
	}else{
		$tuote = getKaikkituote();
	}
	//ja takasi Jsoniksi
	echo json_encode($tuote);
	?>