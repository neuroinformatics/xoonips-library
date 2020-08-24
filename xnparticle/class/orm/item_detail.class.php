<?php
// $Revision: 1.2 $
// ------------------------------------------------------------------------- //
//  XooNIps Library Module Xoops modules for XooNIps Platforms               //
//  Copyright (C) 2006-2009 Keio University and RIKEN, Japan.                //
//  All rights reserved.                                                     //
//  http://sourceforge.jp/projects/xoonips-library/                          //
// ------------------------------------------------------------------------- //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
// ------------------------------------------------------------------------- //
if ( ! defined( 'XOOPS_ROOT_PATH' ) ) {
  exit();
}

/**
 * article item detail information object class
 */
class XNPArticleOrmItemDetail extends XooNIpsTableObject {
  function XNPArticleOrmItemDetail() {
    parent::XooNIpsTableObject();
    $this->initVar( 'article_id', XOBJ_DTYPE_INT, 0, false );
    $this->initVar( 'title', XOBJ_DTYPE_TXTBOX, '', true, 65535 );
    $this->initVar( 'title_kana', XOBJ_DTYPE_TXTBOX, '', false, 65535 );
    $this->initVar( 'title_romaji', XOBJ_DTYPE_TXTBOX, '', false, 65535 );
    $this->initVar( 'edition', XOBJ_DTYPE_TXTBOX, '', false, 255 );
    $this->initVar( 'publish_place', XOBJ_DTYPE_TXTBOX, '', false, 255 );
    $this->initVar( 'publisher', XOBJ_DTYPE_TXTBOX, '', false, 255 );
    $this->initVar( 'publisher_kana', XOBJ_DTYPE_TXTBOX, '', false, 255 );
    $this->initVar( 'publisher_romaji', XOBJ_DTYPE_TXTBOX, '', false, 255 );
    $this->initVar( 'year_f', XOBJ_DTYPE_TXTBOX, '', false, 50 );
    $this->initVar( 'year_t', XOBJ_DTYPE_TXTBOX, '', false, 50 );
    $this->initVar( 'date_create', XOBJ_DTYPE_TXTBOX, '', false, 50 );
    $this->initVar( 'date_update', XOBJ_DTYPE_TXTBOX, '', false, 50 );
    $this->initVar( 'date_record', XOBJ_DTYPE_TXTBOX, '', false, 50 );
    $this->initVar( 'jtitle', XOBJ_DTYPE_TXTBOX, '', false, 65535 );
    $this->initVar( 'jtitle_translation', XOBJ_DTYPE_TXTBOX, '', false, 65535 );
    $this->initVar( 'jtitle_volume', XOBJ_DTYPE_TXTBOX, '', false, 255 );
    $this->initVar( 'jtitle_issue', XOBJ_DTYPE_TXTBOX, '', false, 255 );
    $this->initVar( 'jtitle_year', XOBJ_DTYPE_TXTBOX, '', false, 255 );
    $this->initVar( 'jtitle_month', XOBJ_DTYPE_TXTBOX, '', false, 255 );
    $this->initVar( 'jtitle_spage', XOBJ_DTYPE_TXTBOX, '', false, 255 );
    $this->initVar( 'jtitle_epage', XOBJ_DTYPE_TXTBOX, '', false, 255 );
    $this->initVar( 'abstract', XOBJ_DTYPE_TXTBOX, null, false, 65535 );
    $this->initVar( 'table_of_contents', XOBJ_DTYPE_TXTBOX, null, false, 65535 );
    $this->initVar( 'type_of_resource', XOBJ_DTYPE_TXTBOX, '', false, 65535 );
    $this->initVar( 'genre', XOBJ_DTYPE_TXTBOX, '', false, 255 );
    $this->initVar( 'access_condition', XOBJ_DTYPE_TXTBOX, null, false, 65535 );
    $this->initVar( 'link', XOBJ_DTYPE_TXTBOX, null, false, 255 );
    $this->initVar( 'self_doi', XOBJ_DTYPE_TXTBOX, null, false, 65535 );
    $this->initVar( 'naid', XOBJ_DTYPE_TXTBOX, null, false, 65535 );
    $this->initVar( 'ichushi', XOBJ_DTYPE_TXTBOX, null, false, 65535 );
    $this->initVar( 'textversion', XOBJ_DTYPE_TXTBOX, null, false, 65535 );
    $this->initVar( 'grant_id', XOBJ_DTYPE_TXTBOX, null, false, 65535 );
    $this->initVar( 'date_of_granted', XOBJ_DTYPE_TXTBOX, null, false, 65535 );
    $this->initVar( 'degree_name', XOBJ_DTYPE_TXTBOX, null, false, 65535 );
    $this->initVar( 'grantor', XOBJ_DTYPE_TXTBOX, null, false, 65535 );
  }
}

/**
 * article item detail information handler class
 */
class XNPArticleOrmItemDetailHandler extends XooNIpsTableObjectHandler {
  function XNPArticleOrmItemDetailHandler( &$db ) {
    parent::XooNIpsTableObjectHandler( $db );
    $this->__initHandler( 'XNPArticleOrmItemDetail', 'xnparticle_item_detail', 'article_id', false );
  }
}
?>
