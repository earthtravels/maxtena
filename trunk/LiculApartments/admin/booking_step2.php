<?php
// TODO: Uncomment
include ("access.php");
require_once ("../includes/SystemConfiguration.class.php");
session_start();

global $systemConfiguration;
global $logger;

$bookingDetails = new BookingDetails();
$searchCriteria = SearchCriteria::fetchFromParameters($_POST);
if(!$searchCriteria->isValid())
{
	$_SESSION['errors'] = $searchCriteria->errors;
	header ('Location: error.php');	
}
else if (!$systemConfiguration->isSearchEgineEnabled())
{
	$_SESSION['errors'] = array( 0 => BOOKING_SEARCH_DISABLED);
	header ('Location: error.php');
}
else 
{	
	$bookingDetails->searchCriteria = $searchCriteria;
	$bookingDetailsSerialized = serialize($bookingDetails);
	$_SESSION['bookingDetailsAdmin'] = $bookingDetailsSerialized;		
}

include ("header.php");
?>

<!-- jquery.datePicker.js -->
<script type="text/javascript" src="../js/jquery-1.2.6.min.js"></script>
<script type="text/javascript" src="../js/date.js"></script>
<script type="text/javascript" src="../js/jquery.datePicker.js"></script>

</td>
  </tr> 
  
  <tr>
    <td valign="top" >    
    <fieldset>
	    <legend class="TitleBlue11pt">Search Results</legend>
    	<table cellspacing="1" border="0" cellpadding="3" class="TitleBlue11pt" width="100%">
	    	<tr>
	    		<td width="33%">Check In</td>
			    <td colspan="2"><?= $searchCriteria->checkInDate->format("m/d/Y") ?></td>
			</tr>
			<tr>                                        
			    <td>Check Out</td>
			    <td colspan="2"><?= $searchCriteria->checkOutDate->format("m/d/Y") ?></td>
			</tr>
			<tr>                                            
			    <td>Number of Nights</td>
			    <td colspan="2"><?= $searchCriteria->getNightCount() ?></td>
			</tr>
			<tr>                                            
			    <td>Adults</td>
			    <td colspan="2"><?= $searchCriteria->adultsCount ?></td>
			</tr>
			<tr>                                            
    		    <td>Children</td>
			    <td colspan="2"><?= $searchCriteria->childrenCount ?></td>
			</tr>
			<tr><td colspan="3">&nbsp;</td></tr>
			<?php 
			$searchEngine = new SearchEngine($searchCriteria);	
			$matchingRooms = $searchEngine->runSearch();
			if (sizeof($matchingRooms) == 0)
			{
			?>
				<tr><td colspan="3" align="center"><font color="red"><b>There are no rooms/apartments matching your criteria.</b></font></td></tr>
			<?php
			}
			else 
			{
				foreach ($matchingRooms as $room) 
				{
					if (!($room instanceof Room))
					{
						continue;
					}
			?>
					<tr>                                            
		    		    <td><?= $room->roomName . " #" . $room->roomNumber ?></td>
					    <td width="33%">
					    	<?= $systemConfiguration->formatCurrency($room->getRoomPrice($searchCriteria->checkInDate, $searchCriteria->checkOutDate)) ?>
				    	</td>
				    	<td>					    	
					    	<form name="book_room" action="booking_step3.php" method="post">
					    		<input type="hidden" name="roomId" value="<?= $room->id ?>" />
					    		<input type="image" value="1" src="images/button_booknow.png"  name='SBMT_FORM' />
					    	</form>
					    </td>
					</tr>
			<?php
				}
			} 
			?>						
    	</table>			
    </fieldset>   
    </td>
  </tr>
  <?php include("footer.php"); ?>
</table>
</body>
</html>
