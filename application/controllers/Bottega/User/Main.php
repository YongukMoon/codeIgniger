<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends MY_Controller {

	public function __construct(){
		parent::__construct();
	}

	public function index($page=null)
	{
		$tbl = $this->uri->segment(1);

		$this->load->model('md_board');
		$data['row_cnt'] = 3;

		if($tbl == 'news'){
			//news
			$data['page'] = $page ?: 1;
			
			$data['suffix'] = '#view6';
			$data = $this->board_index('news', $data);


			//review
			$data['page'] = 1;
			
			$data['suffix'] = '#view7';
			$data = $this->board_index('review', $data);
		
		}else{
			//news
			$data['page'] = 1;

			$data['suffix'] = '#view6';
			$data = $this->board_index('news', $data);


			//review
			$data['page'] = $page ?: 1;
			
			$data['suffix'] = '#view7';
			$data = $this->board_index('review', $data);

		}

		$data['contact'] = $this->md_board->board_row('contact', 1);

		$this->load->view('front/main', $data);
	}

	protected function board_index($tbl, $data){
		$total_cnt = count($this->md_board->board_total($tbl, $data));
		$url = '/'.$tbl;
		$data[$tbl.'_paging'] = $this->get_User_paging($total_cnt, $data['row_cnt'], $data['page'], $url, $data['suffix']);
		$data[$tbl] = $this->md_board->board_list($tbl, $data);

		return $data;
	}
}
