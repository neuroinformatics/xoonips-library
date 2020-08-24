<?php
//  XooNIps article item type module
//  --------------------------------------------------------------------------
//  XooNIps Library Module Xoops modules for XooNIps Platforms
//  Copyright (C) 2006 Keio University and RIKEN, Japan. All rights reserved.
//  http://sourceforge.jp/projects/xoonips-library/
//  --------------------------------------------------------------------------
//  This program is free software; you can redistribute it and/or
//  modify it under the terms of the GNU General Public License
//  as published by the Free Software Foundation; either version 2
//  of the License, or (at your option) any later version.
//
//  This program is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//  GNU General Public License for more details.
//
//  You should have received a copy of the GNU General Public License
//  along with this program; if not, write to the Free Software
//  Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
//  --------------------------------------------------------------------------

include '../../../include/cp_header.php';

// load xoonips
if ( !file_exists( '../../xoonips/condefs.php' ) ) {
  xoops_cp_header();
  echo '<span style="font-weight:bold; color:red;">';
  echo 'error: xoonips module not found';
  echo '</span>';
  xoops_cp_footer();
  exit();
}

include_once '../../xoonips/condefs.php';
include_once '../../xoonips/include/functions.php';

$textutil =& xoonips_getutility( 'text' );

$title = _AM_XNPARTICLE_TITLE;
$mid = $xoopsModule -> getVar( 'mid' );
if ( defined( 'XOOPS_CUBE_LEGACY' ) ) {
  // for XOOPS Cube 2.1 Legacy
  $pref_url = XOOPS_URL.'/modules/legacy/admin/index.php?action=PreferenceEdit&confmod_id='.$mid;
} else {
  // for XOOPS 2.0
  $pref_url = XOOPS_URL.'/modules/system/admin.php?fct=preferences&op=showmod&mod='.$mid;
}
$general = _PREFERENCES;
$pref_url = $textutil->html_special_chars( $pref_url );

xoops_cp_header();

echo "<h3>".$title."</h3>";
echo"<table width='100%' border='0' cellspacing='1' class='outer'>";
echo "<tr class=\"odd\"><td>";
echo "<ul style=\"margin: 5px;\">";
echo "<li style=\"padding: 5px;\">";
echo "<a href='$pref_url'>$general</a>\n";
echo "</li>";
echo "</ul>";
echo "</td></tr>";
echo "</table>";

xoops_cp_footer();

?>
