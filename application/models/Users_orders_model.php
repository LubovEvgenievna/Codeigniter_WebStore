<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Users_orders_model extends MY_Model{

    protected $table = 'Users_orders';

    public function create_order ($id, $delivery, $phone, $address, $shop, $comment, $products, $total) {

        $data = array(
            'id_users' => $id,
            'delivery' => $delivery,
            'phone' => $phone,
            'address' => $address,
            'shop_id' => $shop,
            'comment' => $comment,
            'products' => $products,
            'status' => 1,
            'total' => $total
        );

        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function getNum() {
        $res = $this->db->get($this->table);
        return $res->num_rows();
    }

    public function getOrdersAll($limit,$startFrom)
    {
        $records = $this->db->get($this->table,$limit,$startFrom);
        return $records->result_array();
    }

    public function nextStatus($status, $id){
        $this->db->where('id', $id);
        $data = array(
            'status' => $status,
        );
        $this->db->update($this->table, $data);
        return $this->db->affected_rows();
    }
}