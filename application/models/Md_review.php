<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Md_review extends CI_Model
{
	public function __construct(){
		parent::__construct();
	}

	public function get_review_list($idx){
		$tbl=$this->db->dbprefix('review');

		$sql = "select a.*, ";
		$sql .= "(select m_name from ac_member where m_idx = a.m_idx) as m_name ";
		$sql .= "from $tbl a where s_idx = ?";
		$sql .= " order by r_regdate desc";

		return $this->db->query($sql, $idx)->result_array();
	}

	public function get_review_row($r_idx){
		$tbl=$this->db->dbprefix('review');

		$sql = "select * from $tbl where r_idx = ?";

		return $this->db->query($sql, $r_idx)->result_array();
	}

	public function review($data){
		$tbl=$this->db->dbprefix('review');

		$sql="insert into $tbl(s_idx, m_idx, r_content, r_regdate) value(?, ?, ?, now())";

		$this->db->query($sql, array($data['s_idx'], $data['m_idx'], $data['r_content']));

		return $this->db->insert_id();
	}

	public function review_delete($r_idx){
		$tbl=$this->db->dbprefix('review');

		$sql="delete from $tbl where r_idx = ?";

		$this->db->query($sql, $r_idx);
	}

	public function review_update($data){
		$tbl=$this->db->dbprefix('review');

		$sql="update $tbl set r_content = ? where r_idx = ?";

		$this->db->query($sql, array($data['r_content'], $data['r_idx']));
	}

	public function my_review_list($useridx){
		$tbl=$this->db->dbprefix('review');

		$sql = "select a.*, ";
		$sql .= "(select s_name from ac_shop where s_idx = a.s_idx) as s_name ";
		$sql .= "from $tbl a where m_idx = ?";
		$sql .= " order by r_regdate desc";

		return $this->db->query($sql, $useridx)->result_array();
	}
}