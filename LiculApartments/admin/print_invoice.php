<?php  
include ("access.php");
include("../includes/db.conn.php"); 
include("../includes/conf.class.php");
if(isset($_REQUEST['bid'])){
$r_invoice=mysql_fetch_assoc(mysql_query("select * from bsi_invoice where booking_id=".$bsiCore->ClearInput($_REQUEST['bid'])));
$r_booking=mysql_fetch_assoc(mysql_query("select * from bsi_bookings where booking_id=".$bsiCore->ClearInput($_REQUEST['bid'])));
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Print Booking Invoice</title>
<script type="text/javascript">
window.print()
</script>
</head>
<body>
<div align="center">
<table cellpadding="3"  border="0" width="700">
<tr><td width="400" align="left" valign="top">
<span style="font-family:Arial, Helvetica, sans-serif; font-size:24px; font-weight:bold;"><?=$bsiCore->config['conf_hotel_name']?></span><br />
<span style="font-family:Arial, Helvetica, sans-serif; font-size:12px;"><?=$bsiCore->config['conf_hotel_streetaddr']?></span><br />
<span style="font-family:Arial, Helvetica, sans-serif; font-size:12px;"><?=$bsiCore->config['conf_hotel_city']?></span><br />
<span style="font-family:Arial, Helvetica, sans-serif; font-size:12px;"><?=$bsiCore->config['conf_hotel_state']." ". $bsiCore->config['conf_hotel_zipcode']?></span><br />
<span style="font-family:Arial, Helvetica, sans-serif; font-size:12px;"><?=$bsiCore->config['conf_hotel_country']?></span><br />
</td>
<td width="200" align="right" valign="top">
<span style="font-family:Arial, Helvetica, sans-serif; font-size:12px;"><b>Phone:</b> <?=$bsiCore->config['conf_hotel_phone']?></span><br />
<span style="font-family:Arial, Helvetica, sans-serif; font-size:12px;"><b>Fax:</b> <?=$bsiCore->config['conf_hotel_fax']?></span><br />
<span style="font-family:Arial, Helvetica, sans-serif; font-size:12px;"><b>Email:</b> <?=$bsiCore->config['conf_hotel_email']?></span><br />
</td></tr>
</table><br />

<?=$r_invoice['invoice']?>
</div>
</body>
</html>
