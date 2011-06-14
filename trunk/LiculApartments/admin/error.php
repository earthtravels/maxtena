<?php
include ("access.php");
include_once ("includes/SystemConfiguration.class.php");
include ("header.php");

session_start ();

$errors = array();
if (isset($_SESSION['errors']))
{
	$errors = $_SESSION['errors'];
	unset($_SESSION['errors']);
}
else
{
	$errors[] = "Unknown error.";
}

?>
</td>
</tr>
<style type="text/css">
<!--
.style1 {
	font-size: 25px;
	font-weight: bold;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	color: #666666;
}
-->
</style>


<tr><
  <td height="400" valign="middle" align="center">
  	<h2>
  		<em><strong>Error</strong></em>
  	</h2>
	<?php
	$index = 0;
	foreach ($errors as $error) 
	{
		++$index;
		echo '<p style="color: red; font-weight: bold;">' . htmlentities($error) . '</p>';
	}	
	?>
    <br />
    <br /></td>
</tr>
<?php include("footer.php"); ?>
</table>
</body></html>