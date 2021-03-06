<?php

/*a General
 */
function insert_blank( $width, $height )
{
    global $toplevel;
    echo "<img src=\"${toplevel}images/transparent.gif\" width=$width height=$height/>";
}

/*a Page/section functions
 */
function page_identifier( $type, $value )
{
    global $page_details;
    $page_details["header"][$type]=$value;
}

$page_details["header"]["title"] = "";

function page_header( $text )
{
    global $page_details;
    page_ep();
    echo "<tr><td class=header>\n";
    echo "$text\n";
    foreach ($page_details["header"] as $key=>$value) {
        if ($key!="title")
        {
            echo "<p class=headerdetails><b>$value</b></p>\n";
        }
    }
    echo "</td></tr>\n";
    page_sp();
}

function page_sp()
{
    global $page_details;
    if ($page_details["active"]==0)
    {
        echo "<tr><td class=body>\n";
        $page_details["active"]=1;
    }
}

function page_ep()
{
    global $page_details;
    if ($page_details["active"])
    {
        echo "</td></tr>\n";
        $page_details["active"]=0;
    }
}

function page_section( $tag, $heading )
{
    page_ep();
    echo "<tr><td class=section><a name=$tag></a>$heading</td></tr>\n";
    page_sp();
}

function page_subsection( $tag, $heading )
{
    page_ep();
    echo "<tr><td class=subsection><a name=$tag></a>$heading</td></tr>\n";
    page_sp();
}

function page_subsubsection( $tag, $heading )
{
    page_ep();
    echo "<tr><td class=subsubsection><a name=$tag></a>$heading</td></tr>\n";
    page_sp();
}

function page_link( $href, $text )
{
    return "<a href=\"$href\">$text</a>";
}

$page_details["active"] = 0;

/*a Navigation links
 */
function nav_break()
{
    echo "<tr><td>\n";
    echo "<table width=100% cellspacing=0>\n";
    echo "<tr class=nomargin height=10></tr>\n";
    echo "<tr class=nomargin height=1>\n";
    echo "<td class=nomargin width=10>";
    insert_blank(1,1);
    echo"</td>\n";
    echo "<td class=nomargin bgcolor=black>";
    insert_blank(1,1);
    echo"</td>\n";
    echo "<td class=nomargin width=10>";
    insert_blank(1,1);
    echo"</td>\n";
    echo "</tr>\n";
    echo "<tr class=nomargin height=10></tr>\n";
    echo "</table>\n";
    echo "</td></tr>\n";
}

/*f nav_link_start
 */
function nav_link_start( $text )
{
    global $nav_link;
    $nav_link["hier"] = 0;
    $nav_link["side"] = 0;
    echo "<tr><td>\n<table width=100%>\n";
    echo "<tr><td class=linkhdr>$text</td></tr>\n";
}

/*f nav_link_start_hierarchy
 */
function nav_link_start_hierarchy( $text )
{
    global $nav_link;
    $nav_link["hier"] = 1;
    $nav_link["first"] = 1;
    $nav_link["level"] = 0;
    echo "<tr><td>\n<table width=100%>\n";
    if ($text!="")
    {
        echo "<tr><td class=linkhdr>$text</td></tr>\n";
    }
    echo "<tr><td class=link align=left>";
}

/*f nav_link_end
 */
function nav_link_end()
{
    global $nav_link;
    if ($nav_link["hier"])
    {
        echo "</td></tr>";
    }
    else
    {
    }
    echo "</table>\n</td></tr>\n";
}

/*f nav_link_add
 */
