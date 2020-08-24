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

function xoops_module_uninstall_xnparticle( $xoopsMod ) {
	global $xoopsDB;

	$item_type_id = -1; 
	$table = $xoopsDB->prefix('xoonips_item_type'); 
	$mid = $xoopsMod->getVar('mid'); 
	$sql = "SELECT item_type_id FROM $table where mid = $mid"; 
	$result = $xoopsDB->query($sql); 
	if ( $result ){ 
		list($item_type_id) = $xoopsDB->fetchRow($result); 
	}else{ 
		echo mysql_error(); 
		echo $sql; 
		return false; 
	} 
    
	// Set "Deleted" status in the item_status table for repository
	$table = $xoopsDB->prefix('xoonips_item_basic'); 
	$sql = "SELECT item_id from ${table} WHERE item_type_id = $item_type_id"; 
	$result = $xoopsDB->query($sql); 
	if ( !$result ){ 
		echo mysql_error(); 
		echo $sql; 
		return false; 
	} 
	$ids = array( ); 
	while( list( $item_id ) = $xoopsDB->fetchRow($result) ){ 
		$ids[] = $item_id; 
	} 
	if( count( $ids ) > 0 ){ 
		$table = $xoopsDB->prefix('xoonips_item_status'); 
		$sql = "UPDATE ${table} SET deleted_timestamp=UNIX_TIMESTAMP(NOW()), is_deleted=1 WHERE item_id in ( ".implode( ",", $ids ).")"; 
		if ( $xoopsDB->query($sql) == FALSE ){ 
			echo mysql_error(); 
			echo $sql; 
			return false; 
		} 
	} 
    
	// remove basic information 
	$table = $xoopsDB->prefix('xoonips_item_basic'); 
	$sql = "DELETE FROM $table where item_type_id = $item_type_id"; 
	if ( $xoopsDB->query($sql) == FALSE ){ 
		echo mysql_error(); 
		echo $sql; 
		return false; 
	} 
    
	// unregister itemtype
	$table = $xoopsDB->prefix('xoonips_item_type');
	$mid = $xoopsMod->getVar('mid');
	$sql = "DELETE FROM $table where mid = $mid";
	if ( $xoopsDB->query($sql) == FALSE ){
		// cannot unregister itemtype
		return false;
	}
	$table = $xoopsDB->prefix('xoonips_file_type');
	$sql = "DELETE FROM $table where mid = $mid";
	if ( $xoopsDB->query($sql) == FALSE ){
		// cannot unregister filetype
		return false;
	}

	// drop detail_child_tables
        $table = $xoopsDB->prefix('xnparticle_item_detail_child_author');
        $sql = "DROP TABLE $table";
        if ( $xoopsDB->query($sql) == FALSE ){
                // cannot unregister child_author
                return false;
        }

        $table = $xoopsDB->prefix('xnparticle_item_detail_child_keywords');
        $sql = "DROP TABLE $table";
        if ( $xoopsDB->query($sql) == FALSE ){
                // cannot unregister child_keywords
                return false;
        }

        $table = $xoopsDB->prefix('xnparticle_item_detail_child_sub_title');
        $sql = "DROP TABLE $table";
        if ( $xoopsDB->query($sql) == FALSE ){
                // cannot unregister child_sub_title
                return false;
        }

        $table = $xoopsDB->prefix('xnparticle_item_detail_child_ndc_classifications');
        $sql = "DROP TABLE $table";
        if ( $xoopsDB->query($sql) == FALSE ){
                // cannot unregister child_ndc_classification
                return false;
        }

        $table = $xoopsDB->prefix('xnparticle_item_detail_child_physical_descriptions');
        $sql = "DROP TABLE $table";
        if ( $xoopsDB->query($sql) == FALSE ){
                // cannot unregister child_physical_description
                return false;
        }

        $table = $xoopsDB->prefix('xnparticle_item_detail_child_langs');
        $sql = "DROP TABLE $table";
        if ( $xoopsDB->query($sql) == FALSE ){
                // cannot unregister child_lang
                return false;
        }

        $table = $xoopsDB->prefix('xnparticle_item_detail_child_id_issns');
        $sql = "DROP TABLE $table";
        if ( $xoopsDB->query($sql) == FALSE ){
                // cannot unregister child_id_issn
                return false;
        }

        $table = $xoopsDB->prefix('xnparticle_item_detail_child_id_isbns');
        $sql = "DROP TABLE $table";
        if ( $xoopsDB->query($sql) == FALSE ){
                // cannot unregister child_id_isbn
                return false;
        }

        $table = $xoopsDB->prefix('xnparticle_item_detail_child_id_dois');
        $sql = "DROP TABLE $table";
        if ( $xoopsDB->query($sql) == FALSE ){
                // cannot unregister child_id_doi
                return false;
        }

        $table = $xoopsDB->prefix('xnparticle_item_detail_child_id_uris');
        $sql = "DROP TABLE $table";
        if ( $xoopsDB->query($sql) == FALSE ){
                // cannot unregister child_id_uri
                return false;
        }

        $table = $xoopsDB->prefix('xnparticle_item_detail_child_id_locals');
        $sql = "DROP TABLE $table";
        if ( $xoopsDB->query($sql) == FALSE ){
                // cannot unregister child_id_local
                return false;
        }

        $table = $xoopsDB->prefix('xnparticle_item_detail_child_uris');
        $sql = "DROP TABLE $table";
        if ( $xoopsDB->query($sql) == FALSE ){
                // cannot unregister child_uri
                return false;
        }

	return true;
}

