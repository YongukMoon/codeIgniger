<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 


class Simplepagination
{

	function __construct(){
	}

	
	function getAdminPagination($configure = false){
		if(is_array($configure) && ($configure['last_page'] >= $configure['current_page'])) {
			$configure['last_page'] = (int)$configure['last_page'];
			$html_return_string = '<ul class="pagination">';
			$page = (int)(($configure['current_page']-1) / 10)*10;
			if(($page != 0) && ($configure['last_page'] > 10)){
				if(($configure['last_page'] > 20) && ($configure['current_page'] > 20)) {
					$html_return_string .= '<li><a href="' . site_url($configure['url'] . '/1' . $configure['suffix']) . '" class="textbox_arrow"><i class="fas fa-angle-double-left"></i></a></li>';
				}
				$html_return_string .= '<li><a href="' . site_url($configure['url'] . '/' . ($page-9) . $configure['suffix']) . '" class="textbox_arrow"><i class="fas fa-angle-left"></li>';
			}
			$loop_page_count = (($configure['last_page'] - $page) >= 10) ? 10 : $configure['last_page']-$page;
			for($i=$page+1; $i<=$page+$loop_page_count; $i++){
				if($i == $configure['current_page']){
					$html_return_string .= '<li ><a class="textbox active">' . $i . '</a></li>';
				} else {
					$html_return_string .= '<li><a class="textbox" href="' . site_url($configure['url'] . '/' . $i . $configure['suffix']) . '">' . $i . '</a></li>';
				}
			}
			if(($configure['last_page'] > 10) && ( $page+10 < $configure['last_page'])) {
				$html_return_string .= '<li><a href="' . site_url($configure['url'] . '/' . ($page+11) . $configure['suffix']) . '" class="textbox_arrow">&gt;</a></li>';
				if(($configure['last_page'] - $page) > 20){
					if(is_int($configure['last_page'] / 10)) {
						$html_return_string .= '<li><a href="' . site_url($configure['url'] . '/' . (((int)($configure['last_page'] / 10))*10) . $configure['suffix']) . '" class="textbox_arrow"><i class="fas fa-angle-right"></a></li>';
					} else {
						$html_return_string .= '<li><a href="' . site_url($configure['url'] . '/' . (((int)($configure['last_page'] / 10))*10 + 1) . $configure['suffix']) . '" class="lastpage"><i class="fas fa-angle-double-right"></i></a></li>';
					}
				}
			}
			$html_return_string .= '</ul>';
			return $html_return_string;
		} else {
			return false;
		}
	}

	function getUserPagination($configure = false){
		if(is_array($configure) && ($configure['last_page'] >= $configure['current_page'])) {
			$configure['last_page'] = (int)$configure['last_page'];

			$html_return_string = '<div class="pagination">';
			
			if($configure['current_page'] != 1){
				$html_return_string .= '<a class="btn prev" href="' . site_url($configure['url'] . '/' . ($configure['current_page']-1) . $configure['suffix']) . '">〈</a>';
			}
			
			$html_return_string .= '<div class="page">';

			$page = (int)(($configure['current_page']-1) / 10)*10;
			if(($page != 0) && ($configure['last_page'] > 10)){
				if(($configure['last_page'] > 20) && ($configure['current_page'] > 20)) {
					$html_return_string .= '<a href="' . site_url($configure['url'] . '/1' . $configure['suffix']) . '" class="num"></a>';
				}
				$html_return_string .= '<a href="' . site_url($configure['url'] . '/' . ($page-9) . $configure['suffix']) . '" class="num"></a>';
			}
			$loop_page_count = (($configure['last_page'] - $page) >= 10) ? 10 : $configure['last_page']-$page;
			for($i=$page+1; $i<=$page+$loop_page_count; $i++){
				if($i == $configure['current_page']){
					$html_return_string .= '<a class="num current">' . $i . '</a>';
				} else {
					$html_return_string .= '<a class="num" href="' . site_url($configure['url'] . '/' . $i . $configure['suffix']) . '">' . $i . '</a>';
				}
			}
			if(($configure['last_page'] > 10) && ( $page+10 < $configure['last_page'])) {
				$html_return_string .= '<a href="' . site_url($configure['url'] . '/' . ($page+11) . $configure['suffix']) . '" class="num">&gt;</a>';
				if(($configure['last_page'] - $page) > 20){
					if(is_int($configure['last_page'] / 10)) {
						$html_return_string .= '<a href="' . site_url($configure['url'] . '/' . (((int)($configure['last_page'] / 10))*10) . $configure['suffix']) . '" class="num"></a>';
					} else {
						$html_return_string .= '<a href="' . site_url($configure['url'] . '/' . (((int)($configure['last_page'] / 10))*10 + 1) . $configure['suffix']) . '" class="lastpage"></a>';
					}
				}
			}

			$html_return_string .= '</div>';

			if($configure['last_page'] != $configure['current_page']){
				$html_return_string .= '<a class="btn prev" href="' . site_url($configure['url'] . '/' . ($configure['current_page']+1) . $configure['suffix']) . '">〉</a>';
			}

			$html_return_string .= '</div>';

			return $html_return_string;
		} else {
			return false;
		}
	}
}
