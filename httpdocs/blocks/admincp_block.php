<?php
    
   if ($CURUSER["control_panel"] == "yes") 
   {
       begin_block("AdminCP");
       ?>
       
       <select name="admin" onchange="if(this.options[this.selectedIndex].value != -1){ window.location = this.options[this.selectedIndex].value; }">
       <option value="-1">Navigation</option>
       <option value="admincp.php?action=usersearch">Advanced User Search</option>
       <option value="admincp.php?action=avatars">Avatar Log</option>
       <option value="admincp.php?action=backups">Backups</option>
       <option value="admincp.php?action=ipbans">Banned Ip's</option>
       <option value="admincp.php?action=bannedtorrents">Banned Torrents</option>
       <option value="admincp.php?action=blocks&do=view">Blocks</option>
       <option value="admincp.php?action=cheats">Detect Possibe Cheats</option>
       <option value="admincp.php?action=emailbans">E-mail Bans</option>
       <option value="faq-manage.php">FAQ</option>
       <option value="admincp.php?action=freetorrents">Freeleech Torrents</option>
       <option value="admincp.php?action=lastcomm">Latest Comments</option>
       <option value="admincp.php?action=masspm">Mass PM</option>
       <option value="admincp.php?action=messagespy">Message Spy</option>
       <option value="admincp.php?action=news&do=view">News</option>
       <option value="admincp.php?action=peers">Peers List</option>
       <option value="admincp.php?action=polls&do=view">Polls</option>
       <option value="admincp.php?action=reports&do=view">Reports System</option>
       <option value="admincp.php?action=rules&do=view">Rules</option>
       <option value="admincp.php?action=sitelog">Site Log</option>
       <option value="teams-create.php">Teams</option>
       <option value="admincp.php?action=style">Theme Management</option>
       <option value="admincp.php?action=categories&do=view">Torrent Categories</option>
       <option value="admincp.php?action=torrentlangs&do=view">Torrent Languages</option>
       <option value="admincp.php?action=torrentmanage">Torrents</option>
       <option value="admincp.php?action=groups&do=view">Usergroups View</option>
       <option value="admincp.php?action=warned">Warned Users</option>
       <option value="admincp.php?action=whoswhere">Who's Where</option>
       <option value="admincp.php?action=censor">Word Censor</option>
       <option value="admincp.php?action=forum">Forum Management</option>
       </select>
    
       <?php
       end_frame();
   }   

?>