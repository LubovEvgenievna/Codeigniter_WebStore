<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Account extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Users_model');
        $this->load->library('email');
    }

    public function index() {

        $name = $this->input->post('login');
        $password = $this->input->post('password');
        $message = 0;

        $passwordx = sha1($password);

        $query = $this->Users_model->getAll(array('email' => $name, 'password' => $passwordx), 1);
        $errors = array();

        if($name){
            if (!empty($query)) {

                if ($query[0]['activated']) {
                    $data = array(
                        'id' => $query[0]['id'],
                        'username' => $name,
                        'is_logged_in' => true
                    );

                    $this->session->set_userdata($data);
                    redirect('account/accountorders');
                } else {
                    $message = "Учетная запись не активирована.";

                    $this->setData('errors', $errors);
                    $this->setData('message', $message);
                    $this->setData('title', 'Личный кабинет');
                    $this->display('frontend/account/enter');
                }


            } else {
                $errors[] = 'Неверный логин или пароль.';
            }
        }

        $this->setData('errors', $errors);
        $this->setData('message', $message);
        $this->setData('title', 'Личный кабинет');
        $this->display('frontend/account/enter');
    }

    public function logof(){
        $this->session->sess_destroy('username');
        redirect('account');
    }

    public function accountred()
    {
        if($this->session->has_userdata('username')){
            $id = $this->session->userdata('id');

            $query = $this->Users_model->getAll(array('id'=>$id));

            $users_datas = array(
                'lastname'   => $query[0]['lastname'],
                'name'   => $query[0]['name'],
                'email'   => $query[0]['email']
            );

            $errors = array();
            $message = false;

            if (isset($_POST['submit'])) {
                $lastname = $this->input->post('lastname');
                $email = $this->input->post('email');
                $name = $this->input->post('name');

                $users_datas = array(
                    'lastname'   => $lastname,
                    'name'   => $name,
                    'email'   => $email
                );

                $config = array(
                    array(
                        'field'   => 'name',
                        'label'   => 'Имя',
                        'rules'   => 'trim|max_length[50]'
                    ),
                    array(
                        'field'   => 'lastname',
                        'label'   => 'Фамилия',
                        'rules'   => 'trim|max_length[50]'
                    ),
                    array(
                        'field'   => 'email',
                        'label'   => 'email',
                        'rules'   => 'trim|valid_email|max_length[50]'
                    ),
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

                if ($errors == false) {

                    if($lastname) {
                        $this->Users_model->update_acc($id, array('lastname'=>$lastname));
                        $message = true;
                    }

                    if($email) {
                        $this->Users_model->update_acc($id, array('email'=>$email));
                        $message = true;
                    }

                    if($name) {
                        $this->Users_model->update_acc($id, array('name'=>$name));
                        $message = true;
                    }

                    if($message) {
                        $errors[] = 'Изменения внесены';
                    }

                }

            }

            $this->setData('users_data', $users_datas);
            $this->setData('errors', $errors);
            $this->setData('title', 'Личный кабинет');
            $this->display('frontend/account/accountred');
        } else {
            redirect('account');
        }

    }

    public function accountpassred()
    {
        if($this->session->has_userdata('username')){
            $users_datas = array(
                'password'   => '',
                'conf_password'   => ''
            );

            $errors = array();

            if (isset($_POST['submit'])) {
                $password = $this->input->post('password');

                $config = array(
                    array(
                        'field'   => 'password',
                        'label'   => 'Пароль',
                        'rules'   => 'trim|required|max_length[32]'
                    ),
                    array(
                        'field'   => 'conf_password',
                        'label'   => 'Подтверждение пароля',
                        'rules'   => 'trim|required|matches[password]'
                    ),
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

                if ($errors == false) {
                    $id = $this->session->userdata('id');

                    $this->Users_model->update_acc($id, array('password'=>sha1($password)));
                    $errors[] = 'Пароль изменен';
                }

            }

            $this->setData('users_data', $users_datas);
            $this->setData('errors', $errors);
            $this->setData('title', 'Личный кабинет');
            $this->display('frontend/account/accountpassred');
        } else {
            redirect('account');
        }

    }

    public function accountorders()
    {
        if ($this->session->has_userdata('username')) {
            $this->load->model('Users_orders_model');
            $myorders = $this->Users_orders_model->getAll();
            $this->load->model('Product_model');
            $prodabout=$this->Product_model->getAll();

            $prod = array();
            $products = array();
            $i=0;
            foreach ($myorders as $value) {
                foreach ($value as $item) {
                    $prod[$value['id']] = json_decode($value['products'], true);
                }
            }

            $ords = array_keys($prod);
            foreach ($prod as $value) {
                $ids = array_keys($value);
                $k=0;
                foreach ($value as $item) {
                    $products[] = array(
                        'id' => $ids[$k],
                        'qty' => $item,
                        'ord' => $ords[$i]
                    );
                    $k++;
                }
                $i++;
            }

            $this->setData('myorders',$myorders);
            $this->setData('products',$products);
            $this->setData('prodabout',$prodabout);
            $this->setData('title', 'Личный кабинет');
            $this->display('frontend/account/accountorders');
        } else {
            redirect('account');
        }
    }
}