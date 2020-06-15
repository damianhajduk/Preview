<?php
function sent_sms($shop,$prijemce,$zprava,&$clr,$smsType){
	$zprava = urlencode($zprava);
	$fp = fsockopen("xxx.xxx.xxx.xxx", 81, $errno, $errstr, 30);
	if (!$fp) {
		echo "$errstr ($errno)<br />\n";
	} else {
		$out = "POST / HTTP/1.1\r\n";
		$out .= "Content-Type: application/x-www-form-urlencoded \r\n";
		$out .= "Cookie: SourceClient=".$smsType."\\".$shop."|xxx.xxx.xxx.xxx\r\n";
		$out .= "Host: xxxx.cz\r\n";
		$out .= "Connection: Close\r\n";
		$out .= "Content-Length: ".strlen("prijemce=".$prijemce."&zprava=".$zprava)."\r\n\r\n";
		$out .= "prijemce=".$prijemce."&zprava=".$zprava."\r\n\r\n";
				
		fwrite($fp, $out);
		while (!feof($fp)) {
			$ret = $ret.fgets($fp, 128);
		}
		fclose($fp);
	}
	
	$pos = strpos ($ret,"<div id=\"message\">");
	$pos = strpos ($ret,'<span>',$pos);
	if ($pos > 0){
    	$pos = strpos($ret,">",$pos+1);
     	if ($pos>0){
           $res = substr($ret, $pos+1);
           $pos = strpos($res, "<");
		   if($pos > 0) {
               	$result =  substr($res,0,$pos);
				$clr = 'green';
        	}else{
				$result = 0;	
			}
     	}else{
			$result = 0;
		}
	}else{
		$result = 0;
		$clr = 'red';
		$pos = strpos ($ret,"<div id=\"error\">");
		$pos = strpos ($ret,'<span>',$pos);
		if ($pos > 0){
			$pos = strpos($ret,">",$pos+1);
			if ($pos>0){
			   $res = substr($ret, $pos+1);
			   $pos = strpos($res, "<");
			   if($pos > 0) {
					$result =  substr($res,0,$pos);
					$clr = 'red';
				}else{
					$result = 0;	
				}
			}else{
				$result = 0;
			}
		}
	}

	return $result;	
}
?>