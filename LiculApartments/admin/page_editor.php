<?php
// TODO: Uncomment
include ("access.php");
include_once ("../includes/SystemConfiguration.class.php");

global $systemConfiguration;
global $logger;

$errors = array();
$message = "";
$page = new PageContents();
$logger->LogInfo("PageContents:");
if(isset($_POST['SBMT_REG']))
{
	$logger->LogInfo("Form was submitted. Fetching from for parameters:");	
    $logger->LogInfo($_POST);
	$page = PageContents::fetchFromParameters($_POST);
	if (!$page->save())
	{
		foreach ($page->errors as $error) 
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
	$logger->LogInfo("Initial request - fetching from for parameters:");
	$id = intval($_REQUEST['id']);
	$page = PageContents::fetchFromDb($id);
}


$defaultLanguage = Language::fetchDefaultLangauge();

$light="#666666"; 

function outputOptionItem($parentPage, $allPages, $language, $indent, $id)
{
	foreach ($allPages as $page) 
	{
		if ($page->parentId == $parentPage->id)
		{
			$option = "<option value='" . $page->id . "'" . ($page->id == $id ? " selected" : "") . ">" . $indent . htmlentities($page->title->getText($language->languageCode)) . "</option>\n";
			echo $option;
			outputOptionItem($page, $allPages, $language, "    " . $indent, $id);			
		}
	}	
}

include ("header.php");
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
     <table width="99%" border="0" align="center" cellspacing="1" cellpadding="4" bgcolor="<?=$light?>" style="border:solid 1px <?=$light?>">
                                    <form method="post" action="<?=$_SERVER['PHP_SELF']?>" enctype="multipart/form-data">
                                    	<input type="hidden" value="1" name="SBMT_REG" >
                                    	<input type="hidden" name="id" value="<?=$page->id?>">
                                     		 <tr class="header_tr">
                                        		<td height="30" width="100%" colspan="2" class="big_title"><a title="Back to Contents Management" href="javascript:window.parent.location.href='content.list.php'" class="big_title">CONTENT MANAGEMENT</a> &gt;
                                          			<?=$page->title->getText($defaultLanguage->languageCode)?>
                                        		</td>
                                      		</tr>
                                      		<tr bgcolor="#ffffff" class="lnk">
                                        		<td  valign="top" width="20%">
                                        			Menu Title <a class="lnkred">*</a>
                                        		</td>
                                        		<td align="left">
                                        			<table cellpadding="5">
			                                        <?php
			                                        	$languages = Language::fetchAllFromDbActive();	                                        	
			                                        	foreach ($languages as $language) 
			                                        	{	                                        		
			                                        ?>
			                                        		<tr>
			                                        			<td align="center">
			                                        				<img src="../graphics/language_icons/<?=$language->languageCode?>.png" border="0"  title="<?=$language->languageName?>" alt="<?=$language->languageName?>"  align="middle" />
		                                        				</td>			   
		                                        				<td align="center">
		                                        					<input type="text" name="title_<?=$language->languageCode?>"  size="55" value="<?=htmlentities($page->title->getText($language->languageCode))?>" />
	                                        					</td>			                                        		
			                                        		</tr>
			                                        <?php 
			                                        	}										
		                                        	?>
		                                        	</table>
                                        		</td>
                                      		</tr>
                                      		<tr class=lnk bgcolor=#ffffff>
                                        		<td valign=top>
                                        			Menu Display Order
                                        		</td>
                                        		<td align="left">
                                        			<input type="text" size=5 name="ord" value="<?=$page->displayOrder?>"  />
                                          		</td>
                                      		</tr>                                      		
                                      		<tr bgcolor="#ffffff" class="lnk">
                                        		<td  valign="top" width="20%" >
                                        			Parent Header (Optional) 
                                        		</td>
                                        		<td align="left">
                                        			<select name="parent_id" size="1" style="width:50%; height:20">
                                        				<option value="0">Select Header-----&gt;</option>
                                            			<?php
                                            				$pages = PageContents::fetchFromDbAllActive();                                            				
                                            				foreach ($pages as $currentPage) 
                                            				{
                                            					if ($currentPage->parentId == 0)
                                            					{
                                            						echo "<option value='" . $currentPage->id . "'" . ($currentPage->id == $page->parentId ? " selected" : "") . ">" . htmlentities($currentPage->title->getText($defaultLanguage->languageCode)) . "</option>\n";
                                            						outputOptionItem($currentPage, $pages, $defaultLanguage, "|----", $page->parentId);
                                            					}                                            					
                                            				}										
														?>
                                          			</select>
                                          		</td>
                                      		</tr>
                                      		<tr bgcolor="#ffffff" class="lnk">
                                        		<td  valign="top" width="20%">
                                        			Status 
                                        		</td>
                                        		<td align="left">
                                        			<input type="radio" value='Y' name="status" <?= $page->isVisible ? ' checked="checked"' : '' ?>/>
                                          			Visible
                                          			<input type="radio" name="status" value='N' <?= $page->isVisible ? '' : ' checked="checked"' ?>/>
                                          			Hidden
                                          		</td>
                                      		</tr>
                                      		<tr bgcolor="#ffffff" class="lnk">
                                        		<td  valign="top" width="20%">
                                        			Template Type 
                                        		</td>
                                        		<td align="left">
                                        			<input type="radio" name="template_type" value="0"  <?= $page->templateType == 0 ? ' checked="checked"' : '' ?>/>
                                          			Blank Page (<a href="images/template_blank_page.jpg" target="_blank">Sample</a>)
                                          			<input type="radio" name="template_type" value="1" <?= $page->templateType == 0 ? '' : ' checked="checked"' ?>/>
                                          			Page with Side Boxes (<a href="images/template_sidebox_page.jpg" target="_blank">Sample</a>)
                                          		</td>
                                      		</tr>
                                      		<tr bgcolor="#ffffff" class="lnk">
                                        		<td valign="top" >
                                        			URL
                                        		</td>
                                        		<td align="left">
                                        			<input type=text name="url" value="<?= $page->url ?>" size="55" >
                                        		</td>
                                      		</tr>
                                      		<tr bgcolor="#ffffff" class="lnk">
                                        		<td valign="top" colspan="2" align="left">
                                        			Page Contents (<font color="#CE8EA2">Not required if URL field is specified</font>)
                                        		</td>
                                      		</tr>
                                      		<tr  bgcolor="#ffffff" class="lnk">
                                        		<td colspan="2" height="400">
                                        			<?php
			                                        	$languages = Language::fetchAllFromDbActive();	                                        	
			                                        	foreach ($languages as $language) 
			                                        	{	                                        		
			                                        ?>
			                                        		<br />
			                                        		<b><?= $language->languageName ?></b> <img src="../graphics/language_icons/<?=$language->languageCode?>.png" border="0"  title="<?=$language->languageName?>" alt="<?=$language->languageName?>"  align="absmiddle"/><br />
			                                        		<br />
                                        					<textarea class="ckeditor" name="contents_<?=$language->languageCode?>"  ><?= htmlentities($page->contents->getText($language->languageCode))?></textarea>
                                        					<hr>
			                                        <?php 
			                                        	}										
		                                        	?>                                        
                                        		</td>
                                      		</tr>
                                      		<tr>
                                        		<td colspan="2" height="20" align="center">                                        			 
                                        			<input type="image" value="1" src="images/button_save.png">
                                         		</td>
                                      		</tr>
                                    </form>
                                  </table>
  </td>
</tr>
<?php include("footer.php"); ?>
</table>
<br />
</body></html>