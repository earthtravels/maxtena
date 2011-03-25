<?php
include ("access.php");
$id=$_REQUEST['id'];
	if(isset($_REQUEST['act']))
	{
	include("../includes/db.conn.php"); 
	include("../includes/conf.class.php");
	include("../includes/admin.class.php");
	$bsiAdminMain->roomtype_addedit();
	header("location:".$_POST['reffer_http']);
	}
include ("header.php");
include("../includes/conf.class.php");
?>

<script language="javascript">
String.prototype.mytrim = function() { return this.replace(/^\s\s*/, '').replace(/\s\s*$/, '') };

function validateRoomTypeAddEit(){
	if(document.roomtypeaddeit.roomtype.value.mytrim().length == 0){
		alert('Room Type Name cannot be blank!');
		return false;
	}
	if(document.roomtypeaddeit.single.value.mytrim().length == 0 || isNaN(document.roomtypeaddeit.single.value)){
		alert('Price for Single cannot be blank and should be a number/deimal!');
		return false;
	}
	if(document.roomtypeaddeit.double.value.mytrim().length == 0 || isNaN(document.roomtypeaddeit.double.value)){
		alert('Price for Double cannot be blank and should be a number/deimal!');
		return false;
	}
	if(document.roomtypeaddeit.triple.value.mytrim().length == 0 || isNaN(document.roomtypeaddeit.triple.value)){
		alert('Price for Triple cannot be blank and should be a number/deimal!');
		return false;
	}
	if(document.roomtypeaddeit.quad.value.mytrim().length == 0 || isNaN(document.roomtypeaddeit.quad.value)){
		alert('Price for Quad cannot be blank and should be a number/deimal!');
		return false;
	}

	return true;
}
</script>

	</td>
  </tr> 
  <tr>
    <td height="400" valign="top" align="left">
    <?php
$id=$bsiCore->ClearInput($id);
if($id)	{
$row=mysql_fetch_assoc(mysql_query("select * from bsi_roomtype where roomtype_ID=$id"));
}else{
$row=NULL;
}
?>
<form name="roomtypeaddeit" action="<?=$_SERVER['PHP_SELF']?>?id=<?=$id?>&act=1" method="post" >
<input type="hidden" name="reffer_http" value="<?=$_SERVER['HTTP_REFERER']?>" />
<table cellpadding="0" cellspacing="0" border="0"  >
<tr><td align="center" style="font-size:14px; color:#006600; font-weight:bold"><? if(isset($updated_msg)) echo $updated_msg; ?></td></tr>
<tr><td style="font-size:14px; font-weight:bold; color:#718d9d">Room Type Add/Edit</td></tr>
<tr><td colspan="2" height="2" bgcolor="#718d9d"></td></tr>
<tr><td height="5"></td></tr>
<tr><td>
<table cellpadding="5" cellspacing="0" border="0"  style="border:solid #666666 1px; " >
<tr><td class="TitleBlue11pt">Room Type Name</td><td><input type="text" name="roomtype" value="<?=$row['type_name']?>" /></td></tr>
<tr><td valign="top" class="TitleBlue11pt">Default Price</td><td style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
    <table cellpadding="3" cellspacing="0" border="0" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
    <?php
	$sql_capacity=mysql_query("select * from  bsi_capacity");
	while($row_capacity=mysql_fetch_assoc($sql_capacity)){
	if($id)	{
	$row12=mysql_fetch_assoc(mysql_query("select * from bsi_priceplan where roomtype_id=$id and default_plan=true and capacity_id=".$row_capacity['id'].""));
	} else {
	$row12=NULL;
	}
	?>
    <tr><td><?=$row_capacity['title'].' ('.$row_capacity['capacity'].')'?>:</td><td><?=$bsiCore->config['conf_currency_symbol']?><input type="text" name="<?=strtolower($row_capacity['title'])?>" size="5" value="<?=$row12['price']?>"/></td></tr>
    <?php 
	} 
	if($id)	{
	$row121=mysql_fetch_assoc(mysql_query("select * from bsi_priceplan where roomtype_id=$id and default_plan=true limit 1"));
	} else {
	$row121=NULL;
	}
	?>
    <tr><td>Extra / Bed:</td><td><?=$bsiCore->config['conf_currency_symbol']?><input type="text" name="extrabed" size="5" value="<?=$row121['extrabed']?>"/></td></tr>
    </table>
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
