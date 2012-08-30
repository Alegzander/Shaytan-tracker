<?php
//
//  TorrentTrader v2.x
//      $LastChangedDate: 2011-11-15 01:50:56 +0000 (Tue, 15 Nov 2011) $
//      $LastChangedBy: dj-howarth1 $
//
//      http://www.torrenttrader.org
//
//
require_once("backend/functions.php");
dbconn(false);
loggedinonly();


if($CURUSER["edit_users"]!="yes")
	show_error_msg(T_("ACCESS_DENIED"),T_("YOU_DONT_HAVE_EDIT_USER_PERM"),1);

$action = $_POST["action"];

if (!$action)
	show_error_msg(T_("ERROR"), T_("TASK_NOT_FOUND"), 1);

if ($action == 'edituser'){
	$userid = $_POST["userid"];
	$title = sqlesc($_POST["title"]);
	$downloaded = strtobytes($_POST["downloaded"]);
	$uploaded = strtobytes($_POST["uploaded"]);
	$signature = sqlesc($_POST["signature"]);
	$avatar = sqlesc($_POST["avatar"]);
	$ip = sqlesc($_POST["ip"]);
	$class = (int) $_POST["class"];
	$donated = (float) $_POST["donated"];
	$password = $_POST["password"];
	$warned = sqlesc($_POST["warned"]);
	$forumbanned = sqlesc($_POST["forumbanned"]);
	$modcomment = sqlesc($_POST["modcomment"]);
	$enabled = sqlesc($_POST["enabled"]);
	$invites =(int) $_POST["invites"];
	$class = (int)$_POST["class"];
	$email = $_POST["email"];

	if (!is_valid_id($userid))
		show_error_msg(T_("EDITING_FAILED"), T_("INVALID_USERID"),1);

	if (!validemail($email))
		show_error_msg(T_("EDITING_FAILED"), T_("EMAIL_ADDRESS_NOT_VALID"), 1);

	//change user class
	$res = SQL_Query_exec("SELECT class FROM users WHERE id=$userid");
	$arr = mysql_fetch_row($res);
	$uc = $arr[0];

	// skip if class is same as current
	if ($uc != $class && $class > 0) {
		if ($userid == $CURUSER["id"]) {
			show_error_msg(T_("EDITING_FAILED"), T_("YOU_CANT_DEMOTE_YOURSELF"),1);
		} elseif ($uc >= get_user_class()) {
			show_error_msg(T_("EDITING_FAILED"), T_("YOU_CANT_DEMOTE_SOMEONE_SAME_LVL"),1);
		} else {
			@SQL_Query_exec("UPDATE users SET class=$class WHERE id=$userid");
			// Notify user
			$prodemoted = ($class > $uc ? "promoted" : "demoted");
			$msg = sqlesc("You have been $prodemoted to '" . get_user_class_name($class) . "' by " . $CURUSER["username"] . ".");
			$added = sqlesc(get_date_time());
			@SQL_Query_exec("INSERT INTO messages (sender, receiver, msg, added) VALUES(0, $userid, $msg, $added)");
		}
	}
	//continue updates


	SQL_Query_exec("UPDATE users SET email='$email', title=$title, downloaded='$downloaded', uploaded='$uploaded', signature=$signature, avatar=$avatar, ip=$ip, donated=$donated, forumbanned=$forumbanned, warned=$warned, modcomment=$modcomment, enabled=$enabled, invites=$invites WHERE id=$userid");

	write_log($CURUSER['username']." has edited user: $userid details");

	if ($_POST['resetpasskey']=='yes'){
		SQL_Query_exec("UPDATE users SET passkey='' WHERE id=$userid");
		//write_log($CURUSER['username']." ".T_("PASSKEY")." has been reset for: $userid");
	}

	$chgpasswd = $_POST['chgpasswd']=='yes' ? true : false;
	if ($chgpasswd) {
		$passreq = SQL_Query_exec("SELECT password FROM users WHERE id=$userid");
		$passres = mysql_fetch_assoc($passreq);
		if($password != $passres['password']){
			$password = passhash($password);
			SQL_Query_exec("UPDATE users SET password='$password' WHERE id=$userid");
			write_log($CURUSER['username']." has changed password for user: $userid");
		}
	}
  
  header("Location: account-details.php?id=$userid");
  die;
}

