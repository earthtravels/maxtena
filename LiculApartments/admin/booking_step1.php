<?php
// TODO: Uncomment
include ("access.php");
require_once ("../includes/SystemConfiguration.class.php");
session_start();

global $systemConfiguration;
global $logger;

BookingDetails::clearExpiredBookings();

$maxCapacity = 0;
$sqlMaxCapacityQuery=mysql_query("select MAX(capacity) as capacity from bsi_rooms");
if (!$sqlMaxCapacityQuery)
{
	die ("Error: " . mysql_error());
}
if($row = mysql_fetch_assoc($sqlMaxCapacityQuery))
{
	$maxCapacity = intval($row['capacity']);	
}

include ("header.php");
?>
</td>
</tr>
<!-- jquery.datePicker.js -->
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
	
	$('.date-pick').datePicker()
	$('#start-date').bind(
		'dpClosed',
		function(e, selectedDates)
		{
			var d = selectedDates[0];
			if (d) {
				d = new Date(d);
				$('#end-date').dpSetStartDate(d.addDays(<?=$systemConfiguration->getMinimumNightCount()?>).asString());
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
				$('#start-date').dpSetEndDate(d.addDays(-<?=$systemConfiguration->getMinimumNightCount()?>).asString());
			}
		}
	);
});



String.prototype.mytrim = function() { return this.replace(/^\s\s*/, '').replace(/\s\s*$/, '') };

function validateRoomAddEit(){
	if(document.rmsearch.check_in.value.mytrim().length == 0){
		alert('Please select check-in date from calendar.');
		return false;
	}
	if(document.rmsearch.check_out.value.mytrim().length == 0){
		alert('Please select check-out date from calendar.');
		return false;
	}
	return true;
}

</script>

<tr>
  <td valign="middle" align="center">
  <form name="rmsearch" action="booking_step2.php" method="post" onsubmit="return validateRoomAddEit();">
  <fieldset>
	    <legend class="TitleBlue11pt">Administrator Booking</legend>    
      <table class="TitleBlue11pt">
        <tr>
          <td>Check In Date</td>
          <!--<td><input name="check_in" id="start-date" readonly="readonly" class="date-pick"   size="10"/></td>-->
          <td><input name="check_in" id="start-date" class="date-pick"   size="10"/></td>
        </tr>
        <tr>
          <td>Check Out Date</td>
          <!--<td><input name="check_out" id="end-date" readonly="readonly" class="date-pick" size="10" /></td>-->
          <td><input name="check_out" id="end-date" class="date-pick" size="10" /></td>
        </tr>
        <tr>
          <td>Adults</td>
          	<td>
	          	<select name="adults"  style="width:85px;">
	              	<?php
					for ($i = 1; $i <= $maxCapacity; $i++) 
					{
						echo "<option>". $i . "</option>\n";															
					} 
					?>
	            </select>
			</td>
        </tr>        
           <tr>
            <td>Children</td>
            <td valign="top">
            	<select name="children"  style="width:85px;">            
	              	<?php
					for ($i = 0; $i <= $maxCapacity; $i++) 
					{
						echo "<option>". $i . "</option>\n";															
					} 
					?>
              </select></td>
          </tr>            
        <tr>          
          <td colspan="2" align="center">
          	<br />
          	<input type="image" src="../images/button_search.png" />
          </td>
        </tr>
      </table>    
    </fieldset>      
    </form>
</td>
</tr>
<?php include("footer.php"); ?>
</table>
</body></html>