<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Model {

    function __construct()
    {
        parent::__construct();
        $this->load->database();
   	}

    /* User Login */
    public function login($argu) {
      if(empty($argu['id']) || empty($argu['pw'])) {
        return array(
          'status' => API_FAILURE, 
          'message' => '로그인 실패',
          'data' => null
        );
      } else {
        $this->db->where('id', $argu['id']);
        $this->db->where('pw', $argu['pw']);
        $this->db->select("*");
        $this->db->from("user");
        $result = $this->db->get();
        $data = '';
        foreach( $result->result() as $row )
        {
          $data = $row->idx;
        }
        return array(
          'status' => API_SUCCESS, 
          'message' => '로그인 성공',
          'data' => $data
        );
      }
    }

     /* User Join */
    public function insert($argu) {
      if(empty($argu['id']) || empty($argu['pw']) || empty($argu['name']) || empty($argu['birth'])) {
        return array(
          'status' => API_FAILURE, 
          'message' => '회원가입 실패'        
        );
      } else {
        if(!$this->check_id($argu)) {
          $this->db->set('id', $argu['id']);
          $this->db->set('pw', $argu['pw']);
          $this->db->set('name', $argu['name']);
          $this->db->set('gender', $argu['gender']);
          $this->db->set('birth', $argu['birth']);
          $this->db->insert("user");
          $result = $this->db->get();
        
          $idx = $this->db->insert_id();

          return array(
            'status' => API_SUCCESS, 
            'message' => '로그인 성공'
          );
        } else {
          return array(
            'status' => API_INDEX_ERROR, 
            'message' => '이미 존재하는 ID입니다'        
          );
        }
        
      }
    }
    
    private function check_id($argu) {
      $this->db->where('id', $argu['id']);
      $this->db->select("*");
      $this->db->from("user");
      $result = $this->db->get();
      return $result->num_rows();
    }
}