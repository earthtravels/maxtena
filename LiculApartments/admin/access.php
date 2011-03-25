<?
session_start();
// Sends the user to the login-page if not logged in
if(!session_is_registered('password')) :
   header('Location: index.php?msg=requires_login');
endif;
?>