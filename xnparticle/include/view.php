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

if ( ! defined( 'XOOPS_ROOT_PATH' ) ) exit();

$itemtype_path = dirname( dirname( __FILE__ ) );
$itemtype_dirname = basename( $itemtype_path );
$xoonips_path = dirname( $itemtype_path ).'/xoonips';

$langman =& xoonips_getutility( 'languagemanager' );
$langman->read( 'main.php', $itemtype_dirname );

require_once $itemtype_path.'/include/include.php';
require_once $itemtype_path.'/include/kana2roma.inc.php';

function xnparticleGetTypes(){
	return array( 'article'=>_MD_XNPARTICLE_ARTICLE_TYPE_ARTICLE_LABEL, 'review'=>_MD_XNPARTICLE_ARTICLE_TYPE_REVIEW_LABEL, 'other'=>_MD_XNPARTICLE_ARTICLE_TYPE_OTHER_LABEL );
}

function xnparticleTrimArray( $array_data ) {
        foreach( $array_data as $key => $val ) {
                $array_data[$key] = trim($val);
        }
        return $array_data;
}

function xnparticleDetailSubTitleString( $detail, $item_id ) {
	$detail_child_sub_title = xnparticleGetDetailChildSubTitleInformation( $item_id );
	$detail['sub_title_str'] = "<table border='0'>\n";
	$i = 0;
	while ( list( $key, list( $article_child_sub_title_id, $article_id, $sub_title_name, $sub_title_kana, $sub_title_romaji, $sub_title_order ) ) = each( $detail_child_sub_title ) ){
                $detail['sub_title_str'] .= "<tr class='oddeven".fmod($i, 2)."'><td>";
                //$detail['sub_title_str'] .= $sub_title_name." (".$sub_title_kana.":".$sub_title_romaji.")</td></tr>\n";
                $detail['sub_title_str'] .= $sub_title_name."</td></tr>\n";
		$i++;
	}
	$detail['sub_title_str'] .= "</table>";
	$detail['sub_title_cnt'] = strval(fmod($i, 2));
	if ($i == 0) {
		$detail['sub_title_str'] = "";
	}
	return $detail;
}

function xnparticleDetailAuthorString( $detail, $item_id ) {
	$detail_child_author = xnparticleGetDetailChildAuthorInformation( $item_id );
	$detail['author_str'] = "<table border='0'>\n";
	$i = 0;
	while ( list( $key, list( $article_child_author_id, $article_id, $author_id, $author_name, $author_kana, $author_romaji, $author_affiliation, $author_affiliation_translation, $author_role, $author_link, $author_order ) ) = each( $detail_child_author ) ){
		$detail['author_str'] .= "<tr class='oddeven".fmod($i, 2)."'><td colspan='2'>".$author_name;
                if(!empty($author_romaji)){
		    $detail['author_str'] .= "(".$author_romaji.")";
                }
                $detail['author_str'] .= "</td></tr>\n";
                if(!empty($author_affiliation) || !empty($author_affiliation_translation)){
                    if(!empty($author_affiliation) && !empty($author_affiliation_translation)){
		        $detail['author_str'] .= "<tr class='oddeven".fmod($i, 2)."'><td colspan='2'>".$author_affiliation." (".$author_affiliation_translation.")</td></tr>\n";
                    }
                    else if(!empty($author_affiliation)){
		        $detail['author_str'] .= "<tr class='oddeven".fmod($i, 2)."'><td colspan='2'>".$author_affiliation."</td></tr>\n";
                    }else if(!empty($author_affiliation_translation)){
		        $detail['author_str'] .= "<tr class='oddeven".fmod($i, 2)."'><td colspan='2'>(".$author_affiliation_translation.")</td></tr>\n";
                    }
                }
                if(!empty($author_role)){
		$detail['author_str'] .= "<tr class='oddeven".fmod($i, 2)."'><td width='20%'>"._MD_XNPARTICLE_AUTHOR_ROLE_LABEL."</td><td>:".$author_role."</td></tr>\n";
                }
                if(!empty($author_link)){
		$detail['author_str'] .= "<tr class='oddeven".fmod($i, 2)."'><td width='20%'>"._MD_XNPARTICLE_AUTHOR_LINK_LABEL."</td><td>:<a href='".$author_link."' target='_new'>".$author_link."</a></td></tr>\n";
                }

		$i++;
                //2008.07.18 igarashi add
                $author_raw_data["author_name"]=$author_name;
                $author_raw_data["author_romaji"]=$author_romaji;
                $author_raw_data["author_affiliation"] = $author_affiliation;
                $author_raw_data["author_affiliation_translation"] = $author_affiliation_translation;
                $author_raw_data["author_role"] = $author_role;
                $author_raw_data["author_link"] = $author_link;
                $authors_raw_data[]=$author_raw_data;
	}
	$detail['author_str'] .= "</table>";
	$detail['author_cnt'] = strval(fmod($i, 2));
	if (isset($authors_raw_data)) {	//2009.02.09 add
        //2008.07.18 igarashi add
        $detail['authors_raw_data']=$authors_raw_data;
    }

	if ($i == 0) {
		$detail['author_str'] = "";
	}
	return $detail;
}

function xnparticleDetailKeywordsString( $detail, $item_id ) {
	$detail_child_keywords = xnparticleGetDetailChildKeywordsInformation( $item_id );
	$detail['keyword_str'] = "<table>\n";
	$i = 0;
	while ( list( $key, list( $article_child_keywords_id, $article_id, $keywords, $keywords_order ) ) = each( $detail_child_keywords ) ){
		$detail['keyword_str'] .= "<tr class='oddeven".fmod($i, 2)."'><td>".$keywords."</td></tr>\n";
		$i++;
	}
	$detail['keyword_str'] .= "</table>";
	$detail['keywords_cnt'] = strval(fmod($i, 2));
	if ($i == 0) {
		$detail['keyword_str'] = "";
	}
	return $detail;
}

function xnparticleDetailNdcClassificationsString( $detail, $item_id ) {
	$detail_child_ndc_classifications = xnparticleGetDetailChildNdcClassificationsInformation( $item_id );
	$detail['ndc_classification_str'] = "<table>\n";
	$i = 0;
	while ( list( $key, list( $article_child_ndc_classifications_id, $article_id, $ndc_classifications, $ndc_classifications_order ) ) = each( $detail_child_ndc_classifications ) ){
		$detail['ndc_classification_str'] .= "<tr class='oddeven".fmod($i, 2)."'><td>".$ndc_classifications."</td></tr>\n";
		$i++;
	}
	$detail['ndc_classification_str'] .= "</table>";
	$detail['ndc_classifications_cnt'] = strval(fmod($i, 2));
	if ($i == 0) {
		$detail['ndc_classification_str'] = "";
	}
	return $detail;
}

function xnparticleDetailPhysicalDescriptionsString( $detail, $item_id ) {
        $detail_child_physical_descriptions = xnparticleGetDetailChildPhysicalDescriptionsInformation( $item_id );
        $detail['physical_description_str'] = "<table>\n";
        $i = 0;
        while ( list( $key, list( $article_child_physical_descriptions_id, $article_id, $physical_descriptions, $physical_descriptions_order ) ) = each( $detail_child_physical_descriptions ) ){
                $detail['physical_description_str'] .= "<tr class='oddeven".fmod($i, 2)."'><td>".$physical_descriptions."</td></tr>\n";
                $i++;
        }
        $detail['physical_description_str'] .= "</table>";
        $detail['physical_descriptions_cnt'] = strval(fmod($i, 2));
        if ($i == 0) {
                $detail['physical_description_str'] = "";
        }
        return $detail;
}

function xnparticleDetailLangsString( $detail, $item_id ) {
        $detail_child_langs = xnparticleGetDetailChildLangsInformation( $item_id );
        $detail['lang_str'] = "<table>\n";
        $i = 0;
        while ( list( $key, list( $article_child_langs_id, $article_id, $langs, $langs_order ) ) = each( $detail_child_langs ) ){
                $detail['lang_str'] .= "<tr class='oddeven".fmod($i, 2)."'><td>".$langs."</td></tr>\n";
                $i++;
        }
        $detail['lang_str'] .= "</table>";
        $detail['langs_cnt'] = strval(fmod($i, 2));
        if ($i == 0) {
                $detail['lang_str'] = "";
        }
        return $detail;
}

function xnparticleDetailIdIssnsString( $detail, $item_id ) {
        $detail_child_id_issns = xnparticleGetDetailChildIdIssnsInformation( $item_id );
        $detail['id_issn_str'] = "<table>\n";
        $i = 0;
        while ( list( $key, list( $article_child_id_issns_id, $article_id, $id_issns, $id_issns_order ) ) = each( $detail_child_id_issns ) ){
                $detail['id_issn_str'] .= "<tr class='oddeven".fmod($i, 2)."'><td>".$id_issns."</td></tr>\n";
                $i++;
                if(empty($id_issns))$i--;
        }
        $detail['id_issn_str'] .= "</table>";
        $detail['id_issns_cnt'] = strval(fmod($i, 2));
        if ($i == 0) {
                $detail['id_issn_str'] = "";
        }
        return $detail;
}

function xnparticleDetailIdIsbnsString( $detail, $item_id ) {
        $detail_child_id_isbns = xnparticleGetDetailChildIdIsbnsInformation( $item_id );
        $detail['id_isbn_str'] = "<table>\n";
        $i = 0;
        while ( list( $key, list( $article_child_id_isbns_id, $article_id, $id_isbns, $id_isbns_order ) ) = each( $detail_child_id_isbns ) ){
                $detail['id_isbn_str'] .= "<tr class='oddeven".fmod($i, 2)."'><td>".$id_isbns."</td></tr>\n";
                $i++;
                if(empty($id_isbns))$i--;
        }
        $detail['id_isbn_str'] .= "</table>";
        $detail['id_isbns_cnt'] = strval(fmod($i, 2));
        if ($i == 0) {
                $detail['id_isbn_str'] = "";
        }
        return $detail;
}

function xnparticleDetailIdDoisString( $detail, $item_id ) {
        $detail_child_id_dois = xnparticleGetDetailChildIdDoisInformation( $item_id );
        $detail['id_doi_str'] = "<table>\n";
        $i = 0;
        while ( list( $key, list( $article_child_id_dois_id, $article_id, $id_dois, $id_dois_order ) ) = each( $detail_child_id_dois ) ){
                $detail['id_doi_str'] .= "<tr class='oddeven".fmod($i, 2)."'><td>".$id_dois."</td></tr>\n";
                $i++;
                if(empty($id_dois))$i--;
        }
        $detail['id_doi_str'] .= "</table>";
        $detail['id_dois_cnt'] = strval(fmod($i, 2));
        if ($i == 0) {
                $detail['id_doi_str'] = "";
        }

        //igarashi add 2008.07.11
        //add id_doi_raw_str
        $detail['id_doi_raw_str'] = array();
        reset($detail_child_id_dois);
        while ( list( $key, list( $article_child_id_dois_id, $article_id, $id_dois, $id_dois_order ) ) = each( $detail_child_id_dois ) ){
                array_push($detail['id_doi_raw_str'],$id_dois);
        }
        //igarashi add 2008.07.11

        return $detail;
}

function xnparticleDetailIdUrisString( $detail, $item_id ) {
        $detail_child_id_uris = xnparticleGetDetailChildIdUrisInformation( $item_id );
        $detail['id_uri_str'] = "<table>\n";
        $i = 0;
        while ( list( $key, list( $article_child_id_uris_id, $article_id, $id_uris, $id_uris_order ) ) = each( $detail_child_id_uris ) ){
                $detail['id_uri_str'] .= "<tr class='oddeven".fmod($i, 2)."'><td>".$id_uris."</td></tr>\n";
                $i++;
                if(empty($id_uris))$i--;
        }
        $detail['id_uri_str'] .= "</table>";
        $detail['id_uris_cnt'] = strval(fmod($i, 2));
        if ($i == 0) {
                $detail['id_uri_str'] = "";
        }

        //igarashi add 2008.05.26
        //add id_uri_raw_str
        $detail['id_uri_raw_str'] = array();
        reset($detail_child_id_uris);
        while ( list( $key, list( $article_child_id_uris_id, $article_id, $id_uris, $id_uris_order ) ) = each( $detail_child_id_uris ) ){
                array_push($detail['id_uri_raw_str'],$id_uris);
        }
        //igarashi add 2008.05.26

        return $detail;
}

function xnparticleDetailIdLocalsString( $detail, $item_id ) {
        $detail_child_id_locals = xnparticleGetDetailChildIdLocalsInformation( $item_id );
        $detail['id_local_str'] = "<table>\n";
        $i = 0;
        while ( list( $key, list( $article_child_id_locals_id, $article_id, $id_locals, $id_locals_order ) ) = each( $detail_child_id_locals ) ){
                $detail['id_local_str'] .= "<tr class='oddeven".fmod($i, 2)."'><td>".$id_locals."</td></tr>\n";
                $i++;
                if(empty($id_locals))$i--;
        }
        $detail['id_local_str'] .= "</table>";
        $detail['id_locals_cnt'] = strval(fmod($i, 2));
        if ($i == 0) {
                $detail['id_local_str'] = "";
        }
        return $detail;
}

function xnparticleDetailUrisString( $detail, $item_id ) {
        $detail_child_uris = xnparticleGetDetailChildUrisInformation( $item_id );
        $detail['uri_str'] = "<table>\n";
        $i = 0;
        while ( list( $key, list( $article_child_uris_id, $article_id, $uris, $uris_order ) ) = each( $detail_child_uris ) ){
                $detail['uri_str'] .= "<tr class='oddeven".fmod($i, 2)."'><td>".$uris."</td></tr>\n";
                $i++;
                if(empty($uris))$i--;
        }
        $detail['uri_str'] .= "</table>";
        $detail['uris_cnt'] = strval(fmod($i, 2));
        if ($i == 0) {
                $detail['uri_str'] = "";
        }

        //igarashi add 2008.07.11
        //add uri_raw_str
        $detail['uri_raw_str'] = array();
        reset($detail_child_uris);
        while ( list( $key, list( $article_child_uris_id, $article_id, $uris, $uris_order ) ) = each( $detail_child_uris ) ){
                array_push($detail['uri_raw_str'],$uris);
        }
        //igarashi add 2008.07.11

        return $detail;
}

function xnparticleGetDetailChildHiddenStr( $item_id ) {
  $textutil =& xoonips_getutility( 'text' );
	$return_str = "";

	//detail_child_sub_title
	$detail_child_sub_title = xnparticleGetDetailChildSubTitleInformation( $item_id );
        $i = count($detail_child_sub_title);
        if ($i > 0) {
		$j = 0;
		$str_sub_title_name = "<input type='hidden' name='sub_title_name' value='";
		$str_sub_title_kana = "<input type='hidden' name='sub_title_kana' value='";
		$str_sub_title_romaji = "<input type='hidden' name='sub_title_romaji' value='";
		$str_sub_title_order = "<input type='hidden' name='sub_title_order' value='";
		while ( list( $key, list( $article_child_sub_title_id, $article_id, $sub_title_name, $sub_title_kana, $sub_title_romaji, $sub_title_order ) ) = each( $detail_child_sub_title ) ){
			if($j>0){
				$str_sub_title_name .= "\n";
				$str_sub_title_kana .= "\n";
				$str_sub_title_romaji .= "\n";
				$str_sub_title_order .= "\n";
			}
			$str_sub_title_name .= $textutil->html_special_chars($sub_title_name);
			$str_sub_title_kana .= $textutil->html_special_chars($sub_title_kana);
			$str_sub_title_romaji .= $textutil->html_special_chars($sub_title_romaji);
			$str_sub_title_order .= $textutil->html_special_chars($sub_title_order);
			$j++;
		}
		$str_sub_title_name .= "'>\n";
		$str_sub_title_kana .= "'>\n";
		$str_sub_title_romaji .= "'>\n";
		$str_sub_title_order .= "'>\n";
	}
	if (isset($str_sub_title_name)&&isset($str_sub_title_kana)&&isset($str_sub_title_romaji)&&isset($str_sub_title_order)) {	//2009.02.09 add
		$return_str .= $str_sub_title_name.$str_sub_title_kana.$str_sub_title_romaji.$str_sub_title_order;
	}

	//detail_child_author
	$detail_child_author = xnparticleGetDetailChildAuthorInformation( $item_id );
        $i = count($detail_child_author);
        if ($i > 0) {
		$j = 0;
		$str_author_id = "<input type='hidden' name='author_id' value='";
		$str_author_name = "<input type='hidden' name='author_name' value='";
		$str_author_kana = "<input type='hidden' name='author_kana' value='";
		$str_author_romaji = "<input type='hidden' name='author_romaji' value='";
		$str_author_affiliation = "<input type='hidden' name='author_affiliation' value='";
		$str_author_affiliation_translation = "<input type='hidden' name='author_affiliation_translation' value='";
		$str_author_role = "<input type='hidden' name='author_role' value='";
		$str_author_link = "<input type='hidden' name='author_link' value='";
		$str_author_order = "<input type='hidden' name='author_order' value='";
		while ( list( $key, list( $article_child_author_id, $article_id, $author_id, $author_name, $author_kana, $author_romaji, $author_affiliation, $author_affiliation_translation, $author_role, $author_link, $author_order ) ) = each( $detail_child_author ) ){
			if($j>0){
				$str_author_id .= "\n";
				$str_author_name .= "\n";
				$str_author_kana .= "\n";
				$str_author_romaji .= "\n";
				$str_author_affiliation .= "\n";
				$str_author_affiliation_translation .= "\n";
				$str_author_role .= "\n";
				$str_author_link .= "\n";
				$str_author_order .= "\n";
			}

			$str_author_id .= $textutil->html_special_chars($author_id);
			$str_author_name .= $textutil->html_special_chars($author_name);
			$str_author_kana .= $textutil->html_special_chars($author_kana);
			$str_author_romaji .= $textutil->html_special_chars($author_romaji);
			$str_author_affiliation .= $textutil->html_special_chars($author_affiliation);
			$str_author_affiliation_translation .= $textutil->html_special_chars($author_affiliation_translation);
			$str_author_role .= $textutil->html_special_chars($author_role);
			$str_author_link .= $textutil->html_special_chars($author_link);
			$str_author_order .= $textutil->html_special_chars($author_order);
			$j++;
		}
		$str_author_id .= "'>\n";
		$str_author_name .= "'>\n";
		$str_author_kana .= "'>\n";
		$str_author_romaji .= "'>\n";
		$str_author_affiliation .= "'>\n";
		$str_author_affiliation_translation .= "'>\n";
		$str_author_role .= "'>\n";
		$str_author_link .= "'>\n";
		$str_author_order .= "'>\n";
	}
	if (isset($str_author_id)) {	//2009.02.09 ADD
		$return_str .= $str_author_id.$str_author_name.$str_author_kana.$str_author_romaji.$str_author_affiliation.$str_author_affiliation_translation.$str_author_role.$str_author_link.$str_author_order;
	}

	//detail_child_keywords
	$detail_child_keywords = xnparticleGetDetailChildKeywordsInformation( $item_id );
        $i = count($detail_child_keywords);
        if ($i > 0) {
		$j = 0;
		$str_keywords = "<input type='hidden' name='keywords' value='";
		$str_keywords_order = "<input type='hidden' name='keywords_order' value='";
		while ( list( $key, list( $article_child_keywords_id, $article_id, $keywords, $keywords_order ) ) = each( $detail_child_keywords ) ){
			if($j>0){
				$str_keywords .= "\n";
				$str_keywords_order .= "\n";
			}
			$str_keywords .= $textutil->html_special_chars($keywords);
			$str_keywords_order .= $textutil->html_special_chars($keywords_order);
			$j++;
		}
		$str_keywords .= "'>\n";
		$str_keywords_order .= "'>\n";
	}
	if(empty($str_keywords))$str_keywords = "";
	if(empty($str_keywords_order))$str_keywords_order = "";
	$return_str .= $str_keywords.$str_keywords_order;

	//detail_child_ndc_classifications
	$detail_child_ndc_classifications = xnparticleGetDetailChildNdcClassificationsInformation( $item_id );
        $i = count($detail_child_ndc_classifications);
        if ($i > 0) {
		$j = 0;
		$str_ndc_classifications = "<input type='hidden' name='ndc_classifications' value='";
		$str_ndc_classifications_order = "<input type='hidden' name='ndc_classifications_order' value='";
		while ( list( $key, list( $article_child_ndc_classifications_id, $article_id, $ndc_classifications, $ndc_classifications_order ) ) = each( $detail_child_ndc_classifications ) ){
			if($j>0){
				$str_ndc_classifications .= "\n";
				$str_ndc_classifications_order .= "\n";
			}
			$str_ndc_classifications .= $ndc_classifications;
			$str_ndc_classifications_order .= $ndc_classifications_order;
			$j++;
		}
		$str_ndc_classifications .= "'>\n";
		$str_ndc_classifications_order .= "'>\n";
	}
	if(empty($str_ndc_classifications))$str_ndc_classifications = "";
	if(empty($str_ndc_classifications_order))$str_ndc_classifications_order = "";
	$return_str .= $str_ndc_classifications.$str_ndc_classifications_order;

	//detail_child_physical_descriptions
	$detail_child_physical_descriptions = xnparticleGetDetailChildPhysicalDescriptionsInformation( $item_id );
        $i = count($detail_child_physical_descriptions);
        if ($i > 0) {
		$j = 0;
		$str_physical_descriptions = "<input type='hidden' name='physical_descriptions' value='";
		$str_physical_descriptions_order = "<input type='hidden' name='physical_descriptions_order' value='";
		while ( list( $key, list( $article_child_physical_descriptions_id, $article_id, $physical_descriptions, $physical_descriptions_order ) ) = each( $detail_child_physical_descriptions ) ){
			if($j>0){
				$str_physical_descriptions .= "\n";
				$str_physical_descriptions_order .= "\n";
			}
			$str_physical_descriptions .= $physical_descriptions;
			$str_physical_descriptions_order .= $physical_descriptions_order;
			$j++;
		}
		$str_physical_descriptions .= "'>\n";
		$str_physical_descriptions_order .= "'>\n";
	}
	if(empty($str_physical_descriptions))$str_physical_descriptions = "";
	if(empty($str_physical_descriptions_order))$str_physical_descriptions_order = "";
	$return_str .= $str_physical_descriptions.$str_physical_descriptions_order;

	//detail_child_langs
	$detail_child_langs = xnparticleGetDetailChildLangsInformation( $item_id );
        $i = count($detail_child_langs);
        if ($i > 0) {
		$j = 0;
		$str_langs = "<input type='hidden' name='langs' value='";
		$str_langs_order = "<input type='hidden' name='langs_order' value='";
		while ( list( $key, list( $article_child_langs_id, $article_id, $langs, $langs_order ) ) = each( $detail_child_langs ) ){
			if($j>0){
				$str_langs .= "\n";
				$str_langs_order .= "\n";
			}
			$str_langs .= $langs;
			$str_langs_order .= $langs_order;
			$j++;
		}
		$str_langs .= "'>\n";
		$str_langs_order .= "'>\n";
	}
	if(empty($str_langs))$str_langs = "";
	if(empty($str_langs_order))$str_langs_order = "";
	$return_str .= $str_langs.$str_langs_order;

	//detail_child_id_issns
	$detail_child_id_issns = xnparticleGetDetailChildIdIssnsInformation( $item_id );
        $i = count($detail_child_id_issns);
        if ($i > 0) {
		$j = 0;
		$str_id_issns = "<input type='hidden' name='id_issns' value='";
		$str_id_issns_order = "<input type='hidden' name='id_issns_order' value='";
		while ( list( $key, list( $article_child_id_issns_id, $article_id, $id_issns, $id_issns_order ) ) = each( $detail_child_id_issns ) ){
			if($j>0){
				$str_id_issns .= "\n";
				$str_id_issns_order .= "\n";
			}
			$str_id_issns .= $id_issns;
			$str_id_issns_order .= $id_issns_order;
			$j++;
		}
		$str_id_issns .= "'>\n";
		$str_id_issns_order .= "'>\n";
	}
	if(empty($str_id_issns))$str_id_issns = "";
	if(empty($str_id_issns_order))$str_id_issns_order = "";
	$return_str .= $str_id_issns.$str_id_issns_order;

	//detail_child_id_isbns
	$detail_child_id_isbns = xnparticleGetDetailChildIdIsbnsInformation( $item_id );
        $i = count($detail_child_id_isbns);
        if ($i > 0) {
		$j = 0;
		$str_id_isbns = "<input type='hidden' name='id_isbns' value='";
		$str_id_isbns_order = "<input type='hidden' name='id_isbns_order' value='";
		while ( list( $key, list( $article_child_id_isbns_id, $article_id, $id_isbns, $id_isbns_order ) ) = each( $detail_child_id_isbns ) ){
			if($j>0){
				$str_id_isbns .= "\n";
				$str_id_isbns_order .= "\n";
			}
			$str_id_isbns .= $id_isbns;
			$str_id_isbns_order .= $id_isbns_order;
			$j++;
		}
		$str_id_isbns .= "'>\n";
		$str_id_isbns_order .= "'>\n";
	}
	if(empty($str_id_isbns))$str_id_isbns = "";
	if(empty($str_id_isbns_order))$str_id_isbns_order = "";
	$return_str .= $str_id_isbns.$str_id_isbns_order;

	//detail_child_id_dois
	$detail_child_id_dois = xnparticleGetDetailChildIdDoisInformation( $item_id );
        $i = count($detail_child_id_dois);
        if ($i > 0) {
		$j = 0;
		$str_id_dois = "<input type='hidden' name='id_dois' value='";
		$str_id_dois_order = "<input type='hidden' name='id_dois_order' value='";
		while ( list( $key, list( $article_child_id_dois_id, $article_id, $id_dois, $id_dois_order ) ) = each( $detail_child_id_dois ) ){
			if($j>0){
				$str_id_dois .= "\n";
				$str_id_dois_order .= "\n";
			}
			$str_id_dois .= $id_dois;
			$str_id_dois_order .= $id_dois_order;
			$j++;
		}
		$str_id_dois .= "'>\n";
		$str_id_dois_order .= "'>\n";
	}
	if(empty($str_id_dois))$str_id_dois = "";
	if(empty($str_id_dois_order))$str_id_dois_order = "";
	$return_str .= $str_id_dois.$str_id_dois_order;

	//detail_child_id_uris
	$detail_child_id_uris = xnparticleGetDetailChildIdUrisInformation( $item_id );
        $i = count($detail_child_id_uris);
        if ($i > 0) {
		$j = 0;
		$str_id_uris = "<input type='hidden' name='id_uris' value='";
		$str_id_uris_order = "<input type='hidden' name='id_uris_order' value='";
		while ( list( $key, list( $article_child_id_uris_id, $article_id, $id_uris, $id_uris_order ) ) = each( $detail_child_id_uris ) ){
			if($j>0){
				$str_id_uris .= "\n";
				$str_id_uris_order .= "\n";
			}
			$str_id_uris .= $id_uris;
			$str_id_uris_order .= $id_uris_order;
			$j++;
		}
		$str_id_uris .= "'>\n";
		$str_id_uris_order .= "'>\n";
	}
	if(empty($str_id_uris))$str_id_uris = "";
	if(empty($str_id_uris_order))$str_id_uris_order = "";
	$return_str .= $str_id_uris.$str_id_uris_order;

	//detail_child_id_locals
	$detail_child_id_locals = xnparticleGetDetailChildIdLocalsInformation( $item_id );
        $i = count($detail_child_id_locals);
        if ($i > 0) {
		$j = 0;
		$str_id_locals = "<input type='hidden' name='id_locals' value='";
		$str_id_locals_order = "<input type='hidden' name='id_locals_order' value='";
		while ( list( $key, list( $article_child_id_locals_id, $article_id, $id_locals, $id_locals_order ) ) = each( $detail_child_id_locals ) ){
			if($j>0){
				$str_id_locals .= "\n";
				$str_id_locals_order .= "\n";
			}
			$str_id_locals .= $id_locals;
			$str_id_locals_order .= $id_locals_order;
			$j++;
		}
		$str_id_locals .= "'>\n";
		$str_id_locals_order .= "'>\n";
	}
	if(empty($str_id_locals))$str_id_locals = "";
	if(empty($str_id_locals_order))$str_id_locals_order = "";
	$return_str .= $str_id_locals.$str_id_locals_order;

	//detail_child_uris
	$detail_child_uris = xnparticleGetDetailChildUrisInformation( $item_id );
        $i = count($detail_child_uris);
        if ($i > 0) {
		$j = 0;
		$str_uris = "<input type='hidden' name='uris' value='";
		$str_uris_order = "<input type='hidden' name='uris_order' value='";
		while ( list( $key, list( $article_child_uris_id, $article_id, $uris, $uris_order ) ) = each( $detail_child_uris ) ){
			if($j>0){
				$str_uris .= "\n";
				$str_uris_order .= "\n";
			}
			$str_uris .= $uris;
			$str_uris_order .= $uris_order;
			$j++;
		}
		$str_uris .= "'>\n";
		$str_uris_order .= "'>\n";
	}
	if(empty($str_uris))$str_uris = "";
	if(empty($str_uris_order))$str_uris_order = "";
	$return_str .= $str_uris.$str_uris_order;

	return $return_str;
}

function xnparticleRegisterTitleRomajiString( $detail ) {
	if (( isset($_POST['xnparticleTitleRomajiFlag']) ) && ( $_POST['xnparticleTitleRomajiFlag'] == '1' )) {
		$str = $detail['title_kana'];
		$detail['title_romaji'] = xnparticleKana2Roma($str);
	}
	return $detail;
}

//igarashi add 20080616
function xnparticleRegisterSubTitleRomajiString( $detail ) {
	if (( isset($_POST['xnparticleSubTitleRomajiFlag']) ) && ( $_POST['xnparticleSubTitleRomajiFlag'] == '1' )) {
		$str = $detail['sub_title_kana_add'];
		$detail['sub_title_romaji_add'] = xnparticleKana2Roma($str);
	}
	return $detail;
}

function xnparticleRegisterAuthorRomajiString( $detail ) {
        if (( isset($_POST['xnparticleAuthorRomajiFlag']) ) && ( $_POST['xnparticleAuthorRomajiFlag'] == '1' )) {
                $str = $detail['author_kana_add'];
                $detail['author_romaji_add'] = xnparticleKana2Roma($str);
        }
        return $detail;
}

function xnparticleRegisterPublisherRomajiString( $detail ) {
        if (( isset($_POST['xnparticlePublisherRomajiFlag']) ) && ( $_POST['xnparticlePublisherRomajiFlag'] == '1' )) {
                $str = $detail['publisher_kana'];
                $detail['publisher_romaji'] = xnparticleKana2Roma($str);
        }
        return $detail;
}
//igarashi add 20080616

