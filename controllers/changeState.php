<!-- Kontroler zajišťující změnu stavu -->
<?php
	require_once '../inc/protection.php';

	// Načtení POSTU do proměnných
	$id = $_POST['id'];
	$action = $_POST['action'];
	$note = $_POST['note'];
	$username = $_COOKIE['username'];
	
	// Načtení dočasného stavu
	$tempStav = $db->query("SELECT [ID],[Nazev],[Popis],[DalsiKrok],[DnuNaDalsiKrok] FROM tbl_Stav WHERE ID = '$action'")->fetch();
	$action=$tempStav['Nazev'];
	$actionDays=$tempStav['DnuNaDalsiKrok'];
	$datum = date('Y-m-d H:i:s');

	// Vypočítání data následného kroku a jeho aktualizace.
	$datum_dk = date('Y-m-d H:i:s', strtotime('+'.$actionDays.' days'));
	$db->query("UPDATE tbl_Nabidky SET stav = '$action', kontaktovan = '$datum', dalsi_kontakt='$datum_dk' WHERE id = '$id'");

	// Uložení logu z jakého na jaký stav se přepnulo, kdo to udělal, datum a poznámka.
	$db->query("INSERT INTO tbl_Nabidky_log (idNabidky,username,akce,datum,poznamka) VALUES ('$id','$username','$action','$datum','$note')");
	
	// Vrácení odpověďi při zavolání kontroleru
	$response_array['status'] = 'success'; 
	$response_array['msg'] = '
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="itemsLabel">Stav nabídky</h4>
		</div>
		<div class="modal-body">
			Stav nabídky byl nastaven na "'.$action.'".
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Zavřít</button>
		</div>'; 
	print json_encode($response_array);
	exit;	
?>