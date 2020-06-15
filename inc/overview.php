<?php
require_once 'protection.php';

$prodejce = $dbS->prepare("SELECT * FROM tbl_Prodejci WHERE username = '$login'");
$prodejce->execute();
$prodejce = $prodejce->fetch(PDO::FETCH_ASSOC);

//! NEW FILTERING
if($prodejce['vedouci']==0){
	if (isset($_GET['filtrovat'])) {
		$x=0;
		$where = "WHERE (prodejce = '$login' OR tbl_Prodejci.stredisko=(SELECT stredisko from tbl_Prodejci where username='$login')) AND (";

		//! Filtr typy
		foreach($_GET['filtrTypy'] as $filtrTypy){
			$where .= "typ_zaznamu =  '$filtrTypy' OR ";
			$x++;
		}

		//! Filtr Prodejci
		if (isset($_GET['filtrProdejci'])) {
			if (isset($_GET['filtrTypy'])) {
				$where = substr($where,0,strlen($where)-4);
				$where .= ") AND (";
			}
			foreach($_GET['filtrProdejci'] as $filtrProdejci){
				$where .= "prodejce =  '$filtrProdejci' OR ";
				$x++;
			}
		}

		//! Filtr Stavy
		if (isset($_GET['filtrStavy'])) {
			if (isset($_GET['filtrTypy']) || isset($_GET['filtrProdejci'])) {
				$where = substr($where,0,strlen($where)-4);
				$where .= ") AND (";
			}
			foreach($_GET['filtrStavy'] as $filtrStavy){
				$where .= "stav =  '$filtrStavy' OR ";
				$x++;
			}
		}
		
		//! Filtr Strediska
		if (isset($_GET['filtrStrediska'])) {
			if (isset($_GET['filtrTypy']) || isset($_GET['filtrStavy']) || isset($_GET['filtrProdejci'])) {
				$where = substr($where,0,strlen($where)-4);
				$where .= ") AND (";
			}
			foreach($_GET['filtrStrediska'] as $filtrStrediska){
				$where .= "stredisko =  '$filtrStrediska' OR ";
				$x++;
			}
		}

		//! Filtr Datum
		if (isset($_GET['filtrDatum1']) && isset($_GET['filtrDatum2']) && strlen($_GET['filtrDatum1'])>0 && strlen($_GET['filtrDatum2'])>0) {
			if (isset($_GET['filtrTypy']) || isset($_GET['filtrStavy']) || isset($_GET['filtrProdejci']) || isset($_GET['filtrStrediska'])) {
				$where = substr($where,0,strlen($where)-4);
				$where .= ") AND (";
			}
				$where .= "tbl_Nabidky.datum BETWEEN '".$_GET['filtrDatum1']."' AND '".$_GET['filtrDatum2']."' OR ";
				$x++;
		}

		//! Filtr Datum Dalšího kroku
		if (isset($_GET['filtrDatumD1']) && isset($_GET['filtrDatumD2']) && strlen($_GET['filtrDatumD1'])>0 && strlen($_GET['filtrDatumD2'])>0) {
			if (isset($_GET['filtrTypy']) || isset($_GET['filtrStavy']) || isset($_GET['filtrProdejci']) || isset($_GET['filtrStrediska']) || isset($_GET['filtrDatum1']) || isset($_GET['filtrDatum2'])) {
				$where = substr($where,0,strlen($where)-4);
				$where .= ") AND (";
			}
				$where .= "tbl_Nabidky.dalsi_kontakt BETWEEN '".$_GET['filtrDatumD1']."' AND '".$_GET['filtrDatumD2']."' OR ";
				$x++;
		}

		//! Ošetření dotazu
		if($x>0){
			$where_query .= substr($where,0,strlen($where)-3);
			$where_query .= ")";
		}
		else{
			$where_query = "WHERE (prodejce = '$login' OR tbl_Prodejci.stredisko=(SELECT stredisko from tbl_Prodejci where username='$login'))";
		}
	}else{
		//TODO $where_query = "WHERE (prodejce = '$login' OR tbl_Prodejci.stredisko=(SELECT stredisko from tbl_Prodejci where username='$login')) AND (typ_zaznamu='rezervace' OR typ_zaznamu='nabidka' OR typ_zaznamu='predobjednavka') AND (stav='Odeslána nabídka' OR stav='Znovu odeslána nabídka' OR stav='Zákazník nezastižen' OR stav='Nerozhodný zákazník' OR stav='Odeslána nabídka se slevou' OR stav='Vytvořena rezervace' OR stav='Vytvořena předobjednávka')";
		$where_query = "WHERE (prodejce = '$login' OR tbl_Prodejci.stredisko=(SELECT stredisko from tbl_Prodejci where username='$login'))";
	}
}

