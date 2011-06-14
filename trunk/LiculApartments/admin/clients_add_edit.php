<?php
// TODO: Uncomment
include ("access.php");
include_once ("../includes/SystemConfiguration.class.php");
include ("header.php");

global $systemConfiguration;
global $logger;

$errors = array();
$message = "";

$client = new Client();
if(isset($_POST['SBMT_REG']))
{
	$logger->LogInfo("Form has been submitted.");
	$client = Client::fetchFromParameters($_POST);
	if (!$client->save())
	{
		$logger->LogError("Error saving client.");
		foreach ($client->errors as $error) 
		{
			$logger->LogError($error);
			$errors[] = $error;
		}
	}	
	else
	{
		$message = "Values were updated successfully!";
	}
}
else if (isset($_REQUEST['id']) && is_numeric($_REQUEST['id']))
{
	$logger->LogInfo("Page was called for edit of id: " . $_REQUEST['id']);
	$id = intval($_REQUEST['id']);
	$logger->LogDebug("Numeric id is: $id");
	$client = Client::fetchFromDb($id);
	if ($client == null)
	{
		$logger->LogError("Invalid request. No client with id: $id exists.");
		$errors[] = "Invalid request. No client with id: $id exists.";
	}
}
 
?>
</td>
</tr>

<tr>
  <td valign="top">
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
	<form method="post" action="<?=$_SERVER['PHP_SELF']?>">
		<fieldset>	
		    <legend class="TitleBlue11pt">Client Info</legend>
		    <table width="100%" cellspacing="1" border="0" cellpadding="3">	                                        
		        <input type="hidden" name="id" value="<?= $client->id ?>" />
		        <tr class="TitleBlue11pt">
	    			<td valign="middle" align="left" width="18%">
	    				First Name
	            	</td>
	            	<td align="left" valign="middle">
	            		<input type="text" name="first_name" value="<?= htmlentities($client->firstName) ?>" size="100" />
            		</td>
	            </tr>	                                           
	            <tr class="TitleBlue11pt">
	    			<td valign="middle" align="left" width="18%">
	    				Middle Name
	            	</td>
	            	<td align="left" valign="middle">
	            		<input type="text" name="middle_name" value="<?= htmlentities($client->middleName) ?>" size="100" />
            		</td>
	            </tr>
	            <tr class="TitleBlue11pt">
	    			<td valign="middle" align="left" width="18%">
	    				Last Name
	            	</td>
	            	<td align="left" valign="middle">
	            		<input type="text" name="last_name" value="<?= htmlentities($client->lastName) ?>" size="100" />
            		</td>
	            </tr>
	            <tr class="TitleBlue11pt">
	    			<td valign="middle" align="left" width="18%">
	    				Street Address
	            	</td>
	            	<td align="left" valign="middle">
	            		<input type="text" name="street_address" value="<?= htmlentities($client->streetAddress) ?>" size="100" />
            		</td>
	            </tr>
	            <tr class="TitleBlue11pt">
	    			<td valign="middle" align="left" width="18%">
	    				City
	            	</td>
	            	<td align="left" valign="middle">
	            		<input type="text" name="city" value="<?= htmlentities($client->city) ?>" size="100" />
            		</td>
	            </tr>
	            <tr class="TitleBlue11pt">
	    			<td valign="middle" align="left" width="18%">
	    				State/Province
	            	</td>
	            	<td align="left" valign="middle">
	            		<input type="text" name="state" value="<?= htmlentities($client->state) ?>" size="100" />
            		</td>
	            </tr>
	            <tr class="TitleBlue11pt">
	    			<td valign="middle" align="left" width="18%">
	    				Zip/Postal Code
	            	</td>
	            	<td align="left" valign="middle">
	            		<input type="text" name="zip" value="<?= htmlentities($client->zip) ?>" size="100" />
            		</td>
	            </tr>
	            <tr class="TitleBlue11pt">
	    			<td valign="middle" align="left" width="18%">
	    				Country
	            	</td>
	            	<td align="left" valign="middle">
	            		<input type="text" name="country" value="<?= htmlentities($client->country) ?>" size="100" />
            		</td>
	            </tr>
	            <tr class="TitleBlue11pt">
	    			<td valign="middle" align="left" width="18%">
	    				Email
	            	</td>
	            	<td align="left" valign="middle">
	            		<input type="text" name="email" value="<?= htmlentities($client->email) ?>" size="100" />
            		</td>
	            </tr>
	            <tr class="TitleBlue11pt">
	    			<td valign="middle" align="left" width="18%">
	    				Phone
	            	</td>
	            	<td align="left" valign="middle">
	            		<input type="text" name="phone" value="<?= htmlentities($client->phone) ?>" size="100" />
            		</td>
	            </tr>
	            <tr class="TitleBlue11pt">
	    			<td valign="middle" align="left" width="18%">
	    				IP Address
	            	</td>
	            	<td align="left" valign="middle">
	            		<input type="text" name="ip" value="<?= htmlentities($client->ipAddress) ?>" size="100" />
            		</td>
	            </tr>
			</table>		
		</fieldset>
		<table width="100%">
		<tr class="TitleBlue11pt">
		            <td height="20" align="center">
		                <input type="image" value="1" src="images/button_save.png"  name='SBMT_REG' >
		            </td>
		        </tr>
		</table>
	</form>
  </td>
</tr>
<?php include("footer.php"); ?>
</table>
<br />
</body></html>