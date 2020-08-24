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
if ( ! defined( 'XOONIPS_PATH' ) ) {
  exit();
}

// load xmlrpc resources
$langman =& xoonips_getutility( 'languagemanager' );
$langman->read( 'xmlrpc.php', basename( dirname( __FILE__ ) ) );

// load common part of iteminfo.php
include XOONIPS_PATH.'/include/iteminfo.inc.php';

// general information
$iteminfo['description'] = 'XooNIps Article Item Type';
$iteminfo['files']['main'] = 'article_attachment';
$iteminfo['files']['preview'] = 'preview';
$iteminfo['files']['others'] = array();

// define compo
$iteminfo['ormcompo']['module'] = 'xnparticle';
$iteminfo['ormcompo']['name'] = 'item';
$iteminfo['ormcompo']['primary_orm'] = 'basic';
$iteminfo['ormcompo']['primary_key'] = 'item_id';

// define orm of compo
$iteminfo['orm'][] = array(
  'module' => 'xnparticle',
  'name' => 'item_detail',
  'field' => 'detail',
  'foreign_key' => 'article_id',
  'multiple' => false,
  'required' => true,
);
$child_sub_title_order_criteria = new Criteria( 1, 1 );
$child_sub_title_order_criteria->setSort( 'sub_title_order' );
$iteminfo['orm'][] = array(
  'module' => 'xnparticle',
  'name' => 'item_detail_child_sub_title',
  'field' => 'child_sub_title',
  'foreign_key' => 'article_id',
  'multiple' => true,
  'criteria' => $child_sub_title_order_criteria,
);
$child_author_order_criteria = new Criteria( 1, 1 );
$child_author_order_criteria->setSort( 'author_order' );
$iteminfo['orm'][] = array(
  'module' => 'xnparticle',
  'name' => 'item_detail_child_author',
  'field' => 'child_author',
  'foreign_key' => 'article_id',
  'multiple' => true,
  'criteria' => $child_author_order_criteria,
);
$child_keywords_order_criteria = new Criteria( 1, 1 );
$child_keywords_order_criteria->setSort( 'keywords_order' );
$iteminfo['orm'][] = array(
  'module' => 'xnparticle',
  'name' => 'item_detail_child_keywords',
  'field' => 'child_keywords',
  'foreign_key' => 'article_id',
  'multiple' => true,
  'criteria' => $child_keywords_order_criteria,
);
$child_ndc_classifications_order_criteria = new Criteria( 1, 1 );
$child_ndc_classifications_order_criteria->setSort( 'ndc_classifications_order' );
$iteminfo['orm'][] = array(
  'module' => 'xnparticle',
  'name' => 'item_detail_child_ndc_classifications',
  'field' => 'child_ndc_classifications',
  'foreign_key' => 'article_id',
  'multiple' => true,
  'criteria' => $child_ndc_classifications_order_criteria,
);
$child_physical_descriptions_order_criteria = new Criteria( 1, 1 );
$child_physical_descriptions_order_criteria->setSort( 'physical_descriptions_order' );
$iteminfo['orm'][] = array(
  'module' => 'xnparticle',
  'name' => 'item_detail_child_physical_descriptions',
  'field' => 'child_physical_descriptions',
  'foreign_key' => 'article_id',
  'multiple' => true,
  'criteria' => $child_physical_descriptions_order_criteria,
);
$child_langs_order_criteria = new Criteria( 1, 1 );
$child_langs_order_criteria->setSort( 'langs_order' );
$iteminfo['orm'][] = array(
  'module' => 'xnparticle',
  'name' => 'item_detail_child_langs',
  'field' => 'child_langs',
  'foreign_key' => 'article_id',
  'multiple' => true,
  'criteria' => $child_langs_order_criteria,
);
$child_id_issns_order_criteria = new Criteria( 1, 1 );
$child_id_issns_order_criteria->setSort( 'id_issns_order' );
$iteminfo['orm'][] = array(
  'module' => 'xnparticle',
  'name' => 'item_detail_child_id_issns',
  'field' => 'child_id_issns',
  'foreign_key' => 'article_id',
  'multiple' => true,
  'criteria' => $child_id_issns_order_criteria,
);
$child_id_isbns_order_criteria = new Criteria( 1, 1 );
$child_id_isbns_order_criteria->setSort( 'id_isbns_order' );
$iteminfo['orm'][] = array(
  'module' => 'xnparticle',
  'name' => 'item_detail_child_id_isbns',
  'field' => 'child_id_isbns',
  'foreign_key' => 'article_id',
  'multiple' => true,
  'criteria' => $child_id_isbns_order_criteria,
);
$child_id_dois_order_criteria = new Criteria( 1, 1 );
$child_id_dois_order_criteria->setSort( 'id_dois_order' );
$iteminfo['orm'][] = array(
  'module' => 'xnparticle',
  'name' => 'item_detail_child_id_dois',
  'field' => 'child_id_dois',
  'foreign_key' => 'article_id',
  'multiple' => true,
  'criteria' => $child_id_dois_order_criteria,
);
$child_id_uris_order_criteria = new Criteria( 1, 1 );
$child_id_uris_order_criteria->setSort( 'id_uris_order' );
$iteminfo['orm'][] = array(
  'module' => 'xnparticle',
  'name' => 'item_detail_child_id_uris',
  'field' => 'child_id_uris',
  'foreign_key' => 'article_id',
  'multiple' => true,
  'criteria' => $child_id_uris_order_criteria,
);
$child_id_locals_order_criteria = new Criteria( 1, 1 );
$child_id_locals_order_criteria->setSort( 'id_locals_order' );
$iteminfo['orm'][] = array(
  'module' => 'xnparticle',
  'name' => 'item_detail_child_id_locals',
  'field' => 'child_id_locals',
  'foreign_key' => 'article_id',
  'multiple' => true,
  'criteria' => $child_id_locals_order_criteria,
);
$child_uris_order_criteria = new Criteria( 1, 1 );
$child_uris_order_criteria->setSort( 'uris_order' );
$iteminfo['orm'][] = array(
  'module' => 'xnparticle',
  'name' => 'item_detail_child_uris',
  'field' => 'child_uris',
  'foreign_key' => 'article_id',
  'multiple' => true,
  'criteria' => $child_uris_order_criteria,
);
$iteminfo['orm'][] = array(
  'module' => 'xoonips',
  'name' => 'file',
  'field' => 'article_attachment',
  'foreign_key' => 'item_id',
  'multiple' => true,
  'criteria' => iteminfo_file_criteria( 'article_attachment' ),
);
$iteminfo['orm'][] = array(
  'module' => 'xoonips',
  'name' => 'file',
  'field' => 'preview',
  'foreign_key' => 'item_id',
  'multiple' => true,
  'criteria' => iteminfo_file_criteria( 'preview' ),
);