if ($action == 'addwarning'){
	$userid = (int)$_POST["userid"];
	$reason = mysql_real_escape_string($_POST["reason"]);
	$expiry = (int)$_POST["expiry"];
	$type = mysql_real_escape_string($_POST["type"]);

	if (!is_valid_id($userid))
		show_error_msg(T_("EDITING_FAILED"), T_("INVALID_USERID"),1);

	if (!$reason || !$expiry || !$type){
		show_error_msg(T_("ERROR"), T_("MISSING_FORM_DATA").".", 1);
	}

	$timenow = get_date_time();

	$expiretime = get_date_time(gmtime() + (86400 * $expiry));

	$ret = SQL_Query_exec("INSERT INTO warnings (userid, reason, added, expiry, warnedby, type) VALUES ('$userid','$reason','$timenow','$expiretime','".$CURUSER['id']."','$type')");

	$ret = SQL_Query_exec("UPDATE users SET warned='yes' WHERE id='$userid'");

	$msg = sqlesc("You have been warned by " . $CURUSER["username"] . " - Reason: ".$reason." - Expiry: ".$expiretime."");
	$added = sqlesc(get_date_time());
	@SQL_Query_exec("INSERT INTO messages (sender, receiver, msg, added) VALUES(0, $userid, $msg, $added)");

	write_log($CURUSER['username']." has added a warning for user: <a href='account-details.php?id=$userid'>$userid</a>");
	header("Location: account-details.php?id=$userid");
	die;
}


if ($action == "deleteaccount"){
    
    if ($CURUSER["delete_users"] != "yes")//only allow admins to delete users
		show_error_msg(T_("ERROR"), T_("TASK_ADMIN"),1);

	$userid = (int)$_POST["userid"];
	$username = sqlesc($_POST["username"]);
	$delreason = sqlesc($_POST["delreason"]);

	if (!is_valid_id($userid))
		show_error_msg(T_("FAILED"), T_("INVALID_USERID"),1);

    if ($CURUSER["id"] == $userid) 
        show_error_msg("Error", "You cannot delete yourself.", 1);
        
	if (!$delreason){
		show_error_msg(T_("ERROR"), T_("MISSING_FORM_DATA"), 1);
	}

	deleteaccount($userid);

	write_log($CURUSER['username']." has deleted account: $username");

	show_error_msg(T_("COMPLETED"), T_("USER_DELETE"), 1);
	die;
}

/*

if ($action == "banuser")
{
  $userid = $_POST["userid"];
  $what = $_POST["what"];
  if (!is_valid_id($userid))
    genbark("Not a vaild Userid");
  $comment = $_POST['comment'];
  if (!$comment)
    genbark("".T_("ERROR").":", "Please explain why you are banning this user!");
  $r = SQL_Query_exec("SELECT username,ip FROM users WHERE id=$userid") or sqlerr();
  $a = mysql_fetch_assoc($r);
  $username = $a["username"];
  $ip = $a["ip"];
  if ($what == "subnet")
  	$ip = substr($ip, 0, strrpos($ip, ".")) . ".*";
  else
    if ($what == 'ip')
      $extra = " OR ip='" . substr($ip, 0, strrpos($ip, ".")) . ".*'";
    else
      genbark("Heh", "Select what to ban!");
  $r = SQL_Query_exec("SELECT * FROM bans WHERE ip='$ip'$extra") or sqlerr();
  if (mysql_num_rows($r) > 0)
    genbark(T_("ERROR"), "IP/subnet is already banned");
  else {
    $dt = get_date_time();
    $comment = sqlesc($comment);
    SQL_Query_exec("INSERT INTO bans (userid, first, last, added, addedby, comment) VALUES($userid, '$ip', '$ip', '$dt', $CURUSER[id], $comment)") or sqlerr();
    SQL_Query_exec("UPDATE users SET secret='' WHERE id=$userid") or sqlerr();
    $returnto = $_POST["returnto"];
    header("Location: $returnto");
    die;
  }
}

if ($action == "enableaccount")
{
  $userid = $_POST["id"];
  $res = SQL_Query_exec("SELECT * FROM users WHERE id='$userid'") or sqlerr();
  if (mysql_num_rows($res) != 1)
    genbark("User $userid not found!");
  $secret = sqlesc(mksecret());
  SQL_Query_exec("UPDATE users SET secret=" . $secret . " WHERE id=$userid") or sqlerr();
  header("Location: account-details.php?id=$userid");
  die;
}
*/
?>
