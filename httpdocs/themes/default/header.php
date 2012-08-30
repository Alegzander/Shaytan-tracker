<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $title; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $site_config["CHARSET"]; ?>" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta http-equiv="X-UA-Compatible" content="IE=9" />
<!-- CSS -->
<!-- PNG FIX for IE6 -->
<!-- http://24ways.org/2007/supersleight-transparent-png-in-ie6 -->
<!--[if lte IE 6]>
    <script type="text/javascript" src="<?php echo $site_config["SITEURL"]; ?>/themes/default/js/pngfix/supersleight-min.js"></script>
<![endif]-->
<!-- Theme css -->
<link rel="shortcut icon" href="<?php echo $site_config["SITEURL"]; ?>/themes/default/images/favicon.ico" />
<link rel="stylesheet" type="text/css" href="<?php echo $site_config["SITEURL"]; ?>/themes/default/theme.css" />
<!-- JS -->
<script type="text/javascript" src="<?php echo $site_config["SITEURL"]; ?>/backend/java_klappe.js"></script>
</head>
<body>
    <div class='wrapper'>
      <!-- START NAVIGATION -->
      <div class='navigation'>
        <div class='menu'>
          <ul id='nav-one' class='dropmenu'>
            <li><a href="<?=$site_config["SITEURL"]?>"><?php echo T_("BROWSE");?></a></li>
            <li><a href="http://anigari.anime.uz/"><?php echo T_("FORUM");?></a></li>
            <li><a href="torrents-upload.php"><?php echo T_("UPLOAD_TORRENT");?></a></li>
          </ul>
        </div>
      </div>
      <!-- END NAVIGATION -->
  <!-- Start Content -->
  <div id='main'>
    <table cellspacing="0" cellpadding="7" width="100%" border="0">
     <tr>
          <?php if ($site_config["LEFTNAV"]){?>
          <!-- START LEFT COLUM -->
          <td valign="top" width="170">
		  <?php leftblocks();?>
          </td>
          <!-- END LEFT COLUM -->
          <?php } //LEFTNAV ON/OFF END?>
          <!-- START MAIN COLUM -->
          <td valign="top">
