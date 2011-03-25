<?php
include ("access.php");

if(isset($_REQUEST['act']))
{
	include("../includes/db.conn.php");
	include("../includes/conf.class.php");
	include("../includes/admin.class.php");
	$bsiAdminMain->priceplan_add_edit();
} 

include ("header.php");
include("../includes/conf.class.php");
?>

<?php $id=$bsiCore->ClearInput($_REQUEST['rtype']); ?>
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
<script language="javascript">
String.prototype.mytrim = function() { return this.replace(/^\s\s*/, '').replace(/\s\s*$/, '') };

function validatePricePlanAddEit(){
	if(document.priceplanaddeit.startdate.value.mytrim().length == 0){
		alert('Start Date cannot be blank!');
		return false;
	}
	if(document.priceplanaddeit.closingdate.value.mytrim().length == 0){
		alert('End Date cannot be blank!');
		return false;
	}	
<?php
$sql_pr1=mysql_query("select * from bsi_priceplan where roomtype_id=$id and start_date='".$bsiCore->ClearInput($_REQUEST['start_dt'])."'");
while($row_pr1=mysql_fetch_assoc($sql_pr1)){
$row_capacity1=mysql_fetch_assoc(mysql_query("select * from  bsi_capacity where id=".$row_pr1['capacity_id']));
?>	
	if(document.priceplanaddeit.<?=strtolower($row_capacity1['title'])?>.value.mytrim().length == 0 || isNaN(document.priceplanaddeit.<?=strtolower($row_capacity1['title'])?>.value)){
		alert('Price for <?=$row_capacity1['title']?> cannot be blank and should be a number/deimal!');
		return false;
	}
<?php } ?>
	if(document.priceplanaddeit.extrabed.value.mytrim().length == 0 || isNaN(document.priceplanaddeit.extrabed.value)){
		alert('Price for extrabed cannot be blank and should be a number/deimal!');
		return false;
	}
	
	return true;
}
</script>
<script type="text/javascript" charset="utf-8">
$(document).ready(function() {
   $('#roomtype').click(function() { 
         if($('#roomtype').val() != 0){
			var querystr = 'actioncode=1&roomtype_id='+$('#roomtype').val(); 	
			$.post("admin_ajax_processor.php", querystr, function(data){						
				//alert("1");						 
				if(data.errorcode == 0){
					 $('#default_capacity').html(data.strhtml)
				}else{
				    alert(data.strmsg);
				}
				
			}, "json");
			
		} else {
		 $('#default_capacity').html("<span style=\"font-family:Arial, Helvetica, sans-serif; font-size:10px;\">Please select RoomType<br>from dropdown</span>")
		}
	});
});
</script>
	</td>
  </tr> 
  <tr>
    <td height="400" valign="top" align="left">
    <?php
	
if($id)	
$row=mysql_fetch_assoc(mysql_query("SELECT  DATE_FORMAT(start_date, '".$bsiCore->userDateFormat."') AS start_date, DATE_FORMAT(end_date, '".$bsiCore->userDateFormat."') AS end_date, roomtype_id FROM `bsi_priceplan` where `roomtype_id`=".$id." and start_date='".$bsiCore->ClearInput($_REQUEST['start_dt'])."' group by `roomtype_id`,`start_date`"));
else
$row=NULL;


if($id) {
//echo "sss";
$rtype_1=mysql_fetch_assoc(mysql_query("select type_name from bsi_roomtype where roomtype_ID=".$row['roomtype_id']));
$select_rtype=$rtype_1['type_name'];
//***************************
} else {
$rtype_1=mysql_query("select * from bsi_roomtype");
$select_rtype="<select name=\"roomtype\" id=\"roomtype\"><option value='0' selected>Select RoomType</option>";
	while($row_rtype=mysql_fetch_array($rtype_1))
	{
	$select_rtype.='<option value="'.$row_rtype[0].'">'.$row_rtype[1].'</option>';
	}
$select_rtype.="</select>";

//echo $select_rtype;
//******************
}

