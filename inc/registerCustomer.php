<?php
	require_once 'inc/protection.php';
	
	if(isset($_GET['odeslano'])){?>
    	<br><br>
		<div class="row">
        	<div class="col-md-12">
            	<div class="alert alert-success" style="font-size:150%;"><span class="glyphicon glyphicon-ok"></span>&nbsp;Děkujeme za zaregistrování nového zákazníka.</div>
                <div class="text-center"><img src="images/palec.jpg"></div>
           	</div>
        </div>	
        <br>
        <script>
			setTimeout(function () {
				location.replace('/'); //will redirect to your blog page (an ex: blog.html)
			}, 3500);
		</script>	
<?php
	}else{
?>

<style>
	.glyphicon-refresh-animate {
		-animation: spin 1s infinite linear;
		-webkit-animation: spin2 1s infinite linear;
	  font-size:150px;
	  color:#fff;
	}
	
	@-webkit-keyframes spin2 {
		from { -webkit-transform: rotate(0deg);}
		to { -webkit-transform: rotate(360deg);}
	}
	
	@keyframes spin {
		from { transform: scale(1) rotate(0deg);}
		to { transform: scale(1) rotate(360deg);}
	}
	
	.loading{
		background-color:rgba(0,0,0,0.5);
		display:block;
		position:fixed;
		top:0;
		left:0;
		width: 100%; height: 100%;
		overflow:hidden;
		z-index:1000000;
	}
	.loadingAnimation{
		position:absolute;
		left:50%;
		margin-left:-75px;
		top:50%;
		margin-top:-75px;
	}

	.table td, .table th {
    vertical-align: middle;
	}

	.btn:disabled{
		cursor:no-drop;
	}
}
</style>
<br>
	<nav aria-label="breadcrumb">
<ol class="breadcrumb">
	<li class="breadcrumb-item"><a href="/">Domů</a></li>
    <li class="breadcrumb-item active">Registrace zákazníka</li>
</ol>
</nav>
<div class="row">
    <div class="col-md-12 text-center"><h2>Registrace zákazníka</h2></div>
</div>
<div class="cartForm">
<div class="row align-items-center">
	<div class="col-lg-5">
		<h3>Informace o zákazníkovi</h3>
		<p style="color:red; font-size:1rem;">(všechna pole jsou povinná)</p>
		<form method="post" action="" id="zakaznik">
			<div class="row">
				<div class="col-md-6">
					<input type="text" name="jmeno" placeholder="Jméno" id="zak_KontaktJmeno">
				</div>
				<div class="col-md-6">
					<input type="text" name="prijmeni" placeholder="Příjmení" id="zak_KontaktJmeno">
				</div>
				<div class="col-md-6">
					<input type="text" name="osloveni" placeholder="Oslovení v 5.pádu" id="zak_osloveni">
				</div>
				<div class="col-md-6">
					<input type="text" name="PSC" placeholder="PSČ" maxlength="5" id="zak_PSC">
				</div>
				<div class="col-md-6">
					<input type="tel" name="tel" placeholder="Telefon (xxxxxxxxx)" pattern="[0-9]{9}" maxlength="9" id="zak_KontaktTel">
				</div>
				<div class="col-md-6">
					<input type="text" name="email" placeholder="E-mail" id="zak_KontaktEmail">
				</div>
				<div style="display: flex;font-size: 15px;margin-top: 10px;margin-left: 15px;">
					<p>Pohlaví:</p>
  						<input style="width:auto;" type="radio" name="pohlavi" value="Muž">Muž<br>
 					 	<input style="width:auto; margin-left:10px;" type="radio" name="pohlavi" value="Žena">Žena<br>
				</div>
				<div style="display: flex;font-size: 15px;margin-top: 10px;margin-left: 15px;">
					<p>Souhlas se zpracováním osobních údajů:</p>
  						<input style="width:auto;" id="souhlasOsobniUdaje" type="radio" name="souhlasZ" value="1">Ano<br>
 					 	<input style="width:auto; margin-left:10px;" type="radio" name="souhlasZ" value="0">Ne<br>
				</div>
				<div style="display: flex;font-size: 15px;margin-top: 10px;margin-left: 15px;">
					<p>Souhlas se zasíláním novinek:</p>
  						<input style="width:auto;" type="radio" name="souhlas" value="1">Ano<br>
 					 	<input style="width:auto; margin-left:10px;" type="radio" name="souhlas" value="0">Ne<br>
				</div>
			</div>
		</form>
	</div>
	<div class="col-lg-3">
		<button onclick="javascript:sendOrder('REG')" class="btn btn-block btn-lg btn-primary" id="btn1" disabled>Registrovat</button>
	</div>
