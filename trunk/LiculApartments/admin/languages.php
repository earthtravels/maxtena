<?php
// TODO: umcomment
include ("access.php");
require_once ("../includes/SystemConfiguration.class.php");
global $systemConfiguration;


$errors = array();
$message = "";

if (sizeof($_POST) > 0 && isset($_POST['form_submit']))
{
	$languages = Language::fetchAllFromDb();	
	foreach ($languages as $language) 
	{
		if (!($language instanceof Language))
		{
			continue;
		}
		$language->languageName = trim($_POST[$language->languageCode . '_language_name']);
		$language->languageCode = trim(strtolower($_POST[$language->languageCode . '_language_code']));
		$language->fileName = trim($_POST[$language->languageCode . '_language_file_name']);
		$language->isActive = isset($_POST[$language->languageCode . '_is_active']) && intval($_POST[$language->languageCode . '_is_active']) == 1;
		$language->isDefault = isset($_POST['is_default']) && $language->languageCode == $_POST['is_default'];
		$language->displayOrder = intval($_POST[$language->languageCode . '_display_order']);		
		if(!$language->save())
		{
			foreach ($language->errors as $error) 
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



$languages = Language::fetchAllFromDb();
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
		    <legend class="TitleBlue11pt">Languages</legend>			
			<table width="100%">
				<tr>
					<td class="TitleRed11pt">
						Language
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
				foreach ($languages as $language) 
				{
			?>
					<tr>
						<td>
							<input type="text" name="<?= $language->languageCode ?>_language_name" value="<?= $language->languageName ?>" />
						</td>					
						<td>
							<img src="../images/<?= $language->languageCode ?>.png" alt="" />
						</td>					
						<td>
							<input type="text" name="<?= $language->languageCode ?>_language_code" value="<?= $language->languageCode ?>" />
						</td>					
						<td>
							<input type="text" name="<?= $language->languageCode ?>_language_file_name" value="<?= $language->fileName ?>" />
						</td>					
						<td>
							<input type="checkbox" name="<?= $language->languageCode ?>_is_active" value="1" <?= $language->isActive ? ' checked="checked"' : '' ?> />
						</td>					
						<td>
							<input type="radio" name="is_default" value="<?= $language->languageCode ?>" <?= $language->isDefault ? ' checked="checked"' : '' ?> />
						</td>					
						<td>
							<input type="text" name="<?= $language->languageCode ?>_display_order" value="<?= $language->displayOrder ?>" />
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
