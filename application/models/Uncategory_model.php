<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Uncategory_Model extends MY_Model
{

    protected $table = 'Uncategory';

    public function getUncategory()
    {
        $this->db->order_by("sort_order", "asc");
        $records = $this->db->get($this->table);
        return $records->result_array();
    }

    public function getByCategory($id)
    {
        $this->db->where('category_id', $id);
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

    public function removeUncat($id) {
        $this->db->where('id', $id);
        $this->db->delete($this->table);
        return $this->db->affected_rows();
    }

    public function addUncat($title, $category_id, $sort_order){
        $data = array(
            'title' => $title,
            'category_id' => $category_id,
            'sort_order' => $sort_order,
        );
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }
}