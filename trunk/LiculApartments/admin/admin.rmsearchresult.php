<?php
include ("access.php");
include ("header.php");
include("../includes/language.admin.php");
include ('../includes/conf.class.php'); 
include ('../includes/search.class.php');
$bsisearch = new bsiSearch();
$bsiCore->clearExpiredBookings();
?>
<script type="text/javascript" src="../scripts/hotelvalidation.js"></script>
</td>
</tr>

<tr><br />
  <td height="400" valign="top" align="center"><h2><em><strong>Room Search Result</strong></em></h2>
    <div>      
      <br />
      <form name="adminsearchresult" id="adminsearchresult" method="post" action="admin.rmbookdetails.php" onsubmit="return validateSearchResultForm('<?=INDEX_JAVASCRIPT_BOOKING_SEARCH?>');">
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
					echo '<tr><td bgcolor="#ece7db"><strong>'.SEARCH_GUEST_PER_ROOM.'</strong></td><td bgcolor="#ece7db">'.$bsisearch->guestsPerRoom.' Adult ';
					if($bsisearch->childPerRoom > 0){
						echo '+ '.$bsisearch->childPerRoom.'Child';
					}
					
					echo '</td>';
					
					//available rooms
					echo '<td bgcolor="#ece7db"><strong>'.SEARCH_AVAILABLE_ROOM.'</strong></td><td bgcolor="#ece7db"><select name="svars_selectedrooms[]" style="width:70px;">'.$room_result['roomdropdown'].'</select><input type="hidden" id="extrabed'.$idgenrator.'" name="svars_extrabed[]" value="no" />';					
					echo '</td></tr>';	
					
					//total price + price details
					echo'<tr><td bgcolor="#ece7db"><strong>'.SEARCH_TOTAL_PRICE.'</strong></td><td bgcolor="#ece7db">'.$bsiCore->config['conf_currency_symbol'].number_format($room_result['totalprice'], 2 , '.', ',').'</td><td bgcolor="#ece7db" colspan="2"><strong>'.SEARCH_DETAILS_PRICE.':</strong><br><table align="center">'.$room_result['pricedetails'].' </table></td></tr>';			
					
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
				echo 'Sorry onlineline booking currently not available. Please try later.';				
			}else if($bsisearch->searchCode == "OUT_BEFORE_IN"){
				echo 'Sorry you have entered a invalid searching criteria. Please try with invalid searching criteria.';				
			}else if($bsisearch->searchCode == "NOT_MINNIMUM_NIGHT"){
				echo 'Minimum number of night should not be less than '.$bsiCore->config['conf_min_night_booking'].'. Please modify your searching criteria.';
			}else{
				echo 'Sorry no room available as your searching criteria. Please try with different date slot.';
			}
			echo '<br /><br /><br /></td></tr></tbody></table>';
		}
		?>
      </form>
    </div></td>
</tr>
<?php include("footer.php"); ?>
</table>
</body></html>