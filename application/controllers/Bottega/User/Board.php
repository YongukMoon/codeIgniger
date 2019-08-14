<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Board extends MY_Controller {
	
	public $tbl;
	public $cate;

	public function __construct(){
		parent::__construct();
		$this->tbl = $this->uri->segment(1);
		$this->cate = $this->uri->segment(2);

		$this->load->model('md_board');
	}


	public function index()
	{
		$tbl = $this->tbl;
		
		$data['cate'] = $this->cate;
		$data['board'] = $this->md_board->board_total($tbl, $data);
		$data['contact'] =$this->md_board->board_row('contact', 1);
		$data['tbl'] = $tbl;

		$url = 'front/board';
		$this->view_user_layout($url, $data);
	}

	public function news_view($idx){
		$data['news'] = $this->md_board->board_row($this->tbl, $idx);
		$url = 'front/news_view';
		$this->view_user_layout($url, $data);
	}
}