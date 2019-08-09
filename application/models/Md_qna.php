<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Md_qna extends CI_Model
{
	public function __construct(){
		parent::__construct();
	}

	public function get_qna_list($data,$where=""){
		$tbl=$this->db->dbprefix('qna');

		$flag = $data['q_flag'];
		$key = $data['key'];
		$sdate = $data['sdate'];
		$edate = $data['edate'];

		$start = ($data['page']-1)*$data['row_cnt'];
		$limit = $data['row_cnt'];

		$sql="select * from $tbl where 1=1".$where;

		if($flag != ''){
			$sql .= " and q_flag = $flag";
		}

		if(!empty($key)){
			$sql .= " and q_title like '%$key%'";
			$sql .= " or q_content like '%$key%'";
		}

		if(!empty($sdate)){
			if(!empty($edate)){
				$sql .= " and DATE_FORMAT(q_regdate, '%Y-%m-%d') BETWEEN '$sdate' AND '$edate'";
			}else{
				$sql .= " and DATE_FORMAT(q_regdate, '%Y-%m-%d') BETWEEN '$sdate' AND '$sdate'";
			}
		}

		$sql .= " order by q_regdate desc";
		$sql .= " limit $start, $limit";

		return $this->db->query($sql)->result_array();
	}

	public function get_qna_row($id){
		$tbl=$this->db->dbprefix('qna');
		$sql="select * from $tbl where q_idx = ?";
		return $this->db->query($sql, array($id))->row_array();
	}

	public function comment_store($data){
		$tbl=$this->db->dbprefix('qna');
		$q_idx=$data['q_idx'];

		$sql="update $tbl set 
		q_comment = ?, 
		q_flag = ?, 
		q_com_date = ? 
		where q_idx = ?";

		return $this->db->query($sql, array(
			$data['q_comment'], 
			$data['q_flag'], 
			$data['q_com_date'], 
			$data['q_idx']
			));
	}

	public function get_qna_total($data,$where=""){
		$tbl=$this->db->dbprefix('qna');

		$key=$data['key'];
		$q_flag=$data['q_flag'];
		$sdate = $data['sdate'];
		$edate = $data['edate'];

		$sql="select count(*) count from $tbl where 1=1".$where;

		if($q_flag != ''){
			$sql .= " and q_flag = '$q_flag'";
		}

		if(!empty($sdate)){
			if(!empty($edate)){
				$sql .= " and DATE_FORMAT(q_regdate, '%Y-%m-%d') BETWEEN '$sdate' AND '$edate'";
			}else{
				$sql .= " and DATE_FORMAT(q_regdate, '%Y-%m-%d') BETWEEN '$sdate' AND '$sdate'";
			}
		}

		if(!empty($key)){
			$sql .= " and q_title like '%$key%'";
			$sql .= " or q_content like '%$key%'";
        }

		$row=$this->db->query($sql)->row();
		return $row->count;
    }
    
    public function get_user_qna($mdt){
        $tbl = $this->db->dbprefix("qna");
		$this->db->insert($tbl, $mdt);
        
        $sql = "select max(q_idx) idx from $tbl";
		$key = $this->db->query($sql)->row()->idx;

		return $key;
		
    }
}