<!-- Kontroler zajišťující vygenerování a zaslání emailu -->
<?php
	require_once '../inc/protection.php';
	$id = $_POST['id'];
	
	$customer_query = $db->query("
		SELECT 
			tbl_Nabidky.id AS id,
			tbl_Nabidky.jmeno AS ZakaznikJmeno,
			tbl_Nabidky.prijmeni AS ZakaznikPrijmeni,
			tbl_Nabidky.tel AS ZakaznikTel,
			tbl_Nabidky.email AS ZakaznikEmail,
			tbl_Nabidky.cislo AS CisloNabidky,
			tbl_Nabidky.stav AS StavNabidky,
			tbl_Nabidky.datum AS DatumNabidky,
			tbl_Nabidky.kontaktovan AS DatumKontaktu,
			tbl_Nabidky.prodejce AS Prodejce,
			tbl_Nabidky.typ_zaznamu AS rezervace,
			tbl_Prodejci.jmeno AS ProdejceJmeno,
			tbl_Lokace.NazevWeb AS NazevWeb,
			tbl_Lokace.Adresa AS Adresa,
			tbl_Lokace.telefon AS Telefon,
			tbl_Lokace.KodLokace AS KodLokace,
			tbl_Stav.TextaceEmailu AS EmailText,
			tbl_Stav.PredmetEmailu AS EmailPredmet
		FROM 
			tbl_Nabidky
		LEFT JOIN
			tbl_Prodejci
		ON
			tbl_Nabidky.prodejce = tbl_Prodejci.username
		LEFT JOIN
			tbl_Logins
		ON
			tbl_Prodejci.stredisko = tbl_Logins.kod
		LEFT JOIN
			tbl_Lokace
		ON
			tbl_Prodejci.stredisko = tbl_Lokace.KodLokace
		LEFT JOIN
			tbl_Stav
		ON
			tbl_Nabidky.Stav = tbl_Stav.Nazev
		WHERE
			login = '$login'
		AND
			tbl_Nabidky.id = '$id'
		ORDER BY
			rezervace DESC,
			datum ASC
	");
	
	
	$customer = $customer_query->fetch(PDO::FETCH_ASSOC);
	
	if(strlen($customer['id'])>0){
		$datum = date('Y-m-d H:i:s');
		
		$sms = '0';

		$predmet = $customer['EmailPredmet'];
		$zprava_zakaznik = '<h3>Vážený zákazníku,</h3>';
		$zprava_zakaznik .= $customer['EmailText'];
		
		$zprava_zakaznik .= "<br/><br/>
		Budeme se těšit na Vaši další návštěvu.
		<br/><br/>
		
		S pozdravem<br/>".
		$customer["ProdejceJmeno"]."<br/>
		+420 ".$customer["Telefon"]. "<br/><br>".
		$customer["NazevWeb"]." 
		<br/><br/>
		<div style='clear:both'></div>";


$login = $_COOKIE['login'];
$username = $_COOKIE['username'];
$datumPDF = date("d.m.Y");
function oddel_tisice($cislo){
	$oddeleno = number_format($cislo, 0, ',', ':');
	return str_replace(':', '&nbsp;', $oddeleno);
}
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

$prodejna_query = $db->query("SELECT 
			jmeno,
			NazevWeb,
			Adresa,
			tbl_Lokace.Telefon AS Telefon,
			tbl_Lokace.KodLokace AS KodLokace
		FROM 
			tbl_Lokace
		LEFT JOIN
			tbl_Logins
		ON
			tbl_Lokace.KodLokace = tbl_Logins.kod
		LEFT JOIN
			tbl_Prodejci
		ON
			tbl_Lokace.KodLokace = tbl_Prodejci.stredisko
		WHERE
			tbl_Logins.Login = '$login'
		AND
			tbl_Prodejci.username = '$username'
	");
	
	$prodejna = $prodejna_query->fetch(PDO::FETCH_ASSOC);

	$html = '
<style>
	body{
		margin:0px;
		padding:0px;
		color:#363636;
		font-size:11px;
		font-family: Arial;
	}
	
	h1{
        color:#363636;	
        font-size:30px;	
        margin-bottom:30px;
	}
	
	h2{
		color:#363636;
		width:100%;
		text-align:center;		
	}
	
	.content{
		width:100%;
		background-color:#fff;	
		height:100%;
        padding:0 50px;
		position:relative;
	}
	
	.zakaznik{
		font-size:12px;
		border-collapse:collapse;
	}
		.zakaznik td{
			padding:0px 10px 5px 0px;	
		}
			.zakaznik td.text{
				font-size:14px;
                padding-left:20px;
                
			}
	
	.nabidka{
		border-collapse:collapse;
		font-size:12px;
		width:100%;
	}

	.nabidka thead td{
		text-align:center;
		font-weight:bold;	
	}
	
	.nabidka tbody td{
		border-top:solid 1px #9e9e9e;
		padding:5px;
		height:20px;
	}
	
	.kontakt{
		color:#929292;
		font-size:10px;
		background-color:#fff;
		-webkit-border-radius: 0px 0px 6px 6px;
 		border-radius: 0px 0px 6px 6px;
		border: 1px solid #9e9e9e;
		border-top:0px;
		padding:30px 20px 20px 20px;
		margin-top:-1px;
	}
	
	.poznamky{
		background-color:#fff;
		border-left: 1px solid #9e9e9e;
		border-right: 1px solid #9e9e9e;
		padding:0px 20px 0px 20px;
		margin-top:-1px;		
		font-size:8px;
		/*height:100%;*/
	}
	
	.rohy{
		width:100%;
		background-color:#fff;
		-webkit-border-radius: 0px 0px 6px 6px;
 		border-radius: 6px 6px 0px 0px;
		border: 1px solid #9e9e9e;
		border-bottom:none;
		padding:10px 0px 10px 0px;
		margin:10px 0px 10px 0px;
	}
	h4{
        font-size:22px;
        border-bottom:1px solid #6370be;
    }
	
</style>

<body style="">
	<div class="content">
		<h1>Nabídka č. '.$customer['CisloNabidky'].' ze dne '.$datumPDF.'</h1>
		<table class="zakaznik">
			<tr>
				<td style="width:200px;padding:10px; background:#f5f5f5;">
					<p style="font-size:18px;border-bottom:1px solid #6370be;">'.$prodejna['jmeno'].'</p><br>
					<p style="font-size:14px">
						'.str_replace('Space ','',$prodejna['NazevWeb']).'<br>
						'.$prodejna['Adresa'].'<br><br>
						Tel.: '.$prodejna['Telefon'].'<br>
						E-mail: '.$login.'@setos.cz						
					</p>
				</td>
				<td class="text"><strong>Vážený zákazníku,</strong> <br><br> děkujeme Vám za návštěvu naší prodejny. Níže najdete souhrnnou nabídku produktů včetně cen, o kterých jste s prodejcem hovořili. Po levé straně naleznete kontakt na prodejce, kterého v případě dalších dotazů neváhejte kontaktovat. Jsme rádi že jste zavítali na naši prodejnu a budeme se těšit na Vaši další návštěvu.<br /><br />
				<strong>Tým Samsung</strong></td>
			</tr>
		</table>
		<br />
		<br />
		<br />
		<br />
';

$kosik_query = $db->query("SELECT tbl_Nabidky_Zbozi.cislo as Cislo
      ,ks as Ks
	  ,Popis
	  ,ProdejniArgumenty
	  ,Cena
	  ,Sleva
	  ,url
  FROM tbl_Nabidky_Zbozi
  		LEFT JOIN
			tbl_Zbozi 
		ON
			tbl_Nabidky_Zbozi.cislo = tbl_Zbozi.Cislo
		LEFT JOIN
			tbl_Samsung_HeurekaFeed
		ON
			tbl_Zbozi.Cislo2 = tbl_Samsung_HeurekaFeed.ItemID AND tbl_Zbozi.EAN = tbl_Samsung_HeurekaFeed.EAN
 		where id_nabidky = ".$customer['id']."
		");
		
$sleva_celkem = 0;
		while($kosik = $kosik_query->fetch(PDO::FETCH_ASSOC)){
			
			$kontrolni_pocet_polozek++;
            if(intval($kosik['Sleva'])>0){
                
				$sleva = '<tr>
								<td>Kusů: '.$kosik['Ks'].'&nbsp;&nbsp;&nbsp;Celkem bez DPH: '.oddel_tisice(round(($kosik["Cena"])*$kosik['Ks'])).'&nbsp;Kč&nbsp;&nbsp;&nbsp;Celkem s DPH: '.oddel_tisice(round(($kosik["Cena"]*1.21)*$kosik['Ks'])).'&nbsp;Kč&nbsp;&nbsp;&nbsp;Sleva: -'.oddel_tisice($kosik['Sleva']).'&nbsp;Kč</td>
								<td></td>
								<td></td>
							</tr>
                            <tr>
                                <td><strong style="font-size:18px;">Cena se slevou: '.oddel_tisice(round((($kosik["Cena"]*1.21)*$kosik['Ks'])-$kosik['Sleva'])).'&nbsp;Kč</strong></td>
                            </tr>';
            }else{
                $sleva = '	<tr>
								<td>Kusů: '.$kosik['Ks'].'</td>
							</tr>
                            <tr>
                                <td><strong style="font-size:18px;">Cena celkem: '.oddel_tisice(round($kosik["Cena"]*1.21)*$kosik['Ks']).'&nbsp;Kč</strong></td>
                            </tr>';
            }
            
            if(strlen($kosik['ProdejniArgumenty'])>0){
                $kosik['ProdejniArgumenty'] .= '<br><br><br>';
            }
			$html= $html .	'<br>
				<table class="nabidka">
				<tr>
					<td rowspan="4" width="160px;"><img src="http://img.setos.cz?file='.code_md5hex($kosik['Cislo'].'_200_0.jpg').'" style="height:150px;"/></td>
				</tr>
				<tr>
					<td style="padding:3px;"><a href="'.$kosik["url"].'"><h4>'.$kosik["Popis"].'</h4></a><br><br></td>
				</tr>
				<tr>
					<td style="font-size:11px;padding:3px;padding-right:30px;">'.$kosik["ProdejniArgumenty"].'</td>
				</tr>
                <tr>
                    <td style="background:#f5f5f5; padding:5px;">
                        <table>
                            '.$sleva.'
                        </table>
                    </td>
				</tr>
            </table><br><br>
			';
			
			$cena_celkem = $cena_celkem + (round($kosik["Cena"]*1.21)*$kosik["Ks"]);
			$cena_celkemDPH = $cena_celkemDPH + (round($kosik["Cena"])*$kosik["Ks"]);
            $sleva_celkem += $kosik['Sleva'];
		}
		if($sleva_celkem==0){
			$html .= '<br><br><div style="background:#f5f5f5; padding:5px;"><table><tr>
								<td align="right" style="padding-left: 375px;"><strong style="font-size:18px;">Cena celkem bez DPH: '.oddel_tisice($cena_celkemDPH).'&nbsp;Kč</strong></td>
								</tr>
								<tr>
                                <td align="right" style="padding-left: 375px;"><strong style="font-size:18px;">Cena celkem s DPH: '.oddel_tisice($cena_celkem).'&nbsp;Kč</strong></td>
                            </tr><tr><td style=""><small>Změna ceny vyhrazena.</small></td></tr></table></div>';
        }else{
			$html .= '<br><br><div style="background:#f5f5f5; padding:15px;"><table><tr>
										<td align="right" style="padding-left: 375px; font-size:14px;">Celkem bez DPH: '.oddel_tisice($cena_celkemDPH).'&nbsp;Kč</td>
										</tr>
										<tr>
										<td align="right" style="padding-left: 375px; font-size:14px;">Celkem s DPH: '.oddel_tisice($cena_celkem).'&nbsp;Kč</td>
										</tr>
										<tr>
										<td align="right" style="padding-left: 375px;font-size:14px;">Sleva: -'.oddel_tisice($sleva_celkem).'&nbsp;Kč</td>
										</tr>
										<tr>
                                        <td align="right" style="padding-left: 375px;"><strong style="font-size:22px;">Cena celkem: '.oddel_tisice($cena_celkem-$sleva_celkem).'&nbsp;Kč</strong></td>
										</tr><tr><td colspan="2"><small>Změna ceny vyhrazena.</small></td></tr></table></div>';
        }

	$html= $html .'	</div>
	</body>';
		
require_once '../vendor/autoload.php';

$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4','margin_top' => 35, 'margin_right' => 0, 'margin_bottom' => 10, 'margin_left' => 0, 'margin_header' => 0, 'margin_footer' => 0]);
		$mpdf->SetHTMLHeader('<img src="../images/pdf/header.jpg" style="border:none;">');
		$mpdf->SetHTMLFooter('<img src="../images/pdf/footer.jpg" style="border:none;">');
		$mpdf->WriteHTML($html);
		//$mpdf->Output();
		
		//echo "<xmp>".$html."</xmp>";
		$nazevsouboru = $customer['CisloNabidky'];
		$name = $nazevsouboru.".pdf";
        $pdf = $mpdf->Output('', 'S');
        file_put_contents('../pdf/'.$name,$pdf);


		$priloha = "../pdf/".$customer['CisloNabidky'].".pdf";
		$prilohaGlobal = $dbS->prepare("SELECT tbl_Stav.*, tbl_EmailovePrilohy.* FROM tbl_Stav INNER JOIN tbl_EmailovePrilohy_keStavum ON tbl_EmailovePrilohy_keStavum.IDstavu = tbl_Stav.id INNER JOIN tbl_EmailovePrilohy ON tbl_EmailovePrilohy_keStavum.IdPrilohy = tbl_EmailovePrilohy.ID WHERE (tbl_EmailovePrilohy.DatumOd <= GETDATE() AND tbl_EmailovePrilohy.DatumDo >= GETDATE()) AND tbl_Stav.ID = ".$_POST['action']."");
		$prilohaGlobal->execute();
		$prilohaGlobal = $prilohaGlobal->fetch(PDO::FETCH_ASSOC);

		date_default_timezone_set("Europe/Prague");
		require_once '../mailer/PHPMailerAutoload.php';
		$mail = new PHPMailer();
		$mail->IsSMTP();
		$mail->Host = 'xxx.xxx.xxx.xxx';
		$mail->CharSet = 'UTF-8';
		$mail->setLanguage('cz', 'phpmailer.lang-cz.php');
		$mail->setFrom($login.'@setos.cz', $customer['NazevWeb']);
		$mail->AddAddress($customer['ZakaznikEmail'],$customer['ZakaznikJmeno'].' '.$customer['ZakaznikPrijmeni']);
		$mail->AddAttachment($priloha);


		foreach($prilohaGlobal as $prilohaSingle){
			$mail->AddAttachment($prilohaSingle['Soubor']);
		}
		$mail->Subject = $predmet;
		$mail->msgHTML($zprava_zakaznik);
		
		if(strlen($customer['ZakaznikEmail'])>0){
			if (!$mail->send()) {
				$sendMail = 0;
			/*$response_array['status'] = 'error'; 
			$response_array['msg'] = 'Mail zákazníkovi nelze odeslat!';
			
			print json_encode($response_array);
			exit;*/
			}else{
				$sendMail = 1;	
			}
		}else{
			$sendMail = 0;	
		}
		
		if(strlen($customer['ZakaznikTel'])>0 && $sms != '0'){
			
			$prevodni_tabulka = Array(
			  '<br>'=>chr(13).chr(10),
			  '<br/>'=>chr(13).chr(10),
			  '<strong>'=>'',
			  '</strong>'=>''
			);
			
			
			if($customer['rezervace']=='rezervace'){
				$klient = 'Rezervace-SBS|'.$customer['KodLokace'];	
			}else{
				$klient = 'OdlozenyProdej-SBS|'.$customer['KodLokace'];	
			}
			

			$zprava = strtr($sms,$prevodni_tabulka);
			$dbSMS = new PDO ("dblib:charset=UTF-8;host=ND-REPLIKACE:1433;dbname=SMS","sa","W@b1648");
			$dbSMS->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$dbSMS->query("
				SET ANSI_NULLS ON 
				SET CURSOR_CLOSE_ON_COMMIT OFF 
				SET ANSI_NULL_DFLT_ON ON 
				SET IMPLICIT_TRANSACTIONS OFF 
				SET ANSI_PADDING ON 
				SET QUOTED_IDENTIFIER ON 
				SET ANSI_WARNINGS ON
			");

			$stmt = $dbSMS->prepare("SMS_CreateNew2 @Phone_No = :tel, @Message = :zprava, @Client = :klient");
			$stmt->bindParam(':tel',$customer['ZakaznikTel']);
			$stmt->bindParam(':zprava',$zprava);
			$stmt->bindParam(':klient',$klient);
			$stmt->execute();
			$sendSMS = 1;
		}else{			
			$sendSMS = 0;	
		}
		
		if($sendSMS == 1 || $sendMail == 1){
			// $db->query("UPDATE tbl_Nabidky SET stav = '$stav', kontaktovan = '$datum' WHERE id = '$id'");
			
			$response_array['status'] = 'success'; 
			$response_array['msg'] = '
				<div class="modal-header">
					<h4 class="modal-title" id="itemsLabel">Odeslání informace o zboží</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
				<div class="modal-body">';
			if($sendMail ==	1){
				$response_array['msg'] .= 'Zákazníkovi byl odeslán e-mail.<br><br>';
			}
			if($sendSMS ==	1){
				$response_array['msg'] .= 'Zákazníkovi byla odeslána SMS zpráva se stavem:<br>'.$msgSMSMSg;
			}	
				
			$response_array['msg'] .= '	</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Zavřít</button>
				</div>'; 
			print json_encode($response_array);
			exit;
		}else{
			$response_array['status'] = 'error'; 
			$response_array['msg'] = 'Zákazníka nebylo možné kontaktovat (špatný e-mail a telefonní číslo)';
			print json_encode($response_array);
			exit;
		}
	}else{
		$response_array['status'] = 'error'; 
		$response_array['msg'] = 'Zprávu nelze odeslat';
		print json_encode($response_array);
		exit;
	}
	?>