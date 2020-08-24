<?php
// $Revision: 1.1 $
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
 * article item detail child id locals information object class
 */
class XNPArticleOrmItemDetailChildIdLocals extends XooNIpsTableObject {
  function XNPArticleOrmItemDetailChildIdLocals() {
    parent::XooNIpsTableObject();
    $this->initVar( 'article_child_id_locals_id', XOBJ_DTYPE_INT, 0, false );
    $this->initVar( 'article_id', XOBJ_DTYPE_INT, 0, true );
    $this->initVar( 'id_locals', XOBJ_DTYPE_TXTBOX, '', false, 255 );
    $this->initVar( 'id_locals_order', XOBJ_DTYPE_INT, 0, true );
  }
}

/**
 * article item detail child id locals information handler class
 */
class XNPArticleOrmItemDetailChildIdLocalsHandler extends XooNIpsTableObjectHandler {
  function XNPArticleOrmItemDetailChildIdLocalsHandler( &$db ) {
    parent::XooNIpsTableObjectHandler( $db );
    $this->__initHandler( 'XNPArticleOrmItemDetailChildIdLocals', 'xnparticle_item_detail_child_id_locals', 'article_child_id_locals_id', false );
  }
}
?>
