<?php
session_start();
include("includes/db.conn.php");
include("includes/language.php");
include("includes/conf.class.php");
if(isset($_SERVER['HTTP_REFERER'])){
if($_SERVER['HTTP_REFERER'] == $bsiCore->getweburl()."index.php" || $_SERVER['HTTP_REFERER'] == $bsiCore->getweburl()){ }else{
header('Location: booking-failure.php?error_code=9'); } }else{ header('Location: booking-failure.php?error_code=9'); }
$_SESSION['auth']=$bsiCore->config['conf_license_key'];
date_default_timezone_set($bsiCore->config['conf_hotel_timezone']);
include("includes/search.class.php");
$bsisearch = new bsiSearch();
$bsiCore->clearExpiredBookings();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:<?=HTML_PARAMS?>>
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
<!-- Pull in and set up the JFLOW functionality -->
<script type="text/javascript" src="scripts/jquery-1.2.6.min.js"></script>
<!-- Pull in and set up the DROPDOWN functionality -->
<script type="text/javascript" src="scripts/hoverIntent.js"></script>
<script type="text/javascript" src="scripts/superfish.js"></script>
<script type="text/javascript"> 
$(document).ready(function(){ 
	$("ul.sf-menu").superfish(); 
}); 
</script>
<script type="text/javascript" src="scripts/hotelvalidation.js"></script>
</head>
<body>
<div id="content">
  <?php include("header.php"); ?>
  <div id="main-content">
    <div class="right">
      <h2><em><strong>
        <?=LEFT_ONLINE_BOOKING?>
        </strong></em></h2>
      <table cellpadding="3" style="font-size:13px">
        <tr>
          <td><?=LEFT_CHECK_IN_DT?>
            : </td>
          <td><?=$bsisearch->checkInDate?></td>
        </tr>
        <tr>
          <td><?=LEFT_CHECK_OUT_DT?>
            : </td>
          <td><?=$bsisearch->checkOutDate?></td>
        </tr>
        <tr>
          <td><?=LEFT_CAPACITY?>
            : </td>
          <td style="font-size:18px"><?=$bsisearch->guestsPerRoom?></td>
        </tr>
        <?php if($bsisearch->childPerRoom > 0){ ?>
        <tr>
          <td><?=LEFT_CHILD_PER_ROOM?> :</td>
          <td style="font-size:18px"><?=$bsisearch->childPerRoom ?></td>
        </tr>
        <?php } ?>
        <tr>
          <td><?=LEFT_TOTAL_NIGHT?>
            :</td>
          <td style="font-size:18px"><?=$bsisearch->nightCount?></td>
        </tr>         
        <?php if($bsisearch->extrabedPerRoom){ ?>
        <tr>
          <td><?=LEFT_EXTRA_BED_NEED?>
            </td>
          <td style="font-size:18px"><?=SEARCH_EXTRA_BED_YES?></td>
        </tr>
        <?php } ?> 
      </table>
    </div>
    <div class="left">
      <h2>
        <?=SEARCH_TITLE?>
      </h2>
      <br />
      <form name="searchresult" id="searchresult" method="post" action="booking-details.php" onsubmit="return validateSearchResultForm('<?=INDEX_JAVASCRIPT_BOOKING_SEARCH?>');">
      	<input type="hidden" name="allowlang" id="allowlang" value="no" />     
        <?php
		$gotSearchResult = false;
		$idgenrator = 0;
		foreach($bsisearch->roomType as $room_type){
			foreach($bsisearch->multiCapacity as $capid=>$capvalues){			
				$room_result = $bsisearch->getAvailableRooms($room_type['rtid'], $room_type['rtname'], $capid);
				if(intval($room_result['roomcnt']) > 0) {
					$gotSearchResult = true;							
					echo '<table cellpadding="1" cellspacing="0" border="0" width="100%" bgcolor="#c7b998">';
					echo '<tr><td width="100%"><b>&nbsp;'.$room_type['rtname'].'</b> ('.$capvalues['captitle'].')</td></tr>';
					echo '<tr><td width="100%" valign="top" style="font-size:11px">';					
					echo '<table cellpadding="1" cellspacing="2" border="0" width="100%" bgcolor="#FFFFFF">';					

					//guest per room
					echo '<tr><td bgcolor="#ece7db"><strong>'.LEFT_CAPACITY.'</strong></td><td bgcolor="#ece7db">'.$bsisearch->guestsPerRoom.' '.SEARCH_ADULT;
					if($bsisearch->childPerRoom > 0){
						echo '+ '.$bsisearch->childPerRoom.'Child';
					}
					
					echo '</td>';
					
					//available rooms
					echo '<td bgcolor="#ece7db"><strong>'.SEARCH_AVAILABLE_ROOM.'</strong></td><td bgcolor="#ece7db"><select name="svars_selectedrooms[]" style="width:70px;">'.$room_result['roomdropdown'].'</select><input type="hidden" id="extrabed'.$idgenrator.'" name="svars_extrabed[]" value="no" />';					
					echo '</td></tr>';	
					
					//total price + price details
					echo'<tr><td bgcolor="#ece7db"><strong>'.SEARCH_TOTAL_PRICE.'</strong></td><td bgcolor="#ece7db">'.$bsiCore->config['conf_currency_symbol'].number_format($room_result['totalprice'], 2 , '.', ',').'</td><td bgcolor="#ece7db" colspan="2" align="left"><table align="left">'.$room_result['pricedetails'].' </table></td></tr>';			
					
					//extrabed details									
					if($bsisearch->extrabedPerRoom){
						echo '<tr><td bgcolor="#ece7db"><strong>'.SEARCH_EXTRA_BED.'</strong></td><td bgcolor="#ece7db" colspan="3"><input type="checkbox" name="checkbox_extrabed_'.$idgenrator.'" value="yes" onclick="javascript:setExtraBed(this,\'extrabed'.$idgenrator.'\')" />'.SEARCH_ADDITIONAL_COST.' '.$bsiCore->config['conf_currency_symbol'].number_format($room_result['extraprice'], 2 , '.', ',').'</td></tr>';
					}
											
					echo '</table>';					
					echo '</td></tr></table><br>'; //$this->childPerRoom
					$idgenrator++;
				}//END room result 
			}//END multi capacity 
		}//END roomType foreach		
		
		
		if($gotSearchResult){
			$hotelextras = $bsisearch->getHotelExtras();	
			if(count($hotelextras) > 0){	
				echo '<table cellpadding="1" cellspacing="0" border="0" width="100%" bgcolor="#c7b998">';
				echo '<tr><td width="100%" align="left"><b>'.SEARCH_EXTRAS_TILTE.'</b></td></tr>';
				echo '<tr><td width="100%" valign="top" style="font-size:11px">';					
				echo '<table cellpadding="1" cellspacing="2" border="0" width="100%" bgcolor="#FFFFFF">';					
				echo '<tr><td bgcolor="#ece7db"><strong>'.SEARCH_EXTRAS_SERVICES.'</strong></td><td bgcolor="#ece7db"><strong>'.SEARCH_EXTRAS_PRICE.'</strong></td><td bgcolor="#ece7db"><strong>'.SEARCH_EXTRAS_REQUIRED.'</strong></td></tr>';			
				foreach($hotelextras as $hextra){
					echo '<tr><td bgcolor="#ece7db">'.$hextra['description'].'</td><td bgcolor="#ece7db">'.$bsiCore->config['conf_currency_symbol'].number_format($hextra['price'], 2 , '.', ',').'</td><td bgcolor="#ece7db" >
					
					<select name="extraservices['.$hextra['extraid'].']"><option value="0">'.SEARCH_EXTRAS_NO.'</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option></select></td></tr>';					
				}
				echo '</table>';					
				echo '</td></tr></table><br>'; 
			}
			
			//Code for Book Now button
			echo '<table cellpadding="5" cellspacing="0" border="0" width="100%" >';
			echo '<tr><td align="right" style="padding-right:37px;"><input type="submit" name="submit" value="'.SEARCH_BOOK_NOW_BTN.'" class="button2" /></td></tr>';
			echo '</table>';				
		} else{			
			echo '<table cellpadding="4" cellspacing="0" border="1" width="100%"><tbody><tr><td style="font-size:13px; color:#F00;" align="center"><br /><br />';
			if($bsisearch->searchCode == "SEARCH_ENGINE_TURN_OFF"){
				echo SEARCH_BOOKING_TURN_OFF;				
			}else if($bsisearch->searchCode == "OUT_BEFORE_IN"){
				echo SEARCH_INVALID_INPUT;				
			}else if($bsisearch->searchCode == "NOT_MINNIMUM_NIGHT"){
				echo SEARCH_MIN_NIGHT_PART1.' '.$bsiCore->config['conf_min_night_booking'].' '. SEARCH_MIN_NIGHT_PART2;
			}else if($bsisearch->searchCode == "TIME_ZONE_MISMATCH"){
				$tempdate = date("l F j, Y G:i:s T");
				echo SEARCH_TIMEZONE_PART1.$bsisearch->checkInDate.'. '.SEARCH_TIMEZONE_PART1.' '.$tempdate; 
			}else{
				echo SEARCH_NOT_AVAILABLE;
			}
			echo '<br /><br /><br /></td></tr></tbody></table>';
		}
		?>
      </form>
    </div>
    <div class="clear"></div>
  </div>
</div>
<!-- END content -->
<?php include("footer.php"); ?>
</body>
</html>