<?php 
session_start();
require_once 'inc/protection.php';


error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
ini_set('display_errors', 1);

session_start();
$username = $_SESSION['username'];
$loginHash = $_SESSION['loginHash'];
$profile = $_SESSION['profile'];

require_once 'inc/connect.php';
require_once 'inc/function.php';

$category = $_GET['page'];
$cnt_query = $db->query("SELECT count(*) FROM tbl_Kategorie WHERE MainCategory = '$category'");
$cnt = $cnt_query->fetch(PDO::FETCH_NUM);
$cnt = $cnt[0];


if(!isset($_COOKIE['username'])|| $_GET['page']=='vyber-prodejce'){
	if(count($_GET)!=0 && $_GET['page']!='vyber-prodejce'){
		header('location:/');	
	}
	$include = 'inc/chooseVendor.php';
}else if(count($_GET)==0){
	$include = NULL;
}else if($cnt>0){
	$include = 'inc/catalog.php';					
}else if($_GET['page']=='product'){
	$include = 'inc/productDetail.php';
}else if($_GET['page']=='kosik'){
	$include = 'inc/cart.php';	
}else if($_GET['page']=='profil'){
	$include = 'inc/profile.php';	
}else if($_GET['page']=='hledat'){
	$include = 'inc/search.php';	
}else if($_GET['page']=='porovnani'){
	$include = 'inc/comparison.php';	
}else if($_GET['page']=='popisky'){
	$include = 'inc/labels.php';	
}else if($_GET['page']=='prehled'){
	$include = 'inc/overview.php';	
}else if($_GET['page']=='evidence-zasilek'){
	$include = 'inc/shipments.php';	
}else if($_GET['page']=='predobjednavky'){
    $include = 'inc/predobjednavky.php';	
}else if($_GET['page']=='nabidkyinfo'){
	$include = 'inc/nabidkyinfo.php';
}else if($_GET['page']=='prodejci'){
    $include = 'inc/prodejci.inc.php';	
}else if($_GET['page']=='registraceZakaznika'){
	$include = 'inc/registerCustomer.php';	
}else if($_GET['page']=='zmeny'){
	$include = 'inc/changes.php';	
}else{
	$include = NULL;	
}

$url = $_SERVER['REQUEST_URI'];

$urlObsah = urldecode($url);
$urlObsah = str_replace('%','',$urlObsah);

$stmt = $db->prepare("SELECT * FROM tbl_URL_Obsah WHERE :urlObsah LIKE URL ORDER BY Typ, Priorita");
$stmt->bindParam(':urlObsah',$urlObsah);
$stmt->execute();
$urlObsah = $stmt->fetchAll(PDO::FETCH_ASSOC);

$oldTyp = '';
$_urlObsah = array();
for($u=0;$u<count($urlObsah);$u++){
	$_urlObsah[$urlObsah[$u]['Typ']][] = array('Text'=>$urlObsah[$u]['Text'],'Odkaz'=>$urlObsah[$u]['Odkaz'],'Ikona'=>$urlObsah[$u]['Ikona']);
}


?>
<!DOCTYPE html>
<html lang="cs">

<head >

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
	<link rel="shortcut icon" href="/images/favicon.ico">
    <title>Portál SBS</title>

    <!-- Bootstrap Core CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha256-eSi1q2PG6J7g7ib17yAaWMcrr5GrtohYChqibrV7PBE=" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css" integrity="sha256-UzFD2WYH2U1dQpKDjjZK72VtPeWP50NoJjd26rnAdUI=" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <?php $css = filemtime('css/main.min.css');?>
    <link href="/css/main.min.css?v=<?php echo $css;?>" rel="stylesheet">
    <?php /*<link href="/jquery-ui/jquery-ui.css" rel="stylesheet">
    <link href="/touchTouch/touchTouch.css" rel="stylesheet">
    <link href="/datetimepicker/jquery.datetimepicker.css" rel="stylesheet">*/?>
    

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
        <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha256-VsEqElsCHSGmnmHXGQzvoWjWwoznFSZc6hs7ARLRacQ=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.bundle.min.js" integrity="sha256-fzFFyH01cBVPYzl16KT40wqjhgPtq6FFUB6ckN2+GGw=" crossorigin="anonymous"></script>
    
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    
    <!-- Konami Code - EASTER EGG -->
    <!-- <script>
        function onKonamiCode(cb) {
            var input = '';
            var key = '38384040373937396665';
            document.addEventListener('keydown', function (e) {
                input += ("" + e.keyCode);
                if (input === key) {
                    return cb();
                }
                if (!key.indexOf(input)) return;
                input = ("" + e.keyCode);
            });
        }

        onKonamiCode(function () {
            document.body.style.backgroundImage = "url('images/konami.jpg')";
            document.body.style.backgroundSize  = "contain";
            $("*[src]").attr("src", "images/konami.jpg");
        })
    </script> -->
    
    <!-- Bootstrap Core JavaScript -->    
    <?php /*<script src="/js/typeahead.min.js"></script>
    <script src="/jquery-ui/jquery-ui.min.js"></script>
	<script src="/touchTouch/touchTouch.jquery.js"></script>
    
    <script type="text/javascript">  
     $(window).load(function() {
          $('.loading').delay(700).fadeOut("slow");}); 
	</script>*/?>

