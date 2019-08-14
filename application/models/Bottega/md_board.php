<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class md_board extends CI_Model
{
	public function __construct(){
		parent::__construct();
	}

	public function board_store($tbl, $data){
		$tbl = $this->db->dbprefix($tbl);
		$this->db->insert($tbl, $data);
	}

	public function board_list($tbl, $data){
		$tbl = $this->db->dbprefix($tbl);

		$start = ($data['page']-1)*$data['row_cnt'];
		$limit = $data['row_cnt'];

		if(!empty($data['cate'])){
			$this->db->where('cate', $data['cate']);
		}

		if(!empty($data['key'])){
			$key = $data['key'];
			$where = "(title like '%$key%' or content like '%$key%')";
			$this->db->where($where);
		}

		$this->db->limit($limit, $start);
		$this->db->order_by('idx', 'desc');
		return $this->db->get($tbl)->result_array();
	}

	public function board_total($tbl, $data){
		if(!empty($data['cate'])){
			$this->db->where('cate', $data['cate']);
		}

		if(!empty($data['key'])){
			$key = $data['key'];
			$where = "(title like '%$key%' or content like '%$key%')";
			$this->db->where($where);
		}

		$tbl = $this->db->dbprefix($tbl);
		return $this->db->get($tbl)->result_array();
	}

	public function board_row($tbl, $idx){
		$tbl = $this->db->dbprefix($tbl);
		return $this->db->get_where($tbl, array('idx' => $idx))->row_array();
	}

	public function board_update($tbl, $idx, $data){
		$tbl = $this->db->dbprefix($tbl);
		$this->db->update($tbl, $data, array('idx' => $idx));
	}

	public function board_delete($tbl, $idx){
		$tbl = $this->db->dbprefix($tbl);
		$this->db->delete($tbl, array('idx' => $idx));
	}
}