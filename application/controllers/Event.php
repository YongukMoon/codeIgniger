<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Event extends MY_controller
{
	function __construct(){
		parent::__construct();
	}

	public function index($page=null){
		$this->load->model('Md_event');

		$data['key']=$this->input->get('key');
		
		//페이징
		$total_cnt=$this->Md_event->get_event_total($data);
		$data['row_cnt']=10;
		$data['page']=$page ?: 1;
		$url='event/index';
		$suffix='?&key='.$data['key'];
		$data['paging']=$this->get_Admin_paging($total_cnt, $data['row_cnt'], $data['page'], $url, $suffix);

		$data['sub_act']="5";
		
		$data['event']=$this->Md_event->get_event_list($data);

		$this->view_adm_layout('admin/event/event_index', $data);
	}

	public function create(){
		$data['class']='store';
		$data['btn']='등록';
		$this->view_adm_layout('admin/event/event_f', $data);
	}

	public function store(){
		$data['m_id']=$this->get_session('adm_id');
		$data['e_title']=$this->input->post('title');
		$data['e_content']=$this->input->post('content');
		$data['e_sdate']=$this->input->post('sdate');
		$data['e_edate']=$this->input->post('edate');
		$data['e_regdate']=date("Y-m-d H:i:s");

		//null check
		if(empty($data['e_title']) or ($data['e_content'] == '<p>&nbsp;</p>') or empty($data['e_sdate']) or empty($data['e_edate']) or ($_FILES['event_attach']['name'][0] == '')){
			alert("메세지", "이벤트 등록에 실패하였습니다.", "/event/index");
		}

		$this->load->model('Md_event');

		//event 등록
		$data['a_tbl_idx'] = $this->Md_event->event_set($data);

		//첨부파일
		$files=$_FILES['event_attach'];

		if(!empty($files['name'][0])){
			$uploads_dir=$this->config->item('file_path');

			for($i = 0; $i < count($files['name']); $i++){
				$data['a_origin_nm']=$files['name'][$i];
				$file_name=substr(md5(time()), 0, 16).$files['name'][$i];
				$data['a_name'][]=$file_name;
				$data['a_tbl']='ac_event';

				move_uploaded_file( $files['tmp_name'][$i], "$uploads_dir/event/$file_name");
			}

			$this->load->model('Md_attach');
			$this->Md_attach->attach_store($data);
		}

		alert("메세지", "이벤트가 등록되었습니다.", "/event/index");
	}

	public function show($id){
		$this->load->model('Md_event');

		$data['e_idx']=$id;
		$data=$this->Md_event->get_event_row($data);

		$data['e_count'] += 1;

		$this->Md_event->count_up($data);

		//첨부파일
		$this->load->model('Md_attach');
		$data['attach']=$this->Md_attach->get_attach_row($id, $a_tbl='ac_event');

		$data['class']='update/'.$id;
		$data['btn']='수정';

		$this->view_adm_layout('admin/event/event_f', $data);
	}

	public function edit($id){
		$this->load->model('Md_event');

		$data['e_idx']=$id;
		$data=$this->Md_event->get_event_row($data);

		//첨부파일
		$this->load->model('Md_attach');
		$data['attach']=$this->Md_attach->get_attach_row($id, $a_tbl='ac_event');

		$data['class']='update/'.$id;
		$data['btn']='수정';

		$this->view_adm_layout('admin/event/event_f', $data);
	}

	public function update($id){
		$this->load->model('Md_event');

		$data['e_idx']=$id;
		$data['e_title']=$this->input->post('title');
		$data['e_content']=$this->input->post('content');
		$data['e_sdate']=$this->input->post('sdate');
		$data['e_edate']=$this->input->post('edate');
		$data['e_regdate']=date("Y-m-d H:i:s");

		//null check
		if(empty($data['e_title']) or ($data['e_content'] == '<p>&nbsp;</p>') or empty($data['e_sdate']) or empty($data['e_edate']) or ($_FILES['event_attach']['name'][0] == '')){
			alert("메세지", "이벤트 수정에 실패하였습니다.", "/event/index");
		}

		$this->Md_event->event_update($data);

		//첨부파일
		$files=$_FILES['event_attach'];

		if(!empty($files['name'][0])){
			$this->load->model('Md_attach');
			$data['a_tbl_idx']=$id;
			$data['a_tbl']='ac_event';
			$uploads_dir=$this->config->item('file_path');

			//기존 업로드된 파일 제거
			$row=$this->Md_attach->get_attach_row($data['a_tbl_idx'], $data['a_tbl']);
			unlink($uploads_dir.'/event/'.$row[0]['a_name']);

			//기존 업로드된 파일 데이터 제거
			$this->Md_attach->attach_delete($data['a_tbl_idx'], $data['a_tbl']);

			//새로 업로드한 파일 저장
			for($i = 0; $i < count($files['name']); $i++){
				$data['a_origin_nm']=$files['name'][$i];
				$file_name=substr(md5(time()), 0, 16).$files['name'][$i];
				$data['a_name'][]=$file_name;

				move_uploaded_file( $files['tmp_name'][$i], "$uploads_dir/event/$file_name");
			}

			//새로 업로드한 파일 데이터 저장
			$this->Md_attach->attach_store($data);
		}

		alert("메세지", "이벤트가 수정되었습니다.", "/event/index");
	}

	public function destroy($id){
		$this->load->model('Md_event');

		$data['e_idx']=$id;

		$upload_dir=$this->config->item('file_path');
		$row=$this->Md_event->get_event_row($data);

		$this->load->model('Md_attach');
		$attach=$this->Md_attach->get_attach_row($data, $a_tbl='ac_event');

		for($i = 0; $i < count($attach); $i++){
			unlink($upload_dir.'/event/'.$attach[$i]['a_name']);
		}
		
		$this->Md_attach->attach_delete($row['e_idx'], $a_tbl='ac_event');
		
		//editor img delete
		preg_match_all("/<img[^>]*src=[\"']?([^>\"']+)[\"']?[^>]*>/i", $row['e_content'], $matches);

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

		$this->Md_event->event_delete($id);
		
		alert("메세지", "이벤트가 삭제되었습니다.", "/event/index");
	}
}