function nav_link_add( $href, $text )
{
    global $nav_link, $nav_width, $toplevel;
    if (!strstr($href,"http:"))
    {
        $href= "$toplevel$href";
    }
    if ($nav_link["hier"])
    {
        if ($nav_link["first"]==0)
        {
            echo "<br>\n";
        }
        $nav_link["first"]=0;
        for ($i=0; $i<$nav_link["level"]; $i++)
        {
            echo "&nbsp;&nbsp;";
        }
        echo "<a class=nav href=\"$href\">$text</a>\n";
    }
    else
    {
        if ($nav_link["side"]==0)
        {
            echo "<tr><td class=link align=left><a class=nav href=\"$href\">$text</a></td></tr>\n";
        }
        else
        {
            echo "<tr><td class=link align=right><a class=nav href=\"$href\">$text</a></td></tr>\n";
        }
    }
    $nav_link["side"] = !$nav_link["side"];
}

/*f nav_link_push
 */
function nav_link_push()
{
    global $nav_link;
    $nav_link["level"] = $nav_link["level"]+1;
}

/*f nav_link_pop
 */
function nav_link_pop()
{
    global $nav_link;
    $nav_link["level"] = $nav_link["level"]-1;
}

/*a Site map
 */
/*v Variables
 */
$site_map["level"] = 0;
$site_map["tag"][0] = "";
$site_map["count"][0] = 0;

/*f site_map
 */
function site_map( $marker, $href, $text )
{
    global $site_map;
    $level = $site_map["level"];
    $site_map["count"][$level] = $site_map["count"][$level]+1;
    $count = $site_map["count"][$level];
    $tag = $site_map["tag"][$level].".".$count;
    $site_map["markers"][$tag] = $marker;
    if ($level>0)
    {
        $ptag = $site_map["tag"][$level-1].".".$site_map["count"][$level-1];;
        if (strstr($href,"http:"))
        {
            $site_map["hrefs"][$tag] = $href;
        }
        else
        {
            $site_map["hrefs"][$tag] = $site_map["path"][$ptag].$href;
        }
    }
    else
    {
        $site_map["hrefs"][$tag] = $href;
    }
    $site_map["texts"][$tag] = $text;
}

/*f site_push
 */
function site_push( $path )
{
    global $site_map;
    $level = $site_map["level"];
    $count = $site_map["count"][$level];
    $tag = $site_map["tag"][$level].".".$count;
    if ($level>0)
    {
        $ptag = $site_map["tag"][$level-1].".".$site_map["count"][$level-1];;
        $site_map["path"][$tag] = $site_map["path"][$ptag].$path;
    }
    else
    {
        $site_map["path"][$tag] = $path;
    }
    $site_map["nsublinks"][$tag] = 0;
    $site_map["level"] = $site_map["level"]+1;
    $site_map["count"][$level+1] = 0;
    $site_map["tag"][$level+1] = $site_map["tag"][$level].".".$count;
}

/*f site_pop
 */
function site_pop()
{
    global $site_map;
    $level = $site_map["level"];
    $count = $site_map["count"][$level];
    $tag = $site_map["tag"][$level-1].".".$site_map["count"][$level-1];;
    $site_map["nsublinks"][$tag] = $count;
    $site_map["level"] = $site_map["level"]-1;
    if ($site_map["level"]<0)
    {
        echo "<h1>BUG IN SITE NAVIGATION - POPPED TOO FAR</h1>";
    }
}

/*f site_done
 */
function site_done()
{
    global $site_map;
    if ($site_map["level"]!=0)
    {
        echo "<h1>BUG IN SITE NAVIGATION - NOT AT TOPLEVEL AT COMPLETION</h1>";
    }
}

/*f site_set_location
 */
function site_set_location( $markers )
{
    global $site_depth, $site_position;
    $site_depth = 1;
    $site_position = ".".$markers;
}

/*a Code formatting
 */