function xnparticleRegisterSubTitleString( $detail ) {
  $textutil =& xoonips_getutility( 'text' );
	if (( isset($_POST['xnparticleAddSubTitleFlag']) ) && ( $_POST['xnparticleAddSubTitleFlag'] == '1' )) {
		if (!empty($detail['sub_title_name']) || !empty($detail['sub_title_kana']) || !empty($detail['sub_title_romaji']) ){
			$detail['sub_title_name'] .= "\n";
			$detail['sub_title_kana'] .= "\n";
			$detail['sub_title_romaji'] .= "\n";
		}
		$detail['sub_title_name'] .= ( !empty( $detail['sub_title_name_add'] ) ) ? $detail['sub_title_name_add'] : " ";
		$detail['sub_title_kana'] .= ( !empty( $detail['sub_title_kana_add'] ) ) ? $detail['sub_title_kana_add'] : " ";
		$detail['sub_title_romaji'] .= ( !empty( $detail['sub_title_romaji_add'] ) ) ? $detail['sub_title_romaji_add'] : " ";
		$detail['sub_title_name_add'] = '';
		$detail['sub_title_kana_add'] = '';
		$detail['sub_title_romaji_add'] = '';
	}
	if (( isset($_POST['xnparticleUpdateSubTitleFlag']) ) && ( $_POST['xnparticleUpdateSubTitleFlag'] == '1' )) {
		$sub_title_name_update = array();
		$sub_title_kana_update = array();
		$sub_title_romaji_update = array();
		$sub_title_order_update = array();
		$sub_title_delete_update = array();
		foreach ( $_POST as $key=>$value ){
			if (substr($key,0,24) == "sub_title_delete_update_"){
				array_push($sub_title_delete_update, $value);
			}
		}
		$i = 0;
		$j = 0;
		$k = 0;
		$l = 0;
		foreach ( $_POST as $key=>$value ){
			if (substr($key,0,22) == "sub_title_name_update_"){
				if (in_array($i, $sub_title_delete_update) == FALSE) {
					array_push($sub_title_name_update, ( $value != "" ) ? $value : " ");
				}
				$i++;
			}
			if (substr($key,0,22) == "sub_title_kana_update_"){
				if (in_array($j, $sub_title_delete_update) == FALSE) {
					array_push($sub_title_kana_update, ( $value != "" ) ? $value : " ");
				}
				$j++;
			}
			if (substr($key,0,24) == "sub_title_romaji_update_"){
				if (in_array($k, $sub_title_delete_update) == FALSE) {
					array_push($sub_title_romaji_update, ( $value != "" ) ? $value : " ");
				}
				$k++;
			}
			if (substr($key,0,23) == "sub_title_order_update_"){
				if (in_array($l, $sub_title_delete_update) == FALSE) {
					array_push($sub_title_order_update, $value);
				}
				$l++;
			}
		}
		$sub_title_sort = array_map(NULL, $sub_title_order_update, $sub_title_name_update, $sub_title_kana_update, $sub_title_romaji_update);
		sort($sub_title_sort);
		$detail['sub_title_name'] = "";
		$detail['sub_title_kana'] = "";
		$detail['sub_title_romaji'] = "";
		foreach ( $sub_title_sort as $key=>$value ){
			if (!empty($value[1]) || !empty($value[2]) ||!empty($value[3]) ) { 
				if ( !empty( $detail['sub_title_name'] ) || !empty( $detail['sub_title_kana'] ) || !empty( $detail['sub_title_romaji'] ) ){
					$detail['sub_title_name'] .= "\n";
					$detail['sub_title_kana'] .= "\n";
					$detail['sub_title_romaji'] .= "\n";
				}
				$detail['sub_title_name'] .= $value[1];
				$detail['sub_title_kana'] .= $value[2];
				$detail['sub_title_romaji'] .= $value[3];
			}
		}
	}
	if ( !empty($detail['sub_title_name']) ){
		$sub_title_name = explode( "\n", $detail['sub_title_name'] );
		$sub_title_kana = explode( "\n", $detail['sub_title_kana'] );
		$sub_title_romaji = explode( "\n", $detail['sub_title_romaji'] );
		$i = count($sub_title_name);
		$j = 0;
		reset($sub_title_kana);
		reset($sub_title_romaji);
		$detail['sub_title_str'] = "<table><tr><td>"._MD_XNPARTICLE_EDIT_LABEL."</td><td>"._MD_XNPARTICLE_DELETE_LABEL."&nbsp;"._MD_XNPARTICLE_ORDER_LABEL."</td></tr>\n";
		foreach ( $sub_title_name as  $value ){
			$sub_title_name_1 = ( $value != " " ) ? $value : "";
			$sub_title_kana_1 = ( current($sub_title_kana) != " " ) ? current($sub_title_kana) : "";
			$sub_title_romaji_1 = ( current($sub_title_romaji) != " " ) ? current($sub_title_romaji) : "";
			$detail['sub_title_str'] .= "<tr><td colspan='3'><hr /></td></tr><tr><td width='320'><input size=40 type='text' name='sub_title_name_update_".$j."' value='".$textutil->html_special_chars($value)."' />:"._MD_XNPARTICLE_AUTHOR_NAME_LABEL."</td>\n";
			$detail['sub_title_str'] .= "<td>&nbsp;&nbsp;<input type='checkbox' name='sub_title_delete_update_".$j."' value='".$j."' />\n";
			$detail['sub_title_str'] .= "&nbsp;&nbsp;<select size='1' name='sub_title_order_update_".$j."'>";
			for ($k = 0; $k < $i; $k++) {
				$detail['sub_title_str'] .= "<option value='".$k."'";
				if ($k == $j) {
					$detail['sub_title_str'] .= " selected";
				}
				$detail['sub_title_str'] .= ">".$k."</option>";
			}
			$detail['sub_title_str'] .= "</td></tr>\n<tr><td width='320'><input size=40 type='text' name='sub_title_kana_update_".$j."' value='".$textutil->html_special_chars($sub_title_kana_1)."' />:"._MD_XNPARTICLE_KANA_LABEL."</td>\n";
			$detail['sub_title_str'] .= "</td></tr>\n<tr><td width='320'><input size=40 type='text' name='sub_title_romaji_update_".$j."' value='".$textutil->html_special_chars($sub_title_romaji_1)."' />:"._MD_XNPARTICLE_ROMAJI_LABEL."</td>\n";
			$j++;
			next($sub_title_kana);
			next($sub_title_romaji);
		}
		$detail['sub_title_str'] .= "</table>";
	}
	return $detail;
}


function xnparticleRegisterAuthorString( $detail ) {
  $textutil =& xoonips_getutility( 'text' );

	if (( isset($_POST['xnparticleAddAuthorFlag']) ) && ( $_POST['xnparticleAddAuthorFlag'] == '1' )) {
		if (( !empty( $detail['author_id'] ) ) || ( !empty( $detail['author_name'] ) ) || ( !empty( $detail['author_kana'] ) ) || ( !empty( $detail['author_romaji'] ) ) || ( !empty( $detail['author_affiliation'] ) ) || ( !empty( $detail['author_affiliation_translation'] )) || ( !empty( $detail['author_role'] ) ) || ( !empty( $detail['author_link'] ) )){
			$detail['author_id'] .= "\n";
			$detail['author_name'] .= "\n";
			$detail['author_kana'] .= "\n";
			$detail['author_romaji'] .= "\n";
			$detail['author_affiliation'] .= "\n";
			$detail['author_affiliation_translation'] .= "\n";
			$detail['author_role'] .= "\n";
			$detail['author_link'] .= "\n";
		}
		$detail['author_id']   .= ( !empty( $detail['author_id_add'] ) ) ? $detail['author_id_add'] : " ";
		$detail['author_name'] .= ( !empty( $detail['author_name_add'] ) ) ? $detail['author_name_add'] : " ";
		$detail['author_kana'] .= ( !empty( $detail['author_kana_add'] ) ) ? $detail['author_kana_add'] : " ";
		$detail['author_romaji'] .= ( !empty( $detail['author_romaji_add'] ) ) ? $detail['author_romaji_add'] : " ";
		$detail['author_affiliation'] .= ( !empty( $detail['author_affiliation_add'] ) ) ? $detail['author_affiliation_add'] : " ";
		$detail['author_affiliation_translation'] .= ( !empty( $detail['author_affiliation_translation_add'] ) ) ? $detail['author_affiliation_translation_add'] : " ";
		$detail['author_role'] .= ( !empty( $detail['author_role_add'] ) ) ? $detail['author_role_add'] : " ";
		$detail['author_link'] .= ( !empty( $detail['author_link_add'] ) ) ? $detail['author_link_add'] : " ";
		$detail['author_id_add'] = '';
		$detail['author_name_add'] = '';
		$detail['author_kana_add'] = '';
		$detail['author_romaji_add'] = '';
		$detail['author_role_add'] = '';
		$detail['author_link_add'] = '';
	}
	if (( isset($_POST['xnparticleUpdateAuthorFlag']) ) && ( $_POST['xnparticleUpdateAuthorFlag'] == '1' )) {
		$author_id_update = array();
		$author_name_update = array();
		$author_kana_update = array();
		$author_romaji_update = array();
		$author_affiliation_update = array();
		$author_affiliation_translation_update = array();
		$author_role_update = array();
		$author_link_update = array();
		$author_order_update = array();
		$author_delete_update = array();
		foreach ( $_POST as $key=>$value ){
			if (substr($key,0,21) == "author_delete_update_"){
				array_push($author_delete_update, $value);
			}
		}
		$i = 0;
		$j = 0;
		$k = 0;
		$l = 0;
		$m = 0;
		$n = 0;
		$o = 0;
		$p = 0;
		$q = 0;
		foreach ( $_POST as $key=>$value ){
			if (substr($key,0,17) == "author_id_update_"){
				if (in_array($i, $author_delete_update) == FALSE) {
					array_push($author_id_update, ( $value != "" ) ? $value : " ");
				}
				$i++;
			}
			if (substr($key,0,19) == "author_name_update_"){
				if (in_array($j, $author_delete_update) == FALSE) {
					array_push($author_name_update, ( $value != "" ) ? $value : " ");
				}
				$j++;
			}
			if (substr($key,0,19) == "author_kana_update_"){
				if (in_array($k, $author_delete_update) == FALSE) {
					array_push($author_kana_update, ( $value != "" ) ? $value : " ");
				}
				$k++;
			}
			if (substr($key,0,21) == "author_romaji_update_"){
				if (in_array($l, $author_delete_update) == FALSE) {
					array_push($author_romaji_update, ( $value != "" ) ? $value : " ");
				}
				$l++;
			}
			if (substr($key,0,26) == "author_affiliation_update_"){
				if (in_array($m, $author_delete_update) == FALSE) {
					array_push($author_affiliation_update, ( $value != "" ) ? $value : " ");
				}
				$m++;
			}
			if (substr($key,0,38) == "author_affiliation_translation_update_"){
				if (in_array($n, $author_delete_update) == FALSE) {
					array_push($author_affiliation_translation_update, ( $value != "" ) ? $value : " ");
				}
				$n++;
			}
			if (substr($key,0,19) == "author_role_update_"){
				if (in_array($o, $author_delete_update) == FALSE) {
					array_push($author_role_update, ( $value != "" ) ? $value : " ");
				}
				$o++;
			}
			if (substr($key,0,19) == "author_link_update_"){
				if (in_array($p, $author_delete_update) == FALSE) {
					array_push($author_link_update, ( $value != "" ) ? $value : " ");
				}
				$p++;
			}
			if (substr($key,0,20) == "author_order_update_"){
				if (in_array($q, $author_delete_update) == FALSE) {
					array_push($author_order_update, $value);
				}
				$q++;
			}
		}
		$author_sort = array_map(NULL, $author_order_update, $author_id_update, $author_name_update, $author_kana_update, $author_romaji_update, $author_affiliation_update, $author_affiliation_translation_update, $author_role_update, $author_link_update);
		sort($author_sort);
		$detail['author_id'] = "";
		$detail['author_name'] = "";
		$detail['author_kana'] = "";
		$detail['author_romaji'] = "";
		$detail['author_affiliation'] = "";
		$detail['author_affiliation_translation'] = "";
		$detail['author_role'] = "";
		$detail['author_link'] = "";
		foreach ( $author_sort as $key=>$value ){
			if ((!empty($value[1])) || (!empty($value[2])) || (!empty($value[3])) || (!empty($value[4])) || (!empty($value[5]))  || (!empty($value[6]))  || (!empty($value[7])) || (!empty($value[8])) ) { 
				if ( ( !empty( $detail['author_id'] ) ) || ( !empty( $detail['author_name'] ) ) || ( !empty( $detail['author_kana'] ) ) || ( !empty( $detail['author_romaji'] ) ) || ( !empty( $detail['author_affiliation'] ) ) || ( !empty( $detail['author_affiliation_translation'] )) || ( !empty( $detail['author_role'] ) ) || ( !empty( $detail['author_link'] )) ){
					$detail['author_id'] .= "\n";
					$detail['author_name'] .= "\n";
					$detail['author_kana'] .= "\n";
					$detail['author_romaji'] .= "\n";
					$detail['author_affiliation'] .= "\n";
					$detail['author_affiliation_translation'] .= "\n";
					$detail['author_role'] .= "\n";
					$detail['author_link'] .= "\n";
				}
				$detail['author_id'] .= $value[1];
				$detail['author_name'] .= $value[2];
				$detail['author_kana'] .= $value[3];
				$detail['author_romaji'] .= $value[4];
				$detail['author_affiliation'] .= $value[5];
				$detail['author_affiliation_translation'] .= $value[6];
				$detail['author_role'] .= $value[7];
				$detail['author_link'] .= $value[8];
			}
		}
	}
	if ( !empty( $detail['author_name'] ) ){
		$author_id   = explode( "\n", $detail['author_id'] );
		$author_name = explode( "\n", $detail['author_name'] );
		$author_kana = explode( "\n", $detail['author_kana'] );
		$author_romaji = explode( "\n", $detail['author_romaji'] );
		$author_affiliation = explode( "\n", $detail['author_affiliation'] );
		$author_affiliation_translation = explode( "\n", $detail['author_affiliation_translation'] );
		$author_role = explode( "\n", $detail['author_role'] );
		$author_link = explode( "\n", $detail['author_link'] );
		$i = count($author_name);
		$j = 0;
		reset($author_id);
		reset($author_kana);
		reset($author_romaji);
		reset($author_affiliation);
		reset($author_affiliation_translation);
		reset($author_role);
		reset($author_link);
		$detail['author_str'] = "<table><tr><td>"._MD_XNPARTICLE_EDIT_LABEL."</td><td>"._MD_XNPARTICLE_DELETE_LABEL."&nbsp;"._MD_XNPARTICLE_ORDER_LABEL."</td></tr>\n";
		foreach ( $author_name as  $value ){
			$author_name_1 = ( $value != " " ) ? $value : "";
			$author_id_1 = ( current($author_id) != " " ) ? current($author_id) : "";
			$author_kana_1 = ( current($author_kana) != " " ) ? current($author_kana) : "";
			$author_romaji_1 = ( current($author_romaji) != " " ) ? current($author_romaji) : "";
			$author_affiliation_1 = ( current($author_affiliation) != " " ) ? current($author_affiliation) : "";
			$author_affiliation_translation_1 = ( current($author_affiliation_translation) != " " ) ? current($author_affiliation_translation) : "";
			$author_role_1 = ( current($author_role) != " " ) ? current($author_role) : "";
			$author_link_1 = ( current($author_link) != " " ) ? current($author_link) : "";
			$detail['author_str'] .= "<tr><td colspan='3'><hr /></td></tr><tr><td width='320'><input size=40 type='text' name='author_id_update_".$j."' value='".$author_id_1."' />:"._MD_XNPARTICLE_AUTHOR_ID_LABEL."</td>\n";
			$detail['author_str'] .= "<td>&nbsp;&nbsp;<input type='checkbox' name='author_delete_update_".$j."' value='".$j."' />\n";
			$detail['author_str'] .= "&nbsp;&nbsp;<select size='1' name='author_order_update_".$j."'>";
			for ($k = 0; $k < $i; $k++) {
				$detail['author_str'] .= "<option value='".$k."'";
				if ($k == $j) {
					$detail['author_str'] .= " selected";
				}
				$detail['author_str'] .= ">".$k."</option>";
			}
			$detail['author_str'] .= "</td></tr>\n<tr><td width='320'><input size=40 type='text' name='author_name_update_".$j."' value='".$textutil->html_special_chars($value)."' />:"._MD_XNPARTICLE_AUTHOR_NAME_LABEL."</td>\n";
			$detail['author_str'] .= "</td></tr>\n<tr><td width='320'><input size=40 type='text' name='author_kana_update_".$j."' value='".$textutil->html_special_chars($author_kana_1)."' />:"._MD_XNPARTICLE_KANA_LABEL."</td>\n";
			$detail['author_str'] .= "</td></tr>\n<tr><td width='320'><input size=40 type='text' name='author_romaji_update_".$j."' value='".$textutil->html_special_chars($author_romaji_1)."' />:"._MD_XNPARTICLE_ROMAJI_LABEL."</td>\n";
			$detail['author_str'] .= "<tr><td width='320'><input size='40' type='text' name='author_affiliation_update_".$j."' value='".$textutil->html_special_chars($author_affiliation_1)."' />:"._MD_XNPARTICLE_AUTHOR_AFFILIATION_LABEL."</td></tr>\n";
			$detail['author_str'] .= "<tr><td width='320'><input size='40' type='text' name='author_affiliation_translation_update_".$j."' value='".$textutil->html_special_chars($author_affiliation_translation_1)."' />:"._MD_XNPARTICLE_AUTHOR_AFFILIATION_TRANSLATION_LABEL."</td></tr>\n";
			$detail['author_str'] .= "</td></tr>\n<tr><td width='320'><input size=40 type='text' name='author_role_update_".$j."' value='".$textutil->html_special_chars($author_role_1)."' />:"._MD_XNPARTICLE_AUTHOR_ROLE_LABEL."</td>\n";
			$detail['author_str'] .= "</td></tr>\n<tr><td width='320'><input size=40 type='text' name='author_link_update_".$j."' value='".$textutil->html_special_chars($author_link_1)."' />:"._MD_XNPARTICLE_AUTHOR_LINK_LABEL."</td>\n";
			$j++;
			next($author_id);
			next($author_kana);
			next($author_romaji);
			next($author_affiliation);
			next($author_affiliation_translation);
			next($author_role);
			next($author_link);
		}
		$detail['author_str'] .= "</table>";
	}
	return $detail;
}


function xnparticleRegisterKeywordsString( $detail ) {
  $textutil =& xoonips_getutility( 'text' );

	if (( isset($_POST['xnparticleAddKeywordFlag']) ) && ( $_POST['xnparticleAddKeywordFlag'] == '1' )) {
		if ( !empty( $detail['keywords'] ) ){
			$detail['keywords'] .= "\n";
		}
		$detail['keywords'] .= $detail['keywords_add'];
		$detail['keywords_add'] = '';
	}
	if (( isset($_POST['xnparticleUpdateKeywordFlag']) ) && ( $_POST['xnparticleUpdateKeywordFlag'] == '1' )) {
		$keywords_update = array();
		$keywords_order_update = array();
		$keywords_delete_update = array();
		foreach ( $_POST as $key=>$value ){
			if (substr($key,0,22) == "keyword_delete_update_"){
				array_push($keywords_delete_update, $value);
			}
		}
		$i = 0;
		$j = 0;
		foreach ( $_POST as $key=>$value ){
			if (substr($key,0,15) == "keyword_update_"){
				if (in_array($i, $keywords_delete_update) == FALSE) {
					array_push($keywords_update, ( $value != "" ) ? $value : " ");
				}
				$i++;
			}
			if (substr($key,0,21) == "keyword_order_update_"){
				if (in_array($j, $keywords_delete_update) == FALSE) {
					array_push($keywords_order_update, $value);
				}
				$j++;
			}
		}
		$keywords_sort = array_map(NULL, $keywords_order_update, $keywords_update);
		sort($keywords_sort);
		$detail['keywords'] = "";
		foreach ( $keywords_sort as $key=>$value ){
			if (!empty($value[1])) { 
				if ( !empty( $detail['keywords'] ) ){
					$detail['keywords'] .= "\n";
				}
				$detail['keywords'] .= $value[1];
			}
		}
	}
	if ( !empty( $detail['keywords'] ) ){
		$keywords = explode( "\n", $detail['keywords'] );
		$i = count($keywords);
		$j = 0;
		$detail['keyword_str'] = "<table><tr><td>"._MD_XNPARTICLE_EDIT_LABEL."</td><td class='oddeven'>"._MD_XNPARTICLE_DELETE_LABEL."&nbsp;"._MD_XNPARTICLE_ORDER_LABEL."</td></tr>\n";
		foreach ( $keywords as  $value ){
			$keywords_1 = ( $value != " " ) ? $value : "";
			$detail['keyword_str'] .= "<tr>";
			$detail['keyword_str'] .= "<td width='10'><input size=50 type='text' name='keyword_update_".$j."' value='".$textutil->html_special_chars($keywords_1)."' STYLE='ime-mode:active;' /></td>\n";
			$detail['keyword_str'] .= "<td>&nbsp;&nbsp;<input type='checkbox' name='keyword_delete_update_".$j."' value='".$j."' />\n";
			$detail['keyword_str'] .= "&nbsp;&nbsp;<select size='1' name='keyword_order_update_".$j."'>";
			for ($k = 0; $k < $i; $k++) {
				$detail['keyword_str'] .= "<option value='".$k."'";
				if ($k == $j) {
					$detail['keyword_str'] .= " selected";
				}
				$detail['keyword_str'] .= ">".$k."</option>";
			}
			$detail['keyword_str'] .= "</td></tr>\n";
			$j++;
		}
		$detail['keyword_str'] .= "</table>\n";
	}
	return $detail;
}


function xnparticleRegisterNdcClassificationsString( $detail ) {
        if (( isset($_POST['xnparticleAddNdcClassificationFlag']) ) && ( $_POST['xnparticleAddNdcClassificationFlag'] == '1' )) {
                if ( !empty( $detail['ndc_classifications'] ) ){
                        $detail['ndc_classifications'] .= "\n";
                }
                $detail['ndc_classifications'] .= $detail['ndc_classifications_add'];
                $detail['ndc_classifications_add'] = '';
        }
        if (( isset($_POST['xnparticleUpdateNdcClassificationFlag']) ) && ( $_POST['xnparticleUpdateNdcClassificationFlag'] == '1' )) {
                $ndc_classifications_update = array();
                $ndc_classifications_order_update = array();
                $ndc_classifications_delete_update = array();
                foreach ( $_POST as $key=>$value ){
                        if (substr($key,0,33) == "ndc_classification_delete_update_"){
                                array_push($ndc_classifications_delete_update, $value);
                        }
                }
                $i = 0;
                $j = 0;
                foreach ( $_POST as $key=>$value ){
                        if (substr($key,0,26) == "ndc_classification_update_"){
                                if (in_array($i, $ndc_classifications_delete_update) == FALSE) {
                                        array_push($ndc_classifications_update, ( $value != "" ) ? $value : " ");
                                }
                                $i++;
                        }
                        if (substr($key,0,32) == "ndc_classification_order_update_"){
                                if (in_array($j, $ndc_classifications_delete_update) == FALSE) {
                                        array_push($ndc_classifications_order_update, $value);
                                }
                                $j++;
                        }
                }
                $ndc_classifications_sort = array_map(NULL, $ndc_classifications_order_update, $ndc_classifications_update);
                sort($ndc_classifications_sort);
                $detail['ndc_classifications'] = "";
                foreach ( $ndc_classifications_sort as $key=>$value ){
                        if (!empty($value[1])) {
                                if ( !empty( $detail['ndc_classifications'] ) ){
                                        $detail['ndc_classifications'] .= "\n";
                                }
                                $detail['ndc_classifications'] .= $value[1];
                        }
                }
        }
        if ( !empty( $detail['ndc_classifications'] ) ){
                $ndc_classifications = explode( "\n", $detail['ndc_classifications'] );
                $i = count($ndc_classifications);
                $j = 0;
                $detail['ndc_classification_str'] = "<table><tr><td>"._MD_XNPARTICLE_EDIT_LABEL."</td><td class='oddeven'>"._MD_XNPARTICLE_DELETE_LABEL."&nbsp;"._MD_XNPARTICLE_ORDER_LABEL."</td></tr>\n";
                foreach ( $ndc_classifications as  $value ){
                        $ndc_classifications_1 = ( $value != " " ) ? $value : "";
                        $detail['ndc_classification_str'] .= "<tr>";
                        $detail['ndc_classification_str'] .= "<td width='10'><input size=50 type='text' name='ndc_classification_update_".$j."' value='".$ndc_classifications_1."' STYLE='ime-mode:active;' /></td>\n";
                        $detail['ndc_classification_str'] .= "<td>&nbsp;&nbsp;<input type='checkbox' name='ndc_classification_delete_update_".$j."' value='".$j."' />\n";
			$detail['ndc_classification_str'] .= "&nbsp;&nbsp;<select size='1' name='ndc_classification_order_update_".$j."'>";
                        for ($k = 0; $k < $i; $k++) {
                                $detail['ndc_classification_str'] .= "<option value='".$k."'";
                                if ($k == $j) {
                                        $detail['ndc_classification_str'] .= " selected";
                                }
                                $detail['ndc_classification_str'] .= ">".$k."</option>";
                        }
                        $detail['ndc_classification_str'] .= "</td></tr>\n";
                        $j++;
                }
                $detail['ndc_classification_str'] .= "</table>\n";
        }
        return $detail;
}

function xnparticleRegisterPhysicalDescriptionsString( $detail ) {
        if (( isset($_POST['xnparticleAddPhysicalDescriptionFlag']) ) && ( $_POST['xnparticleAddPhysicalDescriptionFlag'] == '1' )) {
                if ( !empty( $detail['physical_descriptions'] ) ){
                        $detail['physical_descriptions'] .= "\n";
                }
                $detail['physical_descriptions'] .= $detail['physical_descriptions_add'];
                $detail['physical_descriptions_add'] = '';
        }
        if (( isset($_POST['xnparticleUpdatePhysicalDescriptionFlag']) ) && ( $_POST['xnparticleUpdatePhysicalDescriptionFlag'] == '1' )) {
                $physical_descriptions_update = array();
                $physical_descriptions_order_update = array();
                $physical_descriptions_delete_update = array();
                foreach ( $_POST as $key=>$value ){
                        if (substr($key,0,35) == "physical_description_delete_update_"){
                                array_push($physical_descriptions_delete_update, $value);
                        }
                }
                $i = 0;
                $j = 0;
                foreach ( $_POST as $key=>$value ){
                        if (substr($key,0,28) == "physical_description_update_"){
                                if (in_array($i, $physical_descriptions_delete_update) == FALSE) {
                                        array_push($physical_descriptions_update, ( $value != "" ) ? $value : " ");
                                }
                                $i++;
                        }
                        if (substr($key,0,34) == "physical_description_order_update_"){
                                if (in_array($j, $physical_descriptions_delete_update) == FALSE) {
                                        array_push($physical_descriptions_order_update, $value);
                                }
                                $j++;
                        }
                }
                $physical_descriptions_sort = array_map(NULL, $physical_descriptions_order_update, $physical_descriptions_update);
                sort($physical_descriptions_sort);
                $detail['physical_descriptions'] = "";
                foreach ( $physical_descriptions_sort as $key=>$value ){
                        if (!empty($value[1])) {
                                if ( !empty( $detail['physical_descriptions'] ) ){
                                        $detail['physical_descriptions'] .= "\n";
                                }
                                $detail['physical_descriptions'] .= $value[1];
                        }
                }
        }
        if ( !empty( $detail['physical_descriptions'] ) ){
                $physical_descriptions = explode( "\n", $detail['physical_descriptions'] );
                $i = count($physical_descriptions);
                $j = 0;
                $detail['physical_description_str'] = "<table><tr><td>"._MD_XNPARTICLE_EDIT_LABEL."</td><td class='oddeven'>"._MD_XNPARTICLE_DELETE_LABEL."&nbsp;"._MD_XNPARTICLE_ORDER_LABEL."</td></tr>\n";
                foreach ( $physical_descriptions as  $value ){
                        $physical_descriptions_1 = ( $value != " " ) ? $value : "";
                        $detail['physical_description_str'] .= "<tr>";
                        $detail['physical_description_str'] .= "<td width='10'><input size=50 type='text' name='physical_description_update_".$j."' value='".$physical_descriptions_1."' STYLE='ime-mode:active;' /></td>\n";
                        $detail['physical_description_str'] .= "<td>&nbsp;&nbsp;<input type='checkbox' name='physical_description_delete_update_".$j."' value='".$j."' />\n";
			$detail['physical_description_str'] .= "&nbsp;&nbsp;<select size='1' name='physical_description_order_update_".$j."'>";
                        for ($k = 0; $k < $i; $k++) {
                                $detail['physical_description_str'] .= "<option value='".$k."'";
                                if ($k == $j) {
                                        $detail['physical_description_str'] .= " selected";
                                }
                                $detail['physical_description_str'] .= ">".$k."</option>";
                        }
                        $detail['physical_description_str'] .= "</td></tr>\n";
                        $j++;
                }
                $detail['physical_description_str'] .= "</table>\n";
        }
        return $detail;
}


function xnparticleRegisterLangsString( $detail ) {
        if (( isset($_POST['xnparticleAddLangFlag']) ) && ( $_POST['xnparticleAddLangFlag'] == '1' )) {
                if ( !empty( $detail['langs'] ) ){
                        $detail['langs'] .= "\n";
                }
                $detail['langs'] .= $detail['langs_add'];
                $detail['langs_add'] = '';
        }
        if (( isset($_POST['xnparticleUpdateLangFlag']) ) && ( $_POST['xnparticleUpdateLangFlag'] == '1' )) {
                $langs_update = array();
                $langs_order_update = array();
                $langs_delete_update = array();
                foreach ( $_POST as $key=>$value ){
                        if (substr($key,0,19) == "lang_delete_update_"){
                                array_push($langs_delete_update, $value);
                        }
                }
                $i = 0;
                $j = 0;
                foreach ( $_POST as $key=>$value ){
                        if (substr($key,0,12) == "lang_update_"){
                                if (in_array($i, $langs_delete_update) == FALSE) {
                                        array_push($langs_update, ( $value != "" ) ? $value : " ");
                                }
                                $i++;
                        }
                        if (substr($key,0,18) == "lang_order_update_"){
                                if (in_array($j, $langs_delete_update) == FALSE) {
                                        array_push($langs_order_update, $value);
                                }
                                $j++;
                        }
                }
                $langs_sort = array_map(NULL, $langs_order_update, $langs_update);
                sort($langs_sort);
                $detail['langs'] = "";
                foreach ( $langs_sort as $key=>$value ){
                        if (!empty($value[1])) {
                                if ( !empty( $detail['langs'] ) ){
                                        $detail['langs'] .= "\n";
                                }
                                $detail['langs'] .= $value[1];
                        }
                }
        }
        if ( !empty( $detail['langs'] ) ){
                $langs = explode( "\n", $detail['langs'] );
                $i = count($langs);
                $j = 0;
                $detail['lang_str'] = "<table><tr><td>"._MD_XNPARTICLE_EDIT_LABEL."</td><td class='oddeven'>"._MD_XNPARTICLE_DELETE_LABEL."&nbsp;"._MD_XNPARTICLE_ORDER_LABEL."</td></tr>\n";
                foreach ( $langs as  $value ){
                        $langs_1 = ( $value != " " ) ? $value : "";
                        $detail['lang_str'] .= "<tr>";
                        $detail['lang_str'] .= "<td width='10'><input size=50 type='text' name='lang_update_".$j."' value='".$langs_1."' STYLE='ime-mode:active;' /></td>\n";
                        $detail['lang_str'] .= "<td>&nbsp;&nbsp;<input type='checkbox' name='lang_delete_update_".$j."' value='".$j."' />\n";
			$detail['lang_str'] .= "&nbsp;&nbsp;<select size='1' name='lang_order_update_".$j."'>";
                        for ($k = 0; $k < $i; $k++) {
                                $detail['lang_str'] .= "<option value='".$k."'";
                                if ($k == $j) {
                                        $detail['lang_str'] .= " selected";
                                }
                                $detail['lang_str'] .= ">".$k."</option>";
                        }
                        $detail['lang_str'] .= "</td></tr>\n";
                        $j++;
                }
                $detail['lang_str'] .= "</table>\n";
        }
        return $detail;
}

