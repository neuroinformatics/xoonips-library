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
if ( ! defined( 'XOOPS_ROOT_PATH' ) ) {
  exit();
}

include_once XOOPS_ROOT_PATH . '/include/functions.php';

function xoops_module_update_xnparticle ( $xoopsMod, $oldversion) {

	//XooNIps Support Version Check 
	$handler =& xoops_gethandler('module');
	$module =& $handler->getByDirname('xoonips');
	if($module){
		$xoonips_version = $module->get('version');
	}else{
		echo "<code>ERROR: line=" . __LINE__ . " not installed XooNIps</code><br>";
		return false;
	}
	if($xoonips_version < 347){
		echo "<code>ERROR: line=" . __LINE__ . " XooNIps version " . $xoonips_version/100 . " is not support</code><br>";
		return false;
	}

	//XooNIps Article Item Type Support Version Check
	if($oldversion <120){
		echo "<code>ERROR: line=" . __LINE__ . " XooNIps Article Item Type version " . $oldversion/100 . " update is not support</code><br>";
		return false;
	}

	global $xoopsDB;
	switch ($oldversion) { //remember that version is multiplied with 100 to get an integer
	case 100:
		// 
		// change table type from MyISAM to InnoDB
		//
   		foreach( array( 'xnparticle_item_detail',
			'xnparticle_item_detail_child_author',
			'xnparticle_item_detail_child_id_dois',
			'xnparticle_item_detail_child_id_isbns',
			'xnparticle_item_detail_child_id_issns',
			'xnparticle_item_detail_child_id_locals',
			'xnparticle_item_detail_child_id_uris',
			'xnparticle_item_detail_child_keywords',
			'xnparticle_item_detail_child_langs',
			'xnparticle_item_detail_child_ndc_classifications',
			'xnparticle_item_detail_child_physical_descriptions',
			'xnparticle_item_detail_child_sub_title',
			'xnparticle_item_detail_child_uris') as $table ){
			$sql = "ALTER TABLE " . $xoopsDB->prefix( $table ) . " TYPE = innodb";
			$result = $xoopsDB->query( $sql );
			if( !$result ){
				echo "ERROR: line=" . __LINE__ . " Failed to modify table structure from version 1.00";
			}
		}
	
	case 110:
		// 
		// modify some table column size 
		//
                $sql = "alter table " . $xoopsDB->prefix( "xnparticle_item_detail" ) . " modify year_f varchar(50), modify year_t varchar(50), modify date_create varchar(50), modify date_update varchar(50), modify date_record varchar(50)";
		$result = $xoopsDB->query( $sql );
		if( !$result ){
			echo "ERROR: line=" . __LINE__ . " Failed to modify table structure from version 1.10 - (1)";
		}
                $sql = "alter table " . $xoopsDB->prefix( "xnparticle_item_detail_child_physical_descriptions" ) . " modify physical_descriptions varchar(1000)";
		$result = $xoopsDB->query( $sql );
		if( !$result ){
			echo "ERROR: line=" . __LINE__ . " Failed to modify table structure from version 1.10 - (2)";
		}
                $sql = "alter table " . $xoopsDB->prefix( "xnparticle_item_detail_child_id_uris" ) . " modify id_uris varchar(1000)";
		$result = $xoopsDB->query( $sql );
		if( !$result ){
			echo "ERROR: line=" . __LINE__ . " Failed to modify table structure from version 1.10 - (3)";
		}
                $sql = "alter table " . $xoopsDB->prefix( "xnparticle_item_detail_child_uris" ) . " modify uris varchar(1000)";
		$result = $xoopsDB->query( $sql );
		if( !$result ){
			echo "ERROR: line=" . __LINE__ . " Failed to modify table structure from version 1.10 - (4)";
		}
	case 120:
	case 130:
		// 
		// create article_id index
		//
                $sql = "create index idx_article_id on " . $xoopsDB->prefix( "xnparticle_item_detail_child_sub_title" ) . " (article_id)";
		$result = $xoopsDB->query( $sql );
		if( !$result ){
			echo "ERROR: line=" . __LINE__ . " Failed to modify table structure from version 1.20 or 1.30 - (1)";
		}
                $sql = "create index idx_article_id on " . $xoopsDB->prefix( "xnparticle_item_detail_child_author" ) . " (article_id)";
		$result = $xoopsDB->query( $sql );
		if( !$result ){
			echo "ERROR: line=" . __LINE__ . " Failed to modify table structure from version 1.20 or 1.30 - (2)";
		}
                $sql = "create index idx_article_id on " . $xoopsDB->prefix( "xnparticle_item_detail_child_keywords" ) . " (article_id)";
		$result = $xoopsDB->query( $sql );
		if( !$result ){
			echo "ERROR: line=" . __LINE__ . " Failed to modify table structure from version 1.20 or 1.30 - (3)";
		}
                $sql = "create index idx_article_id on " . $xoopsDB->prefix( "xnparticle_item_detail_child_ndc_classifications" ) . " (article_id)";
		$result = $xoopsDB->query( $sql );
		if( !$result ){
			echo "ERROR: line=" . __LINE__ . " Failed to modify table structure from version 1.20 or 1.30 - (4)";
		}
                $sql = "create index idx_article_id on " . $xoopsDB->prefix( "xnparticle_item_detail_child_physical_descriptions" ) . " (article_id)";
		$result = $xoopsDB->query( $sql );
		if( !$result ){
			echo "ERROR: line=" . __LINE__ . " Failed to modify table structure from version 1.20 or 1.30 - (5)";
		}
                $sql = "create index idx_article_id on " . $xoopsDB->prefix( "xnparticle_item_detail_child_langs" ) . " (article_id)";
		$result = $xoopsDB->query( $sql );
		if( !$result ){
			echo "ERROR: line=" . __LINE__ . " Failed to modify table structure from version 1.20 or 1.30 - (6)";
		}
                $sql = "create index idx_article_id on " . $xoopsDB->prefix( "xnparticle_item_detail_child_id_isbns" ) . " (article_id)";
		$result = $xoopsDB->query( $sql );
		if( !$result ){
			echo "ERROR: line=" . __LINE__ . " Failed to modify table structure from version 1.20 or 1.30 - (7)";
		}
                $sql = "create index idx_article_id on " . $xoopsDB->prefix( "xnparticle_item_detail_child_id_issns" ) . " (article_id)";
		$result = $xoopsDB->query( $sql );
		if( !$result ){
			echo "ERROR: line=" . __LINE__ . " Failed to modify table structure from version 1.20 or 1.30 - (8)";
		}
                $sql = "create index idx_article_id on " . $xoopsDB->prefix( "xnparticle_item_detail_child_id_dois" ) . " (article_id)";
		$result = $xoopsDB->query( $sql );
		if( !$result ){
			echo "ERROR: line=" . __LINE__ . " Failed to modify table structure from version 1.20 or 1.30 - (9)";
		}
                $sql = "create index idx_article_id on " . $xoopsDB->prefix( "xnparticle_item_detail_child_id_uris" ) . " (article_id)";
		$result = $xoopsDB->query( $sql );
		if( !$result ){
			echo "ERROR: line=" . __LINE__ . " Failed to modify table structure from version 1.20 or 1.30 - (10)";
		}
                $sql = "create index idx_article_id on " . $xoopsDB->prefix( "xnparticle_item_detail_child_id_locals" ) . " (article_id)";
		$result = $xoopsDB->query( $sql );
		if( !$result ){
			echo "ERROR: line=" . __LINE__ . " Failed to modify table structure from version 1.20 or 1.30 - (11)";
		}
                $sql = "create index idx_article_id on " . $xoopsDB->prefix( "xnparticle_item_detail_child_uris" ) . " (article_id)";
		$result = $xoopsDB->query( $sql );
		if( !$result ){
			echo "ERROR: line=" . __LINE__ . " Failed to modify table structure from version 1.20 or 1.30 - (12)";
		}

	case 131:
	case 140:
		//
		// add Junii2Ver.3 element columns
		//
		$sql = "ALTER TABLE " .$xoopsDB->prefix( "xnparticle_item_detail" ) . " ADD (self_doi text, naid text, ichushi text, textversion text, grant_id text, date_of_granted text, degree_name text, grantor text);";
		$result = $xoopsDB->query( $sql );
		if( !$result ){
			echo "ERROR: line=" . __LINE__ . " Failed to modify table structure from version 1.31 or 1.40";
		}

	case 150:
	default:
		return true;
	}
	return true;
}

