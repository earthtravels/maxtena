<?php
// TODO: umcomment
include ("access.php");
require_once ("../includes/SystemConfiguration.class.php");
global $systemConfiguration;
global $logger;

$imageId = 0;
$roomId = 0;

if (isset($_GET['image_id']) && is_numeric($_GET['image_id']) && isset($_GET['room_id']) && is_numeric($_GET['room_id']))
{
	$imageId = $_GET['image_id'];
	$roomId = $_GET['room_id'];
	
	$sql = "DELETE FROM bsi_gallery_images WHERE id = $imageId";
	$query = mysql_query($sql);
	if (!$query)
	{	
		$logger->LogFatal("Database error: " . mysql_errno() . ". Message: " . mysql_error());
		$logger->LogError("SQL: " . $sql);
		die("Database error: " . mysql_errno() . ". Message: " . mysql_error());
		die('Error: ' . mysql_error());
	}
	
	$sql = "DELETE FROM bsi_room_images WHERE room_id = $roomId AND image_id = $imageId";
	$query = mysql_query($sql);
	if (!$query)
	{	
		$logger->LogFatal("Database error: " . mysql_errno() . ". Message: " . mysql_error());
		$logger->LogError("SQL: " . $sql);
		die("Database error: " . mysql_errno() . ". Message: " . mysql_error());
		die('Error: ' . mysql_error());
	}	
	
	header ("Location: room_images_list.php?room_id=" . $roomId);
}

include ("header.php");
?>


</td>
</tr>
<tr>
	<td valign="top" align="left" width="100%">	
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td align="center"
				style="font-size: 14px; color: red; font-weight: bold">INVALID REQUEST!</td>
		</tr>		
	</table>	
	</td>
</tr>
<?php
include ("footer.php");
?>
</table>
</body>
</html>
