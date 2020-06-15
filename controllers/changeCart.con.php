<?php

if(!isset( $_SERVER['HTTP_X_REQUESTED_WITH']) || ( $_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest' )){
	header('location:/');
	exit;
}

try{

    require_once '../inc/connect.php';
	require '../inc/protection.php';

    // Načtení cookies do proměnných
	$login = $_COOKIE['login'];
    $username = $_COOKIE['username'];
    
    // Načtení POSTU do proměnných
    $cislo = trim($_POST['cislo']);

    // Pokud se v postu zašle změna kusů, v košíku se zaktualizuje počet kusů zboží
    if($_POST['akce']=='ks'){
        $ks = intval($_POST['ks']);
        $sleva = intval($_POST['sleva']);
        $stmt = $db->prepare("DELETE FROM tbl_Kosik WHERE Cislo = :cislo AND Login = :login");
        $stmt->bindParam(':cislo',$cislo);
        $stmt->bindParam(':login',$login);
        $stmt->execute();

            $stmt = $db->prepare("INSERT INTO tbl_Kosik (Cislo, Login, Ks, Sleva) VALUES (:cislo,:login, :ks, :sleva)");
            $stmt->bindParam(':cislo',$cislo);
            $stmt->bindParam(':login',$login);
            $stmt->bindParam(':ks',$ks);
            $stmt->bindParam(':sleva',$sleva);
            $stmt->execute();
        $response_array['status'] = 'success'; 
	    print json_encode($response_array);
	    exit;	
    }

    // Pokud se v postu zašle sleva, v košíku se zaktualizuje cena zboží se slevou
    if($_POST['akce']=='sleva'){
        $sleva = intval($_POST['sleva']);
        $stmt = $db->prepare("UPDATE tbl_Kosik SET Sleva = :sleva WHERE Cislo = :cislo AND Login = :login");
        $stmt->bindParam(':cislo',$cislo);
        $stmt->bindParam(':login',$login);
        $stmt->bindParam(':sleva',$sleva);
        $stmt->execute();
        
        $response_array['status'] = 'success'; 
	    print json_encode($response_array);
	    exit;	
    }
// Zachycení a vypsání výjimky
}catch (Exception $e){
	$response_array['status'] = 'error'; 
	//$response_array['status'] = 'Chyba, více informací je v logu';	
	print json_encode($response_array);
	exit;	
}
?>