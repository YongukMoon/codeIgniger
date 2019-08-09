<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class News extends MY_controller
{
	function __construct(){
		parent::__construct();
	}

	public function index($page=null){
		$this->load->model('Md_news');

		$data['key']=$this->input->get('key');
		
		//페이징
		$total_cnt=$this->Md_news->get_news_total($data);
		$data['row_cnt']=10;
		$data['page']=$page ?: 1;
		$url='news/index';
		$suffix='?&key='.$data['key'];
		$data['paging']=$this->get_Admin_paging($total_cnt, $data['row_cnt'], $data['page'], $url, $suffix);

		$data['sub_act']="5";
		
		$data['news']=$this->Md_news->get_news_list($data);

		$this->view_adm_layout('admin/news/news', $data);
	}

	public function create(){
		$data['class']='store';
		$data['btn']='등록';
		$this->view_adm_layout('admin/news/news_f', $data);
	}

	public function store(){
		$mdt['title']=$this->input->post('title');
		$mdt['content']=$this->input->post('content');

		//입력값이 없을때
		if($mdt['title'] == null or $mdt['content'] == '<p>&nbsp;</p>'){
			alert("메세지", "공지사항 등록을 실패하였습니다.", "/news/index");
		}

		$this->load->model('Md_news');

		$mdt['adm_id']=$this->get_session('adm_id');
		$mdt['n_ragdate']=date("Y-m-d H:i:s");

		//news 등록
		$mdt['a_tbl_idx'] = $this->Md_news->news_set($mdt);

		//첨부파일
		$files=$_FILES['news_attach'];

		if(!empty($files['name'][0])){
			$uploads_dir=$this->config->item('file_path');

			for($i = 0; $i < count($files['name']); $i++){
				$mdt['a_origin_nm']=$files['name'][$i];
				$file_name=substr(md5(time()), 0, 16).$files['name'][$i];
				$mdt['a_name'][]=$file_name;
				$mdt['a_tbl']='ac_news';

				move_uploaded_file( $files['tmp_name'][$i], "$uploads_dir/news/$file_name");
			}

			$this->load->model('Md_attach');
			$this->Md_attach->attach_store($mdt);
		}

		alert("메세지", "공지사항이 등록되었습니다.", "/news/index");
	}

	public function show($id){
		$this->load->model('Md_news');

		$mdt['news_id']=$id;
		$data=$this->Md_news->get_news_row($mdt);

		$data['n_count'] += 1;

		$this->Md_news->count_up($data);

		//첨부파일
		$this->load->model('Md_attach');
		$data['attach']=$this->Md_attach->get_attach_row($id, $a_tbl='ac_news');
		$data['news_id']=$id;

		$data['class']='update/'.$id;
		$data['btn']='수정';

		$this->view_adm_layout('admin/news/news_f', $data);
	}

	public function edit($id){
		$this->load->model('Md_news');

		$mdt['news_id']=$id;
		$data=$this->Md_news->get_news_row($mdt);

		//첨부파일
		$this->load->model('Md_attach');
		$data['attach']=$this->Md_attach->get_attach_row($mdt, $a_tbl='ac_news');

		$data['class']='update/'.$id;
		$data['btn']='수정';
		$this->view_adm_layout('admin/news/news_f', $data);
	}

	public function update($id){
		$this->load->model('Md_news');

		$mdt['news_id']=$id;
		$mdt['title']=$this->input->post('title');
		$mdt['content']=$this->input->post('content');
		$mdt['n_ragdate']=date("Y-m-d H:i:s");

		//입력값이 없을때
		if($mdt['title'] == null or $mdt['content'] == '<p>&nbsp;</p>'){
			alert("메세지", "공지사항 수정을 실패하였습니다.", "/news/index");
		}

		$data=$this->Md_news->news_update($mdt);

		//첨부파일
		$files=$_FILES['news_attach'];

		if(!empty($files['name'][0])){
			$this->load->model('Md_attach');
			$mdt['a_tbl_idx']=$id;
			$mdt['a_tbl']='ac_news';
			$uploads_dir=$this->config->item('file_path');

			//기존 업로드된 파일 제거
			$row=$this->Md_attach->get_attach_row($mdt['a_tbl_idx'], $mdt['a_tbl']);
			unlink($uploads_dir.'/news/'.$row[0]['a_name']);

			//기존 업로드된 파일 데이터 제거
			$this->Md_attach->attach_delete($mdt['a_tbl_idx'], $mdt['a_tbl']);

			//새로 업로드한 파일 저장
			for($i = 0; $i < count($files['name']); $i++){
				$mdt['a_origin_nm']=$files['name'][$i];
				$file_name=substr(md5(time()), 0, 16).$files['name'][$i];
				$mdt['a_name'][]=$file_name;

				move_uploaded_file( $files['tmp_name'][$i], "$uploads_dir/news/$file_name");
			}

			//새로 업로드한 파일 데이터 저장
			$this->Md_attach->attach_store($mdt);
		}

		alert("메세지", "공지사항이 수정되었습니다.", "/news/index");
	}

	public function destroy($newsId){
		$this->load->model('Md_news');

		$mdt['news_id']=$newsId;

		$upload_dir=$this->config->item('file_path');
		$row=$this->Md_news->get_news_row($mdt);

		$this->load->model('Md_attach');
		$attach=$this->Md_attach->get_attach_row($mdt, $a_tbl='ac_news');

		for($i = 0; $i < count($attach); $i++){
			unlink($upload_dir.'/news/'.$attach[$i]['a_name']);
		}
		
		$this->Md_attach->attach_delete($row['id'], $a_tbl='ac_news');
		
		//editor img delete
		preg_match_all("/<img[^>]*src=[\"']?([^>\"']+)[\"']?[^>]*>/i", $row['content'], $matches);

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

		$this->Md_news->news_delete($newsId);
		
		alert("메세지", "공지사항이 삭제되었습니다.", "/news/index");
	}
}