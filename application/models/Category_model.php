<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Category_Model extends MY_Model
{

    protected $table = 'Category';

    public function getCategory()
    {
        $this->db->order_by("sort_order", "asc");
        $records = $this->db->get($this->table);
        return $records->result_array();
    }

    public function getById($id)
    {
        $this->db->where('id', $id);
        $records = $this->db->get($this->table);
        return $records->row_array();
    }

    public function updateById($id, $title, $sort_order){
        $this->db->where('id', $id);
        $data = array(
            'title' => $title,
            'sort_order' => $sort_order,
        );
        $this->db->update($this->table, $data);
        return $this->db->affected_rows();
    }

    public function removeCat($id) {
        $this->db->where('id', $id);
        $this->db->delete($this->table);
        return $this->db->affected_rows();
    }

    public function addCat($title, $sort_order){
        $data = array(
            'title' => $title,
            'sort_order' => $sort_order,
        );
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }
}