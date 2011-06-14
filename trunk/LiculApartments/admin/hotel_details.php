<?php
// TODO: umcomment
include ("access.php");
require_once ("../includes/SystemConfiguration.class.php");
global $systemConfiguration;
$hotelDetails = $systemConfiguration->getHotelDetails();

$errors = array();
$message = "";

if (sizeof($_POST) > 0 && isset($_POST['conf_hotel_name']))
{
	$details = HotelDetails::fetchFromParameters($_POST);
	if ($details->save())
	{
		$message = "Values were succesfully updated.";				
	}	
	else
	{
		$errors = $details->errors;
	}
	$hotelDetails = $details;
}

include ("header.php");
?>
	</td>
  </tr> 
  
  <tr>
    <td valign="top" >
    <?php
		if (sizeof($errors) > 0)
		{
			echo '			<table width="100%">' . "\n";
			foreach ($errors as $error) 
			{
				echo '				<tr><td class="TitleBlue11pt" style="color: red; font-weight: bold;">' . htmlentities($error) . '</td></tr>' . "\n";
			}
			echo '			</table>' . "\n";
		}
		else if ($message != "")
		{
			echo '			<table width="100%">' . "\n";
			echo '				<tr><td class="TitleBlue11pt" align="center" style="color: green; font-weight: bold;">' . htmlentities($message) . '</td></tr>' . "\n";
			echo '			</table>' . "\n";
		}			
	?>
    <form name="hotel_details_form" action="<?=$_SERVER['PHP_SELF']?>" method="post">
    <fieldset>
	    <legend class="TitleBlue11pt">Company Details</legend>
	    <table cellpadding="5" cellspacing="0" border="0" class="bodytext">
		    <tr>
		    	<td>Company Name:</td>
		    	<td>
		    		<input type="text" name="conf_hotel_name" size="50" value="<?= htmlentities($hotelDetails->getHotelName()) ?>"/>
	    		</td>
    		</tr>	
		    <tr><td>Street Address:</td><td><input type="text" name="conf_hotel_streetaddr" size="50" value="<?= htmlentities($hotelDetails->getHotelAddress()) ?>"/></td></tr>
		    <tr><td>City:</td><td><input type="text" name="conf_hotel_city" size="50" value="<?= htmlentities($hotelDetails->getHotelCity()) ?>"/></td></tr>
		    <tr><td>State:</td><td><input type="text" name="conf_hotel_state" size="50" value="<?= htmlentities($hotelDetails->getHotelState()) ?>"/></td></tr>
		    <tr><td>Country:</td><td><input type="text" name="conf_hotel_country" size="50" value="<?= htmlentities($hotelDetails->getHotelCountry()) ?>"/></td></tr>		    
		    <tr><td>Phone:</td><td><input type="text" name="conf_hotel_phone" size="50" value="<?= htmlentities($hotelDetails->getHotelPhone()) ?>"/></td></tr>		    
		    <tr><td>Email:</td><td><input type="text" name="conf_hotel_email" size="50" value="<?= htmlentities($hotelDetails->getHotelEmail()) ?>"/></td></tr>		  
	    </table>
    </fieldset>
    <table width="100%">
		<tr>
			<td align="center">
				<input src="images/button_save.png" name="SBMT_REG" type="image">
			</td>
		</tr>
	</table>
    </form>
    </td>
  </tr>
  <?php include("footer.php"); ?>
</table>


</body>
</html>
