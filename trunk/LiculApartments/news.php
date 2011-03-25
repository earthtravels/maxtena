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
                                    <form method="post" action="<?= 'news.php?lang=' . $language_selected ?>" class="clear">
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
										$sql_categories=mysql_query("select distinct category_" . $language_selected . " as category from news_posts order by category_" . $language_selected);
										echo "<ul>\n";
										while($cat=mysql_fetch_assoc($sql_categories))
										{												
											echo '<li class="page_item"><a href="'. $this_script . '?lang=' . $language_selected . '&cat=' . $cat['category'] . '">' . $cat['category'] . '</a></li>';
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
                                    <ul>
                                    	<?php
                                    	$old_locale = setlocale(LC_ALL, NULL);
										setlocale(LC_ALL, $language_selected);
										
										$sql_categories=mysql_query("select distinct date_format(date_posted, '%Y') as cat_year, date_format(date_posted, '%m') as cat_month from news_posts order by date_format(date_posted, '%Y-%m')");
										echo "<ul>\n";																				
										while($cat=mysql_fetch_assoc($sql_categories))
										{		
											$monthName = strftime("%B", strtotime('2011-' . $cat['cat_month'] . '-01'));
											echo '<li class="page_item"><a href="'. $this_script . '?lang=' . $language_selected . '&year=' . $cat['cat_year'] . '&month=' . $cat['cat_month'] . '">' . $monthName . ' ' . $cat['cat_year'] . '</a></li>';
										}
										echo "</ul>\n";
										setlocale(LC_ALL, $old_locale);
										?>                                        
                                    </ul>
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
                            	$sql_posts="select * from news_posts ";
                            	$sql_where="";
                            	$sql_and = "";
                            	if (isset($_GET['cat']))
                            	{
                            		$sql_where = $sql_where . $sql_and . " (category_en = '" . mysql_escape_string($_GET['cat']) . "' OR category_hr = '" . mysql_escape_string($_GET['cat']) . "' OR category_de = '" . mysql_escape_string($_GET['cat']) . "' OR category_it = '" . mysql_escape_string($_GET['cat']) . "')";
                            		$sql_and = "AND";                                        		
                            	}
                            	
                            	if (isset($_GET['year']) && is_numeric($_GET['year']))
                            	{
                            		$sql_where = $sql_where . $sql_and . "CAST(date_fromat('%Y') as UNSIGNED) = " . $_GET['year'];
                            		$sql_and = " AND ";                                        		
                            	}
                            	
                            	if (isset($_GET['month']) && is_numeric($_GET['month']))
                            	{
                            		$sql_where = $sql_where . $sql_and . "CAST(date_fromat('%m') as UNSIGNED) = " . $_GET['month'];
                            		$sql_and = " AND ";                                        		
                            	}
                            	
                            	if (isset($_GET['keyword']) && is_numeric($_GET['keyword']))
                            	{
                            		$sql_where = $sql_where . $sql_and . " (contents_en = '" . mysql_escape_string($_GET['cat']) . "' OR contents_hr = '" . mysql_escape_string($_GET['cat']) . "' OR contents_de = '" . mysql_escape_string($_GET['cat']) . "' OR contents_it = '" . mysql_escape_string($_GET['cat']) . "')";
                            		$sql_and = " AND ";                                        		
                            	}
                            	
                            	if (isset($sql_where) && strlen($sql_where) > 0)
                            	{
                            		$sql_where = " WHERE " .$sql_where . " order by date_posted DESC ";
                            	}
                            	else
                            	{
                            		$sql_where = " order by date_posted DESC ";
                            	}
                            	$sql_posts = $sql_posts . $sql_where;
                            	
                            	$page=1;
                            	if (isset($_GET['page']) && is_numeric($_GET['page']))
                            	{
                            		$page=$_GET['page'];                                        		
                            	}
                            	
                            	
                            	$postsPerPage = 5;
                            	$sql_posts_prepared = mysql_query($sql_posts);  
                            	$rows = mysql_num_rows($sql_posts_prepared);
							 	$last = ceil($rows/$postsPerPage);
							 	if ($page < 1) 
							 	{
							 		$page = 1;
							 	}								
							 	elseif ($page > $last)								
							 	{								
									$page = $last;								
							 	} 
							 	
							 	$sql_posts = $sql_posts . ' LIMIT ' .($page - 1) * $postsPerPage .',' .$postsPerPage;
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
	                                    <small><?= NEWS_POSTED_IN ?> <a rel="category tag" title="" href="<?= $this_script . '?lang=' . $language_selected .'&cat=' . $posts['category_' . $language_selected]?>"><?= $posts['category_' . $language_selected] ?></a></small>
	                                </div>
	                                <!-- /.post-info -->
	                                <div class="rule">
	                                </div>
	                            </div>
                            <?php
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
