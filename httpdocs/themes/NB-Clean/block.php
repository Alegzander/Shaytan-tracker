
<?php
//BEGIN FRAME
function begin_frame($caption = "-", $align = "justify"){
    global $THEME, $site_config;
    print("<div class='myBlock'>
  <div class='myBlock-tr'>
    <div class='myBlock-trl'>
      <div class='myBlock-trr'>
        <div class='myBlock-caption'>$caption</div>
      </div>
    </div>
  </div>
  <div class='myBlock-mr'>
    <div class='myBlock-mrl'>
      <div class='myBlock-mrr'>
        <div class='myBlock-content'>");
}


//END FRAME
function end_frame() {
    global $THEME, $site_config;
    print("</div>
      </div>
    </div>
  </div>
  <div class='myBlock-br'>
    <div class='myBlock-brl'></div>
    <div class='myBlock-brr'></div>
  </div>
</div>");
}

//BEGIN BLOCK
function begin_block($caption = "-", $align = "justify"){
    global $THEME, $site_config;
    print("<div class='myBlock'>
  <div class='myBlock-tr'>
    <div class='myBlock-trl'>
      <div class='myBlock-trr'>
        <div class='myBlock-caption'>$caption</div>
      </div>
    </div>
  </div>
  <div class='myBlock-mr'>
    <div class='myBlock-mrl'>
      <div class='myBlock-mrr'>
        <div class='myBlock-content'>");
}

//END BLOCK
function end_block(){
    global $THEME, $site_config;
    print("</div>
      </div>
    </div>
  </div>
  <div class='myBlock-br'>
    <div class='myBlock-brl'></div>
    <div class='myBlock-brr'></div>
  </div>
</div>");
}

function begin_table(){
    print("<div class='ttable_headouter'><table align='center' cellpadding='0' cellspacing='0' class='ttable_headinner' width='100%'>\n");
}

function end_table()  {
    print("</table></div>\n");
}

function tr($x,$y,$noesc=0) {
    if ($noesc)
        $a = $y;
    else {
        $a = htmlspecialchars($y);
        $a = str_replace("\n", "<br />\n", $a);
    }
    print("<tr><td class='heading' valign='top' align='right'>$x</td><td valign='top' align='left'>$a</td></tr>\n");
}
?>