function xnparticleRegisterIdIssnsString( $detail ) {
        if (( isset($_POST['xnparticleAddIdIssnFlag']) ) && ( $_POST['xnparticleAddIdIssnFlag'] == '1' )) {
                if ( !empty( $detail['id_issns'] ) ){
                        $detail['id_issns'] .= "\n";
                }
                $detail['id_issns'] .= $detail['id_issns_add'];
                $detail['id_issns_add'] = '';
        }
        if (( isset($_POST['xnparticleUpdateIdIssnFlag']) ) && ( $_POST['xnparticleUpdateIdIssnFlag'] == '1' )) {
                $id_issns_update = array();
                $id_issns_order_update = array();
                $id_issns_delete_update = array();
                foreach ( $_POST as $key=>$value ){
                        if (substr($key,0,22) == "id_issn_delete_update_"){
                                array_push($id_issns_delete_update, $value);
                        }
                }
                $i = 0;
                $j = 0;
                foreach ( $_POST as $key=>$value ){
                        if (substr($key,0,15) == "id_issn_update_"){
                                if (in_array($i, $id_issns_delete_update) == FALSE) {
                                        array_push($id_issns_update, ( $value != "" ) ? $value : " ");
                                }
                                $i++;
                        }
                        if (substr($key,0,21) == "id_issn_order_update_"){
                                if (in_array($j, $id_issns_delete_update) == FALSE) {
                                        array_push($id_issns_order_update, $value);
                                }
                                $j++;
                        }
                }
                $id_issns_sort = array_map(NULL, $id_issns_order_update, $id_issns_update);
                sort($id_issns_sort);
                $detail['id_issns'] = "";
                foreach ( $id_issns_sort as $key=>$value ){
                        if (!empty($value[1])) {
                                if ( !empty( $detail['id_issns'] ) ){
                                        $detail['id_issns'] .= "\n";
                                }
                                $detail['id_issns'] .= $value[1];
                        }
                }
        }
        if ( !empty( $detail['id_issns'] ) ){
                $id_issns = explode( "\n", $detail['id_issns'] );
                $i = count($id_issns);
                $j = 0;
                $detail['id_issn_str'] = "<table><tr><td>"._MD_XNPARTICLE_EDIT_LABEL."</td><td class='oddeven'>"._MD_XNPARTICLE_DELETE_LABEL."&nbsp;"._MD_XNPARTICLE_ORDER_LABEL."</td></tr>\n";
                foreach ( $id_issns as  $value ){
                        $id_issns_1 = ( $value != " " ) ? $value : "";
                        $detail['id_issn_str'] .= "<tr>";
                        $detail['id_issn_str'] .= "<td width='10'><input size=50 type='text' name='id_issn_update_".$j."' value='".$id_issns_1."' STYLE='ime-mode:active;' /></td>\n";
                        $detail['id_issn_str'] .= "<td>&nbsp;&nbsp;<input type='checkbox' name='id_issn_delete_update_".$j."' value='".$j."' />\n";
			$detail['id_issn_str'] .= "&nbsp;&nbsp;<select size='1' name='id_issn_order_update_".$j."'>";
                        for ($k = 0; $k < $i; $k++) {
                                $detail['id_issn_str'] .= "<option value='".$k."'";
                                if ($k == $j) {
                                        $detail['id_issn_str'] .= " selected";
                                }
                                $detail['id_issn_str'] .= ">".$k."</option>";
                        }
                        $detail['id_issn_str'] .= "</td></tr>\n";
                        $j++;
                }
                $detail['id_issn_str'] .= "</table>\n";
        }
        return $detail;
}


function xnparticleRegisterIdIsbnsString( $detail ) {
        if (( isset($_POST['xnparticleAddIdIsbnFlag']) ) && ( $_POST['xnparticleAddIdIsbnFlag'] == '1' )) {
                if ( !empty( $detail['id_isbns'] ) ){
                        $detail['id_isbns'] .= "\n";
                }
                $detail['id_isbns'] .= $detail['id_isbns_add'];
                $detail['id_isbns_add'] = '';
        }
        if (( isset($_POST['xnparticleUpdateIdIsbnFlag']) ) && ( $_POST['xnparticleUpdateIdIsbnFlag'] == '1' )) {
                $id_isbns_update = array();
                $id_isbns_order_update = array();
                $id_isbns_delete_update = array();
                foreach ( $_POST as $key=>$value ){
                        if (substr($key,0,22) == "id_isbn_delete_update_"){
                                array_push($id_isbns_delete_update, $value);
                        }
                }
                $i = 0;
                $j = 0;
                foreach ( $_POST as $key=>$value ){
                        if (substr($key,0,15) == "id_isbn_update_"){
                                if (in_array($i, $id_isbns_delete_update) == FALSE) {
                                        array_push($id_isbns_update, ( $value != "" ) ? $value : " ");
                                }
                                $i++;
                        }
                        if (substr($key,0,21) == "id_isbn_order_update_"){
                                if (in_array($j, $id_isbns_delete_update) == FALSE) {
                                        array_push($id_isbns_order_update, $value);
                                }
                                $j++;
                        }
                }
                $id_isbns_sort = array_map(NULL, $id_isbns_order_update, $id_isbns_update);
                sort($id_isbns_sort);
                $detail['id_isbns'] = "";
                foreach ( $id_isbns_sort as $key=>$value ){
                        if (!empty($value[1])) {
                                if ( !empty( $detail['id_isbns'] ) ){
                                        $detail['id_isbns'] .= "\n";
                                }
                                $detail['id_isbns'] .= $value[1];
                        }
                }
        }
        if ( !empty( $detail['id_isbns'] ) ){
                $id_isbns = explode( "\n", $detail['id_isbns'] );
                $i = count($id_isbns);
                $j = 0;
                $detail['id_isbn_str'] = "<table><tr><td>"._MD_XNPARTICLE_EDIT_LABEL."</td><td class='oddeven'>"._MD_XNPARTICLE_DELETE_LABEL."&nbsp;"._MD_XNPARTICLE_ORDER_LABEL."</td></tr>\n";
                foreach ( $id_isbns as  $value ){
                        $id_isbns_1 = ( $value != " " ) ? $value : "";
                        $detail['id_isbn_str'] .= "<tr>";
                        $detail['id_isbn_str'] .= "<td width='10'><input size=50 type='text' name='id_isbn_update_".$j."' value='".$id_isbns_1."' STYLE='ime-mode:active;' /></td>\n";
                        $detail['id_isbn_str'] .= "<td>&nbsp;&nbsp;<input type='checkbox' name='id_isbn_delete_update_".$j."' value='".$j."' />\n";
			$detail['id_isbn_str'] .= "&nbsp;&nbsp;<select size='1' name='id_isbn_order_update_".$j."'>";
                        for ($k = 0; $k < $i; $k++) {
                                $detail['id_isbn_str'] .= "<option value='".$k."'";
                                if ($k == $j) {
                                        $detail['id_isbn_str'] .= " selected";
                                }
                                $detail['id_isbn_str'] .= ">".$k."</option>";
                        }
                        $detail['id_isbn_str'] .= "</td></tr>\n";
                        $j++;
                }
                $detail['id_isbn_str'] .= "</table>\n";
        }
        return $detail;
}

function xnparticleRegisterIdDoisString( $detail ) {
        if (( isset($_POST['xnparticleAddIdDoiFlag']) ) && ( $_POST['xnparticleAddIdDoiFlag'] == '1' )) {
                if ( !empty( $detail['id_dois'] ) ){
                        $detail['id_dois'] .= "\n";
                }
                $detail['id_dois'] .= $detail['id_dois_add'];
                $detail['id_dois_add'] = '';
        }
        if (( isset($_POST['xnparticleUpdateIdDoiFlag']) ) && ( $_POST['xnparticleUpdateIdDoiFlag'] == '1' )) {
                $id_dois_update = array();
                $id_dois_order_update = array();
                $id_dois_delete_update = array();
                foreach ( $_POST as $key=>$value ){
                        if (substr($key,0,21) == "id_doi_delete_update_"){
                                array_push($id_dois_delete_update, $value);
                        }
                }
                $i = 0;
                $j = 0;
                foreach ( $_POST as $key=>$value ){
                        if (substr($key,0,14) == "id_doi_update_"){
                                if (in_array($i, $id_dois_delete_update) == FALSE) {
                                        array_push($id_dois_update, ( $value != "" ) ? $value : " ");
                                }
                                $i++;
                        }
                        if (substr($key,0,20) == "id_doi_order_update_"){
                                if (in_array($j, $id_dois_delete_update) == FALSE) {
                                        array_push($id_dois_order_update, $value);
                                }
                                $j++;
                        }
                }
                $id_dois_sort = array_map(NULL, $id_dois_order_update, $id_dois_update);
                sort($id_dois_sort);
                $detail['id_dois'] = "";
                foreach ( $id_dois_sort as $key=>$value ){
                        if (!empty($value[1])) {
                                if ( !empty( $detail['id_dois'] ) ){
                                        $detail['id_dois'] .= "\n";
                                }
                                $detail['id_dois'] .= $value[1];
                        }
                }
        }
        if ( !empty( $detail['id_dois'] ) ){
                $id_dois = explode( "\n", $detail['id_dois'] );
                $i = count($id_dois);
                $j = 0;
                $detail['id_doi_str'] = "<table><tr><td>"._MD_XNPARTICLE_EDIT_LABEL."</td><td class='oddeven'>"._MD_XNPARTICLE_DELETE_LABEL."&nbsp;"._MD_XNPARTICLE_ORDER_LABEL."</td></tr>\n";
                foreach ( $id_dois as  $value ){
                        $id_dois_1 = ( $value != " " ) ? $value : "";
                        $detail['id_doi_str'] .= "<tr>";
                        $detail['id_doi_str'] .= "<td width='10'><input size=50 type='text' name='id_doi_update_".$j."' value='".$id_dois_1."' STYLE='ime-mode:active;' /></td>\n";
                        $detail['id_doi_str'] .= "<td>&nbsp;&nbsp;<input type='checkbox' name='id_doi_delete_update_".$j."' value='".$j."' />\n";
			$detail['id_doi_str'] .= "&nbsp;&nbsp;<select size='1' name='id_doi_order_update_".$j."'>";
                        for ($k = 0; $k < $i; $k++) {
                                $detail['id_doi_str'] .= "<option value='".$k."'";
                                if ($k == $j) {
                                        $detail['id_doi_str'] .= " selected";
                                }
                                $detail['id_doi_str'] .= ">".$k."</option>";
                        }
                        $detail['id_doi_str'] .= "</td></tr>\n";
                        $j++;
                }
                $detail['id_doi_str'] .= "</table>\n";
        }
        return $detail;
}

function xnparticleRegisterIdUrisString( $detail ) {
        if (( isset($_POST['xnparticleAddIdUriFlag']) ) && ( $_POST['xnparticleAddIdUriFlag'] == '1' )) {
                if ( !empty( $detail['id_uris'] ) ){
                        $detail['id_uris'] .= "\n";
                }
                $detail['id_uris'] .= $detail['id_uris_add'];
                $detail['id_uris_add'] = '';
        }
        if (( isset($_POST['xnparticleUpdateIdUriFlag']) ) && ( $_POST['xnparticleUpdateIdUriFlag'] == '1' )) {
                $id_uris_update = array();
                $id_uris_order_update = array();
                $id_uris_delete_update = array();
                foreach ( $_POST as $key=>$value ){
                        if (substr($key,0,21) == "id_uri_delete_update_"){
                                array_push($id_uris_delete_update, $value);
                        }
                }
                $i = 0;
                $j = 0;
                foreach ( $_POST as $key=>$value ){
                        if (substr($key,0,14) == "id_uri_update_"){
                                if (in_array($i, $id_uris_delete_update) == FALSE) {
                                        array_push($id_uris_update, ( $value != "" ) ? $value : " ");
                                }
                                $i++;
                        }
                        if (substr($key,0,20) == "id_uri_order_update_"){
                                if (in_array($j, $id_uris_delete_update) == FALSE) {
                                        array_push($id_uris_order_update, $value);
                                }
                                $j++;
                        }
                }
                $id_uris_sort = array_map(NULL, $id_uris_order_update, $id_uris_update);
                sort($id_uris_sort);
                $detail['id_uris'] = "";
                foreach ( $id_uris_sort as $key=>$value ){
                        if (!empty($value[1])) {
                                if ( !empty( $detail['id_uris'] ) ){
                                        $detail['id_uris'] .= "\n";
                                }
                                $detail['id_uris'] .= $value[1];
                        }
                }
        }
        if ( !empty( $detail['id_uris'] ) ){
                $id_uris = explode( "\n", $detail['id_uris'] );
                $i = count($id_uris);
                $j = 0;
                $detail['id_uri_str'] = "<table><tr><td>"._MD_XNPARTICLE_EDIT_LABEL."</td><td class='oddeven'>"._MD_XNPARTICLE_DELETE_LABEL."&nbsp;"._MD_XNPARTICLE_ORDER_LABEL."</td></tr>\n";
                foreach ( $id_uris as  $value ){
                        $id_uris_1 = ( $value != " " ) ? $value : "";
                        $detail['id_uri_str'] .= "<tr>";
                        $detail['id_uri_str'] .= "<td width='10'><input size=50 type='text' name='id_uri_update_".$j."' value='".$id_uris_1."' STYLE='ime-mode:active;' /></td>\n";
                        $detail['id_uri_str'] .= "<td>&nbsp;&nbsp;<input type='checkbox' name='id_uri_delete_update_".$j."' value='".$j."' />\n";
			$detail['id_uri_str'] .= "&nbsp;&nbsp;<select size='1' name='id_uri_order_update_".$j."'>";
                        for ($k = 0; $k < $i; $k++) {
                                $detail['id_uri_str'] .= "<option value='".$k."'";
                                if ($k == $j) {
                                        $detail['id_uri_str'] .= " selected";
                                }
                                $detail['id_uri_str'] .= ">".$k."</option>";
                        }
                        $detail['id_uri_str'] .= "</td></tr>\n";
                        $j++;
                }
                $detail['id_uri_str'] .= "</table>\n";
        }
        return $detail;
}

function xnparticleRegisterIdLocalsString( $detail ) {
        if (( isset($_POST['xnparticleAddIdLocalFlag']) ) && ( $_POST['xnparticleAddIdLocalFlag'] == '1' )) {
                if ( !empty( $detail['id_locals'] ) ){
                        $detail['id_locals'] .= "\n";
                }
                $detail['id_locals'] .= $detail['id_locals_add'];
                $detail['id_locals_add'] = '';
        }
        if (( isset($_POST['xnparticleUpdateIdLocalFlag']) ) && ( $_POST['xnparticleUpdateIdLocalFlag'] == '1' )) {
                $id_locals_update = array();
                $id_locals_order_update = array();
                $id_locals_delete_update = array();
                foreach ( $_POST as $key=>$value ){
                        if (substr($key,0,23) == "id_local_delete_update_"){
                                array_push($id_locals_delete_update, $value);
                        }
                }
                $i = 0;
                $j = 0;
                foreach ( $_POST as $key=>$value ){
                        if (substr($key,0,16) == "id_local_update_"){
                                if (in_array($i, $id_locals_delete_update) == FALSE) {
                                        array_push($id_locals_update, ( $value != "" ) ? $value : " ");
                                }
                                $i++;
                        }
                        if (substr($key,0,22) == "id_local_order_update_"){
                                if (in_array($j, $id_locals_delete_update) == FALSE) {
                                        array_push($id_locals_order_update, $value);
                                }
                                $j++;
                        }
                }
                $id_locals_sort = array_map(NULL, $id_locals_order_update, $id_locals_update);
                sort($id_locals_sort);
                $detail['id_locals'] = "";
                foreach ( $id_locals_sort as $key=>$value ){
                        if (!empty($value[1])) {
                                if ( !empty( $detail['id_locals'] ) ){
                                        $detail['id_locals'] .= "\n";
                                }
                                $detail['id_locals'] .= $value[1];
                        }
                }
        }
        if ( !empty( $detail['id_locals'] ) ){
                $id_locals = explode( "\n", $detail['id_locals'] );
                $i = count($id_locals);
                $j = 0;
                $detail['id_local_str'] = "<table><tr><td>"._MD_XNPARTICLE_EDIT_LABEL."</td><td class='oddeven'>"._MD_XNPARTICLE_DELETE_LABEL."&nbsp;"._MD_XNPARTICLE_ORDER_LABEL."</td></tr>\n";
                foreach ( $id_locals as  $value ){
                        $id_locals_1 = ( $value != " " ) ? $value : "";
                        $detail['id_local_str'] .= "<tr>";
                        $detail['id_local_str'] .= "<td width='10'><input size=50 type='text' name='id_local_update_".$j."' value='".$id_locals_1."' STYLE='ime-mode:active;' /></td>\n";
                        $detail['id_local_str'] .= "<td>&nbsp;&nbsp;<input type='checkbox' name='id_local_delete_update_".$j."' value='".$j."' />\n";
			$detail['id_local_str'] .= "&nbsp;&nbsp;<select size='1' name='id_local_order_update_".$j."'>";
                        for ($k = 0; $k < $i; $k++) {
                                $detail['id_local_str'] .= "<option value='".$k."'";
                                if ($k == $j) {
                                        $detail['id_local_str'] .= " selected";
                                }
                                $detail['id_local_str'] .= ">".$k."</option>";
                        }
                        $detail['id_local_str'] .= "</td></tr>\n";
                        $j++;
                }
                $detail['id_local_str'] .= "</table>\n";
        }
        return $detail;
}

function xnparticleRegisterUrisString( $detail ) {
        if (( isset($_POST['xnparticleAddUriFlag']) ) && ( $_POST['xnparticleAddUriFlag'] == '1' )) {
                if ( !empty( $detail['uris'] ) ){
                        $detail['uris'] .= "\n";
                }
                $detail['uris'] .= $detail['uris_add'];
                $detail['uris_add'] = '';
        }
        if (( isset($_POST['xnparticleUpdateUriFlag']) ) && ( $_POST['xnparticleUpdateUriFlag'] == '1' )) {
                $uris_update = array();
                $uris_order_update = array();
                $uris_delete_update = array();
                foreach ( $_POST as $key=>$value ){
                        if (substr($key,0,18) == "uri_delete_update_"){
                                array_push($uris_delete_update, $value);
                        }
                }
                $i = 0;
                $j = 0;
                foreach ( $_POST as $key=>$value ){
                        if (substr($key,0,11) == "uri_update_"){
                                if (in_array($i, $uris_delete_update) == FALSE) {
                                        array_push($uris_update, ( $value != "" ) ? $value : " ");
                                }
                                $i++;
                        }
                        if (substr($key,0,17) == "uri_order_update_"){
                                if (in_array($j, $uris_delete_update) == FALSE) {
                                        array_push($uris_order_update, $value);
                                }
                                $j++;
                        }
                }
                $uris_sort = array_map(NULL, $uris_order_update, $uris_update);
                sort($uris_sort);
                $detail['uris'] = "";
                foreach ( $uris_sort as $key=>$value ){
                        if (!empty($value[1])) {
                                if ( !empty( $detail['uris'] ) ){
                                        $detail['uris'] .= "\n";
                                }
                                $detail['uris'] .= $value[1];
                        }
                }
        }
        if ( !empty( $detail['uris'] ) ){
                $uris = explode( "\n", $detail['uris'] );
                $i = count($uris);
                $j = 0;
                $detail['uri_str'] = "<table><tr><td>"._MD_XNPARTICLE_EDIT_LABEL."</td><td class='oddeven'>"._MD_XNPARTICLE_DELETE_LABEL."&nbsp;"._MD_XNPARTICLE_ORDER_LABEL."</td></tr>\n";
                foreach ( $uris as  $value ){
                        $uris_1 = ( $value != " " ) ? $value : "";
                        $detail['uri_str'] .= "<tr>";
                        $detail['uri_str'] .= "<td width='10'><input size=50 type='text' name='uri_update_".$j."' value='".$uris_1."' STYLE='ime-mode:active;' /></td>\n";
                        $detail['uri_str'] .= "<td>&nbsp;&nbsp;<input type='checkbox' name='uri_delete_update_".$j."' value='".$j."' />\n";
			$detail['uri_str'] .= "&nbsp;&nbsp;<select size='1' name='uri_order_update_".$j."'>";
                        for ($k = 0; $k < $i; $k++) {
                                $detail['uri_str'] .= "<option value='".$k."'";
                                if ($k == $j) {
                                        $detail['uri_str'] .= " selected";
                                }
                                $detail['uri_str'] .= ">".$k."</option>";
                        }
                        $detail['uri_str'] .= "</td></tr>\n";
                        $j++;
                }
                $detail['uri_str'] .= "</table>\n";
        }
        return $detail;
}


/** get DetailInformation by item_id
  * 
  */
function xnparticleGetDetailInformation( $item_id ){
	global $xoopsDB;
	if ( empty( $item_id ) )
		return array(
			'title'           	  =>'',
			'title_kana'              =>'',
			'title_romaji'            =>'',
			'edition'                 =>'',
			'publish_place'           =>'',
			'publisher'               =>'',
			'publisher_kana'          =>'',
			'publisher_romaji'        =>'',
			'year_f'                  =>'',
			'year_t'                  =>'',
			'date_create'             =>'',
			'date_update'             =>'',
			'date_record'             =>'',
			'jtitle'                  =>'',
			'jtitle_translation'      =>'',
			'jtitle_volume'           =>'',
			'jtitle_issue'            =>'',
			'jtitle_year'             =>'',
			'jtitle_month'            =>'',
			'jtitle_spage'            =>'',
			'jtitle_epage'            =>'',
			'abstract'        	  =>'',
			'table_of_contents'    	  =>'',
			'type_of_resource'        =>'',
			'genre'	        	  =>'',
			'access_condition'     	  =>'',
			'self_doi'                =>'',
			'naid'                    =>'',
			'ichushi'                 =>'',
			'textversion'	          =>'',
			'grant_id'                =>'',
			'date_of_granted'         =>'',
			'degree_name'             =>'',
			'grantor'                 =>''
		);
	
	$sql = "select * from " . $xoopsDB->prefix("xnparticle_item_detail") . " where article_id=$item_id";
	$result = $xoopsDB->query( $sql );
	if ( $result == FALSE ){
		echo " $sql " . mysql_error();
		return false;
	}
	$detail = $xoopsDB->fetchArray($result);
	return $detail;
}

function xnparticleGetDetailChildSubTitleInformation( $item_id ){
	global $xoopsDB;
	if ( empty( $item_id ) )
		return array(
			'sub_title_name'                    =>'',
			'sub_title_kana'                    =>'',
			'sub_title_romaji'                  =>'',
		);
	
	$sql = "select article_child_sub_title_id,article_id,sub_title_name,sub_title_kana,sub_title_romaji,sub_title_order from " . $xoopsDB->prefix("xnparticle_item_detail_child_sub_title") . " where article_id=$item_id order by sub_title_order";
	$result = $xoopsDB->query( $sql );
	if ( $result == FALSE ){
		echo " $sql " . mysql_error();
		return false;
	}
	$detail_child_sub_title = array();
	while ( false != ( $row = $xoopsDB->fetchRow($result) ) ) {
		$detail_child_sub_title[] = $row;
	}
	return $detail_child_sub_title;
}

function xnparticleGetDetailChildAuthorInformation( $item_id ){
	global $xoopsDB;
	if ( empty( $item_id ) )
		return array(
			'author_id'                      =>'',
			'author_name'                    =>'',
			'author_kana'                    =>'',
			'author_romaji'                  =>'',
			'author_affiliation'             =>'',
			'author_affiliation_translation' =>'',
			'author_role'                    =>'',
			'author_link'                    =>''
		);
	
	$sql = "select article_child_author_id,article_id,author_id,author_name,author_kana,author_romaji,author_affiliation,author_affiliation_translation,author_role,author_link,author_order from " . $xoopsDB->prefix("xnparticle_item_detail_child_author") . " where article_id=$item_id order by author_order";
	$result = $xoopsDB->query( $sql );
	if ( $result == FALSE ){
		echo " $sql " . mysql_error();
		return false;
	}
	$detail_child_author = array();
	while ( false != ( $row = $xoopsDB->fetchRow($result) ) ) {
		$detail_child_author[] = $row;
	}
	return $detail_child_author;
}

function xnparticleGetDetailChildKeywordsInformation( $item_id ){
	global $xoopsDB;
	if ( empty( $item_id ) )
		return array(
			'keywords'         =>'',
		);
	
	$sql = "select * from " . $xoopsDB->prefix("xnparticle_item_detail_child_keywords") . " where article_id=$item_id order by keywords_order";
	$result = $xoopsDB->query( $sql );
	if ( $result == FALSE ){
		echo " $sql " . mysql_error();
		return false;
	}
	$detail_child_keywords = array();
	while ( false != ( $row = $xoopsDB->fetchRow($result) ) ) {
		$detail_child_keywords[] = $row;
	}
	return $detail_child_keywords;
}

function xnparticleGetDetailChildNdcClassificationsInformation( $item_id ){
	global $xoopsDB;
	if ( empty( $item_id ) )
		return array(
			'ndc_classifications'         =>'',
		);
	
	$sql = "select * from " . $xoopsDB->prefix("xnparticle_item_detail_child_ndc_classifications") . " where article_id=$item_id order by ndc_classifications_order";
	$result = $xoopsDB->query( $sql );
	if ( $result == FALSE ){
		echo " $sql " . mysql_error();
		return false;
	}
	$detail_child_ndc_classifications = array();
	while ( false != ( $row = $xoopsDB->fetchRow($result) ) ) {
		$detail_child_ndc_classifications[] = $row;
	}
	return $detail_child_ndc_classifications;
}

function xnparticleGetDetailChildPhysicalDescriptionsInformation( $item_id ){
	global $xoopsDB;
	if ( empty( $item_id ) )
		return array(
			'physical_descriptions'         =>'',
		);
	
	$sql = "select * from " . $xoopsDB->prefix("xnparticle_item_detail_child_physical_descriptions") . " where article_id=$item_id order by physical_descriptions_order";
	$result = $xoopsDB->query( $sql );
	if ( $result == FALSE ){
		echo " $sql " . mysql_error();
		return false;
	}
	$detail_child_physical_descriptions = array();
	while ( false != ( $row = $xoopsDB->fetchRow($result) ) ) {
		$detail_child_physical_descriptions[] = $row;
	}
	return $detail_child_physical_descriptions;
}

function xnparticleGetDetailChildLangsInformation( $item_id ){
	global $xoopsDB;
	if ( empty( $item_id ) )
		return array(
			'langs'         =>'',
		);
	
	$sql = "select * from " . $xoopsDB->prefix("xnparticle_item_detail_child_langs") . " where article_id=$item_id order by langs_order";
	$result = $xoopsDB->query( $sql );
	if ( $result == FALSE ){
		echo " $sql " . mysql_error();
		return false;
	}
	$detail_child_langs = array();
	while ( false != ( $row = $xoopsDB->fetchRow($result) ) ) {
		$detail_child_langs[] = $row;
	}
	return $detail_child_langs;
}

function xnparticleGetDetailChildIdIssnsInformation( $item_id ){
	global $xoopsDB;
	if ( empty( $item_id ) )
		return array(
			'id_issns'         =>'',
		);
	
	$sql = "select * from " . $xoopsDB->prefix("xnparticle_item_detail_child_id_issns") . " where article_id=$item_id order by id_issns_order";
	$result = $xoopsDB->query( $sql );
	if ( $result == FALSE ){
		echo " $sql " . mysql_error();
		return false;
	}
	$detail_child_id_issns = array();
	while ( false != ( $row = $xoopsDB->fetchRow($result) ) ) {
		$detail_child_id_issns[] = $row;
	}
	return $detail_child_id_issns;
}

function xnparticleGetDetailChildIdIsbnsInformation( $item_id ){
	global $xoopsDB;
	if ( empty( $item_id ) )
		return array(
			'id_isbns'         =>'',
		);
	
	$sql = "select * from " . $xoopsDB->prefix("xnparticle_item_detail_child_id_isbns") . " where article_id=$item_id order by id_isbns_order";
	$result = $xoopsDB->query( $sql );
	if ( $result == FALSE ){
		echo " $sql " . mysql_error();
		return false;
	}
	$detail_child_id_isbns = array();
	while ( false != ( $row = $xoopsDB->fetchRow($result) ) ) {
		$detail_child_id_isbns[] = $row;
	}
	return $detail_child_id_isbns;
}

function xnparticleGetDetailChildIdDoisInformation( $item_id ){
	global $xoopsDB;
	if ( empty( $item_id ) )
		return array(
			'id_dois'         =>'',
		);
	
	$sql = "select * from " . $xoopsDB->prefix("xnparticle_item_detail_child_id_dois") . " where article_id=$item_id order by id_dois_order";
	$result = $xoopsDB->query( $sql );
	if ( $result == FALSE ){
		echo " $sql " . mysql_error();
		return false;
	}
	$detail_child_id_dois = array();
	while ( false != ( $row = $xoopsDB->fetchRow($result) ) ) {
		$detail_child_id_dois[] = $row;
	}
	return $detail_child_id_dois;
}

function xnparticleGetDetailChildIdUrisInformation( $item_id ){
	global $xoopsDB;
	if ( empty( $item_id ) )
		return array(
			'id_uris'         =>'',
		);
	
	$sql = "select * from " . $xoopsDB->prefix("xnparticle_item_detail_child_id_uris") . " where article_id=$item_id order by id_uris_order";
	$result = $xoopsDB->query( $sql );
	if ( $result == FALSE ){
		echo " $sql " . mysql_error();
		return false;
	}
	$detail_child_id_uris = array();
	while ( false != ( $row = $xoopsDB->fetchRow($result) ) ) {
		$detail_child_id_uris[] = $row;
	}
	return $detail_child_id_uris;
}

function xnparticleGetDetailChildIdLocalsInformation( $item_id ){
	global $xoopsDB;
	if ( empty( $item_id ) )
		return array(
			'id_locals'         =>'',
		);
	
	$sql = "select * from " . $xoopsDB->prefix("xnparticle_item_detail_child_id_locals") . " where article_id=$item_id order by id_locals_order";
	$result = $xoopsDB->query( $sql );
	if ( $result == FALSE ){
		echo " $sql " . mysql_error();
		return false;
	}
	$detail_child_id_locals = array();
	while ( false != ( $row = $xoopsDB->fetchRow($result) ) ) {
		$detail_child_id_locals[] = $row;
	}
	return $detail_child_id_locals;
}

function xnparticleGetDetailChildUrisInformation( $item_id ){
	global $xoopsDB;
	if ( empty( $item_id ) )
		return array(
			'uris'         =>'',
		);
	
	$sql = "select * from " . $xoopsDB->prefix("xnparticle_item_detail_child_uris") . " where article_id=$item_id order by uris_order";
	$result = $xoopsDB->query( $sql );
	if ( $result == FALSE ){
		echo " $sql " . mysql_error();
		return false;
	}
	$detail_child_uris = array();
	while ( false != ( $row = $xoopsDB->fetchRow($result) ) ) {
		$detail_child_uris[] = $row;
	}
	return $detail_child_uris;
}

