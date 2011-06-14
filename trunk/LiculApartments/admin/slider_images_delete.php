<?php
// TODO: umcomment
include ("access.php");
require_once ("../includes/SystemConfiguration.class.php");
global $systemConfiguration;
global $logger;

$imageId = 0;
$roomId = 0;

if (isset($_GET['id']) && is_numeric($_GET['id']))
{
	$imageId = $_GET['id'];	
	
	$sql = "DELETE FROM bsi_gallery_images WHERE id = $imageId";
	$query = mysql_query($sql);
	if (!$query)
	{	
		$logger->LogFatal("Database error: " . mysql_errno() . ". Message: " . mysql_error());
		$logger->LogError("SQL: " . $sql);
		die("Database error: " . mysql_errno() . ". Message: " . mysql_error());
		die('Error: ' . mysql_error());
	}
	
	$sql = "DELETE FROM bsi_slider_images WHERE image_id = $imageId";
	$query = mysql_query($sql);
	if (!$query)
	{	
		$logger->LogFatal("Database error: " . mysql_errno() . ". Message: " . mysql_error());
		$logger->LogError("SQL: " . $sql);
		die("Database error: " . mysql_errno() . ". Message: " . mysql_error());
		die('Error: ' . mysql_error());
	}	
	
	header ("Location: slider_images_list.php");
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
