<?php
 //
 //  TorrentTrader v2.x
 //      $LastChangedDate: 2011-10-29 16:02:24 +0100 (Sat, 29 Oct 2011) $
 //      $LastChangedBy: dj-howarth1 $
 //
 //      http://www.torrenttrader.org
 //
 //
 
 require_once("backend/functions.php");
 dbconn();
 loggedinonly();
 
 $updateset = array();
 
 $stylesheet = $_POST['stylesheet'];
 $language = $_POST['language'];
 
 if (is_valid_id($stylesheet))
     $updateset[] = "stylesheet = '$stylesheet'";
 if (is_valid_id($language))
     $updateset[] = "language = '$language'";

 if (count($updateset))
     SQL_Query_exec("UPDATE `users` SET " . implode(', ', $updateset) . " WHERE `id` = " . $CURUSER["id"]);
 
 if (isset($_SERVER["HTTP_REFERER"]))
 {
     header("Location: {$_SERVER["HTTP_REFERER"]}"); 
     return;
 }     
 
 header("Location: index.php"); 
 
?>