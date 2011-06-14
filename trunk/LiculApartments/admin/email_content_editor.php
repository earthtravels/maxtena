<?php
// TODO: Uncomment
include ("access.php");
include_once ("../includes/SystemConfiguration.class.php");
include ("header.php");

global $systemConfiguration;
global $logger;

$errors = array();
$message = "";

$emailContents = new EmailContents();
if(isset($_POST['SBMT_REG']))
{
	$logger->LogInfo("Form was submitted.");
	$emailContents = EmailContents::fetchFromParameters($_POST);
	if (!$emailContents->save())
	{
		$logger->LogError("Email contents failed to save.");
		foreach ($emailContents->errors as $error) 
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
	$logger->LogInfo("Form was called with id: " .  $_REQUEST['id']);
	$id = intval($_REQUEST['id']);
	$emailContents = EmailContents::fetchFromDb($id);
}


$defaultLanguage = Language::fetchDefaultLangauge();
$languages = Language::fetchAllFromDbActive();
 
?>
<script type="text/javascript" src="../ckeditor/ckeditor.js"></script>
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
	        <input type="hidden" name="id" value="<?= $emailContents->id ?>" />
	        <input type="hidden" name="email_code" value="<?= $emailContents->emailCode ?>" />
	        <?php		        	                                        	
		        foreach ($languages as $language) 
		        {	                                        		
		    ?>
		    		<tr  bgcolor="#ffffff" class="TitleBlue11pt">
		    			<td valign="middle" align="left" width="30%">
		    				<img src="../graphics/language_icons/<?=$language->languageCode?>.png" border="0"  title="<?=$language->languageName?>" alt="<?=$language->languageName?>" /> <?= $language->languageName ?> Email Subject
		            	</td>
		            	<td align="left" valign="middle">
		            		<input type="text" name="email_subject_<?= $language->languageCode ?>" value="<?= htmlentities($emailContents->emailSubject->getText($language->languageCode)) ?>" size="100"/>
	            		</td>
		            </tr>                       
		    <?php 
		        }										
	        ?>	       
	        <tr  bgcolor="#ffffff" class="TitleBlue11pt">
	            <td colspan="2">
	            	Email Contents
	            	<p style="font-size: x-small;">Following tokens are available for use in email content:
	            	<ul style="font-size: x-small;">
	            	<?php
	            		$emailPersonalizer = new EmailPersonalizer(null);
	            		foreach ($emailPersonalizer->terms as $term) 
	            		{
	            			echo '<li style="font-size: x-small;">' . $term . "</li>";
	            		} 
	            	?>	            		
	            	</ul>
	            	</p>
            	</td>
            </tr>	            
	        <tr  bgcolor="#ffffff" class="TitleBlue11pt">
	            <td colspan="2" height="400">
	                <?php				   
	                	reset($languages);     	                                        	
				        foreach ($languages as $language) 
				        {	                                        		
				    ?>
				            <br />
				            <b><?= $language->languageName ?></b> <img src="../graphics/language_icons/<?=$language->languageCode?>.png" border="0"  title="<?=$language->languageName?>" alt="<?=$language->languageName?>"  align="middle"/><br />
				            <br />	                        
	                        <textarea  class="ckeditor" name="email_text_<?=$language->languageCode?>"  ><?=htmlentities($emailContents->emailText->getText($language->languageCode))?></textarea>
				    <?php 
				        }										
			        ?>                                        
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