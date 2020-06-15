<?php
require_once 'connect.php';



if(isset($_COOKIE["logged"])){
	$ip = $_SERVER['REMOTE_ADDR'];
	$cookie = $_COOKIE["logged"];
	$login = $_COOKIE["login"];
	$overeni_query = $db->query("SELECT COUNT(login) AS Cnt FROM tbl_Logins WHERE login = '$login' AND password = '$cookie' AND password IS NOT NULL");
	$overeni = $overeni_query->fetch(PDO::FETCH_ASSOC);
	$overeni = $overeni['Cnt'];
	
	if($overeni != 1){
		setcookie('login',NULL, 0);
		setcookie('logged',NULL, 0);
		setcookie('username',NULL, 0);
		header('Location:/login.php');
	}
	
}else{
	setcookie('login',NULL, 0);
	setcookie('logged',NULL, 0);
	setcookie('username',NULL, 0);		
	header('Location: /login.php');
}

/*session_start();
if (!isset($_SESSION['username']) || !isset($_SESSION['loginHash'])) {
    // Redirection to login page twitter or facebook
    header("location: login.php");
}else{
	require_once 'connect.php';

	$username = $_SESSION['username'];
	$loginHash = $_SESSION['loginHash'];
	
	$query = "SELECT COUNT(username) AS cnt FROM tbl_Uzivatele WHERE username = '$username' AND loginHash='$loginHash' AND verified = '1'";
	$sql_query = $db->query($query);
	$sql = $sql_query->fetch(PDO::FETCH_ASSOC);
	if($sql['cnt']!='1'){
		session_destroy();
		header('location:login.php');
	}	
}*/
?>