<?php
class Admin_model extends CI_Model
{
    function __construct(){
        parent::__construct();
    }

    public function gets(){
        // 객체 리턴
        // return $this->db->query('select * from admin')->result();

        // 배열리턴
        return $this->db->query('select * from admin')->result_array();
    }

    public function topic_model(){
        //db 에서 title 칼럼만 가져온다
        $this->db->select('title');

        //파일의 형식을 변환
        $this->db->select('UNIX_TIMESTAMP(created) AS created');
        // date('o년 n월 j일, G시 i분 s초', $topic->created)
        // UNIX_TIMESTAMP 를 이용해서 한국시간 출력하는 PHP 함수

        return $this->db->get_where('topic', array('id'=>$topic_id))->row();
    }

    public function get($topic_id){
        // 엑티브 레코드, 데이터베이스 변경이 용이하다
        // row() = 한건의 데이터만 가져올때 사용, 이터레이션을 하지 않아도 된다.
        // 이터레이션 프로토콜 = 데이터를 순회하기위한 프로토콜(미리 약속된 규칙)
        return $this->db->get_where('topic', array('id'=>$topic_id))->row();

        // sql문 사용, 결과는 위와 같다
        // return $this->db->query('select * from topic where id='.$topic_id);
    }
}