<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Board extends MY_Controller {

	public $u_idx;
	public $u_name;
	public $tbl;

	public function __construct(){
		parent::__construct();

		date_default_timezone_set('Asia/Seoul');

		$this->u_idx = $this->session->userdata('u_idx');
		$this->u_name = $this->session->userdata('u_name');

		if(!$this->u_idx){
			redirect('Admin/Login');
		}

		$this->load->model('md_board');
		$this->tbl = $this->uri->segment(2);
	}

	public function index($page=null){
		$tbl = $this->tbl;

		$data['title'] = $tbl;
		$data['key'] = $this->input->get('key');
		$data['cate'] = $this->input->get('cate');

		$data['row_cnt'] = 10;
		$data['page'] = $page ?: 1;

		$data['board'] = $this->md_board->board_list($tbl, $data);

		//페이징
		$total_cnt = count($this->md_board->board_total($tbl, $data));
		$url = '/Admin/'.$tbl;
		$suffix = '?cate='.$data['cate'].'&key='.$data['key'];
		$data['paging'] = $this->get_Admin_paging($total_cnt, $data['row_cnt'], $data['page'], $url, $suffix);

		$data['tbl'] = $tbl;
		$this->view_adm_layout('admin/board/board_index', $data);
	}

	public function create(){
		$tbl = $this->tbl;

		$data['title'] = $tbl;
		$data['action'] = '/Admin/'.$tbl.'/store';
		$data['board']['cate'] = '';
		$data['btn'] = '등록';


		if($tbl == 'view' or $tbl == 'gallery'){
			$data['msg'] = 'Image width : 955px 이상 (권장)';
		}else if($tbl == 'news'){
			$data['msg'] = 'Image size : 331px (w) X 161px (h) (권장)';
		}else if($tbl == 'review'){
			$data['msg'] = 'Image size : 358px (w) X 250px (h) (권장)';
		}


		$this->view_adm_layout('admin/board/board_form', $data);
	}

	public function store(){

		$this->form_validation->set_rules('title', '제목', 'required');
		$this->form_validation->set_rules('content', '내용', 'required');

		if($this->tbl == 'view' or $this->tbl == 'gallery'){
			$this->form_validation->set_rules('cate', '카테고리', 'required');
			$data['cate'] = $this->input->post('cate');
		}else if($this->tbl == 'review'){
			$data['link'] = $this->input->post('link');
		}

		if($this->form_validation->run() == FALSE){
			alert('에러', '입력값을 확인해 주세요', '/Admin/'.$tbl);
			return;
		}

		if($_FILES['attach']['name'] == ''){
			alert('에러', '첨부파일을 업로드 해주세요', '/Admin/'.$tbl);
			return;
		}
		

		$data['u_idx'] = $this->u_idx;
		$data['title'] = $this->input->post('title');
		$data['content'] = $this->input->post('content');
		$data['attach'] = random_string('unique').$_FILES['attach']['name'];
		$data['regdate'] = date("Y-m-d H:i:s");
		$tbl = $this->tbl;

		//첨부파일
		$upload_dir = $this->config->item('file_path');
		$file_path = $upload_dir.$this->tbl.'/'.$data['attach'];
		move_uploaded_file( $_FILES['attach']['tmp_name'], $file_path);
		
		$this->md_board->board_store($tbl, $data);

		alert("성공", "등록이 완료되었습니다.", '/Admin/'.$tbl);
	}

	public function edit($idx){
		$tbl = $this->tbl;

		$data['title'] = $tbl;
		$data['action'] = '/Admin/'.$tbl.'/update/'.$idx;
		$data['btn'] = '수정';

		if($tbl == 'view' or $tbl == 'gallery'){
			$data['msg'] = 'Image width : 955px 이상 (권장)';
		}else if($tbl == 'news'){
			$data['msg'] = 'Image size : 331px (w) X 161px (h) (권장)';
		}else if($tbl == 'review'){
			$data['msg'] = 'Image size : 358px (w) X 250px (h) (권장)';
		}

		$data['board'] = $this->md_board->board_row($tbl, $idx);

		$this->view_adm_layout('admin/board/board_form', $data);
	}

	public function update($idx){
		$tbl = $this->tbl;
		
		$this->form_validation->set_rules('title', '제목', 'required');

		if($tbl == 'view' or $tbl == 'gallery'){
			$this->form_validation->set_rules('cate', '카테고리', 'required');
			$data['cate'] = $this->input->post('cate');
		}else if($tbl == 'review'){
			$data['link'] = $this->input->post('link');
		}

		$this->form_validation->set_rules('content', '내용', 'required');

		if($this->form_validation->run() == FALSE){
			alert('에러', '입력값을 확인해 주세요', '/Admin/'.$tbl);
			return;
		}

		$data['title'] = $this->input->post('title');
		$data['content'] = $this->input->post('content');

		$files = $_FILES['attach'];

		if($files['name'] != ''){
			$row = $this->md_board->board_row($tbl, $idx);
			$upload_dir = $this->config->item('file_path');

			// 기존 파일 삭제
			unlink($upload_dir.$this->tbl.'/'.$row['attach']); 

			// 새로운 파일 저장
			$data['attach'] = random_string('unique').$files['name'];
			$file_path = $upload_dir.$this->tbl.'/'.$data['attach'];
			move_uploaded_file($files['tmp_name'], $file_path);
			
		}

		$this->md_board->board_update($tbl, $idx, $data);

		alert("성공", "수정이 완료되었습니다.", '/Admin/'.$tbl);
	}

	public function destroy($idx){
		$tbl = $this->tbl;

		$row = $this->md_board->board_row($tbl, $idx);
		$upload_dir = $this->config->item('file_path');

		// 기존 파일 삭제
		unlink($upload_dir.$this->tbl.'/'.$row['attach']);

		//editor img delete
		preg_match_all("/<img[^>]*src=[\"']?([^>\"']+)[\"']?[^>]*>/i", $row['content'], $matches);

		if(!empty($matches[1])){
			for($i = 0; $i < count($matches[1]); $i++){
				unlink($_SERVER['DOCUMENT_ROOT'].$matches[1][$i]);
			}
		}

		$this->md_board->board_delete($tbl, $idx);

		alert("성공", "삭제되었습니다.", '/Admin/'.$tbl);
	}

	public function contact($idx){
		$tbl = $this->tbl;

		$data['title'] = $tbl;
		$data['action'] = '/Admin/'.$tbl.'/update/'.$idx;
		$data['btn'] = '수정';
		$data['msg'] = 'Image size : 586px (w) X 302px (h) (권장)';

		$data['board'] = $this->md_board->board_row($tbl, $idx);

		$this->view_adm_layout('admin/board/main_contact', $data);
	}

	public function contact_update($idx){
		$tbl = $this->tbl;
		
		$this->form_validation->set_rules('title', '타이틀', 'required');
		$this->form_validation->set_rules('e_address', '영문주소', 'required');
		$this->form_validation->set_rules('k_address', '한글주소', 'required');
		$this->form_validation->set_rules('tel', '전화번호', 'required');
		$this->form_validation->set_rules('fax', '팩스번호', 'required');
		$this->form_validation->set_rules('email', '이메일', 'required');
		$this->form_validation->set_rules('i_address', '이탈리아 주소', 'required');
		$this->form_validation->set_rules('i_tel', '이탈리아 전화번호', 'required');
		$this->form_validation->set_rules('i_fax', '이탈리아 팩스번호', 'required');
		$this->form_validation->set_rules('i_email', '이탈리아 이메일', 'required');
		

		if($this->form_validation->run() == FALSE){
			alert('에러', '입력값을 확인해 주세요.', '/Admin/'.$tbl.'/edit/1');
			return;
		}

		$data['title'] = $this->input->post('title');
		$data['e_address'] = $this->input->post('e_address');
		$data['k_address'] = $this->input->post('k_address');
		$data['tel'] = $this->input->post('tel');
		$data['fax'] = $this->input->post('fax');
		$data['email'] = $this->input->post('email');
		$data['i_address'] = $this->input->post('i_address');
		$data['i_tel'] = $this->input->post('i_tel');
		$data['i_fax'] = $this->input->post('i_fax');
		$data['i_email'] = $this->input->post('i_email');

		$files = $_FILES['attach'];

		if($files['name'] != ''){
			$row = $this->md_board->board_row($tbl, $idx);
			$upload_dir = $this->config->item('file_path');

			// 기존 파일 삭제
			unlink($upload_dir.$this->tbl.'/'.$row['attach']);

			// 새로운 파일 저장
			$data['attach'] = random_string('unique').$files['name'];
			$file_path = $upload_dir.$this->tbl.'/'.$data['attach'];
			move_uploaded_file($files['tmp_name'], $file_path);
		}

		$this->md_board->board_update($tbl, $idx, $data);

		alert("성공", "수정되었습니다.", '/Admin/'.$tbl.'/edit/1');
	}
}

?>