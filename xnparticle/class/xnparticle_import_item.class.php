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
if ( ! defined( 'XOONIPS_PATH' ) ) {
  exit();
}

include_once XOONIPS_PATH.'/class/xoonips_import_item.class.php';

/**
 * import item object class for article item type
 */
class XNPArticleImportItem extends XooNIpsImportItem {

  /**
   * flag for item has attachment file
   * @var bool
   * @access private
   */
  var $_has_article_attachment = false;

  /**
   * flag for item has preview file
   * @var bool
   * @access private
   */
  var $_has_runtime_preview = false;

  /**
   * constractor
   *
   * @access public
   */
  function XNPArticleImportItem() {
    $handler =& xoonips_getormcompohandler( 'xnparticle', 'item' );
    $this->_item =& $handler->create();
  }

  /**
   * set has attachment file flag
   *
   * @access public
   */
  function setHasArticleAttachment() {
    $this->_has_article_attachment = true;
  }

  /**
   * unset has attachment file flag
   *
   * @access public
   */
  function unsetHasArticleAttachment() {
    $this->_has_article_attachment = false;
  }

  /**
   * get has attachment file flag
   *
   * @access public
   * @return bool true if item has attachment files
   */
  function hasArticleAttachment() {
    return $this->_has_article_attachment;
  }

  /**
   * set has preview file flag
   *
   * @access public
   */
  function setHasPreview() {
    $this->_has_runtime_preview = true;
  }

  /**
   * unset has preview file flag
   *
   * @access public
   */
  function unsetHasPreview() {
    $this->_has_runtime_preview = false;
  }

  /**
   * get has preview file flag
   *
   * @access public
   * @return bool true if item has preview files
   */
  function hasPreview() {
    return $this->_has_runtime_preview;
  }

  /**
   * get total file size (bytes) of this item
   *
   * @access public
   * @return int file size in bytes.
   */
  function getTotalFileSize() {
    $size = 0;
    $file_objs =& $this->getVar( 'article_attachment' );
    foreach ( $file_objs as $file_obj ) {
      $size += $file_obj->get( 'file_size' );
    }
    return $size;
  }

  /**
   * get clone object instance
   *
   * @access public
   * @return object cloened object instance
   */
  function &getClone() {
    $clone =& parent::getClone();
    $clone->_has_article_attachment = $this->_has_article_attachment;
    $clone->_has_runtime_preview = $this->_has_runtime_preview;
    return $clone;
  }
}

/**
 * import item object handler class for article item type
 */
class XNPArticleImportItemHandler extends XooNIpsImportItemHandler {

  /**
   *
   * for attachment file
   *
   */
  var $file = null;
  var $attachment_info;

  // for column length check
  var $columnLengths;
  var $childColumnLengths;

  /**
   * acceptable import file versions
   * @var string
   * @access private
   */
  var $_import_file_versions = array(
    '1.00',
  );

  /**
   * runtime parser condtion of file type attribute
   * @var string
   * @access private
   */
  var $_runtime_file_type_attribute;

  /**
   * runtime parser condtion of article attachment file object
   * @var object
   * @access private
   */
  var $_runtime_article_attachment;

  /**
   * runtime parser condtion of preview file object
   * @var object
   * @access private
   */
  var $_runtime_preview;

  /**
   * runtime parser condtion of import file version
   * @var string
   * @access private
   */
  var $_runtime_detail_version;

  /**
   * item compo handler
   * @var object
   * @access private
   */
  var $chandler;

  /**
   * constractor
   *
   * @access public
   */
  function XNPArticleImportItemHandler() {
    parent::XooNIpsImportItemHandler();
    $this->chandler =& xoonips_getormcompohandler( 'xnparticle', 'item' );
  }

  /**
   * create import item object
   *
   * @access public
   * @return object created object
   */
  function &create() {
    $obj = new XNPArticleImportItem();
    return $obj;
  }

