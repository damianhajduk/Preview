</div> <?php /* zavření předchozího containeru, abych mohl udělat container přes celou šířku */ ?>
<?php

	$login = $_COOKIE['login'];
	$username = $_COOKIE['username'];

	$prehled_query = $db->query("SELECT DISTINCT pred.* FROM tbl_Logins AS l LEFT JOIN tbl_Prodejci AS p ON l.kod = p.stredisko INNER JOIN tbl_Predobjednavky AS pred ON p.username = pred.login WHERE l.login = '$login' AND Cislo IN (SELECT cislo FROM tbl_Predobjednavky_zbozi WHERE GETDATE() BETWEEN od AND do ) ORDER BY datum ASC");
	
	echo '<br><br>
<div class="container-fluid">
	<div class="row">
	<div class="col-12">
		<table class="table table-striped">';
		// Vypsání předobjednávek do tabulky
	while($prehled = $prehled_query->fetch()){
		$datum = date('d.m.Y H:i',strtotime($prehled['datum']));
		if($prehled['kontakt']!=NULL ){$kontakt = date('d.m.Y H:i',strtotime($prehled['kontakt']));}else{$kontakt = 'Zákazník nekontaktován';}
		if(strlen($prehled['mail'])>0 || strlen($prehled['tel'])>0){$form = '<form method="post" action="inc/save-s6.php"><input type="hidden" name="idRezervace" value="'.$prehled['id'].'"><input type="submit" value="Skladem"></form>';$mail = '<a href="mailto:'.$prehled['mail'].'">'.$prehled['mail'].'</a>';}else{$form = $mail = '';}
		if($_GET['edit']==$prehled['id']){
			$active = ' active';
		}else{
			$active = '';
		}
		$tr = '';
		if($prehled['stav']=='prodáno'){$tr='table-success';}
		if($prehled['stav']=='zrušeno'){$tr='table-danger';}
		echo '
			<tr class="'.$tr.'">
				<td>#'.$prehled['id'].'</td>
				<td>'.$prehled['jmeno'].'</td>
				<td>'.$prehled['tel'].'</td>
				<td>'.$mail.'</td>
				<td>'.$prehled['model'].'</td>
				<td>'.$datum.'</td>
				<td>'.$prehled['login'].'</td>
				<td class="text-right">
					<a href="/predobjednavky?edit='.$prehled['id'].'" class="btn btn-outline-primary'.$active.'" data-toggle="tooltip" data-placement="top" title="Upravit"><i class="fas fa-pencil-alt"></i></a>
					<form method="post" action="/controllers/save-predobjednavky.php" method="post" class="d-inline"><button type="submit" name="zrusit" value="'.$prehled['id'].'" data-toggle="tooltip" data-placement="top" title="Zrušit" class="btn btn-outline-danger"><i class="fas fa-trash-alt"></i></button></form>
					<form method="post" action="/controllers/save-predobjednavky.php" method="post" class="d-inline"><button type="submit" name="prodano" value="'.$prehled['id'].'" data-toggle="tooltip" data-placement="top" title="Prodáno" class="btn btn-outline-success"><i class="fas fa-check"></i></button></form>
				</td>
			</tr>
			<tr class="'.$tr.'">
				<td></td>
				<td colspan="7">'.$prehled['poznamka'].'</td>
			</tr>
		';	
		
	}
	echo '</table>
	</div></div></div>';
?>
<div class="container"> <?php /* otevření nového containeru - viz první řádek */?>