function code_format( $language, $file )
{
    global $code_styles;
    $handle = fopen( $file, "r" );
    if (!$handle)
    {
        echo "<pre><em>BUG IN PAGE - UNABLE TO LOAD FILE $file</em></pre>\n";
        return;
    }
    echo "<pre>\n";
    while (!feof($handle))
    {
        $line = fgets($handle, 8192);
        $line = preg_replace( $code_styles[$language]["patterns"], $code_styles[$language]["replacements"], $line );
        echo "$line";
    }
    echo "</pre>\n";
    fclose($handle);
}
$code_styles["general"]["replacements"]["keyword"]  = "<font color=blue>\$1</font>";
$code_styles["general"]["replacements"]["type"]     = "<font color=blue>\$1</font>";
$code_styles["general"]["replacements"]["usertype"] = "<font color=red>\$1</font>";
$code_styles["general"]["replacements"]["modifier"] = "<font color=green>\$1</font>";
$code_styles["general"]["replacements"]["operator"] = "<font color=blue>\$1</font>";
$code_styles["cdl"]["patterns"][]      = "/</";
$code_styles["cdl"]["replacements"][]  = "&lt;";
$code_styles["cdl"]["patterns"][]      = "/>/";
$code_styles["cdl"]["replacements"][]  = "&gt;";
$code_styles["cdl"]["patterns"][]      = "/\\b(constant|typedef|extern|module|clock|input|output|clocked|comb|net|default|reset|timing|full_switch|part_switch|case|if|else)\\b/";
$code_styles["cdl"]["replacements"][]  = $code_styles["general"]["replacements"]["keyword"];
$code_styles["cdl"]["patterns"][]      = "/\\b(fsm|enum|struct|bit|string|integer)\\b/";
$code_styles["cdl"]["replacements"][]  = $code_styles["general"]["replacements"]["type"];
$code_styles["cdl"]["patterns"][]      = "/\\b(t_\\S*)\\b/";
$code_styles["cdl"]["replacements"][]  = $code_styles["general"]["replacements"]["usertype"];
$code_styles["cdl"]["patterns"][]      = "/\\b(active_low|active_high|rising|falling)\\b/";
$code_styles["cdl"]["replacements"][]  = $code_styles["general"]["replacements"]["modifier"];
$code_styles["cdl"]["patterns"][]      = "/(&lt;=|&gt;=|=&gt;)/";
$code_styles["cdl"]["replacements"][]  = $code_styles["general"]["replacements"]["operator"];
$code_styles["gip"]["patterns"][]      = "/</";
$code_styles["gip"]["replacements"][]  = "&lt;";
$code_styles["gip"]["patterns"][]      = "/>/";
$code_styles["gip"]["replacements"][]  = "&gt;";

/*a Bit breakouts
 */
function bit_breakout_start( $size, $heading )
{
    global $bit_breakout;
    echo "<table border=1 class=data><tr><th>$heading";
    for ($i=$size-1; $i>=0; $i--)
    {
        echo "<th>$i";
    }
    echo "<th>Comment";
    echo "</tr>\n";
    $bit_breakout["size"] = $size;
}

function bit_breakout_hdr( $heading )
{
    global $bit_breakout;
    echo "<tr><th>$heading";
}

function bit_breakout_bits( $num_bits, $value )
{
    global $bit_breakout;
    if ($num_bits<0)
    {
        echo "<td>$value</tr>\n";
        return;
    }
    echo "<td colspan=$num_bits align=center>$value";
}

function bit_breakout_bits_split( $value )
{
    global $bit_breakout;
    for ($i=0; $i<strlen($value); $i++)
    {
        bit_breakout_bits( 1, $value[$i] );
    }
}

function bit_breakout_end()
{
    global $bit_breakout;
    echo "</table>\n";
}

/*a Encodings
 */
function encoding_start( $size, $heading )
{
    echo "<table border=1 class=data><tr><th>$heading<th>Encoding<th>Description</tr>\n";
}

function encoding( $heading, $encoding, $desc )
{
    echo "<tr><th>$heading</th><td>$encoding</td><td>$desc</td></tr>";
}

function encoding_end()
{
    echo "</table>\n";
}

/*a Mappings
 */
function mapping_start( $from, $to )
{
    echo "<table border=1 class=data><tr><th>$from<th>$to</tr>\n";
}

function mapping( $from, $to )
{
    echo "<tr><th>$from</th><td>$to</td></tr>";
}

function mapping_end()
{
    echo "</table>\n";
}

/*a Done
 */
?>