function xnparticleGetMetaInformation( $item_id ){
	
	$ret = array();
	$basic = xnpGetBasicInformationArray($item_id);
	$detail = xnparticleGetDetailInformation( $item_id );
	if ( !empty( $basic ) ){
		$ret[_MD_XOONIPS_ITEM_TITLE_LABEL] = implode( "\n", $basic['titles'] );
		$ret[_MD_XOONIPS_ITEM_CONTRIBUTOR_LABEL] = $basic['contributor'];
		$ret[_MD_XOONIPS_ITEM_KEYWORDS_LABEL] = implode( "\n", $basic['keywords'] );
		$ret[_MD_XOONIPS_ITEM_DESCRIPTION_LABEL] = $basic['description'];
		$ret[_MD_XOONIPS_ITEM_DOI_LABEL] = $basic['doi'];
		$ret[_MD_XOONIPS_ITEM_CREATION_DATE_LABEL] = $basic['creation_date'];
		$ret[_MD_XOONIPS_ITEM_LAST_UPDATE_DATE_LABEL] = $basic['last_update_date'];
	}
	if ( !empty( $detail ) ){
		$ret[_MD_XNPARTICLE_TITLE_LABEL." "._MD_XOONIPS_ITEM_TITLE_LABEL] = $detail['title'];
		$ret[_MD_XNPARTICLE_TITLE_LABEL." "._MD_XNPARTICLE_KANA_LABEL] = $detail['title_kana'];
		$ret[_MD_XNPARTICLE_TITLE_LABEL." "._MD_XNPARTICLE_ROMAJI_LABEL] = $detail['title_romaji'];
	}
	$detail_child_sub_title = xnparticleGetDetailChildSubTitleInformation( $item_id );
	$i = count($detail_child_sub_title);
	if ($i > 0) {
		while ( list( $key, list( $article_child_sub_title_id, $article_id, $sub_title_name, $sub_title_kana, $sub_title_romaji, $sub_title_order ) ) = each( $detail_child_sub_title ) ){
			if (!empty($sub_title_name)) {
				$sub_title_str = $sub_title_name;
			}
			if (!empty($sub_title_kana)) {
				$sub_title_str = $sub_title_kana;
			}
			if (!empty($sub_title_romaji)) {
				$sub_title_str .= " (".$sub_title_romaji.")";
			}
			$ret[_MD_XNPARTICLE_SUB_TITLE_LABEL] = $sub_title_str;
		}
   	}
	$detail_child_author = xnparticleGetDetailChildAuthorInformation( $item_id );
	$i = count($detail_child_author);
	if ($i > 0) {
		while ( list( $key, list( $article_child_author_id, $article_id, $author_id, $author_name, $author_kana, $author_romaji, $author_affiliation, $author_affiliation_translation, $author_role, $author_link, $author_order ) ) = each( $detail_child_author ) ){
			if (!empty($author_id)) {
				$author_str = $author_id;
			}
			if (!empty($author_name)) {
				$author_str = $author_name;
			}
			if (!empty($author_kana)) {
				$author_str = $author_kana;
			}
			if (!empty($author_romaji)) {
				$author_str .= " (".$author_romaji.")";
			}
			if (!empty($author_affiliation)) {
				$author_str .= " -".$author_affiliation;
			}
			if (!empty($author_affiliation_translation)) {
				$author_str .= " (".$author_affiliation_translation.")";
			}
			if (!empty($author_role)) {
				$author_str = $author_role;
			}
			if (!empty($author_link)) {
				$author_str = $author_link;
			}
			$ret[_MD_XNPARTICLE_AUTHOR_LABEL] = $author_str;
		}
   	}
	if ( !empty( $detail ) ){
		$ret[_MD_XNPARTICLE_EDITION_LABEL] = $detail['edition'];
		$ret[_MD_XNPARTICLE_PUBLISH_PLACE_LABEL] = $detail['publish_place'];
        }
	if ( !empty( $detail ) ){
		$ret[_MD_XNPARTICLE_PUBLISHER_LABEL] = $detail['publisher'];
		$ret[_MD_XNPARTICLE_PUBLISHER_LABEL." "._MD_XNPARTICLE_KANA_LABEL] = $detail['publisher_kana'];
		$ret[_MD_XNPARTICLE_PUBLISHER_LABEL." "._MD_XNPARTICLE_ROMAJI_LABEL] = $detail['publisher_romaji'];
	}
	if ( !empty( $detail ) ){
		$ret[_MD_XNPARTICLE_PUBLISH_YEAR_LABEL] = $detail['year_f'].$detail['year_t'];
		$ret[_MD_XNPARTICLE_DATE_CREATE_LABEL] = $detail['date_create'];
		$ret[_MD_XNPARTICLE_DATE_UPDATE_LABEL] = $detail['date_update'];
		$ret[_MD_XNPARTICLE_DATE_RECORD_LABEL] = $detail['date_record'];
	}
	if ( !empty( $detail ) ){
		$ret[_MD_XNPARTICLE_JTITLE_LABEL]." ".$ret[_MD_XNPARTICLE_AUTHOR_NAME_LABEL] = $detail['jtitle'];
		$ret[_MD_XNPARTICLE_JTITLE_LABEL]." ".$ret[_MD_XNPARTICLE_TRANSLATION_LABEL] = $detail['jtitle_translation'];
		$ret[_MD_XNPARTICLE_JTITLE_LABEL]." ".$ret[_MD_XNPARTICLE_JTITLE_VOLUME_LABEL] = $detail['jtitle_volume'];
		$ret[_MD_XNPARTICLE_JTITLE_LABEL]." ".$ret[_MD_XNPARTICLE_JTITLE_ISSUE_LABEL] = $detail['jtitle_issue'];
		$ret[_MD_XNPARTICLE_JTITLE_LABEL]." ".$ret[_MD_XNPARTICLE_JTITLE_YEAR_LABEL] = $detail['jtitle_year'];
		$ret[_MD_XNPARTICLE_JTITLE_LABEL]." ".$ret[_MD_XNPARTICLE_JTITLE_MONTH_LABEL] = $detail['jtitle_month'];
		$ret[_MD_XNPARTICLE_JTITLE_LABEL]." ".$ret[_MD_XNPARTICLE_JTITLE_SPAGE_LABEL] = $detail['jtitle_spage'];
		$ret[_MD_XNPARTICLE_JTITLE_LABEL]." ".$ret[_MD_XNPARTICLE_JTITLE_EPAGE_LABEL] = $detail['jtitle_epage'];
	}
	if ( !empty( $detail ) ){
		$ret[_MD_XNPARTICLE_ABSTRACT_LABEL] = $detail['abstract'];
		$ret[_MD_XNPARTICLE_TABLE_OF_CONTENTS_LABEL] = $detail['table_of_contents'];
		$ret[_MD_XNPARTICLE_ACCESS_CONDITION_LABEL] = $detail['access_condition'];
	}
	$detail_child_keywords = xnparticleGetDetailChildKeywordsInformation( $item_id );
	$i = count($detail_child_keywords);
	if ($i > 0) {
		while ( list( $key, list( $article_child_keywords_id, $article_id, $keywords, $keywords_order ) ) = each( $detail_child_keywords ) ){
			$ret[_MD_XNPARTICLE_KEYWORDS_LABEL] = $keywords;
		}
        }
	$detail_child_ndc_classifications = xnparticleGetDetailChildNdcClassificationsInformation( $item_id );
	$i = count($detail_child_ndc_classifications);
	if ($i > 0) {
		while ( list( $key, list( $article_child_ndc_classifications_id, $article_id, $ndc_classifications, $ndc_classifications_order ) ) = each( $detail_child_ndc_classifications ) ){
			$ret[_MD_XNPARTICLE_NDC_CLASSIFICATIONS_LABEL] = $ndc_classifications;
		}
        }
	$detail_child_physical_descriptions = xnparticleGetDetailChildPhysicalDescriptionsInformation( $item_id );
	$i = count($detail_child_physical_descriptions);
	if ($i > 0) {
		while ( list( $key, list( $article_child_physical_descriptions_id, $article_id, $physical_descriptions, $physical_descriptions_order ) ) = each( $detail_child_physical_descriptions ) ){
			$ret[_MD_XNPARTICLE_PHYSICAL_DESCRIPTIONS_LABEL] = $physical_descriptions;
		}
        }
	$detail_child_langs = xnparticleGetDetailChildLangsInformation( $item_id );
	$i = count($detail_child_langs);
	if ($i > 0) {
		while ( list( $key, list( $article_child_langs_id, $article_id, $langs, $langs_order ) ) = each( $detail_child_langs ) ){
			$ret[_MD_XNPARTICLE_LANGS_LABEL] = $langs;
		}
        }
	$detail_child_id_issns = xnparticleGetDetailChildIdIssnsInformation( $item_id );
	$i = count($detail_child_id_issns);
	if ($i > 0) {
		while ( list( $key, list( $article_child_id_issns_id, $article_id, $id_issns, $id_issns_order ) ) = each( $detail_child_id_issns ) ){
			$ret[_MD_XNPARTICLE_ISSN_LABEL] = $id_issns;
		}
        }
	$detail_child_id_isbns = xnparticleGetDetailChildIdIsbnsInformation( $item_id );
	$i = count($detail_child_id_isbns);
	if ($i > 0) {
		while ( list( $key, list( $article_child_id_isbns_id, $article_id, $id_isbns, $id_isbns_order ) ) = each( $detail_child_id_isbns ) ){
			$ret[_MD_XNPARTICLE_ISBN_LABEL] = $id_isbns;
		}
        }
	$detail_child_id_dois = xnparticleGetDetailChildIdDoisInformation( $item_id );
	$i = count($detail_child_id_dois);
	if ($i > 0) {
		while ( list( $key, list( $article_child_id_dois_id, $article_id, $id_dois, $id_dois_order ) ) = each( $detail_child_id_dois ) ){
			$ret[_MD_XNPARTICLE_DOI_LABEL] = $id_dois;
		}
        }
	$detail_child_id_uris = xnparticleGetDetailChildIdUrisInformation( $item_id );
	$i = count($detail_child_id_uris);
	if ($i > 0) {
		while ( list( $key, list( $article_child_id_uris_id, $article_id, $id_uris, $id_uris_order ) ) = each( $detail_child_id_uris ) ){
			$ret[_MD_XNPARTICLE_ID_URI_LABEL] = $id_uris;
		}
        }
	$detail_child_id_locals = xnparticleGetDetailChildIdLocalsInformation( $item_id );
	$i = count($detail_child_id_locals);
	if ($i > 0) {
		while ( list( $key, list( $article_child_id_locals_id, $article_id, $id_locals, $id_locals_order ) ) = each( $detail_child_id_locals ) ){
			$ret[_MD_XNPARTICLE_OTHER_LABEL] = $id_locals;
		}
        }
	$detail_child_uris = xnparticleGetDetailChildUrisInformation( $item_id );
	$i = count($detail_child_uris);
	if ($i > 0) {
		while ( list( $key, list( $article_child_uris_id, $article_id, $uris, $uris_order ) ) = each( $detail_child_uris ) ){
			$ret[_MD_XNPARTICLE_URI_LABEL] = $uris;
		}
        }
	if ( !empty( $detail ) ){
		$ret[_MD_XNPARTICLE_TYPE_OF_RESOURCE_LABEL] = $detail['type_of_resource'];
		$ret[_MD_XNPARTICLE_GENRE_LABEL] = $detail['genre'];
	}
        if ( !empty( $detail ) ){
                $ret[_MD_XNPARTICLE_SELF_DOI_LABEL] = $detail['self_doi'];
                $ret[_MD_XNPARTICLE_NAID_LABEL] = $detail['naid'];
                $ret[_MD_XNPARTICLE_ICHUSHI_LABEL] = $detail['ichushi'];
                $ret[_MD_XNPARTICLE_TEXTVERSION_LABEL] = $detail['textversion'];
                $ret[_MD_XNPARTICLE_GRANT_ID_LABEL] = $detail['grant_id'];
                $ret[_MD_XNPARTICLE_DATE_OF_GRANTED_LABEL] = $detail['date_of_granted'];
                $ret[_MD_XNPARTICLE_DEGREE_NAME_LABEL] = $detail['degree_name'];
                $ret[_MD_XNPARTICLE_GRANTOR_LABEL] = $detail['grantor'];
	}
	
	return $ret;
}

function xnparticleGetListBlock( $item_basic ){
	$item_id = $item_basic['item_id'];
	
	// get DetailInformation
	$item_detail = xnparticleGetDetailInformation( $item_id );
	if (!empty($item_detail['title'])) {
		$item_detail['title_str'] = $item_detail['title'];
	}
	$item_detail_child_author = xnparticleGetDetailChildAuthorInformation( $item_id );
	$item_detail['author_str'] = '';
	while ( list( $key, list( $article_child_author_id, $article_id, $author_id, $author_name, $author_kana, $author_romaji, $author_affiliation, $author_affiliation_translation, $author_role, $author_link, $author_order ) ) = each( $item_detail_child_author ) ){
		if (($item_detail['author_str'] != '' ) && (!empty($author_name))) {
			$item_detail['author_str'] .= ' . ';
		}
		if (!empty($author_name)) {
			$item_detail['author_str'] .= $author_name;
		}
	}
        //igarashi mod 2008.05.15
	//$article_images = xnparticleGetPreviewDetailBlock2( $item_id );
	$article_images = xnparticleGetPreviewDetailBlock3( $item_id );
	
        //igarashi add 2008.08.06
        $article_attachment = xnpGetAttachmentDetailBlock( $item_id, 'article_attachment' );

	// Set into the template
	global $xoopsTpl;
	$tpl = new xoopsTpl();
	$tpl->assign( $xoopsTpl->get_template_vars() ); // copy variables in $xoopsTpl to $tpl
	
	$tpl->assign( 'item_basic', $item_basic );
	$tpl->assign( 'item_detail', $item_detail );
	$tpl->assign( 'article_images', $article_images );
        //igarashi add 2008.08.06
        $tpl->assign( 'article_attachment', $article_attachment );
        $tpl->assign( 'doi', urlencode( $item_basic[ 'doi' ] ) );
	if( xnpIsPending( $item_basic['item_id'] ) ) $tpl->assign( 'pending', 'true' );
	
	// Return as HTML
	return $tpl->fetch( "db:xnparticle_list_block.html" );
}

// Generate PreviewBlock shown in the listing page.
// Difference from the original xnpGetPreviewDetailBlock is that
// this function only obtain the first image thumbnail without caption.
// igarashi mod no use 2008.05.15
function xnparticleGetPreviewDetailBlock2( $item_id ){
	
	// Obrain thumbnail and caption from the item_id
	$files = xnpGetFileInfo( "t_file.file_id, t_file.caption", "t_file_type.name='preview' and sess_id is NULL ", $item_id );
	// Generate html
	reset( $files );
	$imageHtml1 = array();
	$imageHtml2 = array();
	$fileIDs = array();
  if( count( $files ) > 0 ){
	  $file = $files[0];
    if( $file != NULL ){
		$fileID = $file[0];
		$thumbnailFileName = XOOPS_URL . "/modules/xoonips/image.php?file_id=$fileID&thumbnail=1";
		$imageFileName = XOOPS_URL . "/modules/xoonips/image.php?file_id=$fileID";
		$html = "<a href='$imageFileName' target='_blank'><img src='$thumbnailFileName' width='50' ></a>";
		return array( 'name'=>_MD_XOONIPS_ITEM_PREVIEW_LABEL, 'value'=> $html);
	}
    }
}

//igarashi add 2008.05.15
function xnparticleGetPreviewDetailBlock3( $item_id ){

        // Obrain thumbnail and caption from the item_id
        $files = xnpGetFileInfo( "t_file.file_id, t_file.caption", "t_file_type.name='preview' and sess_id is NULL ", $item_id );
        // Generate html
        reset( $files );
        $imageHtml1 = array();
        $imageHtml2 = array();
        $fileIDs = array();
        if( count( $files ) > 0 ){
                $file = $files[0];
                if( $file != NULL ){
                        $fileID = $file[0];
                        $thumbnailFileName = XOOPS_URL . "/modules/xoonips/image.php?file_id=$fileID&thumbnail=1";
                        $imageFileName = XOOPS_URL . "/modules/xoonips/image.php?file_id=$fileID";
                        $html = "<img src='$thumbnailFileName' width='50' >";
                        return array( 'name'=>_MD_XOONIPS_ITEM_PREVIEW_LABEL, 'value'=> $html);
                }
        }
}

function xnparticleGetPrinterFriendlyListBlock( $item_basic ){
	return xnparticleGetListBlock( $item_basic );
}


function xnparticleGetDetailBlock( $item_id ){
	
	// Get DetailInformation
	$detail = xnparticleGetDetailInformation( $item_id );
	$detail = xnparticleDetailSubTitleString( $detail, $item_id );
	$detail = xnparticleDetailAuthorString( $detail, $item_id );
	$detail = xnparticleDetailKeywordsString( $detail, $item_id );
	$detail = xnparticleDetailNdcClassificationsString( $detail, $item_id );
	$detail = xnparticleDetailPhysicalDescriptionsString( $detail, $item_id );
	$detail = xnparticleDetailLangsString( $detail, $item_id );
	$detail = xnparticleDetailIdIssnsString( $detail, $item_id );
	$detail = xnparticleDetailIdIsbnsString( $detail, $item_id );
	$detail = xnparticleDetailIdDoisString( $detail, $item_id );
	$detail = xnparticleDetailIdUrisString( $detail, $item_id );
	$detail = xnparticleDetailIdLocalsString( $detail, $item_id );
	$detail = xnparticleDetailUrisString( $detail, $item_id );
	
	// Get blocks of "BasicInformation / Preview / IndexKeywords"
	$basic   = xnpGetBasicInformationDetailBlock( $item_id );
	$index   = xnpGetIndexDetailBlock( $item_id );
	$article_attachment = xnpGetAttachmentDetailBlock( $item_id, 'article_attachment' );
	$article_images = xnpGetPreviewDetailBlock( $item_id );
	
	// Set into the template
	global $xoopsTpl;
	$tpl = new xoopsTpl();
	$tpl->assign( $xoopsTpl->get_template_vars() ); // xoopsTplにセットされた変数($xoops_urlとか)を、$tplにコピーする。
	
	$tpl->assign( 'editable', xnp_get_item_permission( $_SESSION['XNPSID'], $item_id, OP_MODIFY ) );
	$tpl->assign( 'basic', $basic );
	$tpl->assign( 'article_attachment', $article_attachment );
	$tpl->assign( 'article_images', $article_images );
	$tpl->assign( 'index', $index );
	$tpl->assign( 'detail', $detail );
	
	//リピータブル項目のhiddenを作成
	$hidden_str_for_repeatable = xnparticleGetDetailChildHiddenStr( $item_id );
	$tpl->assign( 'hidden_str_for_repeatable', $hidden_str_for_repeatable );

	// タイトルを格納しておく。テーマによっては表示可能。
        $xoopsTpl -> assign( 'xoops_contentstitle',$detail['title']);

	// pdf, abstractの表示設定を得る
	$mhandler =& xoops_gethandler('module');
	$module = $mhandler->getByDirname( 'xnparticle' );
	$chandler = & xoops_gethandler('config');
	$assoc = $chandler->getConfigsByCat(false, $module->mid());
	$pdf_access_rights = $assoc['pdf_access_rights'];
	$abstract_access_rights = $assoc['abstract_access_rights'];
	
	// userがどの権限でitem_idにアクセスしているかを調べ， *_access_rightsと比べる
	$access_rights = xnpGetAccessRights( $item_id );
	$tpl->assign( 'show_pdf',      ( $pdf_access_rights      <= $access_rights ) );
	$tpl->assign( 'show_abstract', ( $abstract_access_rights <= $access_rights ) );
	
	// Return as HTML
	return $tpl->fetch( "db:xnparticle_detail_block.html" );
}

// todo
function xnparticleGetPrinterFriendlyDetailBlock( $item_id )
{
	// Get DetailInformation
	$detail = xnparticleGetDetailInformation( $item_id );
	$detail = xnparticleDetailSubTitleString( $detail, $item_id );
	$detail = xnparticleDetailAuthorString( $detail, $item_id );
	$detail = xnparticleDetailKeywordsString( $detail, $item_id );
	$detail = xnparticleDetailNdcClassificationsString( $detail, $item_id );
	$detail = xnparticleDetailPhysicalDescriptionsString( $detail, $item_id );
	$detail = xnparticleDetailLangsString( $detail, $item_id );
	$detail = xnparticleDetailIdIssnsString( $detail, $item_id );
	$detail = xnparticleDetailIdIsbnsString( $detail, $item_id );
	$detail = xnparticleDetailIdDoisString( $detail, $item_id );
	$detail = xnparticleDetailIdUrisString( $detail, $item_id );
	$detail = xnparticleDetailIdLocalsString( $detail, $item_id );
	$detail = xnparticleDetailUrisString( $detail, $item_id );
	
	// Get blocks of "BasicInformation / Preview / IndexKeywords"
	$basic   = xnpGetBasicInformationPrinterFriendlyBlock( $item_id );
	$index   = xnpGetIndexPrinterFriendlyBlock( $item_id );
	$article_attachment = xnpGetAttachmentPrinterFriendlyBlock( $item_id, 'article_attachment' );
	$article_images = xnpGetPreviewPrinterFriendlyBlock( $item_id );
	
	// Set into the template
	global $xoopsTpl;
	$tpl = new xoopsTpl();
	$tpl->assign( $xoopsTpl->get_template_vars() ); // xoopsTplにセットされた変数($xoops_urlとか)を、$tplにコピーする。
	$tpl->assign( 'basic', $basic );
	$tpl->assign( 'index', $index );
	$tpl->assign( 'article_attachment', $article_attachment );
	$tpl->assign( 'article_images', $article_images );
	$tpl->assign( 'detail', $detail );
	
	// pdf, abstractの表示設定を得る
	$mhandler =& xoops_gethandler('module');
	$module = $mhandler->getByDirname( 'xnparticle' );
	$chandler = & xoops_gethandler('config');
	$assoc = $chandler->getConfigsByCat(false, $module->mid());
	$pdf_access_rights = $assoc['pdf_access_rights'];
	$abstract_access_rights = $assoc['abstract_access_rights'];
	
	// userがどの権限でitem_idにアクセスしているかを調べ， *_access_rightsと比べる
	$access_rights = xnpGetAccessRights( $item_id );
	$tpl->assign( 'show_pdf',      ( $pdf_access_rights      <= $access_rights ) );
	$tpl->assign( 'show_abstract', ( $abstract_access_rights <= $access_rights ) );
	
	// Return as HTML
	return $tpl->fetch( "db:xnparticle_detail_block.html" );
}

function xnparticleGetRegisterBlock(){
  global $xoopsDB;

  $formdata =& xoonips_getutility( 'formdata' );
  // Get DetailInformation
  if ( isset( $_POST['title'] ) ){
    $detail = _xnparticle_get_form_request( true, true );
  } else {
    $detail = array();
  }
	
	$detail = xnparticleRegisterTitleRomajiString( $detail );
//igarashi add 20080616
	$detail = xnparticleRegisterSubTitleRomajiString( $detail );
	$detail = xnparticleRegisterAuthorRomajiString( $detail );
	$detail = xnparticleRegisterPublisherRomajiString( $detail );
//igarashi add 20080616
	$detail = xnparticleRegisterSubTitleString( $detail );
	$detail = xnparticleRegisterAuthorString( $detail );
	$detail = xnparticleRegisterKeywordsString( $detail );
	$detail = xnparticleRegisterNdcClassificationsString( $detail );
	$detail = xnparticleRegisterPhysicalDescriptionsString( $detail );
	$detail = xnparticleRegisterLangsString( $detail );
	$detail = xnparticleRegisterIdIssnsString( $detail );
	$detail = xnparticleRegisterIdIsbnsString( $detail );
	$detail = xnparticleRegisterIdDoisString( $detail );
	$detail = xnparticleRegisterIdUrisString( $detail );
	$detail = xnparticleRegisterIdLocalsString( $detail );
	$detail = xnparticleRegisterUrisString( $detail );
	
	// Get blocks of "BasicInformation / Preview / index block"
	$basic = xnpGetBasicInformationRegisterBlock();
	$index = xnpGetIndexRegisterBlock();
	$article_attachment = xnpGetAttachmentRegisterBlock( 'article_attachment' );
	$article_images = xnpGetPreviewRegisterBlock( );
	
	// Set into the template
	global $xoopsTpl;
	$tpl = new xoopsTpl();
	$tpl->assign( $xoopsTpl->get_template_vars() ); // xoopsTplにセットされた変数($xoops_urlとか)を、$tplにコピーする。
	
	$tpl->assign( 'basic', $basic );
	$tpl->assign( 'index', $index );
	$tpl->assign( 'article_images', $article_images );
	$tpl->assign( 'article_attachment', $article_attachment );
	$tpl->assign( 'detail', $detail );
	
	// Return as HTML
	return $tpl->fetch( "db:xnparticle_register_block.html" );
}

