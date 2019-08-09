<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Md_news extends CI_Model
{
	public $newsId;

	public function __construct(){
		parent::__construct();
	}

	public function get_news_total($data){
		$tbl=$this->db->dbprefix('news');

		$key=$data['key'];

		$sql="select count(*) count from $tbl";

		if(!empty($key)){
			$sql .= " where title like '%$key%'";
			$sql .= " or content like '%$key%'";
		}

		$row=$this->db->query($sql)->row();
		return $row->count;
	}

	public function get_news_list($data){
		$tbl=$this->db->dbprefix('news');

		$key=$data['key'];
		$start = ($data['page']-1)*$data['row_cnt'];
		$limit = $data['row_cnt'];

		$sql = "select * from $tbl";

		if(!empty($key)){
			$sql .= " where title like '%$key%'";
			$sql .= " or content like '%$key%'";
		}
		
		$sql .= " order by n_ragdate desc";
		$sql .= " limit $start, $limit";

		return $this->db->query($sql)->result_array();
	}

	public function news_delete($newsId){
		$tbl=$this->db->dbprefix('news');

		$sql="delete from $tbl where id = ?";
		$this->db->query($sql, $newsId);
	}

	public function news_set($mdt){
		$tbl=$this->db->dbprefix('news');

		$sql = "insert into $tbl values(null, ?, ?, ?, ?, 0)";

		$this->db->query($sql, array(
			$mdt['adm_id'],
			$mdt['title'],
			$mdt['content'],
			$mdt['n_ragdate']
			));

		return $this->db->insert_id();
	}

	public function get_news_row($mdt){
		$tbl=$this->db->dbprefix('news');

		$sql="select * from $tbl where id = ?";

		return $this->db->query($sql, array($mdt['news_id']))->row_array();
	}

	public function news_update($mdt){
		$tbl=$this->db->dbprefix('news');

		$sql="update $tbl set 
		title = ?, 
		content = ? 
		where id = ?";

		return $this->db->query($sql, array(
			$mdt['title'], 
			$mdt['content'], 
			$mdt['news_id']
			));
	}

	public function count_up($data){
		$tbl=$this->db->dbprefix('news');

		$sql="update $tbl set 
		n_count = ? 
		where id = ?";

		return $this->db->query($sql, array(
			$data['n_count'], 
			$data['id']
			));
	}

//	public function get_id($mdt){
//		$tbl=$this->db->dbprefix('news');
//
//		$sql="select id from $tbl where title = ? and content = ?";
//
//		return $this->db->query($sql, array($mdt['title'], $mdt['content']))->row_array();
//	}
}