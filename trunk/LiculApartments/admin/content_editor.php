<?php
// TODO: Uncomment
include ("access.php");
include_once ("../includes/SystemConfiguration.class.php");
include ("header.php");

global $systemConfiguration;
global $logger;

$errors = array();
$message = "";
$content = new Content();
 if(isset($_POST['SBMT_REG']))
{
	$content = Content::fetchFromParameters($_POST);
	if (!$content->save())
	{
		foreach ($content->errors as $error) 
		{
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
	$id = intval($_REQUEST['id']);
	$content = Content::fetchFromDbForId($id);
}


$defaultLanguage = Language::fetchDefaultLangauge();

$light="#666666"; 
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
	        <input type="hidden" name="id" value="<?= $content->id ?>" />
	        <input type="hidden" name="cont_title" value="<?= $content->title ?>" />                                   	
	        <tr bgcolor="#ffffff" class="TitleBlue11pt">
	            <td  valign="top" width="20%">
	                Status 
	            </td>
	            <td align="left">
	                <input type="radio" name="status" value='Y' <?= $content->isVisible ? ' checked="checked"' : '' ?>/>
	                Visible
	                <input type="radio" name="status" value='N' <?= $content->isVisible ? '' : ' checked="checked"' ?>/>
	                Hidden
	            </td>
	        </tr>                                      		
	        <tr bgcolor="#ffffff" class="TitleBlue11pt">
	            <td  valign="top" width="20%">
	                Content Title 
	            </td>
	            <td align="left">
	                <?= $content->title ?>
	            </td>
	        </tr>
	        <tr bgcolor="#ffffff" class="TitleBlue11pt">
	            <td valign="top" colspan="2" align="left">
	                Content
	            </td>
	        </tr>
	        <tr  bgcolor="#ffffff" class="TitleBlue11pt">
	            <td colspan="2" height="400">
	                <?php
				        $languages = Language::fetchAllFromDbActive();	                                        	
				        foreach ($languages as $language) 
				        {	                                        		
				    ?>
				            <br />
				            <b><?= $language->languageName ?></b> <img src="../graphics/language_icons/<?=$language->languageCode?>.png" border="0"  title="<?=$language->languageName?>" alt="<?=$language->languageName?>"  align="absmiddle"/><br />
				            <br />
	                        <textarea  class="ckeditor" name="contents_<?=$language->languageCode?>"  ><?=htmlentities($content->contents->getText($language->languageCode))?></textarea>
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