?>
<form name="priceplanaddeit" action="<?=$_SERVER['PHP_SELF']?>?act=1" method="post" onsubmit="return validatePricePlanAddEit();">
<input type="hidden" name="roomtype_edit" value="<?=$id?>" />
<input type="hidden" name="start_date_old" value="<?=mysql_real_escape_string($_REQUEST['start_dt'])?>" />
<table cellpadding="0" cellspacing="0" border="0"  >
<tr><td align="center" style="font-size:14px; color:#006600; font-weight:bold"><? if(isset($error_msg)) echo $error_msg; ?></td></tr>
<tr><td style="font-size:14px; font-weight:bold; color:#718d9d">Price Plan Add/Edit</td></tr>
<tr><td colspan="2" height="2" bgcolor="#718d9d"></td></tr>
<tr><td height="5"></td></tr>
<tr><td>
<table cellpadding="5" cellspacing="0" border="0"  style="border:solid #666666 1px; " >

<tr><td class="TitleBlue11pt">Room Type</td><td class="bodytext"><b><?=$select_rtype?></b></td></tr>
<tr><td class="TitleBlue11pt">Start Date</td><td><input name="startdate" id="start-date" class="date-pick" value="<?=$row['start_date']?>" readonly="readonly"></td></tr>
<tr><td class="TitleBlue11pt">End Date</td><td><input name="closingdate" id="end-date" class="date-pick" value="<?=$row['end_date']?>" readonly="readonly"></td></tr>
<tr><td valign="top" class="TitleBlue11pt">Price</td><td  id="default_capacity">
<?php  if($id){ ?>
<input type="hidden" name="roomtype" value="<?=$id?>" />
    <table cellpadding="3" cellspacing="0" border="0" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
    <?php
	
	$sql_capacity=mysql_query("select * from  bsi_capacity");
	
	while($row_capacity=mysql_fetch_assoc($sql_capacity)){
	if($id)	{
	$sql_pr=mysql_query("select * from bsi_priceplan where roomtype_id=$id and start_date='".$bsiCore->ClearInput($_REQUEST['start_dt'])."' and capacity_id=".$row_capacity['id']."");
	if(mysql_num_rows($sql_pr)){
	$row12=mysql_fetch_assoc($sql_pr);
	
	?>
    <tr><td><?=$row_capacity['title'].' ('.$row_capacity['capacity'].')'?>:</td><td><?=$bsiCore->config['conf_currency_symbol']?><input type="text" name="<?=strtolower($row_capacity['title'])?>" size="5" value="<?=$row12['price']?>"/></td></tr>
    <?php 
	}
	}
	} 
	if($id)	{
	$row121=mysql_fetch_assoc(mysql_query("select * from bsi_priceplan where roomtype_id=$id and  start_date='".$bsiCore->ClearInput($_REQUEST['start_dt'])."' limit 1"));
	} else {
	$row121=NULL;
	}
	$extrabedprice_row=mysql_fetch_assoc(mysql_query("select extrabed from bsi_priceplan where roomtype_id=".$id." and default_plan=true group by roomtype_id"));
	if($extrabedprice_row['extrabed'] != "0.00"){
	?>
    <tr><td>Extra / Bed:</td><td><?=$bsiCore->config['conf_currency_symbol']?><input type="text" name="extrabed" size="5" value="<?=$row121['extrabed']?>"/></td></tr>
    <?php } ?>
    </table>
    <?php } else {?>
    <span style="font-family:Arial, Helvetica, sans-serif; font-size:10px;">Please select RoomType<br />from dropdown</span>
    <?php } ?>
</td></tr>
<tr><td height="5" colspan="2"></td></tr>
</table>
</td></tr>
<tr><td height="5"></td></tr>
<tr><td align="right"><input type="submit" value="Submit" /></td></tr>
</table>
</form>
    </td>
  </tr>
  <?php include("footer.php"); ?>
</table>


</body>
</html>
