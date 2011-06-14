<?php
// TODO: umcomment
include ("access.php");
require_once ("../includes/SystemConfiguration.class.php");
global $systemConfiguration;
global $logger;


$errors = array();

$logger->LogDebug("Fetching all news categories ...");
$newsCategories = NewsCategory::fetchAllFromDb();
if ($newsCategories == null)
{
	$logger->LogError("There were errors fetching nerws categories.");
	foreach (NewsCategory::$staticErrors as $error) 
	{
		$logger->LogError($error);
		$errors[] = $error;
	}
}

$logger->LogDebug("Fetching default language ...");
$defaultLanguage = Language::fetchDefaultLangauge();
if ($defaultLanguage == null)
{
	$logger->LogError("There were errors fetching default language.");
	foreach (Language::$staticErrors as $error) 
	{
		$logger->LogError($error);
		$errors[] = $error;
	}
}
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
	?>	
		<fieldset>	
		    <legend class="TitleBlue11pt">News Categories</legend>
		    <table width="100%" cellspacing="1" border="0" cellpadding="3">
				<tr bgcolor="#747471">
					<td scope="col" align="left"><b><font color="white"
						face="Verdana, Arial, Helvetica, sans-serif" size="2">Title</font></b>
					</td>					
					<td scope="col" class="bodytext_h">&nbsp;</td>
				</tr>
			<?php 
					foreach ($newsCategories as $newsCategory) 
					{
						if (!($newsCategory instanceof NewsCategory))
						{
							continue;
						}
						echo '<tr class="odd" bgcolor="#f2eaeb">' . "\n";
						echo '	<td align="left"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">' . htmlentities($newsCategory->title->getText($defaultLanguage->languageCode)) . '</font></td>' . "\n";						
						echo '	<td align="left"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">';
						echo '		<a href="news_categories_add_edit.php?id=' . $newsCategory->id . '" style="text-decoration:none"><font color="#990000"  face="Verdana, Arial, Helvetica, sans-serif" size="2" >Edit</font></a>&nbsp;&nbsp;';
						echo '		<a href="news_categories_delete.php?id=' . $newsCategory->id . '" style="text-decoration:none" onclick = "if (! confirm(\'Are you sure?\')) { return false; }"><font color="#990000"  face="Verdana, Arial, Helvetica, sans-serif" size="2" >Delete</font></a>';
						echo '	</td>' . "\n";
						echo "</tr>\n";	;
					}

					if(sizeof($newsCategories) == 0)
					{
						echo '<tr><td colspan="7">No news categories are defined yet!</td></tr>' . "\n";
					}
			?>
			</table>							   
	    </fieldset>	
	    <table width="100%">
			<tr  bgcolor="#ffffff" class="TitleBlue11pt">
	            <td height="20" align="center">
	                <input type="image" value="1" src="images/button_add.png"  name='SBMT_REG'  onclick="javascript:window.location.href='news_categories_add_edit.php'">
	            </td>
	        </tr>
		</table>   
    </td>
  </tr>
  <?php include("footer.php"); ?>
</table>


</body>
</html>
