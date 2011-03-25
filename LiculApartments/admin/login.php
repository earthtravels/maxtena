<?php
session_start();
include("../includes/db.conn.php"); 
include("../includes/conf.class.php");
//First lets get the username and password from the user 
$username=$bsiCore->ClearInput($_POST["username"]); 
$password=md5($_POST["password"]);

//Second let's check if that username and password are correct and found in our database
$sql1=mysql_query("SELECT username, pass, id FROM bsi_admin WHERE username='$username' AND pass='$password'");
if (mysql_num_rows($sql1)==0 || mysql_num_rows($sql1)>1)
{ 
header("location:index.php?error=88"); 
}
//if there are found in our database, and there is only one occurence of that username and password
//thus making them valid, so inside, you can include the webpage you want to open
if(mysql_num_rows($sql1)==1)
{
$row = mysql_fetch_assoc($sql1);
$_SESSION['password'] = $row['pass'];
$_SESSION['id'] = $row['id'];
header("location:admin_home.php"); //open up the secure page //instead of "the webpage" type in the path your secure website is located in
}
?>
 
