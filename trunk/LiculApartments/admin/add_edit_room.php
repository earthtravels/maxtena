<?php
include ("access.php");
if(isset($_REQUEST['act']))
{
	include("../includes/db.conn.php"); 
	include("../includes/conf.class.php");
	include("../includes/admin.class.php");
	$bsiAdminMain->room_add_edit();
}
include ("header.php");
include("../includes/conf.class.php");

?>
<script language="javascript">
String.prototype.mytrim = function() { return this.replace(/^\s\s*/, '').replace(/\s\s*$/, '') };

function validateRoomAddEit(){
	if(document.roomaddeit.roomno.value.mytrim().length == 0){
		alert('Room No cannot be blank!');
		return false;
	}
	return true;
}
</script>
<script type="text/javascript" src="../scripts/jquery-1.2.6.min.js"></script>
<script type="text/javascript" charset="utf-8">
$(document).ready(function() {
<?php if(!$_GET['id'])	{  ?>
$('input[type="submit"]').attr('disabled','disabled');
<?php } ?>

   $('#roomtype').click(function() { 
         if($('#roomtype').val() != 0){
			var querystr = 'actioncode=2&roomtype_id='+$('#roomtype').val(); 	
			//alert(querystr);
			$.post("admin_ajax_processor.php", querystr, function(data){						
				//alert("1");						 
				if(data.errorcode == 0){
					 $('#allocate_capacity').html(data.strhtml)
					 $('#extrabed_allowed').html(data.strhtml1)
					 $('input[type="submit"]').removeAttr('disabled');

				}else{
				    alert(data.strmsg);
				}
				
			}, "json");
			
		} else {
		 $('#allocate_capacity').html("<span style=\"font-family:Arial, Helvetica, sans-serif; font-size:10px;\">Please select RoomType</span>")
		  
		}
	});
});
</script>
	</td>
  </tr> 
  <tr>
    <td height="400" valign="top" align="left">
    <?php

$id=$bsiCore->ClearInput($_GET['id']);
if($id)	{
$row=mysql_fetch_assoc(mysql_query("select * from bsi_room where room_ID=$id"));

$row_extbed=mysql_fetch_assoc(mysql_query("SELECT distinct(`extrabed`) as extbed FROM `bsi_priceplan` WHERE `roomtype_id`=".$row['roomtype_id']." and `default_plan`=true"));
if($row_extbed['extbed'] !='0.00'){
$extrabed_check = ($row['extra_bed'] == true) ? '<input type="checkbox" name="extrabed" checked="checked"  />' : '<input type="checkbox" name="extrabed"  />';
}else{
$extrabed_check='<span style="font-family:Arial, Helvetica, sans-serif; font-size:10px;">NA(for active enter price in room type)</span>';
}
$room_edit_readonly='readonly="readonly"';
}else{
$row=NULL;
$room_edit_readonly='';
}


if($id) {
//$rtype_1=mysql_fetch_assoc(mysql_query("select type_name from bsi_roomtype where roomtype_ID=".$row['roomtype_id']));
$rtype=mysql_query("select * from bsi_roomtype");
$select_rtype="<select name=\"roomtype\" id=\"roomtype\">";
	while($row_rtype=mysql_fetch_array($rtype))
	{
	if($row_rtype[0]==$row['roomtype_id'])
	$select_rtype.='<option value="'.$row_rtype[0].'" selected>'.$row_rtype[1].'</option>';
	else
	$select_rtype.='<option value="'.$row_rtype[0].'">'.$row_rtype[1].'</option>';
	}
$select_rtype.="</select>";
//***************************
$capa=mysql_query("select * from bsi_priceplan where default_plan=true and roomtype_id=".$row['roomtype_id']);
$select_capa="<select name=\"roomcapid\">";
	while($row_capa=mysql_fetch_array($capa))
	{
	$row_capacity=mysql_fetch_assoc(mysql_query("select * from  bsi_capacity where id=".$row_capa['capacity_id']));
	if($row_capa['capacity_id']==$row['capacity_id'])
	$select_capa.='<option value="'.$row_capacity['id'].'" selected>'.$row_capacity['title'].' ('.$row_capacity['capacity'].')</option>';
	else
	$select_capa.='<option value="'.$row_capacity['id'].'">'.$row_capacity['title'].' ('.$row_capacity['capacity'].')</option>';
	}
 $select_capa.="</select>";

} else {
$rtype_1=mysql_query("select * from bsi_roomtype");
$select_rtype="<select name=\"roomtype\" id=\"roomtype\"><option value='0' selected>Select RoomType</option>";
	while($row_rtype=mysql_fetch_array($rtype_1))
	{
	$select_rtype.='<option value="'.$row_rtype[0].'">'.$row_rtype[1].'</option>';
	}
$select_rtype.="</select>";
//******************
$select_capa="<span style=\"font-family:Arial, Helvetica, sans-serif; font-size:10px;\">Please select RoomType</span>";
$extrabed_check="<span style=\"font-family:Arial, Helvetica, sans-serif; font-size:10px;\">Please select RoomType</span>";
}

?>
<form name="roomaddeit" action="<?=$_SERVER['PHP_SELF']?>?id=<?=$id?>&act=1" method="post" onsubmit="return validateRoomAddEit();">
<table cellpadding="0" cellspacing="0" border="0"  >
<tr><td align="center" style="font-size:14px; color:#006600; font-weight:bold"><? if(isset($updated_msg)) echo $updated_msg; ?></td></tr>
<tr><td style="font-size:14px; font-weight:bold; color:#718d9d">Room Add/Edit</td></tr>
<tr><td colspan="2" height="2" bgcolor="#718d9d"></td></tr>
<tr><td height="5"></td></tr>
<tr><td>
<table cellpadding="5" cellspacing="0" border="0"  style="border:solid #666666 1px; " >
<tr><td class="TitleBlue11pt">Room No#</td><td><input type="text" name="roomno" value="<?=$row['room_no']?>"  <?=$room_edit_readonly ?>/></td></tr>
<tr><td class="TitleBlue11pt">Room Type</td><td><?=$select_rtype?></td></tr>
<tr><td class="TitleBlue11pt">No of Adult</td><td id="allocate_capacity"><?=$select_capa?></td></tr>
<tr><td class="TitleBlue11pt">No of Child</td><td><input type="text" size="5" name="nochild" value="<?=$row['no_of_child']?>"/></td></tr>
<tr><td class="TitleBlue11pt">Extra One  Bed</td><td id="extrabed_allowed" ><?=$extrabed_check?></td></tr>
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
