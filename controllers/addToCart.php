<!-- Vložení produktu do košíku -->
<?php
	require_once '../inc/protection.php';
	
	// Načtení POSTU do proměnných
	$cislo = $_POST['cislo'];
	
	/*if(!isset($_POST['ks']) || $_POST['ks']<0){
		$ks = 1;	
	}*/

	// Vložení produktu pomocí dotazu do tabulky, která se poté načítá v košíku
	$sql_insert = "INSERT INTO tbl_Kosik (Cislo,Login) VALUES ('$cislo','$login')";
	

	$stmt = $db->prepare($sql_insert);

	// Kontrola zda dotaz vloží zboží do košíku
	if($stmt->execute()){
		echo '
			<div class="addToText badge badge-primary">
				<a href="/kosik">
					Zboží vloženo do košíku
				</a>
			</div>';
	}else{
		echo '
			<div class="addToText badge badge-danger">
  				<span class="sr-only">Error:</span>
				Zboží nelze vložit do košíku
			</div>';
	}
?>