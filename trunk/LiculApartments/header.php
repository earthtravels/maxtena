<?php
global $systemConfiguration;
global $logger;
$logger->LogInfo(__FILE__);

function outputMenuItemRecurse($parentId, $pad, $language_selected)
{
	global $logger;
	$logger->LogDebug("Getting menu items for parent: $parentId ...");
	$pages = PageContents::fetchFromDbActiveForParent($parentId);	   
	if (sizeof($pages) > 0)
	{
		$logger->LogDebug("Found matches.");
		echo $pad . "<ul>\n";
		foreach ($pages as $page) 
		{
			if (!($page instanceof PageContents))
			{
				continue;	
			}
			$title = $page->title->getText($language_selected);
			$logger->LogDebug("	Title=$title");
			$logger->LogDebug("	URL=" . $page->getUrl());
			echo $pad . '    <li><a href="' . $page->getUrl() . '">' . $page->title->getText($language_selected) . '</a>' . "\n";
			outputMenuItemRecurse($page->id, $pad . "	", $language_selected);		
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
                        	<span class="title"><?= $systemConfiguration->getSiteTitle() ?></span> 
                        	<span class="subtitle"><?=HEADER_SLOGAN?></span> </a>
                    </div>
                </div>
                <div style="float: right; padding-right: 5px; padding-top: 5px;">
				<?php
					$logger->LogDebug("Getting languages ...");
					$langauges = Language::fetchAllFromDbActive();
					if ($langauges == null)
					{
						die (Language::$staticErrors);
					}
					
					foreach ($langauges as $language) 
					{
						if (!($language instanceof Language))
						{
							continue;
						}
						
				?>
						<a href="index.php?lang=<?= $language->languageCode ?>"
						   title="<?=$language->languageName?>">
						   <img src="images/<?=$language->languageCode?>.png"
						   		title="<?=$language->languageName?>" alt="<?=$language->languageName?>" />
						</a>&nbsp;
				<?php
					}
				?>					
				</div> 
				<div id="main_menu">								               
					<?php
						$logger->LogDebug("Output menu itmes ...");
						outputMenuItemRecurse(0, "                ", $language_selected)					
					?>
				</div>                
                <!-- /#main_menu -->
            </div>