</head>

<body>
<div class="loading" style="display:none;">
	<div class="loadingInner">
		<div class="uil-squares-css" style="transform:scale(0.5);"><div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div></div>
   	</div>
</div>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar--white">        
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>Menu
        </button>
        <div class="container-fluid">
            <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
                <div class="col-5">
                    <ul class="navbar-nav" style="place-content: center;">
                        <li class="nav-item active">
                            <a class="nav-link" href="/">Domů</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/prehled">Přehled</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/registraceZakaznika">Registrace zákazníka</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" target="_blank" href="http://intranet.setos.cz/wiki/index.php/Odlo%C5%BEen%C3%BD_prodej_Samsung">HELP</a>
                        </li>
                    </ul>
                </div>
                <div class="col-2 text-center">
                    <a href="/"><img src="https://static.obchod-samsung.cz/design/samsung-logo-blue.svg" class="img-fluid" style="max-width:140px;"></a>
                </div>
                <div class="col-5 text-right">
                    <div class="row" style="align-items: center;">
                        <div class="col-6">
                            Přihlášen: <?php echo $_COOKIE['username'];?>
                        </div>
                        <div class="col-lg-2">
                            <div class="cartInfo">
                                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 20 18" enable-background="new 0 0 20 18" xml:space="preserve">
<g>
	<path fill="#060001" d="M19.213,16.201c0,0.993-0.805,1.799-1.799,1.799s-1.8-0.806-1.8-1.799c0-0.994,0.806-1.802,1.8-1.802
		S19.213,15.207,19.213,16.201"/>
	<path fill="#060001" d="M6.838,16.201C6.838,17.194,6.034,18,5.04,18S3.24,17.194,3.24,16.201c0-0.994,0.805-1.802,1.799-1.802
		S6.838,15.207,6.838,16.201"/>
	<path fill="#040000" d="M17.472,2.53v2.527v2.46v2.594L5.056,10.11V7.516V5.055V2.527V2.503C5.056,1.121,3.935,0,2.553,0H2.529H0
		v2.526h2.529v7.583c0,1.396,1.131,2.527,2.527,2.527l12.416,0.002c1.396,0,2.528-1.131,2.528-2.527V2.53H17.472z"/>
</g>
<rect x="3.109" y="2.53" fill="#060001" width="16.375" height="2.518"/>
</svg>
                            </div>
                        </div>
                        <div class="mx-auto">
                            <a class="nav-link" href="login.php?logout">Odhlásit</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

<!-- SearchBOX -->
<div class="container-fluid searchBox">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-xl-6">
                <form class="form-inline align-items-end" role="search" method="get" action="/">
                    <input type="hidden" name="page" value="hledat">
                    <input type="text" class="flex-grow-1" placeholder="Hledat číslo zboží" name="q" <?php if(isset($_GET['q'])){echo 'value="'.$_GET['q'].'"';}?>>
                    <button class="" type="submit">Hledat</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- <div class="alert text-center">Jakékoliv připomínky, problémy či vylepšení k odloženému prodeji pište na email hajdukd@setos.cz</div> -->
