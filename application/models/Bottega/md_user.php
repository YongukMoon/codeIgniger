<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class md_user extends CI_Model
{

	public function __construct(){
		parent::__construct();
	}

	public function login($u_id, $u_pw){
		$tbl = $this->db->dbprefix('user');
		$where = array('u_id'=>$u_id, 'u_pw'=>$u_pw);
		return $this->db->get_where($tbl, $where)->row_array();
	}
}