// define database table information
$iteminfo['ormfield']['detail'] = array(
  array(
    'name' => 'article_id',
    'type' => 'int',
    'required' => false,
  ),
  array(
    'name' => 'title',
    'type' => 'string',
    'required' => true,
  ),
  array(
    'name' => 'title_kana',
    'type' => 'string',
    'required' => false,
  ),
  array(
    'name' => 'title_romaji',
    'type' => 'string',
    'required' => false,
  ),
  array(
    'name' => 'edition',
    'type' => 'string',
    'required' => false,
  ),
  array(
    'name' => 'publish_place',
    'type' => 'string',
    'required' => false,
  ),
  array(
    'name' => 'publisher',
    'type' => 'string',
    'required' => false,
  ),
  array(
    'name' => 'publisher_kana',
    'type' => 'string',
    'required' => false,
  ),
  array(
    'name' => 'publisher_romaji',
    'type' => 'string',
    'required' => false,
  ),
  array(
    'name' => 'year_f',
    'type' => 'string',
    'required' => false,
  ),
  array(
    'name' => 'year_t',
    'type' => 'string',
    'required' => false,
  ),
  array(
    'name' => 'date_create',
    'type' => 'string',
    'required' => false,
  ),
  array(
    'name' => 'date_update',
    'type' => 'string',
    'required' => false,
  ),
  array(
    'name' => 'date_record',
    'type' => 'string',
    'required' => false,
  ),
  array(
    'name' => 'jtitle',
    'type' => 'string',
    'required' => false,
  ),
  array(
    'name' => 'jtitle_translation',
    'type' => 'string',
    'required' => false,
  ),
  array(
    'name' => 'jtitle_volume',
    'type' => 'string',
    'required' => false,
  ),
  array(
    'name' => 'jtitle_issue',
    'type' => 'string',
    'required' => false,
  ),
  array(
    'name' => 'jtitle_year',
    'type' => 'string',
    'required' => false,
  ),
  array(
    'name' => 'jtitle_month',
    'type' => 'string',
    'required' => false,
  ),
  array(
    'name' => 'jtitle_spage',
    'type' => 'string',
    'required' => false,
  ),
  array(
    'name' => 'jtitle_epage',
    'type' => 'string',
    'required' => false,
  ),
  array(
    'name' => 'self_doi',
    'type' => 'string',
    'required' => false,
  ),
  array(
    'name' => 'naid',
    'type' => 'string',
    'required' => false,
  ),
  array(
    'name' => 'ichushi',
    'type' => 'string',
    'required' => false,
  ),
  array(
    'name' => 'grant_id',
    'type' => 'string',
    'required' => false,
  ),
  array(
    'name' => 'date_of_granted',
    'type' => 'string',
    'required' => false,
  ),
  array(
    'name' => 'degree_name',
    'type' => 'string',
    'required' => false,
  ),
  array(
    'name' => 'grantor',
    'type' => 'string',
    'required' => false,
  ),
  array(
    'name' => 'abstract',
    'type' => 'string',
    'required' => false,
  ),
  array(
    'name' => 'table_of_contents',
    'type' => 'string',
    'required' => false,
  ),
  array(
    'name' => 'type_of_resource',
    'type' => 'string',
    'required' => false,
  ),
  array(
    'name' => 'genre',
    'type' => 'string',
    'required' => false,
  ),
  array(
    'name' => 'textversion',
    'type' => 'string',
    'required' => false,
  ),
  array(
    'name' => 'access_condition',
    'type' => 'string',
    'required' => false,
  ),
  array(
    'name' => 'link',
    'type' => 'string',
    'required' => false,
  ),
);
$iteminfo['ormfield']['child_sub_title'] = array(
  array(
    'name' => 'sub_title_name',
    'type' => 'string',
    'required' => false,
  ),
  array(
    'name' => 'sub_title_kana',
    'type' => 'string',
    'required' => false,
  ),
  array(
    'name' => 'sub_title_romaji',
    'type' => 'string',
    'required' => false,
  ),
  array(
    'name' => 'sub_title_order',
    'type' => 'int',
    'required' => true,
  ),
);
$iteminfo['ormfield']['child_author'] = array(
  array(
    'name' => 'author_id',
    'type' => 'string',
    'required' => false,
  ),
  array(
    'name' => 'author_name',
    'type' => 'string',
    'required' => false,
  ),
  array(
    'name' => 'author_kana',
    'type' => 'string',
    'required' => false,
  ),
  array(
    'name' => 'author_romaji',
    'type' => 'string',
    'required' => false,
  ),
  array(
    'name' => 'author_affiliation',
    'type' => 'string',
    'required' => false,
  ),
  array(
    'name' => 'author_affiliation_translation',
    'type' => 'string',
    'required' => false,
  ),
  array(
    'name' => 'author_role',
    'type' => 'string',
    'required' => false,
  ),
  array(
    'name' => 'author_link',
    'type' => 'string',
    'required' => false,
  ),
  array(
    'name' => 'author_order',
    'type' => 'int',
    'required' => true,
  ),
);
$iteminfo['ormfield']['child_keywords'] = array(
  array(
    'name' => 'keywords',
    'type' => 'string',
    'required' => false,
  ),
  array(
    'name' => 'keywords_order',
    'type' => 'int',
    'required' => true,
  ),
);
$iteminfo['ormfield']['child_ndc_classifications'] = array(
  array(
    'name' => 'ndc_classifications',
    'type' => 'string',
    'required' => false,
  ),
  array(
    'name' => 'ndc_classifications_order',
    'type' => 'int',
    'required' => true,
  ),
);
$iteminfo['ormfield']['child_physical_descriptions'] = array(
  array(
    'name' => 'physical_descriptions',
    'type' => 'string',
    'required' => false,
  ),
  array(
    'name' => 'physical_descriptions_order',
    'type' => 'int',
    'required' => true,
  ),
);
$iteminfo['ormfield']['child_langs'] = array(
  array(
    'name' => 'langs',
    'type' => 'string',
    'required' => false,
  ),
  array(
    'name' => 'langs_order',
    'type' => 'int',
    'required' => true,
  ),
);
$iteminfo['ormfield']['detail_child_id_issns'] = array(
  array(
    'name' => 'id_issns',
    'type' => 'string',
    'required' => false,
  ),
  array(
    'name' => 'id_issns_order',
    'type' => 'int',
    'required' => true,
  ),
);
$iteminfo['ormfield']['child_id_isbns'] = array(
  array(
    'name' => 'id_isbns',
    'type' => 'string',
    'required' => false,
  ),
  array(
    'name' => 'id_isbns_order',
    'type' => 'int',
    'required' => true,
  ),
);
$iteminfo['ormfield']['child_id_dois'] = array(
  array(
    'name' => 'id_dois',
    'type' => 'string',
    'required' => false,
  ),
  array(
    'name' => 'id_dois_order',
    'type' => 'int',
    'required' => true,
  ),
);
$iteminfo['ormfield']['child_id_uris'] = array(
  array(
    'name' => 'id_uris',
    'type' => 'string',
    'required' => false,
  ),
  array(
    'name' => 'id_uris_order',
    'type' => 'int',
    'required' => true,
  ),
);
$iteminfo['ormfield']['child_id_locals'] = array(
  array(
    'name' => 'id_locals',
    'type' => 'string',
    'required' => false,
  ),
  array(
    'name' => 'id_locals_order',
    'type' => 'int',
    'required' => true,
  ),
);
$iteminfo['ormfield']['child_uris'] = array(
  array(
    'name' => 'uris',
    'type' => 'string',
    'required' => false,
  ),
  array(
    'name' => 'uris_order',
    'type' => 'int',
    'required' => true,
  ),
);