</div>
</div>
<script>
$("#souhlasOsobniUdaje").change(function() {
  if($("#souhlasOsobniUdaje").val != "1"){
	  $("#btn1").prop('disabled', false);
  }
  else{
	$("#btn1").prop('disabled', true);
  }
});

(function($) {
$.fn.serializeFormJSON = function() {

   var o = {};
   var a = this.serializeArray();
   $.each(a, function() {
       if (o[this.name]) {
           if (!o[this.name].push) {
               o[this.name] = [o[this.name]];
           }
           o[this.name].push(this.value || '');
       } else {
           o[this.name] = this.value || '';
       }
   });
   return o;
};
})(jQuery);


	
	
	$('[data-btn="deleteFromCart"]').click(function(){
		var Cislo = $(this).data('no');
		$.ajax({
			type: "POST",
			url: "/controllers/deleteFromCart.php",
			dataType:'html',
			data: {cislo: Cislo},
			success:  function(data){
				//alert("---"+data);
				//alert("Settings has been updated successfully.");
				window.location.reload(true);
			}
		});
	});

	function sendOrder(Typ){
		var Data = $("#zakaznik").serializeFormJSON();
		Data.typ = Typ;
		//console.log(Data);

		$.ajax({
			type: "POST",
			url: "/controllers/finishOrder.php",
			dataType:'json',
			data: Data,
			
			beforeSend: function() { $('.loading').show(); },
        	complete: function() { $('.loading').hide(); },
			
			success:  function(data){
				if(data.status == 'success'){
					$('.loading').hide();
        			//console.log(data.msg);
					location.replace('index.php?page=registraceZakaznika&odeslano');
    			}else if(data.status == 'error'){
					$('.loading').hide();
        			//console.log(data.msg);
					alert(data.msg);
    			}else if(data.status == 'zadne-polozky'){
					$('.loading').hide();
        			location.replace('index.php?page=registraceZakaznika');
					//alert(data.msg);
    			}
				//location.replace('index.php?page=kosik&odeslano');
			}
		});
	};

	$(function(){
		$('#zakProf').change(function(){
		var ID = $(this).val();
			$.ajax({
				url: '/controllers/fillCustomer.php',
				data: {id: ID},
				type: 'POST',
				dataType: 'JSON',
				beforeSend: function() { $('.loading').show(); },
	        	complete: function() { $('.loading').hide(); },		
				success: function(data){
					//console.log(data);
					$("#zak_KontaktEmail").val	(data.zak_KontaktEmail);
					$("#zak_KontaktJmeno").val	(data.zak_KontaktJmeno);
					$("#zak_KontaktTel").val	(data.zak_KontaktTel);
					$("#zak_Mesto").val			(data.zak_Mesto);
					$("#zak_Ulice").val			(data.zak_Ulice);
					$("#zak_NazevFirmy").val	(data.zak_NazevFirmy);
					$("#zak_PSC").val			(data.zak_PSC);
				}
			});
		});
	});
	$('[data-name="amount"]').change(function(){
		var $ks = $(this).val();
		var $cislo = $(this).data('no');
		var $akce = 'ks';
		var $sleva = $('[data-no="'+$cislo+'"][data-name="discount"]').val();
		$.ajax({
			url: '/controllers/changeCart.con.php',
			data: {akce:$akce,cislo:$cislo,ks:$ks,sleva:$sleva},
			type: 'POST',
			dataType: 'json',
			beforeSend: function() { $('.loading').show(); },
	        complete: function() { },		
			success: function(data){
				//$('.loading').hide();
				location.reload();
			}
		});
	})
	$('[data-name="discount"]').change(function(){
		var $sleva = $(this).val();
		var $cislo = $(this).data('no');
		var $akce = 'sleva';
		$.ajax({
			url: '/controllers/changeCart.con.php',
			data: {akce:$akce,cislo:$cislo,sleva:$sleva},
			type: 'POST',
			dataType: 'json',
			beforeSend: function() { $('.loading').show(); },
	        complete: function() { },		
			success: function(data){
				//$('.loading').hide();
				location.reload();
			}
		});
	})
	
</script>
<?php 	}?>