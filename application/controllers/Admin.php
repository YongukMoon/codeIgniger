<?php

class Admin extends CI_controller
{
    public function __construct(){
        parent::__construct();
        $this->load->database();
        $this->load->model('Admin_model');
    }

    // url/index.php/class/method
    public function index(){
        
        $datas=$this->Admin_model->gets();

        $this->load->view('main', array('datas'=>$datas));
    }

    // url/index.php/class/method/$params
    public function create($params){
        echo $params;
    }

    // views/test.php 로드
    // www/assets/css css는 views에 포함시키지 말것
    public function store($params){
        // view 에 데이터 넘기기
        $data=array('params' => $params);

        // 뷰분기
        $this->load->view('header');

        // views/test.html 경우 $this->load->view('test.html');
        $this->load->view('test', $data);
        
        // 뷰분기
        $this->load->view('footer');
    }

    public function get($id){
        $topic=$this->Admin_model->get($id);

        $this->load->view('get', array('topic'=>$topic));
    }
}