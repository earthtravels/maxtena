<?
session_start();

//include_once ("../includes/SystemConfiguration.class.php");


// Sends the user to the login-page if not logged in
if(!isset($_SESSION['password']))
{
   header('Location: index.php?msg=requires_login');
}
?>