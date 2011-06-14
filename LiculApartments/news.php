<?php
include_once ("includes/SystemConfiguration.class.php");
session_start();
include("includes/language.php");


global $systemConfiguration;
global $logger;


$this_script = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>    
    <meta name="description" content="<?=$systemConfiguration->getSiteDescription()?>" />
	<meta name="keywords" content="<?=$systemConfiguration->getSiteKeywords()?>" />
	<meta http-equiv="Content-Type" content="text/html;charset=<?=CHARSET?>" />    
    <meta name="robots" content="ALL,FOLLOW" />    
    <meta http-equiv="imagetoolbar" content="no" />
    <title><?=$systemConfiguration->getSiteTitle()?></title>
    <link rel="stylesheet" href="css/reset.css" type="text/css" />
    <link rel="stylesheet" href="css/jquery.fancybox.css" type="text/css" />
    <link rel="stylesheet" href="css/nivo-slider.css" type="text/css" />
    <link rel="stylesheet" href="css/screen.css" type="text/css" />    
    <!--[if IE 7]>
		<link rel="stylesheet" type="text/css" href="http://www.ait.sk/simplicius/html/css/ie7.css" />
	<![endif]-->
	
	<script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/jquery.validate.js"></script>
    <script type="text/javascript" src="js/jquery.fancybox.js"></script>
    <script type="text/javascript" src="js/jquery.nivo.js"></script>
    <script type="text/javascript" src="js/cufon.js"></script> 
    <script type="text/javascript" src="./js/geometr231_hv_bt_400.font.js"></script>   
    <script type="text/javascript" src="js/script.js"></script>	
