<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Md_event extends CI_Model
{
	public $newsId;

	public function __construct(){
		parent::__construct();
	}

	public function get_event_total($data){
		$tbl=$this->db->dbprefix('event');

		$key=$data['key'];

		$sql="select count(*) count from $tbl";

		if(!empty($key)){
			$sql .= " where e_title like '%$key%'";
			$sql .= " or e_content like '%$key%'";
		}

		$row=$this->db->query($sql)->row();
		return $row->count;
	}

	public function get_event_list($data){
		$tbl=$this->db->dbprefix('event');

		$key=$data['key'];
		$start = ($data['page']-1)*$data['row_cnt'];
		$limit = $data['row_cnt'];

		$sql = "select * from $tbl";

		if(!empty($key)){
			$sql .= " where e_title like '%$key%'";
			$sql .= " or e_content like '%$key%'";
		}
		
		$sql .= " order by e_regdate desc";
		$sql .= " limit $start, $limit";

		return $this->db->query($sql)->result_array();
	}

	public function event_delete($id){
		$tbl=$this->db->dbprefix('event');

		$sql="delete from $tbl where e_idx = ?";
		$this->db->query($sql, $id);
	}

	public function event_set($data){
		$tbl=$this->db->dbprefix('event');

		$sql = "insert into $tbl values(null, ?, ?, ?, ?, ?, ?, 0)";

		$this->db->query($sql, array(
			$data['m_id'],
			$data['e_title'],
			$data['e_content'],
			$data['e_sdate'],
			$data['e_edate'],
			$data['e_regdate']
			));

		return $this->db->insert_id();
	}

	public function get_event_row($data){
		$tbl=$this->db->dbprefix('event');

		$sql="select * from $tbl where e_idx = ?";

		return $this->db->query($sql, array($data['e_idx']))->row_array();
	}

	public function event_update($data){
		$tbl=$this->db->dbprefix('event');

		$sql="update $tbl set 
		e_title = ?, 
		e_content = ?, 
		e_sdate = ?, 
		e_edate = ? 
		where e_idx = ?";

		return $this->db->query($sql, array(
			$data['e_title'], 
			$data['e_content'], 
			$data['e_sdate'], 
			$data['e_edate'], 
			$data['e_idx']
			));
	}

	public function count_up($data){
		$tbl=$this->db->dbprefix('event');

		$sql="update $tbl set e_count = ? where e_idx = ?";

		return $this->db->query($sql, array(
			$data['e_count'], 
			$data['e_idx']
			));
	}

//	public function get_id($data){
//		$tbl=$this->db->dbprefix('event');
//
//		$sql="select e_idx from $tbl where e_title = ? and e_content = ?";
//
//		return $this->db->query($sql, array(
//			$data['e_title'], 
//			$data['e_content']
//			))->row_array();
//	}
    public function main_banner_total($where_str){
        $tbl=$this->db->dbprefix('event');

		$sql="select count(1) as count
			from ac_event a
			where ".$where_str;

        $row=$this->db->query($sql)->row();
        return $row->count;

    }
	public function main_banner($where_str){
		$tbl=$this->db->dbprefix('event');

		$sql="select 
			a.e_idx,
			(select a_name from ac_attach where a_tbl = 'ac_event' and a_tbl_idx = a.e_idx) as a_name,
			(select a_tbl_idx from ac_attach where a_tbl = 'ac_event' and a_tbl_idx = a.e_idx) as a_tbl_idx, a.e_sdate, a.e_edate, a.e_title
			from ac_event a
			where ".$where_str;

		return $this->db->query($sql)->result_array();
	}
}