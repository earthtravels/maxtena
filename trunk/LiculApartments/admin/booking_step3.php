<?php
// TODO: Uncomment
include ("access.php");
require_once ("../includes/SystemConfiguration.class.php");
session_start();
global $systemConfiguration;
global $logger;


if (!isset($_SESSION['bookingDetailsAdmin']))
{
	$_SESSION['errors'] = array (0 => "Invalid request: could not find search data in session");
	header ("Location: error.php");
}

// Get booking details from session
$bookingDetails = unserialize($_SESSION['bookingDetailsAdmin']);

// Get selected room
if (!isset($_POST['roomId']) || !is_numeric($_POST['roomId']))
{
    $_SESSION['errors'] = array (0 => "Invalid request: could not find selected room to book");
    header ("Location: booking-failure.php");
}

$roomId = intval($_POST['roomId']);
$selectedRoom = Room::fetchFromDb($roomId);
if ($selectedRoom == null)
{
    $_SESSION['errors'] = array (0 => "Invalid request: there is no room/apartment with id $roomId");
    header ("Location: error.php");
}
$bookingDetails->room = $selectedRoom;

// Save booking details to session
$bookingDetailsSerialized = serialize($bookingDetails);
$_SESSION['bookingDetailsAdmin'] = $bookingDetailsSerialized;

// Get all extra services
$extraServices = ExtraService::fetchAllFromDb();

// If there are no extra bed option or other services available, skip
if (!$bookingDetails->room->hasExtraBed == 0 && sizeof($extraServices) == 0)
{
	header ("Location booking_step4.php");
} 

$logger->LogDebug("booking-services date:");
$logger->LogDebug($bookingDetails->searchCriteria->checkInDate->format("Y-m-d"));
$language_selected="en";

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
	    <legend class="TitleBlue11pt">Additional Services</legend>
	    <form name="book_services" method="post" action="booking_step4.php"> 
	    	<table cellspacing="1" border="0" cellpadding="3" class="TitleBlue11pt" width="100%">
		    	<tr>
		    		<td width="25%">Check In</td>
				    <td width="25%"><?= $bookingDetails->searchCriteria->checkInDate->format("m/d/Y") ?></td>
				    <td>&nbsp;</td>
				</tr>
				<tr>                                        
				    <td>Check Out</td>
				    <td><?= $bookingDetails->searchCriteria->checkOutDate->format("m/d/Y") ?></td>
				    <td>&nbsp;</td>
				</tr>
				<tr>                                            
				    <td>Number of Nights</td>
				    <td><?= $bookingDetails->searchCriteria->getNightCount() ?></td>
				    <td>&nbsp;</td>
				</tr>
				<tr>                                            
				    <td>Adults</td>
				    <td><?= $bookingDetails->searchCriteria->adultsCount ?></td>
				    <td>&nbsp;</td>
				</tr>
				<tr>                                            
	    		    <td>Children</td>
				    <td><?= $bookingDetails->searchCriteria->childrenCount ?></td>
				    <td>&nbsp;</td>
				</tr>
				<tr><td colspan="3">&nbsp;</td></tr>
				<?php
				if ($bookingDetails->room->hasExtraBed)
				{
				    $bedPrice = $bookingDetails->room->getBedPrice($bookingDetails->searchCriteria->checkInDate, $bookingDetails->searchCriteria->checkOutDate);
					echo '<tr>' . "\n";
					echo '	<td>Extra Bed</td>' . "\n";
					echo '	<td>' .$systemConfiguration->formatCurrency($bedPrice) . ' per stay</td>' . "\n";
					echo '	<td>' . "\n";
					echo '		<select name="extraBed" style="width: 50px;">' . "\n";
					echo '			<option value="0" selected>0</option>' . "\n";
					echo '			<option value="1">1</option>' . "\n";
					echo '		</select>' . "\n";
					echo '	</td>' . "\n";                                    		
					echo '</tr>' . "\n";
				} 
				
				$extraServices = ExtraService::fetchAllFromDb();
				if ($extraServices == null && sizeof(ExtraService::$staticErrors) > 0)
				{
					$_SESSION['errors'] = ExtraService::$staticErrors;
					header("Location: error.php");
				}
				foreach($extraServices as $extraService)
				{                                            	                                            	
				    if (!($extraService instanceof ExtraService))
				    {
				        continue;
				    }
				
					echo '<tr>' . "\n";
					echo '	<td >' . $extraService->getName($language_selected) . '</td>' . "\n";
					echo '	<td >' . $systemConfiguration->formatCurrency($extraService->price) . ' ';
					$maxNumberSelectable = 0;
					if ($extraService->isNightly)
					{
						echo 'per night</td>' . "\n";
						$maxNumberSelectable = $bookingDetails->searchCriteria->getNightCount();
					}
					else 
					{                                    			
						echo 'per stay</td>' . "\n";
						$maxNumberSelectable = $extraService->maxNumberAvailable;
					}                                    		
					echo '	<td>' . "\n";
					echo '		<select name="extraServices[' . $extraService->id . ']" style="width: 50px;">' . "\n";
					for ($i = 0; $i <= $maxNumberSelectable; $i++) 
					{
						if ($i == 0)
						{
							echo '			<option value="'. $i . '" selected>'. $i . '</option>' . "\n";
						}
						else
						{
							echo '			<option value="'. $i . '">'. $i . '</option>' . "\n";	
						}                                    			
					}                                    		
					echo '		</select>' . "\n";
					echo '	</td>' . "\n";                                    		
					echo '</tr>' . "\n";                                    		
				}
				?>	
				<tr>
    				<td align="center" colspan="3">
    					<br />
    					<input type="image" value="1" src="images/button_continue.png"  name='SBMT_FORM' />    					
    				</td>
    			</tr>					
	    	</table>
    	</form>			
    </fieldset>   
    </td>
  </tr>
  <?php include("footer.php"); ?>
</table>
</body>
</html>
