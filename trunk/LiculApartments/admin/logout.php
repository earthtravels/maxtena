<?php 
session_start();
if(true === session_unregister('password')) :
   header('Location: index.php?msg=logout_complete');
else :
   unset($_SESSION['password']);
   sleep(3);
   header('Location: index.php?msg=logout_complete');
endif;
?> 