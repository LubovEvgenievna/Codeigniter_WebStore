<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adminproductsadd extends MY_Controller
{
    public function selectCat() {
        $id = $_POST['id'];

        $this->load->model('Uncategory_model');
        $Cat = $this->Uncategory_model->getByCategory($id);

        echo json_encode($Cat);
    }

    public function addProduct() {
        if ($this->checkAdmin()) {
            $errors = array();

            $this->load->model('Category_model');
            $this->load->model('Product_model');
            $productscats = $this->Category_model->getAll();

            $article = $this->input->post('article');
            $title = $this->input->post('title');
            $price = $this->input->post('price');
            $brand = $this->input->post('brand');
            $description = $this->input->post('description');
            $productscat = $this->input->post('productscat');
            $productsuncat = $this->input->post('productsuncat');
            $image = $this->input->post('image');
            $is_new = $this->input->post('is_new');
            $is_recomended = $this->input->post('is_recomended');

            $products_datas = array(
                'article'   => '',
                'title'   => '',
                'price' => '',
                'brand' => '',
                'description'   => '',
                'productscat'   => '',
                'productsuncat'   => '',
                'image'   => '',
                'is_new'   => '',
                'is_recomended'   => ''
            );

            if (isset($_POST['submit'])) {
                $products_datas = array(
                    'article'   => $article,
                    'title'   => $title,
                    'price'   => $price,
                    'brand'   => $brand,
                    'description'   => $description,
                    'productscat'   => $productscat,
                    'productsuncat'   => $productsuncat,
                    'image'   => $image,
                    'is_new' => $is_new,
                    'is_recomended' => $is_recomended
                );


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

                if ($productscat == '0') {
                    $errors[] = 'Поле Категория обязательно.';
                }

                $this->load->library('form_validation');
                $this->load->helper('security');
                $this->form_validation->set_rules($config);

                if ($this->form_validation->run() == FALSE) {
                    $valid_errors[] = $this->form_validation->error_array();
                    foreach ($valid_errors as $value) {
                        foreach ($value as $key) {
                            $errors[]=$key;
                        }
                    }
                }

                if ($this->Product_model->getProductArticle($article)) {
                    $errors[] = 'Такой АРТИКЛЬ уже существует.';
                }

                $this->load->helper(array('form', 'url'));

                $config['upload_path']          = 'assets/images/product/';
                $config['allowed_types']        = 'gif|jpg|png';
                $config['max_width']            = 2048;

                $this->load->library('upload', $config);

                if ( ! $this->upload->do_upload('image'))
                {
                    $errors[] = 'Изображение обязательно';
                }
                else
                {
                    $data = array('upload_data' => $this->upload->data());
                    $image = $data['upload_data']['file_name'];
                }

                if ($errors == false) {


                    $this->Product_model->createProduct($article, $title, $price, $brand, $description,
                        $productscat, $productsuncat, $image, $is_new, $is_recomended);

                    $errors[] = 'Товар добавлен!!!';

                }
            }


            $this->setData('productscats', $productscats);
            $this->setData('products_datas', $products_datas);
            $this->setData('errors', $errors);
            $this->setData('title', 'Админпанель');
            $this->display('backend/admin_products/addproduct');
        } else {

            die('Необходимо войти в систему под учетной записью администратора.');
        }
    }
}