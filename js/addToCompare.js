// Funkce na přidání do porovnávače
function addToCompare(Cislo){
	$('#notifications').removeClass('show');
	$('#notifications').removeClass('hide');
	$.ajax({	//Vytvoření ajax požadavku na controller addToCompare.php
		type: "POST",
		url: "/controllers/addToCompare.php",
		dataType:'html',
		data: {cislo: Cislo},
		success:  function(response){
			
			$('#notifications').html(response);
						
			$('.compareInfo').load('inc/compareInfo.php');
			$('#notifications').addClass('show');
			setTimeout(function() {
				$('#notifications').addClass('hide');
   			},2500);
			setTimeout(function() {
    			$('#notifications').removeClass('show');
				$('#notifications').removeClass('hide');
   			},4000);
		}
	});
};