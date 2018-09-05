<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Product_Model extends MY_Model
{

    protected $table = 'Product';

    public function allProduct( $limit = 0, $category=0)
    {
        if ($category) {
            $this->db->where('category_id', $category);
        }
        if($limit){
            $records = $this->db->get($this->table, $limit);
        } else {
            $records = $this->db->get($this->table);
        }
        return $records->result_array();
    }

    public function getProductUncat($uncategory,$limit,$startFrom)
    {
        $this->db->where('uncategory_id', $uncategory);
        $records = $this->db->get($this->table,$limit,$startFrom);
        return $records->result_array();
    }

    public function getProductCat($category,$limit,$startFrom)
    {
        $this->db->where('category_id', $category);
        $records = $this->db->get($this->table,$limit,$startFrom);
        return $records->result_array();
    }

    public function getProductAll($limit,$startFrom)
    {
        $records = $this->db->get($this->table,$limit,$startFrom);
        return $records->result_array();
    }

    public function getProduct($ID)
    {
        $this->db->where('id', $ID);
        $records = $this->db->get($this->table);
        return $records->row_array();
    }

    public function getProductArticle($article)
    {
        $this->db->where('article', $article);
        $records = $this->db->get($this->table);
        return $records->result_array();
    }

    public function createProduct ($article, $title, $price, $brand, $description, $productscat,
                                   $productsuncat, $image, $is_new, $is_recomended) {

        $data = array(
            'article' => $article,
            'title' => $title,
            'price' => $price,
            'brand' => $brand,
            'description' => $description,
            'category_id' => $productscat,
            'uncategory_id' => $productsuncat,
            'image' => $image,
            'is_new' => $is_new,
            'is_recomended' => $is_recomended,
        );

        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function updateById($id, $article, $title, $price, $brand, $description,
                               $productscat, $productsuncat, $image, $is_new, $is_recomended){
        $this->db->where('id', $id);
        $data = array(
            'article' => $article,
            'title' => $title,
            'price' => $price,
            'brand' => $brand,
            'description' => $description,
            'category_id' => $productscat,
            'uncategory_id' => $productsuncat,
            'image' => $image,
            'is_new' => $is_new,
            'is_recomended' => $is_recomended,
        );
        $this->db->update($this->table, $data);
        return $this->db->affected_rows();
    }

    public function removeProd($id) {
        $this->db->where('id', $id);
        $this->db->delete($this->table);
        return $this->db->affected_rows();
    }

    public function serchprod($param) {
        $this->db->like('title', $param, 'both');
        $this->db->or_like('description', $param, 'both');
        $this->db->or_like('brand', $param, 'both');
        $result = $this->db->get($this->table);
        return $result->result_array();
    }


    public function getNum() {
        $res = $this->db->get($this->table);
        return $res->num_rows();
    }
}