<?php
// TODO: umcomment
include ("access.php");
require_once ("../includes/SystemConfiguration.class.php");
global $systemConfiguration;
global $logger;


$errors = array();

$logger->LogDebug("Fetching all non-admin payment gateways ...");
$paymentGateways = PaymentGateway::fetchFromDbNonAdmin();
if ($paymentGateways == null)
{
	$logger->LogError("There were errors fetching gateways.");
	foreach (PaymentGateway::$staticErrors as $error) 
	{
		$logger->LogError($error);
		$errors[] = $error;
	}
}

$logger->LogDebug("Fetching default language ...");
$defaultLanguage = Language::fetchDefaultLangauge();
if ($defaultLanguage == null)
{
	$logger->LogError("There were errors fetching default language.");
	foreach (Language::$staticErrors as $error) 
	{
		$logger->LogError($error);
		$errors[] = $error;
	}
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
	?>	
		<fieldset>	
		    <legend class="TitleBlue11pt">Payment Gateways</legend>
		    <table width="100%" cellspacing="1" border="0" cellpadding="3">
				<tr bgcolor="#747471">
					<td scope="col" align="left"><b><font color="white"
						face="Verdana, Arial, Helvetica, sans-serif" size="2">Gateway Name</font></b>
					</td>
					<td scope="col" align="left"><b><font color="white"
						face="Verdana, Arial, Helvetica, sans-serif" size="2">Gateway Code</font></b>
					</td>							
					<td scope="col" align="left"><b><font color="white"
						face="Verdana, Arial, Helvetica, sans-serif" size="2">Account</font></b>
					</td>
					<td scope="col" align="left"><b><font color="white"
						face="Verdana, Arial, Helvetica, sans-serif" size="2">Enabled</font></b></td>
					<td scope="col" align="left"><b><font color="white"
						face="Verdana, Arial, Helvetica, sans-serif" size="2">Mode</font></b></td>					
					<td scope="col" align="left"><b><font color="white"
						face="Verdana, Arial, Helvetica, sans-serif" size="2">Production URL</font></b></td>
					<td scope="col" align="left"><b><font color="white"
						face="Verdana, Arial, Helvetica, sans-serif" size="2">Test URL</font></b></td>
					<td scope="col" class="bodytext_h">&nbsp;</td>
				</tr>
			<?php 
					foreach ($paymentGateways as $paymentGateway) 
					{
						if (!($paymentGateway instanceof PaymentGateway))
						{
							continue;
						}
						echo '<tr class="odd" bgcolor="#f2eaeb">' . "\n";
						echo '	<td align="left"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">' . htmlentities($paymentGateway->gatewayName->getText($defaultLanguage->languageCode)) . '</font></td>' . "\n";
						echo '	<td align="left"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">' . htmlentities($paymentGateway->gatewayCode) . '</font></td>' . "\n";
						echo '	<td align="left"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">' . htmlentities($paymentGateway->account) . '</font></td>' . "\n";																						
						echo '	<td align="left"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">' . ($paymentGateway->isEnabled ? 'Yes' : 'No') . '</font></td>' . "\n";
						echo '	<td align="left"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">' . ($paymentGateway->isProductionMode ? 'Production' : 'Test') . '</font></td>' . "\n";
						echo '	<td align="left"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">' . htmlentities($paymentGateway->productionUrl) . '</font></td>' . "\n";						                                    
						echo '	<td align="left"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">' . htmlentities($paymentGateway->testUrl) . '</font></td>' . "\n";
						echo '	<td align="left"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">';
						echo '		<a href="payment_gateways_add_edit.php?id=' . $paymentGateway->id . '" style="text-decoration:none"><font color="#990000"  face="Verdana, Arial, Helvetica, sans-serif" size="2" >Edit</font></a>&nbsp;&nbsp;';
						echo '		<a href="payment_gateways_delete.php?id=' . $paymentGateway->id . '" style="text-decoration:none" onclick = "if (! confirm(\'Are you sure?\')) { return false; }"><font color="#990000"  face="Verdana, Arial, Helvetica, sans-serif" size="2" >Delete</font></a>';
						echo '	</td>' . "\n";
						echo "</tr>\n";	;
					}

					if(sizeof($paymentGateways) == 0)
					{
						echo '<tr><td colspan="7">No payment gateways are defined yet!</td></tr>' . "\n";
					}
			?>
			</table>							   
	    </fieldset>	
	    <table width="100%">
			<tr  bgcolor="#ffffff" class="TitleBlue11pt">
	            <td height="20" align="center">
	                <input type="image" value="1" src="images/button_add.png"  name='SBMT_REG'  onclick="javascript:window.location.href='payment_gateways_add_edit.php'">
	            </td>
	        </tr>
		</table>   
    </td>
  </tr>
  <?php include("footer.php"); ?>
</table>


</body>
</html>
