<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Qna extends MY_controller
{
	public function __construct(){
		parent::__construct();
	}

	public function index($page=null){
		$this->load->model('Md_qna');

		$data['q_flag']=$this->input->get('q_flag');
		$data['key']=$this->input->get('key');
		$data['sdate']=$this->input->get('sdate');
		$data['edate']=$this->input->get('edate');

		//페이징
		$total_cnt=$this->Md_qna->get_qna_total($data);
		$data['row_cnt']=10;
		$data['page']=$page ?: 1;
		$url='qna/index';

		$suffix='?q_flag='.$data['q_flag']
				.'&sdate='.$data['sdate']
				.'&edate='.$data['edate']
				.'&key='.$data['key'];

		$data['paging']=$this->get_Admin_paging($total_cnt, $data['row_cnt'], $data['page'], $url, $suffix);

		$data['qna']=$this->Md_qna->get_qna_list($data);

		$this->view_adm_layout('admin/qna/qna_index', $data);
	}

	public function edit($q_idx){
		$this->load->model('Md_qna');

		$data['qna']=$this->Md_qna->get_qna_row($q_idx);

		$data['class']='update/'.$q_idx;

		//attach list
		$this->load->model('Md_attach');
		$data['attach']=$this->Md_attach->get_attach_row($q_idx, $a_tbl='ac_qna');

		$this->view_adm_layout('admin/qna/qna_f', $data);
	}

	public function update($q_idx){
		$data['q_comment']=$this->input->post('comment');

		if($data['q_comment'] == ''){
			alert("메세지", "답변 등록을 실패하였습니다..", "/qna/index");
		}

		$this->load->model('Md_qna');

		$data['q_idx']=$q_idx;
		$data['q_flag']=1;
		$data['q_com_date']=date("Y-m-d H:i:s");

		$this->Md_qna->comment_store($data);

		alert("메세지", "답변이 등록되었습니다.", "/qna/index");
	}

	public function destroy($q_idx){
		$this->load->model('Md_qna');

		$data['q_comment']=null;
		$data['q_idx']=$q_idx;

		$data['q_flag']=0;
		$data['q_com_date']=null;



		$this->Md_qna->comment_store($data);

		alert("메세지", "답변이 삭제되었습니다.", "/qna/index");
	}
}