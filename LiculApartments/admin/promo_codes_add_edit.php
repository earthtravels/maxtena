<?php
// TODO: Uncomment
//include_once ("access.php");
include_once ("../includes/SystemConfiguration.class.php");

global $systemConfiguration;
global $logger;

$errors = array();
$message = "";

$promoCode = new PromoCode();
if(isset($_POST['SBMT_REG']))
{
	$logger->LogInfo("Form has been submitted.");
	$promoCode = PromoCode::fetchFromParameters($_POST);
	if (!$promoCode->save())
	{
		$logger->LogError("Error saving promo code.");
		foreach ($promoCode->errors as $error) 
		{
			$logger->LogError($error);
			$errors[] = $error;
		}
	}	
	else
	{
		header ("Location: promo_codes_list.php");
	}
}
else if (isset($_REQUEST['id']) && is_numeric($_REQUEST['id']))
{
	$logger->LogInfo("Page was called for edit of id: " . $_REQUEST['id']);
	$id = intval($_REQUEST['id']);
	$logger->LogDebug("Numeric id is: $id");
	$promoCode = PromoCode::fetchFromDb($id);
	if ($promoCode == null)
	{
		$logger->LogError("Invalid request. No promo code with id: $id exists.");
		$errors[] = "Invalid request. No promo code with id: $id exists.";
	}
}
include ("header.php");
?>

<!-- jquery.datePicker.js -->
<script type="text/javascript" src="../scripts/jquery-1.2.6.min.js"></script>
<script type="text/javascript" src="../scripts/date.js"></script>
<script type="text/javascript" src="../scripts/jquery.datePicker.js"></script>
<!-- datePicker required styles -->
<link rel="stylesheet" type="text/css" media="screen" href="../css/datePicker.css">
<!-- page specific styles -->
<link rel="stylesheet" type="text/css" media="screen" href="../css/date.css">
<!-- page specific scripts -->
<script type="text/javascript" charset="utf-8">
Date.firstDayOfWeek = 0;
Date.format = 'mm/dd/yyyy';
$(function()
{	
	$('.date-pick').datePicker();
	$('#exp-date').bind(
		'dpClosed',
		function(e, selectedDates)
		{			
		}
	);
	
});

$(document).ready(function() {
	$('#promo_category').change(function() { 
		if($('#promo_category').val() != 2)
		{
			$('#customer_email').val("");
		}	
	});
});
</script>
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
	<input type="hidden" name="id" value="<?= $promoCode->id ?>" />
		<fieldset>	
		    <legend class="TitleBlue11pt">Promotional Code</legend>
		    <table width="100%" cellspacing="1" border="0" cellpadding="3">	                                        
				<tr class="TitleBlue11pt">
					<td valign="middle" align="left" width="22%">
						Promo Code
					</td>
					<td align="left" valign="middle">
						<input type="text" name="promo_code" value="<?= $promoCode->promoCode ?>" size="80"/>
					</td>
				</tr>
				<tr class="TitleBlue11pt">
					<td valign="middle" align="left" width="22%">
						Discount Amount
					</td>
					<td align="left" valign="middle">
						<input type="text" name="discount" value="<?= $promoCode->discountAmount ?>" size="80"/>						
					</td>
				</tr>
				<tr class="TitleBlue11pt">
					<td valign="middle" align="left" width="22%">
						Discount Type
					</td>
					<td align="left" valign="middle">						
						<input type="radio" name="is_percentage" value="1"<?= $promoCode->isPercentage ? ' checked="checked"' : '' ?>>Percent Off&nbsp;
						<input type="radio" name="is_percentage" value="0"<?= $promoCode->isReusable ? '' : ' checked="checked"' ?>>Amount Off
					</td>
				</tr>
				<tr class="TitleBlue11pt">
					<td valign="middle" align="left" width="22%">
						Minimum Purchase Amount
					</td>
					<td align="left" valign="middle">
						<input type="text" name="min_amount" value="<?= $promoCode->minAmount ?>" size="80"/>						
					</td>
				</tr>
				<tr class="TitleBlue11pt">
					<td valign="middle" align="left" width="22%">
						Minimum Booked Nights
					</td>
					<td align="left" valign="middle">
						<input type="text" name="min_nights" value="<?= $promoCode->minNights ?>" size="80"/>						
					</td>
				</tr>
				<tr class="TitleBlue11pt">
					<td valign="middle" align="left" width="22%">
						Expiration Date
					</td>
					<td align="left" valign="middle">						 
						<input type="text" id="exp-date" name="exp_date" class="date-pick" value="<?= ($promoCode->expirationDate == null ? '' : $promoCode->expirationDate->format('m/d/Y')) ?>" size="80"/>						
					</td>
				</tr>
				<tr class="TitleBlue11pt">
					<td valign="middle" align="left" width="22%">
						Applicability
					</td>
					<td align="left" valign="middle">
						<select name="promo_category" id="promo_category">
							<option value="0"<?= $promoCode->category == 0 ? ' selected' : '' ?>>All customers</option>
							<option value="1"<?= $promoCode->category == 1 ? ' selected' : '' ?>>Only existing customers</option>
							<option value="3"<?= $promoCode->category == 3 ? ' selected' : '' ?>>Only new customers</option>
							<option value="2"<?= $promoCode->category == 2 ? ' selected' : '' ?>>One specific customer (enter email address below)</option>							 
						</select>												
					</td>
				</tr>
				<tr class="TitleBlue11pt">
					<td valign="middle" align="left" width="22%">
						Customer Email
					</td>
					<td align="left" valign="middle">						 
						<input type="text" id="customer_email" name="customer_email" value="<?= $promoCode->customerEmail ?>" size="80"/>						
					</td>
				</tr>
				<tr class="TitleBlue11pt">
					<td valign="middle" align="left" width="22%">
						Reusable
					</td>
					<td align="left" valign="middle">						
						<input type="radio" name="reuse_promo" value="1"<?= $promoCode->isReusable ? ' checked="checked"' : '' ?>>Yes&nbsp;
						<input type="radio" name="reuse_promo" value="0"<?= $promoCode->isReusable ? '' : ' checked="checked"' ?>>No
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
</body></html>