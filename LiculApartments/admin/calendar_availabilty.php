<?php
include ("access.php");
include ("header.php");
include("../includes/conf.class.php");
$rtype_1=mysql_query("select * from bsi_roomtype");
$select_rtype="<select name=\"roomtype\" id=\"roomtype\"><option value='0' selected>Select RoomType</option>";
while($row_rtype=mysql_fetch_array($rtype_1)){
	$select_rtype.='<option value="'.$row_rtype[0].'">'.$row_rtype[1].'</option>';
}
$select_rtype.="</select>";
?>
<link rel="stylesheet" type="text/css" href="super_calendar_style.css" />
<script type="text/javascript" src="super_calendar.js"></script>
<script type="text/javascript" src="../scripts/jquery-1.2.6.min.js"></script>
<script type="text/javascript" charset="utf-8">
$(document).ready(function() {
	$("#tblblockdisplay").hide();
	$("#frame_block").hide();
	$("#calback").hide();
	$("#rnumber").attr("disabled", "disabled");
	$('#roomtype').change(function() {    
		 if($('#roomtype').val() != 0){
			var querystr = 'actioncode=3&roomtype_id='+$('#roomtype').val(); 	
			$.post("admin_ajax_processor.php", querystr, function(data){	
				if(data.errorcode == 0){
					$("#rnumber").attr("disabled", "");
					$('#rnumber').children().remove();		
					$(data.strhtml).appendTo("#rnumber");	 
				}else{
					alert(data.strmsg);
				}				
			}, "json");			
		} else {
			$('#select_room_numbers').html("<select name=\"rnumber\" id=\"rnumber\"><option value=\"0\">Select RoomNumber</option></select>")
			$("#rnumber").attr("disabled", "disabled");			
		}
		$("#calback").hide();
		$("#frame_block").hide();
		$("#tblblockdisplay").hide();
	});	
		
	$('#rnumber').change(function() {
		var roomid=$('#rnumber').val();		
		if($('#rnumber').val() != 0){
			var querystr = 'actioncode=9&block=0&roomtypeid='+$('#roomtype').val()+'&roomid='+roomid;
			$.post("admin_ajax_processor.php", querystr, function(data){				
				$("#tblblockdisplay").show();
				$("#tblblockdisplay").html(data.tbldata);								
			}, "json");									
			navigate('','','', roomid);
			$("#calback").show();
			$("#frame_block").show();
		}else{
			$("#tblblockdisplay").hide();
			$("#calback").hide();
			$("#frame_block").hide();			
		}
	});	
	
	$('#blockbttn').click(function() { 		
		var roomid = $('#rnumber').val();
		var querystr = 'actioncode=9&block=1&roomtypeid='+$('#roomtype').val()+'&roomid='+roomid+'&blockfrom='+$('#start-date').val()+'&blockto='+$('#end-date').val(); 		
		$.post("admin_ajax_processor.php", querystr, function(data){				
			if(data.errorcode == 0){					
				alert(data.strmsg);
				$("#tblblockdisplay").show();
				$("#tblblockdisplay").html(data.tbldata);	
				navigate('','','', roomid);
				$("#calback").show();
				$("#frame_block").show();
			}else{
				alert(data.strmsg);
				$("#tblblockdisplay").show();
				$("#tblblockdisplay").html(data.tbldata);
			}				
		}, "json");				
	});	
});

function unblockRoom(bookingid, roomtypeid, roomid){
	var querystr = 'actioncode=10&bookingid='+bookingid+'&roomtypeid='+roomtypeid+'&roomid='+roomid; 		
	$.post("admin_ajax_processor.php", querystr, function(data){				
		if(data.errorcode == 0){
			$("#tblblockdisplay").show();
			$("#tblblockdisplay").html(data.tbldata);	
			navigate('','','', roomid);
			$("#calback").show();
			$("#frame_block").show();
		}else{
			alert(data.strmsg);
			$("#tblblockdisplay").show();
			$("#tblblockdisplay").html(data.tbldata);
		}				
	}, "json");		
}

</script>

<!-- jquery.datePicker.js -->
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
$(function(){	
	$('.date-pick').datePicker()
	$('#start-date').bind('dpClosed', function(e, selectedDates){
		var d = selectedDates[0];
		if (d) {
			d = new Date(d);
			$('#end-date').dpSetStartDate(d.addDays(1).asString());
		}
	});
	$('#end-date').bind('dpClosed', function(e, selectedDates){
		var d = selectedDates[0];
		if (d) {
			d = new Date(d);
			$('#start-date').dpSetEndDate(d.addDays(-1).asString());
		}
	});
});
</script>
</td>
</tr>

<tr>
  <td height="400" valign="top" align="left" style="padding-left:20px;"><table  border="0" cellspacing="0" cellpadding="5">
      <tr>
        <td colspan="4" style="font-family:Arial, Helvetica, sans-serif; font-size:17px; font-weight:bold;" align="center">Calendar View of Room Availability and Blocking</td>
      </tr>
      <tr>
        <td colspan="4" height="15"></td>
      </tr>
      <tr>
        <td class="TitleBlue11pt">Room Type:</td>
        <td><?=$select_rtype?></td>
        <td class="TitleBlue11pt">Room No:</td>
        <td><select name="rnumber" id="rnumber" style="width:150px;">
            <option value="0">Select RoomNumber</option>
          </select></td>
      </tr>
      <tr>
        <td colspan="4" height="6"></td>
      </tr>
      <tr>
        <td colspan="2" valign="top"><div id="calback"><div id="calendar"></div></div></td>
        <td colspan="2" valign="top"><table cellpadding="3" cellspacing="0" border="0" style="border:solid #666666 1px;" id="tblblockdisplay">
            <tr>
              <td class="TitleBlue11pt">Block ID</td>
              <td class="TitleBlue11pt">Start Date</td>
              <td class="TitleBlue11pt">End Date</td>
              <td></td>
            </tr>
            <tr>
              <td class="bodytext" colspan="4">Please select Room Type and Room No.</td>
            </tr>
          </table></td>
      </tr>
      <tr >
        <td colspan="2" align="center" id="frame_block"><table cellpadding="2" cellspacing="0" border="0">
            <tr>
              <td class="TitleBlue11pt">Block This Room :</td>
              <td><input name="startdate" id="start-date" class="date-pick"  readonly="readonly"></td>
              <td class="TitleBlue11pt">To </td>
              <td><input name="closingdate" id="end-date" class="date-pick"  readonly="readonly">
                &nbsp;&nbsp;
                <input type="button" value="Block" id="blockbttn" name="blockbttn" /></td>
            </tr>
          </table></td>
        <td colspan="2"></td>
      </tr>
    </table></td>
</tr>
<?php include("footer.php"); ?>
</table>
</body></html>