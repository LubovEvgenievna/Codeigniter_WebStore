<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Passrec extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Users_model');
        $this->load->library('email');
    }

    public function passrec() {

        $errors = array();

        if (isset($_POST['submit'])) {
            $email = $this->input->post('email');

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

                $code = sha1(mt_rand(10000, 99999) . time() . $email);

                $this->Users_model->create_reset_pass($email,$code);

                $this->email->from('admin@admin.ru','http://codeigniterwebstore.webaddiction.ru');
                $this->email->to($email);
                $this->email->subject('Восстановление пароля');
                $this->email->message('Пожалуйста, пройдите по ссылке, чтобы восстановить пароль:   ' . 'http://codeigniterwebstore.webaddiction.ru/passrec/reset_pass/' . $code);
                $this->email->send();

                $errors[] = 'На Ваш почтовый ящик было отправлено письмо с данными для восстановления пароля.';
            }
        }

        $this->setData('errors', $errors);
        $this->setData('title', 'Вход');
        $this->display('frontend/passrec/index');
    }

    public function reset_pass($code) {
        $errors = array();
        $enter = 0;

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
                $new_password=sha1($password);
                $this->Users_model->fogot_pass($code, $new_password);

                $errors[] = 'Пароль восстановлен.';
                $enter = 1;
            }

        }

        $this->setData('errors', $errors);
        $this->setData('enter', $enter);
        $this->setData('title', 'Вход');
        $this->display('frontend/passrec/reset_pass');
    }
}