<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Slider_model extends MY_Model{

    protected $table = 'Slider';

    public function updateById($id , $img)
    {
        $this->db->where('id', $id);
        $data = array(
            'img' => $img,
        );
        $this->db->update($this->table, $data);
        return $this->db->affected_rows();
    }

}