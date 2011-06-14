<?php
include ("access.php");
include ("../includes/SystemConfiguration.class.php");


$bookingId = 0;

// If we have id, then we are truying to edit
if (isset($_GET['id']) && is_numeric($_GET['id']))
{
	$bookingId = intval($_GET['id']);
	$query=mysql_query("UPDATE bsi_bookings SET is_deleted = 1 WHERE booking_id = " . $bookingId);					
	if (!$query)
	{	
		die('Error: ' . mysql_error());
	}
	header ("Location: bookings_list.php");
}

include ("header.php");
?>


</td>
</tr>
<tr>
	<td height="400" valign="top" align="left" width="100%">
	<form name="room_add_edit" action="rooms_add_edit.php"
		method="post" enctype="multipart/form-data" onsubmit="return validateAddRoom();">
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td align="center" style="font-size: 14px; color: red; font-weight: bold">
				INVALID REQUEST!
			</td>
		</tr>		
	</table>
	</form>
	<!--################################################# --> <br />
	<!--################################################# --></td>
</tr>
<?php
include ("footer.php");
?>
</table>
</body>
</html>
