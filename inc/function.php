<?php
	require_once 'protection.php';
?>
<?php

// Řešení pokud se v URL objeví mezera
function spaceInURL($brandName){
		$brandName = explode(' ',$brandName);
		if(count($brandName)==1){
			return $brandName[0];
		}else{
			for($i=0;$i<count($brandName);$i++){
				$brandNameURL .= $brandName[$i].'+';
			}
			$brandNameURL = substr($brandNameURL,0,strlen($brandNameURL)-1);
			return $brandNameURL;
		}
	}
	
function html_cut($s, $limit,$whole_word=1){
    static $empty_tags = array('area', 'base', 'basefont', 'br', 'col', 'frame', 'hr', 'img', 'input', 'isindex', 'link', 'meta', 'param');
    $length = 0;
    $tags = array(); //dosud neuzavrene znacky

    for($i=0; ($i < strlen($s) && $length < $limit) || ($whole_word==1 && ($length >= $limit && (isset($s{$i}) && !preg_match('/\s/',$s{$i})))); $i++) {
        switch ($s{$i}) {
        case '<':
        //nactení znacky
          $start = $i+1;
          while ($i < strlen($s) && $s{$i} != '>' && !ctype_space($s{$i})) {
            $i++;
          }#end while
          $tag = strtolower(substr($s, $start, $i - $start));
          //preskoení pripadnych atributu
            $in_quote = '';
            while ($i < strlen($s) && ($in_quote || $s{$i} != '>')) {
              if (($s{$i} == '"' || $s{$i} == "'") && !$in_quote) {
                $in_quote = $s{$i};
              }#end if
              elseif ($in_quote == $s{$i}) {
                $in_quote = '';
              }#end elseif
              $i++;
            }#end while
            if ($s{$start} == '/') {//uzaviraci znacka
              $tags = array_slice($tags, array_search(substr($tag, 1), $tags) + 1);
            }#end if
            elseif ($s{$i-1} != '/' && !in_array($tag, $empty_tags)) { //oteviraci znacka
              array_unshift($tags, $tag);
            }#end elseif
            break;
        case '&':
            $length++;
            while ($i < strlen($s) && $s{$i} != ';') {
                $i++;
            }#end while
            break;
        default:
            $length++;
        }#end switch
    }#end for
    $s = substr($s, 0, $i);
  //odstraneni prazdnych znaku na konci retezce
    $s=preg_replace('/[\s]$/is','',$s);
  //pokud neni konec retezce uzavreni tagu nebo nejaky znak na konci vety, pridej tri tecky
    if(!preg_match('/>[\s]{0,}$/is',$s) && !preg_match('/[\.!\?]$/is',$s)){$s .= ' ...';}
  //uzavreni tagu
    if ($tags) {
        $s .= "</" . implode("></", $tags) . ">";
    }#end if
  //navratova hodnota funkce
    return $s;
  }#end function

  // Funkce na oddělení tisíců v čísle
  function oddel_tisice($cislo){
    $oddeleno = number_format($cislo, 0, ',', ':');
    return str_replace(':', '&nbsp;', $oddeleno);
  }

$prevodni_tabulka = Array(
  'ä'=>'a',
  'Ä'=>'a',
  'á'=>'a',
  'Á'=>'a',
  'à'=>'a',
  'À'=>'a',
  'ã'=>'a',
  'Ã'=>'a',
  'â'=>'a',
  'Â'=>'a',
  'č'=>'c',
  'Č'=>'c',
  'ć'=>'c',
  'Ć'=>'c',
  'ď'=>'d',
  'Ď'=>'d',
  'ě'=>'e',
  'Ě'=>'e',
  'é'=>'e',
  'É'=>'e',
  'ë'=>'e',
  'Ë'=>'e',
  'è'=>'e',
  'È'=>'e',
  'ê'=>'e',
  'Ê'=>'e',
  'í'=>'i',
  'Í'=>'i',
  'ï'=>'i',
  'Ï'=>'i',
  'ì'=>'i',
  'Ì'=>'i',
  'î'=>'i',
  'Î'=>'i',
  'ľ'=>'l',
  'Ľ'=>'l',
  'ĺ'=>'l',
  'Ĺ'=>'l',
  'ń'=>'n',
  'Ń'=>'n',
  'ň'=>'n',
  'Ň'=>'n',
  'ñ'=>'n',
  'Ñ'=>'n',
  'ó'=>'o',
  'Ó'=>'o',
  'ö'=>'o',
  'Ö'=>'o',
  'ô'=>'o',
  'Ô'=>'o',
  'ò'=>'o',
  'Ò'=>'o',
  'õ'=>'o',
  'Õ'=>'o',
  'ő'=>'o',
  'Ő'=>'o',
  'ř'=>'r',
  'Ř'=>'r',
  'ŕ'=>'r',
  'Ŕ'=>'r',
  'š'=>'s',
  'Š'=>'s',
  'ś'=>'s',
  'Ś'=>'s',
  'ť'=>'t',
  'Ť'=>'t',
  'ú'=>'u',
  'Ú'=>'u',
  'ů'=>'u',
  'Ů'=>'u',
  'ü'=>'u',
  'Ü'=>'u',
  'ù'=>'u',
  'Ù'=>'u',
  'ũ'=>'u',
  'Ũ'=>'u',
  'û'=>'u',
  'Û'=>'u',
  'ý'=>'y',
  'Ý'=>'y',
  'ž'=>'z',
  'Ž'=>'z',
  'ź'=>'z',
  'Ź'=>'z',
  ' '=>'-',
  '_'=>'-',
  '/'=>'-',
  '´'=>'',
  '"'=>'',
  '+'=>'',
  '%'=>'',
  ':'=>'',
  '@'=>'',
  ';'=>'',
  ','=>'-',
  '°'=>''
);

function code_md5hex($str){
	$md5 = md5($str);
	$md5 = substr($md5,0,6);
	$array = str_split($str);
	$hex = '';
	foreach($array as &$char){
		$tmp = ord($char);	
		$hex .= base_convert($tmp,10, 16);
	}
	$md5hex = $md5.$hex.'.jpg';
	return $md5hex;
}

function checkRemoteFile($url){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$url);
	// don't download content
	curl_setopt($ch, CURLOPT_NOBODY, 1);
	curl_setopt($ch, CURLOPT_FAILONERROR, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	if(curl_exec($ch)!==FALSE){
		//return true;
		return 1;
	}else{
		//return false;
		return 0;
	}
}
function velikost($soubor) {
  $size = @filesize("./".$soubor);
  if($size < 1024) {$size = ($size); $k = " B";}
  if($size >= 1024) {$size = ($size / 1024); $k = " kB";}
  if($size >= 1024) {$size = ($size / 1024); $k = " MB";}
  return round($size, 1).$k; /* 1 = zaokrouhlování na jedno desetinné místo */
}

// Vytváření hezkých URL v adresním řádku prohlížeče
function coolUrl($text){
	$coolUrl = strtolower(strtr($text, $GLOBALS['prevodni_tabulka']));
	while(strpos($coolUrl,'--')!==FALSE){
		$coolUrl = str_replace('--','-',$coolUrl);
	}
	return $coolUrl;
}
?>