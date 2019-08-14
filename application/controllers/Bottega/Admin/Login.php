<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends MY_Controller {

	public function __construct(){
		parent::__construct();
		
		
	}

	public function index()
	{
		$u_idx = $this->session->userdata('u_idx');

		if($u_idx){
			redirect('/Admin/view');
		}

		$this->load->view('admin/login');
	}

	public function store(){

		$this->form_validation->set_rules('u_id', '아이디', 'required');
		$this->form_validation->set_rules('u_pw', '비밀번호', 'required');

		if($this->form_validation->run() == FALSE){
			echo "입력값이 없습니다.";
			return;
		}

		$u_id = $this->input->post('u_id');
		$u_pw = $this->input->post('u_pw');

		$this->load->model('md_user');
		$user = $this->md_user->login($u_id, $u_pw);

		if(!empty($user)){
			$this->session->set_userdata('u_idx', $user['u_idx']);
			$this->session->set_userdata('u_name', $user['u_name']);
			redirect('/Admin/view');
		}else{
			redirect('/Admin/Login');
		}
	}

	public function destroy(){
		$this->session->sess_destroy();
		redirect('/Admin/Login');
	}
}

?>