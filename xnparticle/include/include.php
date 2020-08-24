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

function xnparticleCreateModsXml(
    $detail,
    $detail_child_sub_title,
    $detail_child_author,
    $detail_child_keywords,
    $detail_child_ndc_classifications,
    $detail_child_physical_descriptions,
    $detail_child_langs,
    $detail_child_id_issns,
    $detail_child_id_isbns,
    $detail_child_id_dois,
    $detail_child_id_uris,
    $detail_child_id_locals,
    $detail_child_uris ){

    $detail['sub_title_str'] = '';
    while (list($key,list($article_child_sub_title_id,
                              $article_id,
                              $sub_title_name,
                              $sub_title_kana,
                              $sub_title_romaji,
                              $sub_title_order)) = each($detail_child_sub_title ) ){
        $detail['sub_title_str'] .= '';
        if($article_child_sub_title_id!="" && $article_id!=""){

            $detail['sub_title_str'] .= "<sub_title>\n";
            if($sub_title_name!=""){
                $detail['sub_title_str'] .= "<sub_title_name>".htmlspecialchars( $sub_title_name, ENT_QUOTES )."</sub_title_name>\n";
            }else{
                $detail['sub_title_str'] .= "<sub_title_name />";
            }
            if($sub_title_kana!=""){
                $detail['sub_title_str'] .= "<sub_title_kana>".htmlspecialchars( $sub_title_kana, ENT_QUOTES )."</sub_title_kana>\n";
            }else{
                $detail['sub_title_str'] .= "<sub_title_kana />";
            }
            if($sub_title_romaji!=""){
                $detail['sub_title_str'] .= "<sub_title_romaji>".htmlspecialchars( $sub_title_romaji, ENT_QUOTES )."</sub_title_romaji>\n";
            }else{
                $detail['sub_title_str'] .= "<sub_title_romaji />";
            }
            $detail['sub_title_str'] .= "</sub_title>\n";
        }

    }
    $rtn = "".$detail['sub_title_str']."";

    return $rtn;
}

if (!function_exists('xoonips_error_exit')) {
    function xoonips_error_exit($code) {
        exit('Fatal Error: '.$code);
    }
}

