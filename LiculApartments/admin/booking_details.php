<?php
include ("access.php");
include ("header.php");
include("../includes/conf.class.php");
if(isset($_REQUEST['id'])){
	$r_invoice=mysql_fetch_assoc(mysql_query("select * from bsi_invoice where booking_id=".$bsiCore->ClearInput(base64_decode($_REQUEST['id']))));
	$r_booking=mysql_fetch_assoc(mysql_query("select * from bsi_bookings where booking_id=".$bsiCore->ClearInput(base64_decode($_REQUEST['id']))));
	$c_row=mysql_fetch_assoc(mysql_query("select * from bsi_clients where client_id=".$bsiCore->ClearInput($r_booking['client_id'])));
	
	if(strtotime($r_booking['end_date']) >=time() && $r_booking['is_deleted']==false)
	$booking_state="<font color='#000099'>Active &amp; Confirm</font>";
	elseif(strtotime($r_booking['end_date']) < time() && $r_booking['is_deleted']==false)
	$booking_state="<font color='#006600'>Completed</font>";
	elseif($r_booking['is_deleted']==true)
	$booking_state="<font color='#FF0000'>Cancelled<font";
}
?>
</td>
</tr>

<tr>
  <td height="400" valign="top">
  <table style="width:700px;" cellpadding="4" cellspacing="0" border="0">
  <tr><td align="right"><a href="<?=$_SERVER['HTTP_REFERER']?>"><img src="images/button_back.gif" border="0" /></a></td></tr>
  </table>
  
  <table style="font-family:Verdana, Geneva, sans-serif; font-size: 12px;  width:700px; border:none;" cellpadding="4" cellspacing="0" border="1">
  <tr style="font-family:Verdana, Geneva, sans-serif; font-size:12px; font-weight:bold; font-variant:small-caps; background:#eeeeee"><td  colspan="2" align="left">Customer Details</td></tr>
  <tr style="font-family:Verdana, Geneva, sans-serif; font-size:12px;"><td align="left" width="30%" style="font-weight:bold;">Guest Name:</td><td align="left"><?=$c_row['title']." ".$c_row['first_name']." ".$c_row['surname']?></td></tr>
  <tr style="font-family:Verdana, Geneva, sans-serif; font-size:12px;"><td align="left" width="30%" style="font-weight:bold;">Street Address:</td><td align="left"><?=$c_row['street_addr']?></td></tr>
  <tr style="font-family:Verdana, Geneva, sans-serif; font-size:12px;"><td align="left" width="30%" style="font-weight:bold;">City:</td><td align="left"><?=$c_row['city']?></td></tr>
  <tr style="font-family:Verdana, Geneva, sans-serif; font-size:12px;"><td align="left" width="30%" style="font-weight:bold;">State:</td><td align="left"><?=$c_row['province']?></td></tr>
  <tr style="font-family:Verdana, Geneva, sans-serif; font-size:12px;"><td align="left" width="30%" style="font-weight:bold;">Zip/Post Code:</td><td align="left"><?=$c_row['zip']?></td></tr>
  <tr style="font-family:Verdana, Geneva, sans-serif; font-size:12px;"><td align="left" width="30%" style="font-weight:bold;">Country:</td><td align="left"><?=$c_row['country']?></td></tr>
  <tr style="font-family:Verdana, Geneva, sans-serif; font-size:12px;"><td align="left" width="30%" style="font-weight:bold;">Phone:</td><td align="left"><?=$c_row['phone']?></td></tr>
  <tr style="font-family:Verdana, Geneva, sans-serif; font-size:12px;"><td align="left" width="30%" style="font-weight:bold;">Fax:</td><td align="left"><?=$c_row['fax']?></td></tr>
  <tr style="font-family:Verdana, Geneva, sans-serif; font-size:12px;"><td align="left" width="30%" style="font-weight:bold;">Email:</td><td align="left"><?=$c_row['email']?></td></tr>
  </table><br/>
  <?=$r_invoice['invoice']?>

  
<br>

<table style="border:thin; border-color:#CCC; width:700px;" cellpadding="4" cellspacing="0" border="1">
  <tr style="font-family:Verdana, Geneva, sans-serif; font-size:12px; font-weight:bold; font-variant:small-caps; background:#999999"><td align="center">Booking Status</td></tr>
  <tr style="font-family:Verdana, Geneva, sans-serif; font-size:12px; font-weight:bold; font-variant:small-caps;">
  <td align="center"><?=$booking_state?></td></tr>
  </table>
  <table style="width:700px;" cellpadding="4" cellspacing="0" border="0">
  <tr><td align="right"><a href="<?=$_SERVER['HTTP_REFERER']?>"><img src="images/button_back.gif" border="0" /></a></td></tr>
  </table>
  </td>
</tr>
<?php include("footer.php"); ?>
</table>
</body></html>