function xnparticleGetEditBlock( $item_id ){

  // Get "BasicInformation / Preview / index block"
  $basic   = xnpGetBasicInformationEditBlock( $item_id );
  $index = xnpGetIndexEditBlock( $item_id );
  $article_attachment = xnpGetAttachmentEditBlock( $item_id, 'article_attachment' );
  $article_images = xnpGetPreviewEditBlock( $item_id );

  $textutil =& xoonips_getutility( 'text' );
  $formdata =& xoonips_getutility( 'formdata' );
  // Get DetailInformation
  if ( isset( $_POST['title'] ) ){
    $detail = _xnparticle_get_form_request( true, true );
  } else if ( !empty( $item_id ) ){
		$detail = xnparticleGetDetailInformation( $item_id );
		$detail_child_sub_title = xnparticleGetDetailChildSubTitleInformation( $item_id );
		$i = count($detail_child_sub_title);
		if ($i > 0) {
			$j = 0;
			$detail['sub_title_name'] = "";
			$detail['sub_title_kana'] = "";
			$detail['sub_title_romaji'] = "";
			$detail['sub_title_str'] = "<table><tr><td>"._MD_XNPARTICLE_EDIT_LABEL."</td><td>"._MD_XNPARTICLE_DELETE_LABEL."&nbsp;"._MD_XNPARTICLE_ORDER_LABEL."</td></tr>\n";
			while ( list( $key, list( $article_child_sub_title_id, $article_id, $sub_title_name, $sub_title_kana, $sub_title_romaji, $sub_title_order ) ) = each( $detail_child_sub_title ) ){
				if ( (!empty($detail['sub_title_name'])) || (!empty($detail['sub_title_kana'])) || (!empty($detail['sub_title_romaji'] )) ){
					$detail['sub_title_name'] .= "\n";
					$detail['sub_title_kana'] .= "\n";
					$detail['sub_title_romaji'] .= "\n";
				}
				$detail['sub_title_name'] .= ( !empty( $sub_title_name ) ) ? $sub_title_name : " ";
				$detail['sub_title_kana'] .= ( !empty( $sub_title_kana ) ) ? $sub_title_kana : " ";
				$detail['sub_title_romaji'] .= ( !empty( $sub_title_romaji ) ) ? $sub_title_romaji : " ";
				$detail['sub_title_str'] .= "<tr><td width='320'><input size=40 type='text' name='sub_title_name_update_".$j."' value='".$textutil->html_special_chars($sub_title_name)."' /></td>\n";
				$detail['sub_title_str'] .= "<td>&nbsp;&nbsp;<input type='checkbox' name='sub_title_delete_update_".$j."' value='".$j."' />\n";
				$detail['sub_title_str'] .= "&nbsp;&nbsp;<select size='1' name='sub_title_order_update_".$j."'>";
				for ($k = 0; $k < $i; $k++) {
					$detail['sub_title_str'] .= "<option value='".$k."'";
					if ($k == $j) {
						$detail['sub_title_str'] .= " selected";
					}
					$detail['sub_title_str'] .= ">".$k."</option>";
				}
				$detail['sub_title_str'] .= "</td></tr>\n<tr><td width='320'><input size=40 type='text' name='sub_title_kana_update_".$j."' value='".$textutil->html_special_chars($sub_title_kana)."' /></td>\n";
				$detail['sub_title_str'] .= "</td></tr>\n<tr><td width='320'><input size=40 type='text' name='sub_title_romaji_update_".$j."' value='".$textutil->html_special_chars($sub_title_romaji)."' /></td>\n";
				$j++;
			}
			$detail['sub_title_str'] .= "</table>";
		}
		$detail_child_author = xnparticleGetDetailChildAuthorInformation( $item_id );
		$i = count($detail_child_author);
		if ($i > 0) {
			$j = 0;
			$detail['author_id'] = "";
			$detail['author_name'] = "";
			$detail['author_kana'] = "";
			$detail['author_romaji'] = "";
			$detail['author_affiliation'] = "";
			$detail['author_affiliation_translation'] = "";
			$detail['author_role'] = "";
			$detail['author_link'] = "";
			$detail['author_str'] = "<table><tr><td>"._MD_XNPARTICLE_EDIT_LABEL."</td><td>"._MD_XNPARTICLE_DELETE_LABEL."&nbsp;"._MD_XNPARTICLE_ORDER_LABEL."</td></tr>\n";
			while ( list( $key, list( $article_child_author_id, $article_id, $author_id, $author_name, $author_kana, $author_romaji, $author_affiliation, $author_affiliation_translation, $author_role, $author_link, $author_order ) ) = each( $detail_child_author ) ){
				if ( (!empty($detail['author_id'])) || (!empty($detail['author_name'])) || (!empty($detail['author_kana'])) || (!empty($detail['author_romaji'] )) || (!empty($detail['author_affiliation'])) || (!empty($detail['author_affiliation_translation'])) || (!empty($detail['author_role'])) || (!empty( $detail['author_link'])) ){
					$detail['author_id'] .= "\n";
					$detail['author_name'] .= "\n";
					$detail['author_kana'] .= "\n";
					$detail['author_romaji'] .= "\n";
					$detail['author_affiliation'] .= "\n";
					$detail['author_affiliation_translation'] .= "\n";
					$detail['author_role'] .= "\n";
					$detail['author_link'] .= "\n";
				}
				$detail['author_id'] .= ( !empty( $author_id ) ) ? $author_id : " ";
				$detail['author_name'] .= ( !empty( $author_name ) ) ? $author_name : " ";
				$detail['author_kana'] .= ( !empty( $author_kana ) ) ? $author_kana : " ";
				$detail['author_romaji'] .= ( !empty( $author_romaji ) ) ? $author_romaji : " ";
				$detail['author_affiliation'] .= ( !empty( $author_affiliation ) ) ? $author_affiliation : " ";
				$detail['author_affiliation_translation'] .= ( !empty( $author_affiliation_translation ) ) ? $author_affiliation_translation : " ";
				$detail['author_role'] .= ( !empty( $author_role ) ) ? $author_role : " ";
				$detail['author_link'] .= ( !empty( $author_link ) ) ? $author_link : " ";
				$detail['author_str'] .= "<tr><td width='320'><input size=40 type='text' name='author_id_update_".$j."' value='".$author_id."' /></td>\n";
				$detail['author_str'] .= "<td>&nbsp;&nbsp;<input type='checkbox' name='author_delete_update_".$j."' value='".$j."' />\n";
				$detail['author_str'] .= "&nbsp;&nbsp;<select size='1' name='author_order_update_".$j."'>";
				for ($k = 0; $k < $i; $k++) {
					$detail['author_str'] .= "<option value='".$k."'";
					if ($k == $j) {
						$detail['author_str'] .= " selected";
					}
					$detail['author_str'] .= ">".$k."</option>";
				}
				$detail['author_str'] .= "</td></tr>\n<tr><td width='320'><input size=40 type='text' name='author_name_update_".$j."' value='".$textutil->html_special_chars($author_name)."' /></td>\n";
				$detail['author_str'] .= "</td></tr>\n<tr><td width='320'><input size=40 type='text' name='author_kana_update_".$j."' value='".$textutil->html_special_chars($author_kana)."' /></td>\n";
				$detail['author_str'] .= "</td></tr>\n<tr><td width='320'><input size=40 type='text' name='author_romaji_update_".$j."' value='".$textutil->html_special_chars($author_romaji)."' /></td>\n";
				$detail['author_str'] .= "<tr><td width='320'><input size='50' type='text' name='author_affiliation_update_".$j."' value='".$textutil->html_special_chars($author_affiliation)."' /></td></tr>\n";
				$detail['author_str'] .= "<tr><td width='320'><input size='50' type='text' name='author_affiliation_translation_update_".$j."' value='".$textutil->html_special_chars($author_affiliation_translation)."' /></td></tr>\n";
				$detail['author_str'] .= "</td></tr>\n<tr><td width='320'><input size=40 type='text' name='author_role_update_".$j."' value='".$textutil->html_special_chars($author_role)."' /></td>\n";
				$detail['author_str'] .= "</td></tr>\n<tr><td width='320'><input size=40 type='text' name='author_link_update_".$j."' value='".$textutil->html_special_chars($author_link)."' /></td>\n";
				$j++;
			}
			$detail['author_str'] .= "</table>";
		}
		$detail_child_keywords = xnparticleGetDetailChildKeywordsInformation( $item_id );
		$i = count($detail_child_keywords);
		if ($i > 0) {
			$j = 0;
			$detail['keywords'] = "";
			$detail['keyword_str'] = "<table><tr><td></td><td class='oddeven'>"._MD_XNPARTICLE_EDIT_LABEL."</td><td class='oddeven'>"._MD_XNPARTICLE_DELETE_LABEL."&nbsp;"._MD_XNPARTICLE_ORDER_LABEL."</td></tr>\n";
			while ( list( $key, list( $article_child_keywords_id, $article_id, $keywords, $keywords_order ) ) = each( $detail_child_keywords ) ){
				if ( !empty( $detail['keywords'] ) ){
					$detail['keywords'] .= "\n";
				}
				$detail['keywords'] .= $keywords;
				$detail['keyword_str'] .= "<tr>";
				$detail['keyword_str'] .= "<td width='10'><input size=50 type='text' name='keyword_jpn_update_".$j."' value='".$textutil->html_special_chars($keywords)."' STYLE='ime-mode:active;' /></td>\n";
				$detail['keyword_str'] .= "<td>&nbsp;&nbsp;<input type='checkbox' name='keyword_delete_update_".$j."' value='".$j."' />\n";
				$detail['keyword_str'] .= "&nbsp;&nbsp;<select size='1' name='keyword_order_update_".$j."'>";
				for ($k = 0; $k < $i; $k++) {
					$detail['keyword_str'] .= "<option value='".$k."'";
					if ($k == $j) {
						$detail['keyword_str'] .= " selected";
					}
					$detail['keyword_str'] .= ">".$k."</option>";
				}
				$detail['keyword_str'] .= "</td></tr>\n";
				$j++;
			}
			$detail['keyword_str'] .= "</table>\n";
		}
		$detail_child_ndc_classifications = xnparticleGetDetailChildNdcClassificationsInformation( $item_id );
		$i = count($detail_child_ndc_classifications);
		if ($i > 0) {
			$j = 0;
			$detail['ndc_classifications'] = "";
			$detail['ndc_classification_str'] = "<table><tr><td></td><td class='oddeven'>"._MD_XNPARTICLE_EDIT_LABEL."</td><td class='oddeven'>"._MD_XNPARTICLE_DELETE_LABEL."&nbsp;"._MD_XNPARTICLE_ORDER_LABEL."</td></tr>\n";
			while ( list( $key, list( $article_child_ndc_classifications_id, $article_id, $ndc_classifications, $ndc_classifications_order ) ) = each( $detail_child_ndc_classifications ) ){
				if ( !empty( $detail['ndc_classifications'] ) ){
					$detail['ndc_classifications'] .= "\n";
				}
				$detail['ndc_classifications'] .= $ndc_classifications;
				$detail['ndc_classification_str'] .= "<tr>";
				$detail['ndc_classification_str'] .= "<td width='10'><input size=50 type='text' name='ndc_classification_jpn_update_".$j."' value='".$ndc_classifications."' STYLE='ime-mode:active;' /></td>\n";
				$detail['ndc_classification_str'] .= "<td>&nbsp;&nbsp;<input type='checkbox' name='ndc_classification_delete_update_".$j."' value='".$j."' />\n";
				$detail['ndc_classification_str'] .= "&nbsp;&nbsp;<select size='1' name='ndc_classification_order_update_".$j."'>";
				for ($k = 0; $k < $i; $k++) {
					$detail['ndc_classification_str'] .= "<option value='".$k."'";
					if ($k == $j) {
						$detail['ndc_classification_str'] .= " selected";
					}
					$detail['ndc_classification_str'] .= ">".$k."</option>";
				}
				$detail['ndc_classification_str'] .= "</td></tr>\n";
				$j++;
			}
			$detail['ndc_classification_str'] .= "</table>\n";
		}
		$detail_child_physical_descriptions = xnparticleGetDetailChildPhysicalDescriptionsInformation( $item_id );
		$i = count($detail_child_physical_descriptions);
		if ($i > 0) {
			$j = 0;
			$detail['physical_descriptions'] = "";
			$detail['physical_description_str'] = "<table><tr><td></td><td class='oddeven'>"._MD_XNPARTICLE_EDIT_LABEL."</td><td class='oddeven'>"._MD_XNPARTICLE_DELETE_LABEL."&nbsp;"._MD_XNPARTICLE_ORDER_LABEL."</td></tr>\n";
			while ( list( $key, list( $article_child_physical_descriptions_id, $article_id, $physical_descriptions, $physical_descriptions_order ) ) = each( $detail_child_physical_descriptions ) ){
				if ( !empty( $detail['physical_descriptions'] ) ){
					$detail['physical_descriptions'] .= "\n";
				}
				$detail['physical_descriptions'] .= $physical_descriptions;
				$detail['physical_description_str'] .= "<tr>";
				$detail['physical_description_str'] .= "<td width='10'><input size=50 type='text' name='physical_description_jpn_update_".$j."' value='".$physical_descriptions."' STYLE='ime-mode:active;' /></td>\n";
				$detail['physical_description_str'] .= "<td>&nbsp;&nbsp;<input type='checkbox' name='physical_description_delete_update_".$j."' value='".$j."' />\n";
				$detail['physical_description_str'] .= "&nbsp;&nbsp;<select size='1' name='physical_description_order_update_".$j."'>";
				for ($k = 0; $k < $i; $k++) {
					$detail['physical_description_str'] .= "<option value='".$k."'";
					if ($k == $j) {
						$detail['physical_description_str'] .= " selected";
					}
					$detail['physical_description_str'] .= ">".$k."</option>";
				}
				$detail['physical_description_str'] .= "</td></tr>\n";
				$j++;
			}
			$detail['physical_description_str'] .= "</table>\n";
		}
		$detail_child_langs = xnparticleGetDetailChildLangsInformation( $item_id );
		$i = count($detail_child_langs);
		if ($i > 0) {
			$j = 0;
			$detail['langs'] = "";
			$detail['lang_str'] = "<table><tr><td></td><td class='oddeven'>"._MD_XNPARTICLE_EDIT_LABEL."</td><td class='oddeven'>"._MD_XNPARTICLE_DELETE_LABEL."&nbsp;"._MD_XNPARTICLE_ORDER_LABEL."</td></tr>\n";
			while ( list( $key, list( $article_child_langs_id, $article_id, $langs, $langs_order ) ) = each( $detail_child_langs ) ){
				if ( !empty( $detail['langs'] ) ){
					$detail['langs'] .= "\n";
				}
				$detail['langs'] .= $langs;
				$detail['lang_str'] .= "<tr>";
				$detail['lang_str'] .= "<td width='10'><input size=50 type='text' name='lang_jpn_update_".$j."' value='".$langs."' STYLE='ime-mode:active;' /></td>\n";
				$detail['lang_str'] .= "<td>&nbsp;&nbsp;<input type='checkbox' name='lang_delete_update_".$j."' value='".$j."' />\n";
				$detail['lang_str'] .= "&nbsp;&nbsp;<select size='1' name='lang_order_update_".$j."'>";
				for ($k = 0; $k < $i; $k++) {
					$detail['lang_str'] .= "<option value='".$k."'";
					if ($k == $j) {
						$detail['lang_str'] .= " selected";
					}
					$detail['lang_str'] .= ">".$k."</option>";
				}
				$detail['lang_str'] .= "</td></tr>\n";
				$j++;
			}
			$detail['lang_str'] .= "</table>\n";
		}
		$detail_child_id_issns = xnparticleGetDetailChildIdIssnsInformation( $item_id );
		$i = count($detail_child_id_issns);
		if ($i > 0) {
			$j = 0;
			$detail['id_issns'] = "";
			$detail['id_issn_str'] = "<table><tr><td></td><td class='oddeven'>"._MD_XNPARTICLE_EDIT_LABEL."</td><td class='oddeven'>"._MD_XNPARTICLE_DELETE_LABEL."&nbsp;"._MD_XNPARTICLE_ORDER_LABEL."</td></tr>\n";
			while ( list( $key, list( $article_child_id_issns_id, $article_id, $id_issns, $id_issns_order ) ) = each( $detail_child_id_issns ) ){
				if ( !empty( $detail['id_issns'] ) ){
					$detail['id_issns'] .= "\n";
				}
				$detail['id_issns'] .= $id_issns;
				$detail['id_issn_str'] .= "<tr>";
				$detail['id_issn_str'] .= "<td width='10'><input size=50 type='text' name='id_issn_jpn_update_".$j."' value='".$id_issns."' STYLE='ime-mode:active;' /></td>\n";
				$detail['id_issn_str'] .= "<td>&nbsp;&nbsp;<input type='checkbox' name='id_issn_delete_update_".$j."' value='".$j."' />\n";
				$detail['id_issn_str'] .= "&nbsp;&nbsp;<select size='1' name='id_issn_order_update_".$j."'>";
				for ($k = 0; $k < $i; $k++) {
					$detail['id_issn_str'] .= "<option value='".$k."'";
					if ($k == $j) {
						$detail['id_issn_str'] .= " selected";
					}
					$detail['id_issn_str'] .= ">".$k."</option>";
				}
				$detail['id_issn_str'] .= "</td></tr>\n";
				$j++;
			}
			$detail['id_issn_str'] .= "</table>\n";
		}
		$detail_child_id_isbns = xnparticleGetDetailChildIdIsbnsInformation( $item_id );
		$i = count($detail_child_id_isbns);
		if ($i > 0) {
			$j = 0;
			$detail['id_isbns'] = "";
			$detail['id_isbn_str'] = "<table><tr><td></td><td class='oddeven'>"._MD_XNPARTICLE_EDIT_LABEL."</td><td class='oddeven'>"._MD_XNPARTICLE_DELETE_LABEL."&nbsp;"._MD_XNPARTICLE_ORDER_LABEL."</td></tr>\n";
			while ( list( $key, list( $article_child_id_isbns_id, $article_id, $id_isbns, $id_isbns_order ) ) = each( $detail_child_id_isbns ) ){
				if ( !empty( $detail['id_isbns'] ) ){
					$detail['id_isbns'] .= "\n";
				}
				$detail['id_isbns'] .= $id_isbns;
				$detail['id_isbn_str'] .= "<tr>";
				$detail['id_isbn_str'] .= "<td width='10'><input size=50 type='text' name='id_isbn_jpn_update_".$j."' value='".$id_isbns."' STYLE='ime-mode:active;' /></td>\n";
				$detail['id_isbn_str'] .= "<td>&nbsp;&nbsp;<input type='checkbox' name='id_isbn_delete_update_".$j."' value='".$j."' />\n";
				$detail['id_isbn_str'] .= "&nbsp;&nbsp;<select size='1' name='id_isbn_order_update_".$j."'>";
				for ($k = 0; $k < $i; $k++) {
					$detail['id_isbn_str'] .= "<option value='".$k."'";
					if ($k == $j) {
						$detail['id_isbn_str'] .= " selected";
					}
					$detail['id_isbn_str'] .= ">".$k."</option>";
				}
				$detail['id_isbn_str'] .= "</td></tr>\n";
				$j++;
			}
			$detail['id_isbn_str'] .= "</table>\n";
		}
		$detail_child_id_dois = xnparticleGetDetailChildIdDoisInformation( $item_id );
		$i = count($detail_child_id_dois);
		if ($i > 0) {
			$j = 0;
			$detail['id_dois'] = "";
			$detail['id_doi_str'] = "<table><tr><td></td><td class='oddeven'>"._MD_XNPARTICLE_EDIT_LABEL."</td><td class='oddeven'>"._MD_XNPARTICLE_DELETE_LABEL."&nbsp;"._MD_XNPARTICLE_ORDER_LABEL."</td></tr>\n";
			while ( list( $key, list( $article_child_id_dois_id, $article_id, $id_dois, $id_dois_order ) ) = each( $detail_child_id_dois ) ){
				if ( !empty( $detail['id_dois'] ) ){
					$detail['id_dois'] .= "\n";
				}
				$detail['id_dois'] .= $id_dois;
				$detail['id_doi_str'] .= "<tr>";
				$detail['id_doi_str'] .= "<td width='10'><input size=50 type='text' name='id_doi_jpn_update_".$j."' value='".$id_dois."' STYLE='ime-mode:active;' /></td>\n";
				$detail['id_doi_str'] .= "<td>&nbsp;&nbsp;<input type='checkbox' name='id_doi_delete_update_".$j."' value='".$j."' />\n";
				$detail['id_doi_str'] .= "&nbsp;&nbsp;<select size='1' name='id_doi_order_update_".$j."'>";
				for ($k = 0; $k < $i; $k++) {
					$detail['id_doi_str'] .= "<option value='".$k."'";
					if ($k == $j) {
						$detail['id_doi_str'] .= " selected";
					}
					$detail['id_doi_str'] .= ">".$k."</option>";
				}
				$detail['id_doi_str'] .= "</td></tr>\n";
				$j++;
			}
			$detail['id_doi_str'] .= "</table>\n";
		}
		$detail_child_id_uris = xnparticleGetDetailChildIdUrisInformation( $item_id );
		$i = count($detail_child_id_uris);
		if ($i > 0) {
			$j = 0;
			$detail['id_uris'] = "";
			$detail['id_uri_str'] = "<table><tr><td></td><td class='oddeven'>"._MD_XNPARTICLE_EDIT_LABEL."</td><td class='oddeven'>"._MD_XNPARTICLE_DELETE_LABEL."&nbsp;"._MD_XNPARTICLE_ORDER_LABEL."</td></tr>\n";
			while ( list( $key, list( $article_child_id_uris_id, $article_id, $id_uris, $id_uris_order ) ) = each( $detail_child_id_uris ) ){
				if ( !empty( $detail['id_uris'] ) ){
					$detail['id_uris'] .= "\n";
				}
				$detail['id_uris'] .= $id_uris;
				$detail['id_uri_str'] .= "<tr>";
				$detail['id_uri_str'] .= "<td width='10'><input size=50 type='text' name='id_uri_jpn_update_".$j."' value='".$id_uris."' STYLE='ime-mode:active;' /></td>\n";
				$detail['id_uri_str'] .= "<td>&nbsp;&nbsp;<input type='checkbox' name='id_uri_delete_update_".$j."' value='".$j."' />\n";
				$detail['id_uri_str'] .= "&nbsp;&nbsp;<select size='1' name='id_uri_order_update_".$j."'>";
				for ($k = 0; $k < $i; $k++) {
					$detail['id_uri_str'] .= "<option value='".$k."'";
					if ($k == $j) {
						$detail['id_uri_str'] .= " selected";
					}
					$detail['id_uri_str'] .= ">".$k."</option>";
				}
				$detail['id_uri_str'] .= "</td></tr>\n";
				$j++;
			}
			$detail['id_uri_str'] .= "</table>\n";
		}
		$detail_child_id_locals = xnparticleGetDetailChildIdLocalsInformation( $item_id );
		$i = count($detail_child_id_locals);
		if ($i > 0) {
			$j = 0;
			$detail['id_locals'] = "";
			$detail['id_local_str'] = "<table><tr><td></td><td class='oddeven'>"._MD_XNPARTICLE_EDIT_LABEL."</td><td class='oddeven'>"._MD_XNPARTICLE_DELETE_LABEL."&nbsp;"._MD_XNPARTICLE_ORDER_LABEL."</td></tr>\n";
			while ( list( $key, list( $article_child_id_locals_id, $article_id, $id_locals, $id_locals_order ) ) = each( $detail_child_id_locals ) ){
				if ( !empty( $detail['id_locals'] ) ){
					$detail['id_locals'] .= "\n";
				}
				$detail['id_locals'] .= $id_locals;
				$detail['id_local_str'] .= "<tr>";
				$detail['id_local_str'] .= "<td width='10'><input size=50 type='text' name='id_local_jpn_update_".$j."' value='".$id_locals."' STYLE='ime-mode:active;' /></td>\n";
				$detail['id_local_str'] .= "<td>&nbsp;&nbsp;<input type='checkbox' name='id_local_delete_update_".$j."' value='".$j."' />\n";
				$detail['id_local_str'] .= "&nbsp;&nbsp;<select size='1' name='id_local_order_update_".$j."'>";
				for ($k = 0; $k < $i; $k++) {
					$detail['id_local_str'] .= "<option value='".$k."'";
					if ($k == $j) {
						$detail['id_local_str'] .= " selected";
					}
					$detail['id_local_str'] .= ">".$k."</option>";
				}
				$detail['id_local_str'] .= "</td></tr>\n";
				$j++;
			}
			$detail['id_local_str'] .= "</table>\n";
		}
		$detail_child_uris = xnparticleGetDetailChildUrisInformation( $item_id );
		$i = count($detail_child_uris);
		if ($i > 0) {
			$j = 0;
			$detail['uris'] = "";
			$detail['uri_str'] = "<table><tr><td></td><td class='oddeven'>"._MD_XNPARTICLE_EDIT_LABEL."</td><td class='oddeven'>"._MD_XNPARTICLE_DELETE_LABEL."&nbsp;"._MD_XNPARTICLE_ORDER_LABEL."</td></tr>\n";
			while ( list( $key, list( $article_child_uris_id, $article_id, $uris, $uris_order ) ) = each( $detail_child_uris ) ){
				if ( !empty( $detail['uris'] ) ){
					$detail['uris'] .= "\n";
				}
				$detail['uris'] .= $uris;
				$detail['uri_str'] .= "<tr>";
				$detail['uri_str'] .= "<td width='10'><input size=50 type='text' name='uri_jpn_update_".$j."' value='".$uris."' STYLE='ime-mode:active;' /></td>\n";
				$detail['uri_str'] .= "<td>&nbsp;&nbsp;<input type='checkbox' name='uri_delete_update_".$j."' value='".$j."' />\n";
				$detail['uri_str'] .= "&nbsp;&nbsp;<select size='1' name='uri_order_update_".$j."'>";
				for ($k = 0; $k < $i; $k++) {
					$detail['uri_str'] .= "<option value='".$k."'";
					if ($k == $j) {
						$detail['uri_str'] .= " selected";
					}
					$detail['uri_str'] .= ">".$k."</option>";
				}
				$detail['uri_str'] .= "</td></tr>\n";
				$j++;
			}
			$detail['uri_str'] .= "</table>\n";
		}
	}
	else
		$detail = array();

	$detail = xnparticleRegisterTitleRomajiString( $detail );
//igarashi add 20080616
	$detail = xnparticleRegisterSubTitleRomajiString( $detail );
	$detail = xnparticleRegisterAuthorRomajiString( $detail );
	$detail = xnparticleRegisterPublisherRomajiString( $detail );
//igarashi add 20080616
	$detail = xnparticleRegisterSubTitleString( $detail );
	$detail = xnparticleRegisterAuthorString( $detail );
	$detail = xnparticleRegisterKeywordsString( $detail );
	$detail = xnparticleRegisterNdcClassificationsString( $detail );
	$detail = xnparticleRegisterPhysicalDescriptionsString( $detail );
	$detail = xnparticleRegisterLangsString( $detail );
	$detail = xnparticleRegisterIdIssnsString( $detail );
	$detail = xnparticleRegisterIdIsbnsString( $detail );
	$detail = xnparticleRegisterIdDoisString( $detail );
	$detail = xnparticleRegisterIdUrisString( $detail );
	$detail = xnparticleRegisterIdLocalsString( $detail );
	$detail = xnparticleRegisterUrisString( $detail );

	// Set into the template
	global $xoopsTpl;
	$tpl = new xoopsTpl();
	$tpl->assign( $xoopsTpl->get_template_vars() ); // xoopsTplにセットされた変数($xoops_urlとか)を、$tplにコピーする。

	$tpl->assign( 'basic', $basic );
	$tpl->assign( 'index', $index );
	$tpl->assign( 'article_images', $article_images );
	$tpl->assign( 'article_attachment', $article_attachment );
	$tpl->assign( 'detail', $detail );

	
	// Return as HTML
	return $tpl->fetch( "db:xnparticle_register_block.html" );
}


function xnparticleGetConfirmBlock( $item_id ){
	// Get "BasicInformation / Preview / index block"
	$basic   = xnpGetBasicInformationConfirmBlock( $item_id );
	$index   = xnpGetIndexConfirmBlock( $item_id );
	$article_attachment = xnpGetAttachmentConfirmBlock( $item_id, 'article_attachment' );
	$article_images = xnpGetPreviewConfirmBlock( $item_id );
	
	// Get DetailInformation
	if ( isset( $_POST['title'] ) ){
          $detail = _xnparticle_get_form_request( false, false );
          foreach ( $detail as $key => $val ) {
            $detail[$key] = array( 'value' => $val );
          }
		xnpConfirmHtml( $detail, 'xnparticle_item_detail' );
		xnpConfirmHtml( $detail, 'xnparticle_item_detail_child_sub_title' );
		xnpConfirmHtml( $detail, 'xnparticle_item_detail_child_author' );
		xnpConfirmHtml( $detail, 'xnparticle_item_detail_child_keywords' );
		xnpConfirmHtml( $detail, 'xnparticle_item_detail_child_ndc_classifications' );
		xnpConfirmHtml( $detail, 'xnparticle_item_detail_child_physical_descriptions' );
		xnpConfirmHtml( $detail, 'xnparticle_item_detail_child_langs' );
		xnpConfirmHtml( $detail, 'xnparticle_item_detail_child_id_issns' );
		xnpConfirmHtml( $detail, 'xnparticle_item_detail_child_id_isbns' );
		xnpConfirmHtml( $detail, 'xnparticle_item_detail_child_id_dois' );
		xnpConfirmHtml( $detail, 'xnparticle_item_detail_child_id_uris' );
		xnpConfirmHtml( $detail, 'xnparticle_item_detail_child_id_locals' );
		xnpConfirmHtml( $detail, 'xnparticle_item_detail_child_uris' );
		
		$sub_title_name                    = explode( "\n", $detail['sub_title_name'                   ]['value'] );
		$sub_title_kana                    = explode( "\n", $detail['sub_title_kana'                   ]['value'] );
		$sub_title_romaji                  = explode( "\n", $detail['sub_title_romaji'                 ]['value'] );
		$i = 0;
		reset($sub_title_kana);
		reset($sub_title_romaji);
		$detail['sub_title_str'] = array( 'value' => "<table>\n" );
		foreach ( $sub_title_name as  $value ){
			$sub_title_name_1 = ( $value != " " ) ? $value : "";
			$sub_title_kana_1 = ( current($sub_title_kana) != " " ) ? current($sub_title_kana) : "";
			$sub_title_romaji_1 = ( current($sub_title_romaji) != " " ) ? current($sub_title_romaji) : "";
			$detail['sub_title_str']['value'] .= "<tr class='oddeven".fmod($i, 2)."'><td>".$value." (".$sub_title_kana_1.":".$sub_title_romaji_1.")</td></tr>\n";
			next($sub_title_kana);
			next($sub_title_romaji);
			$i++;
		}
		$detail['sub_title_str']['value'] .= "</table>";
		$detail['sub_title_cnt'] = array( 'value' => strval(fmod(count($sub_title_name), 2)));

		$author_id                      = explode( "\n", $detail['author_id'                     ]['value'] );
		$author_name                    = explode( "\n", $detail['author_name'                   ]['value'] );
		$author_kana                    = explode( "\n", $detail['author_kana'                   ]['value'] );
		$author_romaji                  = explode( "\n", $detail['author_romaji'                 ]['value'] );
		$author_affiliation             = explode( "\n", $detail['author_affiliation'            ]['value'] );
		$author_affiliation_translation = explode( "\n", $detail['author_affiliation_translation']['value'] );
		$author_role                    = explode( "\n", $detail['author_role'                   ]['value'] );
		$author_link                    = explode( "\n", $detail['author_link'                   ]['value'] );
		$i = 0;
		reset($author_id);
		reset($author_kana);
		reset($author_romaji);
		reset($author_affiliation);
		reset($author_affiliation_translation);
		reset($author_role);
		reset($author_link);
		$detail['author_str'] = array( 'value' => "<table>\n" );
		foreach ( $author_name as  $value ){
			$author_id_1 = ( current($author_id) != " " ) ? current($author_id) : "";
			$author_name_1 = ( $value != " " ) ? $value : "";
			$author_kana_1 = ( current($author_kana) != " " ) ? current($author_kana) : "";
			$author_romaji_1 = ( current($author_romaji) != " " ) ? current($author_romaji) : "";
			$author_affiliation_1 = ( current($author_affiliation) != " " ) ? current($author_affiliation) : "";
			$author_affiliation_translation_1 = ( current($author_affiliation_translation) != " " ) ? current($author_affiliation_translation) : "";
			$author_role_1 = ( current($author_role) != " " ) ? current($author_role) : "";
			$author_link_1 = ( current($author_link) != " " ) ? current($author_link) : "";
			$detail['author_str']['value'] .= "<tr class='oddeven".fmod($i, 2)."'><td width='70'>"._MD_XNPARTICLE_AUTHOR_ID_LABEL."</td><td>:&nbsp;".$author_id_1."</td></tr>\n";
			$detail['author_str']['value'] .= "<tr class='oddeven".fmod($i, 2)."'><td width='70'>"._MD_XNPARTICLE_AUTHOR_NAME_LABEL."</td><td>:&nbsp;".$value." (".$author_kana_1.":".$author_romaji_1.")</td></tr>\n";
			$detail['author_str']['value'] .= "<tr class='oddeven".fmod($i, 2)."'><td width='70'>"._MD_XNPARTICLE_AUTHOR_AFFILIATION_LABEL."</td><td>:&nbsp;".$author_affiliation_1."(".$author_affiliation_translation_1.")</td></tr>\n";
			$detail['author_str']['value'] .= "<tr class='oddeven".fmod($i, 2)."'><td width='70'>"._MD_XNPARTICLE_AUTHOR_ROLE_LABEL."</td><td>:&nbsp;".$author_role_1."</td></tr>\n";
			$detail['author_str']['value'] .= "<tr class='oddeven".fmod($i, 2)."'><td width='70'>"._MD_XNPARTICLE_AUTHOR_LINK_LABEL."</td><td>:&nbsp;<a href='".$author_link_1."'>".$author_link_1."</a></td></tr>\n";
			next($author_id);
			next($author_kana);
			next($author_romaji);
			next($author_affiliation);
			next($author_affiliation_translation);
			next($author_role);
			next($author_link);
			$i++;
		}
		$detail['author_str']['value'] .= "</table>";
		$detail['author_cnt'] = array( 'value' => strval(fmod(count($author_name), 2)));
		$detail['keyword_str'] = array( 'value' => "" );
		if ( !empty( $detail['keywords'] ) ){
			$detail['keyword_str']['value'] = "<table>\n";
			$keywords = explode( "\n", $detail['keywords']['value'] );
			$i = 0;
			foreach ( $keywords as  $value ){
				$detail['keyword_str']['value'] .= "<tr class='oddeven".fmod($i, 2)."'><td>".$value."</td></tr>\n";
				$i++;
			}
			$detail['keyword_str']['value'] .= "</table>";
			$detail['keywords_cnt'] = array( 'value' => strval(fmod(count($keywords), 2)));
		}
		$detail['ndc_classification_str'] = array( 'value' => "" );
		if ( !empty( $detail['ndc_classifications'] ) ){
			$detail['ndc_classification_str']['value'] = "<table>\n";
			$ndc_classifications = explode( "\n", $detail['ndc_classifications']['value'] );
			$i = 0;
			foreach ( $ndc_classifications as  $value ){
				$detail['ndc_classification_str']['value'] .= "<tr class='oddeven".fmod($i, 2)."'><td>".$value."</td></tr>\n";
				$i++;
			}
			$detail['ndc_classification_str']['value'] .= "</table>";
			$detail['ndc_classifications_cnt'] = array( 'value' => strval(fmod(count($ndc_classifications), 2)));
		}
		$detail['physical_description_str'] = array( 'value' => "" );
		if ( !empty( $detail['physical_descriptions'] ) ){
			$detail['physical_description_str']['value'] = "<table>\n";
			$physical_descriptions = explode( "\n", $detail['physical_descriptions']['value'] );
			$i = 0;
			foreach ( $physical_descriptions as  $value ){
				$detail['physical_description_str']['value'] .= "<tr class='oddeven".fmod($i, 2)."'><td>".$value."</td></tr>\n";
				$i++;
			}
			$detail['physical_description_str']['value'] .= "</table>";
			$detail['physical_descriptions_cnt'] = array( 'value' => strval(fmod(count($physical_descriptions), 2)));
		}
		$detail['lang_str'] = array( 'value' => "" );
		if ( !empty( $detail['langs'] ) ){
			$detail['lang_str']['value'] = "<table>\n";
			$langs = explode( "\n", $detail['langs']['value'] );
			$i = 0;
			foreach ( $langs as  $value ){
				$detail['lang_str']['value'] .= "<tr class='oddeven".fmod($i, 2)."'><td>".$value."</td></tr>\n";
				$i++;
			}
			$detail['lang_str']['value'] .= "</table>";
			$detail['langs_cnt'] = array( 'value' => strval(fmod(count($langs), 2)));
		}
		$detail['id_issn_str'] = array( 'value' => "" );
		if ( !empty( $detail['id_issns'] ) ){
			$detail['id_issn_str']['value'] = "<table>\n";
			$id_issns = explode( "\n", $detail['id_issns']['value'] );
			$i = 0;
			foreach ( $id_issns as  $value ){
				$detail['id_issn_str']['value'] .= "<tr class='oddeven".fmod($i, 2)."'><td>".$value."</td></tr>\n";
				$i++;
			}
			$detail['id_issn_str']['value'] .= "</table>";
			$detail['id_issns_cnt'] = array( 'value' => strval(fmod(count($id_issns), 2)));
		}
		$detail['id_isbn_str'] = array( 'value' => "" );
		if ( !empty( $detail['id_isbns'] ) ){
			$detail['id_isbn_str']['value'] = "<table>\n";
			$id_isbns = explode( "\n", $detail['id_isbns']['value'] );
			$i = 0;
			foreach ( $id_isbns as  $value ){
				$detail['id_isbn_str']['value'] .= "<tr class='oddeven".fmod($i, 2)."'><td>".$value."</td></tr>\n";
				$i++;
			}
			$detail['id_isbn_str']['value'] .= "</table>";
			$detail['id_isbns_cnt'] = array( 'value' => strval(fmod(count($id_isbns), 2)));
		}
		$detail['id_doi_str'] = array( 'value' => "" );
		if ( !empty( $detail['id_dois'] ) ){
			$detail['id_doi_str']['value'] = "<table>\n";
			$id_dois = explode( "\n", $detail['id_dois']['value'] );
			$i = 0;
			foreach ( $id_dois as  $value ){
				$detail['id_doi_str']['value'] .= "<tr class='oddeven".fmod($i, 2)."'><td>".$value."</td></tr>\n";
				$i++;
			}
			$detail['id_doi_str']['value'] .= "</table>";
			$detail['id_dois_cnt'] = array( 'value' => strval(fmod(count($id_dois), 2)));
		}
		$detail['id_uri_str'] = array( 'value' => "" );
		if ( !empty( $detail['id_uris'] ) ){
			$detail['id_uri_str']['value'] = "<table>\n";
			$id_uris = explode( "\n", $detail['id_uris']['value'] );
			$i = 0;
			foreach ( $id_uris as  $value ){
				$detail['id_uri_str']['value'] .= "<tr class='oddeven".fmod($i, 2)."'><td>".$value."</td></tr>\n";
				$i++;
			}
			$detail['id_uri_str']['value'] .= "</table>";
			$detail['id_uris_cnt'] = array( 'value' => strval(fmod(count($id_uris), 2)));
		}
		$detail['id_local_str'] = array( 'value' => "" );
		if ( !empty( $detail['id_locals'] ) ){
			$detail['id_local_str']['value'] = "<table>\n";
			$id_locals = explode( "\n", $detail['id_locals']['value'] );
			$i = 0;
			foreach ( $id_locals as  $value ){
				$detail['id_local_str']['value'] .= "<tr class='oddeven".fmod($i, 2)."'><td>".$value."</td></tr>\n";
				$i++;
			}
			$detail['id_local_str']['value'] .= "</table>";
			$detail['id_locals_cnt'] = array( 'value' => strval(fmod(count($id_locals), 2)));
		}
		$detail['uri_str'] = array( 'value' => "" );
		if ( !empty( $detail['uris'] ) ){
			$detail['uri_str']['value'] = "<table>\n";
			$uris = explode( "\n", $detail['uris']['value'] );
			$i = 0;
			foreach ( $uris as  $value ){
				$detail['uri_str']['value'] .= "<tr class='oddeven".fmod($i, 2)."'><td>".$value."</td></tr>\n";
				$i++;
			}
			$detail['uri_str']['value'] .= "</table>";
			$detail['uris_cnt'] = array( 'value' => strval(fmod(count($uris), 2)));
		}
	}
	else
		$detail = array();
	
	if ( xnpHasWithout( $basic ) || xnpHasWithout( $detail ) 
	  || xnpHasWithout( $article_attachment ) || xnpHasWithout( $article_images ) ){
		global $system_message;
		$system_message = $system_message."\n<br /><font color='#ff0000'>" . _MD_XOONIPS_ITEM_WARNING_FIELD_TRIM . "</font><br />";
	}
	
	// Set into the template
	global $xoopsTpl;
	$tpl = new xoopsTpl();
	$tpl->assign( $xoopsTpl->get_template_vars() ); // xoopsTplにセットされた変数($xoops_urlとか)を、$tplにコピーする。
	
	$tpl->assign( 'basic', $basic );
	$tpl->assign( 'index', $index );
	$tpl->assign( 'article_attachment', $article_attachment );
	$tpl->assign( 'article_images', $article_images );
	$tpl->assign( 'detail', $detail );
	
	// Return as HTML
	return $tpl->fetch( "db:xnparticle_confirm_block.html" );
}

