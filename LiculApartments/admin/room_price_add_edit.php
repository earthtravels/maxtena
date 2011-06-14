<?php
// TODO: Uncomment
include ("access.php");
include_once ("../includes/SystemConfiguration.class.php");

global $systemConfiguration;
global $logger;

$errors = array();
$message = "";

$roomPricePlan = new RoomPricePlan();
if(isset($_POST['SBMT_REG']))
{
	$logger->LogInfo("Form has been submitted.");
	$roomPricePlan = RoomPricePlan::fetchFromParameters($_POST);
	if (!$roomPricePlan->save())
	{
		$logger->LogError("Error saving room price plan.");
		foreach ($roomPricePlan->errors as $error) 
		{
			$logger->LogError($error);
			$errors[] = $error;
		}
	}	
	else
	{
		header("Location: room_price_list.php");
		$message = "Values were updated successfully!";
	}
}
else if (isset($_REQUEST['id']) && is_numeric($_REQUEST['id']))
{
	$logger->LogInfo("Page was called for edit of id: " . $_REQUEST['id']);
	$id = intval($_REQUEST['id']);
	$logger->LogDebug("Numeric id is: $id");
	$roomPricePlan = RoomPricePlan::fetchFromDb($id);
	if ($roomPricePlan == null)
	{
		$logger->LogError("Invalid request. No room price plan with id: $id exists.");
		$errors[] = "Invalid request. No room price plan with id: $id exists.";
	}
}

include ("header.php");
?>
</td>
</tr>

<tr>
  <td valign="top">
  	<?php
	if (sizeof($errors) > 0)
	{
		echo '			<table width="100%">' . "\n";
		foreach ($errors as $error) 
		{
			echo '				<tr><td class="TitleBlue11pt" style="color: red; font-weight: bold;">' . htmlentities($error) . '</td></tr>' . "\n";
		}
		echo '			</table>' . "\n";
	}
	else if ($message != "")
	{
		echo '			<table width="100%">' . "\n";
		echo '				<tr><td class="TitleBlue11pt" align="center" style="color: green; font-weight: bold;">' . htmlentities($message) . '</td></tr>' . "\n";
		echo '			</table>' . "\n";
	}
	?>	
	<form name="room_price_add_edit" action="<?=$_SERVER['PHP_SELF']?>"
		method="post" onsubmit="return validatePricePlan();">	
		<fieldset>	
		    <legend class="TitleBlue11pt">Room/Apartment Price Plan</legend>
		    <table width="100%" cellspacing="1" border="0" cellpadding="3">	                                        
		        <input type="hidden" name="id" value="<?= $roomPricePlan->id ?>" />
		        <tr>
					<td class="TitleBlue11pt">Room/Apartment</td>
                    <td>
                    	<select name="room_id">                    
		                <?php
		                	$allRooms = Room::fetchAllFromDb();
		                	foreach ($allRooms as $room) 
		                	{
		                		echo '<option value="'. $room->id  . '"' . ($roomPricePlan->roomId == $room->id ? ' selected' : '') . '>' . $room->roomName . ' (' . $room->roomNumber . ')</option>';
		                	}												
						?>
                		</select>
                	</td>                    
				</tr>
				<tr>
                	<td class="TitleBlue11pt">Start Date</td>
                    <td>
                    	<input name="start_date" id="start-date" class="date-pick" value="<?= $roomPricePlan->startDate->format("m/d/Y") ?>" readonly="readonly">
                    </td>
                </tr>
                <tr>
                	<td class="TitleBlue11pt">End Date</td>
                    <td>
                    	<input name="end_date" id="end-date" class="date-pick" value="<?= $roomPricePlan->endDate->format("m/d/Y") ?>" readonly="readonly">
                    </td>
                </tr>                
				<tr>
					<td class="TitleBlue11pt">Price</td>
					<td><input type="text" name="price" value="<?= $roomPricePlan->price ?>" /></td>
				</tr>
                <tr>
					<td class="TitleBlue11pt">Extra Bed Price</td>
					<td><input type="text" name="extrabed" value="<?= $roomPricePlan->extraBedPrice ?>" /></td>
				</tr>			                                            
			</table>		
		</fieldset>
		<table width="100%">
		<tr class="TitleBlue11pt">
		            <td height="20" align="center">
		                <input type="image" value="1" src="images/button_save.png"  name='SBMT_REG' >
		            </td>
		        </tr>
		</table>
	</form>
  </td>
</tr>
<?php include("footer.php"); ?>
</table>
<br />
</body>
<script type="text/javascript" src="../scripts/jquery-1.2.6.min.js"></script>
<script type="text/javascript" src="../scripts/date.js"></script>
<script type="text/javascript" src="../scripts/jquery.datePicker.js"></script>
<!-- datePicker required styles -->
<link rel="stylesheet" type="text/css" media="screen" href="../css/datePicker.css">
<!-- page specific styles -->
<link rel="stylesheet" type="text/css" media="screen" href="../css/date.css">
<!-- page specific scripts -->
<script type="text/javascript" charset="utf-8">
Date.firstDayOfWeek = 0;
Date.format = 'mm/dd/yyyy';
$(function()
{	
	$('.date-pick').datePicker({startDate:'01/01/1996'});
	$('#start-date').bind(
		'dpClosed',
		function(e, selectedDates)
		{
			var d = selectedDates[0];
			if (d) {
				d = new Date(d);
				$('#end-date').dpSetStartDate(d.addDays(1).asString());
			}
		}
	);
	$('#end-date').bind(
		'dpClosed',
		function(e, selectedDates)
		{
			var d = selectedDates[0];
			if (d) {
				d = new Date(d);
				$('#start-date').dpSetEndDate(d.addDays(-1).asString());
			}
		}
	);
});
</script>

<script type="text/javascript">
String.prototype.mytrim = function() { return this.replace(/^\s\s*/, '').replace(/\s\s*$/, '') };

function validatePricePlan(){
	if(document.room_price_add_edit.start_date.value.mytrim().length == 0){
		alert('Start date cannot be blank!');
		return false;
	}
	if(document.room_price_add_edit.end_date.value.mytrim().length == 0){
		alert('End date cannot be blank!');
		return false;
	}	
	if(document.room_price_add_edit.price.value.mytrim().length == 0 || isNaN(document.room_price_add_edit.price.value)){
		alert('Price cannot be blank and should be a number/decimal!');
		return false;
	}
	if(document.room_price_add_edit.extrabed.value.mytrim().length == 0 || isNaN(document.room_price_add_edit.extrabed.value)){
		alert('Bed price cannot be blank and should be a number/decimal!');
		return false;
	}
}
</script>
</html>