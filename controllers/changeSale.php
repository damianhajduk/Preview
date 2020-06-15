<!-- Kontroler, který zajišťuje změnu slevy u produktu. -->
<?php
	require_once '../inc/protection.php';

	// Načtení POSTU do proměnných
	$id = $_POST['id'];
	$idNabidky = $_POST['idNabidky'];
	$sale = $_POST['sale'];
	$username = $_COOKIE['username'];
	

	$datum = date('Y-m-d H:i:s');
	
	// Uložení logu z jakého na jaký stav se přepnulo, kdo to udělal, datum a poznámka.
	$db->query("INSERT INTO [tbl_Nabidky_log] (idnabidky, username, akce, data, datum, poznamka) VALUES ($idNabidky, '$username', 'Změna slevy', null,getdate(),'Změna slevy zboží ' + (select cast(Cislo as varchar(20)) as Cislo from tbl_Nabidky_Zbozi where id = $id) + ' z ' + (select cast(Sleva as varchar(6)) from tbl_Nabidky_Zbozi where id = $id) + 'Kč na $sale Kč.');update [tbl_Nabidky_Zbozi] SET Sleva = $sale WHERE ID=$id");
	
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