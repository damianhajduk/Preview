<?php
	require_once '/inc/protection.php';
?>
<ol class="breadcrumb">
	<li><a href="/">Katalog</a></li>
    <li class="active">Porovnání produktů</li>
</ol>
            
<div class="row">
	<div class="col-md-12">
    	<h2>Porovnání produktů</h2>
        <hr>
   	</div>
</div>

<?php
	$sql = "
		DECLARE	@return_value int
		EXEC	@return_value = sp_PorovnaniZbozi
		@User = N'".$login."'";
		
	$stmt = $db->prepare($sql);
	$stmt -> execute();
	
	$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
if(count($products)>0){	
	
	$cena = array();
	
	$stmt -> nextRowset();
?>
	<div class="row">
    	<div class="col-md-12">
        	<div class="table-responsive">
  				<table class="table table-striped comparison">
                	<thead>
                    	<tr>
                        	<th>Název parametru</th>
                            <?php
								for($i=0;$i<count($products);$i++){
									$prod = $products[$i];
									echo '
										<th class="text-center">
											<div style="position:relative;">
												<a href="index.php?page=product&amp;name='.strtolower(strtr($prod["Popis"].'-'.$prod["Cislo"],$prevodni_tabulka)).'">
													<img src="http://img.setos.cz?file='.code_md5hex($prod["Cislo"].'_100_0.jpg').'" alt="" style="">
													'.$prod['Popis'].'
												</a>
												<br>
												<br>
												<a href="javascript:addToCart(\''.$prod["Cislo"].'\')" class="btn btn-primary">
													<span class="glyphicon glyphicon-shopping-cart"></span> Koupit
												</a>
												<a href="javascript:deleteFromComp(\''.$prod["Cislo"].'\')" class="deleteFromComp"><span class="glyphicon glyphicon-remove"></span></a>	
											</div>
										</th>';
									$cena[$i] = $prod['Cena'];
								}
							?>
                        </tr>
                    </thead>
                    <tfoot>
                    	<tr>
                        	<th>Cena</th>
                            <?php
								for($i=0;$i<count($cena);$i++){
									$cenaDisp = explode('.',$cena[$i]);
									$cenaDisp = round($cenaDisp[0] * $marze * 1.21);
        							$cenaDisp = oddel_tisice($cenaDisp);
									echo '<td class="priceColor text-center">'.$cenaDisp.' Kč</td>';
								}
							?>
                        </tr>
                    </tfoot>
                    <tbody>
                    	<?php 
							while($param = $stmt->fetch(PDO::FETCH_NUM)){
								echo '<tr>';
									for($i=0;$i<=count($products);$i++){
										if($i==0){
											echo '<th>';	
										}else{
											echo '<td class="text-center">';
										}
										echo $param[$i];
										
										if($i==0){
											echo '</th>';	
										}else{
											echo '</td>';
										}
									}								
								echo '</tr>';
							}
						?>
                    </tbody>
                    <tfoot>
                    
                    </tfoot>
				</table>
			</div>
        </div>
    </div>

<?php
}else{?>
	<div class="row"><div class="alert alert-warning"><span class="glyphicon glyphicon-exclamation-sign"></span> Nemáte žádné produkty k porovnání</div></div>
<?php	
}

	$stmt->closeCursor();
	unset($stmt);
?>
<script src="js/addToCart.js"></script>
<script>
	function deleteFromComp(Cislo){
		$.ajax({
			type: "POST",
			url: "/controllers/deleteFromComp.php",
			dataType:'html',
			data: {cislo: Cislo},
			success:  function(data){
				//alert("---"+data);
				//alert("Settings has been updated successfully.");
				window.location.reload(true);
			}
		});
	};
</script>