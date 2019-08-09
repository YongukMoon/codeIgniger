<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Faq extends MY_controller
{
	function __construct(){
		parent::__construct();
	}

	public function index($page=null){
		$this->load->model('Md_faq');

		$data['f_type']=$this->input->get('f_type');
		$data['key']=$this->input->get('key');

		$total_cnt=$this->Md_faq->get_faq_total($data);

		//페이징
		$data['row_cnt']=10;
		$data['page']=$page ?: 1;
		$url='faq/index';
		$suffix='?f_type='.$data['f_type'].'&key='.$data['key'];
		$data['paging']=$this->get_Admin_paging($total_cnt, $data['row_cnt'], $data['page'], $url, $suffix);
		$data['sub_act']="7";
		
		$data['faq']=$this->Md_faq->get_faq_list($data);

		$this->view_adm_layout('admin/faq/faq_index', $data);
	}

	public function create(){
		$data['class']='store';
		$data['btn']='등록';
		$data['f_type']='';
		$this->view_adm_layout('admin/faq/faq_f', $data);
	}

	public function store(){
		$data['f_type']=$this->input->post('type');
		$data['f_title']=$this->input->post('title');
		$data['f_content']=$this->input->post('content');

		if($data['f_title'] == null or $data['f_content'] == '<p>&nbsp;</p>' or $data['f_type'] == ''){
			alert("메세지", "이용안내 등록을 실패하였습니다.", "/faq/index");
		}

		$this->load->model('Md_faq');

		$data['m_id']=$this->get_session('adm_id');
		$data['f_ragdate']=date("Y-m-d H:i:s");

		//faq 등록
		$data['a_tbl_idx'] = $this->Md_faq->faq_set($data);

		//첨부파일
		$files=$_FILES['faq_attach'];

		if(!empty($files['name'][0])){
			$uploads_dir=$this->config->item('file_path');

			for($i = 0; $i < count($files['name']); $i++){
				$data['a_origin_nm']=$files['name'][$i];
				$file_name=substr(md5(time()), 0, 16).$files['name'][$i];
				$data['a_name'][]=$file_name;
				$data['a_tbl']='ac_faq';

				move_uploaded_file( $files['tmp_name'][$i], "$uploads_dir/faq/$file_name");
			}

			$this->load->model('Md_attach');
			$this->Md_attach->attach_store($data);
		}

		alert("메세지", "이용안내가 등록되었습니다.", "/faq/index");
	}

	public function show($f_idx){
		$this->load->model('Md_faq');

		$data['f_idx']=$f_idx;
		$data=$this->Md_faq->get_faq_row($data);

		//조회수
		$data['f_count'] += 1;
		$this->Md_faq->count_up($data);

		//첨부파일 조회
		$this->load->model('Md_attach');
		$data['attach']=$this->Md_attach->get_attach_row($f_idx, $a_tbl='ac_faq');

		$data['class']='update/'.$f_idx;
		$data['btn']='수정';

		$this->view_adm_layout('admin/faq/faq_f', $data);
	}

	public function edit($f_idx){
		$this->load->model('Md_faq');

		$data['f_idx']=$f_idx;
		$data=$this->Md_faq->get_faq_row($data);

		//첨부파일 조회
		$this->load->model('Md_attach');
		$data['attach']=$this->Md_attach->get_attach_row($f_idx, $a_tbl='ac_faq');

		$data['class']='update/'.$f_idx;
		$data['btn']='수정';
		$this->view_adm_layout('admin/faq/faq_f', $data);
	}

	public function update($f_idx){
		$this->load->model('Md_faq');

		$data['f_idx']=$f_idx;
		$data['f_type']=$this->input->post('type');
		$data['f_title']=$this->input->post('title');
		$data['f_content']=$this->input->post('content');
		$data['f_regdate']=date("Y-m-d H:i:s");

		//null check
		if($data['f_title'] == null or $data['f_content'] == '<p>&nbsp;</p>' or $data['f_type'] == ''){
			alert("메세지", "이용안내 수정을 실패하였습니다.", "/faq/index");
		}

		$this->Md_faq->faq_update($data);

		$data['a_tbl_idx']=$f_idx;
		
		//첨부파일
		$files=$_FILES['faq_attach'];

		if(!empty($files['name'][0])){
			$this->load->model('Md_attach');
			$data['a_tbl']='ac_faq';
			$uploads_dir=$this->config->item('file_path');

			//기존 업로드된 파일 제거
			$row=$this->Md_attach->get_attach_row($data['a_tbl_idx'], $data['a_tbl']);
			unlink($uploads_dir.'/faq/'.$row[0]['a_name']);

			//기존 업로드된 파일 데이터 제거
			$this->Md_attach->attach_delete($data['a_tbl_idx'], $data['a_tbl']);

			//새로운 파일 저장
			for($i = 0; $i < count($files['name']); $i++){
				$data['a_origin_nm']=$files['name'][$i];
				$file_name=substr(md5(time()), 0, 16).$files['name'][$i];
				$data['a_name'][]=$file_name;

				move_uploaded_file( $files['tmp_name'][$i], "$uploads_dir/faq/$file_name");
			}

			$this->Md_attach->attach_store($data);
		}

		alert("메세지", "이용안내가 수정되었습니다.", "/faq/index");
	}

	public function destroy($f_idx){
		$this->load->model('Md_faq');

		$data['f_idx']=$f_idx;

		$upload_dir=$this->config->item('file_path');
		$row=$this->Md_faq->get_faq_row($data);

		$this->load->model('Md_attach');
		$attach=$this->Md_attach->get_attach_row($f_idx, $a_tbl='ac_faq');

		for($i = 0; $i < count($attach); $i++){
			unlink($upload_dir.'/faq/'.$attach[$i]['a_name']);
		}

		$this->Md_attach->attach_delete($row['f_idx'], $a_tbl='ac_faq');
		
		//editor img delete
		preg_match_all("/<img[^>]*src=[\"']?([^>\"']+)[\"']?[^>]*>/i", $row['f_content'], $matches);

		if(!empty($matches[1])){
			$this->load->model('Md_attach');
			for($i = 0; $i < count($matches[1]); $i++){
				$row['a_name'][]=substr($matches[1][$i], 8, 40);
			}

			if($row['a_name'] != null){
				for($i = 0; $i < count($row['a_name']); $i++){
					unlink($upload_dir.$row['a_name'][$i]);
				}
			}
		}

		$this->Md_faq->faq_delete($f_idx);

		alert("메세지", "이용안내가 삭제되었습니다.", "/faq/index");
	}
}