<?php
	require_once 'inc/protection.php';
?>
<?php              
	$searchString = $_GET['q'];
	
	$heading = $searchString.' - Výsledek hledání';
		
	if(isset($_GET['sort'])){
		if($_GET['sort']=='Relevace'){
			$orderBy = "NULL";
			$orderAsc = "NULL";
		}else{
			$sort = explode('-',$_GET['sort']);
			if(count($sort)==2){
				$orderBy = "'".$sort[0]."'";
				$orderAsc = $sort[1];
			}else{	
				$orderBy = "NULL";
				$orderAsc = "NULL";
			}
		}
	}else{
		$orderBy = "NULL";
		$orderAsc = "NULL";
	}
	if(isset($_GET['onPage'])){
		$onPage = $_GET['onPage'];
	}else{
		$onPage = 3000;	
	}
	
	
	if(isset($_GET['actPage'])&&$_GET['actPage']>0){
		$actPage = $_GET['actPage'];	
	}else{
		$actPage = 1;	
	}
	/*------------NEW SP END-----------------*/	
	
	$sql = "DECLARE	@return_value int;
			EXEC	@return_value = sp_HledaniZbozi
					@SearchString = '$searchString',
					@PageSize = $onPage,
					@Page = $actPage,
					@OrderByFieldName = $orderBy,
					@OrderByASC = $orderAsc
	
			SELECT	'RetVal' = @return_value";
			
    echo '<!-- '.$sql.' -->';
	$stmt = $db->prepare($sql);
	
	$stmt->execute();
	
	$products = $stmt->fetchAll(PDO::FETCH_ASSOC);	
	$stmt->nextRowset();
	
	$totalPages = $stmt->fetchAll(PDO::FETCH_ASSOC);	
	$totalPages = $totalPages[0]['RetVal'];
	
	$stmt->closeCursor();
	unset($stmt);
	
	$dispPages = 3; //zobrazí se 5 za a 5 před aktuální stránkou
	
	if(($actPage-$dispPages)<=0){
		$cntPages = 1;	// když 5 za bude menší než 0
	}else{
		$cntPages =($actPage-$dispPages);
	}
	
	if(($actPage+$dispPages)<=(2*$dispPages)){
		$lastPage = (2*$dispPages)+1;
	}else{
		$lastPage = $actPage+$dispPages;	
	}
	
	
	if(($actPage+$dispPages)>=$totalPages){
		$lastPage = $totalPages;	
		$cntPages = ($totalPages - (2*$dispPages));	
	}
	
	if($cntPages<0){$cntPages=1;}
									
?>

<div class="row">
	<div class="col-12">
		<h2 style="margin-top:20px; margin-bottom:21px;" class="text-center"><?php echo $heading;?></h2>
	</div>
</div>
<!-- <div class="row justify-content-center zakaznikEmail">
	<div class="col-md-6">
		<div class="d-flex align-items-center">E-mail na zákazníka: <input class="flex-grow-1" type="email" name="zakaznikEmail" value="<?php echo $_SESSION['zakaznik'];?>" placeholder="@"></div>
	</div>
	<div class="col-md-4">
		<button type="button" data-btn="saveMail">Uložit</button>
		<button type="button" data-btn="clearMail">Vymazat</button>
	</div>
</div> -->
<div class="row">
<?php	$cntProducts = count($products);
        for($i=0;$i<$cntProducts;$i++){
			$prod = $products[$i];
        	include 'inc/catalogItem.inc.php';
		}			
?>
</div>
<div id="availabilityStores">
	<div class="container">
	</div>
</div>  
   
<script src="/js/addToCart.js"></script>
<script src="/js/addToCompare.js"></script> 
<script src="/js/availability.js"></script>  
<script src="/js/availabilityShowAjax.js"></script> 
<script>

</script>