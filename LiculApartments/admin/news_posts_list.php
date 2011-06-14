<?php
// TODO: umcomment
include ("access.php");
require_once ("../includes/SystemConfiguration.class.php");
global $systemConfiguration;
global $logger;


$errors = array();
$newsPosts = array();

$page = 1;
$postsPerPage = $systemConfiguration->getAdminItemsPerPage();
$lastPage = 1;
if (isset($_GET['page']) && is_numeric($_GET['page']))
{
	$page = intval($_GET['page']);			
}

// Validate page number
if ($page < 1)
{
	$page = 1;
}
else
{
	$count = NewsPost::count();		
	$lastPage = max(ceil($count/$postsPerPage), 1);
	if ($page > $lastPage)
	{
		$page = $lastPage;
	}
}

$logger->LogDebug("Fetching news posts for page $page ...");
$newsPosts = NewsPost::fetchFromDbPage($page, true);
//if ($newsPosts == null)
//{
//	$logger->LogError("There were errors fetching news posts.");
//	foreach (NewsPost::$staticErrors as $error) 
//	{
//		$logger->LogError($error);
//		$errors[] = $error;
//	}
//}

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
		    <legend class="TitleBlue11pt">News Posts</legend>
		    <table width="100%" cellspacing="1" border="0" cellpadding="3">
				<tr bgcolor="#747471">
					<td scope="col" align="left"><b><font color="white"
						face="Verdana, Arial, Helvetica, sans-serif" size="2">Title</font></b>
					</td>					
					<td scope="col" align="left"><b><font color="white"
						face="Verdana, Arial, Helvetica, sans-serif" size="2">Contents</font></b>
					</td>
					<td scope="col" align="left"><b><font color="white"
						face="Verdana, Arial, Helvetica, sans-serif" size="2">Poster</font></b>
					</td>
					<td scope="col" align="left"><b><font color="white"
						face="Verdana, Arial, Helvetica, sans-serif" size="2">Posted Date</font></b>
					</td>
					<td scope="col" class="bodytext_h">&nbsp;</td>
				</tr>
			<?php 
					foreach ($newsPosts as $newsPost) 
					{
						if (!($newsPost instanceof NewsPost))
						{
							continue;
						}
						echo '<tr class="odd" bgcolor="#f2eaeb">' . "\n";
						echo '	<td align="left"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">' . htmlentities($newsPost->title->getText($defaultLanguage->languageCode)) . '</font></td>' . "\n";
						$postContents = substr($newsPost->contents->getText($defaultLanguage->languageCode), 0, 200);
						if (strlen($newsPost->contents->getText($defaultLanguage->languageCode)) > 200)
						{
							$postContents.= " ...";
						}											
						echo '	<td align="left"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">' . htmlentities($postContents) . '</font></td>' . "\n";
						echo '	<td align="left"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">' . htmlentities($newsPost->posterName) . '</font></td>' . "\n";
						echo '	<td align="left"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">' . htmlentities($newsPost->postedDate->formatMySql()) . '</font></td>' . "\n";
						echo '	<td align="left"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">';
						echo '		<a href="news_posts_add_edit.php?id=' . $newsPost->id . '" style="text-decoration:none"><font color="#990000"  face="Verdana, Arial, Helvetica, sans-serif" size="2" >Edit</font></a>&nbsp;&nbsp;';
						echo '		<a href="news_posts_delete.php?id=' . $newsPost->id . '" style="text-decoration:none" onclick = "if (! confirm(\'Are you sure?\')) { return false; }"><font color="#990000"  face="Verdana, Arial, Helvetica, sans-serif" size="2" >Delete</font></a>';
						echo '	</td>' . "\n";
						echo "</tr>\n";	;
					}

					if(sizeof($newsPosts) == 0)
					{
						echo '	<td colspan="5" align="left"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="2">No news posts are defined yet!</font></td>' . "\n";
					}
			?>
				<tr><td colspan="5">&nbsp;</td></tr>
				<tr>
					<td colspan="5" align="center">
						<div style="text-align: center; font-family: Arial, Helvetica, sans-serif;">
                        	<ul id="pagination-digg" class="paginate">
							<?php
//						if ($lastPage > 1)
//						{							
//							if ($page == 1)
//							{
//								echo "&lt;&lt; Previous" . "&nbsp;";                            			
//							}
//							else
//							{
//								echo '<a href="' . $_SERVER['PHP_SELF'] . '?page=' . ($page - 1) . '">&lt;&lt; Previous</a>&nbsp;';                            			
//							}
//							for ($i = 1; $i <= $lastPage; $i++) 
//							{
//								if ($i == $page)
//								{
//									echo $page . "&nbsp;";
//								}
//								else 
//								{
//									echo '<a href="' . $_SERVER['PHP_SELF'] . '?page=' . $i . '">' . $i . '</a>&nbsp;';
//								}	                            		
//							}
//							if ($page == $lastPage)
//							{
//								echo "Next &gt;&gt;" . "&nbsp;";                            			
//							}
//							else
//							{
//								echo '<a href="' . $_SERVER['PHP_SELF'] . '?page=' . ($page + 1) . '">Next &gt;&gt;</a>&nbsp;';                            			
//							}
//						}

								if ($lastPage > 1)
								{							
									if ($page == 1)
									{
										echo '<li class="previous-off">&lt;&lt; Previous</li>';                            			
									}
									else
									{
										echo '<li class="previous"><a href="' . $_SERVER['PHP_SELF'] . '?page=' . ($page - 1) . '"> &lt;&lt; Previous</a></li>';								                            			
									}
									for ($i = 1; $i <= $lastPage; $i++) 
									{
										if ($i == $page)
										{
											echo '<li class="active">'. $i . '</li>';									
										}
										else 
										{
											echo '<li><a href="' . $_SERVER['PHP_SELF'] . '?page=' . $i . '">'. $i . '</a></li>';									
										}	                            		
									}
									if ($page == $lastPage)
									{
										echo '<li class="next-off">Next &gt;&gt;</li>';								                            			
									}
									else
									{
										echo '<li class="next"><a href="' . $_SERVER['PHP_SELF'] . '?page=' . ($page + 1) . '">Next &gt;&gt;</a></li>';								                            			
									}
								}
							?>
							</ul>
                    	</div>
					</td>
				</tr>
			</table>							   
	    </fieldset>	
	    <table width="100%">
			<tr  bgcolor="#ffffff" class="TitleBlue11pt">
	            <td height="20" align="center">
	                <input type="image" value="1" src="images/button_add.png"  name='SBMT_REG'  onclick="javascript:window.location.href='news_posts_add_edit.php'">
	            </td>
	        </tr>
		</table>   
    </td>
  </tr>
  <?php include("footer.php"); ?>
</table>


</body>
</html>