<!-- <div class="alert text-center">Může se stát, že zákazníkovi nelze odeslat EMAIL, ať už při vytváření nabídky, či změně stavu. Avšak nabídky se vždy vytvoří bez odeslání emailu. Pracujeme na nápravě této chyby.</div> -->
    <div class="mainContentWrapper">
        <div class="container">
            <?php 

                // Místo, kde se vkládají Views (složka inc) do hlavního templatu.
                if($include!=NULL){
                    include $include;
                }else{
                    include 'inc/home.inc.php';
                }?>
        </div>
    </div>
    <div class="footer text-center">
        <a href="/zmeny">
            <svg viewBox="0 0 136 24" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                <title>SAMSUNG</title>
                <defs></defs>
                <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                    <g id="Icons" transform="translate(-855.000000, -208.000000)" fill="#fff">
                        <g id="Logo" transform="translate(855.000000, 104.000000)">
                            <g id="Samsung/Blue" transform="translate(0.000000, 104.000000)">
                                <path d="M111.587365,16.9479648 L111.328373,1.95939002 L116.058529,1.95939002 L116.058529,20.8564252 L109.256934,20.8564252 L104.544961,5.35183343 L104.4393,5.35183343 L104.702715,20.8564252 L100.00696,20.8564252 L100.00696,1.95939002 L107.095559,1.95939002 L111.48367,16.9479648 L111.587365,16.9479648 Z M25.6759345,3.72908272 L23.0658712,21.0534946 L17.904718,21.0534946 L21.4431205,1.95939002 L29.9387267,1.95939002 L33.4584543,21.0534946 L28.3277707,21.0534946 L25.7825781,3.72760839 L25.6759345,3.72908272 Z M47.9388752,16.5602148 L50.2953529,1.95939002 L58.079347,1.95939002 L58.5010067,21.0534946 L53.7256376,21.0534946 L53.6047422,3.88831083 L53.5000644,3.89175095 L50.3096048,21.0534946 L45.4634677,21.0534946 L42.2744825,3.88831083 L42.1683304,3.88831083 L42.0489093,21.0534946 L37.271083,21.0534946 L37.6912683,1.95939002 L45.4787025,1.95939002 L47.8322316,16.5602148 L47.9388752,16.5602148 Z M9.86812131,15.6667682 C10.0543789,16.1282349 9.99491406,16.7209173 9.906454,17.079672 C9.75017456,17.716093 9.31819458,18.3638172 8.04633545,18.3638172 C6.85114172,18.3638172 6.12675209,17.6738287 6.12675209,16.6334401 L6.12282053,14.782659 L1.00294867,14.782659 L1,16.2550276 C1,20.5138882 4.35263638,21.7999992 7.93969193,21.7999992 C11.3960232,21.7999992 14.2355912,20.6215146 14.6891947,17.4344951 C14.922631,15.7861893 14.7506253,14.7064851 14.6690455,14.3035004 C13.8635675,10.305597 6.61868833,9.11286044 6.0785905,6.87826096 C5.98619888,6.48952813 6.00929678,6.08752629 6.05745837,5.8737478 C6.19358858,5.26091614 6.60836799,4.59156833 7.80159595,4.59156833 C8.92061574,4.59156833 9.57522021,5.28204827 9.57522021,6.32194545 L9.57522021,7.50436163 L14.3407604,7.50436163 L14.3407604,6.15878578 C14.3407604,2.00116283 10.6047971,1.34999847 7.90283357,1.34999847 C4.50940727,1.35048992 1.73323563,2.47442416 1.22655605,5.58920127 C1.08846006,6.44136654 1.06929372,7.20310597 1.27078608,8.16437199 C2.09887056,12.0669351 8.88424883,13.1952923 9.86812131,15.6667682 Z M71.9484106,15.6304013 C72.1307366,16.0884278 72.0742204,16.6727557 71.9847775,17.0280703 C71.8304638,17.6566282 71.4029068,18.2979636 70.1428424,18.2979636 C68.9604262,18.2979636 68.2419339,17.6163297 68.2419339,16.5847871 L68.2389853,14.7541552 L63.1692408,14.7541552 L63.167275,16.211289 C63.167275,20.4278854 66.487476,21.7017103 70.0386561,21.7017103 C73.4586204,21.7017103 76.2721419,20.5355118 76.7203395,17.3784704 C76.9508271,15.7488395 76.7817701,14.6804385 76.7011732,14.2789281 C75.9015925,10.3203403 68.7294472,9.13939846 68.1957381,6.92543966 C68.102855,6.54506139 68.1264444,6.14600822 68.1726402,5.93468696 C68.3077875,5.32873553 68.7201097,4.6657765 69.8995772,4.6657765 C71.0092596,4.6657765 71.6569838,5.34544465 71.6569838,6.37698727 L71.6569838,7.54613444 L76.3758367,7.54613444 L76.3758367,6.21677627 C76.3758367,2.09994323 72.6757489,1.4556591 70.0003234,1.4556591 C66.6427726,1.4556591 63.8926475,2.56779867 63.393831,5.65161475 C63.2547522,6.49444258 63.2355858,7.24979322 63.4370782,8.2007389 C64.2553338,12.0659522 70.974367,13.1815319 71.9484106,15.6304013 Z M87.9778654,18.1947602 C89.3052578,18.1947602 89.7141399,17.2801815 89.8075144,16.8108517 C89.8487957,16.6044449 89.8566588,16.32727 89.8537102,16.0815476 L89.8537102,1.95447557 L94.6865783,1.95447557 L94.6865783,15.6461275 C94.6934585,15.9984934 94.6566001,16.7155114 94.6374338,16.9027519 C94.3042342,20.4667095 91.4872727,21.6216048 87.9778654,21.6216048 C84.4664923,21.6216048 81.6495308,20.4667095 81.3143654,16.9027519 C81.2971649,16.7155114 81.260798,15.9984934 81.2701354,15.6461275 L81.2701354,1.95447557 L86.099072,1.95447557 L86.099072,16.0810562 C86.0951404,16.3267786 86.1049693,16.6039534 86.1452678,16.8103602 C86.2386423,17.2801815 86.6494901,18.1947602 87.9778654,18.1947602 Z M127.80406,17.9962165 C129.186494,17.9962165 129.669093,17.1209534 129.755587,16.6103422 C129.79392,16.3950894 129.804732,16.1287263 129.801783,15.8854611 L129.801783,13.1132211 L127.843867,13.1132211 L127.843867,10.3291863 L134.60713,10.3291863 L134.60713,15.4475838 C134.604182,15.8063385 134.59681,16.0707358 134.53882,16.7051911 C134.222821,20.1826544 131.208298,21.424044 127.823718,21.424044 C124.438155,21.424044 121.425107,20.1831459 121.10665,16.7051911 C121.051117,16.0707358 121.042271,15.80683 121.040305,15.4475838 L121.042271,7.41491868 C121.042271,7.07434743 121.083552,6.47576767 121.122868,6.15682 C121.547968,2.58794791 124.438155,1.43894998 127.825684,1.43894998 C131.211247,1.43894998 134.17515,2.57664468 134.527516,6.15682 C134.588947,6.76473721 134.567815,7.41491868 134.570763,7.41491868 L134.570763,8.05084824 L129.756079,8.05084824 L129.756079,6.97999003 C129.758045,6.98146437 129.752147,6.52786082 129.695631,6.25314318 C129.609137,5.83246644 129.247433,4.86776031 127.787351,4.86776031 C126.398528,4.86776031 125.990629,5.78184763 125.884968,6.25314318 C125.830418,6.50427147 125.808303,6.84533416 125.808303,7.15592727 L125.808303,15.8854611 C125.805354,16.1282349 125.816166,16.3950894 125.852533,16.6103422 C125.94001,17.1209534 126.4231,17.9962165 127.80406,17.9962165 Z" id="Samsung"></path>
                            </g>
                        </g>
                    </g>
                </g>
            </svg>
        </a>
    </div>
    <!-- /.container -->
