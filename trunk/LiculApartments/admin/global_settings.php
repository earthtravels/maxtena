<?php
// TODO: umcomment
include ("access.php");
require_once ("../includes/SystemConfiguration.class.php");
global $systemConfiguration;
global $logger;

$errors = array();
$message = "";

if (sizeof($_POST) > 0 && isset($_POST['conf_currency_code']))
{
	$logger->LogInfo("Updating values for global settings ...");
	$details = SystemConfiguration::fetchFromParameters($_POST);
	if ($details->save())
	{
		$message = "Values were succesfully updated.";				
	}	
	else
	{
		$errors = $details->errors;
	}
	$systemConfiguration = $details;
}

include ("header.php");
?>
<script type="text/javascript" src="../scripts/jquery-1.2.6.min.js"></script>
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
	<form action="<?=$_SERVER['PHP_SELF']?>" method="post">
	<fieldset>
		<legend class="TitleBlue11pt">Logo Settings</legend>
		<table cellpadding="5" cellspacing="0" border="0" class="bodytext">
			<tr>
				<td>Logo Title:</td>
				<td>
					<input type="text" name="conf_hotel_logo_title" size="50" width="40"
					value="<?= htmlentities($systemConfiguration->getLogoTitle()) ?>" />
				</td>
			</tr>					
		</table>
	</fieldset>
	<fieldset>
		<legend class="TitleBlue11pt">SEO Settings</legend>
		<table cellpadding="5" cellspacing="0" border="0" class="bodytext">
			<tr>
				<td>Website Title:</td>
				<td>
					<input type="text" name="conf_hotel_sitetitle" size="50" width="40"
					value="<?= htmlentities($systemConfiguration->getSiteTitle()) ?>" />
				</td>
			</tr>
			<tr>
				<td valign="top">Website Description:</td>
				<td><textarea cols="40" rows="3" name="conf_hotel_sitedesc"><?= htmlentities($systemConfiguration->getSiteDescription()) ?></textarea></td>
			</tr>
			<tr>
				<td valign="top">Website Keyword:</td>
				<td>
					<textarea cols="40" rows="3" name="conf_hotel_sitekeywords"><?= htmlentities($systemConfiguration->getSiteKeywords()) ?></textarea>
				</td>
			</tr>			
		</table>
	</fieldset>
	<br />

	<fieldset>
		<legend class="TitleBlue11pt">Currency Settings</legend>
		<table cellpadding="5" cellspacing="0" border="0" class="bodytext">
			<tr>
				<td>Currency Code:</td>
				<td><input type="text" name="conf_currency_code"
					value="<?= htmlentities($systemConfiguration->getCurrencyCode()) ?>" size="10" /></td>
			</tr>
			<tr>
				<td>Currency Symbol (<?= $systemConfiguration->getCurrencySymbol() ?>)</td>
				<td><input type="text" name="conf_currency_symbol"
					value="<?=  htmlentities($systemConfiguration->getCurrencySymbol()) ?>" size="10" /></td>
			</tr>
			<tr>
				<td>Display Currency Symbol Before Amount:</td>
				<td><input type="checkbox" name="conf_currency_before_amount"
					value="1" <?=  $systemConfiguration->istCurrencyBeforeAmount() ? 'checked="checked"' : ""  ?>" /></td>
			</tr>
			<tr>
				<td>Decimal Point:</td>
				<td><input type="text" name="conf_decimal_symbol"
					value="<?=  htmlentities($systemConfiguration->getDecimalPoint()) ?>" size="10" /></td>
			</tr>
			<tr>
				<td>Thousand Separator:</td>
				<td><input type="text" name="conf_thousand_symbol"
					value="<?=  htmlentities($systemConfiguration->getThousandSeparator()) ?>" size="10" /></td>
			</tr>			
		</table>
	</fieldset>
	<br />

	<fieldset><legend class="TitleBlue11pt">Other Settings</legend>
		<table cellpadding="5" cellspacing="0" border="0" class="bodytext">
			<tr>
				<td>Booking Engine:</td>
				<td>
					<select name="conf_booking_turn_off">
						<option value="0" <?= $systemConfiguration->isSearchEgineEnabled() ? "selected" : ""  ?>>On</option>
						<option value="1" <?= $systemConfiguration->isSearchEgineEnabled() ? "" : "selected"  ?>>Off</option>				
					</select>
				</td>
			</tr>
			<tr>
				<td>Hotel Timezone:</td>
				<td>
					<select name="conf_hotel_timezone">
					<?php
						$selected = "";
						foreach (SystemConfiguration::$timeZones as $timeZone => $displayValue) 
						{
							$selected = "";
							if ($systemConfiguration->getTimeZone()->getName() == $timeZone)							
							{
								$selected = " selected";								
							}
							echo '						<option value="' . $timeZone . '"' . $selected . '>' . $displayValue . '</option>' . "\n";
						} 
					?>
					</select>				
				</td>
			</tr>
			<tr>
				<td>Minimum Booking:</td>
				<td>
					<select name="conf_min_night_booking">
					<?php
						for ($i = 1; $i < 15; $i++) 
						{
							$selected = "";
							if ($systemConfiguration->getMinimumNightCount() == $i)							
							{
								$selected = " selected";								
							}
							echo '						<option value="' . $i . '"' . $selected . '>' . $i . '</option>' . "\n";							
						} 
					?>				
					</select> night(s)
				</td>
			</tr>			
			<tr>
				<td nowrap="nowrap">Booking Expiration Time:</td>
				<td>
					<select name="conf_booking_exptime">
					<?php
						foreach (SystemConfiguration::$bookingExpirationTimes as $time => $displayValue) 
						{
							$selected = "";
							if ($systemConfiguration->getBookingExpirationTime() == intval($time))							
							{
								$selected = " selected";								
							}
							echo '						<option value="' . $time . '"' . $selected . '>' . $displayValue . '</option>' . "\n";
						} 
					?>				
					</select>
				</td>
			</tr>
			<tr>
				<td>Tax Rate:</td>
				<td><input type="text" name="conf_tax_amount" size="6"
					value="<?= htmlentities($systemConfiguration->getTaxRate()) ?>" />%
				</td>
			</tr>
		</table>
	</fieldset>
	<br />
	<fieldset>
		<legend class="TitleBlue11pt">Paging Settings</legend>
		<table cellpadding="5" cellspacing="0" border="0" class="bodytext">
			<tr>
				<td>Posts per News Page:</td>
				<td><input type="text" name="conf_news_items_per_page"
					value="<?= htmlentities($systemConfiguration->getNewsItemsPerPage()) ?>" size="10" /></td>
			</tr>
			<tr>
				<td>Items per Page (Admin Section):</td>
				<td><input type="text" name="conf_admin_items_per_page"
					value="<?=  htmlentities($systemConfiguration->getAdminItemsPerPage()) ?>" size="10" /></td>
			</tr>						
		</table>
	</fieldset>
	<br />
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
