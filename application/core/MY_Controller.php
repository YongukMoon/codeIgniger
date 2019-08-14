<?php
class MY_Controller extends CI_Controller {

	function __construct()
    {
        parent::__construct();
    }
	
	function view_adm_layout($viewpg, $data){
		$data['u_name'] = $this->u_name;

		$this->load->view("admin/layouts/header", $data);
		$this->load->view($viewpg, $data);
		$this->load->view("admin/layouts/footer", $data);
	}

	function view_user_layout($viewpg, $data){

		$this->load->view("front/layouts/header", $data);
		$this->load->view($viewpg, $data);
		$this->load->view("front/layouts/footer", $data);
	}

	function get_Admin_paging($total_cnt, $row_cnt, $current_page, $url, $suffix = ""){
		$last_page = ceil($total_cnt/$row_cnt);
		$this->load->library('simplepagination');
		$page_data['url'] = $url;
		$page_data['current_page'] = $current_page;	//현재 페이지번호
		$page_data['last_page'] = $last_page;				//마지막 페이지 번호
		$page_data['suffix'] = $suffix;								//추가값
		$paging = $this->simplepagination->getAdminPagination($page_data);
		return $paging;
    }

	function get_User_paging($total_cnt, $row_cnt, $current_page, $url, $suffix = ""){
		$last_page = ceil($total_cnt/$row_cnt);
		$this->load->library('simplepagination');
		$page_data['url'] = $url;
		$page_data['current_page'] = $current_page;	//현재 페이지번호
		$page_data['last_page'] = $last_page;				//마지막 페이지 번호
		$page_data['suffix'] = $suffix;								//추가값
		$paging = $this->simplepagination->getUserPagination($page_data);
		return $paging;
    }
	
}

