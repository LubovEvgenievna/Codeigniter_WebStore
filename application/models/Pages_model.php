<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Pages_model extends MY_Model{

    protected $table = 'Pages';

    public function getPage($ID)
    {
        $this->db->where('id', $ID);
        $records = $this->db->get($this->table);
        return $records->row_array();
    }

    public function getByName($name)
    {
        $this->db->where('name', $name);
        $records = $this->db->get($this->table);
        return $records->row_array();
    }


    public function updatePage($name, $text)
    {
        $this->db->where('name', $name);
        $data = array(
            'text' => $text,
        );
        $this->db->update($this->table, $data);
        return $this->db->affected_rows();
    }

    public function addPage($name, $title)
    {
        $data = array(
            'name' => $name,
            'title' => $title,
        );

        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function removePage($id)
    {
        $this->db->where('id', $id);
        $this->db->delete($this->table);
        return $this->db->affected_rows();
    }

}