  /**
   * xml parser handler of start element
   *
   * @param resource $parser
   * @param string $name
   * @param array $attribs
   */
  function xmlStartElementHandler( $parser, $name, $attribs ) {
    global $xoopsDB;
    parent::xmlStartElementHandler( $parser, $name, $attribs );

    $tag = strtolower( end( $this->_tag_stack ) );
    switch ( implode( '/', $this->_tag_stack ) ) {
    case 'ITEM/DETAIL':
      // validate version and set it to 'version' variable
      if ( empty( $attribs['VERSION'] ) ) {
        $this->_runtime_detail_version = '1.00';
      } else {
        if ( in_array( $attribs['VERSION'], $this->_import_file_versions ) ) {
          $this->_runtime_detail_version = $attribs['VERSION'];
        } else {
          $this->_import_item->setErrors( E_XOONIPS_INVALID_VALUE, 'unsupported version('.$attribs['VERSION'].') '.$this->_get_parser_error_at() );
        }
      }
      break;
    case 'ITEM/DETAIL/AUTHOR':
    case 'ITEM/DETAIL/SUB_TITLE':
      $objs =& $this->_import_item->getVar( 'child_'.$tag );
      $obj_handler =& xoonips_getormhandler( 'xnparticle', 'item_detail_child_'.$tag );
      $obj =& $obj_handler->create();
      $obj->set( $tag.'_order', count( $objs ) );
      $objs[] =& $obj;
      unset( $obj );
      break;
    case 'ITEM/DETAIL/FILE':
      $this->_runtime_file_type_attribute = $attribs['FILE_TYPE_NAME'];
      $file_type_handler =& xoonips_getormhandler( 'xoonips', 'file_type' );
      $file_handler =& xoonips_getormhandler( 'xoonips', 'file' );
      $criteria = new Criteria( 'name', addslashes( $attribs['FILE_TYPE_NAME'] ) );
      $file_type =& $file_type_handler->getObjects( $criteria );
      if ( count( $file_type ) == 0 ) {
        $this->_import_item->setErrors( E_XOONIPS_ATTR_NOT_FOUND, 'file_type_id is not found:'.$attribs['FILE_TYPE_NAME'].$this->_get_parser_error_at() );
        break;
      }

      $unicode =& xoonips_getutility( 'unicode' );
      if ( $this->_runtime_file_type_attribute == 'article_attachment' ) {
        $this->_runtime_article_attachment =& $file_handler->create();
        $this->_runtime_article_attachment->setFilepath( $this->_attachment_dir.'/'.$attribs['FILE_NAME'] );
        $this->_runtime_article_attachment->set( 'original_file_name', $unicode->decode_utf8( $attribs['ORIGINAL_FILE_NAME'], xoonips_get_server_charset(), 'h' ) );
        $this->_runtime_article_attachment->set( 'mime_type', $attribs['MIME_TYPE'] );
        $this->_runtime_article_attachment->set( 'file_size', $attribs['FILE_SIZE'] );
        $this->_runtime_article_attachment->set( 'sess_id', session_id() );
        $this->_runtime_article_attachment->set( 'file_type_id', $file_type[0]->get( 'file_type_id' ) );
      } else if ( $this->_runtime_file_type_attribute == 'preview' ) {
        $this->_runtime_preview =& $file_handler->create();
        $this->_runtime_preview->setFilepath( $this->_attachment_dir.'/'.$attribs['FILE_NAME'] );
        $this->_runtime_preview->set( 'original_file_name', $unicode->decode_utf8( $attribs['ORIGINAL_FILE_NAME'], xoonips_get_server_charset(), 'h' ) );
        $this->_runtime_preview->set( 'mime_type', $attribs['MIME_TYPE'] );
        $this->_runtime_preview->set( 'file_size', $attribs['FILE_SIZE'] );
        $this->_runtime_preview->set( 'sess_id', session_id() );
        $this->_runtime_preview->set( 'file_type_id', $file_type[0]->get( 'file_type_id' ) );
      } else {
        $this->_import_item->setErrors( E_XOONIPS_ATTR_NOT_FOUND, 'file_type_id is not found:'.$attribs['FILE_TYPE_NAME'].$this->_get_parser_error_at() );
        break;
      }
      break;
    }
  }