else {
	if (isset($_GET['filtrovat'])) {
		$x=0;
		$where = "WHERE (";

		//! Filtr typy
		foreach($_GET['filtrTypy'] as $filtrTypy){
			$where .= "typ_zaznamu =  '$filtrTypy' OR ";
			$x++;
		}

		//! Filtr Prodejci
		if (isset($_GET['filtrProdejci'])) {
			if (isset($_GET['filtrTypy'])) {
				$where = substr($where,0,strlen($where)-4);
				$where .= ") AND (";
			}
			foreach($_GET['filtrProdejci'] as $filtrProdejci){
				$where .= "prodejce =  '$filtrProdejci' OR ";
				$x++;
			}
		}

		//! Filtr Stavy
		if (isset($_GET['filtrStavy'])) {
			if (isset($_GET['filtrTypy']) || isset($_GET['filtrProdejci'])) {
				$where = substr($where,0,strlen($where)-4);
				$where .= ") AND (";
			}
			foreach($_GET['filtrStavy'] as $filtrStavy){
				$where .= "stav =  '$filtrStavy' OR ";
				$x++;
			}
		}
		
		//! Filtr Strediska
		if (isset($_GET['filtrStrediska'])) {
			if (isset($_GET['filtrTypy']) || isset($_GET['filtrStavy']) || isset($_GET['filtrProdejci'])) {
				$where = substr($where,0,strlen($where)-4);
				$where .= ") AND (";
			}
			foreach($_GET['filtrStrediska'] as $filtrStrediska){
				$where .= "stredisko =  '$filtrStrediska' OR ";
				$x++;
			}
		}

		//! Filtr Datum
		if (isset($_GET['filtrDatum1']) && isset($_GET['filtrDatum2']) && strlen($_GET['filtrDatum1'])>0 && strlen($_GET['filtrDatum2'])>0) {
			if (isset($_GET['filtrTypy']) || isset($_GET['filtrStavy']) || isset($_GET['filtrProdejci']) || isset($_GET['filtrStrediska'])) {
				$where = substr($where,0,strlen($where)-4);
				$where .= ") AND (";
			}
				$where .= "tbl_Nabidky.datum BETWEEN '".$_GET['filtrDatum1']."' AND '".$_GET['filtrDatum2']."' OR ";
				$x++;
		}

		//! Filtr Datum Dalšího kroku
		if (isset($_GET['filtrDatumD1']) && isset($_GET['filtrDatumD2']) && strlen($_GET['filtrDatumD1'])>0 && strlen($_GET['filtrDatumD2'])>0) {
			if (isset($_GET['filtrTypy']) || isset($_GET['filtrStavy']) || isset($_GET['filtrProdejci']) || isset($_GET['filtrStrediska']) || isset($_GET['filtrDatum1']) || isset($_GET['filtrDatum2'])) {
				$where = substr($where,0,strlen($where)-4);
				$where .= ") AND (";
			}
				$where .= "tbl_Nabidky.dalsi_kontakt BETWEEN '".$_GET['filtrDatumD1']."' AND '".$_GET['filtrDatumD2']."' OR ";
				$x++;
		}

		//! Ošetření dotazu
		if($x>0){
			$where_query .= substr($where,0,strlen($where)-3);
			$where_query .= ")";
		}
		else{
			$where_query = "";
		}
	}else{
		$where_query = "";
	}
}

