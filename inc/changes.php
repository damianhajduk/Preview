<!-- PHP Kód -->
<?php
// Integrace stránky, která ověřuje přihlášení.
require_once 'protection.php';

// Nastavení formátu data a dalšího na Český.
SetLocale(LC_ALL, "cs_CZ.UTF-8");

// Načtení dat z databáze.
$changelists = $db->query("SELECT * FROM (SELECT TOP 20 * FROM tbl_ChangeList ORDER BY ID Desc) AS Changelist INNER JOIN tbl_ChangeList_Popisky ON ID_ChangeListu = ChangeList.ID ORDER BY ChangeList.ID Desc, Akce Asc")->fetchAll();

//Vypsání každého listu změn a jeho podzměn do proměné $changelistData, která je dále jen vypsána v kódu, kvůli přehlednosti.
foreach ($changelists as $changelist) {
    // Porovnání pokud už je vypsaný název changelistu, pokud není, vypíše se hlavička a první změna a její popisek.
    if ($changelist[Nazev] != $title) {
        $changelistData .= "<div class='header'>
                            <h1 class='title'>$changelist[Nazev]</h1>
                            <h5 class='date'>".strftime("%A %d. %B %Y", strtotime($changelist[Datum]))."</h5>
                            </div>
                            <p class='change $changelist[Akce]'><img src='/images/icons/$changelist[Akce].svg' class='action'> $changelist[Zmena]</p>
                            <p class='minorChange $changelist[Akce]'>$changelist[Popisek]</p>";
    }
    // Porovnání pokud už je vypsaný název changelistu, pokud je, vypíše se změna a její popisek. 
    else{
        $changelistData .= "<p class='change $changelist[Akce]'><img src='/images/icons/$changelist[Akce].svg' class='action'> $changelist[Zmena]</p>
                            <p class='minorChange $changelist[Akce]'>$changelist[Popisek]</p>";
    }
    // Vložení jména posledního changelistu do proměnné, kvůli porovnání na začátku.
    $title = $changelist[Nazev];

    // Pokud se mezi changelogem nachází otázka, nastaví se proměnná question na true, díky čemuž se objeví upozornění na vrchu stránky.
    if ($changelist[Akce] == "Question") {
        $question = true;
    }
}
?>

<!-- HTML Kód -->
<!-- Vypsání alertu, pokud se v changelistu nachází otázka -->
<?php if($question)echo '<div class="alert text-center">Odpovědi na otázky psát prosím do emailu hajdukd@setos.cz</div>'; ?>

<!-- Vypsání changelistu -->
<div class="row">
    <div class="col-12">
        <?=$changelistData?>
    </div>
</div>