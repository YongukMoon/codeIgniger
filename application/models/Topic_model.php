<?php
class Topic_model extends CI_Model
{
    function __construct(){
        parent::__construct();
    }

    function add($title, $description){
        //함수로 호출하기 위함
        $this->db->set('created', 'NOW()', false);

        $this->db->insert('topic', array(
            'title'=>$title,
            'description'=>$description
        ));

        //마지막으로 insert 된 data 의 id 를 가져온다
        return $this->db->insert_id();

        //쿼리 출력
        echo $this->db->last_query();

        echo $title;
    }
}