  /**
   * xml parser handler of end element
   *
   * @param resource $parser
   * @param string $name
   */
  function xmlEndElementHandler( $parser, $name ) {
    global $xoopsDB;
    $detail =& $this->_import_item->getVar( 'detail' );
    $unicode =& xoonips_getutility( 'unicode' );
    $tag = strtolower( end( $this->_tag_stack ) );
    switch ( implode( '/', $this->_tag_stack ) ) {
    case 'ITEM/DETAIL':
      $keys = array(
        'title',
        'title_kana',
        'title_romaji',
        'edition',
        'publish_place',
        'publisher',
        'publisher_kana',
        'publisher_romaji',
        'year_f',
        'year_t',
        'date_create',
        'date_update',
        'date_record',
        'jtitle',
        'jtitle_translation',
        'jtitle_volume',
        'jtitle_issue',
        'jtitle_year',
        'jtitle_month',
        'jtitle_spage',
        'jtitle_epage',
        'abstract',
        'table_of_contents',
        'type_of_resource',
        'genre',
        'access_condition',
	'self_doi',
	'naid',
	'ichushi',
	'textversion',
	'grant_id',
	'date_of_granted',
	'degree_name',
	'grantor',
      );
      foreach ( $keys as $key ) {
        if ( is_null( $detail->get( $key ) ) ) {
          $this->_import_item->setErrors( E_XOONIPS_TAG_NOT_FOUND, ' no '.$key.' tag '.$this->_get_parser_error_at() );
        }
      }
      break;
    case 'ITEM/DETAIL/TITLE':
    case 'ITEM/DETAIL/TITLE_KANA':
    case 'ITEM/DETAIL/TITLE_ROMAJI':
    case 'ITEM/DETAIL/EDITION':
    case 'ITEM/DETAIL/PUBLISH_PLACE':
    case 'ITEM/DETAIL/PUBLISHER':
    case 'ITEM/DETAIL/PUBLISHER_KANA':
    case 'ITEM/DETAIL/PUBLISHER_ROMAJI':
    case 'ITEM/DETAIL/YEAR_F':
    case 'ITEM/DETAIL/YEAR_T':
    case 'ITEM/DETAIL/DATE_CREATE':
    case 'ITEM/DETAIL/DATE_UPDATE':
    case 'ITEM/DETAIL/DATE_RECORD':
    case 'ITEM/DETAIL/JTITLE':
    case 'ITEM/DETAIL/JTITLE_TRANSLATION':
    case 'ITEM/DETAIL/JTITLE_VOLUME':
    case 'ITEM/DETAIL/JTITLE_ISSUE':
    case 'ITEM/DETAIL/JTITLE_YEAR':
    case 'ITEM/DETAIL/JTITLE_MONTH':
    case 'ITEM/DETAIL/JTITLE_SPAGE':
    case 'ITEM/DETAIL/JTITLE_EPAGE':
    case 'ITEM/DETAIL/ABSTRACT':
    case 'ITEM/DETAIL/TABLE_OF_CONTENTS':
    case 'ITEM/DETAIL/TYPE_OF_RESOURCE':
    case 'ITEM/DETAIL/GENRE':
    case 'ITEM/DETAIL/ACCESS_CONDITION':
    case 'ITEM/DETAIL/SELF_DOI':
    case 'ITEM/DETAIL/NAID':
    case 'ITEM/DETAIL/ICHUSHI':
    case 'ITEM/DETAIL/TEXTVERSION':
    case 'ITEM/DETAIL/GRANT_ID':
    case 'ITEM/DETAIL/DATE_OF_GRANTED':
    case 'ITEM/DETAIL/DEGREE_NAME':
    case 'ITEM/DETAIL/GRANTOR':
      $detail->set( $tag, $unicode->decode_utf8( $this->_cdata, xoonips_get_server_charset(), 'h' ) );
      break;
    case 'ITEM/DETAIL/SUB_TITLE':
      $keys = array(
        'sub_title_name',
        'sub_title_kana',
        'sub_title_romaji',
      );
      $sub_titles =& $this->_import_item->getVar( 'child_sub_title' );
      $sub_title =& $sub_titles[count( $sub_titles ) - 1];
      foreach ( $keys as $key ) {
        if ( is_null( $sub_title->get( $key ) ) ) {
          $this->_import_item->setErrors( E_XOONIPS_TAG_NOT_FOUND, ' no '.$key.' tag '.$this->_get_parser_error_at() );
        }
      }
      break;
    case 'ITEM/DETAIL/AUTHOR':
      $keys = array(
        'author_id',
        'author_name',
        'author_kana',
        'author_romaji',
        'author_affiliation',
        'author_affiliation_translation',
        'author_role',
        'author_link',
      );
      $authors =& $this->_import_item->getVar( 'child_author' );
      $author =& $authors[count( $authors ) - 1];
      foreach ( $keys as $key ) {
        if ( is_null( $author->get( $key ) ) ) {
          $this->_import_item->setErrors( E_XOONIPS_TAG_NOT_FOUND, ' no '.$key.' tag '.$this->_get_parser_error_at() );
        }
      }
      break;
    case 'ITEM/DETAIL/SUB_TITLE/SUB_TITLE_NAME':
    case 'ITEM/DETAIL/SUB_TITLE/SUB_TITLE_KANA':
    case 'ITEM/DETAIL/SUB_TITLE/SUB_TITLE_ROMAJI':
    case 'ITEM/DETAIL/AUTHOR/AUTHOR_ID':
    case 'ITEM/DETAIL/AUTHOR/AUTHOR_NAME':
    case 'ITEM/DETAIL/AUTHOR/AUTHOR_KANA':
    case 'ITEM/DETAIL/AUTHOR/AUTHOR_ROMAJI':
    case 'ITEM/DETAIL/AUTHOR/AUTHOR_AFFILIATION':
    case 'ITEM/DETAIL/AUTHOR/AUTHOR_AFFILIATION_TRANSLATION':
    case 'ITEM/DETAIL/AUTHOR/AUTHOR_ROLE':
    case 'ITEM/DETAIL/AUTHOR/AUTHOR_LINK':
      $parent_tag = strtolower( $this->_tag_stack[2] );
      $objs =& $this->_import_item->getVar( 'child_'.$parent_tag );
      $obj =& $objs[count( $objs ) - 1];
      $obj->set( $tag, $unicode->decode_utf8( $this->_cdata, xoonips_get_server_charset(), 'h' ) );
      unset( $obj );
      unset( $objs );
      break;
    case 'ITEM/DETAIL/KEYWORDS/KEYWORD':
    case 'ITEM/DETAIL/NDC_CLASSIFICATIONS/NDC_CLASSIFICATION':
    case 'ITEM/DETAIL/PHYSICAL_DESCRIPTIONS/PHYSICAL_DESCRIPTION':
    case 'ITEM/DETAIL/LANGS/LANG':
    case 'ITEM/DETAIL/ID_ISSNS/ID_ISSN':
    case 'ITEM/DETAIL/ID_ISBNS/ID_ISBN':
    case 'ITEM/DETAIL/ID_DOIS/ID_DOI':
    case 'ITEM/DETAIL/ID_URIS/ID_URI':
    case 'ITEM/DETAIL/ID_LOCALS/ID_LOCAL':
    case 'ITEM/DETAIL/URIS/URI':
      $parent_tag = strtolower( $this->_tag_stack[2] );
      $objs =& $this->_import_item->getVar( 'child_'.$parent_tag );
      $obj_handler =& xoonips_getormhandler( 'xnparticle', 'item_detail_child_'.$parent_tag );
      $obj =& $obj_handler->create();
      $obj->set( $parent_tag, $unicode->decode_utf8( $this->_cdata, xoonips_get_server_charset(), 'h' ) );
      $obj->set( $parent_tag.'_order', count( $objs ) );
      $objs[] =& $obj;
      unset( $obj );
      unset( $objs );
      break;
    case 'ITEM/DETAIL/FILE':
      $file_handler =& xoonips_getormhandler( 'xoonips', 'file' );
      if ( $this->_runtime_file_type_attribute == 'article_attachment' ) {
        if ( ! $file_handler->insert( $this->_runtime_article_attachment ) ) {
          $this->_import_item->setErrors( E_XOONIPS_DB_QUERY, 'can\'t insert attachment file:'.$this->_runtime_article_attachment->get( 'original_file_name' ).$this->_get_parser_error_at() );
        }
        $this->_runtime_article_attachment = $file_handler->get( $this->_runtime_article_attachment->get( 'file_id' ) );
        $attachments =& $this->_import_item->getVar( 'article_attachment' );
        $attachments[] = $this->_runtime_article_attachment;
        $this->_import_item->setHasArticleAttachment();
        $this->_runtime_file_type_attribute = null;
      } else if ( $this->_runtime_file_type_attribute == 'preview' ) {
        if ( ! $file_handler->insert( $this->_runtime_preview ) ) {
          $this->_import_item->setErrors( E_XOONIPS_DB_QUERY, 'can\'t insert attachment file:'.$this->_runtime_preview->get( 'original_file_name' ).$this->_runtime_preview->get( 'original_file_name' ).$this->_get_parser_error_at() );
        }
        $this->_runtime_preview = $file_handler->get( $this->_runtime_preview->get( 'file_id' ) );
        $previews =& $this->_import_item->getVar( 'preview' );
        $previews[] = $this->_runtime_preview;
        $this->_import_item->setHasPreview();
        $this->_runtime_file_type_attribute = null;
      } else {
        die( 'unknown file type:'.$this->_runtime_file_type_attribute );
      }
      break;
    case 'ITEM/DETAIL/FILE/CAPTION':
      $unicode =& xoonips_getutility( 'unicode' );
      if ( $this->_runtime_file_type_attribute == 'article_attachment' ) {
        $this->_runtime_article_attachment->set( 'caption', $unicode->decode_utf8( $this->_cdata, xoonips_get_server_charset(), 'h' ) );
      } else if ( $this->_runtime_file_type_attribute == 'preview' ) {
        $this->_runtime_preview->set( 'caption', $unicode->decode_utf8( $this->_cdata, xoonips_get_server_charset(), 'h' ) );
      }
      break;
    case 'ITEM/DETAIL/FILE/THUMBNAIL':
      if ( $this->_runtime_file_type_attribute == 'article_attachment' ) {
        $this->_runtime_article_attachment->set( 'thumbnail_file', base64_decode( $this->_cdata ) );
      } else if ( $this->_runtime_file_type_attribute == 'preview' ) {
        $this->_runtime_preview->set( 'thumbnail_file', base64_decode( $this->_cdata ) );
      }
      break;
    }
    parent::xmlEndElementHandler( $parser, $name );
  }

