<?php
session_start();
include("includes/db.conn.php");
include("includes/language.php");
include("includes/conf.class.php");

$this_script = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="description" content="<?=$bsiCore->config['conf_hotel_sitedesc']?>" />
	<meta name="keywords" content="<?=$bsiCore->config['conf_hotel_sitekeywords']?>" />
	<meta http-equiv="Content-Type" content="text/html;charset=<?=CHARSET?>" />    
    <meta name="robots" content="ALL,FOLLOW" />    
    <meta http-equiv="imagetoolbar" content="no" />
    <title><?=$bsiCore->config['conf_hotel_sitetitle']?></title>
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
                                    <form method="post" action="<?=  $this_script ?>" class="clear">
                                    <div class="text">
                                        <input type="text" name="keyword" />
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
										$sql_categories=mysql_query("select distinct category_" . $language_selected . " as category from bsi_news_posts order by category_" . $language_selected . " LIMIT 15");
										echo "<ul>\n";
										while($cat=mysql_fetch_assoc($sql_categories))
										{																	
											echo '<li class="page_item"><a href="'. $this_script . '?lang=' . $language_selected . '&cat=' . urlencode($cat['category']) . '">' . $cat['category'] . '</a></li>';
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
										$sql_categories=mysql_query("select distinct date_format(date_posted, '%Y') as cat_year, date_format(date_posted, '%m') as cat_month from bsi_news_posts order by date_format(date_posted, '%Y-%m') LIMIT 15");
										echo "<ul>\n";																				
										while($cat=mysql_fetch_assoc($sql_categories))
										{											
	                                    	//TODO: Output month names in correct language
											$monthName = strftime("%B", strtotime('2011-' . $cat['cat_month'] . '-01'));
											echo '<li class="page_item"><a href="'. $this_script . '?lang=' . $language_selected . '&year=' . $cat['cat_year'] . '&month=' . $cat['cat_month'] . '">' . $monthName . ' ' . $cat['cat_year'] . '</a></li>';
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
                            	$sql_posts="select * from bsi_news_posts ";
                            	$message = NEWS_NO_NEWS_MESSAGE;
                            	if (isset($_GET['cat']))
                            	{
                            		$sql_posts = $sql_posts . " WHERE (category_en = '" . mysql_escape_string(urldecode($_GET['cat'])) . "' OR category_hr = '" . mysql_escape_string(urldecode($_GET['cat'])) . "' OR category_de = '" . mysql_escape_string(urldecode($_GET['cat'])) . "' OR category_it = '" . mysql_escape_string(urldecode($_GET['cat'])) . "')";
                            		$message = NEWS_NO_NEWS_WITH_CRITERIA;                                        		
                            	}
                            	else if (isset($_GET['year']) && is_numeric($_GET['year']) && isset($_GET['month']) && is_numeric($_GET['month']))
                            	{
                            		$sql_posts = $sql_posts . " WHERE  CAST(DATE_FORMAT(date_posted, '%Y') as UNSIGNED) = " . $_GET['year'] . " AND CAST(DATE_FORMAT(date_posted,'%m') as UNSIGNED) = " . $_GET['month'];
                            		$message = NEWS_NO_NEWS_WITH_CRITERIA;                                        		
                            	}
                            	else if (isset($_POST['keyword']))
                            	{
                            		$sql_posts = $sql_posts . " WHERE  (contents_en LIKE '%" . mysql_escape_string(urldecode($_POST['keyword'])) . "%' OR contents_hr LIKE '%" . mysql_escape_string(urldecode($_POST['keyword'])) . "%' OR contents_de LIKE '%" . mysql_escape_string(urldecode($_POST['keyword'])) . "%' OR contents_it LIKE '%" . mysql_escape_string(urldecode($_POST['keyword'])) . "%' OR title_en LIKE '%" . mysql_escape_string(urldecode($_POST['keyword'])) . "%' OR title_hr LIKE '%" . mysql_escape_string(urldecode($_POST['keyword'])) . "%' OR title_de LIKE '%" . mysql_escape_string(urldecode($_POST['keyword'])) . "%' OR title_it LIKE '%" . mysql_escape_string(urldecode($_POST['keyword'])) . "%')";
                            		$message = NEWS_NO_NEWS_WITH_CRITERIA;                                        		
                            	}
                            	else if (isset($_GET['id']) && is_numeric($_GET['id']))
                            	{
                            		$sql_posts = $sql_posts . " WHERE  id = " . $_GET['id'];
                            		$message = NEWS_NO_NEWS_WITH_CRITERIA;                                        		
                            	}                            	
                            	$sql_posts = $sql_posts . " order by date_posted DESC ";
                            	
								// Pagination                            	
                            	$page=1;
                            	if (isset($_GET['page']) && is_numeric($_GET['page']))
                            	{
                            		$page=$_GET['page'];                                        		
                            	}
                            	
                            	// TODO: Move to admin panel as a setting
                            	$postsPerPage = 5;
                            	
                            	$sql_posts_prepared = mysql_query($sql_posts);  
                            	$rows = mysql_num_rows($sql_posts_prepared);
                            
                            	// If no news posts are found, output default message
                            	if ($rows == 0)
                            	{
                            ?>
	                            	<div class="post blog_multi">		                                
		                                <div class="post-text">
		                                    <p><?= $message ?></p>
		                                </div>		                                
		                            </div>
                            <?php 
                            	}
                            	else
                            	{           
                            		// Validate page numbers                 
								 	$lastPage = ceil($rows/$postsPerPage);
								 	if ($page < 1) 
								 	{
								 		$page = 1;
								 	}								
								 	elseif ($page > $lastPage)								
								 	{								
										$page = $lastPage;								
								 	} 
								 	
								 	$sql_posts = $sql_posts . ' LIMIT ' . ($page - 1) * $postsPerPage . ',' .$postsPerPage;
								 	$sql_posts_prepared_paginated = mysql_query($sql_posts);                            	
	                            	while($posts=mysql_fetch_assoc($sql_posts_prepared_paginated))
									{									
							?>										
			                            <div class="post blog_multi">
			                                <div class="post-header">
			                                    <h2><a href="#"><?= $posts['title_' . $language_selected] ?></a></h2>
			                                    <small><?= NEWS_POSTED_ON ?> <?= $posts['date_posted'] ?> <?= NEWS_POSTED_BY ?> <?= $posts['poster_name'] ?></small>
			                                </div>
			                                <div class="post-image">
			                                    <img src="images/<?= $posts['image_large'] ?>" alt="" />
			                                </div>
			                                <div class="post-text">
			                                    <?= $posts['contents_' . $language_selected] ?>
			                                </div>
			                                <!-- /.post-text -->
			                                <div class="rule">
			                                </div>	                                
			                                <div class="post-info">
			                                    <small><?= NEWS_POSTED_IN ?> <a rel="category tag" title="" href="<?= $this_script . '?lang=' . $language_selected .'&cat=' . urlencode($posts['category_' . $language_selected]) ?>"><?= $posts['category_' . $language_selected] ?></a></small>
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
                            	if ($lastPage > 1)
                            	{
                            		$queryString = preg_replace("/\&?page=[0-9]+/", "", $_SERVER['QUERY_STRING']);
                            		if ($page == 1)
                            		{
                            			echo NEWS_PREVIOUS . "&nbsp;";                            			
                            		}
                            		else
                            		{
                            			echo '<a href="' . $this_script . '?' . $queryString . '&page=' . ($page - 1) . '">' . NEWS_PREVIOUS . '</a>&nbsp;';                            			
                            		}
                            		
	                            	for ($i = 1; $i <= $lastPage; $i++) 
	                            	{
	                            		if ($i == $page)
	                            		{
	                            			echo $page . "&nbsp;";
	                            		}
	                            		else 
	                            		{
	                            			echo '<a href="' . $this_script . '?' . $queryString . '&page=' . $i . '">' . $i . '</a>&nbsp;';
	                            		}	                            		
	                            	}
	                            	
                            		if ($page == $lastPage)
                            		{
                            			echo NEWS_NEXT . "&nbsp;";                            			
                            		}
                            		else
                            		{
                            			echo '<a href="' . $this_script . '?' . $queryString . '&page=' . ($page + 1) . '">' . NEWS_NEXT . '</a>&nbsp;';                            			
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
