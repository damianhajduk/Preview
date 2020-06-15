<!-- Kontroler zajišťující změnu hesla -->
<?php
	require_once '../inc/protection.php';
	
	// Načtení POSTU do proměnných
	$oldPassword = $_POST['hesloPuvodni'];
	$newPassword = $_POST['hesloNove'];
	$newPassword2 = $_POST['hesloNove2'];
	
	// Vytvoření hashe ze stareého a nového hesla
	$oldPasswordHash = sha1($oldPassword.'SaLt~15963[]×');
	$newPasswordHash = sha1($newPassword.'SaLt~15963[]×');

	// Podmínka ověří zda se nová hesla shodují
	if($newPassword==$newPassword2){
		// Z DB se vybere záznam uživatele
		$user_query = $db->query("SELECT COUNT(username)AS cnt FROM tbl_Uzivatele WHERE username = '$username' AND heslo = '$oldPasswordHash' AND loginHash = '$loginHash'");
		$user = $user_query->fetch(PDO::FETCH_ASSOC);
		// Podmínka, která ověří zda záznam nalezl a v případě, že ANO, pak se záznam zaktualizuje.
		if($user['cnt']=='1'){
			$loginHashNew = sha1($oldPasswordHash.'SaLt~15963[]×'.$newPasswordHash.'SaLt~15963[]×'.$oldPasswordHash);
			$db->query("UPDATE tbl_Uzivatele SET heslo = '$newPasswordHash', loginHash = '$loginHashNew' WHERE username = '$username' AND heslo = '$oldPasswordHash' AND loginHash = '$loginHash'");
			session_destroy();
			header('location:/login.php?msg=heslo-zmeneno');
		}else{
			header('location:/index.php?page=profil&error=heslo&msg=spatne-heslo#zmenitHeslo');	
		}
	}else{
		header('location:/index.php?page=profil&error=heslo&msg=hesla-se-neshoduji#zmenitHeslo');		
	}
	
?>