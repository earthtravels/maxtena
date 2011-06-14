<?php
// TODO: Uncomment
include ("access.php");
include_once ("../includes/SystemConfiguration.class.php");
include ("header.php");

global $systemConfiguration;
global $logger;

$errors = array();
$message = "";

$paymentGateway = new PaymentGateway();
if(isset($_POST['SBMT_REG']))
{
	$logger->LogInfo("Form has been submitted.");
	$paymentGateway = PaymentGateway::fetchFromParameters($_POST);
	if (!$paymentGateway->save())
	{
		$logger->LogError("Error saving payment gateway.");
		foreach ($paymentGateway->errors as $error) 
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
	$paymentGateway = PaymentGateway::fetchFromDb($id);
	if ($paymentGateway == null)
	{
		$logger->LogError("Invalid request. No payment gateway with id: $id exists.");
		$errors[] = "Invalid request. No payment gateway with id: $id exists.";
	}
}


$defaultLanguage = Language::fetchDefaultLangauge();
 
?>
</td>
</tr>

<tr>
  <td height="400" valign="top">
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
	<form method="post" action="<?=$_SERVER['PHP_SELF']?>" enctype="multipart/form-data">
	    <table width="99%" border="0" align="center" cellspacing="1" cellpadding="4" bgcolor="#666666" style="border:solid 1px;">                                    
	        <input type="hidden" name="id" value="<?= $paymentGateway->id ?>" />
	        <?php
		        $languages = Language::fetchAllFromDbActive();	                                        	
		        foreach ($languages as $language) 
		        {	                                        		
		    ?>
		    		<tr  bgcolor="#ffffff" class="TitleBlue11pt">
		    			<td valign="middle" align="left" width="30%">
		    				<img src="../graphics/language_icons/<?=$language->languageCode?>.png" border="0"  title="<?=$language->languageName?>" alt="<?=$language->languageName?>" /> <?= $language->languageName ?> Gateway Name
		            	</td>
		            	<td align="left" valign="middle">
		            		<input type="text" name="gateway_name_<?= $language->languageCode ?>" value="<?= htmlentities($paymentGateway->gatewayName->getText($language->languageCode)) ?>" size="50"/>
	            		</td>
		            </tr>                       
		    <?php 
		        }										
	        ?>
	        <tr bgcolor="#ffffff" class="TitleBlue11pt">
	            <td  valign="middle">
	                Gateway Code 
	            </td>
	            <td align="left" valign="middle">
	                <input type="text" name="gateway_code" value="<?= htmlentities($paymentGateway->gatewayCode) ?>" size="50" />	               
	            </td>
	        </tr>
	        <tr bgcolor="#ffffff" class="TitleBlue11pt">
	            <td  valign="middle">
	                Account 
	            </td>
	            <td align="left" valign="middle">
	                <input type="text" name="account" value="<?= htmlentities($paymentGateway->account) ?>"  size="50"/>	               
	            </td>
	        </tr>
	        <tr bgcolor="#ffffff" class="TitleBlue11pt">
	            <td  valign="middle">
	                Enabled 
	            </td>
	            <td align="left">
	                <input type="radio" name="enabled" value="1" <?= $paymentGateway->isEnabled ? ' checked="checked"' : '' ?>/>
	                Yes
	                <input type="radio" name="enabled" value="0" <?= $paymentGateway->isEnabled ? '' : ' checked="checked"' ?>/>
	                No
	            </td>
	        </tr>                                      		
	        <tr bgcolor="#ffffff" class="TitleBlue11pt">
	            <td  valign="middle">
	                Mode 
	            </td>
	            <td align="left">
	                <input type="radio" name="is_production_mode" value="1" <?= $paymentGateway->isProductionMode ? ' checked="checked"' : '' ?>/>
	                Production
	                <input type="radio" name="is_production_mode" value="0" <?= $paymentGateway->isProductionMode ? '' : ' checked="checked"' ?>/>
	                Test
	            </td>
	        </tr>
	        <tr bgcolor="#ffffff" class="TitleBlue11pt">
	            <td  valign="middle">
	                Production URL 
	            </td>
	            <td align="left" valign="middle">
	                <input type="text" name="production_url" value="<?= htmlentities($paymentGateway->productionUrl) ?>" size="50" />	               
	            </td>
	        </tr>
	        <tr bgcolor="#ffffff" class="TitleBlue11pt">
	            <td  valign="middle">
	                Test URL 
	            </td>
	            <td align="left" valign="middle">
	                <input type="text" name="test_url" value="<?= htmlentities($paymentGateway->testUrl) ?>"  size="50"/>	               
	            </td>
	        </tr>
	       <tr bgcolor="#ffffff" class="TitleBlue11pt">
	            <td  valign="middle">
	                Display Order 
	            </td>
	            <td align="left" valign="middle">
	                <input type="text" name="display_order" value="<?= htmlentities($paymentGateway->displayOrder) ?>" />	               
	            </td>
	        </tr>	                                            
	</table>
	<table width="100%">
	<tr  bgcolor="#ffffff" class="TitleBlue11pt">
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