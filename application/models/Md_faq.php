<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Md_faq extends CI_Model
{
	public function __construct(){
		parent::__construct();
	}

	public function faq_set($data){
		$tbl=$this->db->dbprefix('faq');

		$sql="insert into $tbl values(null, ?, ?, ?, ?, null, now())";

		$this->db->query($sql, array(
			$data['m_id'],
			$data['f_type'],
			$data['f_title'],
			$data['f_content']
		));

		return $this->db->insert_id();
	}

//	public function get_id($data){
//		$tbl=$this->db->dbprefix('faq');
//
//		$sql="select f_idx from $tbl where f_content = ?";
//
//		return $this->db->query($sql, array($data['f_content']))->row_array();
//	}

	public function get_faq_list($data){
		$tbl=$this->db->dbprefix('faq');

		$f_type=$data['f_type'];
		$key=$data['key'];
		$start = ($data['page']-1)*$data['row_cnt'];
		$limit = $data['row_cnt'];

		$sql = "select * from $tbl where 1=1 ";

		if(!empty($f_type)){
			$sql .= " and f_type = '$f_type'";
		}
		
		if(!empty($key)){
			$sql .= " and f_title like '%$key%'";
			$sql .= " or f_content like '%$key%'";
		}

		$sql .= " order by f_regdate desc";
		$sql .= " limit $start, $limit";

		return $this->db->query($sql)->result_array();
	}

	public function get_faq_total($data=null){
		$tbl=$this->db->dbprefix('faq');

		$key=$data['key'];
		$f_type=$data['f_type'];

		$sql = "select count(*) count from $tbl where 1=1";
		
		if(!empty($f_type)){
			$sql .= " and f_type = '$f_type'";
		}

		if(!empty($key)){
			$sql .= " and f_title like '%$key%'";
			$sql .= " or f_content like '%$key%'";
		}

		$row=$this->db->query($sql)->row();
		return $row->count;
	}

	public function get_faq_row($data){
		$tbl=$this->db->dbprefix('faq');
		$sql="select * from $tbl where f_idx = ?";
		return $this->db->query($sql, array($data['f_idx']))->row_array();
	}

	public function count_up($data){
		$tbl=$this->db->dbprefix('faq');

		$sql="update $tbl set f_count = ? where f_idx = ?";

		return $this->db->query($sql, array($data['f_count'], $data['f_idx']));
	}

	public function faq_update($data){
		$tbl=$this->db->dbprefix('faq');

		$sql="update $tbl set 
		f_type = ?, 
		f_title = ?, 
		f_content = ? 
		where f_idx = ?";

		return $this->db->query($sql, array(
			$data['f_type'],
			$data['f_title'], 
			$data['f_content'], 
			$data['f_idx']
			));
	}

	public function faq_delete($f_idx){
		$tbl=$this->db->dbprefix('faq');
		$sql="delete from $tbl where f_idx = ?";
		$this->db->query($sql, $f_idx);
	}
}