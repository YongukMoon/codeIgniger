<?php

class FormValidation extends CI_controller
{
    public function __construct(){
        parent::__construct();
        $this->load->model('topic_model');
    }

    public function add(){
        $this->load->library('form_validation');

        $this->form_validation->set_rules('title', '제목', 'required');
        $this->form_validation->set_rules('descriptoin', '본문', 'required');

        //유효성 검사
        if($this->form_validation->run() == FALSE){
            $this->load->view('add');
        }else{

            $topic_id = $this->topic_model->add(
                $this->input->post('title'),
                $this->input->post('description')
            );

            $this->load->helper('url');
            redirect('/topic/get/'.$topic_id);
        }
    }
}