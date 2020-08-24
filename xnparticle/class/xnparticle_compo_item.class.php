<?php
// $Revision: 1.3 $
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

include_once XOONIPS_PATH.'/class/xoonips_compo_item.class.php';
include_once dirname( dirname( __FILE__ ) ).'/iteminfo.php';

/**
 * item compo handler class for article item type
 */
class XNPArticleCompoHandler extends XooNIpsItemInfoCompoHandler {

  /**
   * constractor
   *
   * @access public
   * @param resource &$db xoops database instance
   */
  function __construct( &$db ) {
    parent::__construct( $db, 'xnparticle' );
  }

  /**
   * create compo object
   *
   * @access public
   * @return object class instance of XNPArticleCompo object
   */
  function &create() {
    $obj = new XNPArticleCompo();
    return $obj;
  }

  /**
   * get template file name
   *
   * @access public
   * @param string $type defined symbol of template type
   *   - for transfer detail page : XOONIPS_TEMPLATE_TYPE_TRANSFER_ITEM_DETAIL
   *   - for transfer list page   : XOONIPS_TEMPLATE_TYPE_TRANSFER_ITEM_LIST
   * @return string template file name
   */
  function getTemplateFileName( $type ) {
    switch ( $type ) {
    case XOONIPS_TEMPLATE_TYPE_TRANSFER_ITEM_DETAIL:
      return 'xnparticle_transfer_item_detail.html';
    case XOONIPS_TEMPLATE_TYPE_TRANSFER_ITEM_LIST:
      return 'xnparticle_transfer_item_list.html';
    default:
      return '';
    }
  }

  /**
   * get template variables
   *
   * @param string $type defined symbol of template type
   *   - for transfer detail page : XOONIPS_TEMPLATE_TYPE_TRANSFER_ITEM_DETAIL
   *   - for transfer list page   : XOONIPS_TEMPLATE_TYPE_TRANSFER_ITEM_LIST
   *   - for detail page : XOONIPS_TEMPLATE_TYPE_ITEM_DETAIL
   *   - for list page   : XOONIPS_TEMPLATE_TYPE_ITEM_LIST
   * @param int $item_id item id
   * @param int $uid user id
   * @return array template variables
   */
  function getTemplateVar( $type, $item_id, $uid ) {
    $result = parent::getTemplateVar( $type, $item_id, $uid );

    $item_chandler =& xoonips_getormcompohandler( 'xnparticle', 'item' );
    $item_cobj =& $item_chandler->get( $item_id );
    $detail =& $item_cobj->getVar( 'detail' );

    $result['detail'] = $detail->getVarArray( 's' );

    switch ( $type ) {
    case XOONIPS_TEMPLATE_TYPE_TRANSFER_ITEM_LIST:
    case XOONIPS_TEMPLATE_TYPE_ITEM_LIST:
    case XOONIPS_TEMPLATE_TYPE_ITEM_DETAIL:
      break;
    case XOONIPS_TEMPLATE_TYPE_TRANSFER_ITEM_DETAIL:
      // 2009.02.18 sc.ito add start --- add 'item_detail_child' table information
      include_once dirname( dirname( __FILE__ ) ).'/include/view.php';
      $tmpDetail = $result['detail'];
      $tmpDetail = xnparticleDetailSubTitleString( $tmpDetail, $item_id );
      $tmpDetail = xnparticleDetailAuthorString( $tmpDetail, $item_id );
      $tmpDetail = xnparticleDetailKeywordsString( $tmpDetail, $item_id );
      $tmpDetail = xnparticleDetailNdcClassificationsString( $tmpDetail, $item_id );
      $tmpDetail = xnparticleDetailPhysicalDescriptionsString( $tmpDetail, $item_id );
      $tmpDetail = xnparticleDetailLangsString( $tmpDetail, $item_id );
      $tmpDetail = xnparticleDetailIdIssnsString( $tmpDetail, $item_id );
      $tmpDetail = xnparticleDetailIdIsbnsString( $tmpDetail, $item_id );
      $tmpDetail = xnparticleDetailIdDoisString( $tmpDetail, $item_id );
      $tmpDetail = xnparticleDetailIdUrisString( $tmpDetail, $item_id );
      $tmpDetail = xnparticleDetailIdLocalsString( $tmpDetail, $item_id );
      $tmpDetail = xnparticleDetailUrisString( $tmpDetail, $item_id );
      $result['detail'] = $tmpDetail;

      $article_images = xnpGetPreviewDetailBlock( $item_id );
      $result['article_images'] = $article_images;

      include_once XOONIPS_PATH.'/include/AL.php';
      $article_attachment = xnpGetAttachmentDetailBlock( $item_id, 'article_attachment' );
      $result['article_attachment'] = $article_attachment;

      $mhandler =& xoops_gethandler('module');
      $module = $mhandler->getByDirname( 'xnparticle' );
      $chandler = & xoops_gethandler('config');
      $assoc = $chandler->getConfigsByCat(false, $module->mid());
      $pdf_access_rights = $assoc['pdf_access_rights'];
      $access_rights = xnpGetAccessRights( $item_id );
      $result['show_pdf'] = ( $pdf_access_rights <= $access_rights );
      // 2009.02.18 sc.ito add end
      break;
    }
    return $result;
  }

  /**
   * check user can download file
   *
   * @param int $uid user id
   * @param int $file_id file id
   * @return bool false if not permitted
   */
  function hasDownloadPermission( $uid, $file_id ) {
    $file_ohandler =& xoonips_getormhandler( 'xoonips', 'file' );
    $file_obj =& $file_ohandler->get( $file_id );
    if ( ! $file_obj ) {
      // file not found
      return false;
    }
    $item_id = $file_obj->get( 'item_id' );
    if ( ! $item_id ) {
      // file is not belong to any item
      return false;
    }
    $item_cobj =& $this->get( $item_id );
    if ( ! is_object( $item_cobj ) ) {
      // bad item
      return false;
    }
    $detail_obj = $item_cobj->getVar( 'detail' );
    if ( ! is_object( $detail_obj ) ) {
      // bad item
      return false;
    }
    if ( ! $this->getPerm( $item_id, $uid, 'read' ) ) {
      // not permitted
      return false;
    }
    return true;
  }
}

/**
 * item compo object class for article item type
 */
class XNPArticleCompo extends XooNIpsItemInfoCompo {

  /**
   * constractor
   *
   * @access public
   */
  function __construct() {
    parent::__construct( 'xnparticle' );
  }
}

