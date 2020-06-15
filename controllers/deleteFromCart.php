<!-- Kontroler pro mazání věcí z košíku -->
<?php 
	require_once '../inc/protection.php';
	session_start();
	require_once '../inc/connect.php';
	
	// Načtení POSTU a Cookies do proměnných
	$login = $_COOKIE['login'];
	$cislo = $_POST['cislo'];
	
	
	/*if(!isset($_POST['ks']) || $_POST['ks']<0){
		$ks = 1;	
	}*/

	// Dotaz, který smaže zboží z košíku, pokud nalezne v košíku záznam o zboží a zároveň pokud se jedná o přihlášeného prodejce
	$sql_delete = "DELETE TOP(1) FROM tbl_Kosik WHERE Cislo = '$cislo' AND Login = '$login'";
	
	$stmt = $db->prepare($sql_delete);
	$stmt->execute();
?>