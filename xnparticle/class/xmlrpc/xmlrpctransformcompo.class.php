<?php
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
 * XmlRpcTransform composer class for Article type
 */
class XNPArticleXmlRpcTransformCompo extends XooNIpsXmlRpcTransformCompo {
  function __construct() {
    parent::__construct( 'xnparticle' );
  }

  /**
   * override getObject to order child tables.
   *
   * @see XooNIpsXmlRpcTransformCompo::getObject
   *
   * @param array associative array of XML-RPC argument
   * @return object XNPArticleCompo
   */
  function getObject( $in_array ) {
    $cobj = parent::getObject( $in_array );
    $order_keys = array(
      'sub_title',
      'author',
      'keywords',
      'ndc_classifications',
      'physical_descriptions',
      'langs',
      'id_issns',
      'id_isbns',
      'id_dois',
      'id_uris',
      'id_locals',
      'uris',
    );
    foreach ( $order_keys as $key ) {
      $objs =& $cobj->getVar( 'child_'.$key );
      for ( $i = 0; $i < count( $objs ); $i++ ) {
        $objs[$i]->set( $key.'_order', $i );
      }
      unset( $objs );
    }
    return $cobj;
  }
}

