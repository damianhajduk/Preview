<?php
$cena = round($prod['Cena'] * 1.21);
$cena = oddel_tisice($cena);
$page = $category;
if($prod['Mnozstvi']<5 && $prod['Mnozstvi']>0){
    $dostupnost = 'Skladem';
    $label = 'success';	
}else if($prod['Mnozstvi']>=5){
    $dostupnost = 'Skladem více než 5 ks';
    $label = 'success';
}else{
    $dostupnost = 'Není skladem';
    $label = 'default';	
}
//print_r($porovnani);
echo '
    <div class="col-sm-6 col-lg-4 col-md-4 col-xs-6">
        <div class="prodItem">
            <div class="prodItem__name">
                '.$prod["Popis"].'
            </div>
            <div class="prodItem__imgInfo">
                <div class="prodItem__img">
                    <img src="http://img.setos.cz?file='.code_md5hex($prod["Cislo"].'_140_0.jpg').'" alt="" style="" class="img-fluid">
                </div>
                <div class="prodItem__info">
                    <div class="prodItem__no">'.$prod['Cislo'].'</div>
                    <div class="prodItem__price">'.$cena.'&nbsp;Kč</div>
                    <div class="prodItem__btn"><a href="javascript:addToCart(\''.$prod["Cislo"].'\')" class="btn btn-buy">Přidat do košíku</a></div>
                </div>
            </div>
        </div>
    </div>
';
?>