<br><br>

	<?php
    if(isset($_GET['ok'])){echo '<div class="row"><div class="col-12"><div class="alert alert-success">Data byla uložena.</div></div></div>';}
    if(isset($_GET['edit-ok'])){echo '<div class="row"><div class="col-12"><div class="alert alert-success">Editace byla uložena.</div></div></div>';}
    if(isset($_GET['zrusit-ok'])){echo '<div class="row"><div class="col-12"><div class="alert alert-success">Předobjednávka byla označená jako neuskutečněná.</div></div></div>';}
    if(isset($_GET['prodano-ok'])){echo '<div class="row"><div class="col-12"><div class="alert alert-success">Předobjednávka byla označená jako prodaná.</div></div></div>';}
	if(isset($_GET['chyba-email'])){echo '<div class="row"><div class="col-12"><div class="alert alert-danger">Nebylo možné odeslat e-mail.</div></div></div>';}
	if(isset($_GET['neulozeno'])){echo '<div class="row"><div class="col-12"><div class="alert alert-danger">Data nebylo možné uložit.</div></div></div>';}
	if(isset($_GET['chyba-tel'])){echo '<div class="row"><div class="col-12"><div class="alert alert-danger">Telefonní číslo je ve špatném formátu.</div></div></div>';}
    if(isset($_GET['chyba-kontakt'])){echo '<div class="row"><div class="col-12"><div class="alert alert-danger">Zákazníka nebylo možné kontaktovat.</div></div></div>';}
    if(isset($_GET['chyba-produkt'])){echo '<div class="row"><div class="col-12"><div class="alert alert-danger">Zadaný produkt neexistuje nebo ho ještě není možné předobjednat.</div></div></div>';}
    
    //$edit = array();
    if(isset($_GET['edit'])){
        $edit = $db->prepare("SELECT nab.*, prod.username, prod.jmeno as jmenoProdejce, prod.telefon, prod.stredisko, prod.vedouci FROM tbl_Nabidky AS nab LEFT JOIN tbl_Prodejci AS prod ON prod.username = nab.prodejce WHERE nab.id = ?");
        $edit->execute(array(intval($_GET['edit'])));
        $edit = $edit->fetch(PDO::FETCH_ASSOC);
        if(empty($edit)){
            echo '<div class="row"><div class="col-12"><div class="alert alert-danger"><div class="row"><div class="col-11">Na editaci záznamu nemáte oprávnění nebo tento záznam neexistuje.</div><div class="col-1"><a href="/predobjednavky">X</a></div></div></div></div></div>';
        }else{
            //echo '<div class="row"><div class="col-12"><div class="alert alert-warning"><div class="row"><div class="col-11">Editace</div><div class="col-1"><a href="/predobjednavky">X</a></div></div></div></div></div>';
        }
    }
?>
	<div class="row justify-content-center">
        <div class="col-md-6">
            <form method="post" action="/controllers/save-predobjednavky.php" method="post">
                <input type="hidden" name="edit" value="<?=$_GET['edit']?>">
                <?php   
                // if ($edit['cislo'!=""]){
                    echo '<div class="form-group">
                            <label for="cislo">Číslo nabídky: <strong>'.$edit['cislo'].'</strong></label>
                        </div>';
                // }
                // if ($edit['jmeno'!=""]){
                    echo '<div class="form-group">
                            <label for="jmeno">Jméno a příjmení: <strong>'.$edit['jmeno'].'</strong></label>
                        </div>';
                // }
                // if ($edit['tel'!=""]){
                    echo '<div class="form-group">
                            <label for="tel">Telefon: <strong>'.$edit['tel'].'</strong></label>
                        </div>';
                // }
                // if ($edit['email'!=""]){
                    echo '<div class="form-group">
                            <label for="mail">E-mail: <strong>'.$edit['email'].'</strong></label>
                        </div>';
                // }
                // if ($edit['PSC'!=""]){
                    echo '<div class="form-group">
                            <label for="psc">PSČ zákazníka: <strong>'.$edit['PSC'].'</strong></label>
                        </div>';
                // }
                // if ($edit['pohlavi'!=""]){
                    echo '<div class="form-group">
                            <label for="pohlavi">Pohlaví zákazníka: <strong>'.$edit['pohlavi'].'</strong></label>
                        </div>';
                // }
                // if ($edit['prodejce'!=""]){
                    echo '<div class="form-group">
                            <label for="prodejce">Prodejce: <strong>'.$edit['prodejce'].'</strong></label>
                        </div>';
                // }
                // if ($edit['datum'!=""]){
                    echo '<div class="form-group">
                            <label for="datum">Datum a čas vytvoření: <strong>'.$edit['datum'].'</strong></label>
                        </div>';
                // }
                // if ($edit['stav'!=""]){
                    echo '<div class="form-group">
                            <label for="stav">Stav objednávky: <strong>'.$edit['stav'].'</strong></label>
                        </div>';
                // }
                echo '<div class="form-group">';
                        $produkty = $db->prepare("SELECT * FROM tbl_Nabidky_Zbozi WHERE id_nabidky = ?");
                        $produkty->execute(array(intval($_GET['edit'])));
                        $produkty = $produkty->fetchAll(PDO::FETCH_ASSOC);
                        foreach($produkty as $produkt){
                            echo '<label for="model">Číslo zboží: <strong>'.$produkt['cislo'].'</strong>   (<strong>'.$produkt['ks'].'</strong>ks)</label><br>';
                        }
                echo '</div>';
                
                // if ($edit['poznamka'!=""]){
                   echo '<div class="form-group">
                        <label for="poznamka">Poznámka: <strong>'.$edit['poznamka'].'</strong>"</label>
                        </div>'; 
                // }
                ?>
                <div class="form-group">
                <?php
                        $logy = $db->prepare("SELECT * FROM tbl_Nabidky_log WHERE idNabidky = ? ORDER BY datum DESC");
                        $logy->execute(array(intval($_GET['edit'])));
                        $logy = $logy->fetchAll(PDO::FETCH_ASSOC);
                        foreach($logy as $log){
                            echo '<label for="model"><strong>'.$log['username'].'</strong> změnil stav nabídky/rezervace na <strong>'.$log['akce'].'</strong> a přidal následující poznámku: <strong>'.$log['poznamka'].'</strong> </label><br>';
                        }
                ?>
                </div>
                
                <!-- <input type="submit" value="Uložit" class="btn btn-success"> -->
            </form>
        </div>
    </div>