<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adminproducts extends MY_Controller
{
    public function prod()
    {
        if ($this->checkAdmin()) {
            $this->load->model('Product_model');
            $productsnum = $this->Product_model->getNum();

            $this->load->model('Category_model');
            $productscats = $this->Category_model->getAll();

            $this->load->model('Uncategory_model');
            $productsuncats = $this->Uncategory_model->getAll();

            $this->load->library('pagination');

            $config['base_url'] = 'http://hookan.local/adminproducts/prod';
            $config['total_rows'] = $productsnum;
            $config['per_page'] = 60;

            $this->pagination->initialize($config);


            $products = $this->Product_model->getProductAll($config['per_page'],$this->uri->segment(3));


            $links = $this->renderHTML('links', $contents=0, true);
            $this->setData('links', $links);

            $this->setData('products', $products);
            $this->setData('productscats', $productscats);
            $this->setData('productsuncats', $productsuncats);
            $this->setData('title', 'Админпанель');
            $this->display('backend/admin_products/index');
        } else {

            die('Необходимо войти в систему под учетной записью администратора.');
        }
    }

    public function safeprod()
    {
        if ($this->checkAdmin()) {
            $id = $this->input->post('id');
            $article = $this->input->post('article');
            $title = $this->input->post('title');
            $price = $this->input->post('price');
            $brand = $this->input->post('brand');
            $description = $this->input->post('description');
            $productscat = $this->input->post('productscat');
            $productsuncat = $this->input->post('productsuncat');
            $is_new = $this->input->post('is_new');
            $is_recomended = $this->input->post('is_recomended');

            $image = '';

            $prod = $this->Product_model->getProduct($id);

            $config = array(
                array(
                    'field'   => 'article',
                    'label'   => 'Артикль',
                    'rules'   => 'trim|required'
                ),
                array(
                    'field'   => 'title',
                    'label'   => 'Название',
                    'rules'   => 'trim|required|max_length[50]'
                ),
                array(
                    'field'   => 'price',
                    'label'   => 'Цена',
                    'rules'   => 'trim|required'
                ),
                array(
                    'field'   => 'brand',
                    'label'   => 'Бренд',
                    'rules'   => 'trim|required'
                ),
                array(
                    'field'   => 'description',
                    'label'   => 'Описание',
                    'rules'   => 'trim|required'
                )
            );

            $this->load->library('form_validation');
            $this->load->helper('security');
            $this->form_validation->set_rules($config);

            $this->load->helper(array('form', 'url'));

            $this->load->library('upload');

            $config['upload_path']          = 'assets/images/product/';
            $config['allowed_types']        = 'gif|jpg|png';
            $config['max_width']            = 2048;

            $this->upload->initialize($config);

            if ( $this->upload->do_upload('image'))
            {
                unlink('assets/images/product/' . $prod['image']);

                $data = array('upload_data' => $this->upload->data());
                $image = $data['upload_data']['file_name'];
            } else {
                $image = $prod['image'];
            }

            if ($this->form_validation->run()) {
                $this->load->model('Product_model');
                $this->Product_model->updateById($id, $article, $title, $price, $brand, $description,
                    $productscat, $productsuncat, $image, $is_new, $is_recomended);

                redirect('adminproducts/prod');
            } else {

                redirect('adminproducts/prod');
            }

        } else {

            die('Необходимо войти в систему под учетной записью администратора.');
        }
    }

    public function removeproduct($id) {
        $prod = $this->Product_model->getProduct($id);
        unlink('assets/images/product/' . $prod['image']);


        $this->Product_model->removeProd($id);

        redirect('adminproducts/prod');
    }
}