/** DetailInformationの入力チェックを行う。
 * 登録確認画面、登録完了画面の2ヶ所から呼ばれる。
 */
function xnparticleCheckRegisterParameters( &$message ){
	$messages = array();
	if ( empty( $_POST['title'] ) )  $messages[] = "title required.";
	if ( count($messages) == 0 )
		return true;
	$message = implode( "", $messages );
	return false;
}

/** DetailInformationの入力チェックを行う。
 */
function xnparticleCheckEditParameters( &$message ){
	return xnparticleCheckRegisterParameters( $message );
}

function xnparticleInsertItem(){
  $xnpsid = $_SESSION['XNPSID'];

  // BasicInformation, Index, Attachmentの登録
  // override title
    // 2009.02.18 sc.ito add start --- titleの退避
    $tmpTitle = $_POST['title'];
    // 2009.02.18 sc.ito add end
  $_POST['title'] .= "\n".$_POST['title_kana']."\n".$_POST['title_romaji'];

  $item_id = 0;
  $result = xnpInsertBasicInformation( $item_id );
  if ( $result ){
    $result = xnpUpdateIndex( $item_id );
    if ( $result ){
      $result = xnpUpdatePreview( $item_id );
      if ( $result ){
        $result = xnpUpdateAttachment( $item_id, 'article_attachment' );
        if ( $result ){
        }
      }
    }
    if ( ! $result ) {
      xnpDeleteBasicInformation( $xnpsid, $item_id );
    }
  }
  if ( ! $result ) {
    return false;
  }

    // 2009.02.18 sc.ito add start --- 退避しておいたtitleに戻す
    $_POST['title'] = $tmpTitle;
    // 2009.02.18 sc.ito add end
  $detail = _xnparticle_get_form_request( false, false );
  xnpTrimColumn( $detail, 'xnparticle_item_detail' );
  $keys = array(
    'title',
    'title_kana',
    'title_romaji',
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
    'edition',
    'publish_place',
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
  $detail_handler =& xoonips_getormhandler( 'xnparticle', 'item_detail' );
  $detail_obj =& $detail_handler->create();
  $detail_obj->set( 'article_id', $item_id );
  foreach ( $keys as $key ) {
    if ( in_array( $key, array( 'abstract', 'table_of_contents', 'access_condition' ) ) ) {
      // set null if empty string
      if ( $detail[$key] != '' ) {
        $detail_obj->set( $key, $detail[$key] );
      }
    } else {
      $detail_obj->set( $key, $detail[$key] );
    }
  }
  // Registration of the DetailInformation
  if ( ! $detail_handler->insert( $detail_obj ) ) {
    echo "cannot insert item_detail";
    return false;
  }

  if ( ( ! empty( $detail['sub_title_name'] ) ) || ( ! empty( $detail['sub_title_kana'] ) ) || ( ! empty( $detail['sub_title_romaji'] ) ) ) {
    $sub_title_handler =& xoonips_getormhandler( 'xnparticle', 'item_detail_child_sub_title' );
    $sub_title_name = explode( "\n", $detail['sub_title_name'] );
    $sub_title_kana = explode( "\n", $detail['sub_title_kana'] );
    $sub_title_romaji = explode( "\n", $detail['sub_title_romaji'] );
    foreach ( array_keys( $sub_title_name ) as  $num) {
      $ar = array(
        'sub_title_name' => $sub_title_name[$num],
        'sub_title_kana' => $sub_title_kana[$num],
        'sub_title_romaji' => $sub_title_romaji[$num]
      );
      xnpTrimColumn( $ar, 'xnparticle_item_detail_child_sub_title' );
      $sub_title_obj =& $sub_title_handler->create();
      $sub_title_obj->set( 'article_id', $item_id );
      $sub_title_obj->set( 'sub_title_name', $ar['sub_title_name'] );
      $sub_title_obj->set( 'sub_title_kana', $ar['sub_title_kana'] );
      $sub_title_obj->set( 'sub_title_romaji', $ar['sub_title_romaji'] );
      $sub_title_obj->set( 'sub_title_order', $num );
      // Registration of the DetailInformation Child Sub Title
      if ( ! $sub_title_handler->insert( $sub_title_obj ) ) {
        echo "cannot insert item_detail_child_sub_title";
        return false;
      }
    }
  }

  if ( ( ! empty( $detail['author_id'] ) ) || ( ! empty( $detail['author_name'] ) ) || ( ! empty( $detail['author_kana'] ) ) || ( ! empty( $detail['author_romaji'] ) ) || ( ! empty( $detail['author_affiliation'] ) ) || (! empty( $detail['author_affiliation_translation'] ) ) ) {
    $author_handler =& xoonips_getormhandler( 'xnparticle', 'item_detail_child_author' );
    $author_id = explode( "\n", $detail['author_id'] );
    $author_name = explode( "\n", $detail['author_name'] );
    $author_kana = explode( "\n", $detail['author_kana'] );
    $author_romaji = explode( "\n", $detail['author_romaji'] );
    $author_affiliation = explode( "\n", $detail['author_affiliation'] );
    $author_affiliation_translation = explode( "\n", $detail['author_affiliation_translation'] );
    $author_role = explode( "\n", $detail['author_role'] );
    $author_link = explode( "\n", $detail['author_link'] );
    foreach ( array_keys( $author_name ) as  $num) {
      $ar = array(
        'author_id' => $author_id[$num],
        'author_name' => $author_name[$num],
        'author_kana' => $author_kana[$num],
        'author_romaji' => $author_romaji[$num],
        'author_affiliation' => $author_affiliation[$num],
        'author_affiliation_translation' => $author_affiliation_translation[$num],
        'author_role' => $author_role[$num],
        'author_link' => $author_link[$num]
      );
      xnpTrimColumn( $ar, 'xnparticle_item_detail_child_author' );
      $author_obj =& $author_handler->create();
      $author_obj->set( 'article_id', $item_id );
      $author_obj->set( 'author_name', $ar['author_name'] );
      $author_obj->set( 'author_kana', $ar['author_kana'] );
      $author_obj->set( 'author_romaji', $ar['author_romaji'] );
      $author_obj->set( 'author_affiliation', $ar['author_affiliation'] );
      $author_obj->set( 'author_affiliation_translation', $ar['author_affiliation_translation'] );
      $author_obj->set( 'author_role', $ar['author_role'] );
      $author_obj->set( 'author_link', $ar['author_link'] );
      $author_obj->set( 'author_order', $num );
      // Registration of the DetailInformation Child Author
      if ( ! $author_handler->insert( $author_obj ) ) {
        echo "cannot insert item_detail_child_author";
        return false;
      }
    }
  }

  $simple_repeatable_keys = array(
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
  foreach( $simple_repeatable_keys as $key ) {
    if ( ! empty( $detail[$key] ) ) {
      $handler =& xoonips_getormhandler( 'xnparticle', 'item_detail_child_'.$key );
      $values = explode( "\n", $detail[$key] );
      foreach ( array_keys( $values ) as $num ) {
        $ar = array(
          $key => $values[$num]
        );
        xnpTrimColumn( $ar, 'xnparticle_item_detail_child_'.$key );
        $obj =& $handler->create();
        $obj->set( 'article_id', $item_id );
        $obj->set( $key, $ar[$key] );
        $obj->set( $key.'_order', $num );
        // Registration of the DetailInformation Child $key
        if ( ! $handler->insert( $obj ) ) {
          echo "cannot insert item_detail_child_".$key;
          return false;
        }
      }
    }
  }
  return true;
}

function xnparticleUpdateItem( $item_id ){
  $xnpsid = $_SESSION['XNPSID'];
  $formdata =& xoonips_getutility( 'formdata' );

  // Edit BasicInformation, Index, Attachmentの登録
  // override title
    // 2009.02.18 sc.ito add start --- titleの退避
    $tmpTitle = $_POST['title'];
    // 2009.02.18 sc.ito add end
  $_POST['title'] .= "\n".$_POST['title_kana']."\n".$_POST['title_romaji'];

  $result = xnpUpdateBasicInformation( $item_id );
  if ( $result ){
    $result = xnpUpdateIndex( $item_id );
    if ( $result ){
      $result = xnpUpdatePreview( $item_id );
      if ( $result ){
        $result = xnpUpdateAttachment( $item_id, 'article_attachment' );
        if ( $result ){
          $result = xnp_insert_change_log( $xnpsid, $item_id, $formdata->getValue( 'post', 'change_log', 's', false, '' ) );
          $result = !$result;
          if( !$result ) {
            echo " xnp_insert_change_log failed.";
          }
        } else {
          echo " xnpUpdateAttachment failed.";
        }
      } else {
        echo " xnpUpdatePreview failed.";
      }
    } else {
      echo " xnpUpdateIndex failed.";
    }
  } else {
    echo " xnpUpdateBasicInformation failed.";
  }
  if ( !$result ) {
    return false;
  }

    // 2009.02.18 sc.ito add start --- 退避しておいたtitleに戻す
    $_POST['title'] = $tmpTitle;
    // 2009.02.18 sc.ito add end
  $detail = _xnparticle_get_form_request( false, false );
  xnpTrimColumn( $detail, 'xnparticle_item_detail' );
  $keys = array(
    'title',
    'title_kana',
    'title_romaji',
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
    'edition',
    'publish_place',
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
  $detail_handler =& xoonips_getormhandler( 'xnparticle', 'item_detail' );
  $detail_obj =& $detail_handler->get( $item_id );
  if ( ! is_object( $detail_obj ) ) {
    return false;
  }

  foreach ( $keys as $key ) {
    if ( in_array( $key, array( 'abstract', 'table_of_contents', 'access_condition' ) ) ) {
      // set null if empty string
      if ( $detail[$key] == '' ) {
        $detail_obj->setDefault( $key );
      } else {
        $detail_obj->set( $key, $detail[$key] );
      }
    } else {
      $detail_obj->set( $key, $detail[$key] );
    }
  }
  // Registration of the DetailInformation
  if ( ! $detail_handler->insert( $detail_obj ) ) {
    echo "cannot update item_detail";
    return false;
  }

  $criteria = new Criteria( 'article_id', $item_id );
  $sub_title_handler =& xoonips_getormhandler( 'xnparticle', 'item_detail_child_sub_title' );
  if ( ( ! empty( $detail['sub_title_name'] ) ) || ( ! empty( $detail['sub_title_kana'] ) ) || ( ! empty( $detail['sub_title_romaji'] ) ) ) {
    $criteria->setSort( 'sub_title_order' );
    $criteria->setOrder( 'ASC' );
    $sub_title_objs = $sub_title_handler->getObjects( $criteria );
    $sub_title_name = explode( "\n", $detail['sub_title_name'] );
    $sub_title_kana = explode( "\n", $detail['sub_title_kana'] );
    $sub_title_romaji = explode( "\n", $detail['sub_title_romaji'] );
    $old_num = count( $sub_title_objs );
    $new_num = count( $sub_title_name );
    $loop_num = max( $old_num, $new_num );
    for ( $i = 0; $i < $loop_num; $i++ ) {
      if ( $new_num <= $i ) {
        $sub_title_obj =& $sub_title_objs[$i];
        $sub_title_handler->delete( $sub_title_obj );
        continue;
      }
      $ar = array(
        'sub_title_name' => $sub_title_name[$i],
        'sub_title_kana' => $sub_title_kana[$i],
        'sub_title_romaji' => $sub_title_romaji[$i]
      );
      xnpTrimColumn( $ar, 'xnparticle_item_detail_child_sub_title' );
      if ( $old_num > $i ) {
        $sub_title_obj =& $sub_title_objs[$i];
      } else {
        $sub_title_obj =& $sub_title_handler->create();
      }
      $sub_title_obj->set( 'article_id', $item_id );
      $sub_title_obj->set( 'sub_title_name', $ar['sub_title_name'] );
      $sub_title_obj->set( 'sub_title_kana', $ar['sub_title_kana'] );
      $sub_title_obj->set( 'sub_title_romaji', $ar['sub_title_romaji'] );
      $sub_title_obj->set( 'sub_title_order', $i );
      // Registration of the DetailInformation Child Sub Title
      if ( ! $sub_title_handler->insert( $sub_title_obj ) ) {
        echo "cannot insert item_detail_child_sub_title";
        return false;
      }
    }
  } else {
    $sub_title_handler->deleteAll( $criteria );
  }

  $criteria = new Criteria( 'article_id', $item_id );
  $author_handler =& xoonips_getormhandler( 'xnparticle', 'item_detail_child_author' );
  if ( ( ! empty( $detail['author_id'] ) ) || ( ! empty( $detail['author_name'] ) ) || ( ! empty( $detail['author_kana'] ) ) || ( ! empty( $detail['author_romaji'] ) ) || ( ! empty( $detail['author_affiliation'] ) ) || (! empty( $detail['author_affiliation_translation'] ) ) ) {
    $criteria->setSort( 'author_order' );
    $criteria->setOrder( 'ASC' );
    $author_objs =& $author_handler->getObjects( $criteria );
    $author_id = explode( "\n", $detail['author_id'] );
    $author_name = explode( "\n", $detail['author_name'] );
    $author_kana = explode( "\n", $detail['author_kana'] );
    $author_romaji = explode( "\n", $detail['author_romaji'] );
    $author_affiliation = explode( "\n", $detail['author_affiliation'] );
    $author_affiliation_translation = explode( "\n", $detail['author_affiliation_translation'] );
    $author_role = explode( "\n", $detail['author_role'] );
    $author_link = explode( "\n", $detail['author_link'] );
    $old_num = count( $author_objs );
    $new_num = count( $author_name );
    $loop_num = max( $old_num, $new_num );
    for ( $i = 0; $i < $loop_num; $i++ ) {
      if ( $new_num <= $i ) {
        $author_obj =& $author_objs[$i];
        $author_handler->delete( $author_obj );
        continue;
      }
      $ar = array(
        'author_id' => $author_id[$i],
        'author_name' => $author_name[$i],
        'author_kana' => $author_kana[$i],
        'author_romaji' => $author_romaji[$i],
        'author_affiliation' => $author_affiliation[$i],
        'author_affiliation_translation' => $author_affiliation_translation[$i],
        'author_role' => $author_role[$i],
        'author_link' => $author_link[$i]
      );
      xnpTrimColumn( $ar, 'xnparticle_item_detail_child_author' );
      if ( $old_num > $i ) {
        $author_obj =& $author_objs[$i];
      } else {
        $author_obj =& $author_handler->create();
      }
      $author_obj->set( 'article_id', $item_id );
      //2014.12.09 bug fix by inaki
      $author_obj->set( 'author_id', $ar['author_id'] );
      $author_obj->set( 'author_name', $ar['author_name'] );
      $author_obj->set( 'author_kana', $ar['author_kana'] );
      $author_obj->set( 'author_romaji', $ar['author_romaji'] );
      $author_obj->set( 'author_affiliation', $ar['author_affiliation'] );
      $author_obj->set( 'author_affiliation_translation', $ar['author_affiliation_translation'] );
      $author_obj->set( 'author_role', $ar['author_role'] );
      $author_obj->set( 'author_link', $ar['author_link'] );
      $author_obj->set( 'author_order', $i );
      // Registration of the DetailInformation Child Author
      if ( ! $author_handler->insert( $author_obj ) ) {
        echo "cannot insert item_detail_child_author";
        return false;
      }
    }
  } else {
    $author_handler->deleteAll( $criteria );
  }

  $simple_repeatable_keys = array(
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
  foreach( $simple_repeatable_keys as $key ) {
    $handler =& xoonips_getormhandler( 'xnparticle', 'item_detail_child_'.$key );
    if ( ! is_object( $handler ) ) {
      return false;
    }
    $criteria = new Criteria( 'article_id', $item_id );
    if ( ! empty( $detail[$key] ) ) {
      $criteria->setSort( $key.'_order' );
      $criteria->setOrder( 'ASC' );
      $objs = $handler->getObjects( $criteria );
      $values = explode( "\n", $detail[$key] );
      $old_num = count( $objs );
      $new_num = count( $values );
      $loop_num = max( $old_num, $new_num );
      for ( $i = 0; $i < $loop_num; $i++ ) {
        if ( $new_num <= $i ) {
          $obj =& $objs[$i];
          $handler->delete( $obj );
          continue;
        }
        $ar = array(
          $key => $values[$i]
        );
        xnpTrimColumn( $ar, 'xnparticle_item_detail_child_'.$key );
        if ( $old_num > $i ) {
          $obj =& $objs[$i];
        } else {
          $obj =& $handler->create();
        }
        $obj->set( 'article_id', $item_id );
        $obj->set( $key, $ar[$key] );
        $obj->set( $key.'_order', $i );
        // Registration of the DetailInformation Child $key
        if ( ! $handler->insert( $obj ) ) {
          var_dump( $obj ); exit();
          echo "cannot insert item_detail_child_".$key;
          return false;
        }
      }
    } else {
      $handler->deleteAll( $criteria );
    }
  }
  return true;
}

function xnparticleGetDetailInformationQuickSearchQuery(&$wheres, &$join, $keywords){
	global $xoopsDB;
	$article_table = $xoopsDB->prefix('xnparticle_item_detail');
	//$article_child_sub_title_table = $xoopsDB->prefix('xnparticle_item_detail_child_sub_title');
	$article_child_author_table = $xoopsDB->prefix('xnparticle_item_detail_child_author');
	$file_table  = $xoopsDB->prefix('xoonips_file');
	
        //$join  .= " left join $article_child_sub_title_table on $article_child_sub_title_table.article_id = $article_table.article_id ";
        $join  .= " left join $article_child_author_table on $article_child_author_table.article_id = $article_table.article_id ";
	//$wheres = xnpGetKeywordsQueries( array("$article_child_sub_title_table.sub_title_name", "$article_child_author_table.author_name", "$article_child_author_table.author_role", "$article_child_author_table.author_affiliation", "$article_table.publisher", "$article_table.abstract","$article_table.table_of_contents","$article_table.jtitle"), $keywords );
        $wheres = xnpGetKeywordsQueries( array("$article_child_author_table.author_name", "$article_child_author_table.author_role", "$article_child_author_table.author_affiliation", "$article_table.publisher","$article_table.jtitle"), $keywords );
	return true;
}


function xnparticleGetAdvancedSearchQuery(&$where, &$join){
	global $xoopsDB;
	$article_table = $xoopsDB->prefix('xnparticle_item_detail');
	//$article_child_sub_title_table = $xoopsDB->prefix('xnparticle_item_detail_child_sub_title');
	$article_child_author_table = $xoopsDB->prefix('xnparticle_item_detail_child_author');
	$article_child_keyword_table = $xoopsDB->prefix('xnparticle_item_detail_child_keywords');
	//$article_child_ndc_classification_table = $xoopsDB->prefix('xnparticle_item_detail_child_ndc_classifications');
	//$article_child_physical_description_table = $xoopsDB->prefix('xnparticle_item_detail_child_physical_descriptions');
	//$article_child_lang_table = $xoopsDB->prefix('xnparticle_item_detail_child_langs');
	//$article_child_id_issn_table = $xoopsDB->prefix('xnparticle_item_detail_child_id_issns');
	//$article_child_id_isbn_table = $xoopsDB->prefix('xnparticle_item_detail_child_id_isbns');
	//$article_child_id_doi_table = $xoopsDB->prefix('xnparticle_item_detail_child_id_dois');
	//$article_child_id_uri_table = $xoopsDB->prefix('xnparticle_item_detail_child_id_uris');
	//$article_child_id_local_table = $xoopsDB->prefix('xnparticle_item_detail_child_id_locals');
	$file_table  = $xoopsDB->prefix('xoonips_file');
	
	$wheres = array();
	$w = xnpGetBasicInformationAdvancedSearchQuery('xnparticle');                  if ( $w ) $wheres[] = $w;
	//$w = xnpGetKeywordQuery($article_child_sub_title_table.'.sub_title_name'              ,'xnparticle_sub_title_name'   );  if ( $w ) $wheres[] = $w;
	//$w = xnpGetKeywordQuery($article_child_sub_title_table.'.sub_title_kana'              ,'xnparticle_sub_title_kana'   );  if ( $w ) $wheres[] = $w;
	//$w = xnpGetKeywordQuery($article_child_sub_title_table.'.sub_title_romaji'            ,'xnparticle_sub_title_romaji'   );  if ( $w ) $wheres[] = $w;
	$w = xnpGetKeywordQuery($article_child_author_table.'.author_id'                    ,'xnparticle_author_id'   );  if ( $w ) $wheres[] = $w;
	$w = xnpGetKeywordQuery($article_child_author_table.'.author_name'                    ,'xnparticle_author_name'   );  if ( $w ) $wheres[] = $w;
	$w = xnpGetKeywordQuery($article_child_author_table.'.author_kana'                    ,'xnparticle_author_kana'   );  if ( $w ) $wheres[] = $w;
	$w = xnpGetKeywordQuery($article_child_author_table.'.author_romaji'                  ,'xnparticle_author_romaji'   );  if ( $w ) $wheres[] = $w;
	$w = xnpGetKeywordQuery($article_child_author_table.'.author_affiliation'             ,'xnparticle_author_affiliation'   );  if ( $w ) $wheres[] = $w;
	$w = xnpGetKeywordQuery($article_child_author_table.'.author_affiliation_translation' ,'xnparticle_author_affiliation_translation'   );  if ( $w ) $wheres[] = $w;
	$w = xnpGetKeywordQuery($article_child_author_table.'.author_role'                    ,'xnparticle_author_role'   );  if ( $w ) $wheres[] = $w;
	$w = xnpGetKeywordQuery($article_child_author_table.'.author_link'                    ,'xnparticle_author_link'   );  if ( $w ) $wheres[] = $w;
	$w = xnpGetKeywordQuery($article_table.'.publisher'            ,'xnparticle_publisher'  );  if ( $w ) $wheres[] = $w;
	$w = xnpGetKeywordQuery($article_table.'.publisher_kana'       ,'xnparticle_publisher_kana'  );  if ( $w ) $wheres[] = $w;
	$w = xnpGetKeywordQuery($article_table.'.publisher_romaji'     ,'xnparticle_publisher_romaji'  );  if ( $w ) $wheres[] = $w;
	//$w = xnpGetKeywordQuery($article_table.'.year_f'     ,'xnparticle_year_f'  );  if ( $w ) $wheres[] = $w;
	//$w = xnpGetKeywordQuery($article_table.'.year_t'     ,'xnparticle_year_t'  );  if ( $w ) $wheres[] = $w;
	//$w = xnpGetKeywordQuery($article_table.'.date_create'     ,'xnparticle_date_create'  );  if ( $w ) $wheres[] = $w;
	//$w = xnpGetKeywordQuery($article_table.'.date_update'     ,'xnparticle_date_update'  );  if ( $w ) $wheres[] = $w;
	//$w = xnpGetKeywordQuery($article_table.'.date_record'     ,'xnparticle_date_record'  );  if ( $w ) $wheres[] = $w;
	$w = xnpGetKeywordQuery($article_table.'.jtitle'     ,'xnparticle_jtitle'  );  if ( $w ) $wheres[] = $w;
	$w = xnpGetKeywordQuery($article_table.'.jtitle_translation'     ,'xnparticle_jtitle_translation'  );  if ( $w ) $wheres[] = $w;
	$w = xnpGetKeywordQuery($article_table.'.jtitle_volume'     ,'xnparticle_jtitle_volume'  );  if ( $w ) $wheres[] = $w;
	$w = xnpGetKeywordQuery($article_table.'.jtitle_issue'     ,'xnparticle_jtitle_issue'  );  if ( $w ) $wheres[] = $w;
	$w = xnpGetKeywordQuery($article_table.'.jtitle_year'     ,'xnparticle_jtitle_year'  );  if ( $w ) $wheres[] = $w;
	$w = xnpGetKeywordQuery($article_table.'.jtitle_month'     ,'xnparticle_jtitle_month'  );  if ( $w ) $wheres[] = $w;
	$w = xnpGetKeywordQuery($article_table.'.jtitle_spage'     ,'xnparticle_jtitle_spage'  );  if ( $w ) $wheres[] = $w;
	$w = xnpGetKeywordQuery($article_table.'.jtitle_epage'     ,'xnparticle_jtitle_epage'  );  if ( $w ) $wheres[] = $w;
	$w = xnpGetKeywordQuery($article_table.'.abstract'     		,'xnparticle_abstract'   );  if ( $w ) $wheres[] = $w;
	//$w = xnpGetKeywordQuery($article_table.'.table_of_contents'     	,'xnparticle_table_of_contents'   );  if ( $w ) $wheres[] = $w;
	//$w = xnpGetKeywordQuery($article_table.'.edition'     		,'xnparticle_edition'   );  if ( $w ) $wheres[] = $w;
	$w = xnpGetKeywordQuery($article_table.'.publish_place'     		,'xnparticle_publish_place'   );  if ( $w ) $wheres[] = $w;
	//$w = xnpGetKeywordQuery($article_table.'.type_of_resource'     ,'xnparticle_type_of_resource'     );  if ( $w ) $wheres[] = $w;
	//$w = xnpGetKeywordQuery($article_table.'.genre'      			,'xnparticle_genre'   );  if ( $w ) $wheres[] = $w;
	//$w = xnpGetKeywordQuery($article_table.'.access_condition'     	,'xnparticle_access_condition'   );  if ( $w ) $wheres[] = $w;
	//$w = xnpGetKeywordQuery($article_table.'.self_doi'     ,'xnparticle_self_doi'  );  if ( $w ) $wheres[] = $w;
	//$w = xnpGetKeywordQuery($article_table.'.naid'     ,'xnparticle_naid'  );  if ( $w ) $wheres[] = $w;
	//$w = xnpGetKeywordQuery($article_table.'.ichushi'     ,'xnparticle_ichushi'  );  if ( $w ) $wheres[] = $w;
	//$w = xnpGetKeywordQuery($article_table.'.textversion'      			,'xnparticle_textversion'   );  if ( $w ) $wheres[] = $w;
	//$w = xnpGetKeywordQuery($article_table.'.grant_id'     ,'xnparticle_grant_id'  );  if ( $w ) $wheres[] = $w;
	//$w = xnpGetKeywordQuery($article_table.'.date_of_granted'     ,'xnparticle_date_of_granted'  );  if ( $w ) $wheres[] = $w;
	//$w = xnpGetKeywordQuery($article_table.'.degree_name'     ,'xnparticle_degree_name'  );  if ( $w ) $wheres[] = $w;
	//$w = xnpGetKeywordQuery($article_table.'.grantor'     ,'xnparticle_grantor'  );  if ( $w ) $wheres[] = $w;
	$w = xnpGetKeywordQuery($article_child_keyword_table.'.keywords'     	,'xnparticle_keywords'   );  if ( $w ) $wheres[] = $w;
	//$w = xnpGetKeywordQuery($article_child_ndc_classification_table.'.ndc_classifications'     	,'xnparticle_ndc_classifications'   );  if ( $w ) $wheres[] = $w;
	//$w = xnpGetKeywordQuery($article_child_physical_description_table.'.physical_descriptions'     	,'xnparticle_physical_descriptions'   );  if ( $w ) $wheres[] = $w;
	//$w = xnpGetKeywordQuery($article_child_lang_table.'.langs'     	,'xnparticle_langs'   );  if ( $w ) $wheres[] = $w;
	//$w = xnpGetKeywordQuery($article_child_id_issn_table.'.id_issns'     	,'xnparticle_id_issns'   );  if ( $w ) $wheres[] = $w;
	//$w = xnpGetKeywordQuery($article_child_id_isbn_table.'.id_isbns'     	,'xnparticle_id_isbns'   );  if ( $w ) $wheres[] = $w;
	//$w = xnpGetKeywordQuery($article_child_id_doi_table.'.id_dois'     	,'xnparticle_id_dois'   );  if ( $w ) $wheres[] = $w;
	//$w = xnpGetKeywordQuery($article_child_id_uri_table.'.id_uris'     	,'xnparticle_id_uris'   );  if ( $w ) $wheres[] = $w;
	//$w = xnpGetKeywordQuery($article_child_id_local_table.'.id_locals'     	,'xnparticle_id_locals'   );  if ( $w ) $wheres[] = $w;
	
	$w = xnpGetKeywordQuery( $file_table.'.caption', 'xnparticle_caption' );
	if( $w ){
		$wheres[] = $w;
		$wheres[] = " $file_table.file_type_id = 1";
	}
	if ( !empty($_POST['xnparticle_article_attachment' ]) ){
		list( $pattern, $errorMessage ) = xnpKeywordsToFulltextSql($_POST['xnparticle_article_attachment']);
		if ( !$errorMessage )
			$wheres[] = "match ( $file_table.search_text ) against ( '$pattern' in boolean mode )";
	}

	$where = implode( ' and ', $wheres );
        //$join  = " left join $article_child_sub_title_table on $article_child_sub_title_table.article_id = $article_table.article_id ";
        $join  .= " left join $article_child_author_table on $article_child_author_table.article_id = $article_table.article_id ";
        $join  .= " left join $article_child_keyword_table on $article_child_keyword_table.article_id = $article_table.article_id ";
        //$join  .= " left join $article_child_ndc_classification_table on $article_child_ndc_classification_table.article_id = $article_table.article_id ";
        //$join  .= " left join $article_child_physical_description_table on $article_child_physical_description_table.article_id = $article_table.article_id ";
        //$join  .= " left join $article_child_lang_table on $article_child_lang_table.article_id = $article_table.article_id ";
        //$join  .= " left join $article_child_id_issn_table on $article_child_id_issn_table.article_id = $article_table.article_id ";
        //$join  .= " left join $article_child_id_isbn_table on $article_child_id_isbn_table.article_id = $article_table.article_id ";
        //$join  .= " left join $article_child_id_doi_table on $article_child_id_doi_table.article_id = $article_table.article_id ";
        //$join  .= " left join $article_child_id_uri_table on $article_child_id_uri_table.article_id = $article_table.article_id ";
        //$join  .= " left join $article_child_id_local_table on $article_child_id_local_table.article_id = $article_table.article_id ";
}

function xnparticleGetAdvancedSearchBlock(&$search_var){
	// Get blocks of "BasicInformation / Preview / IndexKeywords"
	$basic   = xnpGetBasicInformationAdvancedSearchBlock('xnparticle',$search_var);
	$search_var[] = 'xnparticle_sub_title_name'   ;
	$search_var[] = 'xnparticle_sub_title_kana'   ;
	$search_var[] = 'xnparticle_sub_title_romaji'   ;
	$search_var[] = 'xnparticle_author_id'   ;
	$search_var[] = 'xnparticle_author_name'   ;
	$search_var[] = 'xnparticle_author_kana'   ;
	$search_var[] = 'xnparticle_author_romaji'   ;
	$search_var[] = 'xnparticle_author_affiliation'   ;
	$search_var[] = 'xnparticle_author_affiliation_translation'   ;
	$search_var[] = 'xnparticle_author_role'   ;
	$search_var[] = 'xnparticle_author_link'   ;
	$search_var[] = 'xnparticle_publisher'  ;
	$search_var[] = 'xnparticle_publisher_kana'  ;
	$search_var[] = 'xnparticle_publisher_romaji'  ;
	$search_var[] = 'xnparticle_year_f'  ;
	$search_var[] = 'xnparticle_year_t'  ;
	$search_var[] = 'xnparticle_date_create'  ;
	$search_var[] = 'xnparticle_date_update'  ;
	$search_var[] = 'xnparticle_date_record'  ;
	$search_var[] = 'xnparticle_jtitle'  ;
	$search_var[] = 'xnparticle_jtitle_translation'  ;
	$search_var[] = 'xnparticle_jtitle_volume'  ;
	$search_var[] = 'xnparticle_jtitle_issue'  ;
	$search_var[] = 'xnparticle_jtitle_year'  ;
	$search_var[] = 'xnparticle_jtitle_month'  ;
	$search_var[] = 'xnparticle_jtitle_spage'  ;
	$search_var[] = 'xnparticle_jtitle_epage'  ;
	$search_var[] = 'xnparticle_abstract';
	$search_var[] = 'xnparticle_table_of_contents';
	$search_var[] = 'xnparticle_edition';
	$search_var[] = 'xnparticle_publish_place';
	$search_var[] = 'xnparticle_type_of_resource';
	$search_var[] = 'xnparticle_genre';
	$search_var[] = 'xnparticle_access_condition';
	$search_var[] = 'xnparticle_self_doi'  ;
	$search_var[] = 'xnparticle_naid'  ;
	$search_var[] = 'xnparticle_ichushi'  ;
	$search_var[] = 'xnparticle_textversion';
	$search_var[] = 'xnparticle_grant_id'  ;
	$search_var[] = 'xnparticle_date_of_grated'  ;
	$search_var[] = 'xnparticle_degree_name'  ;
	$search_var[] = 'xnparticle_grantor'  ;
	$search_var[] = 'xnparticle_article_attachment';
	$search_var[] = 'xnparticle_caption';
	
	// Set into the template
	global $xoopsTpl;
	$tpl = new xoopsTpl();
	$tpl->assign( $xoopsTpl->get_template_vars() ); // xoopsTplにセットされた変数($xoops_urlとか)を、$tplにコピーする。
	
	$tpl->assign( 'basic', $basic );
	$tpl->assign( 'module_name', 'xnparticle' );
	$tpl->assign( 'module_display_name', 'Article' ); // Get from "todo:db"
	
	// Return as HTML
	return $tpl->fetch( "db:xnparticle_search_block.html" );
}

function xnparticleGetDetailInformationTotalSize($iids){
	return xnpGetTotalFileSize($iids);
}

/**
 * 
 * アイテムのDetailInformatinoをExportするXMLを作成する
 * 
 * @param fhdl 結果を書き出すファイルハンドル
 * @param item_id ExportしたいアイテムのID
 * @param attachment 添付ファイル・画像ファイルをExportするときtrue．未指定時false．
 * @return true:成功，false:失敗
 */
function xnparticleExportItem($export_path, $fhdl, $item_id, $attachment )
{
    if( !$fhdl ) return false;
    $textutil =& xoonips_getutility( 'text' );

    $detail = xnparticleGetDetailInformation( $item_id );
    $detail_child_sub_title = xnparticleGetDetailChildSubTitleInformation( $item_id );
    $detail_child_author    = xnparticleGetDetailChildAuthorInformation( $item_id );
    $detail_child_keywords  = xnparticleGetDetailChildKeywordsInformation( $item_id );
    $detail_child_ndc_classifications    = xnparticleGetDetailChildNdcClassificationsInformation( $item_id );
    $detail_child_physical_descriptions  = xnparticleGetDetailChildPhysicalDescriptionsInformation( $item_id );
    $detail_child_langs     = xnparticleGetDetailChildLangsInformation( $item_id );
    $detail_child_id_issns  = xnparticleGetDetailChildIdIssnsInformation( $item_id );
    $detail_child_id_isbns  = xnparticleGetDetailChildIdIsbnsInformation( $item_id );
    $detail_child_id_dois   = xnparticleGetDetailChildIdDoisInformation( $item_id );
    $detail_child_id_uris   = xnparticleGetDetailChildIdUrisInformation( $item_id );
    $detail_child_id_locals = xnparticleGetDetailChildIdLocalsInformation( $item_id );
    $detail_child_uris = xnparticleGetDetailChildUrisInformation( $item_id );

    $detail['sub_title_str'] = xnparticleCreateModsXml($detail, $detail_child_sub_title, $detail_child_author, $detail_child_keywords, $detail_child_ndc_classifications, $detail_child_physical_descriptions, $detail_child_langs, $detail_child_id_issns, $detail_child_id_isbns, $detail_child_id_dois, $detail_child_id_uris, $detail_child_id_locals, $detail_child_uris);
   
	$detail['author_str'] = '';
	while ( list( $key, list( $article_child_author_id, $article_id, $author_id, $author_name, $author_kana, $author_romaji, $author_affiliation, $author_affiliation_translation, $author_role, $author_link, $author_order ) ) = each( $detail_child_author ) ){
		$detail['author_str'] .= "<author>\n<author_id>".$textutil->html_special_chars( $author_id )."</author_id>\n<author_name>".$textutil->html_special_chars( $author_name )."</author_name>\n<author_kana>".$textutil->html_special_chars( $author_kana )."</author_kana>\n<author_romaji>".$textutil->html_special_chars( $author_romaji )."</author_romaji>\n<author_affiliation>".$textutil->html_special_chars( $author_affiliation )."</author_affiliation>\n<author_affiliation_translation>".$textutil->html_special_chars( $author_affiliation_translation )."</author_affiliation_translation>\n<author_role>".$textutil->html_special_chars( $author_role )."</author_role>\n<author_link>".$textutil->html_special_chars( $author_link )."</author_link>\n</author>\n";
	}
	$detail['keywords_str'] = "";
        while( list( $key, list( $article_child_keywords_id, $article_id, $keywords, $keywords_order ) ) = each( $detail_child_keywords ) ){
		$detail['keywords_str'] .= "<keyword>".$textutil->html_special_chars( $keywords )."</keyword>\n";
	}
	$detail['ndc_classifications_str'] = "";
	while ( list( $key, list( $article_child_ndc_classifications_id, $article_id, $ndc_classifications, $ndc_classifications_order ) ) = each( $detail_child_ndc_classifications ) ){
		$detail['ndc_classifications_str'] .= "<ndc_classification>".$textutil->html_special_chars( $ndc_classifications )."</ndc_classification>\n";
	}
	$detail['physical_descriptions_str'] = "";
	while ( list( $key, list( $article_child_physical_descriptions_id, $article_id, $physical_descriptions, $physical_descriptions_order ) ) = each( $detail_child_physical_descriptions ) ){
		$detail['physical_descriptions_str'] .= "<physical_description>".$textutil->html_special_chars( $physical_descriptions )."</physical_description>\n";
	}
	$detail['langs_str'] = "";
	while ( list( $key, list( $article_child_langs_id, $article_id, $langs, $langs_order ) ) = each( $detail_child_langs ) ){
		$detail['langs_str'] .= "<lang>".$textutil->html_special_chars( $langs )."</lang>\n";
	}
	$detail['id_issns_str'] = "";
	while ( list( $key, list( $article_child_id_issns_id, $article_id, $id_issns, $id_issns_order ) ) = each( $detail_child_id_issns ) ){
		$detail['id_issns_str'] .= "<id_issn>".$textutil->html_special_chars( $id_issns )."</id_issn>\n";
	}
	$detail['id_isbns_str'] = "";
	while ( list( $key, list( $article_child_id_isbns_id, $article_id, $id_isbns, $id_isbns_order ) ) = each( $detail_child_id_isbns ) ){
		$detail['id_isbns_str'] .= "<id_isbn>".$textutil->html_special_chars( $id_isbns )."</id_isbn>\n";
	}
	$detail['id_dois_str'] = "";
	while ( list( $key, list( $article_child_id_dois_id, $article_id, $id_dois, $id_dois_order ) ) = each( $detail_child_id_dois ) ){
		$detail['id_dois_str'] .= "<id_doi>".$textutil->html_special_chars( $id_dois )."</id_doi>\n";
	}
	$detail['id_uris_str'] = "";
	while ( list( $key, list( $article_child_id_uris_id, $article_id, $id_uris, $id_uris_order ) ) = each( $detail_child_id_uris ) ){
		$detail['id_uris_str'] .= "<id_uri>".$textutil->html_special_chars( $id_uris )."</id_uri>\n";
	}
	$detail['id_locals_str'] = "";
	while ( list( $key, list( $article_child_id_locals_id, $article_id, $id_locals, $id_locals_order ) ) = each( $detail_child_id_locals ) ){
		$detail['id_locals_str'] .= "<id_local>".$textutil->html_special_chars( $id_locals )."</id_local>\n";
	}
	$detail['uris_str'] = "";
	while ( list( $key, list( $article_child_uris_id, $article_id, $uris, $uris_order ) ) = each( $detail_child_uris ) ){
		$detail['uris_str'] .= "<uri>".$textutil->html_special_chars( $uris )."</uri>\n";
	}

	if( !fwrite( $fhdl, "<detail id=\"${item_id}\">\n"
        ."<title>".$textutil->html_special_chars( $detail['title'] )."</title>\n"
        ."<title_kana>".$textutil->html_special_chars( $detail['title_kana'] )."</title_kana>\n"
        ."<title_romaji>".$textutil->html_special_chars( $detail['title_romaji'] )."</title_romaji>\n"
        .$detail['sub_title_str']
        .$detail['author_str']
        ."<edition>".$textutil->html_special_chars( $detail['edition'] )."</edition>\n"
        ."<publish_place>".$textutil->html_special_chars( $detail['publish_place'] )."</publish_place>\n"
        ."<publisher>".$textutil->html_special_chars( $detail['publisher'] )."</publisher>\n"
        ."<publisher_kana>".$textutil->html_special_chars( $detail['publisher_kana'] )."</publisher_kana>\n"
        ."<publisher_romaji>".$textutil->html_special_chars( $detail['publisher_romaji'] )."</publisher_romaji>\n"
        ."<year_f>".$textutil->html_special_chars( $detail['year_f'] )."</year_f>\n"
        ."<year_t>".$textutil->html_special_chars( $detail['year_t'] )."</year_t>\n"
        ."<date_create>".$textutil->html_special_chars( $detail['date_create'] )."</date_create>\n"
        ."<date_update>".$textutil->html_special_chars( $detail['date_update'] )."</date_update>\n"
        ."<date_record>".$textutil->html_special_chars( $detail['date_record'] )."</date_record>\n"
        ."<jtitle>".$textutil->html_special_chars( $detail['jtitle'] )."</jtitle>\n"
        ."<jtitle_translation>".$textutil->html_special_chars( $detail['jtitle_translation'] )."</jtitle_translation>\n"
        ."<jtitle_volume>".$textutil->html_special_chars( $detail['jtitle_volume'] )."</jtitle_volume>\n"
        ."<jtitle_issue>".$textutil->html_special_chars( $detail['jtitle_issue'] )."</jtitle_issue>\n"
        ."<jtitle_year>".$textutil->html_special_chars( $detail['jtitle_year'] )."</jtitle_year>\n"
        ."<jtitle_month>".$textutil->html_special_chars( $detail['jtitle_month'] )."</jtitle_month>\n"
        ."<jtitle_spage>".$textutil->html_special_chars( $detail['jtitle_spage'] )."</jtitle_spage>\n"
        ."<jtitle_epage>".$textutil->html_special_chars( $detail['jtitle_epage'] )."</jtitle_epage>\n"
        ."<abstract>".$textutil->html_special_chars( $detail['abstract'] )."</abstract>\n"
        ."<table_of_contents>".$textutil->html_special_chars( $detail['table_of_contents'] )."</table_of_contents>\n"
        ."<keywords>\n".$detail['keywords_str']."</keywords>\n"
        ."<ndc_classifications>\n".$detail['ndc_classifications_str']."</ndc_classifications>\n"
        ."<physical_descriptions>\n".$detail['physical_descriptions_str']."</physical_descriptions>\n"
        ."<langs>\n".$detail['langs_str']."</langs>\n"
        ."<id_issns>\n".$detail['id_issns_str']."</id_issns>\n"
        ."<id_isbns>\n".$detail['id_isbns_str']."</id_isbns>\n"
        ."<id_dois>\n".$detail['id_dois_str']."</id_dois>\n"
        ."<id_uris>\n".$detail['id_uris_str']."</id_uris>\n"
        ."<id_locals>\n".$detail['id_locals_str']."</id_locals>\n"
        ."<uris>\n".$detail['uris_str']."</uris>\n"
        ."<type_of_resource>".$textutil->html_special_chars( $detail['type_of_resource'] )."</type_of_resource>\n"
        ."<genre>".$textutil->html_special_chars( $detail['genre'] )."</genre>\n"
        ."<access_condition>".$textutil->html_special_chars( $detail['access_condition'] )."</access_condition>\n"
        ."<self_doi>".$textutil->html_special_chars( $detail['self_doi'] )."</self_doi>\n"
        ."<naid>".$textutil->html_special_chars( $detail['naid'] )."</naid>\n"
        ."<ichushi>".$textutil->html_special_chars( $detail['ichushi'] )."</ichushi>\n"
        ."<textversion>".$textutil->html_special_chars( $detail['textversion'] )."</textversion>\n"
        ."<grant_id>".$textutil->html_special_chars( $detail['grant_id'] )."</grant_id>\n"
        ."<date_of_granted>".$textutil->html_special_chars( $detail['date_of_granted'] )."</date_of_granted>\n"
        ."<degree_name>".$textutil->html_special_chars( $detail['degree_name'] )."</degree_name>\n"
        ."<grantor>".$textutil->html_special_chars( $detail['grantor'] )."</grantor>\n"
 ) ) return false;
    if( !( $attachment ? xnpExportFile( $export_path, $fhdl, $item_id ) : true ) ) return false;
    if( !fwrite( $fhdl, "</detail>\n" ) ) return false;

    return true;
}


function xnparticleGetModifiedFields( $item_id )
{
    $ret = array();
    $basic = xnpGetBasicInformationArray($item_id);
    $detail = xnparticleGetDetailInformation( $item_id );
    if( $detail ){
        foreach( array( 'title'			  =>_MD_XNPARTICLE_TITLE_LABEL." "._MD_XOONIPS_ITEM_TITLE_LABEL,
                        'title_kana'		  =>_MD_XNPARTICLE_TITLE_LABEL." "._MD_XNPARTICLE_KANA_LABEL,
                        'title_romaji'		  =>_MD_XNPARTICLE_TITLE_LABEL." "._MD_XNPARTICLE_ROMAJI_LABEL,
                        'publisher'		  =>_MD_XNPARTICLE_PUBLISHER_LABEL,
                        'publisher_kana'          =>_MD_XNPARTICLE_PUBLISHER_LABEL." "._MD_XNPARTICLE_KANA_LABEL,
                        'publisher_romaji'        =>_MD_XNPARTICLE_PUBLISHER_LABEL." "._MD_XNPARTICLE_ROMAJI_LABEL,
                        'year_f'                  =>_MD_XNPARTICLE_PUBLISH_YEAR_LABEL,
                        'year_t'                  =>_MD_XNPARTICLE_PUBLISH_YEAR_LABEL,
                        'date_create'             =>_MD_XNPARTICLE_DATE_CREATE_LABEL,
                        'date_update'             =>_MD_XNPARTICLE_DATE_UPDATE_LABEL,
                        'date_record'             =>_MD_XNPARTICLE_DATE_RECORD_LABEL,
                        'jtitle'                  =>_MD_XNPARTICLE_JTITLE_LABEL,
                        'jtitle_translation'      =>_MD_XNPARTICLE_TRANSLATION_LABEL,
                        'jtitle_volume'           =>_MD_XNPARTICLE_JTITLE_VOLUME_LABEL,
                        'jtitle_issue'            =>_MD_XNPARTICLE_JTITLE_ISSUE_LABEL,
                        'jtitle_year'             =>_MD_XNPARTICLE_JTITLE_YEAR_LABEL,
                        'jtitle_month'            =>_MD_XNPARTICLE_JTITLE_MONTH_LABEL,
                        'jtitle_spage'            =>_MD_XNPARTICLE_JTITLE_SPAGE_LABEL,
                        'jtitle_epage'            =>_MD_XNPARTICLE_JTITLE_EPAGE_LABEL,
                        'abstract'		  =>_MD_XNPARTICLE_ABSTRACT_LABEL,
                        'table_of_contents'	  =>_MD_XNPARTICLE_TABLE_OF_CONTENTS_LABEL,
                        'edition'		  =>_MD_XNPARTICLE_EDITION_LABEL,
                        'publish_place'		  =>_MD_XNPARTICLE_PUBLISH_PLACE_LABEL,
                        'type_of_resource'	  =>_MD_XNPARTICLE_TYPE_OF_RESOURCE_LABEL,
                        'genre'			  =>_MD_XNPARTICLE_GENRE_LABEL,
                        'access_condition'	  =>_MD_XNPARTICLE_ACCESS_CONDITION_LABEL,
                        'self_doi'                =>_MD_XNPARTICLE_SELF_DOI_LABEL,
                        'naid'                    =>_MD_XNPARTICLE_NAID_LABEL,
                        'ichushi'                 =>_MD_XNPARTICLE_ICHUSHI_LABEL,
                        'textversion'		  =>_MD_XNPARTICLE_TEXTVERSION_LABEL,
                        'grant_id'                =>_MD_XNPARTICLE_GRANT_ID_LABEL,
                        'date_of_granted'         =>_MD_XNPARTICLE_DATE_OF_GRANTED_LABEL,
                        'degree_name'             =>_MD_XNPARTICLE_DEGREE_NAME_LABEL,
                        'grantor'                 =>_MD_XNPARTICLE_GRANTOR_LABEL
      ) as $k => $v ){
            if( !array_key_exists( $k, $detail )
                || !array_key_exists( $k, $_POST ) ) continue;
            if( $detail[ $k ] != $_POST[ $k ] ) array_push( $ret, $v );
        }
        
        // pdfファイルの変更があるか?
        if( xnpIsAttachmentModified( 'article_attachment', $item_id ) ){
            array_push( $ret, _MD_XNPARTICLE_ATTACHMENT_LABEL );
        }
    }

	$detail_child_sub_title = xnparticleGetDetailChildSubTitleInformation( $item_id );
	//2009.02.09 sc.ito add start
	$detail['sub_title_name']   = "";
	$detail['sub_title_kana']   = "";
	$detail['sub_title_romaji'] = "";
	$first = true;
	//2009.02.09 sc.ito add end
	$i = count($detail_child_sub_title);
	if ($i > 0) {
		while ( list( $key, list( $article_child_sub_title_id, $article_id, $sub_title_name, $sub_title_kana, $sub_title_romaji, $sub_title_order ) ) = each( $detail_child_sub_title ) ){
			//2009.02.09 sc.ito mod start ---POSTで送られる文字列形式と同じようにデータを整形
			if ( !$first ){
				$detail['sub_title_name']   .= "\n";
				$detail['sub_title_kana']   .= "\n";
				$detail['sub_title_romaji'] .= "\n";
			}
			$first = false;
			//2009.02.09 sc.ito mod end
			$detail['sub_title_name']   .= ( !empty( $sub_title_name ) )   ? $sub_title_name   : " ";
			$detail['sub_title_kana']   .= ( !empty( $sub_title_kana ) )   ? $sub_title_kana   : " ";
			$detail['sub_title_romaji'] .= ( !empty( $sub_title_romaji ) ) ? $sub_title_romaji : " ";
		}
		$flg = 0;
        foreach( array( 'sub_title_name'				    =>_MD_XNPARTICLE_SUB_TITLE_LABEL,
                        'sub_title_kana'				    =>_MD_XNPARTICLE_SUB_TITLE_LABEL,
                        'sub_title_romaji'				    =>_MD_XNPARTICLE_SUB_TITLE_LABEL) as $k => $v ){
            if( !array_key_exists( $k, $detail )
                || !array_key_exists( $k, $_POST ) ) continue;
            //2009.02.09 sc.ito add start ---改行コードを'\n'に統一
            $post_tmp = str_replace("\r\n","\n",$_POST[ $k ]);
            $post_tmp = str_replace("\r","\n",$post_tmp);
            $nospace_detail = str_replace(" ","",$detail[ $k ]);
            //2009.02.09 sc.ito add end
            //2009.02.09 sc.ito mod start ---改行コードを'\n'に統一して比較
            if( $detail[ $k ] != $post_tmp && $nospace_detail != $post_tmp ) {
            //2009.02.09 sc.ito mod end
				if ($flg == 0) array_push( $ret, $v );
				$flg = 1;
			}
    	}
   	}
   	//2009.02.09 sc.ito add start ---DBに登録がない場合でもPOSTに該当フィールドの入力があれば変更ありとする
	else if ($i == 0) {
		$flg = 0;
		foreach( array( 'sub_title_name'				    =>_MD_XNPARTICLE_SUB_TITLE_LABEL,
                        'sub_title_kana'				    =>_MD_XNPARTICLE_SUB_TITLE_LABEL,
                        'sub_title_romaji'				    =>_MD_XNPARTICLE_SUB_TITLE_LABEL) as $k => $v ){
			if( !array_key_exists( $k, $_POST )|| strlen($_POST[$k])==0 ) continue;
			if ($flg == 0) array_push( $ret, $v );
				$flg = 1;
		}
	}
	// 2009.02.09 sc.ito add end

	$detail_child_author = xnparticleGetDetailChildAuthorInformation( $item_id );
	//2009.02.09 sc.ito add start
	$detail['author_id']                      = "";
	$detail['author_name']                    = "";
	$detail['author_kana']                    = "";
	$detail['author_romaji']                  = "";
	$detail['author_affiliation']             = "";
	$detail['author_affiliation_translation'] = "";
	$detail['author_role']                    = "";
	$detail['author_link']                    = "";
	$first = true;
	//2009.02.09 sc.ito add end
	$i = count($detail_child_author);
	if ($i > 0) {
		while ( list( $key, list( $article_child_author_id, $article_id, $author_id, $author_name, $author_kana, $author_romaji, $author_affiliation, $author_affiliation_translation, $author_role, $author_link, $author_order ) ) = each( $detail_child_author ) ){
			//2009.02.09 sc.ito mod start ---POSTで送られる文字列形式と同じようにデータを整形
			if ( !$first ){
				$detail['author_id']                      .= "\n";
				$detail['author_name']                    .= "\n";
				$detail['author_kana']                    .= "\n";
				$detail['author_romaji']                  .= "\n";
				$detail['author_affiliation']             .= "\n";
				$detail['author_affiliation_translation'] .= "\n";
				$detail['author_role']                    .= "\n";
				$detail['author_link']                    .= "\n";
			}
			$first = false;
			//2009.02.09 sc.ito mod end
			$detail['author_id']   .= ( !empty( $author_id ) )   ? $author_id : " ";
			$detail['author_name'] .= ( !empty( $author_name ) ) ? $author_name : " ";
			$detail['author_kana'] .= ( !empty( $author_kana ) ) ? $author_kana : " ";
			$detail['author_romaji'] .= ( !empty( $author_romaji ) ) ? $author_romaji : " ";
			$detail['author_affiliation'] .= ( !empty( $author_affiliation ) ) ? $author_affiliation : " ";
			$detail['author_affiliation_translation'] .= ( !empty( $author_affiliation_translation ) ) ? $author_affiliation_translation : " ";
			$detail['author_role'] .= ( !empty( $author_role ) ) ? $author_role : " ";
			$detail['author_link'] .= ( !empty( $author_link ) ) ? $author_link : " ";
		}
		$flg = 0;
        foreach( array( 'author_id'				    =>_MD_XNPARTICLE_AUTHOR_LABEL,
                        'author_name'				    =>_MD_XNPARTICLE_AUTHOR_LABEL,
                        'author_kana'				    =>_MD_XNPARTICLE_AUTHOR_LABEL,
                        'author_romaji'				    =>_MD_XNPARTICLE_AUTHOR_LABEL,
                        'author_affiliation'		    =>_MD_XNPARTICLE_AUTHOR_LABEL,
                        'author_affiliation_translation'    =>_MD_XNPARTICLE_AUTHOR_LABEL,
                        'author_role'				    =>_MD_XNPARTICLE_AUTHOR_LABEL,
                        'author_link'=>_MD_XNPARTICLE_AUTHOR_LABEL ) as $k => $v ){
            if( !array_key_exists( $k, $detail )
                || !array_key_exists( $k, $_POST ) ) continue;
            //2009.02.09 sc.ito add start ---改行コードを'\n'に統一
            $post_tmp = str_replace("\r\n","\n",$_POST[ $k ]);
            $post_tmp = str_replace("\r","\n",$post_tmp);
            $nospace_detail = str_replace(" ","",$detail[ $k ]);
            //2009.02.09 sc.ito add end
            //2009.02.09 sc.ito mod start ---改行コードを'\n'に統一して比較
            if( $detail[ $k ] != $post_tmp && $nospace_detail != $post_tmp ) {
            //2009.02.09 sc.ito mod end
				if ($flg == 0) array_push( $ret, $v );
				$flg = 1;
			}
    	}
   	}
   	//2009.02.09 sc.ito add start ---DBに登録がない場合でもPOSTに該当フィールドの入力があれば変更ありとする
	else if ($i == 0) {
		$flg = 0;
		foreach( array( 'author_id'				    =>_MD_XNPARTICLE_AUTHOR_LABEL,
                        'author_name'				    =>_MD_XNPARTICLE_AUTHOR_LABEL,
                        'author_kana'				    =>_MD_XNPARTICLE_AUTHOR_LABEL,
                        'author_romaji'				    =>_MD_XNPARTICLE_AUTHOR_LABEL,
                        'author_affiliation'		    =>_MD_XNPARTICLE_AUTHOR_LABEL,
                        'author_affiliation_translation'    =>_MD_XNPARTICLE_AUTHOR_LABEL,
                        'author_role'				    =>_MD_XNPARTICLE_AUTHOR_LABEL,
                        'author_link'=>_MD_XNPARTICLE_AUTHOR_LABEL ) as $k => $v ){
			if( !array_key_exists( $k, $_POST )|| strlen($_POST[$k])==0 ) continue;
			if ($flg == 0) array_push( $ret, $v );
				$flg = 1;
		}
	}
	// 2009.02.09 sc.ito add end
   	
	$detail_child_keywords = xnparticleGetDetailChildKeywordsInformation( $item_id );
	$detail['keywords'] = "";
	$i = count($detail_child_keywords);
	if ($i > 0) {
		while ( list( $key, list( $article_child_keywords_id, $article_id, $keywords, $keywords_order ) ) = each( $detail_child_keywords ) ){
			if ( !empty( $detail['keywords'] ) ){
				$detail['keywords'] .= "\n";
			}
			$detail['keywords'] .= $keywords;
		}
		$flg = 0;
        foreach( array( 'keywords'				  =>_MD_XNPARTICLE_KEYWORDS_LABEL ) as $k => $v ){
            if( !array_key_exists( $k, $detail )
                || !array_key_exists( $k, $_POST ) ) continue;
            //2009.02.09 sc.ito add start ---改行コードを'\n'に統一
            $post_tmp = str_replace("\r\n","\n",$_POST[ $k ]);
            $post_tmp = str_replace("\r","\n",$post_tmp);
            $nospace_detail = str_replace(" ","",$detail[ $k ]);
            //2009.02.09 sc.ito add end
            //2009.02.09 sc.ito mod start ---改行コードを'\n'に統一して比較
            if( $detail[ $k ] != $post_tmp && $nospace_detail != $post_tmp ) {
            //2009.02.09 sc.ito mod end
				if ($flg == 0) array_push( $ret, $v );
				$flg = 1;
			}
	    }
    }
    //2009.02.09 sc.ito add start ---DBに登録がない場合でもPOSTに該当フィールドの入力があれば変更ありとする
	else if ($i == 0) {
		$flg = 0;
		foreach( array( 'keywords'				  =>_MD_XNPARTICLE_KEYWORDS_LABEL ) as $k => $v ){
			if( !array_key_exists( $k, $_POST )|| strlen($_POST[$k])==0 ) continue;
			if ($flg == 0) array_push( $ret, $v );
				$flg = 1;
		}
	}
	// 2009.02.09 sc.ito add end

	$detail_child_ndc_classifications = xnparticleGetDetailChildNdcClassificationsInformation( $item_id );
	$detail['ndc_classifications'] = "";
	$i = count($detail_child_ndc_classifications);
	if ($i > 0) {
		while ( list( $key, list( $article_child_ndc_classifications_id, $article_id, $ndc_classifications, $ndc_classifications_order ) ) = each( $detail_child_ndc_classifications ) ){
			if ( !empty( $detail['ndc_classifications'] ) ){
				$detail['ndc_classifications'] .= "\n";
			}
			$detail['ndc_classifications'] .= $ndc_classifications;
		}
		$flg = 0;
        foreach( array( 'ndc_classifications'				  =>_MD_XNPARTICLE_NDC_CLASSIFICATIONS_LABEL ) as $k => $v ){
            if( !array_key_exists( $k, $detail )
                || !array_key_exists( $k, $_POST ) ) continue;
            //2009.02.09 sc.ito add start ---改行コードを'\n'に統一
            $post_tmp = str_replace("\r\n","\n",$_POST[ $k ]);
            $post_tmp = str_replace("\r","\n",$post_tmp);
            $nospace_detail = str_replace(" ","",$detail[ $k ]);
            //2009.02.09 sc.ito add end
            //2009.02.09 sc.ito mod start ---改行コードを'\n'に統一して比較
            if( $detail[ $k ] != $post_tmp && $nospace_detail != $post_tmp ) {
            //2009.02.09 sc.ito mod end
				if ($flg == 0) array_push( $ret, $v );
				$flg = 1;
			}
	    }
    }
    //2009.02.09 sc.ito add start ---DBに登録がない場合でもPOSTに該当フィールドの入力があれば変更ありとする
	else if ($i == 0) {
		$flg = 0;
		foreach( array( 'ndc_classifications'				  =>_MD_XNPARTICLE_NDC_CLASSIFICATIONS_LABEL ) as $k => $v ){
			if( !array_key_exists( $k, $_POST )|| strlen($_POST[$k])==0 ) continue;
			if ($flg == 0) array_push( $ret, $v );
				$flg = 1;
		}
	}
	// 2009.02.09 sc.ito add end

	$detail_child_physical_descriptions = xnparticleGetDetailChildPhysicalDescriptionsInformation( $item_id );
	$detail['physical_descriptions'] = "";
	$i = count($detail_child_physical_descriptions);
	if ($i > 0) {
		while ( list( $key, list( $article_child_physical_descriptions_id, $article_id, $physical_descriptions, $physical_descriptions_order ) ) = each( $detail_child_physical_descriptions ) ){
			if ( !empty( $detail['physical_descriptions'] ) ){
				$detail['physical_descriptions'] .= "\n";
			}
			$detail['physical_descriptions'] .= $physical_descriptions;
		}
		$flg = 0;
        foreach( array( 'physical_descriptions'				  =>_MD_XNPARTICLE_PHYSICAL_DESCRIPTIONS_LABEL ) as $k => $v ){
            if( !array_key_exists( $k, $detail )
                || !array_key_exists( $k, $_POST ) ) continue;
            //2009.02.09 sc.ito add start ---改行コードを'\n'に統一
            $post_tmp = str_replace("\r\n","\n",$_POST[ $k ]);
            $post_tmp = str_replace("\r","\n",$post_tmp);
            $nospace_detail = str_replace(" ","",$detail[ $k ]);
            //2009.02.09 sc.ito add end
            //2009.02.09 sc.ito mod start ---改行コードを'\n'に統一して比較
            if( $detail[ $k ] != $post_tmp && $nospace_detail != $post_tmp ) {
            //2009.02.09 sc.ito mod end
				if ($flg == 0) array_push( $ret, $v );
				$flg = 1;
			}
	    }
    }
    //2009.02.09 sc.ito add start ---DBに登録がない場合でもPOSTに該当フィールドの入力があれば変更ありとする
	else if ($i == 0) {
		$flg = 0;
		foreach( array( 'physical_descriptions'				  =>_MD_XNPARTICLE_PHYSICAL_DESCRIPTIONS_LABEL ) as $k => $v ){
			if( !array_key_exists( $k, $_POST )|| strlen($_POST[$k])==0 ) continue;
			if ($flg == 0) array_push( $ret, $v );
				$flg = 1;
		}
	}
	// 2009.02.09 sc.ito add end

	$detail_child_langs = xnparticleGetDetailChildLangsInformation( $item_id );
	$detail['langs'] = "";
	$i = count($detail_child_langs);
	if ($i > 0) {
		while ( list( $key, list( $article_child_langs_id, $article_id, $langs, $langs_order ) ) = each( $detail_child_langs ) ){
			if ( !empty( $detail['langs'] ) ){
				$detail['langs'] .= "\n";
			}
			$detail['langs'] .= $langs;
		}
		$flg = 0;
        foreach( array( 'langs'				  =>_MD_XNPARTICLE_LANGS_LABEL ) as $k => $v ){
            if( !array_key_exists( $k, $detail )
                || !array_key_exists( $k, $_POST ) ) continue;
            //2009.02.09 sc.ito add start ---改行コードを'\n'に統一
            $post_tmp = str_replace("\r\n","\n",$_POST[ $k ]);
            $post_tmp = str_replace("\r","\n",$post_tmp);
            $nospace_detail = str_replace(" ","",$detail[ $k ]);
            //2009.02.09 sc.ito add end
            //2009.02.09 sc.ito mod start ---改行コードを'\n'に統一して比較
            if( $detail[ $k ] != $post_tmp && $nospace_detail != $post_tmp ) {
            //2009.02.09 sc.ito mod end
				if ($flg == 0) array_push( $ret, $v );
				$flg = 1;
			}
	    }
    }
    //2009.02.09 sc.ito add start ---DBに登録がない場合でもPOSTに該当フィールドの入力があれば変更ありとする
	else if ($i == 0) {
		$flg = 0;
		foreach( array( 'langs'				  =>_MD_XNPARTICLE_LANGS_LABEL ) as $k => $v ){
			if( !array_key_exists( $k, $_POST )|| strlen($_POST[$k])==0 ) continue;
			if ($flg == 0) array_push( $ret, $v );
				$flg = 1;
		}
	}
	// 2009.02.09 sc.ito add end

	$detail_child_id_issns = xnparticleGetDetailChildIdIssnsInformation( $item_id );
	$detail['id_issns'] = "";
	$i = count($detail_child_id_issns);
	if ($i > 0) {
		while ( list( $key, list( $article_child_id_issns_id, $article_id, $id_issns, $id_issns_order ) ) = each( $detail_child_id_issns ) ){
			if ( !empty( $detail['id_issns'] ) ){
				$detail['id_issns'] .= "\n";
			}
			$detail['id_issns'] .= $id_issns;
		}
		$flg = 0;
        foreach( array( 'id_issns'				  =>_MD_XNPARTICLE_ISSN_LABEL ) as $k => $v ){
            if( !array_key_exists( $k, $detail )
                || !array_key_exists( $k, $_POST ) ) continue;
            //2009.02.09 sc.ito add start ---改行コードを'\n'に統一
            $post_tmp = str_replace("\r\n","\n",$_POST[ $k ]);
            $post_tmp = str_replace("\r","\n",$post_tmp);
            $nospace_detail = str_replace(" ","",$detail[ $k ]);
            //2009.02.09 sc.ito add end
            //2009.02.09 sc.ito mod start ---改行コードを'\n'に統一して比較
            if( $detail[ $k ] != $post_tmp && $nospace_detail != $post_tmp ) {
            //2009.02.09 sc.ito mod end
				if ($flg == 0) array_push( $ret, $v );
				$flg = 1;
			}
	    }
    }
    //2009.02.09 sc.ito add start ---DBに登録がない場合でもPOSTに該当フィールドの入力があれば変更ありとする
	else if ($i == 0) {
		$flg = 0;
		foreach( array( 'id_issns'				  =>_MD_XNPARTICLE_ISSN_LABEL ) as $k => $v ){
			if( !array_key_exists( $k, $_POST )|| strlen($_POST[$k])==0 ) continue;
			if ($flg == 0) array_push( $ret, $v );
				$flg = 1;
		}
	}
	// 2009.02.09 sc.ito add end

	$detail_child_id_isbns = xnparticleGetDetailChildIdIsbnsInformation( $item_id );
	$detail['id_isbns'] = "";
	$i = count($detail_child_id_isbns);
	if ($i > 0) {
		while ( list( $key, list( $article_child_id_isbns_id, $article_id, $id_isbns, $id_isbns_order ) ) = each( $detail_child_id_isbns ) ){
			if ( !empty( $detail['id_isbns'] ) ){
				$detail['id_isbns'] .= "\n";
			}
			$detail['id_isbns'] .= $id_isbns;
		}
		$flg = 0;
        foreach( array( 'id_isbns'				  =>_MD_XNPARTICLE_ISBN_LABEL ) as $k => $v ){
            if( !array_key_exists( $k, $detail )
                || !array_key_exists( $k, $_POST ) ) continue;
            //2009.02.09 sc.ito add start ---改行コードを'\n'に統一
            $post_tmp = str_replace("\r\n","\n",$_POST[ $k ]);
            $post_tmp = str_replace("\r","\n",$post_tmp);
            $nospace_detail = str_replace(" ","",$detail[ $k ]);
            //2009.02.09 sc.ito add end
            //2009.02.09 sc.ito mod start ---改行コードを'\n'に統一して比較
            if( $detail[ $k ] != $post_tmp && $nospace_detail != $post_tmp ) {
            //2009.02.09 sc.ito mod end
				if ($flg == 0) array_push( $ret, $v );
				$flg = 1;
			}
	    }
    }
    //2009.02.09 sc.ito add start ---DBに登録がない場合でもPOSTに該当フィールドの入力があれば変更ありとする
	else if ($i == 0) {
		$flg = 0;
		foreach( array( 'id_isbns'				  =>_MD_XNPARTICLE_ISBN_LABEL ) as $k => $v ){
			if( !array_key_exists( $k, $_POST )|| strlen($_POST[$k])==0 ) continue;
			if ($flg == 0) array_push( $ret, $v );
				$flg = 1;
		}
	}
	// 2009.02.09 sc.ito add end

	$detail_child_id_dois = xnparticleGetDetailChildIdDoisInformation( $item_id );
	$detail['id_dois'] = "";
	$i = count($detail_child_id_dois);
	if ($i > 0) {
		while ( list( $key, list( $article_child_id_dois_id, $article_id, $id_dois, $id_dois_order ) ) = each( $detail_child_id_dois ) ){
			if ( !empty( $detail['id_dois'] ) ){
				$detail['id_dois'] .= "\n";
			}
			$detail['id_dois'] .= $id_dois;
		}
		$flg = 0;
        foreach( array( 'id_dois'				  =>_MD_XNPARTICLE_DOI_LABEL ) as $k => $v ){
            if( !array_key_exists( $k, $detail )
                || !array_key_exists( $k, $_POST ) ) continue;
            //2009.02.09 sc.ito add start ---改行コードを'\n'に統一
            $post_tmp = str_replace("\r\n","\n",$_POST[ $k ]);
            $post_tmp = str_replace("\r","\n",$post_tmp);
            $nospace_detail = str_replace(" ","",$detail[ $k ]);
            //2009.02.09 sc.ito add end
            //2009.02.09 sc.ito mod start ---改行コードを'\n'に統一して比較
            if( $detail[ $k ] != $post_tmp && $nospace_detail != $post_tmp ) {
            //2009.02.09 sc.ito mod end
				if ($flg == 0) array_push( $ret, $v );
				$flg = 1;
			}
	    }
    }
    //2009.02.09 sc.ito add start ---DBに登録がない場合でもPOSTに該当フィールドの入力があれば変更ありとする
	else if ($i == 0) {
		$flg = 0;
		foreach( array( 'id_dois'				  =>_MD_XNPARTICLE_DOI_LABEL ) as $k => $v ){
			if( !array_key_exists( $k, $_POST )|| strlen($_POST[$k])==0 ) continue;
			if ($flg == 0) array_push( $ret, $v );
				$flg = 1;
		}
	}
	// 2009.02.09 sc.ito add end

	$detail_child_id_uris = xnparticleGetDetailChildIdUrisInformation( $item_id );
	$detail['id_uris'] = "";
	$i = count($detail_child_id_uris);
	if ($i > 0) {
		while ( list( $key, list( $article_child_id_uris_id, $article_id, $id_uris, $id_uris_order ) ) = each( $detail_child_id_uris ) ){
			if ( !empty( $detail['id_uris'] ) ){
				$detail['id_uris'] .= "\n";
			}
			$detail['id_uris'] .= $id_uris;
		}
		$flg = 0;
        foreach( array( 'id_uris'				  =>_MD_XNPARTICLE_URI_LABEL ) as $k => $v ){
            if( !array_key_exists( $k, $detail )
                || !array_key_exists( $k, $_POST ) ) continue;
            //2009.02.09 sc.ito add start ---改行コードを'\n'に統一
            $post_tmp = str_replace("\r\n","\n",$_POST[ $k ]);
            $post_tmp = str_replace("\r","\n",$post_tmp);
            $nospace_detail = str_replace(" ","",$detail[ $k ]);
            //2009.02.09 sc.ito add end
            //2009.02.09 sc.ito mod start ---改行コードを'\n'に統一して比較
            if( $detail[ $k ] != $post_tmp && $nospace_detail != $post_tmp ) {
            //2009.02.09 sc.ito mod end
				if ($flg == 0) array_push( $ret, $v );
				$flg = 1;
			}
	    }
    }
    //2009.02.09 sc.ito add start ---DBに登録がない場合でもPOSTに該当フィールドの入力があれば変更ありとする
	else if ($i == 0) {
		$flg = 0;
		foreach( array( 'id_uris'				  =>_MD_XNPARTICLE_URI_LABEL ) as $k => $v ){
			if( !array_key_exists( $k, $_POST )|| strlen($_POST[$k])==0 ) continue;
			if ($flg == 0) array_push( $ret, $v );
				$flg = 1;
		}
	}
	// 2009.02.09 sc.ito add end

	$detail_child_id_locals = xnparticleGetDetailChildIdLocalsInformation( $item_id );
	$detail['id_locals'] = "";
	$i = count($detail_child_id_locals);
	if ($i > 0) {
		while ( list( $key, list( $article_child_id_locals_id, $article_id, $id_locals, $id_locals_order ) ) = each( $detail_child_id_locals ) ){
			if ( !empty( $detail['id_locals'] ) ){
				$detail['id_locals'] .= "\n";
			}
			$detail['id_locals'] .= $id_locals;
		}
		$flg = 0;
        foreach( array( 'id_locals'				  =>_MD_XNPARTICLE_OTHER_LABEL ) as $k => $v ){
            if( !array_key_exists( $k, $detail )
                || !array_key_exists( $k, $_POST ) ) continue;
            //2009.02.09 sc.ito add start ---改行コードを'\n'に統一
            $post_tmp = str_replace("\r\n","\n",$_POST[ $k ]);
            $post_tmp = str_replace("\r","\n",$post_tmp);
            $nospace_detail = str_replace(" ","",$detail[ $k ]);
            //2009.02.09 sc.ito add end
            //2009.02.09 sc.ito mod start ---改行コードを'\n'に統一して比較
            if( $detail[ $k ] != $post_tmp && $nospace_detail != $post_tmp ) {
            //2009.02.09 sc.ito mod end
				if ($flg == 0) array_push( $ret, $v );
				$flg = 1;
			}
	    }
    }
    //2009.02.09 sc.ito add start ---DBに登録がない場合でもPOSTに該当フィールドの入力があれば変更ありとする
	else if ($i == 0) {
		$flg = 0;
		foreach( array( 'id_locals'				  =>_MD_XNPARTICLE_OTHER_LABEL ) as $k => $v ){
			if( !array_key_exists( $k, $_POST )|| strlen($_POST[$k])==0 ) continue;
			if ($flg == 0) array_push( $ret, $v );
				$flg = 1;
		}
	}
	// 2009.02.09 sc.ito add end

	$detail_child_uris = xnparticleGetDetailChildUrisInformation( $item_id );
	$detail['uris'] = "";
	$i = count($detail_child_uris);
	if ($i > 0) {
		while ( list( $key, list( $article_child_uris_id, $article_id, $uris, $uris_order ) ) = each( $detail_child_uris ) ){
			if ( !empty( $detail['uris'] ) ){
				$detail['uris'] .= "\n";
			}
			$detail['uris'] .= $uris;
		}
		$flg = 0;
        foreach( array( 'uris'				  =>_MD_XNPARTICLE_URI_LABEL ) as $k => $v ){
            if( !array_key_exists( $k, $detail )
                || !array_key_exists( $k, $_POST ) ) continue;
            //2009.02.09 sc.ito add start ---改行コードを'\n'に統一
            $post_tmp = str_replace("\r\n","\n",$_POST[ $k ]);
            $post_tmp = str_replace("\r","\n",$post_tmp);
            $nospace_detail = str_replace(" ","",$detail[ $k ]);
            //2009.02.09 sc.ito add end
            //2009.02.09 sc.ito mod start ---改行コードを'\n'に統一して比較
            if( $detail[ $k ] != $post_tmp && $nospace_detail != $post_tmp ) {
            //2009.02.09 sc.ito mod end
				if ($flg == 0) array_push( $ret, $v );
				$flg = 1;
			}
	    }
    }
    //2009.02.09 sc.ito add start ---DBに登録がない場合でもPOSTに該当フィールドの入力があれば変更ありとする
	else if ($i == 0) {
		$flg = 0;
		foreach( array( 'uris'				  =>_MD_XNPARTICLE_URI_LABEL ) as $k => $v ){
			if( !array_key_exists( $k, $_POST )|| strlen($_POST[$k])==0 ) continue;
			if ($flg == 0) array_push( $ret, $v );
				$flg = 1;
		}
	}
	// 2009.02.09 sc.ito add end

    return $ret;
}

function xnparticleGetTopBlock( $itemtype ){
	return xnpGetTopBlock( $itemtype['name'], $itemtype['display_name'], 'images/icon_article.gif', _MD_XNPARTICLE_EXPLANATION, false, false );
}

function xnparticleSupportMetadataFormat( $metadataPrefix, $item_id )
{
    if( $metadataPrefix == 'oai_dc'  ||  $metadataPrefix == 'junii2' ) return true;
    return false;
}

//OAI-PMH
function xnparticleGetMetadata( $metadataPrefix, $item_id )
{
	$mydirpath = dirname(dirname(__FILE__));
	$mydirname = basename($mydirpath);
	if (!in_array($metadataPrefix, array('oai_dc', 'junii2')))
		return false;

	$meta_basic = xnpGetBasicInformationMetadata( $metadataPrefix, $item_id );
	if( $meta_basic ){
		$basic = array();
		if( xnp_get_item( $_SESSION['XNPSID'], $item_id, $basic ) != RES_OK ) return false;
		$detail = xnparticleGetDetailInformation( $item_id );

		//sub_titles
		$detail_child_sub_title = xnparticleGetDetailChildSubTitleInformation( $item_id );
		$num_sub_title = count($detail_child_sub_title);
		$sub_titles = array();
		if ($num_sub_title > 0) {
			while ( list( $key, list( $article_child_sub_title_id, $article_id, $sub_title_name, $sub_title_kana, $sub_title_romaji, $sub_title_order ) ) = each( $detail_child_sub_title ) ){
				$sub_titles[] = array(
						'article_child_sub_title_id' => $article_child_sub_title_id,
						'article_id'                 => $article_id,
						'sub_title_name'             => $sub_title_name,
						'sub_title_kana'             => $sub_title_kana,
						'sub_title_romaji'           => $sub_title_romaji,
						'sub_title_order'            => $sub_title_order
						);
			}
		}

		//creators
		$detail_child_author = xnparticleGetDetailChildAuthorInformation( $item_id );
		$num_author = count($detail_child_author);
		$creators = array();
		if ($num_author > 0) {
			while ( list( $key, list( $article_child_author_id, $article_id, $author_id, $author_name, $author_kana, $author_romaji, $author_affiliation, $author_affiliation_translation, $author_role, $author_link, $author_order ) ) = each( $detail_child_author ) ){
				$creators[] = array(
						'article_child_author_id'        => $article_child_author_id,
						'article_id'                     => $article_id,
						'author_id'                      => $author_id,
						'author_name'                    => $author_name,
						'author_kana'                    => $author_kana,
						'author_romaji'                  => $author_romaji,
						'author_affiliation'             => $author_affiliation,
						'author_affiliation_translation' => $author_affiliation_translation,
						'author_role'                    => $author_role,
						'author_link'                    => $author_link,
						'author_order'                   => $author_order
						);
			}
		}

		//subjects
		$detail_child_keywords = xnparticleGetDetailChildKeywordsInformation( $item_id );
		$num_keywords = count($detail_child_keywords);
		$subjects = array();
		if ($num_keywords > 0) {
			while ( list( $key, list( $article_child_keywords_id, $article_id, $keywords, $keywords_order ) ) = each( $detail_child_keywords) ){
				$subjects[] = array(
						'article_child_keywords_id' => $article_child_keywords_id,
						'article_id'                => $article_id,
						'keywords'                  => $keywords,
						'keywords_order'            => $keywords_order
						);
			}
		}

		//formats
		$detail_child_physical_descriptions = xnparticleGetDetailChildPhysicalDescriptionsInformation( $item_id );
		$num_physical_descriptions = count($detail_child_physical_descriptions);
		$formats = array();
		if ($num_physical_descriptions > 0) {
			while ( list( $key, list( $article_child_physical_descriptions_id, $article_id, $physical_descriptions, $physical_descriptions_order ) ) = each( $detail_child_physical_descriptions) ){
				$formats[] = array(
						'article_child_physical_descriptions_id' => $article_child_physical_descriptions_id,
						'article_id'                             => $article_id,
						'physical_descriptions'                  => $physical_descriptions,
						'physical_descriptions_order'           => $physical_descriptions_order
						);
			}
		}

		//ndc_classifications
		$detail_child_ndc_classifications = xnparticleGetDetailChildNdcClassificationsInformation( $item_id );
		$num_ndc_classifications = count($detail_child_ndc_classifications);
		$ndcs = array();
		if ($num_ndc_classifications > 0) {
			while ( list( $key, list( $article_child_ndc_classifications_id, $article_id, $ndc_classifications, $ndc_classifications_order ) ) = each( $detail_child_ndc_classifications) ){
				$ndcs[] = array(
						'article_child_ndc_classifications_id' => $article_child_ndc_classifications_id,
						'article_id'                           => $article_id,
						'ndc_classifications'                  => $ndc_classifications,
						'ndc_classifications_order'            => $ndc_classifications_order
						);
			}
		}

		//langs
		$detail_child_langs = xnparticleGetDetailChildLangsInformation( $item_id );
		$num_langs = count($detail_child_langs);
		$languages = array();
		if ($num_langs > 0) {
			while ( list( $key, list( $article_child_langs_id, $article_id, $langs, $langs_order ) ) = each( $detail_child_langs) ){
				$languages[] = array(
						'article_child_langs_id' => $article_child_langs_id,
						'article_id'             => $article_id,
						'langs'                  => $langs,
						'langs_order'            => $langs_order
						);
			}
		}

		//NIIType
		$nii_type = $detail['genre'];
		if(!empty($nii_type)){
			switch($detail['genre']){
				case "Journal Article":
					break;
				case "Thesis or Dissertation":
					break;
				case "Departmental Bulletin Paper":
					break;
				case "Conference Paper":
					break;
				case "Presentation":
					break;
				case "Book":
					break;
				case "Technical Report":
					break;
				case "Research Paper":
					break;
				case "Article":
					break;
				case "Preprint":
					break;
				case "Learning Material":
					break;
				case "Data or Dataset":
					break;
				case "Software":
					break;
				default:
					$nii_type = "Others";
			}
		}else{
			$nii_type = "Others";
		}

		//identifier (URI)
		if($basic['doi'] == "" ){
			$uri = XOOPS_URL . "/modules/xoonips/detail.php?item_id=". $item_id;
		}else{
			$uri =  XOOPS_URL . "/modules/xoonips/detail.php?" . XNP_CONFIG_DOI_FIELD_PARAM_NAME . "=" . $basic['doi'];
		}

		//fullTextURL and textversion
		$full_text_url = "";
		$textversion;
		global $xoopsDB;
		$sql = "select file_id from ".$xoopsDB->prefix('xoonips_file')." where item_id='".$item_id."' and mime_type='application/pdf' and is_deleted='0'";
		$result = $xoopsDB->query($sql);
		$row = $xoopsDB->fetchRow($result);
		if ($row[0]!=""){
			if($basic['doi'] != "" ) {
				$full_text_url =  XOOPS_URL . "/modules/xoonips/download.php?" . XNP_CONFIG_DOI_FIELD_PARAM_NAME . "=" . $basic['doi'];
			}
			$textversion = "author";
		}else{
			$textversion = "none";
		}

		//textversion
		if(!empty($detail['textversion'])){
			$textversion = $detail['textversion'];
		}

		//selfDOI
		$self_doi = "";
		$ra = "";
                if(!empty($detail['self_doi'])){
			$self_dois = array();
			$self_dois = explode("#", $detail['self_doi']);
			if(!empty($self_dois[1])){
				if(in_array($self_dois[0], array('JaLC', 'CrossRef'))){
					$ra = $self_dois[0];
				}
				$self_doi = $self_dois[1];
			}else{
				$self_doi = $self_dois[0];
			}
			if(empty($ra)){
				if(in_array(_MD_XNPARTICLE_OAIPMH_JUNII2_RA_OPTION, array('JaLC', 'CrossRef'))){
					$ra = _MD_XNPARTICLE_OAIPMH_JUNII2_RA_OPTION;
				}
			}
                }

		//id_isbns
		$detail_child_id_isbns = xnparticleGetDetailChildIdIsbnsInformation( $item_id );
		$num_id_isbns = count($detail_child_id_isbns);
		$isbns = array();
		if ($num_id_isbns > 0) {
			while ( list( $key, list( $article_child_id_isbns_id, $article_id, $id_isbns, $id_isbns_order ) ) = each( $detail_child_id_isbns) ){
				$isbns[] = array(
						'article_child_id_isbns_id' => $article_child_id_isbns_id,
						'article_id'                => $article_id,
						'id_isbns'                  => $id_isbns,
						'id_isbns_order'            => $id_isbns_order
						);
			}
		}

		//id_issns
		$detail_child_id_issns = xnparticleGetDetailChildIdIssnsInformation( $item_id );
		$num_id_issns = count($detail_child_id_issns);
		$issns = array();
		if ($num_id_issns > 0) {
			while ( list( $key, list( $article_child_id_issns_id, $article_id, $id_issns, $id_issns_order ) ) = each( $detail_child_id_issns) ){
				$issns[] = array(
						'article_child_id_issns_id' => $article_child_id_issns_id,
						'article_id'                => $article_id,
						'id_issns'                  => $id_issns,
						'id_issns_order'            => $id_issns_order
						);
			}
		}

		//id_dois
		$detail_child_id_dois = xnparticleGetDetailChildIdDoisInformation( $item_id );
		$num_id_dois = count($detail_child_id_dois);
		$dois = array();
		if ($num_id_dois > 0) {
			while ( list( $key, list( $article_child_id_dois_id, $article_id, $id_dois, $id_dois_order ) ) = each( $detail_child_id_dois) ){
				$dois[] = array(
						'article_child_id_dois_id' => $article_child_id_dois_id,
						'article_id'               => $article_id,
						'id_dois'                  => $id_dois,
						'id_dois_order'            => $id_dois_order
						);
			}
		}

		//id_uris
		$detail_child_id_uris = xnparticleGetDetailChildIdUrisInformation( $item_id );
		$num_id_uris = count($detail_child_id_uris);
		$uris = array();
		if ($num_id_uris > 0) {
			while ( list( $key, list( $article_child_id_uris_id, $article_id, $id_uris, $id_uris_order ) ) = each( $detail_child_id_uris) ){
				$uris[] = array(
						'article_child_id_uris_id' => $article_child_id_uris_id,
						'article_id'               => $article_id,
						'id_uris'                  => $id_uris,
						'id_uris_order'            => $id_uris_order
						);
			}
		}

		//id_locals
		$detail_child_id_locals = xnparticleGetDetailChildIdLocalsInformation( $item_id );
		$num_id_locals = count($detail_child_id_locals);
		$locals = array();
		if ($num_id_locals > 0) {
			while ( list( $key, list( $article_child_id_locals_id, $article_id, $id_locals, $id_locals_order ) ) = each( $detail_child_id_locals) ){
				$locals[] = array(
						'article_child_id_locals_id' => $article_child_id_locals_id,
						'article_id'                 => $article_id,
						'id_locals'                  => $id_locals,
						'id_locals_order'            => $id_locals_order
						);
			}
		}

		//doctor's degree
		$grant_id ="";
		if(!empty($detail['grant_id'])){
			$grant_id = $detail['grant_id'];
		}
		$date_of_granted ="";
		if(!empty($detail['date_of_granted'])){
			$date_of_granted = $detail['date_of_granted'];
		}
		$degree_name ="";
                if(!empty($detail['degree_name'])){
			$degree_name = $detail['degree_name'];
                }
		$grantor ="";
                if(!empty($detail['grantor'])){
			$grantor = $detail['grantor'];
		}

		// assign template
		global $xoopsTpl;
		$tpl = new XoopsTpl();
		$tpl->plugins_dir[] = XOONIPS_PATH.'/class/smarty/plugins';
		$tpl->assign($xoopsTpl->get_template_vars());
		$tpl->assign('basic', $basic);
		$tpl->assign('detail', $detail);
		$tpl->assign('sub_titles', $sub_titles);
		$tpl->assign('creators', $creators);
		$tpl->assign('subjects', $subjects);
		$tpl->assign('formats', $formats);
		$tpl->assign('ndcs', $ndcs);
		$tpl->assign('languages', $languages);
		$tpl->assign('nii_type', $nii_type);
		$tpl->assign('uri', $uri);
		$tpl->assign('full_text_url', $full_text_url);
		$tpl->assign('textversion', $textversion);
		//self_doi
		if(!empty($ra)){
			$tpl->assign('self_doi', $self_doi);
			$tpl->assign('ra', $ra);
		}
		$tpl->assign('isbns', $isbns);
		$tpl->assign('issns', $issns);
		$tpl->assign('dois', $dois);
		$tpl->assign('uris', $uris);
		$tpl->assign('locals', $locals);
		//doctor's degree
		if( $nii_type == "Thesis or Dissertation" && $textversion == "ETD" ){
			$tpl->assign('grant_id', $grant_id);
			$tpl->assign('date_of_granted', $date_of_granted);
			$tpl->assign('degree_name', $degree_name);
			$tpl->assign('grantor', $grantor);
		}
		$xml = $tpl->fetch('db:'.$mydirname.'_oaipmh_'.$metadataPrefix.'.xml');
		return $xml;
    }
    return false;
}


/**
 * get form request
 *
 * @access private
 * @param bool $is_add_mode
 * @param bool $do_replace_repeatable
 * @return array requested data
 */
function _xnparticle_get_form_request( $is_add_mode, $do_escape_repeatable ) {
  $formdata =& xoonips_getutility( 'formdata' );
  $textutil =& xoonips_getutility( 'text' );
  $detail_keys = array(
    'title',
    'title_kana',
    'title_romaji',
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
    'edition',
    'publish_place',
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
  $repeatable_keys = array(
    'sub_title_name',
    'sub_title_kana',
    'sub_title_romaji',
    'sub_title_order',
    'author_id',
    'author_name',
    'author_kana',
    'author_romaji',
    'author_affiliation',
    'author_affiliation_translation',
    'author_role',
    'author_link',
    'author_order',
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
  $add_mode_keys = array(
    'sub_title_name_add',
    'sub_title_kana_add',
    'sub_title_romaji_add',
    'author_id_add',
    'author_name_add',
    'author_kana_add',
    'author_romaji_add',
    'author_affiliation_add',
    'author_affiliation_translation_add',
    'author_role_add',
    'author_link_add',
    'keywords_add',
    'ndc_classifications_add',
    'physical_descriptions_add',
    'langs_add',
    'id_issns_add',
    'id_isbns_add',
    'id_dois_add',
    'id_uris_add',
    'id_locals_add',
    'uris_add',
  );
  $detail = array();
  foreach ( $detail_keys as $key ) {
    $detail[$key] = $formdata->getValue( 'post', $key, 's', false, '' );
  }
  foreach (  $repeatable_keys as $key ) {
    // for keep new lines, it should not trim() post data in $formdata
    $tmp = $formdata->getValue( 'post', $key, 'n', false, '' );
    // call private function of formdata utility class for temporary
    // TODO: rewrite here
    $tmp = $formdata->_convert_to_numeric_entities( $tmp );
    // trim lines
    $tmp = explode( "\n", $tmp );
    $tmp = array_map( 'trim', $tmp );
    $detail[$key] = implode( "\n", $tmp );
  }
  if ( $do_escape_repeatable ) {
    foreach ( $repeatable_keys as $key ) {
      $detail[$key] = $textutil->html_special_chars( $detail[$key] );
    }
  }
  if ( $is_add_mode ) {
    foreach ( $add_mode_keys as $key ) {
      $detail[$key] = $formdata->getValue( 'post', $key, 's', false, '' );
    }
  }
  return $detail;
}

