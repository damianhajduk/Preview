// Funkce na přidání do košíku
function addToCart(Cislo){
	$('#notifications').removeClass('show');
	$('#notifications').removeClass('hide');
	$.ajax({	//Vytvoření ajax požadavku na controller addToCart.php
		type: "POST",
		url: "/controllers/addToCart.php",
		dataType:'html',
		data: {cislo: Cislo},
		success:  function(response){
			
			$('#notifications').html(response);
						
			$('.cartInfo').load('/inc/cartInfo.php?'+Math.random());
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