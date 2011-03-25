<?php
session_start();
include("includes/db.conn.php");
include("includes/language.php");
include("includes/conf.class.php");
$sql_gallery=mysql_query("select * from bsi_gallery where gallery_type=1");
$total_img=mysql_num_rows($sql_gallery);
$no_of_page=ceil($total_img/18);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:<?=HTML_PARAMS?>>
<head>
    <title>
    <?=$bsiCore->config['conf_hotel_sitetitle']?>
    </title>
    <meta name="description" content="<?=$bsiCore->config['conf_hotel_sitedesc']?>" />
    <meta name="keywords" content="<?=$bsiCore->config['conf_hotel_sitekeywords']?>" />
    <meta http-equiv="Content-Type" content="text/html;charset=<?=CHARSET?>" />
    <meta name="robots" content="all" />
    <link rel="stylesheet" type="text/css" href="css/main.css" />
    
	<!-- Pull in the JQUERY library -->
	<script type="text/javascript" src="scripts/jquery-1.2.6.min.js"></script>
    
    <!-- Pull in and set up the JFLOW functionality -->
    <script type="text/javascript" src="scripts/jquery.flow.1.2.min.js"></script>
    <script type="text/javascript">
	$(document).ready(function(){
	
		$("#myController").jFlow({
			slides: "#mySlides",
			controller: ".jFlowControl", // must be class, use . sign
			slideWrapper : "#jFlowSlide", // must be id, use # sign
			selectedWrapper: "jFlowSelected",  // just pure text, no sign
			width: "980px",
			height: "498px",
			duration: 500,
			prev: ".jFlowPrev", // must be class, use . sign
			next: ".jFlowNext" // must be class, use . sign
		});
	
	});
	</script>
    
    <!-- Pull in and set up the JQUERY Lightbox -->
    <script type="text/javascript" src="scripts/jquery.lightbox-0.5.min.js"></script>
    <link rel="stylesheet" type="text/css" href="css/jquery.lightbox-0.5.css" media="screen" />
    
    <script type="text/javascript">
	$(function() {
		$('#mySlides a').lightBox();
	});
	</script>
    
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
    <!-- Slider Area -->
    <div id="slider-content" class="gallery">
    
    	<div id="myController" class="blue-block small">
        
        	<!-- This area is hidden, but you need to add more of these if you add more gallery pages
			The next one would be: ... <a class="jFlowControl">Gallery Page 05</a> ... and so on
            They can be named whatever you'd like though, just keep that in mind. -->
            <?php
			for ($i=1; $i<=$no_of_page; $i++){
			
			if($i==$no_of_page){
            ?>
            <a class="jFlowControl"><?=GALLERY_PAGE?> <?=$i?></a>
            <?php
            }else{
			?>
            <a class="jFlowControl"><?=GALLERY_PAGE?> <?=$i?></a>
            &nbsp;|&nbsp;
            <?php
            }
			?>
            <?php
			}
			?>
            
        </div>
    
    	<div id="mySlides">
        
        	<!-- Each DIV is a separate gallery page, just add more DIVs if you want more, but be sure to add
			more "jFlowControl" links as described above, or it will not work. -->
            <div><!-- Gallery Page 01 -->
				<div class="gallery-block">
                <ul>
            <?php
			$cols=6;
			$img_display=1;
			while($gallery_row=mysql_fetch_assoc($sql_gallery)){	
		    if($cols==1){	
			?>
			<li class="last"><a href="gallery/<?=$gallery_row['img_path']?>"><img src="gallery/thumb_<?=$gallery_row['img_path']?>" alt="Gallery Image" /></a></li>
             <?php
			 }else{
			 ?>
             <li><a href="gallery/<?=$gallery_row['img_path']?>"><img src="gallery/thumb_<?=$gallery_row['img_path']?>" alt="Gallery Image" /></a></li>
             <?php
			 }

			 
			 	 $cols=$cols-1;
				 if($cols==0 && $total_img > $img_display && $img_display !=18 ){
				 echo "</ul><ul>";
				 $cols=6;
				  }
			 
			 
				 if($img_display == $total_img)
				 echo "</ul></div></div>";
			 
				 if($img_display % 18 == 0  && $total_img > $img_display ){
				 echo "</ul></div></div>";
				 echo "<div><div class=\"gallery-block\"><ul>";
				 }
			 $img_display=$img_display+1;
			 
			 }
			 ?>
						
			
           
            
		</div>
        <!-- END mySlides -->
	
   		<!-- Previous and Next Arrow Buttons (The text is hidden using CSS) -->
        <span class="jFlowPrev">Previous</span>
        <span class="jFlowNext">Next</span>
 
  	</div>
    <!-- END slider-content -->
    
</div>
<!-- END content -->

<?php include("footer.php"); ?>

</body>
</html>