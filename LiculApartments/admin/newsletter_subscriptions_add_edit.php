<?php
// TODO: Uncomment
include ("access.php");
include_once ("../includes/SystemConfiguration.class.php");
include ("header.php");

global $systemConfiguration;
global $logger;

$errors = array();
$message = "";

$newsletterSubscription = new NewsletterSubscription();
if(isset($_POST['SBMT_REG']))
{
	$logger->LogInfo("Form has been submitted.");
	$newsletterSubscription = NewsletterSubscription::fetchFromParameters($_POST);
	if (!$newsletterSubscription->save())
	{
		$logger->LogError("Error saving newsletter subscription.");
		foreach ($newsletterSubscription->errors as $error) 
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
	$newsletterSubscription = NewsletterSubscription::fetchFromDb($id);
	if ($newsletterSubscription == null)
	{
		$logger->LogError("Invalid request. No newsletter subscription with id: $id exists.");
		$errors[] = "Invalid request. No newsletter subscription with id: $id exists.";
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
		    <legend class="TitleBlue11pt">Newsletter Subscription Info</legend>
		    <table width="100%" cellspacing="1" border="0" cellpadding="3">	                                        
		        <input type="hidden" name="id" value="<?= $newsletterSubscription->id ?>" />
		        <tr class="TitleBlue11pt">
	    			<td valign="middle" align="left" width="18%">
	    				Email
	            	</td>
	            	<td align="left" valign="middle">
	            		<input type="text" name="email" value="<?= htmlentities($newsletterSubscription->email) ?>" size="100" />
            		</td>
	            </tr>	                                           
	            <tr class="TitleBlue11pt">
	    			<td valign="middle" align="left" width="18%">
	    				Active
	            	</td>
	            	<td align="left" valign="middle">
	            		<input type="radio" name="is_active" value="1" <?= ($newsletterSubscription->isActive ? 'checked="checked"' : '') ?> size="100" /> Yes
	            		<input type="radio" name="is_active" value="0" <?= ($newsletterSubscription->isActive ? '' : 'checked="checked"') ?> size="100" /> No
            		</td>
	            </tr>
	            <tr class="TitleBlue11pt">
	    			<td valign="middle" align="left" width="18%">
	    				Subscription Date
	            	</td>
	            	<td align="left" valign="middle">
	            		<input type="text" name="subscription_date" value="<?= $newsletterSubscription->subscriptionDate->format("m/d/Y") ?>" size="100" />
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