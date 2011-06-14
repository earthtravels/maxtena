<?php
// TODO: Uncomment
include ("access.php");
include_once ("../includes/SystemConfiguration.class.php");

global $systemConfiguration;
global $logger;


$id = 0;
$errors = array();

$logger->LogInfo("Attempting to delete client ...");
if (isset($_GET['id']) && is_numeric($_GET['id']))
{
	$id = intval($_GET['id']);
	if (!Client::delete($id))
	{
		$logger->LogError("Error deleting client.");
		foreach (Client::$staticErrors as $error) 
		{
			$logger->LogError($error);
			$errors[] = $error;
		}
	}
	else
	{	
		header ("Location: clients_list.php");
	}
}
else
{
	$errors[] = "Invalid request: Client id was not set";
	$logger->LogError("Client id is not set.");
}
include ("header.php");
?>


</td>
</tr>
<tr>
	<td height="400" valign="top" align="left" width="100%">	
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
		<?php
			foreach ($errors as $error) 
			{
				echo '<tr>';
				echo '	<td align="center" style="font-size: 14px; color: red; font-weight: bold">' . "\n";
				echo '		' . $error . "\n";
				echo '</td>' . "\n";
				echo '</tr>';
			} 
		?>
		</tr>		
	</table>	
	<!--################################################# --> <br />
	<!--################################################# --></td>
</tr>
<?php
include ("footer.php");
?>
</table>
</body>
</html>