</head>
<body class="light">    
    <div class="back">
        <div class="base">
			<?php include("header.php"); ?>
			<div class="page_top">				              
			</div>
			<div class="page">
                <div class="page_inside clear">
                    <div class="subpage clear">
                        <!-- ****** SIDEBAR ****** -->
                        <div id="sidebar">
                            <div class="sidebox">
                                <div class="box_top">
                                </div>
                                <div class="sideinner sidesearch">
                                    <form method="post" action="<?= $_SERVER['PHP_SELF'] ?>" class="clear">
                                    <div class="text">
                                        <input type="text" name="keywords" />
                                    </div>                                    
                                    <div class="submit">
                                        <input type="submit" value="Search" />
                                    </div>                                                                        
                                    </form>
                                </div>
                                <div class="box_down">
                                </div>
                            </div>
                            <div class="sidebox">
                                <div class="box_top">
                                </div>
                                <div class="sideinner submenu">
                                    <h2><?= NEWS_CATEGORIES ?></h2>
                                    <?php
                                    	$newsCategories = NewsCategory::fetchFromDbAllUsed($language_selected);
                                    	echo "<ul>\n";
                                    	foreach ($newsCategories as $newsCategory) 
                                    	{
                                    		echo '<li class="page_item"><a href="'. $this_script . '?category_id=' . $newsCategory->id . '">' . $newsCategory->title->getText($language_selected) . '</a></li>';
                                    	}
                                    	echo "</ul>\n";										
									?>                                    
                                </div>
                                <div class="box_down">
                                </div>
                            </div>
                            <div class="sidebox">
                                <div class="box_top">
                                </div>
                                <div class="sideinner submenu">
                                    <h2><?= NEWS_ARCHIVE ?></h2>                                    
                                    	<?php	
                                    	$sql = "SELECT DISTINCT date_format(date_posted, '%Y') as cat_year, date_format(date_posted, '%m') as cat_month FROM bsi_news_posts ORDER BY date_format(date_posted, '%Y-%m') DESC LIMIT 15"; 									
										$query=mysql_query($sql);
										if (!$query)
										{
											$logger->LogError("Error: " . mysql_errno() . ". Error message: " . mysql_error());
											$logger->LogError("SQL: $sql");
											die('Error: ' . mysql_error());
										}
										echo "<ul>\n";																				
										while($cat = mysql_fetch_assoc($query))
										{											
	                                    	//TODO: Output month names in correct language
											//$monthName = strftime("%B", strtotime('2011-' . $cat['cat_month'] . '-01'));
											$monthName = LocalizedCalendar::getMonthName($language_selected, $cat['cat_month']);
											echo '<li class="page_item"><a href="'. $this_script . '?year=' . $cat['cat_year'] . '&month=' . $cat['cat_month'] . '">' . $monthName . ' ' . $cat['cat_year'] . '</a></li>';
										}
										echo "</ul>\n";
										?>
                                </div>
                                <div class="box_down">
                                </div>
                            </div>                           
                        </div>
                        <!-- end of sidebar -->
                        <!-- MAIN -->
                        <div id="main" class="clear">
                            <h1><strong><?= NEWS_NEWS ?></strong></h1>
                            <?php
                            	// Fetch all posts that match supplied criteria (if any)
                            	$searchCriteria = NewsSearchCriteria::fetchFromParameters($_GET);
                            	$newsPosts = $searchCriteria->runSearch();                            
                            
                            	// If no news posts are found, output default message
                            	if (sizeof($newsPosts) == 0)
                            	{
                            ?>
	                            	<div class="post blog_multi">		                                
		                                <div class="post-text">
		                                    <p><?= NEWS_NO_NEWS_WITH_CRITERIA ?></p>
		                                </div>		                                
		                            </div>
                            <?php 
                            	}
                            	else
                            	{                            		
	                            	foreach ($newsPosts as $newsPost) 
	                            	{	   
	                            		$newsCategory = $newsPost->getCategory();                         										
							?>										
			                            <div class="post blog_multi">
			                                <div class="post-header">
			                                    <h2><a href="#"><?= $newsPost->title->getText($language_selected) ?></a></h2>
			                                    <small><?= NEWS_POSTED_ON ?> <?= $newsPost->postedDate->format("m/d/Y") ?> <?= NEWS_POSTED_BY ?> <?= $newsPost->posterName ?></small>
			                                </div>
			                                <div class="post-image">
			                                    <img src="images/<?= $newsPost->imageLarge ?>" alt="" />
			                                </div>
			                                <div class="post-text">
			                                    <?= $newsPost->contents->getText($language_selected) ?>
			                                </div>			                                
			                                <div class="rule">
			                                </div>	                                
			                                <div class="post-info">
			                                    <small><?= NEWS_POSTED_IN ?> <a rel="category tag" title="" href="<?= $_SERVER['PHP_SELF'] . '?category_id=' . $newsPost->categoryId ?>"><?= $newsCategory->title->getText($language_selected) ?></a></small>
			                                </div>
			                                <!-- /.post-info -->
			                                <div class="rule">
			                                </div>
			                            </div>
                            <?php
									} 
                            	}
                            	
                            	// Generate valid page numbers, previous and next links
                            	// TODO: Apply some nice CSS to the page number links 
                            	if ($searchCriteria->totalPages > 1)
                            	{
                            		$queryString = preg_replace("/&?page=[0-9]+/", "", $_SERVER['QUERY_STRING']);
                            		if ($searchCriteria->page == 1)
                            		{
                            			echo NEWS_PREVIOUS . "&nbsp;";                            			
                            		}
                            		else
                            		{
                            			echo '<a href="' . $_SERVER['PHP_SELF'] . '?' . $queryString . '&page=' . ($searchCriteria->page - 1) . '">' . NEWS_PREVIOUS . '</a>&nbsp;';                            			
                            		}
                            		
	                            	for ($i = 1; $i <= $searchCriteria->totalPages; $i++) 
	                            	{
	                            		if ($i == $searchCriteria->page)
	                            		{
	                            			echo $i . "&nbsp;";
	                            		}
	                            		else 
	                            		{
	                            			echo '<a href="' . $this_script . '?' . $queryString . '&page=' . $i . '">' . $i . '</a>&nbsp;';
	                            		}	                            		
	                            	}
	                            	
                            		if ($searchCriteria->page == $searchCriteria->totalPages)
                            		{
                            			echo NEWS_NEXT . "&nbsp;";                            			
                            		}
                            		else
                            		{
                            			echo '<a href="' . $_SERVER['PHP_SELF'] . '?' . $queryString . '&page=' . ($searchCriteria->page + 1) . '">' . NEWS_NEXT . '</a>&nbsp;';                            			
                            		}
                            	}
                            ?>                                                                              
                        </div>                       
                        <!-- end of main -->
                    </div>                    
                </div>
            </div>			
            <div class="page_down">
            </div>
            <?php include("footer.php"); ?>
		</div>
	</div>	
</body>
</html>