  /**
   * update item_id and sess_id of xoonips_file.
   *
   * @param object &$item XooNIpsImportItem that is imported.
   * @param array &$import_items array of all of XooNIpsImportItems
   */
  function onImportFinished( &$item, &$import_items ) {
    if ( 'xnparticleimportitem' != strtolower( get_class( $item ) ) ) {
      return;
    }
    $this->_set_file_delete_flag( $item );
    // nothing to do if no article attachments
    if ( $item->hasArticleAttachment() ) {
      $attachments =& $item->getVar( 'article_attachment' );
      foreach ( array_keys( $attachments ) as $key ) {
        if ( $attachments[$key]->get( 'file_id' ) > 0 ) {
          $this->_fix_item_id_of_file( $item, $attachments[$key] );
          $this->_create_text_search_index( $attachments[$key] );
        }
      }
    }
    // nothing to do if no previews
    if ( $item->hasPreview() ) {
      $previews =& $item->getVar( 'preview' );
      foreach ( array_keys( $previews ) as $key ) {
        if ( $previews[$key]->get( 'file_id' ) > 0 ) {
          $this->_fix_item_id_of_file( $item, $previews[$key] );
          $this->_create_text_search_index( $previews[$key] );
        }
      }
    }
    parent::onImportFinished( $item, $import_items );
  }

