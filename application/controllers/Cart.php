<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cart extends MY_Controller {

    public function index(){
        $this->load->library('cart');
        $contents = $this->cart->contents();

        $form_html = $this->renderHTML('cart_form', $contents, true);
        $this->setData('title', 'Корзина');
        $this->setData('cart_form', $form_html);
        $this->setData('cart', $contents);

        $this->display('frontend/cart/index');
    }

    function addAjax($ID) {

        $this->load->model('Product_model');
        $product = $this->Product_model->getProduct($ID);

        $data = array(
            'id' => $product['id'],
            'qty' => 1,
            'price' => $product['price'],
            'name' => $product['title'],
        );

        $this->cart->insert($data);

        $total = $this->cart->total_items();

        echo($total);
    }

    function delete($id)
    {
        $this->cart->remove($id);
        redirect('cart');
    }

    function clear_cart()
    {
        $this->cart->destroy();
        redirect('cart');
    }

    public function startorder()
    {
        $errors = array();
        if (isset($_POST['submit']))
        {
            if ($this->session->has_userdata('username'))
            {
                if($this->cart->contents()){
                    $phone = $this->input->post('phone');
                    $comment = $this->input->post('comment');
                    $address = $this->input->post('address');
                    $shop = $this->input->post('shop');

                    $users_datas = array(
                        'phone'   => $phone,
                        'comment'   => $comment,
                        'address'   => $address,
                        'shop'   => $shop,
                    );

                    $config = array(
                        array(
                            'field'   => 'phone',
                            'label'   => 'Номер телефона',
                            'rules'   => 'trim|required|max_length[50]'
                        ),
                        array(
                            'field'   => 'comment',
                            'label'   => 'Коментарий',
                            'rules'   => 'trim|max_length[255]'
                        ),
                        array(
                            'field'   => 'address',
                            'label'   => 'Адрес',
                            'rules'   => 'trim|max_length[255]'
                        ),
                        array(
                            'field'   => 'shop',
                            'label'   => 'Магазин',
                            'rules'   => 'trim|max_length[255]'
                        )
                    );

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

                    if ($errors == false)
                    {
                        $id = $id = $this->session->userdata('id');
                        $total = $this->cart->total();

                        if ($address) {
                            $delivery = '1';
                            $shop = ' ';
                        } else {
                            $delivery = '0';
                            $address = ' ';
                        }

                        $prod=array();

                        $prd[] = $this->cart->contents();
                        foreach ($prd as $value) {
                            foreach ($value as $key) {
                                $prod[$key['id']]=$key['qty'];
                            }
                        }

                        $products = json_encode($prod);

                        $this->load->model('Users_orders_model');
                        $this->Users_orders_model->create_order($id, $delivery, $phone, $address, $shop, $comment,$products,$total);
                        $this->cart->destroy();
                        redirect('account/accountorders');
                    }

                    $this->load->model('Shop_model');
                    $shop = $this->Shop_model->getAll();
                    $contents = $this->cart->contents();
                    $form_html = $this->renderHTML('order_form', $contents, true);
                    $this->setData('order_form', $form_html);
                    $this->setData('shop', $shop);
                    $this->setData('title', 'Корзина');
                    $this->setData('errors', $errors);
                    $this->setData('users_datas', $users_datas);
                    $this->display('frontend/cart/orderpage');
                } else {
                    $errors[]='Ваша корзина пуста! Заказ не может быть оформлен';

                    $this->load->model('Shop_model');
                    $shop = $this->Shop_model->getAll();
                    $contents = $this->cart->contents();
                    $form_html = $this->renderHTML('order_form', $contents, true);
                    $this->setData('order_form', $form_html);
                    $this->setData('shop', $shop);
                    $this->setData('title', 'Корзина');
                    $this->setData('errors', $errors);
                    $this->display('frontend/cart/orderpage');
                }
            } else {
                $errors[]='Для оформления заказа необходимо войти в личный кабинет!';

                $this->load->model('Shop_model');
                $shop = $this->Shop_model->getAll();
                $contents = $this->cart->contents();
                $form_html = $this->renderHTML('order_form', $contents, true);
                $this->setData('order_form', $form_html);
                $this->setData('shop', $shop);
                $this->setData('title', 'Корзина');
                $this->setData('errors', $errors);
                $this->display('frontend/cart/orderpage');
            }
        } else {
            $this->load->model('Shop_model');
            $shop = $this->Shop_model->getAll();
            $contents = $this->cart->contents();
            $form_html = $this->renderHTML('order_form', $contents, true);
            $this->setData('order_form', $form_html);
            $this->setData('shop', $shop);
            $this->setData('title', 'Корзина');
            $this->setData('errors', $errors);
            $this->display('frontend/cart/orderpage');
        }
    }
}