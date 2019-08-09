<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Md_attach extends CI_Model
{
	public function __construct(){
		parent::__construct();
	}

	public function attach_store($mdt){
		$tbl=$this->db->dbprefix('attach');

		$sql="insert into $tbl values(null, ?, ?, ?, ?, null, null, NOW())";

		for($i = 0; $i < count($mdt['a_name']); $i++){
			$this->db->query($sql, array(
				$mdt['a_tbl_idx'],
				$mdt['a_tbl'],
				$mdt['a_name'][$i],
				$mdt['a_origin_nm'][$i],
			));
		}
	}

	public function get_attach_row($a_tbl_idx, $a_tbl){
		$tbl=$this->db->dbprefix('attach');

		$sql="select * from $tbl where a_tbl_idx = ? and a_tbl = ?";

		return $this->db->query($sql, array($a_tbl_idx, $a_tbl))->result_array();
	}

	public function attach_delete($a_tbl_idx, $a_tbl){
		$tbl=$this->db->dbprefix('attach');

		$sql="delete from $tbl where a_tbl_idx = ? and a_tbl = ?";

		$this->db->query($sql, array($a_tbl_idx, $a_tbl));
	}

	public function specific_attach_delete($a_name){
		$tbl=$this->db->dbprefix('attach');

		$sql="delete from $tbl where a_name = ?";

		$this->db->query($sql, $a_name);
	}



}