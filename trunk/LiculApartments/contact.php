<?php
session_start();
include("includes/db.conn.php");
include("includes/language.php");
include("includes/conf.class.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:<?=HTML_PARAMS?> >
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
    
 <link rel="stylesheet" type="text/css" media="screen" href="css/milk.css" />
 <script src="scripts/jquery.validate.js" type="text/javascript"></script>
 <script id="demo" type="text/javascript">
$(document).ready(function() {
	// validate signup form on keyup and submit
	var validator = $("#signupform").validate({
		rules: {
			fullname: "required",
			email: {
				required: true,
				email: true
			},
			phone: "required"
		},
		messages: {
			fullname: "<?=CONTACT_JAVASCRIPT_NAME?>",
			
			email: {
				required: "<?=DETAILS_JAVASCRIPT_EMAIL?>",
				minlength: "<?=DETAILS_JAVASCRIPT_EMAIL?>",
				remote: jQuery.format("{0} is already in use")
			},
			phone: "<?=DETAILS_JAVASCRIPT_PHONE?>"
		},
		// the errorPlacement has to take the table layout into account
		errorPlacement: function(error, element) {
			if ( element.is(":radio") )
				error.appendTo( element.parent().next().next() );
			else if ( element.is(":checkbox") )
				error.appendTo ( element.next() );
			else
				error.appendTo( element.parent().next() );
		},
		
		submitHandler: function() {
			var querystr = 'actioncode=3&fullname='+$('#fullname').val()+'&email='+$('#email').val()+'&phone='+$('#phone').val()+'&subject='+$('#subject').val()+'&message='+$('#message').val(); 	
			$('#contact_form').html("<div align='center'><br><br><br><img src='graphics/ajax-loader_2.gif' border='0'></div>")
			$.post("ajaxreq-processor.php", querystr, function(data){						
				//alert("1");						 
				if(data.errorcode == 0){
					 $('#contact_form').html(decode64(data.strhtml))
				}else{
				    alert(decode64(data.strmsg));
				}
				
			}, "json");
		},
		// set this class to error-labels to indicate valid fields
		success: function(label) {
			// set &nbsp; as text for IE
			label.html("&nbsp;").addClass("checked");
			
		}
	});
	

	

});


</script>  
<script type="text/javascript" src="scripts/base64_decode.js"></script>
</head>

<body>
<div id="content">
<?php include("header.php"); ?>    
    <div id="main-content" class="subpage">
    	
      	<div class="left">
			<h2><?=CONTACT_TITLE?></h2>
            <div id="contact_form">
            <p><?=CONTACT_SUB_DESC?></p>
			
			<form action="#" method="post" id="signupform">
				<p>
                <table cellpadding="4" cellspacing="0" border="0" >
                <tr><td><?=CONTACT_NAME?>:</td>
				<td><input type="text" name="fullname" id="fullname" class="textbox" /></td><td  class="status" valign="baseline"></td></tr>
				
				<tr><td><?=BOOKING_DETAILS_EMAIL?>:</td>
				<td><input type="text" name="email" id="email" class="textbox" /></td><td  class="status"></td></tr>
                
                <tr><td nowrap="nowrap"><?=BOOKING_DETAILS_PHONE?>:</td>
				<td><input type="text" name="phone" id="phone" class="textbox" /></td><td  class="status"></td></tr>
				
				<tr><td><?=CONTACT_SUBJECT?>:</td>
				<td><input type="text" name="subject" id="subject" value="" class="textbox" /></td><td></td></tr>
				
				<tr><td valign="top"><?=CONTACT_MESSAGE?>:</td>
				<td colspan="2"><textarea name="message" id="message" rows="1" cols="1" class="textarea"></textarea></td></tr>
				
				<tr><td></td><td><input type="submit" value="<?=CONTACT_SEND_BTN?>" class="button"  id="btn_contact" /></td><td></td></tr>
                </table>
                </p>
			</form>
            </div>
      	</div>
        
        <div class="right">
        	<h2><em><strong><?=CONTACT_RIGHT_TITLE?></strong></em></h2>
            
            <p><strong><?=CONTACT_RIGHT_SUB_DESC?></strong></p>
			
			<p>
			<strong><?=CONTACT_PHONE?>:</strong> <?=$bsiCore->config['conf_hotel_phone']?><br />
            <?php
			if( $bsiCore->config['conf_hotel_fax'] != "") {
			?>
			<strong><?=BOOKING_DETAILS_FAX?>:</strong> <?=$bsiCore->config['conf_hotel_fax']?><br />
            <?php } ?>
			<strong><?=BOOKING_DETAILS_EMAIL?>:</strong> <a href="mailto:<?=$bsiCore->config['conf_hotel_email']?>"><?=$bsiCore->config['conf_hotel_email']?></a><br />
			</p>
			
			<p>
			<strong><?=CONTACT_ADDR?>:</strong><br />
			<?=$bsiCore->config['conf_hotel_streetaddr']?><br />
			<?=$bsiCore->config['conf_hotel_city']?>, <?=$bsiCore->config['conf_hotel_state']?> <?=$bsiCore->config['conf_hotel_zipcode']?><br />
            <?=$bsiCore->config['conf_hotel_country']?>
			</p>
            
        </div>
        
        <div class="clear"></div>
        
    </div>
    
</div>

<!-- END content -->
<?php include("footer.php"); ?>
</body>

</html>