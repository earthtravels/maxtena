<?php
include ("access.php");
include ("header.php");
include("../includes/conf.class.php");
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
Date.format = '<?=$bsiCore->config['conf_dateformat']?>';
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
				$('#end-date').dpSetStartDate(d.addDays(<?=$bsiCore->config['conf_min_night_booking']?>).asString());
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
				$('#start-date').dpSetEndDate(d.addDays(-<?=$bsiCore->config['conf_min_night_booking']?>).asString());
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
  <td height="300" valign="middle" align="center"><h3><em><strong>Administrator Booking</strong></em></h3>
    <form name="rmsearch" action="admin.rmsearchresult.php" method="post" onsubmit="return validateRoomAddEit();">
      <table cellpadding="5" cellspacing="0" border="0" style="font-size:13px; border:solid #999999 2px;">
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
          <td>Adult per Room</td>
          <td><select name="capacity"  style="width:50px;">
              <?php
            	$capacity_sql = mysql_query("SELECT DISTINCT (capacity) FROM bsi_capacity WHERE `id` IN (SELECT DISTINCT (capacity_id) FROM bsi_room) ORDER BY capacity");
				while($capacityrow = mysql_fetch_assoc($capacity_sql)){ 
					echo '<option value="'.$capacityrow["capacity"].'">'.$capacityrow["capacity"].'</option>';
				}
				?>
            </select></td>
        </tr>
        
        <?php
		  $child_sql = mysql_query("SELECT DISTINCT (no_of_child) FROM bsi_room WHERE no_of_child > 0 ORDER BY no_of_child");
		  if(mysql_num_rows($child_sql)){         
		  ?>
           <tr>
            <td style="font-size:12px;">Child/Room</td>
            <td valign="top"><select name="childcount"  style="width:60px;">
            <option value="0" selected="selected">0</option>
                <?php 
				while($childyrow = mysql_fetch_assoc($child_sql)){ 
					echo '<option value="'.$childyrow["no_of_child"].'">'.$childyrow["no_of_child"].'</option>';
				}
				?>
              </select></td>
          </tr>
          <?php } //End of child 
		  $extrabed_sql = mysql_query("SELECT DISTINCT (extra_bed) FROM bsi_room WHERE extra_bed > 0 ");
		  if(mysql_num_rows($extrabed_sql)){         
		  ?>
           <tr>
            <td style="font-size:12px;">Need Extra Bed?</td>
            <td><input type="checkbox" name="extrabed"  value="YES"></td>
          </tr>
          <?php } //End of extra bed ?>
          
        <tr>
          <td></td>
          <td><input type="submit" value="SEARCH" class="button2" id="btn_room_search"/></td>
        </tr>
      </table>
    </form>
    <br />
    <br /></td>
</tr>
<?php include("footer.php"); ?>
</table>
</body></html>