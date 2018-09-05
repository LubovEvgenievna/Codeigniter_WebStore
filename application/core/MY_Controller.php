<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
    protected $data = array();

    public function __construct() {
        parent::__construct();

        $is_logged_in = $this->session->userdata('is_logged_in');
        $username = $this->session->userdata('username');
        $id_user = $this->session->userdata('id');

        $this->setData('is_logged_in', $is_logged_in);
        $this->setData('username', $username);
        $this->setData('id_user', $id_user);

        $total = $this->cart->total();
        $total_items = $this->cart->total_items();

        $this->setData('total',$total);
        $this->setData('total_items',$total_items);

        $this->load->model('Product_model');
        $prod = $this->Product_model->allProduct();
        $this->setData('product',$prod);

        $this->load->model('Slider_model');
        $sliders = $this->Slider_model->getAll();
        $this->setData('sliders',$sliders);

        $this->load->model('Pages_model');
        $pages = $this->Pages_model->getAll();
        $this->setData('pages',$pages);

        $this->load->library('Templater', array('views_folder_path' => VIEWS_FOLDER_PATH));
        $this->setData('base_url', base_url());
    }

    // метод, который кладет данные в массив, который передается в шаблон
    protected function setData($key, $value){
        $this->data[$key] = $value;
    }

    // метод для показа отрендеринового шаблона
    protected function display($template){
        echo $this->templater->render($template, $this->data);
    }

    // метод который генерит шаблон нативного CI и возвращает разметку
    protected function renderHTML($template, $data = array(), $return = false){
        return $this->load->view($template, $data, $return);
    }

    protected function checkAdmin() {
        $this->load->model('Users_model');
        $user = $this->Users_model->get(array('id'=>$this->session->userdata('id')));

        if ($user['admin']) {
            return true;
        } else {
            return false;
        }
    }
}