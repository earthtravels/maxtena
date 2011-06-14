<?php
// TODO: Uncomment
include ("access.php");
require_once ("../includes/SystemConfiguration.class.php");
global $systemConfiguration;
global $logger;

$contents = Content::fetchAllFromDb();
$defaultLanguage = Language::fetchDefaultLangauge();
include ("header.php");
?>

</td>
  </tr> 
  
  <tr>
    <td valign="top" >    
    <fieldset>
	    <legend class="TitleBlue11pt">Site Contents</legend>
	    	<table width="100%" cellspacing="1" border="0" cellpadding="3">
				<tr bgcolor="#747471">
					<td scope="col" align="left" width="30%"><b><font color="white"
						face="Verdana, Arial, Helvetica, sans-serif" size="2">Page Name</font></b>
					</td>												
					<td scope="col" align="left" width="60%"><b><font color="white"
						face="Verdana, Arial, Helvetica, sans-serif" size="2">Contents</font></b>
					</td>					
					<td scope="col" class="bodytext_h" width="10%">&nbsp;</td>
				</tr>
			<?php 
					foreach ($contents as $content) 
					{
						if (!($content instanceof Content))
						{
							continue;
						}
						echo '<tr class="odd" bgcolor="#f2eaeb">' . "\n";
						echo '	<td align="left"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">' . htmlentities($content->title) . '</font></td>' . "\n";
						echo '	<td align="left"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">' . htmlentities($content->contents->getText($defaultLanguage->languageCode)) . '</font></td>' . "\n";
						echo '	<td align="left"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">';
						echo '<a href="content_editor.php?id=' . $content->id . '" style="text-decoration:none"><font color="#990000"  face="Verdana, Arial, Helvetica, sans-serif" size="2" >Edit</font></a></td>' . "\n";
						echo "</tr>\n";	;
					}

					if(sizeof($contents) == 0)
					{
						echo '<tr><td colspan="3">No contents are defined yet!</td></tr>' . "\n";
					}
			?>
			</table>
    </fieldset>       
    </td>
  </tr>
  <?php include("footer.php"); ?>
</table>
</body>
</html>
