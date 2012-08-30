<?php
/*
+ ----------------------------------------------------------------------------+
|	
|	Theme: NB-Clean
|	Author: Nikkbu
|	Website: http://nikkbu.info
|	
|	Updated:  05/10/2011
|	TT Version: TT v2.07
|	
+----------------------------------------------------------------------------+
*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $title; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $site_config["CHARSET"]; ?>" />

<!-- CSS -->
<!-- PNG FIX for IE6 -->
<!-- http://24ways.org/2007/supersleight-transparent-png-in-ie6 -->
<!--[if lte IE 6]>
    <script type="text/javascript" src="themes/NB-Clean/js/pngfix/supersleight-min.js"></script>
<![endif]-->
<link rel="shortcut icon" href="<?php echo $site_config["SITEURL"]; ?>/themes/NB-Clean/images/favicon.ico" />
<link rel="stylesheet" type="text/css" href="<?php echo $site_config["SITEURL"]; ?>/themes/NB-Clean/red.css" />
<link rel="alternate stylesheet" type="text/css" media="screen" title="Aqua" href="<?php echo $site_config["SITEURL"]; ?>/themes/NB-Clean/aqua.css" />
<link rel="alternate stylesheet" type="text/css" media="screen" title="Black" href="<?php echo $site_config["SITEURL"]; ?>/themes/NB-Clean/black.css" />
<link rel="alternate stylesheet" type="text/css" media="screen" title="Blue" href="<?php echo $site_config["SITEURL"]; ?>/themes/NB-Clean/blue.css" />
<link rel="alternate stylesheet" type="text/css" media="screen" title="Gray" href="<?php echo $site_config["SITEURL"]; ?>/themes/NB-Clean/gray.css" />
<link rel="alternate stylesheet" type="text/css" media="screen" title="Green" href="<?php echo $site_config["SITEURL"]; ?>/themes/NB-Clean/green.css" />
<link rel="alternate stylesheet" type="text/css" media="screen" title="Pink" href="<?php echo $site_config["SITEURL"]; ?>/themes/NB-Clean/pink.css" />
<link rel="alternate stylesheet" type="text/css" media="screen" title="Purple" href="<?php echo $site_config["SITEURL"]; ?>/themes/NB-Clean/purple.css" />
<link rel="alternate stylesheet" type="text/css" media="screen" title="Red" href="<?php echo $site_config["SITEURL"]; ?>/themes/NB-Clean/red.css" />
<link rel="alternate stylesheet" type="text/css" media="screen" title="Yellow" href="<?php echo $site_config["SITEURL"]; ?>/themes/NB-Clean/yellow.css" />

<!-- JS -->
<script src='<?php echo $site_config["SITEURL"]; ?>/themes/NB-Clean/js/styleswitch.js' type='text/javascript'></script>
<script type="text/javascript" src="<?php echo $site_config["SITEURL"]; ?>/backend/java_klappe.js"></script>
</head>

<body>
<div class='wrapper'>
  <!-- START HEADER -->
  <div class='header'>
    <div class='header-r'>
      <!-- START LOGO -->
      <div id='logo'><a href='index.php'><img src='<?php echo $site_config["SITEURL"]; ?>/themes/NB-Clean/images/logo.png' alt='Logo' width='240' height='80' /></a></div>
      <!-- END LOGO -->
      <!-- START COLOR PICKER -->
      <div class='color-picker'>
        <form id='switchform' action=''>
          <select name='switchcontrol' size='1' onchange='chooseStyle(this.options[this.selectedIndex].value, 60)'>
            <option class='csellect' value='none' selected='selected'>Color</option>
            <option class='aqua' value='Aqua'>Aqua</option>
            <option class='black' value='Black'>Black</option>
            <option class='blue' value='Blue'>Blue</option>
            <option class='gray' value='Gray'>Gray</option>
            <option class='green' value='Green'>Green</option>
            <option class='pink' value='Pink'>Pink</option>
            <option class='purple' value='Purple'>Purple</option>
            <option class='red' value='Red'>Red</option>
            <option class='yellow' value='Yellow'>Yellow</option>
          </select>
        </form>
      </div>
      <!-- END COLOR PICKER -->
      <!-- START INFOBAR -->
      <div id='infobar'>
        <div class='info-l'>
          <div class='inf-r'>
            <div class='infobar'>
              <?php
			if (!$CURUSER){
				echo "[<a href=\"account-login.php\">".T_("LOGIN")."</a>]<b> ".T_("OR")." </b>[<a href=\"account-signup.php\">".T_("SIGNUP")."</a>]";
			}else{
				print (T_("LOGGED_IN_AS").": ".$CURUSER["username"].""); 
				echo " [<a href=\"account-logout.php\">".T_("LOGOUT")."</a>] ";
				if ($CURUSER["control_panel"]=="yes") {
					print("[<a href='admincp.php'>".T_("STAFFCP")."</a>] ");
				}
		
				//check for new pm's
				$res = SQL_Query_exec("SELECT COUNT(*) FROM messages WHERE receiver=" . $CURUSER["id"] . " and unread='yes' AND location IN ('in','both')");
				$arr = mysql_fetch_row($res);
				$unreadmail = $arr[0];
				if ($unreadmail){
					print("[<b><a href='mailbox.php?inbox'>".T_("NEW_MESSAGE")." <font color='#ff0000'>$unreadmail</font></a></b>] ");
				}else{
					print("[<a href='mailbox.php'>".T_("YOUR_MESSAGES")."</a>] ");
				}
				//end check for pm's
			}
			?>
            </div>
          </div>
        </div>
      </div>
      <!-- END INFOBAR -->
    </div>
  </div>
  <!-- END HEADER -->
<!--  START NAVIGATION -->
  <div class='navbar'>
    <div class='navigation'>
      <table border='0' align='center' cellpadding='0' cellspacing='0'>
        <tr>
          <td height='25' align='center'><ul class='nav'>
              <li><a href='index.php'><?php echo T_("HOME");?></a></li>
              <li><a href='forums.php'><?php echo T_("FORUMS");?></a></li>
              <li><a href='torrents-upload.php'><?php echo T_("UPLOAD_TORRENT");?></a></li>
              <li><a href='torrents.php'><?php echo T_("BROWSE_TORRENTS");?></a></li>
              <li><a href='torrents-today.php'><?php echo T_("TODAYS_TORRENTS");?></a></li>
              <li><a href='torrents-search.php'><?php echo T_("SEARCH_TORRENTS");?></a></li>
            </ul></td>
        </tr>
      </table>
    </div>
  </div>
<!--  END NAVIGATION -->
  <!-- START CONTENT AREA -->
  <div class='content'>
    <table width="100%" border="0" cellspacing="10" cellpadding="0">
      <tbody>
        <tr>
          <?php if ($site_config["LEFTNAV"]){?>
          <!-- LEFT COLUMN -->
          <td valign="top" width="170"><?php leftblocks();?>
          </td>
          <!-- END LEFT COLUMN -->
          <?php } //LEFTNAV ON/OFF END?>
          <td valign="top"><!-- MAIN CENTER CONTENT START -->
            <?php
	if ($site_config["MIDDLENAV"]){
		middleblocks();
	} //MIDDLENAV ON/OFF END
	?>
            <!-- END HEADER.PHP -->
