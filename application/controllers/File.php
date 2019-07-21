<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class File extends CI_Controller {
	public function index()
	{
		$this->load->view('input_file');
    }
    
    public function store(){
        $file=$_FILES['attach'];
        print_r($file);

        // 파일 이동
        $uploads_dir=$_SERVER['DOCUMENT_ROOT'].'/upload';
        $name=$file['name'];
        move_uploaded_file( $file['tmp_name'], "$uploads_dir/$name");

        print_r($uploads_dir);
    }
}
