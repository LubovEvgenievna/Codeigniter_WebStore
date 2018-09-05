<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Model extends CI_Model {

    protected $table = '';

    // получение всех записей из таблицы
    public function getAll($condition = array(), $limit = 0){
        $this->db->where($condition);
        if($limit){
            $records = $this->db->get($this->table, $limit);
        } else {
            $records = $this->db->get($this->table);
        }

        return $records->result_array();
    }

    // получение одной записи
    public function get($condition){
        $this->db->where($condition);
        $result = $this->db->get($this->table);
        return $result->row_array();
    }

    // вставка данных в таблицу
    public function insert($data){
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    // метод обновления данных в таблице
    public function update($condition, $data){
        $updated_at = new DateTime();
        $data['updated_at'] = $updated_at->format('Y-m-d H:i:s');
        $this->db->update($this->table, $data, $condition);
        return $this->db->affected_rows();
    }

    // удаление записи из таблицы
    public function delete($condition){
        $this->db->delete($this->table, $condition);
        return $this->db->affected_rows();
    }
}