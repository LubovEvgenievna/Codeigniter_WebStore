<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Users_model extends MY_Model{

    protected $table = 'Users';

    public function create_user ($name, $password, $email, $lastname, $activation_code) {

        $data = array(
            'name' => $name,
            'password' => sha1($password),
            'email' => $email,
            'lastname' => $lastname,
            'code' => $activation_code,
        );

        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function getEmail($email){
        $this->db->where('email', $email);
        $records = $this->db->get($this->table);
        return $records->result_array();
    }

    public function getCode($activation_code){
        $this->db->where('code', $activation_code);
        $records = $this->db->get($this->table);
        return $records->result_array();
    }

    public function activating($activation_code){
        $this->db->where('code', $activation_code);
        $this->db->set('activated', '1');
        $this->db->update($this->table);
        return $this->db->insert_id();
    }

    public function fogot_pass($code, $password) {
        $this->db->where('code', $code);
        $this->db->set('password', $password);
        return $this->db->update($this->table);
    }

    public function create_reset_pass ($email, $code) {
        $this->db->where('email', $email);
        $this->db->set('code', $code);
        $this->db->update($this->table);
        return $this->db->insert_id();
    }

    public function update_acc ($id, $condition = array()) {
        $this->db->where('id', $id);
        $this->db->set($condition);
        $this->db->update($this->table);
        return $this->db->insert_id();
    }
}