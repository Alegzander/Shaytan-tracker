<?php
//MYSQL CONNECTION INFO, DONT PASS IT OUT!

//Access Security check
if (preg_match('/mysql.php/i',$_SERVER['PHP_SELF'])) {
	die;
}

//Change the settings below to match your MYSQL server connection settings
$mysql_host = "localhost";  //leave this as localhost if you are unsure
$mysql_user = "tracker";  //Username to connect
$mysql_pass = "nhfvgkby"; //Password to connect
$mysql_db = "tracker";  //Database name
?>
