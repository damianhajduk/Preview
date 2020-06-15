// Funkce pro uložení emailu
$('[data-btn="saveMail"]').click(function () {
	var $email = $('input[name="zakaznikEmail"]').val();
	if ($email.length > 0) {
		$.ajax({    //Vytvoření ajax požadavku na controller saveMail.con.php
			type: "POST",
			url: "/controllers/saveMail.con.php",
			dataType: "json",            
			data: { email: $email }
		});
	}
})
$('[data-btn="clearMail"]').click(function () {
	$('input[name="zakaznikEmail"]').val('');
	var $email = '';
	$.ajax({    //Vytvoření ajax požadavku na controller
		type: "POST",
		url: "/controllers/saveMail.con.php",
		dataType: "json",            
		data: { email: $email }
	});
}) 
	
$('[data-toggle="tooltip"]').tooltip() 