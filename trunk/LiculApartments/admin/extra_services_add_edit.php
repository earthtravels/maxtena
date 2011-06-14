<?php
include ("access.php");
include ("../includes/SystemConfiguration.class.php");

global $logger;
$extraService = new ExtraService();
$errors = array();

// If we have id, then we are trying to edit
if (isset ($_POST['SBMT_REG']))
{
	$extraService = ExtraService::fetchFromParameters($_POST);
	if (!$extraService->save())
	{
		$errors = $extraService->errors;
	}
	else
	{		
		header("Location: extra_services_list.php");	
	}	
}
else if (isset($_GET['id']) && is_numeric($_GET['id']))
{	
	$id = $_GET['id'];
	$extraService = ExtraService::fetchFromDb($id);
	if ($extraService == null)
	{	
		$logger->LogError("Invalid request. No extra service with id: $id exists.");
		$errors[] = "Invalid request. No extra service with id: $id exists.";
	}			
}

include ("header.php");
?>



</td>
</tr>
<tr>
	<td valign="top" align="left" width="100%">
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
	<form name="extra_services_add_edit" action="extra_services_add_edit.php"
		method="post" onsubmit="return validateExtraService();">
		<input type="hidden" name="id" value="<?= $extraService->id ?>" />
		<fieldset>	
		    <legend class="TitleBlue11pt">Extra Service</legend>
			<table cellpadding="0" cellspacing="0" border="0" width="100%" class="TitleBlue11pt">		
				<?php
			        $languages = Language::fetchAllFromDbActive();	                                        	
			        foreach ($languages as $language) 
			        {	                                        		
			    ?>
			    		<tr>
							<td><img src="../graphics/language_icons/<?=$language->languageCode?>.png" title="<?=$language->languageName?>" alt="<?=$language->languageName?>" />&nbsp; <?= $language->languageName ?> Service Name</td>
		                    <td width="70%"><input type="text" size="70%" name="service_name_<?= $language->languageCode ?>" value="<?= $extraService->getName($language->languageCode) ?>" /></td>
		                </tr>			    		                      
			    <?php 
			        }
			    ?>				
                <tr>
					<td>Charge per Night?</td>
                    <td><input type="checkbox" name="is_nightly" id="is_nightly" value="1" <?= $extraService->isNightly ? 'checked' : '' ?> onchange="return isNightlyChecked()" /></td>
                </tr>
                <tr>
					<td>Maximum Number Available</td>					
                    <td><input type="text" size="70%" name="max_available" id="max_available" value="<?= $extraService->maxNumberAvailable ?>" <?= $extraService->isNightly ? 'disabled="disabled" style="background-color: #D0D0D0;"' : '' ?> /></td>
                </tr>
                <tr>
					<td class="TitleBlue11pt">Price</td>
                    <td><input type="text" size="70%" name="price" value="<?= $extraService->price ?>" /></td>
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
	<!--################################################# --> <br />
	<!--################################################# --></td>
</tr>
<?php
include ("footer.php");
?>
</table>
</body>
<script type="text/javascript" src="../scripts/jquery-1.2.6.min.js"></script>
<script type="text/javascript" src="../scripts/date.js"></script>
<script type="text/javascript" src="../scripts/jquery.datePicker.js"></script>
<script type="text/javascript">
String.prototype.mytrim = function() { return this.replace(/^\s\s*/, '').replace(/\s\s*$/, '') };

function validateExtraService()
{
	if(document.extra_services_add_edit.service_name_en.value.mytrim().length == 0
			|| document.extra_services_add_edit.service_name_hr.value.mytrim().length == 0
			|| document.extra_services_add_edit.service_name_de.value.mytrim().length == 0
			|| document.extra_services_add_edit.service_name_it.value.mytrim().length == 0)
	{
		alert('Service name cannot be blank!');
		return false;
	}		
	if(document.extra_services_add_edit.price.value.mytrim().length == 0 || isNaN(document.extra_services_add_edit.price.value))
	{
		alert('Price cannot be blank and should be a number/decimal!');
		return false;
	}
	if(document.extra_services_add_edit.max_available.value.mytrim().length == 0 || isNaN(document.extra_services_add_edit.max_available.value))
	{
		alert('maximum number available cannot be blank and should be a number/decimal!');
		return false;
	}	
}

function isNightlyChecked()
{
	if (document.extra_services_add_edit.is_nightly.checked)
	{
		document.extra_services_add_edit.max_available.value = 0;
		document.extra_services_add_edit.max_available.disabled = true;
		document.extra_services_add_edit.max_available.style.background = '#D0D0D0';
	}
	else
	{
		document.extra_services_add_edit.max_available.disabled = false;
		document.extra_services_add_edit.max_available.style.background = '#FFFFFF';
	}
}
</script>

</html>
