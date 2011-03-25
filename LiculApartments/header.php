<?php
function outputMenuItem($parentId, $pad, $langauge_selected)
{
	$sql_parent=mysql_query("select * from bsi_site_contents where parent_id=".$parentId." and status='Y' order by ord");
	$rowCount=mysql_num_rows($sql_parent);
    
	if ($rowCount > 0)
	{
		echo $pad . "<ul>\n";	
	  	while($row=mysql_fetch_assoc($sql_parent))
		{
			
			echo $pad . '    <li><a href="' . $row['url'] . '">' . $row['title_'.$langauge_selected] . '</a>' . "\n";
			outputMenuItem($row['id'], $pad . "    ", $langauge_selected);		
			echo $pad . "</li>\n";
		}
		echo $pad . "</ul>\n";
	}
} 
?>
			<div id="header">
                <div id="logo">
                    <div class="picture">
                        <a href="index.php" title="">
                            <img src="images/logo.png" alt="Logo" />
                        </a>
                    </div>
                    <div class="text">
                        <a href="index.php" title="">
                        	<span class="title"><?=$bsiCore->config['conf_hotel_sitetitle']?></span> 
                        	<span class="subtitle"><?=HEADER_SLOGAN?></span> </a>
                    </div>
                </div>
                <div style="float: right; padding-right: 5px; padding-top: 5px;">
				<?php
				$sql_lang = mysql_query ("select * from bsi_language where status=true order by `default` desc, lang_order");
				if (mysql_num_rows ($sql_lang) > 1)
				{
					while ($row_lang = mysql_fetch_assoc ($sql_lang))
					{
						if (isset ($_POST['allowlang']))
						{
							?>
							<a href="index.php?lang=<?=$row_lang['lang_code']?>"
							   title="<?=$row_lang['language']?>">
							   <img src="images/<?=$row_lang['lang_code']?>.png"
							   		title="<?=$row_lang['language']?>" alt="<?=$row_lang['language']?>" />
							</a>&nbsp;
				<?php
						}
						else
						{
							if (strpos ($_SERVER['REQUEST_URI'], 'lang=') > 0)
								$get_url = substr ($_SERVER['REQUEST_URI'], 0, - 8);
							else
								$get_url = $_SERVER['REQUEST_URI'];
							
							if (strpos ($get_url, '?') > 1)
							{
								?>
								<a href="index.php?lang=<?=$row_lang['lang_code']?>"
								   title="<?=$row_lang['language']?>">
								   <img src="images/<?=$row_lang['lang_code']?>.png"
								   		title="<?=$row_lang['language']?>" alt="<?=$row_lang['language']?>" />
								</a>&nbsp;
				        <?php
							}
							else
							{
								?>
								<a href="<?=$get_url?>?lang=<?=$row_lang['lang_code']?>"
								   title="<?=$row_lang['language']?>">
								   <img src="images/<?=$row_lang['lang_code']?>.png"
								   		title="<?=$row_lang['language']?>" alt="<?=$row_lang['language']?>" />
								</a>&nbsp;								      
				<?php
							}
						}
					}
				} 
				?>					
				</div> 
				<div id="main_menu">								               
					<?php
						outputMenuItem(0, "                ", $langauge_selected)					
					?>
				</div>                
                <!-- /#main_menu -->
            </div>
            
			