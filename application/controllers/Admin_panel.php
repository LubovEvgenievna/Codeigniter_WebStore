<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_panel extends MY_Controller
{
    public function index()
    {
        $this->load->model('Users_model');

        $name = $this->input->post('login');
        $password = $this->input->post('password');

        $passwordx = sha1($password);

        $query = $this->Users_model->getAll(array('email' => $name, 'password' => $passwordx), 1);
        $errors = array();

        if($name){
            if (!empty($query)) {
                if ($query[0]['admin']) {
                    $data = array(
                        'id' => $query[0]['id'],
                        'username' => $name,
                        'is_logged_in' => true
                    );
                    $this->session->set_userdata($data);
                    redirect('adminproducts/prod');
                }
            }
            else {
                $errors[] = 'Неверный логин или пароль.';
            }
        }

        $this->setData('errors', $errors);
        $this->setData('title', 'Админпанель');
        $this->display('backend/admin_panel/index');
    }

    public function logof(){
        $this->session->sess_destroy('username');
        redirect('admin_panel');
    }
}