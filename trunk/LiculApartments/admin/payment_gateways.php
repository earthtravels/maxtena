<?php
// TODO: umcomment
include ("access.php");
require_once ("../includes/SystemConfiguration.class.php");
global $systemConfiguration;


$errors = array();
$message = "";

if (sizeof($_POST) > 0 && isset($_POST['form_submit']))
{
	$paymentGateways = PaymentGateway::fetchFromDbNonAdmin();		
	foreach ($paymentGateways as $paymentGateway) 
	{
		if (!($paymentGateway instanceof PaymentGateway))
		{
			continue;
		}
		$paymentGateway->account = trim($_POST[$paymentGateway->gatewayCode . '_account']);
		$paymentGateway->displayOrder = trim(strtolower($_POST[$paymentGateway->gatewayCode . '_display_order']));
		$paymentGateway->gatewayName = trim($_POST[$paymentGateway->gatewayCode . '_paymentGateway_file_name']);
		$paymentGateway->isEnabled = isset($_POST[$paymentGateway->gatewayCode . '_is_active']) && intval($_POST[$paymentGateway->gatewayCode . '_enabled']) == 1;
		$paymentGateway->isProductionMode = isset($_POST[$paymentGateway->gatewayCode . '_is_production_mode']) && intval($_POST[$paymentGateway->gatewayCode . '_is_production_mode']) == 1;
		$paymentGateway->productionUrl = trim($_POST[$paymentGateway->gatewayCode . '_production_url']);				
		$paymentGateway->testUrl = trim($_POST[$paymentGateway->gatewayCode . '_test_url']);
		if(!$paymentGateway->save())
		{
			foreach ($paymentGateway->errors as $error) 
			{
				$errors[] = $error;
			}			
		}
	}	
	if (sizeof($errors) == 0)
	{
		$message = "Values were updated successfully.";
	}	
}



$paymentGateways = PaymentGateway::fetchFromDbNonAdmin();
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
		<form action="<?=$_SERVER['PHP_SELF']?>" method="post">
		<input type="hidden" name="form_submit" value="form_submit" />
		<fieldset>
		    <legend class="TitleBlue11pt">Payment Gateways</legend>			
			<table width="100%">
				<tr>
					<td class="TitleRed11pt">
						PaymentGateway
					</td>
					<td class="TitleRed11pt">
						Symbol
					</td>
					<td class="TitleRed11pt">
						Code
					</td>
					<td class="TitleRed11pt">
						File
					</td>
					<td class="TitleRed11pt">
						Enabled
					</td>
					<td class="TitleRed11pt">
						Default
					</td>
					<td class="TitleRed11pt">
						Display Order
					</td>
				</tr>									
			<?php 
				foreach ($paymentGateways as $paymentGateway) 
				{
			?>
					<tr>
						<td>
							<input type="text" name="<?= $paymentGateway->paymentGatewayCode ?>_paymentGateway_name" value="<?= $paymentGateway->paymentGatewayName ?>" />
						</td>					
						<td>
							<img src="../images/<?= $paymentGateway->paymentGatewayCode ?>.png" alt="" />
						</td>					
						<td>
							<input type="text" name="<?= $paymentGateway->paymentGatewayCode ?>_paymentGateway_code" value="<?= $paymentGateway->paymentGatewayCode ?>" />
						</td>					
						<td>
							<input type="text" name="<?= $paymentGateway->paymentGatewayCode ?>_paymentGateway_file_name" value="<?= $paymentGateway->fileName ?>" />
						</td>					
						<td>
							<input type="checkbox" name="<?= $paymentGateway->paymentGatewayCode ?>_is_active" value="1" <?= $paymentGateway->isActive ? ' checked="checked"' : '' ?> />
						</td>					
						<td>
							<input type="radio" name="is_default" value="<?= $paymentGateway->paymentGatewayCode ?>" <?= $paymentGateway->isDefault ? ' checked="checked"' : '' ?> />
						</td>					
						<td>
							<input type="text" name="<?= $paymentGateway->paymentGatewayCode ?>_display_order" value="<?= $paymentGateway->displayOrder ?>" />
						</td>
					</tr>					
			<?php 
				}
			?>				
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
