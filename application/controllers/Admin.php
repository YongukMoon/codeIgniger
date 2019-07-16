<?php

class Admin extends CI_controller
{
    // url/index.php/class/method
    public function index(){
        echo "test";
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
}