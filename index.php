<!DOCTYPE html>
<html lang="en">
<head>
	<title>phploppuharkka</title>
	<meta charset="UTF-8">
	<style>
	.row-table {padding-left: 10px;	padding-right: 10px;}
	tr:hover {background-color: #42f4df;}
	th {text-align: center; padding: 7px;vertical-align: middle;}
	input,select {border: 3px solid #dddddd;border-radius: 7px;}
</style>
</head>
<body>
	<?php
		//vaihtoehdot
	$parametrit = array(
		array('value'=>'', 			'text'=>'---'),
		array('value'=>'kategoria', 	'text'=>'kategoria'),
		array('value'=>'id', 		'text'=>'id'),
		array('value'=>'hinta', 	'text'=>'hinta')
	);
	?>
	<!-- formi gettimetodilla -->
	<form action="index.php" method="get">
		<!-- hakuparametrin valinta -->
		Valitse hakutapa:<br>
		<select name="parametri">
			<?php foreach ($parametrit as $key => $value){?>
				<option value="<?php echo $value['value'] ?>" <?php echo (!empty($_GET['parametri']) && $_GET['parametri']==$value['value']?'valittu':'') ?>><?php echo $value['text'];?></option>
			<?php } ?>
		</select>
		<br><br>
		<!--  kirjoitus fieldi -->
		<br>
		<input type="text" name="strval" value="<?php echo (!empty($_GET['parametri']) && !empty($_GET['strval'])?$_GET['strval']:'') ?>"><br>
		ID: 1-10<br>
		Kategorioita: saippua, shampoo ja hammastahna<br>
		<input type="submit" value="Hae"><br><br>

	</form>
		<?php if(!$_GET){ //jos ei syöteparametria -> palauta tyhjä array
			$result = array();
		}else{ 
			//hae data API:sta
			$url = "http://localhost/PHP_kurssi/phploppuharkka/tuote.php";
			//tarkistetaan ettei parametri ole tyhjä
			if($_GET['parametri']!=''){
				$parametrit = '?' . $_GET['parametri'] . '=' . $_GET['strval'];
			}else{//hae ilman syötettä
				$parametrit = '';
			}
			$url = $url . $parametrit;
			//näytä tiedot
			//echo "API --> GET: " . $url . "<br/><br/>";
    		//taulukon ylätunnisteet
			$tunnisteet = array(
				'Content-Type: application/json',
				'Accept: application/json'
			);//juttelee jsonia
    		//curl API kutsu
			$curl = curl_init($url);
    		//API paluuarvoon 
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    		//taulukon ylätunnisteet
			curl_setopt($curl, CURLOPT_HTTPHEADER, $tunnisteet);
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($curl, CURLOPT_HTTPGET, true);
			if(!($curl_response = curl_exec($curl))){
				die('Virhe: "' . curl_error($curl) . '" - Virhekoodi: ' . curl_errno($curl));
			}
		    //sulje yhteys
			curl_close($curl);
		    //paluutulos
			$result = json_decode($curl_response, true);
		}?>

		<table border="2" style="border: 3px solid #dddddd; border-radius: 7px; border-collapse: collapse; ">
			<tr>
				<th>Id</th>
				<th>Kuva</th>
				<th>Nimi</th>
				<th>kategoria</th>
				<th>hinta</th>
				<th>paino</th>
			</tr>
			<!-- looppaa tulokset tauluun -->
			<?php foreach ($result as $key => $value){?>
				<tr>
					<td class="row-table"><?php echo $value['id']; ?></td>
					<td class="row-table"><img src="<?php echo $value['picurli']; ?>" alt="<?php echo $value['nimi']; ?>" width="50" height="50" /></td>
					<td class="row-table"><?php echo $value['nimi']; ?></td>
					<td class="row-table"><?php echo $value['kategoria']; ?></td>
					<td class="row-table"><?php echo $value['hinta']; ?></td>
					<td class="row-table"><?php echo $value['paino']; ?></td>
				</tr>
			<?php } ?>
		</table>

	</body>
	</html>