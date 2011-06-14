<?php  
include ("access.php");
require_once ("../includes/SystemConfiguration.class.php");

global $systemConfiguration;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Invoice</title>
</head>
<body>

<?php

if(isset($_REQUEST['booking_id']) && is_numeric($_REQUEST['booking_id']))
{
	$bookingId = intval($_REQUEST['booking_id']);	
	$booking = Booking::fetchFromDb($bookingId);	
	if ($booking == null)
	{
		echo "<h1>Invalid booking id!</h1>";
		die("");
	}	
}
else 
{
	echo "<h1>Invalid booking id!</h1>";
	die("");
}
?>

<div align="center">
<table cellpadding="3"  border="0" width="700">
<tr><td colspan="2" align="right"><a href="javascript:window.print();">Print</a></td></tr>
<tr><td width="400" align="left" valign="top">
<span style="font-family:Arial, Helvetica, sans-serif; font-size:24px; font-weight:bold;"><?= $systemConfiguration->getHotelDetails()->getHotelName() ?></span><br />
<span style="font-family:Arial, Helvetica, sans-serif; font-size:12px;"><?= $systemConfiguration->getHotelDetails()->getHotelAddress() ?></span><br />
<span style="font-family:Arial, Helvetica, sans-serif; font-size:12px;"><?= $systemConfiguration->getHotelDetails()->getHotelCity() ?></span><br />
<span style="font-family:Arial, Helvetica, sans-serif; font-size:12px;"><?= $systemConfiguration->getHotelDetails()->getHotelCountry() ?></span><br />
</td>
<td width="200" align="right" valign="top">
<span style="font-family:Arial, Helvetica, sans-serif; font-size:12px;"><b>Phone:</b> <?= $systemConfiguration->getHotelDetails()->getHotelPhone() ?></span><br />
<span style="font-family:Arial, Helvetica, sans-serif; font-size:12px;"><b>Email:</b> <?= $systemConfiguration->getHotelDetails()->getHotelEmail() ?></span><br />
</td></tr>
</table><br />

<?=$booking->invoice?>
</div>
</body>
</html>
