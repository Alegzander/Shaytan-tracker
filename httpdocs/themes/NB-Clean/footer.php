            <!-- START FOOTER.PHP -->
          </td>
          <!-- END MAIN CONTENT AREA -->
          <?php if ($site_config["RIGHTNAV"]){ ?>
          <!-- RIGHT COLUMN -->
          <td valign="top" width="170"><?php rightblocks(); ?>
          </td>
          <!-- END RIGHT COLUMN -->
          <?php } ?>
        </tr>
      </tbody>
    </table>
  </div>
  <!-- END CONTENT AREA  -->
  <!-- CREDITS START HERE -->
  <div class='credits'>
    <?php
//
// *************************************************************************************************************************************
//			PLEASE DO NOT REMOVE THE POWERED BY LINE, SHOW SOME SUPPORT! WE WILL NOT SUPPORT ANYONE WHO HAS THIS LINE EDITED OR REMOVED!
// *************************************************************************************************************************************
printf ("<center><br />".T_("POWERED_BY_TT")." -|- ", $site_config["ttversion"]);
$totaltime = array_sum(explode(" ", microtime())) - $GLOBALS['tstart'];
printf(T_("PAGE_GENERATED_IN"), $totaltime);
print (" -|- <a href=\"http://www.torrenttrader.org\" target=\"_blank\">www.torrenttrader.org</a> -|- <a href='rss.php'>RSS Feed <img src='".$site_config["SITEURL"]."/images/icon_rss.gif' border='0' width='12' height='12' alt='' /></a> <a href='rss.php?custom=1'>Feed Info</a> -|- Theme By:  <a href='http://nikkbu.info'>Nikkbu</a></center>");
//
// *************************************************************************************************************************************
//			PLEASE DO NOT REMOVE THE POWERED BY LINE, SHOW SOME SUPPORT! WE WILL NOT SUPPORT ANYONE WHO HAS THIS LINE EDITED OR REMOVED!
// *************************************************************************************************************************************

?>
  </div>
  <!-- CREDITS END HERE -->
</div>
</body>
</html>
<?php
ob_end_flush();
?>
