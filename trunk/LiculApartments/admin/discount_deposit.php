<?php
// TODO: umcomment
include ("access.php");
require_once ("../includes/SystemConfiguration.class.php");
global $systemConfiguration;


$errors = array();
$message = "";

if (sizeof($_POST) > 0 && isset($_POST['deposit_10']))
{
	$discountsDeposits = MonthlyDiscountDeposit::fetchAllFromDb();
	foreach ($discountsDeposits as $discountDeposit) 
	{
		if (!($discountDeposit instanceof MonthlyDiscountDeposit))
		{
			continue;
		}
		$discountDeposit->depositPercent = floatval($_POST['deposit_' . $discountDeposit->getMonthNumber()]);
		$discountDeposit->discountPercent = floatval($_POST['discount_' . $discountDeposit->getMonthNumber()]);
		if(!$discountDeposit->save())
		{
			array_push($errors, $discountDeposit->errors);
		}
	}
	
	$enabled = isset($_POST['conf_enabled_deposit']) && intval($_POST['conf_enabled_deposit']) == 1 ? 1 : 0;
	$systemConfiguration->setIsMonthlyDepositSchemeEnabled($enabled);
	
	$enabled = isset($_POST['conf_enabled_discount']) && intval($_POST['conf_enabled_discount']) == 1 ? 1 : 0;
	$systemConfiguration->setIsMonthlyDiscountSchemeEnabled($enabled);
	
	if (!$systemConfiguration->save(true))
	{
		$errors = array_merge($errors, $systemConfiguration->errors);
	}
	
	if (sizeof($errors) == 0)
	{
		$message = "Values were succesfully updated.";
	}	
}

$discountsDeposits = MonthlyDiscountDeposit::fetchAllFromDb();
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
		<fieldset>
		    <legend class="TitleBlue11pt">Discount / Deposit Scheme</legend>			
			<table width="100%" class="TitleBlue11pt">
				<tr>
					<td>					
						<table>
							<tr>
								<td>
									Enable Monthly Deposits
								</td>
								<td>
									<input type="checkbox" name="conf_enabled_deposit" value="1" <?= $systemConfiguration->isMonthlyDepositSchemeEnabled() ? ' checked="checked"' : '' ?>/>
								</td>
							</tr>
							<?php
								foreach ($discountsDeposits as $discountDeposit) 
								{
									echo '							<tr>' ."\n";
									echo '								<td>' ."\n";
									echo '								 	' . $discountDeposit->getMonthName() ."\n";
									echo '								</td>' ."\n";
									echo '								<td>' ."\n";
									echo '								 	<input type="text" size="4" name="deposit_' . $discountDeposit->getMonthNumber() . '" value="' .  htmlentities($discountDeposit->depositPercent) . '"/>%' . "\n";
									echo '								</td>' ."\n";
									echo '							</tr>' ."\n";
								} 
							?>							
						</table>					
					</td>
					<td>
						<table>
							<tr>
								<td>
									Enable Monthly Discounts
								</td>
								<td>
									<input type="checkbox" name="conf_enabled_discount" value="1" <?= $systemConfiguration->isMonthlyDiscountSchemeEnabled() ? ' checked="checked"' : '' ?>/>
								</td>
							</tr>
							<?php
								foreach ($discountsDeposits as $discountDeposit) 
								{
									echo '							<tr>' ."\n";
									echo '								<td>' ."\n";
									echo '								 	' . $discountDeposit->getMonthName() ."\n";
									echo '								</td>' ."\n";
									echo '								<td>' ."\n";
									echo '								 	<input type="text" size="4" name="discount_' . $discountDeposit->getMonthNumber() . '" value="' .  htmlentities($discountDeposit->discountPercent) . '"/>%' . "\n";
									echo '								</td>' ."\n";
									echo '							</tr>' ."\n";
								} 
							?>							
						</table>					
					</td>				
				</tr>
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