// basic information (override conditions)
foreach ( $iteminfo['io']['xmlrpc']['item'] as $key => $val ) {
  switch ( $val['xmlrpc']['field'][0] ) {
  case 'comment':
    $iteminfo['io']['xmlrpc']['item'][$key]['xmlrpc']['display_name'] = '_XR_XNPARTICLE_DISPLAY_NAME_NOTES';
    break;
  }
}

// detail information (modify below for each item types)
$iteminfo['io']['xmlrpc']['item'][] = array(
  'orm' => array(
    'field' => array(
      array(
        'orm' => 'detail',
        'field' => 'article_id',
      ),
    ),
  ),
  'xmlrpc' => array(
    'field' => array(
      'detail_field',
      'article_id',
    ),
    'display_name' => '_XR_XNPARTICLE_DISPLAY_NAME_ARTICLE_ID',
    'type' => 'string',
    'multiple' => false,
    'readonly' => true,
  ),
);
$iteminfo['io']['xmlrpc']['item'][] = array(
  'orm' => array(
    'field' => array(
      array(
        'orm' => 'detail',
        'field' => 'title',
      ),
    ),
  ),
  'xmlrpc' => array(
    'field' => array(
      'detail_field',
      'title',
    ),
    'display_name' => '_XR_XNPARTICLE_DISPLAY_NAME_TITLE',
    'type' => 'string',
    'multiple' => false,
    'required' => true,
  ),
);
$iteminfo['io']['xmlrpc']['item'][] = array(
  'orm' => array(
    'field' => array(
      array(
        'orm' => 'detail',
        'field' => 'title_kana',
      ),
    ),
  ),
  'xmlrpc' => array(
    'field' => array(
      'detail_field',
      'title_kana',
    ),
    'display_name' => '_XR_XNPARTICLE_DISPLAY_NAME_TITLE_KANA',
    'type' => 'string',
    'multiple' => false,
  ),
);
$iteminfo['io']['xmlrpc']['item'][] = array(
  'orm' => array(
    'field' => array(
      array(
        'orm' => 'detail',
        'field' => 'title_romaji',
      ),
    ),
  ),
  'xmlrpc' => array(
    'field' => array(
      'detail_field',
      'title_romaji',
    ),
    'display_name' => '_XR_XNPARTICLE_DISPLAY_NAME_TITLE_ROMAJI',
    'type' => 'string',
    'multiple' => false,
  ),
);
$iteminfo['io']['xmlrpc']['item'][] = array(
  'orm' => array(
    'field' => array(
      array(
        'orm' => 'child_sub_title',
        'field' => 'sub_title_name',
      ),
      array(
        'orm' => 'child_sub_title',
        'field' => 'sub_title_kana',
      ),
      array(
        'orm' => 'child_sub_title',
        'field' => 'sub_title_romaji',
      ),
    ),
  ),
  'xmlrpc' => array(
    'field' => array(
      'detail_field',
      'sub_title',
    ),
    'display_name' => '_XR_XNPARTICLE_DISPLAY_NAME_SUB_TITLE',
    'type' => 'string',
    'multiple' => true,
  ),
);
$iteminfo['io']['xmlrpc']['item'][] = array(
  'orm' => array(
    'field' => array(
      array(
        'orm' => 'child_author',
        'field' => 'author_id',
      ),
      array(
        'orm' => 'child_author',
        'field' => 'author_name',
      ),
      array(
        'orm' => 'child_author',
        'field' => 'author_kana',
      ),
      array(
        'orm' => 'child_author',
        'field' => 'author_romaji',
      ),
      array(
        'orm' => 'child_author',
        'field' => 'author_affiliation',
      ),
      array(
        'orm' => 'child_author',
        'field' => 'author_affiliation_translation',
      ),
    ),
  ),
  'xmlrpc' => array(
    'field' => array(
      'detail_field',
      'author',
    ),
    'display_name' => '_XR_XNPARTICLE_DISPLAY_NAME_AUTHOR',
    'type' => 'string',
    'multiple' => true,
  ),
);
$iteminfo['io']['xmlrpc']['item'][] = array(
  'orm' => array(
    'field' => array(
      array(
        'orm' => 'detail',
        'field' => 'edition',
      ),
    ),
  ),
  'xmlrpc' => array(
    'field' => array(
      'detail_field',
      'edition',
    ),
    'display_name' => '_XR_XNPARTICLE_DISPLAY_NAME_EDITION',
    'type' => 'string',
    'multiple' => false,
  ),
);
$iteminfo['io']['xmlrpc']['item'][] = array(
  'orm' => array(
    'field' => array(
      array(
        'orm' => 'detail',
        'field' => 'publish_place',
      ),
    ),
  ),
  'xmlrpc' => array(
    'field' => array(
      'detail_field',
      'publish_place',
    ),
    'display_name' => '_XR_XNPARTICLE_DISPLAY_NAME_PUBLISH_PLACE',
    'type' => 'string',
    'multiple' => false,
  ),
);
$iteminfo['io']['xmlrpc']['item'][] = array(
  'orm' => array(
    'field' => array(
      array(
        'orm' => 'detail',
        'field' => 'publisher',
      ),
    ),
  ),
  'xmlrpc' => array(
    'field' => array(
      'detail_field',
      'publisher',
    ),
    'display_name' => '_XR_XNPARTICLE_DISPLAY_NAME_PUBLISHER',
    'type' => 'string',
    'multiple' => false,
  ),
);
$iteminfo['io']['xmlrpc']['item'][] = array(
  'orm' => array(
    'field' => array(
      array(
        'orm' => 'detail',
        'field' => 'publisher_kana',
      ),
    ),
  ),
  'xmlrpc' => array(
    'field' => array(
      'detail_field',
      'publisher_kana',
    ),
    'display_name' => '_XR_XNPARTICLE_DISPLAY_NAME_PUBLISHER_KANA',
    'type' => 'string',
    'multiple' => false,
  ),
);
$iteminfo['io']['xmlrpc']['item'][] = array(
  'orm' => array(
    'field' => array(
      array(
        'orm' => 'detail',
        'field' => 'year_f',
      ),
    ),
  ),
  'xmlrpc' => array(
    'field' => array(
      'detail_field',
      'year_f',
    ),
    'display_name' => '_XR_XNPARTICLE_DISPLAY_NAME_DATE_PUBLICATION_YEAR_F',
    'type' => 'string',
    'multiple' => false,
  ),
);
$iteminfo['io']['xmlrpc']['item'][] = array(
  'orm' => array(
    'field' => array(
      array(
        'orm' => 'detail',
        'field' => 'year_t',
      ),
    ),
  ),
  'xmlrpc' => array(
    'field' => array(
      'detail_field',
      'year_t',
    ),
    'display_name' => '_XR_XNPARTICLE_DISPLAY_NAME_PUBLICATION_YEAR_T',
    'type' => 'string',
    'multiple' => false,
  ),
);
$iteminfo['io']['xmlrpc']['item'][] = array(
  'orm' => array(
    'field' => array(
      array(
        'orm' => 'detail',
        'field' => 'date_create',
      ),
    ),
  ),
  'xmlrpc' => array(
    'field' => array(
      'detail_field',
      'date_create',
    ),
    'display_name' => '_XR_XNPARTICLE_DISPLAY_NAME_DATE_CREATE',
    'type' => 'string',
    'multiple' => false,
  ),
);
$iteminfo['io']['xmlrpc']['item'][] = array(
  'orm' => array(
    'field' => array(
      array(
        'orm' => 'detail',
        'field' => 'date_update',
      ),
    ),
  ),
  'xmlrpc' => array(
    'field' => array(
      'detail_field',
      'date_update',
    ),
    'display_name' => '_XR_XNPARTICLE_DISPLAY_NAME_DATE_UPDATE',
    'type' => 'string',
    'multiple' => false,
  ),
);
$iteminfo['io']['xmlrpc']['item'][] = array(
  'orm' => array(
    'field' => array(
      array(
        'orm' => 'detail',
        'field' => 'date_record',
      ),
    ),
  ),
  'xmlrpc' => array(
    'field' => array(
      'detail_field',
      'date_record',
    ),
    'display_name' => '_XR_XNPARTICLE_DISPLAY_NAME_DATE_RECORD',
    'type' => 'string',
    'multiple' => false,
  ),
);
$iteminfo['io']['xmlrpc']['item'][] = array(
  'orm' => array(
    'field' => array(
      array(
        'orm' => 'child_physical_descriptions',
        'field' => 'physical_descriptions',
      ),
    ),
  ),
  'xmlrpc' => array(
    'field' => array(
      'detail_field',
      'physical_descriptions',
    ),
    'display_name' => '_XR_XNPARTICLE_DISPLAY_NAME_PHYSICAL_DESCRIPTIONS',
    'type' => 'string',
    'multiple' => false,
  ),
);
$iteminfo['io']['xmlrpc']['item'][] = array(
  'orm' => array(
    'field' => array(
      array(
        'orm' => 'detail',
        'field' => 'jtitle',
      ),
    ),
  ),
  'xmlrpc' => array(
    'field' => array(
      'detail_field',
      'jtitle',
    ),
    'display_name' => '_XR_XNPARTICLE_DISPLAY_NAME_JTITLE',
    'type' => 'string',
    'multiple' => false,
  ),
);
$iteminfo['io']['xmlrpc']['item'][] = array(
  'orm' => array(
    'field' => array(
      array(
        'orm' => 'etail',
        'field' => 'jtitle_translation',
      ),
    ),
  ),
  'xmlrpc' => array(
    'field' => array(
      'detail_field',
      'jtitle_translation',
    ),
    'display_name' => '_XR_XNPARTICLE_DISPLAY_NAME_JTITLE_TRANSLATION',
    'type' => 'string',
    'multiple' => false,
  ),
);
$iteminfo['io']['xmlrpc']['item'][] = array(
  'orm' => array(
    'field' => array(
      array(
        'orm' => 'detail',
        'field' => 'jtitle_volume',
      ),
    ),
  ),
  'xmlrpc' => array(
    'field' => array(
      'detail_field',
      'jtitle_volume',
    ),
    'display_name' => '_XR_XNPARTICLE_DISPLAY_NAME_JTITLE_VOLUME',
    'type' => 'string',
    'multiple' => false,
  ),
);
$iteminfo['io']['xmlrpc']['item'][] = array(
  'orm' => array(
    'field' => array(
      array(
        'orm' => 'detail',
        'field' => 'jtitle_issue',
      ),
    ),
  ),
  'xmlrpc' => array(
    'field' => array(
      'detail_field',
      'jtitle_issue',
    ),
    'display_name' => '_XR_XNPARTICLE_DISPLAY_NAME_JTITLE_ISSUE',
    'type' => 'string',
    'multiple' => false,
  ),
);
$iteminfo['io']['xmlrpc']['item'][] = array(
  'orm' => array(
    'field' => array(
      array(
        'orm' => 'detail',
        'field' => 'jtitle_year',
      ),
    ),
  ),
  'xmlrpc' => array(
    'field' => array(
      'detail_field',
      'jtitle_year',
    ),
    'display_name' => '_XR_XNPARTICLE_DISPLAY_NAME_JTITLE_YEAR',
    'type' => 'string',
    'multiple' => false,
  ),
);
$iteminfo['io']['xmlrpc']['item'][] = array(
  'orm' => array(
    'field' => array(
      array(
        'orm' => 'detail',
        'field' => 'jtitle_month',
      ),
    ),
  ),
  'xmlrpc' => array(
    'field' => array(
      'detail_field',
      'jtitle_month',
    ),
    'display_name' => '_XR_XNPARTICLE_DISPLAY_NAME_JTITLE_MONTH',
    'type' => 'string',
    'multiple' => false,
  ),
);
$iteminfo['io']['xmlrpc']['item'][] = array(
  'orm' => array(
    'field' => array(
      array(
        'orm' => 'detail',
        'field' => 'jtitle_spage',
      ),
    ),
  ),
  'xmlrpc' => array(
    'field' => array(
      'detail_field',
      'jtitle_spage',
    ),
    'display_name' => '_XR_XNPARTICLE_DISPLAY_NAME_JTITLE_SPAGE',
    'type' => 'string',
    'multiple' => false,
  ),
);
$iteminfo['io']['xmlrpc']['item'][] = array(
  'orm' => array(
    'field' => array(
      array(
        'orm' => 'detail',
        'field' => 'jtitle_epage',
      ),
    ),
  ),
  'xmlrpc' => array(
    'field' => array(
      'detail_field',
      'jtitle_epage',
    ),
    'display_name' => '_XR_XNPARTICLE_DISPLAY_NAME_JTITLE_EPAGE',
    'type' => 'string',
    'multiple' => false,
  ),
);
$iteminfo['io']['xmlrpc']['item'][] = array(
  'orm' => array(
    'field' => array(
      array(
        'orm' => 'child_id_issns',
        'field' => 'id_issns',
      ),
    ),
  ),
  'xmlrpc' => array(
    'field' => array(
      'detail_field',
      'id_issns',
    ),
    'display_name' => '_XR_XNPARTICLE_DISPLAY_NAME_IDENTIFIER_ISSN',
    'type' => 'string',
    'multiple' => true,
  ),
);
$iteminfo['io']['xmlrpc']['item'][] = array(
  'orm' => array(
    'field' => array(
      array(
        'orm' => 'child_id_isbns',
        'field' => 'id_isbns',
      ),
    ),
  ),
  'xmlrpc' => array(
    'field' => array(
      'detail_field',
      'id_isbns',
    ),
    'display_name' => '_XR_XNPARTICLE_DISPLAY_NAME_IDENTIFIER_ISBN',
    'type' => 'string',
    'multiple' => true,
  ),
);
$iteminfo['io']['xmlrpc']['item'][] = array(
  'orm' => array(
    'field' => array(
      array(
        'orm' => 'child_id_dois',
        'field' => 'id_dois',
      ),
    ),
  ),
  'xmlrpc' => array(
    'field' => array(
      'detail_field',
      'id_dois',
    ),
    'display_name' => '_XR_XNPARTICLE_DISPLAY_NAME_IDENTIFIER_DOI',
    'type' => 'string',
    'multiple' => true,
  ),
);
$iteminfo['io']['xmlrpc']['item'][] = array(
  'orm' => array(
    'field' => array(
      array(
        'orm' => 'child_id_uris',
        'field' => 'id_uris',
      ),
    ),
  ),
  'xmlrpc' => array(
    'field' => array(
      'detail_field',
      'id_uris',
    ),
    'display_name' => '_XR_XNPARTICLE_DISPLAY_NAME_IDENTIFIER_URI',
    'type' => 'string',
    'multiple' => true,
  ),
);
$iteminfo['io']['xmlrpc']['item'][] = array(
  'orm' => array(
    'field' => array(
      array(
        'orm' => 'detail',
        'field' => 'self_doi',
      ),
    ),
  ),
  'xmlrpc' => array(
    'field' => array(
      'detail_field',
      'self_doi',
    ),
    'display_name' => '_XR_XNPARTICLE_DISPLAY_NAME_IDENTIFIER_SELF_DOI',
    'type' => 'string',
    'multiple' => false,
  ),
);
$iteminfo['io']['xmlrpc']['item'][] = array(
  'orm' => array(
    'field' => array(
      array(
        'orm' => 'detail',
        'field' => 'naid',
      ),
    ),
  ),
  'xmlrpc' => array(
    'field' => array(
      'detail_field',
      'naid',
    ),
    'display_name' => '_XR_XNPARTICLE_DISPLAY_NAME_IDENTIFIER_NAID',
    'type' => 'string',
    'multiple' => false,
  ),
);
$iteminfo['io']['xmlrpc']['item'][] = array(
  'orm' => array(
    'field' => array(
      array(
        'orm' => 'detail',
        'field' => 'ichushi',
      ),
    ),
  ),
  'xmlrpc' => array(
    'field' => array(
      'detail_field',
      'ichushi',
    ),
    'display_name' => '_XR_XNPARTICLE_DISPLAY_NAME_IDENTIFIER_ICHUSHI',
    'type' => 'string',
    'multiple' => false,
  ),
);
$iteminfo['io']['xmlrpc']['item'][] = array(
  'orm' => array(
    'field' => array(
      array(
        'orm' => 'child_id_locals',
        'field' => 'id_locals',
      ),
    ),
  ),
  'xmlrpc' => array(
    'field' => array(
      'detail_field',
      'id_locals',
    ),
    'display_name' => '_XR_XNPARTICLE_DISPLAY_NAME_IDENTIFIER_OTHER',
    'type' => 'string',
    'multiple' => true,
  ),
);
$iteminfo['io']['xmlrpc']['item'][] = array(
  'orm' => array(
    'field' => array(
      array(
        'orm' => 'detail',
        'field' => 'grant_id',
      ),
    ),
  ),
  'xmlrpc' => array(
    'field' => array(
      'detail_field',
      'grant_id',
    ),
    'display_name' => '_XR_XNPARTICLE_DISPLAY_NAME_DOCTORS_DEGREE_GRANT_ID',
    'type' => 'string',
    'multiple' => false,
  ),
);
$iteminfo['io']['xmlrpc']['item'][] = array(
  'orm' => array(
    'field' => array(
      array(
        'orm' => 'detail',
        'field' => 'date_of_granted',
      ),
    ),
  ),
  'xmlrpc' => array(
    'field' => array(
      'detail_field',
      'date_of_granted',
    ),
    'display_name' => '_XR_XNPARTICLE_DISPLAY_NAME_DOCTORS_DEGREE_DATE_OF_GRANTED',
    'type' => 'string',
    'multiple' => false,
  ),
);
$iteminfo['io']['xmlrpc']['item'][] = array(
  'orm' => array(
    'field' => array(
      array(
        'orm' => 'detail',
        'field' => 'degree_name',
      ),
    ),
  ),
  'xmlrpc' => array(
    'field' => array(
      'detail_field',
      'degree_name',
    ),
    'display_name' => '_XR_XNPARTICLE_DISPLAY_NAME_DOCTORS_DEGREE_DEGREE_NAME',
    'type' => 'string',
    'multiple' => false,
  ),
);
$iteminfo['io']['xmlrpc']['item'][] = array(
  'orm' => array(
    'field' => array(
      array(
        'orm' => 'detail',
        'field' => 'grantor',
      ),
    ),
  ),
  'xmlrpc' => array(
    'field' => array(
      'detail_field',
      'grantor',
    ),
    'display_name' => '_XR_XNPARTICLE_DISPLAY_NAME_DOCTORS_DEGREE_GRANTOR',
    'type' => 'string',
    'multiple' => false,
  ),
);
$iteminfo['io']['xmlrpc']['item'][] = array(
  'orm' => array(
    'field' => array(
      array(
        'orm' => 'detail',
        'field' => 'abstract',
      ),
    ),
  ),
  'xmlrpc' => array(
    'field' => array(
      'detail_field',
      'abstract',
    ),
    'display_name' => '_XR_XNPARTICLE_DISPLAY_NAME_ABSTRACT',
    'type' => 'string',
    'multiple' => false,
  ),
);
$iteminfo['io']['xmlrpc']['item'][] = array(
  'orm' => array(
    'field' => array(
      array(
        'orm' => 'detail',
        'field' => 'table_of_contents',
      ),
    ),
  ),
  'xmlrpc' => array(
    'field' => array(
      'detail_field',
      'table_of_contents',
    ),
    'display_name' => '_XR_XNPARTICLE_DISPLAY_NAME_TABLE_OF_CONTENTS',
    'type' => 'string',
    'multiple' => false,
  ),
);
$iteminfo['io']['xmlrpc']['item'][] = array(
  'orm' => array(
    'field' => array(
      array(
        'orm' => 'child_keywords',
        'field' => 'keywords',
      ),
    ),
  ),
  'xmlrpc' => array(
    'field' => array(
      'detail_field',
      'keywords',
    ),
    'display_name' => '_XR_XNPARTICLE_DISPLAY_NAME_KEYWORDS',
    'type' => 'string',
    'multiple' => true,
  ),
);
$iteminfo['io']['xmlrpc']['item'][] = array(
  'orm' => array(
    'field' => array(
      array(
        'orm' => 'ndc_classifications',
        'field' => 'child_ndc_classifications',
      ),
    ),
  ),
  'xmlrpc' => array(
    'field' => array(
      'detail_field',
      'ndc_classifications',
    ),
    'display_name' => '_XR_XNPARTICLE_DISPLAY_NAME_NDC_CLASSIFICATIONS',
    'type' => 'string',
    'multiple' => true,
  ),
);
$iteminfo['io']['xmlrpc']['item'][] = array(
  'orm' => array(
    'field' => array(
      array(
        'orm' => 'child_langs',
        'field' => 'langs',
      ),
    ),
  ),
  'xmlrpc' => array(
    'field' => array(
      'detail_field',
      'langs',
    ),
    'display_name' => '_XR_XNPARTICLE_DISPLAY_NAME_LANGS',
    'type' => 'string',
    'multiple' => true,
  ),
);
$iteminfo['io']['xmlrpc']['item'][] = array(
  'orm' => array(
    'field' => array(
      array(
        'orm' => 'detail',
        'field' => 'type_of_resource',
      ),
    ),
  ),
  'xmlrpc' => array(
    'field' => array(
      'detail_field',
      'type_of_resource',
    ),
    'display_name' => '_XR_XNPARTICLE_DISPLAY_NAME_TYPE_OF_RESOURCE',
    'type' => 'string',
    'multiple' => false,
  ),
);
$iteminfo['io']['xmlrpc']['item'][] = array(
  'orm' => array(
    'field' => array(
      array(
        'orm' => 'detail',
        'field' => 'genre',
      ),
    ),
  ),
  'xmlrpc' => array(
    'field' => array(
      'detail_field',
      'genre',
    ),
    'display_name' => '_XR_XNPARTICLE_DISPLAY_NAME_GENRE',
    'type' => 'string',
    'multiple' => false,
  ),
);
$iteminfo['io']['xmlrpc']['item'][] = array(
  'orm' => array(
    'field' => array(
      array(
        'orm' => 'detail',
        'field' => 'textversion',
      ),
    ),
  ),
  'xmlrpc' => array(
    'field' => array(
      'detail_field',
      'textversion',
    ),
    'display_name' => '_XR_XNPARTICLE_DISPLAY_NAME_TEXTVERSION',
    'type' => 'string',
    'multiple' => false,
  ),
);
$iteminfo['io']['xmlrpc']['item'][] = array(
  'orm' => array(
    'field' => array(
      array(
        'orm' => 'preview',
        'field' => 'file_id',
      ),
    ),
  ),
  'xmlrpc' => array(
    'field' => array(
      'detail_field',
      'preview',
    ),
    'display_name' => '_XR_XNPARTICLE_DISPLAY_NAME_ITEM_IMAGES',
    'type' => 'string',
    'multiple' => true,
  ),
);
$iteminfo['io']['xmlrpc']['item'][] = array(
  'orm' => array(
    'field' => array(
      array(
        'orm' => 'article_attachment',
        'field' => 'file_id',
      ),
    ),
  ),
  'xmlrpc' => array(
    'field' => array(
      'detail_field',
      'article_attachment',
    ),
    'display_name' => '_XR_XNPARTICLE_DISPLAY_NAME_THIS_ITEM_DOCUMENT',
    'type' => 'string',
    'multiple' => true,
  ),
);
$iteminfo['io']['xmlrpc']['item'][] = array(
  'orm' => array(
    'field' => array(
      array(
        'orm' => 'child_uris',
        'field' => 'uris',
      ),
    ),
  ),
  'xmlrpc' => array(
    'field' => array(
      'detail_field',
      'uris',
    ),
    'display_name' => '_XR_XNPARTICLE_DISPLAY_NAME_URI',
    'type' => 'string',
    'multiple' => true,
  ),
);
$iteminfo['io']['xmlrpc']['item'][] = array(
  'orm' => array(
    'field' => array(
      array(
        'orm' => 'detail',
        'field' => 'access_condition',
      ),
    ),
  ),
  'xmlrpc' => array(
    'field' => array(
      'detail_field',
      'access_condition',
    ),
    'display_name' => '_XR_XNPARTICLE_DISPLAY_NAME_ACCESS_CONDITION',
    'type' => 'string',
    'multiple' => false,
  ),
);

