<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Catalog extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Category_model');
        $category = $this->Category_model->getCategory();
        $this->setData('category',$category);

        $this->load->model('Uncategory_model');
        $uncategory = $this->Uncategory_model->getUncategory();
        $this->setData('uncategory',$uncategory);

        $this->load->model('Product_model');

        $this->setData('title', 'Каталог');
    }

    public function showProduct($cat=0)
    {
        $product = $this->Product_model->allProduct(6, $cat);
        $this -> setData('product', $product);
        $this -> setData('cat_id', $cat);
        if ($cat) {
            $this -> setData('cat', 'Cat');
        } else {
            $this -> setData('cat', 'All');
        }

        $this->display('frontend/catalog/index');
    }

    public function showProductUncat() {

        $uncategory = $_POST['cat'];
        $startFrom = $_POST['startFrom'];
        $limit = 6;

        $product = $this->Product_model->getProductUncat($uncategory,$limit,$startFrom);

        echo json_encode($product);
    }

    public function showProductCat()
    {
        $category = $_POST['cat'];
        $startFrom = $_POST['startFrom'];
        $limit = 6;

        //print_r($category);

        $product = $this->Product_model->getProductCat($category,$limit,$startFrom);

        echo json_encode($product);
    }

    public function showProductAll()
    {
        $startFrom = $_POST['startFrom'];
        $limit = 6;

        $product = $this->Product_model->getProductAll($limit,$startFrom);

        echo json_encode($product);
    }

    public function getProd()
    {
        $id = $_POST['id'];
        $prod = $this->Product_model->getProduct($id);

        echo json_encode($prod);
    }

    public function searchprod()
    {
        $param = $this->input->post('param');
        $product = $this->Product_model->serchprod($param);

        echo json_encode($product);
    }
}