$orderby = "ORDER BY datum DESC";
	if($_GET['sorting'] != "undefined" && $_GET['sorting'] != "" && $_GET['sorting'] != NULL){
		$orderby = "ORDER BY ".$_GET['sorting']."";
	}

$prehled_query = $db->query("
SELECT TOP (1000)
			tbl_Nabidky.id AS id,
			tbl_Nabidky.jmeno AS ZakaznikJmeno,
			tbl_Nabidky.prijmeni AS ZakaznikPrijmeni,
			tbl_Nabidky.tel AS ZakaznikTel,
			tbl_Nabidky.email AS ZakaznikEmail,
			tbl_Nabidky.cislo AS CisloNabidky,
			tbl_Nabidky.stav AS StavNabidky,
			convert(varchar, tbl_Nabidky.datum, 104) AS DatumNabidky,
			convert(varchar, tbl_Nabidky.kontaktovan, 104) AS DatumKontaktu,
			convert(varchar, tbl_Nabidky.dalsi_kontakt, 104) AS DatumDalsihoKontaktu,
			tbl_Nabidky.prodejce AS Prodejce,
			tbl_Nabidky.stav AS Stav,
			tbl_Nabidky.typ_zaznamu AS Typ,
			tbl_Prodejci.jmeno AS ProdejceJmeno,
			tbl_Prodejci.stredisko AS Stredisko,
			tbl_Prodejci.vedouci AS Vedouci
		FROM 
			tbl_Nabidky
		LEFT JOIN
			tbl_Prodejci
		ON
			tbl_Nabidky.prodejce = tbl_Prodejci.username
		$where_query
		AND (((stav <> 'Prodáno' AND stav<>'Uzavřeno' AND stav<>'Zrušeno' AND stav<>'Odmítnuto' AND stav<>''))
		OR tbl_Nabidky.datum >= GETDATE()-180)
		$orderby
	");

$nabidkyItems_query = $dbS->query("
SELECT tbl_Nabidky_Zbozi.*, tbl_Zbozi.Cislo2 as PartNum, tbl_Zbozi.Popis, tbl_Zbozi.Cena,tbl_Zbozi.Obrazek,url FROM tbl_Nabidky_Zbozi LEFT JOIN tbl_Zbozi ON tbl_Nabidky_Zbozi.cislo = tbl_Zbozi.Cislo LEFT JOIN tbl_Samsung_HeurekaFeed ON tbl_Zbozi.Cislo2 = tbl_Samsung_HeurekaFeed.ItemID AND tbl_Zbozi.EAN = tbl_Samsung_HeurekaFeed.EAN order by id_nabidky desc
	");
	$nabidkyItems = $nabidkyItems_query->fetchAll();

	function itemTop($StavNabidky,$ZakaznikJmeno, $ZakaznikPrijmeni, $id, $DatumNabidky, $ProdejceJmeno, $ZakaznikTel, $ZakaznikEmail, $DatumKontaktu){
		?>
<div class="overview">
	<div class="overviewItem" style="font-size:0.8rem;flex-direction: row;" <?php 
							if($StavNabidky=='Zboží skladem'){echo 'alert-info';}
							if($StavNabidky=='Další kontakt'){echo 'alert-warning';}?>" style="flex-direction: row;">
		<h4 style="margin-right: 15px;font-size: 1.2rem;">
			<?php echo $ZakaznikJmeno.' '.$ZakaznikPrijmeni.' ('.$id.')';?></h4>
		<h5 style="margin-right: 15px;font-size: 1rem;"><?php echo date('d.m.Y', strtotime($DatumNabidky));?></h5>
		<p style="margin-right: 15px;"><strong>Vytvořil(a):</strong><br /> <?php echo $ProdejceJmeno;?>
			<hr>
		</p>
		<!-- <p style="margin-right: 15px;"><?php echo $ZakaznikTel;?></p> -->
		<p style="margin-right: 15px;"><?php echo $ZakaznikEmail; echo "<br/>$ZakaznikTel";?></p>
		<p style="margin-right: 15px;"><strong>Poslední
				kontakt:</strong><br><?php echo date('d.m.Y', strtotime($DatumKontaktu));?></p>
		<p style="margin-right: 15px;"><strong>Stav:</strong><br><?php echo $StavNabidky;?></p>
		<p style="margin-right: 15px;"><strong>
				<!-- Zboží: --></strong><a href="javascript:showItems('<?php echo $id;?>')">Více informací</a>
			<p />
			<ul class="overviewItemProducts" style="margin-right: 15px;"><?php
	}
	function itemBottom($type,$id){
		?>
			</ul>
			<div class="overviewItem__btns" style="flex-grow: 0;flex-direction: row;">
				<?php if($type!='1'){?><p><span class="btn btn-warning btn-block btn-sm" style="width: 35px;"
						onclick="sendInfo('<?php echo $id;?>','contactAgain')"><img src="images/resend.svg"
							height="20px"></span></p><?php }?>
				<p><span class="btn btn-primary btn-block btn-sm" style="width: 35px;"
						onclick="sendInfo('<?php echo $id;?>','inStock')"><img src="images/skladem.svg"
							height="20px"></span></p>
				<p><span class="btn btn-danger btn-block btn-sm" style="width: 35px;"
						onclick="finishOffer('<?php echo $id;?>','Closed')"><img src="images/uzavreno.svg"
							height="20px"></span></p>
				<p><span class="btn btn-success btn-block btn-sm" style="width: 35px;"
						onclick="finishOffer('<?php echo $id;?>','Sold')"><img src="images/prodano.svg"
							height="20px"></span></p>
			</div>
	</div>
</div><?php
	}
?>

<?php
$typy_query = $dbS->query("SELECT distinct [typ_zaznamu], CASE [typ_zaznamu] WHEN 'nabidka' then 'Nabídka' WHEN 'predobjednavka' THEN 'Předobjednávka' WHEN 'rezervace' THEN 'Rezervace' WHEN 'zakaznik' THEN 'Zákazník' END as Nazev FROM [SamsungPortal].[dbo].[tbl_Nabidky] order by [typ_zaznamu]");
$typy = $typy_query->fetchAll();

$prodejci_query = $dbS->query("SELECT tbl_Prodejci.username, tbl_Prodejci.Jmeno, tbl_Prodejci.telefon, tbl_Prodejci.stredisko, tbl_Prodejci.Vedouci FROM tbl_Nabidky INNER join tbl_Prodejci ON tbl_Nabidky.prodejce = tbl_Prodejci.username WHERE (tbl_Prodejci.stredisko IN (select distinct stredisko from tbl_prodejci WHERE (username='$login' AND vedouci = 0) OR vedouci = (SELECT vedouci from tbl_Prodejci where username='$login' AND vedouci = 1))) AND (( stav <> 'Prodáno' AND stav <> 'Odmítnuto' AND stav <> '' ) OR ( ( stav = 'Prodáno' OR stav = 'Odmítnuto' ) AND tbl_Nabidky.datum > getdate()-30 )) group by tbl_Prodejci.username, tbl_Prodejci.Jmeno, tbl_Prodejci.telefon, tbl_Prodejci.stredisko, tbl_Prodejci.Vedouci order by tbl_Prodejci.username");
$prodejci = $prodejci_query->fetchAll();

$strediska_query = $dbS->query("SELECT tbl_Prodejny.kod, tbl_Prodejny.name, tbl_Prodejci.stredisko, REPLACE(tbl_Prodejci.stredisko,'S001.','') as kod2 FROM tbl_Nabidky INNER join tbl_Prodejci ON tbl_Nabidky.prodejce = tbl_Prodejci.username INNER JOIN tbl_Prodejny ON 'S001.' + cast( tbl_Prodejny.kod as varchar(3) ) = tbl_prodejci.stredisko OR 'S001.0' + cast( tbl_Prodejny.kod as varchar(3) ) = tbl_prodejci.stredisko WHERE (tbl_Prodejci.stredisko IN (select distinct stredisko from tbl_prodejci WHERE (username='$login' AND vedouci = 0) OR vedouci = (SELECT vedouci from tbl_Prodejci where username='$login' AND vedouci = 1))) AND (( stav <> 'Prodáno' AND stav <> 'Odmítnuto' AND stav <> '' ) OR ( ( stav = 'Prodáno' OR stav = 'Odmítnuto' ) AND tbl_Nabidky.datum > getdate()-30 )) group by tbl_Prodejny.kod, tbl_Prodejny.name, tbl_Prodejci.stredisko order by tbl_Prodejci.stredisko");
$strediska = $strediska_query->fetchAll();

$stavy_query = $dbS->query("SELECT [ID],[Nazev] FROM [SamsungPortal].[dbo].[tbl_Stav] order by ID");
$stavy = $stavy_query->fetchAll();
?>

</div> <?php /* zavření předchozího containeru, abych mohl udělat container přes celou šířku */ ?>

<div class="container-fluid">
	<div class="row">
		<div class="col-12">
			<form method="get" class="form-inline overviewFilter" id="formFilter" style="display:none;">
				<div class="row">
					<div class="col-2">
						Střediska
						<div class="form-group" style="height: 165px;overflow: overlay;">
							<?php foreach ($strediska as $stredisko) : ?>
							<label class="mb-0 p-md-2 list-group-item list-group-item-action">
								<input
									<?php if(in_array($stredisko['stredisko'],$_GET['filtrStrediska'])){echo 'checked="checked"';}?>
									type="checkbox" name="filtrStrediska[]" style="margin-right: 5px;"
									value="<?=$stredisko['stredisko']?>">
								<?php echo("".$stredisko['name']." (".$stredisko['kod2'].")")?>
							</label>
							<?php endforeach ?>
						</div>
					</div>
					<div class="col-2">
						Prodejce
						<div class="form-group" style="height: 165px;overflow: overlay;">
							<!-- <select onchange="this.form.submit();"> -->
							<?php foreach ($prodejci as $prodejce) : ?>
							<!-- <option name="filtrProdejci[]" value="<?=$prodejce['username']?>" ><?=$prodejce['Jmeno']?></option> -->
							<label class="mb-0 p-md-2 list-group-item list-group-item-action">
								<input
									<?php if(in_array($prodejce['username'],$_GET['filtrProdejci'])){echo 'checked="checked"';}?>
									type="checkbox" name="filtrProdejci[]" style="margin-right: 5px;"
									value="<?=$prodejce['username']?>">
								<?=$prodejce['Jmeno']?>
							</label>
							<?php endforeach ?>
							<!-- </select> -->
						</div>
					</div>
					<div class="col-2">
						Typy
						<div class="form-group" style="height: 165px;overflow: overlay;">
							<?php foreach ($typy as $typ) : ?>
							<label class="mb-0 p-md-2 list-group-item list-group-item-action">
								<input
									<?php if(in_array($typ['typ_zaznamu'],$_GET['filtrTypy'])){echo 'checked="checked"';}?>
									type="checkbox" name="filtrTypy[]" style="margin-right: 5px;"
									value="<?=$typ['typ_zaznamu']?>">
								<?=$typ['Nazev']?>
							</label>
							<?php endforeach ?>
						</div>
					</div>
					<div class="col-2">
						Stavy
						<div class="form-group" style="height: 165px;overflow: overlay;">
							<?php foreach ($stavy as $stav) : ?>
							<label class="mb-0 p-md-2 list-group-item list-group-item-action">
								<input
									<?php if(in_array($stav['Nazev'],$_GET['filtrStavy'])){echo 'checked="checked"';}?>
									type="checkbox" name="filtrStavy[]" style="margin-right: 5px;"
									value="<?=$stav['Nazev']?>"> <?=$stav['Nazev']?>
							</label>
							<?php endforeach ?>
						</div>
					</div>
					<div class="">
						Datum vytvoření
						<div class="form-group">
							<label class="mb-0 p-md-2 list-group-item list-group-item-action"
								style="flex-direction: column; width: auto;">
								<label for="Datum1">Od</label>
								<input type="date" name="filtrDatum1"
									value="<?php if(isset($_GET['filtrDatum1'])){echo $_GET['filtrDatum1'];}?>"
									min="2015-01-01" max="<?php echo date("Y-m-d"); ?>">
								<label for="Datum2">Do</label>
								<input type="date" name="filtrDatum2"
									value="<?php if(isset($_GET['filtrDatum2'])){echo $_GET['filtrDatum2'];}?>"
									min="2015-01-01" max="<?php echo date("Y-m-d", strtotime("+1 days")); ?>">
							</label>
						</div>
					</div>
					<div class="col-2">
						Datum dalšího kroku
						<div class="form-group">
							<label class="mb-0 p-md-2 list-group-item list-group-item-action"
								style="flex-direction: column; width: auto;">
								<label for="DatumD1">Od</label>
								<input type="date" name="filtrDatumD1"
									value="<?php if(isset($_GET['filtrDatumD1'])){echo $_GET['filtrDatumD1'];}?>"
									min="2015-01-01">
								<label for="Datum2">Do</label>
								<input type="date" name="filtrDatumD2"
									value="<?php if(isset($_GET['filtrDatumD2'])){echo $_GET['filtrDatumD2'];}?>"
									min="2015-01-01">
							</label>
						</div>
					</div>
				</div>
				<input type="text" name="sorting" id="sortInput" style="display:none;">
				<input type="hidden" name="filtrovat">
				<div class="text-center" style="width: -webkit-fill-available;">
					<button class="specialButton col-lg-3" type="submit" form="formFilter" value="Submit">Aplikovat filtry</button>
					<a name="reset" href="https://portal.obchod-samsung.cz/prehled" class="specialButton col-lg-3">Resetovat filtry</a>
				</div>
			</form>
			<div class="button_cont" align="center">
				<div id="buttonFilter" class="specialButton" onclick="hideFilters()">⇃ Zobrazit filtry ⇂</div>
			</div>
		</div>
	</div>
</div>

<script>
	var hidden = true;

	function hideFilters() {
		if (hidden) {
			$("#formFilter").slideDown();
			$("#buttonFilter").html("↿ Skrýt filtry ↾");
			hidden = false;
		} else {
			$("#formFilter").slideUp();
			$("#buttonFilter").html("⇃ Zobrazit filtry ⇂");
			hidden = true;
		}
	}

	function sort(myColumn) {
		var post = <?php if($_GET['sorting']==""){echo "\"undefined\"";}else{echo "\"".$_GET['sorting']."\"";} ?>;
		
		if (post == "undefined" || post.includes("ASC")) {
			$("#sortInput").val(myColumn + " DESC");
		}
		else if (post.includes(myColumn) && post.includes("DESC")) {
			$("#sortInput").val(myColumn + " ASC");
		}
		$("#formFilter").submit();
	}
</script>



<?php

	$login = $_COOKIE['login'];
	$username = $_COOKIE['username'];

	// $prehled_query = $db->query("SELECT DISTINCT pred.* FROM tbl_Logins AS l LEFT JOIN tbl_Prodejci AS p ON l.kod = p.stredisko INNER JOIN tbl_Predobjednavky AS pred ON p.username = pred.login WHERE l.login = '$login' AND Cislo IN (SELECT cislo FROM tbl_Predobjednavky_zbozi WHERE GETDATE() BETWEEN od AND do ) ORDER BY datum ASC");
	
	

	echo '<br><br>
<div class="container-fluid">
	<div class="row">
	<div class="col-12">
	<div class="col-12"><h3>Přehled nabídek</h3></div>
		<table class="table table-striped">
		<tr class="'.$tr.'">
				<td><a href="javascript:;" onclick="sort(\''.'id'.'\')">Číslo nabídky</a></td>
				<td><a href="javascript:;" onclick="sort(\''.'typ_zaznamu'.'\')">Typ</a></td>
				<td><a href="javascript:;" onclick="sort(\''.'prijmeni'.'\')">Jméno a Příjmení</a></td>
				<td><a href="javascript:;" onclick="sort(\''.'tel'.'\')">Telefon</a></td>
				<td><a href="javascript:;" onclick="sort(\''.'datum'.'\')">Vytvoření</a></td>
				<td><a href="javascript:;" onclick="sort(\''.'prodejce'.'\')">Vytvořil</a></td>
				<td><a href="javascript:;" onclick="sort(\''.'stredisko'.'\')">Středisko</a></td>
				<td><a href="javascript:;" onclick="sort(\''.'stav'.'\')">Stav</a></td>
				<td><a href="javascript:;" onclick="sort(\''.'dalsi_kontakt'.'\')">Datum dalšího kroku</a></td>
				<td><a href="javascript:;" onclick="sort(\''.'dalsi_kontakt'.'\')">Další krok</a></td>
				<td>Číslo zboží</td>
				<td class="text-right">Akce</td>
			</tr>';
	while($prehled = $prehled_query->fetch()){
		$datum = date('d.m.Y H:i',strtotime($prehled['datum']));
		if($prehled['kontakt']!=NULL ){$kontakt = date('d.m.Y H:i',strtotime($prehled['kontakt']));}else{$kontakt = 'Zákazník nekontaktován';}
		if(strlen($prehled['mail'])>0 || strlen($prehled['tel'])>0){$form = '<form method="post" action="inc/save-s6.php"><input type="hidden" name="idRezervace" value="'.$prehled['id'].'"><input type="submit" value="Skladem"></form>';$mail = '<a href="mailto:'.$prehled['mail'].'">'.$prehled['mail'].'</a>';}else{$form = $mail = '';}
		if($_GET['edit']==$prehled['id']){
			$active = ' active';
		}else{
			$active = '';
		}
		$Rtr = '';
		if($prehled['StavNabidky']=='Prodáno'){$tr='table-success';}
		else if($prehled['StavNabidky']=='Odmítnuto'){$tr='table-danger';}
		else if($prehled['Typ']=='zakaznik'){$tr='table-info';}
		else{$tr='';}
		echo '
			<tr class="'.$tr.'">
				<td>';
				if($prehled['Typ']=='predobjednavka'){
					echo '<a href="/nabidkyinfo?edit='.$prehled['id'].'" data-toggle="tooltip" data-placement="top" title="Zobrazit detail předobjednávky">'.$prehled['id'].'</a>';
				}
				else if($prehled['Typ']=='nabidka'){
					echo '<a href="/nabidkyinfo?edit='.$prehled['id'].'" data-toggle="tooltip" data-placement="top" title="Zobrazit detail nabídky">'.$prehled['id'].'</a>';
				}
				else if($prehled['Typ']=='rezervace'){
					echo '<a href="/nabidkyinfo?edit='.$prehled['id'].'" data-toggle="tooltip" data-placement="top" title="Zobrazit detail rezervace">'.$prehled['id'].'</a>';
				}
				else{
					echo '<a href="/nabidkyinfo?edit='.$prehled['id'].'" data-toggle="tooltip" data-placement="top" title="Zobrazit detail">'.$prehled['id'].'</a>';
				}
				
				echo '</td>
				<td>'.$prehled['Typ'].'</td>
				<td>'.$prehled['ZakaznikJmeno'].' '.$prehled['ZakaznikPrijmeni'].'</td>
				<td>'.$prehled['ZakaznikTel'].'</td>
				<td>'.substr($prehled['DatumNabidky'],0,10).'</td>
				<td>'.$prehled['ProdejceJmeno'].'</td>
				<td>'.substr($prehled['Stredisko'],-3).'</td>
				<td>'.$prehled['StavNabidky'].'</td>
				<td>'.substr($prehled['DatumDalsihoKontaktu'],0,10).'</td>
				<td>'; if($prehled['StavNabidky'] != "Odmítnuto" && $prehled['StavNabidky'] != "Prodáno" && $prehled['StavNabidky'] != "Vytvořen zákazník"){echo "Kontaktovat zákazníka";} echo '</td>
				<td>'; 
				foreach ($nabidkyItems as $nabidkaItem) {
					if($prehled['id'] == $nabidkaItem['id_nabidky']){echo "<a href='$nabidkaItem[url]' data-toggle='tooltip' data-placement='top' title='$nabidkaItem[Popis]'>$nabidkaItem[cislo]</a></br>";} 
				}
				echo '</td>
				<td class="text-right" style="display:flex;"><a href="/nabidkyinfo?edit='.$prehled['id'].'" class="btn btn-warning btn-block btn-sm">Akce</a></td>
			</tr>';
	}
	echo '</table>
	</div></div></div>
	';
?>

</div> <?php /* zavření předchozího containeru, abych mohl udělat container přes celou šířku */ ?>

<script>

	function showItems(Id) {
		$.ajax({ //create an ajax request to load_page.php
			type: "POST",
			url: "/controllers/showItems.php",
			dataType: "html", //expect html to be returned                
			data: {
				id: Id
			},
			success: function (response) {
				$('#items .modal-content').html(response); //select the id and put the response in the html
				$('#items').modal('show');
			},
			error: function (jqXHR, textStatus, errorThrown) {
				console.log('error(s):' + textStatus, errorThrown);
			}
		});
	}

	function sendInfo(Id, Action) {
		$.ajax({ //create an ajax request to load_page.php
			type: "POST",
			url: "/controllers/sendInfo.php",
			dataType: "json", //expect html to be returned                
			data: {
				id: Id,
				action: Action
			},
			success: function (data) {
				if (data.status == 'success') {
					$('#items .modal-content').html(data.msg); //select the id and put the response in the html
					$('#items').modal('show');
					$('#items').on('hidden.bs.modal', function () {
						location.reload();
					})
				} else if (data.status == 'error') {
					alert(data.msg);
				}
			}
		});
	}

	function finishOffer(Id, Action) {
		$.ajax({ //create an ajax request to load_page.php
			type: "POST",
			url: "/controllers/finishOffer.php",
			dataType: "json", //expect html to be returned                
			data: {
				id: Id,
				action: Action
			},
			success: function (data) {
				if (data.status == 'success') {
					$('#items .modal-content').html(data.msg); //select the id and put the response in the html
					$('#items').modal('show');
					$('#items').on('hidden.bs.modal', function () {
						location.reload();
					})
				} else if (data.status == 'error') {
					alert(data.msg);
				}
			}
		});
	}

	function changeState(Id, Action, Note) {
		$.ajax({ //create an ajax request to load_page.php
			type: "POST",
			url: "/controllers/changeState.php",
			dataType: "json", //expect html to be returned                
			data: {
				id: Id,
				action: Action,
				note: Note
			},
			success: function (data) {
				if (data.status == 'success') {
					$('#items .modal-content').html(data.msg); //select the id and put the response in the html
					$('#items').modal('show');
					$('#items').on('hidden.bs.modal', function () {
						location.reload();
					})
				} else if (data.status == 'error') {
					alert(data.msg);
				}
			}
		});

		<?php 
		
			$sendStavy = $dbS->prepare("SELECT ID, Nazev FROM [SamsungPortal].[dbo].[tbl_Stav] where OdeslatEmail = 1");
            $sendStavy->execute();
			$sendStavy = $sendStavy->fetchAll(PDO::FETCH_ASSOC);
			$ifSendStav='';
            foreach($sendStavy as $sendStav){
				$ifSendStav .= 'Action == '.$sendStav['ID'].' || ';
			}
			$ifSendStav = substr($ifSendStav,0,strlen($ifSendStav)-4);
		?>

		if (<?php echo $ifSendStav;?>) {
			$.ajax({ //create an ajax request to load_page.php
				type: "POST",
				url: "/controllers/sendInfo.php",
				dataType: "json", //expect html to be returned                
				data: {
					id: Id,
					action: Action
				},
				success: function (data) {
					if (data.status == 'success') {
						$('#items .modal-content').html(data
							.msg); //select the id and put the response in the html
						$('#items').modal('show');
						$('#items').on('hidden.bs.modal', function () {
							location.reload();
						})
					} else if (data.status == 'error') {
						alert(data.msg);
					}
				}
			});
		}
	}
</script>