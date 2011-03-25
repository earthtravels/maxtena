<?php 
session_start();
include("includes/db.conn.php");
include("includes/language.php");
include("includes/conf.class.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?=HTML_PARAMS?>" lang="<?=HTML_PARAMS?>">
<head>
    <title><?=$bsiCore->config['conf_hotel_sitetitle']?></title> 
    <meta name="description" content="<?=$bsiCore->config['conf_hotel_sitedesc']?>" />
    <meta name="keywords" content="<?=$bsiCore->config['conf_hotel_sitekeywords']?>" />
    <meta http-equiv="Content-Type" content="text/html;charset=<?=CHARSET?>" />
    <meta name="robots" content="all" />
    <link rel="stylesheet" type="text/css" href="css/main.css" />
    
	<!-- Pull in the JQUERY library -->
	<script type="text/javascript" src="scripts/jquery-1.2.6.min.js"></script>
    
    <!-- Pull in and set up the DROPDOWN functionality -->
	<script type="text/javascript" src="scripts/hoverIntent.js"></script> 
    <script type="text/javascript" src="scripts/superfish.js"></script> 
     
    <script type="text/javascript">      
        $(document).ready(function(){ 
            $("ul.sf-menu").superfish(); 
        });      
    </script>    
</head>

<body>

<!-- Centers the page -->
<div id="content">
<?php include("header.php"); ?>    
    
    <div id="main-content" class="subpage">
    	
      	<div class="left1">
			<h2><?=BOOKING_CONFIRM_TITLE?> </h2>
			<br />		<br />		<br />			
			<div align="center">
			<?=BOOKING_CONFIRM_MSG?>
			</div>
            <br />		<br />		<br />		
      	</div>
        
        
        
        <div class="clear"></div>
        
    </div>
    
</div>
<!-- END content -->

<?php include("footer.php"); ?>

</body>
</html>