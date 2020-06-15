<?php
	require_once '/inc/protection.php';
?>
<div class="row">
        <div class="col-md-3">
            <div class="panel panel-primary">
                <div class="panel-heading" data-toggle="collapse" data-target="#kategorie_Panel">Kategorie</div>
                <div class="collapse in" id="kategorie_Panel">
                	<div class="list-group">					

    
    
    	<?php
			$subCat_query = $db->query("SELECT category, categoryName FROM tbl_Kategorie WHERE MainCategory = '$category' GROUP BY category,categoryName");
			while($subCat = $subCat_query->fetch(PDO::FETCH_ASSOC)){
				echo '
				<div class="checkbox list-group-item">
					<a href="'.$subCat['category'].'">'.$subCat['categoryName'].'</a>
				</div>';
			}
		?>
                    </div>
                </div>
            </div>
        </div>
</div>