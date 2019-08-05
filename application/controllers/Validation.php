<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Validation extends CI_Controller {

	public function add()
	{
        // private function 은 class 안에서 사용 가능
        $this->_head();

        //post 로 전송된 data
        $data['title'] = $this->input->post('title');
        $data['description'] = $this->input->post('description');
    }
    
    function _head(){
        // private function
        // uri routing 되지 않는다.

        echo 'reached';
    }

}
