<!-- Kontroler zajišťující zápis prodejce u objednávky -->
<?php

	// Uložení prodejce do Cookie, následně se používá ve výpisu, při odesílání emailu a v detailu objednávky.
	if(setcookie("username",$_POST['username'],time()+45000,'/')){
		$response_array['status'] = 'success'; 

		print json_encode($response_array);
		exit;
	}else{
		$response_array['status'] = 'error'; 
		$response_array['msg'] = 'Nebylo možné vybrat prodejce!';
			
		print json_encode($response_array);
		exit;
	}

?>