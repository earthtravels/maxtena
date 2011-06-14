<?php
// TODO: Uncomment
include ("access.php");
include_once ("../includes/SystemConfiguration.class.php");

global $systemConfiguration;
global $logger;

$errors = array();
$message = "";

$faq = new Faq();
if(isset($_POST['SBMT_REG']))
{
	$logger->LogInfo("Form has been submitted.");
	$faq = Faq::fetchFromParameters($_POST);
	if (!$faq->save())
	{
		$logger->LogError("Error saving faq.");
		foreach ($faq->errors as $error) 
		{
			$logger->LogError($error);
			$errors[] = $error;
		}
	}	
	else
	{
		$message = "Values were updated successfully!";
		header("Location: faqs_list.php");
	}
}
else if (isset($_REQUEST['id']) && is_numeric($_REQUEST['id']))
{
	$logger->LogInfo("Page was called for edit of id: " . $_REQUEST['id']);
	$id = intval($_REQUEST['id']);
	$logger->LogDebug("Numeric id is: $id");
	$faq = Faq::fetchFromDb($id);
	if ($faq == null)
	{
		$logger->LogError("Invalid request. No faq with id: $id exists.");
		$errors[] = "Invalid request. No faq with id: $id exists.";
	}
}

include ("header.php");
$defaultLanguage = Language::fetchDefaultLangauge();
 
?>
<script type="text/javascript" src="../ckeditor/ckeditor.js"></script>
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
		    <legend class="TitleBlue11pt">FAQ</legend>
		    <table width="100%" cellspacing="1" border="0" cellpadding="3">	                                        
		        <input type="hidden" name="id" value="<?= $faq->id ?>" />
		        <?php
			        $languages = Language::fetchAllFromDbActive();	                                        	
			        foreach ($languages as $language) 
			        {	                                        		
			    ?>
			    		<tr class="TitleBlue11pt">
			    			<td colspan="2">
			    				<img src="../graphics/language_icons/<?=$language->languageCode?>.png" border="0"  title="<?=$language->languageName?>" alt="<?=$language->languageName?>" /> <?= $language->languageName ?> Question			            	
			            		<br /><br />
			    				<textarea  class="ckeditor" name="question_<?= $language->languageCode ?>"><?= htmlentities($faq->question->getText($language->languageCode)) ?></textarea>
		            		</td>
			            </tr>                       
			    <?php 
			        }

			        foreach ($languages as $language) 
			        {
			    ?>
			    		<tr class="TitleBlue11pt">
    		    			<td colspan="2">
			    				<img src="../graphics/language_icons/<?=$language->languageCode?>.png" border="0"  title="<?=$language->languageName?>" alt="<?=$language->languageName?>" /> <?= $language->languageName ?> Answer			            	
			            		<br /><br />			            		
			    				<textarea  class="ckeditor" name="answer_<?= $language->languageCode ?>"><?= htmlentities($faq->answer->getText($language->languageCode)) ?></textarea>			            		
		            		</td>
			            </tr>                       
			    <?php 
			        }
		        ?>
		        
		        <tr class="TitleBlue11pt">
	    			<td valign="middle" align="left" width="22%">
	    				Display Order
	            	</td>
	            	<td align="left" valign="middle">
	            		<input type="text" name="display_order" value="<?= $faq->displayOrder ?>" size="100"/>
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