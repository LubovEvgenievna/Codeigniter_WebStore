<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Registration extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Users_model');
        $this->load->library('email');
    }

    public function register(){

        $users_datas = array(
            'lastname'   => '',
            'name'   => '',
            'email'   => '',
            'password'   => '',
            'conf_password'   => ''
        );

        $errors = array();

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
                    'rules'   => 'trim|required|max_length[50]'
                ),
                array(
                    'field'   => 'lastname',
                    'label'   => 'Фамилия',
                    'rules'   => 'trim|max_length[50]'
                ),
                array(
                    'field'   => 'email',
                    'label'   => 'email',
                    'rules'   => 'trim|required|valid_email|max_length[50]'
                ),
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

            if ($this->Users_model->getEmail($email)) {
                $errors[] = 'Такой e-mail уже зарегистрирован.';
            }

            if ($errors == false) {
                $lastname = $this->input->post('lastname');
                $email = $this->input->post('email');
                $name = $this->input->post('name');
                $password = $this->input->post('password');
                $activation_code = sha1(mt_rand(10000, 99999) . time() . $email);
                $this->Users_model->create_user($name, $password, $email, $lastname, $activation_code);
                $userid = $this->db->insert_id();
                $this->session->set_userdata('userID', $userid);

                $from = 'admin@codeigniterwebstore.webaddiction.ru';

                $this->email->from($from,'http://codeigniterwebstore.webaddiction.ru');
                $this->email->to($email);
                $this->email->subject('Активация');
                $this->email->message('Пожалуйста, подтвердите вашу учетную запись, пройдя по ссылке:   ' . 'http://codeigniterwebstore.webaddiction.ru/registration/activate/' . $activation_code);
                $this->email->send();

                $errors[] = 'Благодарим за регистрацию! На Ваш почтовый ящик было отправлено письмо с данными для активации.';
            }

        }

        $this->setData('title', 'Регистрация');
        $this->setData('users_data', $users_datas);
        $this->setData('errors', $errors);
        $this->display('frontend/registration/index');
    }

    function confirm_registration($activation_code) {

        if ($this->Users_model->getCode($activation_code)) {
            $this->Users_model->activating($activation_code);
            return true;
        }
        else {
            return false;
        }
    }

    function activate() {

        $registration_code = $this->uri->segment(3);

        $confirmed = $this->confirm_registration($registration_code);

        if ($confirmed) {
            $message = ("Ваша учетная запись подтверждена. Благодарим за регистрацию!");
            $enter = 1;
        }
        else {
            $message = ("Ошибка активации");
            $enter = 0;
        }

        $this->setData('message', $message);
        $this->setData('enter', $enter);
        $this->display('frontend/registration/activation');
    }

    function reactivate() {
        $users_datas = array(
            'email'   => ''
        );

        $errors = array();

        if (isset($_POST['submit'])) {
            $email = $this->input->post('email');

            $users_datas = array(
                'email'   => $email
            );

            $config = array(
                array(
                    'field'   => 'email',
                    'label'   => 'email',
                    'rules'   => 'trim|required|valid_email|max_length[50]'
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

            if (!$this->Users_model->getEmail($email)) {
                $errors[] = 'Такой e-mail не зарегистрирован.';
            }

            if ($errors == false) {
                $activation_code = sha1(mt_rand(10000, 99999) . time() . $email);
                $this->Users_model->create_reset_pass ($email, $activation_code);

                $from = 'admin@codeigniterwebstore.webaddiction.ru';

                $this->email->from($from,'http://codeigniterwebstore.webaddiction.ru');
                $this->email->to($email);
                $this->email->subject('Активация');
                $this->email->message('Пожалуйста, подтвердите вашу учетную запись, пройдя по ссылке:   ' . 'http://codeigniterwebstore.webaddiction.ru/registration/activate/' . $activation_code);
                $this->email->send();

                $errors[] = 'На Ваш почтовый ящик было отправлено письмо с данными для активации.';
            }

        }

        $this->setData('title', 'Регистрация');
        $this->setData('users_data', $users_datas);
        $this->setData('errors', $errors);
        $this->display('frontend/registration/reactivate');
    }
}