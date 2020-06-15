<!-- Kontroler zajišťující změnu následného kroku -->
<?php
	require_once '../inc/protection.php';

	// Načtení POSTU do proměnných
	$idNabidky = $_POST['idNabidky'];
    $nextContact = $_POST['nextContact'];
    $nextContactLog = strftime("%d.%m.%Y", strtotime($nextContact));
	$username = $_COOKIE['username'];

	$datum = date('Y-m-d H:i:s');
	
	// Dotaz (INSERT) do DB
	$db->query("INSERT INTO [tbl_Nabidky_log] ( idnabidky, username, akce, data, datum, poznamka ) VALUES ( $idNabidky, '$username', 'Změna dalšího kontaktu', null, getdate(), 'Změna dalšího kontaktu na datum $nextContactLog');update [tbl_Nabidky] SET dalsi_kontakt = '$nextContact' WHERE id=$idNabidky");
	
	// Odpověd na zavolání kontroleru
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