// simple item
$iteminfo['io']['xmlrpc']['simpleitem'][] = array(
  'orm' => array(
    'field' => array(
      array(
        'orm' => 'basic',
        'field' => 'item_id',
      ),
    ),
  ),
  'xmlrpc' => array(
    'field' => array(
      'item_id',
    ),
    'type' => 'int',
    'multiple' => false,
  ),
);
$iteminfo['io']['xmlrpc']['simpleitem'][] = array(
  'orm' => array(
    'field' => array(
      array(
        'orm' => 'basic',
        'field' => 'item_type_id',
      ),
    ),
  ),
  'xmlrpc' => array(
    'field' => array(
      'itemtypeid',
    ),
    'type' => 'int',
    'multiple' => false,
  ),
);
$iteminfo['io']['xmlrpc']['simpleitem'][] = array(
  'orm' => array(
    'field' => array(
      array(
        'orm' => 'basic',
        'field' => 'uid',
      ),
    ),
  ),
  'xmlrpc' => array(
    'field' => array(
      'username',
    ),
    'type' => 'string',
    'multiple' => false,
  ),
  'eval' => array(
    'orm2xmlrpc' => '$u_handler=&xoops_gethandler("user"); $user=&$u_handler->get($in_var[0]); $out_var[0]=$user->getVar("uname");',
    'xmlrpc2orm' => ';',
  ),
);
$iteminfo['io']['xmlrpc']['simpleitem'][] = array(
  'orm' => array(
    'field' => array(
      array(
        'orm' => 'titles',
        'field' => 'title',
      ),
    ),
  ),
  'xmlrpc' => array(
    'field' => array(
      'titles',
    ),
    'type' => 'string',
    'multiple' => true,
  ),
);
$iteminfo['io']['xmlrpc']['simpleitem'][] = array(
  'orm' => array(
    'field' => array(
      array(
        'orm' => 'basic',
        'field' => 'last_update_date',
      ),
    ),
  ),
  'xmlrpc' => array(
    'field' => array(
      'last_modified_date',
    ),
    'type' => 'dateTime.iso8601',
    'multiple' => false,
  ),
);
$iteminfo['io']['xmlrpc']['simpleitem'][] = array(
  'orm' => array(
    'field' => array(
      array(
        'orm' => 'basic',
        'field' => 'creation_date',
      ),
    ),
  ),
  'xmlrpc' => array(
    'field' => array(
      'registration_date',
    ),
    'type' => 'dateTime.iso8601',
    'multiple' => false,
  ),
);
$iteminfo['io']['xmlrpc']['simpleitem'][] = array(
  'orm' => array(
    'field' => array(
      array(
        'orm' => 'detail',
        'field' => 'title',
      ),
      array(
        'orm' => 'child_author',
        'field' => 'author_name',
      ),
      array(
        'orm' => 'detail',
        'field' => 'jtitle',
      ),
      array(
        'orm' => 'detail',
        'field' => 'jtitle_volume',
      ),
      array(
        'orm' => 'detail',
        'field' => 'jtitle_issue',
      ),
      array(
        'orm' => 'detail',
        'field' => 'jtitle_year',
      ),
      array(
        'orm' => 'detail',
        'field' => 'jtitle_month',
      ),
      array(
        'orm' => 'detail',
        'field' => 'jtitle_spage',
      ),
      array(
        'orm' => 'detail',
        'field' => 'jtitle_epage',
      ),
    ),
  ),
  'xmlrpc' => array(
    'field' => array(
      'text',
    ),
    'type' => 'string',
  ),
  'eval' => array(
    'orm2xmlrpc' => '
/* title */
$in_var[0] .= ";"
/* author */
$in_var[1] = implode( ".", $in_var[1] );
/* jtitle */
$in_var[2] = empty( $in_var[2] ) ? "" : $in_var[2].".";
/* jtitle_volume */
$in_var[3] = empty( $in_var[3] ) ? "" : $in_var[3].",";
/* jtitle_issue - no touch */
/* jtitle_year, jtitle_month */
if ( empty( $in_var[5] ) ) {
 $in_var[6] = "";
} else if ( empty( $in_var[6] ) ) {
 $in_var[5] = "(".$in_var[5].")";
} else {
 $in_var[5] = "(".$in_var[5].".";
 $in_var[6] .= ")";
}
/* jtitle_spage, jtitle_epage */
if ( ! empty( $in_var[7] ) || ! empty( $in_var[8] ) ) {
 if ( ! empty( $in_var[7] ) ) {
   $in_var[7] = ",p.".$in_var[7]."-";
 } else {
   $in_var[7] = ",p.";
 }
}
$out_var[0] = $in_var[0].$in_var[1].$in_var[2].$in_var[3].$in_var[4].$in_var[5].$in_var[6].$in_var[7].$in_var[8];',
    'xmlrpc2orm' => ';',
  ),
);

?>
