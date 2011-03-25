<?php
session_start();
include("includes/db.conn.php");
include("includes/language.php");
include("includes/conf.class.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:<?=HTML_PARAMS?> >
<head>
<title>
<?=$bsiCore->config['conf_hotel_sitetitle']?>
</title>
<meta name="description" content="<?=$bsiCore->config['conf_hotel_sitedesc']?>" />
<meta name="keywords" content="<?=$bsiCore->config['conf_hotel_sitekeywords']?>" />
<meta http-equiv="Content-Type" content="text/html;charset=<?=CHARSET?>" />
<meta name="robots" content="all" />
<link rel="stylesheet" type="text/css" href="css/main.css" />

<!-- Pull in the JQUERY library -->
<script type="text/javascript" src="scripts/jquery-1.2.6.min.js"></script>

<!-- Pull in and set up the DROPDOWN functionality -->
<script type="text/javascript" src="scripts/hoverIntent.js"></script>
<script type="text/javascript" src="scripts/superfish.js"></script>
<script type="text/javascript">  
$(document).ready(function(){ 
	$("ul.sf-menu").superfish(); 
});  
</script>
</head>

<body>

<!-- Centers the page -->
<div id="content">
  <?php include("header.php"); ?>
  <div id="main-content" class="subpage">
    <div class="left1">
      <h2><?=TARIFF_TITLE?></h2>
      <br />
      <?php
	require_once("includes/bsiutil.class.php");
	$bsiTariff = new bsiRoomTariff();	
	$showextrabed = 1;
	foreach($bsiTariff->roomTypes as $rmid => $rmtype){
		echo '<fieldset><legend style="font-weight:bold;">'.$rmtype.'</legend>';
		
		$priceplans = $bsiTariff->loadPricePaln($rmid, 1); 	
		$rpcount = count($priceplans);	
		$valuehtml = '';
		$headerhtml = '';
		
		echo '<table cellpadding="0" cellspacing="0" border="0" width="100%" style="font-size:12px; background:#ffffff;"><tr><td bgcolor="#c7b998" style="font-weight:bold;">'.TARIFF_REGULAR_PRICE.'</td></tr>';
		echo '<tr><td><table cellpadding="2" cellspacing="1" border="0" width="100%"><tr>';
		for($i = 0; $i < $rpcount; $i++){					
				$headerhtml.= '<td bgcolor="#ece7db">'.$bsiTariff->capacities[$priceplans[$i]["capacity"]]["title"].' ('.$bsiTariff->capacities[$priceplans[$i]["capacity"]]["capacity"].')</td>';
				$valuehtml.= '<td bgcolor="#ece7db">'.$bsiCore->config['conf_currency_symbol'].$priceplans[$i]["price"].'</td>';
				if ($i+1 == $rpcount){
					if($priceplans[$i]["extrabed"] == 0){ $showextrabed = 0;}
					if($showextrabed){
						$headerhtml.= '<td bgcolor="#ece7db">Extra Bed/Room</td></tr>';
						$valuehtml.= '<td bgcolor="#ece7db">'.$bsiCore->config['conf_currency_symbol'].$priceplans[$i]["extrabed"].'</td></tr>';
					}else{
						$headerhtml.= '</tr>';
						$valuehtml.= '</tr>';
					}
				}
		}	
		echo $headerhtml.$valuehtml.'</table></td></tr></table>';
		unset($priceplans);
		
		$priceplans = $bsiTariff->loadPricePaln($rmid, 0); 	
		$opcount = count($priceplans);
		
		echo '';
		$pheaderhtml = '';
		$startdateold = '';
		for($i = 0; $i < $opcount; $i++){
				
				if($startdateold != $priceplans[$i]["startdate"]){	
					$startdateold = $priceplans[$i]["startdate"];
					
					echo '<table cellpadding="0" cellspacing="0" border="0" width="100%" style="font-size:12px; background:#ffffff;">
					<tr><td bgcolor="#c7b998" style="font-weight:bold;">'.TARIFF_OFFERS_PRICE.' ['.TARIFF_FROM.': '.$priceplans[$i]["startdate"].' '.TARIFF_TO.': '.$priceplans[$i]["enddate"].']</td><tr>';
					echo '<tr><td>
					<table cellpadding="2" cellspacing="1" border="0" width="100%"><tr>'.$headerhtml.'</tr><tr>';
				}	
				echo '<td bgcolor="#ece7db">'.$bsiCore->config['conf_currency_symbol'].$priceplans[$i]["price"].'</td>';
				if (($i+1)%$rpcount == 0){
					if($showextrabed){
						echo '<td bgcolor="#ece7db">'.$bsiCore->config['conf_currency_symbol'].$priceplans[$i]["extrabed"].'</td></tr></table></td></tr></table>';
					}else{
						echo '</tr></table></td></tr></table>';
					}
					
					
				}
		}			
		echo '</fieldset><br />';	
	}
	?>
    </div>
    <div class="clear"></div>
  </div>
</div>
<!-- END content -->

<?php include("footer.php"); ?>
</body>
</html>