<div id="notifications">
</div>

<?php $js = filemtime('js/main.min.js');?>
<script src="/js/main.min.js?v=<?php echo $js;?>"></script>
<script type="text/javascript">
	$(document).ready(function(){
    	/*$(".typeahead").typeahead({
       	 	name : 'q',
        	remote: '/controllers/searchWords.php?q=%QUERY',
			limit: 5
        })
		.on('typeahead:selected', function(e){
     		e.target.form.submit();
   		});*/
		$('.cartInfo').load('/inc/cartInfo.php?'+Math.random());
		$('.vendorInfo').load('/inc/vendorInfo.php?'+Math.random());
		$('.compareInfo').load('/inc/compareInfo.php?'+Math.random());
	});
	//$('.typeahead.input-sm').siblings('input.tt-hint').addClass('hint-small');
	//$('.typeahead.input-lg').siblings('input.tt-hint').addClass('hint-large');

	$('.loading').hide();
	
	//$('.navbar a[href!=#], #filtr .checkbox.list-group-item > a[href!=#], .breadcrumb a[href!=#]').click(function() {showPreloader()});
	$('#filtr').submit(function() {showPreloader()});
	
	function showPreloader(){
		$('.loading').show();					
	}
	$('.loading').hide();	
</script>
</body>

</html>
<?php
	$db = NULL; /* ukončení konexe */
?>