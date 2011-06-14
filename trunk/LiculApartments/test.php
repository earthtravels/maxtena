<? 
include_once("Mail.php"); 

$to = "mmedic123@yahoo.com";  
$subject = "Email from php"; 
$body = "Hi \n this is a test"; 

$rt = mail($to, $subject, $body);
echo $rt; 
?>