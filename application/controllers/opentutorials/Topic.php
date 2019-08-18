<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Topic extends MY_Controller
{
    function upload_receive_from_ck(){
        $config['upload_path'] = './static/user';
        $config['allowed_type'] = 'gif|jpg|png';
        $config['max_size'] = '100';
        $config['max_width'] = '1024';
        $config['max_height'] = '768';
        $this->load->library('upload', $config);

        if( ! $this->upload->do_upload("upload")){
            $error=array('error'=>$this->upload->display_errors());
            //$this->load->view('upload_form', $error);
            // echo $this->upload->display_errors();
            echo "<script>alert('업로드에 실패했습니다.".$this->upload->display_errors('', '')."')</script>";
        }else{
            $data = array('upload_data'=>$this->upload->data());
            //$this->load->view('upload_success', $data);
            $CKEditorFuncNum = $this->input->get('CKEditorFuncNum');
            $filename = $data['filename'];
            $url = '/static/user'.$filename;
            echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction('".$CKEditorFuncNum."', '".$url."', '전송에 성곡했습니다.')</script>";
            // echo "success";
            // var_dump($data);
        }
    }

    function upload_receive(){
        $config['upload_path']='./static/user';
        $config['allowed_type']='gif|jpg|png';
        $config['max_size']='100';
        $config['max_width']='1024';
        $config['max_height']='768';
        $this->load->library('upload', $config);

        if( ! $this->upload->do_upload("user_upload_file")){
            $error=array('error'=>$this->upload->display_errors());
            //$this->load->view('upload_form', $error);
            echo $this->upload->display_errors();
        }else{
            $data=array('upload_data'=>$this->upload->data());
            //$this->load->view('upload_success', $data);
            echo "success";
            var_dump($data);
        }
    }

    function upload_form(){
        $this->_head();
        $this->load->view('upload_form');
        $this->load->view('footer');
    }

    function _head(){
        $this->load->config('opentutorials');
        $this->load->view('head');
        $topics = $this->topic_model_gets();
        $this->load->view('topic_list', array('topics'=>$topics));
    }
}