  /**
   * insert import item object
   *
   * @access public
   * @param object &$item import item object
   * @return bool false if failure
   */
  function insert( &$item ) {
    return $this->chandler->insert( $item );
  }

  /**
   * set new flag for import item object
   *
   * @access public
   * @param object &$item import item object
   * @return bool false if failure
   */
  function setNew( &$item ) {
    return $this->chandler->setNew( $item );
  }

  /**
   * unset new flag for import item object
   *
   * @access public
   * @param object &$item import item object
   * @return bool false if failure
   */
  function unsetNew( &$item ) {
    return $this->chandler->unsetNew( $item );
  }

  /**
   * set dirty flag for import item object
   *
   * @access public
   * @param object &$item import item object
   * @return bool false if failure
   */
  function setDirty( &$item ) {
    return $this->chandler->setDirty( $item );
  }

  /**
   * unset dirty flag for import item object
   *
   * @access public
   * @param object &$item import item object
   * @return bool false if failure
   */
  function unsetDirty( &$item ) {
    return $this->chandler->unsetDirty( $item );
  }

  /**
   * import item object
   *
   * @access public
   * @param object &$item import item object
   * @return bool false if failure
   */
  function import( &$item ) {
    if ( $item->getUpdateFlag() ) {
      $detail =& $item->getVar( 'detail' );
      $detail->unsetNew();
      $detail->setDirty();
      $file_ohandler =& xoonips_getormhandler( 'xoonips', 'file' );

      // copy attachment file
      if ( $item->hasArticleAttachment() ) {
        $old_file_objs =& $item->getVar( 'article_attachment' );
        $new_file_objs = array();
        foreach ( $old_file_objs as $old_file_obj ) {
          $clone_obj =& $file_ohandler->fileClone( $old_file_obj );
          $clone_obj->setDirty();
          $new_file_objs[] =& $clone_obj;
          unset( $clone_obj );
        }
        $item->setVar( 'article_attachment', $new_file_objs );
        unset( $old_file_objs );
        unset( $new_file_objs );
      }
      // copy preview file
      if ( $item->hasPreview() ) {
        $old_file_objs =& $item->getVar( 'preview' );
        $new_file_objs = array();
        foreach ( $old_file_objs as $old_file_obj ) {
          $clone_obj =& $file_ohandler->fileClone( $old_file_obj );
          $clone_obj->setDirty();
          $new_file_objs[] =& $clone_obj;
          unset( $clone_obj );
        }
        $item->setVar( 'preview', $new_file_objs );
        unset( $old_file_objs );
        unset( $new_file_objs );
      }
    }
    parent::import( $item );
  }
}

?>
