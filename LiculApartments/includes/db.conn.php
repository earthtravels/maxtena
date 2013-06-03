<?php
define ("MYSQL_SERVER", "rabaccroatia.db.10808651.hostedresource.com");
//define ("MYSQL_SERVER", "localhost");
define ("MYSQL_USER", "rabaccroatia");
define ("MYSQL_PASSWORD", "sdg62m@vA");
define ("MYSQL_DATABASE", "rabaccroatia");

mysql_connect (MYSQL_SERVER, MYSQL_USER, MYSQL_PASSWORD) or die ('I cannot connect to the database because 1: ' . mysql_error ());
mysql_select_db (MYSQL_DATABASE) or die ('I cannot connect to the database because 2: ' . mysql_error ());
?>